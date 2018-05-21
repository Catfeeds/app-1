<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;
use think\Request;
use think\Session;

class Collect extends Controller
{
    public function index()
    {   
        if(!session('id')){
            $this->redirect('Parentlogin/index');
        }
        else{
            return $this->fetch();
        }
    }
}