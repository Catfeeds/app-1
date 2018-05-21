<?php
namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);


use think\Exception;
use think\Db;
use think\Loader;
use think\Config;
use think\Controller;


class Alipay extends Controller
{
	use \app\admin\traits\controller\Controller;
						//商户订单号     订单名称    付款金额     body
	public function pagepay($out_trade_no,$subject,$total_amount,$body="")
	{
		require_once '../vendor/alipay/config.php';
		require_once '../vendor/alipay/pagepay/service/AlipayTradeService.php';
		require_once '../vendor/alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';

		/*vendor('alipay.config');
		vendor('alipay.pagepay.service.AlipayTradeService');
		vendor('alipay.pagepay.buildermodel.AlipayTradePagePayContentBuilder');
		Loader::import('alipay.config', VENDOR_PATH);*/

		//构造参数
		$payRequestBuilder = new \AlipayTradePagePayContentBuilder();
		$payRequestBuilder->setBody($body);
		$payRequestBuilder->setSubject($subject);
		$payRequestBuilder->setTotalAmount($total_amount);
		$payRequestBuilder->setOutTradeNo($out_trade_no);
	
		$aop = new \AlipayTradeService($config);
	
		/**
		 * pagePay 电脑网站支付请求
		 * @param $builder 业务参数，使用buildmodel中的对象生成。
		 * @param $return_url 同步跳转地址，公网可以访问
		 * @param $notify_url 异步通知地址，公网可以访问
		 * @return $response 支付宝返回的信息
 		*/
		$response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);
	
		//输出表单
	    var_dump($response);
	}
	//get同步跳转
	public function return_url()
	{
		require_once '../vendor/alipay/config.php';	
		require_once '../vendor/alipay/pagepay/service/AlipayTradeService.php';

		$arr = $_GET;
		$aop = new \AlipayTradeService($config);
		$result = $aop->check($arr);

		if($result){
			$out_trade_no = htmlspecialchars($arr['out_trade_no']);//订单号

			$trade_no = htmlspecialchars($arr['trade_no']);//支付宝交易号

			$where['order_number'] = $out_trade_no;

			$orderInfo = DB::name('AccountManagement')->where($where)->find();
			$buynum = $orderInfo['buynum'];//获取充值金额; 
			$schoolAccount = $orderInfo['schoolAccount'];

			if($orderInfo['ispay'] == 0 ){

				$data['ispay'] = 1;//更改订单状态

				$res = DB::name('AccountManagement')->where($where)->update($data);

				if($res){
					//return true;
					$scInfo = DB::name('SchoolManagement')->where('schoolAccount',$schoolAccount)->find();
					$change['money'] = $buynum + $scInfo['money'];
					$pay = DB::name('SchoolManagement')->where('schoolAccount',$schoolAccount)->update($change);
					$this->redirect('SchoolManagement/finish');
				}
			}

		}else{
			echo "验证失败";
		}
	}


	/*public function return_url()
	{
		$res = $this->returndata();
		if($res){

		}
	}*/
	//post异步跳转
	public function notify_url()
	{
		require_once '../vendor/alipay/config.php';
		require_once '../vendor/alipay/pagepay/service/AlipayTradeService.php';

		$arr = $_POST;
		$aop = new \AlipayTradeService($config);
		$aop->writelog(var_export($_POST,true));
		$result = $aop->check($arr);

		if($result){
			$out_trade_no = $_POST['out_trade_no'];//订单号
			$trade_no = $_POST['trade_no']; //支付宝交易号
			$trade_status = $_POST['trade_status'];//交易状态

			if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS'){
				$where['order_number'] = $out_trade_no;

				$orderInfo = DB::name('AccountManagement')->where($where)->find();
				$buynum = $orderInfo['buynum'];//获取充值金额; 
				$schoolAccount = $orderInfo['schoolAccount'];

				if($orderInfo['ispay'] == 0 ){

				$data['ispay'] = 1;//更改订单状态

				$res = DB::name('AccountManagement')->where($where)->update($data);

				if($res){
					//return true;
					$scInfo = DB::name('SchoolManagement')->where('schoolAccount',$schoolAccount)->find();
					$change['money'] = $buynum + $scInfo['money'];
					$pay = DB::name('SchoolManagement')->where('schoolAccount',$schoolAccount)->update($change);
				}
			}
			}
			echo "success";
		}else{
			echo "fail";
		}

	}

}