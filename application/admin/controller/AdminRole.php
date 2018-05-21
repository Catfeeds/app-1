<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

//------------------------
// 角色控制器
//-------------------------

namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Exception;
use think\Db;
use think\Loader;

class AdminRole extends Controller
{
    use \app\admin\traits\controller\Controller;

//    protected static $blacklist = ['recyclebin', 'delete', 'recycle', 'deleteforever', 'clear'];

    protected function filter(&$map)
    {
        if ($this->request->param('name')) {
            $map['name'] = ["like", "%" . $this->request->param('name') . "%"];
        }
    }






    /**
     * 用户列表
     */
    public function user()
    {
        $role_id = $this->request->param('id/d'); //当前admin_role表序号id
        //echo $role_id;
        //角色设置成功后-----开始分配角色
        if ($this->request->isPost()) {
            // 提交
            if (!$role_id) {
                return ajax_return_adv_error("缺少必要参数");
            }

            $db_role_user = Db::name("AdminRoleUser");
            //删除之前的角色绑定
            $db_role_user->where("role_id", $role_id)->delete();
            //写入新的角色绑定
            $data = $this->request->post();
            if (isset($data['user_id']) && !empty($data['user_id']) && is_array($data['user_id'])) {
                $insert_all = [];
                foreach ($data['user_id'] as $v) {
                    $insert_all[] = [
                        "role_id" => $role_id,
                        "user_id" => intval($v),
                    ];
                }
                $db_role_user->insertAll($insert_all);
            }
            return ajax_return_adv("分配角色成功", '');
        }else {
            // 编辑页-----展示用户列表
            if (!$role_id) {
                throw new Exception("缺少必要参数");
            }

            $info = Db::name("AdminUser")->field('type,realname,account')->where('isdelete','0')->where("id", UID)->find();//获取当前登录用户信息
            // 以管理员账号登录
            if($info['type'] == 0)
            {
                //选择了部门和班级
                if($this->request->param('division') != '' && $this->request->param('className') != ''){
                    $division = $this->request->param('division');//获取传过来的选择部门
                    $className = $this->request->param('className');//获取传过来的选择班级
                    $list_user = Db::name("EmployeeManagement")->alias('a')
                                ->where('divisionName',$division)
                                ->where('className',$className)
                                ->field('admin_user.id,admin_user.account,admin_user.realname,admin_user.type')
                                ->join('admin_user','admin_user.account=a.account')
                                ->where('a.status=1 AND a.id > 1')
                                ->select();
                }elseif($this->request->param('division') != '' && $this->request->param('className') == ''){
                    //选择了部门
                    $division = $this->request->param('division');//获取传过来的选择部门
                    $list_user = Db::name("EmployeeManagement")->alias('a')
                                ->where('divisionName',$division)
                                ->field('admin_user.id,admin_user.account,admin_user.realname,admin_user.type')
                                ->join('admin_user','admin_user.account=a.account')
                                ->where('a.status=1 AND a.id > 1')
                                ->select();

                }elseif($this->request->param('division') == '' && $this->request->param('className') != ''){
                    //选择了班级
                    $className = $this->request->param('className');//获取传过来的选择班级
                    $account = array();//用来存放账号
                    $employeeInfo = Db::name('EmployeeManagement')->field('account')->where('className',$className)->where('status',1)->where('isdelete',0)->select();
                    foreach($employeeInfo as $value){
                        $account[] = $value['account'];
                    }
                    $studentInfo = Db::name('StudentManagement')->field('account')->where('className',$className)->where('status',1)->where('isdelete',0)->select();
                    foreach($studentInfo as $value2){
                        $account[] = $value2['account'];
                    }
                    $list_user = array();
                    foreach($account as $value3){
                        $list_user[] = Db::name('AdminUser')->field('id,account,realname,type')->where('account',$value3)->where('status',1)->where('isdelete',0)->find();
                    }


                }else{
                    // 读取系统的用户列表
                    $list_user = Db::name("AdminUser")->field('id,account,realname,type')->where('status=1 AND id > 1')->select();
                }
                // 已授权权限
                $list_role_user = Db::name("AdminRoleUser")->where("role_id", $role_id)->select();


                //查询所有部门----去除重复部门名称
                $all_division = Db::name('DivisionManagement')->distinct(true)->field('divisionName')->where('status',1)->where('isdelete',0)->select();
                //查询所有班级--去除重复班级名称
                $all_class = Db::name('ClassManagement')->distinct(true)->field('class,className')->where('status',1)->where('isdelete',0)->select();


            }else{
                 //可能是校区登录，教师登录，学生登录
                $schoolID = substr($info['account'],0,5);//获取校区编号，如C0398

                //选择了部门和班级
                if($this->request->param('division') != '' && $this->request->param('className') != ''){
                    $division = $this->request->param('division');//获取传过来的选择部门
                    $className = $this->request->param('className');//获取传过来的选择班级
                    $list_user = Db::name("EmployeeManagement")->alias('a')
                        ->where('a.account','like', '%'.$schoolID.'%')
                        ->where('divisionName',$division)
                        ->where('className',$className)
                        ->field('admin_user.id,admin_user.account,admin_user.realname,admin_user.type')
                        ->join('admin_user','admin_user.account=a.account')
                        ->where('a.status=1 AND a.id > 1')
                        ->select();
                }elseif($this->request->param('division') != '' && $this->request->param('className') == ''){
                    //选择了部门
                    $division = $this->request->param('division');//获取传过来的选择部门
                    $list_user = Db::name("EmployeeManagement")->alias('a')
                        ->where('a.account','like', '%'.$schoolID.'%')
                        ->where('divisionName',$division)
                        ->field('admin_user.id,admin_user.account,admin_user.realname,admin_user.type')
                        ->join('admin_user','admin_user.account=a.account')
                        ->where('a.status=1 AND a.id > 1')
                        ->select();

                }elseif($this->request->param('division') == '' && $this->request->param('className') != ''){
                    //选择了班级
                    $className = $this->request->param('className');//获取传过来的选择班级
                    $account = array();//用来存放账号
                    $employeeInfo = Db::name('EmployeeManagement')->field('account')->where('account','like', '%'.$schoolID.'%')->where('className',$className)->where('status',1)->where('isdelete',0)->select();
                    foreach($employeeInfo as $value){
                        $account[] = $value['account'];
                    }
                    $studentInfo = Db::name('StudentManagement')->field('account')->where('account','like', '%'.$schoolID.'%')->where('className',$className)->where('status',1)->where('isdelete',0)->select();
                    foreach($studentInfo as $value2){
                        $account[] = $value2['account'];
                    }
                    $list_user = array();
                    foreach($account as $value3){
                        $list_user[] = Db::name('AdminUser')->field('id,account,realname,type')->where('account',$value3)->where('status',1)->where('isdelete',0)->find();
                    }
                }else{
                    // 读取系统的用户列表
                    $list_user = Db::name("AdminUser")->field('id,account,realname,type')->where('status=1 AND id > 1')
                        ->where('account','like', '%'.$schoolID.'%')->select();
                }

                // 已授权权限
                $list_role_user = Db::name("AdminRoleUser")->where("role_id", $role_id)->select();


                //查询当前登录用户所在校区下的所有部门
                $schoolInfo = Db::name('SchoolManagement')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->find();
                $all_division = Db::name('DivisionManagement')->field('divisionName')->where('schoolName',$schoolInfo['schoolName'])->where('status',1)->where('isdelete',0)->select();
                //查询当前登录用户所在校区下所有班级
                $all_class = Db::name('ClassManagement')->field('class,className')->where('schoolName',$schoolInfo['schoolName'])->where('status',1)->where('isdelete',0)->select();
            }
            $this->view->assign('all_division',$all_division);
            $this->view->assign('all_class',$all_class);


            $checks = filter_value($list_role_user, "user_id", true); //被选中的所有用户id 如56,61,62

            $this->view->assign('list', $list_user); //当前校区下所有的用户
            $this->view->assign('checks', $checks);


            $this->view->assign('role_id',$role_id);
            return $this->view->fetch();
        }


    }




    /**
     * 授权
     *
     */
    public function access()
    {
        $role_id = $this->request->param('id/d'); //tp_admin_role表的当前角色id
        if ($this->request->isPost()) {
            if (!$role_id) {
                return ajax_return_adv_error("缺少必要参数");
            }

            if (true !== $error = Loader::model('AdminAccess', 'logic')->insertAccess($role_id, $this->request->post())) {
                return ajax_return_adv_error($error);
            }
            return ajax_return_adv("权限分配成功", '');
        }else{
            if (!$role_id) {
                throw new Exception("缺少必要参数");
            }

            //开始展示树形图
            $tree = Loader::model('AdminRole','logic')->getAccessTree($role_id);

            $this->view->assign("tree", json_encode($tree));

            return $this->view->fetch();
        }
    }

}
