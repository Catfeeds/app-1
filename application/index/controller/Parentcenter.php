<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;
use think\Session;
use think\Request;

class Parentcenter extends Controller
{
    //个人信息
    public function message() {
        $phone_account = $this->request->param('phone_account');
        $type = $this->request->param('type');
		$account = $this->request->param('account');
        if($phone_account){
            $info = DB::name('patriarch')->field('bgimage,grade,province,city,phone,headerurl,nickname')->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->find();//
            
            if($info){
                if(empty($type)){ //家长
					$info['account'] = Db::table('tp_student_linkman a, tp_student_management b')->field('b.account as account')->where('b.id=a.student_id and a.number='.$info['phone'])->select();
					$info['area'] = $info['province'].$info['city'];
				    $student = DB::name('StudentManagement')->where('account','=',$account)->where('status',1)->where('isdelete',0)->find();
					//$linkman = DB::name('StudentLinkman')->where('number','=',$info['phone'])->where('status',1)->where('isdelete',0)->find();
					$firstlinkman = DB::name('StudentLinkman')->where('student_id','=',$student['id'])->where('status',1)->where('isdelete',0)->order('id','asc')->limit(1)->find();
					//var_dump($linkman);
					//echo Db::getlastsql();exit;  

					if($firstlinkman['number'] == $info['phone'] ){
						$firstlinkman = 1;
					}else{
						$firstlinkman = 0;
					}
					$info['firstlinkman'] = $firstlinkman;//第一联系人
					//var_dump($info);
					
				}else{//教师
				    $info['account'] = Db::name('EmployeeManagement')->field('account')->where('iphone',$info['phone'])->where('status',1)->where('isdelete',0)->select();
					$info['area'] = $info['province'].$info['city'];
				 
					$info['firstlinkman'] = 0;//第一联系人
					
				}
                $allSchoolPost = DB::name('AllSchoolPost')->where('phone_account',$phone_account)->where('status',1)->where('isdelete',0)->count();//发全部学校帖子数
             
                $Like_count = DB::name('InformationArticleAnswerLike')->where('phone_account',$phone_account)->where('status',1)->where('isdelete',0)->where('islike',1)->count();//社区问答回答和文集详情喜欢
                
				$isread = Db::name('DirectMessage')->where('receive_phone_account',$phone_account)->where('status',1)->where('isdelete',0)->where('isread',1)->select();
                if($isread){
					$info['message_count'] = 1;//有消息
				}else{
					$info['message_count'] = 0;//无新消息
				}

   			    $info['post_count'] = $allSchoolPost;//发帖数
                $info['collect_count'] = $Like_count;//喜欢数
                $info['growth_count'] = 0;//成长值
           
                $this->json(0,'正确',$info);
            }else{
                $this->json(1,'用户不存在');
            }
            
        }
    }

     
    
    //上传个人头像
    public function headerurl() {
        $phone_account = $this->request->param('phone_account');
        //var_dump($phone_account);
        if(!empty($_FILES['file'])){
            $files = $_FILES['file'];      
            $headerurl = $this->oneupload($files); 
           
            $re = Db::name('patriarch')->where('phone_account',$phone_account)->where('status',1)->where('isdelete',0)->update(['headerurl'=>$headerurl]);
            //var_dump(Db::getlastsql());
            if($re){
                $this->json(0,'成功',$headerurl);
            }else{
                $this->json(1,'失败');
            }
      

        }
    }
      //宝宝资料
    public function babyinfo() {
        $phone_account = $this->request->param('phone_account');
		$account = $this->request->param('account');
        $info = Db::name('patriarch')->where('phone_account',$phone_account)->where('status',1)->where('isdelete',0)->find();
        $baby= Db::table('tp_student_linkman a, tp_student_management b')->field('b.name as name,b.className as className,b.sex as sex,b.height as height,b.weight as weight,b.student_headurl as headerurl,b.birthDate as birthDate')->where('b.id=a.student_id and b.account="'.$account.'" and a.number='.$info['phone'])->where('a.status',1)->where('a.isdelete',0)->where('b.status',1)->where('b.isdelete',0)->find();
        
        if($baby){
                $this->json(0,'正确',$baby);
            }else{
                $this->json(1,'失败');
            }
    }
    
     //获取班级
    public function babyclass() {
        $phone_account = $this->request->param('phone_account');
		$account = $this->request->param('account');
        $info = Db::name('patriarch')->where('phone_account',$phone_account)->where('status',1)->where('isdelete',0)->find();
        $info1 = Db::table('tp_student_linkman a, tp_student_management b')->field('b.schoolName as schoolName')->where('b.id=a.student_id and b.account="'.$account.'" and a.number='.$info['phone'])->where('a.status',1)->where('a.isdelete',0)->where('b.status',1)->where('b.isdelete',0)->find();
        
        if($info1){
			$class = Db::name('ClassManagement')->field('class,className')->where('schoolName',$info1['schoolName'])->where('status',1)->where('isdelete',0)->select();
			$className = array();
		   
			foreach ($class as $key => $value) {
				//var_dump($value);exit;
				$className[] = $value['class'].$value['className'];
			}
			if($info){
					$this->json(0,'正确',$className);
			}else{
					$this->json(1,'失败');
			}
        }
	}
    
     //修改个人信息
    public function modifymessage() {
        $phone_account = $this->request->param('phone_account');
        if($this->request->param('province')){
            $data['province'] = $this->request->param('province');
        }
        if($this->request->param('city')){
            $data['city'] = $this->request->param('city');
        }
        if($this->request->param('grade')){
            $data['grade'] = $this->request->param('grade');
        }

        if($this->request->param('nickname')){
            $data['nickname'] = $this->request->param('nickname');
            $info = Db::name('patriarch')->where('phone_account',$phone_account)->where('status',1)->where('isdelete',0)->find();
			$info1 = Db::name('patriarch')->where('nickname',$data['nickname'])->where('status',1)->where('isdelete',0)->find();
            if(time()-$info['update_time']<90*60*60*24){
                $this->json(2,'未超过三个月!');
            }if($info1){
				$this->json(3,'昵称已存在!');
			}else{
                
                $data['update_time'] = time();
            }
        }
        
        $re = Db::name('patriarch')->where('phone_account',$phone_account)->where('status',1)->where('isdelete',0)->update($data);
        if($re){
                $this->json(0,'修改成功');
        }else{
            $this->json(1,'修改失败');
        }
    }
    
	
     //宝宝头像
    public function babyheaderurl() {
        $phone_account = $this->request->param('phone_account');
		$account = $this->request->param('account');
        //var_dump($phone_account);
        if(!empty($_FILES['file'])){
            $files = $_FILES['file'];      
            $headerurl = $this->oneupload($files); 
            
            $info = DB::name('patriarch')->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->find();//或手机号登录
                
            $student =  Db::table('tp_student_linkman a, tp_student_management b')->field('b.id as id')->where('b.id=a.student_id and b.account="'.$account.'" and a.number='.$info['phone'])->where('a.status',1)->where('a.isdelete',0)->where('b.status',1)->where('b.isdelete',0)->find();

            $re = Db::name('StudentManagement')->where('id',$student['id'])->where('status',1)->where('isdelete',0)->update(['student_headurl'=>$headerurl]);
            //var_dump(Db::getlastsql());
            if($re){
                $this->json(0,'成功',$headerurl);
            }else{
                $this->json(1,'失败');
            }
      
        }
    }
	
	//修改宝宝资料
	public function modifybaby (){
		$phone_account = $this->request->param('phone_account');
		$account = $this->request->param('account');
		$className = $this->request->param('className');
		$sex = $this->request->param('sex');
		$birthDate = $this->request->param('birthDate');
		$height = $this->request->param('height');
		$weight = $this->request->param('weight');
		
		if($className){
			$data['className'] = $className;
		}
		if($sex){
			$data['sex'] = $sex;
		}
		if($birthDate){
			$data['birthDate'] = $birthDate;
		}
		if($height){
			$data['height'] = $height;
		}
		if($weight){
			$data['weight'] = $weight;
		}
		$re = Db::name('StudentManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->update($data);
		if($re){
			$data = Db::name('StudentManagement')->field('className,sex,birthDate,height,weight')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
            $this->json(0,'修改成功',$data);
        }else{
            $this->json(1,'修改失败');
        }
	}
	
	//更换背景图片
    public function bgimage (){
        $phone_account = $this->request->param('phone_account');
        $bgimage = $this->request->param('bgimage');
        $re = Db::name('patriarch')->where('status',1)->where('isdelete',0)->where('phone_account',$phone_account)->update(['bgimage'=>$bgimage]);
        if($re){
            $this->json(0,'更换成功');
        }else{
            $this->json(1,'更换失败');
        }
    }
	
	//查看帖子  
	
	public function post(){
		$phone_account = $this->request->param('phone_account');
        $phone_account = isset($phone_account)?$phone_account:'';
        $page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
		
		//DB::name('AllSchoolPost')->where('phone_account',$phone_account)->where('status',1)->where('isdelete',0)->order('release_time','desc')->limit($page,$num)->select();//发全部学校帖子
		if($phone_account){
			$data = Db::table('tp_all_school_post a, tp_patriarch b')->field('a.id as id,a.title as title,a.model as model,a.image as image,a.release_time as release_time')
			->where('a.phone_account=b.phone_account and a.phone_account ="'.$phone_account.'"')->order('release_time','desc')->limit($page,$num)->select();
		//	echo Db::getlastsql();exit;
			$this->json(0,'成功',$data);
		}else{
			$this->json(1,'无参数');
		}

	}
	
	//查看喜欢   
	
	public function like(){
		$phone_account = $this->request->param('phone_account');
        $phone_account = isset($phone_account)?$phone_account:'';
        $page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
		$data = [];
		//DB::name('AllSchoolPost')->where('phone_account',$phone_account)->where('status',1)->where('isdelete',0)->order('release_time','desc')->limit($page,$num)->select();//发全部学校帖子
		if($phone_account){
			$list = Db::name('InformationArticleAnswerLike')->where('status',1)->where('isdelete',0)->where('phone_account',$phone_account)->where('islike',1)->order('release_time','desc')->limit($page,$num)->select();
		 //  var_dump($list);
		   foreach($list as $key=>$value){
				if($value['model'] == 1){//文集中的喜欢
					$info = Db::name('CorpusArticle')->where('status',1)->where('isdelete',0)->where('id',$value['answer_id'])->find();
				    $id = $info['id'];
					$image = $info['image'];
				}else{//社区问答回答喜欢
					$info1 = Db::name('InformationArticleAnswer')->where('status',1)->where('isdelete',0)->where('id',$value['answer_id'])->find();
					$info = Db::name('InformationArticle')->where('status',1)->where('isdelete',0)->where('id',$info1['infoarticle_id'])->find();
					$id = $info1['id'];
					if($info1['thumbs']){
						$arr = json_decode($info1['thumbs']);
						$image =$arr[0];
					}else{
						$image ='';
					}
				}
				$data[] = array(
				'id' =>$id,
				'title'=>$info['title'],
				'image'=>$image,
				'model'=>$value['model'],
				'release_time'=>$value['release_time'],
				);
				
			}
			$this->json(0,'成功',$data);
		}else{
			$this->json(1,'无参数');
		}

	}
	
	//删除帖子
	public function deletePost(){
		$id = $this->request->param('id');
		$model = $this->request->param('model');
		$re = Db::name('AllSchoolPost')->where('status',1)->where('isdelete',0)->where('model',$model)->where('id',$id)->delete();
		if($re){
			$this->json(0,'删除成功');
			//$re = Db::name('ArtTutoringPost')->where('status',1)->where('isdelete',0)->where('id',$id)->delete();
		}else{
			$this->json(1,'删除失败');
		}
		
	}
	
	//查看消息列表
	public function directMessage (){
		$phone_account = $this->request->param('phone_account');
        $phone_account = isset($phone_account)?$phone_account:'';
        $page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
		
		if($page==0&&$num ==0){
			$limit = "";
		}else{
			$limit = "limit ".$page.",".$num;
		}
		
		$data = [];
		if($phone_account ){
			$list = Db::query("select a.id,a.phone_account,a.bephone_account,c.release_time,c.content,c.direct_id from tp_direct as a left join 
			        (select t1.release_time,t1.direct_id,t1.content,t1.id from tp_direct_message t1,
                    (select direct_id,max(id) as mid from tp_direct_message group by direct_id) t2 where t1.direct_id=t2.direct_id and t1.id=t2.mid )
					c on  a.id = c.direct_id  where a.phone_account = '".$phone_account."' or a.bephone_account = '".$phone_account."' order by c.release_time desc ".$limit);

            // $list = Db::query("select a.id,a.phone_account,a.bephone_account,c.mid from tp_direct as a left join (select direct_id ,release_time ,max(id) as mid from
			// tp_direct_message group by direct_id ) c on  a.id = c.direct_id  where a.phone_account = '".$phone_account."' or a.bephone_account = '".$phone_account."' order by c.release_time desc ".$limit);
          // //echo DB::getlastsql();
		  foreach($list as $key=>$value){
				$re = Db::name('DirectMessage')->where('status',1)->where('isdelete',0)->where('direct_id',$value['direct_id'])->where('receive_phone_account',$phone_account)->where('isread',1)->find();
		
				if($phone_account==$value['bephone_account']){
					$phone_account1 = $value['phone_account'];
				}else{
					$phone_account1 = $value['bephone_account'];
				}
			
				$info1 = Db::name('patriarch')->where('status',1)->where('isdelete',0)->where('phone_account',$phone_account1)->find();
				
				if($re){
					$isread = 1;
				}else{
					$isread = 0;
				}
				//var_dump($re);
				if($value['bephone_account']==$phone_account){
					$bephone_account = $value['phone_account'];
				}else{
					$bephone_account = $value['bephone_account'];
				}
				$data[]= array(
				    'id'=>$value['id'],
					'content'=>$value['content'],
					'release_time'=>$value['release_time'],
					'nickname'=>$info1['nickname'],
					'headerurl'=>$info1['headerurl'],
					'bephone_account'=>$bephone_account,
					'isread'=>$isread,
				
				);

			}
		//	exit;
			$this->json(0,'正确',$data);
		    
		}else{
			$this->json(1,'无参数');
		}

	}
	
	//查看聊天页面
	
	public function chat(){
		$phone_account = $this->request->param('phone_account');
        $phone_account = isset($phone_account)?$phone_account:'';
		$bephone_account = $this->request->param('bephone_account');
        $bephone_account = isset($bephone_account)?$bephone_account:'';
		$id = $this->request->param('id');//聊天人id
        $id = isset($id)?$id:0;
        $page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
		
		if($id){
			// $list = DB::name('DirectMessage')->where('direct_id',$id)->where('status',1)->where('isdelete',0)->select();
			// foreach($list as $key=>$value){
				// $re = DB::name('DirectMessage')->where('id',$value['id'])->where('status',1)->where('isdelete',0)->update(['isread'=>0]);
			// }
		$re = DB::name('DirectMessage')->where('direct_id',$id)->where('receive_phone_account',$phone_account)->where('status',1)->where('isdelete',0)->update(['isread'=>0]);
		
	
			$data = Db::table('tp_direct_message a, tp_patriarch b')
			->field('a.content as content,a.release_time as release_time,a.send_phone_account as send_phone_account,a.receive_phone_account as receive_phone_account,b.headerurl as headerurl')
			->where('a.send_phone_account=b.phone_account and a.direct_id='.$id)->order('release_time','desc')->limit($page,$num)->select();
            $this->json(0,'正确',$data);
			//$listDB::name('DirectMessage')->where('direct_id',$id)->where('status',1)->where('isdelete',0)->order('release_time','desc')->limit($page,$num)->select();
		}else{
			$this->json(1,'无参数');
		}

	}
	
	//发送消息
	public function sendMessage(){
		$phone_account = $this->request->param('phone_account');//发送方
		$bephone_account = $this->request->param('bephone_account');//接收方
		
		$data['content'] = $this->request->param('content');//聊天内容
		$data['release_time'] = date('Y-m-d H:i:s');
		if($this->request->param('id')){
			$data['direct_id'] = $this->request->param('id');//聊天人id
		}else{
			$data1['phone_account'] = $phone_account;
			$data1['bephone_account'] = $bephone_account;
			$id1 = DB::name('direct')->insertgetid($data1);
			$data['direct_id'] = $id1;
		}
	//	var_dump($phone_account);var_dump($bephone_account);
		$data['send_phone_account'] = $phone_account;
		$data['receive_phone_account'] = $bephone_account;
		$re = DB::name('DirectMessage')->insert($data);
		$id = $data['direct_id'];
		if($re){
            $this->json(0,'发送成功',$id);
        }else{
            $this->json(1,'发送失败');
        }

	}

    //我的文集
    public function corpus() {
          
        if($this->request->isPost()){
            $id = $this->request->param('id');
            if($id){//编辑
                $data['phone_account'] = $this->request->param('phone_account');
                $data['corpus_name'] = $this->request->param('corpus_name');
                $data['release_time'] = date('Y-m-d H:i:s');
                $re = DB::name('corpus')->where('id',$id)->where('status',1)->where('isdelete',0)->update($data);
                if($re){
                    $this->json(0,'编辑成功');
                }else{
                    $this->json(1,'编辑失败');
                }
            }else{//添加
                $data['phone_account'] = $this->request->param('phone_account');
                $data['corpus_name'] = $this->request->param('corpus_name');
                $data['release_time'] = date('Y-m-d H:i:s');
                $re = DB::name('corpus')->insert($data);
                if($re){
                    $this->json(0,'添加成功');
                }else{
                    $this->json(1,'添加失败');
                }
            }
            
        }else{
            $phone_account = $this->request->param('phone_account');
            $corpus = [];
            $corpus1 = DB::name('corpus')->field('corpus_name,id')->where('phone_account','=',$phone_account)->whereOr('phone_account',0)->where('status',1)->where('isdelete',0)->order('id','asc')->select();
            
			foreach($corpus1 as $key=>$value){
                $count = DB::name('CorpusArticle')->where('corpus_id','=',$value['id'])->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->count();
                if($value['id'] == 1){
					$corpus[] = array(
						'corpus_name' =>$value['corpus_name'],
						'id' =>$value['id'],
						'count' =>$count,
						'isedit' =>0,
                    );
				}else{
					$corpus[] = array(
						'corpus_name' =>$value['corpus_name'],
						'id' =>$value['id'],
						'count' =>$count,
						'isedit' =>1,
                    );
					
				}
				
            }
            if($corpus){
                    $this->json(0,'成功',$corpus);
            }else{
                    $this->json(1,'没有文集');
            }
        }
        
    }
	
		//删除我的文集中的文章

	public function deleteCorpusArticle(){
		$id = $this->request->param('id');
		$phone_account = $this->request->param('phone_account');
		//$list = DB::name('CorpusArticle')->where('id','=',$id)->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->select();
		$re = DB::name('CorpusArticle')->where('id','=',$id)->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->update(['isdelete'=>1]);
//echo Db::getlastsql();exit;
		if($re){
            $this->json(0,'删除成功');
		}else{
			$this->json(1,'删除失败');
			
		}

	}
	
	//删除我的文集

	public function deleteCorpus (){
		$id = $this->request->param('id');
		$phone_account = $this->request->param('phone_account');
		$list = DB::name('CorpusArticle')->where('corpus_id','=',$id)->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->select();
		$re = DB::name('corpus')->where('id','=',$id)->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->update(['isdelete'=>1]);
//echo Db::getlastsql();exit;
		if($re){
			if($list){
			    $re1 = DB::name('CorpusArticle')->where('corpus_id','=',$id)->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->update(['isdelete'=>1]);
			}
			
            $this->json(0,'删除成功');
		}else{
			$this->json(1,'删除失败');
			
		}
	}

    //发布文章
    public function publishCorpusArticle() {
        //if($this->request->isPost()){
            $id = $this->request->param('id');
            $isrelease = $this->request->param('isrelease');//是否发布
            $phone_account = $this->request->param('phone_account');//
            if($id){//编辑
                $phone_account = $this->request->param('phone_account');
                $info = DB::name('patriarch')->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->find();
                $data['city'] = $info['city'];
				$data['corpus_id'] = $this->request->param('corpus_id');
                $data['image'] = $this->request->param('image'); //第一张图片路径
                $data['title'] = $this->request->param('title');
                $data['content'] = $this->request->param('content');
               
                $data['isrelease'] = $isrelease;
                if($isrelease == 1){
                    $data['release_time'] = date('Y-m-d H:i:s');
                }else{
                    $data['update_time'] = date('Y-m-d H:i:s');
                }
				$data['isqualified'] = 1;
                $re = DB::name('CorpusArticle')->where('id',$id)->where('status',1)->where('isdelete',0)->update($data);
                if($re){
                    $this->json(0,'编辑成功');
                }else{
                    $this->json(1,'编辑失败');
                }
            }else{//添加
                $data['phone_account'] = $this->request->param('phone_account');
                $info = DB::name('patriarch')->where('phone_account','=',$data['phone_account'])->where('status',1)->where('isdelete',0)->find();
                $data['city'] = $info['city'];
                $data['corpus_id'] = $this->request->param('corpus_id'); 
                $data['image'] = $this->request->param('image'); //第一张图片路径
                $data['title'] = $this->request->param('title');
                $data['content'] = $this->request->param('content');
                if($isrelease == 1){
                    $data['release_time'] = date('Y-m-d H:i:s');

                }else{
					$data['update_time'] = date('Y-m-d H:i:s');
				}
                $data['isrelease'] = $isrelease;
                $re = DB::name('CorpusArticle')->insert($data);
                if($re){
                    $this->json(0,'添加成功');
                }else{
                    $this->json(1,'添加失败');
                }
            }
    }
	
	
    //我的文章

    public function corpusArticle () {
        $phone_account = $this->request->param('phone_account');
        $phone_account = isset($phone_account)?$phone_account:'';
        $isrelease = $this->request->param('isrelease');
        $isrelease = isset($isrelease)?$isrelease:'';
        $id = $this->request->param('id');
        $id = isset($id)?$id:'';
        $page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
		
		if($page==0&&$num ==0){
			$limit = "";
		}else{
			$limit = "limit ".$page.",".$num;
		}

        $CorpusArticle = [];
        if($isrelease ==0){
           // $CorpusArticleDate = DB::name('CorpusArticle')->where('phone_account',$phone_account)->where('corpus_id',$id)->where('status',1)->where('isdelete',0)->whereOr('isqualified',2)->where('isrelease',$isrelease)->order('update_time','desc')->limit($page,$num)->select();
			$CorpusArticleDate = Db::query("SELECT * FROM `tp_corpus_article` WHERE `corpus_id` = ".$id." AND `phone_account` = '".$phone_account."' AND (`isrelease` = 0 OR`isqualified` = 2)  AND `status` = 1 AND `isdelete` = 0 ORDER BY `update_time` desc ".$limit);
			//echo Db::getlastsql();exit;
			foreach($CorpusArticleDate as $key=>$value){
				$info1 = DB::name('patriarch')->where('phone_account','=',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();//
			    $corpus1 = DB::name('corpus')->where('id','=',$value['corpus_id'])->where('status',1)->where('isdelete',0)->find();
				$CorpusArticle[] = array(
			    'nickname' =>$info1['nickname'],
				'headerurl' =>$info1['headerurl'],
				'title' =>str_replace('&nbsp;','',strip_tags(htmlspecialchars_decode($value['title']))),
                'content' =>html_entity_decode(htmlspecialchars_decode($value['content'])),
				'corpus_name' =>$corpus1['corpus_name'],
				'id' =>$value['id'],
				'image' =>$value['image'],
				'phone_account' =>$value['phone_account'],
				'click' =>0,
				'release_time' =>$value['update_time'],
				'collectCount' =>0,
				'commentCount' =>0,
				'isrelease' =>$value['isrelease'],
				'isqualified' =>$value['isqualified'],
				);
			}
			
        }else{
            $CorpusArticleDate = DB::name('CorpusArticle')->where('phone_account',$phone_account)->where('corpus_id',$id)->where('status',1)->where('isdelete',0)->where('isrelease',$isrelease)->where('isqualified','<>',2)->order('release_time','desc')->limit($page,$num)->select();
			//$CorpusArticleDate = Db::query("SELECT * FROM `tp_corpus_article` WHERE `corpus_id` = ".$id." AND `phone_account` = '".$phone_account."' AND (`isrelease` = 1 OR`isqualified` <> 2)  AND `status` = 1 AND `isdelete` = 0 ORDER BY `id` desc ".$limit);
			//echo Db::getlastsql();exit;
            foreach($CorpusArticleDate as $key=>$value){
                        $info1 = DB::name('patriarch')->where('phone_account','=',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();//
                        $collectCount = Db::name('CorpusArticleCollect')->where('status',1)->where('isdelete',0)->where('corpusarticle_id',$value['id'])->where('iscollect',1)->count();
                        $commentCount = Db::name('CorpusArticleComment')->where('status',1)->where('isdelete',0)->where('corpusarticle_id',$value['id'])->count();
                        $corpus1 = DB::name('corpus')->where('id','=',$value['corpus_id'])->where('status',1)->where('isdelete',0)->find();
                        $CorpusArticle[] = array(
                            'nickname' =>$info1['nickname'],
                            'headerurl' =>$info1['headerurl'],
                            'title' =>str_replace('&nbsp;','',strip_tags(htmlspecialchars_decode($value['title']))),
                            'content' =>str_replace('&nbsp;','',strip_tags(htmlspecialchars_decode($value['content']))),
                            'corpus_name' =>$corpus1['corpus_name'],
                            'id' =>$value['id'],
                            'image' =>$value['image'],
                            'phone_account' =>$value['phone_account'],
                            'click' =>$value['click'],
                            'release_time' =>$value['release_time'],
                            'collectCount' =>$collectCount,
                            'commentCount' =>$commentCount,
							'isrelease' =>$value['isrelease'],
				            'isqualified' =>$value['isqualified'],

                        );
            }
        }
//var_dump( $CorpusArticleDate);
        return $this->json(0,'正确',$CorpusArticle);
    }


    public function  uploadimg (){

        if(!empty($_FILES['file'])){
            $files = $_FILES['file'];
            if($files){
                $data['images'] = $this->upload($files); 
                $data['thumbs'] = $this->thumb($data['images']);

                return $this->json(0,'正确',$data);
            }
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
 //$this->json(1,'失败',$data);
            return $data;
            
    }

    // 上传图片
    public function upload($files){

        $data = array();

        $path = 'uploads/file/'.date('Ymd').'/';//获取当前时间

        if(isset( $files )) {
            //var_dump($files['name']);exit;
            foreach ($files['name'] as $key=> $value){


                $upfile = $path. $value;
                if (! @file_exists ( $path )) {
                    @mkdir ( $path );
                }
                $result = @move_uploaded_file ( $files['tmp_name'][$key], $upfile );
                if (!$result){

                    echo $this->json(2,'上传失败');exit;
                }

                $data[] = "/".$upfile;
            }
        }

//        return json_encode($data);
        return $data;

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
            $newwidth = $width * $percent;
            $newheight = $height * $percent;
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

//        return json_encode($thumbinfo);
        return $thumbinfo;
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
