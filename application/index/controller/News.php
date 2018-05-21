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

class News extends Controller
{
    public function index()
    {
        //return \think\Response::create(\think\Url::build('/admin'), 'redirect');
		//$model = Db::name("Video");
		//$data = $model->where('type','视频安全')->where('release','超级管理员')->select();
		//$this->view->assign("data", $data);

        if(!session('id')){
            $this->redirect('Parentlogin/index');
            //$this->error('请先登录系统',url('Parentlogin/index'));
        }
        else{
            $info = Db::name('AdminUser')->where('id',session('id'))->where('status',1)->find();
            $type = $info['type'];
            if($type == 2){
                $array = Db::name('EmployeeManagement')->where('account',session('user_name'))->where('status',1)->find();
                $headurl = $array['employee_headurl'];
            }
            if($type == 3){
                $array = Db::name('StudentManagement')->where('account',session('user_name'))->where('status',1)->find();
                $headurl = $array['student_headurl'];
            }
            $this->assign('headurl',$headurl);
            return $this->fetch();
        }

    }
    public function logout(){  
        session(null);//退出清空session  
        $this->redirect('Index/index');
       // $this->success('退出成功',url('Index/index/index'));//跳转到登录页面
    } 
}