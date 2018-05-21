<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;

use think\Cookie;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;
use think\Request;
use think\Session;

//初中---活动相册
class MiddleAlbum extends Controller
{
    use \app\admin\traits\controller\Controller;
    // 方法黑名单
    protected static $blacklist = [];

    protected function filter(&$map)
    {
        if ($this->request->param("title")) {
            $map['title'] = ["like", "%" . $this->request->param("title") . "%"];
        }
        if ($this->request->param("type")) {
            $map['type'] = ["like", "%" . $this->request->param("type") . "%"];
        }

        $info = Db::name("AdminUser")->where("id", UID)->find();
        if($info['type'] ==1)
        {

//          $map['release']=["like", "%" . $info1['realname']. "%"];
            $map['schoolAccount'] = $info['account'];
        }
        else if($info['type'] ==2)
        {

//          $map['release']=["like", "%" . $info1['realname']. "%"];
            $account = $info['account'];
            $schoolID = substr($account,0,5);//截取当前用户账号的前5位---即学区号 ,如C0398
            $map['schoolAccount'] = ["like", "%" . $schoolID. "%"];

        }
        else if($info['type'] ==3)
        {

//          $map['release']=["like", "%" . $info1['realname']. "%"];
            $account = $info['account'];
            $schoolID = substr($account,0,5);//截取当前用户账号的前5位---即学区号 ,如C0398
            $map['schoolAccount'] = ["like", "%" . $schoolID. "%"];

        }


    }

    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        $model = $this->getModel();

        // 列表过滤器，生成查询Map对象
        $map = $this->search($model, [$this->fieldIsDelete => $this::$isdelete]);

        // 特殊过滤器，后缀是方法名的
        $actionFilter = 'filter' . $this->request->action();
        if (method_exists($this, $actionFilter)) {
            $this->$actionFilter($map);
        }

        // 自定义过滤器
        if (method_exists($this, 'filter')) {
            $this->filter($map);
        }
        $model = $this->getModel();
        $this->datalist($model, $map);

        return $this->view->fetch();
    }
    /**
     * 编辑
     * @return mixed
     */
    public function fsedit()
    {
        $controller = $this->request->controller();

        if($this->request->isPost()) {

            $id = $this->request->param("id");
            $data['title'] = $this->request->param("title");
            $data['release_time'] = $this->request->param("release_time");
            $data['schoolName'] = $this->request->param("schoolName");
            //获取选择的学区名对应的账号
            $schoolInfo = Db::name('SchoolManagement')->where('schoolName',$data['schoolName'])->where('status',1)->where('isdelete',0)->find();
            $data['schoolAccount'] = $schoolInfo['schoolAccount'];

            $data['className'] = $this->request->param("className");//班级名称
            //$data['content'] = htmlspecialchars_decode(html_entity_decode($this->request->param("content")));
            $data['label'] = $this->request->param("label");



            //获取上传的图片路径
            $path = $this->request->param('path'); //原图路径，以逗号分隔 形如    /uploads/file/20180314/3bf5a01b66d3c.jpg,/uploads/file/20180314/266f372263972.jpg
            $thumbpath = $this->request->param('thumbpath'); //缩略图路径，以逗号分隔  /uploads/file/20180314/3bf5a01b66d3c_thumb.jpg,/uploads/file/20180314/266f372263972_thumb.jpg
            //如果有值就更新
            if($path){
                $path_arr = explode(',',$path);
                $data['images'] = json_encode($path_arr); //将原图片路径转为json格式

                $thumbpath_arr = explode(',',$thumbpath);
                $data['thumbs'] = json_encode($thumbpath_arr); //同时将缩略图图片路径转为json格式
            }


            $admininfo = Db::name("AdminUser")->field('realname')->where("id", UID)->find();
            $data['release'] = $admininfo['realname'];

            Db::name("MiddleAlbum")->where('id',$id)->update($data);


            return ajax_return_adv('编辑成功');

        } else {

            $id = $this->request->param('id');
            if (!$id){
                throw new HttpException(404, "缺少参数ID");
            }
            $vo = $this->getModel($controller)->find($id);
            if (!$vo) {
                throw new HttpException(404, '该记录不存在');
            }


            //以下是根据当前登录用户找出相应的初中校区名称、班级
            $info = Db::name("AdminUser")->where("id", UID)->where('isdelete','0')->where('status',1)->find();
            $type = $info['type'];
            //若当前登录用户是超级管理员
            if($type == 0){
                //获取所有初中校区的名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where(array('schoolAccount'=>array('like','%C%')))->where('isdelete',0)->select();
                //获取所有班级名
                $className = array(); //三维数组
                foreach($schoolName as $k=>$v){
                    $classNameInfo = Db::name('ClassManagement')->field('class,className')->where('schoolName',$v['schoolName'])->where('isdelete',0)->select();
                    if($classNameInfo){
                        $className[] = $classNameInfo;
                    }

                }
                $className2 = array(); //二维数组-----如Array ( [0] => Array([class]=>小 [className]=>1)  [1]=>Array([class]=>小 [className]=>1)  [2]=>Array([class]=>大  [className]=>1) )
                foreach($className as $k2=>$v2){
                    foreach($v2 as $k3=>$v3){
                        $className2[] = $v3;
                    }
                }
                //print_r($className2);exit;
            }
            //若当前登录用户是校区
            if($type == 1){
                //获取当前校区名称-----二维数组
                $schoolName = Db::name("SchoolManagement")->field('schoolName')->where('schoolAccount',$info['account'])->where('isdelete','0')->select();

                //获取当前校区下的所有班级-----二维数组
                $className2 = Db::name('ClassManagement')->field('class,className')->where('schoolName',$info['realname'])->where('isdelete',0)->select();
            }
            //若是教师
            if($type == 2){
                $schoolID = substr($info['account'],0,5);//获取该教师所在的学区编号
                //获取当前教师所在的校区名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();

                //获取该教师所在校区下的所有班级--------二维数组
                $schoolInfo = Db::name('SchoolManagement')->where('schoolID',$schoolID)->where('isdelete',0)->find();
                $className2 = Db::name('ClassManagement')->field('class,className')->where('schoolName',$schoolInfo['schoolName'])->where('isdelete',0)->select();

            }
            //若是学生
            if($type == 3){
                $schoolID = substr($info['account'],0,5);//获取该学生所在的学区编号
                //获取当前学生所在的校区名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();

                //获取该学生所在校区下的所有班级--------二维数组
                $schoolInfo = Db::name('SchoolManagement')->where('schoolID',$schoolID)->where('isdelete',0)->find();
                $className2 = Db::name('ClassManagement')->field('class,className')->where('schoolName',$schoolInfo['schoolName'])->where('isdelete',0)->select();
            }

            $this->view->assign('schoolName',$schoolName);  //二维数组
            $this->view->assign('className2',$className2);  //二维数组
            //--------------------------------------------------------------------------------------------------------------


            $this->view->assign("vo", $vo);


            return $this->view->fetch();
        }
    }

    /**
     * 添加
     * @return mixed
     */
    public function fadd()
    {
        //$controller = $this->request->controller();

        if ($this->request->isPost()) {

            $data['title'] = $this->request->param("title");
            $data['release_time'] = $this->request->param("release_time");
            $data['schoolName'] = $this->request->param("schoolName");//获取选择的学区名
            //获取选择的学区名对应的账号
            $schoolInfo = Db::name('SchoolManagement')->where('schoolName',$data['schoolName'])->where('status',1)->where('isdelete',0)->find();
            $data['schoolAccount'] = $schoolInfo['schoolAccount'];

            $data['className'] = $this->request->param("className"); //所选择的班级名称
            //$data['content'] = htmlspecialchars_decode(html_entity_decode($this->request->param("content")));
            $data['label'] = $this->request->param("label");


            //获取图片路径
            $path = $this->request->param('path'); //原图路径，以逗号分隔 形如    /uploads/file/20180314/3bf5a01b66d3c.jpg,/uploads/file/20180314/266f372263972.jpg

            $thumbpath = $this->request->param('thumbpath'); //缩略图路径，以逗号分隔  /uploads/file/20180314/3bf5a01b66d3c_thumb.jpg,/uploads/file/20180314/266f372263972_thumb.jpg
            if(!$path){
                $data['images'] = json_encode(array('/uploads/file/033fc20efb497505073ed7ff96f84088.png')); //上传一张默认的活动相册图片
                $data['thumbs'] = json_encode(array('/uploads/file/033fc20efb497505073ed7ff96f84088.png')); //上传一张默认的活动相册缩略图图片
            }else{
                $path_arr = explode(',',$path);
                $data['images'] = json_encode($path_arr); //将图片路径转为json格式

                $thumbpath_arr = explode(',',$thumbpath);
                $data['thumbs'] = json_encode($thumbpath_arr); //将图片路径转为json格式

            }



            $admininfo = Db::name("AdminUser")->field('realname')->where("id", UID)->find();
            $data['release'] = $admininfo['realname'];

            Db::name("MiddleAlbum")->insert($data);


            return ajax_return_adv('添加成功');

        }else{


            //以下是根据当前登录用户找出相应的初中校区名称、班级
            $info = Db::name("AdminUser")->where("id", UID)->where('isdelete','0')->where('status',1)->find();
            $type = $info['type'];
            //若当前登录用户是超级管理员
            if($type == 0){
                //获取所有初中校区的名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where(array('schoolAccount'=>array('like','%C%')))->where('isdelete',0)->select();
                //获取所有班级名
                $className = array(); //三维数组
                foreach($schoolName as $k=>$v){
                    $classNameInfo = Db::name('ClassManagement')->field('class,className')->where('schoolName',$v['schoolName'])->where('isdelete',0)->select();
                    if($classNameInfo){
                        $className[] = $classNameInfo;
                    }

                }
                $className2 = array(); //二维数组-----如Array ( [0] => Array([class]=>小 [className]=>1)  [1]=>Array([class]=>小 [className]=>1)  [2]=>Array([class]=>大  [className]=>1) )
                foreach($className as $k2=>$v2){
                    foreach($v2 as $k3=>$v3){
                        $className2[] = $v3;
                    }
                }
                //print_r($className2);exit;
            }
            //若当前登录用户是校区
            if($type == 1){
                //获取当前校区名称-----二维数组
                $schoolName = Db::name("SchoolManagement")->field('schoolName')->where('schoolAccount',$info['account'])->where('isdelete','0')->select();

                //获取当前校区下的所有班级-----二维数组
                $className2 = Db::name('ClassManagement')->field('class,className')->where('schoolName',$info['realname'])->where('isdelete',0)->select();
            }
            //若是教师
            if($type == 2){
                $schoolID = substr($info['account'],0,5);//获取该教师所在的学区编号
                //获取当前教师所在的校区名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();

                //获取该教师所在校区下的所有班级--------二维数组
                $schoolInfo = Db::name('SchoolManagement')->where('schoolID',$schoolID)->where('isdelete',0)->find();
                $className2 = Db::name('ClassManagement')->field('class,className')->where('schoolName',$schoolInfo['schoolName'])->where('isdelete',0)->select();

            }
            //若是学生
            if($type == 3){
                $schoolID = substr($info['account'],0,5);//获取该学生所在的学区编号
                //获取当前学生所在的校区名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();

                //获取该学生所在校区下的所有班级--------二维数组
                $schoolInfo = Db::name('SchoolManagement')->where('schoolID',$schoolID)->where('isdelete',0)->find();
                $className2 = Db::name('ClassManagement')->field('class,className')->where('schoolName',$schoolInfo['schoolName'])->where('isdelete',0)->select();
            }

            $this->view->assign('schoolName',$schoolName);  //二维数组
            $this->view->assign('className2',$className2);  //二维数组



            $vo =['content' => ''];
            $this->view->assign("vo", $vo);


            // 添加
            return $this->view->fetch(isset($this->template) ? $this->template : 'fsedit');
        }
    }

//    public function upindex()
//    {
//
//        return $this->view->fetch();
//    }


    /**
     * 开始上传图片
     * 可一次上传多张
     */
    public function upload()
    {

//        if(session('image')!=''){
//            Session::delete('image');
//        }

        $files = request()->file("myfile");
        $img_path = '';   //字符串，存放原图路径
        $thumb_path = ''; //字符串，存放缩略图路径
        foreach($files as $file)
        {

            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads'. DS . 'file');


            $imgSrc = ROOT_PATH.'public'.DS.'uploads'.DS.'file'.DS.$info->getSaveName(); //上传成功后的路径
            $image = \think\Image::open($imgSrc);
            $array1 = explode('\\',$info->getSaveName());
            $folder = $array1[0];//获取原图所在的文件夹名称
            $array2 = explode('.',$info->getFilename());
            $suffix = $array2[1]; //原图后缀名
            $thumbname = '/uploads/file/'.$folder.'/'.$array2[0].'_thumb.'.$suffix;//含文件夹的缩略图的文件名

            $image->thumb(90)->save(ROOT_PATH.'public/'.$thumbname);//生成缩略图


            $info2 = '/uploads/file/'.str_replace('\\','/',$info->getSaveName());
            $img_path.=$info2.',';   //将多张上传图片的路径拼接在一起


            $thumb_path.=$thumbname.','; //将多张缩略图的路径拼接在一起

        }
        $imgpath = trim($img_path,',');
        $thumbpath = trim($thumb_path,',');
        echo $imgpath.'|'.$thumbpath;    //所有的原图路径用|连接对应的缩略图路径

    }




}
