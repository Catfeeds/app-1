<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

//审核社区资讯
class InformationArticle extends Controller
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

        // $model = $this->getModel();
//        // 列表过滤器，生成查询Map对象
//        $map = $this->search($model, [$this->fieldIsDelete => $this::$isdelete]);
//        // 特殊过滤器，后缀是方法名的
//        $actionFilter = 'filter' . $this->request->action();
//        if (method_exists($this, $actionFilter)) {
//            $this->$actionFilter($map);
//        }
//
//        // 自定义过滤器
//        if (method_exists($this, 'filter')) {
//            $this->filter($map);
//        }
//        $model = $this->getModel();
//        $this->datalist($model, $map);


        //获取检索过来的数据---未审核0、合格1
        if($this->request->isPost()){
            $isqualified = $this->request->param('isqualified');

        }else{
            //刚开始加载时，默认是未审核0
            $isqualified = 0;
        }




        //社区文章------根据条件查找所有相应的社区文章（可能是未审核、可能是合格）
        $all_information = Db::name('InformationArticle')->where('isqualified',$isqualified)->where('status',1)->where('isdelete',0)->order('release_time desc')->select();

        $information_array = array(); //存放每个用户最新的社区文章-------二维数组（键表示账号，值是一维数组）
        $information_count = array(); //用于存放每个用户发表的社区文章个数-----用法：count($information_count['V18715096987'])得到该用户发表的社区文章的个数
        foreach($all_information as $value3){
            if(!array_key_exists($value3['phone_account'],$information_array)){
                $information_array[$value3['phone_account']] = $value3;
            }
            $information_count[$value3['phone_account']][] = $value3; //三维数组
        }
        //print_r($information_array);exit;
        //-----------------------------------------------------------------------------------------------------


        //最后结果
        $all_new_info4 = array();//追加一个用户昵称---------二维数组
        foreach($information_array as $key3=>$value11){
            //从注册表中查出相应用户的昵称
            $patriarch_info = Db::name('patriarch')->where('phone_account',$key3)->where('status',1)->where('isdelete',0)->find();
            $nick_name = array('nickname'=>$patriarch_info['nickname']);
            $array = array_merge($value11,$nick_name);
            $all_new_info4[$key3] = $array;
        }
        //print_r($all_new_info4);exit;   //如$all_new_info4['E13637282041']值是一维数组
        //-----------------------------------------------------------------------------------------------------
        //最后结果2
        $all_new_info5 = array();//追加一个info_id对应的板块名称---------二维数组
        foreach($all_new_info4 as $key4=>$value12){

            $info = Db::name('Information')->where('id',$value12['info_id'])->where('status',1)->where('isdelete',0)->find();
            $plate_array['plate_name'] = $info['info_name'];  //查找info_id对应的社区资讯名称
            $all_new_info5[$key4] = $value12+$plate_array;
        }
        //print_r($all_new_info5);exit;    //如$all_new_info5['E13637282041']值是一维数组
        //--------------------------------------------------------------------------------------------------------

        $this->view->assign('list',$all_new_info5); //二维数组--所有每个人的最新发布信息---按时间排序
        if($all_new_info4){
            $this->view->assign('count',count($all_new_info5));//统计数据条数
        }else{
            $this->view->assign('count',0);
        }

        $this->view->assign('information_count',$information_count);  //如count($information_count['V18715096987'])得到该用户发表的社区文章的个数

        $this->view->assign('result',$isqualified);//通过判断是未审核、还是合格，th显示相应的头标题



        return $this->view->fetch();
    }



    /**
     * 获取传过来的id并查看相应的社区资讯 id
     *
     */
    public function select()
    {

        if($this->request->param('id')!=''){
            $id = $this->request->param('id'); //当前信息id
            //当前的社区资讯
            $current_Info = Db::name('InformationArticle')->where('id',$id)->where('status',1)->where('isdelete',0)->find();

            $this->view->assign('current_info',$current_Info);

            $this->view->assign('id',$id);


            return $this->view->fetch();

        }

    }

    /**
     * 获取审核结果 ，同时获取当前审核人姓名---当前登录用户
     *
     */
    public function check()
    {
        if($this->request->isPost()){
            $user_info = Db::name('AdminUser')->where('id',UID)->where('status',1)->where('isdelete',0)->find();
            $name = $user_info['realname']; //获取当前审核人的姓名

            $id = $this->request->param('id'); //获取当前社区资讯的id


            $result = $this->request->param('demo-radio1');//判断选择的是合格还是不合格
            //若选择的是合格
            if($result == 1){
                $data = array(
                    'isqualified' => 1,
                    'assessor' => $name,
                );
                Db::name('InformationArticle')->where('id',$id)->update($data);
                echo '<script>window.location.href="'.url('index').'"</script>'; //审核后页面直接跳转到首页
            }
            //若选择的是不合格---直接删除该记录
            if($result == 2){

                Db::name('InformationArticle')->where('id',$id)->delete();
                echo '<script>window.location.href="'.url('index').'"</script>'; //审核后页面直接跳转到首页
            }


        }
    }


    /**
     * 根据传过来的isqualified参数判断是查询未审核、还是合格
     * 根据phone_account参数获取当前用户
     * 通过点击查看--查看关于当前用户发表的未审核、还是合格的所有社区资讯
     */
    public function selectAll()
    {
        if($this->request->param('phone_account')!='' && $this->request->param('isqualified')!=''){
            $phone_account = $this->request->param('phone_account'); //获取传过来的该用户账号
            $isqualified = $this->request->param('isqualified'); //获取审核结果，判断是未审核、还是合格

            //查看该用户发表的所有的是未审核、还是合格的社区资讯----按时间降序排序
            $informationAll = Db::name('InformationArticle')->where('phone_account',$phone_account)->where('isqualified',$isqualified)->order('release_time desc')->where('status',1)->where('isdelete',0)->select();
            //print_r($informationAll);exit;


            $all3 = array();//追加一个info_id对应的板块名称---------二维数组
            foreach($informationAll as $k=>$vs){

                $info = Db::name('Information')->where('id',$vs['info_id'])->where('status',1)->where('isdelete',0)->find();
                $plate_array['plate_name'] = $info['info_name'];  //查找info_id对应的社区资讯名称
                $all3[$k] = $vs+$plate_array;

            }
            //print_r($all3);exit;  //二维数组


            $this->view->assign('all3',$all3);
            $this->view->assign('isqualified',$isqualified);

            return $this->view->fetch();




        }
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






//    /**
//     * 添加
//     * @return mixed
//     */
//    public function fadd()
//    {
//
//        $controller = $this->request->controller();
//
//        if ($this->request->isAjax()) {
//            // 插入
//            $data = $this->request->except(['id']);
//
//            // 验证
//            if (class_exists($validateClass = Loader::parseClass(Config::get('app.validate_path'), 'validate', $controller))) {
//                $validate = new $validateClass();
//                if (!$validate->check($data)) {
//                    return ajax_return_adv_error($validate->getError());
//                }
//            }
//
//            // 写入数据
//            if (
//                class_exists($modelClass = Loader::parseClass(Config::get('app.model_path'), 'model', $this->parseCamelCase($controller)))
//                || class_exists($modelClass = Loader::parseClass(Config::get('app.model_path'), 'model', $controller))
//            ) {
//                //使用模型写入，可以在模型中定义更高级的操作
//                $info = Db::name("AdminUser")->field('realname,account')->where("id", UID)->find();
//                $data['source'] = $info['realname'];//报名来源
//
//                $account = $info['account'];//当前登录用户的账号
//                $schoolID = substr($account,0,5);//当前用户所在的学区编号
//                $schoolInfo = Db::name('SchoolManagement')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->find();
//                $data['schoolAccount'] = $schoolInfo['schoolAccount'];//当前登录用户的所在的学区账号
//
//                $data['enroll_time'] = date('Y-m-d',time());//报名时间
//
//                $model = new $modelClass();
//
//                $ret = $model->isUpdate(false)->save($data);
//            } else {
//                // 简单的直接使用db写入
//                Db::startTrans();
//                try {
//                    $info = Db::name("AdminUser")->field('realname,account')->where("id", UID)->find();
//                    $data['source'] = $info['realname'];//报名来源
//
//                    $account = $info['account'];//当前登录用户的账号
//                    $schoolID = substr($account,0,5);
//                    $schoolInfo = Db::name('SchoolManagement')->where('schoolID',$schoolID)->where('status',1)->where('isdelete',0)->find();
//                    $data['schoolAccount'] = $schoolInfo['schoolAccount'];//当前登录用户的所在的学区账号
//
//                    $data['enroll_time'] = date('Y-m-d',time());//报名时间
//
//                    $model = Db::name($this->parseTable($controller));
//
//                    $ret = $model->insert($data);
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
//            return ajax_return_adv('添加成功');
//        }else {
//            $vo =['content' => ''];
//            $this->view->assign("vo", $vo);
//
//
//            $info = Db::name("AdminUser")->where('isdelete','0')->where('status',1)->where("id", UID)->find();
//            //管理员登陆
//            if($info['type'] == 0){
//
//
//                $type = $info['account'];//为了显示相应的学级
//            }
//            //学区登陆
//            if($info['type'] == 1){
//
//                $type = substr($info['account'],0,1);
//
//            }
//            //教师登陆
//            if($info['type'] == 2){
//
//                $type = substr($info['account'],0,1);
//            }
//            //学生登陆
//            if($info['type'] == 3){
//
//                $type = substr($info['account'],0,1);
//            }
//            $this->view->assign('type',$type);
//
//
//
//            // 添加
//            return $this->view->fetch(isset($this->template) ? $this->template : 'fsedit');
//        }
//    }



}
