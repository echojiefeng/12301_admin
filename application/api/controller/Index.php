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
        $this->success('请求成功');
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
