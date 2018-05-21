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


class Circle extends Controller
{

    public function index()
    {   
        if(session('id')){  

            $student = Db::name('StudentManagement')->where('account',session('user_name'))->where('status',1)->find();
            if($student){
                $schoolname = $student['schoolName'];
            }else{
                $student = Db::name('EmployeeManagement')->where('account',session('user_name'))->find();
                $schoolname = $student['schoolName'];
            }
            $show = '我的';
           
        }else{
            $show = '未登录';
            $schoolname = '';
        }
        $this->assign('show',$show);
        $this->assign('schoolname',$schoolname);
        return $this->fetch();
         
    }
    
}
