<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Exception;
use think\Loader;
use think\Db;
use think\Config;
use think\Session;


class StudentAccessManagement extends Controller
{
    use \app\admin\traits\controller\Controller;
    // 方法黑名单
    protected static $blacklist = [];

	protected function filter(&$map)
    {
        if ($this->request->param("account")) {
            $map['account'] = ["like", "%" . $this->request->param("account") . "%"];
        }
        if ($this->request->param("name")) {
            $map['name'] = ["like", "%" . $this->request->param("name") . "%"];
        }
        if ($this->request->param("parentName")) {
            $map['parentName'] = ["like", "%" . $this->request->param("parentName") . "%"];
        }
        if ($this->request->param("contact")) {
            $map['contact'] = ["like", "%" . $this->request->param("contact") . "%"];
        }
		$info = Db::name("AdminUser")->field('type,realname,account')->where('isdelete','0')->where("id", UID)->find();
		if($info['type'] ==1)
		{
			//$school = Db::name("SchoolManagement");
			//$data = $school->field('schoolName')->where('schoolName',$info1['realname'])->find();
			$map['schoolName']=["like", "%" . $info['realname']. "%"];
		}
		else if($info['type'] ==2)
		{
			$data = Db::name("EmployeeManagement")->where('isdelete','0')->field('schoolName,className')->where('account',$info['account'])->find();
			$map['schoolName']=["like", "%" . $data['schoolName']. "%"];
			$map['className']=["like", "%" . $data['className']. "%"];
		}
		else if($info['type'] ==3)
		{
			//$data = Db::name("StudentManagement")->field('schoolName')->where('account',$info1['account'])->find();
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
        $model = Db::name("StudentManagement");
        
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
		$model = Db::name("StudentManagement");
        $data = $this->datalist($model, $map,'','','','true');
        $list = array();
     //   $list2 = array();
        foreach ($data as $key =>$value){
            $studentLinkman = Db::name("StudentLinkman")->where('isdelete','0')->where('student_id',$value['id'])->order('id','asc')->limit(1)->find();//查找联系人
			$StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$studentLinkman['id'])->find();//联系人门禁
			$image =  Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
            

            $list[] = array(
                    'id' => $value['id'],
                    'schoolName' => $value['schoolName'],
                    'className' => $value['className'],
                    'account' => $value['account'],
                    'name' => $value['name'],
                    'sex' => $value['sex'],
                    'birthDate' => $value['birthDate'],
                    'contact' => $value['contact'],
                    'image' => $image ['name'],
                    'relation' => $studentLinkman['relation']
	            ); 
        }

        
        $this->view->assign('list', $list);


		$info = Db::name("AdminUser")->field('type,realname,account')->where('isdelete','0')->where("id", UID)->find();
		if($info['type'] ==0)
		{
			$model = Db::name("StudentManagement");
			$schoolName = $model->field('schoolName')->Distinct(true)->where('isdelete','0')->select();
			$className = $model->field('className')->Distinct(true)->where('isdelete','0')->select();
			$this->view->assign("schoolName", $schoolName);
			$this->view->assign("className", $className);
		}
		else if($info['type'] ==1)
		{
			$model = Db::name("StudentManagement");
			$schoolName = $model->field('schoolName')->Distinct(true)->where('isdelete','0')->where('schoolName',$info['realname'])->select();
			$className = $model->field('className')->Distinct(true)->where('isdelete','0')->where('schoolName',$info['realname'])->select();
			$this->view->assign("schoolName", $schoolName);
			$this->view->assign("className", $className);
		}
		else if($info['type'] ==2)
		{
			$model = Db::name("StudentManagement");
			$data = Db::name("EmployeeManagement")->field('schoolName,className')->where('isdelete','0')->where('account',$info['account'])->find();
			$schoolName = $model->field('schoolName')->Distinct(true)->where('isdelete','0')->where('schoolName',$data['schoolName'])
			->where('className',$data['className'])->select();
			$className = $model->field('className')->Distinct(true)->where('isdelete','0')->where('schoolName',$data['schoolName'])
			->where('className',$data['className'])->select();
			$this->view->assign("schoolName", $schoolName);
			$this->view->assign("className", $className);
		}
		else if($info['type'] ==3)
		{
			$model = Db::name("StudentManagement");
			$schoolName = $model->field('schoolName')->Distinct(true)->where('isdelete','0')->where('account',$info['account'])->select();
			$className = $model->field('className')->Distinct(true)->where('isdelete','0')->where('account',$info['account'])->select();
			$this->view->assign("schoolName", $schoolName);
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
				$data1 = Db::name("StudentAccessManagement")->field('student_id')->where('isdelete','0')->where("id",$data['id'])->find();
				$vo2 = Db::name("StudentLinkman")->field('id,name,relation')->where('isdelete','0')->where("student_id",$data1['student_id'])->select();
				$count = Db::name("StudentLinkman")->where('isdelete','0')->where("student_id",$data1['student_id'])->count();
				

				for($i=0;$i<$count;$i++)
				{
					$data2=['car_file_ids'=>$data['carCard_'.$vo2[$i]['id']]];
    				
    				$ret = Db::name("StudentLinkmanAccessManagement")
    				->where('studentLinkman_id', $vo2[$i]['id'])->update($data2);
					
				}
				//return ajax_return_adv_error($str);
				//$data1=['access'=>$data['access']];
				$model = Db::name("StudentAccessManagement");
				$ret = $model->where('id', $data['id'])->update($data1);
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
		
			$data = Db::name("StudentManagement")->where('isdelete','0')->find($id);
			$studentLinkman = Db::name("StudentLinkman")->where('isdelete','0')->where('student_id',$id)->order('id','asc')->limit(1)->find();//查找联系人
			$StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$studentLinkman['id'])->where('face_file_ids','neq','')->where('access','1')->find();//联系人门禁
			$info = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片

			$count = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$data['id'])->count();
			
			if($count==0)
			{
			    $data1 = ['student_id'=>$data['id'],'access'=>'1','isAllUpload'=>0];
				$ret = Db::name("StudentAccessManagement")->insert($data1);
			}
			$vo = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$id)->find();
			$vo1 = Db::name("StudentManagement")->where('isdelete','0')->where("id",$id)->find();
			
			$vo3 = Db::name("StudentLinkman")->field('id,name,relation')->where('isdelete','0')->where("student_id",$id)->select();
			
			
			for($i=0;$i<count($vo3);$i++)
			{
			    $count = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where("studentLinkman_id",$vo3[$i]['id'])->count();
			   
			    $data2 = ['studentLinkman_id'=>$vo3[$i]['id'],'access'=>'0'];
			    
			    if($count==0)
    			{
    				$ret = Db::name("StudentLinkmanAccessManagement")->insert($data2);
    			}
			}
			// $vo2 = Db::table('tp_student_linkman a, tp_student_linkman_access_management b')
			// ->field('a.id as id,a.name as name,a.relation as relation,b.access as access')
			// ->where('a.id=b.studentLinkman_id and a.student_id='.$id)->select();

            $vo2 = [];
			foreach($vo3 as $key => $value){
                $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$value['id'])->find();
                $image = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                //$carimage = Db::name("File")->where('id',$StudentLinkmanAccessManagement['car_file_ids'])->find();
				if($image){
					$image = $image['name'];
				}else{
					$image ='';
				}
	            $vo2[]= array(
                    'id' => $value['id'],
                    'name' => $value['name'],
                    'relation' => $value['relation'],
                    'access' => $StudentLinkmanAccessManagement['access'],
                    'image' => $image,
                   'carCard' => $StudentLinkmanAccessManagement ['car_file_ids'],

	            );

			}
		
			//$vo = Db::name("EmployeeLinkmanAccessManagement")->where("employee_id",$id)->find();
			if($vo2){
				 $vo2js = json_encode($vo2);
			 }else{
				 $vo2js = '';
				 $vo2 = '';
			 }
			
			if (!$vo&&!vo1) {
				throw new HttpException(404, '该记录不存在');
			}
			$this->view->assign("image", $info['name']);
			$this->view->assign("vo", $vo);
            $this->view->assign("vo1", $vo1);
            $this->view->assign("vo2", $vo2);
			$this->view->assign("vo2js", $vo2js);
            return $this->view->fetch();
        }
	}
	
	//启用联系人
	 
	public function start()
    {
        if($this->request->isPost() && $this->request->param('id')!=''){
            $linkman_id = $this->request->param('id');//获取此联系人id
            Db::name('StudentLinkman')->where('id',$linkman_id)->where('status',1)->where('isdelete',0)->update(['ismodifyData'=>1]);
            $data = array('access'=>1);
            $bool = Db::name('StudentLinkmanAccessManagement')->where('studentLinkman_id',$linkman_id)->where('status',1)->where('isdelete',0)->update($data);
            //echo Db::getLastsql();
            // if($bool){
            //     echo 'ok';
            // }
        }
    }

    //禁用联系人
    public function forbidden()
    {
             
        if($this->request->isPost() && $this->request->param('id')!=''){
            $linkman_id = $this->request->param('id');//获取此联系人id
            Db::name('StudentLinkman')->where('id',$linkman_id)->where('status',1)->where('isdelete',0)->update(['ismodifyData'=>1]);
            $data = array('access'=>0);
            $bool = Db::name('StudentLinkmanAccessManagement')->where('studentLinkman_id',$linkman_id)->where('status',1)->where('isdelete',0)->update($data);
           
            if($bool){
                echo 'ok';
            }
        }
    }

    
}
