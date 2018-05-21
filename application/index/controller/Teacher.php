<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

class Teacher extends Controller
{
    public function index()
    {
       
		$phone_account = $this->request->param('phone_account');
		$account = $this->request->param('account');
	    $type = $this->request->param('type');

	    $info1 = DB::name('SchoolManagement')->where('schoolID','=',substr($account,0,5))->where('status',1)->where('isdelete',0)->find();//查找校区账号	
		$data = Db::name("Teacher")->where('schoolAccount',$info1['schoolAccount'])->where('status','1')->select();
		$this->view->assign("data", $data);
		
//var_dump($data);
	    
	    return $this->fetch();
    }

}
