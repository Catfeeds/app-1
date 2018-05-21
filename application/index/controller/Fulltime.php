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

class Fulltime extends Controller
{
	//全职职位搜索栏
	public function search_position()
	{
		$user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
		$token = $this->request->param('token');
        $token = isset($token)?$token:'';
        $city = $this->request->param('city');

        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
            	//获取city下级区域
            	$area_code = DB::name('position')->field('area_code')->where('name','like','%'.$city.'%')->find();
            	$area_code = $area_code['area_code'];
            	if(empty($area_code)){
            		return $this->json(2,"失败");
            	}
            	$area = DB::name('Position')->field('name')->where('parent_area_code',$area_code)->select();
            	foreach ($area as $key => $value) {
            		$search['address'][$key] = $value['name'];
            	}
            	
            	//获取行业
            	$profession = DB::name('ProfessionFulltime')->field('full_name')->select();
            	foreach ($profession as $key => $value) {
            		$search['profess'][$key] = $value['full_name'];
            	}
            	//var_dump($search['profess']);exit;
            	//获取工作经验列表
            	$work_ex = DB::name('WorkexFulltime')->field('actually')->select();
            	foreach ($work_ex as $key => $value) {
            		$search['work_ex'][$key] = $value['actually'];
            	}
            	//获取薪资要求列表
            	$salary = DB::name('SalaryFulltime')->field('salary')->select();
            	foreach ($salary as $key => $value) {
            		$search['need_salary'][$key] = $value['salary'];
            	}
            	//var_dump($search);exit;
            	return $this->json(1,"成功",$search);
            }
        }else{
            $this->json(12,'无参数');
        }

	}

	//全职公司搜索栏
	public function search_company()
	{
		$user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
		$token = $this->request->param('token');
        $token = isset($token)?$token:'';
        $city = $this->request->param('city');

        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
            	//获取city下级区域
            	$area_code = DB::name('Position')->field('area_code')->where('name','like','%'.$city.'%')->find();
            	$area_code = $area_code['area_code'];
            	if(empty($area_code)){
            		return $this->json(2,"失败");
            	}
            	$area = DB::name('Position')->field('name')->where('parent_area_code',$area_code)->select();
            	foreach ($area as $key => $value) {
            		$search['address'][$key] = $value['name'];
            	}
            	//获取规模
            	$size = DB::name('SizeFulltime')->field('size')->select();
            	foreach ($size as $key => $value) {
            		$search['size'][$key] = $value['size'];
            	}
            	
            	//获取行业
            	$Proname = DB::name('ProfessionFulltime')->field('full_name')->where('parent_id',0)->select();
            	foreach ($Proname as $key => $value) {
            		$search['profess'][$key] = $value['full_name'];
            	}
            	//var_dump($search);exit;
            	return $this->json(1,"成功",$search);
            }
        }else{
            $this->json(12,'无参数');
        }

	}
	//根据搜索条件获取全职职位信息
	public function position()
	{
		$user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
		$token = $this->request->param('token');
        $token = isset($token)?$token:'';

        $city = $this->request->param('city');
		$address = $this->request->param('address');
		$profess = $this->request->param('profess');
		$work_ex = $this->request->param('work_ex');
		$need_salary = $this->request->param('need_salary');

		if($user_id&&$token){
            if($this->token($user_id,$token)==1){
		//判断是否输入了查询条件
				if(!empty($address)){
					$where['area'] = $address;//公司地址
				}
				if(!empty($profess)){
					$professInfo = DB::name('ProfessionFulltime')->where('full_name',$profess)->find();
					$profess_id = $professInfo['id'];
					$where['type'] = $profess_id;//行业
				}
				if(!empty($work_ex)){
					$experienceInfo = DB::name('WorkexFulltime')->where('actually',$work_ex)->find();
					$ex_id = $experienceInfo['work_ex'];
					$where['experience'] = $ex_id;//工作经验
				}
				if(!empty($need_salary)){
					$salary_arr = explode('-', $need_salary);
					$lowsalary = intval($salary_arr[0]);
					$highsalary = intval($salary_arr[1]);
					$where['salary2'] = array('egt',$lowsalary);//薪资要求
					$where['salary1'] = array('elt',$highsalary);//薪资要求
				}
				
				if(!empty($where)){
					$position = DB::name('CompanyReleaseFullposition')->field('positionName,companyName,city,experience,education,release_time,salary1,salary2,id')->where('city','like','%'.$city.'%')->where($where)->select();
					//var_dump($position);exit;
				}else{
					$position = DB::name('CompanyReleaseFullposition')->field('positionName,companyName,city,experience,education,release_time,salary1,salary2,id')->where('city','like','%'.$city.'%')->select();
				}

		
			if(!empty($position)){
				return $this->json(1,"成功",$position);
			} else{
				return $this->json(2,"获取全职职位信息失败");
			}
		}
		}else{
            $this->json(12,'无参数');
        }

	}
	//根据搜索条件查询全职公司信息
	//在SchoolManagement表增加规模business_size，行业字段business_property，公司区域area
	//页面要给数据库传公司规模，公司性质，和公司区域area
	public function company()
	{
		$user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
		$token = $this->request->param('token');
        $token = isset($token)?$token:'';

        $city = $this->request->param('city');
		$size = $this->request->param('size');
		$profess = $this->request->param('profess');
		$address = $this->request->param('address');
		
		if($user_id&&$token){
            if($this->token($user_id,$token)==1){
				//判断是否输入了查询条件
				$where['two_level'] = array('like',"%$city%");
				if(!empty($address)){
					$where['area'] = $address;//区域
				}
				if(!empty($size)){
					$sizeInfo = DB::name('SizeFulltime')->where('size',$size)->find();
					$sizeid = $sizeInfo['id'];
					$where['business_size'] = $sizeid;//规模
				}
				if(!empty($profess)){
					$professInfo = DB::name('ProfessionFulltime')->where('full_name',$profess)->find();
					$profess_id = $professInfo['id'];
					$profess_son = DB::name('ProfessionFulltime')->where('parent_id',$profess_id)->select();
					foreach ($profess_son as $key => $value) {
						$psInfo[$key] = $value['id'];
					}
					$where['business_property'] = array('in',$psInfo);//行业
				}
				
				if(!empty($where['two_level'])){
					$company = DB::name('SchoolManagement')->field('schoolName,two_level,business_size,business_property,area,logo,schoolAccount,id')->where($where)->select();
				}else{
					$company = DB::name('SchoolManagement')->field('schoolName,two_level,business_size,business_property,area,logo,schoolAccount,id')->where($where)->select();
				}
				

				foreach ($company as $key => $value) {
					$account = $value['schoolAccount'];
					//var_dump($account);
					$fulltime = DB::name('CompanyReleaseFullposition')->field('positionName')->where('companyAccount',$account)->find();
					$company[$key]['position'] = $fulltime['positionName'];
					$company[$key]['num'] = DB::name('CompanyReleaseFullposition')->where('companyAccount',$account)->count('companyAccount');
				}
			//	var_dump($company);exit;

				if(!empty($company)){
					return $this->json('1',"成功",$company);
				}else{
					return $this->json(2,"获取全职公司信息失败");
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