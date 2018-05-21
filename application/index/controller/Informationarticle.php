<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;
use think\Session;
use think\Request;

class Informationarticle extends Controller
{
   
    //  社区问答回答详情
    public function index() {
        $phone_account = $this->request->param('phone_account'); 
        $id = $this->request->param('id');//回答id
        $zanData=[];
        $commentData=[];
        if($id){
			$info2 = DB::name('InformationArticleAnswer')->where('id',$id)->where('status',1)->where('isdelete',0)->find();//查找当前的回答
			DB::name('InformationArticleAnswer')->where('id',$id)->where('status',1)->where('isdelete',0)->update(['click'=>$info2['click']+1]);
			
            $info = DB::name('InformationArticle')->where('id',$info2['infoarticle_id'])->where('status',1)->where('isdelete',0)->find();//查找当前回答所属问题

            $info1 = DB::name('patriarch')->where('phone_account','=',$info2['phone_account'])->where('status',1)->where('isdelete',0)->find();//获取回答者的信息
                    //DB::name('InformationArticleZan')->where('phone_account','=',$info['phone_account'])->where('status',1)->where('isdelete',0)->find();//
            $zanData = Db::table('tp_information_article_answer_like a, tp_patriarch b')->field('b.headerurl as headerurl,b.nickname as nickname,b.phone_account as phone_account')->where('b.phone_account=a.phone_account and a.answer_id='.$id)->where('a.islike',1)->where('a.model',0)->order('release_time','desc')->select();//谁喜欢

            $commentList = Db::name('InformationArticleAnswerComment')->where('answer_id',$id)->where('status',1)->where('isdelete',0)->order('comment_time','desc')->order('comment_time','desc')->select();//谁评论
            foreach ($commentList as $key => $value) {
                    $personalInfo = Db::name('patriarch')->where('phone_account',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();
                    $commentData[]=array(
                       'id' => $value['id'],
                       'nickname' =>$personalInfo['nickname'],
                       'headerurl' =>$personalInfo['headerurl'],
                       'comment_time'=>$this->time_tran($value['comment_time']),
                       'content' =>$value['content'],
					   'phone_account' =>$value['phone_account'],

                   );
                  
            }
			$like_count = DB::name('InformationArticleAnswerLike')->where('answer_id',$id)->where('islike',1)->where('status',1)->where('isdelete',0)->where('model',0)->count();//喜欢数
            $comment_count = DB::name('InformationArticleAnswerComment')->where('answer_id',$id)->where('status',1)->where('isdelete',0)->count();//评论数
             //标签名
			$infomartion = DB::name('information')->where('id',$info['info_id'])->where('status',1)->where('isdelete',0)->find();//
			
			$data['info_name'] = $infomartion['info_name'];
			$data['id'] = $infomartion['id'];
			$data['like_count'] = $like_count;
            $data['comment_count'] = $comment_count;
            $data['title'] = htmlspecialchars_decode($info['title']);
            $data['headerurl'] = $info1['headerurl'];
			$data['phone_account'] = $info['phone_account'];
            $data['nickname'] = $info1['nickname'];
			$data['grade'] = $info1['grade'];
            $data['release_time'] = $this->time_tran($info['release_time']);
            $data['content'] = htmlspecialchars_decode($info2['content']);
            $this->assign("data", $data); 
            $this->assign("zanData", $zanData);  
            $this->assign("commentData", $commentData);       
            return $this->view->fetch();
        }

    }
	
	
	//学校详情
	public function  index2(){
        $phone_account = $this->request->param('phone_account'); 
        $id = $this->request->param('id');
		$model = $this->request->param('model');
		$data = [];
		if(empty($model)){
			$info2 = Db::name('AllSchoolPost')->where('id',$id)->where('status',1)->where('isdelete',0)->find();
			$info['commentCount'] = Db::name('AllSchoolPostComment')->where('schoolpost_id',$info2['id'])->where('status',1)->where('isdelete',0)->count();
			$data1 = Db::name('AllSchoolPostComment')->where('schoolpost_id',$info2['id'])->where('status',1)->where('isdelete',0)->order('comment_time','desc')->select();
		  
		}else{
			$info2 = Db::name('ArtTutoringPost')->where('id',$id)->where('status',1)->where('isdelete',0)->find();
			$info['commentCount'] = Db::name('ArtTutoringPostComment')->where('schoolpost_id',$info2['id'])->where('status',1)->where('isdelete',0)->count();
			$data1 = Db::name('ArtTutoringPostComment')->where('schoolpost_id',$info2['id'])->where('status',1)->where('isdelete',0)->order('comment_time','desc')->select();
		   
		}
		foreach ($data1 as $key => $value) {
                    $personalInfo = Db::name('patriarch')->where('phone_account',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();
                    $data[]=array(
                       'id' => $value['id'],
                       'nickname' =>$personalInfo['nickname'],
                       'headerurl' =>$personalInfo['headerurl'],
                       'comment_time'=>$this->time_tran($value['comment_time']),
                       'content' =>$value['content'],
					   'phone_account' =>$value['phone_account'],

                   );
                  
            }

        $info1 = Db::name('patriarch')->where('phone_account',$info2['phone_account'])->where('status',1)->where('isdelete',0)->find();
        $info['nickname'] = $info1['nickname'];
        $info['headerurl'] = $info1['headerurl'];
        $info['release_time'] = $this->time_tran($info2['release_time']);
        $info['content'] = htmlspecialchars_decode($info2['content']);
		$info['title'] = htmlspecialchars_decode($info2['title']);
		
		//var_dump($data);exit;
        $this->assign("info", $info);  
        $this->assign("data", $data);   
      
        return $this->view->fetch('index2');
    }
	
     // 文集作品详情
    public function cindex() {
        $phone_account = $this->request->param('phone_account'); 
        $id = $this->request->param('id');
		//var_dump($id);var_dump($phone_account);
        $zanData=[];
        $commentData=[];
        if($id){
            $info = DB::name('CorpusArticle')->where('id',$id)->where('status',1)->where('isdelete',0)->find();//
			
            $info1 = DB::name('patriarch')->where('phone_account','=',$info['phone_account'])->where('status',1)->where('isdelete',0)->find();//
                    //DB::name('InformationArticleZan')->where('phone_account','=',$info['phone_account'])->where('status',1)->where('isdelete',0)->find();//
            $collectData = Db::table('tp_information_article_answer_like a, tp_patriarch b')->field('b.headerurl as headerurl,b.nickname as nickname,b.phone_account as phone_account')->where('b.phone_account=a.phone_account and a.answer_id='.$id)->where('a.islike',1)->where('a.model',1)->order('a.release_time','desc')->select();//谁赞

            $commentList = Db::name('CorpusArticleComment')->where('corpusarticle_id',$id)->where('status',1)->where('isdelete',0)->order('comment_time','desc')->select();
            foreach ($commentList as $key => $value) {
                    $personalInfo = Db::name('patriarch')->where('phone_account',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();
                    $commentData[]=array(
                       'id' => $value['id'],
                       'nickname' =>$personalInfo['nickname'],
                       'headerurl' =>$personalInfo['headerurl'],
                       'comment_time'=>$this->time_tran($value['comment_time']),
                       'content' =>$value['content'],
                       'phone_account' =>$value['phone_account'],
                   );
                  
            }
            $collect_count =  $collect_count = DB::name('InformationArticleAnswerLike')->where('answer_id',$id)->where('islike',1)->where('status',1)->where('model',1)->where('isdelete',0)->count();//喜欢数
            $comment_count = DB::name('CorpusArticleComment')->where('corpusarticle_id',$id)->where('status',1)->where('isdelete',0)->count();//评论数

            $isconcern = DB::name('CorpusArticleConcern')->where('status',1)->where('isdelete',0)->where('phone_account',$phone_account)->where('bephone_account',$info['phone_account'])->where('isconcern',1)->find();//是否关注
            //var_dump($info);
            //var_dump($phone_account);
            if($isconcern){
                $data['isconcern'] = 1;
            }else{
               $data['isconcern'] = 0;
            }
            //是否自己查看自己
			if($phone_account==$info['phone_account']){
				$data['isme'] = 1;
			}else{
				$data['isme'] = 0;
			}
            //阅读数
			$info['click'] = $info['click']+1;
			$re = DB::name('CorpusArticle')->where('id',$id)->where('status',1)->where('isdelete',0)->update(['click'=>$info['click']]);//
		    //标签名
			$corpus = DB::name('corpus')->where('id',$info['corpus_id'])->where('status',1)->where('isdelete',0)->find();//
			
			$data['corpus_name'] = $corpus['corpus_name'];
			$data['id'] = $corpus['id'];
            $data['collect_count'] = $collect_count;
            $data['comment_count'] = $comment_count;
            $data['title'] = htmlspecialchars_decode($info['title']);
            $data['headerurl'] = $info1['headerurl'];
            $data['phone_account'] = $phone_account;
            $data['bephone_account'] = $info['phone_account'];
            $data['nickname'] = $info1['nickname'];
			$data['grade'] = $info1['grade'];
            $data['release_time'] = $this->time_tran($info['release_time']);
            $data['content'] = htmlspecialchars_decode($info['content']);
            $this->assign("data", $data); 
            $this->assign("collectData", $collectData);  
            $this->assign("commentData", $commentData);       
            return $this->view->fetch();
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
                   exit('0');
                }else{
                   exit('1');
                }
            }elseif($concern['isconcern'] ==1){
                $re1 = $model->where('status',1)->where('isdelete',0)->where('phone_account',$data['phone_account'])->where('bephone_account',$data['bephone_account'])->update(['concern_time'=>date('Y-m-d H:i:s'),'isconcern'=>0]);
                if($re1){
                    exit('2');

                }else{
                   exit('3');
                }

            }elseif($concern['isconcern'] ==0){
                $re1 = $model->where('status',1)->where('isdelete',0)->where('phone_account',$data['phone_account'])->where('bephone_account',$data['bephone_account'])->update(['concern_time'=>date('Y-m-d H:i:s'),'isconcern'=>1]);
                if($re1){
                   exit('0');
                }else{
                  exit('1');
                }

            }
    }
	
	public function time_tran($the_time) {  
		$now_time = date("Y-m-d H:i:s", time());  
		//echo $now_time;  
		$now_time = strtotime($now_time);  
		$show_time = strtotime($the_time);  
		$dur = $now_time - $show_time;  
		if ($dur < 0) {  
			return $the_time;  
		} else {  
			if ($dur < 60) {  
				return $dur . '秒前';  
			} else {  
				if ($dur < 3600) {  
					return floor($dur / 60) . '分钟前';  
				} else {  
					if ($dur < 86400) {  
						return floor($dur / 3600) . '小时前';  
					} else {  
						if ($dur < 259200) {//3天内  
							return floor($dur / 86400) . '天前';  
						} else {  
							return $the_time;  
						}  
					}  
				}  
			}  
		}  
    }

}
