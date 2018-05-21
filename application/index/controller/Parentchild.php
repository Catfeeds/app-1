<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

class Parentchild extends Controller
{
    	//亲子任务详情
	
	public  function index (){
		//$phone_account = $this->request->param('phone_account');
		//$account = $this->request->param('account');
	//	$type = $this->request->param('type');
		$id = $this->request->param('id');
		$info1 = DB::name('parentchild')->where('id',$id)->where('status',1)->where('isdelete',0)->find();//获取亲自任务
		$info2 = DB::name('patriarch')->where('phone_account',$info1['phone_account'])->where('status',1)->where('isdelete',0)->find();//获取头像
		$info3 = DB::name('EmployeeManagement')->where('iphone',$info2['phone'])->where('status',1)->where('isdelete',0)->find();//获取姓名
		$data['title']= $info1['title'];
		$data['content']= $info1['content'];
		$data['thumbs']= json_decode($info1['thumbs']);
		$data['images']= json_decode($info1['images']);
		$data['headerurl']= $info2['headerurl'];
		$data['release_time']= $info1['release_time'];
		$data['nickname']= $info3['name'].'老师';
		$this->assign("data",$data);       
        return $this->view->fetch();
		

		
		
	}
	
}