<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

class Copyright extends Controller
{
    public function index()
    {
        //return \think\Response::create(\think\Url::build('/admin'), 'redirect');


		if(!session('id')){
;
			$this->redirect('Parentlogin/index');//直接跳转到登录页面
		}
		else{

			return $this->fetch();
		}


    }
}
