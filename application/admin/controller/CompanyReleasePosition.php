<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

//公司发布兼职----也是兼职列表
class CompanyReleasePosition extends Controller
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

        //echo session('user_name');exit;
        //echo UID;exit;
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
        $all_position = Db::name('CompanyReleasePosition')->where(array('companyAccount'=>$companyAccount))->where('isdelete',0)->select();
        $nowTime = time();//当前时间戳
        foreach($all_position as $val){
            $release_time = strtotime($val['release_time']); //职位发布时间
            //若发布时间超过2天下架
            if($nowTime-$release_time>48*60*60){
                $data = array(
                    'isdown' => 1
                );
                Db::name('CompanyReleasePosition')->where('id',$val['id'])->update($data);
            }
        }


        //----------------------------------------------------------------------------------------------------------------
        //用于表单------从tp_company_release_position表中获取所有公司（不含重复值）
        $companyNames = Db::name('CompanyReleasePosition')->where(array('companyAccount'=>$companyAccount))->where('isdelete',0)->field('companyName')->distinct(true)->select();
        $this->view->assign('companyName',$companyNames);

        //用于表单------获取所有区域（不含重复值）  形如合肥市蜀山
        $city_areas = Db::name('CompanyReleasePosition')->where(array('companyAccount'=>$companyAccount))->where('isdelete',0)->field('city,area')->distinct(true)->select();
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
        $list = Db::name('CompanyReleasePosition')->where('isdelete',0)->where(array('companyAccount'=>$companyAccount))->where(array('companyName'=>$companyName))->where(array('city'=>$city))->where(array('area'=>$area))->where(array('isdown'=>$isdown))->order('release_time desc')->select();
        //print_r($list);exit;
        if(!$list){
            $this->view->assign('list','');
            $this->view->assign('count',0);
        }else{
            $this->view->assign('list',$list);
            $this->view->assign('count',count($list));
        }

        //-------------------------------------------------------------------------------------------------------

        //从tp_company_position_enroll表(职位报名表)中查询每个职位对应的报名人数-----在这里查询发布的所有兼职对应的报名人数
        $enrollInfo = Db::name('CompanyPositionEnroll')->where(array('companyAccount'=>$companyAccount))->where('position_type',1)->where('isdelete',0)->select();
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
            $bool = Db::name('CompanyReleasePosition')->where('id',$id)->update($data);
            if($bool){
                echo '<script>window.location.href="'.url('CompanyReleasePosition/index').'"</script>'; //刷新完毕后跳转到首页
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
            $positionInfo = Db::name('CompanyReleasePosition')->where('id',$id)->find();
            if($positionInfo['isdown'] == 0){
                $data = array(
                    'isdown' => 1   //让该职位下架
                );
                $bool = Db::name('CompanyReleasePosition')->where('id',$id)->update($data);
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
                $bool = Db::name('CompanyReleasePosition')->where('id',$id)->update($data);
                if($bool){
                    echo 'up';
                }
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
		//	return ajax_return_adv_error($data['companyName']);
			$data['work_unit'] = $this->request->param('work_unit'); //用工单位
            //获取选择的公司名对应的账号
            $schoolInfo = Db::name('SchoolManagement')->where('schoolName',$data['companyName'])->where('status',1)->where('isdelete',0)->find();
            $money = $schoolInfo['money'];//公司账户余额
            $all_salary = $this->request->param('all_salary');//获取需要充值的金额
            

            $ischecked = $schoolInfo['ischecked'];//获取公司是否注册
            if($ischecked || ($all_salary<=$money)){

            $data['companyAccount'] = $schoolInfo['schoolAccount'];  //公司账号
			$workInfo = Db::name('WorkUnit')->where('work_unit',$data['work_unit'])->where('status',1)->where('isdelete',0)->find();
            $data['work_id'] = $workInfo['id'];  //用工单位
           // $data['work_unit'] = $workInfo['work_unit'];  //用工单位
            $data['province'] = $this->request->param('province');  //省
            $data['city'] = $this->request->param('city');//市
            $data['area'] = $this->request->param('area');//区
            $data['work_place'] = $this->request->param('work_place');//工作地点
			$data['work_place_lng'] = $this->request->param('work_place_lng');//工作地点的经度和纬度
            $data['work_place_lat'] = $this->request->param('work_place_lat');
            $data['property'] = $this->request->param('property');//职位属性
            $data['positionName'] = $this->request->param('positionName');//职位名称
            $data['type'] = $this->request->param('type');//工作类别
            $data['start_time'] = $this->request->param('start_time');//开始时间
            $data['end_time'] = $this->request->param('end_time');//开始时间
            $data['period'] = $this->request->param('period'); //兼职时段
			$data['gather_time'] = $this->request->param('gather_time'); //集合时间
			$data['gather_date'] = $this->request->param('gather_date'); //集合地点
            $data['gather_place'] = $this->request->param('gaode'); //集合地点
			$data['gather_place_lng'] = $this->request->param('gather_place_lng');//集合地点的经度和纬度
            $data['gather_place_lat'] = $this->request->param('gather_place_lat');
            $data['count'] = $this->request->param('count'); //招聘人数
            $data['sex'] = $this->request->param('sex'); //性别
			$data['up_work'] = $this->request->param('up_work'); //上班时间
            $data['off_work'] = $this->request->param('off_work'); //下班时间
			$data['work_days'] = $this->request->param('work_days'); //天数
            $data['work_hours'] = $this->request->param('work_hours'); //时长

            $data['salary'] = $this->request->param('salary'); //薪资待遇
            $data['unit'] = $this->request->param('unit'); //薪资单位
            $data['payroll'] = $this->request->param('payroll'); //结薪方式
            $data['requirement'] = $this->request->param('requirement'); //招聘要求
            $data['content'] = $this->request->param('content');//工作内容
            $data['contacts'] = $this->request->param('contacts');//联系人
            $data['phone'] = $this->request->param('phone');//联系电话
            $data['label'] = $this->request->param('label');

            //$data['content'] = htmlspecialchars_decode(html_entity_decode($this->request->param("content"))); //内容
            $data['release_time'] = date('Y-m-d H:i:s',time());//发布时间


            Db::name('CompanyReleasePosition')->where('id',$id)->update($data);

            return ajax_return_adv("编辑成功");
            }else{
                return ajax_return_adv_error("请先充值");
            }
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
                $schoolName = Db::name('SchoolManagement')->field('schoolName,schoolAccount,money')->where('isperfect',1)->where('isdelete',0)->find();
				$work = Db::name('WorkUnit')->field('work_unit')->where('isdelete',0)->select();
            }
            //若当前登录用户是公司
            if($type == 1){
                //获取当前公司的名称-----二维数组
                $schoolName = Db::name("SchoolManagement")->field('schoolName,schoolAccount,money')->where('schoolAccount',$info['account'])->where('isdelete','0')->find();
				//var_dump($schoolName);
			//	return ajax_return_adv_error($schoolName);
				$work = Db::name('WorkUnit')->field('work_unit')->where('companyAccount',$info['account'])->where('isdelete',0)->select();
            }
            //若是员工
            if($type == 2){
                $schoolID = substr($info['account'],0,5);//获取该员工所在的学区编号
                //获取当前员工所在的公司的名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName,schoolAccount,money')->where('schoolID',$schoolID)->where('isdelete',0)->find();
				$work = Db::name('WorkUnit')->field('work_unit')->where('companyAccount',$info['account'])->where('isdelete',0)->select();
            }

            $money = $schoolName['money'];
            $this->view->assign('money',$money);

            $this->view->assign('schoolName',$schoolName);  //二维数组
			$this->view->assign('work',$work);  //二维数组
			//$this->view->assign('work',$work);  //二维数组
            //-----------------------------------------------------------------------------------

            //从tp_work_type表中获取所有未删除的工作类别
            $all_type = Db::name('WorkType')->where('isdelete',0)->select();
            $this->view->assign('all_type',$all_type);
            //-----------------------------------------------------------------------------------------

            //将工作类别由数字转为名称
            $all_type2 = Db::name('WorkType')->select();
            $typeName = array();
            foreach($all_type2 as $v){
                $typeName[$v['id']] = $v['name'];
            }
            $this->view->assign('type_name',$typeName[$vo['type']]);
            //------------------------------------------------------------------------------------------

            $this->view->assign("vo", $vo);  //当前记录，一维数组

            //将职位属性由数字转为名称
            $property = $vo['property']; //当前兼职类别 值为1,2,3,4
            if($property == 1){
                $property_name = '日结兼职';
            }
            if($property == 2){
                $property_name = '长期兼职';
            }
            if($property == 3){
                $property_name = '实习兼职';
            }
            if($property == 4){
                $property_name = '旅行兼职';
            }
            $this->view->assign('property_name',$property_name);

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


            //将兼职时段由数字转为名称
            $period = $vo['period'];
            if($period == 1){
                $period_name = '每天';
            }
            if($period == 2){
                $period_name = '工作日';
            }
            if($period == 3){
                $period_name = '双休日';
            }
            $this->view->assign('period_name',$period_name);


            //将结薪方式由数字转为名称
            $payroll = $vo['payroll'];
            if($payroll == 0){
                $payroll_name = '日结';
            }
            if($payroll == 1){
                $payroll_name = '周结';
            }
            if($payroll == 2){
                $payroll_name = '月结';
            }
            $this->view->assign('payroll_name',$payroll_name);



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
			$data['work_unit'] = $this->request->param('work_unit'); //公司名称
            //获取选择的公司名对应的账号
            $schoolInfo = Db::name('SchoolManagement')->where('schoolName',$data['companyName'])->where('status',1)->where('isdelete',0)->find();

            $money = $schoolInfo['money'];//公司账户余额

            $ischecked = $schoolInfo['ischecked'];//获取公司是否注册

            $all_salary = $this->request->param('all_salary');//获取需要充值的金额
            if($ischecked || ($all_salary<=$money)){

			$data['companyAccount'] = $schoolInfo['schoolAccount'];  //公司账号
			$workInfo = Db::name('WorkUnit')->where('work_unit',$data['work_unit'])->where('status',1)->where('isdelete',0)->find();
            $data['work_id'] = $workInfo['id'];  //用工单位
			//$data['work_unit'] = $workInfo['id'];  //用工单位

            $data['province'] = $this->request->param('province');  //省
            $data['city'] = $this->request->param('city');//市
            $data['area'] = $this->request->param('area');//区
            $data['work_place'] = $this->request->param('work_place');//工作地点
			$data['work_place_lng'] = $this->request->param('work_place_lng');//工作地点的经度和纬度
            $data['work_place_lat'] = $this->request->param('work_place_lat');
            $data['property'] = $this->request->param('property');//职位属性
            $data['positionName'] = $this->request->param('positionName');//职位名称
            $data['type'] = $this->request->param('type');//工作类别
            $data['start_time'] = $this->request->param('start_time');//开始时间
            $data['end_time'] = $this->request->param('end_time');//开始时间
            $data['period'] = $this->request->param('period'); //兼职时段
            $data['gather_date'] = $this->request->param('gather_date'); //兼职时段
			$data['gather_time'] = $this->request->param('gather_time'); //集合时间
            $data['gather_place'] = $this->request->param('gaode'); //集合地点
			$data['gather_place_lng'] = $this->request->param('gather_place_lng');//集合地点的经度和纬度
            $data['gather_place_lat'] = $this->request->param('gather_place_lat');
            $data['count'] = $this->request->param('count'); //招聘人数
            $data['sex'] = $this->request->param('sex'); //性别
			
			$data['up_work'] = $this->request->param('up_work'); //上班时间
            $data['off_work'] = $this->request->param('off_work'); //下班时间
			$data['work_days'] = $this->request->param('work_days'); //天数
            $data['work_hours'] = $this->request->param('work_hours'); //时长


            $data['salary'] = $this->request->param('salary'); //薪资待遇
            $data['unit'] = $this->request->param('unit'); //薪资单位
            $data['payroll'] = $this->request->param('payroll'); //结薪方式
            $data['requirement'] = $this->request->param('requirement'); //招聘要求
            $data['content'] = $this->request->param('content');//工作内容
            $data['contacts'] = $this->request->param('contacts');//联系人
            $data['phone'] = $this->request->param('phone');//联系电话
            $data['label'] = $this->request->param('label');

            //$data['content'] = htmlspecialchars_decode(html_entity_decode($this->request->param("content"))); //内容
            $data['release_time'] = date('Y-m-d H:i:s',time()); //发布时间



            Db::name('CompanyReleasePosition')->insert($data);


            return ajax_return_adv('添加成功');
            }else{
            return ajax_return_adv_error("请先充值"); 
            }
        } else {

            //以下是根据当前登录用户找出相应的公司名称
            $info = Db::name("AdminUser")->where("id", UID)->where('isdelete','0')->where('status',1)->find();
            $type = $info['type'];
       
            //若当前登录用户是超级管理员
            if($type == 0){
                //获取所有公司的名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName,schoolAccount,money')->where('isperfect',1)->where('isdelete',0)->find();
				$work = Db::name('WorkUnit')->field('work_unit')->where('isdelete',0)->select();
            }
            //若当前登录用户是公司
            if($type == 1){
                //获取当前公司的名称-----二维数组
                $schoolName = Db::name("SchoolManagement")->field('schoolName,schoolAccount,money')->where('schoolAccount',$info['account'])->where('isdelete','0')->find();
				//var_dump($schoolName);
			//	return ajax_return_adv_error($schoolName);
				$work = Db::name('WorkUnit')->field('work_unit')->where('companyAccount',$info['account'])->where('isdelete',0)->select();
            }
            //若是员工
            if($type == 2){
                $schoolID = substr($info['account'],0,5);//获取该员工所在的学区编号
                //获取当前员工所在的公司的名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName,schoolAccount')->where('schoolID',$schoolID)->where('isdelete',0)->find();
				$work = Db::name('WorkUnit')->field('work_unit')->where('companyAccount',$info['account'])->where('isdelete',0)->select();
            }

            //获取公司余额
            $money = $schoolName['money'];
            $this->view->assign('money',$money);

            $this->view->assign('schoolName',$schoolName);  //二维数组
			$this->view->assign('work',$work);  //二维数组


            //从tp_work_type表中获取所有的工作类别
            $all_type = Db::name('WorkType')->where('isdelete',0)->select();
            $this->view->assign('all_type',$all_type);


            $vo =['content' => ''];
            $this->view->assign("vo", $vo);
            // 添加
            return $this->view->fetch(isset($this->template) ? $this->template : 'fsedit');
        }
    }
	
	public function wadd()
    {

        //$controller = $this->request->controller();

        if ($this->request->isPost()) {
            // 插入
            $info = Db::name("AdminUser")->where("id", UID)->where('isdelete','0')->where('status',1)->find();
            
       
            $data['companyAccount'] = $info['account']; //
            $data['work_unit'] = $this->request->param('work_unit'); //用工单位
            $data['release_time'] = date('Y-m-d H:i:s');//发表时间


            $re = Db::name('WorkUnit')->insert($data);
			echo $re;
			
          
        } else {

            // //以下是根据当前登录用户找出相应的初中校区名称
            // $info = Db::name("AdminUser")->where("id", UID)->where('isdelete','0')->where('status',1)->find();
            // $type = $info['type'];
            // //若当前登录用户是超级管理员
            // if($type == 0){
                // //获取所有初中学区的名称-----二维数组
                // $schoolName = Db::name('SchoolManagement')->field('schoolName')->where(array('schoolAccount'=>array('like','%C%')))->where('isdelete',0)->select();
            // }
            // //若当前登录用户是校区
            // if($type == 1){
                // //获取当前校区的名称-----二维数组
                // $schoolName = Db::name("SchoolManagement")->field('schoolName')->where('schoolAccount',$info['account'])->where('isdelete','0')->select();
            // }
            // //若是教师
            // if($type == 2){
                // $schoolID = substr($info['account'],0,5);//获取该教师所在的学区编号
                // //获取当前教师所在的校区的名称-----二维数组
                // $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();
            // }
            // //若是学生
            // if($type == 3){
                // $schoolID = substr($info['account'],0,5);//获取该学生所在的学区编号
                // //获取当前学生所在的校区名称-----二维数组
                // $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();
            // }

            // $this->view->assign('schoolName',$schoolName);  //二维数组




            // $vo =['content' => ''];
            // $this->view->assign("vo", $vo);
            // 添加
            return $this->view->fetch();
        }
    }

}
