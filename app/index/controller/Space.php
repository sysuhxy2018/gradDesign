<?php
namespace app\index\controller;

use think\Controller;
use app\common\model\Stu;
use app\common\model\User;
use app\common\model\StuCourse;
use think\facade\Session;
use think\Db;
use think\captcha\Captcha;
    
class Space extends Controller
{
    public function main()
    {
        if(Session::has('loginId')&&Session::has('loginRole')){
            $id=Session::get('loginId');
            $role=Session::get('loginRole');
            if($role==0){
                $list=Db::table(['course'=>'course', 'stu_course'=>'stu_course', 'teacher'=>'teacher'])
                    ->where('stu_course.stu_id','=',$id)
                    ->where('course.course_id = stu_course.course_id')
                    ->where('teacher.teacher_id = stu_course.teacher_id')
                    ->field('course.course_id as cid, course.course_name as cname,'.
                           'course.course_year as cyear, course.course_session as csession,'.
                           'teacher.teacher_name as tname, teacher.teacher_id as tid')
                    ->paginate(1);
                $this->assign('list', $list);
            }
            elseif($role==1){
                $list=Db::table(['course'=>'course', 'stu_course'=>'stu_course'])
                    ->where('stu_course.teacher_id', '=', $id)
                    ->where('course.course_id = stu_course.course_id')
                    ->field('course.course_id as cid, course.course_name as cname,'.
                           'course.course_year as cyear, course.course_session as csession,'.
                           'course.stu_num as sn')
                    ->group('stu_course.course_id, stu_course.teacher_id')
                    ->paginate(1);
                $this->assign('list', $list);
            }
            else{
                $list=Db::name('account')
                    ->paginate(1);
                $this->assign('list', $list);
            }
            return $this->fetch('Main'.Session::get('loginRole'));
        }
        else{
            $this->redirect('index/index/index', ['isSuccess' => 0]);
        }
    }
    
    public function getInfo(){
        if(Session::has('loginId')&&Session::has('loginRole')){
            $id=Session::get('loginId');
            $role=Session::get('loginRole');
            $usr=Db::name('account')
                    ->where('id', $id)
                    ->where('role', $role)
                    ->find();
            $data['pwd']=$usr['password'];
            $data['email']=$usr['email'];
            $data['id']=$id;
            if($role==0){
                $stu=Db::name('student')
                    ->where('stu_id', $id)->find();
                $data['name']=$stu['stu_name'];
            }
            elseif($role==1){
                $tea=Db::name('teacher')->where('teacher_id','=',$id)->find();
                $data['name']=$tea['teacher_name'];
            }
            $this->assign('data', $data);
            return $this->fetch('Info'.$role);
        }
        else{
            $this->redirect('index/index/index', ['isSuccess' => 0]);
        }
    }
    
    public function changeInfo(){
        if(Session::has('loginId')&&Session::has('loginRole')){
            $id=Session::get('loginId');
            $role=Session::get('loginRole');
            if(isset($_POST['email'])){
                Db::name('account')
                    ->where('id',$id)
                    ->where('role',$role)
                    ->update(['email'=>$_POST['email']]);
                $data['email']=$_POST['email'];
            }
            if(isset($_POST['pwd'])){
                Db::name('account')
                    ->where('id',$id)
                    ->where('role',$role)
                    ->update(['password'=>$_POST['pwd']]);
                $data['pwd']=$_POST['pwd'];
            }
            return json($data);
        }
        else{
            $this->redirect('index/index/index', ['isSuccess' => 0]);
        }
    }
    
    public function homework($cid, $tid){
        if(Session::has('loginId')&&Session::has('loginRole')){
            $id=Session::get('loginId');
            $role=Session::get('loginRole');
            if($role==0){
                $list=Db::table(['homework'=>'homework'])
                    ->where('teacher_id','=',$tid)
                    ->where('course_id','=',$cid)
                    ->field('start_date as sd, end_date as ed, hw_info as info')
                    ->paginate(2);
                $this->assign('list', $list);
                $this->assign('tid', $tid);
                $this->assign('cid', $cid);
            }
            elseif($role==1){
                $list=Db::table(['homework'=>'homework'])
                    ->where('teacher_id','=',$id)
                    ->where('course_id','=',$cid)
                    ->field('start_date as sd, end_date as ed, hw_info as info, '.
                           'extra_file as ex')
                    ->paginate(2);
                $this->assign('list', $list);
                $this->assign('cid', $cid);
                $this->assign('syear', date("Y"));
            }
            return $this->fetch('homework'.Session::get('loginRole'));
        }
        else{
            $this->redirect('index/index/index', ['isSuccess' => 0]);
        }
    }
    
    public function getScore($cid, $sd, $tid){
        if(Session::has('loginId')&&Session::has('loginRole')){
            $id=Session::get('loginId');
            $role=Session::get('loginRole');
            if($role==0){
                $list=Db::table('stu_homework')
                    ->where('stu_id','=',$id)
                    ->where('teacher_id', '=', $tid)
                    ->where('course_id', '=', $cid)
                    ->where('start_date', '=', $sd)
                    ->paginate(1);
                $this->assign('list', $list);
                $this->assign('cid', $cid);
                $this->assign('sd', $sd);
                $this->assign('tid', $tid);
            }
            elseif($role==1){
                $list=Db::table('stu_homework')
                    ->where('teacher_id', '=', $id)
                    ->where('course_id', '=', $cid)
                    ->where('start_date', '=', $sd)
                    ->count();
                $pageNum=ceil($list / 10);
                $this->assign('pageNum', $pageNum);
                $this->assign('cid', $cid);
                $this->assign('sd', $sd);
            }
            return $this->fetch('score'.Session::get('loginRole'));
        }
        else{
            $this->redirect('index/index/index', ['isSuccess' => 0]);
        }
    }
    
    public function getPage(){
        if(Session::has('loginId')&&Session::has('loginRole')){
            $id=Session::get('loginId');
            $role=Session::get('loginRole');
            if($role==1){
                $cid=$_POST['cid'];
                $sd=$_POST['sd'];
                $pc=$_POST['page'];
                $list=Db::table('stu_homework')
                    ->where('teacher_id', '=', $id)
                    ->where('course_id', '=', $cid)
                    ->where('start_date', '=', $sd)
                    ->limit(10)
                    ->page($pc)
                    ->select();
                $content='';
                $arr=['stu_id', 'submit_date', 'extra_file', 'score', 'comment', 'hand_in', 'hand_after'];
                foreach($list as $item){
                    $content=$content.'<tr>';
                    foreach($arr as $col){
                        if($col=='score' || $col=='comment'){
                            $content=$content.'<td><input class="form-control mb-0" style="min-width:3.5rem;" type="input" value="'.$item[$col].'" readonly></td>';
                        }
                        elseif($col=='hand_in' || $col=='hand_after'){
                            if($item[$col]==0)
                                $content=$content.'<td><input class="text-danger form-control mb-0" style="min-width:3.5rem;" type="input" value="'.$item[$col].'" readonly></td>';
                            else
                                $content=$content.'<td><input class="text-success form-control mb-0" style="min-width:3.5rem;" type="input" value="'.$item[$col].'" readonly></td>';
                        }
                        else
                            $content=$content.'<td>'.$item[$col].'</td>';
                    }
                    $content=$content.'</tr>';
                }
                return $content;
            }
        }
        else{
            $this->redirect('index/index/index', ['isSuccess' => 0]);
        }
    }
    
    public function uptScore(){
        $id=Session::get('loginId');
        $tags=$_POST['tags'];
        $cid=$_POST['cid'];
        $sd=$_POST['sd'];
        foreach($tags as $tag){
            $obj=json_decode($tag);
            Db::table('stu_homework')
                ->where('teacher_id', '=', $id)
                ->where('course_id', '=', $cid)
                ->where('start_date', '=', $sd)
                ->where('stu_id', '=', $obj->{'stu_id'})
                ->data(['score'=>$obj->{'score'}, 'comment'=>$obj->{'comment'}, 
                       'hand_in'=>$obj->{'hand_in'}, 'hand_after'=>$obj->{'hand_after'}])
                ->update();
        }
        return json(['msg'=>'成绩单更新成功']);
    }
    
}
