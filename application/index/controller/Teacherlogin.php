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
class Teacherlogin extends Controller
{
    public function index()
    {
        //return \think\Response::create(\think\Url::build('/admin'), 'redirect');
		//$model = Db::name("Video");
		//$data = $model->where('type','视频安全')->where('release','超级管理员')->select();
		//$this->view->assign("data", $data);
		return $this->fetch();
    }
    public function checkLogin()
    {
        if($this->request->isAjax() &&$this->request->isPost()) {
            
            $data = $this->request->post();
            $username = $data['username'];
            $password = $data['password'];
      
            $admin = DB::name('AdminUser')->where('account','=',$username)->where("type",2)->where('status',1)->find(); 
            
            if(!$admin)
            {
                $admin = DB::name('AdminUser')->where('mobile','=',$username)->where("type",2)->where('status',1)->find(); 
            }
            
            if($admin){  
                if($admin['password'] === md5($password)){  
                    
                   
					if($admin['type'] == 2){
						session(null);//退出清空session
                    
                    //将登录id和名称存入session  
						Session::set('id',$admin['id']);  
						Session::set('password',$password);
						Session::set('user_name',$admin['account']);  
						Session::set('real_name', $admin['realname']);
						Session::set('last_login_ip', $admin['last_login_ip']);
						Session::set('last_login_time', $admin['last_login_time']);
				    }
                    return ajax_return_adv('登录成功！', '');  
                }else{  
                    return ajax_return_adv_error('密码错误！'); 
                }  
            }else{  
                return ajax_return_adv_error('帐号不存在或已禁用！');
            }  
        }
        else {
            
            throw new Exception("非法请求");
        }
    
    }
}