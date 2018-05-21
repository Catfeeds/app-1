<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

//报名学生的信息
class Enrolstudent extends Controller
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
            $map['schoolAccount']=$info1['account'];
        }
        else if($info['type'] ==2)
        {
            $info1 = Db::name("AdminUser")->field('realname,account')->where("id", UID)->find();

//            $map['release']=["like", "%" . $info1['realname']. "%"];
            $account = $info1['account'];
            $schoolId = substr($account,0,5);//截取教师账号的前5位
            $map['schoolAccount']=["like", "%".$schoolId."%"];

        }
        else if($info['type'] ==3)
        {
            $info1 = Db::name("AdminUser")->field('realname,account')->where("id", UID)->find();

//            $map['release']=["like", "%" . $info1['realname']. "%"];
            $account = $info1['account'];
            $schoolId = substr($account,0,5);//截取学生账号的前5位
            $map['schoolAccount']=["like", "%".$schoolId."%"];

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
//    public function fsedit()
//    {
//        $controller = $this->request->controller();
//
//        if ($this->request->isAjax()) {
//            // 更新
//            $data = $this->request->post();
//            if (!$data['id']) {
//                return ajax_return_adv_error("缺少参数ID");
//            }
//
//            // 验证
//            if (class_exists($validateClass = Loader::parseClass(Config::get('app.validate_path'), 'validate', $controller))) {
//                $validate = new $validateClass();
//                if (!$validate->check($data)) {
//                    return ajax_return_adv_error($validate->getError());
//                }
//            }
//
//            // 更新数据
//            if (
//                class_exists($modelClass = Loader::parseClass(Config::get('app.model_path'), 'model', $this->parseCamelCase($controller)))
//                || class_exists($modelClass = Loader::parseClass(Config::get('app.model_path'), 'model', $controller))
//            ) {
//                $info = Db::name("AdminUser")->field('realname')->where("id", UID)->find();
////                $data['release'] = $info['realname'];
//                // 使用模型更新，可以在模型中定义更高级的操作
//                $model = new $modelClass();
////                $data['introduce'] = htmlspecialchars_decode(html_entity_decode($data['introduce']));
//
//
//                $ret = $model->isUpdate(true)->save($data, ['id' => $data['id']]);
//            } else {
//                // 简单的直接使用db更新
//                Db::startTrans();
//                try {
//                    $info = Db::name("AdminUser")->field('realname')->where("id", UID)->find();
////                    $data['release'] = $info['realname'];
////                    $data['introduce'] = htmlspecialchars_decode(html_entity_decode($data['introduce']));
//
//                    $model = Db::name($this->parseTable($controller));
//
//                    $ret = $model->where('id', $data['id'])->update($data);
//
//                    // 提交事务
//                    Db::commit();
//                } catch (\Exception $e) {
//                    // 回滚事务
//                    Db::rollback();
//
//                    return ajax_return_adv_error($e->getMessage());
//                }
//            }
//
//            return ajax_return_adv("编辑成功");
//        } else {
//            // 编辑
//            $id = $this->request->param('id');
//            if (!$id) {
//                throw new HttpException(404, "缺少参数ID");
//            }
//            $vo = $this->getModel($controller)->find($id);
//            if (!$vo) {
//                throw new HttpException(404, '该记录不存在');
//            }
//
//            $this->view->assign("vo", $vo);
//
//            return $this->view->fetch();
//        }
//    }



    /*
     * 查看传过来的当前学生报名信息
     * 以及关于该学生的所有追踪信息
     */
    public function select()
    {

        if($this->request->isGet() && $this->request->param('id')!=''){
            $id = $this->request->param('id'); //当前学生报名信息的id
            //查询当前学生的报名信息
            $enrolInfo = Db::name('enrolstudent')->where('id',$id)->where('status',1)->where('isdelete',0)->find();//获取当前学生报名的信息
            $this->view->assign('enrolInfo',$enrolInfo);


            //查询关于当前学生的所有追踪信息
            $student_traceInfo = Db::name('EnrolStudentTrace')->where('rid',$id)->where('status',1)->where('isdelete',0)->order('id desc')->select();
            $this->view->assign('student_traceInfo',$student_traceInfo);




            //只要浏览该页面就将当前浏览者的信息插入到tp_enrol_student_trace数据表中
            $info = Db::name("AdminUser")->field('realname')->where("id", UID)->find();
            $contacts = $info['realname']; //当前备注信息者--联系人

            $enrolInfo = Db::name('Enrolstudent')->where('id',$id)->where('status',1)->where('isdelete',0)->find();
            $schoolAccount = $enrolInfo['schoolAccount']; //当前报名学生所报名的学区账号
            $name = $enrolInfo['name']; //报名学生姓名
            $current_school = $enrolInfo['current_school']; //报名学生目前在读学校
            $class = $enrolInfo['class']; //报名学段
            $sex = $enrolInfo['sex']; //报名学生性别

            $contact_time = date('Y-m-d H:i:s',time());

            $data = array(
                'rid' => $id,
                'schoolAccount' => $schoolAccount,
                'name' => $name,
                'current_school' => $current_school,
                'class' => $class,
                'sex' => $sex,
                'contacts' => $contacts,
                'remark' => '',
                'contact_time' => $contact_time,
            );
            Db::name('EnrolStudentTrace')->insert($data);
            return $this->view->fetch();
        }

    }


    /**
     * 将备注信息插入到报名信息追踪表tp_enrol_student_trace中
     */
    public function remarkInfo()
    {

        if($this->request->isPost()){
            $id = $this->request->param('hide'); //当前学生报名信息的id
            $remark = $this->request->param('remark'); //备注信息

            $info = Db::name("AdminUser")->field('realname')->where("id", UID)->find();
            $contacts = $info['realname']; //当前备注信息者--联系人姓名

            $enrolInfo = Db::name('Enrolstudent')->where('id',$id)->where('status',1)->where('isdelete',0)->find();
            $schoolAccount = $enrolInfo['schoolAccount']; //当前报名学生所报名的学区账号
            $name = $enrolInfo['name']; //报名学生姓名
            $current_school = $enrolInfo['current_school']; //报名学生目前在读学校
            $class = $enrolInfo['class']; //报名学段
            $sex = $enrolInfo['sex']; //报名学生性别

            $contact_time = date('Y-m-d H:i:s',time());

            $data = array(
                'rid' => $id,
                'schoolAccount' => $schoolAccount,
                'name' => $name,
                'current_school' => $current_school,
                'class' => $class,
                'sex' => $sex,
                'contacts' => $contacts,
                'remark' => $remark,
                'contact_time' => $contact_time,
            );

            $bool = Db::name('EnrolStudentTrace')->insert($data);
            if(!$bool){
                echo '<script>history.back()</script>';
            }else{
                echo '<script>history.go(-2)</script>';
            }

        }

    }


    /**
     * 添加
     * @return mixed
     */
    public function fadd()
    {

        $controller = $this->request->controller();

        if ($this->request->isAjax()) {
            // 插入
            $data = $this->request->except(['id']);

            // 验证
            if (class_exists($validateClass = Loader::parseClass(Config::get('app.validate_path'), 'validate', $controller))) {
                $validate = new $validateClass();
                if (!$validate->check($data)) {
                    return ajax_return_adv_error($validate->getError());
                }
            }

            // 写入数据
            if (
                class_exists($modelClass = Loader::parseClass(Config::get('app.model_path'), 'model', $this->parseCamelCase($controller)))
                || class_exists($modelClass = Loader::parseClass(Config::get('app.model_path'), 'model', $controller))
            ) {
                //使用模型写入，可以在模型中定义更高级的操作
                $info = Db::name("AdminUser")->field('realname,account')->where("id", UID)->find();
                $data['source'] = $info['realname'];//报名来源

                $account = $info['account'];//当前登录用户的账号
                $schoolID = substr($account,0,5);//当前用户所在的学区编号
                $schoolInfo = Db::name('SchoolManagement')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->find();
                $data['schoolAccount'] = $schoolInfo['schoolAccount'];//当前登录用户的所在的学区账号

                $data['enroll_time'] = date('Y-m-d',time());//报名时间

                $model = new $modelClass();

                $ret = $model->isUpdate(false)->save($data);
            } else {
                // 简单的直接使用db写入
                Db::startTrans();
                try {
                    $info = Db::name("AdminUser")->field('realname,account')->where("id", UID)->find();
                    $data['source'] = $info['realname'];//报名来源

                    $account = $info['account'];//当前登录用户的账号
                    $schoolID = substr($account,0,5);
                    $schoolInfo = Db::name('SchoolManagement')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->find();
                    $data['schoolAccount'] = $schoolInfo['schoolAccount'];//当前登录用户的所在的学区账号

                    $data['enroll_time'] = date('Y-m-d',time());//报名时间

                    $model = Db::name($this->parseTable($controller));

                    $ret = $model->insert($data);
                    // 提交事务
                    Db::commit();
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();

                    return ajax_return_adv_error($e->getMessage());
                }
            }

            return ajax_return_adv('添加成功');
        }else {
            $vo =['content' => ''];
            $this->view->assign("vo", $vo);


            $info = Db::name("AdminUser")->where('isdelete','0')->where('status',1)->where("id", UID)->find();
            //管理员登陆
            if($info['type'] == 0){


                $type = $info['account'];//为了显示相应的学级
            }
            //学区登陆
            if($info['type'] == 1){

                $type = substr($info['account'],0,1);

            }
            //教师登陆
            if($info['type'] == 2){

                $type = substr($info['account'],0,1);
            }
            //学生登陆
            if($info['type'] == 3){

                $type = substr($info['account'],0,1);
            }
            $this->view->assign('type',$type);



            // 添加
            return $this->view->fetch(isset($this->template) ? $this->template : 'fsedit');
        }
    }



}
