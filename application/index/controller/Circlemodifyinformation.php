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

//圈子某一条动态的详情页
class Circlemodifyinformation extends Controller{
    public function index()
    {
        //需要获取一个链接地址和一个圈子动态的id

        //获取传过来的圈子动态id
            if($this->request->isGet() && $this->request->param('id')!=''){
                $id = $this->request->param('id');//获取传过来的圈子动态id
                $data = Db::name('CircleDynamic')->where('id',$id)->where('status',1)->where('isdelete',0)->find();//根据动态id查询相应动态信息


                //发布动态者----教师头像
                $account = $data['account'];
                $info2 = Db::name('EmployeeManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();//查询当前动态发布者的头像
                $employee_headurl = $info2['employee_headurl'];


                //关于该动态所有相关的评论
                //$id = $data['id'];//获取动态内容id
                $comment_array = Db::name('CircleComment')->where('rid',$id)->where('status',1)->where('isdelete',0)->order('comment_time desc')->select();//从tp_circle_comment表中获取该条动态的所有评论
                //所有评论者的个人信息
                $personal = array();
                foreach($comment_array as $value){
                    $info = Db::name('AdminUser')->where('account',$value['account'])->where('status',1)->where('isdelete',0)->find();
                    $type = $info['type'];
                    //若是教师
                    if($type == 2){
                        $personal[] = Db::name('EmployeeManagement')->where('account',$value['account'])->where('status',1)->where('isdelete',0)->find();
                    }
                    //若是学生
                    if($type == 3){
                        $personal[] = Db::name('StudentManagement')->where('account',$value['account'])->where('status',1)->where('isdelete',0)->find();
                    }
                }
                $this->assign('personal',$personal);


                //关于该动态所有的点赞记录
                $zan_array = Db::name('CircleZan')->where('ids',$id)->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time desc')->select();
                $this->assign('zan_array',$zan_array);

                //关于该动态所有的点赞者信息
                $zan_user = array();
                foreach($zan_array as $value2){
                    $info = Db::name('AdminUser')->where('account',$value2['account'])->where('status',1)->where('isdelete',0)->find();
                    $type = $info['type'];
                    //若是教师
                    if($type == 2){
                        $zan_user[] = Db::name('EmployeeManagement')->where('account',$value2['account'])->where('status',1)->where('isdelete',0)->find();
                    }
                    //若是学生
                    if($type == 3){
                        $zan_user[] = Db::name('StudentManagement')->where('account',$value2['account'])->where('status',1)->where('isdelete',0)->find();
                    }
                }
                $this->assign('zan_user',$zan_user);





                $this->assign('data',$data);
                $this->assign('employee_headurl',$employee_headurl);
                $this->assign('comment_array',$comment_array);

                return $this->fetch();
            }


    }


}