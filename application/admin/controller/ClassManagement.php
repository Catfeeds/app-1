<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);


use app\admin\Controller;
use think\Exception;
use think\Loader;
use think\Db;
use think\Config;
use think\Session;

class ClassManagement extends Controller
{
    use \app\admin\traits\controller\Controller;
    // 方法黑名单
    protected static $blacklist = [];

    protected function filter(&$map)
    {
		
		if ($this->request->param("schoolName")) {
			
			$map['schoolName'] = ["like", "%" . $this->request->param("schoolName"). "%"];
		}
		
        if ($this->request->param("className")) {
            $map['className'] = ["like", "%" . $this->request->param("className") . "%"];
        }
        if ($this->request->param("classTeacher")) {
            $map['classTeacher'] = ["like", "%" . $this->request->param("classTeacher") . "%"];
        }
		
		$info = Db::name("AdminUser")->field('type,realname')->where("id", UID)->find();
		if($info['type'] ==1)
		{
			//$school = Db::name("SchoolManagement");
			//$data = $school->field('schoolName')->where('schoolName',$info['realname'])->select();
			if(count($info)>0)
			    $map['schoolName']=["like", "%" . $info['realname']. "%"];
		}
		else if($info['type'] ==2)
		{
			$info1 = Db::name("AdminUser")->field('realname')->where('isdelete','0')->where("id", UID)->find();
			$data = Db::name("EmployeeManagement")->field('schoolName,className,name')->where('isdelete','0')->where('name',$info1['realname'])->find();
			if(count($data)>0)
			{
			    $map['schoolName']=["like", "%" . $data['schoolName']. "%"];
			    //$map['className'] = ["like", "%" . $data['className']. "%"];
			    //$map['classTeacher'] = ["like", "%" . $data['name'] . "%"];
			}
			
		}
		else if($info['type'] ==3)
		{
			$info1 = Db::name("AdminUser")->field('realname')->where('isdelete','0')->where("id", UID)->find();
			$data = Db::name("StudentManagement")->field('schoolName,name')->where('isdelete','0')->where('schoolName',$info1['realname'])->find();
			if(count($data)>0)
			{
			    $map['schoolName']=["like", "%" . $data['schoolName']. "%"];
			    //$map['classTeacher'] = ["like", "%" . $data['name'] . "%"];
			}
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


        $info = Db::name("AdminUser")->where('isdelete','0')->where('status',1)->where("id", UID)->find();
        //管理员登陆
        if($info['type'] == 0){
            //获取所有的校区名
            $all_schoolName = Db::name('SchoolManagement')->field('schoolName')->where('status',1)->where('isdelete',0)->select();

            $type = $info['account'];//为了显示相应的学级
        }
        //校区账号登陆
        if($info['type'] == 1){
            //获取当前校区名
            $all_schoolName = Db::name("SchoolManagement")->field('schoolName')->where('schoolAccount',$info['account'])->where('status',1)->where('isdelete','0')->select();

            $type = substr($info['account'],0,1);

        }

        //教师登陆
        if($info['type'] == 2){
            $schoolID = substr($info['account'],0,5);//获取该教师所在的学区编号
            $all_schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->select();

            $type = substr($info['account'],0,1);
        }

        //学生登陆
        if($info['type'] == 3){
            $schoolID = substr($info['account'],0,5);//获取该学生所在的学区编号
            $all_schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->select();

            $type = substr($info['account'],0,1);
        }


        $this->view->assign('all_schoolName',$all_schoolName);
        $this->view->assign('type',$type);

        return $this->view->fetch();
    }
	
	/**
     * 添加
     * @return mixed
     */
    public function add()
    {   
	    $controller = $this->request->controller();

        if ($this->request->isAjax()) {
			$data['schoolAccount'] = $this->request->param('schoolAccount');
			$data['class'] = $this->request->param('class');
			$data['className'] = $this->request->param('className');
			$data['classTeacher'] = $this->request->param('classTeacher');
			$data['remark'] = $this->request->param('remark');
			
			$schoolName = Db::name("SchoolManagement")->field('schoolName')->where('isdelete','0')->where('schoolAccount',$data['schoolAccount'])->find();
			$data['schoolName'] = $schoolName['schoolName'];
			$ret = Db::name("ClassManagement")->insert($data);
			if($ret){
				return ajax_return_adv("编辑成功");
			}else{
				return ajax_return_adv_error("添加失败");
			}
        // $controller = $this->request->controller();

        // if ($this->request->isAjax()) {
            // // 插入
            // $data = $this->request->except(['id']);
            
			
            // // 验证
            // if (class_exists($validateClass = Loader::parseClass(Config::get('app.validate_path'), 'validate', $controller))) {
                // $validate = new $validateClass();
                // if (!$validate->check($data)) {
                    // return ajax_return_adv_error($validate->getError());
                // }
            // }
		// //	$schoolAccount = $this->request->param('schoolAccount');
          // //  $data['schoolName'] = Db::name("SchoolManagement")->field('schoolName')->where('isdelete','0')->where('schoolAccount',$schoolAccount)->find();
            // // 写入数据
            // if (
                // class_exists($modelClass = Loader::parseClass(Config::get('app.model_path'), 'model', $this->parseCamelCase($controller)))
                // || class_exists($modelClass = Loader::parseClass(Config::get('app.model_path'), 'model', $controller))
            // ) {
                // //使用模型写入，可以在模型中定义更高级的操作
                // $model = new $modelClass();
				// $schoolName = Db::name("SchoolManagement")->field('schoolName')->where('isdelete','0')->where('schoolAccount',$data['schoolAccount'])->find();
				// $data['schoolName'] = $schoolName['schoolName'];
                // $ret = $model->isUpdate(false)->save($data);
            // } else {
                // // 简单的直接使用db写入
                // Db::startTrans();
                // try {
                    // $model = Db::name($this->parseTable($controller));
					// $schoolName = Db::name("SchoolManagement")->field('schoolName')->where('isdelete','0')->where('schoolAccount',$data['schoolAccount'])->find();
					// $data['schoolName'] = $schoolName['schoolName'];
                    // $ret = $model->insert($data);
                    // // 提交事务
                    // Db::commit();
                // } catch (\Exception $e) {
                    // // 回滚事务
                    // Db::rollback();

                    // return ajax_return_adv_error($e->getMessage());
                // }
            // }

          
        } else {
            // 添加
			
//            $info = Db::name("AdminUser")->where('isdelete','0')->where("id", UID)->find();
//            $type = $info['type'];
//			if($type ==1)
//			{
//                $data = Db::name("SchoolManagement")->field('schoolName')->where('isdelete','0')->where('schoolName',$info['realname'])->find();
//
//				$data1 = Db::name("EmployeeManagement")->field('schoolName,className,name')->where('isdelete','0')->where('schoolName',$info['realname'])->select();
//			}
//			else if($type ==2)
//			{
//				$info1 = Db::name("AdminUser")->field('realname')->where('isdelete','0')->where("id", UID)->find();
//				$data1 = Db::name("EmployeeManagement")->field('schoolName,className,name')->where('isdelete','0')->where('name',$info1['realname'])->select();
//
//
//			}
//			else if($type ==3)
//			{
//				$info1 = Db::name("AdminUser")->field('realname')->where('isdelete','0')->where("id", UID)->find();
//				$data1 = Db::name("EmployeeManagement")->field('schoolName,className,name')->where('isdelete','0')->where('name',$info1['realname'])->select();
//			}
//			else if($type ==0)
//			{
//				$school = Db::name("SchoolManagement");
//				$data = $school->field('schoolName')->where('isdelete','0')->select();
//				$data1 = Db::name("EmployeeManagement")->field('schoolName,className,name')->where('isdelete','0')->select();
//			}


            // 添加

            $info = Db::name("AdminUser")->where('isdelete','0')->where('status',1)->where("id", UID)->find();

            //管理员登陆
            if($info['type'] == 0){
                //获取所有的校区名
                $schoolName = Db::name('SchoolManagement')->field('schoolName,schoolAccount')->where('status',1)->where('isdelete',0)->select();

                $type = $info['account'];//为了显示相应的学级


                //查找所有的老师
                $teacherName = Db::name('EmployeeManagement')->field('name')->where('status',1)->where('isdelete',0)->select();
            }
            //校区账号登陆
            if($info['type'] == 1){
                //获取当前校区名
                $schoolName = Db::name("SchoolManagement")->field('schoolName,schoolAccount')->where('schoolAccount',$info['account'])->where('status',1)->where('isdelete','0')->select();

                $type = substr($info['account'],0,1);

                $teacherName = Db::name('EmployeeManagement')->field('name')->where('schoolName',$info['realname'])->where('status',1)->where('isdelete',0)->select();
            }

            //教师登陆
            if($info['type'] == 2){
                $schoolID = substr($info['account'],0,5);//获取该教师所在的学区编号
                $schoolName = Db::name('SchoolManagement')->field('schoolName,schoolAccount')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->select();

                $type = substr($info['account'],0,1);

                //获取该教师所在校区下的所有教师
                $schoolInfo = Db::name('SchoolManagement')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->find();
                $teacherName = Db::name('EmployeeManagement')->field('name')->where('schoolName',$schoolInfo['schoolName'])->where('status',1)->where('isdelete',0)->select();

            }

            //学生登陆
            if($info['type'] == 3){
                $schoolID = substr($info['account'],0,5);//获取该学生所在的学区编号
                $schoolName = Db::name('SchoolManagement')->field('schoolName,schoolAccount')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->select();

                $type = substr($info['account'],0,1);

                //获取该学生所在校区下的所有教师
                $schoolInfo = Db::name('SchoolManagement')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->find();
                $teacherName = Db::name('EmployeeManagement')->field('name')->where('schoolName',$schoolInfo['schoolName'])->where('status',1)->where('isdelete',0)->select();

            }

//return ajax_return_adv_error($schoolName);
            $this->view->assign('schoolName',$schoolName);
            $this->view->assign('type',$type);
            $this->view->assign("teacherName",$teacherName);

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
            $id = $this->request->param('id');
			$data['schoolAccount'] = $this->request->param('schoolAccount');
			$data['class'] = $this->request->param('class');
			$data['className'] = $this->request->param('className');
			$data['classTeacher'] = $this->request->param('classTeacher');
			$data['remark'] = $this->request->param('remark');
			
			$schoolName = Db::name("SchoolManagement")->field('schoolName')->where('isdelete','0')->where('schoolAccount',$data['schoolAccount'])->find();
			$data['schoolName'] = $schoolName['schoolName'];
			
			$ret = Db::name("ClassManagement")->where('id',$id)->where('status',1)->where('isdelete',0)->update($data);
			//return ajax_return_adv_error(DB::getlastsql());
			return ajax_return_adv("编辑成功");
        } else {
            // 编辑
            $id = $this->request->param('id');
            if (!$id) {
                throw new HttpException(404, "缺少参数ID");
            }
            $vo = $this->getModel($controller)->find($id);  //当前记录
            if (!$vo) {
                throw new HttpException(404, '该记录不存在');
            }

//				$info = Db::name("AdminUser")->field('type,realname')->where('isdelete','0')->where("id", UID)->find();
//			if($info['type'] ==1)
//			{
//				$school = Db::name("SchoolManagement");
//				$data = $school->field('schoolName')->where('isdelete','0')->where('schoolName',$info['realname'])->select();
//				$data1 = Db::name("EmployeeManagement")->field('schoolName,className,name')->where('isdelete','0')->where('schoolName',$info['realname'])->select();
//			}
//			else if($info['type'] ==2)
//			{
//				$info1 = Db::name("AdminUser")->field('realname')->where('isdelete','0')->where("id", UID)->find();
//				$data1 = Db::name("EmployeeManagement")->field('schoolName,className,name')->where('isdelete','0')->where('name',$info1['realname'])->select();
//
//
//			}
//			else if($info['type'] ==3)
//			{
//				$info1 = Db::name("AdminUser")->field('realname')->where('isdelete','0')->where("id", UID)->find();
//				$data1 = Db::name("EmployeeManagement")->field('schoolName,className,name')->where('isdelete','0')->where('name',$info1['realname'])->select();
//			}
//			else if($info['type'] ==0)
//			{
//				$school = Db::name("SchoolManagement");
//				$data = $school->field('schoolName')->where('isdelete','0')->select();
//				$data1 = Db::name("EmployeeManagement")->field('schoolName,className,name')->where('isdelete','0')->select();
//			}


            $info = Db::name("AdminUser")->where('isdelete','0')->where('status',1)->where("id", UID)->find();

            //管理员登陆
            if($info['type'] == 0){
                //获取所有的校区名
                $schoolName = Db::name('SchoolManagement')->field('schoolName,schoolAccount')->where('status',1)->where('isdelete',0)->select();

                $type = $info['account'];//为了显示相应的学级


                //查找所有的老师
                $teacherName = Db::name('EmployeeManagement')->field('name')->where('status',1)->where('isdelete',0)->select();
            }
            //校区账号登陆
            if($info['type'] == 1){
                //获取当前校区名
                $schoolName = Db::name("SchoolManagement")->field('schoolName,schoolAccount')->where('schoolAccount',$info['account'])->where('status',1)->where('isdelete','0')->select();

                $type = substr($info['account'],0,1);

                $teacherName = Db::name('EmployeeManagement')->field('name')->where('schoolName',$info['realname'])->where('status',1)->where('isdelete',0)->select();
            }

            //教师登陆
            if($info['type'] == 2){
                $schoolID = substr($info['account'],0,5);//获取该教师所在的学区编号
                $schoolName = Db::name('SchoolManagement')->field('schoolName,schoolAccount')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->select();

                $type = substr($info['account'],0,1);

                //获取该教师所在校区下的所有教师
                $schoolInfo = Db::name('SchoolManagement')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->find();
                $teacherName = Db::name('EmployeeManagement')->field('name')->where('schoolName',$schoolInfo['schoolName'])->where('status',1)->where('isdelete',0)->select();

            }

            //学生登陆
            if($info['type'] == 3){
                $schoolID = substr($info['account'],0,5);//获取该学生所在的学区编号
                $schoolName = Db::name('SchoolManagement')->field('schoolName,schoolAccount')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->select();

                $type = substr($info['account'],0,1);

                //获取该学生所在校区下的所有教师
                $schoolInfo = Db::name('SchoolManagement')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->find();
                $teacherName = Db::name('EmployeeManagement')->field('name')->where('schoolName',$schoolInfo['schoolName'])->where('status',1)->where('isdelete',0)->select();

            }


            $this->view->assign('schoolName',$schoolName);
            $this->view->assign('type',$type);
            $this->view->assign("teacherName",$teacherName);


            $this->view->assign("vo", $vo);

            return $this->view->fetch();
        }
    }

}
