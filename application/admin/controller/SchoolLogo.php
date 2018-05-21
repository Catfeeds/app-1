<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;
use think\Session;

//校区logo
class SchoolLogo extends Controller
{
    use \app\admin\traits\controller\Controller;
    // 方法黑名单
    protected static $blacklist = [];

    protected function filter(&$map)
    {
        if ($this->request->param("title")) {
            $map['title'] = ["like", "%" . $this->request->param("title") . "%"];
        }
        if ($this->request->param("type")) {
            $map['type'] = ["like", "%" . $this->request->param("type") . "%"];
        }

        $info = Db::name("AdminUser")->field('type')->where("id", UID)->find();
        if($info['type'] ==1)
        {
            $info1 = Db::name("AdminUser")->field('realname,account')->where("id", UID)->find();
//            $map['release']=["like", "%" . $info1['realname']. "%"];
            $schoolAccount = $info1['account'];
            $map['schoolAccount']= $schoolAccount;
        }
        else if($info['type'] ==2)
        {
            $info1 = Db::name("AdminUser")->field('realname,account')->where("id", UID)->find();
            /*
            $data = Db::name("EmployeeManagement")->field('name')->where('name',$info1['realname'])->select();
            */
//            $map['release']=["like", "%" . $info1['realname']. "%"];

            $schoolID = substr($info1['account'],0,5);
            $map['schoolAccount']=["like", "%" . $schoolID. "%"];

        }
        else if($info['type'] ==3)
        {
            $info1 = Db::name("AdminUser")->field('realname')->where("id", UID)->find();
            /*
            $data = Db::name("StudentManagement")->field('name')->where('name',$info1['realname'])->select();
            */
//            $map['release']=["like", "%" . $info1['realname']. "%"];
            $schoolID = substr($info1['account'],0,5);
            $map['schoolAccount']=["like", "%" . $schoolID. "%"];
        }
    }

    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        $model = $this->getModel();

        // 列表过滤器，生成查询Map对象
        $map = $this->search($model, [$this->fieldIsDelete => $this::$isdelete]);
        // 特殊过滤器，后缀是方法名的
        $actionFilter = 'filter' . $this->request->action();
        if (method_exists($this, $actionFilter)) {
            $this->$actionFilter($map);
        }

        // 自定义过滤器
        if (method_exists($this, 'filter')) {
            $this->filter($map);
        }
        $model = $this->getModel();
        $this->datalist($model, $map);

        return $this->view->fetch();
    }


    /**
     * 编辑
     * @return mixed
     */
    public function fsedit()
    {
        $controller = $this->request->controller();
        //获取编辑后传过来的数据
        if ($this->request->isAjax()) {

            $id = $this->request->param("id"); //tp_school_logo表中当前记录的id

            $schoolName = $this->request->param("schoolName");
            $schoolInfo = Db::name("SchoolManagement")->field('schoolAccount')->where('schoolName',$schoolName)->find();
            $schoolAccount = $schoolInfo['schoolAccount']; //获取更新后的校区账号


            //获取当前上传的图片路径
            $path = $this->request->param('path'); //字符串，形如    /uploads/file/20180314/2cf5asd12dscsfdsfa01b66d3c.jpg
            if($path){
                $data = array(
                    'schoolName' => $schoolName,
                    'schoolAccount' => $schoolAccount,
                    'logo' => $path
                );
            }else{
                $data = array(
                    'schoolName' => $schoolName,
                    'schoolAccount' => $schoolAccount
                );
            }

            Db::name("SchoolLogo")->where('id',$id)->update($data);


            return ajax_return_adv('编辑成功');

        }else {
            // 编辑
            $id = $this->request->param('id');
            if (!$id) {
                throw new HttpException(404, "缺少参数ID");
            }
            $vo = $this->getModel($controller)->find($id);
            if (!$vo) {
                throw new HttpException(404, '该记录不存在');
            }

            $this->view->assign("vo", $vo);


            //判断当前登录用户身份
            $user_info = Db::name("AdminUser")->where("id", UID)->find();
            $type = $user_info['type'];
            //超级管理员
            if($type == 0){
                //查找所有校区名
                $all_schoolName = Db::name('SchoolManagement')->field('schoolName')->where('status',1)->where('isdelete',0)->select();
            }
            //校区登录
            if($type == 1){
                $schoolAccount = $user_info['account'];
                //查找当前校区名
                $all_schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->select();//查找当前校区名
            }
            //教师登录
            if($type == 2){
                $schoolID = substr($user_info['account'],0,5);
                //查找该教师所属校区名
                $all_schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->select();
            }
            //学生登录
            if($type == 3){
                $schoolID = substr($user_info['account'],0,5);
                //查找该学生所属校区名
                $all_schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->select();
            }
            $this->view->assign('all_schoolName',$all_schoolName);


            return $this->view->fetch();
        }
    }


    /**
     * 添加
     * @return mixed
     */
    public function fadd()
    {
        $controller = $this->request->controller();
        //获取传过来的添加数据
        if ($this->request->isAjax()) {


            $schoolName = $this->request->param("schoolName"); //获取校区名

            $schoolInfo = Db::name("SchoolManagement")->field('schoolAccount')->where('schoolName',$schoolName)->find();
            $schoolAccount = $schoolInfo['schoolAccount'];//获取校区账号


            //获取当前上传的图片路径
            $path = $this->request->param('path'); //字符串，形如    /uploads/file/20180314/3bf5a01b66d3c.jpg
            if(!$path){
                $logo = '/uploads/file/033fc20efb497505073ed7ff96f84088.png';  //默认logo
                $data = array(
                    'schoolName' => $schoolName,
                    'schoolAccount' => $schoolAccount,
                    'logo' => $logo, //默认校区logo
                );
            }else{
                $data = array(
                    'schoolName' => $schoolName,
                    'schoolAccount' => $schoolAccount,
                    'logo' => $path
                );
            }
            Db::name("SchoolLogo")->insert($data);


            return ajax_return_adv('添加成功');

        }else {
            $vo =['content' => ''];
            $this->view->assign("vo", $vo);


            //判断当前登录用户身份
            $user_info = Db::name("AdminUser")->where("id", UID)->find();
            $type = $user_info['type'];
            //超级管理员
            if($type == 0){
                //查找所有校区名
                $all_schoolName = Db::name('SchoolManagement')->field('schoolName')->where('status',1)->where('isdelete',0)->select();
            }
            //校区登录
            if($type == 1){
                $schoolAccount = $user_info['account'];
                //查找当前校区名
                $all_schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->select();//查找当前校区名
            }
            //教师登录
            if($type == 2){
                $schoolID = substr($user_info['account'],0,5);
                //查找该教师所属校区名
                $all_schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->select();
            }
            //学生登录
            if($type == 3){
                $schoolID = substr($user_info['account'],0,5);
                //查找该学生所属校区名
                $all_schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->select();
            }
            $this->view->assign('all_schoolName',$all_schoolName);



            // 添加
            return $this->view->fetch(isset($this->template) ? $this->template : 'fsedit');
        }
    }


    // 弹出上传文件页面
//    public function upindex()
//    {
//
//        return $this->view->fetch();
//    }


    /**
     * 只上传一张图片
     */
    public function upload()
    {

        $file = request()->file("myfile");
        if(!empty($file)){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads'. DS . 'file');

            if($info){

                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                echo '/uploads/file/'.str_replace('\\','/',$info->getSaveName());

            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }

        }

    }



}
