<?php
namespace app\index\controller;

use think\Controller;
use app\common\model\User;
use think\Db;
use think\facade\Session;
use think\captcha\Captcha;
    
class Index extends Controller
{
    public function index($isSuccess=1)
    {
        if(Session::has('loginId')&&Session::has('loginRole')){
            $this->redirect('index/space/main');
        }
        if(!$isSuccess){
            $this->assign('hint','登录失败');
        }
        else{
            $this->assign('hint','');
        }
        return $this->fetch('index');   
    }

    public function login()
    {
        $code=['student'=>0, 'teacher'=>1, 'admin'=>2];
        $code=$code[$_POST['userType']];
            
        $user = Db::name('account')
            ->where('id', $_POST['userName'])
            ->where('role', $code)
            ->where('password', $_POST['userPwd'])
            ->find();
        $captcha = new Captcha();
        if(!$captcha->check($_POST['userCaptcha']))
        {
            $this->redirect('index/index/index', ['isSuccess' => 0]);
        }
        if(!isset($user)){
            $this->redirect('index/index/index', ['isSuccess' => 0]);
        }
        else{
            Session::set('loginId', $_POST['userName']);
            Session::set('loginRole', $code);
            $this->redirect('index/space/main');
        }
    }
    
    public function logout(){
        if(Session::has('loginId')&&Session::has('loginRole')){
            Session::delete('loginId');
            Session::delete('loginRole');
        }
        $this->redirect('index/index/index', ['isSuccess' => 1]);
    }
    
    //生成验证码
    public function verify(){
        ob_clean();
        $captcha = new Captcha();
        return $captcha->entry();
    }
}
