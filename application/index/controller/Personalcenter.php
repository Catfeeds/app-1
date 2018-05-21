<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;
use think\Session;
use think\Request;

class Personalcenter extends Controller
{   
   //个人中心
   
   public function my(){
	     
        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';
        
        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
				$info = DB::name('patriarch')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->find();
				$info1 = DB::name('certification')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->find();
				$data['headerurl'] = $info['headerurl'];
				$data['score'] = $info['score'];
				$data['name'] = $info1['name'];
				
				$this->json(1,'成功',$data);
                
            }

        }else{
            $this->json(12,'无参数');
        }

	   
	   
   }

    //我的兼职
    public function part() {
        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';
        $ishire = $this->request->param('ishire');
        $ishire = isset($ishire)?$ishire:0;
		
		$name = isset($name)?$name:0;
		$page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
		
		
        $data = [];
        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
                $list = Db::name('CompanyPositionEnroll')->field('pid,ishire,id')->where('user_id',$user_id)->where('status',1)->where('ishire',$ishire)->where('isdelete',0)->order('enroll_time','desc')->limit($page,$num)->select();
                $count = Db::name('CompanyPositionEnroll')->field('pid,ishire,id')->where('user_id',$user_id)->where('status',1)->where('ishire',$ishire)->where('isdelete',0)->count();
				if($list){
                	foreach($list as $key=>$value){
	                    $info = Db::name('CompanyReleasePosition')->field('id,salary,unit,area,release_time,positionName,up_work,off_work,companyAccount')->where('id',$value['pid'])->where('status',1)->where('isdelete',0)->find();
	                    $info1 = Db::name('SchoolManagement')->field('logo')->where('schoolAccount',$info['companyAccount'])->where('status',1)->where('isdelete',0)->find();
	                  //  $arr = explode('-',$info['work_time']);
	                    $data[] = array(
	                        'positionName'=>$info['positionName'],
	                        'salary'=>$info['salary'],
	                        'up_work'=>$info['up_work'],
	                        'off_work'=>$info['off_work'],
	                        'logo'=>$info1['logo'],
	                        'unit'=>$info['unit'],
	                        'id'=>$info['id'],
							'ishire'=>$value['ishire'],
							'area'=>$info['area'],
							'enroll_id'=>$value['id'],
	                        'release_time'=>$info['release_time'],

	                    );

	                }
                }
               
                
                if($data){
                    $this->json(1,'成功',$data);
                }else{
                    $this->json(13,'暂无数据');
 
                }

                $this->json(1,'成功',$data);

            }

        }else{
            $this->json(12,'无参数');
        }

    }
    
     //我的兼职已被邀约
    public function parthire() {
        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';
        $ishire = $this->request->param('ishire');
        $ishire = isset($ishire)?$ishire:0;
		$name = isset($name)?$name:0;
		$page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
		
        $data = [];
        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
                $list = Db::name('CompanyPositionEnroll')->field('pid,ishire')->where('user_id',$user_id)->where('status',1)->where('ishire',$ishire)->where('isdelete',0)->order('enroll_time','desc')->limit($page,$num)->select();
                if($list){
                	foreach($list as $key=>$value){
	                    $info = Db::name('CompanyReleasePosition')->field('id,area,unit,salary,gather_place,phone,positionName')->where('id',$value['pid'])->where('status',1)->where('isdelete',0)->find();
	                    
	                    $data[] = array(
	                        'positionName'=>$info['positionName'],
	                        'salary'=>$info['salary'],
	                        'gather_place'=>$info['gather_place'],
	                        'phone'=>$info['phone'],
	                        'unit'=>$info['unit'],
	                        'id'=>$info['id'],
	                        'area'=>$info['area'],
							'ishire'=>$value['ishire'],

	                    );

	                }
                }
               
               
                if($data){
                    $this->json(1,'成功',$data);
                }else{
                    $this->json(13,'暂无数据');
 
                }

                $this->json(1,'成功',$data);

            }

        }else{
            $this->json(12,'无参数');
        }

    }
	//取消报名
	public function cancelApply(){

        
        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';
        $enroll_id = $this->request->param('enroll_id');
        $enroll_id = isset($enroll_id)?$enroll_id:'';
        if($user_id&&$token&&$enroll_id){
            if($this->token($user_id,$token)==1){
				$info = DB::name('patriarch')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->find();
				if($info['score']<10){
					$this->json(17,'你的信用分少于10分,不能取消报名');
				}else{
					$re = Db::name('CompanyPositionEnroll')->where('user_id',$user_id)->where('status',1)->where('ishire',0)->where('id',$enroll_id)->where('isdelete',0)->delete();
					if($re){  
					   
						$data1['score'] = $info['score']-10;
						DB::name('patriarch')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->update($data1);
						$this->json(1,'成功',$data1['score']);
					}else{
						$this->json(0,'失败');
					}
					
				}
                
            }

        }else{
            $this->json(12,'无参数');
        }

    }
	
	    //上传头像

    public function headerurl(){

        
        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';
        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
				
				if(!empty($_FILES['file'])){
                    $files = $_FILES['file'];
					if($files){
						$headerurl = $this->oneupload($files);
						$data['headerurl'] = $headerurl;
						$info = Db::name('patriarch')->where('status',1)->where('user_id',$user_id)->where('isdelete',0)->update($data);
						if($info){
							$this->json(1,'成功',$headerurl);
						}else{
							$this->json(0,'失败');
						}
                    }
                }
                
                
            }

        }else{
            $this->json(12,'无参数');
        }


    }
	
	//岗位能力
    public  function ability (){
        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';
		$name = isset($name)?$name:0;
		$page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
                
                $data = Db::name('SalaryInfo')->field('positionName,work_day,work_score')->where('status',1)->where('user_id',$user_id)->where('isdelete',0)->order('id','desc')->limit($page,$num)->select();
                if($data){
                    $this->json(1,'成功',$data);
                }else{
                    $this->json(0,'失败');
                }
                
            }

        }else{
            $this->json(12,'无参数');
        }

    }
	    //公司评分

    public  function score (){
        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';
 
        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
                $data['score'] = $this->request->param('score');
				$data['companyAccount'] = $this->request->param('companyAccount');
				
                $data['user_id'] = $user_id;//var_dump($data);
                $info = Db::name('CompanyScore')->insert($data);
                if($info){
                    $this->json(1,'成功');
                }else{
                    $this->json(0,'失败');
                }
                
            }

        }else{
            $this->json(12,'无参数');
        }

    }

    //意见反馈


    public  function opinion (){
        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';
 
        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
                $data['content'] = $this->request->param('content');
                $data['user_id'] = $user_id;//var_dump($data);
                $info = Db::name('opinion')->insert($data);
                if($info){
                	$this->json(1,'成功');
                }else{
                	$this->json(0,'失败');
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
