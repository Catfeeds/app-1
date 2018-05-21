<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;
use think\Session;

class Schoolrecruit  extends Controller
{
    public function index()
    {  
        $schoolAccount = $this->request->param('schoolAccount');
        if($schoolAccount){
       	    $info = Db::name('EnrolRecruit')->where('schoolAccount',$schoolAccount)->where('type','学校招生')->where('status',1)->where('isdelete',0)->find();//招生信息
       	    $info1 = Db::name('SchoolManagement')->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->find();//学校
       	    $info2 = Db::name('AllSchool')->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->find();//所有学校

       	    $this->view->assign("info", $info);
			$this->view->assign("schoolAccount",$schoolAccount);
       	    $this->view->assign("info1", $info1);
       	    $this->view->assign("grade", $this->grade($info2['educationLevel']));
       	    $this->view->assign("title", $info1['schoolName'].'_'.$info['title']);
            return $this->fetch();
        }
      
    }


    public function recruit(){
    	if($this->request->isPOst()){
    		$data['current_school'] = $this->request->param('current_school');
    		$data['class']  = $this->request->param('class');
    		$data['name']  = $this->request->param('name');
    		$data['sex']  = $this->request->param('sex');
    		$data['birthDate']  = $this->request->param('birthDate');
    		$data['contact']  = $this->request->param('contact');
    		$data['introduce']  = $this->request->param('introduce');
    		$data['schoolAccount']  = $this->request->param('schoolAccount');
    		$data['enroll_time']  = date('Y-m-d');
    		$data['source']  = 'App';

    		$re = Db::name('enrolstudent')->insert($data);

    		if($re){
                exit('1') ;
    		}else{
    			exit('2') ;

    		}
    	}

    }

    public function grade ($educationLevel){
    	$arr = [];
        if($educationLevel == '幼儿园') {
            $arr[0] = '小';
            $arr[1] = '中';
            $arr[2] = '大';
            return $arr;
        }elseif($educationLevel == '小学'){
        	$arr[0] = '一年级';
            $arr[1] = '二年级';
            $arr[2] = '三年级';
            $arr[3] = '四年级';
            $arr[4] = '五年级';
            $arr[5] = '六年级';
            return $arr;

        }elseif($educationLevel == '初中'){
        	$arr[0] = '初一';
            $arr[1] = '初二';
            $arr[2] = '初三';
            return $arr;
        }elseif($educationLevel == '高中'){
        	$arr[0] = '高一';
            $arr[1] = '高二';
            $arr[2] = '高三';
            return $arr;
        }elseif($educationLevel == '大学'){
        	$arr[0] = '大一';
            $arr[1] = '大二';
            $arr[2] = '大三';
            $arr[3] = '大四';
            return $arr;
        }

    }

}
