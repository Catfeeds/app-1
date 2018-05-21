<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

//明星榜
class Starlist extends Controller
{
    public function index()
    {
        $phone_account = $this->request->param('phone_account');
	    $account = $this->request->param('account');
        $type = $this->request->param('type');
       
     
        if(empty($type)){ //若是学生
            $info2 = Db::name('StudentManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
            
		}else{//教师
			$info2 = Db::name('EmployeeManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();echo '1111';
		}
		    $schoolName =  $info2['schoolName'];
            $className =  $info2['className'];
            $date = $this->request->param('date');
        
           //根据当前用户所在校区从tp_starlist表中找到所有该校区的所有明星
			if(!$date){
				 $date = date('Y-m-d');
			}

			$list = Db::name('starlist')->where('schoolName',$schoolName)->where('className',$className)->where('release_time',$date)->where('student','neq','请选择')->where('status',1)->where('isdelete',0)->order('id desc')->select();
			  //echo Db::getlastsql();
			$data = array();
            foreach($list as $key => $value){
 
                $re = Db::name("StudentManagement")->where('status',1)->where('isdelete',0)->where('account',$value['account'])->order('id','desc')->find();
                $data[] = array(
                         'content' =>$value['content'],    
                         'sjstar' =>$value['sjstar'],  
                         'tjstar' =>$value['tjstar'],  
                         'sxstar' =>$value['sxstar'],    
                         'cystar' =>$value['cystar'],  
                         'gastar' =>$value['gastar'],  
                         'wsstar' =>$value['wsstar'],
                         'image' =>$re['student_headurl'],
                         'release_time' =>$value['release_time'],
                         'content' =>$value['content'],
                         'className' =>$value['className'],
                         'student' =>$value['student'],
                      );
              
            }
            $this->view->assign("date", $date);
            $this->view->assign("data", $data);
      
            return $this->fetch();
     
    }
    
}
