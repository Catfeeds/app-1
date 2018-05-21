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

class Register extends Controller
{
     //注册接口
    public function register(){
        if($this->request->isPost()){
			
			$data['phone'] = $this->request->param('phone');//手机号
			$phonecode = $this->request->param('phonecode');//短息验证码
			$data['password'] = $this->request->param('password');//密码
			$data['register_time'] = date('Y-m-d H:i:s');//注册时间
			$data['expire_time'] = time();//失效时间
			
			$data['user_id'] = str_shuffle($this->sum().$data['phone']);//唯一账号
			$data['token'] = md5(str_shuffle(time().$this->sum()));//
          
            session_id($data['phone']);
          //$this->json(10,'短信验证码不正确',$data1);
			if($phonecode<>session('mobile_code')){
				Session::clear('mobile_code');
				Session::clear('time');
				Session::clear('mobile');
				  
				$this->json(3,'短信验证码不正确');
			} 
            
			if(time()-session('time')>60){
				Session::clear('mobile_code');
				Session::clear('time');
				Session::clear('mobile');
				$this->json(4,'已超过一分钟重新发送');
			}
			
			if(empty($data['phone'])||empty($data['password'])){
				Session::clear('mobile_code');
				Session::clear('time');
				Session::clear('mobile');
				$this->json(5,'信息不全');
			} 

          
			$info1 = Db::name('patriarch')->where('phone',$data['phone'])->where('status',1)->where('isdelete',0)->find();
			if($info1){
				Session::clear('mobile_code');
				Session::clear('time');
				Session::clear('mobile');
				$this->json(6,'你已注册');
			}
            $id = Db::name('patriarch')->insertgetid($data);
            if($id){
				Session::clear('mobile_code');
				Session::clear('time');
				Session::clear('mobile');
				$info = DB::name('patriarch')->field('user_id,token')->where('id','=',$id)->where('status',1)->where('isdelete',0)->find();
                $data['user_id'] = $info['user_id'];
				$data['token'] = $info['token'];
				return  $this->json(1,'成功',$data);
		    }else{
				Session::clear('mobile_code');
				Session::clear('time');
				Session::clear('mobile');
				$this->json(0,'失败');
			} 
        }else{
            $this->json(7,'非法请求'); 
        }
         
    }
	

	
	//登陆
	public function login (){
		if($this->request->isPost()) {
		    $phone = $this->request->param('phone');
			$password = $this->request->param('password');
			$data['expire_time'] = time();//失效时间
			$data['token'] = md5(str_shuffle(time().$this->sum()));//
			
		    $phone1 = DB::name('patriarch')->where('phone','=',$phone)->where('status',1)->where('isdelete',0)->find();
			
		    if($phone1){
				
				if($password == $phone1['password']){
				//var_dump($phone['user_id']);exit('aaa');
					DB::name('patriarch')->where('phone','=',$phone)->where('password','=',$password)->where('status',1)->where('isdelete',0)->update($data);
					
					$info['user_id'] = $phone1['user_id'];
					$info['token'] = $data['token'];
					
					$this->json(1,'成功',$info); 
				}else{
					$this->json(8,'密码错误'); 
				}
			}else{
				$this->json(9,'请先注册'); 
			}
		    
		}else{
			$this->json(7,'非法请求'); 
		}

	}
	//随机获取10字符
	public function sum(){
        $str1='';   
        for($i=0;$i<10;$i++){
            $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghigklmnopqrstuvwxyz';
            $num = rand(0,51); 
            $str1.= substr($str,$num,1);
           
        }
        return $str1;
    }
	
  
       //忘记密码
    public function forgetpassword(){
        //if($this->request->isPost()){
            $phone = $this->request->param('phone');//手机号
			$phonecode = $this->request->param('phonecode');//短息验证码
			$data1['password'] = $this->request->param('password');//密码
			$data1['expire_time'] = time();//失效时间
			
			
            session_id($phone);
          //$this->json(10,'短信验证码不正确',$data1);
			if($phonecode<>session('mobile_code')){
				Session::clear('mobile_code');
				Session::clear('time');
				Session::clear('mobile');
				  
				$this->json(3,'短信验证码不正确');
			} 
            
			if(time()-session('time')>60){
				Session::clear('mobile_code');
				Session::clear('time');
				Session::clear('mobile');
				$this->json(4,'已超过一分钟重新发送');
			}
			
			if(empty($phone)||empty($data['password'])){
				Session::clear('mobile_code');
				Session::clear('time');
				Session::clear('mobile');
				$this->json(5,'信息不全');
			} 

          
			$info1 = Db::name('patriarch')->where('phone',$phone)->where('status',1)->where('isdelete',0)->find();
			if(empty($info1)){
				Session::clear('mobile_code');
				Session::clear('time');
				Session::clear('mobile');
				$this->json(9,'请先注册');
			}else{
                $info = DB::name('patriarch')->field('user_id,token')->where('phone','=',$phone)->where('status',1)->where('isdelete',0)->update($data1);
            }
            if($info){
				Session::clear('mobile_code');
				Session::clear('time');
				Session::clear('mobile');
				
                $data['user_id'] = $info1['user_id'];
				$data['token'] = $info1['token'];
				return  $this->json(1,'成功',$data);
		    }else{
				Session::clear('mobile_code');
				Session::clear('time');
				Session::clear('mobile');
				$this->json(0,'失败');
			} 
       
       // }else{
       //   $this->json(7,'非法请求'); 
//}
             
    }
      
    //发送短信验证码
    public function send(){
                //短信接口地址
        $target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
        //获取手机号
        $mobile = $_POST['phone'];

        //生成的随机数
        $mobile_code = $this->random(4,1);
        if(empty($mobile)){
            $this->json(2,'请输入手机号');
        }


        $post_data = "account=cf_dwxx&password=daA1SDZA11&mobile=".$mobile."&content=".rawurlencode("您的验证码是：".$mobile_code."。请不要把验证码泄露给其他人。");
        //用户名是登录用户中心->验证码短信->产品总览->APIID
        //查看密码请登录用户中心->验证码短信->产品总览->APIKEY
        $gets = $this->xml_to_array($this->Post($post_data, $target));
        if($gets['SubmitResult']['code']==2){
			session_id($mobile);
			Session::set('mobile',$mobile);
            Session::set('mobile_code',$mobile_code);
            Session::set('time',time());
      
		    $this->json(1,'成功');
		}else{
		  $this->json(0,'失败');
		}
    }
    
    public function Post($curlPost,$url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }

    //将 xml数据转换为数组格式。
    public function xml_to_array($xml){
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if(preg_match_all($reg, $xml, $matches)){
            $count = count($matches[0]);
            for($i = 0; $i < $count; $i++){
            $subxml= $matches[2][$i];
            $key = $matches[1][$i];
                if(preg_match( $reg, $subxml )){
                    $arr[$key] = $this->xml_to_array( $subxml );
                }else{
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }

    //random() 函数返回随机整数。
    public function random($length = 6 , $numeric = 0) {
        PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
        if($numeric) {
            $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
            $max = strlen($chars) - 1;
            for($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
            }
        }
        return $hash;
    }
  
  

    public static function json($code, $msg = '', $data = array()) {
        
        if(!is_numeric($code)) {
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
        

        echo json_encode($result);
        exit;
    }

}