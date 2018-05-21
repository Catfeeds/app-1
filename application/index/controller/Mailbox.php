<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Exception;
use think\Loader;
use think\Db;
use think\Config;
use think\Session;


class Mailbox extends Controller
{
    public function index()
    {
        $phone_account = $this->request->param('phone_account');
		$type = $this->request->param('type');
		$account = $this->request->param('account');
		
		
		$this->assign('account',$account);
		$this->assign('type',$type);

		return $this->fetch();

    }


    /**
     * 获取留言信息并插入到数据库中
     */
    public function getMessage()
    {

        if(request()->isAjax()){
           
			$release_time = date('Y-m-d H:i:s',time());//留言时间
			$content = $this->request->param('content');//获取留言内容
            $account = $this->request->param('account');//获取账号
			$type = $this->request->param('type');//获取账号
			
			//若是学生
			if(empty($type)){
				$info2 = Db::name('StudentManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
				$name = $info2['name'];//学生姓名
				$schoolName =  $info2['schoolName']; //学区名
				$className = $info2['className']; //班级名
			}else{//若是老师
				$info2 = Db::name('EmployeeManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
				$name = $info2['name']; //老师姓名
				$schoolName =  $info2['schoolName'];//学区名
				$className = $info2['className']; //班级名
			}
			

			//开始插入数据
			$data = array(
				'release' => $name,
				'release_time' => $release_time,
				'schoolName' => $schoolName,
				'className' => $className,
				'content' => $content
			);
			$bool = Db::name('Mailbox')->insert($data);
			if($bool){
				echo 'success';
			}else{
				echo 'fail';
			}
         


        }


    }
}
