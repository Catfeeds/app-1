<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;
use think\Session;
//查看成长日记
class Note extends Controller
{
    public function index()
    {
        
		

        $phone_account = $this->request->param('phone_account');
        $account = $this->request->param('account');
		
        $type = $this->request->param('type');
        
		
		$info3 = DB::name('SchoolManagement')->where('schoolID','=',substr($account,0,5))->where('status',1)->where('isdelete',0)->find();//查找校区账号
        if(empty($type))
		{
			//$info = DB::name('patriarch')->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->find();//或手机号登录
                
            $info1 = Db::name('StudentManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();//学生的基本信息

			
			$vo = Db::name("StudentLinkman")->where('student_id',$info1['id'])->where('relation','<>','学生本人')->select(); //当前用户联系人的信息
			$list =[];
			foreach($vo as $key=>$value){
				$info = DB::name('patriarch')->where('phone','=',$value['number'])->where('status',1)->where('isdelete',0)->find();//或手机号登录
				$list[] = array(
				    'headerurl'=>$info['headerurl'],
					'relation'=>$value['relation'],
				);				
			}
			
			
			$this->view->assign("info", $info1);
			$this->view->assign("list", $list);

            	 
			$note = Db::name("Note");

			$data1 = $note->where('account',$account)->where('status',1)->where('isdelete',0)->order('release_time','desc')->select();  //从tp_note表中查询当前用户的信息
			$data=array();

			if(!empty($data1)){
				foreach($data1 as $key => $value){
					//$info2 = DB::name('patriarch')->where('phone_account','=',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();//或手机号登录
			//	if($info2){
		                 //   if($info3){
		                    //	if($info3['id'] == $info1['id']){
		                    	//
								$riqi = $this->diffDate($info1['birthDate'],$value['release_time']);
								$data[] = array(
									'content' =>$value['content'],    //tp_note表的--内容字段
									'release_time' =>$value['release_time'],  //发布时间
									'riqi' =>$riqi ,  //年月日
									'images' =>json_decode($value['images']),  //图片
									'thumbs' =>json_decode($value['thumbs']),  //图片
								);

			               //     }
		                   // }
					///}              
				}
			}
            
			

			$this->view->assign("data", $data);
            
			$riqi = $this->diffDate($info1['birthDate'],date('Y-m-d'));

			$this->view->assign("riqi", $riqi);
			$this->view->assign("phone_account", $phone_account);
			$this->view->assign("account", $account);
			
			
			return $this->fetch();
		}else{
			$info2 = Db::name('EmployeeManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
            $schoolAccount = $info3['schoolAccount']; //判断当前用户所属校区
            //$schoolName = $info2['schoolName']; //判断当前用户所属校区

            //查询该校区内的所有的宝宝成长日记
            $result = Db::name("note")->where('schoolAccount',$schoolAccount)->where('status',1)->order('release_time','desc')->select();//查询所有
            $array1 = array();
            $array2 = array();
            foreach($result as $value){
                if(!array_key_exists($value['account'],$array1)){
                    $array1[$value['account']] = $value;
                    $info = DB::name('StudentManagement')->where('account',$value['account'])->where('status',1)->find();//根据该账户从tp_student_management中获取信息
                    $array2[$value['account']] = $info;
                }
            }
			
           
            $this->view->assign("data", $array1); //得到以账号为索引，以该行记录(数组)为元素的二维数组
            $this->view->assign("data2", $array2);//得到以账号为索引，以对应姓名为元素的一维数组
			
			$this->view->assign('iswrite',0);//要显示写日记
            return $this->fetch('selectnote');
			

		}

    }
	
	public function index2(){
		    $phone_account = $this->request->param('phone_account');
            $account = $this->request->param('account');
		
            $type = $this->request->param('type');
        
		
		    // $info1 = DB::name('SchoolManagement')->where('schoolID','=',substr($account,0,5))->where('status',1)->where('isdelete',0)->find();//查找校区账号
		    $info1 = Db::name('StudentManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();

			//$info = Db::name("StudentManagement")->where('account',$user_name)->find();   //当前用户的信息
			$list = Db::name("StudentLinkman")->where('student_id',$info1['id'])->where('relation','<>','学生本人')->select(); //当前用户联系人的信息
			$this->view->assign("info", $info1);
			$this->view->assign("list", $list);

            	 
			$note = Db::name("Note");

			$data1 = $note->where('account',$account)->where('status',1)->where('isdelete',0)->order('release_time','desc')->select();  //从tp_note表中查询当前用户的信息
			$data=array();

			if(!empty($data1)){
				foreach($data1 as $key => $value){
					//$info2 = DB::name('patriarch')->where('phone_account','=',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();//或手机号登录
			//	if($info2){
		                 //   if($info3){
		                    //	if($info3['id'] == $info1['id']){
		                    	//
								$riqi = $this->diffDate($info1['birthDate'],$value['release_time']);
								$data[] = array(
									'content' =>$value['content'],    //tp_note表的--内容字段
									'release_time' =>$value['release_time'],  //发布时间
									'riqi' =>$riqi ,  //年月日
									'images' =>json_decode($value['images']),  //图片
									'thumbs' =>json_decode($value['thumbs']),  //图片
								);

			               //     }
		                   // }
					///}              
				}
			}
            
			

			$this->view->assign("data", $data);
            
			$riqi = $this->diffDate($info1['birthDate'],date('Y-m-d'));

			$this->view->assign("riqi", $riqi);
			$this->view->assign("phone_account", $phone_account);
			$this->view->assign("account", $account);
			$this->view->assign("type", $type);
		
			
			return $this->fetch();
		
		
		
	}

	
	public  function seeNote(){
		
		    $phone_account = $this->request->param('phone_account');
            $account = $this->request->param('account');
			$type = $this->request->param('type');
		    $info1 = DB::name('SchoolManagement')->where('schoolID','=',substr($account,0,5))->where('status',1)->where('isdelete',0)->find();//查找校区账号
            $schoolAccount = $info1['schoolAccount']; //判断当前用户所属校区

            //查询该校区内的所有的宝宝成长日记
            $result = Db::name("note")->where('schoolAccount',$schoolAccount)->where('status',1)->order('release_time','desc')->select();//查询所有
            $array1 = array();
            $array2 = array();
            foreach($result as $value){
                if(!array_key_exists($value['account'],$array1)){
                    $array1[$value['account']] = $value;
                    $info = DB::name('StudentManagement')->where('account',$value['account'])->where('status',1)->find();//根据该账户从tp_student_management中获取信息
                    $array2[$value['account']] = $info;
                }
            }
   
            $this->view->assign("data", $array1); //得到以账号为索引，以该行记录(数组)为元素的二维数组
            $this->view->assign("data2", $array2);//得到以账号为索引，以对应姓名为元素的一维数组
			
			
			
            return $this->fetch('selectnote');

	}

    public function diffDate($date1,$date2)
    { 
        $datestart= date('Y-m-d',strtotime($date1));
		$date2= date('Y-m-d',strtotime($date2));
        if(strtotime($datestart)>strtotime($date2)){ 
            $tmp=$date2; 
            $date2=$datestart; 
            $datestart=$tmp; 
        } 
        list($Y1,$m1,$d1)=explode('-',$datestart); 
        list($Y2,$m2,$d2)=explode('-',$date2); 
        
        $Y=$Y2-$Y1; // 1   
        $m=$m2-$m1; // 0   
        $d=$d2-$d1; // -11   
        
        if($d<0){ 
            $d+=(int)date('t',strtotime("-1 month $date2")); 
            $m=$m--; 
        } 
        if($m<0){ 
            $m+=12; 
            $y=$Y--;
        } 
        if($Y == 0 && $m == 0 && $d != 0){
           return $d.'天';
        }elseif($Y == 0 && $m != 0 && $d != 0){
           return $m.'个月'.$d.'天';
        }elseif($Y != 0 && $m == 0 && $d != 0){
           return $Y.'岁'.$d.'天';
        }else{
           return $Y.'岁'.$m.'个月'.$d.'天';
        }  
    } 
}
