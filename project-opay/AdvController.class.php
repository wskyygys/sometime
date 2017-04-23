<?php
namespace Admin\Controller;
include_once "PublicData.php";
include_once"PublicFunction.php";

/**
 * co short summary.
 *                  厂商控制器
 * co description.
 *
 * @version 1.0
 * @author 华亮
 */
class AdvController extends AdminController
{   

    public function index()
    {   
        $model = M();
        $model = D('adlist2');
        $data = array('coid');
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'name');
        $selectwhere = $selectwhere_map[0];
       // $map = $selectwhere_map[1];
        
        $data = array('telestatus'=>$selectwhere['telestatus'],'appname'=>$selectwhere['appname'],'id'=>$selectwhere['id'],'coid'=>$selectwhere['coid']);
        //模拟数据库操作
        $map['coid']    =  array('like', '%'.(string)$_GET['name'].'%');
        if($map == null)
        {   
            $list   =   $this->lists($model->where($selectwhere),$selectwhere);
            // print_r( $sql =$model->_sql());
        }
        else
        {
            $list   =   $this->lists($model->where($map),$map);
        }
        $gamelist = M('gamelist')->field('appid,name')->select();
        $colist = M('colist')->field('coid,name')->select();
        $index = -1;
        foreach($list as $k=>$value){
            $index ++;
            $url=explode('_',$value['url']);
            unset($url['0']);
           if($value['kpstatus']==1){
               $list[$k]['kpstatus'] = '开启';
           }else{
               $list[$k]['kpstatus'] = '关闭';
           }
           if($value['fhadstatus']==1){
               $list[$k]['fhadstatus'] = '开启';
           }else{
               $list[$k]['fhadstatus'] = '关闭';
           }
           if($value['cpadstatus']==1){
               $list[$k]['cpadstatus'] = '开启';
           }else{
               $list[$k]['cpadstatus'] = '关闭';
           }
           if($value['installadstatus']==1){
               $list[$k]['installadstatus'] = '开启';
           }else{
               $list[$k]['installadstatus'] = '关闭';
           }
           if($value['telstatus']==1){
               $list[$k]['telstatus'] = '开启';
           }else{
               $list[$k]['telstatus'] = '关闭';
           }
            foreach($colist as $v)
            {
                if($value['coid'] == $v['coid'])
                {
                     $list[$k]['coid'] = $v['name'];
                     $list[$k]['coidid'] = $v['coid'];
                 }
            }
           
            $glist=explode('_',$list[$k]['appid']);
            unset($glist[0]);
            foreach($gamelist as $gamekey=>$gamevalue){
                foreach($glist as $glistvalue){
                if($glistvalue == $gamevalue['appid']){
                    $app[$index] .= '_'.$gamevalue['name']; 
                }
                }
                $list[$index]['appid'] = $app[$index];
            }
            
            
        }
        $colist = M('colist')->field('coid,name')->select();
        $this->assign('colist',$colist);
        $this->assign('data',$data);
        $this->assign('appname',$appname);
        $this->assign('name',$name);
        $this->assign('list',$list);
        Cookie('__Advforward__',$_SERVER['REQUEST_URI']);
        $this->display();   
    } 
    
    public function add(){
      if($_POST){
            $data = I('post.');
            //print_r($data);
            //exit();
            //厂商ID
            $coid = $data['coid'];
            //游戏ID
            $app = $data['appid'];
            //判断选择的厂商下的游戏有无设置广告
            $adapp = M('adlist2')->field('coid,appid')->where('coid='.$coid)->select();
            if($adapp){
               foreach($adapp as $k=>$v){
                foreach($app as $k1=>$v1){
                if($v == $v1){
                    $this->error('我很烦恼的告诉你:你选的游戏,广告已经存在,请查看后再另选一个');
                  }
                }
              }
            }
         
            //判断有无选择游戏
            $game = count($data['appid']);
            if($game == 0){
                $this->error('我很烦恼的告诉你:麻烦选一个游戏,');
            }
            //判断空值，默认为0
            if($data['kptime']==''){
                $data['kptime'] = 0;
            }
            if($data['cpadtime']==''){
                $data['cpadtime'] = 0;
            }
            if($data['cpadshowtime']==''){
                $data['cpadshowtime'] = 0;
            }
            if($data['installshowtime']==''){
                $data['installshowtime'] = 0;
            }
            if($data['apkshowtime']==''){
                $data['apkshowtime'] = 0;
            }
            if($data['updateshowtime']==''){
                $data['updateshowtime'] = 0;
            }
            if($data['useshowtime']==''){
                $data['useshowtime'] = 0;
            }
            if($data['usemessage']==''){
                $data['usemessage'] = 0;
            }
            if($data['updatemessage']==''){
                $data['updatemessage'] = 0;
            }
            if($data['apkmessage']==''){
                $data['apkmessage'] = 0;
            }
            
            //判断状态，然后判断提交数据是否符合要求
            $kp = count($data['kp']);
            $fh = count($data['fh']);
            $cp = count($data['cp']);
            $ins = count($data['ins']);
            if($data['kpstatus']==1){
                if($kp == 0){
                    $this->error('你选择了开启开屏广告,麻烦选下广告-_-!');
                }
            }
            if($data['fhadstatus']==1){
                if($fh!=1){
                    $this->error('你选择了开启返回广告,返回广告只能选一个-_-!');
                }
            }
            if($data['cpadstatus']==1){
                if($cp!=1){
                    $this->error('你选择了开启插屏广告,插屏广告只能选一个-_-!');
                }
            }
            if($data['installadstatus']==1){
                if($ins!=1){
                    $this->error('你选择了开启安装下载广告,安装广告只能选一个-_-!');
                }
            }
          //拼接游戏ID
            foreach($data['appid'] as $v){
                $appid .= '_'.$v;
            }
            //拼接KPURL CURL
            $photonumber = 0;
            foreach($data['kp'] as $k=>$v){
                $photonumber ++;
                $data['kpurl'] .= '_'.$v;
                $data['curl'] .= '_'.$v;
            }
            //赋值
            $data['teltype'] .= '_'.$data['teltype1'];
            $data['teltype'] .= '_'.$data['teltype2'];
            $data['teltype'] .= '_'.$data['teltype3'];
            $data['fhadurl'] = $data['fh'][0];
            $data['cpadurl'] = $data['cp'][0];
            $data['installadurl'] = $data['ins'][0];
            $data['appid']=$appid;
            $data['date']=time();
            if($photonumber==''){
                  $photonumber = 0;  
            }
            $data['kpnumber'] = $photonumber;
            $data['telinprocessstatus'] .= '_'.$data['telinprocessstatus1']; 
            $data['telinprocessstatus'] .= '_'.$data['telinprocessstatus2']; 
            $data['telinprocessstatus'] .= '_'.$data['telinprocessstatus3']; 
           
            $model=M('adlist2');
            $modeldata= $model->add($data);
            //   print_r($a=M()->_sql());
               if($modeldata!=false )
            {
                $this->success('新增成功', Cookie('__Advforward__'));
            }else
            {
                    $this->error('新增失败');
            }
           
        }else{
            //获取厂商/游戏
            $gamelist = M('gamelist')->field('id,appid,name')->select();
            $colist = M('colist')->field('id,name,coid,appid')->select();
            $kplog = M('adpicture')->field('id,name,url,curl')->where("type='kplog'")->select();
            $kploge = M('adpicture')->field('id,name,url,curl')->where("type='kploge'")->select();
            $kplist = M('adpicture')->field('id,name,url,curl')->where("type='kpad'")->select();
            $fhlist = M('adpicture')->field('id,name,url,curl')->where("type='fhad'")->select();
            $cplist = M('adpicture')->field('id,name,url,curl')->where("type='cpad'")->select();
            $installlist = M('adpicture')->field('id,name,url,curl')->where("type='installad'")->select();
            $tellist = M('adpicture')->field('id,name,url,curl')->where("type='telad'")->select();
          
            $this->assign('kplog',$kplog);
            $this->assign('kploge',$kploge);
            $this->assign('kpad',$kplist);
            $this->assign('fhad',$fhlist);
            $this->assign('cpad',$cplist);
            $this->assign('inad',$installlist);
            $this->assign('telad',$tellist);
            $this->assign('colist',$colist);
            $this->assign('gamelist',$gamelist);
            $this->display(); 
        }
        
    }
    
    public function edit(){
        if(IS_POST)
        {
            $data = I('post.');
            $id = I('post.id');
          
            //if(!$data['fhurl'])){
            //    $data['kpurl'] = I('post.kpurl');
            //}
            
           
            $data['fh'] = I('post.fh');
            $data['ins'] = I('post.ins');
            $data['cp'] = I('post.cp');
            //$data['kp'] = I('post.kp');
            //$data['kp'] = I('post.kp');
            //$data['kp'] = I('post.kp');
            //$data['kp'] = I('post.kp');
            if(!$data['telapkurl']){
              $data['telapkurl'] = I('post.telapkurl');
            }
            if(!$data['telapkpicurl']){
                $data['telapkpicurl'] = '';
            }
            if(!$data['updateurl']){
                $data['updateurl'] = '';
            }
            if(!$data['updatepicurl']){
                $data['updatepicurl'] = '';
            }
            if(!$data['useurl']){
                $data['useurl'] = '';
            }
            if(!$data['usepicurl']){
                $data['usepicurl'] = '';
            }
            //厂商ID
            $coid = $data['coid'];
            //游戏ID
            $app = $data['appid'];
            //判断选择的厂商下的游戏有无设置广告
            $adapp = M('adlist2')->field('coid,appid')->where('coid='.$coid)->select();
            //if($adapp){
            //    foreach($adapp as $k=>$v){
            //        foreach($app as $k1=>$v1){
            //            if($v = $v1){
            //                $this->error('我很烦恼的告诉你:你选的游戏,广告已经存在,请查看后再另选一个');
            //            }
            //        }
            //    }
            //}
            
            //判断有无选择游戏
            $game = count($data['appid']);
            if($game == 0){
                $this->error('我很烦恼的告诉你:麻烦选一个游戏,');
            }
            //判断空值，默认为0
            if($data['kptime']==''){
                $data['kptime'] = 0;
            }
            if($data['cpadtime']==''){
                $data['cpadtime'] = 0;
            }
            if($data['cpadshowtime']==''){
                $data['cpadshowtime'] = 0;
            }
            if($data['installshowtime']==''){
                $data['installshowtime'] = 0;
            }
            if($data['apkshowtime']==''){
                $data['apkshowtime'] = 0;
            }
            if($data['updateshowtime']==''){
                $data['updateshowtime'] = 0;
            }
            if($data['useshowtime']==''){
                $data['useshowtime'] = 0;
            }
            if($data['usemessage']==''){
                $data['usemessage'] = 0;
            }
            if($data['updatemessage']==''){
                $data['updatemessage'] = 0;
            }
            if($data['apkmessage']==''){
                $data['apkmessage'] = 0;
            }
            
            //判断状态，然后判断提交数据是否符合要求
            $kp = count($data['kp']);
            $fh = count($data['fh']);
            $cp = count($data['cp']);
            $ins = count($data['ins']);
            if($data['kpstatus']==1){
                if($kp == 0){
                    $this->error('你选择了开启开屏广告,麻烦选下广告-_-!');
                    exit();
                }
            }
            if($data['fhadstatus']==1){
                if($fh !=1){
                    $this->error('你选择了开启返回广告,请选择广告,而且只能选一个-_-!');
                    exit();
                }
            }
            if($data['cpadstatus']==1){
                if($cp!=1){
                    $this->error('你选择了开启插屏广告,插屏广告只能选一个-_-!');
                    exit();
                }
            }
            if($data['installadstatus']==1){
                if($ins!=1){
                    $this->error('你选择了开启安装下载广告,安装广告只能选一个-_-!');
                    exit();
                }
            }
            //拼接游戏ID
            foreach($data['appid'] as $v){
                $appid .= '_'.$v;
            }
            //拼接KPURL CURL
            $data['kp'] = I('post.kp');
            if($data['kp']==''){
                $data['kpurl'] ='';
                $data['curl']='';
            }else{
                $photonumber = 0;
                foreach($data['kp'] as $k=>$v){
                    $photonumber ++;
                    $data['kpurl'] .= '_'.$v;
                    $data['curl'] .= '_'.$v;
                }
            }
            if($photonumber==''){
                $photonumber = 0;
            }
            //赋值
            $data['teltype'] .= '_'.$data['teltype1'];
            $data['teltype'] .= '_'.$data['teltype2'];
            $data['teltype'] .= '_'.$data['teltype3'];
            $data['fhadurl'] = $data['fh'][0];
            $data['cpadurl'] = $data['cp'][0];
            $data['installadurl'] = $data['ins'][0];
            $data['appid']=$appid;
            $data['date']=time();
            $data['kpnumber'] = $photonumber;
            $data['telinprocessstatus'] .= '_'.$data['telinprocessstatus1']; 
            $data['telinprocessstatus'] .= '_'.$data['telinprocessstatus2']; 
            $data['telinprocessstatus'] .= '_'.$data['telinprocessstatus3']; 
            
            $model=M('adlist2');
            $modeldata= $model->where('id='.$id)->save($data);
            //   print_r($a=M()->_sql());
            if($modeldata!=false )
            {
                $this->success('编辑成功', Cookie('__Advforward__'));
            }else
            {
                $this->error('编辑失败');
            }
           
        
        
        }
        else
        {
             $id = I('get.id');            
             //获取页面信息
             $kplog = M('adpicture')->field('id,name,url,curl')->where("type='kplog'")->select();
             $kploge = M('adpicture')->field('id,name,url,curl')->where("type='kploge'")->select();
             $gamelist = M('gamelist')->field('id,appid,name')->select();
             $colist = M('colist')->field('id,name,coid,appid')->select();
             $kplist = M('adpicture')->field('id,name,url,curl')->where("type='kpad'")->select();
             $fhlist = M('adpicture')->field('id,name,url,curl')->where("type='fhad'")->select();
             $cplist = M('adpicture')->field('id,name,url,curl')->where("type='cpad'")->select();
             $installlist = M('adpicture')->field('id,name,url,curl')->where("type='installad'")->select();
             $tellist = M('adpicture')->field('id,name,url,curl')->where("type='telad'")->select();
             //获取对应信息
             $adlist = M('adlist2')->find($id);
             $appid = explode('_',$adlist['appid']);
             unset($appid[0]);
             foreach($gamelist as $k=>$v){
                foreach($appid as $v1){
                if($v['appid'] == $v1){
                    $gamelist[$k]['id2'] = $v1;
                    }
                }
             }
             $kpid = explode('_',$adlist['kpurl']);
             unset($kpid[0]);
             foreach($kplist as $k=>$v){
                 foreach($kpid as $v1){
                     if($v['id'] == $v1){
                         $kplist[$k]['id2'] = $v1;
                     }
                 }
             }
             foreach($kplog as $k=>$v){
                if($adlist['kplog']==$v['id']){
                    $kplog[$k]['id2'] = $v['id'];
                }
             
             }
             foreach($kploge as $k=>$v){
                 if($adlist['kploge']==$v['id']){
                     $kploge[$k]['id2'] = $v['id'];
                 }
                 
             }
             $telinprocessstatus = explode('_',$adlist['telinprocessstatus']);
             unset($telinprocessstatus[0]);
             $adlist['telinprocessstatus1'] = $telinprocessstatus[1];
             $adlist['telinprocessstatus2'] = $telinprocessstatus[2];
             $adlist['telinprocessstatus3'] = $telinprocessstatus[3];
             $type = explode('_',$adlist['teltype']);
             unset($type[0]);
             $adlist['teltype1'] = $type[1];
             $adlist['teltype2'] = $type[2];
             $adlist['teltype3'] = $type[3];
             $adstatus = \PublicData::$adstatus;
             $cpstatus = \PublicData::$cpagstatus;
             $adtime = \PublicData::$adtime;
             $this->assign('adtime',$adtime);
             $this->assign('kplog',$kplog);
             $this->assign('kploge',$kploge);
             $this->assign('cpstatus',$cpstatus);
             $this->assign('adstatus',$adstatus);
             $this->assign('adlist',$adlist);
             $this->assign('kpad',$kplist);
             $this->assign('fhad',$fhlist);
             $this->assign('cpad',$cplist);
             $this->assign('inad',$installlist);
             $this->assign('telad',$tellist);
             $this->assign('colist',$colist);
             $this->assign('gamelist',$gamelist);
             $this->display(); 
        
        }
    }
    
    public function photo(){
        $model = M();
        $model = D('adpicture');
        $data = array('type');
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'name');
        $selectwhere = $selectwhere_map[0];
        $map = $selectwhere_map[1];
        //模拟数据库操作
        if($map == null)
        {   
            $advlist   =   $this->lists($model->where($selectwhere),$selectwhere);
            // print_r( $sql =$model->_sql());
        }
        else
        {
            $advlist   =   $this->lists($model->where($map),$map);
        }
        
        //$selectwhere = I('get.');
        //$advlist = M('adpicture')->where($selectwhere)->select();
        $advtype = \PublicData::$advstatic;
        foreach($advlist as $k=>$v){
        foreach($advtype as $v1){
            if($v['type']==$v1['id']){
            $advlist[$k]['type'] = $v1['name'];
            }
          }
        }
        //print_r($advtype);
        Cookie('__pgotoforward__',$_SERVER['REQUEST_URI']);
        $this->assign('type',$advtype);
        $this->assign('list',$advlist);
        
        $this->display();
    
    
    }
    
    public function addad(){
        if(IS_POST){
            $data = I('post.');
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize = 31457280;
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
            $data['url'] = 'http://'.$_SERVER['HTTP_HOST'].$rootPath.$adlist['photo']['savepath'].$adlist['photo']['savename'];
          
            $adid = M('adpicture')->add($data);
            if($adid){
                $this->success('资源上传成功', Cookie('__pgotoforward__'));
                }else{
                $this->error('资源上传失败');
            }

        
        
        
        }else{
            
            $this->display();
        
        }
    
    
    }
    
    public function editphoto(){
    if(IS_POST){
        $data = I('post.');
        $files = $_FILES;
        $data['type'] = I('post.adtype');
        if(!empty($files['photo']['name'])){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 31457280;
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
        $data['url'] = 'http://'.$_SERVER['HTTP_HOST'].$rootPath.$adlist['photo']['savepath'].$adlist['photo']['savename'];
        }
        $adid = M('adpicture')->where('id='.$data['id'])->save($data);
        if($adid !=='' && $adid !==null ){
            $this->success('编辑成功',Cookie('__pgotoforward__'));
        }else{
            $this->error('编辑失败');
        }
        
       
    
    
    }else{
        //$adid = I('get.id');
        //$adlist = M('adpicture')->find($adid);
        //$name = explode('_',$adlist['name']);
        //    unset($name[0]);
        //    $url = explode('_',$adlist['url']);
        //    unset($url[0]);
        //    $curl = explode('_',$adlist['curl']);
        //    unset($curl[0]);
        //    $n = count($name);
        //    for($i=1;$i<=$n;$i++){
        //        $arr[] = array('name'=>$name[$i],'url'=>$url[$i],'curl'=>$curl[$i]);
        //    } 
        //    foreach($arr as $k=>$v){
        //        $key = $k+1;
        //        $addata['name'.$key] =$v['name'];
        //        $addata['url'.$key] =$v['url'];
        //        $addata['curl'.$key] =$v['curl'];
                
        //    }
            //广告类型
        $adid = I('get.id');
        $adlist = M('adpicture')->find($adid);
        if(!$adlist){
            $this->error('参数错误');
        } 
        $advtype = \PublicData::$advstatic;
        $this->assign('advlist',$adlist);
        $this->assign('advtype',$advtype);
        $this->display();
           
        
        }    
    }
    
    public function change(){
        if(IS_POST)
        {
            $data = I('post.');
            foreach($data['kpggsys'] as $k=>$v){
                $kpggsys .='_'.$v; 
            }
            $data['kpggsys'] = $kpggsys;
            foreach($data['kpggphone'] as $k=>$v){
                $kpggphone .='_'.$v; 
            }
            $data['kpggphone'] = $kpggphone;
            foreach($data['kpggweb'] as $k=>$v){
                $kpggweb .='_'.$v; 
            }
            $data['kpggweb'] = $kpggweb;
            foreach($data['fhggsys'] as $k=>$v){
                $fhggsys .='_'.$v; 
            }
            $data['fhggsys'] = $fhggsys;
            foreach($data['fhggphone'] as $k=>$v){
                $fhggphone .='_'.$v; 
            }
            $data['fhggphone'] = $fhggphone;
            foreach($data['fhggweb'] as $k=>$v){
                $fhggweb .='_'.$v; 
            }
            $data['fhggweb'] = $fhggweb;
            foreach($data['cpggsys'] as $k=>$v){
                $cpggsys .='_'.$v; 
            }
            $data['cpggsys'] = $cpggsys;
            foreach($data['cpggphone'] as $k=>$v){
                $cpggphone .='_'.$v; 
            }
            $data['cpggphone'] = $cpggphone;
            foreach($data['cpggweb'] as $k=>$v){
                $cpggweb .='_'.$v; 
            }
            $data['cpggweb'] = $cpggweb;
            foreach($data['installsys'] as $k=>$v){
                $installsys .='_'.$v; 
            }
            $data['installsys'] = $installsys;
            foreach($data['installphone'] as $k=>$v){
                $installphone .='_'.$v; 
            }
            $data['installphone'] = $installphone;
            foreach($data['installweb'] as $k=>$v){
                $installweb .='_'.$v; 
            }
            $data['installweb'] = $installweb;
            foreach($data['telinstallsys'] as $k=>$v){
                $telinstallsys .='_'.$v; 
            }
            $data['telinstallsys'] = $telinstallsys;
            foreach($data['telinstallphone'] as $k=>$v){
                $telinstallphone .='_'.$v; 
            }
            $data['telinstallphone'] = $telinstallphone;
            foreach($data['telinstallweb'] as $k=>$v){
                $telinstallweb .='_'.$v; 
            }
            $data['telinstallweb'] = $telinstallweb;
            foreach($data['telupdatesys'] as $k=>$v){
                $telupdatesys .='_'.$v; 
            }
            $data['telupdatesys'] = $telupdatesys;
            foreach($data['telupdatephone'] as $k=>$v){
                $telupdatephone .='_'.$v; 
            }
            $data['telupdatephone'] = $telupdatephone;
            foreach($data['telupdateweb'] as $k=>$v){
                $telupdateweb .='_'.$v; 
            }
            $data['telupdateweb'] = $telupdateweb;
            foreach($data['telusesys'] as $k=>$v){
                $telusesys .='_'.$v; 
            }
            $data['telusesys'] = $telusesys;
            foreach($data['telusephone'] as $k=>$v){
                $telusephone .='_'.$v; 
            }
            $data['telusephone'] = $telusephone;
            foreach($data['teluseweb'] as $k=>$v){
                $teluseweb .='_'.$v; 
            }
            $data['teluseweb'] = $teluseweb;
            //$data['kpggcity'] =  '_'.$data['kpggcity'];
            //$data['fhggcity'] =  '_'.$data['fhggcity'];
            //$data['cpggcity'] =   '_'.$data['cpggcity'];
            //$data['installcity'] =  '_'.$data['installcity'];
            //$data['telinstallcity'] = '_'.$data['telinstallcity'];
            //$data['telupdatecity'] = '_'.$data['telupdatecity'];
            //$data['telusecity'] = '_'.$data['telusecity'];
            $adlist = M('adlist2')->save($data);
            if(isset($adlist))
            {
                $this->success('广告限制设置成功', Cookie('__Advforward__'));
            }else
            {
                $this->error('广告限制设置失败');
            }
            
        }
        else
        {
            $id = I('get.id');            
            //获取页面信息
            $adlist = M('adlist2')->find($id);
            $os_info =M('androidverion')->select();
            $os_moblie = M('moblieversion')->field('id,version')->select();
            $net_info = \PublicData::$net_info;
            //开屏
              $os_info1 = $os_info;
              $os_moblie1 =   $os_moblie;
              $net_info1 =  $net_info;
            //返回
              $os_info2 = $os_info;
              $os_moblie2 =   $os_moblie;
              $net_info2 =  $net_info;
            //插屏
              $os_info3 = $os_info;
              $os_moblie3 =   $os_moblie;
              $net_info3 =  $net_info;
            //安装
              $os_info4 = $os_info;
              $os_moblie4 =   $os_moblie;
              $net_info4 =  $net_info;
            //推荐安装
              $os_info5 = $os_info;
              $os_moblie5 =   $os_moblie;
              $net_info5 =  $net_info;
            //推荐更新
              $os_info6 = $os_info;
              $os_moblie6 =   $os_moblie;
              $net_info6 =  $net_info;
            //推荐使用
              $os_info7 = $os_info;
              $os_moblie7 =   $os_moblie;
              $net_info7 =  $net_info;
            //开屏
            $kpggsys = explode('_',$adlist['kpggsys']);
            unset($kpggsys[0]);
            foreach($os_info1 as $k=>$v){
                foreach($kpggsys as $v1){
                if($v['id'] == $v1){
                    $os_info1[$k]['id2'] = $v1;
                }
                }
            }
            $kpggphone = explode('_',$adlist['kpggphone']);
            unset($kpggphone[0]);
            foreach($os_moblie1 as $k=>$v){
                foreach($kpggphone as $v1){
                    if($v['id'] == $v1){
                        $os_moblie1[$k]['id2'] = $v1;
                    }
                }
            }
            $kpggweb = explode('_',$adlist['kpggweb']);
            unset($kpggweb[0]);
            foreach($net_info1 as $k=>$v){
                foreach($kpggweb as $v1){
                    if($v['id'] == $v1){
                        $net_info1[$k]['id2'] = $v1;
                    }
                }
            }
            //返回
            $fhggsys = explode('_',$adlist['fhggsys']);
            unset($fhggsys[0]);
            foreach($os_info2 as $k=>$v){
                foreach($fhggsys as $v1){
                    if($v['id'] == $v1){
                        $os_info2[$k]['id2'] = $v1;
                    }
                }
            }
            $fhggphone = explode('_',$adlist['fhggphone']);
            unset($fhggphone[0]);
            foreach($os_moblie2 as $k=>$v){
                foreach($fhggphone as $v1){
                    if($v['id'] == $v1){
                        $os_moblie2[$k]['id2'] = $v1;
                    }
                }
            }
            $fhggweb = explode('_',$adlist['fhggweb']);
            unset($fhggweb[0]);
            foreach($net_info2 as $k=>$v){
                foreach($fhggweb as $v1){
                    if($v['id'] == $v1){
                        $net_info2[$k]['id2'] = $v1;
                    }
                }
            }
            //插屏
            $cpggsys = explode('_',$adlist['cpggsys']);
            unset($cpggsys[0]);
            foreach($os_info3 as $k=>$v){
                foreach($cpggsys as $v1){
                    if($v['id'] == $v1){
                        $os_info3[$k]['id2'] = $v1;
                    }
                }
            }
            $cpggphone = explode('_',$adlist['cpggphone']);
            unset($cpggphone[0]);
            foreach($os_moblie3 as $k=>$v){
                foreach($cpggphone as $v1){
                    if($v['id'] == $v1){
                        $os_moblie3[$k]['id2'] = $v1;
                    }
                }
            }
            $cpggweb = explode('_',$adlist['cpggweb']);
            unset($cpggweb[0]);
            foreach($net_info3 as $k=>$v){
                foreach($cpggweb as $v1){
                    if($v['id'] == $v1){
                        $net_info3[$k]['id2'] = $v1;
                    }
                }
            }
            //安装下载
            $installsys = explode('_',$adlist['installsys']);
            unset($installsys[0]);
            foreach($os_info4 as $k=>$v){
                foreach($installsys as $v1){
                    if($v['id'] == $v1){
                        $os_info4[$k]['id2'] = $v1;
                    }
                }
            }
            $installphone = explode('_',$adlist['installphone']);
            unset($installphone[0]);
            foreach($os_moblie4 as $k=>$v){
                foreach($installphone as $v1){
                    if($v['id'] == $v1){
                        $os_moblie4[$k]['id2'] = $v1;
                    }
                }
            }
            $installweb = explode('_',$adlist['installweb']);
            unset($installweb[0]);
            foreach($net_info4 as $k=>$v){
                foreach($installweb as $v1){
                    if($v['id'] == $v1){
                        $net_info4[$k]['id2'] = $v1;
                    }
                }
            }
            //通知栏下载
            $telinstallsys = explode('_',$adlist['telinstallsys']);
            unset($telinstallsys[0]);
            foreach($os_info5 as $k=>$v){
                foreach($telinstallsys as $v1){
                    if($v['id'] == $v1){
                        $os_info5[$k]['id2'] = $v1;
                    }
                }
            }
            $telinstallphone = explode('_',$adlist['telinstallphone']);
            unset($telinstallphone[0]);
            foreach($os_moblie5 as $k=>$v){
                foreach($telinstallphone as $v1){
                    if($v['id'] == $v1){
                        $os_moblie5[$k]['id2'] = $v1;
                    }
                }
            }
            $telinstallweb = explode('_',$adlist['telinstallweb']);
            unset($telinstallweb[0]);
            foreach($net_info5 as $k=>$v){
                foreach($telinstallweb as $v1){
                    if($v['id'] == $v1){
                        $net_info5[$k]['id2'] = $v1;
                    }
                }
            }
            
            //通知栏推荐更新
            $telupdatesys = explode('_',$adlist['telupdatesys']);
            unset($telupdatesys[0]);
            foreach($os_info6 as $k=>$v){
                foreach($telupdatesys as $v1){
                    if($v['id'] == $v1){
                        $os_info6[$k]['id2'] = $v1;
                    }
                }
            }
            $telupdatephone = explode('_',$adlist['telupdatephone']);
            unset($telupdatephone[0]);
            foreach($os_moblie6 as $k=>$v){
                foreach($telupdatephone as $v1){
                    if($v['id'] == $v1){
                        $os_moblie6[$k]['id2'] = $v1;
                    }
                }
            }
            $telupdateweb = explode('_',$adlist['telupdateweb']);
            unset($telupdateweb[0]);
            foreach($net_info6 as $k=>$v){
                foreach($telupdateweb as $v1){
                    if($v['id'] == $v1){
                        $net_info6[$k]['id2'] = $v1;
                    }
                }
            }
            //通知栏推荐使用
            $telusesys = explode('_',$adlist['telusesys']);
            unset($telusesys[0]);
            foreach($os_info7 as $k=>$v){
                foreach($telusesys as $v1){
                    if($v['id'] == $v1){
                        $os_info7[$k]['id2'] = $v1;
                    }
                }
            }
            $telusephone = explode('_',$adlist['telusephone']);
            unset($telusephone[0]);
            foreach($os_moblie7 as $k=>$v){
                foreach($telusephone as $v1){
                    if($v['id'] == $v1){
                        $os_moblie7[$k]['id2'] = $v1;
                    }
                }
            }
            $teluseweb = explode('_',$adlist['teluseweb']);
            unset($teluseweb[0]);
            foreach($net_info7 as $k=>$v){
                foreach($teluseweb as $v1){
                    if($v['id'] == $v1){
                        $net_info7[$k]['id2'] = $v1;
                    }
                }
            }
            //print_r($os_info1);
            $this->assign('os_info1',$os_info1);
            $this->assign('os_moblie1',$os_moblie1);
            $this->assign('net_info1',$net_info1);
            $this->assign('os_info2',$os_info2);
            $this->assign('os_moblie2',$os_moblie2);
            $this->assign('net_info2',$net_info2);
            $this->assign('os_info3',$os_info3);
            $this->assign('os_moblie3',$os_moblie3);
            $this->assign('net_info3',$net_info3);
            $this->assign('os_info4',$os_info4);
            $this->assign('os_moblie4',$os_moblie4);
            $this->assign('net_info4',$net_info4);
            $this->assign('os_info5',$os_info5);
            $this->assign('os_moblie5',$os_moblie5);
            $this->assign('net_info5',$net_info5);
            $this->assign('os_info6',$os_info6);
            $this->assign('os_moblie6',$os_moblie6);
            $this->assign('net_info6',$net_info6);
            $this->assign('os_info7',$os_info7);
            $this->assign('os_moblie7',$os_moblie7);
            $this->assign('net_info7',$net_info7);
            $this->assign('adlist',$adlist);
            $this->display(); 
            
        }
    }
    
 
    
  
}
