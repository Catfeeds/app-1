<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

class Buildhome extends Controller
{
    public function index()
    {
        //return \think\Response::create(\think\Url::build('/admin'), 'redirect');
		$model = Db::name("BuildHome");
		$data = $model->where('type','共建家园')->where('release','超级管理员')->select();
		//$data = $model->select();
		$this->view->assign("data", $data);
		return $this->fetch();
    }

}
