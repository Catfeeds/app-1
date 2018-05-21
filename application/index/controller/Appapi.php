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

class Appapi extends Controller
{
    //班级
    public function classes()
    {   
        $phone_account = $this->request->param('phone_account');
        $phone_account = isset($phone_account)?$phone_account:'';
		$account = $this->request->param('account');
        $account = isset($account)?$account:'';
		$type = $this->request->param('type');
        $type = isset($type)?$type:'';
        $page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
		
        $info1 = DB::name('SchoolManagement')->where('schoolID','=',substr($account,0,5))->where('status',1)->where('isdelete',0)->find();//查找校区账号
	//	var_dump($info1);
        if($phone_account){  

                if(empty($type)){//家长
                    $student = Db::name('StudentManagement')->field('student_headurl,schoolName,className,name')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
                    $student['headurl'] = $student['student_headurl'];
			   }elseif($type==1){//园长
				    $info5 = DB::name('patriarch')->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->find();//获取头像
                    $student = DB::name('EmployeeManagement')->field('schoolName,className,name')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
                    $student['headurl'] = $info5['headerurl'];
			   }elseif($type==2){//教师
				    $info5 = DB::name('patriarch')->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->find();//获取头像
                    $student = DB::name('EmployeeManagement')->field('schoolName,className,name')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
                    $student['headurl'] = $info5['headerurl'];
			   }
              //  var_dump($student);

                //动态信息
                $dynamic = Db::name('dynamic')->where('isdelete',0)->where('status',1)->where('schoolAccount',$info1['schoolAccount'])->order('release_time','desc')->limit($page,$num)->select();
                
				$list = array();
                foreach($dynamic as $key=>$value){
                    if($value['type_num'] == 1){
                        $info2 = Db::name("notice")->where('isdelete','0')->where("status",1)->where("id",$value['pid'])->find();//园内通知
						
                        $info3 = Db::name("EmployeeManagement")->where('isdelete','0')->where("status",1)->where("account",$info2['account'])->find();//员工信息
                        $info4 = DB::name('patriarch')->where('phone_account','=',$info2['phone_account'])->where('status',1)->where('isdelete',0)->find();//获取头像
                        $comment_count = Db::name("NoticeComment")->where('isdelete','0')->where("status",1)->where("nid",$info2['id'])->count();//统计评论个数
                        $zan_count = Db::name("NoticeZan")->where('isdelete','0')->where("status",1)->where("iszan",1)->where("nid",$info2['id'])->count();//统计赞个数
                        $iszan = Db::name("NoticeZan")->where('isdelete','0')->where("status",1)->where("nid",$info2['id'])->where("phone_account",$phone_account)->where("iszan",1)->find();//当前用户是否点赞
                        if($iszan){
                            $iszan = 1;
                        }else{
                            $iszan = 0;
                        }

                    }elseif($value['type_num'] == 2){
                        $info2 = Db::name("mien")->where('isdelete','0')->where("status",1)->where("id",$value['pid'])->find();//园区风采
						//var_dump($info2);exit('aaa');
                        $info3 = Db::name("EmployeeManagement")->where('isdelete','0')->where("status",1)->where("account",$info2['account'])->find();//员工信息
                        $info4 = DB::name('patriarch')->where('phone_account','=',$info2['phone_account'])->where('status',1)->where('isdelete',0)->find();//获取头像
                        $comment_count = Db::name("MienComment")->where('isdelete','0')->where("status",1)->where("rid",$info2['id'])->count();//统计评论个数
                        $zan_count = Db::name("MienZan")->where('isdelete','0')->where("status",1)->where("iszan",1)->where("rid",$info2['id'])->count();//统计赞个数
                        // $iszan = Db::name("MienZan")->where('isdelete','0')->where("status",1)->where("rid",$info2['id'])->where("phone_account",$phone_account)->where("iszan",1)->find();//当前用户是否点赞
                        // if($iszan){
                            // $iszan = 1;
                         // }else{
                            // $iszan = 0;
                         // }

                    }
                   
                    $list[] = array(
                        'id' => $value['pid'],
                        'name' => $info3 ['name'].'老师',
                        'headerurl' => $info4['headerurl'],
                        'release_time' => $value['release_time'],
                        'content' => $info2['content'],
                        'zan_count' => $zan_count,
                        'images' => json_decode($info2['images']),
                        'thumbs' => json_decode($info2['thumbs']),
                        'comment_count' =>  $comment_count,
                       // 'iszan' =>  $iszan,
                        'type_num' => $value['type_num'],
                    );
                }
               
       //  var_dump($list);
                //广告位
                $banner = Db::name('banner')->where('isdelete',0)->where('status',1)->select();
                $data['banner'] = $banner;
                $data['student'] = $student;
                $data['dynamic'] = $list;
                if(!empty($data['banner'])&&!empty($data['student'])){
                        
                        $this ->json(0,'正确',$data);
                }else{

                        $this ->json(1,'信息不全');
                }
        }else{
            $this ->json(2,'无参数');
        }
    }
  //班级发布动态
    public  function  classdynamic (){
        $data['content'] =  $this->request->param('content');//内容
        $tongbu = $this->request->param('tongbu');//同步成长日记
        $phone_account = $this->request->param('phone_account');
		$account = $this->request->param('account');
        if(!empty($_FILES['file'])){
            $files = $_FILES['file'];
            if($files){
                $data['images'] = $this->upload($files); 
                $data['thumbs'] = $this->thumb(json_decode($data['images']));
            
            }
        }
    
            $data['phone_account'] = $phone_account;
            $data['release_time'] = date('Y-m-d H:i:s');

            $info = DB::name('patriarch')->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->find();//或手机号登录
                
            $student =  Db::name('StudentManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
            $info1 = DB::name('SchoolManagement')->where('schoolID','=',substr($account,0,5))->where('status',1)->where('isdelete',0)->find();//查找校区账号
            if($student){
                $data['schoolAccount'] = $info1['schoolAccount'];
                $data['release'] = $phone_account;
                $data['title'] = $info['grade'].'家长';
               // $data['headerurl'] = $info['headerurl'];
            }else{
        
                $student = Db::name('EmployeeManagement')->where('account',$account)->where('status',1)->find();
                $data['schoolAccount'] = $info1['schoolAccount'];
                $data['release'] = $student['name'];
               // $data['headerurl'] = $student['employee_headurl'];
              
            }
    
            if(!empty($tongbu)){
                $data1['content'] = $data['content'];
                $data1['release_time'] = $data['release_time'];
                $data1['images'] = $data['images'];
                $data1['release'] = $phone_account;
                $data1['title'] = $info['grade'].'家长';
                $data1['thumbs'] = $data['thumbs'];
                $data1['schoolAccount'] = $data['schoolAccount'];
                $data1['type'] = '成长日记';

                Db::name('note')->insert($data1);
            }

            $data['type'] = '园区风采';
            $re = Db::name('dynamic')->insertgetid($data);
            $url = '/index/expression/details?id='.$re;
            $re1 = Db::name('dynamic')->where('id',$re)->update(['url'=>$url]);
            if($re1){

                $this->json(0,'发布成功');

            }else{

                $this->json(1,'发布失败');
            }

    }
  //班级赞
  public function classzan(){
      
        $data['rid'] = $this->request->param('id');//动态id
                
        $data['phone_account'] = $this->request->param('phone_account');//账号

        $data['zan_time'] = date('Y-m-d H:i:s');
        $model = Db::name('zan');
        $info = $model->where('rid',$data['rid'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->find();
         
        if(empty($info)){
          $data['iszan'] = 1;
          $re = $model->insert($data);
          if($re){
            $this->json(0,'赞成功');
          }else{
            $this->json(3,'赞成功');
          }
          
        }elseif($info['iszan'] == 1){
          $re = $model->where('rid',$data['rid'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['iszan'=>0]);
                    if($re){
            $this->json(1,'取消赞成功');
          }else{
            $this->json(2,'取消赞失败');
          }
           

        }elseif($info['iszan'] == 0){

          $model->where('rid',$data['rid'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['iszan'=>1]);
          $this->json(0,'赞成功');
        }

      }
      

      //发表班级评论
    public   function  classcomment (){

        if($this->request->isPost()){
             $data['rid'] = $this->request->param('id');
             $data['phone_account'] = $this->request->param('phone_account');
             $data['content'] = $this->request->param('content');
             $data['comment_time'] = date('Y-m-d H:i:s');
             $re = Db::name('comment')->insert($data);

             if($re){
                $this ->json(0,'评论成功');

             }else{
                $this ->json(0,'评论失败');
             }

        }else{
            //该条动态信息
            $rid = $this->request->param('id');
            $dynamicinfo = Db::name('dynamic')->where('id',$rid)->where('status',1)->where('isdelete',0)->find();

            $dynamic['name'] = $dynamicinfo['title'];
            $dynamic['headerurl'] = $dynamicinfo['headerurl'];
            $dynamic['content'] = $dynamicinfo['content'];
            $dynamic['release_time'] = $dynamicinfo['release_time'];
            $dynamic['images'] = json_decode($dynamicinfo['images']);
            

            $commentlist = Db::name('comment')->where('rid',$rid)->where('status',1)->where('isdelete',0)->order('comment_time','desc')->select();
            $zanlist = Db::name('zan')->where('rid',$rid)->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->select();
            $comment =array();
            $zan =array();
          
            //动态评论
            if($commentlist){
                foreach ($commentlist as $key => $value) {
                      $info =  DB::name('patriarch')->where('phone_account','=',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();
                      $comment[]=array(
                         'name' =>$info['grade'].'家长',
                         'headerurl' =>$info['headerurl'],
                         'comment_time'=>$value['comment_time'],
                         'content' =>$value['content']

                     );
                    
                }
            }
            
            //动态赞
            foreach ($zanlist as $key => $value) {
                   $info =  DB::name('patriarch')->where('phone_account','=',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();
                   $zan[]=array(
                       'name' =>$info['grade'].'家长',
                       'headerurl' =>$info['headerurl'],
                   );
            }
            
            $data['dynamic'] = $dynamic;
            $data['zan'] = $zan;
            $data['comment'] = $comment;

            if(!empty($data['dynamic'])){
                $this ->json(0,'正确',$data);
            }else{
                $this ->json(1,'信息不全');
            }
        }

    }
	
	//发布成长日记
	public  function writeNote(){
		$data['phone_account'] = $this->request->param('phone_account'); 
		$data['account'] = $this->request->param('account');
		$data['content'] = $this->request->param('content');
		$data['release_time'] = date('Y-m-d H:i:s');
		$info1 = DB::name('SchoolManagement')->where('schoolID','=',substr($data['account'],0,5))->where('status',1)->where('isdelete',0)->find();//查找校区账号
		$data['schoolAccount'] = $info1['schoolAccount'];
		$data['schoolName'] = $info1['schoolName'];
		
		$data['release'] = $info1['schoolName'];
		if(!empty($_FILES['file'])){
                $files = $_FILES['file'];
                if($files){
                    $data['images'] = $this->upload($files); 
                    $data['thumbs'] = $this->thumb(json_decode($data['images']));
                
                }
        }
		
		$re = DB::name('note')->insert($data);
		if($re){
			$this ->json(0,'发布成功');
		}else{
			$this ->json(1,'发布失败');
		}
	
		
		
	}
	
  
    //采集学生联系人
   
    public function collect(){
       
        $phone_account = $this->request->param('phone_account');
		$account = $this->request->param('account');
		$type = $this->request->param('type');
		//var_dump($account );
		if(empty($type)){//家长
			$info = DB::name('patriarch')->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->find();

			$student =  Db::name('StudentManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();

			$linkman = array();
			if($student){
				$studentlinkman = Db::name('StudentLinkman')->where('student_id',$student['id'])->where('status',1)->where('isdelete',0)->order('id','desc')->select();
				if($studentlinkman){
					foreach ($studentlinkman as $key => $value) {
						$linkmanimg = Db::name('StudentLinkmanAccessManagement')->where('studentlinkman_id',$value['id'])->where('status',1)->where('isdelete',0)->find();
						if($linkmanimg['face_file_ids']){
							$imginfo = Db::name('file')->where('id',$linkmanimg['face_file_ids'])->find();
						}else{
						$imginfo['name'] = '';
						}
						if($value['number'] == $info['phone']){//是否可以更改图片
							$isimage = 1;
						}else{
							$isimage = 0;

						}
						$linkman[] = array(
					   'id' =>$value['id'],
					   'relation' =>$value['relation'],
					   'image' =>$imginfo['name'],
					   'isimage' =>$isimage,
					   );
					}

					$this->json(0,'正确',$linkman);
				}else{
					$this->json(1,'没有数据');
				}
			}
			
		}else{
			$employee = DB::name('EmployeeManagement')->where('account','=',$account)->where('status',1)->where('isdelete',0)->find();
			$employeeImg = DB::name('EmployeeAccessManagement')->where('employee_id','=',$employee['id'])->where('status',1)->where('isdelete',0)->find();
			if($employeeImg){
				$imginfo = Db::name('file')->where('id',$employeeImg['face_file_ids'])->find();
			}
			$linkman[0] = array(
					   'id' =>$employee['id'],
					   'relation' =>$employee['name'],
					   'image' =>$imginfo['name'],
					   'isimage' =>1,
					   );
			$this->json(0,'正确',$linkman);
			
			
			
		}
        
    }
   //采集学生联系人图片
    public function collectimg(){
        $id = $this->request->param('id');
        $type = $this->request->param('type');
        if(!empty($_FILES['file'])){
                $files = $_FILES['file'];
                if($files){
                $data1['name'] = $this->oneupload($files); 
                $data1['mtime'] = date('Y-m-d');
                $re = Db::name('File')->insertgetid($data1);
               }
            }
		if(empty($re)){
		  $this->json(4,'采集图片失败');
		  
		}

        $data['access'] = 1;
        $data['face_file_ids'] = $re;
		if(empty($type)){
			$info = Db::name('StudentLinkmanAccessManagement')->where('studentlinkman_id',$id)->where('status',1)->where('isdelete',0)->find();//是否上传采集图片
  
			if($info['face_file_ids']){
				$re1 =  Db::name('StudentLinkmanAccessManagement')->where('studentlinkman_id',$id)->where('status',1)->where('isdelete',0)->update($data);
				Db::name('StudentLinkman')->where('id',$id)->where('status',1)->where('isdelete',0)->update(['ismodifyImg'=>1]);
		  
				if($re1){
				   $this->json(2,'修改成功');
				}else{
				   $this->json(3,'修改失败');
				}
			}else{
				$re2 = Db::name('StudentLinkmanAccessManagement')->where('studentlinkman_id',$id)->where('status',1)->where('isdelete',0)->update($data);
				Db::name('StudentLinkman')->where('id',$id)->where('status',1)->where('isdelete',0)->update(['isUpload'=>1]);
				if($re2){
				   $this->json(0,'上传成功');
				}else{
				   $this->json(1,'上传失败');
				}
			}
			
		}else{
			$info = Db::name('EmployeeAccessManagement')->where('employee_id',$id)->where('status',1)->where('isdelete',0)->find();//是否上传采集图片
			if($info['face_file_ids']){
				$re1 =  Db::name('EmployeeAccessManagement')->where('employee_id',$id)->where('status',1)->where('isdelete',0)->update($data);
				Db::name('EmployeeManagement')->where('id',$id)->where('status',1)->where('isdelete',0)->update(['ismodifyImg'=>1]);
				if($re1){
				   $this->json(2,'修改成功');
				}else{
				   $this->json(3,'修改失败');
				}
				
			}else{
				$re2 = Db::name('EmployeeAccessManagement')->where('employee_id',$id)->where('status',1)->where('isdelete',0)->update($data);
				Db::name('EmployeeManagement')->where('id',$id)->where('status',1)->where('isdelete',0)->update(['isUpload'=>1]);
				if($re2){
				   $this->json(0,'上传成功');
				}else{
				   $this->json(1,'上传失败');
				}
				
			}
			 
		}
        

    }
  
 
    
  //     //发表园内通知评论
    public   function  noticecomment (){

      //  if($this->request->isPost()){
             $id = $this->request->param('id');
             $data['phone_account'] = $this->request->param('phone_account');
			 $data['account'] = $this->request->param('account');
             $data['content'] = $this->request->param('content');
			 $type_num = $this->request->param('type_num');
             $data['comment_time'] = date('Y-m-d H:i:s');
			 if($type_num==1){
				 $data['nid'] = $id;
				 $re = Db::name('NoticeComment')->insert($data);
			 }elseif($type_num==2){
				 $data['rid'] =  $id;
				 $re = Db::name('MienComment')->insert($data);
			 }
             

             if($re){
                $this ->json(0,'评论成功');

             }else{
                $this ->json(0,'评论失败');
             }


    }
	//亲子任务
	 public function parentchild()
    {
        
		$state = $this->request->param('state');
		$phone_account = $this->request->param('phone_account');
        $phone_account = isset($phone_account)?$phone_account:'';
		$account = $this->request->param('account');
        $account = isset($account)?$account:'';
		$type = $this->request->param('type');
        $type = isset($type)?$type:'';
        $page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
		
		
		$release_time = date('Y-m-d',time()-7*60*60*24);
		
		$data = [];
	//	return $this->fetch();
        if(empty($type)){//若是家长
	//	var_dump($release_time);
	        $info1 = DB::name('SchoolManagement')->where('schoolID','=',substr($account,0,5))->where('status',1)->where('isdelete',0)->find();//查找校区账号
		    $info2 = Db::name('StudentManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
		    $schoolAccount =  $info1['schoolAccount'];//所在校区
            $className = $info2['className']; //所在班级
			//var_dump($className);
			if($state ==1){//已完成
				$data1 = Db::table('tp_parentchild a, tp_parentchild_complete b')->field('a.id as id,a.title as title,a.release_time as release_time')->where('a.id=b.pid and b.account="'.$account.'"')->where('a.status',1)->where('a.release_time','gt',$release_time)->where('a.isdelete',0)->where('b.status',1)->where('b.isdelete',0)->order('a.release_time','desc')->limit($page,$num)->select();
			   
			   if($data1){
					foreach($data1 as $key =>$value){
						$count = Db::name('ParentchildComplete')->where('pid',$value['id'])->where('status',1)->where('isdelete',0)->count();
						$data[] = array(
							'id' =>$value['id'],
							'title' =>$value['title'],
							'release_time' =>$value['release_time'],
							'count' =>$count,
							'state' =>'已完成',
						
						
						);
				    }
					
				}
				
			}elseif($state ==0){//待完成
				$parentchild1 = Db::table('tp_parentchild a, tp_parentchild_complete b')->field('a.id as id')->where('a.id=b.pid and b.account="'.$account.'"')->where('a.status',1)->where('a.isdelete',0)->where('b.status',1)->where('b.isdelete',0)->order('a.release_time','desc')->select();
				$ids = '';
				foreach($parentchild1 as $key=>$value){
					$ids.=$value['id'].',';
				}
				$ids = substr($ids,0,-1);
				$data1 = Db::name('parentchild')->field('id,title,release_time')->where('schoolAccount',$schoolAccount)->where('className',$className)->where('release_time','gt',$release_time)->where('status',1)->where('isdelete',0)->where('id','not in',$ids)->order('release_time','desc')->limit($page,$num)->select();
              //  echo DB::getlastsql();
			//	var_dump($data1 );
				if($data1){
					foreach($data1 as $key =>$value){
						$count = Db::name('ParentchildComplete')->where('pid',$value['id'])->where('status',1)->where('isdelete',0)->count();
						$data[] = array(
							'id' =>$value['id'],
							'title' =>$value['title'],
							'release_time' =>$value['release_time'],
							'count' =>$count,
							'state' =>'待完成',
						
						
						);
				    }
					
				}
			}elseif($state ==2){//已过期一周
				
				$data1 = Db::name('parentchild')->field('id,title,release_time')->where('schoolAccount',$schoolAccount)->where('className',$className)->where('release_time','lt',$release_time)->where('status',1)->where('isdelete',0)->order('release_time','desc')->limit($page,$num)->select();
	            if($data1){
					foreach($data1 as $key =>$value){
						$count = Db::name('ParentchildComplete')->where('pid',$value['id'])->where('status',1)->where('isdelete',0)->count();
						$data[] = array(
							'id' =>$value['id'],
							'title' =>$value['title'],
							'release_time' =>$value['release_time'],
							'count' =>$count,
							'state' =>'已过期',
						
						
						);
				    }
					
				}
		    }
			

        }elseif($type==2){//若是教师
		    $info1 = DB::name('SchoolManagement')->where('schoolID','=',substr($account,0,5))->where('status',1)->where('isdelete',0)->find();//查找校区账号
		    $info2 = Db::name('EmployeeManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
		    $schoolAccount =  $info1['schoolAccount'];//所在校区
			$schoolID =  $info1['schoolID'];//所在校区
            $className = $info2['className']; //所在班级
			
			//$info = Db::name('parentchild')->field('id,release_time')->where('release_time',date("Y-m-d"))->where('status',1)->where('isdelete',0)->find();
			//if($info){
				if($state ==1){//已完成学生
				    $info = Db::name('parentchild')->field('id,release_time')->where('schoolAccount',$schoolAccount)->where('className',$className)->where('release_time',date("Y-m-d"))->where('status',1)->where('isdelete',0)->find();
					
					if($info){
						$data1 = Db::table('tp_student_management a, tp_parentchild_complete b')->field('b.id as id,a.name as title,b.release_time as release_time')->where('a.account=b.account and b.pid='.$info['id'])->where('a.status',1)->where('a.isdelete',0)->where('b.status',1)->where('b.isdelete',0)->order('b.release_time','desc')->limit($page,$num)->select();
						
						if($data1){
							foreach($data1 as $key =>$value){
					
								$data[] = array(
									'id' =>$value['id'],
									'title' =>$value['title'],
									'release_time' =>$value['release_time'],
									'count' =>0,
									'state' =>'已完成',
								
								
								);
							}
							
						}
					
					}
					
				}elseif($state ==0){//待完成学生
				    $info = Db::name('parentchild')->field('id,release_time')->where('schoolAccount',$schoolAccount)->where('className',$className)->where('release_time',date("Y-m-d"))->where('status',1)->where('isdelete',0)->find();
					if($info){
						$list1 = Db::table('tp_student_management a, tp_parentchild_complete b')->field('a.id as id')->where('a.account=b.account and b.pid='.$info['id'])->where('a.status',1)->where('a.isdelete',0)->where('b.status',1)->where('b.isdelete',0)->order('b.release_time','desc')->select();
						$ids = '';
						foreach($list1 as $key=>$value){
							$ids.=$value['id'].',';
						}
						$ids = substr($ids,0,-1);
						$list = Db::name('StudentManagement')->field('id,name')->where('account','LIKE', $schoolID.'%')->where('className',$className)->where('id','not in',$ids)->where('status',1)->where('isdelete',0)->limit($page,$num)->select();
					//	echo DB::getlastsql();
						if($list){
							foreach($list as $key=>$value){
								$data[] = array(
									'id' =>$info['id'],
									'title' =>$value['name'],
									'release_time' =>$info['release_time'],
									'count' =>0,
									'state' =>'待完成',
								);
							
							}
						}
						
					}
					
				}elseif($state ==2){//历史纪录
				    $data1 = Db::name('parentchild')->field('id,title,release_time')->where('schoolAccount',$schoolAccount)->where('className',$className)->where('phone_account',$phone_account)->where('release_time','neq',date("Y-m-d"))->where('status',1)->where('isdelete',0)->order('release_time','desc')->limit($page,$num)->select();
				   
				    if($data1){
						foreach($data1 as $key =>$value){
						    $count = Db::name('ParentchildComplete')->where('pid',$value['id'])->where('status',1)->where('isdelete',0)->count();
							$data[] = array(
								'id' =>$value['id'],
								'title' =>$value['title'],
								'release_time' =>$value['release_time'],
								'count' =>$count,
								'state' =>'已过期',
							
							
							);
						}
					
				    }
				}
		
		}elseif($type==1){//园长
			$info1 = DB::name('SchoolManagement')->where('schoolID','=',substr($account,0,5))->where('status',1)->where('isdelete',0)->find();//查找校区账号
			$schoolAccount =  $info1['schoolAccount'];//所在校区
			$schoolID =  $info1['schoolID'];//所在校区
			if($state ==1){//已完成学生
				    $list = Db::name('parentchild')->field('id,release_time')->where('schoolAccount',$schoolAccount)->where('release_time',date("Y-m-d"))->where('status',1)->where('isdelete',0)->select();
				//	var_dump($info);exit;
				    if($list){
						foreach($list as $key=>$value){
							$data1 = Db::table('tp_student_management a, tp_parentchild_complete b')->field('b.id as id,a.name as title,b.release_time as release_time')->where('a.account=b.account and b.pid='.$value['id'])->where('a.status',1)->where('a.isdelete',0)->where('b.status',1)->where('b.isdelete',0)->order('b.release_time','desc')->limit($page,$num)->select();
							if($data1){
								foreach($data1 as $key =>$value){
						
									$data[] = array(
										'id' =>$value['id'],
										'title' =>$value['title'],
										'release_time' =>$value['release_time'],
										'count' =>0,
										'state' =>'已完成',
									
									
									);
								}
								
							}
					    }
					}	
			}elseif($state ==0){//待完成学生
				    $list1 = Db::name('parentchild')->field('id,release_time')->where('schoolAccount',$schoolAccount)->where('release_time',date("Y-m-d"))->where('status',1)->where('isdelete',0)->select();
				//	var_dump($info);exit;
				    $ids = '';
				    if($list1){
						foreach($list1 as $key=>$value){
							$data1 = Db::table('tp_student_management a, tp_parentchild_complete b')->field('a.id as id')->where('a.account=b.account and b.pid='.$value['id'])->where('a.status',1)->where('a.isdelete',0)->where('b.status',1)->where('b.isdelete',0)->order('b.release_time','desc')->limit($page,$num)->select();
							if($data1){
								foreach($data1 as $k=>$val){
								    $ids.= $val['id'].',';//获取完成学生的id
							    }
							}
						}	
						if($ids){
							$ids = substr($ids,0,-1);
							$list = Db::name('StudentManagement')->field('id,name')->where('account','LIKE', $schoolID.'%')->where('id','not in',$ids)->where('status',1)->where('isdelete',0)->limit($page,$num)->select();
						}else{
						
							$list = Db::name('StudentManagement')->field('id,name')->where('account','LIKE', $schoolID.'%')->where('status',1)->where('isdelete',0)->limit($page,$num)->select();
						}
					//	var_dump($list);
						if($list){
							foreach($list as $k1=>$v){
								$data[] = array(
									'id' =>$v['id'],
									'title' =>$v['name'],
									'release_time' =>$value['release_time'],
									'count' =>0,
									'state' =>'待完成',
								);
						
							}
						} 

					   
					
			        }

		    }elseif($state ==2){//已过期
				  //  $list1 = Db::name('parentchild')->field('id,release_time')->where('schoolAccount',$schoolAccount)->where('release_time','neq',date("Y-m-d"))->where('status',1)->where('isdelete',0)->select();
			     	$data1 = Db::name('parentchild')->field('id,title,release_time')->where('schoolAccount',$schoolAccount)->where('release_time','neq',date("Y-m-d"))->where('status',1)->where('isdelete',0)->order('release_time','desc')->limit($page,$num)->select();
				   
				    if($data1){
						foreach($data1 as $key =>$value){
						    $count = Db::name('ParentchildComplete')->where('pid',$value['id'])->where('status',1)->where('isdelete',0)->count();
							$data[] = array(
								'id' =>$value['id'],
								'title' =>$value['title'],
								'release_time' =>$value['release_time'],
								'count' =>$count,
								'state' =>'已过期',
							
							
							);
						}
					
				    }

		    }

        }
				
		if($data){
				$this ->json(0,'正确',$data);
			}else{
				$this ->json(1,'无数据');
		}
	
    }	
    //亲子任务详情
	
	public function parentchildComplete(){
		$state = $this->request->param('state');
		$id = $this->request->param('id');
	    $id = isset($id)?$id:'';
		$phone_account = $this->request->param('phone_account');
        $phone_account = isset($phone_account)?$phone_account:'';
		$account = $this->request->param('account');
        $account = isset($account)?$account:'';
		$type = $this->request->param('type');
        $type = isset($type)?$type:'';
        $page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
		$ParentchildComplete =[];
	
		if(empty($type)){//若是家长
		
		    echo $this->call($id,$phone_account,$account,$page,$num);
		

		}else{
			if($state==1){//已完成    教师端查看学生完成任务
				//学生完成的任务
				$info = Db::name('ParentchildComplete')->field('id,pid,account,content,thumbs,images,release_time')->where('id',$id)->where('status',1)->where('isdelete',0)->find();
			//	echo DB::getlastsql();
				if($info){
					$data['iscomplete'] = 1;
				}else{
					$data['iscomplete'] = 0;
				}

				//学生完成哪个任务
				$parentchild = Db::name('parentchild')->field('id,title,release_time,content,images,thumbs')->where('id',$info['pid'])->where('status',1)->where('isdelete',0)->find();
	        ///  var_dump($parentchild );
			    $parentchild['images'] = json_decode($parentchild['images']);
		        $parentchild['thumbs'] = json_decode($parentchild['thumbs']);
				
                 //获取学生头像
				$student = Db::name('StudentManagement')->field('name,student_headurl')->where('account',$info['account'])->where('status',1)->where('isdelete',0)->find();
				
				$ParentchildComplete[0]['name'] = $student['name'];
				$ParentchildComplete[0]['headerurl'] = $student['student_headurl'];
				$ParentchildComplete[0]['id'] = $info['id'];
				$ParentchildComplete[0]['content'] = $info['content'];
				$ParentchildComplete[0]['release_time'] = $info['release_time'];
				$ParentchildComplete[0]['images'] = json_decode($info['images']);
		        $ParentchildComplete[0]['thumbs'] = json_decode($info['thumbs']);
				
				
				
				$data['parentchild'] = $parentchild;
				$data['ParentchildComplete'] = $ParentchildComplete;
				$this ->json(0,'正确',$data);
				
			}elseif($state==2){//已过期
				
				echo $this->call($id,$phone_account,$account,$page,$num);

			}elseif($state==0){
				 //任务
				$parentchild = Db::name('parentchild')->field('id,title,release_time,content,images,thumbs')->where('id',$id)->where('status',1)->where('isdelete',0)->find();
				$parentchild['images'] = json_decode($parentchild['images']);
				$parentchild['thumbs'] = json_decode($parentchild['thumbs']);
				
				$data['parentchild'] = $parentchild;
				$data['ParentchildComplete'] = $ParentchildComplete;
				$this ->json(0,'正确',$data);
				
				
			}

		}

	} 
	//家长state=0,1,2,教师state=2 已过期  调用相同的
	public function call($id,$phone_account,$account,$page,$num){
		 //任务
	        $parentchild = Db::name('parentchild')->field('id,title,release_time,content,images,thumbs')->where('id',$id)->where('status',1)->where('isdelete',0)->find();
	        $parentchild['images'] = json_decode($parentchild['images']);
		    $parentchild['thumbs'] = json_decode($parentchild['thumbs']);
			
			$ParentchildComplete = [];
			//所有学生完成任务
			$list = Db::name('ParentchildComplete')->field('id,content,release_time,images,thumbs,account,phone_account')->where('pid',$id)->where('status',1)->where('isdelete',0)->order('release_time','desc')->limit($page,$num)->select();
		   // var_dump($list );
			foreach($list as $key=>$value){
				$info = Db::name('StudentManagement')->where('account',$value['account'])->where('status',1)->where('isdelete',0)->find();
				if($info){
					$ParentchildComplete[] = array(
				    'id'=>$value['id'],
					'name'=>$info['name'],
					'headerurl'=>$info['student_headurl'],
					'content'=>$value['content'],
					'images'=>json_decode($value['images']),
					'thumbs'=>json_decode($value['thumbs']),
					'release_time'=>$value['release_time'],
					'iscomplete' =>1
				    );
				}
				

			}
			//是否完成
			$info = Db::name('ParentchildComplete')->field('id')->where('pid',$id)->where('phone_account',$phone_account)->where('account',$account)->where('status',1)->where('isdelete',0)->find();
			if($info){
				$data['iscomplete'] = 1;
			}else{
				$data['iscomplete'] = 0;
			}
	
			$data['parentchild'] = $parentchild;
			$data['ParentchildComplete'] = $ParentchildComplete;
			$this ->json(0,'正确',$data);
		
		
		
	}
	
	//完成任务
	public function completeParentchild(){
		$data['phone_account'] = $this->request->param('phone_account');
		$data['account'] = $this->request->param('account');
		$data['pid'] = $this->request->param('id');
		$data['content'] = $this->request->param('content');
		$data['release_time'] = date('Y-m-d');
		if(!empty($_FILES['file'])){
                $files = $_FILES['file'];
                if($files){
                    $data['images'] = $this->upload($files); 
                    $data['thumbs'] = $this->thumb(json_decode($data['images']));
                }
        }
		
		$re = Db::name('ParentchildComplete')->insert($data);

		if($re){
		    $this ->json(0,'成功');
		}else{
			$this ->json(1,'失败');
		}
		
		
		
	}
	
	//本班完成情况
	
	public function allComplete(){
		$id = $this->request->param('id');
	    $id = isset($id)?$id:'';
		$phone_account = $this->request->param('phone_account');
        $phone_account = isset($phone_account)?$phone_account:'';
		$account = $this->request->param('account');
        $account = isset($account)?$account:'';
		$type = $this->request->param('type');
        $type = isset($type)?$type:'';
        $page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
		$ParentchildComplete =[];
		
		//所有学生完成任务
		$list = Db::name('ParentchildComplete')->field('id,content,release_time,images,thumbs,account,phone_account')->where('pid',$id)->where('status',1)->where('isdelete',0)->order('release_time','desc')->limit($page,$num)->select();
		foreach($list as $key=>$value){
			$info = Db::name('StudentManagement')->where('account',$value['account'])->where('status',1)->where('isdelete',0)->find();
			if($info){
				$ParentchildComplete[] = array(
				'id'=>$value['id'],
				'name'=>$info['name'],
				'headerurl'=>$info['student_headurl'],
				'content'=>$value['content'],
				'images'=>json_decode($value['images']),
				'thumbs'=>json_decode($value['thumbs']),
				'release_time'=>$value['release_time'],
				'iscomplete' =>1
				);
			}
			

		}
		
		if($ParentchildComplete){
		    $this ->json(0,'成功',$ParentchildComplete);
		}else{
			$this ->json(1,'无数据');
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