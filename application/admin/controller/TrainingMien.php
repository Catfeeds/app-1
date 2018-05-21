<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;

use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;
use think\Request;
use think\Session;

//培训学校---校园风采
class TrainingMien extends Controller
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
            $map['schoolAccount'] = $info1['account'];
        }
        else if($info['type'] ==2)
        {
            $info1 = Db::name("AdminUser")->field('realname,account')->where("id", UID)->find();

//            $map['release']=["like", "%" . $info1['realname']. "%"];
            $account = $info1['account'];
            $schoolID = substr($account,0,5);//截取当前用户账号的前5位---即学区号 ,如C0398
            $map['schoolAccount'] = ["like", "%" . $schoolID. "%"];


        }
        else if($info['type'] ==3)
        {
            $info1 = Db::name("AdminUser")->field('realname,account')->where("id", UID)->find();
            /*
            $data = Db::name("StudentManagement")->field('name')->where('name',$info1['realname'])->select();
            */
//            $map['release']=["like", "%" . $info1['realname']. "%"];
            $account = $info1['account'];
            $schoolID = substr($account,0,5);//截取当前用户账号的前5位---即学区号 ,如C0398
            $map['schoolAccount'] = ["like", "%" . $schoolID. "%"];

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

        if($this->request->isPost()) {

            $id = $this->request->param("id");
            $data['title'] = $this->request->param("title");
            $data['release_time'] = $this->request->param("release_time");
            $data['schoolName'] = $this->request->param("schoolName");
            //获取选择的学区名对应的账号
            $schoolInfo = Db::name('SchoolManagement')->where('schoolName',$data['schoolName'])->where('status',1)->where('isdelete',0)->find();
            $data['schoolAccount'] = $schoolInfo['schoolAccount'];

            //$data['className'] = $this->request->param("className");
            $data['content'] = htmlspecialchars_decode(html_entity_decode($this->request->param("content")));
            $data['label'] = $this->request->param("label");


            //获取当前上传的图片路径
            $path = $this->request->param('path'); //字符串，形如    /uploads/file/20180314/2cf5asd12dscsfdsfa01b66d3c.jpg
            if($path){
                $data['image'] = $path;
            }


            $admininfo = Db::name("AdminUser")->field('realname')->where("id", UID)->find();
            $data['release'] = $admininfo['realname'];

            Db::name("TrainingMien")-> where('id',$id)->update($data);


            return ajax_return_adv('编辑成功');

        } else {

            $id = $this->request->param('id');
            if (!$id){
                throw new HttpException(404, "缺少参数ID");
            }
            $vo = $this->getModel($controller)->find($id);
            if (!$vo) {
                throw new HttpException(404, '该记录不存在');
            }


            //以下是根据当前登录用户找出相应的初中校区名称
            $info = Db::name("AdminUser")->where("id", UID)->where('isdelete','0')->where('status',1)->find();
            $type = $info['type'];
            //若当前登录用户是超级管理员
            if($type == 0){
                //获取所有初中学区的名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where(array('schoolAccount'=>array('like','%F%')))->where('isdelete',0)->select();
            }
            //若当前登录用户是校区
            if($type == 1){
                //获取当前校区的名称-----二维数组
                $schoolName = Db::name("SchoolManagement")->field('schoolName')->where('schoolAccount',$info['account'])->where('isdelete','0')->select();
            }
            //若是教师
            if($type == 2){
                $schoolID = substr($info['account'],0,5);//获取该教师所在的学区编号
                //获取当前教师所在的校区的名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();
            }
            //若是学生
            if($type == 3){
                $schoolID = substr($info['account'],0,5);//获取该学生所在的学区编号
                //获取当前学生所在的校区名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();
            }

            $this->view->assign('schoolName',$schoolName);  //二维数组



            $this->view->assign("vo", $vo);


            return $this->view->fetch();
        }
    }

    /**
     * 添加
     * @return mixed
     */
    public function fadd()
    {
        //$controller = $this->request->controller();

        if ($this->request->isPost()) {


            $data['title'] = $this->request->param("title");
            $data['release_time'] = $this->request->param("release_time");
            $data['schoolName'] = $this->request->param("schoolName");//获取选择的学区名
            //获取选择的学区名对应的账号
            $schoolInfo = Db::name('SchoolManagement')->where('schoolName',$data['schoolName'])->where('status',1)->where('isdelete',0)->find();
            $data['schoolAccount'] = $schoolInfo['schoolAccount'];

            //$data['className'] = $this->request->param("className");
            $data['content'] = htmlspecialchars_decode(html_entity_decode($this->request->param("content")));
            $data['label'] = $this->request->param("label");


            //获取当前上传的图片路径
            $path = $this->request->param('path'); //字符串，形如    /uploads/file/20180314/3bf5a01b66d3c.jpg
            if(!$path){
                $data['image'] = '/uploads/file/033fc20efb497505073ed7ff96f84088.png';  //默认封面
            }else{
                $data['image'] = $path;
            }


            $admininfo = Db::name("AdminUser")->field('realname')->where("id", UID)->find();
            $data['release'] = $admininfo['realname'];

            Db::name("TrainingMien")->insert($data);


            return ajax_return_adv('添加成功');

        }else{


            //以下是根据当前登录用户找出相应的培训学校的名称
            $info = Db::name("AdminUser")->where("id", UID)->where('isdelete','0')->where('status',1)->find();
            $type = $info['type'];
            //若当前登录用户是超级管理员
            if($type == 0){
                //获取所有培训学校的名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where(array('schoolAccount'=>array('like','%F%')))->where('isdelete',0)->select();
            }
            //若当前登录用户是校区
            if($type == 1){
                //获取当前培训学校的名称-----二维数组
                $schoolName = Db::name("SchoolManagement")->field('schoolName')->where('schoolAccount',$info['account'])->where('isdelete','0')->select();
            }
            //若是教师
            if($type == 2){
                $schoolID = substr($info['account'],0,5);//获取该教师所在的学区编号
                //获取当前教师所在的校区的名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();
            }
            //若是学生
            if($type == 3){
                $schoolID = substr($info['account'],0,5);//获取该学生所在的学区编号
                //获取当前学生所在的校区名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();
            }

            $this->view->assign('schoolName',$schoolName);  //二维数组


            $vo =['content' => ''];
            $this->view->assign("vo", $vo);


            // 添加
            return $this->view->fetch(isset($this->template) ? $this->template : 'fsedit');
        }
    }

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
