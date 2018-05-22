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
// 管理后台首页
//-------------------------

namespace app\admin\controller;

use app\admin\Controller;
use think\Loader;
use think\Session;
use think\Db;

class Index extends Controller
{

    public function index()
    {
        // 读取数据库模块列表生成菜单项
        $nodes = Loader::model('AdminNode', 'logic')->getMenu();

        // 节点转为树
        $tree_node = list_to_tree($nodes);

        // 显示菜单项
        $menu = [];
        $groups_id = [];
        foreach ($tree_node as $module) {
            if ($module['pid'] == 0 && strtoupper($module['name']) == strtoupper($this->request->module())) {
                if (isset($module['_child'])) {
                    foreach ($module['_child'] as $controller) {
                        $group_id = $controller['group_id'];
                        array_push($groups_id, $group_id);
                        $menu[$group_id][] = $controller;
                    }
                }
            }
        }

        // 获取授权节点分组信息
        $groups_id = array_unique($groups_id);
        if (!$groups_id) {
            exception("没有权限");
        }
        $groups = Db::name("AdminGroup")->where(['id' => ['in', $groups_id], 'status' => "1"])->order("sort asc,id asc")->field('id,name,icon')->select();

        $this->view->assign('groups', $groups);
        $this->view->assign('menu', $menu);


        //这里查询当前学区的所有留言记录
        $info = Db::name('AdminUser')->where('id',UID)->where('status',1)->where('isdelete',0)->find();
        $type = $info['type'];
        //学区账号登录
        if($type == 1){
            $schoolName = $info['realname'];
            //查询该学区下未读信箱记录
            $result = Db::name('mailbox')->where('schoolName',$schoolName)->where('isread',0)->where('status',1)->where('isdelete',0)->select();

            //查询该学区下未读的请假记录
            $result2 = Db::name('leave')->where('schoolName',$schoolName)->where('isread',0)->where('status',1)->where('isdelete',0)->select();
        }
        //超级管理员登录
        if($type == 0){
            $result = Db::name('mailbox')->where('isread',0)->where('status',1)->where('isdelete',0)->select();

            $result2 = Db::name('leave')->where('isread',0)->where('status',1)->where('isdelete',0)->select();

        }
		
		//老师登录
        if($type == 2){
            $result = '';
            $result2 = '';
        }
        //学生登录
        if($type == 3){
            $result = '';
            $result2 = '';
        }
		
        if(!$result){
            $this->view->assign('num','');
        }else{
            $this->view->assign('num',count($result));
        }

        if(!$result2){
            $this->view->assign('number','');
        }else{
            $this->view->assign('number',count($result2));
        }


        return $this->view->fetch();
    }

    /**
     * 欢迎页
     * @return mixed
     */
    public function welcome()
    {
        // 查询 ip 地址和登录地点
        if (Session::get('last_login_time')) {
            $last_login_ip = Session::get('last_login_ip');
            $last_login_loc = \Ip::find($last_login_ip);

            $this->view->assign("last_login_ip", $last_login_ip);
            $this->view->assign("last_login_loc", implode(" ", $last_login_loc));

        }
        $current_login_ip = $this->request->ip();
        $current_login_loc = \Ip::find($current_login_ip);

        $this->view->assign("current_login_ip", $current_login_ip);
        $this->view->assign("current_login_loc", implode(" ", $current_login_loc));

        // 查询个人信息
        $info = Db::name("AdminUser")->where("id", UID)->find();
        $this->view->assign("info", $info);

        
        //若是管理员
        if($info['type'] == 0){
            //今日用户注册
            $where['register_time'] = array(array('egt',date('Y-m-d 00:00:00',time())),array('elt',date('Y-m-d 00:00:00',strtotime("+1day"))));
            
            $userRegisterDay = DB::name('patriarch')->where($where)->count();

            //本月用户注册
            $where['register_time'] = array(array('egt',date('Y-m-1 00:00:00',time())),array('elt',date('Y-m-1 00:00:00',strtotime("+1month"))));

            $userRegisterMonth = DB::name('patriarch')->where($where)->count();
            //本季度用户注册
            $month = date('m',time());
            $season = ceil(date('n') /3); //获取月份的季度
            $where['register_time'] = array(array('egt',date('Y-m-01 00:00:00',mktime(0,0,0,($season - 1)*3 +1,1,date('Y')))),array('elt',date('Y-m-t 23:59:59',mktime(0,0,0,$season * 3,1,date('Y')))));

            $userRegisterSeason = DB::name('patriarch')->where($where)->count();

            $this->view->assign('userRegisterDay',$userRegisterDay);
            $this->view->assign('userRegisterMonth',$userRegisterMonth);
            $this->view->assign('userRegisterSeason',$userRegisterSeason);
//----------------------------------------------------------------------------------------------------------
            //今日注册企业
            $where1['school_reg_time'] = array(array('egt',date('Y-m-d 00:00:00',time())),array('elt',date('Y-m-d 00:00:00',strtotime("+1day"))));
            $schoolregDay = DB::name('SchoolManagement')->where($where1)->count();
            //本月用户注册
            $where1['school_reg_time'] = array(array('egt',date('Y-m-1 00:00:00',time())),array('elt',date('Y-m-1 00:00:00',strtotime("+1month"))));

            $schoolregMonth = DB::name('SchoolManagement')->where($where1)->count();
            //本季度用户注册
            $where1['school_reg_time'] = array(array('egt',date('Y-m-01 00:00:00',mktime(0,0,0,($season - 1)*3 +1,1,date('Y')))),array('elt',date('Y-m-t 23:59:59',mktime(0,0,0,$season * 3,1,date('Y')))));

            $schoolregSeason = DB::name('SchoolManagement')->where($where1)->count();

            $this->view->assign('schoolregDay',$schoolregDay);
            $this->view->assign('schoolregMonth',$schoolregMonth);
            $this->view->assign('schoolregSeason',$schoolregSeason);
//--------------------------------------------------------------------------------------------------------------
            //今日职位报名
            $where2['enroll_time'] = array(array('egt',date('Y-m-d 00:00:00',time())),array('elt',date('Y-m-d 00:00:00',strtotime("+1day"))));
            $enrollDay = DB::name('CompanyPositionEnroll')->where($where2)->count();
            //本月用户注册
            $where2['enroll_time'] = array(array('egt',date('Y-m-1 00:00:00',time())),array('elt',date('Y-m-1 00:00:00',strtotime("+1month"))));

            $enrollMonth = DB::name('CompanyPositionEnroll')->where($where2)->count();
            //本季度用户注册
            $where2['enroll_time'] = array(array('egt',date('Y-m-01 00:00:00',mktime(0,0,0,($season - 1)*3 +1,1,date('Y')))),array('elt',date('Y-m-t 23:59:59',mktime(0,0,0,$season * 3,1,date('Y')))));

            $enrollSeason = DB::name('CompanyPositionEnroll')->where($where2)->count();

            $this->view->assign('enrollDay',$enrollDay);
            $this->view->assign('enrollMonth',$enrollMonth);
            $this->view->assign('enrollSeason',$enrollSeason);
//-------------------------------------------------------------------------------------------------------------------
            //今日邀请职位
            $where3['enroll_time'] = array(array('egt',date('Y-m-d 00:00:00',time())),array('elt',date('Y-m-d 00:00:00',strtotime("+1day"))));
            $where['ishire'] = 1;
            $requestDay = DB::name('CompanyPositionEnroll')->where($where3)->count();
            //本月邀请职位
            $where3['enroll_time'] = array(array('egt',date('Y-m-1 00:00:00',time())),array('elt',date('Y-m-1 00:00:00',strtotime("+1month"))));

            $requestMonth = DB::name('CompanyPositionEnroll')->where($where3)->count();
            //本季度邀请职位
            $where3['enroll_time'] = array(array('egt',date('Y-m-01 00:00:00',mktime(0,0,0,($season - 1)*3 +1,1,date('Y')))),array('elt',date('Y-m-t 23:59:59',mktime(0,0,0,$season * 3,1,date('Y')))));

            $requestSeason = DB::name('CompanyPositionEnroll')->where($where3)->count();

            $this->view->assign('requestDay',$requestDay);
            $this->view->assign('requestMonth',$requestMonth);
            $this->view->assign('requestSeason',$requestSeason);
//-------------------------------------------------------------------------------------------------------------------
            //今日发布职位
            $where4['release_time'] = array(array('egt',date('Y-m-d 00:00:00',time())),array('elt',date('Y-m-d 00:00:00',strtotime("+1day"))));
            $releaseDay = DB::name('CompanyReleasePosition')->where($where4)->count();
            //本月发布职位
            $where4['release_time'] = array(array('egt',date('Y-m-1 00:00:00',time())),array('elt',date('Y-m-1 00:00:00',strtotime("+1month"))));

            $releaseMonth = DB::name('CompanyReleasePosition')->where($where4)->count();
            //本季度发布职位
            $where4['release_time'] = array(array('egt',date('Y-m-01 00:00:00',mktime(0,0,0,($season - 1)*3 +1,1,date('Y')))),array('elt',date('Y-m-t 23:59:59',mktime(0,0,0,$season * 3,1,date('Y')))));

            $releaseSeason = DB::name('CompanyReleasePosition')->where($where4)->count();

            $this->view->assign('releaseDay',$releaseDay);
            $this->view->assign('releaseMonth',$releaseMonth);
            $this->view->assign('releaseSeason',$releaseSeason);

            return $this->view->fetch();

        }
        
        return $this->view->fetch('welcome1');
        
        //----------------------------------------------------------------------------------------------------------


        
    }
}