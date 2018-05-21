<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;


class StudentClassroom extends Controller
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
        $info = Db::name("AdminUser")->field('type')->where("id", UID)->find();
		if($info['type'] ==1)
		{
		    $info1 = Db::name("AdminUser")->field('realname')->where("id", UID)->find();
			$map['release']=["like", "%" . $info1['realname']. "%"];
		}
		else if($info['type'] ==2)
		{
			$info1 = Db::name("AdminUser")->field('realname')->where("id", UID)->find();
			$data = Db::name("EmployeeManagement")->field('schoolName')->where('schoolName',$info1['schoolName'])->select();
			$map['release']=["like", "%" . $data['schoolName']. "%"];
			
		}
		else if($info['type'] ==3)
		{
			$info1 = Db::name("AdminUser")->field('realname')->where("id", UID)->find();
			$data = Db::name("StudentManagement")->field('schoolName')->where('schoolName',$info1['schoolName'])->select();
			$map['release']=["like", "%" . $data['schoolName']. "%"];
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
                $info = Db::name("AdminUser")->field('realname')->where("id", UID)->find();
				$data['release'] = $info['realname'];
                // 使用模型更新，可以在模型中定义更高级的操作
                $model = new $modelClass();
//				$data['content'] = htmlspecialchars_decode(html_entity_decode($data['content']));
				
				
                $ret = $model->isUpdate(true)->save($data, ['id' => $data['id']]);
            } else {
                // 简单的直接使用db更新
                Db::startTrans();
                try {
                    $info = Db::name("AdminUser")->field('realname')->where("id", UID)->find();
				    $data['release'] = $info['realname'];
//                    $data['content'] = htmlspecialchars_decode(html_entity_decode($data['content']));
					
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
     * 添加
     * @return mixed
     */
    public function fadd()
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
                $info = Db::name("AdminUser")->field('realname')->where("id", UID)->find();
				$data['release'] = $info['realname'];
                $model = new $modelClass();
//				$data['content'] = htmlspecialchars_decode(html_entity_decode($data['content']));
				
                $ret = $model->isUpdate(false)->save($data);
            } else {
                // 简单的直接使用db写入
                Db::startTrans();
                try {
                    $info = Db::name("AdminUser")->field('realname')->where("id", UID)->find();
				    $data['release'] = $info['realname'];
                    $model = Db::name($this->parseTable($controller));
//					$data['content'] = htmlspecialchars_decode(html_entity_decode($data['content']));
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
			$vo =['content' => ''];
			$this->view->assign("vo", $vo);
            // 添加
            return $this->view->fetch(isset($this->template) ? $this->template : 'fsedit');
        }
    }
}
