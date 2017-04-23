<?php
namespace Admin\Controller;
include_once"PublicData.php";
include_once"PublicFunction.php";
/**
 * Vn short summary.
 *
 * Vn description.
 *
 * @version 1.0
 * @author admin
 */
//破解控制器
class VnController extends AdminController
{
    public function index()
    {
        $this->display();
    }
    public function order()
    {   
        $time = time();
        $time1 = '';
        $time2 = '';
        $selectwhere = array();
        $seltcteach=$_GET['selectall'];
        if(isset($_GET['name'])){          
            if($seltcteach == 'imei')
            {   
                $map['t.imei']    =   (string)$_GET['name'];
            }
            else if($seltcteach == 'imsi')
            {   
                $map['t.imsi']    =   (string)$_GET['name'];
            }
            else if($seltcteach == 'id')
            {
               // if(i){}
                $map['t.id']    =  $_GET['name'];
                 $seltcteach ='订单号';
            }else if($seltcteach == '订单号')
            {
                $map['t.id']    =  $_GET['name'];
                $seltcteach ='订单号';
            }else if($seltcteach == 'maxid')
            {
                $map['t.maxid']    =  $_GET['name'];
                $seltcteach ='支付号';
            }else if($seltcteach == '支付号')
            {
                $map['t.maxid']    =  $_GET['name'];
                $seltcteach ='支付号';
            }
            else if($seltcteach == 'extdata')
            {
                $map['t.extern']    =  $_GET['name'];
            }
            else if($seltcteach == 'mark')
            {
                $map['t2.mark']    =  $_GET['name'];
                $seltcteach ='手机品牌';
            }else if($seltcteach == '手机品牌')
            {
                $map['t2.mark']    =  $_GET['name'];
                $seltcteach ='手机品牌';
            }
            else if($seltcteach == 'os_model')
            {
                $map['t2.os_model']    =  $_GET['name'];
                $seltcteach ='手机型号';
            }else if($seltcteach == '手机型号')
            {
                $map['t2.os_model']    =  $_GET['name'];
                $seltcteach ='手机型号';
            }
            else if($seltcteach == 'city')
            {
                $map['t.city']    = $_GET['name'];
                $seltcteach ='省份';
            }else if($seltcteach == '省份')
            {
                $map['t.city']    = $_GET['name'];
                $seltcteach ='省份';
            }
            else if($seltcteach == 'iccid_city')
            {
                $map['t.iccid_city']    = $_GET['name'];
                $seltcteach ='iccid省份';
            }else if($seltcteach == 'iccid省份')
            {
                $map['t.iccid_city']    = $_GET['name'];
                $seltcteach ='iccid省份';
            }
            else if($seltcteach == 'time')
            {
                $map['t.time']    =  strtotime($_GET['name']);
                $seltcteach ='时间';
            }else if($seltcteach == '时间')
            {
                $map['t.time']    =  strtotime($_GET['name']);
                $seltcteach ='时间';
            }
        }
        //if(isset($_GET['name'])){          
        //    $len = strlen($_GET['name']);
        //    if($len == 12)
        //    {
        //        $map['code']    =  array('like', '%'.(string)$_GET['name'].'%');
        //    }
        //    else if($len == 15&& is_numeric($_GET['name'])==false)
        //    {
        //        $map['imei'] = (string)$_GET['name'];
        //    }
        //    else if($len == 15)
        //    {
        //        $map['imsi'] = (string)$_GET['name'];
        //    }
        //    else if($len == 6 && is_numeric($_GET['name']))
        //    {
        //        $map['maxid']    =  array('like', '%'.(string)$_GET['name'].'%');
        //    }
        //    else
        //    {
        //        $map['id']    =  array('like', '%'.(string)$_GET['name'].'%');
        //    }
        //}
        $outletsinfo = array(); 
        $telecominfo = array();
        $keynames = array('t.telecom','t.co');

        $is_t_o_v = $this->check_auth_info($selectwhere,$outletsinfo,$telecominfo,$keynames);        
        $is_telecom_visible = $is_t_o_v[0];
        $is_outlets_visible = $is_t_o_v[1];
        $isbad_visible = $is_t_o_v[2];
        $group_id   =  $is_t_o_v[3];
        //$is_telecom_visible = 2;
        //$is_outlets_visible = 2;      
        if(isset($_GET['errorcode'])){
            $selectwhere['errorcode']    =   (string)$_GET['errorcode'];
            $selectwherestr['errorcode']    =   (string)$_GET['errorcode'];
        }
        if(isset($_GET['xystatus'])){
            $selectwhere['xystatus']    =   (string)$_GET['xystatus'];
            $selectwherestr['xystatus']    =   (string)$_GET['xystatus'];
        }
        if(isset($_GET['orderstatus'])){
            $selectwhere['orderstatus']    =   (string)$_GET['orderstatus'];
            $selectwherestr['orderstatus']    =   (string)$_GET['orderstatus'];
        }
        if(isset($_GET['cpgame'])){
            $selectwhere['cpgame']    =   (string)$_GET['cpgame'];
            $selectwherestr['cpgame']    =   (string)$_GET['cpgame'];
        }
        if(isset($_GET['telecom'])){
            $selectwhere['t.telecom']    =   (string)$_GET['telecom'];
            $selectwherestr['t.telecom']    =   (string)$_GET['telecom'];
        }
        if(isset($_GET['coname'])){
            $selectwhere['co']    =   (string)$_GET['coname'];
            $selectwherestr['co']    =   (string)$_GET['coname'];
        }
        if(isset($_GET['prop'])){
            $selectwhere['t.prop']    =   (string)$_GET['prop'];
            $selectwherestr['t.prop']    =   (string)$_GET['prop'];
        }
        if(isset($_GET['egt'])){
            $selectwhere['t.egt']    =   (string)$_GET['egt'];
            $selectwherestr['t.egt']    =   (string)$_GET['egt'];
        }
        if(isset($_GET['userstatus'])){
            $selectwhere['newuser']    =   (string)$_GET['userstatus'];
            $selectwherestr['newuser']    =   (string)$_GET['userstatus'];
        }
       
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
        $arr='%';
        $iccid_citywhere=$selectwhere['iccid_city']['1'];
        $iccid_city=str_replace($arr,"",$iccid_citywhere);     
        $isold =  $_GET['isold'];
        $data = array('isold'=>$isold,'xystatus'=>$selectwhere['xystatus'],'selectall'=>$seltcteach,'orderstatus'=>$selectwhere['orderstatus']
            ,'telecom'=>$selectwhere['t.telecom'],'coname'=>$selectwhere['co'],'cpgame'=>$selectwhere['cpgame'],'prop'=>$selectwhere['t.prop'],'iccid_cityt'=>$iccid_city,'egt'=>$selectwhere['t.egt'],'newuser'=>$selectwhere['newuser'],'mark'=>$selectwhere['mark'],'model'=>$selectwhere['model']);
        if($time1!='' && $time2!='')
        {
            if(empty($isold)) $isold = 0;
            $this->dateformonth = \PublicFunction::getTimeForYH($time1).'_';
            if($isold != 1)
            {
                $this->dateformonth =  $this->dateformonth.'2'.'_';
            }
            //$this->dateformonth =  $this->dateformonth.'2'.'_';
            $citylist = \PublicData::$city;        //获取省份 
            $tablename = $this->dateformonth.'vnorder';
            $model2 = M($tablename);
            $datatime1s = (string)date("Y-m-d",$time1); 
            $datatime2s =(string)date("Y-m-d",$time2);
            $datatime1 = explode('-',$datatime1s);
            $datatime2 = explode('-',$datatime2s);
            if($datatime1[1] != $datatime2[1])
            {
                $this->error('不允许跨月查询');
            }
            $this->dateformonth ='';
            foreach($datatime1 as $k=>$value)
            {
                if($k != 2)
                {
                    $this->dateformonth = $this->dateformonth.$value.'_';
                    if($isold != 1)
                    {
                        $this->dateformonth =  $this->dateformonth.'2'.'_';
                    }
                }
            }
            //$tablename = $this->dateformonth.'vnorder';
            //$model2 = M($tablename);
            if((int)$time1 > (int)$time2)
            {
                $this->error('操作失误');
            }
          //  $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));    
           // $selectwhere['status']=1;
        }
        $order = 'maxid desc,id desc';
        //获取数据表
        $dateformonth = \PublicFunction::getTimeForYH($time1).'_';
        $dateformonth= $dateformonth.'2'.'_'; 
        $tablename =C('DB_PREFIX').$dateformonth.'vnorder'; 
          if($_GET['userstatus']==2){        //判断newuser  为2 改成0
                   $selectwhere['newuser']=0;
                    $selectwherestr['newuser']=0;
            }
            if($group_id==null){
                foreach ($selectwhere as $k=>$v){
                    $ab .= ' and '.$k.' = \''.$v.'\'';
                }
            
                if(isset($_GET['city'])){
                    $abeach .= ' and '.iccid_city.' like\'%'.$_GET['city'].'%\'';
                }
            }else{
                  // $n=1;
                foreach ($selectwherestr as $k=>$v){
                           $abstr .= ' and '.$k.' = \''.$v.'\'';
                    }
                    foreach ($selectwhere['t.co'][1] as $k=>$v){
                        $selectwherein .=',\''.$v.'\'';
                    }
                    $whereadasdasd=ltrim($selectwherein,',');
                        $selectwherearr='and t.co in'.'('.$whereadasdasd.')';
                    foreach ($selectwhere['t.telecom'][1] as $k=>$v){
                                $selectwhereintoo .=',\''.$v.'\'';
                    }
                     $whereadasdasdtoo=ltrim($selectwhereintoo,',');
                     $selectwheretle='and t.telecom in'.'('.$whereadasdasdtoo.')';
            }
            
        $time = ' and t.time > '.$time1.' and t.time < '.$time2;
        foreach ($map as $k=>$v){           //输入框搜索
            $cd .= ' and '.$k.' like \'%'.$v.'%\'';
        }
        
        $mm=M();
        $inf="  SELECT t.`id`,t.`type`,t.`co`,t.`cpgame`,t.`prop`,t.`telecom`,t.`extern`,t.`extern2`,t.`bksid`,t.`city`,t.`iccid_city`,t.`xystatus`,t.`orderstatus`,
                 t.`time`,t.`egt`,t.`paycode`,t.`imei`,t.`imsi`,t.`errorcode`,t.`status`, t.`adtype`,t.`orderid`,t.`orderno`,t.`maxid`,ifnull(t2.newuser,0) as newuser,t2.mark,t2.os_model as model
            FROM $tablename t  left join bks_usercount t2 on (t.bksid=t2.bksid) WHERE( t.`status` = 1) $time $ab $cd $selectwherearr $selectwheretle $abstr $abeach ORDER BY  t.maxid DESC ,id DESC";
        $info1=$mm->query($inf);
          $aaa= M()->_sql();
        $count =  count($info1);// 查询满足要求的总记录数
        $page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        $show = $page->show();// 分页显示输出
        $inf="  SELECT t.`id`,t.`type`,t.`co`,t.`cpgame`,t.`prop`,t.`telecom`,t.`extern`,t.`extern2`,t.`bksid`,t.`city`,t.`iccid_city`,t.`xystatus`,t.`orderstatus`,
                t.`time`,t.`egt`,t.`paycode`,t.`imei`,t.`imsi`,t.`errorcode`,t.`status`, t.`adtype`,t.`orderid`,t.`orderno`,t.`maxid`,ifnull(t2.newuser,0) as newuser,t2.mark,t2.os_model as model
          FROM $tablename t  left join bks_usercount t2 on (t.bksid=t2.bksid) WHERE( t.`status` = 1) $time $ab $cd $selectwherearr $selectwheretle $abstr $abeach ORDER BY  t.maxid DESC , id DESC limit $page->firstRow,$page->listRows";
        $info = $mm->query($inf);
        $aaa= M()->_sql();
        //if($map == null)
        //{
        // //   $info   =   $this->lists($model2->where($selectwhere),$selectwhere,$order);
        
        //}
        //else
        //{
        //    $map['_complex'] = $selectwhere;
        //    $info   =   $this->lists4($model2->where($map),$map,$order);
        //    if($info == null)
        //    {
        //        $map2['extern']    =  array('like', '%'.(string)$_GET['name'].'%');
        //        $map2['_complex'] = $selectwhere;

        //        $info   =   $this->lists4($model2->where($map2),$map2,$order);
        //    }
        //}
        $telecomtypeinfo = M('telecomtypelist')->select();
        $gamelist = M('gamelist')->field('id,name,appid')->select(); 
        $colist = M('colist')->field('id,name,coid')->select(); 
        $proplist = M('proplist')->field('id,name,propid')->select(); 
        $telecomlist = M('telecomlist')->field('id,name,egt')->select();
        $statuslist = \PublicData::$vnstatus;
        $egtlist = \PublicData::$egtlist;
        $index -=1;
        foreach($info as $k=>$value)
        {
            $index ++;
            if($info[$k]['newuser']==0){       //0为否
                $info[$k]['newuser']=2;
            }
            foreach($telecomtypeinfo as $value2)
            {
                if($value['type'] == $value2['id'])
                {
                    $info[$k]['type'] = $value2['name'];
                }
            }
            foreach($telecominfo as $value3)
            {
                if($value['telecom'] == $value3['id'])
                {
                    $info[$k]['telecom'] = $value3['name'];
                }
            }
            foreach($gamelist as $value3)
            {
                if($value['cpgame'] == $value3['appid'])
                {
                    $info[$k]['cpgame'] = $value3['name'];
                }
            }
            foreach($colist as $value4)
            {
                if($value['co'] == $value4['coid'])
                {
                    $info[$k]['co'] = $value4['name'];
                }
            }
            foreach($proplist as $value4)
            {
                if($value['prop'] == $value4['propid'])
                {
                    $info[$k]['prop'] = $value4['name'];
                }
            }
            //获取运营商
            foreach ($egtlist as $v=>$value5) {
               if ($value['egt']==$value5['id']) {
                   $info[$k]['egt'] = $value5['name'];
               }
            }
            $cityid = $info[$k]['city'];
            if($cityid == 50)
                $info[$k]['city']='其他';
            else
            {
                foreach($citylist as $value6)
                {
                    $id = $value6['id'];
                    if((int)$cityid == $id)
                    {
                        $info[$k]['city'] = $value6['city'];
                    }
                }
            }
            $id = $value['xystatus'];
            $info[$k]['xystatus'] = $statuslist[$id]['name']; 
            $id = $value['orderstatus'];
            $info[$k]['orderstatus'] = $statuslist[$id]['name'];
            $info[$index]['time'] = date("Y-m-d H:i:s", $value['time']);
        }
        $egt = \PublicData::$egtlist;
        foreach ($info as $k => $v) {
            foreach ($egt as $value) {
                if ($v['egt'] ==$value['id']) {
                    $info[$k]['egt'] = $value['name'];
                }
            }
        }
        //是否新用户  1是 0否
        $userstatus = \PublicData::$userstatus;
        foreach ($info as $k => $v) {
            foreach ($userstatus as $value) {
                if ($info[$k]['newuser'] ==$value['id']) {
                    $info[$k]['infonewuser'] = $value['name'];
                }
            }
        }
     
        //print_r($data);
        $errorcodelist = \PublicData::$errorcodelist;
        $this->assign('egt',$egt);
        $this->assign('userstatus',$userstatus); 
        $this->assign('group_id',$group_id);
        $this->assign('telecomlist',$telecomlist);
        $this->assign('is_outlets_visible',$is_outlets_visible);
        $this->assign('is_telecom_visible',$is_telecom_visible);
        $this->assign('errorcodelist',$errorcodelist);
        $this->assign("gamelist",$gamelist); //游戏
        $this->assign("proplist",$proplist); //道具
        $this->assign("citylist",$citylist); //获取省份
        //$isbad_visible = 2;
        $this->assign('isbad_visible',$isbad_visible);
        $this->assign('data',$data);
        $this->assign("list",$info);
        $this->assign("page",$show);
        $this->assign("type",$telecomtypeinfo);
        $this->assign("colist",$colist); //厂商
        $this->assign("telecom",$telecominfo);
        $this->assign("status",$statuslist);
        $this->assign('time_start',$datatime1s);
        $this->assign('time_end',$datatime2s);

        $this->display();        
    }
    public function publicf(&$model2,&$value,&$info)
    {
        $time = '2015-03-25 21:39:42';
        $time2 = '2015-03-24 21:39:42';
        $time = '2016-03-25 21:39:42';
        $time2 = '2016-03-24 21:39:42';
        $time3 = '2016-03-26 21:39:42';
        $time4 = '2016-03-23 21:39:42';

        $selectwhere = array('id'=>(int)$value['id']);
       
        $time = strtotime($time);
        $time2 = strtotime($time2);
        $time3 = strtotime($time3);
        $time4 = strtotime($time4);
        $time = (int)$time;
        $time2 = (int)$time2;
        $time3 = (int)$time3;
        $time4 = (int)$time4;
        //dump(strlen((string)$time));
        if((int)$value['id']<10 && (int)$value['id']>0)
        {
            $data = array('time'=>$time);
        }
        else if((int)$value['id']<13 && (int)$value['id']>10)
        {
            $data = array('time'=>$time2);
        }
        else if((int)$value['id']<15 && (int)$value['id']>12)
        {
            $data = array('time'=>$time3);
        }
        else
        {
            $data = array('time'=>$time4);
        }
        $data['utf8name'] = $info['telecomname'];
        $model2->where($selectwhere)->data($data)->save();
    }

    public function count()
    {
        //$info = array(array("id"=>0,"date"=>"2016-02-23","telecomtype"=>"电信翼支付",
        //"telecomname"=>"电信翼支付 飞天冒险夜","channelname"=>"测试渠道","ordervalue"=>"11","province"=>"-","agreements"=>"11 (100.00)",
        //"agreementf"=>"0(0.00)","pays"=>"0(0.00)","payf"=>"0(0.00)")
        // );
        $time1 = '';
        $time2 = '';
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth =  $this->dateformonth.'2'.'_';

        $selectwhere = array();
        if(isset($_GET['name'])){
            $map['utf8name']    =  array('like', '%'.(string)$_GET['name'].'%');
        }
        if(isset($_GET['telecomtype'])){
            $selectwhere['telecomtype']    =   (string)$_GET['telecomtype'];
        }
        if(isset($_GET['telecomname'])){
            $selectwhere['telecomname']    =   (string)$_GET['telecomname'];
        }
        if(isset($_GET['outletsname'])){
            $selectwhere['outletsname']    =   (string)$_GET['outletsname'];
        }
        if(isset($_GET['timestart'])){
            $time1    =  (string)$_GET['timestart'].' 00:00:00';
            $time1 = strtotime($time1);
        }
        if(isset($_GET['timestart2'])){
            $time2    =   (string)$_GET['timestart2'].' 23:59:59';
            $time2 = strtotime($time2);
        }
        $tablename = $this->dateformonth.'vncount';
        $model2 = M($tablename);
        if($time1!='' && $time2!='')
        {
            $datatime1 = (string)$_GET['timestart'];
            $datatime2 =(string)$_GET['timestart2'];
            $datatime1 = explode('-',$datatime1);
            $datatime2 = explode('-',$datatime2);
            if($datatime1[1] != $datatime2[1])
            {
                $this->error('不允许跨月查询');
            }
            $this->dateformonth ='';
            foreach($datatime1 as $k=>$value)
            {
                if($k != 2)
                {
                    $this->dateformonth = $this->dateformonth.$value.'_';
                }
            }
            $tablename = $this->dateformonth.'vncount';
            $model2 = M($tablename);
            if((int)$time1 > (int)$time2)
            {
                $this->error('操作失误');
            }
            $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));            
        }
        //if($map == null)
        //{
        //    $info = $model2->order('id DESC')->where($selectwhere)->select();
        //}
        //else
        //{
        //    $info = $model2->order('id DESC')->where($map)->select();
        //}
        if($map == null)
        {
            $info   =   $this->lists($model2->where($selectwhere),$selectwhere);
        }
        else
        {
            $info   =   $this->lists($model2->where($map),$map);
        }
   //     $info = M('vncount')->select();
        $telecomtypeinfo = M('telecomtypelist')->select();
        $outletsinfo = M('outletslist')->select();
        $telecominfo = M('telecomlist')->select();
        $index = -1;
        foreach($info as $value)
        {
            $index++;
            foreach($telecomtypeinfo as $value2)
            {
                if($value['telecomtype'] == $value2['id'])
                {
                    $info[$index]['telecomtype'] = $value2['name'];
                }
            }
            foreach($outletsinfo as $value3)
            {
                if($value['outletsname'] == $value3['id'])
                {
                    $info[$index]['outletsname'] = $value3['name'];
                }
            }
            foreach($telecominfo as $value4)
            {
                if($value['telecomname'] == $value4['id'])
                {
                    $info[$index]['telecomname'] = $value4['name'];
                }
            }
       //     $this->publicf($model2,$value,$info[$index]);
            $info[$index]['time'] = date("Y-m-d H:i:s", $value['time']);
        }
        $this->assign("list",$info);
        $this->assign("telecomtype",$telecomtypeinfo);
        $this->assign("outletsname",$outletsinfo);
        $this->assign("telecomname",$telecominfo);
        $this->display();
    }

    public function history($orderid=0,$maxid=0)
    {
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth =  $this->dateformonth.'2'.'_';

        $tablename = $this->dateformonth.'vnhistory';
        $model2 = M($tablename);  
        if($orderid == 0)
        {
            $time1 = '';
            $time2 = '';
            $selectwhere = array();
            if(isset($_GET['name'])){
                $map['orderid']    =  array('like', '%'.(string)$_GET['name'].'%');
            }
            if(isset($_GET['type'])){
                $selectwhere['type']    =   (string)$_GET['type'];
            }
            if(isset($_GET['logtype'])){
                $selectwhere['status']    =   (string)$_GET['logtype'];
            }
            if(isset($_GET['timestart'])){
                $time1    =  (string)$_GET['timestart'].' 00:00:00';
                $time1 = strtotime($time1);
            }else{
                $time1  =date('Y-m-d').'00:00:00';
                $time1=strtotime($time1);
            }
            if(isset($_GET['timestart2'])){
                $time2    =   (string)$_GET['timestart2'].' 23:59:59';
                $time2 = strtotime($time2);
            }else{
                $time2  =date('Y-m-d').'23:59:59';
                $time2=strtotime($time2);            
            }
            $data = array('type'=>$selectwhere['type'],'logtype'=>$selectwhere['status']);
            $this->assign('data',$data);
            if($time1!='' && $time2!='')
            {
                $datatime1s = (string)date("Y-m-d",$time1); 
                $datatime2s =(string)date("Y-m-d",$time2);
                $datatime1 = explode('-',$datatime1s);
                $datatime2 = explode('-',$datatime2s);
                if($datatime1[1] != $datatime2[1])
                {
                    $this->error('不允许跨月查询');
                }
                $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));   
                $this->dateformonth ='';
                foreach($datatime1 as $k=>$value)
                {
                    if($k != 2)
                    {
                        $this->dateformonth = $this->dateformonth.$value.'_';
                        if($isold != 1)
                        {
                            $this->dateformonth =  $this->dateformonth.'2'.'_';
                        }
                    }
                }
                // $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';   
                // $this->dateformonth = $this->dateformonth.'2'.'_';
                //$tablename = $this->dateformonth.'vnhistory';
                //$model2 = M($tablename);
                if((int)$time1 > (int)$time2)
                {
                    $this->error('操作失误');
                }
                $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));            
            }
            if($map == null)
            {   
                    if(isset($_GET['timestart']) and isset($_GET['timestart'])){    
                        //  1
                        $timearr = strtotime($_GET['timestart']);
                        $datatimes1 = (string)$timearr;
                        $this->dateformonth = \PublicFunction::getTimeForYH($datatimes1).'_';
                        $this->dateformonth =  $this->dateformonth.'2'.'_';
                        $tablename2 = $this->dateformonth.'vnhistory'; 
                        $model2=M($tablename2);
                        $info   =   $this->lists($model2->where($selectwhere),$selectwhere);
                         //  print_r($model2->_sql());
                    }else{
                        $info   =   $this->lists($model2->where($selectwhere),$selectwhere);   
                    }
            }
            else
            {
                $info   =   $this->lists($model2->where($map),$map);
            }

        }
        else
        {
            //2
            $selectwhere2 = array('maxid'=>$maxid);
            $datatimes1 = strtotime($_GET['time']);
            $this->dateformonth = \PublicFunction::getTimeForYH($datatimes1).'_';
            $this->dateformonth =  $this->dateformonth.'2'.'_';
            $tablename2 = $this->dateformonth.'vnhistory';
            $selectwhere = array('orderid'=>$orderid);
          
            $info = M($tablename2)->order('id DESC')->where($selectwhere2)->select();
            //print_r($a=M()->_sql());
        }

        $telecomtypeinfo = M('telecomtypelist')->select();
        $statuslist = \PublicData::$logtype;
        $index = -1;
        foreach($info as $value)
        {
            $index++;
            foreach($telecomtypeinfo as $value2)
            {
                if($value['type'] == $value2['id'])
                {
                    $info[$index]['type'] = $value2['name'];
                }
            }
            $id = $value['status'];
            $info[$index]['status'] = $statuslist[$id]['name'];
            //      $selectwhere = array('id'=>$value['id']);
            ////      $time2 = strtotime($value['time2']);
            //      $data = array('time'=>$value['time2']);
            //      $model2->where($selectwhere)->data($data)->save();
            $info[$index]['time'] = date("Y-m-d H:i:s", $value['time']);

        }

        $this->assign("list",$info);
        $this->assign("type",$telecomtypeinfo);
        $this->assign("logtype",$statuslist);
        $this->assign('time_start',$datatime1s);
        $this->assign('time_end',$datatime2s);
        $this->display();
    
    
    }
    public function history2($orderid = 0)
    {
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth =  $this->dateformonth.'2'.'_';

        $tablename = $this->dateformonth.'vnhistory';
        $model2 = M($tablename);
        if($orderid == 0)       //破解履历
        {
            $time1 = '';
            $time2 = '';
            $selectwhere = array();
            if(isset($_GET['name'])){
                $map['orderid']    =  array('like', '%'.(string)$_GET['name'].'%');
            }
            if(isset($_GET['type'])){
                $selectwhere['type']    =   (string)$_GET['type'];
            }
            if(isset($_GET['logtype'])){
                $selectwhere['status']    =   (string)$_GET['logtype'];
            }
            if(isset($_GET['timestart'])){
                $time1    =  (string)$_GET['timestart'].' 00:00:00';
                $time1 = strtotime($time1);
            }
            if(isset($_GET['timestart2'])){
                $time2    =   (string)$_GET['timestart2'].' 23:59:59';
                $time2 = strtotime($time2);
            }
            $data = array('type'=>$selectwhere['type'],'logtype'=>$selectwhere['logtype']);
            $this->assign('data',$data);
            
            if($time1!='' && $time2!='')
            {
                $datatimes1 = (string)$_GET['timestart'];
                $datatimes2 =(string)$_GET['timestart2'];
                $datatime1 = explode('-',$datatimes1);
                $datatime2 = explode('-',$datatimes2);
               
                if($datatime1[1] != $datatime2[1])
                {
                    $this->error('不允许跨月查询');
                }
                $this->dateformonth ='';
                foreach($datatime1 as $k=>$value)
                {
                    if($k !=2)
                    {
                        $this->dateformonth = $this->dateformonth.$value.'_';
                    }
                }
                $tablename = $this->dateformonth.'2'.'_'.'vnhistory';
                $model2 = M($tablename);
                if((int)$time1 > (int)$time2)
                {
                    $this->error('操作失误');
                }
                $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));            
            }
            
            $this->assign('time_start',$datatimes1);
            $this->assign('time_end',$datatimes2);
            if($map == null)
            {
                $info   =   $this->lists($model2->where($selectwhere),$selectwhere);
                 //  print_r($model2->_sql());
            }
            else
            {
                $info   =   $this->lists($model2->where($map),$map);
            }

        }
        else
        {
            $selectwhere = array('orderid'=>$orderid);
            $info = M($tablename)->order('id DESC')->where($selectwhere)->select();
            print_r( M($tablename)->_sql());
        }

        $telecomtypeinfo = M('telecomtypelist')->select();
        $statuslist = \PublicData::$logtype;
        $index = -1;
        foreach($info as $value)
        {
            $index++;
            foreach($telecomtypeinfo as $value2)
            {
                if($value['type'] == $value2['id'])
                {
                    $info[$index]['type'] = $value2['name'];
                }
            }
            $id = $value['status'];
            $info[$index]['status'] = $statuslist[$id]['name'];
            $info[$index]['time'] = date("Y-m-d H:i:s", $value['time']);

        }

        $this->assign("list",$info);
        $this->assign("type",$telecomtypeinfo);
        $this->assign("logtype",$statuslist);
        $this->display();
    }
    //excel下载
    public function makecountexcel($time_start = '',$time_end = '',$newuser='',$xystatus = '',$orderstatus = '',$type = '',$telecom = '',$cpgame='',$prop='',$coname='',$iccid_cityt='',$egt='')
    {
        $time1 = $time_start.' 00:00:00'; 
        $time1 = strtotime($time1);
        
        $time2 = $time_end.' 23:59:59'; 
        $time2 = strtotime($time2);
        $time = $time1;
       // $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
       // $this->dateformonth =  $this->dateformonth.'2'.'_';

       // $tablename = $this->dateformonth.'vnorder';
       // $model2 = M($tablename);     
       // $outletsinfo = array();
       // $this->auto_user($selectwhere,$outletsinfo,'coid');
        if($xystatus != 'Array' and $xystatus != '')
        {
            $selectwhere['t.xystatus'] = $xystatus;
        }
        if($newuser != 'Array' and $newuser != '')
        {
            $selectwhere['t.newuser'] = $newuser;
        }
        if($orderstatus != 'Array' and $orderstatus != '')
        {
            $selectwhere['t.orderstatus'] = $orderstatus;
        }
        if($type != 'Array' and $type != '')
        {
            $selectwhere['t.type'] = $type;
        }
        if($telecom != 'Array' and $telecom != '')
        {
            $selectwhere['t.telecom'] = $telecom;
        }
         if($coname != 'Array' and $coname != '')
        {
            $selectwhere['t.co'] = $coname;
        }
         if($cpgame != 'Array' and $cpgame != '')
        {
            $selectwhere['t.cpgame'] = $cpgame;
        }
          if($prop != 'Array' and $prop != '')
        {
            $selectwhere['t.prop'] = $prop;
        }
            if($iccid_cityt != 'Array' and $iccid_cityt != '')
        {
            $selectwhere['t.iccid_city'] = $iccid_cityt;
        }
            if($egt != 'Array' and $egt != '')
        {
            $selectwhere['t.egt'] = $egt;
        }


        //  if($outlets != 'array' and $outlets != '')
        //{
        //$selectwhere['outlets'] = $outlets;
        //}
        // $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));
        //$selectwhere['status'] = 1;
        //查询vnorder所有数据
       // $info = $model2->where($selectwhere)->order('id desc')->select();
            $dateformonth = \PublicFunction::getTimeForYH($time1).'_';
            $dateformonth= $dateformonth.'2'.'_'; 
            $tablename =C('DB_PREFIX').$dateformonth.'vnorder'; 
            if($_GET['userstatus']==2){        //判断newuser  为2 改成0
                $selectwhere['newuser']=0;
                $selectwherestr['newuser']=0;
            }
  
            foreach ($selectwhere as $k=>$v){
                $ab .= ' and '.$k.' = \''.$v.'\'';
            }
                
            if(isset($_GET['city'])){
                $abeach .= ' and '.iccid_city.' like\'%'.$_GET['city'].'%\'';
            }
            $time = ' and t.time > '.$time1.' and t.time < '.$time2;
            foreach ($map as $k=>$v){           //输入框搜索
                $cd .= ' and '.$k.' like \'%'.$v.'%\'';
            }
        $mm=M();
        $inf="  SELECT t.`id`,t.`type`,t.`co`,t.`cpgame`,t.`prop`,t.`telecom`,t.`extern`,t.`extern2`,t.`bksid`,t.`city`,t.`iccid_city`,t.`xystatus`,t.`orderstatus`,
                 t.`time`,t.`egt`,t.`paycode`,t.`imei`,t.`imsi`,t.`errorcode`,t.`status`, t.`adtype`,t.`orderid`,t.`orderno`,t.`maxid`,ifnull(t2.newuser,0) as newuser,t2.mark,t2.os_model as model
            FROM $tablename t  left join bks_usercount t2 on (t.bksid=t2.bksid) WHERE( t.`status` = 1) $time $ab $cd $abeach ORDER BY  t.maxid DESC ,id DESC";
        $info=$mm->query($inf);
        $aaa= M()->_sql();
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
        //是否新用户  1是 0否
        $userstatus = \PublicData::$userstatus;
        $egtlist = \PublicData::$egtlist;
        $data[0] = array('id'=>'订单号','egt'=>'运营商', 'coname'=>'厂商','cpgame'=>'游戏名','prop'=>'道具','telecom'=>'通道','extern'=>'透传参数','city'=>'省份','iccid_city'=>'iccid_省份','imei'=>'imei','imsi'=>'imsi','mark'=>'手机品牌','os_model'=>'手机型号','newuser'=>'是否新用户', 'xystatus'=>'协议状态','orderstatus'=>'订单状态','time'=>'时间');
        foreach($info as $value)
        {
            $index++;
            $index2++;
            $value['time'] = date("Y-m-d H:i:s", $value['time']);

            $data[$index2] = array('id'=>$value['id'],'egt'=>$value['egt'],'coname'=>$value['co'],'cpgame'=>$value['cpgame'],'prop'=>$value['prop'],'telecom'=>$value['telecom'],'extern'=>$value['extern'],'city'=>$value['city'],'iccid_city'=>$value['iccid_city'],'imei'=>$value['imei'],'imsi'=>$value['imsi'],'mark'=>$value['mark'],'os_model'=>$value['model'],'newuser'=>$value['newuser'], 'xystatus'=>$value['xystatus'],'orderstatus'=>$value['orderstatus'],'time'=>$value['time']);
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
            foreach($userstatus as $value6)
            {
                if($value['newuser']==0){
                        $value['newuser']=2;    
                }
                if($value['newuser'] == $value6['id'])
                {
                    $data[$index2]['newuser'] = $value6['name'];
                }
            }
            
            $id = $value['xystatus'];
            $data[$index2]['xystatus'] = $statuslist[$id]['name']; 
            $id = $value['orderstatus'];
            $data[$index2]['orderstatus'] = $statuslist[$id]['name'];
        }
       // print_r($data[$index2]);
        phpExcel($data,$time_start.'至'.$time_end.'破解订单表');
    }
}
