<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Exception;
use think\Loader;
use think\Db;
use think\Config;
use think\Session;
class Leave extends Controller{
    //请假页面
    public function index()
    {
            $phone_account = $this->request->param('phone_account');
			$type = $this->request->param('type');
			$account = $this->request->param('account');
       
            $nowtime = date('Y-m-d',time());//当前时间
            //查询当前用户的请假时间在当前时间以后的请假安排
            $result = Db::name('leave')->where('account',$account)->where('end_time','egt',$nowtime)->where('status',1)->where('isdelete',0)->order('end_time desc')->select();
            if($result){
                $this->assign('result',$result);
            }else{
                $this->assign('result','');
            }
			$this->assign('account',$account);
			$this->assign('type',$type);
			//$this->assign('result',$result);
			
            return $this->fetch();
        
		
    }

    //获取请假信息并插入到数据库中
    public function getInfo()
    {
        if(request()->isAjax()){
            $data['start_time'] = $this->request->param('start_time');
		
            $data['end_time'] = $this->request->param('end_time');
            $data['content'] = $this->request->param('content');
            $type = $this->request->param('type');
            $data['account'] = $this->request->param('account');
            $data['release_time'] = date('Y-m-d H:i:s',time());//当前发布时间

            $info = DB::name('SchoolManagement')->where('schoolID','=',substr($data['account'],0,5))->where('status',1)->where('isdelete',0)->find();//查找校区账号
            $data['schoolAccount'] = $info['schoolAccount'];
			$data['schoolName'] = $info['schoolName'];
		   if(empty($type)){
                $info2 = Db::name('StudentManagement')->where('account',$data['account'])->where('status',1)->where('isdelete',0)->find();
                $data['release'] = $info2['name'];//当前登录用户姓名
                $data['className'] = $info2['className'];//所在班级
            }else{
				$info2 = Db::name('EmployeeManagement')->where('account',$data['account'])->where('status',1)->where('isdelete',0)->find();
                $data['release'] = $info2['name'];//当前登录用户姓名
                $data['className'] = $info2['className'];//所在班级
			}
          //  exit( $info2['name']);
            $re = Db::name('leave')->insert($data);
				//var_dump($data);
			//echo ;
            if($re){
                exit('1') ;
    		}else{
    			exit('2') ;

    		}

        }

    }


    /**
     * 删除请假记录
     */
    public function remove()
    {
       if($this->request->isAjax()){
           $id = $this->request->param('id');//获取指定的id
           $bool = Db::name('leave')->where('id',$id)->where('status',1)->where('isdelete',0)->delete();
           if($bool){
               echo 'success';
           }else{
               echo 'fail';
           }
       }else{
           $this->redirect('Leave/index');
       }
    }
}