<?php
namespace app\common\model;

use think\Model;

class Article extends Model
{
    protected $table='article';
    protected $pk='id';
    
    public function layers(){
        return $this->hasMany('Layer', 'aid');
    }
}

