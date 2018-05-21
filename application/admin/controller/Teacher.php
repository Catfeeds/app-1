<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

//幼儿园师资介绍
class Teacher extends Controller
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

        $info = Db::name("AdminUser")->where("id", UID)->find();
        if($info['type'] ==1)
        {

//          $map['release']=["like", "%" . $info1['realname']. "%"];
            $map['schoolAccount'] = $info['account'];
        }
        else if($info['type'] ==2)
        {

//          $map['release']=["like", "%" . $info1['realname']. "%"];
            $account = $info['account'];
            $schoolID = substr($account,0,5);//截取当前用户账号的前5位---即学区号 ,如C0398
            $map['schoolAccount'] = ["like", "%" . $schoolID. "%"];

        }
        else if($info['type'] ==3)
        {

//          $map['release']=["like", "%" . $info1['realname']. "%"];
            $account = $info['account'];
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

        if ($this->request->isPost()) {
            // 更新
            $id = $this->request->param('id');

            $admininfo = Db::name("AdminUser")->where("id", UID)->find();
            $data['release'] = $admininfo['realname']; //发布者姓名

            $data['schoolName'] = $this->request->param('schoolName'); //学校名称
            //获取选择的学区名对应的账号
            $schoolInfo = Db::name('SchoolManagement')->where('schoolName',$data['schoolName'])->where('status',1)->where('isdelete',0)->find();
            $data['schoolAccount'] = $schoolInfo['schoolAccount'];  //学区账号

            $data['title'] = $this->request->param('title');  //标题
            $data['release_time'] = $this->request->param('release_time');  //发表时间
            $data['content'] = htmlspecialchars_decode(html_entity_decode($this->request->param("content"))); //内容
            $data['type'] = $this->request->param('type');  //类型

            $data['label'] = $this->request->param('label');  //标签


            Db::name('teacher')->where('id',$id)->update($data);




            return ajax_return_adv("编辑成功");
        } else {
            // 编辑
            $id = $this->request->param('id');
            if (!$id) {
                throw new HttpException(404, "缺少参数ID");
            }
            $vo = $this->getModel($controller)->find($id);
            if (!$vo) {
                throw new HttpException(404, '该记录不存在');
            }


            //以下是根据当前登录用户找出相应的幼儿园校区名称
            $info = Db::name("AdminUser")->where("id", UID)->where('isdelete','0')->where('status',1)->find();
            $type = $info['type'];
            //若当前登录用户是超级管理员
            if($type == 0){
                //获取所有幼儿园的名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where(array('schoolAccount'=>array('like','%A%')))->where('isdelete',0)->select();
            }
            //若当前登录用户是校区
            if($type == 1){
                //获取当前幼儿园名称-----二维数组
                $schoolName = Db::name("SchoolManagement")->field('schoolName')->where('schoolAccount',$info['account'])->where('isdelete','0')->select();
            }
            //若是教师
            if($type == 2){
                $schoolID = substr($info['account'],0,5);//获取该教师所在的学区编号
                //获取当前教师所在的幼儿园名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();
            }
            //若是学生
            if($type == 3){
                $schoolID = substr($info['account'],0,5);//获取该学生所在的学区编号
                //获取当前学生所在的幼儿园名称-----二维数组
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
            // 插入

            $admininfo = Db::name("AdminUser")->where("id", UID)->find();
            $data['release'] = $admininfo['realname']; //发布者姓名

            $data['schoolName'] = $this->request->param('schoolName'); //学校名称
            //获取选择的学区名对应的账号
            $schoolInfo = Db::name('SchoolManagement')->where('schoolName',$data['schoolName'])->where('status',1)->where('isdelete',0)->find();
            $data['schoolAccount'] = $schoolInfo['schoolAccount'];  //学区账号

            $data['title'] = $this->request->param('title');  //标题
            $data['release_time'] = $this->request->param('release_time');  //发表时间
            $data['content'] = htmlspecialchars_decode(html_entity_decode($this->request->param("content"))); //内容
            $data['type'] = $this->request->param('type');  //类型

            $data['label'] = $this->request->param('label');  //标签


            Db::name('teacher')->insert($data);



            return ajax_return_adv('添加成功');
        } else {

            //以下是根据当前登录用户找出相应的幼儿园校区名称
            $info = Db::name("AdminUser")->where("id", UID)->where('isdelete','0')->where('status',1)->find();
            $type = $info['type'];
            //若当前登录用户是超级管理员
            if($type == 0){
                //获取所有幼儿园的名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where(array('schoolAccount'=>array('like','%A%')))->where('isdelete',0)->select();
            }
            //若当前登录用户是校区
            if($type == 1){
                //获取当前幼儿园名称-----二维数组
                $schoolName = Db::name("SchoolManagement")->field('schoolName')->where('schoolAccount',$info['account'])->where('isdelete','0')->select();
            }
            //若是教师
            if($type == 2){
                $schoolID = substr($info['account'],0,5);//获取该教师所在的学区编号
                //获取当前教师所在的幼儿园名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();
            }
            //若是学生
            if($type == 3){
                $schoolID = substr($info['account'],0,5);//获取该学生所在的学区编号
                //获取当前学生所在的幼儿园名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();
            }

            $this->view->assign('schoolName',$schoolName);  //二维数组




            $vo =['content' => ''];
			$this->view->assign("vo", $vo);
            // 添加
            return $this->view->fetch(isset($this->template) ? $this->template : 'fsedit');
        }
    }
}
