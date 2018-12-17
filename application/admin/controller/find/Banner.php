<?php

namespace app\admin\controller\find;

use app\common\controller\Backend;
use fast\Tree;

/**
 * 首页轮播图
 *
 * @icon fa fa-list
 * @remark 用于统一管理网站的所有分类,分类可进行无限级分类
 */
class Banner extends Backend
{

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Find');
    }

    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->where('place','1')
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->where('place','1')
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

     /**
     * 添加
     */
    public function add()
    {
        $is_banner = $this->model
            ->where('place','1')
            ->where('status','normal')
            ->select();
        if (!empty($is_banner)) {
            $this->error(__('首页轮播图只能一张'));
            return;
        }
        
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                if ($this->dataLimit && $this->dataLimitFieldAutoFill)
                {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                try
                {
                    //是否采用模型验证
                    if ($this->modelValidate)
                    {
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
                        $this->model->validate($validate);
                    }
                    $params['name'] = $params['title'] = 'banner';
                    $params['place'] = 1;
                    $result = $this->model->allowField(true)->save($params);

                    // print_r($result);exit;
                    if ($result !== false)
                    {
                        $this->success();
                    }
                    else
                    {
                        $this->error($this->model->getError());
                    }
                }
                catch (\think\exception\PDOException $e)
                {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    /**
     * Selectpage搜索
     * 
     * @internal
     */
    public function selectpage()
    {
        return parent::selectpage();
    }

}
