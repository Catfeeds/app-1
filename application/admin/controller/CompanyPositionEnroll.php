<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

//报名信息
class CompanyPositionEnroll extends Controller
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

    }

    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        $model = $this->getModel();

        // 列表过滤器，生成查询Map对象
        $map = $this->search($model, [$this->fieldIsDelete => $this::$isdelete,'ishire' =>array('in','1,0')]);
        // 特殊过滤器，后缀是方法名的
        $actionFilter = 'filter' . $this->request->action();
        if (method_exists($this, $actionFilter)) {
            $this->$actionFilter($map);
        }

        // 自定义过滤器
        if (method_exists($this, 'filter')) {
            $this->filter($map);
        }
		
		
        $model = Db::name("CompanyPositionEnroll");
		$data = $this->datalist($model, $map,'','','','true');
		$info1 = Db::name('AdminUser')->where('id',UID)->find(); //获取当前登陆者信息
        $list = array();
         //   $list2 = array();
        // foreach ($data as $key =>$value){
            // $CompanyReleasePosition = Db::name("CompanyReleasePosition")->where('isdelete','0')->where('id',$value['pid'])->find();//查找联系人
			// $certification = Db::name("certification")->where('isdelete','0')->where('user_id',$value['user_id'])->find();//获取认证资料
			// $info = Db::name("patriarch")->where('isdelete','0')->where('user_id',$value['user_id'])->find();//获取注册信息
			// //$image =  Db::name("File")->where('id',$EmployeeAccessManagement['face_file_ids'])->find();   //联系人门禁照片
   
	
	    // if($value['position_type']==1){
			// $position_type = '兼职';
		// }elseif($value['position_type']==2){
			// $position_type = '全职';
		// }
		
		// if($certification['sex']==0){
			// $sex = '男';
		// }elseif($certification['sex']==1){
			// $sex = '女';
		// }
		
		// $year = substr($certification['cardID'],6,4);
		// //return ajax_return_adv($year);
        // $age = date('Y')-$year;
		// $list[] = array(
				// 'id' => $value['id'],
				// 'companyName' => $value['companyName'],
				// 'area' => $value['area'],
				// 'positionName' => $CompanyReleasePosition['positionName'],
				// 'position_type' => $position_type,
				// 'sex' => $sex,
				// 'name' => $certification['name'],
				// 'phone' => $info['phone'],
				// 'age' => $age,
				// 'enroll_time' => $value['enroll_time'],
				// 'score' => $info['score'],
				// 'ishire' => $value['ishire'],
				// 'user_id' => $value['user_id'],
				// 'status' => $value['status'],
			// ); 
        // }
		
		foreach ($data as $key =>$value){
            if($value['ishire']==1){
                if(strtotime($value['gather_date'])+24*60*60<time()){
					//return ajax_return_adv_error(strtotime($value['gather_date'])+24*60*60);
                    $info2 = Db::name("CompanyPositionEnroll")->where('isdelete','0')->where('id',$value['id'])->update(['ishire'=>4]);//
                    $info3 = Db::name("patriarch")->where('isdelete','0')->where('status','1')->where('user_id',$value['user_id'])->find();//
                    $score = $info3['score']-30;
                    if($score>=0){
                        $data2['score'] = $score;
                    }else{
                        $data2['score'] = 0;
                    }
                            
                    DB::name('patriarch')->where('user_id',$value['user_id'])->where('status',1)->where('isdelete',0)->update($data2);
                  //  Db::name("patriarch")->where('isdelete','0')->where('status','1')->where('user_id',$value['user_id'])->find();//







                }else{
                    $CompanyReleasePosition = Db::name("CompanyReleasePosition")->where('isdelete','0')->where('id',$value['pid'])->find();//查找联系人
					$certification = Db::name("certification")->where('isdelete','0')->where('user_id',$value['user_id'])->find();//获取认证资料
					$info = Db::name("patriarch")->where('isdelete','0')->where('user_id',$value['user_id'])->find();//获取注册信息
					//$image =  Db::name("File")->where('id',$EmployeeAccessManagement['face_file_ids'])->find();   //联系人门禁照片
		   
			
					if($value['position_type']==1){
						$position_type = '兼职';
					}elseif($value['position_type']==2){
						$position_type = '全职';
					}
					
					if($certification['sex']==0){
						$sex = '男';
					}elseif($certification['sex']==1){
						$sex = '女';
					}
					
					$year = substr($certification['cardID'],6,4);
					//return ajax_return_adv($year);
					$age = date('Y')-$year;
					$list[] = array(
							'id' => $value['id'],
							'companyName' => $value['companyName'],
							'area' => $value['area'],
							'positionName' => $CompanyReleasePosition['positionName'],
							'position_type' => $position_type,
							'sex' => $sex,
							'name' => $certification['name'],
							'phone' => $info['phone'],
							'age' => $age,
							'enroll_time' => $value['enroll_time'],
							'score' => $info['score'],
							'ishire' => $value['ishire'],
							'user_id' => $value['user_id'],
							'status' => $value['status'],
						); 

						}
					}else{
					
					$CompanyReleasePosition = Db::name("CompanyReleasePosition")->where('isdelete','0')->where('id',$value['pid'])->find();//查找联系人
					$certification = Db::name("certification")->where('isdelete','0')->where('user_id',$value['user_id'])->find();//获取认证资料
					$info = Db::name("patriarch")->where('isdelete','0')->where('user_id',$value['user_id'])->find();//获取注册信息
					//$image =  Db::name("File")->where('id',$EmployeeAccessManagement['face_file_ids'])->find();   //联系人门禁照片
		   
			
					if($value['position_type']==1){
						$position_type = '兼职';
					}elseif($value['position_type']==2){
						$position_type = '全职';
					}
					
					if($certification['sex']==0){
						$sex = '男';
					}elseif($certification['sex']==1){
						$sex = '女';
					}
					
					$year = substr($certification['cardID'],6,4);
					//return ajax_return_adv($year);
					$age = date('Y')-$year;
					$list[] = array(
							'id' => $value['id'],
							'work_unit' => $value['work_unit'],
							'area' => $value['area'],
							'positionName' => $CompanyReleasePosition['positionName'],
							'position_type' => $position_type,
							'sex' => $sex,
							'name' => $certification['name'],
							'phone' => $info['phone'],
							'age' => $age,
							'enroll_time' => $value['enroll_time'],
							'score' => $info['score'],
							'ishire' => $value['ishire'],
							'user_id' => $value['user_id'],
							'status' => $value['status'],
						); 
					}
        }

		$this->view->assign('list', $list);
       // $companyNames = Db::name('CompanyPositionEnroll')->where('isdelete',0)->field('companyName')->distinct(true)->select();
       // $this->view->assign('companyName',$companyNames);
         //超级管理员
        if($info1['type'] == 0){
       
           // $vo = Db::name('CompanyPositionEnroll')->where('ishire',3)->where('status',1)->where('isdelete',0)->select();//已工作
            $work = Db::name('WorkUnit')->where('status',1)->where('isdelete',0)->select();//用工企业
          

        }
        //公司登录
        if($info1['type'] == 1){

            //$vo = Db::name('CompanyPositionEnroll')->where('companyAccount',$info['account'])->where('ishire',3)->where('status',1)->where('isdelete',0)->select();//已工作
            $work = Db::name('WorkUnit')->where('companyAccount',$info1['account'])->where('status',1)->where('isdelete',0)->select(); //用工企业
        

        }
        $this->view->assign('work',$work);
        //用于表单-----获取所有区域（不含重复值）
        $area = Db::name('CompanyPositionEnroll')->where('isdelete',0)->field('area')->distinct(true)->select();
        $this->view->assign('area',$area);
        return $this->view->fetch();
    }


    /**
     * 查看个人简历
     */
    public function selectResume()
    {
        if($this->request->param('user_id')!=''){
            $user_id = $this->request->param('user_id');
            //echo '查看'.$user_id.'的个人简历!';
            return $this->view->fetch();
        }

    }

    /**
     * 利用ajax获取传过来的报名id
     * 根据当前录用情况，实现录用与不录用状态的切换
     */
    public function ishire()
    {
         if($this->request->param('id')!=''){
             $id = $this->request->param('id'); //当前报名信息id
             $enrollInfo = Db::name('CompanyPositionEnroll')->where('id',$id)->find();
             if($enrollInfo['ishire'] == 0){
                 $data = array(
                     'ishire' => 1
                 );
                 $bool = Db::name('CompanyPositionEnroll')->where('id',$id)->update($data);
                 if($bool){
                     echo 'hire';
                 }
             }
             if($enrollInfo['ishire'] == 1){
                 $data = array(
                     'ishire' => 0
                 );
                 $bool = Db::name('CompanyPositionEnroll')->where('id',$id)->update($data);
                 if($bool){
                     echo 'nohire';
                 }
             }
         }

    }

//确认邀约给录用和未录用的用户发送短信
    public function add()
    {   
        
        if($this->request->isAjax()){
           $ids = $this->request->param('id/a');
           $noids = $this->request->param('noid/a');
           $sendMsg = new SendMsg();
           //给被雇佣的用户发送短信
            foreach ($ids as $key => $value) {
            $data = array(
                'ishire' => 1,
                'hire_time' => time()
            );
            $user_info = DB::name('CompanyPositionEnroll')->where('id',$ids[$key])->field('user_id,companyName,pid')->where('ishire',0)->find();
            $update_employ_status = DB::name('CompanyPositionEnroll')->where('id',$ids[$key])->where('ishire',0)->update($data);

         //   $user_info = DB::name('CompanyPositionEnroll')->where('id',$ids[$key])->field('user_id,companyName,pid')->where('ishire',0)->find();
            $user_id = $user_info['user_id'];
            $companyName = $user_info['companyName'];
            $pid = $user_info['pid'];
            //$companyName = "XXX";

            $user_phone = DB::name('Patriarch')->where('user_id',$user_id)->field('phone')->find();//获取申请的用户号码
            $user_phone = $user_phone['phone'];

            $positionInfo = DB::name('CompanyReleasePosition')->where('id',$pid)->field('positionName')->find();
            $positionName = $positionInfo['positionName'];
            
            $sendResult = $sendMsg->sendMsg($user_phone,$companyName,$positionName);

            }
            //给没被雇佣的用户发送短信
            if(!empty($noids) ){
                foreach ($noids as $key => $value) {
                    if($value !== 'undefined'){
                        $data = array(
                            'ishire' => 2,
                            'hire_time' => time()
                        );
                        $user_info = DB::name('CompanyPositionEnroll')->where('id',$noids[$key])->field('user_id,companyName,pid')->where('ishire',0)->find();
                        if(!empty($user_info)){
                            $update_employ_status = DB::name('CompanyPositionEnroll')->where('id',$noids[$key])->where('ishire',0)->update($data);

                            $user_id = $user_info['user_id'];
                            $companyName = $user_info['companyName'];
                            $pid = $user_info['pid'];
                            

                            //$companyName = "XXX";

                            $user_phone = DB::name('Patriarch')->where('user_id',$user_id)->field('phone')->find();//获取申请的用户号码
                            $user_phone = $user_phone['phone'];

                            $positionInfo = DB::name('CompanyReleasePosition')->where('id',$pid)->field('positionName')->find();
                            $positionName = $positionInfo['positionName'];
                            //获取用户申请职位信息
                            
                            $sendResult = $sendMsg->sendMsgTwo($user_phone,$companyName,$positionName);
                        }
                        
                            }
                

                }
            }
            

        return json($noids);
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
//        if ($this->request->isPost()) {
//            // 更新
//            $id = $this->request->param('id');
//
//            $admininfo = Db::name("AdminUser")->where("id", UID)->find();
//            $data['release'] = $admininfo['realname']; //发布者姓名
//
//            $data['companyName'] = $this->request->param('companyName'); //公司名称
//            //获取选择的公司名对应的账号
//            $schoolInfo = Db::name('SchoolManagement')->where('schoolName',$data['companyName'])->where('status',1)->where('isdelete',0)->find();
//            $data['companyAccount'] = $schoolInfo['schoolAccount'];  //公司账号
//
//            $data['province'] = $this->request->param('province');  //省
//            $data['city'] = $this->request->param('city');//市
//            $data['area'] = $this->request->param('area');//区
//            $data['property'] = $this->request->param('property');//职位属性
//            $data['positionName'] = $this->request->param('positionName');//职位名称
//            $data['type'] = $this->request->param('type');//工作类别
//            $data['start_time'] = $this->request->param('start_time');//开始时间
//            $data['end_time'] = $this->request->param('end_time');//开始时间
//            $data['period'] = $this->request->param('period'); //兼职时段
//            $data['count'] = $this->request->param('count'); //招聘人数
//
//            $data['salary'] = $this->request->param('salary'); //薪资待遇
//            $data['payroll'] = $this->request->param('payroll'); //结薪方式
//            $data['requirement'] = $this->request->param('requirement'); //招聘要求
//            $data['content'] = $this->request->param('content');//工作内容
//            $data['label'] = $this->request->param('label');
//
//            //$data['content'] = htmlspecialchars_decode(html_entity_decode($this->request->param("content"))); //内容
//            $data['release_time'] = date('Y-m-d H:i:s',time()); //发布时间
//
//
//            Db::name('CompanyReleasePosition')->where('id',$id)->update($data);
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
//            //以下是根据当前登录用户找出相应的公司名称
//            $info = Db::name("AdminUser")->where("id", UID)->where('isdelete','0')->where('status',1)->find();
//            $type = $info['type'];
//            //若当前登录用户是超级管理员
//            if($type == 0){
//                //获取所有公司的名称-----二维数组
//                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('isdelete',0)->select();
//            }
//            //若当前登录用户是公司
//            if($type == 1){
//                //获取当前公司的名称-----二维数组
//                $schoolName = Db::name("SchoolManagement")->field('schoolName')->where('schoolAccount',$info['account'])->where('isdelete','0')->select();
//            }
//            //若是员工
//            if($type == 2){
//                $schoolID = substr($info['account'],0,5);//获取该员工所在的学区编号
//                //获取当前教师所在的校区的名称-----二维数组
//                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();
//            }
//            //若是学生
////            if($type == 3){
////                $schoolID = substr($info['account'],0,5);//获取该学生所在的学区编号
////                //获取当前学生所在的校区名称-----二维数组
////                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();
////            }
//
//            $this->view->assign('schoolName',$schoolName);  //二维数组
//
//
//
//            $this->view->assign("vo", $vo);
//
//
//            return $this->view->fetch();
//        }
//    }


    /**
     * 添加
     * @return mixed
     */
//    public function fadd()
//    {
//        //$controller = $this->request->controller();
//
//        if ($this->request->isPost()) {
//            // 插入
//
//            $admininfo = Db::name("AdminUser")->where("id", UID)->find();
//            $data['release'] = $admininfo['realname']; //发布者姓名
//
//            $data['companyName'] = $this->request->param('companyName'); //公司名称
//            //获取选择的公司名对应的账号
//            $schoolInfo = Db::name('SchoolManagement')->where('schoolName',$data['companyName'])->where('status',1)->where('isdelete',0)->find();
//            $data['companyAccount'] = $schoolInfo['schoolAccount'];  //公司账号
//
//            $data['province'] = $this->request->param('province');  //省
//            $data['city'] = $this->request->param('city');//市
//            $data['area'] = $this->request->param('area');//区
//            $data['property'] = $this->request->param('property');//职位属性
//            $data['positionName'] = $this->request->param('positionName');//职位名称
//            $data['type'] = $this->request->param('type');//工作类别
//            $data['start_time'] = $this->request->param('start_time');//开始时间
//            $data['end_time'] = $this->request->param('end_time');//开始时间
//            $data['period'] = $this->request->param('period'); //兼职时段
//            $data['count'] = $this->request->param('count'); //招聘人数
//
//            $data['salary'] = $this->request->param('salary'); //薪资待遇
//            $data['payroll'] = $this->request->param('payroll'); //结薪方式
//            $data['requirement'] = $this->request->param('requirement'); //招聘要求
//            $data['content'] = $this->request->param('content');//工作内容
//            $data['label'] = $this->request->param('label');
//
//            //$data['content'] = htmlspecialchars_decode(html_entity_decode($this->request->param("content"))); //内容
//            $data['release_time'] = date('Y-m-d H:i:s',time());//发布时间
//
//
//
//            Db::name('CompanyReleasePosition')->insert($data);
//
//
//            return ajax_return_adv('添加成功');
//        } else {
//
//            //以下是根据当前登录用户找出相应的公司名称
//            $info = Db::name("AdminUser")->where("id", UID)->where('isdelete','0')->where('status',1)->find();
//            $type = $info['type'];
//            //若当前登录用户是超级管理员
//            if($type == 0){
//                //获取所有公司的名称-----二维数组
//                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('isdelete',0)->select();
//            }
//            //若当前登录用户是公司
//            if($type == 1){
//                //获取当前公司的名称-----二维数组
//                $schoolName = Db::name("SchoolManagement")->field('schoolName')->where('schoolAccount',$info['account'])->where('isdelete','0')->select();
//            }
//            //若是员工
//            if($type == 2){
//                $schoolID = substr($info['account'],0,5);//获取该员工所在的学区编号
//                //获取当前教师所在的校区的名称-----二维数组
//                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();
//            }
//            //若是学生
////            if($type == 3){
////                $schoolID = substr($info['account'],0,5);//获取该学生所在的学区编号
////                //获取当前学生所在的校区名称-----二维数组
////                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();
////            }
//
//            $this->view->assign('schoolName',$schoolName);  //二维数组
//
//
//
//
//
//            $vo =['content' => ''];
//            $this->view->assign("vo", $vo);
//            // 添加
//            return $this->view->fetch(isset($this->template) ? $this->template : 'fsedit');
//        }
//    }



}
