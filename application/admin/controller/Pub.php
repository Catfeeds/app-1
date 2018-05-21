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
// 公开不授权控制器
//-------------------------

namespace app\admin\controller;

\think\Loader::import('controller/Jump', TRAIT_PATH, EXT);

use think\Controller;
use think\Loader;
use think\Session;
use think\Db;
use think\Config;
use think\Exception;
use think\View;
use think\Request;
use think\Cookie;
use app\admin\controller\SendMsg;

class Pub
{
    use \traits\controller\Jump;

    // 视图类实例
    protected $view;
    // Request实例
    protected $request;

    public function __construct()
    {
        if (null === $this->view) {
            $this->view = View::instance(Config::get('template'), Config::get('view_replace_str'));
        }
        if (null === $this->request) {
            $this->request = Request::instance();
        }

        // 用户ID
        defined('UID') or define('UID', Session::get(Config::get('rbac.user_auth_key')));
    }

    /**
     * 检查用户是否登录
     */
    protected function checkUser()
    {
        if (null === UID) {
            if ($this->request->isAjax()) {
				
                ajax_return_adv_error("登录超时，请先登陆", "", "", "current", url("loginFrame"))->send();
            } else {
				
                $this->error("登录超时，请先登录", Config::get('rbac.user_auth_gateway'));
            }
        }

        return true;
    }


    /**
     * 用户登录页面
     * @return mixed
     */
    public function login()
    {
        if (Session::has(Config::get('rbac.user_auth_key'))) {
            $this->redirect('index/index');
        } else {
            return $this->view->fetch();
        }
    }
    
    


    //--------------------------------------------------------------------
    /*
     * 完善企业信息页面
     */
    public function evpi()
    {

        if($this->request->isPost() || $this->request->param('phone')!=''){
            $phone = $this->request->param('phone'); //企业注册时的手机号
            $this->view->assign('phone',$phone);
            return $this->view->fetch();
        }else {
            throw new Exception("非法请求");
        }


    }


    /*
     * 获取---完善的企业信息
     * 进行更新,同时保存该企业信息
     *
     */
    public function updateInfo()
    {
        if($this->request->isPost()){
            $companyAccount = trim($this->request->param('companyAccount')); //公司账号
            $number = trim($this->request->param('number')); //联系电话
            $regNumber = trim($this->request->param('reg'));//注册号
            $companyName = trim($this->request->param('companyName')); //公司名称
            $corporation = trim($this->request->param('corporation'));//法人
            $province = trim($this->request->param('province')); //省
            $city = trim($this->request->param('city'));//市
            $address = trim($this->request->param('address'));//详细地址

            //更新企业信息
            $data = array(
                'schoolName' => $companyName,
                'one_level' => $province,
                'two_level' => $city,
                'schoolID' => substr($companyAccount,0,5),
                'schoolAccount' => $companyAccount,
                'regNumber' => $regNumber,
                'corporation' => $corporation,
                'address' => $address,
                'isperfect' => 1
            );
            $info = Db::name('SchoolManagement')->where('number',$number)->update($data);
            $data2 = array(
                'account' => $companyAccount,
                'realname' => $companyName,
            );
            Db::name('AdminUser')->where('mobile',$number)->where('type',1)->update($data2);

return ajax_return_adv_error($info);

            //------------------------------------------------------------------------------------
            //保存当前用户信息
            $auth_info = Db::name('AdminUser')->where('type',1)->where('mobile',$number)->find(); //当前企业信息
            // 生成session信息
            Session::set(Config::get('rbac.user_auth_key'), $auth_info['id']);
            Session::set('user_name', $auth_info['account']);
            Session::set('real_name', $auth_info['realname']);

            Session::set('type', $auth_info['type']);//记录当前登录用户的角色类型
            $schoolID = substr($auth_info['account'],0,5);
            $schoolInfo = Db::name('SchoolManagement')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->find();
            Session::set('schoolName', $schoolInfo['schoolName']); //获取当前用户所在的校区名

            Session::set('last_login_ip', $auth_info['last_login_ip']);
            Session::set('last_login_time', $auth_info['last_login_time']);

            // 超级管理员标记
//            if ($auth_info['id'] == 1) {
//                Session::set(Config::get('rbac.admin_auth_key'), true);
//            }

            // 保存登录信息
            $update['last_login_time'] = time();
            $update['login_count'] = ['exp', 'login_count+1'];
            $update['last_login_ip'] = $this->request->ip();
            Db::name("AdminUser")->where('id', $auth_info['id'])->update($update);

            // 记录登录日志
            $log['uid'] = $auth_info['id'];
            $log['login_ip'] = $this->request->ip();
            $log['login_location'] = implode(" ", \Ip::find($log['login_ip']));
            $log['login_browser'] = \Agent::getBroswer();
            $log['login_os'] = \Agent::getOs();
            Db::name("LoginLog")->insert($log);

            // 缓存访问权限
            \Rbac::saveAccessList();



            //-------------------------
             //给该公司赋角色
            //找到该公司的id
            $userInfo = Db::name('AdminUser')->where('type',1)->where('mobile',$number)->where('isdelete',0)->find();
            $user_id = $userInfo['id'];
            //获取角色id
            $roleInfo = Db::name('AdminRole')->where('type','公司')->where('isdelete',0)->find();
            $role_id = $roleInfo['id'];
            $array = array(
                'role_id' => $role_id,
                'user_id' => $user_id
            );
            Db::name('AdminRoleUser')->insert($array); //给公司赋予角色


            $this->redirect('index/index'); //跳转到首页

        }else{
            throw new Exception("非法请求");
        }

    }



    /**
     * 小窗口登录页面
     * @return mixed
     */
    public function loginFrame()
    {
        return $this->view->fetch();
    }

    /**
     * 首页
     */
    public function index()
    {
        // 如果通过认证跳转到首页

        $this->redirect("index/index");
    }

    /**
     * 用户登出
     */
    public function logout()
    {
        if (UID) {
            Session::clear();
            $this->success('退出成功！', Config::get('rbac.user_auth_gateway'));
        } else {
            $this->error('已经退出！', Config::get('rbac.user_auth_gateway'));
        }
    }



    /**
     * 登录检测
     * @return \think\response\Json
     */
    public function checkLogin()
    {
        if ($this->request->isAjax() && $this->request->isPost()) {
            $data = $this->request->post();
            $validate = Loader::validate('Pub');
            if (!$validate->scene('login')->check($data)) {
                return ajax_return_adv_error($validate->getError());
            }


            $phone_preg = '/^(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/';
            if(preg_match($phone_preg,$data['account'])){
                $map['mobile'] = $data['account']; //当前手机号
                $map['status'] = 1;
                $auth_info = \Rbac::authenticate($map);   //当前登录用户的信息，一维数组
            }else{
                $map['account'] = $data['account'];  //当前账号
                $map['status'] = 1;
                $auth_info = \Rbac::authenticate($map);
            }



            // 使用用户名、密码和状态的方式进行认证
            if (null === $auth_info) {
                return ajax_return_adv_error('帐号不存在或已禁用！');  //这里验证账号是否正确
            } else {
                if ($auth_info['password'] != password_hash_tp($data['password'])) {
                    return ajax_return_adv_error('密码错误！');
                }

                //以下是判断如果是企业登录的话，检测该企业信息是否完善
                $type = $auth_info['type'];//当前用户类型
                if($type == 1){
                    $mobile = $auth_info['mobile'];
                    $info = Db::name('SchoolManagement')->where('number',$mobile)->find();
                    //若当前企业没有完善信息
                    if($info['isperfect'] == 0){
                        return ajax_return_adv_error('请先完善企业信息');

                    }
                }


                // 生成session信息
                Session::set(Config::get('rbac.user_auth_key'), $auth_info['id']); //当前登录用户id
                Session::set('user_name', $auth_info['account']);
                Session::set('real_name', $auth_info['realname']);

                Session::set('type', $auth_info['type']);//记录当前登录用户的角色类型
                $schoolID = substr($auth_info['account'],0,5);
                $schoolInfo = Db::name('SchoolManagement')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->find();
                Session::set('schoolName', $schoolInfo['schoolName']); //获取当前用户所在的校区名

                Session::set('last_login_ip', $auth_info['last_login_ip']);
                Session::set('last_login_time', $auth_info['last_login_time']);

                // 超级管理员标记
                if ($auth_info['id'] == 1) {
                    Session::set(Config::get('rbac.admin_auth_key'), true);
                }

                // 保存登录信息
                $update['last_login_time'] = time();
                $update['login_count'] = ['exp', 'login_count+1'];
                $update['last_login_ip'] = $this->request->ip();
                Db::name("AdminUser")->where('id', $auth_info['id'])->update($update);

                // 记录登录日志
                $log['uid'] = $auth_info['id'];
                $log['login_ip'] = $this->request->ip();
                $log['login_location'] = implode(" ", \Ip::find($log['login_ip']));
                $log['login_browser'] = \Agent::getBroswer();
                $log['login_os'] = \Agent::getOs();
                Db::name("LoginLog")->insert($log);

                // 缓存访问权限
                \Rbac::saveAccessList();

                return ajax_return_adv('登录成功！', '');
            }
        } else {
            throw new Exception("非法请求");
        }
    }


    /*
     * 企业注册页面
     */
    public function register()
    {
        return $this->view->fetch();
    }

    /*
     * 检测提交过来的企业注册信息
     * 再将其插入到tp_school_management表和tp_admin_user表中
     */
    public function checkRegister()
    {
        if($this->request->isPost()){
            $phone = trim($this->request->param('phone'));//电话号码----注意检测该手机号码是否注册过
            $pwd = trim($this->request->param('pwd'));//密码
            $rpwd = trim($this->request->param('rpwd'));//确认密码
            $code = trim($this->request->param('code')); //验证码
            $identified = $this->request->param('identified'); //是否同意服务条款


            $phone_preg = '/^(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/'; //匹配手机号

            //从公司管理表tp_school_management表中判断此手机号是否注册过
            $phoneInfo = Db::name('SchoolManagement')->where('isdelete',0)->where('number',$phone)->find();

            $password_preg = '/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/';//匹配 由数字和字母组成，并且要同时含有数字和字母，且长度要在6-16位之间。

            if($phone == ''){
                $this->json(0,'请输入手机号');
            }
            if($pwd == ''){
                $this->json(1,'请输入密码');
            }
            if($rpwd == ''){
                $this->json(2,'请输入确认密码');
            }
            if($code == ''){
                $this->json(3,'请输入验证码');
            }
            if($identified == '0'){
                $this->json(4,'请同意派职网的服务条款');
            }

            if(!preg_match($phone_preg,$phone)){
                $this->json(5,'手机号码不存在');
            }elseif($phoneInfo){
                $this->json(6,'此手机号码已注册过');
            }

            if(!preg_match($password_preg,$pwd)){
                $this->json(7,'密码必须是6-16位混合字符');
            }

            if($rpwd != $pwd){
                $this->json(8,'两次密码不一致');
            }


            if(Cookie::get('code') == ''){
                $this->json(9,'验证码不存在或已失效');
            }
            if($code != Cookie::get('code')){
                $this->json(10,'验证码输入错误');
            }else{
                //信息都正确的情况下
                $data = array(
                    'number' => $phone,
                    'passWord' => md5($pwd)
                );
                $data2 = array(
                    'mobile' => $phone,
                    'password' => md5($pwd),
                    'type' => 1,
                    'create_time' => time(), //当前时间
                );
                Db::name('SchoolManagement')->insert($data);
                Db::name('AdminUser')->insert($data2);
                $this->json(11,'注册成功');

            }


        }


    }





    //-----------------------------------------------------------------------
    //验证传过来的手机号，正确则弹出发送成功并发送验证码，不正确弹出错误提示
    public function send()
    {

        if($this->request->isPost()){
            $phone = trim($this->request->param('phone')); //手机号
            $preg = '/^(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/';
            //判断手机号是否正确
            if($phone == ''){
                $this->json(0, '请输入手机号');
            }elseif(!preg_match($preg,$phone)){
                $this->json(1, '手机号码不存在');
            }else{

                //短信接口地址
                $mobile_code = $this->random(4,1); //生成的随机4位验证码

                 //$sendCode = new SendMsg();

                 $result = sendCode($phone,$mobile_code);

                if ($result) {
                    //$_SESSION['mobile'] = $mobile;    //将手机号放入session中---不需要


                    //Session::set('mobile_code',$mobile_code); //将验证码放入session中

                    //Session::set('time',time());

                    //将生成的验证码放入cookie中，保存时间60s
                    Cookie::set('code',$mobile_code,60); //cookie名为code
                    $this->json(2, '发送成功');

                }else {
                    $this->json(3, '发送失败');
                }

            }


        }

    }


    public function Post($curlPost,$url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }

    //将 xml数据转换为数组格式。
    public function xml_to_array($xml){
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if(preg_match_all($reg, $xml, $matches)){
            $count = count($matches[0]);
            for($i = 0; $i < $count; $i++){
                $subxml= $matches[2][$i];
                $key = $matches[1][$i];
                if(preg_match( $reg, $subxml )){
                    $arr[$key] = $this->xml_to_array( $subxml );
                }else{
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }

    //random() 函数返回随机整数。
    public function random($length = 6 , $numeric = 0) {
        PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
        if($numeric) {
            $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
            $max = strlen($chars) - 1;
            for($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
            }
        }
        return $hash;
    }



    public static function json($code, $msg = '', $data = array())
    {

        if(!is_numeric($code)) {
            return '';
        }
        if(empty($data)){
            $result = array(
                'code' => $code,
                'msg' => $msg,
            );
        }else{
            $result = array(
                'code' => $code,
                'msg' => $msg,
                'data' => $data,
            );
        }
        echo json_encode($result);exit;
    }
    //-------------------------------------------------------------------------------------------



    /**
     * 修改密码
     */
    public function password()
    {
        $this->checkUser();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 数据校验
            $validate = Loader::validate('Pub');
            if (!$validate->scene('password')->check($data)) {
                return ajax_return_adv_error($validate->getError());
            }

            // 查询旧密码进行比对
            $info = Db::name("AdminUser")->where("id", UID)->field("password")->find();
            if ($info['password'] != password_hash_tp($data['oldpassword'])) {
                return ajax_return_adv_error("旧密码错误");
            }

            // 写入新密码
            if (false === Loader::model('AdminUser')->updatePassword(UID, $data['password'])) {
                return ajax_return_adv_error("密码修改失败");
            }

            return ajax_return_adv("密码修改成功", '');
        } else {
            return $this->view->fetch();
        }
    }



    /**
     * 查看用户信息|修改资料
     */
    public function profile()
    {
        $this->checkUser();
        if ($this->request->isPost()) {
            // 修改资料
            $data = $this->request->only(['realname', 'email', 'mobile', 'remark'], 'post');
            if (Db::name("AdminUser")->where("id", UID)->update($data) === false) {
                return ajax_return_adv_error("信息修改失败");
            }

            return ajax_return_adv("信息修改成功", '');
        } else {
            // 查看用户信息
            $vo = Db::name("AdminUser")->field('realname,email,mobile,remark')->where("id", UID)->find();
            $this->view->assign('vo', $vo);

            return $this->view->fetch();
        }
    }

    /**
     * 查看信箱消息
     */
    public function message()
    {
        //这里查询当前学区的所有留言记录
        $info = Db::name('AdminUser')->where('id',UID)->where('status',1)->where('isdelete',0)->find();
        $type = $info['type'];
        //学区账号登录
        if($type == 1){
            $schoolName = $info['realname'];
            $data = array('isread'=>1);
            Db::name('mailbox')->where('schoolName',$schoolName)->where('isread',0)->where('status',1)->where('isdelete',0)->update($data);//将关于本校区未读消息变为已读
            //查看该校区的所有消息
            $result = Db::name('mailbox')->where('schoolName',$schoolName)->order('release_time desc')->where('status',1)->where('isdelete',0)->select();
        }
        //超级管理员登录
        if($type == 0){
            $data = array('isread'=>1);
            Db::name('mailbox')->where('isread',0)->where('status',1)->where('isdelete',0)->update($data);
            $result = Db::name('mailbox')->order('release_time desc')->where('status',1)->where('isdelete',0)->select();//查看所有留言
        }
        //教师登录
        if($type == 2){
            $result = null;
        }
        //学生登录
        if($type == 3){
            $result = null;
        }

        $this->view->assign('count',count($result));
        $this->view->assign('result',$result);
        return $this->view->fetch();
    }


    /**
     * 查看请假记录
     */
    public function info()
    {
        //这里查询当前学区的所有请假记录
        $info = Db::name('AdminUser')->where('id',UID)->where('status',1)->where('isdelete',0)->find();
        $type = $info['type'];
        //学区账号登录
        if($type == 1){
            $schoolName = $info['realname'];
            $data = array('isread'=>1);
            Db::name('leave')->where('schoolName',$schoolName)->where('isread',0)->where('status',1)->where('isdelete',0)->update($data);//将关于本校区未读消息变为已读
            //查看该校区的所有的请假消息----含已读和未读、已审和未审
            $result = Db::name('leave')->where('schoolName',$schoolName)->order('release_time desc')->where('status',1)->where('isdelete',0)->select();
        }
        //超级管理员登录
        if($type == 0){
            $data = array('isread'=>1);
            Db::name('leave')->where('isread',0)->where('status',1)->where('isdelete',0)->update($data);
            $result = Db::name('leave')->order('release_time desc')->where('status',1)->where('isdelete',0)->select();//查看所有留言
        }

        //教师登录
        if($type == 2){
            $result = null;
        }
        //学生登录
        if($type == 3){
            $result = null;
        }

        $this->view->assign('count',count($result));
        $this->view->assign('result',$result);
        return $this->view->fetch();
    }


    /**
     * 获取指定的id，并更新tp_leave表中的checkinfo字段
     */
    public function getId()
    {
        if(request()->isAjax()){
            $id =  $this->request->param('id');
            $data1 = array('checkinfo' => 1);
            $data2 = array('checkinfo' => 0);
            $result = Db::name('leave')->where('id',$id)->where('status',1)->where('isdelete',0)->find();
            $checkinfo = $result['checkinfo'];
            if($checkinfo == 0)
            {
                $bool = Db::name('leave')->where('id',$id)->where('status',1)->where('isdelete',0)->update($data1);
                if($bool){
                    echo 'approve';
                }
            }
            if($checkinfo == 1)
            {
                $bool = Db::name('leave')->where('id',$id)->where('status',1)->where('isdelete',0)->update($data2);
                if($bool){
                    echo 'disapprove';
                }
            }

        }
    }



}
