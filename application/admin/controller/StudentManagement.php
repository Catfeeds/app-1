<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);


use app\admin\Controller;
use think\Exception;
use think\Loader;
use think\Db;
use think\Config;
use think\Session;

class StudentManagement extends Controller
{
    use \app\admin\traits\controller\Controller;
    // 方法黑名单
    protected static $blacklist = [];

    protected function filter(&$map)
    {
        if ($this->request->param("account")) {
            $map['account'] = ["like", "%" . $this->request->param("account") . "%"];
        }
        if ($this->request->param("name")) {
            $map['name'] = ["like", "%" . $this->request->param("name") . "%"];
        }
        if ($this->request->param("parentName")) {
            $map['parentName'] = ["like", "%" . $this->request->param("parentName") . "%"];
        }
        if ($this->request->param("contact")) {
            $map['contact'] = ["like", "%" . $this->request->param("contact") . "%"];
        }
		$info = Db::name("AdminUser")->field('type,realname,account')->where('isdelete','0')->where("id", UID)->find();
		if($info['type'] ==1)
		{
			//$school = Db::name("SchoolManagement");
			//$data = $school->field('schoolName')->where('schoolName',$info1['realname'])->find();
			$map['schoolName']=["like", "%" . $info['realname']. "%"];
		}
		else if($info['type'] ==2)
		{
			$data = Db::name("EmployeeManagement")->field('schoolName,className')->where('isdelete','0')->where('account',$info['account'])->find();
			$map['schoolName']=["like", "%" . $data['schoolName']. "%"];
			$map['className']=["like", "%" . $data['className']. "%"];
		}
		else if($info['type'] ==3)
		{

			//$data = Db::name("StudentManagement")->field('schoolName')->where('account',$info['account'])->find();
			//$map['schoolName']=["like", "%" . $data['schoolName']. "%"];
			$map['account']=["like", "%" . $info['account']. "%"];
		}
    }
	/**
     * 首页
     * @return mixed
     */
    public function index()
    {
        $model = $this->getModel();
		
		// 列表过滤器，生成查询Map对象
		$map = $this->search($model, [$this->fieldIsDelete => $this::$isdelete]);
        // 特殊过滤器，后缀是方法名的
        $actionFilter = 'filter' . $this->request->action();
        if (method_exists($this, $actionFilter)) {
            $this->$actionFilter($map);
        }

        // 自定义过滤器
        if (method_exists($this, 'filter')) {
            $this->filter($map);
        }
		$model = $this->getModel();
        $this->datalist($model, $map);
		$info = Db::name("AdminUser")->field('type,realname,account')->where('isdelete','0')->where("id", UID)->find();
		if($info['type'] ==0)
		{
			$model = Db::name("StudentManagement");
			$schoolName = $model->field('schoolName')->Distinct(true)->where('isdelete','0')->select();
			$className = $model->field('className')->Distinct(true)->where('isdelete','0')->select();
			$this->view->assign("schoolName", $schoolName);
			$this->view->assign("className", $className);
		}
		else if($info['type'] ==1)
		{
			$model = Db::name("StudentManagement");
			$schoolName = $model->field('schoolName')->Distinct(true)->where('isdelete','0')->where('schoolName',$info['realname'])->select();
			$className = $model->field('className')->Distinct(true)->where('isdelete','0')->where('schoolName',$info['realname'])->select();
			$this->view->assign("schoolName", $schoolName);
			$this->view->assign("className", $className);
		}
		else if($info['type'] ==2)
		{
			$model = Db::name("StudentManagement");
			$data = Db::name("EmployeeManagement")->field('schoolName,className')->where('isdelete','0')->where('account',$info['account'])->find();
			$schoolName = $model->field('schoolName')->Distinct(true)->where('isdelete','0')->where('schoolName',$data['schoolName'])
			->where('className',$data['className'])->select();
			$className = $model->field('className')->Distinct(true)->where('isdelete','0')->where('schoolName',$data['schoolName'])
			->where('className',$data['className'])->select();
			$this->view->assign("schoolName", $schoolName);
			$this->view->assign("className", $className);
		}
		else if($info['type'] ==3)
		{
			$model = Db::name("StudentManagement");
			$schoolName = $model->field('schoolName')->Distinct(true)->where('isdelete','0')->where('account',$info['account'])->select();
			$className = $model->field('className')->Distinct(true)->where('isdelete','0')->where('account',$info['account'])->select();
			$this->view->assign("schoolName", $schoolName);
			$this->view->assign("className", $className);
		}
		
        return $this->view->fetch();
    }
	
	/**
	* 创建编号
	* @param Int $id 自增id
	* @param Int $num_length 数字最大位数
	* @param String $prefix 前缀
	* @return String
	*/
	public static function create($id, $num_length, $prefix){

		// 基数
		$base = pow(10, $num_length);

		// 生成字母部分
		$division = (int)($id/$base);
		$word = '';

		while($division){
		$tmp = fmod($division, 26); // 只使用26个大写字母
		$tmp = chr($tmp + 65); // 转为字母
		$word .= $tmp;
		$division = floor($division/26);
		}

		if($word==''){
		$word = chr(65);
		}

		// 生成数字部分
		$mod = $id % $base;
		$digital = str_pad($mod, $num_length, 0, STR_PAD_LEFT);

		$code = sprintf('%s%s%s', $prefix, $word, $digital);
		return $code;

	}
	
	
	/**
     * 添加
     * @return mixed
     */
    public function add()
    {
        $controller = $this->request->controller();

        if ($this->request->isAjax()) {
            // 插入
            $data = $this->request->except(['id']);

            // 验证
            if (class_exists($validateClass = Loader::parseClass(Config::get('app.validate_path'), 'validate', $controller))) {
                $validate = new $validateClass();
                if (!$validate->check($data)) {
                    return ajax_return_adv_error($validate->getError());
                }
            }
			
            // 写入数据
            if (
                class_exists($modelClass = Loader::parseClass(Config::get('app.model_path'), 'model', $this->parseCamelCase($controller)))
                || class_exists($modelClass = Loader::parseClass(Config::get('app.model_path'), 'model', $controller))
            ) {
                
                //使用模型写入，可以在模型中定义更高级的操作
                $model = new $modelClass();
				$data5 = ['schoolName' => $data['schoolName'], 'className' => $data['className'],'account' => $data['account'], 'passWord' => password_hash_tp($data['passWord'])
				,'name' => $data['name'], 'sex' => $data['sex'],'birthDate' => $data['birthDate'],'height'=>$data['height'],'weight'=>$data['weight'],'contact' => $data['contact'],'isUpload'=>'0','isDownloadP'=>'0','isDownloadD'=>'0'
				];
                $ret = $model->isUpdate(false)->save($data5);
                $EId = $model->getLastInsID();
				$user = Db::name("AdminUser");
				$data1 = ['account' => $data['account'], 'realname' => $data['name'], 'password' => password_hash_tp($data['passWord']), 'type' => 3,'mobile' => $data['contact'],'status' => 1];
				$ret = $user->isUpdate(false)->save($data1);
				$Id = $user->getLastInsID();
				$db_role_user = Db::name("AdminRoleUser");
				$data2 = ['role_id' => 3, 'user_id' => $Id];
				$ret = $db_role_user->isUpdate(false)->save($data2);
				
				if (array_key_exists("msg",$data)) {
					$data3 = $data['msg'];
					$linkmanDB = Db::name("StudentLinkman");
					$linkmanDB->where('student_id',$EId)->delete();
					$LID = $linkmanDB->max('id')+1;
					foreach($data3 as $key=>$val1){
						$LID = $linkmanDB->max('id')+1;
						$data4 = ['id'=>$LID,'student_id'=>$EId,'name'=>$val1['name'],'relation'=>$val1['relation'],'number'=>$val1['number'],'isUpload'=>'0','isDownloadP'=>'0','isDownloadD'=>'0'];
						//$linkmanDB->isUpdate(false)->save($data4);
						if($val1['relation'] =='学生本人'){
								$re = $linkmanDB->where('student_id', $EId)->where('status',1)->where('isdelete',0)->where('relation','学生本人')->find();
								
						        if(empty($re)&&$data['name']==$data4['name']){
						         	$linkmanDB->isUpdate(false)->save($data4);
						        }
							}else{
								$linkmanDB->isUpdate(false)->save($data4);
							}
					}
				}
				
				
            } else {
                // 简单的直接使用db写入
                Db::startTrans();
                try {
					
                    $model = Db::name($this->parseTable($controller));

					$data5 = ['schoolName' => $data['schoolName'], 'className' => $data['className'],'account' => $data['account'], 'passWord' => password_hash_tp($data['passWord'])
				,'name' => $data['name'], 'sex' => $data['sex'],'birthDate' => $data['birthDate'],'height'=>$data['height'],'weight'=>$data['weight'],'contact' => $data['contact'],'isUpload'=>0,'isDownloadP'=>0,'isDownloadD'=>0
				];
				    
                    $ret = $model->insert($data5);
                    $EId = $model->getLastInsID();
					$user = Db::name("AdminUser");
					$data1 = ['account' => $data['account'], 'realname' => $data['name'], 'password' => password_hash_tp($data['passWord']), 'type' => 3,'mobile' => $data['contact'],'status' => 1];
					$ret = $user->insert($data1);
					$Id = $user->getLastInsID();
					$db_role_user = Db::name("AdminRoleUser");
					$data2 = ['role_id' => 3, 'user_id' => $Id];
					$ret = $db_role_user->insert($data2);
					
					if (array_key_exists("msg",$data)) {
						$data3 = $data['msg'];
						$linkmanDB = Db::name("StudentLinkman");
						$linkmanDB->where('student_id',$EId)->delete();
						$LID = $linkmanDB->max('id')+1;
						foreach($data3 as $key=>$val1){
							$LID = $linkmanDB->max('id')+1;
							$data4 = ['id'=>$LID,'student_id'=>$EId,'name'=>$val1['name'],'relation'=>$val1['relation'],'number'=>$val1['number'],'isUpload'=>'0','isDownloadP'=>'0','isDownloadD'=>'0'];
							//$linkmanDB->insert($data4);
							if($val1['relation'] =='学生本人'){
								$re = $linkmanDB->where('student_id', $EId)->where('status',1)->where('isdelete',0)->where('relation','学生本人')->find();
						        if(empty($re)&&$data['name']==$data4['name']){
						         	$linkmanDB->insert($data4);
						        }
							}else{
								$linkmanDB->insert($data4);
							}
						}
					}
					
                    // 提交事务
                    Db::commit();
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();

                    return ajax_return_adv_error($e->getMessage());
                }
            }

            return ajax_return_adv('添加成功');
        } else {
			/*
			$model = Db::name($this->parseTable($controller));
			$id = $model->max('id')+1;
			$ID = $this->create($id, 1, '');
			$this->view->assign("ID", $ID);
			*/
			
			$info = Db::name("AdminUser")->field('type,realname,account')->where('isdelete','0')->where("id", UID)->find();
			if($info['type'] ==1)
			{
				$school = Db::name("SchoolManagement");
				$data_s = $school->field('schoolName,schoolID')->where('isdelete','0')->where('schoolName',$info['realname'])->select();
				//$division = Db::name("DivisionManagement");
				//$data_d = $division->field('divisionName')->where('schoolName',$info['realname'])->select();
				$division = Db::name("ClassManagement");
				$data_c = $division->field('class,className')->where('isdelete','0')->where('schoolName',$info['realname'])->select();
				
			}
			else if($info['type'] ==2)
			{
				$info1 = Db::name("AdminUser")->field('realname')->where('isdelete','0')->where("id", UID)->find();
				$data_s = Db::name("EmployeeManagement")->field('schoolName')->where('isdelete','0')->where('name',$info1['realname'])->select();
				//$division = Db::name("DivisionManagement");
				//$data_d = $division->field('divisionName')->where('schoolName',$info1['realname'])->select();
				$division = Db::name("ClassManagement");
				$data_c = $division->field('class,className')->where('isdelete','0')->where('classTeacher',$info1['realname'])->select();
			}
			else if($info['type'] ==3)
			{
				$info1 = Db::name("AdminUser")->field('realname')->where('isdelete','0')->where("id", UID)->find();
				$data_s = Db::name("StudentManagement")->field('schoolName')->where('isdelete','0')>where('name',$info1['realname'])->select();
				//$division = Db::name("DivisionManagement");
				//$data_d = $division->field('divisionName')->where('isdelete','0')->where('schoolName',$info1['realname'])->select();
				$division = Db::name("ClassManagement");
				$data_c = $division->field('class,className')->where('isdelete','0')->where('classTeacher',$info1['realname'])->select();
			}
			else if($info['type'] ==0)
			{
				$school = Db::name("SchoolManagement");
				$data_s = $school->field('schoolName,schoolID')->where('isdelete','0')->select();
				//$division = Db::name("DivisionManagement");
				//$data_d = $division->field('divisionName')->where('isdelete','0')->select();
				$division = Db::name("ClassManagement");
				$data_c = $division->field('class,className')->where('isdelete','0')->select();
			}
			$this->view->assign("data_s", $data_s);
			//$this->view->assign("data_d", $data_d);
			$this->view->assign("data_c", $data_c);
			$linkman = [];
			$count = count($linkman);
			$this->view->assign("linkman", $linkman);
			$this->view->assign("count", $count);
            // 添加
            return $this->view->fetch(isset($this->template) ? $this->template : 'edit');
        }
    }
	
	/**
     * 编辑
     * @return mixed
     */
    public function edit()
    {
        $controller = $this->request->controller();
		
        if ($this->request->isAjax()) {
			
			
            // 更新
            $data = $this->request->post();
			/*
			$str = "";
			foreach($data as $key=>$val){
				if($key!='msg')
					$str = $str.$val;
				else
				foreach($val as $key=>$val1){
					foreach($val1 as $key=>$val2){
						$str = $str.$val2;
					}
				}
					
			}
			return ajax_return_adv_error($str);
			*/
            if (!$data['id']) {
                return ajax_return_adv_error("缺少参数ID");
            }

            // 验证
            if (class_exists($validateClass = Loader::parseClass(Config::get('app.validate_path'), 'validate', $controller))) {
                $validate = new $validateClass();
                if (!$validate->check($data)) {
                    return ajax_return_adv_error($validate->getError());
                }
            }

            // 更新数据
            if (
                class_exists($modelClass = Loader::parseClass(Config::get('app.model_path'), 'model', $this->parseCamelCase($controller)))
                || class_exists($modelClass = Loader::parseClass(Config::get('app.model_path'), 'model', $controller))
            ) {
                // 使用模型更新，可以在模型中定义更高级的操作
                $model = new $modelClass();
				$data2 = ['schoolName' => $data['schoolName'], 'className' => $data['className'],'account' => $data['account']
				,'name' => $data['name'], 'sex' => $data['sex'],'birthDate' => $data['birthDate'],'height'=>$data['height'],'weight'=>$data['weight'],'contact' => $data['contact'],'isUpload'=>'0','isDownloadP'=>'0','isDownloadD'=>'0'
				];
                $ret = $model->isUpdate(true)->save($data2, ['id' => $data['id']]);
				$user = Db::name("AdminUser");
				$id = $user->where('account', $data['schoolAccount'])->value('id');
				$data1 = ['account' => $data['account'], 'realname' => $data['schoolName'],'mobile' => $data['contact']];
				$user->isUpdate(false)->save($data1,['id' => $data['id']]);
				if (array_key_exists("msg",$data)) {
					$data3 = $data['msg'];
					$linkmanDB = Db::name("StudentLinkman");
					//$linkmanDB->where('student_id',$data['id'])->delete();
					//$LID = $linkmanDB->max('id')+1;
					foreach($data3 as $key=>$val1){
						$count = $linkmanDB->where('id',$val1['id'])->count();
					    if($count>0)
					    {  
					        $data5 = ['name'=>$val1['name'],'relation'=>$val1['relation'],'number'=>$val1['number']];
							$info = $linkmanDB->where('id', $val1['id'])->where('status',1)->where('isdelete',0)->find();
							if($val1['relation']=='学生本人'){
    								$re = $linkmanDB->where('student_id', $data['id'])->where('status',1)->where('isdelete',0)->where('relation','学生本人')->find();
                                    if(empty($re)){
										if($data['name']==$data5['name']){
											if($val1['relation']!=$info['relation']||$val1['name']!=$info['name']){
									            $linkmanDB->where('id', $val1['id'])->update(['ismodifyData'=>1]); 

								            }
							         	    $linkmanDB->where('id', $val1['id'])->update($data5); 
											
										}
                                   // var_dump('1111');	
							        }else{
										if($data['name']==$data5['name']){
											$data6 = ['name'=>$val1['name'],'number'=>$val1['number']];
										    if($val1['name']!=$info['name']){
									        $linkmanDB->where('id', $val1['id'])->update(['ismodifyData'=>1]); 

								            }
										   $linkmanDB->where('id', $val1['id'])->update($data6);
											
										}
										 
									}


    							}else{
    								if($val1['relation']!=$info['relation']||$val1['name']!=$info['name']){
									    $linkmanDB->where('id', $val1['id'])->update(['ismodifyData'=>1]); 
								    }

    								$linkmanDB->where('id', $val1['id'])->update($data5); 
    							}
						    
					       
					    }
					    else
					    {
					        $LID = $linkmanDB->max('id')+1;
							$data4 = ['id'=>$LID,'student_id'=>$data['id'],'name'=>$val1['name'],'relation'=>$val1['relation'],'number'=>$val1['number'],'isUpload'=>'0','isDownloadP'=>'0','isDownloadD'=>'0'];
							if($val1['relation']=='学生本人'){
    								$re = $linkmanDB->where('student_id', $data['id'])->where('status',1)->where('isdelete',0)->where('relation','学生本人')->find();
							        if(empty($re)){
										if($data['name']==$data4['name']){
											$linkmanDB->insert($data4);
										}
							         	
							        }
    							}else{
    								$linkmanDB->insert($data4);
    							}
    							
					    }
					}
				}
				
            } else {
                // 简单的直接使用db更新
                Db::startTrans();
                try {
                    $model = Db::name($this->parseTable($controller));
					$data2 = ['schoolName' => $data['schoolName'], 'className' => $data['className'],'account' => $data['account']
				,'name' => $data['name'], 'sex' => $data['sex'],'birthDate' => $data['birthDate'],'height'=>$data['height'],'weight'=>$data['weight'],'contact' => $data['contact'],'isUpload'=>'0','isDownloadP'=>'0','isDownloadD'=>'0'
				];
                    $relation = $this->request->param('msg[$linkman1.id][relation]');
					$ret = $model->where('id',$data['id'])->update($data2);
					$user = Db::name("AdminUser");
					$id = $user->where('account', $data['account'])->value('id');
					$data1 = ['account' => $data['account'], 'realname' => $data['name'],'mobile' => $data['contact']];
					$user->where('id',$id)->update($data1);
					if(array_key_exists("msg",$data)) {
						$data3 = $data['msg'];
						$linkmanDB = Db::name("StudentLinkman");
						//$linkmanDB->where('student_id',$data['id'])->delete();
						//$LID = $linkmanDB->max('id')+1;
						 
						foreach($data3 as $key=>$val1){
						    $count = $linkmanDB->where('id',$val1['id'])->count();
							
    					    if($count>0)
    					    {
    					        $data5 = ['name'=>$val1['name'],'relation'=>$val1['relation'],'number'=>$val1['number']];
								$info = $linkmanDB->where('id', $val1['id'])->where('status',1)->where('isdelete',0)->find();
								if($val1['relation']=='学生本人'){
    								$re = $linkmanDB->where('student_id', $data['id'])->where('status',1)->where('isdelete',0)->where('relation','学生本人')->find();
                                    if(empty($re)){
										if($data['name']==$data5['name']){
											if($val1['relation']!=$info['relation']||$val1['name']!=$info['name']){
									            $linkmanDB->where('id', $val1['id'])->update(['ismodifyData'=>1]); 

								            }
							         	    $linkmanDB->where('id', $val1['id'])->update($data5); 
											
										}
                                    	
							        }else{
										if($data['name']==$data5['name']){
											$data6 = ['name'=>$val1['name'],'number'=>$val1['number']];
											if($val1['name']!=$info['name']){
												$linkmanDB->where('id', $val1['id'])->update(['ismodifyData'=>1]); 

											}
											$linkmanDB->where('id', $val1['id'])->update($data6); 
										}
									}


    							}else{
    								if($val1['relation']!=$info['relation']||$val1['name']!=$info['name']){
									    $linkmanDB->where('id', $val1['id'])->update(['ismodifyData'=>1]); 
								    }

    								$linkmanDB->where('id', $val1['id'])->update($data5); 
    							}
						        
    					    }
    					    else
    					    {
    					        $LID = $linkmanDB->max('id')+1;
    							$data4 = ['id'=>$LID,'student_id'=>$data['id'],'name'=>$val1['name'],'relation'=>$val1['relation'],'number'=>$val1['number'],'isUpload'=>'0','isDownloadP'=>'0','isDownloadD'=>'0'];
    							//$linkmanDB->insert($data4);
								if($val1['relation']=='学生本人'){
    								$re = $linkmanDB->where('student_id', $data['id'])->where('status',1)->where('isdelete',0)->where('relation','学生本人')->find();
							        if(empty($re)){
										if($data['name']==$data4['name']){
											$linkmanDB->insert($data4);
										}
							         	
							        }
    							}else{
    								$linkmanDB->insert($data4);
    							}
    							
    					    }
							
						}
					}
                    // 提交事务
                    Db::commit();
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();

                    return ajax_return_adv_error($e->getMessage());
                }
            }

            return ajax_return_adv("编辑成功");
        } else {
            // 编辑
            $id = $this->request->param('id');

            if (!$id) {
                throw new HttpException(404, "缺少参数ID");
            }
            $vo = $this->getModel($controller)->find($id);
            if (!$vo) {
                throw new HttpException(404, '该记录不存在');
            }
			$this->view->assign("vo", $vo);
			/*
			$model = Db::name($this->parseTable($controller));
			$id = $model->max('id')+1;
			$ID = $this->create($id, 1, '');
			$this->view->assign("ID", $ID);
			*/
			
			$info = Db::name("AdminUser")->field('type,realname,account')->where('isdelete','0')->where("id", UID)->find();
			if($info['type'] ==1)
			{
				$school = Db::name("SchoolManagement");
				$data_s = $school->field('schoolName,schoolID')->where('isdelete','0')->where('schoolName',$info['realname'])->select();
				//$division = Db::name("DivisionManagement");
				//$data_d = $division->field('divisionName')->where('isdelete','0')->where('schoolName',$info['realname'])->select();
				$division = Db::name("ClassManagement");
				$data_c = $division->field('class,className')->where('isdelete','0')->where('schoolName',$info['realname'])->select();
			}
			else if($info['type'] ==2)
			{
				$info1 = Db::name("AdminUser")->field('realname')->where('isdelete','0')->where("id", UID)->find();
				$data_s = Db::name("EmployeeManagement")->field('schoolName')->where('isdelete','0')->where('name',$info1['realname'])->select();
				//$division = Db::name("DivisionManagement");
				//$data_d = $division->field('divisionName')->where('isdelete','0')->where('schoolName',$info1['realname'])->select();
				$division = Db::name("ClassManagement");
				$data_c = $division->field('class,className')->where('isdelete','0')->where('classTeacher',$info1['realname'])->select();
			}
			else if($info['type'] ==3)
			{
				$info1 = Db::name("AdminUser")->field('realname')->where('isdelete','0')->where("id", UID)->find();
				$data_s = Db::name("StudentManagement")->field('schoolName')->where('isdelete','0')->where('name',$info1['realname'])->select();
				//$division = Db::name("DivisionManagement");
				//$data_d = $division->field('divisionName')->where('isdelete','0')->where('schoolName',$info1['realname'])->select();
				$division = Db::name("ClassManagement");
				$data_c = $division->field('class,className')->where('isdelete','0')->where('classTeacher',$info1['realname'])->select();
			}
			else if($info['type'] ==0)
			{
				$school = Db::name("SchoolManagement");
				$data_s = $school->field('schoolName,schoolID')->where('isdelete','0')->select();
				//$division = Db::name("DivisionManagement");
				//$data_d = $division->field('divisionName')->where('isdelete','0')->select();
				$division = Db::name("ClassManagement");
				$data_c = $division->field('class,className')->where('isdelete','0')->select();
			}
			$this->view->assign("data_s", $data_s);
			//$this->view->assign("data_d", $data_d);
			$this->view->assign("data_c", $data_c);
			
			$linkman = Db::name("StudentLinkman")->where("student_id", $id)->order('id','asc')->select();
			$this->view->assign("linkman", $linkman);
			$maxid = Db::name("EmployeeLinkman")->max('id');
			$this->view->assign("maxid", $maxid);
			$count = count($linkman);
			$this->view->assign("count", $count);
            return $this->view->fetch();
        }
    }
	
	/**
     * 默认删除操作
     */
    public function delete()
    {
		
		$model = $this->getModel();
        $pk = $model->getPk();
		$ids = $this->request->param($pk);
		$where[$pk] = ["in", $ids];
		$arr = explode(",",$where[$pk][1]);
		$num = count($arr);
		if($num>0)
		{
			for($i = 0; $i<$num; $i++){
				$data = Db::query("select b.id from tp_student_management a,tp_admin_user b where a.account = b.account and a.id=?",[$arr[$i]]);
				//return ajax_return_adv_error($arr);
				foreach($data as $key=>$val){
					
					$data1 = ['isdelete' => 1];
					if (false === Db::name("AdminUser")->where('id',$val['id'])->update($data1)) {
						return ajax_return_adv_error($model->getError());
					}
				}
			}
		}
        return $this->updateField($this->fieldIsDelete, 1, "移动到回收站成功");
		
    }

    /**
     * 从回收站恢复
     */
    public function recycle()
    {
		$model = $this->getModel();
        $pk = $model->getPk();
		$ids = $this->request->param($pk);
		$where[$pk] = ["in", $ids];
		$arr = explode(",",$where[$pk][1]);
		$num = count($arr);
		if($num>0)
		{
			for($i = 0; $i<$num; $i++){
				$data = Db::query("select b.id from tp_student_management a,tp_admin_user b where a.account = b.account and a.id=?",[$arr[$i]]);
				//return ajax_return_adv_error($arr);
				foreach($data as $key=>$val){
					
					$data1 = ['isdelete' => 0];
					if (false === Db::name("AdminUser")->where('id',$val['id'])->update($data1)) {
						return ajax_return_adv_error($model->getError());
					}
				}
			}
		}
        return $this->updateField($this->fieldIsDelete, 0, "恢复成功");
    }

    /**
     * 默认禁用操作
     */
    public function forbid()
    {
		$model = $this->getModel();
        $pk = $model->getPk();
		$ids = $this->request->param($pk);
		$where[$pk] = ["in", $ids];
		$arr = explode(",",$where[$pk][1]);
		$num = count($arr);
		if($num>0)
		{
			for($i = 0; $i<$num; $i++){
				$data = Db::query("select b.id from tp_student_management a,tp_admin_user b where a.account = b.account and a.id=?",[$arr[$i]]);
				//return ajax_return_adv_error($arr);
				foreach($data as $key=>$val){
					//return ajax_return_adv_error($val['id']);
					$data1 = ['status' => 0];
					if (false === Db::name("AdminUser")->where('id',$val['id'])->update($data1)) {
						return ajax_return_adv_error($model->getError());
					}
				}
			}
		}
        return $this->updateField($this->fieldStatus, 0, "禁用成功");
    }


    /**
     * 默认恢复操作
     */
    public function resume()
    {
		$model = $this->getModel();
        $pk = $model->getPk();
		$ids = $this->request->param($pk);

		$where[$pk] = ["in", $ids];
		$arr = explode(",",$where[$pk][1]);
		$num = count($arr);
		if($num>0)
		{
			for($i = 0; $i<$num; $i++){
				$data = Db::query("select b.id from tp_student_management a,tp_admin_user b where a.account = b.account and a.id=?",[$arr[$i]]);
				//return ajax_return_adv_error($arr);
				foreach($data as $key=>$val){
					$data1 = ['status' => 1];
					if (false === Db::name("AdminUser")->where('id',$val['id'])->update($data1)) {
						return ajax_return_adv_error($model->getError());
					}
				}
			}
		}
        return $this->updateField($this->fieldStatus, 1, "恢复成功");
    }
	
	/**
     * 永久删除
     */
    public function deleteForever()
    {
        $model = $this->getModel();
        $pk = $model->getPk();
        $ids = $this->request->param($pk);
		
		//return ajax_return_adv_error($ids);
        
		$where[$pk] = ["in", $ids];
		$arr = explode(",",$where[$pk][1]);
		$num = count($arr);
		if($num>0)
		{
			for($i = 0; $i<$num; $i++){
				$data = Db::query("select b.id from tp_student_management a,tp_admin_user b where a.account = b.account and a.id=?",[$arr[$i]]);
				//return ajax_return_adv_error($arr);
				foreach($data as $key=>$val){
					if (false === Db::name("AdminRoleUser")->where('user_id',$val['id'])->delete()) {
						return ajax_return_adv_error($model->getError());
					}
					if (false === Db::name("AdminUser")->delete($val)) {
						return ajax_return_adv_error($model->getError());
					}
					
				}
				
			}
		}
		if (false === $model->where($where)->delete()) {
            return ajax_return_adv_error($model->getError());
        }
		
        return ajax_return_adv("删除成功");
    }

    /**
     * 清空回收站
     */
    public function clear()
    {
        
		$model = $this->getModel();
		$where[$this->fieldIsDelete] = 1;
		
		
		$data = Db::query("select b.id from tp_student_management a,tp_admin_user b where a.account = b.account and a.isdelete = 1");
		foreach($data as $key=>$val){
			if (false === Db::name("AdminRoleUser")->where('user_id',$val['id'])->delete()) {
				return ajax_return_adv_error($model->getError());
			}
			//return ajax_return_adv_error($val['id']);
			if (false === Db::name("AdminUser")->where('id',$val['id'])->delete()) {
				return ajax_return_adv_error($model->getError());
			}
			
		}
		if (false === $model->where($where)->delete()) {
			return ajax_return_adv_error($model->getError());
		}
        return ajax_return_adv("清空回收站成功");
    }
	
 //导入Excel
    public function upload()  
    {  
    	vendor("PHPExcel.PHPExcel"); //
        
        $file = request()->file('file');  
   
        $info = $file->validate(['ext' => 'xlsx,xls'])->move(ROOT_PATH . 'public' . DS . 'uploads');  
        if ($info) {  
             
            $exclePath = $info->getSaveName();  //获取文件名  
            $extension  = $info->getExtension();  
         
            $file_name = ROOT_PATH . 'public' . DS . 'uploads' . DS . $exclePath;   //上传文件的地址  
            
            if($extension == 'xlsx') {
                $objReader =\PHPExcel_IOFactory::createReader('Excel2007');
              
            }else if($extension == 'xls'){
                $objReader =\PHPExcel_IOFactory::createReader('Excel5');
             
            }


            $obj_PHPExcel = $objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8  
            
            $excel_array = $obj_PHPExcel->getsheet(0)->toArray();   //转换为数组格式  
            
            array_shift($excel_array);  //删除第一个数组(标题);  
            
            $data = [];  
            foreach ($excel_array as $k => $v) { 
            	if(!empty($v[0])&&!empty($v[1])&&!empty($v[2])){

            		$info = Db::name('SchoolManagement')->where('schoolID',$v[0])->where('status',1)->where('isdelete',0)->find();  
	                $data['schoolName'] = $info['schoolName'] ;  
	                $data['className'] = $v[1]; 
	                $data['name'] = $v[2]; 
	                $data['sex'] = $v[3]; 
	                $data['birthDate'] = $v[4]; 
	                $data['contact'] = $v[5]; 
	                $data['account'] = $v[0].$this->sum(); 
					$data['passWord'] = md5('123456'); 
	                $re = Db::name('StudentManagement')->where('account',$data['account'])->where('status',1)->where('isdelete',0)->find(); 
	                if($re){
	                	$data['account'] = $v[0].$this->sum(); 
	                }
	                $add = Db::name('StudentManagement')->insertgetid($data); //批量插入数据  
	                if($add){
	                    $data1['name'] = $v[6]; 
		                $data1['relation'] = $v[7]; 
		                $data1['number'] = $v[8]; 
		                $data1['student_id'] = $add; 
		                Db::name('StudentLinkman')->insertgetid($data1); //批量插入数据  
	                }
            	}
                
            }  
          //var_dump($excel_array);die;  
           
            if($add){  
              // $this->success('导入成功');  
                return ajax_return_adv("导入成功");
            }else{  
                return ajax_return_adv_error('失败');
            }  
        } else {  
            echo $file->getError();  
        }  
    }  
	
	


    public function sum(){
        $num='';
        for($i=0;$i<5;$i++){
            $num.= rand(0,9);
        }
        return $num;
    }
	
	   //删除联系人
    public function dellinkman(){
    	$id = $this->request->param('id');
    	if($id){
    		$re = Db::name('StudentLinkman')->where('id',$id)->delete(); 
			$re1 = Db::name('StudentLinkmanAccessManagement')->where('studentLinkman_id',$id)->delete(); 
    		if($re&&$re1){
    			exit('1');
    		}else{
                exit('0');
    		}
    	}else{
    		exit('无参数');
    	}
       
    }
}
