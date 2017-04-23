<?php
namespace Admin\Controller;
include_once"PublicData.php";
include_once"PublicFunction.php";

/**
 * Order short summary.
 *
 * Order description.
 *
 * @version 1.0
 * @author admin
 */
//查询控制器代码
class OrderController extends AdminController
{
    
    //游戏明细
    public function count()
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
        //$data = array('iscpgame','isco','isprop','ishour','isday','iscode','paycode','telecomname','cpgame','isday','egt','iscity');
        //$selectwhere_map = \PublicFunction::getClickSeachData($data,'utf8name');
        $iscpgame = $_GET['iscpgame'];
        $isco = $_GET['isco'];
        $isprop = $_GET['isprop'];
        $iscity = $_GET['iscity'];
        $ishour = $_GET['ishour'];
        $isday = $_GET['isday'];
        $isegt =  $_GET['isegt'];
        $istel =  $_GET['istel'];
        if(empty($isegt)) $isegt = 0;
        if(empty($ishour)) $ishour = 0;
        if(empty($iscity)) $iscity = 0;
        if(empty($iscpgame)) $iscpgame = 0;
        if(empty($isday)) $isday = 0;
        if(empty($isprop)) $isprop = 0;
        if(empty($isco)) $isco = 0;     
        
        $selectwhere = $selectwhere_map['0'];       //条件为勾选框的值 1        如： $selectwhere_map['iscpgame']=1
        $map = $selectwhere_map['1'];               //输入框的值
      //  $selectwhere['co']=$_GET['coname'];
        $telecominfo =array();      //通道
        $outletsinfo = array();     //厂商
        $keynames = array('telecomname','coid','appid');
        $is_t_o_v = $this->check_auth_info($selectwhere,$outletsinfo,$telecominfo,$keynames);        
        $is_telecom_visible = $is_t_o_v[0];
        $is_outlets_visible = $is_t_o_v[1];
        $isbad_visible = $is_t_o_v[2];
        $isgroup = $is_t_o_v[3];
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
            if((int)$time1 > (int)$time2)
            {
                $this->error('操作失误');
            }
            $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));            
        }
        $group = '';
        $field = '';
        $order = '';

        if($iscpgame == 1 || $isprop == 1 || $isco == 1 || $ishour == 1 || $istel == 1 ||$iscity == 1 || $_GET['cpgame'] != null  || $_GET['isday'] != null || $_GET['isegt'] != null)
        {
            $field = 'id';
            if($iscpgame == 1)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'t.cpgame';
                $order['t.cpgame'] = 't.cpgame';
                $field = $field.',cpgame';
            }
            if($iscpgame != 1 && $_GET['cpgame']!= null)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'t.cpgame';
                $order['t.cpgame'] = 't.cpgame';
                $field = $field.',cpgame';
            }
            if($isprop == 1)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'t.prop';
                $order['t.prop'] = 't.prop';
                $field = $field.',prop';
            }
            if($isprop != 1 && $_GET['prop']!= null)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'t.prop';
                $order['t.prop'] = 't.prop';
                $field = $field.',prop';
            }
            //if($isco == 1)
            //{
            //    if($group != '')
            //        $group = $group.',';
            //    $group = $group.'t.co,t.telecomname';
            //    $order = 'msggold desc';
            //    $field = $field.',co';
            //}
            if($isco == 1)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'t.co';
                $order['t.co'] = 't.co';
                $field = $field.',co';
            }
            if($iscity == 1)
            {
                if($group != '')
                $group = $group.',';
                $group = $group.'t.city';
                $order['t.city'] = 't.city';
                $field = $field.',city';
            }
            if($ishour == 1)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'t.hour';
                $order['t.hour'] = 't.hour';
                $field = $field.',hour';
            }
            if($isday == 1)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'t.day';
                $order['t.day'] = 't.day';
                $field = $field.',day';
            }
            if($isegt == 1)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'t.egt';
                $order['t.egt'] = 't.egt';
                $field = $field.',egt';
            }
            if($istel == 1)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'t.telecomname';
                $order['t.telecomname'] = 't.telecomname';
                $field = $field.',telecomname';
            }
            $field = $field.',day,sum(payresult) payresult,sum(xysuccess) xysuccess,sum(paysuccess)paysuccess,sum(msggold) msggold, sum(badvalue) badvalue,sum(badgold) badgold ';
        }
        else
        {
            if($is_outlets_visible == 1)
            {
                $iscpgame = 1;
                $group = $group.'t.cpgame';       //游戏
                $order['t.cpgame'] = 't.cpgame';
                $field = $field.'id,cpgame,time';
                $field = $field.',day,sum(payresult) payresult,sum(xysuccess) xysuccess,sum(paysuccess)paysuccess,sum(msggold) msggold, sum(badvalue) badvalue,sum(badgold) badgold ';
            }
            else
            {
                $istelecom = 1;
                $group = $group.' t.co,t.telecomname';       //厂商
                $order['t.cpgame'] = 't.cpgame';
                $field = $field.'id,co,time';
                $field = $field.',day,sum(payresult) payresult,sum(xysuccess) xysuccess,sum(paysuccess)paysuccess,sum(msggold) msggold, sum(badvalue) badvalue,sum(badgold) badgold ';
            }
        }

     //   if($map == null)
     //   {

     //       G('begin3');

     //       $info   =   $this->lists2($model2->where($selectwhere),$selectwhere,$order,$field,$group);  //查询数据表ordercount  
     //    //   $payselectwhere=$selectwhere['time'];
     //      // print_r( $sql = $model2->_sql());
     //       $sum_order = $model2->where($selectwhere)->sum('payresult');                // // 算出计费请求之和   
              
     //       $sum_success_order = $model2->where($selectwhere)->sum('paysuccess');        //算出计费成功之和
     //       if($isbad_visible!=2) //管理员为1
     //       {
     //           $sum_fee = $model2->where($selectwhere)->sum('msggold');            //算出信息费(成交金额)之和
     //         //  $sql = $model2->_sql();
     //       }
     //       else
     //       {
     //           $sum_fee = $model2->where($selectwhere)->sum('msggold');         //算出信息费(成交金额)之和

     //           $sum_fee1 = $model2->where($selectwhere)->sum('badgold');       //     bad元之和
     //           $sum_fee = $sum_fee - $sum_fee1;                                   // 实际成交金额******
     //           $bad_value = $model2->where($selectwhere)->sum('badvalue');        //bad条 之和
     //           $sum_success_order = $sum_success_order - $bad_value;         //实际成功数 *****  
     //       }

     //   }
     //   else
     //  {        
     //      $group = 'cpgame';
     //      $map['_complex'] = $selectwhere;
     //     // $info['day']   =   $this->lists2($model2->where(array('day'=>$isday)),$map,$order,'day',$group);
     //     //  print_r($model2->_sql());
     ////      $info['time']   =   $this->lists2($model2->where($map),$map,$order,'timt',$group);
           
     //       $arr1=I('get.utf8name');       
     //       $this->dateformonth='bks_'.$this->dateformonth;
     //      // $field=$this->dateformonth.'ordercount.cpgame,'.$this->dateformonth.'ordercount.co,SUM('.$this->dateformonth.'ordercount.payresult)payresult,SUM('.$this->dateformonth.'ordercount.xysuccess) xysuccess,SUM('.$this->dateformonth.'ordercount.paysuccess)paysuccess,SUM('.$this->dateformonth.'ordercount.msggold) msggold,SUM('.$this->dateformonth.'ordercount.badvalue) badvalue,SUM('.$this->dateformonth.'ordercount.badgold)badgold';
     //       $field=$this->dateformonth.'ordercount.cpgame,'.$this->dateformonth.'ordercount.day,'.$this->dateformonth.'ordercount.city,'.$this->dateformonth.'ordercount.hour,'.$this->dateformonth.'ordercount.co,SUM('.$this->dateformonth.'ordercount.payresult)payresult,SUM('.$this->dateformonth.'ordercount.xysuccess) xysuccess,SUM('.$this->dateformonth.'ordercount.paysuccess)paysuccess,SUM('.$this->dateformonth.'ordercount.msggold) msggold,SUM('.$this->dateformonth.'ordercount.badvalue) badvalue,SUM('.$this->dateformonth.'ordercount.badgold)badgold';
     //       $info=$model2    //$tablename
     //                   ->join('left join bks_gamelist on(bks_gamelist.appid='.$this->dateformonth.'ordercount.cpgame)')
     //                   ->join('LEFT JOIN bks_colist ON (bks_colist.coid ='.$this->dateformonth.'ordercount.co)')
     //                   ->field($field)
     //                    ->where('bks_gamelist.name LIKE '.'\'%'.$arr1.'%\'')
     //                    ->group($this->dateformonth.'ordercount.co,'.$this->dateformonth.'ordercount.cpgame')
     //                   ->select();
     //       $aaaa= $model2->_sql();
     //      $sum_success_order = $model2->where($map)->sum('paysuccess');        //计费成功之和    
     //      // print_r($sql =  $model2->_sql());
     //       if($isbad_visible!=2)
     //       {
     //           $sum_fee = $model2->where($selectwhere)->sum('msggold');        //信息费之和
     //       }
     //       else
     //       {
     //           $sum_fee = $model2->where($selectwhere)->sum('msggold');
     //           $sum_fee1 = $model2->where($selectwhere)->sum('badgold');
     //           $sum_fee = $sum_fee - $sum_fee1;
     //           $bad_value = $model2->where($selectwhere)->sum('badvalue');      //bad条 之和
     //           $sum_success_order = $sum_success_order - $bad_value;            //实际成功数 *****  成功订单
     //       }

     //   }
       /* $data = array('sum_order'=>$sum_order,'sum_fee'=>$sum_fee,'sum_success_order'=>$sum_success_order,'isday'=>$selectwhere['isday']
            ,'ishour'=>$selectwhere['ishour'],'iscpgame'=>$selectwhere['iscpgame'],'cpgame'=>$selectwhere['cpgame'],'iscode'=>$selectwhere['iscode'],'coname'=>$seachall['coname'],'egt'=>$selectwhere['egt']);
        print_r($data);
        $this->assign('data',$data);         //data 主要用于控制 查询后的默认值*/
        $this->assign('time_start',$datatime1s);
        $this->assign('time_end',$datatime2s);
        $citylist = \PublicData::$city;        //获取省份 
        $paycodeinfo = M('paycodenamelist')->select();       //查询出计费代码表
        $paycodelist = \PublicData::$paygoldlist;
       
        if($isgroup!=null){
                       $cpwhere['coid']=$selectwhere['coid'];  
                       $gamewhere['appid']=$selectwhere['appid'];
                       $colist = M('colist')->field('id,name,coid')->where($cpwhere)->select(); 
                       $gamelist = M('gamelist')->field('id,name,appid')->where($gamewhere)->select(); 
        }else{
                 $colist = M('colist')->field('id,name,coid')->select(); 
                 $gamelist = M('gamelist')->field('id,name,appid')->select(); 
        }
        $proplist = M('proplist')->field('id,name')->select(); 
        foreach($info as $k=>$value)
        {
            $info[$k]['egt'] = \PublicData::$egtlist[$info[$k]['egt']]['name'];
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
            $info[$k]['xyfaild']= $info[$k]['payresult'] - $info[$k]['xysuccess'];          //计费请求-协议成功=协议失败*****
            $info[$k]['payfaild']= $info[$k]['xysuccess'] - $info[$k]['paysuccess'];         //计费失败
            $paysuccess = $info[$k]['paysuccess'];          //计费成功
            $payresult = $info[$k]['xysuccess'];             //协议成功
            $success = $paysuccess/$payresult;                  //  成功率 
                                            //UP值     用户数/信息费
                                           //UUP     计费用户数/信息费
            $success = $success *100;
            $success = substr($success,0,5);
            $info[$k]['success']= $success;          //  成功率   
            $outletspaysuccess = $paysuccess -  $info[$k]['badvalue'];      //计费成功-bad条=实际成功数*****  
            $success = $outletspaysuccess/$payresult;                            //  实际成功数/协议成功=渠道成功率****
            $success = $success *100;
            $success = substr($success,0,5);
            $info[$k]['outletssuccess']= $success;                           //渠道成功率****
            $outletsmsggoldsuccess = $info[$k]['msggold'] - $info[$k]['badgold'];       //信息费- bad元= 实际成交金额 *****
            $info[$k]['outletsmsggoldsuccess'] = $outletsmsggoldsuccess;             //实际成交金额 *****
            $paysuccess = $paysuccess;                                   //计费成功
            $badvalue = $info[$k]['badvalue'];                   //bad条     
            $bads = $badvalue/$paysuccess;
            $bads = $bads *100;
            $bads = substr($bads,0,5);
            $info[$k]['bads'] = $bads;                           //bad比 
            $outletsuccess = $outletspaysuccess;            //实际成功数

            $info[$k]['outletsuccess'] = $outletsuccess;
            if($info[$k]['hour'] == null)
            {
                $info[$k]['hour'] = '';
            }
        }
           $seachwhere = array();
            if($isgroup==null){
                    $seachwhere['t.egt'] = I('get.egt');
                    $seachwhere['t.cpgame'] = I('get.cpgame');
                    $seachwhere['t.prop'] = I('get.prop');
                    $seachwhere['t.co'] = I('get.coname');
                    $seachwhere['t.city'] = I('get.city');
                    $seachwhere['t.telecomname'] = I('get.telecomname');
                    $keyword = I('get.utf8name');
      
                    foreach ($seachwhere as $k=> $value) {
                        if ($seachwhere[$k]==='') {
                            unset($seachwhere[$k]);
                        }else{
                            $arr .= ' and '.$k.'='.$value;
                        }
                    }
           }else{
               $seachwhere['t.egt'] = I('get.egt');
              // $seachwhere['t.cpgame'] = I('get.cpgame');
               $seachwhere['t.prop'] = I('get.prop');
               if(isset($_GET['coname'])){
                    $seachwhere['t.co'] = I('get.coname');
               }else{
                    foreach($selectwhere['coid'][1] as $covalue){
                         $selectcotoo .=',\''.$covalue.'\'';
                    }
                    $whereadascotoo=ltrim($selectcotoo,',');
                    if($whereadascotoo!=""){
                    $cowinfo='and t.co in'.'('.$whereadascotoo.')';
                    }
               }
               
               if(isset($_GET['cpgame'])){
                   $seachwhere['t.cpgame'] = I('get.coname');
               }else{
                   foreach($selectwhere['appid'][1] as $gamevalue){
                       $selectgametoo .=',\''.$gamevalue.'\'';
                   }
                   $whereadasgametoo=ltrim($selectgametoo,',');
                   if($whereadasgametoo!=""){
                       $gamewinfo='and t.cpgame in'.'('.$whereadasgametoo.')';
                   }
               }
               
               $seachwhere['t.city'] = I('get.city');
               $seachwhere['t.telecomname'] = I('get.telecomname');
               $keyword = I('get.utf8name');
               
               foreach ($seachwhere as $k=> $value) {
                   if ($seachwhere[$k]==='') {
                       unset($seachwhere[$k]);
                   }else{
                       $arr .= ' and '.$k.'='.$value;
                   }
               }
            
           }
      /*  if ($keyword) {  
    
            $arr .= ' and t4.cpgame like %'.trim($keyword).'%';  
        }
        */
      if($order){
        foreach($order as $k=>$v){
          $orde .= $v.' desc,';
        }
      }else{
        $orde = 't.cpgame';
        }
        $orde = rtrim($orde,',');
        //获取数据表
        $dateformonth = \PublicFunction::getTimeForYH($time1).'_';
        $dateformonth= $dateformonth.'2'.'_'; 
        $tableordercount = C('DB_PREFIX').$dateformonth.'ordercount';
        $tablename =C('DB_PREFIX').$dateformonth.'vnorder'; 
        if (empty($arr)) {
            $sql = "SELECT t7.name as telname, t.city as iccid_city, t.co, t.hour, t4.name AS name1, t.cpgame, t5.name AS name2, t.prop, 
                    t6.name AS name3, CASE t.egt WHEN 1 THEN '电信' WHEN 2 THEN '联通' WHEN 3 THEN '移动' WHEN 4  THEN '支付宝' WHEN 5 
                    THEN '微信' WHEN 6 THEN '银联' WHEN 7 THEN '全网'  WHEN -1  THEN '未知' END AS egt2,
                        t.time, t.day, SUM(t.msggold-t.badgold) msggold, sum(t.payresult) payresult, SUM(t.xysuccess) xysuccess, 
                        SUM(t.paysuccess-t.badvalue) paysuccess, SUM(t.badvalue) badvalue, SUM(t.badgold) badgold, sum(b.daynewuser) as hyuser,
                        sum(b.newuser) as newuser,sum(b.daysaleuser) as daysaleuser,sum(b.initdayuser)-ifnull(sum(t8.baduser),0) as initdayuser,sum(b.initnewuser)-ifnull(sum(t8.baduser),0) as initnewuser,ifnull(sum(t8.baduser),0) as baduser,format(case sign(SUM(t.msggold-t.badgold)/sum(b.initdayuser)) when -1 then 0 when 1 then SUM(t.msggold-t.badgold)/sum(b.initdayuser) else 0 end,2)AS arpu,
                        ifnull(FORMAT(SUM(t.msggold-t.badgold) / SUM(b.daysaleuser), 2),0) AS arppu FROM  $tableordercount t LEFT JOIN bks_colist t4 
                        ON (t.co = t4.coid) LEFT JOIN bks_gamelist t5 ON (t.cpgame = t5.appid) LEFT JOIN bks_proplist t6 ON (t.prop = t6.propid) 
                        left join bks_usercount b ON (b.bksid = t.bksid) left join bks_telecomlist t7 on (t.telecomname=t7.id) 
                        left join (SELECT MAX(bksid) as bksid,baduser FROM bks_usercount where baduser=1 GROUP BY imei) t8 ON (t.bksid = t8.bksid)
                        WHERE ((t.time >= $time1) AND (t.time <=  $time2)) $cowinfo $gamewinfo  GROUP BY  $group ORDER BY $orde ";
                   }else{
                       $sql = "SELECT t7.name as telname, t.city as iccid_city, t.co, t.hour, t4.name AS name1, t.cpgame, t5.name AS name2, t.prop, 
                    t6.name AS name3, CASE t.egt WHEN 1 THEN '电信' WHEN 2 THEN '联通' WHEN 3 THEN '移动' WHEN 4  THEN '支付宝' WHEN 5 
                    THEN '微信' WHEN 6 THEN '银联' WHEN 7 THEN '全网'  WHEN -1  THEN '未知' END AS egt2,
                    t.time, t.day, SUM(t.msggold-t.badgold) msggold, sum(t.payresult) payresult, SUM(t.xysuccess) xysuccess, 
                    SUM(t.paysuccess-t.badvalue) paysuccess, SUM(t.badvalue) badvalue, SUM(t.badgold) badgold, sum(b.daynewuser) as hyuser,
                    sum(b.newuser) as newuser,sum(b.daysaleuser) as daysaleuser,sum(b.initdayuser)-ifnull(sum(t8.baduser),0) as initdayuser,sum(b.initnewuser)-ifnull(sum(t8.baduser),0) as initnewuser, ifnull(sum(t8.baduser),0) as baduser,format(case sign(SUM(t.msggold-t.badgold)/sum(b.initdayuser)) when -1 then 0 when 1 then SUM(t.msggold-t.badgold)/sum(b.initdayuser) else 0 end,2)AS arpu,
                    ifnull(FORMAT(SUM(t.msggold-t.badgold) / SUM(b.daysaleuser), 2),0) AS arppu FROM  $tableordercount t LEFT JOIN bks_colist t4 
                    ON (t.co = t4.coid) LEFT JOIN bks_gamelist t5 ON (t.cpgame = t5.appid) LEFT JOIN bks_proplist t6 ON (t.prop = t6.propid) 
                    left join bks_usercount b ON (b.bksid = t.bksid) left join bks_telecomlist t7 on (t.telecomname=t7.id)
                    left join (SELECT MAX(bksid) as bksid,baduser FROM bks_usercount where baduser=1 GROUP BY imei) t8 ON (t.bksid = t8.bksid)
                    WHERE ((t.time >= $time1) AND (t.time <=  $time2))  $arr $cowinfo $gamewinfo GROUP BY $group ORDER BY $orde ";
                   }
        $mod = M();
        $dlist = $mod->query($sql);
        $count = count($dlist);
        $m = $mod->_sql();
        //分页
        $page = new \Think\Page($count,15);
        $show = $page->show();
        if (empty($arr)) {
            $sql = "SELECT t7.name as telname, t.city as iccid_city, t.co, t.hour, t4.name AS name1, t.cpgame, t5.name AS name2, t.prop, 
                    t6.name AS name3, CASE t.egt WHEN 1 THEN '电信' WHEN 2 THEN '联通' WHEN 3 THEN '移动' WHEN 4  THEN '支付宝' WHEN 5 
                    THEN '微信' WHEN 6 THEN '银联' WHEN 7 THEN '全网'  WHEN -1  THEN '未知' END AS egt2,
                    t.time, t.day, SUM(t.msggold-t.badgold) msggold, sum(t.payresult) payresult,sum(t.mosuccess) mosuccess,SUM(t.xysuccess) xysuccess, 
                    SUM(t.paysuccess-t.badvalue) paysuccess, SUM(t.badvalue) badvalue, SUM(t.badgold) badgold, sum(b.daynewuser) as hyuser,
                    sum(b.newuser) as newuser,sum(b.daysaleuser) as daysaleuser,sum(b.initdayuser)-ifnull(sum(t8.baduser),0) as initdayuser,sum(b.initnewuser)-ifnull(sum(t8.baduser),0) as initnewuser, ifnull(sum(t8.baduser),0) as baduser,format(case sign(SUM(t.msggold-t.badgold)/sum(b.initdayuser)) when -1 then 0 when 1 then SUM(t.msggold-t.badgold)/sum(b.initdayuser) else 0 end,2)AS arpu,
                    ifnull(FORMAT(SUM(t.msggold-t.badgold) / SUM(b.daysaleuser), 2),0) AS arppu FROM  $tableordercount t LEFT JOIN bks_colist t4 
                    ON (t.co = t4.coid) LEFT JOIN bks_gamelist t5 ON (t.cpgame = t5.appid) LEFT JOIN bks_proplist t6 ON (t.prop = t6.propid) 
                    left join bks_usercount b ON (b.bksid = t.bksid) left join bks_telecomlist t7 on (t.telecomname=t7.id) 
                    left join (SELECT MAX(bksid) as bksid,baduser FROM bks_usercount where baduser=1 GROUP BY imei) t8 ON (t.bksid = t8.bksid)
                    WHERE ((t.time >= $time1) AND (t.time <=  $time2)) $cowinfo $gamewinfo GROUP BY  $group ORDER BY $orde  limit $page->firstRow,$page->listRows";
                   }else{
                       $sql = "SELECT t7.name as telname, t.city as iccid_city, t.co, t.hour, t4.name AS name1, t.cpgame, t5.name AS name2, t.prop, 
                    t6.name AS name3, CASE t.egt WHEN 1 THEN '电信' WHEN 2 THEN '联通' WHEN 3 THEN '移动' WHEN 4  THEN '支付宝' WHEN 5 
                    THEN '微信' WHEN 6 THEN '银联' WHEN 7 THEN '全网'  WHEN -1  THEN '未知' END AS egt2,
                    t.time, t.day, SUM(t.msggold-t.badgold) msggold, sum(t.payresult) payresult,sum(t.mosuccess) mosuccess,SUM(t.xysuccess) xysuccess, 
                    SUM(t.paysuccess-t.badvalue) paysuccess, SUM(t.badvalue) badvalue, SUM(t.badgold) badgold, sum(b.daynewuser) as hyuser,
                    sum(b.newuser) as newuser,sum(b.daysaleuser) as daysaleuser,sum(b.initdayuser)-ifnull(sum(t8.baduser),0) as initdayuser,sum(b.initnewuser)-ifnull(sum(t8.baduser),0) as initnewuser, ifnull(sum(t8.baduser),0) as baduser,format(case sign(SUM(t.msggold-t.badgold)/sum(b.initdayuser)) when -1 then 0 when 1 then SUM(t.msggold-t.badgold)/sum(b.initdayuser) else 0 end,2)AS arpu,
                    ifnull(FORMAT(SUM(t.msggold-t.badgold) / SUM(b.daysaleuser), 2),0) AS arppu FROM  $tableordercount t LEFT JOIN bks_colist t4 
                    ON (t.co = t4.coid) LEFT JOIN bks_gamelist t5 ON (t.cpgame = t5.appid) LEFT JOIN bks_proplist t6 ON (t.prop = t6.propid) 
                    left join bks_usercount b ON (b.bksid = t.bksid) left join bks_telecomlist t7 on (t.telecomname=t7.id)
                    left join (SELECT MAX(bksid) as bksid,baduser FROM bks_usercount where baduser=1 GROUP BY imei) t8 ON (t.bksid = t8.bksid)
                    WHERE ((t.time >= $time1) AND (t.time <=  $time2))  $arr $cowinfo $gamewinfo GROUP BY $group ORDER BY $orde limit $page->firstRow,$page->listRows";
                }
        $mod = M();
        $list = $mod->query($sql);
        $m = $mod->_sql();
        //print_r($m);
        $sum = array();
        foreach($dlist as $v){
            //总信息费
            $sum['msggoldall'] += $v['msggold'];
            //总订单
            $sum['order'] += $v['payresult'];
            //订单成功数
            $sum['succ'] += $v['paysuccess'];
            $sum['user'] += $v['user'];
            $sum['newuser'] += $v['newuser'];
            $sum['saleuser'] += $v['saleuser'];
        }
        //print_r($sum);
        $proplist = M('proplist')->field('name,propid')->select();
        //print_r($proplist);
        //获取查询分组/查询字段,给excel
        $data = I('get.');
        //print_r($data);
        $egtlist = \PublicData::$egtlist;
        $citylist = \PublicData::$city;
           foreach($list as $k=>$v){
            foreach($citylist as $k1=>$v1){
            if($v['iccid_city']==$v1['id']){
                $list[$k]['iccid_city'] = $v1['city'];
            }
            }
        }
           //print_r($list);
        $telelist = M('telecomlist')->select();
        $this->assign('telelist',$telelist);
        $this->assign('city',$citylist);
        $this->assign('data',$data);
        $this->assign('sum',$sum);
        $this->assign('proplist',$proplist);
        $this->assign('is_outlets_visible',$is_outlets_visible);
        $this->assign('is_telecom_visible',$is_telecom_visible);
        $this->assign('egtlist',$egtlist);
        $this->assign('isbad_visible',$isbad_visible);
        $this->assign('ischilddata',$ischilddata);
        $this->assign('isgroup',$isgroup);
        $this->assign('isegt',$isegt);
        $this->assign("list",$list);
        $this->assign('page',$show);       
        $this->assign("colist",$colist); //厂商
        $this->assign("gamelist",$gamelist); //游戏
        $this->assign("paycode",$paycodelist);
        $this->assign('outletname',$outletsinfo);
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
    
     //通道明细
    public function tetlecomdetail()
    {
        G('countbegin');
        $time = time();
        $time1 = '';
        $time2 = '';
        
        if(isset($_GET['timestart'])){      //获取页面过来的开始时间
            $time1    =  (string)$_GET['timestart'].' 00:00:00';
            $time1 = strtotime($time1);
        }
        else
        {
            $t = time();        //当没选择时间就默认的开始时间
            $time1 = date("y-m-d",$t);
            $time1 = $time1.' 00:00:00'; 
            $time1 = strtotime($time1);
        }
        if(isset($_GET['timestart2'])){     //获取页面过来的结束时间
            $time2    =   (string)$_GET['timestart2'].' 23:59:59';
            $time2 = strtotime($time2);
        }
        else
        {
            $t = time();         //当没选择时间就默认的结束时间
            $time2 = date("Y-m-d",$t).' 23:59:59'; 
            $time2 = strtotime($time2);
        }
        $data = array('ishour','isday','telecomname','istelecom','iscity','egt','isegt','paycode'); //  ishour：小时 isday：天  iscity:省市  egt：运营商 telecomname:通道 paycode:计费点
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'utf8name');        //判断是从输入框传入 还是 选择框输入 来获取条件
        
        $istelecom = $_GET['istelecom'];
        $iscity = $_GET['iscity'];
        $ishour = $_GET['ishour'];
        $isday = $_GET['isday'];
        $isegt =  $_GET['isegt'];
        
        if(empty($istelecom)) $istelecom = 0;   //判断$_GET  数据是否配置 然后初始化
        if(empty($iscity)) $iscity = 0;
        if(empty($ishour)) $ishour = 0;
        if(empty($isday)) $isday = 0;
        if(empty($isegt)) $isegt = 0;
        
        $selectwhere=$selectwhere_map['0'];         //条件为勾选框的条件  //将$_get=1 的值 赋值给 如: $selectwhere['telecomname']=1
        $map=$selectwhere_map['1'];             //条件为 输出口的条件
        $telecominfo =array();
        $outletsinfo = array();
        $keynames = array('telecomname','outletname');
        $is_t_o_v = $this->check_auth_info($selectwhere,$outletsinfo,$telecominfo,$keynames);     
        $is_telecom_visible = $is_t_o_v[0]; //管理员为1  渠道组为2
        $is_outlets_visible = $is_t_o_v[1]; //管理员为1  通道组为2
        $isbad_visible = $is_t_o_v[2];  //管理员为1  其他为2
        $isbad_visible2 = $is_t_o_v[3];  //管理员为1  其他为2
         if($time1!='' && $time2!='')
        {
         
            $this->dateformonth = \PublicFunction::getTimeForYH($time1).'_';
            $isold =  $_GET['isold']|0;
            if($isold !== 1)  //当老数据勾选后 值为1
            {
                $this->dateformonth =  $this->dateformonth.'2'.'_'; //新数据
            }
            $tablename = $this->dateformonth.'ordercount';      //获取数据表
            $model2 = M($tablename);  
            $datatime1s = (string)date("Y-m-d",$time1); 
            $datatime2s =(string)date("Y-m-d",$time2);
            $datatime1 = explode('-',$datatime1s);  //开始时间
            $datatime2 = explode('-',$datatime2s); //结束时间
            if($datatime1[1] != $datatime2[1])
            {
                $this->error('不允许跨月查询');
            }
            if((int)$time1 > (int)$time2)
            {
                $this->error('操作失误');
            }
            $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));   
        }      
            $group = '';
            $field = '';
            $order = '';
             if($istelecom == 1 || $ishour == 1 || $iscity == 1 ||  $_GET['telecomname'] != null || $_GET['isday'] != null || $_GET['isegt'] != null) //判断是否选择条件
             {
                     $field = 'id';
                     if($istelecom == 1)
                     {
                         if($group != '')
                             $group = $group.',';
                         $group = $group.'telecomname';
                         $order = 'msggold desc';
                         $field = $field.',telecomname';
                     }
                     if($istelecom != 1 && $_GET['telecomname']!= null)
                     {
                         if($group != '')
                             $group = $group.',';
                         $group = $group.'telecomname';
                         $order = 'msggold desc';
                         $field = $field.',telecomname';
                     }
                     if($ishour == 1)
                     {
                         if($group != '')
                             $group = $group.',';
                         $group = $group.'hour';
                         $order = 'hour desc';
                         $field = $field.',hour';
                     }
                
                     if($iscity == 1)
                     {
                         if($group != '')
                             $group = $group.',';
                         $group = $group.'city';
                         $order = 'city asc';
                         $field = $field.',city';
                     }
                     if($isday == 1)
                     {
                         if($group != '')
                             $group = $group.',';
                         $group = $group.'day';
                         $order = 'day desc';
                         $field = $field.',day';
                     }
                     if($isegt == 1)
                     {
                         if($group != '')
                             $group = $group.',';
                         $group = $group.'egt';
                         $order = 'id desc';
                         $field = $field.',egt';
                     }
                     $field = $field.',day,sum(payresult) payresult,sum(xysuccess) xysuccess,sum(paysuccess)paysuccess,sum(msggold) msggold, sum(badvalue) badvalue,sum(badgold) badgold ';  
                }
               else{
                        if($is_outlets_visible == 1)
                        {
                                $istelecom = 1;
                                $group = $group.'telecomname';
                                $order = 'msggold desc';
                                $field = $field.'id,telecomname,time';
                                $field = $field.',day,sum(payresult) payresult,sum(xysuccess) xysuccess,sum(paysuccess)paysuccess,sum(msggold) msggold, sum(badvalue) badvalue,sum(badgold) badgold ';
                        }else{
                        
                                $iscpgame = 1;
                                $group = $group.'cpgame';
                                $order = 'msggold desc';
                                $field = $field.'id,cpgame,time';
                                $field = $field.',day,sum(payresult) payresult,sum(xysuccess) xysuccess,sum(paysuccess)paysuccess,sum(msggold) msggold, sum(badvalue) badvalue,sum(badgold) badgold ';                                                               
                        }                  
               }
              if($map == null)      //如果输入框为空
              {
                    $info   =   $this->lists2($model2->where($selectwhere),$selectwhere,$order,$field,$group);
                    $sql = $model2->_sql(); //用于 输出上一次执行的SQL语句  主要用来排错 
                    $sum_order = $model2->where($selectwhere)->sum('payresult');        // 算出计费请求之和 
                    $sql = $model2->_sql();
                    $sum_success_order = $model2->where($selectwhere)->sum('paysuccess');   //算出计费成功之和
                    $sql = $model2->_sql();
                    if($isbad_visible!=2)   //管理员为1
                     {
                            $sum_fee = $model2->where($selectwhere)->sum('msggold');        //算出信息费之和
                            $sql = $model2->_sql();            //用于 输出上一次执行的SQL语句    
                     }
                     else
                    {
              
                      $sum_fee = $model2->where($selectwhere)->sum('msggold');               //算出信息费(成交金额)之和
                      $sql = $model2->_sql();
                      $sum_fee1 = $model2->where($selectwhere)->sum('badgold');          //     bad元之和
                      $sql = $model2->_sql();
                      $sum_fee = $sum_fee - $sum_fee1;      // 信息费(成交进入)-bad元=    实际成交金额******
                      $bad_value = $model2->where($selectwhere)->sum('badvalue');       //bad条 之和
                      $sql = $model2->_sql();
                      $sum_success_order = $sum_success_order - $bad_value;             //实际成功数 *****  
                    }
              }else
              {
                  $group = 'telecomname';
                  $map['_complex'] = $selectwhere;
                  $info   =   $this->lists2($model2->where($map),$map,$order,$field,$group);
                  $sql = $model2->_sql();
                  $sum_success_order = $model2->where($map)->sum('paysuccess');         //计费成功之和
                  $sql = $model2->_sql();
                  if($isbad_visible!=2) //管理员为1
                  {
                      $sum_fee = $model2->where($selectwhere)->sum('msggold');          //信息费之和
                      $sql = $model2->_sql();
                  }
                  else
                  {
                      $sum_fee = $model2->where($selectwhere)->sum('msggold');
                      $sql = $model2->_sql();
                      $sum_fee1 = $model2->where($selectwhere)->sum('badgold');
                      $sql = $model2->_sql();
                      $sum_fee = $sum_fee - $sum_fee1;
                      $bad_value = $model2->where($selectwhere)->sum('badvalue');
                      $sql = $model2->_sql();
                      $sum_success_order = $sum_success_order - $bad_value;
                  }                 
              
              }
            $getall = I('get.');
            //print_r($getall);        
            $data = array('sum_order'=>$sum_order,'sum_fee'=>$sum_fee,'sum_success_order'=>$sum_success_order,'isday'=>$selectwhere['isday']
               ,'ishour'=>$selectwhere['ishour'],'iscpgame'=>$selectwhere['iscpgame'],'iscode'=>$selectwhere['iscode'],'telecomname'=>$selectwhere['telecomname']
               ,'outletname'=>$selectwhere['outletname'],'egt'=>$selectwhere['egt']);
          
            $this->assign('data',$data);
            $this->assign('getall',$getall);
            $this->assign('time_start',$datatime1s);
            $this->assign('time_end',$datatime2s);
            $citylist = \PublicData::$city;      //获取省份 
           
            $paycodeinfo = M('paycodenamelist')->select();       //查询出
            $paycodelist = \PublicData::$paygoldlist;
            foreach($info as $k=>$value)
            {
                $info[$k]['egt'] = \PublicData::$egtlist[$info[$k]['egt']]['name'];
                    foreach($telecominfo as $value4)
                    {
                        if($value['telecomname'] == $value4['id'])
                        {
                            $info[$k]['telecomname'] = $value4['name'];
                        }
                    } 
                    foreach($paycodeinfo as $value3)
                    {
                        if($value['paycode'] == $value3['id'])
                        {
                            $info[$k]['paycode'] = $value3['name'];
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
                    $info[$k]['xyfaild']= $info[$k]['payresult'] - $info[$k]['xysuccess'];      //计费请求-协议成功=协议失败*****
                    $info[$k]['payfaild']= $info[$k]['xysuccess'] - $info[$k]['paysuccess'];    //计费失败
                    $paysuccess = $info[$k]['paysuccess'];      //计费成功
                    $payresult = $info[$k]['xysuccess'];        //协议成功
                    $success = $paysuccess/$payresult;              
                    $success = $success *100;                   // 计费成功率   
                    $success = substr($success,0,5);            
                    $info[$k]['success']= $success;               // 计费 成功率 
                    $xysuccessbas=$payresult/$info[$k]['xyfaild'];  //协议成功率
                    $xysuccessbas=$xysuccessbas*100;
                    $xysuccessbas = substr($xysuccessbas,0,5); 
                    $info[$k]['xysuccessbas']=$xysuccessbas;        //协议成功率
                    $outletspaysuccess = $paysuccess -  $info[$k]['badvalue'];        //计费成功-bad条=实际成功数*****  
                    $success = $outletspaysuccess/$payresult;                           //  实际成功数/计费请求=渠道成功率****
                    $success = $success *100;
                    $success = substr($success,0,5);
                    $info[$k]['outletssuccess']= $success;              //渠道成功率****
                    $outletsmsggoldsuccess = $info[$k]['msggold'] - $info[$k]['badgold'];  //信息费- bad元= 实际成交金额 *****
                    $info[$k]['outletsmsggoldsuccess'] = $outletsmsggoldsuccess;        //实际成交金额 *****
                    $paysuccess = $paysuccess;      //计费成功
                    $badvalue = $info[$k]['badvalue'];      //bad条
                    $bads = $badvalue/$paysuccess;          //
                    $bads = $bads *100;
                    $bads = substr($bads,0,5);
                    $info[$k]['bads'] = $bads;              //bad比
                    $outletsuccess = $outletspaysuccess;            //实际成功数
                    $info[$k]['outletsuccess'] = $outletsuccess;
                    if($info[$k]['hour'] == null)
                    {
                        $info[$k]['hour'] = '';
                    }
                         
            }
            //获取数据表
            $dateformonth = \PublicFunction::getTimeForYH($time1).'_';
            $dateformonth= $dateformonth.'2'.'_'; 
            $tablename =C('DB_PREFIX').$dateformonth.'ordercount'; 
            //查询条件
            $teleid = I('get.telecomname');
            $egt = I('get.egt');
            $seachwhere = array();
            $seachwhere['egt'] = I('get.egt');
            $seachwhere['telecomname'] = I('get.telecomname');
            foreach ($seachwhere as $k=> $value) {
                if ($seachwhere[$k]==='') {
                    unset($seachwhere[$k]);
                }else{
                    $arr .= ' and '.$k.'='.$value;
                }
            }
            if ($arr){
                      $sql = "SELECT `city`,`day`,`hour`,`telecomname`,`egt`,SUM(msggold) msggold1,sum(payresult) AS payresult,SUM(xysuccess) AS xysuccess,
                    sum(payresult)-SUM(xysuccess) AS xyf,FORMAT(SUM(xysuccess)/sum(payresult),4) AS xyl,SUM(paysuccess) AS paysuccess,FORMAT(SUM(paysuccess)/SUM(xysuccess),4) AS payl,
                    FORMAT(sum(payresult)-SUM(paysuccess),4) AS payf,SUM(mosuccess) AS mo,FORMAT(SUM(mosuccess)/sum(payresult),4) AS  mol, FORMAT(SUM(paysuccess)-SUM(badvalue),4) AS sjcg,
                    SUM(msggold)-SUM(badgold) AS msggold,SUM(badvalue) AS badvalue,SUM(badgold) AS badgold,ifnull(format(SUM(paysuccess)-SUM(badvalue),4)/SUM(xysuccess),0) as sjl,SUM(badvalue)/SUM(paysuccess) as badb FROM $tablename WHERE 
                   status=1 AND (`time` >= $time1) AND (`time` <= $time2)  $arr GROUP BY $group   ORDER BY id DESC";
            }else{
                     $sql = "SELECT `city`,`day`,`hour`,`telecomname`,`egt`,SUM(msggold) msggold1, sum(payresult) AS payresult,SUM(xysuccess) AS xysuccess,
                    sum(payresult)-SUM(xysuccess) AS xyf,FORMAT(SUM(xysuccess)/sum(payresult),4) AS xyl,SUM(paysuccess) AS paysuccess,FORMAT(SUM(paysuccess)/SUM(xysuccess),4) AS payl,
                    FORMAT(sum(payresult)-SUM(paysuccess),4) AS payf,SUM(mosuccess) AS mo,FORMAT(SUM(mosuccess)/sum(payresult),4) AS  mol, FORMAT(SUM(paysuccess)-SUM(badvalue),4) AS sjcg,
                    SUM(msggold)-SUM(badgold) AS msggold,SUM(badvalue) AS badvalue,SUM(badgold) AS badgold,ifnull(format(SUM(paysuccess)-SUM(badvalue),4)/SUM(xysuccess),0) as sjl,SUM(badvalue)/SUM(paysuccess) as badb FROM $tablename WHERE 
                    status=1 AND (`time` >= $time1) AND (`time` <= $time2)  GROUP BY  $group   ORDER BY id DESC";
         
                 }
            $mod = M();
            $dlist = $mod->query($sql);
            $count = count($dlist);
            //分页
            $page = new \Think\Page($count,25);
            $show = $page->show();
            if ($arr){
                      $sql = "SELECT `city`,`day`,`hour`,`telecomname`,`egt`,SUM(msggold) msggold1,sum(payresult) AS payresult,SUM(xysuccess) AS xysuccess,
                    sum(payresult)-SUM(xysuccess) AS xyf,FORMAT(SUM(xysuccess)/sum(payresult),4) AS xyl,SUM(paysuccess) AS paysuccess,FORMAT(SUM(paysuccess)/SUM(xysuccess),4) AS payl,
                    FORMAT(sum(payresult)-SUM(paysuccess),4) AS payf,SUM(mosuccess) AS mo,FORMAT(SUM(mosuccess)/sum(payresult),4) AS  mol, FORMAT(SUM(paysuccess)-SUM(badvalue),4) AS sjcg,
                    SUM(msggold)-SUM(badgold) AS msggold,SUM(badvalue) AS badvalue,SUM(badgold) AS badgold,ifnull(format(SUM(paysuccess)-SUM(badvalue),4)/SUM(xysuccess),0) as sjl,SUM(badvalue)/SUM(paysuccess) as badb FROM $tablename WHERE 
                    status=1 AND  (`time` >= $time1) AND (`time` <= $time2)   $arr GROUP BY $group   ORDER BY id DESC limit $page->firstRow,$page->listRows";
            }else{
                $sql = "SELECT `city`,`day`,`hour`,`telecomname`,`egt`,SUM(msggold) msggold1, sum(payresult) AS payresult,SUM(xysuccess) AS xysuccess,
                    sum(payresult)-SUM(xysuccess) AS xyf,FORMAT(SUM(xysuccess)/sum(payresult),4) AS xyl,SUM(paysuccess) AS paysuccess,FORMAT(SUM(paysuccess)/SUM(xysuccess),4) AS payl,
                    FORMAT(sum(payresult)-SUM(paysuccess),4) AS payf,SUM(mosuccess) AS mo,FORMAT(SUM(mosuccess)/sum(payresult),4) AS  mol, FORMAT(SUM(paysuccess)-SUM(badvalue),4) AS sjcg,
                    SUM(msggold)-SUM(badgold) AS msggold,SUM(badvalue) AS badvalue,SUM(badgold) AS badgold,ifnull(format(SUM(paysuccess)-SUM(badvalue),4)/SUM(xysuccess),0) as sjl,SUM(badvalue)/SUM(paysuccess) as badb FROM $tablename WHERE 
                     status=1 AND (`time` >= $time1) AND (`time` <= $time2)  GROUP BY  $group   ORDER BY id DESC limit $page->firstRow,$page->listRows";
                 }
            $mod = M();
            $list = $mod->query($sql);
            foreach($dlist as $v){
                //总信息费
                $sum['msggoldall'] += $v['msggold1'];
                //总订单
                $sum['order'] += $v['payresult'];
                //订单成功数
                $sum['succ'] += $v['sjcg'];
            }
            //获取通道名
            $telename = M('telecomlist')->field('id,name')->select();
            $citylist = \PublicData::$city;
            $in -= 1;
            foreach ($list as $key => $value) {
                $in++;
                foreach ($telename as $value2) {
                    if ($list[$in]['telecomname']==$value2['id']) {
                       $list[$in]['telecomname'] =$value2['name']; 
                    }
                  
                }
                //获取运营商
                switch ($list[$in]['egt']) {
                    case $list[$in]['egt']=1:
                        $list[$in]['egt'] = '电信';
                        break;
                    case $list[$in]['egt']=2:
                        $list[$in]['egt'] = '联通';
                    break;
                    case $list[$in]['egt']=3:
                        $list[$in]['egt'] = '移动';
                    break;
                }
                //获取城市名
                foreach ($citylist as $value3) {
                    if($list[$in]['city']==$value3['id']){
                        $list[$in]['city']=$value3['city'];
                    }
                }
                
            }
            $egtlist = \PublicData::$egtlist;  
            $this->assign('isbad_visible2',$isbad_visible2);
            $this->assign('sum',$sum);
            $this->assign('is_outlets_visible',$is_outlets_visible);
            $this->assign('is_telecom_visible',$is_telecom_visible);
            $this->assign('egtlist',$egtlist);
            $this->assign('isbad_visible',$isbad_visible);
            $this->assign('ischilddata',$ischilddata);
            $this->assign('isegt',$isegt);
            $this->assign('page',$show);
            $this->assign("list",$list);
            $this->assign("paycode",$paycodelist);
            $this->assign('outletname',$outletsinfo);
            $this->assign("telecomname",$telecominfo);
            $this->assign('iscpgame',$iscpgame);
            $this->assign('isco',$isco);
            $this->assign('isprop',$isprop);
            $this->assign('istelecom',$istelecom);
            $this->assign('iscity',$iscity);
            $this->assign('ishour',$ishour);
            $this->assign('isday',$isday);
            $this->display();

 }
    
    //明细
    public function detail()
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
     

        $istelecom = $_GET['ischilddata']; //明细
        $iscity = $_GET['iscity'];
        $ishour = $_GET['ishour'];
        $isday = $_GET['isday'];
        $ischilddata =1;
        $isegt =  $_GET['isegt'];
        if(empty($isegt)) $isegt = 0;
        if(empty($ischilddata)) $ischilddata = 1;
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
        $group_id = $is_t_o_v[3];
    //    $ischilddata = $is_t_o_v[4];
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
            if($istelecom == 1)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'telecomname';
                $order = 'msggold desc';
                $field = $field.',telecomname';
            }
            if($istelecom != 1 && $_GET['telecomname']!= null)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'telecomname';
                $order = 'msggold desc';
                $field = $field.',telecomname';
            }
            if($iscity == 1)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'city';
                $order = 'city asc';
                $field = $field.',city';
            }
            if($ishour == 1)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'hour';
                $order = 'hour desc';
                $field = $field.',hour';
            }
            if($isday == 1)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'day';
                $order = 'day desc';
                $field = $field.',day';
            }
            if($isegt == 1)
            {
                if($group != '')
                    $group = $group.',';
                $group = $group.'egt';
                $order = 'id desc';
                $field = $field.',egt';
            }
            $field = $field.',day,sum(payresult) payresult,sum(xysuccess) xysuccess,sum(paysuccess)paysuccess,sum(msggold) msggold, sum(badvalue) badvalue,sum(badgold) badgold ';
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
        $data = array('sum_order'=>$sum_order,'sum_fee'=>$sum_fee,'sum_success_order'=>$sum_success_order,'isday'=>$selectwhere['isday']
            ,'ishour'=>$selectwhere['ishour'],'iscpgame'=>$selectwhere['iscpgame'],'iscode'=>$selectwhere['iscode'],'telecomname'=>$selectwhere['telecomname'],'outletname'=>$selectwhere['outletname'],'egt'=>$selectwhere['egt']);
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
        }

        $egtlist = \PublicData::$egtlist;
        $this->assign('is_outlets_visible',$is_outlets_visible);
        $this->assign('is_telecom_visible',$is_telecom_visible);
        $this->assign('egtlist',$egtlist);
        $this->assign('isbad_visible',$isbad_visible);
        $this->assign('ischilddata',$ischilddata);
        $this->assign('isegt',$isegt);
        $this->assign("list",$info);
        $this->assign("paycode",$paycodelist);
        $this->assign('outletname',$outletsinfo);
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
    
    
    
    public function mobileorder()
    {
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth =  $this->dateformonth.'2'.'_';

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
        $tablename = $this->dateformonth.'mobileorder';
        $model2 = M($tablename);      
        $data = array('outlets');
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'mobile');
        $selectwhere = $selectwhere_map[0];
        $map = $selectwhere_map[1];
        if($time1!='' && $time2!='')
        {
            $datatimes1 = (string)date("Y-m-d",$time1); 
            $datatimes2 =(string)date("Y-m-d",$time2);
            $datatime1 = explode('-',$datatimes1);
            $datatime2 = explode('-',$datatimes2);
            if($datatime1[1] != $datatime2[1])
            {
                $this->error('不允许跨月查询');
            }
            $this->dateformonth ='';
            foreach($datatime1 as $k=>$value)
            {
                if($k != 2)
                    $this->dateformonth = $this->dateformonth.$value.'_';
            }
            $tablename = $this->dateformonth.'mobileorder';
            $model2 = M($tablename);      
            if((int)$time1 > (int)$time2)
            {
                $this->error('操作失误');
            }
            $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));            
        }
        $group = 'outlets';
        $order = 'id desc';
        ////$field = 'telecomname';
        $field = 'id,outlets,sum(result) result,sum(resultsuccess) resultsuccess,time,imsi, mobile ';

        if($map == null)
        {
            $info   =   $this->lists2($model2->where($selectwhere),$selectwhere,$order,$field,$group);
            $sql = $model2->_sql();
            //print_r($sql);
            $sum_order = $model2->where($selectwhere)->sum('result');
            $sum_success_order = $model2->where($selectwhere)->sum('resultsuccess');
        }
        else
        {
            $info   =   $this->lists2($model2->where($map),$map,$order,$field,$group);
            $sql = $model2->_sql();
            //print_r($sql);
            $sum_order = $model2->where($selectwhere)->sum('result');
            $sum_success_order = $model2->where($selectwhere)->sum('resultsuccess');
        }
        $this->assign('time_start',$datatimes1);
        $this->assign('time_end',$datatimes2);
        $outletsinfo = M('colist')->field('id,name')->select();
        $telecominfo = M('telecomlist')->select();
        $paycodelist = \PublicData::$paygoldlist;
        $seachall = I('get.');
        foreach ($outletsinfo as $key => $value) {
            if ($seachall['coid']==$value['id']) {
               $seachall['coid']=$value['name'];
            }
        }
        $data = array('sum_order'=>$sum_order,'sum_success_order'=>$sum_success_order,'coname'=>$seachall['coid']);
        $this->assign('data',$data);
        $index=-1;
        foreach($info as $value)
        {
            $index++;
            foreach($outletsinfo as $value3)
            {
                if($value['outlets'] == $value3['id'])
                {
                    $info[$index]['outlets'] = $value3['name'];
                }
            }
            foreach($telecominfo as $value4)
            {
                if($value['telecomname'] == $value4['id'])
                {
                    $info[$index]['telecomname'] = $value4['name'];
                }
            }
            $info[$index]['time'] = date("Y-m-d H:i:s", $value['time']);
        }

        $seachwhere['coid'] = I('get.coid');
        $cname = M('colist')->field('id,coid')->select();
        foreach ($cname as  $v) {
            if ($seachwhere['coid']==$v['id']) {
               $seachwhere['coid']=$v['coid'];
            }
        }
        //查询条件
        $coid = $seachwhere['coid'];
        //分页总条数
        if ($coid) {
           $sql = "SELECT  t.extData,t3.name as name1,t2.name as name2,t.imsi,t.mobile,t.time 
                FROM `bks_usercount` t left join bks_gamelist t2 on (t.appid=t2.appid)
                left join bks_colist t3 on (t.coid=t3.coid) WHERE  t.saleuser=1 and t.coid=$coid";
        }else{
             $sql = " SELECT  t.extData,t3.name as name1,t2.name as name2,t.imsi,t.mobile,t.time 
                FROM `bks_usercount` t left join bks_gamelist t2 on (t.appid=t2.appid)
                left join bks_colist t3 on (t.coid=t3.coid) WHERE  t.saleuser=1";
        }
        $m = M();
        $dlist = $m->query($sql);
         //print_r($m->_sql());
        $count = count($dlist); 
        $page = new \Think\Page($count,25);
        $show = $page->show();
        if ($coid) {
           $sql = "SELECT  t.extData,t3.name as name1,t2.name as name2,t.imsi,t.mobile,t.time 
                FROM `bks_usercount` t left join bks_gamelist t2 on (t.appid=t2.appid)
                left join bks_colist t3 on (t.coid=t3.coid) WHERE  t.saleuser=1 and t.coid=$coid 
                ORDER BY  t.time limit $page->firstRow,$page->listRows";
        }else{
             $sql = "SELECT  t.extData,t3.name as name1,t2.name as name2,t.imsi,t.mobile,t.time 
                FROM `bks_usercount` t left join bks_gamelist t2 on (t.appid=t2.appid)
                left join bks_colist t3 on (t.coid=t3.coid) WHERE  t.saleuser=1 ORDER BY 
                t.time limit $page->firstRow,$page->listRows";
        }
        $mod = M();
        $list = $mod->query($sql);
        //print_r($mod->_sql());
       
       
        $this->auto_user($selectwhere,$outletsinfo,'outletname');
        $this->assign('outletsinfo',$outletsinfo);
        $this->assign("list",$list);
        $this->assign('page',$show);
        $this->assign("telecomname",$telecominfo);
        $this->assign("paycode",$paycodelist);
        $this->display();
    }
    public function editdata($isold = 0)
    {
        if(IS_POST)
        {
            $data = I('post.');
            $data['time'] = strtotime($data['time']);
            $selectwhere = array('id'=>$data['id']);
            $time = $data['time'];
            $this->dateformonth = \PublicFunction::getTimeForYH($time);
            if($isold == 1)
                $this->dateformonth = $this->dateformonth.'_';
            else
                $this->dateformonth =  $this->dateformonth.'_'.'2'.'_';
            $tablename = $this->dateformonth.'codata';
            $model = M($tablename);
            $data['userstatus']=1;
            $isok = $model->where($selectwhere)->data($data)->save();
            
        //    $isok =  M($tablename)->data($data)->add();
            if($isok === false)
            {
                $this->error("找写代码的解决");
            }
            else
            {
                $id = 0;
                action_log('修改数据', 'uptelecom', $id, UID);
                $this->success('修改成功', Cookie('__forward__'));
            }
        }
        else
        {
            $data = I('get.');
            $id = $data['id'];
            $selectwhere = array('id'=>$id);
            $time = strtotime($data['time']);
            $isold = $data['isold'];
            $this->dateformonth =   \PublicFunction::getTimeForYH($time).'_'.'2'.'_';
            if($isold == 1)
                $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';

            $tablename = $this->dateformonth.'codata';
            $model = M($tablename);
            $info = $model->where($selectwhere)->select();
            $info = $info[0];           
          //  $outletsinfo = M('colist')->where(array('coid'=>$info['co']))->select();
          //  $outletsinfo=$outletsinfo[0];
            $coinfo = M('colist')->select();
            $info['time'] = date("Y-m-d",$info['time']);
            $egtlist = \PublicData::$egtlist;
            $statuslist = \PublicData::$openstatic;
          //  $this->assign('outletsinfo',$outletsinfo);
            $this->assign('colist',$coinfo);
            $this->assign('egtlist',$egtlist);
            $this->assign('statuslist',$statuslist);
            $this->assign('info',$info);
            $this->assign('isold',$isold);
            $this->display();
        }
    }
    public function adddata($isold = 0)
    {
        if(IS_POST)
        {
            $data = I('post.');
            $this->ischeckonenextfordata($data);
            $date = $data['time'];
            $date = explode('-',$date);
            $data['day'] = $date[2];
            $time1    =  $data['time'].' 06:00:00';
            $time1 = strtotime($time1);
            $data['time'] = $time1;
            $selectwhere = array('co'=>$data['co'],'day'=>$data['day'],'egt'=>$data['egt']);
            $this->dateformonth ='';
            foreach($date as $k=>$value)
            {
                if($k != 2)
                {
                    $this->dateformonth =$this->dateformonth.$value.'_';
                }
            }
            if($isold !== 1)
            {
                $this->dateformonth =$this->dateformonth.'2'.'_';
            }
            $tablename = $this->dateformonth.'codata';
            $isok = M($tablename)->where($selectwhere)->select();
            if($isok == null)
            {
               $isok =  M($tablename)->data($data)->add();
              // print_r($a=M()->_sql());
               if($isok == false)
               {
                   $this->error("找写代码的解决");
               }
               else
               {
                   $id = 0;
                   action_log('添加数据', 'uptelecom', $id, UID);
                   $this->success('新增成功', Cookie('__forward__'));
               }
            }
            else
            {
                $this->error("数据已存在，不可重复添加。");
            }
            
        }
        else
        {
            $colist = M('colist')->select();
            $egtlist = \PublicData::$egtlist;
            $statuslist = \PublicData::$openstatic;
            $this->assign('colist',$colist);
            $this->assign('egtlist',$egtlist);
            $this->assign('statuslist',$statuslist);
            $this->assign('isold',$isold);

            $this->display();
        }
    }
    public function makedata($isold = 0)
    {
        if(IS_POST)
        {
            if($_POST['month'] !== ''){
                $time1    =  (string)$_POST['month'].' 00:00:00';
                $time1 = strtotime($time1);
                $time2    =   (string)$_POST['month'].' 23:59:59';
                $time2 = strtotime($time2);
                
                $datatime1 = (string)date("Y-m-d",$time1); 
                $datatime2 =(string)date("Y-m-d",$time2);
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
                            $this->dateformonth =$this->dateformonth.$value.'_';
                    }
               }
                if($isold !== 1)
                { 
                    $this->dateformonth =  $this->dateformonth.'2'.'_';
                }
               $tablename = $this->dateformonth.'ordercount';
               $model2 = M($tablename);      
               if((int)$time1 > (int)$time2)
               {
                    $this->error('操作失误');
               }
               $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));            
               $group = 'co,egt,cpgame';
               $order = 'msggold desc';
               $field = 'id,co,time';
               $field = $field.',day,egt,cpgame,sum(msggold) msggold,sum(badgold) badgold ';
               $info = $model2->order($order)->where($selectwhere)->field($field)->group($group)->select();
             // print_r($a=$model2->_sql());
               $tablename =  'bks_'.$this->dateformonth.'codata';
               $day = $datatime2[2];
               $selectwhere = array('day'=>$day);
               $swapinfo = M($this->dateformonth.'codata')->where($selectwhere)->select();
               if($swapinfo != null)
               {
                   $model2 = M($this->dateformonth.'codata');
                   $isok = $model2->where('day='.$day)->delete();
                   if($isok === false)
                   {
                       $this->error("操作失败");
                   }
               }
               $model = M();
               $model->starttrans();
               foreach($info as $k=>$value)
               {
                   $data['day'] = $day;
                   $data['time'] = $value['time'];
                   $data['egt'] = $value['egt'];
                   $data['msggold'] = $value['msggold'] - $value['badgold'];
                   $data['status'] = 2;
                   $data['appid'] =$value['cpgame'];
                   $data['co'] = $value['co'];
                   if($k == count($info) - 1)
                   {
                       $this->adddataforsql($model,$tablename,$data,true,$id);
                   }
                   else
                   {
                       $this->adddataforsql($model,$tablename,$data,false,$id);
                   }               
               }
            }
            else
            {
                $this->error("请选择结算数据日期时间");

            }
        }
        else
        {
            $this->assign('isold',$isold);
            $this->display();
        }
    }

    public function evritydataall($isold = 0)
    {
        $data = I('post.');
        $data2 = I('get.');
        $count = count($data);
        if($count !== 0)
        {
            
            $time = $data2['time'];
            $this->dateformonth = \PublicFunction::getTimeForYH($time);
            if($isold == 1)
                $this->dateformonth =$this->dateformonth.'_';
            else
            {
                $this->dateformonth =  $this->dateformonth.'_'.'2'.'_';

            }
            $tablename = $this->dateformonth.'codata';
            foreach($data as $value)
            {
                foreach($value as $value2)
                {
                    $upatedata = array('status'=>1);
                    
                    $isok = M($tablename)->where(array("id"=>$value2))->data($upatedata)->save();
                }
            }
            if($isok === false)
            {
                $this->error("找写代码的解决");
                exit();
            }
            else
            {
                $id = 0;
                action_log('审核', 'uptelecom', $id, UID);
                $this->success('审核', Cookie('__forward__'));
            }
        }
        else
        {
            $this->error("请选择要操作的数据");
        }
    }
    public function evritydata($isold = 0)
    {
        $data = I('get.');
        $count = count($data);
        
        if($count !== 0)
        {
            $timearr = $data['time'];
            $time=strtotime($timearr);
            $this->dateformonth = \PublicFunction::getTimeForYH($time);
            $isold = $data['isold'];

            if($isold == 1)
                $this->dateformonth =$this->dateformonth.'_';
            else
            {
                $this->dateformonth =  $this->dateformonth.'_'.'2'.'_';

            }
            $tablename = $this->dateformonth.'codata';
            $info = M($tablename)->where(array("id"=>$data['id']))->select();  
           // $aa=M()->_sql();
            $upatedata = array('status'=>1);
            $isok = M($tablename)->where(array("id"=>$data['id']))->data($upatedata)->save();
            if($isok == false)
            {
                $this->error("找写代码的解决");
            }
            else
            {
                $id = 0;
                action_log('修改数据', 'uptelecom', $id, UID);
                $this->success('修改成功', Cookie('__forward__'));
            }
        }
        else
        {
            $this->error("找写代码的解决");
        }
    }
    var $info = null;
    //结算管理 
    public function settlement()
    {   
        $outletname = $_SESSION['username'];
        $time = strtotime('-1 day');
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth =  $this->dateformonth.'2'.'_';
        $time1 = '';
        $time2 = '';
        $outletsinfo = M('colist')->select(); //厂商列表
        $egtlist = \PublicData::$egtlist;
        $statuslist = \PublicData::$openstatic;
        if(isset($_GET['timestart'])){
            $time1    =  (string)$_GET['timestart'].' 00:00:00';
            $time1 = strtotime($time1);
        }
        else
        {
            $t = strtotime('-1 day');
            //$t = $t - 60*60*24;
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
            $t = strtotime('-1 day');
            /*$t = $t - 60*60*24;*/
            $time2 = date("Y-m-d",$t).' 23:59:59'; 
            $time2 = strtotime($time2);
        }
        $isold = $_GET['isold'];
        $data = array('co','status','egt');
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'name');
        $selectwhere = $selectwhere_map[0];
        $map = $selectwhere_map[1];
        $outletsinfo = array();
        $this->auto_user($selectwhere,$outletsinfo,'coid'); //这里的COid 是指厂商ID
        $data = array('coid'=>$selectwhere['co'],'status'=>$selectwhere['status'],'egt'=>$selectwhere['egt'],'isold'=>$isold);
        if($data['coid']){
            $select['coid'] = $data['coid'];
        }
        if($data['status']){
            $select['status'] = $data['status'];
        }
        if($data['egt']){
            $select['t3.egt'] = $data['egt'];
        }
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
            $this->dateformonth ='';
            foreach($datatime1 as $k=>$value)
            {
                if($k != 2)
                {
                    $this->dateformonth = $this->dateformonth.$value.'_';
                }
                

            }
            if($isold != 1)
                $this->dateformonth =  $this->dateformonth.'2'.'_';
            $tablename = $this->dateformonth.'codata';
            $model2 = M($tablename);
            if((int)$time1 > (int)$time2)
            {
                $this->error('操作失误');
            }
            $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));            
        }
        
        if($select!=''){
            foreach($select as $k=>$v){
                $sele .= $k.' = '.$v .' and ';
            }
           // $sele = ltrim($sele,' and ');
        }
        //获取数据表
        $dateformonth = \PublicFunction::getTimeForYH($time1).'_';
        $dateformonth= $dateformonth.'2'.'_'; 
        $tablenamecodata =C('DB_PREFIX').$dateformonth.'codata'; 
        
        $mm = M();
        $sql = "SELECT t.id, DATE_FORMAT(FROM_UNIXTIME(t.time), '%Y-%m-%d') as time, t.day, t.egt, t.ljnewser,
                t.msggold, t.status, t.co, t.appid, t3.time AS time2, ifnull(t3.newuser,0) as newuser,
                ifnull(t3.hyyh,0) as hyyh, ifnull(t3.ffyh,0) as ffyh FROM $tablenamecodata t LEFT 
                JOIN (SELECT t2.coid,t2.appid,t2.egt,DATE_FORMAT(t2.time, '%Y-%m-%d') AS time,SUM(t2.initnewuser) AS newuser,
                SUM(t2.initdayuser) AS hyyh,t2.daysaleuser AS ffyh FROM bks_usercount t2 WHERE t2.coid <> '' AND t2.appid <> ''  GROUP
                BY t2.coid , t2.appid , t2.egt, DATE_FORMAT(t2.time, '%Y-%m-%d')) t3 ON (t3.time = DATE_FORMAT(FROM_UNIXTIME(t.time), '%Y-%m-%d') AND
                t3.coid = co AND t3.appid = t.appid AND t3.egt = t.egt)  where $sele  ((t.time >= $time1) AND (t.time <=  $time2))  ORDER BY t.time ";
        $info1 = $mm->query($sql);
        $aaaa=M()->_sql();
        $count =  count($info1);// 查询满足要求的总记录数
        $page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        $show = $page->show();// 分页显示输出
        $sql = "SELECT t.id, DATE_FORMAT(FROM_UNIXTIME(t.time), '%Y-%m-%d') as time, t.day, t.egt,t.ljnewser,
                t.msggold, t.status, t.co, t.appid, t3.time AS time2, ifnull(t3.newuser,0) as newuser,
                ifnull(t3.hyyh,0) as hyyh, ifnull(t3.ffyh,0) as ffyh FROM $tablenamecodata t LEFT 
                JOIN (SELECT t2.coid,t2.appid,t2.egt,DATE_FORMAT(t2.time, '%Y-%m-%d') AS time,SUM(t2.initnewuser) AS newuser,
                SUM(t2.initdayuser) AS hyyh,t2.daysaleuser AS ffyh FROM bks_usercount t2 WHERE t2.coid <> '' AND t2.appid <> ''  GROUP
                BY t2.coid , t2.appid , t2.egt, DATE_FORMAT(t2.time, '%Y-%m-%d')) t3 ON (t3.time = DATE_FORMAT(FROM_UNIXTIME(t.time), '%Y-%m-%d') AND
                t3.coid = co AND t3.appid = t.appid AND t3.egt = t.egt)  where $sele  ((t.time >= $time1) AND (t.time <=  $time2))  ORDER BY t.time limit $page->firstRow,$page->listRows";
        $info = $mm->query($sql);
      //  $sum_fee222 = $model2->where($selectwhere)->sum('msggold');                  //信息费之和
        $aaaa11=M()->_sql();
        $tablenamecodatauser =$dateformonth.'codata'; 
        $index = -1;
        $gamelist = M('gamelist')->field('id,appid,name')->select();
        $colist =M('colist')->field('coid,name')->select();
        $egtlistinfio= \PublicData::$egtlist;
        foreach($info as $k=> $value)
        {
            $index++;
            $date =date('Y-m-d',$value['time']);
            $info[$index]['time2'] = $date;
            foreach($egtlistinfio as $m=>$c){
                    if($info[$index]['egt']==$c['id']){
                           $info[$index]['egt']=$c['name'];
                    }
            }
            
            foreach($info1 as $k=>$v){
                     if($v['id']==$value['id']){
                           $info[$k]['newuser']=$value['newuser'];
                           $info[$k]['userstatus']=$value['userstatus'];
                           $info[$k]['id']=$value['id'];
                           $id=$info[$k]['id'];
                           $colistuser = M($tablenamecodatauser)->where(array('id'=>$id))->field('id,userstatus,newuser')->select();  
                           if($colistuser){
                               $userstatus=$colistuser[0]['userstatus'];
                               $newser=$colistuser[0]['newuser'];
                               $ljnewser['newuser']=$info[$k]['newuser'];
                               if($userstatus==0){
                                   $isok = M($tablenamecodatauser)->where(array('id'=>$v['id']))->data($ljnewser)->save();
                               }
                               $info[$index]['newuser']=$newser;
                           }
                          
                      }
                    $sdaa=M()->_sql();
            }
          
           // $info[$index]['egt'] = \PublicData::$egtlist[$info[$index]['egt']]['name'];
            $info[$index]['statusname'] = \PublicData::$openstatic[$info[$index]['status']]['name'];
            foreach($colist as $value3)
            {
                if($value['co'] == $value3['coid'])
                {
                    $info[$index]['co'] = $value3['name'];
                }           
            }
            foreach($gamelist as $value4){                
                if($value['appid'] == $value4['appid'])
                {
                    $info[$index]['appid'] = $value4['name'];
                } 
            
            }
            $sum_fee['msggold'] += $value['msggold'];    
            
            
        }
        foreach($info1 as $k=> $value){
        
            $sum_fee['msggold'] += $value['msggold'];    
        
        }
        
       foreach($info as $k=>$v){
                if(count($v)<10){
                    unset($info[$k]);
        
                }
       
       }
        
        $this->assign('page',$show);
        $this->assign('time1',$time1);
        $this->assign('data',$data);
        $this->assign('isold',$isold);
        $this->assign('time_start',$datatime1s);
        $this->assign('time_end',$datatime2s);
        $this->assign('sum_fee',$sum_fee);
        $this->assign('egtlist',$egtlist);
        $this->assign('statuslist',$statuslist);
        $this->assign('colist',$colist);
        $this->assign("list",$info);
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->display();
    }
    public function order()
    {
        //print_r(I('get.'));
        $time1 = '';
        $time2 = '';
        $time = time();
        //$this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        //$this->dateformonth =  $this->dateformonth.'2'.'_';

        if(isset($_GET['timestart'])){
            //查询开始时间
            $time1    =  (string)$_GET['timestart'].' 00:00:00';
            $time1 = strtotime($time1);
        }
        else
        {
            $t = time();
            //$t = $t - 60*60*24;
            $time1 = date("y-m-d",$t);
            $time1 = $time1.' 00:00:00'; 
            $time1 = strtotime($time1);
        }
        if(isset($_GET['timestart2'])){
            //查询结束时间
            $time2    =   (string)$_GET['timestart2'].' 23:59:59';
            $time2 = strtotime($time2);
        }
        else
        {
            $t = time();
            //$t = $t - 60*60*24;
            $time2 = date("Y-m-d",$t).' 23:59:59'; 
            $time2 = strtotime($time2);
        }
        $time = time();
        $data = array('status','orderstatus','Iskl','telecom','paycode');
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'id');
        //查询条件
        
        $selectwhere = $selectwhere_map[0];
        //模糊查询条件
        $map = $selectwhere_map[1];
        //生成Exec
        $data = array('status'=>$selectwhere['status'],'orderstatus'=>$selectwhere['orderstatus'],
            'Iskl'=>$selectwhere['Iskl'],'telecom'=>$selectwhere['telecom'],'paycode'=>$selectwhere['paycode'],'outlets'=>$selectwhere['outlets']);
        $this->assign('data',$data);
        $outletsinfo = array();
        $telecominfo = array();
        $keynames = array('telecom','outlets');

        $is_t_o_v = $this->check_auth_info($selectwhere,$outletsinfo,$telecominfo,$keynames);        
        $is_telecom_visible = $is_t_o_v[0];
        $is_outlets_visible = $is_t_o_v[1];
        $isbad_visible = $is_t_o_v[2];

        if($time1!='' && $time2!='')
        {
            $datatimes1 = (string)date("Y-m-d",$time1); 
            $datatimes2 =(string)date("Y-m-d",$time2);
            $datatime1 = explode('-',$datatimes1);
            $datatime2 = explode('-',$datatimes2);
            if($datatime1[1] != $datatime2[1])
            {
                $this->error('不允许跨月查询');
                exit();
            }
            $this->dateformonth ='';
            foreach($datatime1 as $k=>$value)
            {
                if($k != 2)
                {
                    $this->dateformonth = $this->dateformonth.$value.'_';
                    $this->dateformonth =  $this->dateformonth;
                }
            }
            $this->dateformonth =  $this->dateformonth.'2'.'_';
            $tablename = $this->dateformonth.'order';
            $model2 = M($tablename);
            if((int)$time1 > (int)$time2)
            {
                $this->error('操作失误');
            }
            $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));            
        }
        //根据对应的条件查询
        if($map == null)
        {
            $info   =   $this->lists($model2->where($selectwhere),$selectwhere);
        }
        else
        {
            $info   =   $this->lists($model2->where($map),$map);
        }
        //查询通道？
        $telecomtypeinfo = M('telecomtypelist')->select();
        //状态
        $statuslist = \PublicData::$vnstatus;
        $statuslist2 = \PublicData::$openstatic;
        //计费代码
        $paycodeinfo = \PublicData::$paygoldlist;
        //查询游戏
        $gamelist = M('gamelist')->field('appid,name')->select();
        
        //查询厂商
        $colist = M('colist')->field('coid,name')->select(); 
        //查询道具
        $proplist = M('proplist')->field('propid,name')->select(); 
        $index=-1;
        foreach($info as $k=>$value)
        {
            $index++;
            foreach($telecomtypeinfo as $value2)
            {
                if($value['type'] == $value2['id'])
                {
                    $info[$k]['type'] = $value2['name'];
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
            foreach ($paycodeinfo as $key => $valuez) {
                if ($value['paycode']==$valuez['id']) {
                    $info[$k]['paycode'] = $valuez['name'];
                }
            }
            foreach($telecominfo as $value4)
            {
                if($value['telecom'] == $value4['id'])
                {
                    $info[$k]['telecom'] = $value4['name'];
                }
            }
            $id = $value['status'];
            $info[$k]['status'] = $statuslist[$id]['name']; 
            //    bks_paycodelist
            $id = $value['Iskl'];
            $info[$k]['Iskl'] = $statuslist2[$id]['name'];
            $id = $value['orderstatus'];
            $info[$k]['orderstatus'] = $statuslist[$id]['name'];
            $info[$k]['time'] = date("Y-m-d H:i:s", $value['time']);
        }
        //print_r($data);
        $this->assign('is_outlets_visible',$is_outlets_visible);
        $this->assign('is_telecom_visible',$is_telecom_visible);
        $this->assign('isbad_visible',$isbad_visible);
        $this->assign("list",$info);
        $this->assign("telecom",$telecominfo);
        $this->assign("outlets",$outletsinfo);
        $this->assign("Iskl",$statuslist2);
        $this->assign("orderstatus",$statuslist);
        $this->assign("status",$statuslist);
        $this->assign("paycode",$paycodeinfo);
        $this->assign('time_start',$datatimes1);
        $this->assign('time_end',$datatimes2);
        //      $this->assign("list",$info);
        $this->display();
    }

    public function history($id = '')
    {
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $dmonth = $this->dateformonth =  $this->dateformonth.'2'.'_';

        if($id == '')
        {
            $time1 = '';
            $time2 = '';
            $selectwhere = array();
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
            $model2 = M( $this->dateformonth.'orderhistory');
            $data = array('status','logtype');
            $selectwhere_map = \PublicFunction::getClickSeachData($data,'oid');
            $selectwhere = $selectwhere_map[0];
            $map = $selectwhere_map[1];
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
                $model2 = M( $this->dateformonth.'orderhistory');

                if((int)$time1 > (int)$time2)
                {
                    $this->error('操作失误');
                }
                $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));            
            }
            if($map == null)
            {
                $info   =   $this->lists($model2->where($selectwhere),$selectwhere);
            }
            else
            {
                $info   =   $this->lists($model2->where($map),$map);
            }
        }
        else
        {
            $selectwhere = array('oid'=>$id);
            $info = M( $this->dateformonth.'orderhistory')->where($selectwhere)->order('id DESC')->select();
        }
        $telecomtypeinfo = M('telecomtypelist')->select();
        $statuslist = \PublicData::$status2;
        $index = -1;
        foreach($info as $value)
        {
            $index++;
            foreach($telecomtypeinfo as $value2)
            {
                if($value['logtype'] == $value2['id'])
                {
                    $info[$index]['logtype'] = $value2['name'];
                }
            }
            $id = $value['status'];
            $info[$index]['status'] = $statuslist[$id]['name']; 
            $info[$index]['time'] = date("Y-m-d H:i:s", $value['time']);
        }
        $this->assign("list",$info);
        $this->assign("logtype",$telecomtypeinfo);
        $this->assign("status",$statuslist);
        $this->assign('time_start',$datatime1s);
        $this->assign('time_end',$datatime2s);
        $this->display();
    }

    public function edit()
    {
        if(IS_POST)
        {
            $this->error("实验数据-你目前还不能对结算进行进行操作");
        }
        else
        {
            $this->display();
        }
    }
    
    //查询页面中的游戏明细的生成excel
    /*
    group
    isegt=1 运营商 
    &istelecom=1 通道
    &isday=1 天
    &ishour=1  时
    &iscity=1 城市
    &timestart2=2016-10-31  结束时间
    &timestart=2016-10-31 开始时间
    where
    &telecomname=15610 通道名称
    &egt=1 运营商
    */
    public function makecountexcel($telecomname ='',$egt='',$istelecom = '',$ishour = '',$iscity='',$isegt ='',$isday ='',$time_start = '',$time_end = '')
    {

        $d = I('get.');
        $group = '';
        $field = '';
        $order = '';
        $time1 = $time_start.' 00:00:00'; 
        $time1 = strtotime($time1);      
        $time2 = $time_end.' 23:59:59'; 
        $time2 = strtotime($time2);
        $time = $time1;
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth =  $this->dateformonth.'2'.'_';

        $tablename = $this->dateformonth.'ordercount';
        $model2 = M($tablename);     
        if( $istelecom == 1 || $ishour == 1 || $iscity == 1 || $isegt = 1 || $isday = 1)
        {
            $field = 'id';
            if($istelecom == 1)
            {
                if($group != '')
                $group = $group.',';
                $group = $group.'telecomname';
            }
            if($istelecom != 1 )
            {
                if($group != '')
                $group = $group.',';
                $group = $group.'telecomname';
            }
            if($iscity == 1)
            {
                if($group != '')
                $group = $group.',';
                $group = $group.'city';
            }
            if($ishour == 1)
            {
            if($group != '')
            $group = $group.',';
            $group = $group.'hour';
            }
            if($isegt == 1)
            {
            if($group != '')
            $group = $group.',';
            $group = $group.'egt';
            }

            if($isday == 1)
            {
            if($group != '')
            $group = $group.',';
            $group = $group.'day';
            }
        }
           if($iscity == 1)
        {
            $data[0]['city'] = "省市";
        }
          if($isday == 1)
        {
          $data[0]['day'] = "天";
        }
         if($ishour == 1)
        {
            $data[0]['hour'] = "小时";
        }

         if($istelecom == 1)
        {
            $data[0]['telecomname'] = '通道名称';
        }
        if($isegt == 1)
        {
            $data[0]['egt'] = '运营商';
        }

        $data[0]['msggoldl'] = '信息费';
        $data[0]['payresult'] = '请求数';
        $data[0]['xysuccess'] = '协议成功';
        $data[0]['xyf'] = '协议失败';
        $data[0]['paysuccess'] = '计费成功';
        $data[0]['xyl'] = '协议成功率';
        $data[0]['payl'] = '计费成功率';
        $data[0]['payf'] = '计费失败';
        $data[0]['mo'] = 'Mo成功数';
        $data[0]['mol'] = 'Mo成功率';
        $data[0]['sjcg'] = '实际成功数';
        $data[0]['msggold'] = '实际成交金额';
        $data[0]['sjl'] = '实际成功率';
        $data[0]['badvalue'] = 'bad(条)';
        $data[0]['badgold'] = 'bad(元)';
        $data[0]['badb']='bad(比)';
        //查询条件
        $seachwhere = array();
        $seachwhere['egt'] = I('get.egt');
        $seachwhere['telecomname'] = I('get.telecomname');
         foreach ($seachwhere as $k=> $value) {
            if ($seachwhere[$k]==='') {
                unset($seachwhere[$k]);
            }else{
                $arr .= ' and '.$k.'='.$value;
                }
            }
            //获取数据表
            $dateformonth = \PublicFunction::getTimeForYH($time1).'_';
            $dateformonth= $dateformonth.'2'.'_'; 
            $tablename =C('DB_PREFIX').$dateformonth.'ordercount'; 
     
            if ($arr){
                    $sql = "SELECT `city`,`day`,`hour`,`telecomname`,`egt`,SUM(msggold) msggold1,COUNT(payresult) AS payresult,SUM(xysuccess) AS xysuccess,
                    COUNT(payresult)-SUM(xysuccess) AS xyf,FORMAT(SUM(xysuccess)/COUNT(payresult),2) AS xyl,SUM(paysuccess) AS paysuccess,FORMAT(SUM(paysuccess)/SUM(xysuccess),2) AS payl,
                    FORMAT(COUNT(payresult)-SUM(paysuccess),2) AS payf,SUM(mosuccess) AS mo,FORMAT(SUM(mosuccess)/COUNT(payresult),2) AS  mol, FORMAT(SUM(paysuccess)-SUM(badvalue),2) AS sjcg,
                    SUM(msggold)-SUM(badgold) AS msggold,SUM(badvalue) AS badvalue,SUM(badgold) AS badgold,ifnull(format(SUM(paysuccess)-SUM(badvalue),2)/SUM(xysuccess),0) as sjl,SUM(badvalue)/SUM(paysuccess) as badb FROM $tablename WHERE 
                     (`time` >= $time1) AND (`time` <= $time2) $arr GROUP BY $group   ORDER BY id DESC";
            }else{
                     $sql = "SELECT `city`,`day`,`hour`,`telecomname`,`egt`,SUM(msggold) msggold1, COUNT(payresult) AS payresult,SUM(xysuccess) AS xysuccess,
                    COUNT(payresult)-SUM(xysuccess) AS xyf,FORMAT(SUM(xysuccess)/COUNT(payresult),2) AS xyl,SUM(paysuccess) AS paysuccess,FORMAT(SUM(paysuccess)/SUM(xysuccess),2) AS payl,
                    FORMAT(COUNT(payresult)-SUM(paysuccess),2) AS payf,SUM(mosuccess) AS mo,FORMAT(SUM(mosuccess)/COUNT(payresult),2) AS  mol, FORMAT(SUM(paysuccess)-SUM(badvalue),2) AS sjcg,
                    SUM(msggold)-SUM(badgold) AS msggold,SUM(badvalue) AS badvalue,SUM(badgold) AS badgold,ifnull(format(SUM(paysuccess)-SUM(badvalue),2)/SUM(xysuccess),0) as sjl,SUM(badvalue)/SUM(paysuccess) as badb FROM $tablename WHERE 
                     (`time` >= $time1) AND (`time` <= $time2) GROUP BY  $group   ORDER BY id DESC";
         
                 }
            $mod = M();
            $list = $mod->query($sql);
        $index = -1;
        $index2 = 0;
        //通道
        $telecominfo = M('telecomlist')->select();
        //城市
        $citylist = \PublicData::$city;
        foreach($list as $value)
        {
            $index++;
            $index2++;
        if($iscity == 1)
        {
           $data[$index2]['city'] = $value['city'];
        }
          if($isday == 1)
        {
        $data[$index2]['day'] = $value['day'];
        }
         if($ishour == 1)
        {
           $data[$index2]['hour'] = $value['hour'];
        }

         if($istelecom == 1)
        {
           $data[$index2]['telecomname'] = $value['telecomname'];
        }
        if($isegt == 1)
        {
           $data[$index2]['egt'] =$value['egt'];
        }
            $data[$index2]['msggoldl'] = $value['msggold1'];
            $data[$index2]['payresult'] =$value['payresult'];
            $data[$index2]['xysuccess'] = $value['xysuccess'];
            $data[$index2]['xyf'] = $value['xyf'];
            $data[$index2]['paysuccess'] = $value['paysuccess'];
            $data[$index2]['xyl'] = $value['xyl'];
            $data[$index2]['payl'] = $value['payl'];
            $data[$index2]['payf'] = $value['payf'];
            $data[$index2]['mo'] = $value['mo'];
            $data[$index2]['mol'] = $value['mol'];
            $data[$index2]['sjcg'] = $value['sjcg'];
            $data[$index2]['msggold'] = $value['msggold'];
            $data[$index2]['sjl'] = $value['sjl'];
            $data[$index2]['badvalue'] = $value['badvalue'];
            $data[$index2]['badgold'] = $value['badgold'];
            $data[$index2]['badb']=$value['badb'];
             //获取通道名
            if($istelecom == 1){
            $telename = M('telecomlist')->field('id,name')->select();
            $citylist = \PublicData::$city;
            foreach ($telename as $value2) {
                    if ($value['telecomname']==$value2['id']) {
                       $data[$index2]['telecomname'] =$value2['name']; 
                    }
                  
                } 
            }
            
                //获取运营商
            switch ($value['egt']) {
                    case$value['egt']=1:
                       $data[$index2]['egt'] = '电信';
                        break;
                    case $value['egt']=2:
                       $data[$index2]['egt'] = '联通';
                    break;
                    case $value['egt']=3:
                       $data[$index2]['egt'] = '移动';
                    break;
                }
                //获取城市名
            if($iscity == 1){
                  foreach ($citylist as $value3) {
                    if($value['city']==$value3['id']){
                       $data[$index2]['city']=$value3['city'];
                    }
                }
            }
          
                
        }
        
        phpExcel($data,$time_start.'至'.$time_end.'数据明细表');
    }
    public function makesettlementexcel($time = '',$time_start='',$time_end='',$oid=0)
    {
        $outletsinfo = M('outletslist')->select();
        $time1 = $time_start.' 00:00:00'; 
        $time1 = strtotime($time1);
        
        $time2 = $time_end.' 23:59:59'; 
        $time2 = strtotime($time2);
        $this->dateformonth = \PublicFunction::getTimeForYH($time1).'_';
        if($oid != 1)
        {
            $this->dateformonth =  $this->dateformonth.'2'.'_';
        }
        $day = \PublicFunction::getTimeForDay($time1);
        $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));            

        $tablename = $this->dateformonth.'codata';
        $info = M($tablename)->where($selectwhere)->select();
        
        $data[0] = array('day'=>'日期','co'=>'厂商','appid'=>'游戏','egt'=>'运营商','msggold'=>'信息费');
        $index = 0;
        $index2 = -1;
        foreach($info as $value)
        {
            $index++;
            $index2++;
            $value['time'] = date('Y-m-d',$value['time']);
            $info[$index2]['egt'] = \PublicData::$egtlist[$info[$index2]['egt']]['name'];
            $data[$index] = array('day'=>$value['day'],'co'=>$value['co'],'appid'=>$info[$index2]['appid'],'egt'=>$info[$index2]['egt'],'msggold'=>$value['msggold']);
        }
        phpExcel($data,$time_start.'至'.$time_end.'结算数据');
    }
   
    public function makeorderexcel($Iskl = '',$status='',$outlets='',$telecom='',$paycode='',$orderstatus='',$time_start='',$time_end='')
    {
        $time1 = '';
        $time2 = '';
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth =  $this->dateformonth.'2'.'_';

        $time1 = $time_start.' 00:00:00'; 
        $time1 = strtotime($time1);
        
        $time2 = $time_end.' 23:59:59'; 
        $time2 = strtotime($time2);
        $time = time();
        $outletsinfo = array();
        $this->auto_user($selectwhere,$outletsinfo,'outlets');
        if($Iskl != 'Array' and $Iskl != '')
        {
            $selectwhere['Iskl'] = $Iskl;
        }
        if($status != 'Array' and $status != '')
        {
            $selectwhere['status'] = $status;
        }
        if($telecom != 'Array' and $telecom != '')
        {
            $selectwhere['telecom'] = $telecom;
        }
        if($paycode != 'Array' and $paycode != '')
        {
            $selectwhere['paycode'] = $paycode;
        }
        if($orderstatus != 'Array' and $orderstatus != '')
        {
            $selectwhere['orderstatus'] = $orderstatus;
        }
        $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));            
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';        
        $this->dateformonth =  $this->dateformonth.'2'.'_';

        $tablename = $this->dateformonth.'order';
        $model2 = M($tablename);
        $info = $model2->where($selectwhere)->order('id desc')->select();
        $data[0] = array('id'=>'订单号','co'=>'厂商','cpgame'=>'游戏名','prop'=>'道具名','telecom'=>'通道名称','paycode'=>'计费代码','mobile'=>'用户识别码','extern'=>'透传参数','orderstatus'=>'订单状态'
,'status'=>'同步状态','value'=>'同步次数','Iskl'=>'是否bad','time'=>'时间');
        //$telecomtypeinfo = M('telecomtypelist')->select();
        $telecominfo = M('telecomlist')->select();
        $gamelist = M('gamelist')->select();
        $colist = M('colist')->select();
        $proplist = M('proplist')->select();
        $statuslist = \PublicData::$vnstatus;
        $statuslist2 = \PublicData::$openstatic;
        $index = -1;
        $index2 = 0;
        foreach($info as $value)
        {
            $index++;
            $index2++;
            $value['time'] = date("Y-m-d H:i:s", $value['time']);
            $data[$index2] = array('id'=>$value['id'],'co'=>$value['co'],'cpgame'=>$value['cpgame'],'prop'=>$value['prop'],'telecom'=>$value['telecom'],'paycode'=>$value['paycode'],'mobile'=>$value['mobile'],'extern'=>$value['extern'],'orderstatus'=>$value['orderstatus']
    ,'status'=>$value['status'],'value'=>$value['value'],'Iskl'=>$value['Iskl'],'time'=>$value['time']);
            foreach ($gamelist as $key => $value2) {
                if ($value['cpgame']==$value2['appid']) {
                    $data[$index2]['cpgame'] = $value2['name'];
                }
            }
            foreach ($colist as $key => $v) {
                if ($value['co'] == $v['coid']) {
                    $data[$index2]['co'] = $v['name'];
                }
            }
              foreach ($proplist as $key => $v1) {
                if ($value['prop'] == $v['propid']) {
                    $data[$index2]['prop'] = $v1['name'];
                }
            }

            foreach($outletsinfo as $value3)
            {
                if($value['outlets'] == $value3['id'])
                {
                    $data[$index2]['outlets'] = $value3['name'];
                }
            }
            foreach($telecominfo as $value4)
            {
                if($value['telecom'] == $value4['id'])
                {
                    $data[$index2]['telecom'] = $value4['name'];
                }
            }
            $id = $value['status'];
            $data[$index2]['status'] = $statuslist[$id]['name']; 
            //    bks_paycodelist
            $id = $value['Iskl'];
            $data[$index2]['Iskl'] = $statuslist2[$id]['name'];
            $id = $value['orderstatus'];
            $data[$index2]['orderstatus'] = $statuslist[$id]['name'];
        }
        phpExcel($data,$time_start.'至'.$time_end.'订单查询表');
    }

    public function makecountexcel22($isegt ='',$iscpgame='',$isco='',$isprop='',$ishour='',$isday='', $iscity='',$time_start='',$time_end='', $egt='',$coname = '',$cpgame = '')
    {

        $d = I('get.');
        $group = '';
        $field = '';
        $order = '';
        $time1 = $time_start.' 00:00:00'; 
        $time1 = strtotime($time1);      
        $time2 = $time_end.' 23:59:59'; 
        $time2 = strtotime($time2);
        $time = $time1;
        //获取数据表
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth =  $this->dateformonth.'2'.'_';
        $tablename = $this->dateformonth.'ordercount';
        $tablename1 =$this->dateformonth.'vnorder';
        $tableordercount = C('DB_PREFIX').$tablename;
        $tablevnorder =  C('DB_PREFIX').$tablename1;
        //判断分组条件   
        if( $iscpgame == 1 ||  $isco == 1 ||  $isprop == 1 || $ishour == 1 || $iscity == 1 || $isegt = 1 || $isday = 1)
        {
            if($iscpgame == 1)
            {
                if($group != '')
                $group = $group.',';
                $group = $group.'t.cpgame';
            }
              if($isco == 1 )
            {
                if($group != '')
                $group = $group.',';
                $group = $group.'t.co';
            }
            if($isprop == 1 )
            {
                if($group != '')
                $group = $group.',';
                $group = $group.'t.prop';
            }
            if($iscity == 1)
            {
                if($group != '')
                $group = $group.',';
                $group = $group.'t.city';
            }
            if($ishour == 1)
            {
            if($group != '')
            $group = $group.',';
            $group = $group.'t.hour';
            }
            if($isegt == 1)
            {
            if($group != '')
            $group = $group.',';
            $group = $group.'t.egt';
            }

            if($isday == 1)
            {
            if($group != '')
            $group = $group.',';
            $group = $group.'t.day';
            }
        }  

        //excel表头     
        if($iscity == 1)
        {
            $data[0]['city'] = "城市";
        }
          if($isday == 1)
        {
          $data[0]['day'] = "天";
        }
         if($ishour == 1)
        {
            $data[0]['hour'] = "小时";
        }

         if($isco == 1)
        {
            $data[0]['name1'] = '厂商';
        }
          if($iscpgame == 1)
        {
            $data[0]['name2'] = '游戏名';
        }
          if($isprop == 1)
        {
            $data[0]['name3'] = '道具';
        }
        if($isegt == 1)
        {
            $data[0]['egt2'] = '运营商';
        }
        $data[0]['msggold'] = '成交金额';
        $data[0]['payresult'] = '计费请求数';
        $data[0]['xysuccess'] = '协议成功';
        //
        $data[0]['xyf'] = '协议失败';
        //
        $data[0]['paysuccess'] = '付费次数';
        $data[0]['hyuser'] = '活跃用户数';
        $data[0]['newuser'] = '新增用户数';
        $data[0]['daysaleuser'] = '付费用户数';
        $data[0]['arpu'] = 'ARPU';
        $data[0]['arppu'] = 'ARPPU';
       
        //查询条件
        $seachwhere = array();
        $seachwhere['t.egt'] = I('get.egt');
        $seachwhere['t.cpgame'] = I('get.cpgame');
        $seachwhere['t.prop'] = I('get.coname'); 
        foreach ($seachwhere as $k=> $value) {
            if ($seachwhere[$k]==='') {
                unset($seachwhere[$k]);
            }else{
                $arr .= ' and '.$k.'='.$value;
                }
            }

        //查询
        if (empty($arr)) {
                $sql = "SELECT c.iccid_city, t.co, t.hour, t4.name AS name1, t.cpgame, t5.name AS name2, t.prop, 
                        t6.name AS name3, CASE t.egt WHEN 1 THEN '电信' WHEN 2 THEN '联通' WHEN 3 THEN '移动' END AS egt2,
                        t.time, t.day, SUM(t.msggold-t.badgold) msggold, COUNT(t.payresult) payresult, SUM(t.xysuccess) xysuccess, 
                        SUM(t.paysuccess-t.badvalue) paysuccess, SUM(t.badvalue) badvalue, SUM(t.badgold) badgold, sum(b.daynewuser) as hyuser,
                        sum(b.newuser) as newuser,sum(b.daysaleuser) as daysaleuser,ifnull(FORMAT(SUM(t.msggold-t.badgold)/sum(b.daynewuser), 2),0) AS arpu,
                        ifnull(FORMAT(SUM(t.msggold-t.badgold) / SUM(b.daysaleuser), 2),0) AS arppu FROM $tableordercount t LEFT JOIN bks_colist t4 
                        ON (t.co = t4.coid) LEFT JOIN bks_gamelist t5 ON (t.cpgame = t5.appid) LEFT JOIN bks_proplist t6 ON (t.prop = t6.propid) 
                        left join bks_usercount b ON (b.bksid = t.bksid) left join  $tablevnorder c on (c.bksid = t.bksid) 
                        WHERE ((t.time >= $time1) AND (t.time <=  $time2)) GROUP BY  $group ORDER BY t.msggold DESC";
                }else{
                $sql = "SELECT c.iccid_city, t.co, t.hour, t4.name AS name1, t.cpgame, t5.name AS name2, t.prop, 
                    t6.name AS name3, CASE t.egt WHEN 1 THEN '电信' WHEN 2 THEN '联通' WHEN 3 THEN '移动' END AS egt2,
                    t.time, t.day, SUM(t.msggold-t.badgold) msggold, COUNT(t.payresult) payresult, SUM(t.xysuccess) xysuccess, 
                    SUM(t.paysuccess-t.badvalue) paysuccess, SUM(t.badvalue) badvalue, SUM(t.badgold) badgold, sum(b.daynewuser) as hyuser,
                    sum(b.newuser) as newuser,sum(b.daysaleuser) as daysaleuser,ifnull(FORMAT(SUM(t.msggold-t.badgold)/sum(b.daynewuser), 2),0) AS arpu,
                    ifnull(FORMAT(SUM(t.msggold-t.badgold) / SUM(b.daysaleuser), 2),0) AS arppu FROM $tableordercount t LEFT JOIN bks_colist t4 
                    ON (t.co = t4.coid) LEFT JOIN bks_gamelist t5 ON (t.cpgame = t5.appid) LEFT JOIN bks_proplist t6 ON (t.prop = t6.propid) 
                    left join bks_usercount b ON (b.bksid = t.bksid) left join  $tablevnorder c on (c.bksid = t.bksid) 
                    WHERE ((t.time >= $time1) AND (t.time <=  $time2)) $arr GROUP BY $group ORDER BY t.msggold DESC";
                   }
        $m = M();
        $list = $m->query($sql);
       
      
        $index = -1;
        $index2 = 0;
        foreach($list as $value)
        {
            $index++;
            $index2++;
        if($iscity == 1)
        {
           $data[$index2]['city'] = $value['iccid_city'];
        }
          if($isday == 1)
        {
        $data[$index2]['day'] = $value['day'];
        }
         if($ishour == 1)
        {
           $data[$index2]['hour'] = $value['hour'];
        }

         if($isco == 1)
        {
           $data[$index2]['name1'] = $value['name1'];
        }
        if($iscpgame == 1)
        {
           $data[$index2]['name2'] =$value['name2'];
        }
          if($isprop == 1)
        {
           $data[$index2]['name3'] =$value['name3'];
        }
          if($isegt == 1)
        {
           $data[$index2]['egt2'] =$value['egt2'];
        }
        $data[$index2]['msggold'] = $value['msggold'];
        $data[$index2]['payresult'] =$value['payresult'];;
        $data[$index2]['xysuccess'] = $value['xysuccess'];
        $data[$index2]['xyf'] = $value['payresult']-$value['xysuccess'];
        $data[$index2]['paysuccess'] = $value['paysuccess'];
        $data[$index2]['hyuser'] = $value['hyuser'];
        $data[$index2]['newuser'] = $value['newuser'];
        $data[$index2]['daysaleuser'] = $value['daysaleuser'];
        $data[$index2]['arpu'] = $value['arpu'];
        $data[$index2]['arppu'] = $value['arppu'];  
        }
        phpExcel($data,$time_start.'至'.$time_end.'数据明细表');
    }


}
