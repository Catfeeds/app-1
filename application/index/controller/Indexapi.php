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

class Indexapi extends Controller
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
					//$list = DB::name('CompanyReleasePosition')->field('id,area,positionName,type,start_time,end_time,salary,unit,payroll')->where('city','like',$city.'%')->where('status',1)->where('isdelete',0)->order('release_time','desc')->limit(15)->select();
  
                    $list = Db::query("SELECT a.id as id ,a.area as area ,a.positionName as positionName ,a.type as type ,a.start_time as start_time ,a.end_time as end_time ,a.salary as salary ,a.unit as unit ,a.payroll as payroll ,b.isRealName as isRealName,b.isGuarantee as isGuarantee FROM `tp_company_release_position` `a`,`tp_school_management` `b` WHERE  (  a.companyAccount=b.schoolAccount )  AND `a`.`status` = 1  AND `a`.`isdelete` = 0  AND (`b`.`isRealName` = 1 or  `b`.`isGuarantee` = 1 ) AND  `a`.`city` LIKE '".$city."%' and `b`.`status` = 1  AND `b`.`isdelete` = 0 ORDER BY `a`.`release_time`  desc LIMIT 15");
                // SELECT `id`,`area`,`positionName`,`type`,`start_time`,`end_time`,`salary`,`unit`,`payroll` FROM `tp_company_release_position` WHERE `city` LIKE '合肥%' AND `status` = 1 AND `isdelete` = 0 ORDER BY `release_time` desc LIMIT 15
  //echo DB::getlastsql();exit;
                }else{
				//	$list = DB::name('CompanyReleasePosition')->where('city','like',$city.'%')->where('property',$property)->where('status',1)->where('isdelete',0)->order('release_time','desc')->limit($page,$num)->select();
                    $list = Db::query("SELECT a.id as id ,a.area as area ,a.positionName as positionName ,a.type as type ,a.start_time as start_time ,a.end_time as end_time ,a.salary as salary ,a.unit as unit ,a.payroll as payroll ,b.isRealName as isRealName,b.isGuarantee as isGuarantee FROM `tp_company_release_position` `a`,`tp_school_management` `b` WHERE  (  a.companyAccount=b.schoolAccount )   AND `a`.`property` = '".$property."' AND `a`.`status` = 1  AND `a`.`isdelete` = 0  AND (`b`.`isRealName` = 1 or  `b`.`isGuarantee` = 1 ) AND `a`.`city` LIKE '".$city."%' and `b`.`status` = 1  AND `b`.`isdelete` = 0 ORDER BY `a`.`release_time`  desc LIMIT 15");


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

                    if($value['isRealName']==1){
                        $wageGuarantee = '实名企业';
                    }elseif($value['isGuarantee']==1){
                       $wageGuarantee = '工资担保';
                    }

					$arr = [];
					$arr = array(
					    $info['name'],
					    $payroll,
					);
					//$enroolCount = DB::name('CompanyPositionEnroll')->where('companyAccount',$value['companyAccount'])->where('pid',$value['id'])->where('position_type',1)->where('status',1)->where('isdelete',0)->count();
					$part[] = array(
					    'id' =>$value['id'],
					    'area' =>$value['area'],
						'positionName' =>$value['positionName'],
						//'type' =>$info['name'],
						'start_time' =>date('m-d',strtotime($value['start_time'])),
						'end_time' =>date('m-d',strtotime($value['end_time'])),
						//'count' =>$value['count'],
						'salary' =>$value['salary'],
                        'unit' =>$value['unit'],
						//'payroll' =>$payroll,
						//'release_time' =>$value['release_time'],
					//	'enroolCount' =>$enroolCount,
						'wageGuarantee' =>$wageGuarantee,
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
	
	//搜索
	
	public function search(){
		
		$user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
		$token = $this->request->param('token');
        $token = isset($token)?$token:'';
		$city = $this->request->param('city');//城市
        $city = isset($city)?$city:'';
		$name = $this->request->param('name');//搜索名称
        $name = isset($name)?$name:0;
		$page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
		$part = [];

        if($page==0&&$num ==0){
            $limit = "";
        }else{
            $limit = "limit ".$page.",".$num;
        }

		if($user_id&&$token){
            if($this->token($user_id,$token)==1){
                $info1 = DB::name('WorkType')->where('name','like','%'.$name.'%')->where('status',1)->where('isdelete',0)->find();
                

                $list = Db::query("SELECT `id`,`area`,`positionName`,`type`,`start_time`,`period`,`count`,`salary`,`payroll`,`release_time`,`companyAccount` FROM `tp_company_release_position` WHERE `city` LIKE '".$city."%' AND ( `area` LIKE '%".$name."%' OR `positionName` LIKE '%".$name."%' or `type` = '".$info1['id']."')  and`status` = 1 AND `isdelete` = 0 ORDER BY `release_time` desc ".$limit);
                //echo Db::getlastsql();
                //var_dump(DB::getlastsql());exit;

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
                    $enroolCount = DB::name('CompanyPositionEnroll')->where('pid',$value['id'])->where('position_type',1)->where('status',1)->where('isdelete',0)->count();
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

                
                        
                if($part){
                    $info = DB::name('WorkType')->where('name','like','%'.$name.'%')->where('status',1)->where('isdelete',0)->find();
               // echo DB::getlastsql();exit;
               // var_dump($info);
                    if($info ){
                        $data2['num'] = $info['num'] +1;
                        $data1['search_time'] = date('Y-m-d');
                        $data1['wid'] = $info['id'];
                       // var_dump($data);
                        DB::name('WorkType')->where('id',$info['id'])->where('status',1)->where('isdelete',0)->update($data2);
                        DB::name('WorkTypeTime')->insert($data1);
                    }

                    $this->json(1,'成功',$part);
                }else{
                    $this->json(13,'暂无数据');
                }
                

            }
            
        }else{
            $this->json(12,'无参数');
        }

		
	}

        // 热门搜索
    public  function hotSearch(){
        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';
        $data = [];
        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
                $vo1 = Db::query("select a.id,a.name ,(select count(*) from tp_work_type_time as b where b.wid=a.id and b.search_time='".date("Y-m-d",strtotime("-1 day"))."') as d from tp_work_type as a order by d desc");
                //echo DB::getlastsql();exit;
                foreach($vo1 as $key =>$value){
                    if($key<3){
                        $data[] = array(
                            'name' =>$value['name']

                        );
                    }

                }
                $this->json(1,'成功',$data);

            }
        }else{
            $this->json(12,'无参数');
        }
 
    }


    //切换城市


    public function city(){

        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';
        $data = [];
        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
                //$list = DB::name('CompanyReleasePosition')->field('city')->where('status',1)->where('isdelete',0)->select();//
                $list = DB::query("SELECT `city` FROM `tp_company_release_position` WHERE `status` = 1 AND `isdelete` = 0  order by convert(city using gbk) collate gbk_chinese_ci asc");
                //select * from tableA order by convert(name using gbk) collate gbk_chinese_ci desc。
            //    echo Db::getlastsql();
                foreach ($list as $key => $value) {
                    if($data){  
                        $mm = 0;
                        foreach($data as $k=>$val){  
                            if(in_array($value['city'],$val)){
                                $mm = 1;
                            }
                        }
                        if($mm==0){
                            $data[] = array(
                                'city' =>$value['city'],
                            );
                        }
                    }else{
                        $data[] = array(
                            'city' =>$value['city'],
                            //'corpus_name' =>$value['corpus_name'],
                        );
                    }
                }
                $this->json(1,'成功',$data);

            }
        }else{
            $this->json(12,'无参数');
        }




    }


        // 筛选
    public  function screening(){
        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';
        $city = $this->request->param('city');//城市
        $city = isset($city)?$city:'';

        $data = [];
        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
                $info = DB::name('position')->where('name','like',$city.'%')->find();
                $data['area'] = DB::name('position')->field('name,id')->where('parent_area_code',$info['area_code'])->select();//获取地区
                $data['type'] = DB::name('WorkType')->field('name,id')->where('status',1)->where('isdelete',0)->select();
                $data['sex'] = array(
                    '0' =>array(
                        'id' =>0,
                        'name' =>'不限',
                    ),
                    '1' =>array(
                        'id' =>1,
                        'name' =>'男',
                    ),
                    '2' =>array(
                        'id' =>2,
                        'name' =>'女',
                    ),
                );
                $data['period'] = array(
                    '0' => array(
                        'id' =>0,
                        'name'=>'不限',
                    ),
                    '1' =>array(
                        'id' =>1,
                        'name' =>'每天',
                    ),
                    '2' =>array(
                        'id' =>2,
                        'name' =>'工作日',
                    ),
                    '3' =>array(
                        'id' =>3,
                        'name' =>'双休日',
                    ),
                    
                );
                $this->json(1,'成功',$data);

            }
        }else{
            $this->json(12,'无参数');
        }
 
    }


     // 筛选兼职
    public  function screeningPart(){

        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';
        $city = $this->request->param('city');//城市
       
        $area = $this->request->param('area');//地区
        $area = isset($area)?$area:0;
        $period = $this->request->param('period');//兼职时间   0工作日 1双休日 2每天
        $period = isset($period)?$period:0;
        $type = $this->request->param('type');//岗位
        $type = isset($type)?$type:0;
        $sex = $this->request->param('sex');//性别 0 男  1 女  2 不限
        $sex = isset($sex)?$sex:0;

        $page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
        
        if($page==0&&$num ==0){
            $limit = "";
        }else{
            $limit = "limit ".$page.",".$num;
        }


        $data = [];
        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
              

                if($area){
                    $area = DB::name('position')->field('name')->where('id',$area)->find();
                    $where1 = "AND `area` = '".$area['name']."'";
                }else{
                    $where1 = "";
                }

                if($period){
                    $where2 = "AND `period` = '".$period."'";
                }else{
                     $where2 = "";
                }

                if($type){
                    $where3 = "AND `type` = '".$type."'";
                }else{
                    $where3 = "";
                }

                if($sex){
                    $where4 = "AND `sex` = '".$sex."'";
                }else{
                    $where4 = "";
                }

                $where = $where1.$where2.$where3.$where4;
                $list = Db::query("SELECT `id`,`area`,`positionName`,`type`,`start_time`,`end_time`,`count`,`salary`,`payroll`,`unit`,`companyAccount` FROM `tp_company_release_position` WHERE `city` LIKE '".$city."%' AND `status` = 1 AND `isdelete` = 0  ".$where." ORDER BY `release_time` desc ".$limit);
                
                foreach($list as $key=>$value){
                    $info = DB::name('WorkType')->where('id',$value['id'])->where('status',1)->where('isdelete',0)->find();

                    if($value['payroll']==0){
                        $payroll = '日结';
                    }elseif($value['payroll']==1){
                       $payroll = '周结';
                    }elseif($value['payroll']==2){
                       $payroll = '月结';
                    }
                    $arr = [];
                    $arr = array(
                        $info['name'],
                        $payroll,
                    );

                


                 //   $enroolCount = DB::name('CompanyPositionEnroll')->where('companyAccount',$value['companyAccount'])->where('pid',$value['id'])->where('position_type',1)->where('status',1)->where('isdelete',0)->count();//报名人数

                    $data[] = array(
					    'id' =>$value['id'],
					    'area' =>$value['area'],
						'positionName' =>$value['positionName'],
						//'type' =>$info['name'],
						'start_time' =>date('m-d',strtotime($value['start_time'])),
						'end_time' =>date('m-d',strtotime($value['end_time'])),
						//'count' =>$value['count'],
						'salary' =>$value['salary'],
                        'unit' =>$value['unit'],
						//'payroll' =>$payroll,
						//'release_time' =>$value['release_time'],
					//	'enroolCount' =>$enroolCount,
						'wageGuarantee' =>'工资单保',
						'arr' =>$arr
					);
                }

                if($data){
                    $this->json(1,'成功',$data);
                }else{
                    $this->json(13,'暂无数据');
 
                }
           
            }
        }else{
            $this->json(12,'无参数');
        }
 
    }


          // 详情
    public function detail(){
            $id = $this->request->param('id');
            $user_id = $this->request->param('user_id');
            $user_id = isset($user_id)?$user_id:'';
            $token = $this->request->param('token');
            $token = isset($token)?$token:'';
            $model = $this->request->param('model');//0 职位详情   1 公司信息

            if($user_id&&$token&&$id){
                if($this->token($user_id,$token)==1){
                    if(empty($model)){ 
                        $data = Db::name('CompanyReleasePosition')->field('id,area,type,period,property,sex,count,positionName,companyAccount,content,unit,requirement,payroll,salary,click,release_time,work_days,work_hours,up_work,off_work,gather_place,contacts,phone,gather_time,gather_date,work_unit')->where('id',$id)->where('status',1)->where('isdelete',0)->find(); 
                        $enrollCount = DB::name('CompanyPositionEnroll')->where('pid',$id)->where('position_type',1)->where('status',1)->where('isdelete',0)->count();//报名人数
                        $data1['click'] = $data['click']+1;
                        Db::name('CompanyReleasePosition')->where('id',$id)->where('status',1)->where('isdelete',0)->update($data1); //点击数
                        //是否报名
                        $isenroll =  DB::name('CompanyPositionEnroll')->where('pid',$id)->where('user_id',$user_id)->where('position_type',1)->where('status',1)->where('isdelete',0)->select();
					
						if($isenroll){
							foreach($isenroll as $key=>$value){
								if($value['ishire']==1){
									$isenroll = 1;//报名
								}elseif($value['ishire']==0){
									$isenroll = 1;//报名
								}elseif($value['ishire']==2){
									$isenroll = 0;//未报名
								}
								
							}
						}else{
							$isenroll = 0;//未报名
						}
                        

                        //是否收藏
                        $iscollect =  DB::name('CompanyPositionCollect')->where('pid',$id)->where('user_id',$user_id)->where('iscollect',1)->where('status',1)->where('isdelete',0)->find();

                        if($iscollect){
                            $iscollect = 1;//收藏
                        }else{
                            $iscollect = 0;//未收藏
                        }

                        //是否认证
                        $iscertification =  DB::name('certification')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->find();

                        if($iscertification){
                            $iscertification = 1;//认证
                        }else{
                            $iscertification = 0;//未认证
                        }



                        $info = DB::name('WorkType')->where('id',$data['type'])->where('status',1)->where('isdelete',0)->find();
                        if($data['payroll']==0){
                            $data['payroll'] = '日结';
                        }elseif($data['payroll']==1){
                           $data['payroll'] = '周结';
                        }elseif($data['payroll']==2){
                           $data['payroll'] = '月结';
                        }

                        if($data['period']==2){
                            $data['period'] = '工作日';
                        }elseif($data['period']==3){
                           $data['period'] = '双休日';
                        }elseif($data['period']==1){
                           $data['period'] = '每天';
                        }

                         //性别
                        if($data['sex']==1){
                            $data['sex'] = '男';
                        }elseif($data['sex']==2){
                            $data['sex'] = '女';
                        }elseif($data['sex']==3){
                            $data['sex'] = '不限';
                        }

                        $data['type'] = $info['name'];
                        $data['enrollCount'] = $enrollCount;
                        $data['isenroll'] = $isenroll;
                        $data['wageGuarantee'] ='工资担保';
                        $data['click'] = $data1['click'];
                        $data['iscollect'] = $iscollect;
                        $data['iscertification'] = $iscertification;
                       


                        $this->json(1,'成功',$data);

                    }else{

                        $info1 = Db::name('CompanyReleasePosition')->field('companyAccount')->where('id',$id)->where('status',1)->where('isdelete',0)->find(); 
                        $info = Db::name('SchoolManagement')->field('schoolAccount,introduce,address,corporation,schoolName')->where('schoolAccount',$info1['companyAccount'])->where('status',1)->where('isdelete',0)->find(); 
						 $creditNum = '5.0';
						
                        // $info2 = Db::name('SchoolManagement')->where('companyAccount',$info['schoolAccount'])->where('status',1)->where('isdelete',0)->select(); 
						// $count = Db::name('SchoolManagement')->where('companyAccount',$info['schoolAccount'])->where('status',1)->where('isdelete',0)->count(); 
						
						// if($info2){
							// $score = 0.0;
							// foreach($info2 as $key=>$value){
							    // $score+=$value['score'];
						    // }
							
						// }else{
							// $creditNum = '5.0';
						// }
						
						
                        $data['creditNum'] = $creditNum;
                        $data['logo'] = '';
                        $data['companyName'] = $info['schoolName'];
                        $data['companyAccount'] = $info['schoolAccount'];
                        $data['address'] = $info['address'];
                        $data['corporation'] = $info['corporation'];
                        $data['introduce'] = $info['introduce'];
                        $this->json(1,'成功',$data);
                    }
                }
            }else{
                $this->json(12,'无参数');
            }
        

    }

    //收藏职位

    public function collect (){
        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';
        $pid = $this->request->param('id');
        if($user_id&&$token&&$pid){
            if($this->token($user_id,$token)==1){
                $data['pid'] = $pid;//职位id     
                $data['user_id'] = $user_id;//用户者账号
                $data['collect_time'] = date('Y-m-d H:i:s');
                $model = Db::name('CompanyPositionCollect');
                $info = $model->where('pid',$data['pid'])->where('user_id',$data['user_id'])->where('status',1)->where('isdelete',0)->find();
                 
                
                if(empty($info)){
                    $data['iscollect'] = 1;
                    $re = $model->insert($data);
                    if($re){
                        $this->json(1,'收藏成功');
                    }else{
                        $this->json(0,'收藏失败');
                    }
                  
                }elseif($info['iscollect'] == 1){
                    $re = $model->where('pid',$data['pid'])->where('user_id',$data['user_id'])->where('status',1)->where('isdelete',0)->update(['iscollect'=>0,'collect_time'=>date('Y-m-d H:i:s')]);
                    if($re){
                        $this->json(1,'取消收藏成功');
                    }else{
                        $this->json(0,'取消收藏失败');
                    }
                   

                }elseif($info['iscollect'] == 0){

                    $re = $model->where('pid',$data['pid'])->where('user_id',$data['user_id'])->where('status',1)->where('isdelete',0)->update(['iscollect'=>1,'collect_time'=>date('Y-m-d H:i:s')]);
                    if($re){
                        $this->json(1,'收藏成功');
                    }else{
                        $this->json(0,'收藏失败');
                    }
                }
            }
        }else{
            $this->json(12,'无参数');

        }

    }
	//打卡记录
	public function clockIn (){

        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';
		$page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
        $data = [];
        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
                //DB::name('GatherSignin')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->select();
                $data = DB::table('tp_gather_signin a, tp_work_unit b, tp_company_release_position c,tp_company_position_enroll d')->field('a.enroll_id as enroll_id,b.work_unit as work_unit,c.positionName as positionName,c.salary as salary,c.unit as unit,a.gather_sign as gather_sign')->where('a.work_id=b.id')->where('a.enroll_id=d.id')->where('d.pid=c.id ')->where('a.user_id',$user_id)->order('a.id','desc')->limit($page,$num)->select();
       // echo Db::getlastsql();
            }
			if($data){
				$this->json(1,'成功',$data);
			}else{
				$this->json(13,'暂无数据');
			}
        }else{
            $this->json(12,'无参数');
        }

    }
	
		//打卡记录详情
	public function seeclockIn (){

        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';
		$enroll_id = $this->request->param('enroll_id');
       
        $list = [];
        if($user_id&&$token&&$enroll_id){
            if($this->token($user_id,$token)==1){
                //DB::name('GatherSignin')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->select();
                $list = DB::name('signin')->field('up_work_time_sign,off_work_time_sign,signin_time')->where('enroll_id',$enroll_id)->where('user_id',$user_id)->select();
				$info = DB::name('GatherSignin')->field('gather_time_sign,enroll_id')->where('enroll_id',$enroll_id)->where('user_id',$user_id)->find();
				$info1 = DB::table('tp_company_position_enroll a,tp_company_release_position b')->field('b.gather_place')->where('a.pid=b.id')->where('a.id',$enroll_id)->find();
				$data['list'] = $list;
				$data['gather_place'] = $info1['gather_place'];
				$data['gather_time_sign'] = $info['gather_time_sign'];
       // echo Db::getlastsql();
			    if($data){
					$this->json(1,'成功',$data);
				}else{
					$this->json(13,'暂无数据');
				}
            }
			
			
        }else{
            $this->json(12,'无参数');
        }

    }
	
    //签到打卡
   
    public function signIn (){
        $user_id = $this->request->param('user_id');
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';
        $issign = $this->request->param('issign');//4 显示
        $id = $this->request->param('id');//报名id
        $time = $this->request->param('time');//打卡时间
       // $showTime = $this->request->param('showTime');//时间
        $signin_id = $this->request->param('signin_id');//打卡id
   

        //$issign = isset($issign)?$is:'';
        
        if($user_id&&$token){
            if($this->token($user_id,$token)==1){
                $info = Db::name('CompanyPositionEnroll')->where('id',$id)->where('status',1)->where('isdelete',0)->find();
                $info1 = Db::name('CompanyReleasePosition')->where('id',$info['pid'])->where('status',1)->where('isdelete',0)->find();
                
              
                if($issign==1){
                    $showTime = strtotime($info1['gather_date'].$info1['gather_time']);
                    if($time>$showTime){
						$info2 = DB::name('patriarch')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->find();
						if($time-$showTime>=0&&$time-$showTime<=300){
							
							
							$score = $info2['score']-5;
							if($score>=0){
								$data2['score'] = $score;
							}else{
								$data2['score'] = 0;
							}
							DB::name('patriarch')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->update($data2);
							

						}elseif($time-$showTime>=300){
							
							$score = $info2['score']-20;
							if($score>=0){
								$data2['score'] = $score;
							}else{
								$data2['score'] = 0;
							}
							
							DB::name('patriarch')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->update($data2);
							

						}
						
                        $gather_sign = 0;
                    }else{
                        $gather_sign = 1;
                    }
                    $data['work_id'] = $info['work_id'];
                    $data['companyAccount'] = $info['companyAccount'];
                    $data['enroll_id'] = $id;
                    $data['user_id'] = $user_id;
                    $data['gather_time_sign'] = $time;
                    $data['gather_sign'] = $gather_sign;
					$data['signin_time'] = date('Y-m-d');
                    $re = Db::name('GatherSignin')->insertgetid($data);
					
					Db::name('CompanyPositionEnroll')->where('id',$id)->where('status',1)->where('isdelete',0)->update(['ishire'=>3]);
                    
                   // $data1['signin_id'] = $re;
                    $data1['gather_sign'] = $gather_sign;
                    $data1['issign'] = '2';
					$data1['gather_sign'] = $gather_sign;

                    
                    
                }elseif($issign==2){
                   
                        if(strtotime($info1['start_time'])<=strtotime(date('Y-m-d'))&&strtotime(date('Y-m-d'))<=strtotime($info1['end_time'])){
                            $showTime = strtotime(date('Y-m-d').$info1['up_work']);
                            if($time>$showTime){
                                $gather_sign = 0;
                            }else{
                                $gather_sign = 1;
                            }
							$data['work_id'] = $info['work_id'];
							$data['companyAccount'] = $info['companyAccount'];
							$data['enroll_id'] = $id;
							$data['user_id'] = $user_id;
							$data['up_work_time_sign'] = $time;
							$data['up_work_sign'] = $gather_sign;
							$data['signin_time'] = date('Y-m-d');
							$re = Db::name('signin')->insertgetid($data);
							
							
							$data1['issign'] = '3';
							$data1['signin_id'] = $re;
							$data1['up_work_sign'] = $gather_sign;
							$data1['up_time'] = $time;
                            $data1['off_time'] = 0;

							//$re = Db::name('signin')->where('id',$signin_id)->where('status',1)->where('isdelete',0)->update($data);
							
							

                        }else{
							$this->json(0,'失败');
						}
                 

                    
                }elseif($issign==3){
                    if($signin_id){
                        if(strtotime($info1['start_time'])<=strtotime(date('Y-m-d'))&&strtotime(date('Y-m-d'))<=strtotime($info1['end_time'])){
                            $showTime = strtotime(date('Y-m-d').$info1['off_work']);
                            if($time>$showTime){
                                $gather_sign = 1;
                            }else{
                                $gather_sign = 0;
                            }

                        }
                     
                        $data['off_work_time_sign'] = $time;
                        $data['off_work_sign'] = $gather_sign;
						$re = Db::name('signin')->where('id',$signin_id)->where('status',1)->where('isdelete',0)->update($data);
						$info = Db::name('signin')->where('id',$signin_id)->where('status',1)->where('isdelete',0)->find();
						       
						
						
                        $data1['issign'] = '4';
						$data1['off_work_sign'] = $gather_sign;
						$data1['up_work_sign'] = $info['up_work_sign'];
						$data1['up_time'] = $info['up_work_time_sign'];
						$data1['off_time'] = $time;


                        

                    }else{
                        $this->json(0,'失败');
                    }
                    
                }elseif($issign==4){
                    $data = DB::table('tp_company_position_enroll a ,tp_company_release_position b')->field('a.id,b.gather_place,b.gather_date,b.gather_time,b.work_place,b.up_work,b.off_work,b.start_time,b.end_time,b.work_place_lng,b.work_place_lat,b.gather_place_lng,gather_place_lat')->where('a.pid=b.id')->where('a.ishire',['=',1],['=',3],'or')->where('a.status',1)->where('b.start_time','elt',date('Y-m-d'))->where('b.end_time','egt',date('Y-m-d'))->where('a.isdelete',0)->where('a.user_id',$user_id)->order('b.start_time')->find();
                 //  echo Db::getlastsql();exit;
						if($data){
							$info1 = Db::name('GatherSignin')->where('enroll_id',$data['id'])->where('status',1)->where('isdelete',0)->where('user_id',$user_id)->find();
						//	var_dump(Db::getlastsql());
							$info = Db::name('signin')->where('enroll_id',$data['id'])->where('status',1)->where('isdelete',0)->where('signin_time',date('Y-m-d'))->where('user_id',$user_id)->find();
						   // $info1 = Db::name('signin')->where('enroll_id',$data['id'])->where('status',1)->where('isdelete',0)->where('gather_time_sign','neq','')->find();
							if(empty($info1)){
								$data['issign'] = 1;
							}elseif(empty($info['up_work_time_sign'])){
								
								$data['issign'] = 2;
							}elseif(empty($info['off_work_time_sign'])){
								
								$data['issign'] = 3;
								$data['signin_id'] = $info['id'];
								$data['up_time'] = $info['up_work_time_sign'];
								$data['up_work_sign'] = $info['up_work_sign'];
							}elseif($info['off_work_time_sign']&&$info['up_work_time_sign']&&$info1['gather_time_sign']){
								$data['off_time'] = $info['off_work_time_sign'];
								$data['up_time'] = $info['up_work_time_sign'];
								$data['off_work_sign'] = $info['off_work_sign'];
								$data['up_work_sign'] = $info['up_work_sign'];
								$data['issign'] = 4;
							}
					    }else{
							$data['issign'] = 5;
							$this->json(1,'成功',$data1);
						}
					
                 
                  
                    $this->json(1,'成功',$data);
                }
				
                    $data1['id'] = $id;
                  
                    $data1['gather_place'] = $info1['gather_place'];
                    $data1['gather_date'] = $info1['gather_date'];
                    $data1['gather_time'] = $info1['gather_time'];
                    $data1['work_place'] =$info1['work_place'];
                    $data1['up_work'] = $info1['up_work'];
                    $data1['off_work'] = $info1['off_work'];
                    $data1['start_time'] = $info1['start_time'];
                    $data1['end_time'] = $info1['end_time'];
                    $data1['work_place_lng'] = $info1['work_place_lng'];
                    $data1['work_place_lat'] =  $info1['work_place_lat'];
                    $data1['gather_place_lng'] = $info1['gather_place_lng'];
                    $data1['gather_place_lat'] = $info1['gather_place_lat'];
                

                    if($re){
                        $this->json(1,'成功',$data1);
                    }else{
                        $this->json(0,'失败');
                    }
             

            }

        }else{
            $this->json(12,'无参数');
        }


    }

    //我要报名
	
	public function signUp (){
		header("Content-type:text/html;charset=utf-8");
        $user_id = $this->request->param('user_id');
        $data['pid'] = $this->request->param('id');//职位id
        $user_id = isset($user_id)?$user_id:'';
        $token = $this->request->param('token');
        $token = isset($token)?$token:'';

        if($user_id&&$token&&$data['pid']){
            if($this->token($user_id,$token)==1){
				$aa = DB::name('patriarch')->where('user_id',$user_id)->where('status',1)->where('isdelete',0)->find();
				if($aa['score']>=60){
					
			    $data['pid'] = $this->request->param('id');//职位id
                $info = DB::name('CompanyReleasePosition')->where('id',$data['pid'])->where('status',1)->where('isdelete',0)->find();
                $info1 = Db::name('CompanyPositionEnroll')->where('user_id',$user_id)->field('ishire,id')->where('gather_date',$info['gather_date'])->where('status',1)->where('isdelete',0)->select(); //
                $list=[];
				foreach($info1 as $key=>$value){
					$list[]= $value['ishire'];
				}
			//var_dump($info1);exit;
				if($info1){
					
						if(in_array(0,$list)){
							//var_dump('aaa');
						    $this->json(15,'您于'.$info['gather_date'].'已报名,请耐心等待');
					    }elseif(in_array(1,$list)){
						    $this->json(14,'您于'.$info['gather_date'].'报名已被邀约');
					    }elseif(in_array(2,$list)){
						    $info = DB::name('CompanyReleasePosition')->field('companyAccount,companyName,work_unit,work_id,gather_date,city,area,id')->where('id',$data['pid'])->where('status',1)->where('isdelete',0)->find();
							$data['companyName'] = $info['companyName'];//发布公司
							$data['companyAccount'] = $info['companyAccount'];//发布公司的账号
							$data['work_unit'] = $info['work_unit'];//用工单位
							$data['work_id'] = $info['work_id'];//用工单位id
							$data['gather_date'] = $info['gather_date'];//集合地点
							$data['position_type'] = $this->request->param('position_type');//兼职类型  1-兼职   2-全职
							$data['area'] = $info['city'].$info['area'];//地区
							$data['pid'] = $info['id'];//地区
							$data['enroll_time'] = date('Y-m-d H:i:s');  
							$data['user_id'] = $user_id; 
							$re = DB::name('CompanyPositionEnroll')->insert($data);
							if($re){
							//	var_dump('aaa');exit;
								$this->json(1,'成功');
							}else{
								$this->json(0,'失败');
							} 
					    }elseif(in_array(3,$list)){
						    $info = DB::name('CompanyReleasePosition')->field('companyAccount,companyName,work_unit,work_id,gather_date,city,area,id')->where('id',$data['pid'])->where('status',1)->where('isdelete',0)->find();
							$data['companyName'] = $info['companyName'];//发布公司
							$data['companyAccount'] = $info['companyAccount'];//发布公司的账号
							$data['work_unit'] = $info['work_unit'];//用工单位
							$data['work_id'] = $info['work_id'];//用工单位id
							$data['gather_date'] = $info['gather_date'];//集合地点
							$data['position_type'] = $this->request->param('position_type');//兼职类型  1-兼职   2-全职
							$data['area'] = $info['city'].$info['area'];//地区
							$data['pid'] = $info['id'];//地区
							$data['enroll_time'] = date('Y-m-d H:i:s');  
							$data['user_id'] = $user_id; 
							$re = DB::name('CompanyPositionEnroll')->insert($data);
							if($re){
							//	var_dump('aaa');exit;
								$this->json(1,'成功');
							}else{
								$this->json(0,'失败');
							} 
					    }
						
			

				}else{
					$info = DB::name('CompanyReleasePosition')->field('companyAccount,companyName,work_unit,work_id,gather_date,city,area,id')->where('id',$data['pid'])->where('status',1)->where('isdelete',0)->find();
					$data['companyName'] = $info['companyName'];//发布公司
					$data['companyAccount'] = $info['companyAccount'];//发布公司的账号
					$data['work_unit'] = $info['work_unit'];//用工单位
					$data['work_id'] = $info['work_id'];//用工单位id
					$data['gather_date'] = $info['gather_date'];//集合地点
					$data['position_type'] = $this->request->param('position_type');//兼职类型  1-兼职   2-全职
					$data['area'] = $info['city'].$info['area'];//地区
					$data['pid'] = $info['id'];//地区
					$data['enroll_time'] = date('Y-m-d H:i:s');  
					$data['user_id'] = $user_id; 
					$re = DB::name('CompanyPositionEnroll')->insert($data);
					if($re){
					//	var_dump('aaa');exit;
						$this->json(1,'成功');
					}else{
						$this->json(0,'失败');
					} 
					
					
				}
					
			}else{
				$this->json(16,'您的信用分低于60分,不能报名');
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