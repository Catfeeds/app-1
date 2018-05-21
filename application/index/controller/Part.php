<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;
use think\Session;

class Part extends Controller
{   
	//日结兼职
    public function daily()

    {   
	    $city = explode('.',$_SERVER['HTTP_HOST']);
		$info1 = DB::name('city')->where('en_name',$city['0'])->find();
		if($city['0']=='www'){
			list($part,$page) = $this->common(1);
		}else{
			list($part,$page) = $this->common(1,$info1['name']);
		}
        
        $hotType = $this->hotType();//热门工作类别
        $this->view->assign("hotType", $hotType);
		$this->view->assign("part", $part);
		$this->view->assign("page", $page);
		return $this->fetch();
        
    }
    //长期兼职
     public function long()

    {
        $city = explode('.',$_SERVER['HTTP_HOST']);
		$info1 = DB::name('city')->where('en_name',$city['0'])->find();
		if($city['0']=='www'){
			list($part,$page) = $this->common(2);
		}else{
			list($part,$page) = $this->common(2,$info1['name']);
		}
        $hotType = $this->hotType();//热门工作类别
		$this->view->assign("part", $part);
		$this->view->assign("hotType", $hotType);
		$this->view->assign("page", $page);
		return $this->fetch();
        
    }
	
    //实习兼职
	 public function internship()

    {
        
        $city = explode('.',$_SERVER['HTTP_HOST']);
		$info1 = DB::name('city')->where('en_name',$city['0'])->find();
		if($city['0']=='www'){
			list($part,$page) = $this->common(3);
		}else{
			list($part,$page) = $this->common(3,$info1['name']);
		}
        $hotType = $this->hotType();//热门工作类别
        $this->view->assign("hotType", $hotType);
		$this->view->assign("part", $part);
		$this->view->assign("page", $page);
		return $this->fetch();
        
    }
	
    //旅行兼职
	 public function travel()

    {
        
        $city = explode('.',$_SERVER['HTTP_HOST']);
		$info1 = DB::name('city')->where('en_name',$city['0'])->find();
		if($city['0']=='www'){
			list($part,$page) = $this->common(4);
		}else{
			list($part,$page) = $this->common(4,$info1['name']);
		}
        $hotType = $this->hotType();//热门工作类别
        $this->view->assign("hotType", $hotType);
        $this->view->assign("page", $page);
		$this->view->assign("part", $part);
		return $this->fetch();
        
    }
	
	
	
     
	 public function common($property,$city='')
    {  
        $part = [];
        $list =  Db::name('CompanyReleasePosition')->field('id,area,positionName,type,start_time,period,count,salary,payroll,release_time,companyAccount')->where('city','like',$city."%")->where('status',1)->where('property',$property)->where('isdelete',0)->order('release_time','desc')->paginate(10); //查找职位列表,每页显示10条数据
	   		//echo Db::getlastsql();
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
			$enroolCount = DB::name('CompanyPositionEnroll')->where('companyAccount',$value['companyAccount'])->where('position_type',1)->where('status',1)->where('isdelete',0)->count();
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
					  
			 );
		}

		$page = $list->render();//分页链接
		return array($part,$page);
    }

    // 热门工作类别
	public  function hotType(){
		$vo = [];
		$vo1 = Db::name('CompanyReleasePosition')->field('type')->where('city','合肥市')->where('status',1)->where('isdelete',0)->order('release_time','desc')->select();
		
		foreach($vo1 as $key=>$value){
			$count = Db::name('CompanyReleasePosition')->where('city','合肥市')->where('type',$value['type'])->where('status',1)->where('isdelete',0)->count();
			$info = Db::name('WorkType')->where('id',$value['type'])->where('status',1)->where('isdelete',0)->find();
			
			if($vo){  
				$mm = 0;
				foreach($vo as $k=>$val){  
					if(in_array($value['type'],$val)){
						$mm = 1;
					}
				}
				if($mm==0){
					
					$vo[] = array(
						'type' =>$info['name'],
						'count' =>$count,
						'id' =>$value['type']
					);
				}
			}else{
			
			$vo[] = array(
					'type' =>$info['name'],
					'count' =>$count,
					'id' =>$value['type']
				);	
			}
		}
		
		$vo2 = [];
		foreach ($vo as $k1 =>$v) {
			
				$vo2[] = $v['count'];
			
			  
		}
		array_multisort($vo2, SORT_DESC, $vo);
		return $vo;
	
		
	}

	public function time_tran($the_time) {  
		$now_time = date("Y-m-d H:i:s", time());  
		//echo $now_time;  
		$now_time = strtotime($now_time);  
		$show_time = strtotime($the_time);  
		$dur = $now_time - $show_time;  
		if ($dur < 0) {  
			return $the_time;  
		} else {  
			if ($dur < 60) {  
				return $dur . '秒前';  
			} else {  
				if ($dur < 3600) {  
					return floor($dur / 60) . '分钟前';  
				} else {  
					if ($dur < 86400) {  
						return floor($dur / 3600) . '小时前';  
					} else {  
						if ($dur < 259200) {//3天内  
							return floor($dur / 86400) . '天前';  
						} else {  
							return $the_time;  
						}  
					}  
				}  
			}  
		}  
	}
}
