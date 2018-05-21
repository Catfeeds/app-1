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
	
	public function resume(){
		
		$user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
		$token = $this->request->param('token');
        $token = isset($token)?$token:'';
		
		if($user_id&&$token){
            if($this->token($user_id,$token)==1){
                $info = DB::name('patriarch')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->find();
                $data['nickname'] = $info['nickname'];
                $data['headerurl'] = $info['headerurl'];
                $data['creditValue'] = '30';

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

        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
                if($this->request->isPost()){
                    $data['user_id'] = $user_id;
                    $data['name'] = $this->request->param('name');
                    $data['sex'] = $this->request->param('sex');
                    $data['cardID'] = $this->request->param('cardID');
                    $data['alipay'] = $this->request->param('alipay');
                    $data['bankCard'] = $this->request->param('bankCard');
                    $re = DB::name('certification')->insert($data);

                    $info = DB::name('patriarch')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->find();
                    $data1['score'] = $info['score'] +30;
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
                    $data['alipay'] = $this->request->param('alipay');
                    $data['bankCard'] = $this->request->param('bankCard');
                    $re = DB::name('certification')->insert($data);

                    $info = DB::name('patriarch')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->find();
                    $data1['score'] = $info['score'] +30;
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


	//验证token
	public  function token ($user_id,$token){
		$info = DB::name('patriarch')->where('user_id','=',$user_id)->where('status',1)->where('isdelete',0)->find();
		if($token<>$info['token']){
			$this->json(10,'你的账号已在其他设备上登录');
		}elseif(time()-$info['expire_time']>60*60*24*30){
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