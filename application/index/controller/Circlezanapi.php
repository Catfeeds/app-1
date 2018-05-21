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

//点赞以及取消赞接口
class Circlezanapi extends Controller{

    public function index()
    {

        //判断传过来的动态id以及点赞者账号是否为空
        if($this->request->isGet() && $this->request->param('id')!='' && $this->request->param('phone_account')!=''){

            $data['ids'] = $this->request->param('id');//获取传过来的动态id
            $data['phone_account'] = $this->request->param('phone_account');//获取点赞者账号
            $data['zan_time'] = date('Y-m-d H:i:s',time());//点赞时间

            $model = Db::name('CircleZan');
            $info = $model->where('ids',$data['ids'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->find();//从tp_circle_zan表中判断当前用户对当前动态是否点赞过
            // $info2 = $model->where('rid',$data['rid'])->where('account',$data['account'])->where('status',1)->where('isdelete',0)->where('iszan',1)->find();
            if(!$info){
                $data['iszan'] = 1;
                $model->insert($data);
                echo $this->json(0,'点赞成功');
            }elseif($info['iszan'] == 1){
                $model->where('ids',$data['ids'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['iszan'=>0]);

                echo $this->json(1,'取消赞成功');

            }elseif($info['iszan'] == 0){

                $model->where('ids',$data['ids'])->where('phone_account',$data['phone_account'])->where('status',1)->where('isdelete',0)->update(['iszan'=>1]);
                echo $this->json(0,'点赞成功');
            }
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