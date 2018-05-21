<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;

class UserManage extends Controller
{
    use \app\admin\traits\controller\Controller;
    // 方法黑名单
    protected static $blacklist = [];

    protected function filter(&$map)
    {
        if ($this->request->param("userName")) {
            $map['userName'] = ["like", "%" . $this->request->param("userName") . "%"];
        }
        if ($this->request->param("iphone")) {
            $map['iphone'] = ["like", "%" . $this->request->param("iphone") . "%"];
        }
        if ($this->request->param("address")) {
            $map['address'] = ["like", "%" . $this->request->param("address") . "%"];
        }
    }
}
