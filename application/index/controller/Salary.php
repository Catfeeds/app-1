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

class Salary extends Controller{
	//工资结算
	public function index()
	{
		$user_id = $this->request->param('user_id');
		if($user_id == ""){
			$this->json(100,"用户id为空");
		}
		$token = $this->request->param('token');
		if($token == ""){
			$this->json(101,"token为空");
		}
		$id = $this->request->param('id');
		
		if($user_id&&$token){
			if($this->token($user_id,$token)==1){
			    $salaryInfo = DB::name('SalaryInfo')->where('user_id',$user_id)->where('id',$id)->find();
				$CompanyScore = DB::name('CompanyScore')->where('user_id',$user_id)->where('companyAccount',$salaryInfo['companyAccount'])->find();
				if($CompanyScore){
				    $score = $CompanyScore['score'];
				}else{
					$score = 0.0;
				}
				if($CompanyScore){
					$iscore = 1;
				}else{
					$iscore = 0;
				}
			
				if(!empty($salaryInfo)){
				$data['salary'] = $salaryInfo['salary'];//薪资详情
				$data['work_day'] = $salaryInfo['work_day'];//出勤天数
				$data['total'] = $salaryInfo['total'];//总计
				$data['work_money'] = $salaryInfo['work_money'];//实发工资
                $data['companyAccount'] = $salaryInfo['companyAccount'];//公司账号
				$data['score'] = $score;//公司评分
				$data['iscore'] = $iscore;//公司评分
				$this->json(1,"成功",$data);
				}else{
				$this->json(102,"未查到工资信息");
				}
			}
			
		}
		
		


	}
	//我的收藏
	public function collection()
	{
		$user_id = $this->request->param('user_id');
		if($user_id == ""){
			$this->json(100,"用户id为空");
		}
		$token = $this->request->param('token');
		if($token == ""){
			$this->json(101,"token为空");
		}
		$page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
		
	
		if($user_id&&$token){
			if($this->token($user_id,$token)==1){

				//$collectionInfo = DB::table('tp_company_release_position a, tp_company_position_collect b')->field('a.companyName as companyName,a.positionName as positionName,a.salary as salary,a.unit as unit,a.release_time as release_time,a.payroll as payroll')->where('b.user_id',$user_id)->where('b.pid=a.id')->where('b.iscollect',1)->select();
			    $collectionInfo = DB::table('tp_company_release_position a, tp_company_position_collect b, tp_school_management c')->field('a.id as id,a.companyName as companyName,a.positionName as positionName,a.salary as salary,a.unit as unit,a.release_time as release_time,a.payroll as payroll,c.logo as logo')->where('a.id=b.pid')->where('a.companyAccount=c.schoolAccount')->where('b.user_id',$user_id)->where('b.iscollect',1)->order('b.collect_time','desc')->limit($page,$num)->select();
			//	var_dump($collectionInfo);
				
				if(!empty($collectionInfo)){
					

					return $this->json(1,"成功",$collectionInfo);
				}else{
					return $this->json(102,"未查到信息");
				}
			}
			
		}
	}
	//工资结算列表页
		public function salarylist()
	{
		$user_id = $this->request->param('user_id');
		if($user_id == ""){
			$this->json(100,"用户id为空");
		}
		
		$page = $this->request->param('page');
		$page = isset($page)?$page-1:0;

		$num = $this->request->param('num');
		$num = isset($num)?$num:10;
		$startnum = $page*$num;
		

		
		$token = $this->request->param('token');
		if($token == ""){
			$this->json(101,"token为空");
		}
		if($user_id&&$token){
			if($this->token($user_id,$token)==1){

				$salaryList = DB::table('tp_company_position_enroll a, tp_company_release_position b ,tp_salary_info c')->field('c.id,b.positionName,b.gather_date,b.salary,b.unit,c.isbalance')->where('a.pid=b.id')->where('c.user_id=a.user_id')->where('a.user_id',$user_id)->where('a.ishire',3)->order('b.gather_date','desc')->limit($startnum,$num)->select();
				if(!empty($salaryList)){
					return $this->json(1,"成功",$salaryList);
				}else{
					return $this->json(102,"未查到信息");
				}
			
			}
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