<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserModel;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;

Class TestController extends Controller
{
    public function test()
    {
//        echo date('Y-m-d H:i:s');

        $user_info = [
            'uid' => 123,
            'name' => 'lisi',
            'email' => 'lisi@qq.com',
            'age' => 12
        ];

        $response = [
            'errno' => 0,
            'msg' => 'ok',
            'data' => [
                'user_info' => $user_info
            ]
        ];

        echo json_encode($user_info);
    }

//    用户注册
    public function reg(Request $request)
    {
        echo '<pre>';print_r($request->input());echo '<pre>';

        $pass1 = $request->input('pass1');
        $pass2 = $request->input('pass2');
        if ($pass1 != $pass2){
            die('两次输入的密码不一致');
        }

        $password = password_hash($pass1,PASSWORD_BCRYPT);
        $data = [
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'password' => $password,
            'last_login' => time(),
            'last_ip' => $_SERVER['REMOTE_ADDR'], // 获取远程ip
        ];

        $uid = UserModel::insertGetId($data);
		var_dump($uid);
        if ($uid){
            echo '注册失败';
        }
    }
	
	public function postman()
	{
		echo __METHOD__;
	}
	
	// 测试接口
	public function postman1()
	{
		$data = [
			'user_name' => 'zhangsan',
			'email' => 'zhangsan@qq.com',
			'amount' => 10000
		];

		echo json_encode($data);
		// //获取用户标识
		// $token = $_SERVER['HTTP_TOKEN'];
		// //当前url
		// $request_uri = $_SERVER['REQUEST_URI'];
		
		// //$url_hash = $token . $request_uri;
		// //echo 'url_hash: ' . $url_hash;
		// $url_hash = md5($token . $request_uri);
		// //echo 'url_hash: ' . $url_hash;echo "</br>";
		// $key = 'count:url:' . $url_hash;
		// //echo 'key: ' . $key;echo '</br>';
		
		// //检查 次数是否已经超过限制
		// $count = Redis::get($key);
		// echo "当前接口访问次数为：" . $count;echo '</br>';
		
		// if($count >= 10){
		// 	$time = 10;	//时间秒
		// 	echo "请勿频繁请求接口，$time 秒后重试";
		// 	Redis::expire($key,$time);
		// 	die;
		// }
		
		// //访问数 +1
		// $count = Redis::incr($key);
		// echo 'count: ' . $count;
		//echo 'POSTMAN1';
		//echo '<hr>';
		//echo '<pre>';print_r($_POST);echo '</pre>';
		//echo '<pre>';print_r($_SERVER);echo '</pre>';
		//echo "token: ". $_SERVER['HTTP_TOKEN'];
	}

	public function md5test()
	{
//	    $str = 'hello world1';
//	    echo $str;echo '</br>';
//	    $md5_str = md5($str);
//	    echo '$md5_str: ' . $md5_str;

        $data = "Hello world";  // 要发送的数据
        $key = "1905";  // 计算签名的key

//        计算签名 MD5($data . $key)
//          $signature = md5($data . $key);
            $signature = 'ascfvazvagc';

          echo "带发送的数据：". $data;echo "</br>";
          echo "签名：". $signature;echo "</br>";

//          发送数据
            $url = "http://passport.1905.com/test/check?data=".$data . '&signature='.$signature;
            echo $url;echo '<hr>';

            $response = file_get_contents($url);
            echo $response;
	}

	public function sign3()
    {
        $key = "lst";          // 签名使用key  发送端与接收端 使用同一个key 计算签名

        //待签名的数据
        $order_info = [
            "order_id"          => 'LN_' . mt_rand(111111,999999),
            "order_amount"      => mt_rand(111,999),
            "uid"               => 12345,
            "add_time"          => time(),
        ];

        $data_json = json_encode($order_info);

        //计算签名
        $sign = md5($data_json.$key);

        // post 表单（form-data）发送数据
        $client = new Client();
        $url = 'http://passport.1905.com/test/check2';
        $response = $client->request("POST",$url,[
            "form_params"   => [
                "data"  => $data_json,
                "sign"  => $sign
            ]
        ]);

        //接收服务器端响应的数据
        $response_data = $response->getBody();
        echo $response_data;

    }
}
