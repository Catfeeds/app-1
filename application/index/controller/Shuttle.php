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
//接送管理
class Shuttle extends Controller{
    //显示所有联系人页面
    public function index()
    {
			//通过account判断是否登录----必须传账号
			if($this->request->param('phone_account')!='') {
				
			}
			
		    $phone_account = $this->request->param('phone_account');//获取当前账号
			$account = $this->request->param('account');//获取当前账号
            $info = DB::name('patriarch')->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->find();

			//echo Db::getlastsql();
            $type = $info['type'];//var_dump($info);
            //仅限学生
            if($type == 0){

            	 $student_info =  Db::table('tp_student_linkman a, tp_student_management b')->field('b.id as id')->where('b.id=a.student_id and b.account="'.$account.'" and a.number='.$info['phone'])->where('a.status',1)->where('a.isdelete',0)->where('b.status',1)->where('b.isdelete',0)->find();

               // $student_info = Db::name('StudentManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
                $student_id = $student_info['id'];//当前学生id

                //关于该学生的所有联系人
                $linkman_info = Db::name('StudentLinkman')->where('student_id',$student_id)->where('status',1)->where('isdelete',0)->order('id asc')->select();//关于当前学生所有联系人的所有信息


                $linkman_img = array();//存放所有联系人的人脸图片
                $linkman_access = array();//存放联系人权限
                foreach($linkman_info as $value){
                    $linkman_access_management_info = Db::name('StudentLinkmanAccessManagement')->where('studentLinkman_id',$value['id'])->where('status',1)->where('isdelete',0)->find();

                    $linkman_access[] = $linkman_access_management_info['access'];

                    $linkman_info2 = Db::name('file')->where('id',$linkman_access_management_info['face_file_ids'])->find();
					if($linkman_info2){
						$linkman_img[] = $linkman_info2['name'];
					}else{
						$linkman_img[] = '/uploads/file/033fc20efb497505073ed7ff96f84088.png';
					}
                    
                }


                $this->assign('linkman_access',$linkman_access);//Array( [0] => 1 [1] => 0 [2] => 1 )
                $this->assign('linkman_img',$linkman_img); //Array([0] => /uploads/file/20171207/20171207101028.jpeg [1] => [2] => /uploads/file/20171229/1514536331402759.png)


                $this->assign('student_id',$student_id);
                $this->assign('phone_account',$phone_account);
                $this->assign('account',$account);
                $this->assign('linkman_info',$linkman_info);

                return $this->fetch();

            }


    }



    //禁用联系人
    public function forbidden()
    {
        if($this->request->isGet() && $this->request->param('id')!=''){
            $linkman_id = $this->request->param('id');//获取此联系人id
			 Db::name('StudentLinkman')->where('id',$linkman_id)->where('status',1)->where('isdelete',0)->update(['ismodifyData'=>1]);
            $data = array('access'=>0);
            $bool = Db::name('StudentLinkmanAccessManagement')->where('studentLinkman_id',$linkman_id)->where('status',1)->where('isdelete',0)->update($data);
			//echo Db::getLastsql();
            if($bool){
                echo 'ok';
            }
        }
    }

    //启用联系人
    public function start()
    {
        if($this->request->isGet() && $this->request->param('id')!=''){
            $linkman_id = $this->request->param('id');//获取此联系人id
			Db::name('StudentLinkman')->where('id',$linkman_id)->where('status',1)->where('isdelete',0)->update(['ismodifyData'=>1]);
            $data = array('access'=>1);
            $bool = Db::name('StudentLinkmanAccessManagement')->where('studentLinkman_id',$linkman_id)->where('status',1)->where('isdelete',0)->update($data);
			//echo Db::getLastsql();
            if($bool){
                echo 'ok';
            }
        }
    }

    //删除指定联系人
    public function delete()
    {
        if($this->request->isGet() && $this->request->param('id')!=''){
            $linkman_id = $this->request->param('id');//获取此联系人id
			
            $bool = Db::name('StudentLinkman')->where('id',$linkman_id)->delete();
			$re = Db::name('StudentLinkmanAccessManagement')->where('studentLinkman_id',$linkman_id)->delete(); 
            if($bool){
                echo 'ok';
            }
        }
    }



    //添加联系人页面
    public function index2()
    {

        if($this->request->param('student_id')!=''){
            $student_id = $this->request->param('student_id');
			$re = Db::name('StudentLinkman')->where('student_id',$student_id)->where('status',1)->where('isdelete',0)->where('relation','学生本人')->find();
            if($re){
                $re = 1;
            }else{
                $re = 0;
            }

            $phone_account = $this->request->param('phone_account');
			$account = $this->request->param('account');
			//var_dump($account);
            $this->assign('student_id',$student_id);
            $this->assign('phone_account',$phone_account);
			$this->assign('account',$account);
            $this->assign('re',$re);

            return $this->fetch();
        }else{
            $this->redirect('Shuttle/index'); //跳转到显示联系人页面
        }

    }


    /**
     * 增加联系人
     * 向tp_student_linkman和tp_student_linkman_access_management表添加数据
     */
    public function addLinkman()
    {
        if($this->request->isPost()){
            $student_id = $this->request->param('student_id');//学生id
            $phone_account = $this->request->param('phone_account');//当前账号
            $account = $this->request->param('account');//当前账号
            $relation = $this->request->param('relation');//联系人关系
            $name = $this->request->param('name');//联系人姓名
            $number = $this->request->param('number');//联系人电话
//var_dump($_POST);
            //将联系人信息插入到数据表tp_student_linkman
            $data = array(
                'student_id' => $student_id,
                'name' => $name,
                'relation' => $relation,
                'number' => $number
            );
            $obj = Db::name('StudentLinkman');
            $bool = $obj->insert($data);
            if($bool){
                $studentLinkman_id =  $obj->getLastInsID();//获取新插入的联系人id
                $data2 = array(
                    'studentLinkman_id' => $studentLinkman_id,
					'access' =>1,
                );
                Db::name('StudentLinkmanAccessManagement')->insert($data2);//将新插入的联系人id插入表中
                $this->redirect('Shuttle/index?phone_account='.$phone_account.'&account='.$account);//跳转到显示联系人页面
            }else{
                echo '<script>history.back()</script>';
            }

        }else{
            $this->redirect('Shuttle/index2');//不提示，直接跳转到添加联系人页面
        }

    }


}