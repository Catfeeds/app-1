<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;
use think\Request;
use think\Session;

class Invite extends Controller
{
    public function index()
    {
         if(!session('id')){
            $this->redirect('Parentlogin/index');
        }
        else{
            if($this->request->isPost()){
               // $data['account'] = session('user_name');
                $data1['number'] = $this->request->param("number");
                $data1['name'] = $this->request->param("name");
                $face_file_ids = $this->request->param("face_file_ids");
                $studentLinkman_id = $this->request->param("studentLinkman_id");
                $data1['student_id'] = $this->request->param("student_id");
                $data1['relation'] = $this->request->param("relation");
                
                $data2['face_file_ids'] = $face_file_ids;
                
                $data2['access'] = 1;
                if(empty($studentLinkman_id)){
					if($face_file_ids){
						$data1['isUpload'] = 1;
					}
                    $re = Db::name("StudentLinkman")->insert($data1);
                    $id = Db::name('StudentLinkman')->getLastInsID();
                    $data2['studentLinkman_id'] = $id;
                    Db::name('StudentLinkmanAccessManagement')->insert($data2); 
                }else
                {   
                    
                    Db::name('StudentLinkman')->where('id',$studentLinkman_id)->where('status',1)->where('isdelete',0)->update($data1); 
                    Db::name('StudentLinkmanAccessManagement')->where('studentLinkman_id',$studentLinkman_id)->where('status',1)->where('isdelete',0)->update($data2); 
                    
                }
                if($data1['relation']=='妈妈'){
                    $relation = 'mommy';
                }
                elseif($data1['relation']=='爸爸'){
                    $relation = 'dada';
                }
                elseif($data1['relation']=='奶奶'){
                    $relation = 'nainai';
                }elseif($data1['relation']=='爷爷'){
                    $relation = 'yeye';
                }
                elseif($data1['relation']=='外婆'){
                    $relation = 'waipo';
                }
                elseif($data1['relation']=='外公'){
                    $relation = 'waigong';
                }
                elseif($data1['relation']=='自定义'){
                    $relation = 'dingyi';
                }

                $this->redirect('invite/index',array('relation'=>$relation));
                

            }else{  
                $relation = $this->request->param('relation');
                if($relation=='mommy'){
                    $relation = '妈妈';
                }
                elseif($relation=='dada'){
                    $relation = '爸爸';
                }
                elseif($relation=='nainai'){
                    $relation = '奶奶';
                }elseif($relation=='yeye'){
                    $relation = '爷爷';
                }
                elseif($relation=='waipo'){
                    $relation = '外婆';
                }
                elseif($relation=='waigong'){
                    $relation = '外公';
                }
                elseif($relation=='dingyi'){
                    $relation = '自定义';
                }
                $info = Db::name("StudentManagement")->where('account',session('user_name'))->where('status',1)->where('isdelete',0)->find();
                $relationinfo = Db::name("StudentLinkman")->where('student_id',$info['id'])->where('relation',$relation)->where('status',1)->where('isdelete',0)->find();
                $info1 = Db::name('StudentLinkmanAccessManagement')->where('studentLinkman_id',$relationinfo['id'])->where('access',1)->where('status',1)->where('isdelete',0)->find(); 
                $info2 = Db::name('File')->where('id',$info1['face_file_ids'])->find(); 
                $this->assign('student_id',$info['id']);
                $this->assign('relationinfo',$relationinfo);
                $this->assign('relation',$relation);
                $this->assign('name',$info2['name']);
                //var_dump($info1);
                return $this->fetch();
            }
        }
		
    }
	

	
    public  function base () {

        $base64 = $this->request->post('str');
      
        if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)){
            $type = $result[2];
            $path = date ('Ymd'); // 接收文件目录
            if (! file_exists ( "./uploads/file/".$path)) {
                mkdir ("./uploads/file/".$path, 0777, true );
            }

            $new_file = "./uploads/file/".$path."/".date('YmdHis').".{$type}";
            $name = "/uploads/file/".$path."/".date('YmdHis').".{$type}";
            $data['name'] = $name;
            $data['mtime'] = time();
            $data['cate'] = 1;
            $re = Db::name('File')->insert($data);
            $fileID = Db::name('File')->field('id')->where('name',$name)->find();
           
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64)))){
                
               return  $fileID['id'];
            }
        }

    }
	

}