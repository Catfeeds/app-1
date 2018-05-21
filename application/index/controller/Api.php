<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

class Api extends Controller
{
    public function index()
    {
        echo "测试API";
    }
    
    
    
    public function login()
    {
        if($this->request->isGet()){
            $username = $_GET['username']; // 获取get变量
            $password = $_GET['password']; // 获取get变量
            $model = Db::name("AdminUser");
            $data = $model->where('account',$username)->where('password',password_hash_tp($password))->find();
            echo json_encode($data,JSON_UNESCAPED_UNICODE);
        }
        else{
            echo "非法请求";
        }
    }
    
    public function downloadImg()
    {
        if($this->request->isGet()){
            $username = $_GET['username']; // 获取get变量
            $password = $_GET['password']; // 获取get变量
            $flag = 1;
            $vo2 = [];
            if(isset($_GET["flag"]))//是否存在"id"的参数
            {
                $flag=$_GET["flag"];//存在
            }
            if($flag==1)
                $flag1 = 0;
            else
                $flag1 = 1;
            $model = Db::name("AdminUser");
            $UserInfo = $model->where('account',$username)->where('password',password_hash_tp($password))->find();
            //echo json_encode($data,JSON_UNESCAPED_UNICODE);
            $num = 0;
            if($UserInfo['type']==1)
            {
                $data = Db::name("StudentManagement")->where('isdelete','0')->where('schoolname',$UserInfo['realname'])->select();
                foreach($data as $key => $value){
                    $studentLinkman = Db::name("StudentLinkman")->where('isdelete','0')->where('number',$value['contact'])->find();//查找联系人
                    $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$studentLinkman['id'])->where('access','1')->find();//联系人门禁
                    $info = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
        
                    $count = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$value['id'])->count();
                    
                    if($count==0)
                    {
                        $data1 = ['student_id'=>$value['id'],'access'=>'1','isAllUpload'=>0];
                        $ret = Db::name("StudentAccessManagement")->insert($data1);
                    }
                    $vo = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$value['id'])->find();
                    $vo1 = Db::name("StudentManagement")->where('isdelete','0')->where("id",$value['id'])->find();


                    
                    $vo3 = Db::name("StudentLinkman")->field('id,name,relation')->where('isdelete','0')->where("student_id",$value['id'])->where('isDownloadP',$flag1)->select();
                    
                    
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
        
        
                    foreach($vo3 as $key => $value){
                        $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$value['id'])->where('access','1')->find();
                        if($StudentLinkmanAccessManagement)
                        {
                            //Db::name('StudentLinkman')->where('id', $value['id'])->update(['isDownloadP' => $flag]);
                            $image = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                            $carimage = Db::name("File")->where('id',$StudentLinkmanAccessManagement['car_file_ids'])->find();
                            $vo2[] = array(
                                'id' => $value['id'],
                                'account' => $vo1['account'],
                                'sname' => $vo1['name'],
                                'name' => $value['name'],
                                'relation' => $value['relation'],
                                'access' => $StudentLinkmanAccessManagement['access'],
                                'image' => $image['name'],
                                'carimage' => $carimage['name'],
            
                            );
                            $num++;
                        }
                    }
                    
                }
            }
            else
            {
                $data = Db::name("StudentManagement")->where('isdelete','0')->select();
                foreach($data as $key => $value){
                    $studentLinkman = Db::name("StudentLinkman")->where('isdelete','0')->where('number',$value['contact'])->find();//查找联系人
                    $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$studentLinkman['id'])->where('access','1')->find();//联系人门禁
                    $info = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
        
                    $count = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$value['id'])->count();
                    
                    if($count==0)
                    {
                        $data1 = ['student_id'=>$value['id'],'access'=>'1','isAllUpload'=>0];
                        $ret = Db::name("StudentAccessManagement")->insert($data1);
                    }
                    $vo = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$value['id'])->find();
                    $vo1 = Db::name("StudentManagement")->where('isdelete','0')->where("id",$value['id'])->find();

                    
                    $vo3 = Db::name("StudentLinkman")->field('id,name,relation')->where('isdelete','0')->where("student_id",$value['id'])->where('isDownloadP',$flag1)->select();
                    
                    
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
        
        
                    foreach($vo3 as $key => $value){
                        $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$value['id'])->where('access','1')->find();
                        if($StudentLinkmanAccessManagement)
                        {
                            //Db::name('StudentLinkman')->where('id', $value['id'])->update(['isDownloadP' => $flag]);
                            $image = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                            $carimage = Db::name("File")->where('id',$StudentLinkmanAccessManagement['car_file_ids'])->find();
                            $vo2[] = array(
                                'id' => $value['id'],
                                'account' => $vo1['account'],
                                'sname' => $vo1['name'],
                                'name' => $value['name'],
                                'relation' => $value['relation'],
                                'access' => $StudentLinkmanAccessManagement['access'],
                                'image' => $image['name'],
                                'carimage' => $carimage['name'],
            
                            );
                            $num++;
                        }
                    }
                }
            }
            //$vo = Db::name("EmployeeLinkmanAccessManagement")->where("employee_id",$id)->find();
            $vo4 = ['num'=>$num,'data'=>$vo2];
            echo json_encode($vo4,JSON_UNESCAPED_UNICODE);
        }
        else{
            echo "非法请求";
        }
    }
    
    //未下载数据
    public function downloadImage()
    {
        if($this->request->isGet()){
            $username = $_GET['username']; // 获取get变量
            $password = $_GET['password']; // 获取get变量
            $flag = $_GET['flag']; // 获取get变量
            
            $vo2 = [];
            $model = Db::name("AdminUser");
            $UserInfo = $model->where('account',$username)->where('password',password_hash_tp($password))->find();
            $num =0;
            if($flag ==0){
                if($UserInfo['type']==1)
                {
                    $data = Db::name("StudentManagement")->where('isdelete','0')->where('schoolname',$UserInfo['realname'])->select();
                    foreach($data as $key => $value){
                        $studentLinkman = Db::name("StudentLinkman")->where('isdelete','0')->where('number',$value['contact'])->find();//查找联系人
                        $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$studentLinkman['id'])->where('access','1')->find();//联系人门禁
                        $info = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
            
                        $count = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$value['id'])->count();
                        
                        if($count==0)
                        {
                            $data1 = ['student_id'=>$value['id'],'access'=>'1','isAllUpload'=>0];
                            $ret = Db::name("StudentAccessManagement")->insert($data1);
                        }
                        $vo = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$value['id'])->find();
                        $vo1 = Db::name("StudentManagement")->where('isdelete','0')->where("id",$value['id'])->find();
                        
                        $vo3 = Db::name("StudentLinkman")->field('id,name,relation,isDownloadP,isDownloadD,isUpload')->where('isdelete','0')->where("student_id",$value['id'])->select();
                      
                        
                        for($i=0;$i<count($vo3);$i++)
                        {
                            $count = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where("studentLinkman_id",$vo3[$i]['id'])->count();
                           
                            $data2 = ['studentLinkman_id'=>$vo3[$i]['id'],'access'=>'0'];
                            
                            if($count==0)
                            {
                                $ret = Db::name("StudentLinkmanAccessManagement")->insert($data2);
                            }
                        }
            
                        foreach($vo3 as $k => $val){

                            $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$val['id'])->where('access','1')->find();
                            
                            if($StudentLinkmanAccessManagement)
                            {   
                                if($val['relation'] =='学生本人'){
                                    
                                if($val['isUpload'] ==1){
                                    // Db::name('StudentLinkman')->where('id', $val['id'])->update(['isDownloadP' => 1,'isDownloadD' => 1]);
                                }else{
                                    // Db::name('StudentLinkman')->where('id', $val['id'])->update(['isDownloadD' => 1]);
                                }
                                $image = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                                $carimage = Db::name("File")->where('id',$StudentLinkmanAccessManagement['car_file_ids'])->find();
                                $vo2[] = array(
                                    'id' =>'S_'. $value['id'],
                                    'account' => $vo1['account'],
                                    'sname' => $val['name'],
                                    'name' => '',
                                    'relation' => '',
                                    'access' => $StudentLinkmanAccessManagement['access'],
                                    'image' => $image['name'],
                                    'carimage' => '',
                
                                     );

                                }else{
                                    if($val['isUpload'] ==1){
                                    // Db::name('StudentLinkman')->where('id', $val['id'])->update(['isDownloadP' => 1,'isDownloadD' => 1]);
                                    }else{
                                        // Db::name('StudentLinkman')->where('id', $val['id'])->update(['isDownloadD' => 1]);
                                    }
                                  
                                    $image = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                                    $carimage = Db::name("File")->where('id',$StudentLinkmanAccessManagement['car_file_ids'])->find();
                                    $vo2[] = array(
                                        'id' =>'J_'. $val['id'],
                                        'account' => $vo1['account'],
                                        'sname' => $vo1['name'],
                                        'name' => $val['name'],
                                        'relation' => $val['relation'],
                                        'access' => $StudentLinkmanAccessManagement['access'],
                                        'image' => $image['name'],
                                        'carimage' => $carimage['name'],
                    
                                    );
                                }
                                
                                $num++;
                            }
                        }
                    }
                    //员工
                    $employee = Db::name("EmployeeManagement")->where('isdelete','0')->where('schoolname',$UserInfo['realname'])->select();
                    foreach($employee as $k1=>$v){

                        
                            $EmployeeAccessManagement = Db::name("EmployeeAccessManagement")->where('isdelete','0')->where('employee_id',$v['id'])->where('access','1')->find();
                             if($v['isUpload'] ==1){
                                    //Db::name('EmployeeManagement')->where('id', $v['id'])->update(['isDownloadP' => 1,'isDownloadD' => 1]); 
                                }else{
                                    //Db::name('EmployeeManagement')->where('id', $v['id'])->update([isDownloadD' => 1]); 
                                }
                               
                            
                            $image = Db::name("File")->where('id',$EmployeeAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                            $carimage = Db::name("File")->where('id',$EmployeeAccessManagement['car_file_ids'])->find();
                            $vo2[] = array(
                                'id' => 'Y_'.$v['id'],
                                'account' => $v['account'],
                                'sname' => $v['name'],
                                'name' => '',
                                'relation' => '',
                                'access' => $EmployeeAccessManagement['access'],
                                'image' => $image['name'],
                                'carimage' => $carimage['name'],
            
                            );

                            $num++;

                       

                    }

                }else
                {
                    $data =  $data = Db::name("StudentManagement")->where('isdelete','0')->select();
                    foreach($data as $key => $value){
                        $studentLinkman = Db::name("StudentLinkman")->where('isdelete','0')->where('number',$value['contact'])->find();//查找联系人
                        $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$studentLinkman['id'])->where('access','1')->find();//联系人门禁
                        $info = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
            
                        $count = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$value['id'])->count();
                        
                        if($count==0)
                        {
                            $data1 = ['student_id'=>$value['id'],'access'=>'1','isAllUpload'=>0];
                            $ret = Db::name("StudentAccessManagement")->insert($data1);
                        }
                        $vo = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$value['id'])->find();
                        $vo1 = Db::name("StudentManagement")->where('isdelete','0')->where("id",$value['id'])->find();
                        
                        $vo3 = Db::name("StudentLinkman")->field('id,name,relation,isDownloadP,isDownloadD,isUpload')->where('isdelete','0')->where("student_id",$value['id'])->select();
                      
                        
                        for($i=0;$i<count($vo3);$i++)
                        {
                            $count = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where("studentLinkman_id",$vo3[$i]['id'])->count();
                           
                            $data2 = ['studentLinkman_id'=>$vo3[$i]['id'],'access'=>'0'];
                            
                            if($count==0)
                            {
                                $ret = Db::name("StudentLinkmanAccessManagement")->insert($data2);
                            }
                        }
            
                        foreach($vo3 as $k => $val){

                            $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$val['id'])->where('access','1')->find();
                            
                            if($StudentLinkmanAccessManagement)
                            {   
                                if($val['relation'] =='学生本人'){
                                if($val['isUpload'] ==1){
                                    // Db::name('StudentLinkman')->where('id', $val['id'])->update(['isDownloadP' => 1,'isDownloadD' => 1]);
                                }else{
                                    // Db::name('StudentLinkman')->where('id', $val['id'])->update(['isDownloadD' => 1]);
                                }
                               
                                $image = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                                $carimage = Db::name("File")->where('id',$StudentLinkmanAccessManagement['car_file_ids'])->find();
                                $vo2[] = array(
                                    'id' =>'S_'. $value['id'],
                                    'account' => $vo1['account'],
                                    'sname' => $val['name'],
                                    'name' => '',
                                    'relation' => '',
                                    'access' => $StudentLinkmanAccessManagement['access'],
                                    'image' => $image['name'],
                                    'carimage' => '',
                
                                     );

                                }else{
                                    if($val['isUpload'] ==1){
                                       // Db::name('StudentLinkman')->where('id', $val['id'])->update(['isDownloadP' => 1,'isDownloadD' => 1]);
                                    }else{
                                        // Db::name('StudentLinkman')->where('id', $val['id'])->update(['isDownloadD' => 1]);
                                    }
                               
                                    $image = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                                    $carimage = Db::name("File")->where('id',$StudentLinkmanAccessManagement['car_file_ids'])->find();
                                    $vo2[] = array(
                                        'id' =>'J_'. $val['id'],
                                        'account' => $vo1['account'],
                                        'sname' => $vo1['name'],
                                        'name' => $val['name'],
                                        'relation' => $val['relation'],
                                        'access' => $StudentLinkmanAccessManagement['access'],
                                        'image' => $image['name'],
                                        'carimage' => $carimage['name'],
                    
                                    );
                                }
                                
                                $num++;
                            }
                        }
                    }
                    //员工
                    $employee = Db::name("EmployeeManagement")->where('isdelete','0')->select();
                    foreach($employee as $k1=>$v){

                      
                            $EmployeeAccessManagement = Db::name("EmployeeAccessManagement")->where('isdelete','0')->where('employee_id',$v['id'])->where('access','1')->find();
                            if($v['isUpload'] ==1){
                                //Db::name('EmployeeManagement')->where('id', $v['id'])->update(['isDownloadP' => 1,'isDownloadD' => 1]); 
                            }else{
                               // Db::name('EmployeeManagement')->where('id', $v['id'])->update(['isDownloadD' => 1]); 
                            }
                            
                            $image = Db::name("File")->where('id',$EmployeeAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                            $carimage = Db::name("File")->where('id',$EmployeeAccessManagement['car_file_ids'])->find();
                            $vo2[] = array(
                                'id' =>'Y_'. $v['id'],
                                'account' => $v['account'],
                                'sname' => $v['name'],
                                'name' => '',
                                'relation' => '',
                                'access' => $EmployeeAccessManagement['access'],
                                'image' => $image['name'],
                                'carimage' => $carimage['name'],
            
                            );

                            $num++;

                      
                    }                
                }
              //var_dump($vo2);
                $vo4 = ['num'=>$num,'data'=>$vo2];
                echo json_encode($vo4,JSON_UNESCAPED_UNICODE);

            }else{
                if($UserInfo['type']==1)
                {
                    $data = Db::name("StudentManagement")->where('isdelete','0')->where('schoolname',$UserInfo['realname'])->select();
                    foreach($data as $key => $value){
                        $studentLinkman = Db::name("StudentLinkman")->where('isdelete','0')->where('number',$value['contact'])->find();//查找联系人
                        $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$studentLinkman['id'])->where('access','1')->find();//联系人门禁
                        $info = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
            
                        $count = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$value['id'])->count();
                        
                        if($count==0)
                        {
                            $data1 = ['student_id'=>$value['id'],'access'=>'1','isAllUpload'=>0];
                            $ret = Db::name("StudentAccessManagement")->insert($data1);
                        }
                        $vo = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$value['id'])->find();
                        $vo1 = Db::name("StudentManagement")->where('isdelete','0')->where("id",$value['id'])->find();
                        
                        $vo3 = Db::name("StudentLinkman")->field('id,name,relation,isDownloadP,isDownloadD,isUpload')->where('isdelete','0')->where("student_id",$value['id'])->select();
                       
                        
                        for($i=0;$i<count($vo3);$i++)
                        {
                            $count = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where("studentLinkman_id",$vo3[$i]['id'])->count();
                           
                            $data2 = ['studentLinkman_id'=>$vo3[$i]['id'],'access'=>'0'];
                            
                            if($count==0)
                            {
                                $ret = Db::name("StudentLinkmanAccessManagement")->insert($data2);
                            }
                        }
            
                        foreach($vo3 as $k=> $val){
                            //if($val['isUpload']==1){
                                if($val['isDownloadP']==0||$val['isDownloadD']==0){
                                $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$val['id'])->where('access','1')->find();
                                    if($StudentLinkmanAccessManagement)
                                    {    //var_dump($vo3);exit;
                                        if($val['relation'] =='学生本人'){
                                            //Db::name('StudentLinkman')->where('id', $val['id'])->update(['isDownloadP' => 1]);
                                            $image = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                                            $carimage = Db::name("File")->where('id',$StudentLinkmanAccessManagement['car_file_ids'])->find();
                                            $vo2[] = array(
                                                'id' => 'S_'.$vo1['id'],
                                                'account' => $vo1['account'],
                                                'sname' => $val['name'],
                                                'name' => '',
                                                'relation' => '',
                                                'access' => $StudentLinkmanAccessManagement['access'],
                                                'image' => $image['name'],
                                                'carimage' => '',
                            
                                            );
                                        }else{
                                            //Db::name('StudentLinkman')->where('id', $val['id'])->update(['isDownloadP' => 1]);
                                            $image = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                                            $carimage = Db::name("File")->where('id',$StudentLinkmanAccessManagement['car_file_ids'])->find();
                                            $vo2[] = array(
                                                'id' => 'J_'.$val['id'],
                                                'account' => $vo1['account'],
                                                'sname' => $vo1['name'],
                                                'name' => $val['name'],
                                                'relation' => $val['relation'],
                                                'access' => $StudentLinkmanAccessManagement['access'],
                                                'image' => $image['name'],
                                                'carimage' => $carimage['name'],
                            
                                            );

                                        }
                                        
                                        $num++;
                                    }

                                }

                           // }
                        }
                        
                    }

                    $employee = Db::name("EmployeeManagement")->where('isdelete','0')->where('schoolname',$UserInfo['realname'])->where("isDownloadP",0)->where("isDownloadD",0)->select();
                    foreach($employee as $k=>$v){
                        if($v['isDownloadP'] ==0||$v['isDownloadD'] ==0){
                            $EmployeeAccessManagement = Db::name("EmployeeAccessManagement")->where('isdelete','0')->where('employee_id',$v['id'])->where('access','1')->find();
                            //Db::name('EmployeeManagement')->where('id', $v['id'])->update(['isDownloadP' => 1]); 
                            $image = Db::name("File")->where('id',$EmployeeAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                            $carimage = Db::name("File")->where('id',$EmployeeAccessManagement['car_file_ids'])->find();
                            $vo2[] = array(
                                'id' => 'Y_'.$v['id'],
                                'account' => $v['account'],
                                'sname' => $v['name'],
                                'name' => '',
                                'relation' => '',
                                'access' => $EmployeeAccessManagement['access'],
                                'image' => $image['name'],
                                'carimage' => $carimage['name'],
            
                            );
                            $num++;

                        }


                    }
                }
                else
                {
                    $data = Db::name("StudentManagement")->where('isdelete','0')->select();
                    foreach($data as $key => $value){
                        $studentLinkman = Db::name("StudentLinkman")->where('isdelete','0')->where('number',$value['contact'])->find();//查找联系人
                        $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$studentLinkman['id'])->where('access','1')->find();//联系人门禁
                        $info = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
            
                        $count = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$value['id'])->count();
                        
                        if($count==0)
                        {
                            $data1 = ['student_id'=>$value['id'],'access'=>'1','isAllUpload'=>0];
                            $ret = Db::name("StudentAccessManagement")->insert($data1);
                        }
                        $vo = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$value['id'])->find();
                        $vo1 = Db::name("StudentManagement")->where('isdelete','0')->where("id",$value['id'])->find();
                        
                        $vo3 = Db::name("StudentLinkman")->field('id,name,relation,isDownloadP,isDownloadD,isUpload')->where('isdelete','0')->where("student_id",$value['id'])->select();
                        // echo Db::getlastsql();
                     
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
            
            
                        foreach($vo3 as $k => $val){
                          //  if($val['isUpload']==1){
                                if($val['isDownloadP']==0||$val['isDownloadD']==0){
                                $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$val['id'])->where('access','1')->find();
                                    if($StudentLinkmanAccessManagement)
                                    {    //var_dump($vo3);exit;
                                        if($val['relation'] =='学生本人'){
                                           // Db::name('StudentLinkman')->where('id', $val['id'])->update(['isDownloadP' => 1]);
                                            $image = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                                            $carimage = Db::name("File")->where('id',$StudentLinkmanAccessManagement['car_file_ids'])->find();
                                            $vo2[] = array(
                                                'id' => 'S_'.$vo1['id'],
                                                'account' => $vo1['account'],
                                                'sname' => $val['name'],
                                                'name' => '',
                                                'relation' => '',
                                                'access' => $StudentLinkmanAccessManagement['access'],
                                                'image' => $image['name'],
                                                'carimage' => '',
                            
                                            );
                                        }else{
                                            //Db::name('StudentLinkman')->where('id', $val['id'])->update(['isDownloadP' => 1]);
                                            $image = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                                            $carimage = Db::name("File")->where('id',$StudentLinkmanAccessManagement['car_file_ids'])->find();
                                            $vo2[] = array(
                                                'id' => 'J_'.$val['id'],
                                                'account' => $vo1['account'],
                                                'sname' => $vo1['name'],
                                                'name' => $val['name'],
                                                'relation' => $val['relation'],
                                                'access' => $StudentLinkmanAccessManagement['access'],
                                                'image' => $image['name'],
                                                'carimage' => $carimage['name'],
                            
                                            );

                                        }
                                        
                                        $num++;
                                    }

                                }

                           //}
                            
                        }
						
						
						
						
                    }

                    $employee = Db::name("EmployeeManagement")->where('isdelete','0')->select();
                    //var_dump($employee);
                    foreach($employee as $k1=>$v){
						
                        //if($v['isUpload']==1){
                            if($v['isDownloadP']==0||$v['isDownloadD']==0){
                            $EmployeeAccessManagement = Db::name("EmployeeAccessManagement")->where('isdelete','0')->where('employee_id',$v['id'])->where('access','1')->find();
                            //Db::name('EmployeeManagement')->where('id', $v['id'])->update(['isDownloadP' => 1]); 
                            $image = Db::name("File")->where('id',$EmployeeAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                            $carimage = Db::name("File")->where('id',$EmployeeAccessManagement['car_file_ids'])->find();
                            $vo2[] = array(
                                'id' => 'Y_'.$v['id'],
                                'account' => $v['account'],
                                'sname' => $v['name'],
                                'name' => '',
                                'relation' => '',
                                'access' => $EmployeeAccessManagement['access'],
                                'image' => $image['name'],
                                'carimage' => $carimage['name'],
            
                            );
                            $num++;

                            }
                            
                        }
                        
                    
                }
                
                $vo4 = ['num'=>$num,'data'=>$vo2];
                echo json_encode($vo4,JSON_UNESCAPED_UNICODE);
            }  
        }else{
            echo "非法请求";
        }
    }

    public function updateImage()
    {    
        if($this->request->isGet()){
            $username = $_GET['username']; // 获取get变量
            $password = $_GET['password']; // 获取get变量
            $flag = $_GET['flag']; // 获取get变量
            
            $vo2 = [];
            $num =0;
            $model = Db::name("AdminUser");
            $UserInfo = $model->where('account',$username)->where('password',password_hash_tp($password))->find();
            if($flag ==1){
                if($UserInfo['type']==1)
                {
                    $data = Db::name("StudentManagement")->where('isdelete','0')->where('schoolname',$UserInfo['realname'])->select();
                    foreach($data as $key => $value){
                        $studentLinkman = Db::name("StudentLinkman")->where('isdelete','0')->where('number',$value['contact'])->find();//查找联系人
                        $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$studentLinkman['id'])->where('access','1')->find();//联系人门禁
                        $info = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
            
                        $count = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$value['id'])->count();
                        
                        if($count==0)
                        {
                            $data1 = ['student_id'=>$value['id'],'access'=>'1','isAllUpload'=>0];
                            $ret = Db::name("StudentAccessManagement")->insert($data1);
                        }
                        $vo = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$value['id'])->find();
                        $vo1 = Db::name("StudentManagement")->where('isdelete','0')->where("id",$value['id'])->find();
                        
                        $vo3 = Db::name("StudentLinkman")->field('id,name,relation,ismodifyData,ismodifyImg')->where('isdelete','0')->where("student_id",$value['id'])->select();
                        //$vo3 = Db::name("StudentLinkman")->field('id,name,relation')->where('isdelete','0')->where("student_id",$value['id'])->where("ismodifyData",1)->select();
                      
                        for($i=0;$i<count($vo3);$i++)
                        {
                            $count = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where("studentLinkman_id",$vo3[$i]['id'])->count();
                           
                            $data2 = ['studentLinkman_id'=>$vo3[$i]['id'],'access'=>'0'];
                            
                            if($count==0)
                            {
                                $ret = Db::name("StudentLinkmanAccessManagement")->insert($data2);
                            }
                        }
            
                        foreach($vo3 as $k => $val){
							
                            if($val['ismodifyImg']==1||$val['ismodifyData']==1){
                                $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$val['id'])->where('access','1')->find();
                                if($StudentLinkmanAccessManagement)
                                {    
                                    if($val['relation'] =='学生本人'){
                                       // Db::name('StudentLinkman')->where('id', $val['id'])->update(['ismodifyImg' => 0]);
                                        $image = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                                        $carimage = Db::name("File")->where('id',$StudentLinkmanAccessManagement['car_file_ids'])->find();
                                        $vo2[] = array(
                                            'id' =>'S_'. $vo1['id'],
                                            'account' => $vo1['account'],
                                            'sname' => $val['name'],
                                            'name' => '',
                                            'relation' => '',
                                            'access' => $StudentLinkmanAccessManagement['access'],
                                            'image' => $image['name'],
                                            'carimage' => '',
                        
                                        );
                                    }else{
                                       // Db::name('StudentLinkman')->where('id', $val['id'])->update(['ismodifyImg' => 0]);
                                        $image = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                                        $carimage = Db::name("File")->where('id',$StudentLinkmanAccessManagement['car_file_ids'])->find();
                                        $vo2[] = array(
                                            'id' =>'J_'. $val['id'],
                                            'account' => $vo1['account'],
                                            'sname' => $vo1['name'],
                                            'name' => $val['name'],
                                            'relation' => $val['relation'],
                                            'access' => $StudentLinkmanAccessManagement['access'],
                                            'image' => $image['name'],
                                            'carimage' => $carimage['name'],
                        
                                        );

                                    }
                                    
                                    $num++;
                                }
                                
                            }

                        }
                        
                    }

                    $employee = Db::name("EmployeeManagement")->where('isdelete','0')->where('schoolname',$UserInfo['realname'])->select();

                    foreach($employee as $k1=>$v){
                        if($v['ismodifyImg'] ==1||$v['ismodifyData'] ==1){
                            $EmployeeAccessManagement = Db::name("EmployeeAccessManagement")->where('isdelete','0')->where('employee_id',$v['id'])->where('access','1')->find();
                            //Db::name('EmployeeManagement')->where('id', $v['id'])->update(['ismodifyImg' => 0]); 
                            $image = Db::name("File")->where('id',$EmployeeAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                            $carimage = Db::name("File")->where('id',$EmployeeAccessManagement['car_file_ids'])->find();
                            $vo2[] = array(
                                'id' =>'Y_'.$v['id'],
                                'account' => $v['account'],
                                'sname' => $v['name'],
                                'name' => '',
                                'relation' => '',
                                'access' => $EmployeeAccessManagement['access'],
                                'image' => $image['name'],
                                'carimage' => $carimage['name'],
            
                            );
                            $num++;
                            
                        }

                    }


                }else
                {
                    $data = Db::name("StudentManagement")->where('isdelete','0')->select();
                    foreach($data as $key => $value){
                        $studentLinkman = Db::name("StudentLinkman")->where('isdelete','0')->where('number',$value['contact'])->find();//查找联系人
                        $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$studentLinkman['id'])->where('access','1')->find();//联系人门禁
                        $info = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
            
                        $count = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$value['id'])->count();
                        
                        if($count==0)
                        {
                            $data1 = ['student_id'=>$value['id'],'access'=>'1','isAllUpload'=>0];
                            $ret = Db::name("StudentAccessManagement")->insert($data1);
                        }
                        $vo = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$value['id'])->find();
                        $vo1 = Db::name("StudentManagement")->where('isdelete','0')->where("id",$value['id'])->find();
                        
                        $vo3 = Db::name("StudentLinkman")->field('id,name,relation,ismodifyData,ismodifyImg')->where('isdelete','0')->where("student_id",$value['id'])->select();
                        // echo Db::getlastsql();
                        // var_dump($vo3);
                        for($i=0;$i<count($vo3);$i++)
                        {
                            $count = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where("studentLinkman_id",$vo3[$i]['id'])->count();
                           
                            $data2 = ['studentLinkman_id'=>$vo3[$i]['id'],'access'=>'0'];
                            
                            if($count==0)
                            {
                                $ret = Db::name("StudentLinkmanAccessManagement")->insert($data2);
                            }
                        }
               //var_dump($vo3);exit;
                        foreach($vo3 as $k => $val){
                            if($val['ismodifyImg'] ==1||$val['ismodifyData'] ==1){
                                $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$val['id'])->where('access','1')->find();
                                if($StudentLinkmanAccessManagement)
                                {   
                                    if($val['relation'] =='学生本人'){
                                       // Db::name('StudentLinkman')->where('id', $val['id'])->update(['ismodifyImg' => 0]);
                                        $image = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                                        $carimage = Db::name("File")->where('id',$StudentLinkmanAccessManagement['car_file_ids'])->find();
                                        $vo2[] = array(
                                            'id' => 'S_'.$vo1['id'],
                                            'account' => $vo1['account'],
                                            'sname' => $val['name'],
                                            'name' => '',
                                            'relation' => '',
                                            'access' => $StudentLinkmanAccessManagement['access'],
                                            'image' => $image['name'],
                                            'carimage' => '',
                        
                                        );

                                    }else{
                                        //Db::name('StudentLinkman')->where('id', $val['id'])->update(['ismodifyImg' => 0]);
                                        $image = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                                        $carimage = Db::name("File")->where('id',$StudentLinkmanAccessManagement['car_file_ids'])->find();
                                        $vo2[] = array(
                                            'id' => 'J_'.$val['id'],
                                            'account' => $vo1['account'],
                                            'sname' => $vo1['name'],
                                            'name' => $val['name'],
                                            'relation' => $val['relation'],
                                            'access' => $StudentLinkmanAccessManagement['access'],
                                            'image' => $image['name'],
                                            'carimage' => $carimage['name'],
                        
                                        );

                                    }
                                   
                                    $num++;
                                }
                                
                            }
  
                        }
                    }

                    $employee = Db::name("EmployeeManagement")->where('isdelete','0')->select();
                    foreach($employee as $k1=>$v){
                        if($v['ismodifyImg'] ==1||$v['ismodifyData'] ==1){
                            $EmployeeAccessManagement = Db::name("EmployeeAccessManagement")->where('isdelete','0')->where('employee_id',$v['id'])->where('access','1')->find();
                            //Db::name('EmployeeManagement')->where('id', $v['id'])->update(['ismodifyImg' => 0]); 
                            $image = Db::name("File")->where('id',$EmployeeAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                            $carimage = Db::name("File")->where('id',$EmployeeAccessManagement['car_file_ids'])->find();
                            $vo2[] = array(
                                'id' => 'Y_'.$v['id'],
                                'account' => $v['account'],
                                'sname' => $v['name'],
                                'name' => '',
                                'relation' => '',
                                'access' => $EmployeeAccessManagement['access'],
                                'image' => $image['name'],
                                'carimage' => $carimage['name'],
            
                            );
                            $num++;
                            
                        }
                           
    
                    }
                    


                }
                $vo4 = ['num'=>$num,'data'=>$vo2];
                echo json_encode($vo4,JSON_UNESCAPED_UNICODE);
                
            }
        }else{
            echo "非法请求";
        }
    }
	
	//改变下载数据状态
  public function isDownloadImage (){
        $ids = $this->request->param('ids');
        $username = $this->request->param('username');
        $password = $this->request->param('password');
        $arrId = explode(',',$ids);
        $model = Db::name("AdminUser");
        $UserInfo = $model->where('account',$username)->where('password',password_hash_tp($password))->find();
        if($UserInfo){
         // 启动事务
            Db::startTrans();
            try{
                foreach($arrId as $value){
					$id = explode('_',$value);
					if($id[0] =="Y"){
						$employee = Db::name("EmployeeManagement")->where('isdelete','0')->where('id',$id[1])->find();
							// if($employee['isUpload'] ==1){
								// Db::name('EmployeeManagement')->where('id', $id[1])->update(['isDownloadP' => 1,'isDownloadD' => 1]); 
							// }else{
								// Db::name('EmployeeManagement')->where('id', $id[1])->update(['isDownloadD' => 1]); 
							// }
							Db::name('EmployeeManagement')->where('id', $id[1])->update(['isDownloadP' => 1,'isDownloadD' => 1]); 

					}elseif($id[0] =="J"){

						$employee = Db::name("StudentLinkman")->where('isdelete','0')->where('id',$id[1])->find();
						// if($employee['isUpload'] ==1){
							// Db::name('StudentLinkman')->where('id', $id[1])->update(['isDownloadP' => 1,'isDownloadD' => 1]); 
						// }else{
							// Db::name('StudentLinkman')->where('id', $id[1])->update(['isDownloadD' => 1]); 
						// }
						Db::name('StudentLinkman')->where('id', $id[1])->update(['isDownloadP' => 1,'isDownloadD' => 1]); 
					}else{
						
						$linkman_S = Db::name("StudentLinkman")->where('relation','学生本人')->where('isdelete','0')->where('student_id',$id[1])->find();
						// if($linkman_S['isUpload'] ==1){
							// Db::name('StudentLinkman')->where('id', $linkman_S['id'])->update(['isDownloadP' => 1,'isDownloadD' => 1]); 
						// }else{
							// Db::name('StudentLinkman')->where('id', $linkman_S['id'])->update(['isDownloadD' => 1]); 
						// }
						
						Db::name('StudentLinkman')->where('id', $linkman_S['id'])->update(['isDownloadP' => 1,'isDownloadD' => 1]); 
					}
                  
                }
				   // 提交事务
                    Db::commit();   
            } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    exit('0');
            }
            exit('1');
        }
    }
    
   //改变更新数据状态
    public function isUpdateImage (){
            $ids = $this->request->param('ids');
            $username = $this->request->param('username');
			$password = $this->request->param('password');
			$arrId = explode(',',$ids);
			$model = Db::name("AdminUser");
			$UserInfo = $model->where('account',$username)->where('password',password_hash_tp($password))->find();
			if($UserInfo){
            
                // 启动事务
                Db::startTrans();
                try{   
                    foreach($arrId as $value){
                        $id = explode('_',$value); 
                        if($id[0] =="Y"){
                            $employee = Db::name("EmployeeManagement")->where('isdelete','0')->where('id',$id[1])->find();
                            if($employee['ismodifyData'] ==1){
                                Db::name('EmployeeManagement')->where('id', $id[1])->update(['ismodifyData' => 0]); 
                            }
                            if($employee['ismodifyImg'] ==1){
                                Db::name('EmployeeManagement')->where('id', $id[1])->update(['ismodifyImg' => 0]); 
                            }
                        }elseif($id[0] =="J"){

                            $linkman = Db::name("StudentLinkman")->where('isdelete','0')->where('id',$id[1])->find();

                            if($linkman['ismodifyData'] ==1){
                                Db::name('StudentLinkman')->where('id', $id[1])->update(['ismodifyData' => 0]); 
                            }
                            if($linkman['ismodifyImg'] ==1){
                                Db::name('StudentLinkman')->where('id', $id[1])->update(['ismodifyImg' => 0]); 
                            }
                        
                         }else{  $linkman_S = Db::name("StudentLinkman")->where('relation','学生本人')->where('isdelete','0')->where('student_id',$id[1])->find();
                           
                           if($linkman_S['ismodifyData'] ==1){//var_dump($employee_S);
                                Db::name('StudentLinkman')->where('id', $linkman_S['id'])->update(['ismodifyData' => 0]); 
                              
                            }
                            if($linkman_S['ismodifyImg'] ==1){//var_dump($employee_S);
                                Db::name('StudentLinkman')->where('id', $linkman_S['id'])->update(['ismodifyImg' => 0]); 
                            }

                        } 

                    }
					  // 提交事务
                       Db::commit();
                }catch (\Exception $e) {
                        // 回滚事务
                        Db::rollback();
                        exit('0');
                }
                exit('1');
			}
        }
		//返回下载成功数据
    public function downloadImageOk()
    {
        if($this->request->isGet()){
            $username = $_GET['username']; // 获取get变量
            $password = $_GET['password']; // 获取get变量
            $flag = $_GET['flag']; // 获取get变量
            
            $vo2 = [];
            $model = Db::name("AdminUser");
            $UserInfo = $model->where('account',$username)->where('password',password_hash_tp($password))->find();
			
            $num =0;
            if($flag ==1){
                if($UserInfo['type']==1)
                {
                    $data = Db::name("StudentManagement")->where('isdelete','0')->where('schoolname',$UserInfo['realname'])->select();
                    foreach($data as $key => $value){
                        $studentLinkman = Db::name("StudentLinkman")->where('isdelete','0')->where('number',$value['contact'])->find();//查找联系人
                        $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$studentLinkman['id'])->where('access','1')->find();//联系人门禁
                        $info = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
            
                        $count = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$value['id'])->count();
                        
                        if($count==0)
                        {
                            $data1 = ['student_id'=>$value['id'],'access'=>'1','isAllUpload'=>0];
                            $ret = Db::name("StudentAccessManagement")->insert($data1);
                        }
                        $vo = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$value['id'])->find();
                        $vo1 = Db::name("StudentManagement")->where('isdelete','0')->where("id",$value['id'])->find();
                        
                        $vo3 = Db::name("StudentLinkman")->field('id,name,relation,isDownloadP,isDownloadD,isUpload')->where('isdelete','0')->where("student_id",$value['id'])->select();
                       
                        
                        for($i=0;$i<count($vo3);$i++)
                        {
                            $count = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where("studentLinkman_id",$vo3[$i]['id'])->count();
                           
                            $data2 = ['studentLinkman_id'=>$vo3[$i]['id'],'access'=>'0'];
                            
                            if($count==0)
                            {
                                $ret = Db::name("StudentLinkmanAccessManagement")->insert($data2);
                            }
                        }
            
                        foreach($vo3 as $k=> $val){
                            //if($val['isUpload']==1){
                                if($val['isDownloadP']==1||$val['isDownloadD']==1){
                                $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$val['id'])->where('access','1')->find();
                                    if($StudentLinkmanAccessManagement)
                                    {    //var_dump($vo3);exit;
                                        if($val['relation'] =='学生本人'){
                                            //Db::name('StudentLinkman')->where('id', $val['id'])->update(['isDownloadP' => 1]);
                                            $image = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                                            $carimage = Db::name("File")->where('id',$StudentLinkmanAccessManagement['car_file_ids'])->find();
                                            $vo2[] = array(
                                                'id' => 'S_'.$vo1['id'],
                                                'account' => $vo1['account'],
                                                'sname' => $val['name'],
                                                'name' => '',
                                                'relation' => '',
                                                'access' => $StudentLinkmanAccessManagement['access'],
                                                'image' => $image['name'],
                                                'carimage' => '',
                            
                                            );
                                        }else{
                                            //Db::name('StudentLinkman')->where('id', $val['id'])->update(['isDownloadP' => 1]);
                                            $image = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                                            $carimage = Db::name("File")->where('id',$StudentLinkmanAccessManagement['car_file_ids'])->find();
                                            $vo2[] = array(
                                                'id' => 'J_'.$val['id'],
                                                'account' => $vo1['account'],
                                                'sname' => $vo1['name'],
                                                'name' => $val['name'],
                                                'relation' => $val['relation'],
                                                'access' => $StudentLinkmanAccessManagement['access'],
                                                'image' => $image['name'],
                                                'carimage' => $carimage['name'],
                            
                                            );

                                        }
                                        
                                        $num++;
                                    }

                                }

                           // }
                        }
                        
                    }

                    $employee = Db::name("EmployeeManagement")->where('isdelete','0')->where('schoolname',$UserInfo['realname'])->where("isDownloadP",0)->where("isDownloadD",0)->select();
                    foreach($employee as $k=>$v){
                        if($v['isDownloadP'] ==1||$v['isDownloadD'] ==1){
                            $EmployeeAccessManagement = Db::name("EmployeeAccessManagement")->where('isdelete','0')->where('employee_id',$v['id'])->where('access','1')->find();
                            //Db::name('EmployeeManagement')->where('id', $v['id'])->update(['isDownloadP' => 1]); 
                            $image = Db::name("File")->where('id',$EmployeeAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                            $carimage = Db::name("File")->where('id',$EmployeeAccessManagement['car_file_ids'])->find();
                            $vo2[] = array(
                                'id' => 'Y_'.$v['id'],
                                'account' => $v['account'],
                                'sname' => $v['name'],
                                'name' => '',
                                'relation' => '',
                                'access' => $EmployeeAccessManagement['access'],
                                'image' => $image['name'],
                                'carimage' => $carimage['name'],
            
                            );
                            $num++;

                        }


                    }
                }
                else
                {
                    $data = Db::name("StudentManagement")->where('isdelete','0')->select();
                    foreach($data as $key => $value){
                        $studentLinkman = Db::name("StudentLinkman")->where('isdelete','0')->where('number',$value['contact'])->find();//查找联系人
                        $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$studentLinkman['id'])->where('access','1')->find();//联系人门禁
                        $info = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
            
                        $count = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$value['id'])->count();
                        
                        if($count==0)
                        {
                            $data1 = ['student_id'=>$value['id'],'access'=>'1','isAllUpload'=>0];
                            $ret = Db::name("StudentAccessManagement")->insert($data1);
                        }
                        $vo = Db::name("StudentAccessManagement")->where('isdelete','0')->where("student_id",$value['id'])->find();
                        $vo1 = Db::name("StudentManagement")->where('isdelete','0')->where("id",$value['id'])->find();
                        
                        $vo3 = Db::name("StudentLinkman")->field('id,name,relation,isDownloadP,isDownloadD,isUpload')->where('isdelete','0')->where("student_id",$value['id'])->select();
                        // echo Db::getlastsql();
						
                     
                        for($i=0;$i<count($vo3);$i++)
                        {
                            $count = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where("studentLinkman_id",$vo3[$i]['id'])->count();
                           
                            $data2 = ['studentLinkman_id'=>$vo3[$i]['id'],'access'=>'0'];
                            
                            if($count==0)
                            {
                                $ret = Db::name("StudentLinkmanAccessManagement")->insert($data2);
                            }
                        }
                 
            
            
                        foreach($vo3 as $k => $val){
                           // if($val['isUpload']==1){
                                if($val['isDownloadP']==1||$val['isDownloadD']==1){
                                $StudentLinkmanAccessManagement = Db::name("StudentLinkmanAccessManagement")->where('isdelete','0')->where('studentLinkman_id',$val['id'])->where('access','1')->find();
                                    if($StudentLinkmanAccessManagement)
                                    {    //var_dump($vo3);exit;
                                        if($val['relation'] =='学生本人'){
                                           // Db::name('StudentLinkman')->where('id', $val['id'])->update(['isDownloadP' => 1]);
                                            $image = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                                            $carimage = Db::name("File")->where('id',$StudentLinkmanAccessManagement['car_file_ids'])->find();
                                            $vo2[] = array(
                                                'id' => 'S_'.$vo1['id'],
                                                'account' => $vo1['account'],
                                                'sname' => $val['name'],
                                                'name' => '',
                                                'relation' => '',
                                                'access' => $StudentLinkmanAccessManagement['access'],
                                                'image' => $image['name'],
                                                'carimage' => '',
                            
                                            );
                                        }else{
                                            //Db::name('StudentLinkman')->where('id', $val['id'])->update(['isDownloadP' => 1]);
                                            $image = Db::name("File")->where('id',$StudentLinkmanAccessManagement['face_file_ids'])->find();   //联系人门禁照片
                                            $carimage = Db::name("File")->where('id',$StudentLinkmanAccessManagement['car_file_ids'])->find();
                                            $vo2[] = array(
                                                'id' => 'J_'.$val['id'],
                                                'account' => $vo1['account'],
                                                'sname' => $vo1['name'],
                                                'name' => $val['name'],
                                                'relation' => $val['relation'],
                                                'access' => $StudentLinkmanAccessManagement['access'],
                                                'image' => $image['name'],
                                                'carimage' => $carimage['name'],
                            
                                            );

                                        }
                                        
                                        $num++;
                                    }

                                }

                           //}
                            
                        }

                    }
 
                    $employee = Db::name("EmployeeManagement")->where('isdelete','0')->select();
                     
                    foreach($employee as $k1=>$v){
                        
                        //if($v['isUpload']==1){
                            if($v['isDownloadP']==1||$v['isDownloadD']==1){
								$EmployeeAccessManagement = Db::name("EmployeeAccessManagement")->where('isdelete','0')->where('employee_id',$v['id'])->where('access','1')->find();
								if($EmployeeAccessManagement){
									$image = Db::name("File")->where('id',$EmployeeAccessManagement['face_file_ids'])->find();   //联系人门禁照片
									$carimage = Db::name("File")->where('id',$EmployeeAccessManagement['car_file_ids'])->find();	 // var_dump($EmployeeAccessManagement);exit;
									$vo2[] = array(
										'id' => 'Y_'.$v['id'],
										'account' => $v['account'],
										'sname' => $v['name'],
										'name' => '',
										'relation' => '',
										'access' => $EmployeeAccessManagement['access'],
										'image' => $image['name'],
										'carimage' => $carimage['name'],
					
									);
									$num++;
										
								}
                            }
                          
                        }

                    }
                }
            
                $vo4 = ['num'=>$num,'data'=>$vo2];
                echo json_encode($vo4,JSON_UNESCAPED_UNICODE);
         
        }else{
            echo "非法请求";
        }
    }
    
     //上传图片
    public function upload(){
       // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads'. 'file');
        if($info){
            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
            $data['name'] = '/uploads/file/'.str_replace('\\','/',$info->getSaveName());
            $data['mtime'] = date('Y-m-d');
            $re = Db::name('file')->insertgetid($data);
            if($re){
                echo $re;
            }
        }else{
            // 上传失败获取错误信息
            echo -1;
        }
    }
	
    //上传信息
    public function info(){
        $id = $this->request->param('id');
        $data['time'] = $this->request->param('time');//记录时间
        $data['fileId'] = $this->request->param('fileId');//图片id
        $data['flag'] = $this->request->param('flag');//出为1  入为0
        $data['type'] = '人脸门禁';
		$data['ymd_time'] = date('Y-m-d',strtotime($data['time']));
         
		 
        
        if($id){
            $arr = explode('_',$id);
            if($arr[0] =='S'){
                $student = Db::name('StudentManagement')->where('id',$arr[1])->where('status',1)->where('isdelete',0)->find();
				//var_dump($student);
                if($student){
                    $data['account'] = $student['account'];
                    $data['divisionName'] = $student['className']; 
                    $data['name'] = $student['name']; 
					$data['relation'] = '学生本人'; 
                    $re = Db::name('StudentAccessControl')->insert($data);
                    if($re){
                        exit('1');
                    }else{
                        exit('0');
                    }

                }else{
					exit('0');
				}
               

            }elseif($arr[0] =='J'){
                $linkman = Db::name('StudentLinkman')->where('id',$arr[1])->where('status',1)->where('isdelete',0)->find();
                if($linkman){
                    $student = Db::name('StudentManagement')->where('id',$linkman['student_id'])->where('status',1)->where('isdelete',0)->find();
                    $data['account'] = $student['account'];
                    $data['name'] = $student['name']; 
					$data['relation'] = $linkman['relation']; 
                    $data['divisionName'] = $student['className'];
                    $re = Db::name('StudentAccessControl')->insert($data);
                    if($re){
                        exit('1');
                    }else{
                        exit('0');
                    }
                }else{
					exit('0');
				}
                
            }else{
                $employee = Db::name('EmployeeManagement')->where('id',$arr[1])->where('status',1)->where('isdelete',0)->find();
                if($employee){
                    $data['account'] = $employee['account'];
                    $data['name'] = $employee['name'];
                    $data['divisionName'] = $employee['className'];
                    $re = Db::name('EmployeeAccessControl')->insert($data);
                    if($re){
                        exit('1');
                    }else{
                        exit('0');
                    }

                }else{
					exit('0');
				}
                
            }
        }else{
             exit('无参数id');
        }
    }
    
    
}