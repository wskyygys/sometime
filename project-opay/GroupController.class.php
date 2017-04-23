<?php
namespace Admin\Controller;
include_once "PublicData.php";
include_once"PublicFunction.php";
include_once"ModelNameList.php";
include_once'JSHttpRequest.php';
/**
 * TelecomCorpController short summary.
 *
 * TelecomCorpController description.
 *
 * @version 1.0
 * @author admin
 */
//通道列表部分代码
class GroupController extends AdminController
{
    public function index()
    {
        G('countbegin');
        $time = time();
        
        $time1 = '';
        $time2 = '';
        
        if(isset($_GET['timestart'])){
            $time1    =  (string)$_GET['timestart'].' 00:00:00';
            $time1 = strtotime($time1);
        }
        else
        {
            $t = time();
            $time1 = date("y-m-d",$t);
            $time1 = $time1.' 00:00:00'; 
            $time1 = strtotime($time1);
        }
        if(isset($_GET['timestart2'])){
            $time2    =   (string)$_GET['timestart2'].' 23:59:59';
            $time2 = strtotime($time2);
        }
        else
        {
            $t = time();
            $time2 = date("Y-m-d",$t).' 23:59:59'; 
            $time2 = strtotime($time2);
        }

        $data = array('iscpgame','isco','isprop','ishour','isday','iscode','paycode','telecomname','cpgame','isday','egt');
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'utf8name');
        $iscpgame = $_GET['iscpgame'];
        $isco = $_GET['isco'];
        $isprop = $_GET['isprop'];

        $istelecom = $_GET['istelecom'];
        $iscity = $_GET['iscity'];
        $ishour = $_GET['ishour'];
        $isday = $_GET['isday'];

        $ischilddata = $_GET['ischilddata'];
        $isegt =  $_GET['isegt'];
        if(empty($isegt)) $isegt = 0;
        if(empty($ischilddata)) $ischilddata = 0;
        if(empty($ishour)) $ishour = 0;
        if(empty($iscity)) $iscity = 0;
        if(empty($istelecom)) $istelecom = 0;
        if(empty($iscpgame)) $iscpgame = 0;
        if(empty($isday)) $isday = 0;

        $selectwhere = $selectwhere_map['0'];
        $map = $selectwhere_map['1'];
        $telecominfo =array();
        $outletsinfo = array();
        $keynames = array('telecomname','outletname');
        $is_t_o_v = $this->check_auth_info($selectwhere,$outletsinfo,$telecominfo,$keynames);        
        $is_telecom_visible = $is_t_o_v[0];
        $is_outlets_visible = $is_t_o_v[1];
        $isbad_visible = $is_t_o_v[2];
        if($time1!='' && $time2!='')
        {
            $this->dateformonth = \PublicFunction::getTimeForYH($time1).'_';
            $isold =  $_GET['isold']|0;
            if($isold !== 1)
            {
                $this->dateformonth =  $this->dateformonth.'2'.'_';
            }
            $tablename = $this->dateformonth.'ordercount';
            $model2 = M($tablename);  
            $datatime1s = (string)date("Y-m-d",$time1); 
            $datatime2s =(string)date("Y-m-d",$time2);
            $datatime1 = explode('-',$datatime1s);
            $datatime2 = explode('-',$datatime2s);
            if($datatime1[1] != $datatime2[1])
            {
                $this->error('不允许跨月查询');
            }
            //$this->dateformonth ='';
            //foreach($datatime1 as $k=>$value)
            //{
            //    if($k != 2)
            //    {
            //        $this->dateformonth = $this->dateformonth.$value.'_';
            //        $this->dateformonth =  $this->dateformonth.'2'.'_';
            //    }
            //}
            //$tablename = $this->dateformonth.'ordercount';
            if((int)$time1 > (int)$time2)
            {
                $this->error('操作失误');
            }
            $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));            
        }
        $group = '';
        $field = '';
        $order = '';

        if($iscpgame == 1 || $isprop == 1 || $isco == 1 ||$istelecom == 1 || $ishour == 1 || $iscity == 1 || $_GET['cpgame'] != null || $_GET['telecomname'] != null || $_GET['isday'] != null || $_GET['isegt'] != null)
        {
            $field = 'id';
            if($iscpgame == 1)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'cpgame';
                $order = 'msggold desc';
                $field = $field.',cpgame';
            }
            if($iscpgame != 1 && $_GET['cpgame']!= null)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'cpgame';
                $order = 'msggold desc';
                $field = $field.',cpgame';
            }
            if($isprop == 1)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'prop';
                $order = 'msggold desc';
                $field = $field.',prop';
            }
            if($isprop != 1 && $_GET['prop']!= null)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'prop';
                $order = 'msggold desc';
                $field = $field.',prop';
            }
            if($isco == 1)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'co';
                $order = 'msggold desc';
                $field = $field.',co';
            }
            if($isco != 1 && $_GET['co']!= null)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'co';
                $order = 'msggold desc';
                $field = $field.',co';
            }
            $field = $field.'sum(user_value) user_value ';
        }
        else if($ischilddata == 1)
        {
            $field = '';
            $group = '';
            $order = 'msggold desc';
        }
        else
        {
            if($is_outlets_visible == 1)
            {
                $iscpgame = 1;
                $group = $group.'cpgame';
                $order = 'msggold desc';
                $field = $field.'id,cpgame,time';
                $field = $field.',day,sum(payresult) payresult,sum(xysuccess) xysuccess,sum(paysuccess)paysuccess,sum(msggold) msggold, sum(badvalue) badvalue,sum(badgold) badgold ';
            }
            else
            {
                $istelecom = 1;
                $group = $group.'telecomname';
                $order = 'msggold desc';
                $field = $field.'id,telecomname,time';
                $field = $field.',day,sum(payresult) payresult,sum(xysuccess) xysuccess,sum(paysuccess)paysuccess,sum(msggold) msggold, sum(badvalue) badvalue,sum(badgold) badgold ';
            }
        }

        if($map == null)
        {

            G('begin3');

            $info   =   $this->lists2($model2->where($selectwhere),$selectwhere,$order,$field,$group);
            $sql = $model2->_sql();
            //print_r($sql);
            //var_dump($sql);

            $sum_order = $model2->where($selectwhere)->sum('payresult');
            
            $sum_success_order = $model2->where($selectwhere)->sum('paysuccess');
            if($isbad_visible!=2)
            {
                G('sumbegin');
                $sum_fee = $model2->where($selectwhere)->sum('msggold');
                $sql = $model2->_sql();
                
                G('sumend');
                $timelong =  G('sumbegin','sumend',10);
                //var_dump($timelong);
                //var_dump($sql);
            }
            else
            {
                $sum_fee = $model2->where($selectwhere)->sum('msggold');

                $sum_fee1 = $model2->where($selectwhere)->sum('badgold');
                $sum_fee = $sum_fee - $sum_fee1;
                $bad_value = $model2->where($selectwhere)->sum('badvalue');
                $sum_success_order = $sum_success_order - $bad_value;
            }
            G('end3');
            $time =  G('begin3','end3',10);
            //var_dump($time);
            //var_dump($sql);

        }
        else
        {
            G('begin');
            $group = 'cpgame';
            $map['_complex'] = $selectwhere;
            $info   =   $this->lists2($model2->where($map),$map,$order,$field,$group);
            $sql = $model2->_sql();
           // print_r($sql);
            $sum_success_order = $model2->where($map)->sum('paysuccess');
            if($isbad_visible!=2)
            {
                $sum_fee = $model2->where($selectwhere)->sum('msggold');
            }
            else
            {
                $sum_fee = $model2->where($selectwhere)->sum('msggold');
                $sum_fee1 = $model2->where($selectwhere)->sum('badgold');
                $sum_fee = $sum_fee - $sum_fee1;
                $bad_value = $model2->where($selectwhere)->sum('badvalue');
                $sum_success_order = $sum_success_order - $bad_value;
            }
            G('end3');
            $time =  G('begin3','end3',10);
            //var_dump($time);
        }
        ////var_dump($isold);
       /* $data = array('sum_order'=>$sum_order,'sum_fee'=>$sum_fee,'sum_success_order'=>$sum_success_order,'isday'=>$selectwhere['isday']
            ,'ishour'=>$selectwhere['ishour'],'iscpgame'=>$selectwhere['iscpgame'],'iscode'=>$selectwhere['iscode'],'telecomname'=>$selectwhere['telecomname']
            ,'outletname'=>$selectwhere['outletname'],'egt'=>$selectwhere['egt']);
        $this->assign('data',$data);
        $this->assign('time_start',$datatime1s);
        $this->assign('time_end',$datatime2s);
        $citylist = \PublicData::$city;

        $paycodeinfo = M('paycodenamelist')->select();
        $paycodelist = \PublicData::$paygoldlist;
        $gamelist = M('gamelist')->field('id,name')->select(); 
        $colist = M('colist')->field('id,name')->select(); 
        $proplist = M('proplist')->field('id,name')->select(); 
        foreach($info as $k=>$value)
        {
            $info[$k]['egt'] = \PublicData::$egtlist[$info[$k]['egt']]['name'];
            
            foreach($gamelist as $value3)
            {
                if($value['cpgame'] == $value3['id'])
                {
                    $info[$k]['cpgame'] = $value3['name'];
                }
            }
            foreach($colist as $value4)
            {
                if($value['co'] == $value4['id'])
                {
                    $info[$k]['co'] = $value4['name'];
                }
            }
            foreach($proplist as $value4)
            {
                if($value['prop'] == $value4['id'])
                {
                    $info[$k]['prop'] = $value4['name'];
                }
            }
            foreach($paycodeinfo as $value3)
            {
                if($value['paycode'] == $value3['id'])
                {
                    $info[$k]['paycode'] = $value3['name'];
                }
            }
            foreach($telecominfo as $value4)
            {
                if($value['telecomname'] == $value4['id'])
                {
                    $info[$k]['telecomname'] = $value4['name'];
                }
            }
            $cityid = $info[$k]['city'];
            if($cityid == 50)
                $info[$k]['city']='其他';
            else
            {
                foreach($citylist as $value5)
                {
                    $id = $value5['id'];
                    if((int)$cityid == $id)
                    {
                        $info[$k]['city'] = $value5['city'];
                    }
                }
            }
            $info[$k]['time'] = date("Y-m-d H:i:s", $value['time']);
            $info[$k]['xyfaild']= $info[$k]['payresult'] - $info[$k]['xysuccess'];
            $info[$k]['payfaild']= $info[$k]['xysuccess'] - $info[$k]['paysuccess'];
            $paysuccess = $info[$k]['paysuccess'];
            $payresult = $info[$k]['xysuccess'];
            $success = $paysuccess/$payresult;
            $success = $success *100;
            $success = substr($success,0,5);
            $info[$k]['success']= $success;
            $outletspaysuccess = $paysuccess -  $info[$k]['badvalue'];
            $success = $outletspaysuccess/$payresult;
            $success = $success *100;
            $success = substr($success,0,5);
            $info[$k]['outletssuccess']= $success;
            $outletsmsggoldsuccess = $info[$k]['msggold'] - $info[$k]['badgold'];
            $info[$k]['outletsmsggoldsuccess'] = $outletsmsggoldsuccess;
            $paysuccess = $paysuccess;
            $badvalue = $info[$k]['badvalue'];
            $bads = $badvalue/$paysuccess;
            $bads = $bads *100;
            $bads = substr($bads,0,5);
            $info[$k]['bads'] = $bads;
            $outletsuccess = $outletspaysuccess;

            $info[$k]['outletsuccess'] = $outletsuccess;
            if($info[$k]['hour'] == null)
            {
                $info[$k]['hour'] = '';
            }
        }*/
       /* $mod = M('usercount');
        $dlist = $mod->field('max(uid),coid,count(uid) as tuser, sum(newuser) as newuser, sum(saleuser) as saleuser,
                            sum(daynewuser) as daynewuser, sum(daysaleuser) as daysaleuser')->group('coid,appid')->select();
        $sql=$mod->_sql();*/
       //print_r($dlist);

        //生成查询条件
        $seachwhere['t.coid'] =I('get.coid'); 
        $seachwhere['t.appid'] =I('get.appid');
        $seachdata['coid'] =I('get.coid'); 
        $seachdata['appid'] =I('get.appid'); 
        foreach ($seachwhere as $k=> $value) {
            if ($seachwhere[$k]==='') {
                unset($seachwhere[$k]);
            }else{
                $arr .= $k.'='.$value.' and ';
            }
        }
        $arr = rtrim($arr,' and ');
        //生成分页总条数
        if ($seachwhere) {
           $sql = "SELECT MAX(t.uid), t.coid, t2.name AS name1, t.appid, t3.name AS name2, COUNT(t.uid) AS tuser, SUM(t.newuser) 
                AS newuser,SUM(t.saleuser) AS saleuser, SUM(t.daynewuser) AS daynewuser,SUM(t.daysaleuser) AS daysaleuser 
                FROM  bks_usercount t LEFT JOIN bks_colist t2 ON (t.coid=t2.coid) LEFT JOIN bks_gamelist t3 ON (t.appid=t3.appid) where $arr
                GROUP BY t.coid";
        }else{
             $sql = "SELECT MAX(t.uid), t.coid, t2.name AS name1, t.appid, t3.name AS name2, COUNT(t.uid) AS tuser, SUM(t.newuser) 
                AS newuser,SUM(t.saleuser) AS saleuser, SUM(t.daynewuser) AS daynewuser,SUM(t.daysaleuser) AS daysaleuser 
                FROM  bks_usercount t LEFT JOIN bks_colist t2 ON (t.coid=t2.coid) LEFT JOIN bks_gamelist t3 ON (t.appid=t3.appid) 
                GROUP BY t.coid";
        }
        $mod = M();
        $dlist = $mod->query($sql);
        $count = count($dlist);
        //分页
        $page = new \Think\Page($count,25);
        $show = $page->show();
        if ($seachwhere) {
           $sql = "SELECT MAX(t.uid), t.coid, t2.name AS name1, t.appid, t3.name AS name2, COUNT(t.uid) AS tuser, SUM(t.newuser) 
                AS newuser,SUM(t.saleuser) AS saleuser, SUM(t.daynewuser) AS daynewuser,SUM(t.daysaleuser) AS daysaleuser 
                FROM  bks_usercount t LEFT JOIN bks_colist t2 ON (t.coid=t2.coid) LEFT JOIN bks_gamelist t3 ON (t.appid=t3.appid) where $arr
                GROUP BY t.coid ORDER BY  t.coid limit $page->firstRow,$page->listRows";
        }else{
             $sql = "SELECT MAX(t.uid), t.coid, t2.name AS name1, t.appid, t3.name AS name2, COUNT(t.uid) AS tuser, SUM(t.newuser) 
                AS newuser,SUM(t.saleuser) AS saleuser, SUM(t.daynewuser) AS daynewuser,SUM(t.daysaleuser) AS daysaleuser 
                FROM  bks_usercount t LEFT JOIN bks_colist t2 ON (t.coid=t2.coid) LEFT JOIN bks_gamelist t3 ON (t.appid=t3.appid) 
                GROUP BY t.coid ORDER BY  t.coid limit $page->firstRow,$page->listRows";
        }
        $mod = M();
        $list = $mod->query($sql);
        $m = $mod->_sql();
        $sum=array();
        foreach($list as $k=>$v){
        //总用户
        $sum['tuser'] += $v['tuser'];
        //总新增用户
        $sum['newuser'] += $v['newuser'];
        $sum['daynewuser'] += $v['daynewuser'];
        $sum['daysaleuser'] += $v['daysaleuser'];
        $sum['saleuser'] += $v['saleuser'];
        
        }
        
        //print_r($list);
        //游戏
        $gamelist = M('gamelist')->field('name,appid')->select();
        //厂商
        $colist = M('colist')->field('coid,name')->select();
        $egtlist = \PublicData::$egtlist;
        $this->assign('sum',$sum);
        $this->assign('page',$show);
        $this->assign('seachdata',$seachdata);
        $this->assign('gamelist',$gamelist);
        $this->assign('is_outlets_visible',$is_outlets_visible);
        $this->assign('is_telecom_visible',$is_telecom_visible);
        $this->assign('egtlist',$egtlist);
        $this->assign('isbad_visible',$isbad_visible);
        $this->assign('ischilddata',$ischilddata);
        $this->assign('isegt',$isegt);
        $this->assign("list",$list);
        $this->assign("paycode",$paycodelist);
        $this->assign('outletname',$colist);
        $this->assign("telecomname",$telecominfo);
        $this->assign('iscpgame',$iscpgame);
        $this->assign('isco',$isco);
        $this->assign('isprop',$isprop);

        $this->assign('istelecom',$istelecom);
        $this->assign('iscity',$iscity);
        $this->assign('ishour',$ishour);
        $this->assign('isday',$isday);
        G('countend');
        $timelong =  G('countbegin','countend',10);
        //var_dump($timelong);
        $this->display();
    }
    
    //excel下载
    public function makecountexcel($time_start = '',$time_end = '',$xystatus = '',$orderstatus = '',$type = '',$telecom = '',$outlets = '',$cpgame='',$prop='',$coname='',$iccid_cityt='',$egt='')
    {
        $time1 = $time_start.' 00:00:00'; 
        $time1 = strtotime($time1);
        
        $time2 = $time_end.' 23:59:59'; 
        $time2 = strtotime($time2);
        $time = $time1;
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth =  $this->dateformonth.'2'.'_';

        $tablename = $this->dateformonth.'vnorder';
        $model2 = M($tablename);     
        $outletsinfo = array();
        $this->auto_user($selectwhere,$outletsinfo,'outlets');
        if($xystatus != 'Array' and $xystatus != '')
        {
            $selectwhere['xystatus'] = $xystatus;
        }
        if($orderstatus != 'Array' and $orderstatus != '')
        {
            $selectwhere['orderstatus'] = $orderstatus;
        }
        if($type != 'Array' and $type != '')
        {
            $selectwhere['type'] = $type;
        }
        if($telecom != 'Array' and $telecom != '')
        {
            $selectwhere['telecom'] = $telecom;
        }
        if($coname != 'Array' and $coname != '')
        {
            $selectwhere['coname'] = $coname;
        }
        if($cpgame != 'Array' and $cpgame != '')
        {
            $selectwhere['cpgame'] = $cpgame;
        }
        if($prop != 'Array' and $prop != '')
        {
            $selectwhere['prop'] = $prop;
        }
        if($iccid_cityt != 'Array' and $iccid_cityt != '')
        {
            $selectwhere['iccid_city'] = $iccid_cityt;
        }
        if($egt != 'Array' and $egt != '')
        {
            $selectwhere['egt'] = $egt;
        }


        //  if($outlets != 'array' and $outlets != '')
        //{
        //$selectwhere['outlets'] = $outlets;
        //}
        $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));
        //查询vnorder所有数据
        $info = $model2->where($selectwhere)->select();
        $index = -1;
        $index2 = 0;
        //通道类型
        $telecomtypeinfo = M('telecomtypelist')->select();
        //通道
        $telecominfo = M('telecomlist')->select();
        //游戏名称
        $gamelist = M('gamelist')->select();
        //厂商名称
        $colist = M('colist')->select();
        //道具名称
        $prop = M('proplist')->select();
        //
        $statuslist = \PublicData::$vnstatus;
        $egtlist = \PublicData::$egtlist;
        $data[0] = array('id'=>'编号','egt'=>'运营商', 'type'=>'通道类型','coname'=>'厂商','cpgame'=>'游戏名','prop'=>'道具','telecom'=>'通道','extern'=>'透传参数'
,'city'=>'省份','iccid_city'=>'iccid_省份','imei'=>'imei','imsi'=>'imsi', 'xystatus'=>'协议状态','orderstatus'=>'订单状态','time'=>'时间');
        foreach($info as $value)
        {
            $index++;
            $index2++;
            $value['time'] = date("Y-m-d H:i:s", $value['time']);

            $data[$index2] = array('id'=>$value['id'],'egt'=>$value['egt'],'type'=>$value['type'],'coname'=>$value['co'],'cpgame'=>$value['cpgame'],'prop'=>$value['prop'],'telecom'=>$value['telecom'],'extern'=>$value['extern']
    ,'city'=>$value['city'],'iccid_city'=>$value['iccid_city'],'imei'=>$value['imei'],'imsi'=>$value['imsi'], 'xystatus'=>$value['xystatus'],'orderstatus'=>$value['orderstatus'],'time'=>$value['time']);
            foreach($colist as $v){
                if($value['co'] == $v['coid']){
                    $data[$index2]['coname'] = $v['name'];
                }
            }
            foreach($gamelist as $v1){
                if($value['cpgame']==$v1['appid']){
                    $data[$index2]['cpgame']=$v1['name'];
                }
            }
            foreach($prop as $v2){
                if($value['prop']==$v2['propid']){
                    $data[$index2]['prop'] = $v2['name'];
                }
            }
            foreach($telecomtypeinfo as $value2)
            {
                if($value['type'] == $value2['id'])
                {
                    $data[$index2]['type'] = $value2['name'];
                }
            }
            foreach($outletsinfo as $value3)
            {
                if($value['outlets'] == $value3['id'])
                {
                    $data[$index2]['outlets'] = $value3['name'];
                }
            }
            foreach ($egtlist as $key => $value5) {
                if ($value['egt']==$value5['id']) {
                    $data[$index2]['egt']=$value5['name'];
                }
            }
            foreach($telecominfo as $value4)
            {
                if($value['telecom'] == $value4['id'])
                {
                    $data[$index2]['telecom'] = $value4['name'];
                }
            }
            $id = $value['xystatus'];
            $data[$index2]['xystatus'] = $statuslist[$id]['name']; 
            $id = $value['orderstatus'];
            $data[$index2]['orderstatus'] = $statuslist[$id]['name'];
        }
        phpExcel($data,$time_start.'至'.$time_end.'破解订单表');
    }
    
    public function userindex(){
    
        $time = time();
        
        $time1 = '';
        $time2 = '';
        
        if(isset($_GET['timestart'])){
            $time1    =  (string)$_GET['timestart'].' 00:00:00';
            $time1 = strtotime($time1);
        }
        else
        {
            $t = time();
            $time1 = date("y-m-d",$t);
            $time1 = $time1.' 00:00:00'; 
            $time1 = strtotime($time1);
        }
        if(isset($_GET['timestart2'])){
            $time2    =   (string)$_GET['timestart2'].' 23:59:59';
            $time2 = strtotime($time2);
        }
        else
        {
            $t = time();
            $time2 = date("Y-m-d",$t).' 23:59:59'; 
            $time2 = strtotime($time2);
        }
        $datatime1s = (string)date("Y-m-d",$time1); 
        $datatime2s =(string)date("Y-m-d",$time2);
        $datatime1 = explode('-',$datatime1s);
        $datatime2 = explode('-',$datatime2s);
        //if($datatime1[1] != $datatime2[1])
        //{
        //    $this->error('不允许跨月查询');
        //}
      
        $model = M();
        $model = D('StatRemain');
        $data = array('appid','coid');
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'name');
        $selectwhere = $selectwhere_map[0];
        // print_r($selectwhere);
        $map = $selectwhere_map[1];
        $selectwhere['stat_time'] = array(array('egt',$datatime1s),array('elt',$datatime2s)); 
        $data = array('appid'=>$selectwhere['appid'],'coid'=>$selectwhere['coid']);
        //模拟数据库操作
        if($map == null)
        {   
            $list   =   $this->lists($model->where($selectwhere),$selectwhere);
       //     print_r( $sql =$model->_sql());
        }
        else
        {
            $list   =   $this->lists($model->where($map),$map);
           // print_r( $sql =$model->_sql());
        }
        $gamelist = M('gamelist')->field('appid,name')->select();
        $colist = M('colist')->field('id,coid,name')->select();
        foreach($list as $k=>$value){
           
            foreach($colist as $v)
            {
                if($value['coid'] == $v['coid'])
                {
                     $list[$k]['coid'] = $v['name'];
                     
                 }
            }
            foreach($gamelist as $v1){
                if($v1['appid'] == $value['appid'])
                {
                    $list[$k]['appid'] = $v1['name'];
                    
                }
            }
        }

        $this->assign('list',$list);
        $this->assign('gamelist',$gamelist);
        $this->assign('data',$data); 
        $this->assign('time_start',$datatime1s);
        $this->assign('time_end',$datatime2s);
        $this->assign('colist',$colist);
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->display();
   }
    
    
}
