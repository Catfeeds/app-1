<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;
use think\Session;

//查看所有日记------以相册的形式存在
class Selectnote extends Controller{
    public function index()
    {

        if(!session('id')){
            //$this->error('请先登录系统',url('Parentlogin/index'));
            $this->redirect('Parentlogin/index');//跳转到登录页面
        }
        else{
            //$result = Db::name("note")->where('type','成长日记')->where('release','超级管理员')->where('status',1)->order('release_time','desc')->select();//查询所有

            $info = Db::name('AdminUser')->where('account',session('user_name'))->where('status',1)->find();
            $type = $info['type'];
            if($type == 2){
                $info2 = Db::name('EmployeeManagement')->where('account',session('user_name'))->find();
                $schoolName = $info2['schoolName']; //判断当前用户所属校区
            }
            if($type == 3){
                $info2 = Db::name('StudentManagement')->where('account',session('user_name'))->find();
                $schoolName = $info2['schoolName']; //判断当前用户所属校区
            }

            //查询该校区内的所有的宝宝成长日记
            $result = Db::name("note")->where('schoolName',$schoolName)->where('status',1)->order('release_time','desc')->select();//查询所有
            $array1 = array();
            $array2 = array();
            foreach($result as $value){
                if(!array_key_exists($value['account'],$array1)){
                    $array1[$value['account']] = $value;
                    $info = DB::name('StudentManagement')->where('account',$value['account'])->where('status',1)->find();//根据该账户从tp_student_management中获取信息
                    $array2[$value['account']] = $info;
                }
            }
    print_r($array2);exit;
            $this->view->assign("data", $array1); //得到以账号为索引，以该行记录(数组)为元素的二维数组
            $this->view->assign("data2", $array2);//得到以账号为索引，以对应姓名为元素的一维数组
            return $this->fetch();
        }

    }



}
