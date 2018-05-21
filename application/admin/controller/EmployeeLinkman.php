<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;

class EmployeeLinkman extends Controller
{
    use \app\admin\traits\controller\Controller;
    // 方法黑名单
    protected static $blacklist = [];

    protected function filter(&$map)
    {
        if ($this->request->param("employee_id")) {
            $map['employee_id'] = ["like", "%" . $this->request->param("employee_id") . "%"];
        }
        if ($this->request->param("name")) {
            $map['name'] = ["like", "%" . $this->request->param("name") . "%"];
        }
        if ($this->request->param("relation")) {
            $map['relation'] = ["like", "%" . $this->request->param("relation") . "%"];
        }
        if ($this->request->param("number")) {
            $map['number'] = ["like", "%" . $this->request->param("number") . "%"];
        }
    }
}
