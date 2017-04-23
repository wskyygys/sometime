<?php
/**
 * CodeController short summary.
 *
 * CodeController description.
 *
 * @version 1.0
 * @author bksen_sjl
 */
namespace Admin\Controller;
class UploadresourcesController extends AdminController
{   
    function publicindex($goodtype,$bt){
        if(!$_GET['p']){
            $_GET['p'] = 0;
        }
        $list = M('findgoods')->where('goodtype='.$goodtype)->page($_GET['p'].',5')->order('id desc')->select();
        $a = M('findgoods')->_Sql();
        $count = M('findgoods')->where('goodtype='.$goodtype)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        $show = $Page->show();// 分页显示输出
        $this->assign('goodtype',$goodtype);
        $this->assign('bt',$bt);// 赋值分页输出
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('list',$list);
        $this->display('Uploadresources:index');
    
    }
  
    
    public function apkadd(){
        if(IS_POST){
            $data = I('post.');
            foreach($data as $k=>$v){
                if($v==''){
                    exit('请填写全部参数');
                }
            }
            $data['status'] = 1;
            $file = $_FILES;
            if($file['icon']['name']!=''||$file['apk']['name']!=''||$file['picture1']['name']!=''||$file['picture2']['name']!=''||$file['picture3']['name']!=''||$file['picture4']['name']!=''||$file['picture5']['name']!=''){
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize = 52428800;
            $upload->rootPath = '/Uploads/';
            $upload->saveName = array('uniqid','');
            $rootPath=$upload->rootPath;
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg','apk');
            $upload->autoSub = true;
            $upload->subName = array('date','Ymd');
            $adlist = $upload->upload();
            if(!$adlist){
                $this->error($upload->getError());
            }
            if($file['icon']['name']==''){
                $this->error('没有上传icon');
            }else{
                if($file['icon']['type']!='image/jpeg'){
                    $this->error('不是图片,请重新上传');
                }
            }
            if($file['apk']['name']==''){
                $this->error('没有上传Apk');
            }else{
                if($file['apk']['type']!='application/octet-stream'){
                   $this->error('不是apk文件,请重新上传'); 
                }
            
            }
            if($file['picture1']['name']!=''||$file['picture2']['name']!=''||$file['picture3']['name']!=''||$file['picture4']['name']!=''||$file['picture5']['name']!=''){
                 if($file['picture1']['name']){
                     if($file['picture1']['type']!='image/jpeg'){
                         $this->error('不是图片,请重新上传');
                     }
                    $data['picture1'] = 'http://'.$_SERVER['HTTP_HOST'].$rootPath.$adlist['picture1']['savepath'].$adlist['picture1']['savename'];
                    }
                 if($file['picture2']['name']){
                     if($file['picture2']['type']!='image/jpeg'){
                         $this->error('不是图片,请重新上传');
                     }
                    $data['picture2'] = 'http://'.$_SERVER['HTTP_HOST'].$rootPath.$adlist['picture2']['savepath'].$adlist['picture2']['savename'];
                 }
                if($file['picture3']['name']){
                    if($file['picture3']['type']!='image/jpeg'){
                        $this->error('不是图片,请重新上传');
                    }
                    $data['picture3'] = 'http://'.$_SERVER['HTTP_HOST'].$rootPath.$adlist['picture3']['savepath'].$adlist['picture3']['savename'];
                 } 
                if($file['picture4']['name']){
                    if($file['picture4']['type']!='image/jpeg'){
                        $this->error('不是图片,请重新上传');
                    }
                    $data['picture4'] = 'http://'.$_SERVER['HTTP_HOST'].$rootPath.$adlist['picture4']['savepath'].$adlist['picture4']['savename'];
                 } 
                if($file['picture5']['name']){
                    if($file['picture5']['type']!='image/jpeg'){
                        $this->error('不是图片,请重新上传');
                    }
                    $data['picture5'] = 'http://'.$_SERVER['HTTP_HOST'].$rootPath.$adlist['picture5']['savepath'].$adlist['picture5']['savename'];
                 } 
            }else{
                $this->error('最少上传一张图片');
            }
            $data['icon'] = 'http://'.$_SERVER['HTTP_HOST'].$rootPath.$adlist['icon']['savepath'].$adlist['icon']['savename'];
            $data['apk'] = 'http://'.$_SERVER['HTTP_HOST'].$rootPath.$adlist['apk']['savepath'].$adlist['apk']['savename'];
          
            }
            $adid = M('findgoods')->add($data);
            if($adid){
                $this->success('资源上传成功', Cookie('__upload__'));
            }else{
                $this->error('资源上传失败');
            }
        }else{
            $goodtype = I('get.goodtype');
            if($goodtype ==''){
                $this->error('参数错误');
            }
            if($goodtype == '1'){
                $bt = '发现栏APP';
            }elseif($goodtype == '2'){
                $bt = '精品推荐';
            }elseif($goodtype == '3'){
                $bt = '排行榜上传';
            }elseif($goodtype == '4'){
                $bt = '搜索上传';
            }elseif($goodtype == '5'){
                $bt = '个人中心';
            }elseif($goodtype == '9'){
                $bt = '公共模块';
            }
            $this->assign('bt',$bt);
            $this->assign('goodtype',$goodtype);
            $this->display('Uploadresources:apkadd');
        }
    }
    public function changeoff(){
        $id = I('get.id');
        $status = I('get.status');
        if($id){
            $chang = M('findgoods')->where(array('id'=>$id))->save(array('status'=>$status));
            if(isset($chang)){
                $this->success('操作成功', Cookie('__upload__'));
            }
        }
        
    }
    public function editapk(){
        if(IS_POST){
            $data = I('post.');
            foreach($data as $k=>$v){
                if($v==''){
                    exit('请填写全部参数');
                }
            }
            $id = $data['id'];
            unset($data['id']);
            $file = $_FILES;
            if($file['icon']['name']!=''||$file['apk']['name']!=''||$file['picture1']['name']!=''||$file['picture2']['name']!=''||$file['picture3']['name']!=''||$file['picture4']['name']!=''||$file['picture5']['name']!=''){
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize = 52428800;
                $upload->rootPath = '/Uploads/';
                $upload->saveName = array('uniqid','');
                $rootPath=$upload->rootPath;
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg','apk');
                $upload->autoSub = true;
                $upload->subName = array('date','Ymd');
                $adlist = $upload->upload();
                if(!$adlist){
                    $this->error($upload->getError());
                }
                if($file['icon']['name']){
                    $data['icon'] = 'http://'.$_SERVER['HTTP_HOST'].$rootPath.$adlist['icon']['savepath'].$adlist['icon']['savename'];
                }
                if($file['icon']['name']){
                    $data['icon'] = 'http://'.$_SERVER['HTTP_HOST'].$rootPath.$adlist['icon']['savepath'].$adlist['icon']['savename'];
                }
                if($file['icon']['name']){
                    $data['apk'] = 'http://'.$_SERVER['HTTP_HOST'].$rootPath.$adlist['apk']['savepath'].$adlist['apk']['savename'];
                }
                if($file['icon']['name']){
                    $data['picture1'] = 'http://'.$_SERVER['HTTP_HOST'].$rootPath.$adlist['picture1']['savepath'].$adlist['picture1']['savename'];
                }
                if($file['icon']['name']){
                    $data['picture2'] = 'http://'.$_SERVER['HTTP_HOST'].$rootPath.$adlist['picture2']['savepath'].$adlist['picture2']['savename'];
                }
                if($file['icon']['name']){
                    $data['picture3'] = 'http://'.$_SERVER['HTTP_HOST'].$rootPath.$adlist['picture3']['savepath'].$adlist['picture3']['savename'];
                }
                if($file['icon']['name']){
                    $data['picture4'] = 'http://'.$_SERVER['HTTP_HOST'].$rootPath.$adlist['picture4']['savepath'].$adlist['picture4']['savename'];
                }
                if($file['icon']['name']){
                    $data['picture5'] = 'http://'.$_SERVER['HTTP_HOST'].$rootPath.$adlist['picture5']['savepath'].$adlist['picture5']['savename'];
                }
            }
              $adid = M('findgoods')->where(array('id'=>$id))->save($data);
            if(isset($adid)){
                $this->success('编辑成功', Cookie('__upload__'));
            }else{
                $this->error('编辑失败');
            }
        }else{
            $id = I('get.id');
            $list = M('findgoods')->find($id);
            $goodtype = $list['goodtype'];
            if($goodtype ==''){
                $this->error('参数错误');
            }
            if($goodtype == '1'){
                $bt = '发现栏APP';
            }elseif($goodtype == '2'){
                $bt = '精品推荐';
            }elseif($goodtype == '3'){
                $bt = '排行榜';
            }elseif($goodtype == '4'){
                $bt = '搜索';
            }elseif($goodtype == '5'){
                $bt = '个人中心';
            }elseif($goodtype == '9'){
                $bt = '公共模块';
            }
            $this->assign('bt',$bt);
            $this->assign('list',$list);
            $this->display('Uploadresources:editapk');
        }
    }
    public function publicadd(){
        if(IS_POST){
            $data['goodtype'] = I('post.goodtype');
            $check = I('post.check');
            foreach($check as $k=>$v){
            $goodlist[$k] = M('findgoods')->find($v);
            $goodlist[$k]['spid'] = 9;
            $goodlist[$k]['goodtype'] = $data['goodtype'];
            unset($goodlist[$k]['id']);
            $goodid = M('findgoods')->add($goodlist[$k]);
            }
            if($goodid){
                $this->success('选择成功', Cookie('__upload__'));
            }else{
                $this->error('选择失败');
            }
        }else{
        $goodtype = I('get.goodtype');
        if($goodtype==''){
           $this->error('参数不全');
        }
        if($goodtype == '1'){
            $bt = '发现栏APP';
        }elseif($goodtype == '2'){
            $bt = '精品推荐';
        }elseif($goodtype == '3'){
            $bt = '排行榜';
        }elseif($goodtype == '4'){
            $bt = '搜索';
        }elseif($goodtype == '5'){
            $bt = '个人中心';
        }elseif($goodtype == '9'){
            $bt = '公共模块';
        }
        $list = M('findgoods')->where(array('goodtype'=>9))->select();
        $this->assign('bt',$bt);
        $this->assign('goodtype',$goodtype);
        $this->assign('list',$list);
        $this->display('Uploadresources:publicadd');
        }
    }
    
    public function index(){
        Cookie('__upload__',$_SERVER['REQUEST_URI']);
        $goodtype = 1;
        $bt = '发现栏APP';
        $this->publicindex($goodtype,$bt);
    }
    public function boutique(){
        Cookie('__upload__',$_SERVER['REQUEST_URI']);
        $goodtype = 2;
        $bt = '精品推荐';
        $this->publicindex($goodtype,$bt);
    }
    public function ranking(){
        Cookie('__upload__',$_SERVER['REQUEST_URI']);
        $goodtype = 3;
        $bt = '排行榜';
        $this->publicindex($goodtype,$bt);
    }
    public function search(){
        Cookie('__upload__',$_SERVER['REQUEST_URI']);
        $goodtype = 4;
        $bt = '搜索';
        $this->publicindex($goodtype,$bt);
    }
    public function personal(){
        Cookie('__upload__',$_SERVER['REQUEST_URI']);
        $goodtype = 5;
        $bt = '个人中心';
        $this->publicindex($goodtype,$bt);
    }
    public function publicupload(){
        Cookie('__upload__',$_SERVER['REQUEST_URI']);
        $goodtype = 9;
        $bt = '公共模块';
        $this->publicindex($goodtype,$bt);
    }
}





