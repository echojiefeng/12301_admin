<?php

namespace app\api\controller;

use app\common\controller\Api;
use think\Controller;
use think\Db;
/**
 * 首页接口
 */
class Index extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];
    const constant = 'http://localhost/hntravel/public';
    const appid = 'wxdbf7888dec209c6b';
    const secret = '6266f6a95b97d6552fd493acff13a6fa';
    const wxUrl = 'http://zjf.strawbeer.xyz/';


    /**
     * 首页
     * 
     */
    public function index()
    {
        $this->success('请求成功');
    }

    /**
     * 获取微信的getToken
     *
     */
    public function getToken()
    {
        $tokenItem =  Db::name('wx_token')->where('appid',self::appid)->select();
        if($tokenItem && $tokenItem[0]['create_time'] + $tokenItem[0]['expires'] > time() ){
//            $this->success('获取token成功',$tokenItem[0]['token']);
            return $tokenItem[0]['token'];
        } else {
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.self::appid.'&secret='.self::secret;
            $reslutData = $this->curl_request($url);
            $reslutData = json_decode($reslutData);
            $data = [
                'token' => $reslutData->access_token,
                'appid' => self::appid,
                'expires' => $reslutData->expires_in,
                'create_time' => time(),
            ];
            if($tokenItem){
                $res = Db::name('wx_token')->where('appid',self::appid)->update(['token' => $reslutData->access_token]) ;
                if($res){
//                    $this->success('更新token成功',$reslutData->access_token);
                    return $reslutData->access_token;
                }else{
//                    $this->error('更新token失败');
                    return false;
                }
            } else {
                $result =  Db::name('wx_token')->insert($data);
                if($result == 1){
//                    $this->success('首次保存token成功',$reslutData->access_token);
                    return $reslutData->access_token;
                } else {
//                    $this->error('首次获取token失败');
                    return false;
                }
            }
        }
    }

    /**
     * 获取jssdk签名
     *
     */
    function signature()
    {
        $responseData['token'] = $this->getToken();
        if($responseData['token']){
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$responseData['token'].'&type=jsapi';
            $responseData['ticket'] = json_decode($this->curl_request($url))->ticket;
//            $responseData['noncestr'] = 'qVWUagyCVFUgZk5a';
            $responseData['noncestr'] = $this->createNonceStr();
            $responseData['timestamp'] = time();
//            $responseData['timestamp'] = '1546047268';
            $responseData['url'] = 'http://zjf.strawbeer.xyz/';
            $responseData['str'] = 'jsapi_ticket='.$responseData['ticket'].'&noncestr='.$responseData['noncestr'].'&timestamp='.$responseData['timestamp'].'&url='.$responseData['url'];
            $signature = sha1($responseData['str']);
            $this->success('获取ticket成功',$responseData);
        } else {
            $this->error('获取token失败');
        }
    }


    function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    function testaaaa()
    {
        $data['test'] = time();
        $data['sdf'] = self::appid;
        $this->success('请求成功hahh',$data);
    }

    function curl_request($url,$method='get',$data=null,$https=true){
        //1.初识化curl
        $ch = curl_init($url);
        //2.根据实际请求需求进行参数封装
        //返回数据不直接输出
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        //如果是https请求
        if($https === true){
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        }
        //如果是post请求
        if($method === 'post'){
            //开启发送post请求选项
            curl_setopt($ch,CURLOPT_POST,true);
            //发送post的数据
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        }
        //3.发送请求
        $result = curl_exec($ch);
        //4.返回返回值，关闭连接
        curl_close($ch);
        return $result;
    }

    /**
     * 轮播图图片
     *
     */
    public function banner()
    {
        $constant = self::constant;
        $sort_name= Db::table('hn_travel_home')
        ->where('place','1')
        ->where('status','normal')
        ->find();
        $data['image'] = $constant.$sort_name['image'];
        $this->success('请求成功',$data);
    }
    
    /**
     * 精选标题
     *
     */
    public function select_title()
    {
        $constant = self::constant;
        $sort_name= Db::table('hn_travel_home')
        ->where('place','2')
        ->where('status','normal')
        ->select();
        if (empty($sort_name)){
            $select_name = NULL;
        }else {
            foreach ($sort_name as $k => $v) {
                $select_name[$k]['id'] = $v['id'];
                $select_name[$k]['image'] = $constant.$v['image'];
                $select_name[$k]['title'] = $v['title'];
            }
        }
        $this->success('请求成功',$select_name);
    }
    
    /**
     * 攻略
     *
     */
    public function travel()
    {
        $constant = self::constant;
        $sort_name= Db::table('hn_user_travel_log')
        ->where('type',$_GET['type'])
        ->select();
        if (empty($sort_name)){
            $select_name = NULL;
        }else {
            foreach ($sort_name as $k => $v) {
                $select_name[$k]['id'] = $v['id'];
                $select_name[$k]['user_image'] = $constant.$v['user_image'];
                $select_name[$k]['user_name'] = $v['user_name'];
                $select_name[$k]['image'] = $constant.$v['image'];
                $select_name[$k]['title'] = $v['title'];
                $select_name[$k]['second_title'] = $v['second_title'];
                $select_name[$k]['abstract'] = $v['abstract'];
                $select_name[$k]['content'] = $v['content'];
            }
        }
        $this->success('请求成功',$select_name);
    }

    /**
     * 分类下所有产品
     *
     */
    public function product()
    {
        $constant = self::constant;
        $sort_name= Db::table('hn_travel_product')
        ->where('type',$_GET['type'])
        ->where('status','normal')
        ->select();
        if (empty($sort_name)){
            $select_name = NULL;
        }else {
            foreach ($sort_name as $k => $v) {
                $v['tab'] = explode('，',$v['tab']);
                $select_name[$k]['id'] = $v['id'];
                $select_name[$k]['image'] = $constant.$v['image'];
                $select_name[$k]['title'] = $v['title'];
                $select_name[$k]['tab'] = $v['tab'];
                $select_name[$k]['abstract'] = $v['abstract'];
                $select_name[$k]['ticket'] = $v['ticket'];
                $select_name[$k]['address'] = $v['address'];
            }
        }
        $this->success('请求成功',$select_name);
    }
    
    /**
     * 点评模块
     *
     */
    public function assess()
    {
        $constant = self::constant;
        $sort_name= Db::table('hn_product_assess')
        ->where('product_id',$_GET['id'])
        ->select();
        if (empty($sort_name)){
            $select_name = NULL;
        }else {
            foreach ($sort_name as $k => $v) {
                $select_name[$k]['id'] = $v['id'];
                $select_name[$k]['user_image'] = $constant.$v['image'];
                $select_name[$k]['user_name'] = $v['user_name'];
                $select_name[$k]['stars'] = $v['stars'];
                $select_name[$k]['abstract'] = $v['abstract'];
                $select_name[$k]['time'] = $v['time'];
            }
        }
        
        $this->success('请求成功',$select_name);
    }
    
    

}
