<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

//明星榜
class Classstar extends Controller
{
    public function index()
    {
        //return \think\Response::create(\think\Url::build('/admin'), 'redirect');
        $phone_account = $this->request->param('phone_account');
        $type = $this->request->param('type');
		return $this->fetch();
        // if(empty($type)){//若是家长

            // return $this->fetch();
        // }else{//若是老师

        // }

    }
}
