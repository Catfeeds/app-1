<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
//use think\Controller;
use think\Exception;
use think\Loader;
use think\Db;
use think\Config;
use PHPExcel_IOFactory;
use PHPExcel;


class AllSchool extends Controller
{
    use \app\admin\traits\controller\Controller;
    // 方法黑名单
    protected static $blacklist = [];

    protected function filter(&$map)
    {
        if ($this->request->param("schoolName")) {
            $map['schoolName'] = ["like", "%" . $this->request->param("schoolName") . "%"];
        }
        if ($this->request->param("schoolID")) {
            $map['schoolID'] = ["like", "%" . $this->request->param("schoolID") . "%"];
        }
        if ($this->request->param("schoolAccount")) {
            $map['schoolAccount'] = ["like", "%" . $this->request->param("schoolAccount") . "%"];
        }
		$info = Db::name("AdminUser")->field('type,realname')->where("id", UID)->find();
		if($info['type'] ==1)
		{
			$map['schoolName']=["like", "%" . $info['realname']. "%"];
		}
		else if($info['type'] ==2)
		{
			$info1 = Db::name("AdminUser")->field('realname,account')->where("id", UID)->where('isdelete','0')->find();
			$data = Db::name("EmployeeManagement")->field('account')->where('account',$info1['account'])->where('isdelete','0')->find();
			$map['account']=["like", "%" . $data['account']. "%"];
		}
		else if($info['type'] ==3)
		{
			$info1 = Db::name("AdminUser")->field('account')->where("id", UID)->where('isdelete','0')->find();
			//$data = Db::name("StudentManagement")->field('schoolName')->where('account',$info1['account'])->find();
			//$map['schoolName']=["like", "%" . $data['schoolName']. "%"];
			$map['account']=["like", "%" . $info1['account']. "%"];
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
       // return ajax_return_adv_error($model);
        $this->datalist($model, $map);

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
                $ret = $model->isUpdate(false)->save($data);
			
		
            } else {
                // 简单的直接使用db写入
                Db::startTrans();
                try {
					
                    $model = Db::name($this->parseTable($controller));
                    $ret = $model->insert($data);
				
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
			$model = Db::name($this->parseTable($controller));
			$id = $model->max('id')+1;
			$ID = $this->create($id, 1, '');
			$this->view->assign("ID", $ID);
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
                $ret = $model->isUpdate(true)->save($data, ['id' => $data['id']]);
				
            } else {
                // 简单的直接使用db更新
                Db::startTrans();
                try {
                    $model = Db::name($this->parseTable($controller));
                    $ret = $model->where('id', $data['id'])->update($data);
					
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
			$model = Db::name($this->parseTable($controller));
			$id = $model->max('id')+1;
			$ID = $this->create($id, 1, '');
			$this->view->assign("ID", $ID);
            $this->view->assign("vo", $vo);

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
				$data = Db::query("select b.id from tp_school_management a,tp_admin_user b where a.schoolAccount = b.account and a.id=?",[$arr[$i]]);
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
				$data = Db::query("select b.id from tp_school_management a,tp_admin_user b where a.schoolAccount = b.account and a.id=?",[$arr[$i]]);
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
				$data = Db::query("select b.id from tp_school_management a,tp_admin_user b where a.schoolAccount = b.account and a.id=?",[$arr[$i]]);
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
				$data = Db::query("select b.id from tp_school_management a,tp_admin_user b where a.schoolAccount = b.account and a.id=?",[$arr[$i]]);
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
				$data = Db::query("select b.id from tp_school_management a,tp_admin_user b where a.schoolAccount = b.account and a.id=?",[$arr[$i]]);
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
		
		
		$data = Db::query("select b.id from tp_school_management a,tp_admin_user b where a.schoolAccount = b.account and a.isdelete = 1");
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
	//phpinfo();exit;
	 //   ini_set("memory_limit","30M");
    	vendor("PHPExcel.PHPExcel"); //方法一  
       // $objPHPExcel = new \PHPExcel();  
        //获取表单上传文件  
        $file = request()->file('file');  
       // $name = $this->request->param('name');   
    //  return ajax_return_adv_error($file);
        $info = $file->validate(['ext' => 'xlsx,xls'])->move(ROOT_PATH . 'public' . DS . 'uploads');  
        if ($info) {  
             //echo $info->getFilename();die;  
            $exclePath = $info->getSaveName();  //获取文件名  
            $extension  = $info->getExtension();  
          //  return ajax_return_adv_error($exclePath);
            $file_name = ROOT_PATH . 'public' . DS . 'uploads' . DS . $exclePath;   //上传文件的地址  
            
            if($extension == 'xlsx') {
                $objReader =\PHPExcel_IOFactory::createReader('Excel2007');
               // $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');
            }else if($extension == 'xls'){
                $objReader =\PHPExcel_IOFactory::createReader('Excel5');
               // $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');
            }


            $obj_PHPExcel = $objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8  
            
            $excel_array = $obj_PHPExcel->getsheet(0)->toArray();   //转换为数组格式  
            
            array_shift($excel_array);  //删除第一个数组(标题);  
            $data = [];  
            foreach ($excel_array as $k => $v) {  
                $data['province'] = $v[0];  
                $data['city'] = $v[1]; 
                $data['educationLevel'] = $v[2]; 
                $data['schoolNature'] = $v[3]; 
                $data['schoolName'] = $v[4]; 
                $data['agent'] = $v[5]; 
                $data['phone'] = $v[6]; 
                $data['website'] = $v[7]; 
                $re = Db::name('AllSchool')->where('schoolName',$data['schoolName'])->where('schoolNature',$data['schoolNature'])->where('status',1)->where('isdelete',0)->find(); 
                if(!$re){
                    $add = Db::name('AllSchool')->insert($data); //批量插入数据  
                }
            } 
            if($add){  
               //$this->success('导入成功');  
                return ajax_return_adv("导入成功");
            }else{  
                return ajax_return_adv_error('失败');
            }  
        } else {  
            echo $file->getError();  
        }  
    }  
	
}
