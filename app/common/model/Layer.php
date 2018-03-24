<?php
namespace app\common\model;

use think\Model;
use app\common\model\Article;

class Layer extends Model
{
    protected $table='layer';
    protected $pk='id';
    
    public function subLayers(){
        return $this->hasMany('SubLayer', 'lid');
    }
    
    //追加一个判断层主是否是楼主的字段信息。
    //参数1，get对应字段值（如不存在可以忽略）；
    //参数2，某条记录的数组形式
    public function getLouzhuAttr($value,$data){
        if(Article::get($data['aid'])->uid==$data['uid']&&
           Article::get($data['aid'])->urole==$data['urole'])
            return true;
        else
            return false;
    }
}

