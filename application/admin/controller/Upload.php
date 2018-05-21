<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author    yuan1994 <tianpian0805@gmail.com>
 * @link      http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

namespace app\admin\controller;

use app\admin\Controller;
use think\Exception;
use think\Loader;
use think\Db;
use think\Config;
use think\Session;


class Upload extends Controller
{
    /**
     * 首页
     */
    public function index()
    {

        return $this->view->fetch();
    }

	
    /**
     * 文件上传
     */
	
    public function upload_s()
    {
        $file = $this->request->file('file');
		
        $path = ROOT_PATH . 'public/tmp/uploads/';
        $info = $file->move($path);
        if (!$info) {
            return ajax_return_error($file->getError());
        }
	
        $data = $this->request->root() . '/tmp/uploads/' . $info->getSaveName();
        $insert = [
            'cate'     => 3,
            'name'     => $data,
            'original' => $info->getInfo('name'),
            'domain'   => '',
            'type'     => $info->getInfo('type'),
            'size'     => $info->getInfo('size'),
            'mtime'    => time(),
        ];
        Db::name('File')->insert($insert);

        return ajax_return(['name' => $data]);
    }
	
	
	/**
     * 多文件上传
     */
	
    public function upload()
    {
        $files = $this->request->file('file');
		$pid = $this->request->param('pid');
		$type = $this->request->param('type');
		$funId = $this->request->param('funId');
		$personId = $this->request->param('personId');
		$self = $this->request->param('self');
		$my_id = 0;
		$table1 = '';
		$table2 = '';
		$table3 = '';
		$tableField = '';
		$tableField1 = '';
		$tableField2 = '';
		$faceORcar = '';
		$error = '';
		if($personId==1)
		{
		    if($self==1)
		    {
    			$table1 = 'StudentManagement';
    			$table2 = 'StudentAccessManagement';
    			$tableField = 'student_id';
		    }
		    else
		    {
		        $table1 = 'StudentLinkman';
    			$table2 = 'StudentLinkmanAccessManagement';
    			$table3 = 'StudentManagement';
    			$tableField = 'studentLinkman_id';
    			$tableField2 = 'student_id';
		    }
		}
		else if($personId==2)
		{
		    if($self==1)
		    {
    			$table1 = 'EmployeeManagement';
    			$table2 = 'EmployeeAccessManagement';
    			$tableField = 'employee_id';
		    }
			else
		    {
		        $table1 = 'EmployeeLinkman';
    			$table2 = 'EmployeeLinkmanAccessManagement';
    			$table3 = 'EmployeeManagement';
    			$tableField = 'employeeLinkman_id';
    			$tableField2 = 'employee_id';
		    }
		}
		
		if($funId == 1)
		{
			$tableField1 = 'face_file_ids';
			$faceORcar = 'face';
		}
		else if($funId == 2)
		{
			$tableField1 = 'car_file_ids';
			$faceORcar = 'car';
		}
        //$insert = [];
		$data = [];
        foreach ($files as $file) {
            if($self==1)
            {
                $account = Db::name($table1)->field('account')->where('id',$pid)->find();
            }
            else
            {
                $my_id = Db::name($table1)->field($tableField2)->where('id',$pid)->find();
                $account = Db::name($table3)->field('account')->where('id',$my_id[$tableField2])->find();
            }
            
            //$account = Db::name($table1)->field('account')->where('account',$account)->find();
            $path = ROOT_PATH . 'public/uploads/file/'.$account['account'].'/'.$faceORcar.'/';
            //return ajax_return_error($path);
            $info = $file->move($path);
			if (!$info) {
				return ajax_return_error($file->getError());
			}
            if ($info) {
                $data[] = $this->request->root() . '/uploads/file/'.$account['account'].'/'.$faceORcar.'/'.$info->getSaveName();
				
				$insert = [
                    'cate'     => $type,
                    'name'     => $this->request->root() . '/uploads/file/'.$account['account'].'/'.$faceORcar.'/'. $info->getSaveName(),
                    'original' => $info->getInfo('name'),
                    'domain'   => '',
                    'type'     => $info->getInfo('type'),
                    'size'     => $info->getInfo('size'),
                    'mtime'    => time(),
                ];
				Db::startTrans();
				try {
                    Db::name('File')->insert($insert);
					$fileID = Db::name('File')->field('id')->where('name',$insert['name'])->find();
					//$ID = Db::name($table1)->field('id')->where('account',$account)->find();
					
					//$paramF = Db::name($table2)->field($tableField1)->where($tableField,$ID['id'])->find();
					
                    $paramF = Db::name($table2)->field($tableField1)->where($tableField,$pid)->find();
                    
					
					if($paramF[$tableField1]==null)
					{
						$data1 = [$tableField1=>$fileID['id']];
						Db::name($table2)->where($tableField,$pid)->update($data1);
					}
					else
					{
					    //限制单图片
						//$data1 = [$tableField1=>$paramF[$tableField1].','.$fileID['id']];
						$data1 = [$tableField1=>$fileID['id']];
						Db::name($table2)->where($tableField,$pid)->update($data1);
					}
					
                    // 提交事务
                    Db::commit();
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();

                    $error = $e->getMessage();
                }
				
            } else {
                $error = $file->getError();
            }
        }
      
		
		
        return ajax_return($data[0]);
    }
	
    /**
     * 远程图片抓取
     */
    public function remote()
    {
        $url = $this->request->post('url');
		
        // validate
        $name = ROOT_PATH . 'public/tmp/uploads/' . get_random();
        $name = \File::downloadImage($url, $name);

        $ret = $this->request->root() . '/tmp/uploads/' . basename($name);
		
        return ajax_return(['url' => $ret], '抓取失败，暂未使用本功能');
    }
	
    /**
     * 图片列表
     */
    public function listImage()
    {
        
        $page = $this->request->param('p', 1);
		$pid = $this->request->param('pid');
		$type = $this->request->param('type');
		$funId = $this->request->param('funId');
		$personId = $this->request->param('personId');
		$self = $this->request->param('self');
		$table1 = '';
		$table2 = '';
		$tableField = '';
		$tableField1 = '';
		$error = '';
		
		if($personId==1)
		{
		    if($self==1)
		    {
    			$table1 = 'StudentManagement';
    			$table2 = 'StudentAccessManagement';
    			$tableField = 'student_id';
		    }
		    else
		    {
		        $table1 = 'StudentLinkmanManagement';
    			$table2 = 'StudentLinkmanAccessManagement';
    			$tableField = 'studentLinkman_id';

		    }
		}
		else if($personId==2)
		{
		    if($self==1)
		    {
    			$table1 = 'EmployeeManagement';
    			$table2 = 'EmployeeAccessManagement';
    			$tableField = 'employee_id';
		    }
			else
		    {
		        $table1 = 'EmployeeLinkmanManagement';
    			$table2 = 'EmployeeLinkmanAccessManagement';
    			$tableField = 'employeeLinkman_id';
		    }
		}
		
		if($funId == 1)
		{
			$tableField1 = 'face_file_ids';
		}
		else if($funId == 2)
		{
			$tableField1 = 'car_file_ids';
		}
		
		Db::startTrans();
		
		try {
		    
            
			//$ID = Db::name($table1)->field('id')->where('account',$account)->find();
			
			//$paramF = Db::name($table2)->field($tableField1)->where($tableField,$ID['id'])->find();
			$paramF = Db::name($table2)->field($tableField1)->where($tableField,$pid)->find();
			//$paramFs = explode(",", $paramF[$tableField1]); 
			$paramFs = $paramF[$tableField1]; 
			
			if ($this->request->param('count')) {
				$ret['count'] = Db::name('File')->where('cate='.$type)->where('id','in',$paramFs)->count();
			}
			$ret['list'] = Db::name('File')->where('cate='.$type)->where('id','in',$paramFs)->field('id,name,original')->page($page, 10)->select();
			
			
			
			
			// 提交事务
			Db::commit();
			return ajax_return($ret);
		} catch (\Exception $e) {
			// 回滚事务
			Db::rollback();

			$error = $e->getMessage();
			
		}
        
        /*
        $page = $this->request->param('p', 1);
        if ($this->request->param('count')) {
            $ret['count'] = Db::name('File')->where('cate=1')->count();
        }
        $ret['list'] = Db::name('File')->where('cate=1')->field('id,name,original')->page($page, 10)->select();
        
        return ajax_return($ret);
        */
    }
}