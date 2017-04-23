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
class CoController extends AdminController
{   
    public function index()
    {   
        $model = M();
        $model = D('colist');
        $data = array('telestatus','appname','id','coid');
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'name');
        $selectwhere = $selectwhere_map[0];
       // print_r($selectwhere);
        //$map = $selectwhere_map[1];
        $statuslist2 = \PublicData::$openstatic2;
        $statuslist2['3']['name'] = '全局通道';
        $statuslist2['2']['name'] = '选择指定通道';
        $statuslist2['1']['name'] = '通道池';
        $data = array('telestatus'=>$selectwhere['telestatus'],'appname'=>$selectwhere['appname'],'id'=>$selectwhere['id'],'coid'=>$selectwhere['coid']);
        //模拟数据库操作
        if(isset($_GET['name'])){
            $len = strlen($_GET['name']);
            if($len == 11 && is_numeric($_GET['name']))
            {
                $map['coid']    =  array('like', '%'.(string)$_GET['name'].'%');
            }
            else
            {
                $map['name']    =  array('like', '%'.(string)$_GET['name'].'%');
            }
        }
        if($map == null)
        {   

            $list   =   $this->lists($model->where($selectwhere),$selectwhere);
          // print_r( $sql =$model->_sql());
        }
        else
        {
            $list   =   $this->lists($model->where($map),$map);
        }
        $sql = $model->_sql();
        $index = -1;
        $egtlist = \PublicData::$egtlist;
        $this->assign('egtlist',$egtlist);
        $telecompools = M('telecompools')->field('id,name')->select();
        $appname = array();
         $name = array();
        foreach($list as $value)
        {
            $index++;
          
            $statusid = $value['telestatus'];
            $list[$index]['telestatus'] = $statuslist2[$statusid]['name'];
            foreach($telecompools as $value5)
            {
                if($value['telecompool'] == $value5['id'])
                    $list[$index]['telecompool'] = $value5['name'];            

            }
        }
        //游戏名
        $appname = M('gamelist')->field('id,appid,name')->select();
        //厂商名
        $name = M('colist')->field('id,coid,name')->select();
        $this->assign('data',$data);
        $this->assign('appname',$appname);
        $this->assign('name',$name);
        $this->assign('statuslist',$statuslist2);
        $this->assign('egtlist',$egtlist);
        $this->assign('telecomcorplist',$telecompools);
        $this->assign('list',$list);
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->display();   
    } 
    public function add()
    {
        if(IS_POST)
        {
            $data = I('post.');
            $sdkmoudle=$data['sdkmoudle'];
            $passkey = $data['passkey'];
            $name = trim($data['name']);
            $user = $data['user'];
            
            if($name == null || $user == null)
            {
                $this->error('操作错误1有不可为空项目为空');
            }

            if(strlen($passkey) == 0)
            {
                $data['passkey'] = md5('123456');
            }
            else
            {
                if(strlen($passkey) < 6)
                {
                    $this->error('密码长度不能等于少于六位，不填写则为默认值123456');
                }
                $data['passkey'] = md5($passkey);
            }
            $colistname = M('colist')->field('name')->where('name="'.$name.'"')->select();
            $sql = M('colist')->_sql();
            if ($colistname != false) {
                $this->error('厂商名已经存在');
            }
            $telecomtablename= C('DB_PREFIX').'colist'; 
            $model = M();
            $model->startTrans();
            $id=0;
            $data['time'] = date('Y-m-d',time());       
            $user_name= session('user_auth.username');
            $data['people'] = $user_name; 
            //指定通道的值
            $telecomsarray = $data['telecoms'];
            $telecom_status = $data['telecom_status'];
            $paypriority = $data['paypriority'];      //运营商是否优先级
            $priorityforoutlets = $data['priorityforoutlets'];
            $priorityforoutletss = '';
            foreach($priorityforoutlets as $k=>$v)
            {
                if($v == '')
                {
                    $v = 0;
                }
                $priorityforoutletss = $priorityforoutletss.'_'.$k.'#'.$v;;
            }
            foreach($sdkmoudle as $k=>$v){
                if($v != '')
                {
                    $sdkmoudlearr=$sdkmoudlearr.'_'.$v;
                }
            }
            $data['sdkmoudle']=$sdkmoudlearr;
            foreach($telecomsarray as $k=>$v)
            {
                if($v != '')
                {
                    $telecoms = $telecoms.'_'.$v;
                }
            }
            unset($data['priorityforoutlets']);
            $telecom_status_s = '';
            foreach($telecom_status as $k=>$v)
            {
                if($v != '')
                {
                    $telecom_status_s = $telecom_status_s.'_'.$v;
                }
            }
            $paypriority_status_s = '';
            foreach($paypriority as $k=>$v)
            {
                if($v != '')
                {
                    $paypriority_status_s = $paypriority_status_s.'_'.$v;
                }
            }
            $data['paypriority'] = $paypriority_status_s; //是否运营商优先级状态
            $data['prioritytelecom_status'] = $telecom_status_s; //通道状态
            $data['telecoms'] = $telecoms;      //通道
            $data['prioritytelecom'] = $priorityforoutletss;        //优先级
            //bad
            $klb = $data['bad'];
            if($klb==''){
                $this->error('请输入bad');
                return;
            }
            $i = $klb %10;      //取余
            if($i != 0)
            {
                $this->error("bad只支持10的倍数");
                return;
            }
            //创建一个包含指定范围的元素数组
            $numbers = range (1,10); 
            //shuffle 将数组顺序随即打乱 
            shuffle ($numbers); 
            //array_slice 取该数组中的某一段 
            $num=10; 
            $result = array_slice($numbers,0,$num); 
            if($klb / 10)
            {
                $i = $klb / 10;
            }
            $i = (int)$i;
            for($index = 0;$index <$i;$index++)
            {
                $badid = $badid.$result[$index].'_';
            }
            $data['bad']=$klb;       
            $data['badid']=$badid;  
            $data['badvalue']=1; 
            $this->adddataforsql($model,$telecomtablename,$data,false,$id);
            $time=date('Ymd');
            $appid=$time.$id;
            $updatedata['coid'] = $appid;
            $uid =  $updatedata['coid'];
            $selectwhere = array('id'=>$id);
            $this->updatedatawhereforsql($model,$telecomtablename,$updatedata,$selectwhere,false,$id);
            $usertable = C('DB_PREFIX').'balanceuser';
            $userdata['username'] = $data['user'];
            $userdata['passwd'] = $data['passkey'];
            $userdata['coop'] = $data['coop'];
            $userdata['status'] = 1;
            $userdata['uid'] = $uid;
            $this->adddataforsql($model,$usertable,$userdata,true,$id); 
        }
        else
        {
            $typelist = M('gamelist')->field('id,name,appid')->select();
            $colist = M('colist')->field('id,name,cotype,telecoms')->select();
            $telecomlist = M('telecomlist')->select(); //通道列表名称
            $telecoms = $colist['0']['telecoms'];      //厂商对应的通道         
            $telecomsarray = explode('_',$telecoms); //厂商对应的通道
            unset($telecomsarray[0]);
            $priorityforoutletss = $info['prioritytelecom'];        //厂商对应的优先级
            $priorityforoutletsarray = explode('_',$priorityforoutletss);//厂商对应的优先级
            $temp = array();
            foreach($priorityforoutletsarray as $k=>$v)
            {
                $v2 = explode('#',$v);
                $temp[] = $v2;
            }
            unset($v2[0]);
            $telecom_m = array();       //移动
            $telecom_u = array();       //联通
            $telecom_t = array();       //电信
            
            foreach($telecomlist as $k=>$v)         //通道列表名称对应
            {
                $telecomid = $v['id'];  //通道列表名称对应的ID
                foreach($telecomsarray as $k2=>$v2)
                {
                    if($v['id'] == $v2)
                    {
                        $v['id2'] = $v2;
                    }
                }
                foreach($temp as $k3=>$v3)          //厂商对应的优先级
                {
                    if($v3[0] == $v['id'])
                    {
                        $v['id3'] = $v3[1];
                    }
                }

                $telecominfo = M('telecom')->where(array('telecomname'=>$telecomid))->select();

                if($telecominfo[0]['egt'] == 1)     //电信
                {
                    $temparray[0] = $v;
                    $telecom_t = array_merge_recursive($telecom_t,$temparray);
                }
                if($telecominfo[0]['egt'] == 2)     //联通
                {
                    $temparray[0] = $v;
                    $telecom_u = array_merge_recursive($telecom_u,$temparray);
                }
                if($telecominfo[0]['egt'] == 3)         //移动
                {
                    $temparray[0] = $v;
                    $telecom_m = array_merge_recursive($telecom_m,$temparray);
                }
                
                //          var_dump($v);
            }
            $sdkmoudlelist = M('sdkmoudle')->field('id,moudlename')->select();
            $priorityforoutlets_status = $info['prioritytelecom_status'];
            $priorityforoutlets_status = explode('_',$priorityforoutlets_status);
            unset($priorityforoutlets_status['0']);
            $telecom_u_status = $priorityforoutlets_status['2'];
            $telecom_t_status = $priorityforoutlets_status['1'];
            $telecom_m_status = $priorityforoutlets_status['3'];
            $paystatus1 = \PublicData::$paystatus;
            $this->assign('paystatus',$paystatus1);
            $this->assign('telecom_u_status',$telecom_u_status);
            $this->assign('sdkmoudlelist',$sdkmoudlelist);
            $this->assign('telecom_t_status',$telecom_t_status);
            $this->assign('telecom_m_status',$telecom_m_status);
            $this->assign('telecom_u',$telecom_u);
            $this->assign('telecom_t',$telecom_t);
            $this->assign('telecom_m',$telecom_m);
            cookie('__forward__',$_SERVER['REQUEST_URI']);            
            $statuslist = \PublicData::$openstatic;
            $openstatusgun =$statuslist[2]['name'];
            $statuslist2 = \PublicData::$openstatic2;    //是否通道池
            $outletstype = \PublicData::$outletstype;
            $this->assign('outletstype',$outletstype);
            $telecompoos = M('telecompools')->field('id,name')->select();
            $this->assign('telecompoos',$telecompoos);
            $this->assign('statuslist',$statuslist);
            $this->assign('statuslist2',$statuslist2);
            $this->assign('colist',$colist);
            $this->assign('typelist',$typelist);
            $this->assign('statuslistgun',$openstatusgun);
            $this->display();
        }
    }
    public function edit($id = 0)
    {
        if(IS_POST)
        {
            $data = I('post.'); 
            $coid = I('post.id');
            $coop = I('post.coop');
            $sdkmoudle=$data['sdkmoudle'];
            $passkey = $data['passkey'];
            $name = $data['name'];
            $user = $data['user'];
            $oldkey = $data['oldkey'];
            unset($data['oldkey']);
            if($name == null || $user == null)
            {
                $this->error('操作错误1有不可为空项目为空');
            }
            $colistname = M('colist')->field('name')->where('name="'.$name.'"')->select();
            $sql = M('colist')->_sql();
            $username = M('colist')->field('user')->where('user="'.$user.'"')->select();
            $sql = M('colist')->_sql();
            /*if ($colistname != false) {
                $this->error('厂商名已经存在');
            }
            if ($username != false) {
                $this->error('用户名已经存在');
            }*/

            if(strlen($passkey) == 0)
            {
                $data['passkey'] = $oldkey;
            }
            else
            {
                if(strlen($passkey) < 6)
                {
                    $this->error('密码长度不能等于少于六位，不填写则为默认值123456');
                }
                $data['passkey'] = md5($passkey);
            }
            $userpass =  $data['passkey'];
            $telecomsarray = $data['telecoms'];
            $telecom_status = $data['telecom_status'];      //通道运营商状态
            $paypriority = $data['paypriority'];      //运营商是否优先级
            $priorityforoutlets = $data['priorityforoutlets'];
            $priorityforoutletss = '';
            foreach($priorityforoutlets as $k=>$v)
            {
                if($v == '')
                {
                    $v = 0;
                }
                $priorityforoutletss = $priorityforoutletss.'_'.$k.'#'.$v;;
            }
            foreach($sdkmoudle as $k=>$v){
                if($v != '')
                {
                   $sdkmoudlearr=$sdkmoudlearr.'_'.$v;
                }
            }
            $data['sdkmoudle']=$sdkmoudlearr;
            foreach($telecomsarray as $k=>$v)
            {
                if($v != '')
                {
                    $telecoms = $telecoms.'_'.$v;
                }
            }
            unset($data['priorityforoutlets']);
            $telecom_status_s = '';
            foreach($telecom_status as $k=>$v)
            {
                if($v != '')
                {
                    $telecom_status_s = $telecom_status_s.'_'.$v;
                }
            }
            
            $paypriority_status_s = '';
            foreach($paypriority as $k=>$v)
            {
                if($v != '')
                {
                    $paypriority_status_s = $paypriority_status_s.'_'.$v;
                }
            }
            $data['paypriority'] = $paypriority_status_s; //是否运营商优先级状态
            $data['prioritytelecom_status'] = $telecom_status_s; //通道状态
            $data['telecoms'] = $telecoms;      //通道
            $data['prioritytelecom'] = $priorityforoutletss;        //优先级
            //bad
            $klb = $data['bad'];
            if($klb==''){
                $this->error('请输入bad');
                return;
            }
            $i = $klb %10;      //取余
            if($i != 0)
            {
                $this->error("bad只支持10的倍数");
                return;
            }
            //创建一个包含指定范围的元素数组
            $numbers = range (1,10); 
            //shuffle 将数组顺序随即打乱 
            shuffle ($numbers); 
            //array_slice 取该数组中的某一段 
            $num=10; 
            $result = array_slice($numbers,0,$num); 
            if($klb / 10)
            {
                $i = $klb / 10;
            }
            $i = (int)$i;
            for($index = 0;$index <$i;$index++)
            {
                $badid = $badid.$result[$index].'_';
            }
            $data['bad']=$klb;       
            $data['badid']=$badid;  
            $data['badvalue']=1;              
            $model = M();
            $data['time'] = date('Y-m-d',time());
            $user_name= session('user_auth.username');
            $data['people'] = $user_name;
            $selectwhere = array('id'=>$id);
            $telecomtablename= C('DB_PREFIX').'colist';    
            $this->updatedatawhereforsql($model,$telecomtablename,$data,$selectwhere,false,$id);
            $appid = M('colist')->field('coid')->find($coid);
            $sele['uid'] = $appid['coid'];
            $usertable = C('DB_PREFIX').'balanceuser';
            $userdata['username'] = $sele['uid'];
            $userdata['username'] = $user;
            $userdata['passwd'] =  $userpass;
            $userdata['coop'] = $coop;
            $this->updatedatawhereforsql($model,$usertable,$userdata,$sele,true,$id);
        }
        else
        {
            $telecomlist = M('telecomlist')->select(); //通道列表名称
            $selectwhere = array('id'=>$id);
            $info = M('colist')->where($selectwhere)->select();
            $info = $info['0'];
            $appid=$info['appid'];   
            $ciod=$info['ciod']; 
            $sdkmoudleid = $info['sdkmoudle'];
            $sdkmoudleid=explode('_',$sdkmoudleid);
            unset($sdkmoudleid['0']);
            $selectwheregame = array('appid'=>$appid);
            $gameinfo = M('gamelist')->select();
            $gameinfo2 = M('gamelist')->where($selectwheregame)->select(); 
            $sdkmoudlelist = M('sdkmoudle')->field('id,moudlename')->select(); //SDK模板
            $gameinfo2=$gameinfo2['0'];
            $telecoms = $info['telecoms'];      //厂商对应的通道
            $telecomsarray = explode('_',$telecoms); //厂商对应的通道
            unset($telecomsarray[0]);
            $priorityforoutletss = $info['prioritytelecom'];        //厂商对应的优先级
            $priorityforoutletsarray = explode('_',$priorityforoutletss);//厂商对应的优先级
            $temp = array();
            foreach($priorityforoutletsarray as $k=>$v)
            {
                $v2 = explode('#',$v);
                $temp[] = $v2;
            }
            unset($v2[0]);
            $telecom_m = array();       //移动
            $telecom_u = array();       //联通
            $telecom_t = array();       //电信
             //  $sdknum= count($sdkmoudlelist);
               foreach($sdkmoudlelist as $k3=>$v5){
                    foreach($sdkmoudleid as$k2 =>$v2){
                        if($v5['id']==$v2){
                            $sdkmoudlelist[$k3]['id2']  =$v2;
                        }
                    }                
            
               }
            foreach($telecomlist as $k=>$v)         //通道列表名称对应
            {
                $telecomid = $v['id'];  //通道列表名称对应的ID
                foreach($telecomsarray as $k2=>$v2)     //列表
                {
                    if($v['id'] == $v2)
                    {
                        $v['id2'] = $v2;
                    }
                }
                foreach($temp as $k3=>$v3)          //厂商对应的优先级
                {
                    if($v3[0] == $v['id'])
                    {
                        $v['id3'] = $v3[1];
                    }
                }

                $telecominfo = M('telecom')->where(array('telecomname'=>$telecomid))->select();

                if($telecominfo[0]['egt'] == 1)     //电信
                {
                    $temparray[0] = $v;
                    $telecom_t = array_merge_recursive($telecom_t,$temparray);
                }
                if($telecominfo[0]['egt'] == 2)     //联通
                {
                    $temparray[0] = $v;
                    $telecom_u = array_merge_recursive($telecom_u,$temparray);
                }
                if($telecominfo[0]['egt'] == 3)         //移动
                {
                    $temparray[0] = $v;
                    $telecom_m = array_merge_recursive($telecom_m,$temparray);
                }
                
                //          var_dump($v);
            }
         //  $moudlelist=array();
          // $moudlelist=$v5;  //厂商对应SDK模板的ID
           //$sdkmoudlelist = array_merge_recursive($moudlelist,$moudlelist);
            //通道运营商状态
            $priorityforoutlets_status = $info['prioritytelecom_status'];
            $priorityforoutlets_status = explode('_',$priorityforoutlets_status);
            unset($priorityforoutlets_status['0']);
            $telecom_u_status = $priorityforoutlets_status['2'];
            $telecom_t_status = $priorityforoutlets_status['1'];
            $telecom_m_status = $priorityforoutlets_status['3'];
            $this->assign('telecom_u_status',$telecom_u_status);
            $this->assign('sdkmoudlelist',$sdkmoudlelist);
            $this->assign('telecom_t_status',$telecom_t_status);
            $this->assign('telecom_m_status',$telecom_m_status);
            //是否第三方优先级
            $paypriority_status = $info['paypriority'];
            $paypriority_status = explode('_',$paypriority_status);
            unset($paypriority_status['0']);        
            $paypriority_dx_status =$paypriority_status['1'];   //电信
            $paypriority_lt_status = $paypriority_status['2'];   //联通
            $paypriority_yd_status = $paypriority_status['3'];   //移动
            $this->assign('paypriority_lt_status',$paypriority_lt_status);    //联通
            $this->assign('paypriority_yd_status',$paypriority_yd_status);   //移动
            $this->assign('paypriority_dx_status',$paypriority_dx_status);   //电信
            $this->assign('telecom_u',$telecom_u);
            $this->assign('telecom_t',$telecom_t);
            $this->assign('telecom_m',$telecom_m);
           // $pseudocodeinfo = M('pseudocode')->field(true)->find($ciod);
          //  $egt = $pseudocodeinfo['egt'];
            $statuslist2 = \PublicData::$openstatic2;    //是否通道池
            $statuslistapi = \PublicData::$openstaticapi;    //第三方支付
          //  $telecompoos = M('telecompools')->field('id,name')->where(array('egt'=>$egt))->select();
            $selectwhereegt='egt!=4';
            $telecompoos = M('telecompools')->where($selectwhereegt)->field('id,name')->select();
            $selectwhereegt4='egt=4';
            $telecompoosegt4 = M('telecompools')->where($selectwhereegt4)->field('id,name')->select();
            $this->assign('telecompoos',$telecompoos);
            $this->assign('telecompoosegt4',$telecompoosegt4);
            $infoTYPT=$info['type'];
            $openstatus = \PublicData::$openstatic;     //厂商状态  
            $outletstype = \PublicData::$outletstype;
            $paystatus1 = \PublicData::$paystatus;
            $this->assign('paystatus',$paystatus1);
            $this->assign('statuslist2',$statuslist2);//是否通道池
            $this->assign('statuslistapi',$statuslistapi);//第三方支付
            $this->assign('outletstype',$outletstype);
            $this->assign('info',$info);
            $this->assign('gameinfo',$gameinfo);//编辑下拉全部game列表
            $this->assign('gameinfo2',$gameinfo2);//界面默认appname           
            $this->assign('appid2',$appid);//界面默认appid
            $this->assign('statuslist',$openstatus);       
            $this->display();
        }
    }
    
    public function indexapp()
    {   
        $model = M();
        $model = D('colist');
        $data = array('telestatus','appname','id','coid');
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'name');
        $selectwhere = $selectwhere_map[0];
        // print_r($selectwhere);
       // $map = $selectwhere_map[1];
        
        if(isset($_GET['name'])){
            $len = strlen($_GET['name']);
            if($len == 11 && is_numeric($_GET['name']))
            {
                $map['coid']    =  array('like', '%'.(string)$_GET['name'].'%');
            }
            else
            {
                $map['name']    =  array('like', '%'.(string)$_GET['name'].'%');
            }
        }
   
        $data = array('telestatus'=>$selectwhere['telestatus'],'appname'=>$selectwhere['appname'],'id'=>$selectwhere['id'],'coid'=>$selectwhere['coid']);
        //模拟数据库操作
        if($map == null)
        {   

            $list   =   $this->lists($model->where($selectwhere),$selectwhere);
            // print_r( $sql =$model->_sql());
        }
        else
        {
            $list   =   $this->lists($model->where($map),$map);
        }
        //游戏名
        $appname = M('gamelist')->field('id,name,appid')->select();
        $name = M('colist')->where($selectwhere)->select();
        foreach($list as $value)
        {
            $index++;

            foreach($name as $value5)
            {
                if($value['coid'] == $value5['ciid'])
                    $list[$index]['name'] = $value5['name'];            

            }
        }
        
        //厂商名
        $appid=array();      
        $this->assign('data',$data);
        $this->assign('appname',$appname);
        $this->assign('name',$name);
        $this->assign('list',$list);
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->display();   
    } 
    
    public function editapp($id=0){
        if($_POST){
            $data = I('post.');
            $gamelist = M('gamelist')->field('appid,name')->select();
            foreach($data['appid'] as $k=>$v){
                       $appidname=explode(',',$v);
                       $games.='_'.$appidname['0'];
                       $gamesappname.='_'.$appidname['1'];
            }
            $data['appid']=$games;
            $data['appname']=$gamesappname;
            $model = M();
            $selectwhere=array('id'=>$data['id']);
            $model->startTrans();
            $telecomtablename= C('DB_PREFIX').'colist'; 
            $this->updatedatawhereforsql($model,$telecomtablename,$data,$selectwhere,true,$id);
        }else{
            $gamelist = M('gamelist')->field('id,appid,name')->select();
            $coid = I('get.id');
            $colist = M('colist')->field('id,name,coid,appid')->find($coid);
            $app = explode('_', $colist['appid']);
            unset($app['0']);
            $apid=array();
            foreach ($gamelist as $k => $value) {
                    foreach ($app as $key => $value1) {
                    if ($value['appid']==$value1) {
                            $gamelist[$k]['id2'] = $value1;
                        }    
                }
                }
            $this->assign('apid',$apid);
            $this->assign('info',$colist);
            $this->assign('gamelist',$gamelist);
            $this->display(); 
        }
        
    }
    
    
    //SDK模板页面
    public function templet(){
        $model = M();
        $data222=$_GET;
        $model = D('sdkmoudle');
        $data = array('id');
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'moudlename');
        $selectwhere = $selectwhere_map[0];
        // print_r($selectwhere);
        $map = $selectwhere_map[1];
        $data = array('moudlename'=>$selectwhere['id']);
        //模拟数据库操作
        if($map == null)
        {   

            $list   =   $this->lists($model->where($selectwhere),$selectwhere);
            // print_r( $sql =$model->_sql());
        }
        else
        {
            $list   =   $this->lists($model->where($map),$map);
         //   print_r( $sql =$model->_sql());
            
        }
        $sdkmoudlelist = M('sdkmoudle')->field('id,moudlename,provinces')->select();
        
        $citylist = \PublicData::$city;
      //  $provincesarr=array();
        foreach($list as $k =>$v){
                   $provinces= $v['provinces'];
                   $provinces=explode('_',$provinces);
                   unset($provinces['0']);
              //   $provincesarr[]=$provinces;
                   foreach($provinces as $k1=>$v1){
                    foreach($citylist as $k2=>$v2){
                        if($v1==$v2['id']){
                            $list[$k]['ciyt']=$list[$k]['ciyt'].'_'.$v2['city'];
                         }
                     }
                }
        }  
        
        $this->assign('data',$data);
        $this->assign('list',$list);
        $this->assign('sdkmoudlelist',$sdkmoudlelist);
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->display();   
  
    }
    
    
    public function addtemplet(){
        if(IS_POST)
        {
                $data = I('post.');  
                $data['time']=date("Y-m-d H:i:s",time());
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize = 31457280;
                $upload->savePath = 'corepic/';
                $upload->saveName = array('uniqid','');
                $rootPath=$upload->rootPath;
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg','apk');
                $upload->autoSub = true;
                $upload->subName = array('date','Ymd');
                $adlist = $upload->upload();
                if(!$adlist){
                    $this->error($upload->getError());
                }
                $rootPath=ltrim($rootPath,'.');
                $data['moudlepic'] = 'http://'.$_SERVER['HTTP_HOST'].$rootPath.$adlist['moudlepic']['savepath'].$adlist['moudlepic']['savename'];
                for($i=0;$i<32;$i++){
                    $citylist[$i] = 0;
                }
                foreach($data['provinces'] as $k=>$value){
                    $citylist[$value-1] = $value;
                
                }
                $data['provinces'] = '';
                foreach($citylist as $value)
                {
                    $data['provinces'] =$data['provinces'].'_'.$value;
                }
                $adid = M('sdkmoudle')->add($data);
                if($adid){
                    $this->success('资源上传成功', Cookie('__forward__'));
                }else{
                    $this->error('资源上传失败');
                }
      
        }else{
          
             $citylist = \PublicData::$city;
            $city_check = explode('_',$info['provinces']);
            unset($city_check[0]);
            foreach($city_check as $k=>$value)
            {
                $citylist[$k-1]['id2'] = $value;
            }
            $this->assign('citylist',$citylist);
            $this->display();
        }
    }
    
    
    public function edittemplet($id = 0){
         if(IS_POST)
        {
                $data = I('post.');  
                //$iget=$_GET;
                $data['time']=date("Y-m-d H:i:s",time());
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize = 31457280;
                $upload->savePath = 'corepic/';
                $upload->saveName = array('uniqid','');
                $rootPath=$upload->rootPath;
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg','apk');
                $upload->autoSub = true;
                $upload->subName = array('date','Ymd');
                $adlist = $upload->upload();
             
                $rootPath=ltrim($rootPath,'.');
                $data['moudlepic'] = 'http://'.$_SERVER['HTTP_HOST'].$rootPath.$adlist['moudlepic']['savepath'].$adlist['moudlepic']['savename'];
                for($i=0;$i<32;$i++){
                    $citylist[$i] = 0;
                }
                foreach($data['provinces'] as $k=>$value){
                    $citylist[$value-1] = $value;
                
                }
                $data['provinces'] = '';
                foreach($citylist as $value)
                {
                    $data['provinces'] =$data['provinces'].'_'.$value;
                }
                if($adlist==false)
                {
                   $moudlepic= M('sdkmoudle')->field('id,moudlepic')->where(array('id'=>$data['id']))->select($data);
                  $moudlepic= $moudlepic['0'];
                     $data['moudlepic']=$moudlepic['moudlepic'];
                }
                $adid = M('sdkmoudle')->where(array('id'=>$data['id']))->save($data);
                if($adid){
                    $this->success('资源上传成功', Cookie('__forward__'));
                }else{
                    $this->error('资源上传失败');
                }
      
        }else{
             $citylist = \PublicData::$city;
             $wherelexect=array('id'=>$id);
             $sdkmoudlelist = M('sdkmoudle')->where($wherelexect)->select();
             $sdkmoudlelist=$sdkmoudlelist['0'];
             unset($provinces['0']);
            
             $city_check = explode('_',$sdkmoudlelist['provinces']);
            unset($city_check[0]);
            foreach($city_check as $k=>$value)
            {
                if($k<=32){
                        $citylist[$k-1]['id2'] = $value;
                }
            }
          
           // $citylist=array_merge_recursive($citylist,$cityarrinfo);
         //   $this->assign('provincesarr',$provincesarr);
            $this->assign('citylist',$citylist);
          //  $this->assign('wherelexect',$wherelexect);
            $this->assign('info',$sdkmoudlelist);
            $this->display();
       }
    }
    
  
}
