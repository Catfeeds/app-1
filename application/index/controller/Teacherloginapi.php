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
class Teacherloginapi extends Controller
{
  
    //检测教师登陆是否正确
    public function checkLogin()
    {
        if($this->request->isPost()) {
           
            $username = $this->request->param('username');
            $password = $this->request->param('password');
            $admin = DB::name('patriarch')->where('phone','=',$username)->where('status',1)->where('isdelete',0)->find();//手机号登录
        
            if($admin){
                if($admin['password'] === $password){
                    $employee = DB::name('EmployeeManagement')->where('iphone','=',$username)->where('status',1)->where('isdelete',0)->find();//教师
                    if($employee){
                        $data['account'] = $employee['account'];
                        $data['phone_account'] = $admin ['phone_account'];
						$data['type'] = $employee['identity'];
                        return  $this->json1(0,'登陆成功！',$data);
                    }else{
                       return  $this->json1(4,'您不是教师请选择正确的登录方式');
                    }
                    
                }else{
//                    return ajax_return_adv_error('密码错误！');
                    return  $this->json1(1,'密码错误！');
                }
            }else{
//                return ajax_return_adv_error('帐号不存在或已禁用！');
               return  $this->json1(2,'帐号不存在或已禁用！');
            }
        }else{

//            throw new Exception("非法请求");
            return $this->json1(3,'非法请求');
        }

    }


    //将数据转为json格式
    public function json1($code,$msg,$data=array())
    {

        if(!is_numeric($code)){
            return '';
        }
        if(empty($data)){
            $result = array(
                'code' => $code,
                'msg' => $msg,
            );
        }else{
            $result = array(
                'code' => $code,
                'msg' => $msg,
                'data' => $data,
            );
        }
        return json_encode($result);
    }






}