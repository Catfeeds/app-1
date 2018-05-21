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
//个人信息接口
class Userinfoapi extends Controller{
    public function index()<?php
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
//个人信息接口
class Userinfoapi extends Controller{
    public function index()
    {
        if($this->request->isGet() && $this->request->param('phone_account')!=''){

            $phone_account = $this->request->param('phone_account');//获取传过来的账号
            $info = DB::name('patriarch')->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->find();
          //  $info = Db::name('AdminUser')->where('account',$account)->where('status',1)->where('isdelete',0)->find();//获取当前用户的信息
            if(!$info){
                    echo $this->json(1,'账号错误');
            }else{  
                    $linkman_info = Db::name('StudentLinkman')->where('number',$info['phone'])->where('status',1)->where('isdelete',0)->find();//
                    $student_info = Db::name('studentManagement')->where('id',$linkman_info['student_id'])->where('status',1)->where('isdelete',0)->find();//查询当前学生的所有信息
                    $userinfo = array(); //新定义一个数组用于存放学生信息和联系人信息
                    $userinfo['student_info'] = $student_info;
                    $userinfo['linkman_info'] = $linkman_info;


                    echo $this->json(0,'ok',$userinfo);

                
            }
        }else{
            echo $this->json(3,'非法请求');
        }
    }





    public function json($code,$msg,$data=array())
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
    {
        if($this->request->isGet() && $this->request->param('phone')!=''){
			
			$phone = $this->request->param('phone');//手机号
			$admin = Db::name('AdminUser')->where('mobile',$phone)->where('status',1)->where('isdelete',0)->find();
			if($admin){
				if($admin['type'] == 3){
					$student = Db::name('StudentManagement')->where('account',$admin['account'])->where('status',1)->where('isdelete',0)->find();
					$account = $student['account'];
				}elseif($admin['type'] ==2){
					$student = Db::name('EmployeeManagement')->where('account',$admin['account'])->where('status',1)->where('isdelete',0)->find();
					$account = $student['account'];
				}
			}else{
				return $this->json(4,'无权限访问');
				
			}
			
            $info = Db::name('AdminUser')->where('account',$account)->where('status',1)->where('isdelete',0)->find();//获取当前用户的信息
            if(!$info){
                echo $this->json(1,'账号错误');
            }else{
                $type = $info['type'];
                if($type == 3){
                    $student_info = Db::name('studentManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();//查询当前学生的所有信息
                    $student_id = $student_info['id'];//学生id
                    $linkman_info = Db::name('StudentLinkman')->where('student_id',$student_id)->where('status',1)->where('isdelete',0)->find();//查询当前学生的联系人的所有信息
                    $linkman_info['student_account'] = $account; //将学生账号也放入联系人信息中
                    //print_r($linkman_info);//联系人的所有信息


                    $userinfo = array(); //新定义一个数组用于存放学生信息和联系人信息
                    $userinfo['student_info'] = $student_info;
                    $userinfo['linkman_info'] = $linkman_info;


                    echo $this->json(0,'ok',$userinfo);

                }else{
                    echo $this->json(2,'身份错误'); //其他角色
                }
            }
        }else{
            echo $this->json(3,'非法请求');
        }
    }





    public function json($code,$msg,$data=array())
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