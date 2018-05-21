<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;
// use think\Image;

class Modifyinformation extends Controller
{
    public function index()
    {
        //return \think\Response::create(\think\Url::build('/admin'), 'redirect');
        //$model = Db::name("Video");
        //$data = $model->where('type','视频安全')->where('release','超级管理员')->select();
        //$this->view->assign("data", $data);
        if(!session('id')){
            //return $this->error('请先登录系统',url('Parentlogin/index'));
            $this->redirect('Parentlogin/index');
        }
        else{
            $info = Db::name('AdminUser')->where('id',session('id'))->where('status',1)->find();//通过当前登录用户的id判断该用户所有信息
            $type = $info['type'];//获取当前用户的身份
            $password = session('password'); //当前用户的密码

            // 如果是3，表示是家长登录，显示相应页面
            if($type == 3){
                $StudentManagement = Db::name('StudentManagement')->where('account',session('user_name'))->where('status',1)->find();

                $student_headurl = $StudentManagement['student_headurl'];//宝宝头像
                $height = $StudentManagement['height'];//宝宝身高
                $weight = $StudentManagement['weight'];//宝宝体重

                $id = $StudentManagement['id'];//StudentManagement表的id
                $student_linkman = Db::name('StudentLinkman')->where('student_id',$id)->where('status',1)->find();
                $linkman_headurl = $student_linkman['linkman_headurl'];//该宝宝的第一个联系人头像
                $number = $student_linkman['number'];//该宝宝的第一个联系人电话号码

                $this->assign('height',$height);
                $this->assign('weight',$weight);
                $this->assign('student_headurl',$student_headurl);
                $this->assign('linkman_headurl',$linkman_headurl);
                $this->assign('number',$number);
                $this->assign('password',$password);
                return $this->fetch();
            }


            //如果是老师登录
            if($type == 2){
                $employeeManagement = Db::name('EmployeeManagement')->where('account',session('user_name'))->where('status',1)->find();

                $employee_headurl = $employeeManagement['employee_headurl'];//员工头像
                $height = $employeeManagement['height'];//员工身高
                $weight = $employeeManagement['weight'];//员工体重

                $id = $employeeManagement['id'];//EmployeeManagement表的id
                $employee_linkman = Db::name('EmployeeLinkman')->where('employee_id',$id)->where('status',1)->find();
                $linkman_headurl = $employee_linkman['linkman_headurl'];//该员工的第一个联系人头像
                $number = $employee_linkman['number'];//该员工的第一个联系人电话号码

                $this->assign('height',$height);
                $this->assign('weight',$weight);
                $this->assign('employee_headurl',$employee_headurl);
                $this->assign('linkman_headurl',$linkman_headurl);
                $this->assign('number',$number);
                $this->assign('password',$password);
                return $this->fetch('index2');//教师修改页面
            }
        }

    }


    /**
     * 接收传过来的学生信息，修改学生信息
     */
    public function modifyStudentInfo()
    {
        if(request()->isPost()){
           
            $height = $this->request->post('height');//获取宝宝身高
            $weight = $this->request->post('weight');//获取宝宝体重
            $files = request()->file('files'); //获取宝宝联系人的头像
            $number = $this->request->post('number');//获取宝宝联系人的电话号码
            $password = md5($this->request->post('password'));//获取宝宝密码

            $studentinfo = Db::name('StudentManagement')->where('account',session('user_name'))->find();
            $student_id = $studentinfo['id']; //获取学生的id，目的为了在联系人表中找到相关的联系人

       
            //更新宝宝身高、体重、联系人电话、宝宝密码信息
            $data1 = array('password' => $password);
            $data2 = array(
                'height' => $height,
                'weight' => $weight,
                'passWord' => $password
            );
            $data3 = array(
                'number' => $number
            );


            Db::name('AdminUser')->where('id',session('id'))->where('status',1)->update($data1); //更新tp_admin_user表
            Db::name('StudentManagement')->where('account',session('user_name'))->where('status',1)->update($data2); //更新tp_student_management表
            Db::name('StudentLinkman')->where('student_id',$student_id)->where('status',1)->limit(1)->update($data3);//更新tp_student_linkman表
            $this->redirect('modifyinformation/index');


        }

    }

    /**
     * 接收传过来的教师信息，修改教师信息
     */
    public function modifyStudentInfo2()
    {
        if(request()->isPost()){
           
            $height = $this->request->post('height');//获取教师身高
            $weight = $this->request->post('weight');//获取教师体重
            $files = request()->file('files'); //获取教师联系人的头像
            $number = $this->request->post('number');//获取教师联系人的电话号码
            $password = md5($this->request->post('password'));//获取教师密码

            $employeeinfo = Db::name('EmployeeManagement')->where('account',session('user_name'))->where('status',1)->find();
            $employee_id = $employeeinfo['id']; //获取教师的id，目的为了在联系人表中找到相关的联系人

      



            //更新教师身高、体重、联系人电话、教师密码信息
            $data1 = array('password' => $password);
            $data2 = array(
                'height' => $height,
                'weight' => $weight,
                'passWord' => $password
            );
            $data3 = array(
                'number' => $number
            );
            Db::name('AdminUser')->where('id',session('id'))->where('status',1)->update($data1); //更新tp_admin_user表
            Db::name('EmployeeManagement')->where('account',session('user_name'))->where('status',1)->update($data2); //更新tp_employee_management表
            Db::name('EmployeeLinkman')->where('employee_id',$employee_id)->where('status',1)->limit(1)->update($data3);//更新tp_employee_linkman表
            $this->redirect('modifyinformation/index');

        }

    }

    //学生头像
    public  function base () {
         
        $base64 = $this->request->post('str');
		
        $linkman = $this->request->post('linkman');
        if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)){
            $type = $result[2];
            $path = date ('Ymd'); // 接收文件目录
            if (! file_exists ("./uploads/file/".$path)) {
                mkdir ("./uploads/file/".$path, 0777, true );
            }

            $new_file = "./uploads/file/".$path."/".time().".{$type}";
            $headurl = date ('Ymd').'/'.time().".{$type}";
		
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64)))){
				
                if(empty($linkman)){
                    $array1 =  array('student_headurl' => $headurl,'isUpload' => 1);
                    Db::name('StudentManagement')->where('account',session('user_name'))->where('status',1)->update($array1);
                }else{
                    $studentinfo = Db::name('StudentManagement')->where('account',session('user_name'))->find();
                    $student_id = $studentinfo['id']; 
                    $array2 =  array('linkman_headurl' => $headurl,'isUpload'=>1);
                    Db::name('StudentLinkman')->where('student_id',$student_id)->where('status',1)->limit(1)->update($array2);

                }
                    
            }
        }

    }

     //教师头像
    public  function jsbase () {

        $base64 = $this->request->post('str');
        $linkman = $this->request->post('linkman');
        if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)){
            $type = $result[2];
            $path = date ('Ymd'); // 接收文件目录
            if (! file_exists ("./uploads/file/".$path)) {
                mkdir ("./uploads/file/".$path, 0777, true );
            }

            $new_file =  "./uploads/file/".$path."/".time().".{$type}";
		
            $headurl = date ('Ymd').'/'.time().".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64)))){
                if(empty($linkman)){
                    $array1 =  array('employee_headurl' => $headurl,'isUpload' => 1);
                    Db::name('EmployeeManagement')->where('account',session('user_name'))->where('status',1)->update($array1);
                }else{
                    $employeeinfo = Db::name('EmployeeManagement')->where('account',session('user_name'))->find();
                    $employee_id = $employeeinfo['id']; 
                    $array2 =  array('linkman_headurl' => $headurl,'isUpload'=>1);
                    Db::name('EmployeeLinkman')->where('employee_id',$employee_id)->where('status',1)->limit(1)->update($array2);

                }
                    
            }
        }

    }

}