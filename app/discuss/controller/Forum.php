<?php
namespace app\discuss\controller;

use think\Controller;
use app\common\model\Course;
use app\common\model\Student;
use app\common\model\Teacher;
use app\common\model\Account;
use app\common\model\Article;
use app\common\model\Layer;
use app\common\model\SubLayer;
use think\facade\Session;

class Forum extends Controller
{
    public function checkLogin(){
        if(!Session::has('loginId')||!Session::has('loginRole')){
            $this->redirect('index/index/index', ['isSuccess' => 0]);
        }
    }
    
    public function getTopics($fid){
        $num_per_page=15;
        $artList=Article::withCount(['layers'=>function($q){
            //将帖主自己那层楼排除在外
            $q->where('floor','>','1');
        }])->where('cid','=',$fid)->paginate($num_per_page);
        
        $this->assign('list', $artList);
        $this->assign('exp', $this->printExp());
        return $this->fetch('mainForum');
    }
    
    public function getReplys($aid){
        //$this->checkLogin();
        $num_per_page=3;
        $npp=4;
        $repList=Layer::withCount(['subLayers'=>function($q){
        }])->where('aid','=',$aid)->paginate($num_per_page);
        
        foreach($repList as $rep){
            $rep->append(['lou_zhu']);
        }
        $this->assign('list', $repList);
        $this->assign('exp', $this->printExp());
        $this->assign('aid', $aid);
        $this->assign('npp', $npp);
        return $this->fetch('mainReply');
    }
    
    public function getSubReplys(){
        //$this->checkLogin();
        $lid=$_GET['lid'];
        $p=$_GET['p'];
        $npp=4;
        //检查页码
        $count=SubLayer::where('lid','=',$lid)
            ->count();
        $end_page=ceil($count / $npp);
        if($end_page==0)
            return json(['data'=>'','cp'=>'1','ep'=>'0']);
        if($p>$end_page)
            $p=$end_page;
        else{
            if($p<=0)
                $p=1;
        }
        //查询并输出结果
        $subrepList=SubLayer::where('lid','=',$lid)
            ->limit($npp)
            ->page($p)
            ->select();
        $sublist='<tbody>';
        foreach($subrepList as $sub){
            $sublist.='<tr>';
            $sublist.='<td>'.$sub->uname.'</td>';
            $sublist.='<td style="word-break: break-all;">'.$sub->reply;
            $sublist.='<p class="mb-0 mt-1 text-right"><span class="text-secondary">'.
                $sub->stamp.'</span>&nbsp;<a class="inline-reply-link" href="javascript:;">回复</a></p></td>';
            $sublist.='</tr>';
        }
        $sublist.='</tbody>';
        return json(['data'=>$sublist, 'cp'=>$p, 'ep'=>$end_page]);
    }
    
    public function postTopics($fid){
        //$this->checkLogin();
        $id=Session::get('loginId');
        $role=Session::get('loginRole');
        $title=$_POST['title'];
        $reply=$_POST['reply'];
        $uname="";
        if($role==0){
            $uname=Student::get($id)->stu_name;
        }
        elseif($role==1){
            $uname=Teacher::get($id)->teacher_name;
        }
        //新增文章
        $article=new Article;
        $article->save([
            'cid'=>$fid,
            'stamp'=>$this->getTimeSql(),
            'uid'=>$id,
            'urole'=>$role,
            'uname'=>$uname,
            'title'=>$title
        ]);
        $aid=$article->id;
        //新增楼层
        $layer=new Layer;
        $layer->save([
            'aid'=>$aid,
            'floor'=>1,
            'stamp'=>$article->stamp, //保持时间戳一致
            'uid'=>$id,
            'urole'=>$role,
            'uname'=>$uname,
            'reply'=>$reply
        ]);
        return json(['status'=>'发帖成功']);
    }
    
    public function postReplys($aid){
        //$this->checkLogin();
        $id=Session::get('loginId');
        $role=Session::get('loginRole');
        $reply=$_POST['reply'];
        $uname="";
        if($role==0){
            $uname=Student::get($id)->stu_name;
        }
        elseif($role==1){
            $uname=Teacher::get($id)->teacher_name;
        }
        //获得所有回复中最高楼层（不存在回复则为0）
        $max_floor=Layer::where('aid','=',$aid)->max('floor');
        $layer=new Layer;
        $layer->save([
            'aid'=>$aid,
            'floor'=>$max_floor+1,
            'stamp'=>$this->getTimeSql(),
            'uid'=>$id,
            'urole'=>$role,
            'uname'=>$uname,
            'reply'=>$reply
        ]);
        return json(['status'=>'回复成功']);
    }
    
    public function postSubReplys(){
        //$this->checkLogin();
        $id=Session::get('loginId');
        $role=Session::get('loginRole');
        $lid=$_POST['lid'];
        $subreply=$_POST['subreply'];
        $uname="";
        if($role==0){
            $uname=Student::get($id)->stu_name;
        }
        elseif($role==1){
            $uname=Teacher::get($id)->teacher_name;
        }
        $sublayer=new SubLayer;
        $sublayer->save([
            'lid'=>$lid,
            'stamp'=>$this->getTimeSql(),
            'uid'=>$id,
            'urole'=>$role,
            'uname'=>$uname,
            'reply'=>$subreply
        ]);
        return json(['status'=>'回复成功']);
    }
    
    public function getTimeSql(){
        $dt=date('c');
        $spDt=preg_split('/[+]/',$dt);
        $dtSql=preg_replace('/[T]+/', ' ', $spDt[0]);
        return $dtSql;
    }
    
    public function upimg(){
        //$this->checkLogin();
        $file=request()->file("pics");
        if(isset($file)){
            //move第二个参数表示是否不使用原名
            $info=$file->rule('md5')->move('public/static/img/cache',true);
            if($info){
                return json(['fullname'=>$info->getSaveName()]);
            }
        }
    }
    
    public function printExp(){
        $pic_num=28;$rows=2;$cols=5;$code=0;$exp='';
        while($code<$pic_num){
            if($code%10==0){
                if($code==0)
                    $exp.='<div class="carousel-item active">';
                else 
                    $exp.='<div class="carousel-item">';
                $exp.='<table class="mx-auto"><tbody>';
                for($i=0;$i<$rows;$i++){
                    $exp.='<tr>';
                    for($j=0;$j<$cols;$j++,$code++){
                        $exp.='<td>';
                        if($code<$pic_num){
                            $pic_id=sprintf('%02d', $code+1);
                            $exp.=('<img class="acfun" src="/public/static/img/exps/'.$pic_id.'.png">');
                        }
                        $exp.='</td>';
                    }
                    $exp.='</tr>';
                }
                $exp.='</tbody></table>';
                $exp.='</div>';
            }
        }
        return $exp;
    }
}
