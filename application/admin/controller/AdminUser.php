<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

//------------------------
// 用户控制器
//-------------------------

namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Exception;
use think\Loader;
use think\Db;
use think\Config;

class AdminUser extends Controller
{
    use \app\admin\traits\controller\Controller;

    //protected static $blacklist = ['delete', 'clear', 'deleteforever', 'recyclebin', 'recycle'];

    protected function filter(&$map)
    {
        //不查询管理员
        $map['id'] = ["gt", 1];
        
        
        if ($this->request->param('realname')) {
            $map['realname'] = ["like", "%" . $this->request->param('realname') . "%"];
        }
        if ($this->request->param('account')) {
            $map['account'] = ["like", "%" . $this->request->param('account') . "%"];
        }
        if ($this->request->param('email')) {
            $map['email'] = ["like", "%" . $this->request->param('email') . "%"];
        }
        if ($this->request->param('mobile')) {
            $map['mobile'] = ["like", "%" . $this->request->param('mobile') . "%"];
        }
        
        $info = Db::name("AdminUser")->field('type,realname,account')->where('isdelete','0')->where("id", UID)->find();
        
		if($info['type'] ==1)
		{
			$school = Db::name("SchoolManagement");
			$data = $school->field('schoolID')->where('schoolName',$info['realname'])->find();
			//$map['account']=["like", "%" . $data['schoolID']. "%"];
			$map = array('account'=>array(array('like','%'.$data['schoolID'].'%'),array('neq',$info['account'])));//查询所有的该校区下的学生和老师，除了当前校区
			
		}
		else if($info['type'] ==2)
		{
			$map['account']=["like", "%" . $info['account']. "%"];
		}
		else if($info['type'] ==3)
		{

			$map['account']=["like", "%" . $info['account']. "%"];
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
		//$model = $this->getModel();
        $this->datalist($model, $map);

        return $this->view->fetch();
    }

    /**
     * 修改密码
     */
    public function password()
    {
        $id = $this->request->param('id/d');
        if ($this->request->isPost()) {
            //禁止修改管理员的密码，管理员id为1
            if ($id < 2) {
                return ajax_return_adv_error("缺少必要参数");
            }

            $password = $this->request->post('password');
            if (!$password) {
                return ajax_return_adv_error("密码不能为空");
            }
            if (false === Loader::model('AdminUser')->updatePassword($id, $password)) {
                return ajax_return_adv_error("密码修改失败");
            }
			$info = Db::name("AdminUser")->field('type,realname')->where("id", $id)->find();
			if($info['type'] ==1)
			{
				
				//$data = Db::name("AdminUser")->field('realname')->where("id", $id)->select();
				$school = Db::name("SchoolManagement");
				$school->where("schoolName", $info['realname'])->update(['passWord' => password_hash_tp($password)]);
			}
			else if($info['type'] ==2)
			{
				//$data = Db::name("AdminUser")->field('realname')->where("id", $id)->select();
				$employee = Db::name("EmployeeManagement");
				$employee->where("schoolName", $info['realname'])->update(['passWord' => password_hash_tp($password)]);
			}
			else if($info['type'] ==3)
			{
				//$data = Db::name("AdminUser")->field('realname')->where("id", $id)->select();
				$student = Db::name("StudentManagement");
				$student->where("schoolName", $info['realname'])->update(['passWord' => password_hash_tp($password)]);
			}
            return ajax_return_adv("密码已修改为{$password}", '');
        } else {
            // 禁止修改管理员的密码，管理员 id 为 1
            if ($id < 2) {
                throw new Exception("缺少必要参数");
            }

            return $this->view->fetch();
        }
    }

    /**
     * 禁用限制
     */
    protected function beforeForbid()
    {
		
        // 禁止禁用 Admin 模块,权限设置节点
        $this->filterId(1, '该用户不能被禁用', '=');
    }
	
	
    /**
     * 添加
     * @return mixed
     */
    public function add()
    {
        $controller = $this->request->controller();

        if ($this->request->isAjax()) {
            // 插入
            $data = $this->request->except(['id']);

            // 验证
            if (class_exists($validateClass = Loader::parseClass(Config::get('app.validate_path'), 'validate', $controller))) {
                $validate = new $validateClass();
                if (!$validate->check($data)) {
                    return ajax_return_adv_error($validate->getError());
                }
            }

            // 写入数据
            if (
                class_exists($modelClass = Loader::parseClass(Config::get('app.model_path'), 'model', $this->parseCamelCase($controller)))
                || class_exists($modelClass = Loader::parseClass(Config::get('app.model_path'), 'model', $controller))
            ) {
                //使用模型写入，可以在模型中定义更高级的操作
                $model = new $modelClass();
                $ret = $model->isUpdate(false)->save($data);
            } else {
                // 简单的直接使用db写入
                Db::startTrans();
                try {
                    $model = Db::name($this->parseTable($controller));
                    $ret = $model->insert($data);
                    // 提交事务
                    Db::commit();
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();

                    return ajax_return_adv_error($e->getMessage());
                }
            }

            return ajax_return_adv('添加成功');
        } else {
            // 添加
            return $this->view->fetch(isset($this->template) ? $this->template : 'edit');
        }
    }

    /**
     * 编辑
     * @return mixed
     */
    public function edit()
    {
        $controller = $this->request->controller();

        if ($this->request->isAjax()) {
            // 更新
            $data = $this->request->post();
            if (!$data['id']) {
                return ajax_return_adv_error("缺少参数ID");
            }

            // 验证
            if (class_exists($validateClass = Loader::parseClass(Config::get('app.validate_path'), 'validate', $controller))) {
                $validate = new $validateClass();
                if (!$validate->check($data)) {
                    return ajax_return_adv_error($validate->getError());
                }
            }

            // 更新数据
            if (
                class_exists($modelClass = Loader::parseClass(Config::get('app.model_path'), 'model', $this->parseCamelCase($controller)))
                || class_exists($modelClass = Loader::parseClass(Config::get('app.model_path'), 'model', $controller))
            ) {
                // 使用模型更新，可以在模型中定义更高级的操作
                $model = new $modelClass();
                $ret = $model->isUpdate(true)->save($data, ['id' => $data['id']]);
            } else {
                // 简单的直接使用db更新
                Db::startTrans();
                try {
                    $model = Db::name($this->parseTable($controller));
                    $ret = $model->where('id', $data['id'])->update($data);
                    // 提交事务
                    Db::commit();
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();

                    return ajax_return_adv_error($e->getMessage());
                }
            }

            return ajax_return_adv("编辑成功");
        } else {
            // 编辑
            $id = $this->request->param('id');
            if (!$id) {
                throw new HttpException(404, "缺少参数ID");
            }
            $vo = $this->getModel($controller)->find($id);
            if (!$vo) {
                throw new HttpException(404, '该记录不存在');
            }

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
				$info = Db::name("AdminUser")->field('type')->where("id", $arr[$i])->find();
				if($info['type'] ==1)
				{
					$data = Db::query("select a.id from tp_school_management a,tp_admin_user b where a.schoolAccount = b.account and b.id=?",[$arr[$i]]);
					//return ajax_return_adv_error($arr);
					foreach($data as $key=>$val){
						
						$data1 = ['isdelete' => 1];
						if (false === Db::name("SchoolManagement")->where('id',$val['id'])->update($data1)) {
							return ajax_return_adv_error($model->getError());
						}
					}
					
				}
				else if($info['type'] ==2)
				{
					$data = Db::query("select a.id from tp_employee_management a,tp_admin_user b where a.account = b.account and b.id=?",[$arr[$i]]);
					//return ajax_return_adv_error($arr);
					foreach($data as $key=>$val){
						$data1 = ['isdelete' => 1];
						if (false === Db::name("EmployeeManagement")->where('id',$val['id'])->update($data1)) {
							return ajax_return_adv_error($model->getError());
						}
					}
				}
				else if($info['type'] ==3)
				{
					$data = Db::query("select a.id from tp_student_management a,tp_admin_user b where a.account = b.account and b.id=?",[$arr[$i]]);
					//return ajax_return_adv_error($arr);
					foreach($data as $key=>$val){
						
						$data1 = ['isdelete' => 1];
						if (false === Db::name("StudentManagement")->where('id',$val['id'])->update($data1)) {
							return ajax_return_adv_error($model->getError());
						}
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
				$info = Db::name("AdminUser")->field('type')->where("id", $arr[$i])->find();
				if($info['type'] ==1)
				{
					$data = Db::query("select a.id from tp_school_management a,tp_admin_user b where a.schoolAccount = b.account and b.id=?",[$arr[$i]]);
					//return ajax_return_adv_error($arr);
					foreach($data as $key=>$val){
						
						$data1 = ['isdelete' => 0];
						if (false === Db::name("SchoolManagement")->where('id',$val['id'])->update($data1)) {
							return ajax_return_adv_error($model->getError());
						}
					}
					
				}
				else if($info['type'] ==2)
				{
					$data = Db::query("select a.id from tp_employee_management a,tp_admin_user b where a.account = b.account and b.id=?",[$arr[$i]]);
					//return ajax_return_adv_error($arr);
					foreach($data as $key=>$val){
						
						$data1 = ['isdelete' => 0];
						if (false === Db::name("EmployeeManagement")->where('id',$val['id'])->update($data1)) {
							return ajax_return_adv_error($model->getError());
						}
					}
				}
				else if($info['type'] ==3)
				{
					$data = Db::query("select a.id from tp_student_management a,tp_admin_user b where a.account = b.account and b.id=?",[$arr[$i]]);
					//return ajax_return_adv_error($arr);
					foreach($data as $key=>$val){
						
						$data1 = ['isdelete' => 0];
						if (false === Db::name("StudentManagement")->where('id',$val['id'])->update($data1)) {
							return ajax_return_adv_error($model->getError());
						}
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
				$info = Db::name("AdminUser")->field('type')->where("id", $arr[$i])->find();
				if($info['type'] ==1)
				{
					$data = Db::query("select a.id from tp_school_management a,tp_admin_user b where a.schoolAccount = b.account and b.id=?",[$arr[$i]]);
					//return ajax_return_adv_error($arr);
					foreach($data as $key=>$val){
						
						$data1 = ['status' => 0];
						if (false === Db::name("SchoolManagement")->where('id',$val['id'])->update($data1)) {
							return ajax_return_adv_error($model->getError());
						}
					}
					
				}
				else if($info['type'] ==2)
				{
					$data = Db::query("select a.id from tp_employee_management a,tp_admin_user b where a.account = b.account and b.id=?",[$arr[$i]]);
					//return ajax_return_adv_error($arr);
					foreach($data as $key=>$val){
						//return ajax_return_adv_error($val['id']);
						$data1 = ['status' => 0];
						if (false === Db::name("EmployeeManagement")->where('id',$val['id'])->update($data1)) {
							return ajax_return_adv_error($model->getError());
						}
					}
				}
				else if($info['type'] ==3)
				{
					$data = Db::query("select a.id from tp_student_management a,tp_admin_user b where a.account = b.account and b.id=?",[$arr[$i]]);
					//return ajax_return_adv_error($arr);
					foreach($data as $key=>$val){
						
						$data1 = ['status' => 0];
						if (false === Db::name("StudentManagement")->where('id',$val['id'])->update($data1)) {
							return ajax_return_adv_error($model->getError());
						}
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
				$info = Db::name("AdminUser")->field('type')->where("id", $arr[$i])->find();
				if($info['type'] ==1)
				{
					$data = Db::query("select a.id from tp_school_management a,tp_admin_user b where a.schoolAccount = b.account and b.id=?",[$arr[$i]]);
					//return ajax_return_adv_error($arr);
					foreach($data as $key=>$val){
						
						$data1 = ['status' => 1];
						if (false === Db::name("SchoolManagement")->where('id',$val['id'])->update($data1)) {
							return ajax_return_adv_error($model->getError());
						}
					}
					
				}
				else if($info['type'] ==2)
				{
					$data = Db::query("select a.id from tp_employee_management a,tp_admin_user b where a.account = b.account and b.id=?",[$arr[$i]]);
					//return ajax_return_adv_error($arr);
					foreach($data as $key=>$val){
						
						$data1 = ['status' => 1];
						if (false === Db::name("EmployeeManagement")->where('id',$val['id'])->update($data1)) {
							return ajax_return_adv_error($model->getError());
						}
					}
				}
				else if($info['type'] ==3)
				{
					$data = Db::query("select a.id from tp_student_management a,tp_admin_user b where a.account = b.account and b.id=?",[$arr[$i]]);
					//return ajax_return_adv_error($arr);
					foreach($data as $key=>$val){
						
						$data1 = ['status' => 1];
						if (false === Db::name("StudentManagement")->where('id',$val['id'])->update($data1)) {
							return ajax_return_adv_error($model->getError());
						}
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
        $where[$pk] = ["in", $ids];

		$arr = explode(",",$where[$pk][1]);
		$num = count($arr);
		if($num>0)
		{
			for($i = 0; $i<$num; $i++){
				
				$info = Db::name("AdminUser")->field('type')->where("id", $arr[$i])->find();
				if($info['type'] ==1)
				{
					$data = Db::query("select a.id from tp_school_management a,tp_admin_user b where a.schoolAccount = b.account and b.id=?",[$arr[$i]]);
					//return ajax_return_adv_error($arr);
					foreach($data as $key=>$val){
						
						//$data1 = ['status' => 1];
						if (false === Db::name("SchoolManagement")->where('id',$val['id'])->delete()) {
							return ajax_return_adv_error($model->getError());
						}
					}
					
				}
				else if($info['type'] ==2)
				{
					$data = Db::query("select a.id from tp_employee_management a,tp_admin_user b where a.account = b.account and b.id=?",[$arr[$i]]);
					//return ajax_return_adv_error($arr);
					foreach($data as $key=>$val){
						
						//$data1 = ['status' => 1];
						if (false === Db::name("EmployeeManagement")->where('id',$val['id'])->delete()) {
							return ajax_return_adv_error($model->getError());
						}
					}
				}
				else if($info['type'] ==3)
				{
					$data = Db::query("select a.id from tp_student_management a,tp_admin_user b where a.account = b.account and b.id=?",[$arr[$i]]);
					//return ajax_return_adv_error($arr);
					foreach($data as $key=>$val){
						
						//$data1 = ['status' => 1];
						if (false === Db::name("StudentManagement")->where('id',$val['id'])->delete()) {
							return ajax_return_adv_error($model->getError());
						}
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
		
		
		$data = Db::query("select id from tp_school_management where isdelete = 1");
		foreach($data as $key=>$val){
			if (false === Db::name("SchoolManagement")->where('id',$val['id'])->delete()) {
				return ajax_return_adv_error($model->getError());
			}
		}
		
		$data = Db::query("select id from tp_employee_management where  isdelete = 1");
		foreach($data as $key=>$val){
			if (false === Db::name("EmployeeManagement")->where('id',$val['id'])->delete()) {
				return ajax_return_adv_error($model->getError());
			}
		}
		
		$data = Db::query("select id from tp_student_management  where  isdelete = 1");
		foreach($data as $key=>$val){
			if (false === Db::name("StudentManagement")->where('id',$val['id'])->delete()) {
				return ajax_return_adv_error($model->getError());
			}
		}
		
        if (false === $model->where($where)->delete()) {
            return ajax_return_adv_error($model->getError());
        }

        return ajax_return_adv("清空回收站成功");
    }

    /**
     * 保存排序
     */
    public function saveOrder()
    {
        $param = $this->request->param();
        if (!isset($param['sort'])) {
            return ajax_return_adv_error('缺少参数');
        }

        $model = $this->getModel();
        foreach ($param['sort'] as $id => $sort) {
            $model->where('id', $id)->update(['sort' => $sort]);
        }

        return ajax_return_adv('保存排序成功', '');
    }
	/*
	* 图形展示
	*/
	public function echarts()
	{

		//遍历数组  
		$data = "{value:335, name:'直接访问'},
				{value:310, name:'邮件营销'},
				{value:234, name:'联盟广告'},
				{value:135, name:'视频广告'},
				{value:1548, name:'搜索引擎'}";
		/*
		foreach($echartsData as $val){
			$data = $data."{";
			foreach(json_decode($val, true) as $key => $val1){  
				if($key == "type")
				{
					$data = $data."name:'".$val1."'";
					$data = $data.",";
				}
				if($key == "money")
					$data = $data."value:'".$val1."'";
			}
			$data = $data."}";
			$data = $data.",";
		}
		*/
		$this->view->assign('data',$data);
	
        return $this->view->fetch();
	}
}