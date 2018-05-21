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

class Parentloginapi extends Controller
{
    public function index()
    {
        //return \think\Response::create(\think\Url::build('/admin'), 'redirect');
        //$model = Db::name("Video");
        //$data = $model->where('type','视频安全')->where('release','超级管理员')->select();
        //$this->view->assign("data", $data);

        return $this->fetch();
    }

    //检测家长登录账号和密码是否正确
public function checkLogin()
    {

      if($this->request->isPost()) {
           
            $username = $this->request->param('username');
            $password = $this->request->param('password');
            $admin = DB::name('patriarch')->where('phone','=',$username)->where('status',1)->where('isdelete',0)->find();//或手机号登录
			
        //var_dump($username);
            if($admin){
                if($admin['password'] === $password){
                  //  $student = DB::name('StudentManagement')->where('contact','=',$username)->where('status',1)->where('isdelete',0)->find();//学生
                    $linkman = DB::name('StudentLinkman')->where('number','=',$username)->where('status',1)->where('isdelete',0)->find();//学生联系人
                    if($linkman){
                        $admin1 = DB::name('StudentManagement')->where('id','=',$linkman['student_id'])->where('status',1)->where('isdelete',0)->find();
                        $data['account'] = $admin1['account'];
                        $data['phone_account'] = $admin ['phone_account'];
						$data['type'] = 0;
                    }else{
                        $data['phone_account'] = $admin['phone_account'];
                        $data['account'] = '';
						$data['type'] = 0;
                        //return  $this->json1(3,'登陆成功！',$data);
                    }
                    
                    return  $this->json1(0,'登陆成功！',$data);

                }else{
//                    return ajax_return_adv_error('密码错误！');
                    return  $this->json1(1,'密码错误！');
                }
            }else{
//                   return ajax_return_adv_error('帐号不存在或已禁用！');
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