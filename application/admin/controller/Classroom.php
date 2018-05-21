<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

//宝宝课程
class Classroom extends Controller
{
    public function index()
    {

        if(!session('id')){
            //return $this->error('请先登录系统',url('Parentlogin/index'));
            $this->redirect('Parentlogin/index');//不提示，直接跳转到登录页面
        }
        else{
            //判断校区
            $info = Db::name('AdminUser')->where('account',session('user_name'))->where('status',1)->find();
            $type = $info['type'];
            //若是学生
            if($type == 3){
                $info2 = Db::name('StudentManagement')->where('account',session('user_name'))->where('status',1)->find();
                $schoolName =  $info2['schoolName'];
                $className = $info2['className']; //所在班级
            }
            //若是老师
            if($type == 2){
                $info2 = Db::name('EmployeeManagement')->where('account',session('user_name'))->where('status',1)->find();
                $schoolName =  $info2['schoolName'];
                $className = $info2['className']; //所在班级
            }

            $date = date('Y-m-d',time());
            $result = Db::name('StudentClassroom')->where('release',$schoolName)->where('className',$className)->where('course_time',$date)->where('status',1)->where('isdelete',0)->find();

            $this->assign('schoolName',$schoolName);
            $this->assign('className',$className);
            $this->assign('result',$result);
            return $this->fetch();
        }

    }

    /**
     *  根据传过来的时间和校区名获取相应的课程安排
     */
    public function getCourse()
    {
        if(request()->isAjax()){
            $date = $this->request->param('date');
            $schoolName = $this->request->param('schoolName');
            $className = $this->request->param('className');
            $result = Db::name('StudentClassroom')->where('release',$schoolName)->where('className',$className)->where('course_time',$date)->where('status',1)->where('isdelete',0)->find();
            if($result){
                $exercise = $result['exercise']; //早操活动
                $education = $result['education']; //行为教育
                $life = $result['life']; //生活活动
                $outdoor = $result['outdoor']; //户外活动
                $game = $result['game']; //游戏表演
                $activities = $result['activities']; //生活活动
                $area_activities = $result['area_activities']; //区域活动
                $leaving = $result['leaving']; //离园活动
                echo $exercise.','.$education.','.$life.','.$outdoor.','.$game.','.$activities.','.$area_activities.','.$leaving;
            }
        }

    }
}