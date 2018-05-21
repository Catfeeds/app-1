<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Exception;
use think\Loader;
use think\Db;
use think\Config;

class EmployeeAccessManagement extends Controller
{
    use \app\admin\traits\controller\Controller;
    // 方法黑名单
    protected static $blacklist = [];

    protected function filter(&$map)
    {
        if ($this->request->param("name")) {
            $map['name'] = ["like", "%" . $this->request->param("name") . "%"];
        }
        if ($this->request->param("cardID")) {
            $map['cardID'] = ["like", "%" . $this->request->param("cardID") . "%"];
        }
        if ($this->request->param("postName")) {
            $map['postName'] = ["like", "%" . $this->request->param("postName") . "%"];
        }
        if ($this->request->param("iphone")) {
            $map['iphone'] = ["like", "%" . $this->request->param("iphone") . "%"];
        }

		$info = Db::name("AdminUser")->field('type,realname,account')->where('isdelete','0')->where("id", UID)->find();
		if($info['type'] ==1)
		{
			//$school = Db::name("SchoolManagement");
			//$data = $school->field('schoolName')->where('schoolName',$info['realname'])->find();
			$map['schoolName']=["like", "%" . $info['realname']. "%"];
		}
		else if($info['type'] ==2)
		{

			$data = Db::name("EmployeeManagement")->field('account')->where('isdelete','0')->where('account',$info['account'])->find();
			$map['account']=["like", "%" . $data['account']. "%"];
		}
		else if($info['type'] ==3)
		{

			//$data = Db::name("StudentManagement")->field('schoolName')->where('account',$info['account'])->find();
			//$map['schoolName']=["like", "%" . $data['schoolName']. "%"];
			$map['account']=["like", "%" . $info['account']. "%"];
		}
    }
	
	/**
     * 首页
     * @return mixed
     */
    public function index()
    {
        $model = Db::name("EmployeeManagement");

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
		$model = Db::name("EmployeeManagement");
		$data = $this->datalist($model, $map,'','','','true');
        $list = array();
     //   $list2 = array();
        foreach ($data as $key =>$value){
           // $studentLinkman = Db::name("EmployeeManagement")->where('isdelete','0')->where('student_id',$value['id'])->order('id','asc')->limit(1)->find();//查找联系人
			$EmployeeAccessManagement = Db::name("EmployeeAccessManagement")->where('isdelete','0')->where('employee_id',$value['id'])->find();//联系人门禁
			$image =  Db::name("File")->where('id',$EmployeeAccessManagement['face_file_ids'])->find();   //联系人门禁照片
            

            $list[] = array(
                    'id' => $value['id'],
                    'schoolName' => $value['schoolName'],
                    'divisionName' => $value['divisionName'],
                    'account' => $value['account'],
                    'name' => $value['name'],
                    'sex' => $value['sex'],
                    'className' => $value['className'],
                    'iphone' => $value['iphone'],
                    'image' => $image ['name'],
                    'postName' => $value['postName']
	            ); 
        }
		$this->view->assign('list', $list);
       // $this->datalist($model, $map);
		$info = Db::name("AdminUser")->field('type,realname,account')->where('isdelete','0')->where("id", UID)->find();
		if($info['type'] ==0)
		{
			$model = Db::name("EmployeeManagement");
			$schoolName = $model->field('schoolName')->Distinct(true)->where('isdelete','0')->select();
			$divisionName = $model->field('divisionName')->Distinct(true)->where('isdelete','0')->select();
			$className = $model->field('className')->Distinct(true)->where('isdelete','0')->select();
			$this->view->assign("schoolName", $schoolName);
			$this->view->assign("divisionName", $divisionName);
			$this->view->assign("className", $className);
		}
		else if($info['type'] ==1)
		{
			$model = Db::name("EmployeeManagement");
			$schoolName = $model->field('schoolName')->Distinct(true)->where('isdelete','0')->where('schoolName',$info['realname'])->select();
			$divisionName = $model->field('divisionName')->Distinct(true)->where('isdelete','0')->where('schoolName',$info['realname'])->select();
			$className = $model->field('className')->Distinct(true)->where('isdelete','0')->where('schoolName',$info['realname'])->select();
			$this->view->assign("schoolName", $schoolName);
			$this->view->assign("divisionName", $divisionName);
			$this->view->assign("className", $className);
		}
		else if($info['type'] ==2)
		{
			$model = Db::name("EmployeeManagement");
			$schoolName = $model->field('schoolName')->Distinct(true)->where('isdelete','0')->where('account',$info['account'])->select();
			$divisionName = $model->field('divisionName')->Distinct(true)->where('isdelete','0')->where('account',$info['account'])->select();
			$className = $model->field('className')->Distinct(true)->where('isdelete','0')->where('account',$info['account'])->select();
			$this->view->assign("schoolName", $schoolName);
			$this->view->assign("divisionName", $divisionName);
			$this->view->assign("className", $className);
		}
		else if($info['type'] ==3)
		{
			$model = Db::name("EmployeeManagement");
			$schoolName = $model->field('schoolName')->Distinct(true)->where('isdelete','0')->where('account',$info['account'])->select();
			$divisionName = $model->field('divisionName')->Distinct(true)->where('isdelete','0')->where('account',$info['account'])->select();
			$className = $model->field('className')->Distinct(true)->where('isdelete','0')->where('account',$info['account'])->select();
			$this->view->assign("schoolName", $schoolName);
			$this->view->assign("divisionName", $divisionName);
			$this->view->assign("className", $className);
		}
		
        return $this->view->fetch();
    }
	
	public function access()
	{
		if ($this->request->isAjax()) {
            // 更新
            $data = $this->request->post();
            if (!$data['id']) {
                return ajax_return_adv_error("缺少参数ID");
            }

            

            // 更新数据
           
			Db::startTrans();
			try {
				$data1 = Db::name("EmployeeAccessManagement")->field('employee_id')->where('isdelete','0')->where("id",$data['id'])->find();
				
				$vo2 = Db::name("EmployeeLinkman")->field('id,name,relation')->where('isdelete','0')
				->where('employee_id',$data1['employee_id'])->select();
				$count = Db::name("EmployeeLinkman")->where('isdelete','0')->where("employee_id",$data1['employee_id'])->count();
				
				
				// for($i=0;$i<$count;$i++)
				// {
					
    // 				$data2=['access'=>$data['lAccess_'.$vo2[$i]['id']]];
    				
    // 				$ret = Db::name("EmployeeLinkmanAccessManagement")
    // 				->where('employeeLinkman_id', $vo2[$i]['id'])->update($data2);
				// }
				
				//return ajax_return_adv_error($str);
				//$data3=['access'=>$data['access']];
				
				$ret = Db::name("EmployeeAccessManagement")->where('id', $data['id'])->update(['car_file_ids'=>$data['carCard'],'remark'=>$data['remark']]);
				
				
				
				// 提交事务
				Db::commit();
			} catch (\Exception $e) {
				// 回滚事务
				Db::rollback();

				return ajax_return_adv_error($e->getMessage());
			}
            

            return ajax_return_adv("编辑成功");
        } else {
            // 编辑
            $id = $this->request->param('id');
            if (!$id) {
                throw new HttpException(404, "缺少参数ID");
            }
		
			$data = Db::name("EmployeeManagement")->field('id')->where('isdelete','0')->find($id);
			
			$count = Db::name("EmployeeAccessManagement")->where('isdelete','0')->where("employee_id",$data['id'])->count();
			if($count==0)
			{
			    $data1 = ['employee_id'=>$data['id'],'access'=>'1','isAllUpload'=>0];
				$ret = Db::name("EmployeeAccessManagement")->insert($data1);
			}
			$vo = Db::name("EmployeeAccessManagement")->where('isdelete','0')->where("employee_id",$id)->find();
			$info = Db::name("file")->where('id',$vo['face_file_ids'])->find();
			$vo1 = Db::name("EmployeeManagement")->where('isdelete','0')->where("id",$id)->find();
			$vo1['image'] = $info['name'];
			$vo1['access'] = $vo['access'];
			$vo1['carCard'] = $vo['car_file_ids'];
			// $vo3 = Db::name("EmployeeLinkman")->field('id,name,relation')->where('isdelete','0')->where("employee_id",$id)->select();
			
			
			// for($i=0;$i<count($vo3);$i++)
			// {
			//     $count = Db::name("EmployeeLinkmanAccessManagement")->where('isdelete','0')->where("employeeLinkman_id",$vo3[$i]['id'])->count();
			//     $data2 = ['employeeLinkman_id'=>$vo3[$i]['id'],'access'=>'0'];
			//     if($count==0)
   //  			{
   //  				$ret = Db::name("EmployeeLinkmanAccessManagement")->insert($data2);
   //  			}
			// }
			$vo2 = [];
			// $vo2 = Db::table('tp_employee_linkman a, tp_employee_linkman_access_management b')
			// ->field('a.id as id,a.name as name,a.relation as relation,b.access as access')
			// ->where('a.id=b.employeeLinkman_id and a.employee_id='.$id)->select();
			$vo2js = json_encode($vo2);
			if (!$vo&&!vo1) {
				throw new HttpException(404, '该记录不存在');
			}
			$this->view->assign("vo", $vo);
            $this->view->assign("vo1", $vo1);
            $this->view->assign("vo2", $vo2);
			$this->view->assign("vo2js", $vo2js);
            return $this->view->fetch();
        }
	}

		//启用
	 
	public function start()
    {
        if($this->request->isPost() && $this->request->param('id')!=''){
            $id = $this->request->param('id');//获取此联系人id
            Db::name('EmployeeManagement')->where('id',$id)->where('status',1)->where('isdelete',0)->update(['ismodifyData'=>1]);
            $data = array('access'=>1);
            $bool = Db::name('EmployeeAccessManagement')->where('employee_id',$id)->where('status',1)->where('isdelete',0)->update($data);
            //echo Db::getLastsql();
            // if($bool){
            //     echo 'ok';
            // }
        }
    }

    //禁用
    public function forbidden()
    {
             
        if($this->request->isPost() && $this->request->param('id')!=''){
            $id = $this->request->param('id');//获取此联系人id
           // exit($id );
            Db::name('EmployeeManagement')->where('id',$id)->where('status',1)->where('isdelete',0)->update(['ismodifyData'=>1]);
            $data = array('access'=>0);
            $bool = Db::name('EmployeeAccessManagement')->where('employee_id',$id)->where('status',1)->where('isdelete',0)->update($data);
           
            // if($bool){
            //     echo 'ok';
            // }
        }
    }
}
