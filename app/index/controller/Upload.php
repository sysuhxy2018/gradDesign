<?php
namespace app\index\controller;

use think\Controller;
use think\facade\Session;
use think\Db;
use think\Exception;
    
class Upload extends Controller
{
    public function sendHw()
    {
         if(Session::has('loginId')&&Session::has('loginRole')){
            $id=Session::get('loginId');
            $role=Session::get('loginRole');
            if($role==0){
                $tid=$_POST['tid'];
                $cid=$_POST['cid'];
                $st=$_POST['startTime'];
                $endTime=Db::table('homework')
                    ->where('teacher_id', '=', $tid)
                    ->where('course_id', '=', $cid)
                    ->where('start_date', '=', $st)
                    ->field('end_date as ed')
                    ->find();
                $endTime=$endTime['ed'];
                $subTime=$this->getTimeSql();
                //检查是否过期
                $status=$this->cmpTimeSql($subTime, $endTime);
                if($status<=0){
                    $file=request()->file('homework');
                    $path='uploads/'.$cid.'/'.$tid.'/'.preg_replace('/[- :]/','',$st).'/homework';
                    //需要判断是否上传了文件
                    if(isset($file)){
                        //保留原名且不可重复
                        $info=$file->move($path,'',false);
                        if($info){
                            //这里只负责更新记录。教师在布置作业时就默认增加所有记录。
                            $record=['submit_date'=>$subTime, 'extra_file'=>$info->getFilename(), 'hand_in'=>1];
                            Db::name('stu_homework')
                                ->where('stu_id','=',$id)
                                ->where('teacher_id', '=', $tid)
                                ->where('course_id', '=', $cid)
                                ->where('start_date', '=', $st)
                                ->update($record);
                            $data['msg']='提交成功';
                        }
                        else{
                            $data['msg']=$file->getError();
                        }
                    }
                    else{
                        $data['msg']='错误，文件不存在';
                    }
                }
                else{
                    $data['msg']='已过期，提交失败';
                }
            }
            return json($data);
        }
        else{
            $this->redirect('index/index/index', ['isSuccess' => 0]);
        }
    }
    
    public function getTimeSql(){
        $dt=date('c');
        //用正则表达式做一个简单的分割和替换
        $spDt=preg_split('/[+]/',$dt);
        $dtSql=preg_replace('/[T]+/', ' ', $spDt[0]);
        return $dtSql;
    }
    
    private function cmpTimeSql($ta, $tb){
        $tas=preg_split('/[- :]/', $ta);
        $tbs=preg_split('/[- :]/', $tb);
        for($i=0;$i<count($tas);$i++){
            if((int)$tas[$i] > (int)$tbs[$i])
                return 1;
            elseif((int)$tas[$i] < (int)$tbs[$i])
                return -1;
        }
        return 0;
    }
    
    public function newHomework(){
        if(Session::has('loginId')&&Session::has('loginRole')){
            $id=Session::get('loginId');
            $role=Session::get('loginRole');
            try{
                if($role==1){
                    $sd=$_POST['startDate'];
                    $year=$_POST['year'];
                    $month=$_POST['month'];
                    $day=$_POST['day'];
                    $hour=$_POST['hour'];
                    $minute=$_POST['minute'];
                    $second=$_POST['second'];
                    $info=$_POST['info'];
                    $file=request()->file('extra');
                    $cid=$_POST['cid'];
                    $isAdd=$_POST['isAdd'];

                    $ed="$year-$month-$day $hour:$minute:$second";
                    $ctime=$this->getTimeSql();             
                    //检查截止日期
                    if(!checkdate($month, $day, $year)){
                        return json(['msg'=>'错误，截止日期不存在']);    
                    }             
                    //检查开始时间和截止时间
                    if(!($this->cmpTimeSql($sd, $ctime)<=0 && $this->cmpTimeSql($ed, $ctime)>=0)){
                        return json(['msg'=>'错误，截止时间已过']);
                    }
                    //检查作业内容部分
                    if(!$this->checkWords($info, 450)){
                        return json(['msg'=>'错误，作业描述过长']);
                    }
                    //检查附件部分
                    if(isset($file)){
                        $fname=$_FILES['extra']['name'];
                        if($this->checkWords($fname, 60)){
                            $path='uploads/'.$cid.'/'.$id.'/'.preg_replace('/[- :]/','',$sd).'/extra';
                            $fmove=$file->move($path,'',true);
                            if(!$fmove){
                                return json(['msg'=>$file->getError()]);
                            }
                        }
                        else{
                            return json(['msg'=>'错误，附件名过长']); 
                        }
                    }

                    //检查操作类型
                    if($isAdd=='true'){
                        if(isset($fname)){
                            $record=['teacher_id'=>$id, 'course_id'=>$cid, 'start_date'=>$sd, 'end_date'=>$ed, 'hw_info'=>$info, 'extra_file'=>$fname];
                        }
                        else{
                            $record=['teacher_id'=>$id, 'course_id'=>$cid, 'start_date'=>$sd, 'end_date'=>$ed, 'hw_info'=>$info];
                        }
                        Db::name('homework')->insert($record);

                        Db::execute("alter table stu_homework alter column start_date set default '".$sd."'");
                        Db::execute("insert into stu_homework(stu_id, teacher_id, course_id) select stu_id, teacher_id, course_id from stu_course where stu_course.teacher_id = ".$id." and stu_course.course_id = ".$cid);
                        Db::execute("alter table stu_homework modify column start_date datetime not null");

                        return json(['msg'=>'作业添加成功']);
                    }
                    else{
                        if(isset($fname)){
                            $record=['end_date'=>$ed, 'hw_info'=>$info, 'extra_file'=>$fname];
                        }
                        else{
                            $record=['end_date'=>$ed, 'hw_info'=>$info];
                        }
                        Db::name('homework')
                            ->where('teacher_id', '=', $id)
                            ->where('course_id', '=', $cid)
                            ->where('start_date', '=', $sd)
                            ->update($record);
                        return json(['msg'=>'作业修改成功']);

                    }
                }
            }
            catch(Exception $e){
                return json($e->getMessage());
            }
        }
        else{
            $this->redirect('index/index/index', ['isSuccess' => 0]);
        }
    }
    
    private function checkWords($txt, $lim){
        return strlen($txt)<=$lim?true:false;
    }
    
    public function getProg(){
        try{
            session_start();
            if(isset($_SESSION['upload_progress_123'])){
                $stat=$_SESSION['upload_progress_123']['done'];
                if($stat){
                    return json(['rate'=>100]);
                }
                $cur=$_SESSION['upload_progress_123']['bytes_processed'];
                $all=$_SESSION['upload_progress_123']['content_length'];
                $rate=floor($cur / $all * 100);
                return json(['rate'=>$rate]);
            }
            else{
                //时间太快，$_SESSION还没更新时
                return json(['rate'=>0]);
            } 
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    public function deleteWork(){
        if(Session::has('loginId')&&Session::has('loginRole')){
            $id=Session::get('loginId');
            $role=Session::get('loginRole');
            if($role==1){
                $cid=$_POST['cid'];
                $sd=$_POST['sd'];
                //清除数据库内容
                Db::name('homework')
                    ->where('teacher_id', '=', $id)
                    ->where('course_id', '=', $cid)
                    ->where('start_date', '=', $sd)
                    ->delete();
                Db::name('stu_homework')
                    ->where('teacher_id', '=', $id)
                    ->where('course_id', '=', $cid)
                    ->where('start_date', '=', $sd)
                    ->delete();
                //清除文件夹内容
         $delPath=$_SERVER['DOCUMENT_ROOT'].'/'.'uploads/'.$cid.'/'.$id.'/'.preg_replace('/[- :]/','',$sd);
                try{
                    if(file_exists($delPath))
                        $this->delDir($delPath);
                }
                catch(Exception $e){
                    return $e->getMessage();
                }
                return json(['msg'=>'删除成功']);
            }
        }
        else{
            $this->redirect('index/index/index', ['isSuccess' => 0]);
        }
    }
    
    private function delDir($path){
        $handle = opendir($path);
        if($handle){
            while(false !== ($file=readdir($handle))){
                //忽略指向上一级和下一级目录的标记
                if($file != '.' && $file != '..'){
                    $nextPath=$path.'/'.$file;
                    if(!is_dir($nextPath)){
                        unlink($nextPath);
                    }
                    else{
                        $this->delDir($nextPath);
                    }
                }
            }
        }
        closedir($handle);
        if(rmdir($path)){
            return true;
        }
        else{
            return false;
        }
    }
}
