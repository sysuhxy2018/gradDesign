<?php
namespace app\index\controller;

use think\Controller;
use think\facade\Session;
use think\Db;
use think\Exception;
    
class Download extends Controller
{
    /*public function dlAll($cid, $sd)
    {
         if(Session::has('loginId')&&Session::has('loginRole')){
            $id=Session::get('loginId');
            $role=Session::get('loginRole');
            if($role==1){
                $sd=preg_replace('/[- :]/','',$sd);
                $path=$_SERVER['DOCUMENT_ROOT'].'/'.'uploads/'.$cid.'/'.$id.'/'.$sd.'/homework';
                if($this->clearDir($path.'/..'))
                {
                    //设置单个压缩文件最大不超过500字节（压缩前）
                    if($packs=$this->packup($path, 10 * 1024 * 1024))
                    {
                        foreach($packs as $pack)
                        {
                            if(file_exists($pack)){
                                header('Content-Description: File Transfer');
                                header('Content-Type: application/octet-stream');
                                header('Content-Disposition: attachment; filename='.basename($pack));
                                header('Content-Transfer-Encoding: binary');
                                header('Expires: 0');
                                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                                header('Pragma: public');
                                header('Content-Length: ' . filesize($pack));
                                
                                ob_clean();
                                flush();
                                readfile($pack);
                                $fp = fopen($pack, "r");
                                while(!feof($fp)) {
                                    echo fgets($fp, 4096);
                                    ob_flush();
                                    flush();
                                }
                                fclose($fp);
                            }
                        }
                    }
                }
            }
         }
        else
        {
            $this->redirect('index/index/index', ['isSuccess' => 0]);
        }
    }*/
    public function dlFile($cid, $sd, $fname){
        if(Session::has('loginId')&&Session::has('loginRole')){
            $id=Session::get('loginId');
            $role=Session::get('loginRole');
            if($role==1){
                $sd=preg_replace('/[- :]/','',$sd);
                $path=$_SERVER['DOCUMENT_ROOT'].'/'.'uploads/'.$cid.'/'.$id.'/'.$sd.'/homework';
                $pack=$path.'/../'.$fname;
                
                            if(file_exists($pack)){
                                header('Content-Description: File Transfer');
                                header('Content-Type: application/octet-stream');
                                header('Content-Disposition: attachment; filename='.basename($pack));
                                header('Content-Transfer-Encoding: binary');
                                header('Expires: 0');
                                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                                header('Pragma: public');
                                header('Content-Length: ' . filesize($pack));
                                
                                ob_clean();
                                flush();
                                readfile($pack);
                                $fp = fopen($pack, "r");
                                while(!feof($fp)) {
                                    echo fgets($fp, 4096);
                                    ob_flush();
                                    flush();
                                }
                                fclose($fp);
                            }
                    
                }
            else
            {
                $this->redirect('index/index/index', ['isSuccess' => 0]);
            }
        }
    }
    
    public function dlAll($cid, $sd){
        $id=Session::get('loginId');
        $role=Session::get('loginRole');
        $sd=preg_replace('/[- :]/','',$sd);
        $path=$_SERVER['DOCUMENT_ROOT'].'/'.'uploads/'.$cid.'/'.$id.'/'.$sd.'/homework';
        $this->clearDir($path.'/..');
        $packs=$this->packup($path, 10 * 1024 * 1024);
        return json($packs);
    }
    
    //清除除子目录外的所有（临时）文件
    private function clearDir($dir){
        if(!file_exists($dir))
            return false;
        $handle = opendir($dir);
        if($handle){
            while(false !== ($file=readdir($handle)))
            {
                if($file != '.' && $file != '..')
                {
                    $nextPath=$dir.'/'.$file;
                    if(!is_dir($nextPath)){
                        unlink($nextPath);
                    }
                }
            }
                
        }
        closedir($handle);
        return true;
    }
    
    private function packup($dir, $maxSize){
        if(!file_exists($dir))
            return 0;
        $handle = opendir($dir);
        $countPacks=0;
        $stack=array();
        $isEmpty=true;
        if($handle){
            $countBytes=0;
            $packName=$dir.'/../tmpHw_'.$this->getTime().'_part';
            $zip=new \ZipArchive();
            $zip->open($packName.$countPacks.'.zip', \ZipArchive::CREATE);
            while(false !== ($file=readdir($handle))){
                if($file != '.' && $file != '..'){
                    $isEmpty=false;
                    $nextPath=$dir.'/'.$file;
                    $countBytes+=filesize($nextPath);
                    if($countBytes > $maxSize){
                        $zip->close();
                        
                        $stack[]=array('filename'=>basename($packName.$countPacks.'.zip'),
                           'filesize'=>filesize($packName.$countPacks.'.zip'));
                        
                        $countPacks++;
                        $countBytes=0;
                        $zip->open($packName.$countPacks.'.zip', \ZipArchive::CREATE);
                        //注意if结束后还是要执行添加文件的操作，所以计数也要先更新
                        $countBytes+=filesize($nextPath);
                    }
                    //重命名被压缩的文件
                    $zip->addFile($nextPath, basename($nextPath));
                }
            }
            $zip->close();
            $stack[]=array('filename'=>basename($packName.$countPacks.'.zip'),
                           'filesize'=>filesize($packName.$countPacks.'.zip'));
            $countPacks++;
        }
        closedir($handle);
        if($isEmpty){
            return 0;
        }
        else{
            return $stack;
        }  
    }
    
    private function getTime(){
        $dt=date('c');
        $spDt=preg_split('/[+]/',$dt);
        $dt=preg_replace('/[- T:]/', '', $spDt[0]);
        return $dt;
    }
               
}
