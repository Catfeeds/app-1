<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Exception;
use think\Loader;
use think\Db;
use think\Config;
use think\Session;

//部门管理
class DivisionManagement extends Controller
{
    use \app\admin\traits\controller\Controller;
    // 方法黑名单
    protected static $blacklist = [];

    protected function filter(&$map)
    {
		if ($this->request->param("schoolName")) {
			
			$map['schoolName'] = ["like", "%" . $this->request->param("schoolName"). "%"];
		}
        if ($this->request->param("divisionName")) {
            $map['divisionName'] = ["like", "%" . $this->request->param("divisionName") . "%"];
        }

        $info = Db::name("AdminUser")->where("id", UID)->find();  //当前登陆者信息
        if($info['type'] ==1)
        {

//          $map['release']=["like", "%" . $info1['realname']. "%"];
            $map['companyAccount'] = $info['account'];
        }
        else if($info['type'] ==2)
        {

//          $map['release']=["like", "%" . $info1['realname']. "%"];
            $account = $info['account'];
            $schoolID = substr($account,0,5);//截取当前用户账号的前5位---即公司编号，如G4040
            $map['companyAccount'] = ["like", "%" . $schoolID. "%"];

        }
//        else if($info['type'] ==3)
//        {
//
////          $map['release']=["like", "%" . $info1['realname']. "%"];
//            $account = $info['account'];
//            $schoolID = substr($account,0,5);//截取当前用户账号的前5位---即公司编号 ,如G4040
//            $map['companyAccount'] = ["like", "%" . $schoolID. "%"];
//
//        }
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

        //------------------------------------------------------------------------------------------------------------
        //获取对应的公司名称，用于表单显示
        $info = Db::name("AdminUser")->where('isdelete','0')->where('status',1)->where("id", UID)->find();
        //管理员登陆
        if($info['type'] == 0){
            //获取所有的公司名称---所有已完善信息的公司
            $all_companyName = Db::name('SchoolManagement')->field('schoolName')->where('isperfect',1)->where('status',1)->where('isdelete',0)->select();
        }
        //公司账号登陆
        if($info['type'] == 1){
            //获取当前公司名称
            $all_companyName = Db::name("SchoolManagement")->field('schoolName')->where('schoolAccount',$info['account'])->where('status',1)->where('isdelete',0)->select();
        }
        //员工登录
        if($info['type'] == 2){
            //获取当前员工所在的公司名称
            $schoolID = substr($info['account'],0,5); //获取该员工所在的公司编号
            $all_companyName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->select();
        }
//        //学生登陆
//        if($info['type'] == 3){
//            $schoolID = substr($info['account'],0,5);//获取该学生所在的学区编号
//            $all_companyName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->select();
//        }
        $this->view->assign('all_companyName',$all_companyName);


        return $this->view->fetch();
    }
	
	/**
     * 添加
     * @return mixed
     */
    public function add()
    {
        //$controller = $this->request->controller();

        if ($this->request->isAjax()) {
            // 插入

            $data['companyName'] = $this->request->param('companyName');//公司名称
            //获取公司账号
            $companyInfo = Db::name('SchoolManagement')->where('schoolName',$data['companyName'])->find();
            $data['companyAccount'] = $companyInfo['schoolAccount'];

            $data['divisionName'] = $this->request->param('divisionName'); //部门名称
            $data['remark'] = $this->request->param('remark'); //备注信息


            Db::name('DivisionManagement')->insert($data);

            return ajax_return_adv('添加成功');
        } else {
            // 添加

            //获取公司名称
            $info = Db::name("AdminUser")->where('isdelete','0')->where('status',1)->where("id", UID)->find();
            //管理员登陆
            if($info['type'] == 0){
                //获取所有的公司名称
                $data = Db::name('SchoolManagement')->field('schoolName')->where('isperfect',1)->where('status',1)->where('isdelete',0)->select();
            }
            //公司账号登陆
            if($info['type'] == 1){
                //获取当前公司的名称
                $data = Db::name("SchoolManagement")->field('schoolName')->where('schoolAccount',$info['account'])->where('status',1)->where('isdelete','0')->select();
            }
            //员工登录
            if($info['type'] == 2){
                $schoolID = substr($info['account'],0,5);//获取该员工所在的公司编号，如G4040
                $data = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->select();
            }
			$this->view->assign("data", $data);

            return $this->view->fetch(isset($this->template) ? $this->template : 'edit');
        }
    }

    /**
     * 编辑
     * @return mixed
     */
    public function edit()
    {
        $controller = $this->request->controller();

        if ($this->request->isAjax()) {
            // 更新
            $id = $this->request->param('id'); //当前记录id

            $data['companyName'] = $this->request->param('companyName');//公司名称
            //获取公司账号
            $companyInfo = Db::name('SchoolManagement')->where('schoolName',$data['companyName'])->find();
            $data['companyAccount'] = $companyInfo['schoolAccount'];

            $data['divisionName'] = $this->request->param('divisionName'); //部门名称
            $data['remark'] = $this->request->param('remark'); //备注

            Db::name('DivisionManagement')->where('id',$id)->update($data);

            return ajax_return_adv("编辑成功");
        } else {
            // 编辑当前记录
            $id = $this->request->param('id');
            if (!$id) {
                throw new HttpException(404, "缺少参数ID");
            }
            $vo = $this->getModel($controller)->find($id);
            if (!$vo) {
                throw new HttpException(404, '该记录不存在');
            }

            //获取公司名称
            $info = Db::name("AdminUser")->where('isdelete','0')->where('status',1)->where("id", UID)->find();
            //管理员登陆
            if($info['type'] == 0){
                //获取所有的公司名称
                $data = Db::name('SchoolManagement')->field('schoolName')->where('isperfect',1)->where('status',1)->where('isdelete',0)->select();
            }
            //公司账号登陆
            if($info['type'] == 1){
                //获取当前公司名称
                $data = Db::name("SchoolManagement")->field('schoolName')->where('schoolAccount',$info['account'])->where('status',1)->where('isdelete','0')->select();
            }
            //员工登录
            if($info['type'] == 2){
                $schoolID = substr($info['account'],0,5);//获取该员工所在的公司编号,如G4040
                $data = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->select();
            }
			$this->view->assign("data", $data);  //二维数组


            $this->view->assign("vo", $vo);




            return $this->view->fetch();
        }
    }
}
