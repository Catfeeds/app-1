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

//圈子
class Circleapi extends Controller
{
    //圈子动态首页
    public function index()
    {
        //账号可传可不传，，但板块类型type、page、num必须传
     

            $phone_account = $this->request->param('phone_account');

            $page = $this->request->param('page'); //共分几页
            $page = isset($page)?$page-1:0;
            $num = $this->request->param('num'); //每页显示的数据量
            $num = isset($num)?$num:0;
            $page = $page*$num;


            if($this->request->param('type')!=''){
                $type = $this->request->param('type');

                //显示全部板块
                if($type == 0)
                {
                    //轮播图
                    $banner = Db::name('CircleBanner')->where('status',1)->where('isdelete',0)->select();

                    $data = Db::name('CircleDynamic')->where('status',1)->where('isdelete',0)->order('release_time desc')->limit($page,$num)->select();//从tp_circle_dynamic表中查询教师发表的所有的动态

                    $all = array();
                    foreach($data as $key=>$values){

                        $employee_id = $values['employee_id']; //获取员工的id
                        $headurl_array = Db::name('EmployeeManagement')->where('id',$employee_id)->where('status',1)->where('isdelete',0)->find();
                        $name = $headurl_array['name'];//教师姓名
                        $employee_headurl = $headurl_array['employee_headurl'];//教师头像


                        //板块logo
                        $logo_array = Db::name('PlateLogo')->where('type',$values['type'])->where('status',1)->where('isdelete',0)->find();
                        $logo = $logo_array['logo'];//板块logo
                        $plate_name = $logo_array['name'];//板块名称

                        //赞的总数
                        $zan_num = count(Db::name('CircleZan')->where('ids',$values['id'])->where('iszan',1)->where('status',1)->where('isdelete',0)->select());

                        //评论的总数
                        $comment_num = count(Db::name('CircleComment')->where('rid',$values['id'])->where('status',1)->where('isdelete',0)->select());


                        $info = Db::name('CircleZan')->where('ids',$values['id'])->where('phone_account',$phone_account)->where('iszan',1)->where('status',1)->where('isdelete',0)->find();
                        if(!$info){
                            $iszan = 0;
                        }else{
                            $iszan = 1;
                        }

                        $infos = array('teacher_name'=>$name,'employee_headurl'=>$employee_headurl,'schoollogo'=>$logo,'plate_name'=>$plate_name,'zan_num'=>$zan_num,'comment_num'=>$comment_num,'iszan'=>$iszan);


                        $arr = array_merge($values,$infos);
                        $all[] = $arr;
                    }

                    $all_data['info'] = array();
                    $all_data['banner'] =  $banner;
                    $all_data['dynamic'] =  $all;

                    return $this->json(0,'ok',$all_data);

                }else{
                    //查询相应的板块
                    $data = Db::name('CircleDynamic')->where('type',$type)->where('status',1)->where('isdelete',0)->order('release_time desc')->limit($page,$num)->select();//从tp_circle_dynamic表中查询教师发表的所有的动态

                    if(!$data){
                        echo $this->json(2,'该板块不存在');
                    }else{
                        $all = array();
                        foreach($data as $key=>$values){

                            $employee_id = $values['employee_id']; //获取员工的id
                            $headurl_array = Db::name('EmployeeManagement')->where('id',$employee_id)->where('status',1)->where('isdelete',0)->find();
                            $name = $headurl_array['name'];//教师姓名
                            $employee_headurl = $headurl_array['employee_headurl'];//教师头像


                            //板块logo
                            $logo_array = Db::name('PlateLogo')->where('type',$values['type'])->where('status',1)->where('isdelete',0)->find();
                            $logo = $logo_array['logo'];//板块logo
                            $plate_name = $logo_array['name'];//板块名称

                            //赞的总数
                            $zan_num = count(Db::name('CircleZan')->where('ids',$values['id'])->where('iszan',1)->where('status',1)->where('isdelete',0)->select());

                            //评论的总数
                            $comment_num = count(Db::name('CircleComment')->where('rid',$values['id'])->where('status',1)->where('isdelete',0)->select());


                            $info = Db::name('CircleZan')->where('ids',$values['id'])->where('phone_account',$phone_account)->where('iszan',1)->where('status',1)->where('isdelete',0)->find();
                            if(!$info){
                                $iszan = 0;
                            }else{
                                $iszan = 1;
                            }

                            $infos = array('teacher_name'=>$name,'employee_headurl'=>$employee_headurl,'schoollogo'=>$logo,'plate_name'=>$plate_name,'zan_num'=>$zan_num,'comment_num'=>$comment_num,'iszan'=>$iszan);


                            $arr = array_merge($values,$infos);
                            $all[] = $arr;
                        }
                        $all_data['info'] = array();
                        $all_data['dynamic'] =  $all;

                        echo $this->json(0,'ok',$all_data);
                    }
                }

            }else{
                echo $this->json(3,'非法请求');
            }

     
    }




    //获取所有板块logo接口
    public function getLogo()
    {
        if($this->request->isGet()){
            $plate_logo = Db::name('PlateLogo')->where('status',1)->where('isdelete',0)->select();
            echo $this->json(0,'ok',$plate_logo);
        }else{
            echo $this->json(3,'非法请求');
        }
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
