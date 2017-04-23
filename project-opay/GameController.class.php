<?php
namespace Admin\Controller;
include_once "PublicData.php";
include_once"PublicFunction.php";
/**
 * Class1 short summary.
 *
 * Class1 description.
 *
 * @version 1.0
 * @author admin
 */
class GameController extends AdminController
{
    //游戏列表
    public function index()
    { 
        
        $model = D('gamelist');
        $data = array('status','type','id','appid');
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'name');
        $selectwhere = $selectwhere_map[0];
        //$map = $selectwhere_map[1];
        if(isset($_GET['name'])){
            $len = strlen($_GET['name']);
            if($len == 10 && is_numeric($_GET['name']))
            {
                $map['appid']    =  array('like', '%'.(string)$_GET['name'].'%');
            }
            else
            {
                $map['name']    =  array('like', '%'.(string)$_GET['name'].'%');
            }
        }
        $data = array('status'=>$selectwhere['status'],'type'=>$selectwhere['type'],'id'=>$selectwhere['id'],'appid'=>$selectwhere['appid']);
        //模拟数据库操作
        if($map == null)
        {
            $info   =   $this->lists($model->where($selectwhere),$selectwhere);
        }
        else
        {
            $info   =   $this->lists($model->where($map),$map);
        }
        $typelist = M('gametypelist')->field('id,name')->select(); //游戏类型
       // $colist = M('colist')->field('id,name,coid')->select();
        $openstatus = \PublicData::$openstatic;
        foreach($info as $k=>$value)
        {
            $index++;
            $info[$k]['status'] = $openstatus[$info[$k]['status']]['name']; 
           // $info[$k]['type'] = $typelist[$info[$k]['type']]['name']; 
            $type = $info[$k]['type']; 

            foreach($typelist as $value2)
            {
                if($value2['id'] == $type)
                {
                    $info[$k]['type'] = $value2['name'];
                }
            }

        }
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $gamelist = M('gamelist')->field('id,appid,name')->select();
        $this->assign('gamelist',$gamelist);
        $this->assign('typelist', $typelist);
        $this->assign('openstatus',$openstatus);
        $this->assign('data',$data);
        $this->assign("list",$info);
        $this->display();
    }
    public function deduction()
    {
        if(IS_POST)
        {
            $model = M('codecontrol');
            $data = $model->create();
            $data   =   I('post.');         //获取URL传过来的值
            $outletsname = $data['outletsname'];
            $egt = $data['egt'];
            if($outletsname == 0)
            {
                $selectwhere = array('egt'=>$egt);
            }
            else
            {
                $selectwhere = array('outlets'=>$outletsname,'egt'=>$egt);
            }
            $klb = $data['kouliangbi'];
            if($klb == '')
            {
                $this->error("请输入bad");
                return;
            }
            $kouliang = $data['kouliang'];
            $i = $klb %10;
            if($i != 0)
            {
                  $this->error("只支持10的倍数");
                  return;
            }
            $numbers = range (1,10); 
            //shuffle 将数组顺序随即打乱 
            shuffle ($numbers); 
            //array_slice 取该数组中的某一段 
            $num=10; 
            $result = array_slice($numbers,0,$num); 
     //       var_dump($result); 
            if($klb / 10);
            $i = $klb / 10;
            $i = (int)$i;
            for($index = 0;$index <$i;$index++)
            {
                $kouliangid = $kouliangid.$result[$index].'_';
            }
      //      dump($kouliangid);
            $data = array('kouliangbi'=>$klb,'kouliang'=>$kouliang,'kouliangid'=>$kouliangid,'kouliangvalue'=>0);
            if($model->where($selectwhere)->select() == null)
            {
                $this->error('渠道没有分配源代码');
                return;
            }
            else
            {
           //     $model = M('pseudocode');
         //       $model->where($selectwhere)->data(array('kouliangid'=>$kouliangid,'kouliangbi'=>$kouliangid))->save();
                $isok = $model->where($selectwhere)->data($data)->save();
                if($isok !== false)
         //       action_log('添加版本信息', 'Upgrade', $id, UID);
                $this->success('新增成功', Cookie('__forward__'));
                else
                    $this->error('错误');
            }
        }
        else
        {
            $outletlist = M('outletslist')->select();
            $chagelist = M('telecomlist')->select();
            $openstatus = \PublicData::$openstatic;
            $egtlist = \PublicData::$egtlist;
            $this->assign('outletlist',$outletlist);
            $this->assign('egtlist',$egtlist);
            $this->assign('openstatus',$openstatus);
            $this->display();
        }
    }
    public function addtype()
    {
        if(IS_POST)
        {
            $data = I('post.');
            $model = M();
            $tablename= C('DB_PREFIX').'gametypelist';    
            $this->adddataforsql($model,$tablename,$data,true,$id);
        }
        else
        {
            $info = M('gametypelist')->select();
            $this->assign('info',$info);
            Cookie('__forward__',$_SERVER['REQUEST_URI']);

            $this->display();
        }
    }
    public function add()
    {
        if(IS_POST)
        {
            $data   =   I('post.');         //获取URL传过来的值
            $this->ischeckonenextfordata(array($data['name'],$data['version']));
            $tablename= C('DB_PREFIX').'gamelist';    
            $model = M();
            $model->startTrans();
            $id = 0;
            $passkey = $data['passwd'];
            if($data['user']==''){
                $this->error('用户名不能为空');
            }
            if(strlen($passkey) == 0)
            {
                $data['passwd'] = md5('123456');
            }
            else
            {
                if(strlen($passkey) < 6)
                {
                    $this->error('密码长度不能等于少于六位，不填写则为默认值123456');
                }
                $data['passwd'] = md5($passkey);
            }
            $data['time'] = date('Y-m-d H:i:s',time()); 
            $this->adddataforsql($model,$tablename,$data,false,$id); 
            $time=date('Ymd');
            $appid=$time.$id;
            $updatedata['appid'] = $appid;
            $selectwhere = array('id'=>$id);
            $this->updatedatawhereforsql($model,$tablename,$updatedata,$selectwhere,false,$id);
            $usertable = C('DB_PREFIX').'balanceuser';
            $userdata['username'] = $data['user'];
            $userdata['passwd'] = $data['passwd'];
            $userdata['coop'] = $data['coop'];
            $userdata['status'] = 2;
            $userdata['uid'] = $appid;
            $this->adddataforsql($model,$usertable,$userdata,true,$id); 
            
            
            //$data['utf8name'] = $data['name'];
            //$data['name'] = $i    d;
            //$data['code'] = substr(md5($id),1,5);
            //if($data['appkey'] === '')
            //    $data['appkey'] = '123456';
            //$str = $data['appkey'];
            //$data['appkey'] = md5($str);
            //$data['user']='jiesen';
            //$tablename= C('DB_PREFIX').'outlets';    
            //$this->adddataforsql($model,$tablename,$data,true,$id);
        }
        else
        {
            Cookie('__forward__',$_SERVER['REQUEST_URI']);
            $typelist = M('gametypelist')->field('id,name')->select();
            $colist = M('colist')->field('id,name')->select();
            $statuslist = \PublicData::$openstatic;

            $this->assign('statuslist',$statuslist);
            $this->assign('colist',$colist);
            $this->assign('typelist',$typelist);
            $this->display();
        }
    }
    public function edit($id = 0,$oldid = 0,$oldkey = '')
    {
        if(IS_POST)
        { 
            $data   =   I('post.');         //获取URL传过来的值
            $id = $data['id'];
            $gameid =  $id; 
            unset($data['id']);
            $model = M();
            $tablename= C('DB_PREFIX').'gamelist';    
            $data['time'] =date('Y-m-d H:i:s',time());
            $model = M();
            $model->startTrans();
            $passkey = $data['passwd'];
            if($data['user']==''){
                $this->error('用户名不能为空');
            }
            if(strlen($passkey) == 0)
            {
                $data['passwd'] = md5('123456');
            }
            else
            {
                if(strlen($passkey) < 6)
                {
                    $this->error('密码长度不能等于少于六位，不填写则为默认值123456');
                }
                $data['passwd'] = md5($passkey);
            }
            $updatedata = $data;
            $selectwhere = array('id'=>$id);
            $this->updatedatawhereforsql($model,$tablename,$updatedata,$selectwhere,false,$id);
            $appid = M('gamelist')->field('appid')->find($gameid);
            $a = M($tablename)->_sql();
            $sele['uid'] = $appid['appid'];
            $usertable = C('DB_PREFIX').'balanceuser';
            $userdata['uid'] = $sele['uid'];
            $userdata['username'] = $data['user'];
            $userdata['passwd'] = $data['passwd'];
            $userdata['coop'] = $data['coop'];
            $this->updatedatawhereforsql($model,$usertable,$userdata,$sele,true,$id);
        }
        else
        {
            $info = M('gamelist')->where(array('id'=>$id))->select();
            $info = $info['0'];
            $typelist = M('gametypelist')->field('id,name')->select();
            $colist = M('colist')->field('id,name')->select();
            $this->assign('colist',$colist);

            $statuslist = \PublicData::$openstatic;
            $this->assign('statuslist',$statuslist);
            $this->assign('typelist',$typelist);
            $this->assign('info',$info);
            $this->display();
        }
    }
    public function info($id = 0)
    {
        $this->display();
    }
    public function warning()
    {
    //    $tablename= C('DB_PREFIX').'outletslist';    
        if(IS_POST)
        {
            $time = time();
            $data   =   I('post.');         //获取URL传过来的值
            $waroutlets = $data['waroutlets'];
            $waroutletstime = $data['waroutletstime'];
            $dayupforoutlets = $data['dayupforoutlets'];
            foreach($waroutletstime as $k=>$v)
            {
                $selectwhere = array('name'=>$k);
                $data = array('warlongtime'=>$v);
                $outletslist = M('outlets')->where($selectwhere)->data($data)->save();
                //$selectwhere = array('name');

                $data = array('warbegintime'=>0);
                $isok = $outletslist = m('outlets')->where($selectwhere)->data($data)->save();
            }
            foreach($dayupforoutlets as $k=>$v)
            {
                $selectwhere = array('name'=>$k);
                $data = array('dayupforoutlets'=>$v);
                $outletslist = M('outlets')->where($selectwhere)->data($data)->save();
            }

            foreach($waroutlets as $k=>$v)
            {
                if($waroutletstime[$v] <= 0)
                {
                    $this->error("请填写渠道预警时间，已选择渠道的预警时间不能等于0");
                    exit();
                }
                $selectwhere = array('name'=>$v);
                $data = array('warbegintime'=>$time);
                $outletslist = M('outlets')->where($selectwhere)->data($data)->save();
            }
            $this->success('预警系统生效');
        }
        else
        {
  //          $outletslist = M('outlets')->select();
            $model = D('outlets');
            $data = array('status','type');
            $selectwhere_map = \PublicFunction::getClickSeachData($data,'utf8name');
            $selectwhere = $selectwhere_map[0];
            $map = $selectwhere_map[1];
            $data = array('status'=>$selectwhere['status'],'type'=>$selectwhere['type']);
            //模拟数据库操作
            if($map == null)
            {
                $outletslist   =   $this->lists($model->where($selectwhere),$selectwhere);
            }
            else
            {
                $outletslist   =   $this->lists($model->where($map),$map);
            }
            $index = -1;
            //$index2 = 0;
            foreach($outletslist as $k=>$v)
            {
                $index++;
                if($v['warbegintime'] != 0)
                {
                    $outletslist[$index]['id2'] = $v['name'];
                }
                $outletslist[$index]['id3'] = $v['warlongtime'];
            }
            $this->assign('outletslist',$outletslist);
            $this->display(); 
        }

    }
    
    public function indexsdk(){
        
        $model = M();
        $model = D('cogameset');
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
    
    public function indexsdkadd($id =0){
        if(IS_POST){
            $data = I('post.');
            $coid = I('post.coid');
            $appid = I('post.appid');
            $colistnameset = M('cogameset')->field('id')->where(array('coid'=>$coid,'appid'=>$appid))->select();
            if ($colistnameset!= null) {
                $this->error('分配已存在');
            }
            $colistname = M('colist')->field('name')->where('coid="'.$coid.'"')->select();
            $gamename = M('gamelist')->field('name')->where('appid="'.$appid.'"')->select();
            $data['coname']=$colistname[0]['name'];
            $data['appname']=$gamename[0]['name'];
            $sdkmoudle=$data['sdkmoudle'];
            $telecomtablename= C('DB_PREFIX').'cogameset'; 
            $model = M();
            $model->startTrans();
            $id=0;
            $data['time'] = date('Y-m-d',time());       
           // $user_name= session('user_auth.username');
           // $data['people'] = $user_name; 
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
            $data['prioritytelecom_status'] = $telecom_status_s; //通道状态
            $paypriority_status_s = '';
            foreach($paypriority as $k=>$v)
            {
                if($v != '')
                {
                    $paypriority_status_s = $paypriority_status_s.'_'.$v;
                }
            }
            $data['paypriority'] = $paypriority_status_s; //是否运营商优先级状态
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
            $this->adddataforsql($model,$telecomtablename,$data,true,$id);
            //$time=date('Ymd');
            //$appid=$time.$id;
            //$updatedata['coid'] = $appid;
            //$uid =  $updatedata['coid'];
            //$selectwhere = array('id'=>$id);
            //$this->updatedatawhereforsql($model,$telecomtablename,$updatedata,$selectwhere,false,$id);
            //$usertable = C('DB_PREFIX').'balanceuser';
            //$userdata['username'] = $data['user'];
            //$userdata['passwd'] = $data['passkey'];
            //$userdata['coop'] = $data['coop'];
            //$userdata['status'] = 1;
            //$userdata['uid'] = $uid;
            //$this->adddataforsql($model,$usertable,$userdata,true,$id);     
        }else{
                //游戏
                $ganmelist = M('gamelist')->field('id,appid,name')->select();
                //厂商
                $colist = M('colist')->field('id,coid,name')->select();
                $sdkmoudlelist = M('sdkmoudle')->field('id,moudlename')->select(); //SDK模板
                $telecompoos = M('telecompools')->field('id,name')->select();   //通道池
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
            $selectwhereegt4='egt=4';
            $telecompoosegt4 = M('telecompools')->where($selectwhereegt4)->field('id,name')->select();      //第三方通道池 
            $this->assign('telecompoosegt4',$telecompoosegt4);
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
            $outletstype = \PublicData::$outletstype;   //厂商类型
            $statuslist = \PublicData::$openstatic;
            $statuslist2 = \PublicData::$openstatic2;    //是否通道池
            $openstatusgun =$statuslist[2]['name'];
            $paystatus1 = \PublicData::$paystatus;      //是否第三方支付
            $this->assign('paystatus',$paystatus1);
            $this->assign('telecompoos',$telecompoos);
            $this->assign('sdkmoudlelist',$sdkmoudlelist);
            $this->assign('statuslist',$statuslist);
            $this->assign('statuslist2',$statuslist2);
            $this->assign('statuslistgun',$openstatusgun);
            $this->assign('outletstype',$outletstype);
            $this->assign('colist',$colist);
            $this->assign('gamelist',$ganmelist);
            $this->display();
        }
    }
    
    
    public function indexsdkedit($id = 0){
        if(IS_POST)
        {
            $data = I('post.'); 
            $sdkmoudle=$data['sdkmoudle'];
          //  $passkey = $data['passkey'];
            $coid = $data['coid'];
            $appid = $data['appid'];
            $paypriority = $data['paypriority'];      //运营商是否优先级
           // $oldkey = $data['oldkey'];
          //  unset($data['oldkey']);
            $colistnameset = M('cogameset')->field('id')->where(array('coid'=>$coid,'appid'=>$appid))->select();
            if ($colistnameset[0][id] != $id && $colistnameset!=null) {
                $this->error('分配已存在');
            }
            $colistname = M('colist')->field('name')->where('coid="'.$coid.'"')->select();
            $data['coname']=$colistname[0]['name'];
            $gamelistname = M('gamelist')->field('name')->where('appid="'.$appid.'"')->select();
            $data['appname']=$gamelistname[0]['name'];
            $telecomsarray = $data['telecoms'];
            $telecom_status = $data['telecom_status'];
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
            $data['prioritytelecom_status'] = $telecom_status_s; //通道状态
            //营运商优先级
            $paypriority_status_s = '';
            foreach($paypriority as $k=>$v)
            {
                if($v != '')
                {
                    $paypriority_status_s = $paypriority_status_s.'_'.$v;
                }
            }
            $data['paypriority'] = $paypriority_status_s; //是否运营商优先级状态
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
            //$user_name= session('user_auth.username');
           // $data['people'] = $user_name;
            $selectwhere = array('id'=>$id);
            $telecomtablename= C('DB_PREFIX').'cogameset';    
            $this->updatedatawhereforsql($model,$telecomtablename,$data,$selectwhere,true,$id);
            //$appid = M('colist')->field('coid')->find($coid);
            //$sele['uid'] = $appid['coid'];
            //$usertable = C('DB_PREFIX').'balanceuser';
            //$userdata['username'] = $sele['uid'];
            //$userdata['username'] = $user;
            //$userdata['passwd'] =  $userpass;
            //$userdata['coop'] = $coop;
            //$this->updatedatawhereforsql($model,$usertable,$userdata,$sele,true,$id);
        }
        else
        {
            $telecomlist = M('telecomlist')->select(); //通道列表名称
            $selectwhere = array('id'=>$id);
            $info = M('cogameset')->where($selectwhere)->select();
            $info = $info['0'];
            $appid=$info['appid'];   
            $coid=$info['coid']; 
            $sdkmoudleid = $info['sdkmoudle'];
            $sdkmoudleid=explode('_',$sdkmoudleid);
            unset($sdkmoudleid['0']);
            $gamelist = M('gamelist')->select();
            $colist = M('colist')->select(); 
            $sdkmoudlelist = M('sdkmoudle')->field('id,moudlename')->select(); //SDK模板
            foreach($gamelist as $k=>$value){
                if($value['appid']==$appid){
                         $gamelist[$k]['id2']=$value['name'];
                        }
            
            }
            foreach($colist as $k=>$value){
                if($value['coid']==$coid){
                    $colist[$k]['id2']=$value['name'];
                }
                
            }
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
            $outletstype = \PublicData::$outletstype;   //厂商类型
            $paystatus1 = \PublicData::$paystatus;
            $this->assign('paystatus',$paystatus1);
            $this->assign('statuslist2',$statuslist2);//是否通道池
            $this->assign('statuslistapi',$statuslistapi);//第三方支付
            $this->assign('outletstype',$outletstype);   //厂商类型
            $this->assign('info',$info);
            $this->assign('gamelist',$gamelist);//编辑下拉全部game列表
            $this->assign('colist',$colist);
            $this->assign('appid2',$appid);//界面默认appid
            $this->assign('statuslist',$openstatus);            //厂商状态和 是否二次弹框  
            $this->display();
        }
    
    
    
    
    }
    
    
    
    
}

