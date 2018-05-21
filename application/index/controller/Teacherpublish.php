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

class Teacherpublish extends Controller
{   
    //教师发布
    public function publish (){
		
        $phone_account = $this->request->param('phone_account'); 
        $type = $this->request->param('type'); 
        $account = $this->request->param('account'); 

        $employee = Db::name('AdminUser')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
		//var_dump($employee);exit;
        $AdminRoleUser = Db::name('AdminRoleUser')->where('user_id',$employee['id'])->find();
        $adminAccess = Db::name('AdminAccess')->where('role_id',$AdminRoleUser['role_id'])->where('level',2)->where('pid',1)->select();

        $data = [];
        foreach ($adminAccess as $key=>$value){
            $info = Db::name('AdminNode')->where('id',$value['node_id'])->find();
            $data[] = array(
               'title' =>$info['title'],
               'type_sort' =>$info['type_sort'],

            );

        }
		if($data){
			$this->json(0,'成功',$data);
		}else{
			$this->json(1,'没有权限发布');
		}
		
		
    }
	
	//发布信息
	
	public function publishInfo (){
		
         if($this->request->isPost()){
            $data['phone_account'] = $this->request->param('phone_account'); 
            $type = $this->request->param('type'); 
            $type_sort = $this->request->param('type_sort'); 
            $account = $this->request->param('account');
            $data['title'] = $this->request->param('title');
            
			if($this->request->param('content')){
				$data['content'] = $this->request->param('content');
			}
			
			if($this->request->param('title')){
				$data['title'] = $this->request->param('title');
			}

            if(!empty($_FILES['file'])){
                $files = $_FILES['file'];
                if($files){
                    $data['images'] = $this->upload($files); 
                    $data['thumbs'] = $this->thumb(json_decode($data['images']));
                
                }
            }

            $data['release_time'] = date('Y-m-d H:i:s');

            $info = DB::name('EmployeeManagement')->where('account','=',$account)->where('status',1)->where('isdelete',0)->find();//查找
			$info1 = DB::name('SchoolManagement')->where('schoolID','=',substr($account,0,5))->where('status',1)->where('isdelete',0)->find();//查找校区账号
            $data['schoolAccount'] = $info1['schoolAccount'];
			$data['schoolName'] = $info1['schoolName'];
			$data['release'] = $info['name'];
			$data['account'] = $account;
			switch($type_sort)
			{
			case 3:
			
				$data['className'] = $info['className'];
			    $re = DB::name('notice')->insertgetid($data);//发布园内通知
				//var_dump($re);exit('ddd');
			    $data1['schoolName'] = $info1['schoolName'];
			    $data1['schoolAccount'] = $info1['schoolAccount'];
				$data1['className'] = $info['className'];
			    $data1['type_num'] = 1;
			    $data1['pid'] = $re;
			    $data1['release_time'] = date('Y-m-d H:i:s');
			    DB::name('dynamic')->insertgetid($data1);//
			    break;
			
			    
			case 0:
			    $data['release_time'] = date('Y-m-d');
			    $data['className'] = $info['className'];
				$info = DB::name('parentchild')->where('schoolAccount',$data['schoolAccount'])->where('className',$data['className'])->where('release_time',$data['release_time'])->where('status',1)->where('isdelete',0)->find();
			   
				if($info){ 
				 $this->json(3,'一天只能发布一次');
				}else{
					$re = DB::name('parentchild')->insert($data);//发布亲子任务
				}
				
			    break;

			case 2:
			    $data['className'] = $info['className'];
			    $re = DB::name('album')->insert($data);//发布活动相册
			    break;
				
		    case 1:
			    $data['className'] = $info['className'];
			    $re = DB::name('mien')->insertgetid($data);//发布园区风采
                $data1['className'] = $info['className'];
			    $data1['schoolName'] = $info1['schoolName'];
			    $data1['schoolAccount'] = $info1['schoolAccount'];
			    $data1['type_num'] = 2;
			    $data1['pid'] = $re;
			    $data1['release_time'] = date('Y-m-d H:i:s');
			    DB::name('dynamic')->insertgetid($data1);//
			    break;

			}
			
			if($re){
                $this->json(0,'发布成功');

            }else{

                $this->json(1,'发布失败');
            }

        }else{
			$this->json(2,'非法请求');
		}
        

    }
	
	// 根据日期获取食谱
	
	public function dateFood(){
		
		$eat_time = $this->request->param('date'); 
		$date = explode('-',$this->request->param('date'));
		if(strlen($date[1])==1){
			$month = '0'.$date[1];
			
		}else{
			$month =$date[1];
		}
		if(strlen($date[2])==1){
			$day = '0'.$date[2];
			
		}else{
			$day = $date[2];
		}
		$eat_time = $date[0].'-'.$month.'-'.$day;
	
		$account = $this->request->param('account'); 
		$phone_account = $this->request->param('phone_account'); 
		
		//$employee = DB::name('EmployeeManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
		//$info = DB::name('recipe')->where('eat_time',$eat_time)->where('schoolName',$employee['schoolName'])->where('status',1)->where('isdelete',0)->find();//午点
		//$data['schoolName'] = $employee['schoolName'];
		
	    $employee = DB::name('SchoolManagement')->where('schoolID','=',substr($account,0,5))->where('status',1)->where('isdelete',0)->find();//查找校区账号
		
		$info = DB::name('recipe')->where('eat_time',$eat_time)->where('schoolAccount',$employee['schoolAccount'])->where('status',1)->where('isdelete',0)->find();;//发布
	
		$arr['breakfast'] =[];
		$arr['lunch'] =[];
		$arr['snack'] =[];
		if($info){
		    $rid = $info['id'];
			if($info['breakfast']){
				$breakfast = explode(',',$info['breakfast']);
			
				foreach($breakfast as $key=>$value){
					$info1 = DB::name('breakfast')->where('id',$value)->where('status',1)->where('isdelete',0)->find();//早餐
					$arr['breakfast'][] = array(
						'id' =>$info1['id'],
						'name' =>$info1['name'],
					);
				}
				
			}
		
			if($info['lunch']){
				$lunch = explode(',',$info['lunch']);
			
				foreach($lunch as $key=>$value){
					$info2 = DB::name('lunch')->where('id',$value)->where('status',1)->where('isdelete',0)->find();//早餐
					$arr['lunch'][] = array(
						'id' =>$info2['id'],
						'name' =>$info2['name'],
					);
				}
				
			}
			if($info['snack']){
				$snack = explode(',',$info['snack']);
			
				foreach($snack as $key=>$value){
					$info3 = DB::name('snack')->where('id',$value)->where('status',1)->where('isdelete',0)->find();//早餐
					$arr['snack'][] = array(
						'id' =>$info3['id'],
						'name' =>$info3['name'],
					);
				}
				
			}
            $data['rid'] = $rid;
		    $data['arr'] = $arr;
			
		}else{
			$data['rid'] = 0;
		    $data['arr'] = $arr;
		}
		$this->json(0,'成功',$data);

	}
	
	//添加早 type_food=1，中 type_food=2，午 type_food=3 常见菜  
    public function addFood(){
        //if($this->request->isPost()){
			$type = $this->request->param('type');
			//var_dump($type);
			if($type==1){
				$type_food = $this->request->param('type_food');
				$id = $this->request->param('id');
				$time = $this->request->param('time');
				$data['phone_account'] = $this->request->param('phone_account');
			   
				$data['name'] = $this->request->param('name');
				if($this->request->param('weight')){
					$data['weight'] = $this->request->param('weight');
				}
				$file = $_FILES['file'];
				if(!empty($file )){
					$data['image'] = $this->oneupload($file);
					$data['thumb'] = $this->onethumb($data['image']);
				}
				
				//添加
				if($type_food =='1'){
					
					$breakfastinfo = Db::name('Breakfast')->where('name',$data['name'])->where('status',1)->where('isdelete',0)->find();
					
					if(empty($breakfastinfo)) {
						
						$re = Db::name('Breakfast')->insert($data);
					}else{
						
						$re = Db::name('Breakfast')->where('name',$data['name'])->where('status',1)->where('isdelete',0)->update($data);
					}

				}

				if($type_food =='2'){
					
					$lunchinfo = Db::name('lunch')->where('name',$data['name'])->where('status',1)->where('isdelete',0)->find();
					
					if(empty($lunchinfo)) {
						
						$re = Db::name('lunch')->insert($data);
					}else{
						
						$re = Db::name('lunch')->where('name',$data['name'])->where('status',1)->where('isdelete',0)->update($data);
					}

				}


				if($type_food =='3'){
					
					$snackinfo = Db::name('snack')->where('name',$data['name'])->where('status',1)->where('isdelete',0)->find();
					
					if(empty($snackinfo)) {
						
						$re = Db::name('snack')->insert($data);
					}else{
						
						$re  = Db::name('snack')->where('name',$data['name'])->where('status',1)->where('isdelete',0)->update($data);
					}

				}

				if($re){
					$this->json(0,'成功');
				}else{
					$this->json(1,'失败');
				}
				
			}
            

        //}
        
     }
	 
	    //选择常用菜 
    public function  chooseFood (){
        $type_food = $this->request->param('type_food'); 
        $phone_account = $this->request->param('phone_account');
		$account = $this->request->param('account');
		$type = $this->request->param('type');
		$id = $this->request->param('rid');
       
	    $arr = [];
		$list = [];
		
        if($type_food ==1){
			
            $list1 = DB::name('breakfast')->field('id,name,weight,image,thumb')->where('status',1)->where('isdelete',0)->select();//早餐
			$recipe = DB::name('recipe')->where('id',$id)->where('status',1)->where('isdelete',0)->find();//
			$breakfast = explode(',',$recipe['breakfast']);//var_dump($breakfast);
			if(!empty($recipe['breakfast'])){
				//echo '12345';
				foreach($breakfast as $key=>$value){
					$info = DB::name('breakfast')->where('id',$value)->where('status',1)->where('isdelete',0)->find();//早餐
					$arr[] = array(
						'id' =>$info['id'],
						'name' =>$info['name'],
					);
			    }
				
			}
			
			//
			foreach($list1 as $k=>$val){//var_dump($val);
			    $isclick = 0;
				foreach($arr as $k1=>$v){
					
					if(in_array($val['id'],$v)){
						
						$isclick = 1;
					
						break;
					}
					
				}
				$list[] = array(
				    'id' =>$val['id'],
					'name' =>$val['name'],
					'weight' =>$val['weight'],
					'image' =>$val['image'],
					'thumb' =>$val['thumb'],
					'isclick' =>$isclick,

			    );
				

			}
			
        }
		
		if($type_food ==2){
            $list2 = DB::name('lunch')->field('id,name,weight,image,thumb')->where('status',1)->where('isdelete',0)->select();//午餐
			$recipe = DB::name('recipe')->where('id',$id)->where('status',1)->where('isdelete',0)->find();//
			$lunch = explode(',',$recipe['lunch']);
			if(!empty($recipe['lunch'])){
				foreach($lunch as $key=>$value){
					$info = DB::name('lunch')->where('id',$value)->where('status',1)->where('isdelete',0)->find();//早餐
					$arr[] = array(
						'id' =>$info['id'],
						'name' =>$info['name'],
					);
				}
			}
			foreach($list2 as $k=>$val){//var_dump($val);
			    $isclick = 0;
				foreach($arr as $k1=>$v){
					
					if(in_array($val['id'],$v)){
						
						$isclick = 1;
					
						break;
					}
					
				}
				$list[] = array(
				    'id' =>$val['id'],
					'name' =>$val['name'],
					'weight' =>$val['weight'],
					'image' =>$val['image'],
					'thumb' =>$val['thumb'],
					'isclick' =>$isclick,
				
				
				
			    );
				

			}
			
        }
		
		if($type_food ==3){
            $list3 = DB::name('snack')->field('id,name,weight,image,thumb')->where('status',1)->where('isdelete',0)->select();//午点
			$recipe = DB::name('recipe')->where('id',$id)->where('status',1)->where('isdelete',0)->find();//
			$snack = explode(',',$recipe['snack']);
			if(!empty($recipe['snack'])){
				foreach($snack as $key=>$value){
					$info = DB::name('snack')->where('id',$value)->where('status',1)->where('isdelete',0)->find();//早餐
					$arr[] = array(
						'id' =>$info['id'],
						'name' =>$info['name'],
					);
				}
			}
			
			foreach($list3 as $k=>$val){//var_dump($val);
			    $isclick = 0;
				foreach($arr as $k1=>$v){
					
					if(in_array($val['id'],$v)){
						
						$isclick = 1;
					
						break;
					}
					
				}
				$list[] = array(
				    'id' =>$val['id'],
					'name' =>$val['name'],
					'weight' =>$val['weight'],
					'image' =>$val['image'],
					'thumb' =>$val['thumb'],
					'isclick' =>$isclick,
				
				
				
			    );
				

			}
			
			
        }
		$data['list'] = $list;
		$data['arr'] = $arr;
		if($data){
		$this->json(0,'成功',$data);
		}else{
			$this->json(1,'无常用菜');
		}
    }
	
	
	//存食谱
	
	public  function  saveFood(){
		$type_food = $this->request->param('type_food');
		$phone_account = $this->request->param('phone_account');
		$account = $this->request->param('account');
        $ids = $this->request->param('ids');
        $date = explode('-',$this->request->param('date'));
		if(strlen($date[1])==1){
			$month = '0'.$date[1];
			
		}else{
			$month =$date[1];
		}
		if(strlen($date[2])==1){
			$day = '0'.$date[2];
			
		}else{
			$day = $date[2];
		}
		$data['eat_time']  = $date[0].'-'.$month.'-'.$day;
		
		$employee1 = DB::name('EmployeeManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
		$employee = DB::name('SchoolManagement')->where('schoolID','=',substr($account,0,5))->where('status',1)->where('isdelete',0)->find();//查找校区账号
		$info = DB::name('recipe')->where('eat_time',$data['eat_time'])->where('schoolAccount',$employee['schoolAccount'])->where('status',1)->where('isdelete',0)->find();//午点
		$data['schoolAccount'] = $employee['schoolAccount'];
		$data['schoolName'] = $employee['schoolName'];
		$data['releaser'] = $employee1['name'];
		
		//var_dump($data['releaser']);exit;
		if($info){
			
			if($type_food ==1){	
			    $re = DB::name('recipe')->where('eat_time',$data['eat_time'])->where('schoolAccount',$employee['schoolAccount'])->update(['breakfast'=>$ids]);
			}
			if($type_food ==2){	
			    $re = DB::name('recipe')->where('eat_time',$data['eat_time'])->where('schoolAccount',$employee['schoolAccount'])->update(['lunch'=>$ids]);
			}
			if($type_food ==3){	
			    $re = DB::name('recipe')->where('eat_time',$data['eat_time'])->where('schoolAccount',$employee['schoolAccount'])->update(['snack'=>$ids]);
			}
			

		}else{
			
			if($type_food ==1){	
			    $data['breakfast'] = $ids;
				$data['release_time'] = date('Y-m-d');
			    $re = DB::name('recipe')->insert($data);
			}
			if($type_food ==2){	
			    $data['lunch'] = $ids;
				$data['release_time'] = date('Y-m-d');
			    $re = DB::name('recipe')->insert($data);
			}
			if($type_food ==3){	
			   $data['snack'] = $ids;
			   $data['release_time'] = date('Y-m-d');
			   $re = DB::name('recipe')->insert($data);
			}
			
			
		}
		
		
		if($re){
			$this->json(0,'添加成功');
		}else{
			$this->json(1,'添加失败');
		}
		
		

	}
		//添加课程名  
    public function addCourse(){
        //if($this->request->isPost()){
			$type = $this->request->param('type');
			//var_dump($type);
			if($type==1){
			
				$data['phone_account'] = $this->request->param('phone_account');
			   
				$data['name'] = $this->request->param('name');
				
				$file = $_FILES['file'];
				if(!empty($file )){
					$data['image'] = $this->oneupload($file);
					$data['thumb'] = $this->onethumb($data['image']);
				}
				
				//添加
				
					
				$info = Db::name('ClassroomCourse')->where('name',$data['name'])->where('status',1)->where('isdelete',0)->find();
				
				if(empty($info)) {
					
					$re = Db::name('ClassroomCourse')->insert($data);
				}else{
					
					$re = Db::name('ClassroomCourse')->where('name',$data['name'])->where('status',1)->where('isdelete',0)->update($data);
				}

				if($re){
					$this->json(0,'成功');
				}else{
					$this->json(1,'失败');
				}
				
			}else{
				$this->json(2,'登陆身份错误');
			}
            

        //}
        
     }
	 
	 
	 	    //选择课程 
    public function  chooseCourse (){
        $phone_account = $this->request->param('phone_account');
		$account = $this->request->param('account');
		$type = $this->request->param('type');

		$data = [];
		$data = DB::name('ClassroomCourse')->field('id,name,image,thumb')->where('status',1)->where('isdelete',0)->select();//课程
	
		if($data){
		$this->json(0,'成功',$data);
		}else{
			$this->json(1,'无课程');
		}
    }
	
	// 根据日期获取宝宝课程
	
	public function dateCourse(){
		
		$course_time = $this->request->param('date'); 
		$date = explode('-',$this->request->param('date'));
		if(strlen($date[1])==1){
			$month = '0'.$date[1];
			
		}else{
			$month =$date[1];
		}
		if(strlen($date[2])==1){
			$day = '0'.$date[2];
			
		}else{
			$day = $date[2];
		}
		$course_time = $date[0].'-'.$month.'-'.$day;
	//var_dump($course_time);
		$account = $this->request->param('account'); 
		$phone_account = $this->request->param('phone_account'); 
		
		$employee1 = DB::name('EmployeeManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
		$employee = DB::name('SchoolManagement')->where('schoolID','=',substr($account,0,5))->where('status',1)->where('isdelete',0)->find();//查找校区账号
		//$info = DB::name('recipe')->where('eat_time',$eat_time)->where('schoolName',$employee['schoolName'])->where('status',1)->where('isdelete',0)->find();//午点
		//$data['schoolName'] = $employee['schoolName'];
		
	
		
		$info = DB::name('StudentClassroom')->where('course_time',$course_time)->where('schoolAccount',$employee['schoolAccount'])->where('className',$employee1['className'])->where('status',1)->where('isdelete',0)->find();;//发布
	
		$arr['morning']=[];
	    $arr['afternoon'] =[];
		if($info){
		    $cid = $info['id'];
			if($info['morning']){
				$morning = explode(',',$info['morning']);
			
				foreach($morning as $key=>$value){
					$info1 = DB::name('ClassroomCourse')->where('id',$value)->where('status',1)->where('isdelete',0)->find();//上午课程
					if($info1){
						$arr['morning'][] = array(
						'id' =>$info1['id'],
						'name' =>$info1['name'],
					    );
					}
					
					
				}
				if(count($arr['morning'])<2){
						$arr['morning'][] = array(
						'id' =>0,
						'name' =>'',
					    );
					}
				
			}
			
		
			if($info['afternoon']){
				$afternoon = explode(',',$info['afternoon']);
			
				foreach($afternoon as $key=>$value){
					$info2 = DB::name('ClassroomCourse')->where('id',$value)->where('status',1)->where('isdelete',0)->find();//下午课程
					if($info2){
						$arr['afternoon'][] = array(
						'id' =>$info2['id'],
						'name' =>$info2['name'],
					    );
					}
					
					
				}
				if(count($arr['afternoon'])<2){
						$arr['afternoon'][] = array(
						'id' =>0,
						'name' =>'',
					    );
					}
				
			}else{
				
			}
			
		//	$morning = explode(',',$info['morning']);//获取上午课程
			$morning1 = explode(',',$info['morning']);//获取上午课程
			$afternoon = explode(',',$info['afternoon']);//获取下午课程
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
				   $classroomImages[$i]['image'] = $info['image'];
				   $classroomImages[$i]['thumb'] = $info['thumb'];
				   $classroomImages[$i]['name'] = $info['name'];
				 
				}
			}
			
            $data['rid'] = $cid;
		    $data['arr'] = $arr;
			$data['classroomImages'] = $classroomImages;
		}else{
			$data['rid'] = 0;
			$arr['morning'][0]=['id'=>0,'name'=>''];
		    $arr['morning'][1]=['id'=>0,'name'=>''];
	        $arr['afternoon'][0] =['id'=>0,'name'=>''];
		    $arr['afternoon'][1] =['id'=>0,'name'=>''];
		    $data['arr'] = $arr;
		}
		$this->json(0,'成功',$data);

	}
	
	//存课程
	
	public  function  saveCourse(){
		//$type_food = $this->request->param('type_course');
		$phone_account = $this->request->param('phone_account');
		$account = $this->request->param('account');
        $date = explode('-',$this->request->param('date'));
		if(strlen($date[1])==1){
			$month = '0'.$date[1];
			
		}else{
			$month =$date[1];
		}
		if(strlen($date[2])==1){
			$day = '0'.$date[2];
			
		}else{
			$day = $date[2];
		}
		$data['course_time']  = $date[0].'-'.$month.'-'.$day;
	//	$data['course_time'] = $date[0].'-'.$date[1].'-'.$date[2];
		if($this->request->param('morning_ids')){
			$data['morning'] = $this->request->param('morning_ids');
		}
		
		if($this->request->param('afternoon_ids')){
			$data['afternoon'] = $this->request->param('afternoon_ids');
		}
		
		
		
		$employee1 = DB::name('EmployeeManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
	    $employee = DB::name('SchoolManagement')->where('schoolID','=',substr($account,0,5))->where('status',1)->where('isdelete',0)->find();//查找校区账号
		//var_dump($data['course_time'] );exit('1111');
		$info = DB::name('StudentClassroom')->where('course_time',$data['course_time'])->where('schoolAccount',$employee['schoolAccount'])->where('status',1)->where('isdelete',0)->find();//
		
		$data['schoolAccount'] = $employee['schoolAccount'];
		$data['schoolName'] = $employee['schoolName'];
		$data['className'] = $employee1['className'];
		$data['release'] = $employee1['name'];
		if($info){
			
			$re = DB::name('StudentClassroom')->where('course_time',$data['course_time'])->where('className',$data['className'])->where('schoolAccount',$employee['schoolAccount'])->update(['morning'=>$data['morning'],'afternoon'=>$data['afternoon']]);
		
		}else{
			$data['release_time'] = date('Y-m-d');
			$re = DB::name('StudentClassroom')->insert($data);

			
		}
		
		if($re){
			$this->json(0,'添加成功');
		}else{
			$this->json(1,'添加失败');
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
                
                    $this->json(2,'上传失败');
                }
                
                $data = "/".$upfile;  
               
            }

            return $data;
            
    }
	
	 // 上传图片
    public function upload($files){
       
        $data = array();
    
      $path = 'uploads/file/'.date('Ymd').'/';
      
      if (isset( $files )) {
        //var_dump($files['name']);exit;
        foreach ($files['name'] as $key=> $value){
          
        
          $upfile = $path. $value;
          if (! @file_exists ( $path )) {
            @mkdir ( $path );
          }
          $result = @move_uploaded_file ( $files['tmp_name'][$key], $upfile );
          if (! $result) {
          
            $this->json(2,'上传失败');
          }
          
          $data[] = "/".$upfile;  
          }
          }

        return json_encode($data);
      
    }
    //单张缩略图片
	
	public function onethumb($data){
            //$thumbinfo = array();
          //  foreach($data as $key=>$value){
                $arr = explode('.',$data);
                
                $thumbinfo = $arr[0].'_thumb.'.$arr[1];

                // 指定文件路径和缩放比例
                $filename = '.'.$data;

                $percent = 0.1;
                // 指定头文件Content typezhi值
                header('Content-type: image/jpeg');
                // 获取图片的宽高
                list($width, $height) = getimagesize($filename);
                $img_size = ceil(filesize($filename) / 1000); //获取文件大小
                if($img_size>100){
                    $newwidth = $width * $percent;
                    $newheight = $height * $percent;
                    
                }else{
                    $newwidth = $width;
                    $newheight = $height;
                }
              
                // 创建一个图片。接收参数分别为宽高，返回生成的资源句柄
                $thumb = imagecreatetruecolor($newwidth, $newheight);
                //获取源文件资源句柄。接收参数为图片路径，返回句柄
                $source = imagecreatefromjpeg($filename);
                // 将源文件剪切全部域并缩小放到目标图片上。前两个为资源句柄
                imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth,
                $newheight, $width, $height);
                imagejpeg($thumb, '.'.$arr[0].'_thumb.'.$arr[1]) ;
                imagedestroy($thumb);
                imagedestroy($source);
            //}
        
            return $thumbinfo;
    }
	
	
    public function thumb($data){
            $thumbinfo = array();
            foreach($data as $key=>$value){
                $arr = explode('.',$value);
                
                $thumbinfo[] = $arr[0].'_thumb.'.$arr[1];

                // 指定文件路径和缩放比例
                $filename = '.'.$value;

                $percent = 0.1;
                // 指定头文件Content typezhi值
                header('Content-type: image/jpeg');
                // 获取图片的宽高
                list($width, $height) = getimagesize($filename);
                $img_size = ceil(filesize($filename) / 1000); //获取文件大小
                if($img_size>100){
                    $newwidth = $width * $percent;
                    $newheight = $height * $percent;
                    
                }else{
                    $newwidth = $width;
                    $newheight = $height;
                }
              
                // 创建一个图片。接收参数分别为宽高，返回生成的资源句柄
                $thumb = imagecreatetruecolor($newwidth, $newheight);
                //获取源文件资源句柄。接收参数为图片路径，返回句柄
                $source = imagecreatefromjpeg($filename);
                // 将源文件剪切全部域并缩小放到目标图片上。前两个为资源句柄
                imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth,
                $newheight, $width, $height);
                imagejpeg($thumb, '.'.$arr[0].'_thumb.'.$arr[1]) ;
                imagedestroy($thumb);
                imagedestroy($source);
            }
        
            return json_encode($thumbinfo);
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