<?php
namespace App\Http\Controllers\Sign;
use App\Http\Controllers\Controller;
use App\Model\UserPubKeyModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * ������ǩ
     */
    public function signOnline()
    {
        return view('sign.online');
    }
    /**
     * ������ǩ
     */
    public function signOnline1()
    {
        unset($_POST['_token']);
        echo '<pre>';print_r($_POST);echo '</pre>';
        //���� POST����
        $sign = base64_decode($_POST['sign']);
        unset($_POST['sign']);
        $params = [];
        foreach ($_POST['k'] as $k=>$v)
        {
            if(empty($v)){
                continue;       //�������ֶ�
            }
            $params[$v] = $_POST['v'][$k];
        }
        echo '<pre>';print_r($params);echo '</pre>';
        //ƴ�Ӳ���
        $str = "";
        foreach($params as $k=>$v){
            $str .= $k . '=' . $v . '&';
        }
        $str = trim($str,'&');
        //��ǩ
        $uid = Auth::id();      //��ȡ��¼�û� uid
        $u = UserPubKeyModel::where(['uid'=>$uid])->first();
        $status = openssl_verify($str,$sign,$u->pubkey,OPENSSL_ALGO_SHA256);
        if($status){
            echo '��ǩ�ɹ�!';
        }else{
            echo '��ǩʧ��!';
        }
    }
    /**
     * ��ǩ����
     */
    public function sign1()
    {
        $sign = base64_decode($_GET['sign']);
        //�ֵ�������
        unset($_GET['sign']);
        $params = $_GET;
        ksort($params);
        //ƴ�Ӳ���
        $str = "";
        foreach($params as $k=>$v){
            $str .= $k . '=' . $v . '&';
        }
        $str = trim($str,'&');
        //��ǩ
        $uid = Auth::id();      //��ȡ��¼�û� uid
        $u = UserPubKeyModel::where(['uid'=>$uid])->first();
        $status = openssl_verify($str,$sign,$u->pubkey,OPENSSL_ALGO_SHA256);
        if($status){
            echo '��ǩ�ɹ�!';
        }else{
            echo '��ǩʧ��!';
        }
    }
    /**
     * ��ǩ����
     */
    public function sign2()
    {
        //���� POST����
        $sign = base64_decode($_POST['sign']);
        unset($_POST['sign']);
        $params = $_POST;
        //ƴ�Ӳ���
        $str = "";
        foreach($params as $k=>$v){
            $str .= $k . '=' . $v . '&';
        }
        $str = trim($str,'&');
        //��ǩ
        $uid = Auth::id();      //��ȡ��¼�û� uid
        $u = UserPubKeyModel::where(['uid'=>$uid])->first();
        $status = openssl_verify($str,$sign,$u->pubkey,OPENSSL_ALGO_SHA256);
        if($status){
            echo '��ǩ�ɹ�!';
        }else{
            echo '��ǩʧ��!';
        }
    }
}
