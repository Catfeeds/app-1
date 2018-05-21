<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

//考勤信息
class Workinfo extends Controller
{
    public function index()
    {   
	    $phone_account = $this->request->param('phone_account');
	    $type = $this->request->param('type');
		$account = $this->request->param('account');
		//if(empty($type)){//家长
		   $riqi = $this->nowweek($account,$type);
		  //var_dump($riqi);
	       $data = [];
		   foreach($riqi as $key =>$value){
			    if($value){
					//var_dump($value);
					foreach($value as $k=>$val){
						if($val['flag']==0){
							$flag ='入门';
						}else{
							$flag ='出门';
						}
						if($val['fileId']){
							$file = Db::name('file')->where('id',$val['fileId'])->find();
							//var_dump($file);exit;
							if($file){
								$file = $file['name'];
							}else{
							    $file = '';
						    }
						}else{
							$file = '';
						}
						$time = date('H:i:s',strtotime($val['time']));
						if($type){
							$relation = $val['name'];
						}else{
							$relation = $val['name'].$val['relation'];
						}
						$data[$key][]= array(
							'relation' =>$relation,
							'flag' =>$flag,
							'time' =>$time,
							'file' =>$file,
						);
					//	var_dump($val);
					}

				}else{
					$data[] = array();
				}
			   
			   
			   
		   }

		
	        $this->assign('data',$data);
            $this->assign('riqi',$riqi);
            
            $this->assign('phone_account',$phone_account);
            $this->assign('account',$account);
            $this->assign('type',$type);
		   
			return $this->fetch();
		
	
	}
	
	//点击上一周或下一周考勤记录
	
	public function  seeWorkinfo(){
		$phone_account = $this->request->param('phone_account');
	    $type = $this->request->param('type');
		$account = $this->request->param('account');
		$riqi = $this->request->param('riqi');
		$riqi = substr($riqi,0,-1);
		$riqi = explode(',',$riqi);
		$html='';

		foreach($riqi as $k=>$val1){
			if($type){
				$EmployeeAccessControl = Db::name('EmployeeAccessControl');
				$result = $EmployeeAccessControl->where('account',$account)->where('status',1)->where('isdelete',0)->where('ymd_time',$val1)->order('time','desc')->select();
			}else{
				$StudentAccessControl = Db::name('StudentAccessControl');
				$result = $StudentAccessControl->where('account',$account)->where('status',1)->where('isdelete',0)->where('ymd_time',$val1)->order('time','desc')->select();
			}
		//	$result = $StudentAccessControl->where('account',$account)->where('status',1)->where('isdelete',0)->where('ymd_time',$val1)->select();
			// echo Db::getlastsql();
			// echo '<hr>';
	//	var_dump($val1);
			if($result){
				$data = [];
				foreach($result as $k=>$val){
					
						if($val['flag']==0){
							$flag ='入门';
						}else{
							$flag ='出门';
						}
						if($val['fileId']){
							$file = Db::name('file')->where('id',$val['fileId'])->find();
							//var_dump($file);exit;
							if($file){
								$file = $file['name'];
							}else{
							    $file = '';
						    }
						}else{
							$file = '';
						}
						//var_dump($file);
						//echo Db::getlastsql();
						if($type){
							$relation = $val['name'];
						}else{
							$relation = $val['name'].$val['relation'];
						}
						$time = date('H:i:s',strtotime($val['time']));
						$data[]= array(
							'relation' =>$relation,
							//'time' =>$val['name'].$val['relation'],
							'flag' =>$flag,
							'time' =>$time,
							'file' =>$file,
						);
					
					}
				
			//	var_dump($data);
				$html.='<div class="swiper-slide "><div class="content-slide"><div class="main-food-family">
				        	<ul class="main-shuttle">';	
					
				foreach($data as $k1=>$v){
					$html.='<li><span>'.$v['relation'].'</span><span>'.$v['flag'].'</span><span>'.$v['time'].'</span><span>查看图片</span></li>';
				}
			
//var_dump($html);
				$html.='</ul></div></div>
			            </div>
						<input type="hidden" name="type" value="'.$type.'">
						<input type="hidden" name="phone_account" value="'.$phone_account.'">
						<input type="hidden" name="account" value="'.$account.'">';
	
			
			}else{
		//	echo "1111@@@";
				$html.='<div class="swiper-slide ">
			        <div class="content-slide">
			        	<div class="default-class">
				        	<div><img src="/static/index/img/defult-shuttle.png"/></div>
				        	<p>暂无接送记录</p>
				        </div></div>
			        </div>
					<input type="hidden" name="type" value="'.$type.'">
					<input type="hidden" name="phone_account" value="'.$phone_account.'">
					<input type="hidden" name="account" value="'.$account.'">';
				
			}
		
		}

		echo $html;

	}
	
	//本周日期
	public function weekDate(){
		
        $timestr = time();
		$now_day = date('w',$timestr);
		//获取一周的第一天，注意第一天应该是星期天
		$data[0] = date('Y-m-d',$timestr - $now_day*60*60*24);
        $data[1] = date('Y-m-d',$timestr + (1-$now_day)*60*60*24);
		$data[2] = date('Y-m-d',$timestr + (2-$now_day)*60*60*24);
		$data[3] = date('Y-m-d',$timestr + (3-$now_day)*60*60*24);
		$data[4] = date('Y-m-d',$timestr + (4-$now_day)*60*60*24);
		$data[5] = date('Y-m-d',$timestr + (5-$now_day)*60*60*24);
		$data[6] = date('Y-m-d',$timestr + (6-$now_day)*60*60*24);
		
		//var_dump($data);
		return $data;
	}
	
    //本周数据
    public function nowweek($account='',$type=''){

        $time = date('Y-m-d');//获取当前年月日
        $week = date('w');//获取当前星期几
       
		$list = $this->weekDate();
		$data = [];
		
		foreach($list as $key=>$value){
			if($type){
				$data[] = Db::name('EmployeeAccessControl')->where('account',$account)->where('status',1)->where('isdelete',0)->where('ymd_time',$value)->order('time','desc')->select();
			}else{
				$data[] = Db::name('StudentAccessControl')->where('account',$account)->where('status',1)->where('isdelete',0)->where('ymd_time',$value)->order('time','desc')->select();
				//echo Db::getlastsql();
			}
			
			//echo Db::getlastsql();
			
		}
		return $data;
		
    }
	
		//根据日期获取数据
    public function week(){
    
        $riqi = $this->request->param('riqi');
        $account = $this->request->param('account');
        $type = $this->request->param('type');
        $riqi = explode(',',$riqi);
        
        
		
        $data = array();
        if($type){
			$EmployeeAccessControl = Db::name('EmployeeAccessControl');
	        $data['info1'] = $EmployeeAccessControl->where('account',$account)->where('status',1)->where('isdelete',0)->where('ymd_time',$riqi[0])->find();
			$data['info2'] = $EmployeeAccessControl->where('account',$account)->where('status',1)->where('isdelete',0)->where('ymd_time',$riqi[1])->find();
			$data['info3'] = $EmployeeAccessControl->where('account',$account)->where('status',1)->where('isdelete',0)->where('ymd_time',$riqi[2])->find();
			$data['info4'] = $EmployeeAccessControl->where('account',$account)->where('status',1)->where('isdelete',0)->where('ymd_time',$riqi[3])->find();
			$data['info5'] = $EmployeeAccessControl->where('account',$account)->where('status',1)->where('isdelete',0)->where('ymd_time',$riqi[4])->find();
			$data['info6'] = $EmployeeAccessControl->where('account',$account)->where('status',1)->where('isdelete',0)->where('ymd_time',$riqi[5])->find();
			$data['info7'] = $EmployeeAccessControl->where('account',$account)->where('status',1)->where('isdelete',0)->where('ymd_time',$riqi[6])->find();
		}else{
			$StudentAccessControl = Db::name('StudentAccessControl');
			$data['info1'] = $StudentAccessControl->where('account',$account)->where('status',1)->where('isdelete',0)->where('ymd_time',$riqi[0])->find();
			$data['info2'] = $StudentAccessControl->where('account',$account)->where('status',1)->where('isdelete',0)->where('ymd_time',$riqi[1])->find();
			$data['info3'] = $StudentAccessControl->where('account',$account)->where('status',1)->where('isdelete',0)->where('ymd_time',$riqi[2])->find();
			$data['info4'] = $StudentAccessControl->where('account',$account)->where('status',1)->where('isdelete',0)->where('ymd_time',$riqi[3])->find();
			$data['info5'] = $StudentAccessControl->where('account',$account)->where('status',1)->where('isdelete',0)->where('ymd_time',$riqi[4])->find();
			$data['info6'] = $StudentAccessControl->where('account',$account)->where('status',1)->where('isdelete',0)->where('ymd_time',$riqi[5])->find();
			$data['info7'] = $StudentAccessControl->where('account',$account)->where('status',1)->where('isdelete',0)->where('ymd_time',$riqi[6])->find();
		}
       
		//var_dump($account);
        if($data['info1']){
            $data['info1'] = 1;//周一存在
        }else{
            $data['info1'] = 2;//周一不存在
        }
        if($data['info2']){
            $data['info2'] = 1;//周二存在
        }else{
            $data['info2'] = 2;//周二不存在
        }
        if($data['info3']){
            $data['info3'] = 1;//周三存在
        }else{
            $data['info3'] = 2;//周三不存在
        }
        if($data['info4']){
            $data['info4'] = 1;//周四存在
        }else{
            $data['info4'] = 2;//周四不存在
        }
        if($data['info5']){
            $data['info5'] = 1;//周五存在
        }else{
            $data['info5'] = 2;//周五不存在
        }
		if($data['info6']){
            $data['info6'] = 1;//周六存在
        }else{
            $data['info6'] = 2;//周六不存在
        }
		if($data['info7']){
            $data['info7'] = 1;//周日存在
        }else{
            $data['info7'] = 2;//周日不存在
        }
        
      return json($data);
     }
	
	
	
	    
        


   



}