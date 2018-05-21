<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

class Introduce extends Controller
{
    public function index()
    {

		$phone_account = $this->request->param('phone_account');
		$account = $this->request->param('account');
	    $type = $this->request->param('type');

	   if(empty($type)){//若是家长
			$info = DB::name('patriarch')->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->find();//或手机号登录
			$info2 =  Db::table('tp_student_linkman a, tp_student_management b')->field('b.name as name,b.className as className,b.schoolName as schoolName')->where('b.id=a.student_id and b.account="'.$account.'" and a.number='.$info['phone'])->where('a.status',1)->where('a.isdelete',0)->where('b.status',1)->where('b.isdelete',0)->find();
		}else{//若是教师

            $info2 = Db::name('EmployeeManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
	    }	
		$data = Db::name("Introduce")->where('release',$info2['schoolName'])->where('status','1')->select();
		$this->view->assign("data", $data);
		

	    
	    return $this->fetch();


    }
	

}
