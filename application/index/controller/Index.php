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


class Index extends Controller
{
	//首页
	public function index()
	{   
		
		$city = explode('.',$_SERVER['HTTP_HOST']);
		
		//var_dump($myarr);
		$type = $this->request->param('type');//工作类别
		$type = isset($type)?$type:'';
		$area = $this->request->param('area');//地区
		$area = isset($area)?$area:'';
		$property = $this->request->param('property');//兼职类型
		$property = isset($property)?$property:0;
		$part = [];
		
		//搜索参数
		$name = $this->request->param('name');//工作类别
		$name = isset($type)?$name:'';
		$m = $this->request->param('m');//工作类别
		
        if($city[0]=='www'){
			$info1['name'] = '切换城市';
			if($m ==1){//搜索
				$pageParam1['query']['name'] = $name;
				$list = Db::table('tp_company_release_position')->field('id,area,positionName,type,start_time,period,count,salary,payroll,release_time,companyAccount')->where('area|positionName|type','like','%'.$name.'%')->where('status',1)->where('isdelete',0)->order('release_time','desc')->paginate(10,false,$pageParam1);
				$count = Db::table('tp_company_release_position')->field('id,area,positionName,type,start_time,period,count,salary,payroll,release_time,companyAccount')->where('area|positionName|type','like','%'.$name.'%')->where('status',1)->where('isdelete',0)->count();

			}else{//筛选
				list($list,$count) = $this->screening($property,$area,$type);
		//var_dump($count);
			}
		}else{
			$info1 = DB::name('city')->where('en_name',$city['0'])->find();
		//	$city1 = $info1['name'];
			if($m ==1){//搜索
				$pageParam1['query']['name'] = $name;
				$list = Db::table('tp_company_release_position')->field('id,area,positionName,type,start_time,period,count,salary,payroll,release_time,companyAccount')->where('area|positionName|type','like','%'.$name.'%')->where('city','like','%'.$info1['name'].'%')->where('status',1)->where('isdelete',0)->order('release_time','desc')->paginate(10,false,$pageParam1);
				$count = Db::table('tp_company_release_position')->field('id,area,positionName,type,start_time,period,count,salary,payroll,release_time,companyAccount')->where('area|positionName|type','like','%'.$name.'%')->where('city','like','%'.$info1['name'].'%')->where('status',1)->where('isdelete',0)->count();

			}else{//筛选
				list($list,$count) = $this->screening($property,$area,$type,$info1['name']);
		//var_dump($count);
			}
			
			
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
			$enroolCount = DB::name('CompanyPositionEnroll')->where('position_type',1)->where('companyAccount',$value['companyAccount'])->where('status',1)->where('isdelete',0)->count();
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
						'release_time' =>$this->time_tran($value['release_time']),
						'enroolCount' =>$enroolCount,
						'wageGuarantee' =>'工资担保',
					  
			 );
		}
		//var_dump($info1);
		$page = $list->render();//分页链接
		
		//banner
		$banner = DB::name('banner')->where('status',1)->where('isdelete',0)->select();
		
		$type1 = $this->type();//工作类别
		$area1 = $this->area($info1['name']);//地区
		$hotType = $this->hotType();//热门工作类别
		//var_dump($hotType);
		$this->assign('count',$count);//总数
		$this->assign('type1',$type1);//获取所有工作类别
		$this->assign('area1',$area1);//获取所有工作地区
		$this->assign('type',$type);//前端选择工作类别
		$this->assign('property',$property);//前端选择兼职类型
		$this->assign('area',$area);//前端选择工作地区
		$this->assign('part',$part);//渲染数据
		$this->assign('page',$page);//分页
		$this->assign('banner',$banner);//banner
		$this->assign('hotType',$hotType);//热门工作类别
		$this->assign('title',$info1['name']);//热门工作类别
		return $this->fetch();
	}
	
	
	
	public function guarantee(){
      return $this->view->fetch();
    }
    //2
    public function pay(){
    
        return $this->view->fetch();
    }
  //3.完成
    public function finish(){

      return $this->view->fetch();
    }
	//详情
	public function detail(){
		$id = $this->request->param('id');
		if($id){

			$data = Db::name('CompanyReleasePosition')->where('id',$id)->where('status',1)->where('isdelete',0)->find(); 
			$enroolCount = DB::name('CompanyPositionEnroll')->where('companyAccount',$data['companyAccount'])->where('position_type',1)->where('status',1)->where('isdelete',0)->count();//报名人数
			$data1['click'] = $data['click']+1;
			Db::name('CompanyReleasePosition')->where('id',$id)->where('status',1)->where('isdelete',0)->update($data1); 
			$data['release_time'] = $this->time_tran($data['release_time']);
			$data['enroolCount'] = $enroolCount;
			$data['click'] = $data1['click'];

			//结薪方式
            if($data['payroll']==0){
                $data['payroll'] = '日结';
            }elseif($data['payroll']==1){
               $data['payroll'] = '周结';
            }elseif($data['payroll']==2){
               $data['payroll'] = '月结';
            }
            //兼职类型
            if($data['property']==1){
                $data['property'] = '日结兼职';
            }elseif($data['property']==2){
               $data['property'] = '长期兼职';
            }elseif($data['property']==3){
               $data['property'] = '实习兼职';
            } elseif($data['property']==4){
               $data['property'] = '旅行兼职';
            }
            //兼职时间
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
            $info = DB::name('WorkType')->where('id',$data['type'])->where('status',1)->where('isdelete',0)->find();
            $data['type'] = $info['name'];

			$hotType = $this->hotType();//热门工作类别
			$this->assign('data',$data);//
			$this->assign('hotType',$hotType);//热门工作类别
			return $this->fetch();
		}

	}
	
	// 切换城市
	public  function city(){
		header("Content-type: text/html; charset=utf-8"); 
		$list = [];
		$vo = DB::name('city')->where('pid',0)->select();
		
		foreach($vo as $key=>$value){
			$vo1 = DB::name('city')->field('name,en_name,url')->where('pid',$value['id'])->select();
			// if($value['spell'] ){
				
			// }
			//var_dump($vo1);
			//foreach($vo1 as $k=>$val){
				$list[$value['spell']][] = array(
					'name' =>$value['name'],
					//'spell' =>$value['spell'],
					'children'=>$vo1,
					
			
			    );
			//}
			
			
		}
		
		//var_dump($list);
		//exit;
		
		$hotType = $this->hotType();//热门工作类别
	    $this->assign('list',$list);//
        $this->assign('hotType',$hotType);//热门工作类别
		return $this->fetch();
	}
	// 城市搜索
	public  function citySearch(){
		$city = $this->request->param('city');//
		
		$info = DB::name('city')->where('name','like',$city.'%')->where('level',2)->find();
	
        if($info){
			$arr = array(
			   'msg' =>$info['url'],
			   'status' =>1,
			);
		}else{
			$arr = array(
			   'msg' =>'抱歉,没有找到该城市!',
			   'status' =>0,
			);
		}
		exit(json_encode($arr));
		//return json_encode($arr);
	}
	
	// 工作类别
	public  function type(){
		$vo = [];
		$data = DB::name('WorkType')->field('name,id')->where('status',1)->where('isdelete',0)->select();
	
		return $data;
	}
	
		
	// 热门工作类别
	public  function hotType(){
		$vo = [];
		$vo1 = Db::name('CompanyReleasePosition')->field('type')->where('status',1)->where('isdelete',0)->order('release_time','desc')->select();
		
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

	// 地区
	public  function area($city){
		$vo = [];
		$info = DB::name('position')->where('name','like',$city.'%')->find();
	//	echo Db::getlastsql();
        $data = DB::name('position')->field('name,id')->where('parent_area_code',$info['area_code'])->select();//获取地区
	  
		
	
		return $data;
	}

	// 筛选
	public  function screening($property,$area,$type,$city=''){
		$pageParam    = ['query' =>[]];
		$pageParam['query']['type'] = $type;
		$pageParam['query']['area'] = $area;
		$pageParam['query']['property'] = $property;
		if(empty($property)&&empty($type)&&empty($area)){
			//类型，种类，地区都为空
				$list = Db::name('CompanyReleasePosition')->field('id,area,positionName,type,start_time,period,count,salary,payroll,release_time,companyAccount')->where('city','like',$city."%")->where('status',1)->where('isdelete',0)->order('release_time','desc')->paginate(10,false,$pageParam); //查找职位列表,每页显示10条数据

			    $count = Db::name('CompanyReleasePosition')->where('city','like',$city."%")->where('status',1)->where('isdelete',0)->count(); //查找职位列表,每页显示10条数据
			}elseif(empty($property)&&empty($type)){
                 //类型，种类都为空
				$info = DB::name('position')->field('name')->where('id',$area)->find();//获取地区
				$list = Db::name('CompanyReleasePosition')->field('id,area,positionName,type,start_time,period,count,salary,payroll,release_time,companyAccount')->where('area',$info['name'])->where('city','like',$city."%")->where('status',1)->where('isdelete',0)->order('release_time','desc')->paginate(10,false,$pageParam); //查找职位列表,每页显示10条数据
				//echo Db::getlastsql();exit;
				$count = Db::name('CompanyReleasePosition')->where('city','like',$city."%")->where('area',$info['name'])->where('status',1)->where('isdelete',0)->count(); //

			}elseif(empty($type)&&empty($area)){
                //种类,地区都为空
			//	$info = DB::name('position')->field('name')->where('id',$area)->find();//获取地区
				$list = Db::name('CompanyReleasePosition')->field('id,area,positionName,type,start_time,period,count,salary,payroll,release_time,companyAccount')->where('property',$property)->where('city','like',$city."%")->where('status',1)->where('isdelete',0)->order('release_time','desc')->paginate(10,false,$pageParam); //查找职位列表,每页显示10条数据
				//echo Db::getlastsql();exit;
				$count = Db::name('CompanyReleasePosition')->where('city','like',$city."%")->where('property',$property)->where('status',1)->where('isdelete',0)->count(); //
			}elseif(empty($property)&&empty($area)){
                 //类型，种类都为空
			//	$info = DB::name('position')->field('name')->where('id',$area)->find();//获取地区
				$list = Db::name('CompanyReleasePosition')->field('id,area,positionName,type,start_time,period,count,salary,payroll,release_time,companyAccount')->where('type',$type)->where('city','like',$city."%")->where('status',1)->where('isdelete',0)->order('release_time','desc')->paginate(10,false,$pageParam); //查找职位列表,每页显示10条数据
				//echo Db::getlastsql();exit;
				$count = Db::name('CompanyReleasePosition')->where('type',$type)->where('city','like',$city."%")->where('status',1)->where('isdelete',0)->count(); //
			}elseif(empty($property)){
			//类型为空
				//var_dump('aaa');
				$info = DB::name('position')->field('name')->where('id',$area)->find();//获取地区
				$list = Db::name('CompanyReleasePosition')->field('id,area,positionName,type,start_time,period,count,salary,payroll,release_time,companyAccount')->where('type',$type)->where('area',$info['name'])->where('city','like',$city."%")->where('status',1)->where('isdelete',0)->order('release_time','desc')->paginate(10,false,$pageParam); //查找职位列表,每页显示10条数据
				//echo Db::getlastsql();exit;
				$count = Db::name('CompanyReleasePosition')->where('type',$type)->where('area',$info['name'])->where('city','like',$city."%")->where('status',1)->where('isdelete',0)->count(); //查找职位列表,每页显示10条数据
			}elseif(empty($area)){
			//地区为空
				//var_dump('vvv');
				$list = Db::name('CompanyReleasePosition')->field('id,area,positionName,type,start_time,period,count,salary,payroll,release_time,companyAccount')->where('type',$type)->where('property',$property)->where('city','like',$city."%")->where('status',1)->where('isdelete',0)->order('release_time','desc')->paginate(10,false,$pageParam); //查找职位列表,每页显示10条数据
				//echo Db::getlastsql();exit;
				$count = Db::name('CompanyReleasePosition')->where('city','like',$city."%")->where('type',$type)->where('area',$area)->where('status',1)->where('isdelete',0)->count(); //查找职位列表,每页显示10条数据
			}elseif(empty($type)){
			//种类为空
				$info = DB::name('position')->field('name')->where('id',$area)->find();//获取地区
				$list = Db::name('CompanyReleasePosition')->field('id,area,positionName,type,start_time,period,count,salary,payroll,release_time,companyAccount')->where('property',$property)->where('area',$info['name'])->where('city','like',$city."%")->where('status',1)->where('isdelete',0)->order('release_time','desc')->paginate(10,false,$pageParam); //查找职位列表,每页显示10条数据
				//echo Db::getlastsql();exit;
				$count = Db::name('CompanyReleasePosition')->where('property',$property)->where('area',$info['name'])->where('city','like',$city."%")->where('status',1)->where('isdelete',0)->count(); //查找职位列表,每页显示10条数据
			}else{
			//类型，种类，地区都不为空
                $info = DB::name('position')->field('name')->where('id',$area)->find();//获取地区
				$list = Db::name('CompanyReleasePosition')->field('id,area,positionName,type,start_time,period,count,salary,payroll,release_time,companyAccount')->where('property',$property)->where('area',$info['name'])->where('type',$type)->where('city','like',$city."%")->where('status',1)->where('isdelete',0)->order('release_time','desc')->paginate(10,false,$pageParam); //查找职位列表,每页显示10条数据
				//echo Db::getlastsql();exit;
				$count = Db::name('CompanyReleasePosition')->where('property',$property)->where('area',$info['name'])->where('city','like',$city."%")->where('type',$type)->where('status',1)->where('isdelete',0)->count(); //查找职位列表,每页显示10条数据
			}

		
		
		return array($list, $count);
	
		//return $data;
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
							return date('Y-m-d',strtotime($the_time));  
						}  
					}  
				}  
			}  
		}  
	}
   
  // 上传图片
	public function upload($files){
   // 获取表单上传文件
		$data = array();
		foreach($files as $file){
		   // 移动到框架应用根目录/public/uploads/ 目录下
		  $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads'.DS.'file');
			if($info){

				$path = date ('Ymd'); // 接收文件目录
					if (! file_exists ("./uploads/file/".$path)) {
						mkdir ("./uploads/file/".$path, 0777, true );
					}
				$root = '/uploads/file/'.$path.'/'.$info->getFilename();

				$data[] = $root;
		  }else{
			   // 上传失败获取错误信息
				echo $file->getError();
			}    
		}

		return $data;
	}

}
