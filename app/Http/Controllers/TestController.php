<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function alipay()
    {
        $ali_gateway = 'https://openapi.alipaydev.com/gateway.do';  //支付网关
//        公共情求参数
        $appid = '2016101100661140';
        $method = 'alipay.trade.page.pay';
        $charset = 'utf-8';
        $signtype = 'RSA2';
        $sign = '';
        $timestamp = date('Y-m-d H:i:s');
        $version ='1.0';
        $return_url = 'http://api.hualuoyu.cn//test/alipay/return';       // 支付宝同步通知
        $notify_url = 'http://api.1905.com/test/alipay/notify'; // 支付宝异步通知地址
        $biz_content = '';

//        请求参数
        $out_trade_no = time() . rand(1111,9999);  // 商户订单号
        $product_code = 'FAST_INSTANT_TRADE_PAY';
        $total_amount = 0.01;
        $subject = '测试订单' . $out_trade_no;

        $request_param =[
            'out_trade_no' => $out_trade_no,
            'product_code' => $product_code,
            'total_amount' => $total_amount,
            'subject' => $subject
        ];

        $param = [
            'app_id'       => $appid,
            'method'      => $method,
            'charset'     => $charset,
            'sign_type'   => $signtype,
            'timestamp'   => $timestamp,
            'version'     => $version,
            'notify_url'  => $notify_url,
            'return_url'    => $return_url,
            'biz_content' => json_encode($request_param)
        ];

//        echo '<pre>';print_r($param);echo '</pre>';

//        字典序排序
        ksort($param);
//        echo '<pre>';print_r($param);echo '<pre>';
//        拼接 key=value1&key2=value2...
        $str = '';
        foreach($param as $k=>$v)
        {
            $str .= $k . '=' . $v . '&';
        }
        echo 'str: '.$str;echo '</br>';

        $str = rtrim($str,'&');
        echo 'str: ' .$str;echo '</br>';echo '</br>';
//        计算签名

        $key = storage_path('keys/app_priv');
        $priKey = file_get_contents($key);
        $res = openssl_get_privatekey($priKey);
        //var_dump($res);echo '</br>';
        openssl_sign($str, $sign, $res, OPENSSL_ALGO_SHA256);       //计算签名
        $sign = base64_encode($sign);
        $param['sign'] = $sign;
        // 4 urlencode
        $param_str = '?';
        foreach($param as $k=>$v){
            $param_str .= $k.'='.urlencode($v) . '&';
        }
        $param_str = rtrim($param_str,'&');
        $url = $ali_gateway . $param_str;
        //发送GET请求
        //echo $url;die;
        header("Location:".$url);
    }

    public function ascii()
    {
        $char = 'hello word';
        $length = strlen($char);
        echo $length;
        $pass = '';
        for ($i=0;$i<$length;$i++)
        {
            echo $char[$i] . '>>>' . ord($char[$i]);echo '</br>';
            $ord = ord($char[$i]) + 3;
            $chr = chr($ord);
            echo $char[$i] . '>>>' . $ord . '>>>' . $chr;echo '</br>';
            $pass .= $chr;
        }
        echo '</br>';
        echo $pass;
    }

    public function dec()
    {
        $enc = 'khoor#zrug';
        echo '密文:'.$enc;echo '<hr>';
        $length = strlen($enc);

        $str = '';
        for ($i=0;$i<$length;$i++)
        {
            $ord = ord($enc[$i]) - 3;
            $chr = chr($ord);
            echo $ord . '>>>' . $chr ;echo '</br>';
            $str .= $chr;
        }
        echo '解密:' . $str;
    }

    public function md1()
    {
        echo base64_decode("VGhpcyBpcyBhbiBlbmNvZGVkIHN0cmluZw==");die;
        echo md5($_GET['p']);die;
        echo md5('123456abc');die;
        $str1 = 'Hello World';
        echo $str1;echo "</br>";
        echo md5($str1);echo "<hr>";

        $str2 = 'Hello World Hello World asdfghjmnbvcfxd';
        echo $str1;echo '</br>';
        echo md5($str2);
    }
	
	public function sign1()
	{
		echo "<pre>";print_r($_GET);echo "</pre>";
		
		$sign = $_GET['sign'];  // base64的签名
		unset($_GET['sign']);
		// 将参数字典序排序
        ksort($_GET);
        echo '<pre>';print_r($_GET);echo '</pre>';echo '<hr>';
		
		 // 拼接字符串
        $str = '';
        foreach ($_GET as $k=>$v)
        {
            $str .= $k . '=' . $v . '&';
        }
        $str = rtrim($str,'&');
        echo $str;echo '<hr>';
		
		 // 使用 公钥进行验签
        $pub_key = file_get_contents(storage_path('keys/pubkey2'));
        $status = openssl_verify($str,base64_decode($sign),$pub_key,OPENSSL_ALGO_SHA256);
		var_dump($status);
		
		if($status)	// 验签通过
		{
			echo "success";
		}else{
			echo "验签失败";
		}
	}
	
	public function sign2()
	{
		$sign_token = 'asdsaf';
		
		// 接收参数
		echo '<pre>';print_r($_GET);echo '</pre>';
		
		// 保存参数
		$sign1 = $_GET['sign'];
		echo "发送端的签名：" . $sign1;echo '</br>';
		unset($_GET['sign']);
		
		ksort($_GET);
		
		echo '<pre>';print_r($_GET);echo '</pre>';
		//拼接待签名字符串
		 $str = '';
        foreach ($_GET as $k=>$v)
        {
            $str .= $k . '=' . $v . '&';
        }
        $str = rtrim($str,'&');
        echo '待签名字符串：' . $str;echo '<hr>';
		
		// 计算签名
		$sign2 = sha1($str . $sign_token);
		echo '</br>';
		echo "接收端计算的签名：" . $sign2;
		
		echo '</br>';
		if ($sign1 === $sign2){
			echo "验签成功";
		}else{
			echo "验签失败";
		}
	}
}
