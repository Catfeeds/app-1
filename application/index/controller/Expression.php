<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

//园区风采
class Expression extends Controller
{
    public function index()
    {

        $phone_account = $this->request->param('phone_account');
		$account = $this->request->param('account');
        $type = $this->request->param('type');
        
            //判断校区
       // $info = DB::name('patriarch')->where('phone_account','=',$phone_account)->where('status',1)->where('isdelete',0)->find();//或手机号登录
		$info1 = DB::name('SchoolManagement')->where('schoolID','=',substr($account,0,5))->where('status',1)->where('isdelete',0)->find();//查找校区账号
		// if(empty($type)){//家长
			
		   // $info2 = Db::name('StudentManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
		// }else{//教师
			// $info2 = Db::name('EmployeeManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
		// }
       
            $schoolAccount =  $info1['schoolAccount'];
			//$className =  $info2['className'];
            $expression = Db::name('mien')->where('schoolAccount',$schoolAccount)->where('status',1)->where('isdelete',0)->order('release_time desc,id desc')->select();
            
            $list= array();
			//var_dump($expression);
            foreach($expression as $key=>$value){
                $info1 = DB::name('EmployeeManagement')->where('account','=',$value['account'])->where('status',1)->where('isdelete',0)->find();//获取头像
				$info2 = DB::name('patriarch')->where('phone_account','=',$value['phone_account'])->where('status',1)->where('isdelete',0)->find();//获取姓名
				
				$comment_count = Db::name("MienComment")->where('isdelete','0')->where("status",1)->where("rid",$value['id'])->count();//统计评论个数
				$zan_count = Db::name("MienZan")->where('isdelete','0')->where("status",1)->where("rid",$value['id'])->where("iszan",1)->count();//统计赞个数
				
			
				$list[] = array(
							'id' => $value['id'],
							'title' => $value['title'],
							'headerurl' => $info2['headerurl'],
							'nickname' => $info1['name'].'老师',
							'release_time' => $value['release_time'],
							'content' => $value['content'],
							'zan_count' => $zan_count,
							'images' => json_decode($value['images']),
						    'thumbs' => json_decode($value['thumbs']),
							'comment_count' =>  $comment_count,
							//'iszan' =>  $iszan,
						);

				}
			//var_dump($list);exit;
            $this->assign('list',$list);
			$this->assign('phone_account',$phone_account);
		    $this->assign('account',$account);
		    $this->assign('type',$type);
            return $this->fetch();
			
            }
		


   	
    //详情
    public function details(){
            $rid = $this->request->param('id');
			$phone_account = $this->request->param('phone_account');
			$type = $this->request->param('type');
			$account = $this->request->param('account');
		
            $noticeinfo = Db::name('mien')->where('id',$rid)->where('status',1)->where('isdelete',0)->find();

            $info = Db::name('patriarch')->where('phone_account',$noticeinfo['phone_account'])->where('status',1)->where('isdelete',0)->find();
            $info1 = Db::name('EmployeeManagement')->where('iphone',$info['phone'])->where('status',1)->where('isdelete',0)->find();
			
			
            $notice['nickname'] = $info1['name'].'老师';
			$notice['title'] = $noticeinfo['title'];
            $notice['headerurl'] = $info['headerurl'];
            $notice['content'] = $noticeinfo['content'];
            $notice['release_time'] = $this->time_tran($noticeinfo['release_time']);
            $notice['images'] = json_decode($noticeinfo['images']);
            $notice['thumbs'] = json_decode($noticeinfo['thumbs']);
			
            $commentCount = Db::name('MienComment')->where('rid',$rid)->where('status',1)->where('isdelete',0)->order('comment_time','desc')->count();//统计评论个数
            $zanCount = Db::name('MienZan')->where('rid',$rid)->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->count();//统计赞个数
            $commentlist = Db::name('MienComment')->where('rid',$rid)->where('status',1)->where('isdelete',0)->order('comment_time','desc')->select();
            $zanlist = Db::name('MienZan')->where('rid',$rid)->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->select();
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
			$iszan = Db::name('MienZan')->where('rid',$rid)->where('iszan',1)->where('phone_account',$phone_account)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->find();//统计赞个数
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
			$this->assign('rid',$rid);
            return $this->fetch();



    }
	
	
	public function expressionzan(){
      
        $data['rid'] = $this->request->param('id');//动态id

        $data['phone_account'] = $this->request->param('phone_account');//账号

        $data['zan_time'] = date('Y-m-d H:i:s');
        $model = Db::name('MienZan');
        $info = $model->where('rid',$data['rid'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->find();
      
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
			
            $re = $model->where('rid',$data['rid'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['iszan'=>0,'zan_time'=>date('Y-m-d H:i:s')]);
            if($re){
                exit('2');//取消赞成功
           }else{
               exit('3');//取消赞失败
           }
           

        }elseif($info['iszan'] == 0){
		
            $re = $model->where('rid',$data['rid'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['iszan'=>1,'zan_time'=>date('Y-m-d H:i:s')]);
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
                $data['account'] = session('user_name');//账号

                $data['zan_time'] = date('Y-m-d H:i:s');
                $model = Db::name('ClassroomZan');
                $info = $model->where('cid',$data['cid'])->where('account',$data['account'])->where('status',1)->where('isdelete',0)->find();
                
                if(empty($info)){
                    $data['iszan'] = 1;
                    $re = $model->insert($data);
                    //谁赞的
                    $zanlist = $model ->where('cid',$data['cid'])->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->select();
                    $zanname='<p class="zan-pass">';
                    foreach($zanlist as $key=>$value){
                        $student = Db::name('StudentManagement')->where('account',$value['account'])->where('status',1)->find();
                        $zanname.="<span><i>".$student['name']."</i>赞过</span>";
                   
                    }
                     return $zanname;
                    
                }elseif($info['iszan'] == 1){
                    $re = $model->where('cid',$data['cid'])->where('account',$data['account'])->where('status',1)->where('isdelete',0)->update(['iszan'=>0]);
                    
                    //谁赞的
                    $zanlist = $model ->where('cid',$data['cid'])->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->select();
                    $zanname='';
                    foreach($zanlist as $key=>$value){
                        $student = Db::name('StudentManagement')->where('account',$value['account'])->where('status',1)->find();
                        $zanname.="<span><i>".$student['name']."</i>赞过</span>";
                   
                    }

                    return $zanname;
                     

                }elseif($info['iszan'] == 0){

                    $re = $model->where('cid',$data['cid'])->where('account',$data['account'])->where('status',1)->where('isdelete',0)->update(['iszan'
                        =>1]);
                
                    //谁赞的
                    $zanlist = $model->where('cid',$data['cid'])->where('iszan',1)->where('status',1)->where('isdelete',0)->order('zan_time','desc')->select();
                    $zanname='';
                    foreach($zanlist as $key=>$value){
                        $student = Db::name('StudentManagement')->where('account',$value['account'])->where('status',1)->find();
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

}