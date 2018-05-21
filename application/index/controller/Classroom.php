<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

//宝宝课程
class Classroom extends Controller
{
    public function index()
    {

        $phone_account = $this->request->param('phone_account');
        $account = $this->request->param('account');
        $type = $this->request->param('type');//登陆身份
		$className = $this->request->param('className');//班级
       
        $info1 = DB::name('SchoolManagement')->where('schoolID','=',substr($account,0,5))->where('status',1)->where('isdelete',0)->find();//查找校区账号
		$classManagement = [];
		$vo = DB::name('ClassManagement')->field('className,class')->where('schoolAccount','=',$info1['schoolAccount'])->where('status',1)->where('isdelete',0)->select();//查找校区下的班级
		foreach($vo as $key=>$value){
			
			if($classManagement){
				$mm = 0;
				foreach($classManagement as $k=>$val){
						if(in_array($value['class'],$val)){
						    $mm = 1;
							break;
					    }
			    }
				
				if($mm==0){
					$clssList = DB::name('ClassManagement')->field('className')->where('schoolAccount','=',$info1['schoolAccount'])->where('class','=',$value['class'])->where('status',1)->where('isdelete',0)->order('className','asc')->select();
					$classManagement[] = array(
						'class' =>$value['class'],
						'className' =>$clssList,
			        );
				}
			}else{
				$clssList = DB::name('ClassManagement')->field('className')->where('schoolAccount','=',$info1['schoolAccount'])->where('class','=',$value['class'])->where('status',1)->where('isdelete',0)->order('className','asc')->select();
					$classManagement[] = array(
						'class' =>$value['class'],
						'className' =>$clssList,
			        );
			}
		}
		if(empty($className)){
			if(empty($type)){//若是家长
				$info2 = Db::name('StudentManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
			}elseif($type==1){//若是园长
				$info2['className'] = $classManagement[0]['class'].$classManagement[0]['className'][0]['className'];
			}elseif($type==2){//若是教师
				$info2 = Db::name('EmployeeManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
			} 
			$className = $info2['className']; //所在班级
		}
        
	//var_dump($className);
		$schoolAccount =  $info1['schoolAccount'];
//		$className = $info2['className']; //所在班级

        $riqi = $this->nowweek($schoolAccount,$className);

	    $data = [];
		
	    foreach($riqi as $key=>$value){
			if(!empty($value)){
				//var_dump($value['id']);
				$morning = explode(',',$value['morning']);//获取上午课程
				$morning1 = explode(',',$value['morning']);//获取上午课程
				$afternoon = explode(',',$value['afternoon']);//获取下午课程
				for($i=0;$i<count($afternoon);$i++){
					if(!in_array($afternoon[$i],$morning1)){
						$morning1[] = $afternoon[$i];
					}
					 
				}
				//var_dump($morning);
				$classroomImages = [];//获取图片
				for($i=0;$i<count($morning1);$i++){
				    $info = Db::name('ClassroomCourse')->where('id',$morning1[$i])->where('status',1)->where('isdelete',0)->find();
					if($info['thumb']){
					   $classroomImages[$i][] = $info['image'];
					   $classroomImages[$i][] = $info['thumb'];
					 
					}
				}

				//获取上午课程
				$morning_course = array();
				foreach($morning as $key=>$val){
					$info = Db::name('ClassroomCourse')->where('id',$val)->where('status',1)->where('isdelete',0)->find();
                    $morning_course[] = array(
					   // 'id'=>$val,
						'name' => $info['name'],
					
					);
					
				}
				//获取下午课程
				$aftrenoon_course= array();
				foreach($afternoon as $key=>$val){
					$info = Db::name('ClassroomCourse')->where('id',$val)->where('status',1)->where('isdelete',0)->find();
                    $aftrenoon_course[]= array(
					    //'id'=>$val,
						'name' => $info['name'],
					
					);
				}

				
				
				//谁赞的
				$zanlist = Db::name('ClassroomZan')->where('cid',$value['id'])->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->select();
				
				$zanname=[];
				
				foreach($zanlist as $k=>$val){
						$info1 = Db::name('patriarch')->where('phone_account',$val['phone_account'])->where('status',1)->where('isdelete',0)->find();
                        $info = Db::name('StudentManagement')->where('account',$val['account'])->where('status',1)->where('isdelete',0)->find();
                        $student =  Db::name('StudentLinkman')->where('student_id',$info['id'])->where('number',$info1['phone'])->where('status',1)->where('isdelete',0)->find();
                        $zanname[]=$info['name'].$student['relation'];
				}
			
				
				//当前用户是否赞
				$iszan = Db::name('ClassroomZan')->where('cid',$value['id'])->where('phone_account',$phone_account)->where('iszan',1)->where('status',1)->where('isdelete',0)->find();
				if($iszan){
					$iszan = 1;
				}else{
					$iszan = 0;
				}
				$data[] = array(
					'morning' =>$morning_course,
					'id' =>$value['id'],
					'afternoon' =>$aftrenoon_course,
					'iszan' =>$iszan,
					'zanname' =>$zanname,
					'classroomImages' =>$classroomImages,
					//'date' =>$classroomImages,
				
				) ;
				
			}else{
				$data[] = array();
			}
			
		}

	  
	//var_dump($data);exit;
			$weekDate = $this->weekDate($schoolAccount,$className);
			
			$this->assign('weekDate',$weekDate);
	        $this->assign('data',$data);
            $this->assign('riqi',$riqi);
            $this->assign('schoolAccount',$schoolAccount);
            $this->assign('phone_account',$phone_account);
            $this->assign('className',$className);
			$this->assign('classManagement',$classManagement);
            $this->assign('account',$account);
            $this->assign('type',$type);
            return $this->fetch();

    }

    /**
     *  家长根据传过来的时间和校区名获取相应的课程安排
     */

    public function seeCourse(){
		
            $riqi = $this->request->param('riqi');
			$riqi = substr($riqi,0,-1);
			$riqi = explode(',',$riqi);
			
            $schoolAccount = $this->request->param('schoolAccount');
            $phone_account = $this->request->param('phone_account');
            $account = $this->request->param('account');
            $className = $this->request->param('className');
			$html='';
			foreach($riqi as $k=>$val1){
					
					$result = Db::name('StudentClassroom')->where('schoolAccount',$schoolAccount)->where('className',$className)->where('course_time',$val1)->where('status',1)->where('isdelete',0)->find();
				//	echo DB::getlastsql();
					//echo "<hr>";
				//	var_dump($result);
					if($result){
						$morning = explode(',',$result['morning']);//获取上午课程
						$morning_course = array();
						foreach($morning as $key=>$val){
							$info = Db::name('ClassroomCourse')->where('id',$val)->where('status',1)->where('isdelete',0)->find();
							$morning_course[] = array(
							   // 'id'=>$val,
								'name' => $info['name'],
							
							);
							
						}
						$afternoon = explode(',',$result['afternoon']);//获取下午课程
						$afternoon_course= array();
						foreach($afternoon as $key=>$val){
							$info = Db::name('ClassroomCourse')->where('id',$val)->where('status',1)->where('isdelete',0)->find();
							$afternoon_course[]= array(
								//'id'=>$val,
								'name' => $info['name'],
							
							);
						}
					
						
					
					//	var_dump($result['morning']);exit('11');
						$morning1 = explode(',',$result['morning']);//获取图片
						
						for($i=0;$i<count($afternoon);$i++){
							if(!in_array($afternoon[$i],$morning1)){
								$morning1[] = $afternoon[$i];
							}
							 
						}
						
						$classroomImages = [];//获取图片
						for($i=0;$i<count($morning1);$i++){
							$info = Db::name('ClassroomCourse')->where('id',$morning1[$i])->where('status',1)->where('isdelete',0)->find();
							if($info['thumb']){
							   $classroomImages[$i][] = $info['image'];
							   $classroomImages[$i][] = $info['thumb'];
							 
							}
						}

						//谁赞的
						$zanlist = Db::name('ClassroomZan')->where('cid',$result['id'])->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->select();
						//return   Db::getlastsql();
						$zanname=array();
						foreach($zanlist as $key=>$value){

							$info1 = Db::name('patriarch')->where('phone_account',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();
							$info = Db::name('StudentManagement')->where('account',$value['account'])->where('status',1)->where('isdelete',0)->find();
							$student =  Db::name('StudentLinkman')->where('student_id',$info['id'])->where('number',$info1['phone'])->where('status',1)->where('isdelete',0)->find();
							$zanname[]=$info['name'].$student['relation'];
						}

				//当前用户是否赞
					$iszan = Db::name('ClassroomZan')->where('cid',$result['id'])->where('phone_account',$phone_account)->where('iszan',1)->where('status',1)->where('isdelete',0)->find();

				
					$html.='<div class="swiper-slide "><div class="content-slide"><div class="main-food-family">
								<a href="/index/starlist/index?date='.$val1.'&account='.$account.'" class="fr" style="background: #33cc99;text-align:center;display: block;height: 0.4rem;color: #fff;line-height: 0.4rem;width: 25%;border-radius:5em ;">
						今日表现</a>';
					if(!empty($result['morning'])){ 

					    $html.='<dl><dt class="dt-am">AM </dt>';
						foreach ($morning_course as $key=>$value){
						    $html.='<dd>'.$value['name'].'</dd>';
						} 
					}
				  
					if($result['afternoon']){
						$html.='<dt class="dt-pm">PM </dt>';
						foreach ($afternoon_course as $key=>$value){
						$html.='<dd>'.$value['name'].'</dd>';
						} 
					}



				 $html.= '</dl><div class="main-class">
					<ul class="row main-class-ul">';
				if($classroomImages){
				  
					foreach($classroomImages as $key=>$value){
						$html.='<li class="col-xs-4">
						<a ><img src="'.$value[1].'" class="js-img"/>
						<input type="hidden" class="js-image" value="'.$value[0].'">
						</a></li>
						';
					}
					

				} 
				
				$html.='
					</ul>
				</div><p class="zan-pass">';
				if($zanname){
				   
				
					foreach($zanname as $key=>$value){
						$html.=' <span>
						<i>'.$value.'</i>
					</span>';
					}
				
				}
				$html.='</p>
				<input type="hidden" name="schoolAccount" value="'.$schoolAccount.'">
				<input type="hidden" name="cid" value="'.$result['id'].'">
				<input type="hidden" name="className" value="'.$className.'">
				<input type="hidden" name="phone_account" value="'.$phone_account.'">
				<input type="hidden" name="account" value="'.$account.'">
				';
				
				if(empty($result['morning'])&&empty($result['afternoon'])){
					 $html.=' <a href="javascript:" class="" style="margin:0.1rem 50%;"></a>';
				}elseif($iszan['iszan']){
					$html.=' <a href="javascript:" class="click-zan click-zan-red" style="margin:0.1rem 50%;"></a></div></div></div>';
				}else{
					$html.=' <a href="javascript:" class="click-zan" style="margin:0.1rem 50%;"></a></div></div></div>';
				}
           
               
			}else{
				//$html="<hr>";
				$html.='<div class="swiper-slide "><div class="content-slide"> <div class="default-class">
				        	<div><img src="/static/index/img/defult-class.png"/></div>
				        	<p>暂无课时</p>
				        </div></div></div>
						<input type="hidden" name="schoolAccount" value="'.$schoolAccount.'">
						
						<input type="hidden" name="className" value="'.$className.'">
						<input type="hidden" name="phone_account" value="'.$phone_account.'">
						<input type="hidden" name="account" value="'.$account.'">
						
						';
				//echo $html;
			}	
		}	
			//var_dump($html);
           echo $html; 
       
    }


     public function zan(){
               // $data['type'] = $this->request->param('type');//动态id
                $data['cid'] = $this->request->param('cid');//动态id
                $data['phone_account'] = $this->request->param('phone_account');//账号
                $data['account'] = $this->request->param('account');//账号
				//var_dump($data['phone_account']);
                $data['zan_time'] = date('Y-m-d H:i:s');
                $model = Db::name('ClassroomZan');
                $info = $model->where('cid',$data['cid'])->where('account',$data['account'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->find();
                
                if(empty($info)){
                    $data['iszan'] = 1;
                    $re = $model->insert($data);
                    //谁赞的
                    $zanlist = $model ->where('cid',$data['cid'])->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->select();
                    $zanname='<p class="zan-pass">';
                    foreach($zanlist as $key=>$value){
						$info1 = Db::name('patriarch')->where('phone_account',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();
                        $info = Db::name('StudentManagement')->where('account',$value['account'])->where('status',1)->where('isdelete',0)->find();
                        $student =  Db::name('StudentLinkman')->where('student_id',$info['id'])->where('number',$info1['phone'])->where('status',1)->where('isdelete',0)->find();
                        $zanname.="<span><i>".$info['name'].$student['relation']."</i></span>";
                   
                    }

                    
                     return $zanname;
                    
                }elseif($info['iszan'] == 1){
                    $re = $model->where('cid',$data['cid'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['iszan'=>0]);
                    
                    //谁赞的
                    $zanlist = $model ->where('cid',$data['cid'])->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->select();
                    $zanname='';
                    foreach($zanlist as $key=>$value){
                        $info1 = Db::name('patriarch')->where('phone_account',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();
                        $info = Db::name('StudentManagement')->where('account',$value['account'])->where('status',1)->where('isdelete',0)->find();
                        $student =  Db::name('StudentLinkman')->where('student_id',$info['id'])->where('number',$info1['phone'])->where('status',1)->where('isdelete',0)->find();
                        $zanname.="<span><i>".$info['name'].$student['relation']."</i></span>";
                   
                    }

                    return $zanname;
                     

                }elseif($info['iszan'] == 0){

                    $re = $model->where('cid',$data['cid'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['iszan'
                        =>1]);
                
                    //谁赞的
                    $zanlist = $model->where('cid',$data['cid'])->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->select();
                    $zanname='';
                    foreach($zanlist as $key=>$value){
                        $info1 = Db::name('patriarch')->where('phone_account',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();
                        $info = Db::name('StudentManagement')->where('account',$value['account'])->where('status',1)->where('isdelete',0)->find();
                        $student =  Db::name('StudentLinkman')->where('student_id',$info['id'])->where('number',$info1['phone'])->where('status',1)->where('isdelete',0)->find();
                        $zanname.="<span><i>".$info['name'].$student['relation']."</i></span>";
                   
                    }
                     return $zanname;               
                }
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
    public function nowweek($schoolAccount='',$className=''){

        $time = date('Y-m-d');//获取当前年月日
        $week = date('w');//获取当前星期几
        $StudentClassroom = Db::name('StudentClassroom');
		$list = $this->weekDate();
		$data = [];
		foreach($list as $key=>$value){
			$data[] = $StudentClassroom->where('className',$className)->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->where('course_time',$value)->find();
			
			
		}
		return $data;
		
    }
	
	//根据日期获取数据
    public function week(){
    
        $riqi = $this->request->param('riqi');
        $schoolAccount = $this->request->param('schoolAccount');
        $className = $this->request->param('className');
        $riqi = explode(',',$riqi);
        
        $recipe = Db::name('StudentClassroom');
        $data = array();

        $data['info1'] = $recipe->where('className',$className)->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->where('course_time',$riqi[0])->find();
        $data['info2'] = $recipe->where('className',$className)->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->where('course_time',$riqi[1])->find();
        $data['info3'] = $recipe->where('className',$className)->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->where('course_time',$riqi[2])->find();
        $data['info4'] = $recipe->where('className',$className)->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->where('course_time',$riqi[3])->find();
        $data['info5'] = $recipe->where('className',$className)->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->where('course_time',$riqi[4])->find();
        $data['info6'] = $recipe->where('className',$className)->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->where('course_time',$riqi[5])->find();
        $data['info7'] = $recipe->where('className',$className)->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->where('course_time',$riqi[6])->find();
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