<?php

namespace app\admin\model;

use think\Model;

class Aimcomment extends Model
{

    // 表名
    protected $name = 'product_assess';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

}
