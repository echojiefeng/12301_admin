<?php

namespace app\admin\controller\aim;

use app\common\controller\Backend;

/**
 * 分类管理
 *
 * @icon fa fa-list
 * @remark 用于统一管理网站的所有分类,分类可进行无限级分类
 */
class Sort extends Backend
{
    
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Aimsort');
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
