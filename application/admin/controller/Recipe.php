<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

//每周食谱
class Recipe extends Controller
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

             $map['schoolAccount'] = $info['account'];
         }
         else if($info['type'] ==2)
         {

             $account = $info['account'];
             $schoolID = substr($account,0,5);//截取当前用户账号的前5位---即学区号 ,如C0398
             $map['schoolAccount'] = ["like", "%" . $schoolID. "%"];

         }
         else if($info['type'] ==3)
         {

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
		

        //查询所有早餐
        $allbreakfast = Db::name('breakfast')->select();
        $breakfast_array = array();//一维数组，键表示早餐的id，值表示id对应的早餐名称-----形如 array( [1]=>鸡蛋  [2] =>油条  [3]=>牛奶  [4] => 豆浆)
        foreach($allbreakfast as $value1){
            $breakfast_array[$value1['id']] = $value1['name'];
        }
        //print_r($breakfast_array);exit;
        $this->view->assign('breakfast_array',$breakfast_array);
        //---------------------------------------------------------------------------------------------------------------

        //查询所有午餐
        $alllunch = Db::name('lunch')->select();
        $lunch_array = array();//一维数组，键表示午餐的id，值表示id对应的午餐名称-----形如 array( [1]=>青椒土豆丝  [2] =>红烧肉  [3]=>红烧鸡块  [4] => 麻婆豆腐)
        foreach($alllunch as $value2){
            $lunch_array[$value2['id']] = $value2['name'];
        }
        //print_r($lunch_array);exit;
        $this->view->assign('lunch_array',$lunch_array);
        //--------------------------------------------------------------------------------------------------------------------

        //查询所有午点
        $allsnack = Db::name('snack')->select();
        $snack_array = array();//一维数组，键表示午点的id，值表示id对应的午点名称-----形如 array( [1]=>青椒土豆丝  [2] =>红烧肉  [3]=>红烧鸡块  [4] => 麻婆豆腐)
        foreach($allsnack as $value3){
            $snack_array[$value3['id']] = $value3['name'];
        }
        //print_r($snack_array);exit;
        $this->view->assign('snack_array',$snack_array);



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
            $id = $this->request->param("id"); //当前食谱的id

            $info = Db::name("AdminUser")->field('realname')->where("id", UID)->find();
            $releaser = $info['realname'];

            $schoolName = $this->request->param('schoolName'); //园区名称
            //获取选择的学区名对应的账号
            $schoolInfo = Db::name('SchoolManagement')->where('schoolName',$schoolName)->where('status',1)->where('isdelete',0)->find();
            $schoolAccount = $schoolInfo['schoolAccount'];  //该校区对应的学区账号

            $release_time = $this->request->param('release_time'); //发布时间
            $eat_time = $this->request->param('eat_time'); //吃饭时间
            $type = $this->request->param('type');  //类型

            $breakfast = trim($this->request->param('breakfast'),','); //获取早餐名,形如   鸡蛋,油条,牛奶
            //将早餐名称转为早餐id
            $breakfast_array = explode(',',$breakfast);
            $breakfast_array2 = array();
            foreach($breakfast_array as $v1){
                $breakfastInfo = Db::name('breakfast')->where('name',$v1)->find();
                $breakfast_array2[] = $breakfastInfo['id'];
            }
            $breakfast2 = implode(',',$breakfast_array2); //早餐的id,形如1,2,3


            $lunch = trim($this->request->param('lunch'),','); //获取午餐名,形如   青椒土豆丝,红烧肉,红烧鸡块
            //将午餐名称转为午餐id
            $lunch_array = explode(',',$lunch);
            $lunch_array2 = array();
            foreach($lunch_array as $v2){
                $lunchInfo = Db::name('lunch')->where('name',$v2)->find();
                $lunch_array2[] = $lunchInfo['id'];
            }
            $lunch2 = implode(',',$lunch_array2); //午餐的id,形如1,2,3


            $snack = trim($this->request->param('snack'),','); //获取午点名,形如   糖果,薯片,饼干
            //将午点名称转为午点id
            $snack_array = explode(',',$snack);
            $snack_array2 = array();
            foreach($snack_array as $v3){
                $snackInfo = Db::name('snack')->where('name',$v3)->find();
                $snack_array2[] = $snackInfo['id'];
            }
            $snack2 = implode(',',$snack_array2); //午点的id,形如1,2,3


            $label = $this->request->param('label');//标签
            $data = array(
                'releaser' => $releaser,

                'schoolName' => $schoolName,
                'schoolAccount' => $schoolAccount,
                'release_time' => $release_time,
                'eat_time' => $eat_time,
                'type' => $type,
                'breakfast' => $breakfast2,
                'lunch' => $lunch2,
                'snack' => $snack2,
                'label' => $label
            );
            Db::name("Recipe")->where('id',$id)->update($data);


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


            //以下是根据当前登录用户找出相应的幼儿园校区名称、班级
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
            //---------------------------------------------------------------------------------------------------------


            //查询所有早餐
            $allbreakfast = Db::name('breakfast')->where('status',1)->where('isdelete',0)->select();
            //查询所有午餐
            $alllunch = Db::name('lunch')->where('status',1)->where('isdelete',0)->select();

            //查询所有午点
            $allsnack = Db::name('snack')->where('status',1)->where('isdelete',0)->select();

            $this->view->assign('allbreakfast',$allbreakfast);
            $this->view->assign('alllunch',$alllunch);
            $this->view->assign('allsnack',$allsnack);
            //------------------------------------------------------------------------------------------------------------


            $this->view->assign("vo", $vo);


            //这里将当前早餐的id转为早餐名
            if(!$vo['breakfast']){
                $breakfastName = '';
            }elseif(strlen($vo['breakfast']) == 1){
                $breakfastInfo = Db::name('breakfast')->where('id',$vo['breakfast'])->find();
                $breakfastName = $breakfastInfo['name'];
            }else{
                $breakfastid_array = explode(',',$vo['breakfast']);
                $breakfastname_array = array();
                foreach($breakfastid_array as $val){
                    $breakfastInfo = Db::name('breakfast')->where('id',$val)->find();
                    $breakfastname_array[] = $breakfastInfo['name'];
                }
                $breakfastName = implode(',',$breakfastname_array);
            }
            $this->view->assign('breakfastName',$breakfastName);//当前早餐id对应的早餐名称



            //这里将当前午餐的id转为午餐名
            if(!$vo['lunch']){
                $lunchName = '';
            }elseif(strlen($vo['lunch']) == 1){
                $lunchInfo = Db::name('lunch')->where('id',$vo['lunch'])->find();
                $lunchName = $lunchInfo['name'];
            }else{
                $lunchid_array = explode(',',$vo['lunch']);
                $lunchname_array = array();
                foreach($lunchid_array as $val2){
                    $lunchInfo = Db::name('lunch')->where('id',$val2)->find();
                    $lunchname_array[] = $lunchInfo['name'];
                }
                $lunchName = implode(',',$lunchname_array);
            }
            $this->view->assign('lunchName',$lunchName);//当前午餐id对应的午餐名称


            //这里将当前午点的id转为午点名
            if(!$vo['snack']){
                $snackName = '';
            }elseif(strlen($vo['snack']) == 1){
                $snackInfo = Db::name('snack')->where('id',$vo['snack'])->find();
                $snackName = $snackInfo['name'];
            }else{
                $snackid_array = explode(',',$vo['snack']);
                $snackname_array = array();
                foreach($snackid_array as $val3){
                    $snackInfo = Db::name('snack')->where('id',$val3)->find();
                    $snackname_array[] = $snackInfo['name'];
                }
                $snackName = implode(',',$snackname_array);
            }
            $this->view->assign('snackName',$snackName); //当前午点id所对应的午点名称





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
            $info = Db::name("AdminUser")->field('realname')->where("id", UID)->find();
            $releaser = $info['realname'];

            $schoolName = $this->request->param('schoolName'); //园区名称
            //获取选择的学区名对应的账号
            $schoolInfo = Db::name('SchoolManagement')->where('schoolName',$schoolName)->where('status',1)->where('isdelete',0)->find();
            $schoolAccount = $schoolInfo['schoolAccount'];  //学区账号

            $release_time = $this->request->param('release_time'); //发布时间
            $eat_time = $this->request->param('eat_time'); //吃饭时间
            $type = $this->request->param('type');  //类型

            $breakfast = trim($this->request->param('breakfast'),','); //获取早餐名,形如   鸡蛋,油条,牛奶
            //将早餐名称转为早餐id
            $breakfast_array = explode(',',$breakfast);
            $breakfast_array2 = array();
            foreach($breakfast_array as $v1){
                $breakfastInfo = Db::name('breakfast')->where('name',$v1)->find();
                $breakfast_array2[] = $breakfastInfo['id'];
            }
            $breakfast2 = implode(',',$breakfast_array2); //早餐的id,形如1,2,3


            $lunch = trim($this->request->param('lunch'),','); //获取午餐名,形如   青椒土豆丝,红烧肉,红烧鸡块
            //将午餐名称转为午餐id
            $lunch_array = explode(',',$lunch);
            $lunch_array2 = array();
            foreach($lunch_array as $v2){
                $lunchInfo = Db::name('lunch')->where('name',$v2)->find();
                $lunch_array2[] = $lunchInfo['id'];
            }
            $lunch2 = implode(',',$lunch_array2); //午餐的id,形如1,2,3


            $snack = trim($this->request->param('snack'),','); //获取午点名,形如   糖果,薯片,饼干
            //将午点名称转为午点id
            $snack_array = explode(',',$snack);
            $snack_array2 = array();
            foreach($snack_array as $v3){
                $snackInfo = Db::name('snack')->where('name',$v3)->find();
                $snack_array2[] = $snackInfo['id'];
            }
            $snack2 = implode(',',$snack_array2); //午点的id,形如1,2,3


            $label = $this->request->param('label');//标签
            $data = array(
                'releaser' => $releaser,

                'schoolName' => $schoolName,
                'schoolAccount' => $schoolAccount,
                'release_time' => $release_time,
                'eat_time' => $eat_time,
                'type' => $type,
                'breakfast' => $breakfast2,
                'lunch' => $lunch2,
                'snack' => $snack2,
                'label' => $label
            );
            Db::name("Recipe")->insert($data);


            return ajax_return_adv('添加成功');
        } else {

            //以下是根据当前登录用户找出相应的幼儿园校区名称、班级
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
                $schoolID = substr($info['account'],0,5); //获取该教师所在的学区编号
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
            //-------------------------------------------------------------------------------------------------------------------

            //查询所有早餐
            $allbreakfast = Db::name('breakfast')->where('status',1)->where('isdelete',0)->select();
            //查询所有午餐
            $alllunch = Db::name('lunch')->where('status',1)->where('isdelete',0)->select();

            //查询所有午点
            $allsnack = Db::name('snack')->where('status',1)->where('isdelete',0)->select();

            $this->view->assign('allbreakfast',$allbreakfast);
            $this->view->assign('alllunch',$alllunch);
            $this->view->assign('allsnack',$allsnack);
            //------------------------------------------------------------------------------------------------------------

            $vo =['content' => ''];
			$this->view->assign("vo", $vo);
            // 添加
            return $this->view->fetch(isset($this->template) ? $this->template : 'fsedit');
        }
    }


}
