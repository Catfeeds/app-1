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


class Pubdynamicapi extends Controller{

    //图片接口
    public function index()
    {

        //接收教师账号account、图片---文件域file
		$phone = $this->request->param('phone');//账号

        $info = Db::name('AdminUser')->where('mobile',$phone)->where('status',1)->where('isdelete',0)->find();
        $type = $info['type'];
        if($type == 2){
            if(!empty($_FILES['file'])){
                $files = $_FILES['file'];
                if($files){
                    $data['images'] = $this->upload($files);
//                    $data['thumbs'] = $this->thumb(json_decode($data['images']));
                    $data['thumbs'] = $this->thumb($data['images']);


                    echo $this->json(0,'ok',$data);

                }
            }



        }else{
            echo $this->json(3,'没有权限');
        }


    }



    public function release()
    {

        //接收教师账号account、标题title、内容content、板块类型type、图片---文件域file
        $phone = $this->request->param('phone');

        $info = Db::name('AdminUser')->where('mobile',$phone)->where('status',1)->where('isdelete',0)->find();
        $type = $info['type'];
        if($type == 2){

            $title = $this->request->param('title');//动态标题
            $type = $this->request->param('type');//板块类型
            $content =  $this->request->param('content');//动态内容
            $release_time = date('Y-m-d H:i:s');//动态发表时间


            $firstImgurl = $this->request->param('firstImgurl'); //第一张图片路径



            $employee_info = Db::name('EmployeeManagement')->where('account',$info['account'])->where('status',1)->where('isdelete',0)->find();
            $employee_id = $employee_info['id'];//教师的id
            $schoolName = $employee_info['schoolName'];//教师所在的校区
            $release = $employee_info['name'];//教师姓名

            //开始插入数据
            $data['employee_id'] = $employee_id;
            $data['content'] = $content;

            $data['firstImgurl'] = $firstImgurl;//动态的第一张图片

            $data['release'] = $release;
            $data['release_time'] = $release_time;
            $data['account'] = $info['account'];
            $data['type'] = $type;
            $data['title'] = $title;
            $data['schoolName'] = $schoolName;

            $re = Db::name('CircleDynamic')->insert($data);
            if($re){
                echo $this->json(0,'发布成功');
            }else{
                echo $this->json(1,'发布失败');
            }


        }else{
            echo $this->json(3,'没有权限');
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

//        return json_encode($thumbinfo);
        return $thumbinfo;
    }




    public function json($code,$msg,$data=array())
    {

        if(!is_numeric($code)){
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
        return json_encode($result);
    }







}