<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;
use think\Request;
use think\Session;

class Rnote extends Controller
{
    public function index()
    {
        if(!session('id')){

            $this->redirect('Parentlogin/index');//不提示，直接跳转到登录页面
        }else{
            if($this->request->isPost()){

                $account = session('user_name');
                $info = Db::name('AdminUser')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
                $type = $info['type'];
                if($type == 3){
                    $info2 = Db::name('StudentManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
                    $schoolName = $info2['schoolName'];
                }
                if($type == 2){
                    $info2 = Db::name('EmployeeManagement')->where('account',$account)->where('status',1)->where('isdelete',0)->find();
                    $schoolName = $info2['schoolName'];
                }
                $data['release'] = $schoolName;//当前用户所在学区
                $data['account'] = session('user_name');
                $data['content'] = $this->request->param("content");
                $data['release_time'] = date('Y-m-d H:i:s',time());
                $data['images'] = $this->request->param("images");


                if(!empty($data['images'])&&!empty($data['images'])){
                    $ret = Db::name("Note")->insert($data);
                    return $this->redirect('Note/index');
                }else{
                    return $this->redirect('Rnote/index');
                }
            }else{

                return $this->fetch();
            }
        }

    }
	
	public function thumb($info){
        $arr = explode('.',$info);
        $thumbinfo = $arr[0].'_thumb.'.$arr[1];
      
       // $path = ROOT_PATH.'public/uploads/file'.date('Ymd');

        $info=\think\Image::open('./uploads/file/'.$info)->thumb(90)->save('./uploads/file/'.$thumbinfo);    
        return $thumbinfo;
        //exit(json_encode(['error'=>0,'url'=>$imgurl]));
    
    }
	
    public  function base () {

        $base64 = $this->request->post('str');
      
        if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)){
            $type = $result[2];
            $path = date ('Ymd'); // 接收文件目录
            if (! file_exists ( "./uploads/file/".$path)) {
                mkdir ("./uploads/file/".$path, 0777, true );
            }

            $new_file = "./uploads/file/".$path."/".time().".{$type}";
            $headurl = date ('Ymd').'/'.time().".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64)))){
                exit($headurl);
                    
            }
        }

    }
	
	
	
	
	
	
	
	
}