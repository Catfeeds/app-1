<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;
use think\Cookie;
//每周食谱
class Recipe extends Controller
{
   
	 public function index()
    {

        $phone_account = $this->request->param('phone_account');
        $account = $this->request->param('account');
        $type = $this->request->param('type');
  
        $info1 = DB::name('SchoolManagement')->where('schoolID','=',substr($account,0,5))->where('status',1)->where('isdelete',0)->find();//查找校区账号
        // if(empty($type)){//若是家长
            // $info2 = Db::name('StudentManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
		// }else{//若是教师
			// $info2 = Db::name('EmployeeManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
		// } 
	
		$schoolAccount =  $info1['schoolAccount'];//所在校区

        $riqi = $this->nowweek($schoolAccount);

	    $data = [];
		
	    foreach($riqi as $key=>$value){
			if(!empty($value)){
				//var_dump($value['id']);
				$breakfastId = explode(',',$value['breakfast']);//获取早餐ids
				$lunchId = explode(',',$value['lunch']);//获取午餐ids
				$snackId = explode(',',$value['snack']);//获取午点ids
				

				//获取早餐
				$breakfast = array();
				$break_imagename=[];
				foreach($breakfastId as $key=>$val){
					$info = Db::name('breakfast')->where('id',$val)->where('status',1)->where('isdelete',0)->find();
                    $breakfast[] = array(
					   // 'id'=>$val,
						'name' => $info['name'],
					);
					
					if($info['thumb']){
					   $break_imagename[$key][] = $info['image'];
					   $break_imagename[$key][] = $info['thumb'];
					   $break_imagename[$key][] = $info['name'];
                    }
					
				}
				//获取午餐
				$lunch= array();
				$lunch_imagename = [];
				foreach($lunchId as $key=>$val){
					$info = Db::name('lunch')->where('id',$val)->where('status',1)->where('isdelete',0)->find();
                    $lunch[]= array(
					    //'id'=>$val,
						'name' => $info['name'],
					
					);
					
					if($info['thumb']){
					   $lunch_imagename[$key][] = $info['image'];
					   $lunch_imagename[$key][] = $info['thumb'];
					   $lunch_imagename[$key][] = $info['name'];
                   }
				}
				
				//获取午点
				$snack= array();
				$snack_imagename = [];
				foreach($snackId as $key=>$val){
					$info = Db::name('snack')->where('id',$val)->where('status',1)->where('isdelete',0)->find();
                    $snack[]= array(
					    //'id'=>$val,
						'name' => $info['name'],
					
					);
					
					if($info['thumb']){
					    $snack_imagename[$key][] = $info['image'];
					    $snack_imagename[$key][] = $info['thumb'];
					    $snack_imagename[$key][] = $info['name'];
                   }
				}
                
				$recipeImage = array_merge_recursive($snack_imagename,$lunch_imagename,$break_imagename);
				
				
				//谁赞的
				$zanlist = Db::name('RecipeZan')->where('rid',$value['id'])->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->select();
				
				$zanname=[];
				
				foreach($zanlist as $k=>$val){

						$info1 = Db::name('patriarch')->where('phone_account',$val['phone_account'])->where('status',1)->where('isdelete',0)->find();
                        $info = Db::name('StudentManagement')->where('account',$val['account'])->where('status',1)->where('isdelete',0)->find();
                        $student =  Db::name('StudentLinkman')->where('student_id',$info['id'])->where('number',$info1['phone'])->where('status',1)->where('isdelete',0)->find();
                        $zanname[]= $info['name'].$student['relation'];
				}
			
				
				//当前用户是否赞
				$iszan = Db::name('RecipeZan')->where('rid',$value['id'])->where('phone_account',$phone_account)->where('iszan',1)->where('status',1)->where('isdelete',0)->find();
				if($iszan){
					$iszan = 1;
				}else{
					$iszan = 0;
				}
				$data[] = array(
					'id' =>$value['id'],
					'breakfast' =>$breakfast,
					'lunch' =>$lunch,
					'snack' =>$snack,
					'iszan' =>$iszan,
					'zanname' =>$zanname,
					'recipeImage' =>$recipeImage,
					//'date' =>$classroomImages,
				
				) ;
				
			}else{
				$data[] = array();
			}
			
		}

	  
	//var_dump($riqi);exit;
			$weekDate = $this->weekDate($schoolAccount);
			
			$this->assign('weekDate',$weekDate);
	        $this->assign('data',$data);
            $this->assign('riqi',$riqi);
            $this->assign('schoolAccount',$schoolAccount);
            $this->assign('phone_account',$phone_account);
          //  $this->assign('className',$className);
            $this->assign('account',$account);
            $this->assign('type',$type);
            return $this->fetch();

    }

     /*
     * 家长根据传过来的时间和校区名称获取相应的食谱
     */

  public function seeFood(){
		
            $riqi = $this->request->param('riqi');
			$riqi = substr($riqi,0,-1);
			$riqi = explode(',',$riqi);
			//var_dump($riqi);
            $schoolAccount = $this->request->param('schoolAccount');
            $phone_account = $this->request->param('phone_account');
            $account = $this->request->param('account');
         
			$html='';
			foreach($riqi as $k=>$val1){
			 		
					$result = Db::name('recipe')->where('schoolAccount',$schoolAccount)->where('eat_time',$val1)->where('status',1)->where('isdelete',0)->find();
					//echo DB::getlastsql();
					//echo "<hr>";
					//var_dump($result);
					if($result){
						$breakfastId = explode(',',$result['breakfast']);//获取早餐ids
						$breakfast = array();
						$break_imagename = [];
						foreach($breakfastId as $key=>$val){
							$info = Db::name('breakfast')->where('id',$val)->where('status',1)->where('isdelete',0)->find();
							$breakfast[] = array(
							   // 'id'=>$val,
								'name' => $info['name'],
							
							);
							if($info['thumb']){
								$break_imagename[$key][] = $info['image'];
								$break_imagename[$key][] = $info['thumb'];
								$break_imagename[$key][] = $info['name'];
                            }
							
						}
						$lunchId = explode(',',$result['lunch']);//获取午餐ids
						$lunch = array();
						$lunch_imagename = [];
						foreach($lunchId as $key=>$val){
							$info = Db::name('lunch')->where('id',$val)->where('status',1)->where('isdelete',0)->find();
							$lunch[]= array(
								//'id'=>$val,
								'name' => $info['name'],
							
							);
							if($info['thumb']){
								$lunch_imagename[$key][] = $info['image'];
								$lunch_imagename[$key][] = $info['thumb'];
								$lunch_imagename[$key][] = $info['name'];
                            }
						}
						
						$snackId = explode(',',$result['snack']);//获取午点ids
						$snack = array();
						$snack_imagename = [];
						foreach($snackId as $key=>$val){
							$info = Db::name('snack')->where('id',$val)->where('status',1)->where('isdelete',0)->find();
							$snack[]= array(
								//'id'=>$val,
								'name' => $info['name'],
							
							);
							if($info['thumb']){
								$snack_imagename[$key][] = $info['image'];
								$snack_imagename[$key][] = $info['thumb'];
								$snack_imagename[$key][] = $info['name'];
                            }
						}
					
					
						$recipeImage = array_merge_recursive($snack_imagename,$lunch_imagename,$break_imagename);
				
					

						//谁赞的
						$zanlist = Db::name('RecipeZan')->where('rid',$result['id'])->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->select();
						//return   Db::getlastsql();
						$zanname=array();
						foreach($zanlist as $key=>$value){
							$info1 = Db::name('patriarch')->where('phone_account',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();
							$info = Db::name('StudentManagement')->where('account',$value['account'])->where('status',1)->where('isdelete',0)->find();
							$student =  Db::name('StudentLinkman')->where('student_id',$info['id'])->where('number',$info1['phone'])->where('status',1)->where('isdelete',0)->find();
							$zanname[]= $info['name'].$student['relation'];
						}

				//当前用户是否赞
					$iszan = Db::name('RecipeZan')->where('rid',$result['id'])->where('phone_account',$phone_account)->where('iszan',1)->where('status',1)->where('isdelete',0)->find();

				
					$html.='<div class="swiper-slide "><div class="content-slide"><div class="main-food-family">
								';
					if(!empty($result['breakfast'])){ 

					    $html.='<dt class="dt-breakfast">早餐</dt>';
						foreach ($breakfast as $key=>$value){
						    $html.='<dd>'.$value['name'].'</dd>';
						} 
					}
				
					if($result['lunch']){
						$html.='<dt class="dt-lunch">中餐</dt>';
						foreach ($lunch as $key=>$value){
						$html.='<dd>'.$value['name'].'</dd>';
						} 
					}
					
					if($result['snack']){
						$html.='<dt class="dt-mug-up">午点</dt>';
						foreach ($snack as $key=>$value){
						$html.='<dd>'.$value['name'].'</dd>';
						} 
					}
            

				 $html.= '</dl><div class="main-food">
					<ul class="row main-food-ul">';
				if($recipeImage){
				  
					foreach($recipeImage as $key=>$value){
						$html.='<li>
						            <a >
						               <img src="'.$value[1].'" class="js-img"/>
						                <input type="hidden" class="js-image" value="'.$value[0].'">
						            </a>
						            <p class="ppp1">'.$value[2].'</p>
						        </li>
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
				<input type="hidden" name="rid" value="'.$result['id'].'">
				<input type="hidden" name="phone_account" value="'.$phone_account.'">
				<input type="hidden" name="account" value="'.$account.'">
				';
				
				if(empty($result['breakfast'])&&empty($result['lunch'])&&empty($result['snack'])){
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
				        	<p>暂无食谱</p>
				        </div></div></div>
						<input type="hidden" name="schoolAccount" value="'.$schoolAccount.'">
						<input type="hidden" name="phone_account" value="'.$phone_account.'">
						<input type="hidden" name="account" value="'.$account.'">
						
						';
				//echo $html;
			}	
		}	
			//var_dump($html);
           echo $html; 
       
    }

     /*
     * 家长点赞
     */

    public function zan(){
               // $data['type'] = $this->request->param('type');//动态id
                $data['rid'] = $this->request->param('rid');//动态id
                $data['phone_account'] = $this->request->param('phone_account');//账号
                $data['account'] = $this->request->param('account');//账号
			
                $data['zan_time'] = date('Y-m-d H:i:s');
                $model = Db::name('RecipeZan');
                $info = $model->where('rid',$data['rid'])->where('phone_account',$data['phone_account'])->where('account',$data['account'])->where('status',1)->where('isdelete',0)->find();
                
                if(empty($info)){
                    $data['iszan'] = 1;
                    $re = $model->insert($data);
                    //谁赞的
                    $zanlist = $model ->where('rid',$data['rid'])->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->select();
                    $zanname='<p class="zan-pass">';
                    foreach($zanlist as $key=>$value){
						$info1 = Db::name('patriarch')->where('phone_account',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();
                        $info = Db::name('StudentManagement')->where('account',$value['account'])->where('status',1)->where('isdelete',0)->find();
                        $student =  Db::name('StudentLinkman')->where('student_id',$info['id'])->where('number',$info1['phone'])->where('status',1)->where('isdelete',0)->find();
                        $zanname.="<span><i>".$info['name'].$student['relation']."</i></span>";
                   
                    }

                    
                     return $zanname;
                    
                }elseif($info['iszan'] == 1){
                    $re = $model->where('rid',$data['rid'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['iszan'=>0]);
                    
                    //谁赞的
                    $zanlist = $model ->where('rid',$data['rid'])->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->select();
                    $zanname='';
                    foreach($zanlist as $key=>$value){
                        $info1 = Db::name('patriarch')->where('phone_account',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();
                        $info = Db::name('StudentManagement')->where('account',$value['account'])->where('status',1)->where('isdelete',0)->find();
                        $student =  Db::name('StudentLinkman')->where('student_id',$info['id'])->where('number',$info1['phone'])->where('status',1)->where('isdelete',0)->find();
                        $zanname.="<span><i>".$info['name'].$student['relation']."</i></span>";
                   
                    }

                    return $zanname;
                     

                }elseif($info['iszan'] == 0){

                    $re = $model->where('rid',$data['rid'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['iszan'
                        =>1]);
                
                    //谁赞的
                    $zanlist = $model->where('rid',$data['rid'])->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->select();
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
    public function nowweek($schoolAccount=''){

        $recipe = Db::name('recipe');
		$list = $this->weekDate();
		$data = [];
		foreach($list as $key=>$value){
			$data[] = $recipe->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->where('eat_time',$value)->find();
			
			
		}
		//var_dump( $data);
		return $data;

    }
	
    public function week(){
        $riqi = substr($this->request->param('riqi'),0,-1);
        $schoolAccount = $this->request->param('schoolAccount');
        $riqi = explode(',',$riqi);
         
        $recipe = Db::name('recipe');
        $data = array();

        $data['info1'] = $recipe->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->where('eat_time',$riqi[0])->find();
        $data['info2'] = $recipe->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->where('eat_time',$riqi[1])->find();
        $data['info3'] = $recipe->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->where('eat_time',$riqi[2])->find();
        $data['info4'] = $recipe->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->where('eat_time',$riqi[3])->find();
        $data['info5'] = $recipe->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->where('eat_time',$riqi[4])->find();
		$data['info6'] = $recipe->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->where('eat_time',$riqi[5])->find();
        $data['info7'] = $recipe->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->where('eat_time',$riqi[6])->find();
       
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

 