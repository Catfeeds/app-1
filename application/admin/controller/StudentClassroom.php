<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

//宝宝课堂
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

        $info = Db::name("AdminUser")->where("id", UID)->find();
        if($info['type'] ==1)
        {

            $map['schoolAccount'] = $info['account'];
        }
        else if($info['type'] ==2)
        {

            $account = $info['account'];
            $schoolID = substr($account,0,5);//截取当前用户账号的前5位---即学区号 ,如C0398
            $map['schoolAccount'] = ["like", "%" . $schoolID. "%"];

        }
        else if($info['type'] ==3)
        {

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
		


        //查询所有课程
        $allcourse = Db::name('ClassroomCourse')->select();
        $course_array = array();//一维数组，键表示课程的id，值表示id对应的课程名称-----形如 array( [2]=>英语  [3] =>下棋  [4]=>弹琴  [5] => 数学)
        foreach($allcourse as $value){
            $course_array[$value['id']] = $value['name'];
        }
        //print_r($course_array);exit;
        $this->view->assign('course_array',$course_array);


        return $this->view->fetch();
    }
	
    
    /**
     * 编辑
     * @return mixed
     */
    public function fsedit()
    {
        $controller = $this->request->controller();

        if ($this->request->isPost()) {
            // 更新
            $id = $this->request->param("id");

            $info = Db::name("AdminUser")->where("id", UID)->find();
            $release = $info['realname']; //获取当前登录用户的名称

            $schoolName = $this->request->param('schoolName');
            //获取选择的学区名对应的账号
            $schoolInfo = Db::name('SchoolManagement')->where('schoolName',$schoolName)->where('status',1)->where('isdelete',0)->find();
            $schoolAccount = $schoolInfo['schoolAccount'];  //学区账号

            $className = $this->request->param('className');
            $release_time = $this->request->param('release_time');
            $course_time = $this->request->param('course_time');

            $morning = trim($this->request->param('morning'),','); //获取上午课程名,形如唱歌,绘画,弹琴
            //将上午课程名转为课程id
            $morning_array = explode(',',$morning);
            $morning_array2 = array();
            foreach($morning_array as $v){
                $courseInfo = Db::name('ClassroomCourse')->where('name',$v)->find();
                $morning_array2[] = $courseInfo['id'];
            }
            $morning2 = implode(',',$morning_array2); //上午课程的id,形如7,6,4



            $afternoon = trim($this->request->param('afternoon'),','); //获取下午课程名,形如唱歌,绘画,弹琴
            //将下午课程名转为课程id
            $afternoon_array = explode(',',$afternoon);
            $afternoon_array2 = array();
            foreach($afternoon_array as $v2){
                $courseInfo = Db::name('ClassroomCourse')->where('name',$v2)->find();
                $afternoon_array2[] = $courseInfo['id'];
            }
            $afternoon2 = implode(',',$afternoon_array2); //下午课程的id,形如7,6,4



            $data = array(
                'release' => $release,
                'schoolName' => $schoolName,
                'schoolAccount' => $schoolAccount,
                'className' => $className,
                'release_time' => $release_time,
                'course_time' => $course_time,
                'morning' => $morning2,
                'afternoon' => $afternoon2
            );
            Db::name("StudentClassroom")->where('id',$id)->update($data);



            return ajax_return_adv("编辑成功");
        }else{
            // 开始编辑，显示编辑页面
            $id = $this->request->param('id');
            if (!$id) {
                throw new HttpException(404, "缺少参数ID");
            }
            $vo = $this->getModel($controller)->find($id);
            if (!$vo) {
                throw new HttpException(404, '该记录不存在');
            }



            //以下是根据当前登录用户找出相应的幼儿园校区名称、班级
            $info = Db::name("AdminUser")->where("id", UID)->where('isdelete','0')->where('status',1)->find();
            $type = $info['type'];
            //若当前登录用户是超级管理员
            if($type == 0){
                //获取所有幼儿园的名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where(array('schoolAccount'=>array('like','%A%')))->where('isdelete',0)->select();
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
                //获取当前幼儿园名称-----二维数组
                $schoolName = Db::name("SchoolManagement")->field('schoolName')->where('schoolAccount',$info['account'])->where('isdelete','0')->select();

                //获取当前校区下的所有班级-----二维数组
                $className2 = Db::name('ClassManagement')->field('class,className')->where('schoolName',$info['realname'])->where('isdelete',0)->select();
            }
            //若是教师
            if($type == 2){
                $schoolID = substr($info['account'],0,5);//获取该教师所在的学区编号
                //获取当前教师所在的幼儿园名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();

                //获取该教师所在校区下的所有班级--------二维数组
                $schoolInfo = Db::name('SchoolManagement')->where('schoolID',$schoolID)->where('isdelete',0)->find();
                $className2 = Db::name('ClassManagement')->field('class,className')->where('schoolName',$schoolInfo['schoolName'])->where('isdelete',0)->select();

            }
            //若是学生
            if($type == 3){
                $schoolID = substr($info['account'],0,5);//获取该学生所在的学区编号
                //获取当前学生所在的幼儿园名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();

                //获取该学生所在校区下的所有班级--------二维数组
                $schoolInfo = Db::name('SchoolManagement')->where('schoolID',$schoolID)->where('isdelete',0)->find();
                $className2 = Db::name('ClassManagement')->field('class,className')->where('schoolName',$schoolInfo['schoolName'])->where('isdelete',0)->select();
            }

            $this->view->assign('schoolName',$schoolName);  //二维数组
            $this->view->assign('className2',$className2);  //二维数组
            //-------------------------------------------------------------------------------------------------------------

            //查询所有课程
            $allcourse = Db::name('ClassroomCourse')->where('status',1)->where('isdelete',0)->select();
            $this->view->assign('allcourse',$allcourse);
            //------------------------------------------------------------------------------------------------------------


            $this->view->assign("vo", $vo); //一维数组，表示当前编辑的课程

            //这里将当前上午课程的id转为课程名
            if(!$vo['morning']){
                $morning_course = '';
            }elseif(strlen($vo['morning']) == 1){
                $courseInfo = Db::name('ClassroomCourse')->where('id',$vo['morning'])->find();
                $morning_course = $courseInfo['name'];
            }else{
                $courseid_array = explode(',',$vo['morning']);
                $coursename_array = array();
                foreach($courseid_array as $val){
                    $courseInfo = Db::name('ClassroomCourse')->where('id',$val)->find();
                    $coursename_array[] = $courseInfo['name'];
                }
                $morning_course = implode(',',$coursename_array);
            }
            $this->view->assign('morning_course',$morning_course);//当前课程id对应的课程名称

            //这里将当前下午课程的id转为课程名
            if(!$vo['afternoon']){
                $afternoon_course = '';
            }elseif(strlen($vo['afternoon']) == 1){
                $courseInfo = Db::name('ClassroomCourse')->where('id',$vo['afternoon'])->find();
                $afternoon_course = $courseInfo['name'];
            }else{
                $courseid_array = explode(',',$vo['afternoon']);
                $coursename_array = array();
                foreach($courseid_array as $val2){
                    $courseInfo = Db::name('ClassroomCourse')->where('id',$val2)->find();
                    $coursename_array[] = $courseInfo['name'];
                }
                $afternoon_course = implode(',',$coursename_array);
            }
            $this->view->assign('afternoon_course',$afternoon_course);//当前课程id对应的课程名称





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

        if($this->request->isPost()) {

            $info = Db::name("AdminUser")->field('realname')->where("id", UID)->find();
            $release = $info['realname'];

            $schoolName = $this->request->param('schoolName');
            //获取选择的学区名对应的账号
            $schoolInfo = Db::name('SchoolManagement')->where('schoolName',$schoolName)->where('status',1)->where('isdelete',0)->find();
            $schoolAccount = $schoolInfo['schoolAccount'];  //学区账号

            $className = $this->request->param('className');
            $release_time = $this->request->param('release_time');
            $course_time = $this->request->param('course_time');

            $morning = trim($this->request->param('morning'),','); //获取上午课程名,形如唱歌,绘画,弹琴
            //将上午课程名转为课程id
            $morning_array = explode(',',$morning);
            $morning_array2 = array();
            foreach($morning_array as $v){
                $courseInfo = Db::name('ClassroomCourse')->where('name',$v)->find();
                $morning_array2[] = $courseInfo['id'];
            }
            $morning2 = implode(',',$morning_array2); //上午课程的id,形如7,6,4



            $afternoon = trim($this->request->param('afternoon'),','); //获取下午课程名,形如唱歌,绘画,弹琴
            //将下午课程名转为课程id
            $afternoon_array = explode(',',$afternoon);
            $afternoon_array2 = array();
            foreach($afternoon_array as $v2){
                $courseInfo = Db::name('ClassroomCourse')->where('name',$v2)->find();
                $afternoon_array2[] = $courseInfo['id'];
            }
            $afternoon2 = implode(',',$afternoon_array2); //下午课程的id,形如7,6,4



            $data = array(
                'release' => $release,
                'schoolName' => $schoolName,
                'schoolAccount' => $schoolAccount,
                'className' => $className,
                'release_time' => $release_time,
                'course_time' => $course_time,
                'morning' => $morning2,
                'afternoon' => $afternoon2
            );
            Db::name("StudentClassroom")->insert($data);

            return ajax_return_adv('添加成功');
        } else {


            //以下是根据当前登录用户找出相应的幼儿园校区名称、班级
            $info = Db::name("AdminUser")->where("id", UID)->where('isdelete','0')->where('status',1)->find();
            $type = $info['type'];
            //若当前登录用户是超级管理员
            if($type == 0){
                //获取所有幼儿园的名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where(array('schoolAccount'=>array('like','%A%')))->where('isdelete',0)->select();
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
                //获取当前幼儿园名称-----二维数组
                $schoolName = Db::name("SchoolManagement")->field('schoolName')->where('schoolAccount',$info['account'])->where('isdelete','0')->select();

                //获取当前校区下的所有班级-----二维数组
                $className2 = Db::name('ClassManagement')->field('class,className')->where('schoolName',$info['realname'])->where('isdelete',0)->select();
            }
            //若是教师
            if($type == 2){
                $schoolID = substr($info['account'],0,5);//获取该教师所在的学区编号
                //获取当前教师所在的幼儿园名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();

                //获取该教师所在校区下的所有班级--------二维数组
                $schoolInfo = Db::name('SchoolManagement')->where('schoolID',$schoolID)->where('isdelete',0)->find();
                $className2 = Db::name('ClassManagement')->field('class,className')->where('schoolName',$schoolInfo['schoolName'])->where('isdelete',0)->select();

            }
            //若是学生
            if($type == 3){
                $schoolID = substr($info['account'],0,5);//获取该学生所在的学区编号
                //获取当前学生所在的幼儿园名称-----二维数组
                $schoolName = Db::name('SchoolManagement')->field('schoolName')->where('schoolID',$schoolID)->where('isdelete',0)->select();

                //获取该学生所在校区下的所有班级--------二维数组
                $schoolInfo = Db::name('SchoolManagement')->where('schoolID',$schoolID)->where('isdelete',0)->find();
                $className2 = Db::name('ClassManagement')->field('class,className')->where('schoolName',$schoolInfo['schoolName'])->where('isdelete',0)->select();
            }

            $this->view->assign('schoolName',$schoolName);  //二维数组
            $this->view->assign('className2',$className2);  //二维数组
            //------------------------------------------------------------------------------------------------------------------

            //查询所有课程
            $allcourse = Db::name('ClassroomCourse')->where('status',1)->where('isdelete',0)->select();
            $this->view->assign('allcourse',$allcourse);



            $vo =['content' => ''];
			$this->view->assign("vo", $vo);
            // 添加
            return $this->view->fetch(isset($this->template) ? $this->template : 'fsedit');
        }
    }
}
