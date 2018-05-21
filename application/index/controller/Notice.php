<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

//园内通知
class Notice extends Controller
{
     public function index()
    {

        
        $phone_account = $this->request->param('phone_account');
		$account = $this->request->param('account');
        $type = $this->request->param('type');
        $className = $this->request->param('className');
        
	//	var_dump($className);
		$info = DB::name('patriarch')->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->find();//或手机号登录
		$info1 = DB::name('SchoolManagement')->where('schoolID','=',substr($account,0,5))->where('status',1)->where('isdelete',0)->find();//查找校区账号
		$classManagement = [];
		$vo = DB::name('ClassManagement')->field('className,class')->where('schoolAccount','=',$info1['schoolAccount'])->where('status',1)->where('isdelete',0)->select();//查找校区下的班级
		foreach($vo as $key=>$value){
			
			if($classManagement){
				$mm = 0;
				foreach($classManagement as $k=>$val){
						if(in_array($value['class'],$val)){
						    $mm = 1;
							break;
					    }
			    }
				
				if($mm==0){
					$clssList = DB::name('ClassManagement')->field('className')->where('schoolAccount','=',$info1['schoolAccount'])->where('class','=',$value['class'])->where('status',1)->where('isdelete',0)->order('className','asc')->select();
					$classManagement[] = array(
						'class' =>$value['class'],
						'className' =>$clssList,
			        );
				}
			}else{
				$clssList = DB::name('ClassManagement')->field('className')->where('schoolAccount','=',$info1['schoolAccount'])->where('class','=',$value['class'])->where('status',1)->where('isdelete',0)->order('className','asc')->select();
					$classManagement[] = array(
						'class' =>$value['class'],
						'className' =>$clssList,
			        );
			}
		}
		if(empty($className)){//园长点击班级
			if(empty($type)){//家长
			
			   $info2 = Db::name('StudentManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
			}elseif($type==1){//园长
				$info2['className'] = $classManagement[0]['class'].$classManagement[0]['className'][0]['className'];
			}elseif($type==2){//教师
				$info2 = Db::name('EmployeeManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
			}
			$className = $info2['className']; //所在班级
		}
		//$className = $info2['className']; //所在班级
		
		$schoolAccount =  $info1['schoolAccount'];//所在校区

	
		$notice1 = Db::table('tp_notice a, tp_notice_read b')->field('a.id as id,a.title as title,a.phone_account as phone_account,a.account as account,a.release_time as release_time,a.content as content,a.images as images,a.thumbs as thumbs')->where('a.id=b.nid and b.account="'.$account.'"  and schoolAccount="'.$schoolAccount.'"')->where('className',['=',$className],['=','无'],'or')->where('a.status',1)->where('a.isdelete',0)->where('b.status',1)->where('b.isdelete',0)->order('a.release_time','desc')->select();
		$ids = '';
		foreach($notice1 as $key=>$value){
			$ids.=$value['id'].',';
		}
		$ids = substr($ids,0,-1);
		$notice2 = Db::name('notice')->where('schoolAccount',$schoolAccount)->where('className',['=',$className],['=','无'],'or')->where('status',1)->where('isdelete',0)->where('id','not in',$ids)->order('release_time','desc')->select();
		$notice = array_merge_recursive($notice2,$notice1);

		$list= array();
		foreach($notice as $key=>$value){
			$info3 = DB::name('patriarch')->where('phone_account','=',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();
			$info4 = DB::name('EmployeeManagement')->where('account','=',$value['account'])->where('status',1)->where('isdelete',0)->find();

			$images = json_decode($value['images']);
			$thumbs = json_decode($value['thumbs']);
			$img = array();
			if(is_array($thumbs )&&is_array($images)){
				foreach($thumbs as $k=>$val){
				$img[$k][] = $images[$k];     
				$img[$k][] = $val;
				}
			}
			$arrid = explode(',',$ids);
			//是否阅读
			if(in_array($value['id'],$arrid)){
				$isread =1;
			}else{
				$isread =0;
			}
			
			
			$list[] = array(
				'id' => $value['id'],
				'title' => $value['title'],
				'nickname' => $info4['name'].'老师',
				'headerurl' => $info3['headerurl'],
				'release_time' => $this->time_tran($value['release_time']),
				'content' => $this->mysub($value['content']),
				'img' => $img,
				'isread' =>$isread
			   
			);

	    }  

		$this->assign('list',$list);
		$this->assign('phone_account',$phone_account);
		$this->assign('classManagement',$classManagement);
		$this->assign('account',$account);
		$this->assign('type',$type);
		return $this->fetch();


        
    }
	
	
    //详情
    public function details(){
            $nid = $this->request->param('id');
			$phone_account = $this->request->param('phone_account');
			$type = $this->request->param('type');
			$account = $this->request->param('account');
			
			$isread = Db::name('NoticeRead')->where('nid',$nid)->where('isdelete',0)->where('status',1)->where('phone_account',$phone_account)->find();
			if(empty($isread)){
				//当前用户阅读
				$data['nid'] = $nid;
				$data['phone_account'] = $phone_account;
				$data['account'] = $account;
				$data['read_time'] = date('Y-m-d H:i:s');
				
				Db::name('NoticeRead')->insert($data);
			}

            $noticeinfo = Db::name('notice')->where('id',$nid)->where('status',1)->where('isdelete',0)->find();

            $info = Db::name('patriarch')->where('phone_account',$noticeinfo['phone_account'])->where('status',1)->where('isdelete',0)->find();
            $info1 = Db::name('EmployeeManagement')->where('iphone',$info['phone'])->where('status',1)->where('isdelete',0)->find();
			
			
            $notice['nickname'] = $info1['name'].'老师';
			$notice['title'] = $noticeinfo['title'];
            $notice['headerurl'] = $info['headerurl'];
            $notice['content'] = $noticeinfo['content'];
            $notice['release_time'] = $this->time_tran($noticeinfo['release_time']);
            $notice['images'] = json_decode($noticeinfo['images']);
            $notice['thumbs'] = json_decode($noticeinfo['thumbs']);
			
            $commentCount = Db::name('NoticeComment')->where('nid',$nid)->where('status',1)->where('isdelete',0)->order('comment_time','desc')->count();//统计评论个数
            $zanCount = Db::name('NoticeZan')->where('nid',$nid)->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->count();//统计赞个数
            $commentlist = Db::name('NoticeComment')->where('nid',$nid)->where('status',1)->where('isdelete',0)->order('comment_time','desc')->select();
            $zanlist = Db::name('NoticeZan')->where('nid',$nid)->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->select();
            $comment =array();
            $zan =array();
          
            //动态评论
            foreach ($commentlist as $key => $value) {
				//$pos = strpos('\n', $value['content']);
				
                $commentinfo = Db::name('patriarch')->where('phone_account',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();
                $comment[]=array(
                   'id' => $value['id'],
                   'nickname' =>$commentinfo['nickname'],
                   'headerurl' =>$commentinfo['headerurl'],
                   'comment_time'=>$this->time_tran($value['comment_time']),
                   'content' =>html_entity_decode(str_replace('\n','</br>',$value['content'])),

               );
              
            }
            //动态赞
            foreach ($zanlist as $key => $value) {
                    $zaninfo = Db::name('patriarch')->where('phone_account',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();
                   
                    $zan[]=array(
                       'id' => $value['id'],
                       'nickname' =>$zaninfo['nickname'],
                       'headerurl' =>$zaninfo['headerurl'],
                   );
            }
			
			//当前用户是否点赞
			$iszan = Db::name('NoticeZan')->where('nid',$nid)->where('iszan',1)->where('phone_account',$phone_account)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->find();//统计赞个数
            if($iszan){
				$iszan = 1;
			}else{
				$iszan = 0;
			}
		 //var_dump($iszan); var_dump($zan);
            $this->assign('zan',$zan);
			$this->assign('iszan',$iszan);
            $this->assign('comment',$comment);
            $this->assign('notice',$notice);
			$this->assign('commentCount',$commentCount);
            $this->assign('zanCount',$zanCount);
			$this->assign('phone_account',$phone_account);
		    $this->assign('account',$account);
		    $this->assign('type',$type);
			$this->assign('nid',$nid);
            return $this->fetch();



    }
	
	public function noticezan(){
      
        $data['nid'] = $this->request->param('id');//动态id

        $data['phone_account'] = $this->request->param('phone_account');//账号

        $data['zan_time'] = date('Y-m-d H:i:s');
        $model = Db::name('NoticeZan');
        $info = $model->where('nid',$data['nid'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->find();
      
         // $info2 = $model->where('rid',$data['rid'])->where('account',$data['account'])->where('status',1)->where('isdelete',0)->where('iszan',1)->find();
        if(empty($info)){
			
			$data['iszan'] = 1;
			$re = $model->insert($data);
			if($re){
				exit('1');//赞成功
			}else{
				exit('0');//赞失败
		    }
          
        }elseif($info['iszan'] == 1){
			
            $re = $model->where('nid',$data['nid'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['iszan'=>0,'zan_time'=>date('Y-m-d H:i:s')]);
            if($re){
                exit('2');//取消赞成功
           }else{
               exit('3');//取消赞失败
           }
           

        }elseif($info['iszan'] == 0){
		
            $re = $model->where('nid',$data['nid'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['iszan'=>1,'zan_time'=>date('Y-m-d H:i:s')]);
        // exit($re);
		    if($re){
				exit('1');//赞成功
			}else{
				exit('0');//赞失败
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
  
  




    public function zan(){
            
                $data['cid'] = $this->request->param('cid');//动态id
                //$data['phone_account'] = session('user_name');//账号
                $data['phone_account'] = $this->request->param('phone_account');//动态id

                $data['zan_time'] = date('Y-m-d H:i:s');
                $model = Db::name('ClassroomZan');
                $info = $model->where('cid',$data['cid'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->find();
                
                if(empty($info)){
                    $data['iszan'] = 1;
                    $re = $model->insert($data);
                    //谁赞的
                    $zanlist = $model ->where('cid',$data['cid'])->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->select();
                    $zanname='<p class="zan-pass">';
                    foreach($zanlist as $key=>$value){
                        $student = Db::name('StudentManagement')->where('phone_account',$value['phone_account'])->where('status',1)->find();
                        $zanname.="<span><i>".$student['name']."</i>赞过</span>";
                   
                    }
                     return $zanname;
                    
                }elseif($info['iszan'] == 1){
                    $re = $model->where('cid',$data['cid'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['iszan'=>0]);
                    
                    //谁赞的
                    $zanlist = $model ->where('cid',$data['cid'])->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->select();
                    $zanname='';
                    foreach($zanlist as $key=>$value){
                        $student = Db::name('StudentManagement')->where('phone_account',$value['phone_account'])->where('status',1)->find();
                        $zanname.="<span><i>".$student['name']."</i>赞过</span>";
                   
                    }

                    return $zanname;
                     

                }elseif($info['iszan'] == 0){

                    $re = $model->where('cid',$data['cid'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['iszan'
                        =>1]);
                
                    //谁赞的
                    $zanlist = $model->where('cid',$data['cid'])->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->select();
                    $zanname='';
                    foreach($zanlist as $key=>$value){
                        $student = Db::name('StudentManagement')->where('phone_account',$value['phone_account'])->where('status',1)->find();
                        $zanname.="<span><i>".$student['name']."</i>赞过</span>";
                   
                    }
                     return $zanname;               
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

    public function mysub($str){
        if(mb_strlen($str,'utf-8')>80){
        	return $str = mb_substr($str, 0,80,'utf-8').'...';
        }else {
        	return $str;
        }
    }
}