<?php

namespace app\admin\controller\find;

use app\common\controller\Backend;
use fast\Tree;
use think\Db;

/**
 * 分类管理
 *
 * @icon fa fa-list
 * @remark 用于统一管理网站的所有分类,分类可进行无限级分类
 */
class Strategy extends Backend
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
                    ->where('place','not in','1,2')
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->where('place','not in','1,2')
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
                    $sort_name= Db::table('hn_strategy_sort')
                        ->where('id',$params['sort'])
                        ->find();
                    switch ($sort_name['id']) 
                    {
                        case 1:
                            $params['place'] = '3';
                            $params['sort'] = '游记';
                            break;
                        case 2:
                            $params['place'] = '4';
                            $params['sort'] = '商城';
                            break;
                        case 3:
                            $params['place'] = '5';
                            $params['sort'] = '问答';
                            break;
                        case 4:
                            $params['place'] = '6';
                            $params['sort'] = '干货';
                            break;
                            
                    }
                    $result = $this->model->allowField(true)->save($params);
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
     * 编辑
     */
    public function edit($ids = NULL)
    {
        $row = $this->model->get($ids);
        $sort_name= Db::table('hn_strategy_sort')
            ->where('name',$row['sort'])
            ->find();
        $row['sort_id'] = $sort_name['id'];
        if (!$row)
            $this->error(__('No Results were found'));
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds))
            {
                if (!in_array($row[$this->dataLimitField], $adminIds))
                {
                    $this->error(__('You have no permission'));
                }
            }
            if ($this->request->isPost())
            {
                $params = $this->request->post("row/a");
                if ($params)
                {
                    try
                    {
                        //是否采用模型验证
                        if ($this->modelValidate)
                        {
                            $name = basename(str_replace('\\', '/', get_class($this->model)));
                            $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : true) : $this->modelValidate;
                            $row->validate($validate);
                        }
                        $row_name= Db::table('hn_strategy_sort')
                            ->where('id',$params['sort'])
                            ->find();
                            switch ($row_name['id'])
                        {
                            case 1:
                                $params['place'] = '3';
                                $params['sort'] = '游记';
                                break;
                            case 2:
                                $params['place'] = '4';
                                $params['sort'] = '商城';
                                break;
                            case 3:
                                $params['place'] = '5';
                                $params['sort'] = '问答';
                                break;
                            case 4:
                                $params['place'] = '6';
                                $params['sort'] = '干货';
                                break;
                                
                        }
                        $result = $row->allowField(true)->save($params);
                        if ($result !== false)
                        {
                            $this->success();
                        }
                        else
                        {
                            $this->error($row->getError());
                        }
                    }
                    catch (\think\exception\PDOException $e)
                    {
                        $this->error($e->getMessage());
                    }
                }
                $this->error(__('Parameter %s can not be empty', ''));
            }
            $this->view->assign("row", $row);
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
