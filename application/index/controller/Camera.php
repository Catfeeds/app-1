<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;
use think\Session;

class Camera extends Controller
{
    public function index()
    {
        
		return $this->fetch();
        
    }

     public function seealbum()
    {
        $id = $this->request->param('id');
        $info = Db::name("Album")->where('id',$id)->where('status',1)->where('isdelete',0)->find();

		$this->view->assign("info", $info);
		return $this->fetch();
        
    }
}
