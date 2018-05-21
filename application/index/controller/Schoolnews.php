<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

class Schoolnews extends Controller
{
    public function index()
    {

		$schoolAccount = $this->request->param('schoolAccount');
	  

    	$data = DB::name('news')->where('schoolAccount','=',$schoolAccount)->where('status',1)->where('isdelete',0)->order('release_time')->select();//
    	$info = Db::name('AllSchool')->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->find();//所有学校
                
        
		$this->view->assign("data", $data);
		$this->view->assign("title", $info['schoolName'].'校内新闻');
		return $this->fetch();

	    


    }

    public function detail(){

    	$id = $this->request->param('id');
    	$info = DB::name('news')->where('id','=',$id)->where('status',1)->where('isdelete',0)->find();//
        $this->view->assign("info", $info);
		return $this->fetch('details');

    }
	
}
