<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;
use think\Session;
use think\Request;

class Entranceapi extends Controller
{
    //学生信息
    public function info() {
        $phone_account = $this->request->param('phone_account');
		$type = $this->request->param('type');
        $account = $this->request->param('account');
		//var_dump($account);
        if($phone_account){
            $info = DB::name('patriarch')->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->find();//
            
            if($info){
                
                $student['grade'] = $info['grade'];
                $student['headerurl'] = $info['headerurl'];
				$student['city'] = $info['city'];
				$student['province'] = $info['province'];
                if(empty($type)){
					$linkman = DB::name('StudentLinkman')->where('number','=',$info['phone'])->where('status',1)->where('isdelete',0)->find();
					$StudentManagement = DB::name('StudentManagement')->where('account','=',$account)->where('status',1)->where('isdelete',0)->find();
			   //   echo DB::getlastsql();exit;
				   $firstlinkman = DB::name('StudentLinkman')->where('student_id','=',$StudentManagement['id'])->where('status',1)->where('isdelete',0)->order('id','asc')->limit(1)->find();
				  // var_dump($firstlinkman);
				   //echo Db::getlastsql();echo "<hr>" ; var_dump($firstlinkman);exit;  
					if($firstlinkman['number'] == $info['phone'] ){
						$firstlinkman = 1;
					}else{
						$firstlinkman = 0;
					}
					$student['firstlinkman'] = $firstlinkman;//第一联系人
					$student['name'] = $StudentManagement['name'];//第一联系人
					
				}else{
					$employee = DB::name('EmployeeManagement')->where('iphone','=',$info['phone'])->where('account','=',$account)->where('status',1)->where('isdelete',0)->find();
					
					$student['firstlinkman'] = 0;//第一联系人
					$student['name'] = $employee['name'];//第一联系人
					
				}
                
            //var_dump($StudentManagement);
                $this->json(0,'正确',$student);
            }else{
                $this->json(1,'用户不存在');
            }
            
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
