<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\Session;
use think\Config;
use think\Exception;
use think\View;
use think\Request;
use think\Model; 

class Userinfo extends Controller
{
    public function index()
    {
        //return \think\Response::create(\think\Url::build('/admin'), 'redirect');
		//$model = Db::name("Video");
		//$data = $model->where('type','视频安全')->where('release','超级管理员')->select();
		//$this->view->assign("data", $data);
		if(session('id'))
		{
    		//先判断该用户角色
			$model = Db::name("AdminUser");
    		$data = $model->where('id','=',session('id'))->where('status',1)->find();
    		
    		$type  = $data['type'];

			// $type == 2老师页面index1.html
			// $type == 3学生页面index.html
    		if($type == 2)
    		{
    		    $data1 = Db::name("EmployeeManagement")->where('account','=',$data['account'])->where('status',1)->find();
				$data2 = Db::name("EmployeeLinkman")->where('employee_id','=',$data1['id'])->where('status',1)->find();
				$this->view->assign("data2", $data2);
				$this->view->assign("data1", $data1);
    		    return $this->fetch('index1');
    		}
    		else if($type == 3)
    		{
    		    $data1 = Db::name("StudentManagement")->where('account','=',$data['account'])->where('status',1)->find();
				$data2 = Db::name("StudentLinkman")->where('student_id','=',$data1['id'])->where('status',1)->find();
				$this->view->assign("data2", $data2);
				$this->view->assign("data1", $data1);
    		    return $this->fetch('index');
    		}
    		
    		
		}
		
    }
    
}