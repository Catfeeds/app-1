<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\Session;
use think\Config;
use think\Exception;
use think\View;
use think\Request;

class Message extends Controller
{
    //app首页
    public function index()
    {   
	   
        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
		$token = $this->request->param('token');
        $token = isset($token)?$token:'';
		$city = $this->request->param('city');//城市
        $city = isset($city)?$city:'';
		$property = $this->request->param('property');//兼职类型
        $property = isset($property)?$property:0;
        $page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
		$part = [];
	//	var_dump($user_id);var_dump($token);
		if($user_id&&$token){
			if($this->token($user_id,$token)==1){
				$banner = Db::name('banner')->field('banner_img,banner_link')->where('status',1)->where('isdelete',0)->select();
				if(empty($property)){
					$list = DB::name('CompanyReleasePosition')->field('id,area,positionName,type,start_time,period,count,salary,payroll,release_time,companyAccount')->where('city','like',$city.'%')->where('status',1)->where('isdelete',0)->order('release_time','desc')->limit($page,$num)->select();
					
				}else{
					$list = DB::name('CompanyReleasePosition')->where('city','like',$city.'%')->where('property',$property)->where('status',1)->where('isdelete',0)->order('release_time','desc')->limit($page,$num)->select();
				}
				
				foreach($list as $key=>$value){
                    $info = DB::name('WorkType')->where('id',$value['id'])->where('status',1)->where('isdelete',0)->find();
                    if($value['payroll']==0){
                        $payroll = '日结';
                    }elseif($value['payroll']==1){
                       $payroll = '周结';
                    }elseif($value['payroll']==2){
                       $payroll = '月结';
                    }

                    if($value['period']==2){
                        $period = '工作日';
                    }elseif($value['period']==3){
                       $period = '双休日';
                    }elseif($value['period']==1){
                       $period = '每天';
                    }

					$arr = [];
					$arr = array(
					    $info['name'],
					    $payroll,
					);
					$enroolCount = DB::name('CompanyPositionEnroll')->where('companyAccount',$value['companyAccount'])->where('pid',$value['id'])->where('position_type',1)->where('status',1)->where('isdelete',0)->count();
					$part[] = array(
					    'id' =>$value['id'],
					    'area' =>$value['area'],
						'positionName' =>$value['positionName'],
						'type' =>$info['name'],
						'start_time' =>$value['start_time'],
						'period' =>$period,
						'count' =>$value['count'],
						'salary' =>$value['salary'],
						'payroll' =>$payroll,
						'release_time' =>$value['release_time'],
						'enroolCount' =>$enroolCount,
						'wageGuarantee' =>'工资担保',
						'arr' =>$arr
					);
				}
				
				$data['part'] = $part;
				$data['banner'] = $banner;
				
				$this->json(1,'成功',$data);
				
				
			}
		}else{
			$this->json(12,'无参数');
		}

    }
	
	//我的简历
	
	
	public function resume()
    {
		
		$user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
		$token = $this->request->param('token');
        $token = isset($token)?$token:'';
		
		if($user_id&&$token){
            if($this->token($user_id,$token)==1){
                //获取个人简历基本信息
                $info = DB::table('tp_patriarch a ,tp_certification b')->where('a.user_id=b.user_id')->where('a.user_id',$user_id)->where('a.status',1)->where('a.isdelete',0)->find();

                /*$data['age'] = $info['age'];
                $data['email'] = $info['email'];
                $data['phone'] = $info['phone'];
                $data['name'] = $info['name'];
                $data['headerurl'] = $info['headerurl'];
                $data['sex'] = $info['sex'];*/
				$age = date('Y')-substr($info['cardID'],6,4);
                $data['baseinfo']['age']       =  $age;
                $data['baseinfo']['email']     =  $info['email'];
                $data['baseinfo']['phone']     =  $info['phone'];
                $data['baseinfo']['name']      =  $info['name'];
                $data['baseinfo']['headerurl'] =  $info['headerurl'];
                $data['baseinfo']['sex']       =  $info['sex'];
                //获取个人简历教育信息
                $education = DB::name('EducationExperience')->field('id,schoolName,profession,educationBackground,admission_time,graduation_time,schoolExperience')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->select();
                
                $data['education'] = $education;
                //获取个人工作信息
                $workExperience = DB::name('WorkExperience')->field('id,company,start_time,end_time,content,positionName')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->select();
                
                $data['work'] = $workExperience;
                //获取技能证书信息
                $skill = DB::name('SkillCertificate')->field('id,skillName,skillDescription')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->select();
                
                $data['skill'] = $skill;

                $psEvaluation = DB::name('Patriarch')->field('psevaluation')->where('user_id',$user_id)->find();

                $data['eval'] = $psEvaluation['psevaluation'];


                $this->json(1,'成功',$data);

            }

        }else{
            $this->json(12,'无参数');
        }

		
	}

    
    //教育经历
    public function education (){
        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';
        $id = $this->request->param('id');
        $id = isset($id)?$id:'';

        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
                if($this->request->isPost()){
                    if($id){
                        $data['user_id'] = $user_id;
                        if($this->request->param('schoolName')){
                            $data['schoolName'] = $this->request->param('schoolName'); //学校
                        }
                        if($this->request->param('profession')){
                             $data['profession'] = $this->request->param('profession'); //专业
                        }
                        if($this->request->param('educationBackground')){
                            $data['educationBackground'] = $this->request->param('educationBackground'); //学历
                        }
                        if($this->request->param('admission_time')){
                            $data['admission_time'] = $this->request->param('admission_time');   //入学时间
                        }
                        if($this->request->param('graduation_time')){
                            $data['graduation_time'] = $this->request->param('graduation_time');  //毕业时间
                        }
                        if($this->request->param('schoolExperience')){
                            $data['schoolExperience'] = $this->request->param('schoolExperience');  //在校经历
                        }
                                               
                        $re = DB::name('EducationExperience')->where('user_id',$user_id)->where('id',$id)->where('status',1)->where('isdelete',0)->update($data);

                    }else{
                        $data['user_id'] = $user_id;
                        $data['schoolName'] = $this->request->param('schoolName'); //学校
                        $data['profession'] = $this->request->param('profession'); //专业
                        $data['educationBackground'] = $this->request->param('educationBackground'); //学历
                        $data['admission_time'] = $this->request->param('admission_time');   //入学时间
                        $data['graduation_time'] = $this->request->param('graduation_time');  //毕业时间
                        $data['schoolExperience'] = $this->request->param('schoolExperience');  //在校经历
                        $re = DB::name('EducationExperience')->insert($data);
						
						$info = DB::name('patriarch')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->find();
                        $data1['score'] = $info['score'] +5;
                        DB::name('patriarch')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->update($data1);

                    }

                    if($re){
                            $this->json(1,'成功');
                    }else{
                            $this->json(0,'失败');
                    }

                }else{
                  
                    $re1 = DB::name('EducationExperience')->field('schoolName,profession,educationBackground,admission_time,graduation_time,schoolExperience')->where('user_id',$user_id)->where('id',$id)->where('status',1)->where('isdelete',0)->find();
                    if($re1){
                        $this->json(1,'成功',$re1);
                    }else{
                        $this->json(13,'暂无数据');
                    }
                }
                
            }

        }else{
            $this->json(12,'无参数');
        }

    }

        //工作经历
    public function work (){
        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';
        $id = $this->request->param('id');
        $id = isset($id)?$id:'';

        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
                if($this->request->isPost()){
                    if($id){
                        $data['user_id'] = $user_id;
                        if($this->request->param('company')){
                            $data['company'] = $this->request->param('company'); //公司名称
                        }
                        if($this->request->param('start_time')){
                             $data['start_time'] = $this->request->param('start_time'); //开始时间
                        }
                        if($this->request->param('end_time')){
                            $data['end_time'] = $this->request->param('end_time'); //结束时间
                        }
                        if($this->request->param('content')){
                            $data['content'] = $this->request->param('content');   //内容
                        }
                        if($this->request->param('positionName')){
                            $data['positionName'] = $this->request->param('positionName');   //职位
                        }                 
                        $re = DB::name('WorkExperience')->where('user_id',$user_id)->where('id',$id)->where('status',1)->where('isdelete',0)->update($data);

                    }else{
                        $data['user_id'] = $user_id;
                        $data['company'] = $this->request->param('company'); //公司名称
                        $data['start_time'] = $this->request->param('start_time'); //开始时间
                        $data['end_time'] = $this->request->param('end_time'); //结束时间
                        $data['content'] = $this->request->param('content');   //内容
						$data['positionName'] = $this->request->param('positionName');   //职位
						
						$info = DB::name('patriarch')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->find();
                        $data1['score'] = $info['score'] +5;
                        DB::name('patriarch')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->update($data1);
             
                        $re = DB::name('WorkExperience')->insert($data);

                    }

                    if($re){
                            $this->json(1,'成功');
                    }else{
                            $this->json(0,'失败');
                    }

                }else{
                  
                    $re1 = DB::name('WorkExperience')->field('company,start_time,end_time,content')->where('user_id',$user_id)->where('id',$id)->where('status',1)->where('isdelete',0)->find();
                    if($re1){
                        $this->json(1,'成功',$re1);
                    }else{
                        $this->json(13,'暂无数据');
                    }
                }
                
            }

        }else{
            $this->json(12,'无参数');
        }

    }
	
        //职业技能
    public function skill (){
        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';
        $id = $this->request->param('id');
        $id = isset($id)?$id:'';

        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
                if($this->request->isPost()){
                    if($id){
                        $data['user_id'] = $user_id;
                        if($this->request->param('skillName')){
                            $data['skillName'] = $this->request->param('skillName'); //技能名称
                        }
                        if($this->request->param('skillDescription')){
                             $data['skillDescription'] = $this->request->param('skillDescription'); //技能描述
                        }

                        if(!empty($_FILES['file'])){
                            $files = $_FILES['file'];
                            if($files){
                                $data['image']  = $this->oneupload($files);//图片
                                //$data['headerurl'] = $headerurl;
                              
                            }
                        }
                        
                        
                                            
                        $re = DB::name('SkillCertificate')->where('user_id',$user_id)->where('id',$id)->where('status',1)->where('isdelete',0)->update($data);

                    }else{
                        $data['user_id'] = $user_id;
                        $data['skillName'] = $this->request->param('skillName'); //技能名称
                        $data['skillDescription'] = $this->request->param('skillDescription'); //技能描述
                        if(!empty($_FILES['file'])){
                            $files = $_FILES['file'];
                            if($files){
                                $data['image']  = $this->oneupload($files);//图片
                                //$data['headerurl'] = $headerurl;
                              
                            }
                        }
                        
                        
                        $info = DB::name('patriarch')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->find();
                        $data1['score'] = $info['score'] +5;
                        DB::name('patriarch')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->update($data1);
             
                        $re = DB::name('SkillCertificate')->insert($data);
						
						

                    }

                    if($re){
                            $this->json(1,'成功');
                    }else{
                            $this->json(0,'失败');
                    }

                }else{
                  
                    $re1 = DB::name('SkillCertificate')->field('skillName,skillDescription,image')->where('user_id',$user_id)->where('id',$id)->where('status',1)->where('isdelete',0)->find();
                    if($re1){
                        $this->json(1,'成功',$re1);
                    }else{
                        $this->json(13,'暂无数据');
                    }
                }
                
            }

        }else{
            $this->json(12,'无参数');
        }

    }


            //个人评价
    public function psevaluation (){
        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';
      //  $id = $this->request->param('id');
     //   $id = isset($id)?$id:'';

        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
                if($this->request->isPost()){
                  
                    //    $data['user_id'] = $user_id;
                        if($this->request->param('psevaluation')){
                            $data['psevaluation'] = $this->request->param('psevaluation'); //个人评价
                        }
                  
                        $re = DB::name('patriarch')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->update($data);
                        if($re){
                                $this->json(1,'成功');
                        }else{
                                $this->json(0,'失败');
                        }

                }else{
                  
                    $re1 = DB::name('patriarch')->field('psevaluation')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->find();
                    if($re1){
                        $this->json(1,'成功',$re1);
                    }else{
                        $this->json(13,'暂无数据');
                    }
                }
                
            }

        }else{
            $this->json(12,'无参数');
        }

    }


    //我的认证
    public function certification(){
        
        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';

        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
                if($this->request->isPost()){
                    $data['user_id'] = $user_id;
                    $data['name'] = $this->request->param('name');
                    $data['sex'] = $this->request->param('sex');
                    $data['cardID'] = $this->request->param('cardID');
                    $data['openBank'] = $this->request->param('openBank');
                    $data['alipay'] = $this->request->param('alipay');
                    $data['bankCard'] = $this->request->param('bankCard');
                    $data['email'] = $this->request->param('email');
                    $re = DB::name('certification')->insert($data);

                    $info = DB::name('patriarch')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->find();
                    $data1['score'] = $info['score'] +60;
                    DB::name('patriarch')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->update($data1);

                    if($re){
                        $this->json(1,'成功',$data);
                    }else{
                        $this->json(0,'失败');
                    }

                }else{
                  
                    $re1 = DB::name('certification')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->find();

                    $data['name'] = $re1['name'];
                    $data['sex'] = $re1['sex'];
                    $data['cardID'] = $re1['cardID'];
                    $data['bankCard'] = $re1['bankCard'];
                    $data['alipay'] = $re1['alipay'];
                    $data['openBank'] = $re1['openBank'];
                    $data['email'] = $re1['email'];
                    if($re1){
                        $this->json(1,'成功',$data);
                    }else{
                        $this->json(0,'失败');
                    }
                }
                
            }

        }else{
            $this->json(12,'无参数');
        }

        
    }
	
		   // 单张上传图片
    public function oneupload($files){
       
            $path = 'uploads/file/'.date('Ymd').'/';
            
            if (isset ( $files )) {
     
                $upfile = $path. $files['name'];
                if (! @file_exists ( $path )) {
                    @mkdir ( $path );
                }
                $result = @move_uploaded_file ( $files['tmp_name'], $upfile );
                if (! $result) {
                
                    $this->json(0,'上传失败');
                }
                
                $data = "/".$upfile;  
               
            }

            return $data;
            
    }


	//验证token
	public  function token ($user_id,$token){
		$info = DB::name('patriarch')->where('user_id','=',$user_id)->where('status',1)->where('isdelete',0)->find();
		if($token<>$info['token']){
			$this->json(10,'你的账号已在其他设备上登录');
		}elseif(time()-strtotime($info['expire_time'])>60*60*24*30){
			$this->json(11,'已失效,请重新登录');
		}else{
			return 1;
		}

	}
 
  
    public static function json($code, $msg = '', $data = array()) { 
        
        if(!is_numeric($code)) {
            return '';
        }
        
        if(empty($data)){
            $result = array(
                'code' => $code,
                'msg' => $msg,
            );
        }else{
            $result = array(
                'code' => $code,
                'msg' => $msg,
                'data' => $data,
            );
        }
        
        echo json_encode($result);
        exit;
    }
	
}