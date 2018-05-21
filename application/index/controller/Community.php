<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;
use think\Session;
use think\Request;

class Community extends Controller
{
    //社区问答列表
    public function information() {
        $phone_account = $this->request->param('phone_account');
        $phone_account = isset($phone_account)?$phone_account:'';
        $id = $this->request->param('id');//模块id
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

        if($phone_account){
            $info = DB::name('patriarch')->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->find();//
            if($info){
                $information = DB::name('information')->field('id,info_name')->where('status',1)->where('isdelete',0)->select();
                if($id){
                   // $list = DB::name('InformationArticle')->where('info_id',$id)->where('city',$info['city'])->where('isqualified',0)->whereOr('isqualified',1)->where('status',1)->where('isdelete',0)->order('id','desc')->limit($page,$num)->select();
					$list = Db::query("SELECT * FROM `tp_information_article` WHERE `info_id` = ".$id." AND `city` = '".$info['city']."' AND (`isqualified` = 0 OR`isqualified` = 1)  AND `status` = 1 AND `isdelete` = 0 ORDER BY `id` desc ".$limit);
                }else{
                   // $list = DB::name('InformationArticle')->where('city',$info['city'])->where('status',1)->where('isqualified',0)->whereOr('isqualified',1)->where('isdelete',0)->order('id','desc')->limit($page,$num)->select();
					$list = Db::query("SELECT * FROM `tp_information_article` WHERE `city` = '".$info['city']."' AND (`isqualified` = 0 OR`isqualified` = 1)  AND `status` = 1 AND `isdelete` = 0 ORDER BY `id` desc ".$limit);
                }
			
					
				$infomationArticle = array();
				foreach($list as $key=>$value){
					$image = '';
			  
					if($value['thumbs']){
						$arr = json_decode($value['thumbs']);
						$image = $arr[0];
					}else{
						$list1 = DB::name('InformationArticleAnswer')->where('infoarticle_id','=',$value['id'])->where('images','neq','')->where('status',1)->where('isdelete',0)->order('answer_time','desc')->find();
						//echo DB::getlastsql();
						//var_dump($list1);
						if($list1['thumbs']){
							$arr = json_decode($list1['thumbs']);
						    $image = $arr[0];
					//	var_dump($arr);	exit;
						}
					   
					}
					
					$information1 = DB::name('information')->where('id','=',$value['info_id'])->where('status',1)->where('isdelete',0)->find();
					$collectCount = DB::name('InformationArticleCollect')->where('infoarticle_id','=',$value['id'])->where('status',1)->where('isdelete',0)->where('iscollect',1)->count();//收藏个数
					$answerCount = DB::name('InformationArticleAnswer')->where('infoarticle_id','=',$value['id'])->where('status',1)->where('isdelete',0)->count();//回答个数
					//echo DB::getlastsql();
					$infomationArticle[] = array(
						'image' =>$image,
						'title' =>html_entity_decode(htmlspecialchars_decode($value['title'])),
						'info_name' =>$information1['info_name'],
						'id' =>$value['id'],
						'answerCount' =>$answerCount,
						'collectCount' =>$collectCount,
					   // 'bephone_account' =>$value['phone_account'],
					);
				}
             // exit;
                $info1['headerurl'] = $info['headerurl'];
				$info1['nickname'] = $info['nickname'];
		        $data['info'] = $info1;
                $data['information'] = $information;
                $data['infomationArticle'] = $infomationArticle;
				
				$this->json(0,'正确',$data);
				
            }else{
                $this->json(1,'用户不存在');
            }
            
        }
    }
	
	//回答列表  
	
	public function informationReply(){
		$phone_account = $this->request->param('phone_account');
        $phone_account = isset($phone_account)?$phone_account:'';
        $id = $this->request->param('id');//问题id
        $id = isset($id)?$id:'';
        $page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
		
		$InformationArticleAnswer = [];
		
		if($id){
          
			$iscollect = DB::name('InformationArticleCollect')->where('infoarticle_id','=',$id)->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->where('iscollect',1)->find();  //是否收藏
			if($iscollect){
				$iscollect = 1;//收藏
			}else{
				$iscollect = 0;//未收藏
			}
			$info = DB::name('InformationArticle')->field('id,title,content,thumbs,images,release_time,info_id')->where('id',$id)->where('status',1)->where('isdelete',0)->find();//查找问题
			$collectCount = DB::name('InformationArticleCollect')->where('infoarticle_id','=',$id)->where('status',1)->where('isdelete',0)->where('iscollect',1)->count();//收藏个数
			$answerCount = DB::name('InformationArticleAnswer')->where('infoarticle_id','=',$id)->where('status',1)->where('isdelete',0)->count();//回答数
			
			$info['images'] = json_decode($info['images']);
			$info['thumbs'] = json_decode($info['thumbs']);
			$info['answerCount'] = $answerCount;
			$info['collectCount'] = $collectCount;
			$info['iscollect'] = $iscollect;
			
			$information =DB::name('Information')->field('id,info_name')->where('id',$info['info_id'])->where('status',1)->where('isdelete',0)->find();//查找问题所在标签 
			
			$list = DB::name('InformationArticleAnswer')->where('infoarticle_id',$id)->where('status',1)->where('isdelete',0)->order('answer_time','desc')->limit($page,$num)->select();
		
			foreach($list as $key=>$value){
				$info1 = DB::name('patriarch')->where('phone_account','=',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();
				$commentCount = DB::name('InformationArticleAnswerComment')->where('answer_id','=',$value['id'])->where('status',1)->where('isdelete',0)->count();//评论个数
				$likeCount = DB::name('InformationArticleAnswerLike')->where('answer_id','=',$value['id'])->where('status',1)->where('isdelete',0)->where('islike',1)->count();//喜欢个数
				$InformationArticleAnswer[] = array(
					'id'=>$value['id'],
					'headerurl'=>$info1['headerurl'],
					'nickname'=>$info1['nickname'],
					'images'=>json_decode($value['images']),
					'thumbs'=>json_decode($value['thumbs']),
					'click'=>$value['click'],
					'content'=>str_replace(array('&nbsp;','来自朗学网APP的图片'),'',strip_tags(htmlspecialchars_decode($value['content']))),
					'likeCount'=>$likeCount,
					'commentCount'=>$commentCount,
					'bephone_account'=>$value['phone_account'],
				);

			}
			$data['info'] = $info;
			$data['information'] = $information;
			$data['InformationArticleAnswer'] = $InformationArticleAnswer;
			
			
			$this->json(0,'正确',$data);
			
		}else{
			
			$this->json(1,'无参数');
		}
		
	}
	
	//收藏问题
    public function informationArticleCollect(){
      
        $data['infoarticle_id'] = $this->request->param('id');//问题id     
        $data['phone_account'] = $this->request->param('phone_account');//账号
        $data['collect_time'] = date('Y-m-d H:i:s');
        $model = Db::name('InformationArticleCollect');
        $info = $model->where('infoarticle_id',$data['infoarticle_id'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->find();
         
        
        if(empty($info)){
          $data['iscollect'] = 1;
          $re = $model->insert($data);
          if($re){
            $this->json(0,'收藏成功');
          }else{
            $this->json(3,'收藏失败');
          }
          
        }elseif($info['iscollect'] == 1){
          $re = $model->where('infoarticle_id',$data['infoarticle_id'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['iscollect'=>0,'collect_time'=>date('Y-m-d H:i:s')]);
                    if($re){
            $this->json(1,'取消收藏成功');
          }else{
            $this->json(2,'取消收藏失败');
          }
           

        }elseif($info['iscollect'] == 0){

         $re = $model->where('infoarticle_id',$data['infoarticle_id'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['iscollect'=>1,'collect_time'=>date('Y-m-d H:i:s')]);
          if($re){
            $this->json(0,'收藏成功');
          }else{
            $this->json(3,'收藏失败');
          }
        }

    }
	
	//回答问题
	
	public function informationAnswer(){
		if($this->request->isPost()){
			$data['infoarticle_id'] = $this->request->param('id');//问题id
			$data['phone_account'] = $this->request->param('phone_account');//账号
			$data['answer_time'] = date('Y-m-d H:i:s');
			$data['content'] = $this->request->param('content');//问题id
			$images = $this->request->param('images');//图片
			if($images){
				$data['images'] = json_encode(explode(',',$images));
			
 			    $data['thumbs'] = json_encode(explode(',',$images));//缩略图
			}
			
		//	var_dump($images);
			$model = Db::name('InformationArticleAnswer');
			$re = $model->insert($data);
			if($re){
				$this->json(0,'回答成功');
			}else{
				$this->json(1,'回答失败');
			} 

		}else{
			$this->json(2,'非法请求');
		}

	}
	
	//我的问答
	public function informationQuestionAnswer (){
		$phone_account = $this->request->param('phone_account');//手机号生成账号
        $phone_account = isset($phone_account)?$phone_account:'';
		$model = $this->request->param('model');//0 收藏  1 提问   2 回答
		$model = isset($model)?$model:'';
        $page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
		$data = [];
		$arr = [];
		if($phone_account){
			if($model==0){
				$list = Db::name('InformationArticleCollect')->where('phone_account',$phone_account)->where('status',1)->where('iscollect',1)->where('isdelete',0)->order('collect_time','desc')->limit($page,$num)->select();//当前用户收藏
				foreach($list as $key=>$value){
					$info = Db::name('InformationArticle')->where('id',$value['infoarticle_id'])->where('status',1)->where('isdelete',0)->find();//
					if($info['thumbs']){
						$arr = json_decode($info['thumbs']);
						//var_dump(json_decode($info['images']));
					}else{
						$arr[0] = '';
					}
					$answerCount = Db::name('InformationArticleAnswer')->where('infoarticle_id',$info['id'])->where('status',1)->where('isdelete',0)->count();//回答数
					$data[] = array(
						'id' =>$info['id'],
						'title' =>$info['title'],
						'answerCount' =>$answerCount,
						'image' =>$arr[0],
						'likeCount' =>0,
						'click' =>0,
						
					);
		
				}
			}elseif($model==1){
				$list = Db::name('InformationArticle')->where('phone_account',$phone_account)->where('status',1)->where('isdelete',0)->order('release_time','desc')->limit($page,$num)->select();//当前用户提问
				foreach($list as $key=>$value){
					
					if($value['thumbs']){
						$arr = json_decode($value['thumbs']);
					}else{
						$arr[0] = '';
					}
					$answerCount = Db::name('InformationArticleAnswer')->where('infoarticle_id',$value['id'])->where('status',1)->where('isdelete',0)->count();//回答数
					$data[] = array(
						'id' =>$value['id'],
						'title' =>$value['title'],
						'answerCount' =>$answerCount,
						'image' =>$arr[0],
						'likeCount' =>0,
						'click' =>0,
						
					);
		
				}
				
				
			}elseif($model==2){
				$list = Db::name('InformationArticleAnswer')->where('phone_account',$phone_account)->where('status',1)->where('isdelete',0)->order('answer_time','desc')->limit($page,$num)->select();//当前用户回答
				foreach($list as $key=>$value){
				
					$info = Db::name('InformationArticle')->where('id',$value['infoarticle_id'])->where('status',1)->where('isdelete',0)->find();//回答的所属标题
					if($info['thumbs']){
						$arr = json_decode($info['thumbs']);
					}else{
						$arr[0] = '';
					}
					$likeCount = Db::name('InformationArticleAnswerLike')->where('answer_id',$value['id'])->where('status',1)->where('isdelete',0)->where('islike',1)->count();//喜欢数
					$data[] = array(
						'id' =>$value['id'],
						'title' =>$info['title'],
						'answerCount' =>0,
						'image' =>$arr[0],
						'likeCount'=>$likeCount,
						'click' =>$value['click'],
						
					);
		
				}

			}
			$this->json(0,'有数据',$data);
		}else{
			$this->json(1,'无用户账号');
		}
		
		
		

	}
	
	

	
	//更多社区问答标签
    public function moreInformation() {

        $info = DB::name('Information')->field('id,info_name')->where('status',1)->where('isdelete',0)->select();
        if($info){
            $this->json(0,'有数据',$info);
        }else{
            $this->json(2,'没有数据');
        }                  

    }
	
	
    //删除社区问答
    public function infoDelete() {
        $phone_account = $this->request->param('phone_account');
        $id = $this->request->param('id');
        if($phone_account){
           
            $info = DB::name('InformationArticle')->where('id',$id)->where('status',1)->where('isdelete',0)->find();
            if($info['phone_account'] == $phone_account ){
                $re = DB::name('InformationArticle')->where('id',$id)->where('status',1)->where('isdelete',0)->delete();
                if($re){
                    $this->json(0,'删除成功');
                }else{
                    $this->json(2,'删除失败');
                }                  
            }else{
                $this->json(1,'不能删除他人的');
            }
            
        }
    }
	

	
	
    //  社区问答回答详情
    public function informationArticle() {
        $phone_account = $this->request->param('phone_account');
        $id = $this->request->param('id');
        if($id){
            $zan_count = DB::name('InformationArticleAnswerLike')->where('answer_id',$id)->where('islike',1)->where('status',1)->where('isdelete',0)->count();//赞数
            $comment_count = DB::name('InformationArticleAnswerComment')->where('answer_id',$id)->where('status',1)->where('isdelete',0)->count();//评论数
            $islike = DB::name('InformationArticleAnswerLike')->where('answer_id',$id)->where('phone_account',$phone_account)->where('islike',1)->where('status',1)->where('isdelete',0)->find();//是否点赞
            if($islike){
                $islike = 1;//喜欢
            }else{
                $islike = 0;//不喜欢
            }
            $data['id'] = $id;
            $data['iszan'] = $islike;
            $data['comment_count'] = $comment_count;
            $data['zan_count'] = $zan_count;
			

            $this->json(0,'正确',$data);
			
        }else{
                $this->json(1,'参数错误');
        }
    }

	   //社区问答回答喜欢
    public function informationArticleZan(){
      
        $data['answer_id'] = $this->request->param('id');//回答id
        $data['phone_account'] = $this->request->param('phone_account');//账号
        $data['release_time'] = date('Y-m-d H:i:s');
        $model = Db::name('InformationArticleAnswerLike');
        $info = $model->where('answer_id',$data['answer_id'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->find();
         
        
        if(empty($info)){
			$data['islike'] = 1;
			$data['model'] = 0;
			$re = $model->insert($data);
			if($re){
				$this->json(0,'喜欢成功');
			}else{
				$this->json(3,'喜欢失败');
			}
          
        }elseif($info['islike'] == 1){
            $re = $model->where('answer_id',$data['answer_id'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['islike'=>0,'release_time'=>date('Y-m-d H:i:s')]);
            if($re){
                $this->json(1,'取消喜欢成功');
            }else{
                $this->json(2,'取消喜欢失败');
        }
           

        }elseif($info['islike'] == 0){

            $re = $model->where('answer_id',$data['answer_id'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['islike'=>1,'release_time'=>date('Y-m-d H:i:s')]);
            if($re){
                $this->json(0,'喜欢成功');
            }else{
                $this->json(3,'喜欢失败');
            }
        }

    }
	//社区问答回答评论
	public function informationArticleComment(){
        if($this->request->isPost()){
            $data['answer_id'] = $this->request->param('id');//回答id    
            $data['phone_account'] = $this->request->param('phone_account');//账号
            $data['content'] = $this->request->param('content');//内容
            $data['comment_time'] = date('Y-m-d H:i:s');
            $model = Db::name('InformationArticleAnswerComment');
            $re = $model->insert($data);
            if($re){
                $this->json(0,'评论成功');
            }else{
                $this->json(1,'评论失败');
            } 
        }else{
                $this->json(2,'非法请求');
        }
                
    }


 
	
	//  发布社区问答
    public function publishInformation() {
     
	    if($this->request->isPost()){
			$data['phone_account'] = $this->request->param('phone_account');
			$info = DB::name('patriarch')->where('phone_account','=',$data['phone_account'])->where('status',1)->where('isdelete',0)->find();
			$data['city'] = $info['city'];
			//var_dump($data['city']);
			$data['info_id'] = $this->request->param('id');
			$data['title'] = $this->request->param('title');
			if(!empty($_FILES['file'])){
				$files = $_FILES['file'];
				if($files){
					$data['images'] = json_encode($this->upload($files)); 
					$data['thumbs'] = json_encode($this->thumb(json_decode($data['images'])));
				}
            }
			$data['content'] = $this->request->param('content');
			$data['release_time'] = date('Y-m-d H:i:s');
			$re = DB::name('InformationArticle')->insert($data);
			if($re){
				$this->json(0,'发布成功');
			}else{
				$this->json(1,'发布失败');
			}
		}else{
			    $this->json(2,'非法请求');
		}
        
    }
	
	
	//模糊查询社区问答标题
	
	public function likeTitle (){
		$title = $this->request->param('title');
		$id = $this->request->param('id');
		if($title){
			$list = DB::name('InformationArticle')->field('title,id')->where('title','like',"%{$title}%")->where('info_id',$id)->where('status',1)->where('isdelete',0)->order('id','desc')->select();
			//var_dump($list);exit;
			$data = [];
			foreach($list as $key=>$value){
				$count = DB::name('InformationArticleAnswer')->where('infoarticle_id',$value['id'])->where('status',1)->where('isdelete',0)->count();
				
				$data[] = array(
					'title' =>$value['title'],
					'count' =>$count,
					'id' =>$value['id'],
				);
			}
			
			if($data){
					$this->json(0,'成功',$data);
			}else{
					$this->json(1,'无数据');
			}

		}else{
			    $this->json(2,'标题为空');
		}
		

		
	}


    //文集作品
    public function corpusWorks() {
        $phone_account = $this->request->param('phone_account');
        $phone_account = isset($phone_account)?$phone_account:'';
        $id = $this->request->param('id');
        $id = isset($id)?$id:'';
        $page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
		//echo $page;
        if($page==0&&$num ==0){
			$limit = "";
		}else{
			$limit = "limit ".$page.",".$num;
		}
        if($phone_account){
            $info = DB::name('patriarch')->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->find();//
            if($info){
                $corpus1 = Db::query("select a.id,a.corpus_name ,(select count(*) from tp_corpus_article as b where b.corpus_id=a.id and (b.isqualified=1 or b.isqualified=0) and b.isrelease=1 and b.status=1 and b.isdelete=0) as d from tp_corpus as a order by d desc");
                $corpus = [];
                foreach($corpus1 as $k=>$val){
                    if($val['d']>0){
                        $corpus[] = array(
                            'id' =>$val['id'],
                            'corpus_name' =>$val['corpus_name'],
                        );
                    }
                
                }
				if($id){
                   // $list = DB::name('CorpusArticle')->where('corpus_id',$id)->where('city',$info['city'])->where('isqualified',0)->whereOr('isqualified',1)->where('status',1)->where('isdelete',0)->order('id','desc')->limit($page,$num)->select();
				$list = Db::query("SELECT * FROM `tp_corpus_article` WHERE `corpus_id` = ".$id." AND `city` = '".$info['city']."' AND (`isqualified` = 0 OR`isqualified` = 1)  AND `status` = 1 AND `isdelete` = 0 AND `isrelease` = 1 ORDER BY `release_time` desc ".$limit);
			
                }else{
                  //  $list = DB::name('CorpusArticle')->where('city',$info['city'])->where('status',1)->where('isqualified',0)->whereOr('isqualified',1)->where('isdelete',0)->order('id','desc')->limit($page,$num)->select();
				  
				  $list = Db::query("SELECT * FROM `tp_corpus_article` WHERE `city` = '".$info['city']."' AND (`isqualified` = 0 OR`isqualified` = 1)  AND `status` = 1 AND `isdelete` = 0 AND `isrelease` = 1 ORDER BY `release_time` desc ".$limit);
            
				}

                if($list){
                    $corpusArticle = array();
                    foreach($list as $key=>$value){
						$info1 = DB::name('patriarch')->where('phone_account','=',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();//
						//var_dump($info1);
                        $collectCount = Db::name('CorpusArticleCollect')->where('status',1)->where('isdelete',0)->where('corpusarticle_id',$value['id'])->where('iscollect',1)->count();
                        $commentCount = Db::name('CorpusArticleComment')->where('status',1)->where('isdelete',0)->where('corpusarticle_id',$value['id'])->count();
                        $corpus1 = DB::name('corpus')->where('id','=',$value['corpus_id'])->where('status',1)->where('isdelete',0)->find();
                        $corpusArticle[] = array(
                            'nickname' =>$info1['nickname'],
                            'headerurl' =>$info1['headerurl'],
                            'title' =>str_replace('&nbsp;','',strip_tags(htmlspecialchars_decode($value['title']))),
                            'content' =>str_replace(array('&nbsp;','来自朗学网APP的图片'),'',strip_tags(htmlspecialchars_decode($value['content']))),
                            'corpus_name' =>$corpus1['corpus_name'],
                            'id' =>$value['id'],
                            'image' =>$value['image'],
                            'phone_account' =>$value['phone_account'],
                            'click' =>$value['click'],
							'release_time' =>$value['release_time'],
                            'collectCount' =>$collectCount,
                            'commentCount' =>$commentCount,

                        );
                    }
                }else{
                    $this->json(2,'模块不存在');
                }
              
                $data['corpus'] = $corpus;
                $data['corpusArticle'] = $corpusArticle;
                $this->json(0,'正确',$data);
            }else{
                $this->json(1,'用户不存在');
            }
            
        }
    }
	
    //他人个人中心基本信息

    public function  corpusWorksMessage (){
        $phone_account = $this->request->param('phone_account');
        $phone_account = isset($phone_account)?$phone_account:'';
        $bephone_account = $this->request->param('bephone_account');
        $bephone_account = isset($bephone_account)?$bephone_account:'';
        //基本信息
        $otherinfo = Db::name('patriarch')->field('headerurl,nickname,bgimage')->where('status',1)->where('isdelete',0)->where('phone_account',$bephone_account)->find();
        $model = Db::name('CorpusArticleConcern');
        $fansCount = $model->where('status',1)->where('isdelete',0)->where('bephone_account',$bephone_account)->where('isconcern',1)->count();
        $concernCount = $model->where('status',1)->where('isdelete',0)->where('phone_account',$bephone_account)->where('isconcern',1)->count();
        $otherinfo['fansCount'] = $fansCount;
        $otherinfo['concernCount'] = $concernCount;
		
		
		$isconcern = $model->where('status',1)->where('isdelete',0)->where('phone_account',$phone_account)->where('bephone_account',$bephone_account)->where('isconcern',1)->find();//是否关注
        if($isconcern){
            $otherinfo['isconcern'] = 1;
        }else{
           $otherinfo['isconcern'] = 0;
        }
        //是否私信
		$info = Db::query("SELECT * FROM `tp_direct` WHERE (`phone_account` = '".$phone_account."' AND `bephone_account` = '".$bephone_account."') OR (`phone_account` = '".$bephone_account."' AND `bephone_account` = '".$phone_account."') AND `status` = 1 AND `isdelete` = 0 LIMIT 1");
		//$info = Db::table('tp_direct')->where('status',1)->where('isdelete',1)->where('phone_account','=',$phone_account)->where('bephone_account','=',$bephone_account)->whereOr('bephone_account',$phone_account)
   // ->find();

	
	    if($info){
			$otherinfo['id'] = $info[0]['id'];
		}else{
			$otherinfo['id'] = 0;
		}

        $CorpusArticle = DB::name('CorpusArticle')->where('phone_account',$bephone_account)->where('isqualified',['=',0],['=',1],'or')->where('status',1)->where('isdelete',0)->select();
        //喜欢个数
        $collectCount = 0;
        foreach($CorpusArticle as $key=>$value){
            $collectCount += Db::name('CorpusArticleCollect')->where('status',1)->where('isdelete',0)->where('corpusarticle_id',$value['id'])->where('iscollect',1)->count();
        }
        $otherinfo['collectCount'] = $collectCount;
     

        $this->json(0,'正确',$otherinfo);


    }

      //他人个人中心文章

    public function  corpusWorksMessageArticle (){
        $phone_account = $this->request->param('phone_account');
        $phone_account = isset($phone_account)?$phone_account:'';
        $bephone_account = $this->request->param('bephone_account');
        $bephone_account = isset($bephone_account)?$bephone_account:'';
        $id = $this->request->param('id');
        $id = isset($id)?$id:'';
        $page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
        
        $CorpusArticle1 = [];
		$CorpusArticle = DB::name('CorpusArticle')->where('phone_account',$bephone_account)->where('isqualified',['<>',0],['<>',1],'or')->where('status',1)->where('isrelease',1)->where('isdelete',0)->order('release_time','desc')->limit($page,$num)->select();
    
        //文章
        foreach($CorpusArticle as $key=>$value){
           
            $collectCount = Db::name('CorpusArticleCollect')->where('status',1)->where('isdelete',0)->where('corpusarticle_id',$value['id'])->where('iscollect',1)->count();
            $commentCount = Db::name('CorpusArticleComment')->where('status',1)->where('isdelete',0)->where('corpusarticle_id',$value['id'])->count();
            $CorpusArticle1[] = array(
                'id' =>$value['id'],
                'release_time' =>$value['release_time'],
                'title' =>str_replace('&nbsp;','',strip_tags(htmlspecialchars_decode($value['title']))),
                'image' =>$value['image'],
                'click' =>$value['click'],
                'commentCount' =>$commentCount,
                'collectCount' =>$collectCount,
            );
        }
  
        $this->json(0,'正确',$CorpusArticle1);


    }
	
	 //文集作品收藏
    public function  corpusWorksCollect(){
        $data['answer_id'] = $this->request->param('id');//文集文章id
        $data['phone_account'] = $this->request->param('phone_account');//账号
        $data['release_time'] = date('Y-m-d H:i:s');
        $model = Db::name('InformationArticleAnswerLike');
        $info = $model->where('answer_id',$data['answer_id'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->find();

        if(empty($info)){
			$data['islike'] = 1;
			$data['model'] = 1;
			$re = $model->insert($data);
			if($re){
				$this->json(0,'收藏成功');
			}else{
				$this->json(3,'收藏失败');
			}
          
        }elseif($info['islike'] == 1){
            $re = $model->where('answer_id',$data['answer_id'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['islike'=>0,'release_time'=>date('Y-m-d H:i:s')]);
            if($re){
                $this->json(1,'取消收藏成功');
            }else{
                $this->json(2,'取消收藏失败');
            }
           
        }elseif($info['islike'] == 0){

            $re = $model->where('answer_id',$data['answer_id'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['islike'=>1,'release_time'=>date('Y-m-d H:i:s')]);
			if($re){
				$this->json(0,'收藏成功');
			}else{
				$this->json(3,'收藏失败');
			}
        }
    }
	
	//文集作品评论
    public function corpusWorksComment(){
        if($this->request->isPost()){
            $data['corpusarticle_id'] = $this->request->param('id');//动态id    
            $data['phone_account'] = $this->request->param('phone_account');//账号
            $data['content'] = $this->request->param('content');//内容
            $data['comment_time'] = date('Y-m-d H:i:s');
            $model = Db::name('CorpusArticleComment');
            $re = $model->insert($data);
            if($re){
                $this->json(0,'评论成功');
            }else{
                $this->json(1,'评论失败');
            } 
        }else{
            $this->json(2,'非法请求');
        }
                
    }

    //关注他人

    public function corpusWorksConcern(){
            $data['phone_account'] = $this->request->param('phone_account');//谁关注的
            $data['bephone_account'] = $this->request->param('bephone_account');//被关注的
            $data['concern_time'] = date('Y-m-d H:i:s');
            $model = Db::name('CorpusArticleConcern');
            $concern = $model->where('status',1)->where('isdelete',0)->where('phone_account',$data['phone_account'])->where('bephone_account',$data['bephone_account'])->find();
            //echo Db::getlastsql();
            if(!$concern){
                $data['isconcern'] = 1;
                $re = $model->insert($data);
                if($re){
                    $this->json(0,'关注成功');
                }else{
                    $this->json(1,'关注失败');
                }
            }elseif($concern['isconcern'] ==1){
                $re1 = $model->where('status',1)->where('isdelete',0)->where('phone_account',$data['phone_account'])->where('bephone_account',$data['bephone_account'])->update(['concern_time'=>date('Y-m-d H:i:s'),'isconcern'=>0]);
                if($re1){
                    $this->json(2,'取消关注成功');
                }else{
                    $this->json(3,'取消关注失败');
                }

            }elseif($concern['isconcern'] ==0){
                $re1 = $model->where('status',1)->where('isdelete',0)->where('phone_account',$data['phone_account'])->where('bephone_account',$data['bephone_account'])->update(['concern_time'=>date('Y-m-d H:i:s'),'isconcern'=>1]);
                if($re1){
                    $this->json(0,'关注成功');
                }else{
                    $this->json(1,'关注失败');
                }

            }
    }
    
	
	//更多文集标签
    public function moreCorpus() {
        $info = Db::query("select a.id,a.corpus_name ,(select count(*) from tp_corpus_article as b where b.corpus_id=a.id and (b.isqualified=1 or b.isqualified=0) and b.isrelease=1 and b.status=1 and b.isdelete=0) as d from tp_corpus as a order by d desc");
		
        $data = [];
        foreach($info as $key=>$value){
			if($value['d']>0){
				if($data){  
					$mm = 0;
					foreach($data as $k=>$val){  
						if(in_array($value['corpus_name'],$val)){
							$mm = 1;
						}
					}
					if($mm==0){
						$data[] = array(
							'id' =>$value['id'],
							'corpus_name' =>$value['corpus_name'],
						);
					}
                }else{
					$data[] = array(
						'id' =>$value['id'],
						'corpus_name' =>$value['corpus_name'],
					);
                }

             }
		}
		
        if($data){
            $this->json(0,'有数据',$data);
        }else{
            $this->json(2,'没有数据');
        }                  
    }

	
	
     //  文集作品详情
    public function corpusWorksArticle() {
        $phone_account = $this->request->param('phone_account');
        $id = $this->request->param('id');
        if($id){
            $collect_count = DB::name('InformationArticleAnswerLike')->where('answer_id',$id)->where('islike',1)->where('status',1)->where('model',1)->where('isdelete',0)->count();//喜欢数
            $comment_count = DB::name('CorpusArticleComment')->where('corpusarticle_id',$id)->where('status',1)->where('isdelete',0)->count();//评论数
            //是否喜欢
			$iscollect = DB::name('InformationArticleAnswerLike')->where('answer_id',$id)->where('phone_account',$phone_account)->where('islike',1)->where('status',1)->where('model',1)->where('isdelete',0)->find();//是否点赞
            if($iscollect){
                $iscollect = 1;
            }else{
                $iscollect = 0;
            }
			//是否关注
			 $info = DB::name('CorpusArticle')->where('id',$id)->where('status',1)->where('isdelete',0)->find();//
			 $isconcern = DB::name('CorpusArticleConcern')->where('status',1)->where('isdelete',0)->where('phone_account',$phone_account)->where('bephone_account',$info['phone_account'])->where('isconcern',1)->find();//是否关注
         
            if($isconcern){
                $isconcern  = 1;
            }else{
                $isconcern = 0;
            }
			
            $data['id'] = $id;
            $data['iscollect'] = $iscollect;
			$data['isconcern'] = $isconcern;
            $data['comment_count'] = $comment_count;
            $data['collect_count'] = $collect_count;
            $data['bephone_account'] = $info['phone_account'];
			
            $this->json(0,'正确',$data);
        }else{
                $this->json(1,'参数错误');
            }
    }
 
	
	 //全部学校
    public function allSchool() {
        $phone_account = $this->request->param('phone_account');
		$model = $this->request->param('model');//0全部学校 1 艺术辅导
        $phone_account = isset($phone_account)?$phone_account:'';
        $page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;

        $info = DB::name('patriarch')->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->find();
		if(empty($model)){
			$list1 = Db::table('tp_all_school a, tp_all_school_concern b')->field('a.schoolName as schoolName,a.id as id,a.score as score')->where('a.id=b.school_id and b.phone_account="'.$phone_account.'" and b.isconcern=1')->where('city',$info['city'])->where('educationLevel',$info['grade'])->where('a.isdelete',0)->where('b.status',1)->where('b.isdelete',0)->order('a.score','desc')->limit($page,$num)->select();
			$ids = '';
			foreach($list1 as $key=>$value){
				$ids.=$value['id'].',';
			}
			$ids = substr($ids,0,-1);
			
			$list2 = Db::name('AllSchool')->field('schoolName,id,score')->where('status',1)->where('isdelete',0)->where('city',$info['city'])->where('educationLevel',$info['grade'])->where('id','not in',$ids)->order('score','desc')->limit($page,$num)->select();
			$list = array_merge_recursive($list1,$list2);
			$data = [];
			foreach($list as $key=>$value){

				$isconcern = Db::name('AllSchoolConcern')->where('school_id',$value['id'])->where('phone_account',$phone_account)->where('isconcern',1)->where('status',1)->where('isdelete',0)->find();//是否关注
				if($isconcern){
					$isconcern = 1;
				}else{
					$isconcern = 0;
				}

				$data[] = array(
					'isconcern' => $isconcern,
					'schoolName' => $value['schoolName'],
					'id' => $value['id'],
					'score' =>$value['score'],
				);
			}
		}else{
			$list1 = Db::table('tp_art_tutoring a, tp_art_tutoring_concern b')->field('a.schoolName as schoolName,a.id as id,a.score as score')->where('a.id=b.school_id and b.phone_account="'.$phone_account.'" and b.isconcern=1')->where('city',$info['city'])->where('educationLevel',$info['grade'])->where('a.isdelete',0)->where('b.status',1)->where('b.isdelete',0)->order('a.score','desc')->limit($page,$num)->select();
		
		    $ids = '';
			foreach($list1 as $key=>$value){
				$ids.=$value['id'].',';
			}
			$ids = substr($ids,0,-1);
			
			$list2 = Db::name('ArtTutoring')->field('schoolName,id,score')->where('status',1)->where('isdelete',0)->where('city',$info['city'])->where('educationLevel',$info['grade'])->where('id','not in',$ids)->order('score','desc')->limit($page,$num)->select();
		    //var_dump($list2);
		    $list = array_merge_recursive($list1,$list2);
			$data = [];
			foreach($list as $key=>$value){

				$isconcern = Db::name('ArtTutoringConcern')->where('school_id',$value['id'])->where('phone_account',$phone_account)->where('isconcern',1)->where('status',1)->where('isdelete',0)->find();//是否关注
				if($isconcern){
					$isconcern = 1;
				}else{
					$isconcern = 0;
				}

				$data[] = array(
					'isconcern' => $isconcern,
					'schoolName' => $value['schoolName'],
					'id' => $value['id'],
					'score' =>$value['score'],
				);
			}
		}
        
		
		//var_dump($data);
	
        if($data){
            $this->json(0,'有数据',$data);
        }else{
            $this->json(2,'没有数据');
        } 

    }
	 //学校详情帖子
    public  function schoolPost(){
        $school_id = $this->request->param('id');
        $school_id = isset($school_id)?$school_id:'';
		$model = $this->request->param('model');
        $id = $this->request->param('id');
        $id = isset($id)?$id:'';
        $page = $this->request->param('page');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;
        $data = [];
		// if(empty($model)){
			// $list = DB::name('AllSchoolPost')->where('school_id',$school_id)->where('isqualified',['=',0],['=',1],'or')->where('status',1)->where('isdelete',0)->order('release_time','desc')->limit($page,$num)->select();
		// //    echo Db::getlastsql();
		// }else{
			// $list = DB::name('ArtTutoringPost')->where('school_id',$school_id)->where('isqualified',['=',0],['=',1],'or')->where('status',1)->where('isdelete',0)->order('release_time','desc')->limit($page,$num)->select();
		// //echo Db::getlastsql();
		// }
     // 
	    $list = DB::name('AllSchoolPost')->where('school_id',$school_id)->where('model',$model)->where('isqualified',['=',0],['=',1],'or')->where('status',1)->where('isdelete',0)->order('release_time','desc')->limit($page,$num)->select();
        if($list){
            foreach($list as $key=>$value){
				//var_dump($value['image']); 
            $info = DB::name('patriarch')->where('phone_account',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();
            $data[] = array(
			    'id' =>$value['id'],
				'school_id' =>$value['school_id'],
                'nickname' =>$info['nickname'],
                'headerurl' =>$info['headerurl'],
                'title' =>$value['title'],
                'image' =>$value['image'], 				
                'title' =>str_replace('&nbsp;','',strip_tags(htmlspecialchars_decode($value['title']))),
                'content' =>str_replace(array('&nbsp;','来自朗学网APP的图片'),'',strip_tags(htmlspecialchars_decode($value['content']))),
				'release_time' =>$value['release_time'],
            );
            }
            $this->json(0,'成功',$data);
        }else{
            $this->json(1,'无数据');
        }
    }
	
	
	
	//学校发布帖子
    public  function publishPost(){
       // if($this->request->isPost()){
			$data['phone_account'] = $this->request->param('phone_account');
			$data['title'] = $this->request->param('title');
			$data['model'] = $this->request->param('model');
		    $info = DB::name('patriarch')->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->find();
            $data['city'] = $info['city'];
			$data['school_id'] = $this->request->param('id');
			$data['title'] = $this->request->param('title');
			$data['image'] = $this->request->param('image');
			$data['content'] = $this->request->param('content');
			$data['release_time'] = date('Y-m-d H:i:s');
			
			$re = DB::name('AllSchoolPost')->insert($data);
			
			
			if($re){
				$this->json(0,'发布成功');
			}else{
				$this->json(1,'发布失败');
			}
			
		//}else{
		//	$this->json(2,'非法请求');
		//}
       
    }
	
	

    public function  uploadImg (){

        if(!empty($_FILES['file'])){
            $files = $_FILES['file'];
            if($files){
                $data['images'] = $this->upload($files); 
                $data['thumbs'] = $this->thumb($data['images']);

                return $this->json(0,'正确',$data);
            }
        }

    }
	
	    //学校关注
    public function schoolConcern(){
      
        $data['school_id'] = $this->request->param('id');//动态id
        $model = $this->request->param('model');//0全部学校 1 艺术辅导  
        $data['phone_account'] = $this->request->param('phone_account');//账号
        $data['concern_time'] = date('Y-m-d H:i:s');
		
		if(empty($model)){
			$model = Db::name('AllSchoolConcern');
		}else{
			$model = Db::name('ArtTutoringConcern');
		}
       
        $info = $model->where('school_id',$data['school_id'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->find();
         // var_dump(DB::getlastsql());
        if(empty($info)){
			$data['isconcern'] = 1;
			$re = $model->insert($data);
			if($re){
				$this->json(0,'关注成功');
			}else{
				$this->json(3,'关注失败');
			}
          
        }elseif($info['isconcern'] == 1){
			$re = $model->where('school_id',$data['school_id'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['isconcern'=>0,'concern_time'=>date('Y-m-d H:i:s')]);
			if($re){
				$this->json(1,'取消关注成功');
			}else{
				$this->json(2,'取消关注失败');
			}
           

        }elseif($info['isconcern'] == 0){

			$re = $model->where('school_id',$data['school_id'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['isconcern'=>1,'concern_time'=>date('Y-m-d H:i:s')]);
			if($re){
				$this->json(0,'关注成功');
			}else{
				$this->json(3,'关注失败');
			}
        }

      }
	  
    //学校详情
   public function schoolDetail(){
        $phone_account = $this->request->param('phone_account');//
        $id = $this->request->param('id');//学校id
		$model = $this->request->param('model');//0全部学校 1 艺术辅导
		$image = '';
        if($id){
            if(empty($model)){
				$info = Db::name('AllSchool')->where('id',$id)->where('status',1)->where('isdelete',0)->find();
				$commentData = Db::table('tp_all_school_score a, tp_patriarch b')->field('b.nickname as nickname,b.headerurl as headerurl,a.id as id,a.score_time as score_time,a.content as content,a.score as score')->where('b.phone_account=a.phone_account and a.school_id='.$id)->select();//评论
				$concern_count = Db::name('AllSchoolConcern')->where('school_id',$id)->where('isconcern',1)->where('status',1)->where('isdelete',0)->count();//关注数
				$isconcern =  Db::name('AllSchoolConcern')->where('school_id',$id)->where('phone_account',$phone_account)->where('isconcern',1)->where('isconcern',1)->where('status',1)->where('isdelete',0)->find();//是否关注
			   
			}else{
				$info = Db::name('ArtTutoring')->where('id',$id)->where('status',1)->where('isdelete',0)->find();
				$commentData = Db::table('tp_art_tutoring_score a, tp_patriarch b')->field('b.nickname as nickname,b.headerurl as headerurl,a.id as id,a.score_time as score_time,a.content as content,a.score as score')->where('b.phone_account=a.phone_account and a.school_id='.$id)->select();//评论
				$concern_count = Db::name('ArtTutoringConcern')->where('school_id',$id)->where('isconcern',1)->where('status',1)->where('isdelete',0)->count();//关注数
				$isconcern =  Db::name('ArtTutoringConcern')->where('school_id',$id)->where('phone_account',$phone_account)->where('isconcern',1)->where('isconcern',1)->where('status',1)->where('isdelete',0)->find();//是否关注
			   
			}
           
			$news = Db::name('news')->field('title,content,release_time')->where('schoolAccount',$info['schoolAccount'])->where('status',1)->where('isdelete',0)->order('release_time desc,id desc')->select();//学校新闻

            $album = Db::name('album')->field('images')->where('schoolAccount',$info['schoolAccount'])->where('status',1)->where('isdelete',0)->order('release_time desc,id desc')->find();//活动相册
            if(!$album){
				$album = Db::name('MiddleAlbum')->field('images')->where('schoolAccount',$info['schoolAccount'])->where('status',1)->where('isdelete',0)->order('release_time desc,id desc')->find();//活动相册
			}
			if($album){
				$image = json_decode($album['images']);
				$image = $image[0];
			}
            
            if($isconcern){
                $isconcern = 1;
            }else{
                $isconcern = 0;
            }
            $commentData = [];

           
            if($info['schoolAccount']){
                $recruiturl = $info['schoolAccount'];
                $newsurl = $info['schoolAccount'];

            }else{
                $recruiturl = '';
                $newsurl = '';
            }

            $data['schoolName'] = $info['schoolName'];
            $data['concern_count'] = $concern_count;
            $data['score'] = $info['score'];
            $data['isconcern'] = $isconcern;

            $data['recruiturl'] = $recruiturl;
            $data['newsurl'] = $newsurl;

            $data['album'] = $image;
            $this->json(0,'正确',$data);

        }else{
            $this->json(1,'参数错误');

        }
       
    }

	  //发布学校评价
	public function publishEvaluation(){
      //  if($this->request->isPost()){
            $data['phone_account'] = $this->request->param('phone_account');
            $data['school_id'] = $this->request->param('id');
            $data['score'] = $this->request->param('score');
            $data['content'] = $this->request->param('content');
            $data['score_time'] = date('Y-m-d H:i:s');
			$model = $this->request->param('model');
			
            
			if(empty($model)){
				$re = DB::name('AllSchoolScore')->insert($data);
			}else{//var_dump($model);
				$re = DB::name('ArtTutoringScore')->insert($data);
			}
			
			$score = 0;
			if(empty($model)){
				$count = Db::name('AllSchoolScore')->where('school_id',$data['school_id'])->where('status',1)->where('isdelete',0)->count();
                $list1 = Db::name('AllSchoolScore')->where('school_id',$data['school_id'])->where('status',1)->where('isdelete',0)->select();
			}else{//var_dump($model);
				$count = Db::name('ArtTutoringScore')->where('school_id',$data['school_id'])->where('status',1)->where('isdelete',0)->count();
                $list1 = Db::name('ArtTutoringScore')->where('school_id',$data['school_id'])->where('status',1)->where('isdelete',0)->select();
			}
            
           //var_dump($list1);
            if(!empty($list1)){
                
                foreach($list1 as $k=>$val){
                    $score = $score + $val['score'];
                }
            }
            if($score!=0){
                $score = sprintf("%.1f", $score/$count);
            }
			
            if(empty($model)){
				 Db::name('AllSchool')->where('id',$data['school_id'])->where('status',1)->where('isdelete',0)->update(['score'=>$score]);
			}else{
				 Db::name('ArtTutoring')->where('id',$data['school_id'])->where('status',1)->where('isdelete',0)->update(['score'=>$score]);
			}
           
			
            if($re){
                $this->json(0,'评价成功');
            }else{
                $this->json(1,'评价失败');
            }
       // }else{
          //  $this->json(2,'非法请求');
       // }
        
    }
	
	//学校评价

    public function schoolEvaluation(){
        $school_id = $this->request->param('id');
        $school_id = isset($school_id)?$school_id:'';
        $page = $this->request->param('page');
		$model = $this->request->param('model');
        $page = isset($page)?$page-1:0;
        $num = $this->request->param('num');
        $num = isset($num)?$num:0;
        $page = $page*$num;

        $data = [];
		if(empty($model)){
			$list = DB::name('AllSchoolScore')->where('school_id',$school_id)->where('status',1)->where('isdelete',0)->order('score_time','desc')->limit($page,$num)->select();
		}else{
			$list = DB::name('ArtTutoringScore')->where('school_id',$school_id)->where('status',1)->where('isdelete',0)->order('score_time','desc')->limit($page,$num)->select();
		}
        
        if($list){
            foreach($list  as $key=>$value){
                $info = DB::name('patriarch')->where('phone_account',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();
                $data[] = array(
                    'id' =>$value['id'],
                    'nickname' =>$info['nickname'],
                    'headerurl' =>$info['headerurl'],
                    'score' =>$value['score'], 
                    'score_time' =>$value['score_time'],
                    'content' =>$value['content'], 
                );
            }
            $this->json(0,'成功',$data);
        }else{
            $this->json(1,'无数据');
        }
        
    }
	
		//学校帖子评论
    public function schoolPostComment(){
        $data['schoolpost_id'] = $this->request->param('id');
        $data['phone_account'] = $this->request->param('phone_account');
		$model = $this->request->param('model');
        $data['content'] = $this->request->param('content');
        $data['comment_time'] = date('Y-m-d H:i:s');
		
		if(empty($model)){
			$re = Db::name('AllSchoolPostComment')->insert($data);
		}else{
			$re = Db::name('ArtTutoringPostComment')->insert($data);
		}
        
		if($re){
			$this->json(0,'发布成功');
		}else{
			$this->json(1,'发布失败');
		}
        
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

            $percent = 0.3;
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
