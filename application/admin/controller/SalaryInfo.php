<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
//use think\Controller;
use think\Exception;
use think\Loader;
use think\Db;
use think\Config;
use PHPExcel_IOFactory;
use PHPExcel;


class SalaryInfo extends Controller
{
    use \app\admin\traits\controller\Controller;
    // 方法黑名单
    protected static $blacklist = [];

    protected function filter(&$map)
    {
		$info = Db::name("AdminUser")->where("id", UID)->find();
        if($info['type'] ==1)
        {

//          $map['release']=["like", "%" . $info1['realname']. "%"];
            $map['companyAccount'] = $info['account'];
        }
        else if($info['type'] ==2)
        {

//          $map['release']=["like", "%" . $info1['realname']. "%"];
            $account = $info['account'];
            $schoolID = substr($account,0,5);//截取当前用户账号的前5位---即学区号 ,如C0398
            $map['companyAccount'] = ["like", "%" . $schoolID. "%"];

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
        $work_id = $this->request->param('work_id');
        //return ajax_return_adv_error($work_id);

        // 自定义过滤器
        if (method_exists($this, 'filter')) {
            $this->filter($map);
        }
		$model = $this->getModel(); 
       // return ajax_return_adv_error($model);
        //var_dump($list);
        //$data = $this->datalist($model, $map,'','','','true');
        $data = $this->datalist($model, $map);
        $info = Db::name('AdminUser')->where('id',UID)->find(); //获取当前登陆者信息
        $list = [];
        //超级管理员
        if($info['type'] == 0){
       
           // $vo = Db::name('CompanyPositionEnroll')->where('ishire',3)->where('status',1)->where('isdelete',0)->select();//已工作
            $work = Db::name('WorkUnit')->where('status',1)->where('isdelete',0)->select();//用工企业
          

        }
        //公司登录
        if($info['type'] == 1){

            //$vo = Db::name('CompanyPositionEnroll')->where('companyAccount',$info['account'])->where('ishire',3)->where('status',1)->where('isdelete',0)->select();//已工作
            $work = Db::name('WorkUnit')->where('companyAccount',$info['account'])->where('status',1)->where('isdelete',0)->select(); //用工企业
        

        }
        $this->view->assign('work',$work);
       // $this->view->assign('list',$list);
        return $this->view->fetch();
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
			$model = Db::name($this->parseTable($controller));
			$id = $model->max('id')+1;
			$ID = $this->create($id, 1, '');
			$this->view->assign("ID", $ID);
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
			$model = Db::name($this->parseTable($controller));
			$id = $model->max('id')+1;
			$ID = $this->create($id, 1, '');
			$this->view->assign("ID", $ID);
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

  

    //导入Excel
    public function upload()  
    {  
       // return ajax_return_adv_error("清空回收站成功");
	//phpinfo();exit;
	 //   ini_set("memory_limit","30M");
    	vendor("PHPExcel.PHPExcel"); //方法一  
       // $objPHPExcel = new \PHPExcel();  
        //获取表单上传文件  

        $file = request()->file('file');  
       // $name = $this->request->param('name');   
    //  return ajax_return_adv_error($file);
        $info = $file->validate(['ext' => 'xlsx,xls'])->move(ROOT_PATH . 'public' . DS . 'uploads');  

        if ($info) {  
             //echo $info->getFilename();die;  
            $exclePath = $info->getSaveName();  //获取文件名  
            $extension  = $info->getExtension();  
          //  return ajax_return_adv_error($exclePath);
            $file_name = ROOT_PATH . 'public' . DS . 'uploads' . DS . $exclePath;   //上传文件的地址  
            
            if($extension == 'xlsx') {
                $objReader =\PHPExcel_IOFactory::createReader('Excel2007');
               // $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');
            }else if($extension == 'xls'){

                $objReader =\PHPExcel_IOFactory::createReader('Excel5');
               // $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');
            }

  //return ajax_return_adv_error('ss');  
            $obj_PHPExcel = $objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8  
            
            $excel_array = $obj_PHPExcel->getsheet(0)->toArray();   //转换为数组格式  
            
            array_shift($excel_array);  //删除第一个数组(标题); 
             
            $data = [];  

            // 启动事务
            Db::startTrans();
            try{
                foreach ($excel_array as $k => $v) {  
                   //  return  ajax_return_adv_error($v[0]);
                 
                        $data['user_id'] = $v[0];  
                        $data['name'] = $v[1]; 
                        $data['positionName'] = $v[2]; 
                        $data['salary'] = $v[3]; 
                        $data['work_day'] = $v[4]; 
                        $data['total'] = $v[5]; 
                        $data['work_money'] = $v[6]; 
                        $data['work_score'] = $v[7]; 
                        $data['companyAccount'] = $v[8]; 
                        $data['work_id'] = $v[9]; 
                        
						$score = ($v[7]/5)*$v[4];
						$info = DB::name('patriarch')->where('user_id',$v[0])->where('status',1)->where('isdelete',0)->find();
                        $data1['score'] = $info['score'] +$score;
                        DB::name('patriarch')->where('user_id',$v[0])->where('status',1)->where('isdelete',0)->update($data1);
               
                        $add = Db::name('SalaryInfo')->insert($data); //批量插入数据  

                 // return ajax_return_adv_error('ss'); 
                   
                } 
                   // 提交事务
                    Db::commit();   
            } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    return ajax_return_adv_error($e->getMessage());
            }
            return ajax_return_adv("上传成功");


            
        } else {  
            return ajax_return_adv_error($file->getError());

        }  
    }

    public function exportToExcel(){
        $work_id = $this->request->param('work_id');//用工企业
        if(empty($work_id)){
            return ajax_return_adv_error('请选择用工单位');
        }
        $info = Db::name('AdminUser')->where('id',UID)->find(); //获取当前登陆者信息
        $vo = Db::name('CompanyPositionEnroll')->where('work_id',$work_id)->where('companyAccount',$info['account'])->where('status',1)->where('isdelete',0)->where('ishire',3)->select();
        if($vo){


            //return ajax_return_adv_error($work_id);

            //实例化
            error_reporting(0); 
          //  include_once("PHPExcel/PHPExcel.php");
          //  include_once("PHPExcel/PHPExcel/IOFactory.php");
            vendor("PHPExcel.PHPExcel");
            vendor("PHPExcel.PHPExcel.IOFactory");
            
            $title=_lang_resume_excel_title;
            // echo get_include_path() . PATH_SEPARATOR . _SITE_ROOT . 'server/include/class/PHPExcel';
            $objPHPExcel = new PHPExcel();
            // Set properties
            $objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
            $objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
            $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
            $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
            $objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");
            $objPHPExcel->getProperties()->setKeywords("office 2007 openxml php");
            $objPHPExcel->getProperties()->setCategory($title);

            // 設定標題部分
            $objPHPExcel->setActiveSheetIndex(0);
            //$objPHPExcel->getActiveSheet()->setCellValue('A1', $title);
            // $objPHPExcel->getActiveSheet()->setCellValue('A2', $date);
            //標題必須合併欄位否則下面的寬度無法限制!!
            //$objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
            //$objPHPExcel->getActiveSheet()->mergeCells('A2:J2');
            //設定寬度
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
            //設置表頭
            $objPHPExcel->getActiveSheet()->setCellValue('A1', "用户ID");
            $objPHPExcel->getActiveSheet()->setCellValue('B1', "姓名");
            $objPHPExcel->getActiveSheet()->setCellValue('C1', "岗位");
            $objPHPExcel->getActiveSheet()->setCellValue('D1', "薪资");
            $objPHPExcel->getActiveSheet()->setCellValue('E1', "出勤");
            $objPHPExcel->getActiveSheet()->setCellValue('F1', "合计");
            $objPHPExcel->getActiveSheet()->setCellValue('G1', "工资");
            $objPHPExcel->getActiveSheet()->setCellValue('H1', "综合得分");
            $objPHPExcel->getActiveSheet()->setCellValue('I1', "公司账号");
            $objPHPExcel->getActiveSheet()->setCellValue('J1', "用工企业");
            
        


            $i=1;
            foreach ($vo as $key=>$v){
             //   return ajax_return_adv_error('aa');
                $info = Db::name('certification')->where('user_id',$v['user_id'])->where('status',1)->where('isdelete',0)->find();
                $info1 = Db::name('CompanyReleasePosition')->where('id',$v['pid'])->where('status',1)->where('isdelete',0)->find();
             //   return ajax_return_adv_error($info1['positionName']);
                $is_full_time=($v['is_full_time']==1)?_lang_full_time_job:_lang_not_full_time_job;
                $i++;
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['user_id']); //用户ID
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $info['name']); //姓名
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $info1['positionName']); //岗位
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, ''); //薪资
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, ''); //出勤
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, ''); //合计
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, ''); //工资
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, ''); //综合得分
                $objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $v['companyAccount']); //公司账号
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $v['work_id']); //用工企业

              //  $i++;
           }
           
            //
            $objPHPExcel->getActiveSheet()->setTitle($title);
            $objPHPExcel->setActiveSheetIndex(0);
            //temporary
        
           
            
            
              if($type == 'excel2003'){ 
                $d_file_name = "job".time().".xls"; 
                header('Content-Type: application/vnd.ms-excel');  
                header("Content-Disposition: attachment;filename=".$d_file_name);
                header('Cache-Control: max-age=0');  
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
                $objWriter->save('php://output');  
              }else{  
                $d_file_name = "job".time().".xlsx"; 
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');    
                header("Content-Disposition: attachment;filename=".$d_file_name);
                header('Cache-Control: max-age=0');    
                $objWriter = \PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');    
                $objWriter->save( 'php://output');    
      
            } 

      //  readfile($excel_file);
        //unlink(_SITE_ROOT . $excel_file);
        }

    }
	
}
