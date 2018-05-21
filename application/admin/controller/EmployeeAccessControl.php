<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;

//员工门禁出入记录管理
class EmployeeAccessControl extends Controller
{
    use \app\admin\traits\controller\Controller;
    // 方法黑名单
    protected static $blacklist = [];

    protected function filter(&$map)
    {
        if ($this->request->param("name")) {
            $map['name'] = ["like", "%" . $this->request->param("name") . "%"];
        }
        if ($this->request->param("account")) {
            $map['account'] = ["like", "%" . $this->request->param("account") . "%"];
        }
        if ($this->request->param("divisionName")) {
            $map['divisionName'] = ["like", "%" . $this->request->param("divisionName") . "%"];
        }
    }
}
