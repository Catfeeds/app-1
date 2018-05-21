<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;
use Aliyun\Core\Config as Aliconfig;  
use Aliyun\Core\Profile\DefaultProfile;  
use Aliyun\Core\DefaultAcsClient;  
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;

class SendMsg extends Controller
{
	use \app\admin\traits\controller\Controller;

	/** 
	 * @param $mobile 手机号 
	 * @param $tplCode 模板ID 
	 * @param $tplParam 短信内容 
	 * 成功发送消息模板
	*/
	public function sendMsg($mobile,$companyName,$positionName)
	{
		require_once '../extend/sendMsg/api_sdk/vendor/autoload.php';
        Aliconfig::load();             //加载区域结点配置

        $accessKeyId = "LTAIKkZOyoTrcCk3";//自己替换自己的accessKeyId
        $accessKeySecret = "cKJBT5fucKxF0ut49Kn6ywSWWfFKyK";//自己替换自己的accessKeySecret
        $templateParam = array(
        		"companyName"=>$companyName,
        		"positionName"=>$positionName
        		);          
        		 //模板变量替换              假如要用验证码的话 需要把你短信模板的验证码参数改正   如  $templateParam = array("code"=>$code); 
        $templateCode = "SMS_134322440";   //短信模板ID

        $signName = "派职网";
        //短信API产品名（短信产品名固定，无需修改）
        $product = "Dysmsapi";
        //短信API产品域名（接口地址固定，无需修改）
        $domain = "dysmsapi.aliyuncs.com";
        //暂时不支持多Region（目前仅支持cn-hangzhou请勿修改）
        $region = "cn-hangzhou";

        // 初始化用户Profile实例
        $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
        // 增加服务结点
        DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
        // 初始化AcsClient用于发起请求
        $acsClient= new DefaultAcsClient($profile);

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();
        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($mobile);

        // 必填，设置签名名称
        $request->setSignName($signName);

        // 必填，设置模板CODE
        $request->setTemplateCode($templateCode);

        // 可选，设置模板参数
        if($templateParam) {
            $request->setTemplateParam(json_encode($templateParam));
        }

        //发起访问请求
        $acsResponse = $acsClient->getAcsResponse($request);

        //返回请求结果
        $result = json_decode(json_encode($acsResponse),true);
        //dump($result);
        //dump(json_encode($templateParam));
        return $result;
	}
	//失败发送消息模板
	public function sendMsgTwo($mobile,$companyName,$positionName)
	{
		require_once '../extend/sendMsg/api_sdk/vendor/autoload.php';
        Aliconfig::load();             //加载区域结点配置

        $accessKeyId = "LTAIKkZOyoTrcCk3";//自己替换自己的accessKeyId
        $accessKeySecret = "cKJBT5fucKxF0ut49Kn6ywSWWfFKyK";//自己替换自己的accessKeySecret
        $templateParam = array(
        		"companyName"=>$companyName,
        		"positionName"=>$positionName
        		);          
        		 //模板变量替换              假如要用验证码的话 需要把你短信模板的验证码参数改正   如  $templateParam = array("code"=>$code); 
        $templateCode = "SMS_134312329";   //短信模板ID

        $signName = "派职网";
        //短信API产品名（短信产品名固定，无需修改）
        $product = "Dysmsapi";
        //短信API产品域名（接口地址固定，无需修改）
        $domain = "dysmsapi.aliyuncs.com";
        //暂时不支持多Region（目前仅支持cn-hangzhou请勿修改）
        $region = "cn-hangzhou";

        // 初始化用户Profile实例
        $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
        // 增加服务结点
        DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
        // 初始化AcsClient用于发起请求
        $acsClient= new DefaultAcsClient($profile);

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();
        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($mobile);

        // 必填，设置签名名称
        $request->setSignName($signName);

        // 必填，设置模板CODE
        $request->setTemplateCode($templateCode);

        // 可选，设置模板参数
        if($templateParam) {
            $request->setTemplateParam(json_encode($templateParam));
        }

        //发起访问请求
        $acsResponse = $acsClient->getAcsResponse($request);

        //返回请求结果
        $result = json_decode(json_encode($acsResponse),true);
        //dump($result);
        //dump(json_encode($templateParam));
        return $result;
	}

	public function sendCode($mobile,$code)
	{
		require_once '../extend/sendMsg/api_sdk/vendor/autoload.php';
        Aliconfig::load();             //加载区域结点配置

        $accessKeyId = "LTAIKkZOyoTrcCk3";//自己替换自己的accessKeyId
        $accessKeySecret = "cKJBT5fucKxF0ut49Kn6ywSWWfFKyK";//自己替换自己的accessKeySecret
        $templateParam = array(
        		"code"=>$code,
        		);          
        		 //模板变量替换              假如要用验证码的话 需要把你短信模板的验证码参数改正   如  $templateParam = array("code"=>$code); 
        $templateCode = "SMS_134317484";   //短信模板ID

        $signName = "派职网";
        //短信API产品名（短信产品名固定，无需修改）
        $product = "Dysmsapi";
        //短信API产品域名（接口地址固定，无需修改）
        $domain = "dysmsapi.aliyuncs.com";
        //暂时不支持多Region（目前仅支持cn-hangzhou请勿修改）
        $region = "cn-hangzhou";

        // 初始化用户Profile实例
        $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
        // 增加服务结点
        DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
        // 初始化AcsClient用于发起请求
        $acsClient= new DefaultAcsClient($profile);

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();
        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($mobile);

        // 必填，设置签名名称
        $request->setSignName($signName);

        // 必填，设置模板CODE
        $request->setTemplateCode($templateCode);

        // 可选，设置模板参数
        if($templateParam) {
            $request->setTemplateParam(json_encode($templateParam));
        }

        //发起访问请求
        $acsResponse = $acsClient->getAcsResponse($request);

        //返回请求结果
        $result = json_decode(json_encode($acsResponse),true);
        //dump($result);
        //dump(json_encode($templateParam));
        return $result;
	}
}