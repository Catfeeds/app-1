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

//门禁
class Entrance extends Controller
{
    public function index()
    {
        //return \think\Response::create(\think\Url::build('/admin'), 'redirect');
		//$model = Db::name("Video");
		//$data = $model->where('type','视频安全')->where('release','超级管理员')->select();
		//$this->view->assign("data", $data);

        if(!session('id')){
            $this->redirect('Parentlogin/index');
        }else{
            $info = Db::name('AdminUser')->where('id',session('id'))->where('status',1)->where('isdelete',0)->find();
            $type = $info['type'];
            if($type == 2){
                $array = Db::name('EmployeeManagement')->where('account',session('user_name'))->where('status',1)->where('isdelete',0)->find();
//                $headurl = $array['employee_headurl'];
                $linkman_info = Db::name('EmployeeLinkman')->where('employee_id',$array['id'])->where('status',1)->where('isdelete',0)->find();//当前用户的联系人信息

            }
            if($type == 3){
                $array = Db::name('StudentManagement')->where('account',session('user_name'))->where('status',1)->find();
//                $headurl = $array['student_headurl'];
                $linkman_info = Db::name('StudentLinkman')->where('student_id',$array['id'])->where('status',1)->where('isdelete',0)->find();//当前用户的联系人信息

            }

            $this->assign('linkman_info',$linkman_info);
			
			
            return $this->fetch();
        }

    }





    public function logout()
    {
        session(null);//退出清空session  
        $this->redirect('Index/index');
       // $this->success('退出成功',url('Index/index/index'));//跳转到登录页面
    } 
}