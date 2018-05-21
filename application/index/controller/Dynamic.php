<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

//考勤信息
class Dynamic extends Controller
{
    public function index()
    {
        if(!session('id')){
            $this->redirect('Parentlogin/index');//不提示，直接跳转到登录页面
        }
        else{

            $student = Db::name('StudentManagement')->where('account',session('user_name'))->where('status',1)->find();
            if($student){
                $student['employee_headurl'] = '';
            }else{
                $student = Db::name('EmployeeManagement')->where('account',session('user_name'))->find();
                $student['student_headurl'] = '';
            }

            $this->assign('student',$student);
// //            $info = Db::name('AdminUser')->where('account',session('user_name'))->where('status',1)->where('isdelete',0)->find();
// //            $type = $info['type'];
// //            if($type == 3){
// //
// //            }
//             $nowtime = date('Y-m-d',time());//当前时间
//             //查询所有关于当前用户的所有已审批的请假消息
//             $result = Db::name('leave')->where('account',session('user_name'))->where('checkinfo',1)->where('status',1)->where('isdelete',0)->select();

// //            $status = '';
// //            $content = '';
//             if($result){
//                 foreach($result as $value){
//                     if($nowtime>=$value['start_time'] && $nowtime<=$value['end_time']){
//                         $status = '请假';
//                         $content = $value['content'];//请假事由
//                         break;//一旦找到了就停止
//                     }else{
//                         $status = '';
//                         $content = '';
//                     }
//                 }

//                 $this->assign('status',$status);
//                 $this->assign('content',$content);
//             }else{
//                 $this->assign('status','');
//                 $this->assign('content','');
//             }

//             $this->assign('account',session('user_name'));//存入当前用户账户名
            return $this->fetch();
        }
    }


    /**
     * 获取提交过来的账号以及时间查看请假记录
     */
    public function getWorkinfo()
    {
        if(request()->isAjax()){
            $date = $this->request->param('date'); //获取指定的时间
            $account = $this->request->param('account');//获取当前用户账号
            $result = Db::name('leave')->where('account',$account)->where('checkinfo',1)->where('status',1)->where('isdelete',0)->select();
            //判断指定的时间是否请假
            foreach($result as $value){
                if($date>=$value['start_time'] && $date<=$value['end_time']){
                    $status = '请假';
                    $content = $value['content'];//请假事由
                    break;//一旦找到了就停止
                }else{
                    $status = '';
                    $content = '';
                }
            }
            if($status!='' && $content!=''){
               echo $status.','.$content;
            }else{
                echo '';
            }


        }
    }



}