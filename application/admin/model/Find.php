<?php

namespace app\admin\model;

use think\Model;

class Find extends Model
{

    // 表名
    protected $name = 'travel_home';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

}
