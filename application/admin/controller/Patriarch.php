<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

//审核用户
class Patriarch extends Controller
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


        //获取检索过来的数据---未审核0、合格1、不合格2
//        if($this->request->isPost()){
//            $isqualified = $this->request->param('isqualified');
//
//        }else{
//            //刚开始加载时，默认是未审核
//            $isqualified = 0;
//        }


        //从tp_patriarch表中查询所有未删除的注册用户
        $all_patriarch = Db::name('patriarch')->where('isdelete',0)->select();

        //开始查找每个用户发表的文章总量----包括学校帖子、文集、社区资讯
        //------------------------------------------------------------------------------
        //查找每个用户发表的所有帖子
        $post_count = array(); //三维数组：用于存放每个用户发表的帖子个数-----用法：count($post_count['V18715096987'])得到该用户发表的帖子的个数
        foreach($all_patriarch as $k1=>$v1){
            $all_post = Db::name('AllSchoolPost')->where('phone_account',$v1['phone_account'])->where('status',1)->where('isdelete',0)->select();
            $post_count[$v1['phone_account']] = $all_post; //三维数组
        }
        //print_r($post_count);exit;  //求每个用户发表的帖子数量时，先判断一下，如：if($post_count['V18715096987'] != ''){echo count($post_count['V18715096987'])}else{echo 0;}
        //-------------------------------------------------------------------------------------

        //查找每个用户发表的所有文集
        $corpus_count = array(); //用于存放每个用户发表的文集个数-----用法：count($corpus_count['V18715096987'])得到该用户发表的文集的个数
        foreach($all_patriarch as $k2=>$v2){
            $all_corpus = Db::name('CorpusArticle')->where('phone_account',$v2['phone_account'])->where('status',1)->where('isdelete',0)->select();
            $corpus_count[$v2['phone_account']] = $all_corpus; //三维数组
        }
        //print_r($corpus_count);exit;  //求每个用户发表的文集数量时，先判断一下，如：if($corpus_count['V18715096987'] != ''){echo count($corpus_count['V18715096987'])}else{echo 0;}


        //对文集收藏统计
        $corpus_collect_count = array();//文集被收藏的统计------一维数组(键表示用户账号，值表示该用户发表的所有文集总共被收藏的次数)
        foreach($corpus_count as $key=>$val){
            $a = 0; //记录每个用户所发表的所有文集总共被收藏的数量
            foreach($val as $val2){
                //收藏统计
                $arr = Db::name('CorpusArticleCollect')->where('corpusarticle_id',$val2['id'])->where('iscollect',1)->where('status',1)->where('isdelete',0)->select();
                if($arr){
                    $a+=count($arr);

                }
            }
            $corpus_collect_count[$key] = $a; //$key表示phone_account
        }
        //print_r($corpus_collect_count);exit;    //如$corpus_collect_count['phone_account']就表示该用户所发表的所有文集总共被收藏的次数
        //----------------------------------------------------------------------------------------------------------------------

        //查找每个用户发表的所有社区文章
        $information_count = array(); //用于存放每个用户发表的社区文章个数-----用法：count($information_count['V18715096987'])得到该用户发表的社区文章的个数
        foreach($all_patriarch as $k3=>$v3){
            $all_information = Db::name('InformationArticle')->where('phone_account',$v3['phone_account'])->where('status',1)->where('isdelete',0)->select();
            $information_count[$v3['phone_account']] = $all_information; //三维数组
        }
        //print_r($information_count);exit;  //求每个用户发表的社区文章的数量时，先判断一下，如：if($information_count['V18715096987'] != ''){echo count($information_count['V18715096987'])}else{echo 0;}
        //------------------------------------------------------------------------------------------------------------------------------------------------------------------


        //查找每个人的粉丝数量
        $follower_count = array(); //三维数组--------用法：count($follower_count['S13866187233'])得到该用户的粉丝数量
        foreach($all_patriarch as $k4=>$v4){
            $all_follower = Db::name('CorpusArticleConcern')->where('bephone_account',$v4['phone_account'])->where('isconcern',1)->select();
            $follower_count[$v4['phone_account']] = $all_follower;
        }
        //print_r($follower_count);exit;
        //--------------------------------------------------------------------------------------------------------------------------------------

        //查找每个用户的关联账号（可能是多个）-------即该用户的孩子所在校区的孩子账号（孩子可能是多个）
        $relation_account = array(); //二维数组-----------键表示用户账号，值是一维数组，用来存放每个用户对应的关联账号
        foreach($all_patriarch as $k5=>$v5){
            $linkInfo = Db::name('StudentLinkman')->field('student_id')->where('number',$v5['phone'])->where('isdelete',0)->select();
            if(!$linkInfo){
                $relation_account[$v5['phone_account']] = array();
            }else{
                foreach($linkInfo as $k6=>$v6){
                    $studentInfo = Db::name('StudentManagement')->where('id',$v6['student_id'])->where('isdelete',0)->find();
                    $relation_account[$v5['phone_account']][] = $studentInfo['account'];
                }
            }

        }
        //print_r($relation_account);exit; //用法：先判断一下，不为空再用foreach循环输出每个用户对应的关联账号   如if($relation_account['S13866187233']){foreach($relation_account['S13866187233'] as $v){echo $v;}}else{echo '无'}
        //---------------------------------------------------------------------------------------------------------------------------------------


        //print_r($all_patriarch);exit;
        $this->view->assign('list',$all_patriarch); //二维数组--tp_patriarch表中所有的注册用户
        if($all_patriarch){
            $this->view->assign('count',count($all_patriarch)); //统计数据条数
        }else{
            $this->view->assign('count',0);
        }


        $this->view->assign('post_count',$post_count);   //如count($post_count['V18715096987'])得到该用户发表的帖子的个数
        $this->view->assign('corpus_count',$corpus_count);  //如count($corpus_count['V18715096987'])得到该用户发表的文集的个数
        $this->view->assign('information_count',$information_count);  //如count($information_count['V18715096987'])得到该用户发表的社区文章的个数

        $this->view->assign('corpus_collect_count',$corpus_collect_count); //如$corpus_collect_count['phone_account']就表示相应的收藏个数

        $this->view->assign('follower_count',$follower_count);//如count($follower_count['S13866187233'])得到该用户的粉丝数量

        $this->view->assign('relation_account',$relation_account); //查询关联账号  ---用foreach循环输出所有的关联账号


        //$this->view->assign('result',$isqualified);//通过判断是未审核、合格还是不合格，th显示相应的头标题



        return $this->view->fetch();

    }




    /*
     * 接收传过来的当前id值
     * 切换发文状态------可以发文1   暂停发文0    同时获取操作员的姓名：即当前登录用户的姓名
     */
    public function changeDispatch()
    {
         if($this->request->param('id')!=''){
             $user = Db::name('AdminUser')->where('id',UID)->find();
             $operator = $user['realname']; //当前登录用户的姓名

             $id = $this->request->param('id');
             $info = Db::name('Patriarch')->where('id',$id)->find();
             $isdispatch = $info['isdispatch'];
             //如果是可以发文 -----改为暂停发文
             if($isdispatch == 1){
                 $data = array(
                     'isdispatch' => 0,
                     'operator' => $operator
                 );
                 $bool = Db::name('Patriarch')->where('id',$id)->update($data);
                 if($bool){
                     echo '<script>window.location.href="'.url('index').'"</script>';
                 }
             }
             //如果是暂停发文-----改为可以发文
             if($isdispatch == 0){
                 $data = array(
                     'isdispatch' => 1,
                     'operator' => $operator
                 );
                 $bool = Db::name('Patriarch')->where('id',$id)->update($data);
                 if($bool){
                     echo '<script>window.location.href="'.url('index').'"</script>';
                 }
             }
         }

    }


    /**
     * 切换封号状态  启用1   禁用0
     * 获取传过来的当前用户的id
     */
    public function changeStatus()
    {
        if($this->request->param('id')!=''){
            $user = Db::name('AdminUser')->where('id',UID)->find();
            $operator = $user['realname'];  //获取当前登录用户的姓名

            $id = $this->request->param('id');
            $info = Db::name('Patriarch')->where('id',$id)->find();
            $status = $info['status'];
            //如果是启用---将状态修改为禁用
            if($status == 1){
                $data = array(
                    'status' => 0,
                    'operator' => $operator
                );
                $bool = Db::name('Patriarch')->where('id',$id)->update($data);
                if($bool){
                    echo '<script>window.location.href="'.url('index').'"</script>';
                }
            }
            //如果是禁用-----将状态修改为启用
            if($status == 0){
                $data = array(
                    'status' => 1,
                    'operator' => $operator
                );
                $bool = Db::name('Patriarch')->where('id',$id)->update($data);
                if($bool){
                    echo '<script>window.location.href="'.url('index').'"</script>';
                }
            }

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
