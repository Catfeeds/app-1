<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

//公司发布全职----也是全职列表
class CompanyReleaseFullposition extends Controller
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
            $map['companyAccount'] = $info['account'];
        }
        else if($info['type'] ==2)
        {

//          $map['release']=["like", "%" . $info1['realname']. "%"];
            $account = $info['account'];
            $schoolID = substr($account,0,5);//截取当前用户账号的前5位---即学区号 ,如C0398
            $map['companyAccount'] = ["like", "%" . $schoolID. "%"];

        }
//        else if($info['type'] ==3)
//        {
//
////          $map['release']=["like", "%" . $info1['realname']. "%"];
//            $account = $info['account'];
//            $schoolID = substr($account,0,5);//截取当前用户账号的前5位---即学区号 ,如C0398
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


        //---------------------------------------------------------------------------
        $info = Db::name('AdminUser')->where('id',UID)->find(); //获取当前登陆者信息
        //超级管理员
        if($info['type'] == 0){
            $companyAccount = array('neq','');
        }
        //公司登录
        if($info['type'] == 1){
            $companyAccount = array('eq',$info['account']);
        }
        //员工登录
        if($info['type'] == 2){
            $account = $info['account'];
            $companyId = substr($account,0,5); //公司编号id ，如G4040
            $companyAccount = array('like','%'.$companyId.'%');
        }
        //-------------------------------------------------------------------------------------------------

        //判断自己公司发布的职位是否过期--48小时，如果过期便自动下架
        $all_position = Db::name('CompanyReleaseFullposition')->where(array('companyAccount'=>$companyAccount))->where('isdelete',0)->select();
        $nowTime = time();//当前时间戳
        foreach($all_position as $val){
            $release_time = strtotime($val['release_time']); //职位发布时间
            //若发布时间超过2天下架
            if($nowTime-$release_time>48*60*60){
                $data = array(
                    'isdown' => 1
                );
                Db::name('CompanyReleaseFullposition')->where('id',$val['id'])->update($data);
            }
        }


        //----------------------------------------------------------------------------------------------------------------
        //用于表单------从tp_company_release_position表中获取所有公司（不含重复值）
        $companyNames = Db::name('CompanyReleaseFullposition')->where(array('companyAccount'=>$companyAccount))->where('isdelete',0)->field('companyName')->distinct(true)->select();
        $this->view->assign('companyName',$companyNames);

        //用于表单------获取所有区域（不含重复值）  形如合肥市蜀山
        $city_areas = Db::name('CompanyReleaseFullposition')->where(array('companyAccount'=>$companyAccount))->where('isdelete',0)->field('city,area')->distinct(true)->select();
        $this->view->assign('city_area',$city_areas);
        //------------------------------------------------------------------------------------------------

        //检索数据
        $companyName =  $this->request->param('companyName');//公司名称
        if($companyName == ''){
            $companyName = array('neq',''); //当没有选择检索公司时，默认选择全部公司
        }else{
            $companyName = array('eq',$companyName);
        }

        $city_area = $this->request->param('city_area');//市区
        if($city_area == ''){
            $city = array('neq','');
            $area = array('neq','');
        }else{
            $city_area_array = explode('-',$city_area);
            $city = $city_area_array[0];//市
            $area = $city_area_array[1];//区
            $city = array('eq',$city);
            $area = array('eq',$area);
        }

        $isdown = $this->request->param('isdown');//职位状态------正在上架还是已下架
        if($isdown == ''){
            $isdown = array('neq',100); //当没有检索条件时，默认是检索所有职位
        }else{
            $isdown = array('eq',$isdown); //否则根据检索条件检索
        }
        //将发布的所有职位按检索条件以发布时间降序排序-----默认是检索所有的职位
        $list = Db::name('CompanyReleaseFullposition')->where('isdelete',0)->where(array('companyAccount'=>$companyAccount))->where(array('companyName'=>$companyName))->where(array('city'=>$city))->where(array('area'=>$area))->where(array('isdown'=>$isdown))->order('release_time desc')->select();
        //print_r($list);exit;
        if(!$list){
            $this->view->assign('list','');
            $this->view->assign('count',0);
        }else{
            $this->view->assign('list',$list);
            $this->view->assign('count',count($list));
        }

        //-------------------------------------------------------------------------------------------------------

        //从tp_company_position_enroll表(职位报名表)中查询每个职位对应的报名人数
        $enrollInfo = Db::name('CompanyPositionEnroll')->where(array('companyAccount'=>$companyAccount))->where('position_type',2)->where('isdelete',0)->select();
        $enroll_array = array(); //三维数组，键表示职位id，值是二维数组（数组的个数就是当前职位对应的报名人数），用法：count($enroll_array[pid])
        foreach($enrollInfo as $value){
            $enroll_array[$value['pid']][] = $value;
        }
        //print_r($enroll_array);exit;
        $this->view->assign('enroll_array',$enroll_array);
        //----------------------------------------------------------------------------------------

        //从工作类别表(tp_work_type)中获取所有的工作类别
        $typeInfo = Db::name('WorkType')->select();
        $type_array = array(); //一维数组，键表示类别id，值表示类别名称，形如Array([1]=>传单派发 [2]=>促销/导购  [3]=>钟点工）
        foreach($typeInfo as $value2){
            $type_array[$value2['id']] = $value2['name'];
        }
        //print_r($type_array);exit;   //用法：$type_array['类别id']
        $this->view->assign('type_array',$type_array);


        return $this->view->fetch();
    }


    /*
     * 刷新职位-----即刷新职位的发布时间
     */
    public function refresh()
    {
        if($this->request->param('id')!=''){
            $id = $this->request->param('id');
            $data = array(
                'release_time'=> date('Y-m-d H:i:s',time()),
            );
            $bool = Db::name('CompanyReleaseFullposition')->where('id',$id)->update($data);
            if($bool){
                echo '<script>window.location.href="'.url('CompanyReleaseFullposition/index').'"</script>'; //刷新完毕后跳转到首页
            }
        }
    }


    /*
     * ajax获取传过来的职位id
     * 根据当前职位状态实现上架与下架的状态切换
     */
    public function isdown()
    {
        if($this->request->param('id')!=''){
            $id = $this->request->param('id');
            $positionInfo = Db::name('CompanyReleaseFullposition')->where('id',$id)->find();
            if($positionInfo['isdown'] == 0){
                $data = array(
                    'isdown' => 1   //让该职位下架
                );
                $bool = Db::name('CompanyReleaseFullposition')->where('id',$id)->update($data);
                if($bool){
                    echo 'down';
                }
            }
            if($positionInfo['isdown'] == 1){
                $nowTime = date('Y-m-d H:i:s',time());
                $data = array(
                    'isdown' => 0,    //让该职位上架
                    'release_time' => $nowTime, //同时更新职位发布时间为当前时间
                );
                $bool = Db::name('CompanyReleaseFullposition')->where('id',$id)->update($data);
                if($bool){
                    echo 'up';
                }
            }

        }

    }


    /*
     * 根据用户输入的工作类型名称，从数据表中进行模糊匹配
     */
    public function selectWorkType()
    {
        if($this->request->param('type')!=''){
            $type = $this->request->param('type');
            //查找所有相匹配的类型
            $all_type = Db::name('ProfessionFulltime')->where(array('full_name'=>array('like','%'.$type.'%')))->where('isdelete',0)->select();
            //若检索到了相对应的类型
            if($all_type){
                $type_name_array = array(); //用来存放所有的类型名称
                foreach($all_type as $val){
                    $type_name_array[] = $val['full_name'];
                }
                $type_name = implode(',',$type_name_array);
                echo $type_name;   //形如    汽车陪练,汽车代驾
            }else{
                //若没有检索到,则工作类型名称就是其它
                echo '其它';
            }

        }
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

            $data['companyName'] = $this->request->param('companyName'); //公司名称
            //获取选择的公司名对应的账号
            $schoolInfo = Db::name('SchoolManagement')->where('schoolName',$data['companyName'])->where('status',1)->where('isdelete',0)->find();
            $data['companyAccount'] = $schoolInfo['schoolAccount'];  //公司账号

            $data['province'] = $this->request->param('province');  //省
            $data['city'] = $this->request->param('city');//市
            $data['area'] = $this->request->param('area');//区
            $data['positionName'] = $this->request->param('positionName');//职位名称


            //将工作类型由名称转为对应的类型id
            $type = $this->request->param('type');//工作类别名称
            $typeInfo = Db::name('ProfessionFulltime')->where('full_name',$type)->find();
            $data['type'] = $typeInfo['id']; //对应的类型id

            $data['experience'] = $this->request->param('experience'); //工作经验要求
            $data['education'] = $this->request->param('education'); //学历要求

            $data['count'] = $this->request->param('count'); //招聘人数
            $data['sex'] = $this->request->param('sex'); //性别

            //$salary1 = $this->request->param('salary1'); //最低薪资，如2k
            //$salary2 = $this->request->param('salary2'); //最高薪资，如5k
            //$data['salary'] = $salary1.'-'.$salary2; //薪资范围  如2k-5k
            $data['salary1'] = $this->request->param('salary1');//最低薪资，如2k
            $data['salary2'] = $this->request->param('salary2');//最高薪资，如5k

            //$data['content'] = htmlspecialchars_decode(html_entity_decode($this->request->param("content"))); //内容
            $data['content'] = $this->request->param('content');//工作内容
            $data['requirement'] = $this->request->param('requirement'); //招聘要求
            $data['welfare'] = $this->request->param('welfare'); //福利待遇
            $data['contacts'] = $this->request->param('contacts');//联系人
            $data['phone'] = $this->request->param('phone');//联系电话
            $data['release_time'] = date('Y-m-d H:i:s',time()); //发布时间

            //福利标签
            $label = $this->request->post('label/a'); //传过来的是一维数组,a是一个变量修饰符，说明提交过来的数据是数组

            if($label){
                if(count($label) == 1){
                    $data['label'] = $label[0];
                }else{
                    $data['label'] = implode(',',$label);
                }
            }


            Db::name('CompanyReleaseFullposition')->where('id',$id)->update($data);

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

            //以下是根据当前登录用户找出相应的公司名称
            $info = Db::name("AdminUser")->where("id", UID)->where('isdelete','0')->where('status',1)->find();
            $type = $info['type'];
            //若当前登录用户是超级管理员
            if($type == 0){
                //获取所有公司的名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('isperfect',1)->where('isdelete',0)->select();
            }
            //若当前登录用户是公司
            if($type == 1){
                //获取当前公司的名称-----二维数组
                $schoolName = Db::name("SchoolManagement")->field('schoolName')->where('schoolAccount',$info['account'])->where('isdelete','0')->select();
            }
            //若是员工
            if($type == 2){
                $schoolID = substr($info['account'],0,5);//获取该员工所在的学区编号
                //获取当前员工所在的公司的名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();
            }
            //若是学生
//            if($type == 3){
//                $schoolID = substr($info['account'],0,5);//获取该学生所在的学区编号
//                //获取当前学生所在的校区名称-----二维数组
//                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();
//            }

            $this->view->assign('schoolName',$schoolName);  //二维数组
            //-----------------------------------------------------------------------------------

            //从tp_work_type表中获取所有未删除的工作类别
            /*$all_type = Db::name('WorkType')->where('isdelete',0)->select();
            $this->view->assign('all_type',$all_type);*/

            $parent_type = DB::name('ProfessionFulltime')->where('isdelete',0)->where('parent_id',0)->select();

            $type = [];
            foreach ($parent_type as $key => $value) {
                $type[$key]['parent'] = $value['full_name'];
                $type[$key]['son'] = DB::name('ProfessionFulltime')->where('isdelete',0)->where('parent_id',$value['id'])->select();
            }

            //var_dump($type);exit;
            //$son_type = DB::name('ProfessionFulltime')->where('isdelete',0)->where('parent_id',$parent_id)->select();

            $this->view->assign('type',$type);
            //print_r($parent_type);exit;
            //-----------------------------------------------------------------------------------------

            //将工作类别由数字转为名称
            $all_type2 = Db::name('ProfessionFulltime')->select();
            $typeName = array();
            //var_dump($all_type2);exit;
            foreach($all_type2 as $v){
                $typeName[$v['id']] = $v['full_name'];
            }
            $this->view->assign('type_name',$typeName[$vo['type']]);
            //------------------------------------------------------------------------------------------

            $this->view->assign("vo", $vo);  //当前记录，一维数组


            //将工作经验由数字转为名称
            $experience = $vo['experience'];
            /*if($experience == 1){
                $experience_name = '一年';
            }
            if($experience == 2){
                $experience_name = '两年';
            }
            if($experience == 3){
                $experience_name = '三年';
            }
            if($experience == 4){
                $experience_name = '三年以上';
            }
            if($experience == 5){
                $experience_name = '无经验';
            }*/
            $experience_name = DB::name('WorkexFulltime')->where('work_ex',$experience)->find();
            $experience_name = $experience_name['actually'];
            $experience_all = DB::name('WorkexFulltime')->select();
            $this->view->assign('experience_name',$experience_name);
            $this->view->assign('experience_all',$experience_all);


            //将学历由数字转为名称
            $education = $vo['education'];
            if($education == 1){
                $education_name = '高中';
            }
            if($education == 2){
                $education_name = '专科';
            }
            if($education == 3){
                $education_name = '本科';
            }
            if($education == 4){
                $education_name = '本科以上';
            }
            if($education == 5){
                $education_name = '不限';
            }
            $this->view->assign('education_name',$education_name);


            //将性别由数字转为名称
            $sex = $vo['sex'];
            if($sex == 1){
                $sex_name = '男';
            }
            if($sex == 2){
                $sex_name = '女';
            }
            if($sex == 3){
                $sex_name = '男女不限';
            }
            $this->view->assign('sex_name',$sex_name);


            //将当前薪资如2k-5k分成最低薪资2k，和最高薪资5k
            $salary1 = $vo['salary1']; //2k
            $salary2 = $vo['salary2']; //5k
            $this->view->assign('salary1',$salary1);
            $this->view->assign('salary2',$salary2);


            //获取福利标签
            $label = $vo['label'];
            $label_arr = array();
            if($label!=''){
                $label_array = explode(',',$label);
                if(count($label_array) == 1){
                    $label_arr[] = $label;  //array('五险一金')
                }else{
                    $label_arr = $label_array;  //array（'包吃','包住'）
                }
            }else{
                $label_arr = array();
            }
            $this->view->assign('label_arr',$label_arr);




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

            $data['companyName'] = $this->request->param('companyName'); //公司名称
            //获取选择的公司名对应的账号
            $schoolInfo = Db::name('SchoolManagement')->where('schoolName',$data['companyName'])->where('status',1)->where('isdelete',0)->find();
            $data['companyAccount'] = $schoolInfo['schoolAccount'];  //公司账号

            $data['province'] = $this->request->param('province');  //省
            $data['city'] = $this->request->param('city');//市
            $data['area'] = $this->request->param('area');//区
            $data['positionName'] = $this->request->param('positionName');//职位名称


            //将工作类型由名称转为对应的类型id
            $type = $this->request->param('type');//工作类别名称
            $typeInfo = Db::name('ProfessionFulltime')->where('full_name',$type)->find();
            $data['type'] = $typeInfo['id']; //对应的类型id

            $data['experience'] = $this->request->param('experience'); //工作经验要求
            $data['education'] = $this->request->param('education'); //学历要求

            $data['count'] = $this->request->param('count'); //招聘人数
            $data['sex'] = $this->request->param('sex'); //性别

            $salary1 = $this->request->param('salary1'); //最低薪资，如2k
            $salary2 = $this->request->param('salary2'); //最高薪资，如5k
            $data['salary1'] = $salary1; 
            $data['salary2'] = $salary2; 

            //$data['content'] = htmlspecialchars_decode(html_entity_decode($this->request->param("content"))); //内容
            $data['content'] = $this->request->param('content'); //工作内容
            $data['requirement'] = $this->request->param('requirement'); //招聘要求
            $data['welfare'] = $this->request->param('welfare'); //福利待遇
            $data['contacts'] = $this->request->param('contacts'); //联系人
            $data['phone'] = $this->request->param('phone');//联系电话
            $data['release_time'] = date('Y-m-d H:i:s',time()); //发布时间

            //福利标签，如果没有选择标签，则传过来的是一个空数组
            $label = $this->request->post('label/a'); //传过来的是一维数组,a是一个变量修饰符，说明提交过来的数据是数组

            if($label){
               if(count($label) == 1){
                   $data['label'] = $label[0];  //传过来的只有一个标签，如：五险一金
               }else{
                   $data['label'] = implode(',',$label); //传过来的是多个标签时，则用逗号分隔，如五险一金，年底双薪
               }
            }


            Db::name('CompanyReleaseFullposition')->insert($data);


            return ajax_return_adv('添加成功');
        } else {
            //以下是根据当前登录用户找出相应的公司名称
            $info = Db::name("AdminUser")->where("id", UID)->where('isdelete','0')->where('status',1)->find();
            $type = $info['type'];
            //若当前登录用户是超级管理员
            if($type == 0){
                //获取所有公司的名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('isperfect',1)->where('isdelete',0)->select();
            }
            //若当前登录用户是公司
            if($type == 1){
                //获取当前公司的名称-----二维数组
                $schoolName = Db::name("SchoolManagement")->field('schoolName')->where('schoolAccount',$info['account'])->where('isdelete','0')->select();
            }
            //若是员工
            if($type == 2){
                $schoolID = substr($info['account'],0,5);//获取该员工所在的学区编号
                //获取当前员工所在的公司的名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();
            }
            //若是学生
//            if($type == 3){
//                $schoolID = substr($info['account'],0,5);//获取该学生所在的学区编号
//                //获取当前学生所在的校区名称-----二维数组
//                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();
//            }

            $this->view->assign('schoolName',$schoolName);  //二维数组


            //从tp_work_type表中获取所有的工作类别
            /*$all_type = Db::name('WorkType')->where('isdelete',0)->select();
            $this->view->assign('all_type',$all_type);*/
            $parent_type = DB::name('ProfessionFulltime')->where('isdelete',0)->where('parent_id',0)->select();

            $type = [];
            foreach ($parent_type as $key => $value) {
                $type[$key]['parent'] = $value['full_name'];
                $type[$key]['son'] = DB::name('ProfessionFulltime')->where('isdelete',0)->where('parent_id',$value['id'])->select();
            }

            //$son_type = DB::name('ProfessionFulltime')->where('isdelete',0)->where('parent_id',$parent_id)->select();

            $this->view->assign('type',$type);

            $experience_all = DB::name('WorkexFulltime')->select();
            $this->view->assign('experience_all',$experience_all);

            $this->view->assign('label_arr',array());  //福利标签会用到，默认是一个空数组


            $vo =['content' => ''];
            $this->view->assign("vo", $vo);
            // 添加
            return $this->view->fetch(isset($this->template) ? $this->template : 'fsedit');
        }
    }
}
