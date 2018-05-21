<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Exception;
use think\Loader;
use think\Db;
use think\Config;
use app\admin\controller\Alipay;

//公司信息
class SchoolManagement extends Controller
{
    use \app\admin\traits\controller\Controller;
    // 方法黑名单
    protected static $blacklist = [];

    protected function filter(&$map)
    {
        if ($this->request->param("schoolName")) {
            $map['schoolName'] = ["like", "%" . $this->request->param("schoolName") . "%"];
        }
        if ($this->request->param("schoolID")) {
            $map['schoolID'] = ["like", "%" . $this->request->param("schoolID") . "%"];
        }
        if ($this->request->param("schoolAccount")) {
            $map['schoolAccount'] = ["like", "%" . $this->request->param("schoolAccount") . "%"];
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
			$schoolID = substr($account,0,5);//截取当前用户账号的前5位---即公司编号 ,如C0398
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


        //-----------------------------------------------------------------------------------------------------------
		//用于表单-----查询相应公司名称
		$info = Db::name("AdminUser")->where("id", UID)->find();
		//若是管理员
		if($info['type'] == 0){
			$all_companyName = Db::name('SchoolManagement')->where(array('schoolName'=>array('neq','')))->field('schoolName')->where('isdelete',0)->select();
		}
		//若是公司账号登录
		if($info['type'] == 1){
			$all_companyName = Db::name('SchoolManagement')->where('schoolAccount',$info['account'])->field('schoolName')->where('isdelete',0)->select();
		}
		//若是公司员工
		if($info['type'] == 2){
            $schoolID = substr($info['account'],0,5);
			$all_companyName = Db::name('SchoolManagement')->where(array('schoolAccount'=>array('like','%'.$schoolID.'%')))->field('schoolName')->where('isdelete',0)->select();
		}
		//print_r($all_companyName);exit;
		$this->view->assign('all_companyName',$all_companyName);
        //----------------------------------------------------------------------------------------------------------


		//--

		//--

        return $this->view->fetch();
    }


	//1
	// public function guarantee(){
	   	// return $this->view->fetch();
	// }
	// //2
	// public function pay(){
	   	// return $this->view->fetch();
	// }
	// //3.完成
	// public function finish(){
	   	// return $this->view->fetch();
	// }
		//1
	public function guarantee(){
	   	return $this->view->fetch();
	}
	//2
	public function pay(){
		
        return $this->view->fetch();
	}
	//3.完成
	public function finish(){

	   	return $this->view->fetch();
	}

	public function alipay()
	{	
		$alipay = new Alipay();
		$out_trade_no = date('YmdHis').rand(100000, 999999);//订单号
		$subject = "派值岗位薪资";
		$total_amount = 2232;
		$alipay->pagepay($out_trade_no,$subject,$total_amount);
	}
	/**
	* 创建编号
	* @param Int $id 自增id
	* @param Int $num_length 数字最大位数
	* @param String $prefix 前缀
	* @return String
	*/
	public static function create($id, $num_length, $prefix){

		// 基数
		$base = pow(10, $num_length);

		// 生成字母部分
		$division = (int)($id/$base);
		$word = '';

		while($division){
		$tmp = fmod($division, 26); // 只使用26个大写字母
		$tmp = chr($tmp + 65); // 转为字母
		$word .= $tmp;
		$division = floor($division/26);
		}

		if($word==''){
		$word = chr(65);
		}

		// 生成数字部分
		$mod = $id % $base;
		$digital = str_pad($mod, $num_length, 0, STR_PAD_LEFT);

		$code = sprintf('%s%s%s', $prefix, $word, $digital);
		return $code;

	}
	
	
	/**
     * 添加
     * @return mixed
     */
//    public function add()
//    {
//        $controller = $this->request->controller();
//
//        if ($this->request->isAjax()) {
//            // 插入
//            $data = $this->request->except(['id']);
//
//            // 验证
//            if (class_exists($validateClass = Loader::parseClass(Config::get('app.validate_path'), 'validate', $controller))) {
//                $validate = new $validateClass();
//                if (!$validate->check($data)) {
//                    return ajax_return_adv_error($validate->getError());
//                }
//            }
//
//            // 写入数据
//            if (
//                class_exists($modelClass = Loader::parseClass(Config::get('app.model_path'), 'model', $this->parseCamelCase($controller)))
//                || class_exists($modelClass = Loader::parseClass(Config::get('app.model_path'), 'model', $controller))
//            ) {
//                //使用模型写入，可以在模型中定义更高级的操作
//                $model = new $modelClass();
//                $ret = $model->isUpdate(false)->save($data);
//				$user = Db::name("AdminUser");
//				$data1 = ['account' => $data['schoolAccount'], 'realname' => $data['schoolName'], 'password' => password_hash_tp($data['passWord']), 'type' => 1,'mobile' => $data['number'],'status' => 1];
//				$ret = $user->isUpdate(false)->save($data1);
//				$Id = $user->getLastInsID();
//				$db_role_user = Db::name("AdminRoleUser");
//				$data2 = ['role_id' => 1, 'user_id' => $Id];
//				$ret = $db_role_user->isUpdate(false)->save($data2);
//            } else {
//                // 简单的直接使用db写入
//                Db::startTrans();
//                try {
//
//                    $model = Db::name($this->parseTable($controller));
//                    $ret = $model->insert($data);
//					$user = Db::name("AdminUser");
//					$data1 = ['account' => $data['schoolAccount'], 'realname' => $data['schoolName'], 'password' => password_hash_tp($data['passWord']), 'type' => 1,'mobile' => $data['number'],'status' => 1];
//					$ret = $user->insert($data1);
//					$Id = $user->getLastInsID();
//					$db_role_user = Db::name("AdminRoleUser");
//					$data2 = ['role_id' => 1, 'user_id' => $Id];
//					$ret = $db_role_user->insert($data2);
//                    // 提交事务
//                    Db::commit();
//                } catch (\Exception $e) {
//                    // 回滚事务
//                    Db::rollback();
//
//                    return ajax_return_adv_error($e->getMessage());
//                }
//            }
//
//            return ajax_return_adv('添加成功');
//        }else {
//			$model = Db::name($this->parseTable($controller));
//			$id = $model->max('id')+1;
//			$ID = $this->create($id, 1, '');
//			$this->view->assign("ID", $ID);
//
//			$schoolType = Db::name('SchoolType')->where('status',1)->where('isdelete',0)->select();
//			$this->view->assign('schoolType',$schoolType);
//			$this->view->assign('type','');
//
//			// 添加
//            return $this->view->fetch(isset($this->template) ? $this->template : 'edit');
//        }
//    }
	
	/**
     * 编辑
     * @return mixed
     */
    public function fsedit()
    {
        $controller = $this->request->controller();
		
        if ($this->request->isAjax()) {
            // 更新
			$id = $this->request->param('id');

            $data['number'] = $this->request->param('number'); //注册电话
			$data['regNumber'] = $this->request->param('regNumber');//注册号或统一社会信用代码
            $data['schoolName'] = $this->request->param('schoolName'); //公司名称
			$data['corporation'] = $this->request->param('corporation');//法人
			$data['one_level'] = $this->request->param('province'); //省
			$data['two_level'] = $this->request->param('city');//市
			$data['address'] = $this->request->param('address'); //公司详细地址
			$data['tel'] = $this->request->param('tel');//联系电话
			$data['business'] = $this->request->param('business'); //主营业务
			$data['introduce'] = $this->request->param('introduce');//公司简介

			Db::name('SchoolManagement')->where('id',$id)->update($data);


            return ajax_return_adv("编辑成功");
        } else {
            // 编辑
            $id = $this->request->param('id');
            if (!$id) {
                throw new HttpException(404, "缺少参数ID");
            }
            $vo = $this->getModel($controller)->find($id);


			$this->view->assign("vo", $vo);


			return $this->view->fetch();
        }
    }
	
	/**
     * 默认删除操作
     */
    public function delete()
    {
		
		$model = $this->getModel();
        $pk = $model->getPk();
		$ids = $this->request->param($pk);
		$where[$pk] = ["in", $ids];
		$arr = explode(",",$where[$pk][1]);
		$num = count($arr);
		if($num>0)
		{
			for($i = 0; $i<$num; $i++){
				$data = Db::query("select b.id from tp_school_management a,tp_admin_user b where a.schoolAccount = b.account and a.id=?",[$arr[$i]]);
				//return ajax_return_adv_error($arr);
				foreach($data as $key=>$val){
					
					$data1 = ['isdelete' => 1];
					if (false === Db::name("AdminUser")->where('id',$val['id'])->update($data1)) {
						return ajax_return_adv_error($model->getError());
					}
				}
			}
		}
        return $this->updateField($this->fieldIsDelete, 1, "移动到回收站成功");
		
    }

    /**
     * 从回收站恢复
     */
    public function recycle()
    {
		$model = $this->getModel();
        $pk = $model->getPk();
		$ids = $this->request->param($pk);
		$where[$pk] = ["in", $ids];
		$arr = explode(",",$where[$pk][1]);
		$num = count($arr);
		if($num>0)
		{
			for($i = 0; $i<$num; $i++){
				$data = Db::query("select b.id from tp_school_management a,tp_admin_user b where a.schoolAccount = b.account and a.id=?",[$arr[$i]]);
				//return ajax_return_adv_error($arr);
				foreach($data as $key=>$val){
					
					$data1 = ['isdelete' => 0];
					if (false === Db::name("AdminUser")->where('id',$val['id'])->update($data1)) {
						return ajax_return_adv_error($model->getError());
					}
				}
			}
		}
        return $this->updateField($this->fieldIsDelete, 0, "恢复成功");
    }

    /**
     * 默认禁用操作
     */
    public function forbid()
    {
		$model = $this->getModel();
        $pk = $model->getPk();
		$ids = $this->request->param($pk);
		$where[$pk] = ["in", $ids];
		$arr = explode(",",$where[$pk][1]);
		$num = count($arr);
		if($num>0)
		{
			for($i = 0; $i<$num; $i++){
				$data = Db::query("select b.id from tp_school_management a,tp_admin_user b where a.schoolAccount = b.account and a.id=?",[$arr[$i]]);
				//return ajax_return_adv_error($arr);
				foreach($data as $key=>$val){
					//return ajax_return_adv_error($val['id']);
					$data1 = ['status' => 0];
					if (false === Db::name("AdminUser")->where('id',$val['id'])->update($data1)) {
						return ajax_return_adv_error($model->getError());
					}
				}
			}
		}
        return $this->updateField($this->fieldStatus, 0, "禁用成功");
    }


    /**
     * 默认恢复操作
     */
    public function resume()
    {
		$model = $this->getModel();
        $pk = $model->getPk();
		$ids = $this->request->param($pk);

		$where[$pk] = ["in", $ids];
		$arr = explode(",",$where[$pk][1]);
		$num = count($arr);
		if($num>0)
		{
			for($i = 0; $i<$num; $i++){
				$data = Db::query("select b.id from tp_school_management a,tp_admin_user b where a.schoolAccount = b.account and a.id=?",[$arr[$i]]);
				//return ajax_return_adv_error($arr);
				foreach($data as $key=>$val){
					$data1 = ['status' => 1];
					if (false === Db::name("AdminUser")->where('id',$val['id'])->update($data1)) {
						return ajax_return_adv_error($model->getError());
					}
				}
			}
		}
        return $this->updateField($this->fieldStatus, 1, "恢复成功");
    }
	
	/**
     * 永久删除
     */
    public function deleteForever()
    {
        $model = $this->getModel();
        $pk = $model->getPk();
        $ids = $this->request->param($pk);
		
		//return ajax_return_adv_error($ids);
        
		$where[$pk] = ["in", $ids];
		$arr = explode(",",$where[$pk][1]);
		$num = count($arr);
		if($num>0)
		{
			for($i = 0; $i<$num; $i++){
				$data = Db::query("select b.id from tp_school_management a,tp_admin_user b where a.schoolAccount = b.account and a.id=?",[$arr[$i]]);
				//return ajax_return_adv_error($arr);
				foreach($data as $key=>$val){
					if (false === Db::name("AdminRoleUser")->where('user_id',$val['id'])->delete()) {
						return ajax_return_adv_error($model->getError());
					}
					if (false === Db::name("AdminUser")->delete($val)) {
						return ajax_return_adv_error($model->getError());
					}
					
				}
				
			}
		}
		if (false === $model->where($where)->delete()) {
            return ajax_return_adv_error($model->getError());
        }
		
        return ajax_return_adv("删除成功");
    }

    /**
     * 清空回收站
     */
    public function clear()
    {
        
		$model = $this->getModel();
		$where[$this->fieldIsDelete] = 1;
		
		
		$data = Db::query("select b.id from tp_school_management a,tp_admin_user b where a.schoolAccount = b.account and a.isdelete = 1");
		foreach($data as $key=>$val){
			if (false === Db::name("AdminRoleUser")->where('user_id',$val['id'])->delete()) {
				return ajax_return_adv_error($model->getError());
			}
			//return ajax_return_adv_error($val['id']);
			if (false === Db::name("AdminUser")->where('id',$val['id'])->delete()) {
				return ajax_return_adv_error($model->getError());
			}
			
		}
		if (false === $model->where($where)->delete()) {
			return ajax_return_adv_error($model->getError());
		}
        return ajax_return_adv("清空回收站成功");
    }
	
	 /**
     * 利用ajax获取传id
     * 根据当前录用情况，实现实名与未实名状态的切换
     */
    public function isRealName()
    {
         if($this->request->param('id')!=''){
             $id = $this->request->param('id'); //当前报名信息id
             $enrollInfo = Db::name('SchoolManagement')->where('id',$id)->find();
             if($enrollInfo['isRealName'] == 0){
                 $data = array(
                     'isRealName' => 1
                 );
                 $bool = Db::name('SchoolManagement')->where('id',$id)->update($data);
                 if($bool){
                     echo '1';
                 }
             }
             if($enrollInfo['isRealName'] == 1){
                 $data = array(
                     'isRealName' => 0
                 );
                 $bool = Db::name('SchoolManagement')->where('id',$id)->update($data);
                 if($bool){
                     echo '0';
                 }
             }
         }

    }
	
	/**
     * 利用ajax获取传id
     * 根据当前录用情况，实现实名与未实名状态的切换
     */
    public function isGuarantee()
    {
         if($this->request->param('id')!=''){
             $id = $this->request->param('id'); //当前报名信息id
             $enrollInfo = Db::name('SchoolManagement')->where('id',$id)->find();
             if($enrollInfo['isGuarantee'] == 0){
                 $data = array(
                     'isGuarantee' => 1
                 );
                 $bool = Db::name('SchoolManagement')->where('id',$id)->update($data);
                 if($bool){
                     echo '1';
                 }
             }
             if($enrollInfo['isGuarantee'] == 1){
                 $data = array(
                     'isGuarantee' => 0
                 );
                 $bool = Db::name('SchoolManagement')->where('id',$id)->update($data);
                 if($bool){
                     echo '0';
                 }
             }
         }

    }
	
}
