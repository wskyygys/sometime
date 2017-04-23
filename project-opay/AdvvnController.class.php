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
//广告破解控制器
class AdvvnController extends AdminController
{

    public function advorder()
    {   
        $time = time();
        $time1 = '';
        $time2 = '';
        $selectwhere = array();
        $seltcteach=$_GET['selectall'];
        if(isset($_GET['name'])){          
            if($seltcteach == 'imei')
            {
                $map['a.imei']    =   (string)$_GET['name'];
            }
            else if($seltcteach == 'imsi')
            {
                $map['a.imsi']    =   (string)$_GET['name'];
            }
            else if($seltcteach == 'id')
            {
                $map['a.id']    =  $_GET['name'];
                $seltcteach ='订单号';
            }else if($seltcteach == '订单号')
            {
                $map['a.id']    =  $_GET['name'];
                $seltcteach ='订单号';
            }
            else if($seltcteach == 'extdata')
            {
                $map['a.extern']    =  $_GET['name'];
            }
            else if($seltcteach == 'mark')
            {
                $map['b.mark']    =  $_GET['name'];
                $seltcteach ='手机品牌';
            }else if($seltcteach == '手机品牌')
            {
                $map['b.mark']    =  $_GET['name'];
                $seltcteach ='手机品牌';
            }
            else if($seltcteach == 'os_model')
            {
                $map['b.os_model']    =  $_GET['name'];
                $seltcteach ='手机型号';
            }else if($seltcteach == '手机型号')
            {
                $map['b.os_model']    =  $_GET['name'];
                $seltcteach ='手机型号';
            }
            else if($seltcteach == 'city')
            {
                $map['a.city']    = $_GET['name'];
                $seltcteach ='省份';
            }else if($seltcteach == '省份')
            {
                $map['a.city']    = $_GET['name'];
                $seltcteach ='省份';
            }
            else if($seltcteach == 'iccid_city')
            {
                $map['a.iccid_city']    = $_GET['name'];
                $seltcteach ='iccid省份';
            }else if($seltcteach == 'iccid省份')
            {
                $map['a.iccid_city']    = $_GET['name'];
                $seltcteach ='iccid省份';
            }
            else if($seltcteach == 'time')
            {
                $map['a.time']    =  strtotime($_GET['name']);
            }
        }
        //if(isset($_GET['name'])){
        //    $len = strlen($_GET['name']);
        //    if($len <= 6&& is_numeric($_GET['name']) == false)
        //    {
        //        $map['b.mark']    =  (string)$_GET['name'];
        //    }
        //    if($len <= 8&& $len >= 6 && is_numeric($_GET['name']) == false)
        //    {
        //        $map['b.os_model']    =  (string)$_GET['name'];
        //    }
        //    if($len <= 6 && is_numeric($_GET['name']))
        //    {
        //        $map['a.id']    =  (string)$_GET['name'];
        //    }
        //    else if($len == 15)
        //    {
        //        $map['a.imsi'] = (string)$_GET['name'];
        //    }
        //    else if($len == 14)
        //    {
        //        $map['a.imei'] = (string)$_GET['name'];
        //    }
        //    else if($len == 6 && is_numeric($_GET['name']) == false)
        //    {
        //        $map['a.iccid_city']    = (string)$_GET['name'];
        //    }
        //    else if($len == 9 && is_numeric($_GET['name']) == false)
        //    {
        //        $map['a.city']    =(string)$_GET['name'];
        //    }
        //}
        $outletsinfo = array(); 
        $telecominfo = array();
        $keynames = array('a.telecom','a.coname');

        $is_t_o_v = $this->check_auth_info($selectwhere,$outletsinfo,$telecominfo,$keynames);        
        $is_telecom_visible = $is_t_o_v[0];
        $is_outlets_visible = $is_t_o_v[1];
        $isbad_visible = $is_t_o_v[2];
        $group_id   =  $is_t_o_v[3];
        if(isset($_GET['errorcode'])){
            $selectwhere['a.errorcode']    =   (string)$_GET['errorcode'];
            $selectwherestr['a.errorcode']    =   (string)$_GET['errorcode'];
        }
        if(isset($_GET['xystatus'])){
            $selectwhere['a.xystatus']    =   (string)$_GET['xystatus'];
            $selectwherestr['a.xystatus']    =   (string)$_GET['xystatus'];
        }
        if(isset($_GET['orderstatus'])){
            $selectwhere['a.orderstatus']    =   (string)$_GET['orderstatus'];
            $selectwherestr['a.orderstatus']    =   (string)$_GET['orderstatus'];
        }
        if(isset($_GET['cpgame'])){
            $selectwhere['a.cpgame']    =   (string)$_GET['cpgame'];
            $selectwherestr['a.cpgame']    =   (string)$_GET['cpgame'];
        }
        if(isset($_GET['coname'])){
            $selectwhere['a.co']    =   (string)$_GET['coname'];
            $selectwherestr['a.co']    =   (string)$_GET['coname'];
        }

        if(isset($_GET['egt'])){
            $selectwhere['a.egt']    =   (string)$_GET['egt'];
            $selectwherestr['a.egt']    =   (string)$_GET['egt'];
        }
        if(isset($_GET['userstatus'])){
            $selectwhere['initnewuser']    =   (string)$_GET['userstatus'];
            $selectwherestr['initnewuser']    =   (string)$_GET['userstatus'];
        }
        if(isset($_GET['mark'])){
            $selectwhere['b.mark']    =   (string)$_GET['mark'];
            $selectwherestr['b.mark']    =   (string)$_GET['mark'];
        }
        if(isset($_GET['os_model'])){
            $selectwhere['b.os_model']    =   (string)$_GET['os_model'];
            $selectwherestr['b.os_model']    =   (string)$_GET['os_model'];
        }
        //if(isset($_GET['city'])){
        //    $selectwhere['a.iccid_city']    = array('like', '%'.(string)$_GET['city'].'%');//  (string)$_GET['city']; 
            
        //}
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
        $data = array('isold'=>$isold,'xystatus'=>$selectwhere['a.xystatus'],'selectall'=>$seltcteach,'orderstatus'=>$selectwhere['a.orderstatus']
            ,'coname'=>$selectwhere['a.co'],'cpgame'=>$selectwhere['a.cpgame'],'iccid_cityt'=>$iccid_city,'egt'=>$selectwhere['a.egt'],'newuser'=>$selectwhere['initnewuser'],'mark'=>$selectwhere['b.mark'],'os_model'=>$selectwhere['b.os_model']);
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
            //$selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));    
            //$selectwhere['status']=0;
        }
         //获取数据表
        $dateformonth = \PublicFunction::getTimeForYH($time1).'_';
        $dateformonth= $dateformonth.'2'.'_'; 
        $tablename =C('DB_PREFIX').$dateformonth.'vnorder'; 
        if($_GET['userstatus']==2){        //判断newuser  为2 改成0
            $selectwhere['initnewuser']=0;
            $selectwherestr['initnewuser']=0;
        }
        //foreach ($selectwhere as $k=>$v){
        //    $ab .= ' and '.$k.' = \''.$v.'\'';
        //}
        if($group_id==null){
            foreach ($selectwhere as $k=>$v){
                $ab .= ' and '.$k.' = \''.$v.'\'';
            }
        }else{
            // $n=1;
            foreach ($selectwherestr as $k=>$v){
                $abstr .= ' and '.$k.' = \''.$v.'\'';
            }
            foreach ($selectwhere['a.coname'][1] as $k=>$v){
                $selectwherein .=',\''.$v.'\'';
            }
            $whereadasdasd=ltrim($selectwherein,',');
            $selectwherearr='and a.co in'.'('.$whereadasdasd.')';
           
        }
        $time = ' and a.time > '.$time1.' and a.time < '.$time2;
        foreach ($map as $k=>$v){           //输入框搜索
            $cd .= ' and '.$k.' like \'%'.$v.'%\'';
        }
       
        $mm = M();  
        $inf = "SELECT a.id, a.egt, a.co, a.cpgame, b.mark, b.os_model,b.initnewuser,a.extern,a.errorcode,a.city,a.iccid_city, a.imei,a.imsi, 
        a.xystatus, FROM_UNIXTIME(a.time) AS TIME FROM $tablename AS a 
        LEFT JOIN bks_usercount AS b ON a.bksid = b.bksid WHERE a.status = 0 $ab $cd $time $selectwherearr $abstr order by id desc";
        $info1 = $mm->query($inf);
         $bb=M()->_sql();
        $count =  count($info1);// 查询满足要求的总记录数
        $page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        $show = $page->show();// 分页显示输出
        $inf = "SELECT a.id, a.egt, a.co, a.cpgame, b.mark, b.os_model,b.initnewuser,a.extern,a.errorcode,a.city,a.iccid_city, a.imei,a.imsi, 
        a.xystatus, FROM_UNIXTIME(a.time) AS TIME FROM $tablename AS a
        LEFT JOIN bks_usercount AS b ON a.bksid = b.bksid WHERE a.status = 0  $ab $time $cd $selectwherearr $abstr  order by id desc limit $page->firstRow,$page->listRows";
        $info = $mm->query($inf);
     //$abb=M()->_sql();
       // if($map == null)
       // {
       //     $info   =   $this->lists($model2->where($selectwhere),$selectwhere);
       ////   print_r($model2->_sql());
       // }
       // else
       // {
       //     $map['_complex'] = $selectwhere;
       //     $info   =   $this->lists4($model2->where($map),$map);
       //     if($info == null)
       //     {
       //         $map2['extern']    =  array('like', '%'.(string)$_GET['name'].'%');
       //         $map2['_complex'] = $selectwhere;

       //         $info   =   $this->lists4($model2->where($map2),$map2);
       //     }
       // }
        $telecomtypeinfo = M('telecomtypelist')->select();
        $gamelist = M('gamelist')->field('id,name,appid')->select(); 
        $colist = M('colist')->field('id,name,coid')->select(); 
        $proplist = M('proplist')->field('id,name,propid')->select(); 
        $advstatuslist = \PublicData::$advstatus;   //广告协议状态
        $advstatuslist2 = \PublicData::$advstatus2; //广告订单状态
        $index -=1;
        foreach($info as $k=>$value)
        {
            $index ++;
    
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
                        $info[$k]['city'] = $value5['city'];
                    }
                }
            }
            $id = $value['xystatus'];
            $info[$k]['xystatus'] = $advstatuslist[$id]['name']; 
            $id = $value['orderstatus'];
            $info[$k]['orderstatus'] = $advstatuslist2[$id]['name'];
            $info[$index]['time'] = date("Y-m-d H:i:s", $value['time']);
        }
        $egt = \PublicData::$adegtlist;
        foreach ($info as $k => $v) {
            foreach ($egt as $value) {
                if ($info[$k]['egt'] ==$value['id']) {
                    $info[$k]['egt'] = $value['name'];
                }
            }
        }
        //是否新用户  1是 0否
        $userstatus = \PublicData::$userstatus;
        foreach ($info as $k => $v) {
            foreach ($userstatus as $value) {
                if($info[$k]['initnewuser']==0){
                        $info[$k]['initnewuser']=2;
                }
                if ($info[$k]['initnewuser'] ==$value['id']) {
                    $info[$k]['advnewuser'] = $value['name'];
                }
            }
        }
        //品牌
        $mob =  M('admobilelist')->field('mobilepp')
               ->group('mobilepp')
               ->order('id desc')->select();
        foreach($mob as $k=>$v){
            if($v['mobilepp'] == ''){
                unset($mob[$k]);
            }
        }
        //型号
        $mobliexh =  M('admobilelist')->field('mobliexh')
           ->group('mobliexh')
           ->order('id desc')->select();
        foreach($mobliexh as $k=>$v){
            if($v['mobliexh'] == ''){
                unset($mobliexh[$k]);
            }
        }
        //print_r($info);
        //print_r($data);
        $errorcodelist = \PublicData::$errorcodelist;
        $this->assign('mob',$mob);
        $this->assign('mobliexh',$mobliexh);
        $this->assign('egt',$egt);
        $this->assign('userstatus',$userstatus); 
        $this->assign('is_outlets_visible',$is_outlets_visible);
        $this->assign('is_telecom_visible',$is_telecom_visible);
        $this->assign('errorcodelist',$errorcodelist);
        $this->assign("gamelist",$gamelist); //游戏
        $this->assign("proplist",$proplist); //道具
        $this->assign("citylist",$citylist); //获取省份
        $this->assign('isbad_visible',$isbad_visible);
        $this->assign('data',$data);
        $this->assign("list",$info);
        $this->assign("type",$telecomtypeinfo);
        $this->assign("colist",$colist); //厂商
        $this->assign("advstatus",$advstatuslist); 
        $this->assign("advstatus2",$advstatuslist2); 
        $this->assign('time_start',$datatime1s);
        $this->assign('time_end',$datatime2s);
        $this->assign('page',$show);

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

    public function advhistory($orderid=0)
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
                $selectwhere['adstatus'] = 0;
                
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
            $datatimes1 = strtotime($_GET['time']);
            $this->dateformonth = \PublicFunction::getTimeForYH($datatimes1).'_';
            $this->dateformonth =  $this->dateformonth.'2'.'_';
            $tablename2 = $this->dateformonth.'vnhistory';
            $selectwhere = array('orderid'=>$orderid);
            $info = M($tablename2)->order('id DESC')->where($selectwhere)->select();
        }

        $telecomtypeinfo = M('telecomtypelist')->select();
        $statuslist = \PublicData::$logtype1;
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
    public function makecountexcel($time_start = '',$time_end = '',$xystatus = '',$orderstatus = '',$type = '',$initnewuser='',$telecom = '',$cpgame='',$prop='',$coname='',$iccid_cityt='',$egt='')
    {
        $time1 = $time_start.' 00:00:00'; 
        $time1 = strtotime($time1);
        
        $time2 = $time_end.' 23:59:59'; 
        $time2 = strtotime($time2);
        $time = $time1;
       // $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
       // $this->dateformonth =  $this->dateformonth.'2'.'_';

       // $tablename = $this->dateformonth.'vnorder';
        //$model2 = M($tablename);     
        //$outletsinfo = array();
       // $this->auto_user($selectwhere,$outletsinfo,'outlets');
        if($xystatus != 'Array' and $xystatus != '')
        {
            $selectwhere['a.xystatus'] = $xystatus;
        }
        if($initnewuser != 'Array' and $initnewuser != '')
        {
            $selectwhere['b.initnewuser'] = $initnewuser;
        }
        if($orderstatus != 'Array' and $orderstatus != '')
        {
            $selectwhere['a.orderstatus'] = $orderstatus;
        }
        if($type != 'Array' and $type != '')
        {
            $selectwhere['a.type'] = $type;
        }
        if($telecom != 'Array' and $telecom != '')
        {
            $selectwhere['a.telecom'] = $telecom;
        }
         if($coname != 'Array' and $coname != '')
        {
            $selectwhere['a.co'] = $coname;
        }
         if($cpgame != 'Array' and $cpgame != '')
        {
            $selectwhere['a.cpgame'] = $cpgame;
        }
          if($prop != 'Array' and $prop != '')
        {
            $selectwhere['a.prop'] = $prop;
        }
            if($iccid_cityt != 'Array' and $iccid_cityt != '')
        {
            $selectwhere['iccid_city'] = $iccid_cityt;
        }
            if($egt != 'Array' and $egt != '')
        {
            $selectwhere['a.egt'] = $egt;
        }


        //  if($outlets != 'array' and $outlets != '')
        //{
        //$selectwhere['outlets'] = $outlets;
        //}
       // $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));
        //查询vnorder所有数据
       // $selectwhere['status'] = 0;
        //$info = $model2->where($selectwhere)->select();
            $dateformonth = \PublicFunction::getTimeForYH($time1).'_';
            $dateformonth= $dateformonth.'2'.'_'; 
            $tablename =C('DB_PREFIX').$dateformonth.'vnorder'; 
            if($_GET['userstatus']==2){        //判断newuser  为2 改成0
                $selectwhere['initnewuser']=0;
                $selectwherestr['initnewuser']=0;
            }
            //foreach ($selectwhere as $k=>$v){
            //    $ab .= ' and '.$k.' = \''.$v.'\'';
            //}
       
                foreach ($selectwhere as $k=>$v){
                    $ab .= ' and '.$k.' = \''.$v.'\'';
                // $n=1;
                }
                //foreach ($selectwherestr as $k=>$v){
                //    $abstr .= ' and '.$k.' = \''.$v.'\'';
                //}
                //foreach ($selectwhere['a.coname'][1] as $k=>$v){
                //    $selectwherein .=',\''.$v.'\'';
                //}
                //$whereadasdasd=ltrim($selectwherein,',');
                //$selectwherearr='and a.co in'.'('.$whereadasdasd.')';                
            
            $time = ' and a.time > '.$time1.' and a.time < '.$time2;
            foreach ($map as $k=>$v){           //输入框搜索
                $cd .= ' and '.$k.' like \'%'.$v.'%\'';
            }           
            $mm = M();  
            $inf = "SELECT a.id, a.egt, a.co, a.cpgame, b.mark, b.os_model,b.initnewuser,a.extern,a.errorcode,a.city,a.iccid_city, a.imei,a.imsi, 
        a.xystatus, FROM_UNIXTIME(a.time) AS TIME FROM $tablename AS a 
        LEFT JOIN bks_usercount AS b ON a.bksid = b.bksid WHERE a.status = 0 $ab $cd $time  order by id desc";
            $info = $mm->query($inf);
            
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
        //是否新用户  1是 0否
        $userstatus = \PublicData::$userstatus;
        $statuslist = \PublicData::$vnstatus;
        $egtlist = \PublicData::$egtlist;
        $data[0] = array('id'=>'编号','egt'=>'运营商','coname'=>'厂商','cpgame'=>'游戏名','mark'=>'手机品牌','os_model'=>'手机型号','extern'=>'透传参数','city'=>'城市'
,'iccid_city'=>'省份','iccid_city'=>'iccid_省份','imei'=>'imei','imsi'=>'imsi','initnewuser'=>'是否新用户', 'xystatus'=>'协议状态','time'=>'时间');
        foreach($info as $value)
        {
            $index++;
            $index2++;
            $value['time'] = date("Y-m-d H:i:s", $value['time']);

            $data[$index2] = array('id'=>$value['id'],'egt'=>$value['egt'],'coname'=>$value['co'],'cpgame'=>$value['cpgame'],'mark'=>$value['mark'],'os_model'=>$value['os_model'],'extern'=>$value['extern'],'city'=>$value['city']
    ,'iccid_city'=>$value['iccid_city'],'imei'=>$value['imei'],'imsi'=>$value['imsi'],'advnewuser'=>$value['initnewuser'], 'xystatus'=>$value['xystatus'],'time'=>$value['time']);
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
            foreach($userstatus as $value6)
            {
                if($value['initnewuser']==0){
                    $value['initnewuser']=2;    
                }
                if($value['initnewuser'] == $value6['id'])
                {
                    $data[$index2]['advnewuser'] = $value6['name'];
                }
            }
            $id = $value['xystatus'];
            $data[$index2]['xystatus'] = $statuslist[$id]['name']; 
            $id = $value['orderstatus'];
            $data[$index2]['orderstatus'] = $statuslist[$id]['name'];
        }
        phpExcel($data,$time_start.'至'.$time_end.'破解订单表');
    }
    public function adlist(){
        //页数初始化
        if($_GET['p']==''){
        $_GET['p']=0;
        }
        $time = time();
        $time1 = '';
        $time2 = '';
        $selectwhere = array();
        //搜索框
        if(isset($_GET['name'])){
            $len = strlen($_GET['name']);
            if($len == 12)
            {
                $map['co']    =  array('like', '%'.(string)$_GET['name'].'%');
            }
            else if($len == 15)
            {
                $map['imsi'] = (string)$_GET['name'];
            }
            else if($len == 6 && is_numeric($_GET['name']) == false)
            {
                $map['iccid_city']    =  array('like', '%'.(string)$_GET['name'].'%');
            }
            else
            {
                $map['id']    =  array('like', '%'.(string)$_GET['name'].'%');
            }
        }
        $outletsinfo = array(); 
        $telecominfo = array();
        $keynames = array('telecom','coname');

        $is_t_o_v = $this->check_auth_info($selectwhere,$outletsinfo,$telecominfo,$keynames);        
        $is_telecom_visible = $is_t_o_v[0];
        $is_outlets_visible = $is_t_o_v[1];
        $isbad_visible = $is_t_o_v[2];
        //$is_telecom_visible = 2;
        //$is_outlets_visible = 2;
        if(isset($_GET['cpgame'])){
            $selectwhere['cpgame']    =   (string)$_GET['cpgame'];
        }
        if(isset($_GET['coname'])){
            $selectwhere['co']    =   (string)$_GET['coname'];
        }
        if(isset($_GET['adtype'])){
            $selectwhere['adtype']    =   (string)$_GET['adtype'];
        }

        if(isset($_GET['city'])){
            $selectwhere['iccid_city']    = array('like', '%'.(string)$_GET['city'].'%');//  (string)$_GET['city']; 
            
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
        if(isset($_GET['name'])){
            $len = strlen($_GET['name']);
            if($len == 6)
            {
                $selectwhere['iccid_city']    =  array('like', '%'.(string)$_GET['name'].'%');
            }
            else
            {
                $selectwhere['id']    =  array('like', '%'.(string)$_GET['name'].'%');
            }
        }
        $arr='%';
        $iccid_citywhere=$selectwhere['iccid_city']['1'];
        $iccid_city=str_replace($arr,"",$iccid_citywhere);     
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
            $tablename = $this->dateformonth.'adorder';
   
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
            //$model2 = m($tablename);
            if((int)$time1 > (int)$time2)
            {
                $this->error('操作失误');
            }
            $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));    
        }
        //if($map == null)
        //{
           // $info   =   $this->lists($model2->where($selectwhere),$selectwhere);
        //    //   print_r($model2->_sql());
        //}
        //else
        //{
        //    $map['_complex'] = $selectwhere;
        //    $info   =   $this->lists4($model2->where($map),$map);
        //    if($info == null)
        //    {
        //        $map2['extern']    =  array('like', '%'.(string)$_get['name'].'%');
        //        $map2['_complex'] = $selectwhere;

        //        $info   =   $this->lists4($model2->where($map2),$map2);
        //    }
        //}
        if(isset($_GET['isgame'])){
            $group1['isgame'] = $_GET['isgame'];
           $group .= 'cpgame,'; 
        }
        if(isset($_GET['isco'])){
            $group1['isco'] = $_GET['isco'];
            $group .= 'co,'; 
        }
        if(isset($_GET['istype'])){
            $group1['istype'] = $_GET['istype'];
            $group .= 'adtype,'; 
        }

        if(isset($_GET['iscity'])){
            $group1['iscity'] = $_GET['iscity'];
            $group .= 'iccid_city,'; 
            
        }
        if(isset($_GET['ishour'])){
            $group1['ishour'] = $_GET['ishour'];
            $group .= 'hour,'; 
            
        }
        if(isset($_GET['isday'])){
            $group1['isday'] = $_GET['isday'];
            $group .= 'day,'; 
            
        }
        $group = rtrim($group,',');
        $data = array('coname'=>$selectwhere['co'],'cpgame'=>$selectwhere['cpgame'],'iccid_cityt'=>$iccid_city,'isgame'=>$group1['isgame'],'isco'=>$group1['isco'],'istype'=>$group1['istype'],'iscity'=>$group1['iscity'],'ishour'=>$group1['ishour'],'isday'=>$group1['isday']);
        
        $telecomtypeinfo = M('telecomtypelist')->select();
        $gamelist = M('gamelist')->field('id,name,appid')->select(); 
        $colist = M('colist')->field('id,name,coid')->select(); 
        $proplist = M('proplist')->field('id,name,propid')->select(); 
        $advstatuslist = \PublicData::$advstatus;   //广告协议状态
        $advstatuslist2 = \PublicData::$advstatus2; //广告订单状态
        $index -=1;
        $advnum = \PublicData::$advnum;
        //$sql = "SELECT max(id) as id, t.co, t.cpgame, t.adtype, case t.adtype when 1 then '开屏广告' when 2 then '返回广告' when 3 then '插屏广告' 
        //          when 4 then '安装下载' when 5 then '通知栏下载' when 6 then '通知栏推荐更新' when 7 then '通知栏推荐使用' end as adtypename, t.iccid_city, sum(t.adsnum) as 
        //          adsnum, sum(t.adnum) as adnum, sum(t.adsnum) - sum(t.adnum) AS adfnum, FROM_UNIXTIME(t.time) as adtime  FROM bks_2016_11_2_adorder t group by t.co, t.cpgame, t.adtype order by t.id";   
       if($group!==''){
           $info =  M($tablename)
               ->field(array('day(FROM_UNIXTIME(time))'=>'day','hour(FROM_UNIXTIME(time))'=>'hour','FROM_UNIXTIME(time)'=>'adtime','sum(adsnum)'=>'adsnum','sum(adnum)'=>'adnum','sum(adsnum) - sum(adnum)'=>'adfnum','max(id)'=>'id','co'=>'co','cpgame'=>'cpgame','adtype'=>'adtypename','iccid_city'=>'iccid_city'))
               ->where($selectwhere)->group('adtypename,'.$group)
               ->order('id desc')->page($_GET['p'].',15')
               ->select();
          // print_r(  M($tablename)->_sql());
           //总行数
           $info1 = M($tablename)->field(array('day(FROM_UNIXTIME(time))'=>'day','hour(FROM_UNIXTIME(time))'=>'hour','FROM_UNIXTIME(time)'=>'adtime','sum(adsnum)'=>'adsnum','sum(adnum)'=>'adnum','sum(adsnum) - sum(adnum)'=>'adfnum','max(id)'=>'id','co'=>'co','cpgame'=>'cpgame','adtype'=>'adtypename','iccid_city'=>'iccid_city'))->where($selectwhere)->group('adtypename,'.$group)->select();
       }else{
           $info =  M($tablename)->field(array('day(FROM_UNIXTIME(time))'=>'day','hour(FROM_UNIXTIME(time))'=>'hour','FROM_UNIXTIME(time)'=>'adtime','sum(adsnum)'=>'adsnum','sum(adnum)'=>'adnum','sum(adsnum) - sum(adnum)'=>'adfnum','max(id)'=>'id','co'=>'co','cpgame'=>'cpgame','adtype'=>'adtypename','iccid_city'=>'iccid_city'))->where($selectwhere)->group('adtypename')->order('id desc')->page($_GET['p'].',15')->select();
           //print_r( $info);
           //总行数
           $info1 = M($tablename)->field(array('day(FROM_UNIXTIME(time))'=>'day','hour(FROM_UNIXTIME(time))'=>'hour','FROM_UNIXTIME(time)'=>'adtime','sum(adsnum)'=>'adsnum','sum(adnum)'=>'adnum','sum(adsnum) - sum(adnum)'=>'adfnum','max(id)'=>'id','co'=>'co','cpgame'=>'cpgame','adtype'=>'adtypename','iccid_city'=>'iccid_city'))->where($selectwhere)->group('adtypename')->select();
           // print_r( M($tablename)->_sql());
       }
        $count =  count($info1);// 查询满足要求的总记录数
        $Page = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();// 分页显示输出
        //$info =  M($tablename)->field(array('FROM_UNIXTIME(time)'=>'adtime','sum(adsnum)'=>'adsnum','sum(adnum)'=>'adnum','sum(adsnum) - sum(adnum)'=>'adfnum','max(id)'=>'id','co'=>'co','cpgame'=>'cpgame','adtype'=>'adtypename','iccid_city'=>'iccid_city'))->where($selectwhere)->group('co,cpgame,adtype')->order('id desc')->select();
        foreach($info as $k=>$value)
        {
            $index ++;
            foreach($gamelist as $value3)
            {
                if($value['cpgame'] == $value3['appid'])
                {
                    $info[$k]['cpgame'] = $value3['name'];
                }
            }
            foreach($advnum as $v){
                if($value['adtypename'] == $v['id']){
                    $info[$k]['adtypename'] = $v['name'];
                }
            
            
            }
            foreach($colist as $value4)
            {
                if($value['co'] == $value4['coid'])
                {
                    $info[$k]['co'] = $value4['name'];
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
                        $info[$k]['city'] = $value5['city'];
                    }
                }
            }
            $id = $value['xystatus'];
            $info[$k]['xystatus'] = $advstatuslist[$id]['name']; 
            $id = $value['orderstatus'];
            $info[$k]['orderstatus'] = $advstatuslist2[$id]['name'];
            $info[$index]['time'] = date("y-m-d h:i:s", $value['time']);
        }
        $egt = \publicdata::$egtlist;
        foreach ($info as $k => $v) {
            foreach ($egt as $value) {
                if ($info[$k]['egt'] ==$value['id']) {
                    $info[$k]['egt'] = $value['name'];
                }
            }
        }
        //print_r($info);
        //print_r($data);
       
          
         // print_r($info);
        $errorcodelist = \PublicData::$errorcodelist;
        $this->assign('advnum',$advnum);
        $this->assign('egt',$egt);
        $this->assign('page',$show);
        $this->assign('is_outlets_visible',$is_outlets_visible);
        $this->assign('is_telecom_visible',$is_telecom_visible);
        $this->assign('errorcodelist',$errorcodelist);
        $this->assign("gamelist",$gamelist); //游戏
        $this->assign("proplist",$proplist); //道具
        $this->assign("citylist",$citylist); //获取省份
        $this->assign('isbad_visible',$isbad_visible);
        $this->assign('data',$data);
        $this->assign("list",$info);
        $this->assign("type",$telecomtypeinfo);
        $this->assign("colist",$colist); //厂商
        $this->assign("advstatus",$advstatuslist); 
        $this->assign("advstatus2",$advstatuslist2); 
        $this->assign('time_start',$datatime1s);
        $this->assign('time_end',$datatime2s);

        $this->display();        
    
    
    }
    
    public function adlistc(){
        $d = I('get.');
        //页数初始化
      
        $time = time();
        $time1 = '';
        $time2 = '';
        $selectwhere = array();
        //搜索框
        if(isset($_GET['name'])){
            $len = strlen($_GET['name']);
            if($len == 9)
            {
                $selectwhere['city']    =  array('like', '%'.(string)$_GET['name'].'%');
            }
            else if($len == 6)
            {
                $selectwhere['moudlename']    =  array('like', '%'.(string)$_GET['name'].'%');
            }
            else
            {
                $selectwhere['id']    =  array('like', '%'.(string)$_GET['name'].'%');
            }
        }
        $outletsinfo = array(); 
        $telecominfo = array();
        $keynames = array('telecom','coname');

        $is_t_o_v = $this->check_auth_info($selectwhere,$outletsinfo,$telecominfo,$keynames);        
        $is_telecom_visible = $is_t_o_v[0];
        $is_outlets_visible = $is_t_o_v[1];
        $isbad_visible = $is_t_o_v[2];
        if(isset($_GET['cpgame'])){
            $selectwhere['appid']    =   (string)$_GET['cpgame'];
        }
        if(isset($_GET['coname'])){
            $selectwhere['coid']    =   (string)$_GET['coname'];
        }
        if(isset($_GET['adtype'])){
            $selectwhere['adtype']    =   (string)$_GET['adtype'];
        }

        if(isset($_GET['city'])){
            $selectwhere['iccid_city']    = array('like', '%'.(string)$_GET['city'].'%');//  (string)$_GET['city']; 
            
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
        if($time1!='' && $time2!='')
        {
            if(empty($isold)) $isold = 0;
            $this->dateformonth = \PublicFunction::getTimeForYH($time1).'_';
            if($isold != 1)
            {
                $this->dateformonth =  $this->dateformonth.'2'.'_';
            }
            //$this->dateformonth =  $this->dateformonth.'2'.'_';
            $citylist = M('area')->select();        //获取省份 
            $tablename = $this->dateformonth.'adorder';
            
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
            if((int)$time1 > (int)$time2)
            {
                $this->error('操作失误');
            }
            $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));    
        }
        if(isset($_GET['isgame'])){
            $group1['isgame'] = $_GET['isgame'];
            $group .= 'appid,'; 
        }
        if(isset($_GET['isco'])){
            $group1['isco'] = $_GET['isco'];
            $group .= 'co,'; 
        }
        if(isset($_GET['istype'])){
            $group1['istype'] = $_GET['istype'];
            $group .= 'adtype,'; 
        }

        if(isset($_GET['iscity'])){
            $group1['iscity'] = $_GET['iscity'];
            $group .= 'city,'; 
            
        }
        if(isset($_GET['ishour'])){
            $group1['ishour'] = $_GET['ishour'];
            $group .= 'hour,'; 
            
        }
        if(isset($_GET['isday'])){
            $group1['isday'] = $_GET['isday'];
            $group .= 'day,'; 
            
        }
        $group = rtrim($group,',');
        $data = array('coname'=>$selectwhere['coid'],'cpgame'=>$selectwhere['appid'],'adtype'=>$selectwhere['adtype'], 'iccid_cityt'=>$iccid_city,'isgame'=>$group1['isgame'],'isco'=>$group1['isco'],'istype'=>$group1['istype'],'iscity'=>$group1['iscity'],'ishour'=>$group1['ishour'],'isday'=>$group1['isday']);
        
        $telecomtypeinfo = M('telecomtypelist')->select();
        $gamelist = M('gamelist')->field('id,name,appid')->select(); 
        $colist = M('colist')->field('id,name,coid')->select(); 
        $proplist = M('proplist')->field('id,name,propid')->select(); 
        $advstatuslist = \PublicData::$advstatus;   //广告协议状态
        $advstatuslist2 = \PublicData::$advstatus2; //广告订单状态
        $index -=1;
        $advnum = \PublicData::$advstatic;
        //$sql = "SELECT max(id) as id, t.co, t.cpgame, t.adtype, case t.adtype when 1 then '开屏广告' when 2 then '返回广告' when 3 then '插屏广告' 
        //          when 4 then '安装下载' when 5 then '通知栏下载' when 6 then '通知栏推荐更新' when 7 then '通知栏推荐使用' end as adtypename, t.iccid_city, sum(t.adsnum) as 
        //          adsnum, sum(t.adnum) as adnum, sum(t.adsnum) - sum(t.adnum) AS adfnum, FROM_UNIXTIME(t.time) as adtime  FROM bks_2016_11_2_adorder t group by t.co, t.cpgame, t.adtype order by t.id";   
        if($_GET['p']==''){
            $_GET['p']=0;
        }
        if($group!==''){
            $info =  M('advclick')
                ->field(array('day(FROM_UNIXTIME(time))'=>'day','hour(FROM_UNIXTIME(time))'=>'hour','FROM_UNIXTIME(time)'=>'adtime','sum(click)'=>'adclick','max(id)'=>'id','coid'=>'co','appid'=>'appid','adtype'=>'adtypename','city'=>'iccid_city','moudlename'=>'picname','picid'=>'picid'))
                ->where($selectwhere)->group('picid,adtype,'.$group)
                ->order('id desc')->page($_GET['p'].',15')
                ->select();
            //总行数
            $info1 =M('advclick')
                ->field(array('day(FROM_UNIXTIME(time))'=>'day','hour(FROM_UNIXTIME(time))'=>'hour','FROM_UNIXTIME(time)'=>'adtime','sum(click)'=>'adclick','max(id)'=>'id','coid'=>'co','appid'=>'appid','adtype'=>'adtypename','city'=>'iccid_city','moudlename'=>'picname','picid'=>'picid'))
                ->where($selectwhere)->group('picid,adtype,'.$group)->select();
        }else{
            $group = 'picid,adtype';
            $info =   M('advclick')
                ->field(array('day(FROM_UNIXTIME(time))'=>'day','hour(FROM_UNIXTIME(time))'=>'hour','FROM_UNIXTIME(time)'=>'adtime','sum(click)'=>'adclick','max(id)'=>'id','coid'=>'co','appid'=>'appid','adtype'=>'adtypename','city'=>'iccid_city','moudlename'=>'picname','picid'=>'picid'))
                ->where($selectwhere)->group($group)
                ->order('id desc')->page($_GET['p'].',15')
                ->select();
            $a =  M('advclick')->_sql();
            //总行数
            $info1 = M('advclick')
                ->field(array('day(FROM_UNIXTIME(time))'=>'day','hour(FROM_UNIXTIME(time))'=>'hour','FROM_UNIXTIME(time)'=>'adtime','sum(click)'=>'adclick','max(id)'=>'id','coid'=>'co','appid'=>'appid','adtype'=>'adtypename','city'=>'iccid_city','moudlename'=>'picname','picid'=>'picid'))
                ->where($selectwhere)->group($group)->select();
        }
        $count =  count($info1);// 查询满足要求的总记录数
        $Page = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();// 分页显示输出
        //$info =  M($tablename)->field(array('FROM_UNIXTIME(time)'=>'adtime','sum(adsnum)'=>'adsnum','sum(adnum)'=>'adnum','sum(adsnum) - sum(adnum)'=>'adfnum','max(id)'=>'id','co'=>'co','cpgame'=>'cpgame','adtype'=>'adtypename','iccid_city'=>'iccid_city'))->where($selectwhere)->group('co,cpgame,adtype')->order('id desc')->select();
      // print_r($info);
        foreach($info as $k=>$value)
        {
            $index ++;
            foreach($gamelist as $value3)
            {
                if($value['appid'] == $value3['appid'])
                {
                    $info[$k]['appid'] = $value3['name'];
                }
            }
            foreach($advnum as $v){
                if($value['adtypename'] == $v['id']){
                    $info[$k]['adtypename'] = $v['name'];
                }
                
                
            }
            foreach($colist as $value4)
            {
                if($value['co'] == $value4['coid'])
                {
                    $info[$k]['co'] = $value4['name'];
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
                        $info[$k]['city'] = $value5['city'];
                    }
                }
            }
            $id = $value['xystatus'];
            $info[$k]['xystatus'] = $advstatuslist[$id]['name']; 
            $id = $value['orderstatus'];
            $info[$k]['orderstatus'] = $advstatuslist2[$id]['name'];
            $info[$index]['time'] = date("y-m-d h:i:s", $value['time']);
        }
        $egt = \publicdata::$egtlist;
        foreach ($info as $k => $v) {
            foreach ($egt as $value) {
                if ($info[$k]['egt'] ==$value['id']) {
                    $info[$k]['egt'] = $value['name'];
                }
            }
        }
        $errorcodelist = \PublicData::$errorcodelist;
        $this->assign('advnum',$advnum);
        $this->assign('egt',$egt);
        $this->assign('page',$show);
        $this->assign('is_outlets_visible',$is_outlets_visible);
        $this->assign('is_telecom_visible',$is_telecom_visible);
        $this->assign('errorcodelist',$errorcodelist);
        $this->assign("gamelist",$gamelist); //游戏
        $this->assign("proplist",$proplist); //道具
        $this->assign("citylist",$citylist); //获取省份
        $this->assign('isbad_visible',$isbad_visible);
        $this->assign('data',$data);
        $this->assign("list",$info);
        $this->assign("type",$telecomtypeinfo);
        $this->assign("colist",$colist); //厂商
        $this->assign("advstatus",$advstatuslist); 
        $this->assign("advstatus2",$advstatuslist2); 
        $this->assign('time_start',$datatime1s);
        $this->assign('time_end',$datatime2s);
        $this->display();        
    }
    
    public function aduse(){
        if($_GET['p']==''){
            $_GET['p']=0;
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
        if($time1!='' && $time2!='')
        {
            if(empty($isold)) $isold = 0;
            $this->dateformonth = \PublicFunction::getTimeForYH($time1).'_';
            if($isold != 1)
            {
                $this->dateformonth =  $this->dateformonth.'2'.'_';
            }
            //$this->dateformonth =  $this->dateformonth.'2'.'_';
            $citylist = M('area')->select();        //获取省份 
            $tablename = $this->dateformonth.'adorder';
            $datatime1s = (string)date("Y-m-d",$time1); 
            $datatime2s =(string)date("Y-m-d",$time2);
            $datatime1 = explode('-',$datatime1s);
            $datatime2 = explode('-',$datatime2s);
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
            if((int)$time1 > (int)$time2)
            {
                $this->error('操作失误');
            }
            $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));  
            $ltime['time-start'] = date('Y-m-d',$time1); 
            $ltime['time-start2'] = date('Y-m-d',$time2); 
        }
        //搜索框
        if(isset($_GET['name'])){
            $len = strlen($_GET['name']);
            if($len == 4)
            {
                $selectwhere['mobilepp']    =  array('like', '%'.(string)$_GET['name'].'%');
            }else if($len == 2)
            {
                $selectwhere['make']    =  array('like', '%'.(string)$_GET['name'].'%');
            }
            else if($len == 6)
            {
                $selectwhere['mobliexh']    =  array('like', '%'.(string)$_GET['name'].'%');
            }  else if($len == 8)
            {
                $selectwhere['mobliesys']    =  array('like', '%'.(string)$_GET['name'].'%');
            }
            else if($len == 9)
            {
                $selectwhere['city']    =  array('like', '%'.(string)$_GET['name'].'%');
            }
            else
            {
                $selectwhere['id']    =  array('like', '%'.(string)$_GET['name'].'%');
            }
        }
        if($_GET['mob']){
                $selectwhere['mobilepp'] = I('get.mob');
            }
        if($_GET['mobliexh']){
            $selectwhere['mobliexh'] = I('get.mobliexh');
        }
        if($_GET['mobliesys']){
            $selectwhere['mobliesys'] = I('get.mobliesys');
        }
        if($_GET['city']){
            $selectwhere['city'] = I('get.city');
        }
        if($_GET['live']){
            $selectwhere['make'] = I('get.live');
        }
        $data = array('mobilepp'=> $selectwhere['mobilepp'],'mobliexh'=> $selectwhere['mobliexh'],'mobliesys'=> $selectwhere['mobliesys'],'city'=> $selectwhere['city'],'make'=> $selectwhere['make']);
        
        //品牌
        $mob =  M('admobilelist')->field('mobilepp')
               ->group('mobilepp')
               ->order('id desc')->select();
        foreach($mob as $k=>$v){
            if($v['mobilepp'] == ''){
            unset($mob[$k]);
            }
        }
        //型号
        $mobliexh =  M('admobilelist')->field('mobliexh')
           ->group('mobliexh')
           ->order('id desc')->select();
        foreach($mobliexh as $k=>$v){
            if($v['mobliexh'] == ''){
                unset($mobliexh[$k]);
            }
        }
        //系统
        $mobliesys =  M('admobilelist')->field('mobliesys')
         ->group('mobliesys')
         ->order('id desc')->select();
        foreach($mobliesys as $k=>$v){
            if($v['mobliesys'] == ''){
                unset($mobliesys[$k]);
            }
        }
        //城市
        $city =  M('admobilelist')->field('city')
        ->group('city')
        ->order('id desc')->select();
        foreach($city as $k=>$v){
            if($v['city'] == ''){
                unset($city[$k]);
            }
        }
        //联网
        $live =  M('admobilelist')->field('make')
        ->group('make')
        ->order('id desc')->select();
        foreach($live as $k=>$v){
            if($v['make'] == ''){
                unset($live[$k]);
            }
        }
        
        //全部
        $group = 'mobilepp,mobliexh,mobliesys,city';
        $info =  M('admobilelist')->field('max(id) as id, max(mobilepp) as mobilepp ,max(make) as make ,max(mobliexh) as mobliexh ,max(mobliesys)  as mobliesys ,max(city) as city,sum(usernum) as user')->where($selectwhere)
               ->group($group)->page($_GET['p'].',10')
               ->order('id desc')->select();
        $info1 = M('admobilelist')->field('max(mobilepp),max(mobliexh),max(mobliesys),max(city),sum(usernum) as user')->where($selectwhere)
               ->group($group)->order('id desc')->select();
      
        $count =  count($info1);// 查询满足要求的总记录数
        $Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();// 分页显示输出
        $this->assign('ltime',$ltime);
        $this->assign('page',$show);
        $this->assign('data',$data);
        $this->assign('mob',$mob);
        $this->assign('mobliexh',$mobliexh);
        $this->assign('mobliesys',$mobliesys);
        $this->assign('live',$live);
        $this->assign('city',$city);
        $this->assign('list',$info);
        $this->display();
    
    }
    
    
}
