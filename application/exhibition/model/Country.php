<?php
namespace app\exhibition\model;

use think\Model;
use think\Db;
/**
 * 国家模型
 * @package app\cms\model
 */
class Country extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__EXHIBITION_DISTRICT__';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 写入时，将菜单id转成json格式
    public function setMenuAuthAttr($value)
    {
        return json_encode($value);
    }

    // 读取时，将菜单id转为数组
    public function getMenuAuthAttr($value)
    {
        return json_decode($value, true);
    }

}