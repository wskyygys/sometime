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
            
            //foreach($outletsinfo as $value3)
            //{
            //    if($value['outletname'] == $value3['id'])
            //    {
            //        $info[$k]['outletname'] = $value3['name'];
            //    }
            //}
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
    
    //通道明细
    public function tetlecomdetail()
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
        $data = array('isprop','ishour','isday','paycode','telecomname','cpgame','isday','egt'); //isprop: 道具  ishour：小时 isday：天
        
        
        

    }
    
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
            
            //foreach($outletsinfo as $value3)
            //{
            //    if($value['outletname'] == $value3['id'])
            //    {
            //        $info[$k]['outletname'] = $value3['name'];
            //    }
            //}
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
            $sum_order = $model2->where($selectwhere)->sum('result');
            $sum_success_order = $model2->where($selectwhere)->sum('resultsuccess');
        }
        else
        {
            $info   =   $this->lists2($model2->where($map),$map,$order,$field,$group);
            $sum_order = $model2->where($selectwhere)->sum('result');
            $sum_success_order = $model2->where($selectwhere)->sum('resultsuccess');
        }
        $data = array('sum_order'=>$sum_order,'sum_success_order'=>$sum_success_order);
        $this->assign('data',$data);
        $this->assign('time_start',$datatimes1);
        $this->assign('time_end',$datatimes2);
        $outletsinfo = M('outletslist')->select();
        $telecominfo = M('telecomlist')->select();
        $paycodelist = \PublicData::$paygoldlist;
        
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
        $outletsinfo = array();
        $this->auto_user($selectwhere,$outletsinfo,'outletname');
        $this->assign('outletsinfo',$outletsinfo);
        $this->assign("list",$info);
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
            $time = $data['time'];
            $isold = $data['isold'];
            $this->dateformonth =   \PublicFunction::getTimeForYH($time).'_'.'2'.'_';
            if($isold == 1)
                $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
            

            $tablename = $this->dateformonth.'codata';
            $model = M($tablename);
            $info = $model->where($selectwhere)->select();
            $info = $info[0];
            $outletsinfo = M('outletslist')->select();
            $info['time'] = date("Y-m-d",$info['time']);
            $egtlist = \PublicData::$egtlist;
            $statuslist = \PublicData::$openstatic;
            $this->assign('outletsinfo',$outletsinfo);
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
            $selectwhere = array('outlets'=>$data['outlets'],'day'=>$data['day'],'egt'=>$data['egt']);
            $this->dateformonth ='';
            foreach($date as $k=>$value)
            {
                if($k != 2)
                {
                    $this->dateformonth =$this->dateformonth.$value.'_';
                }
            }
            if($isold == 1)
            {
                $this->dateformonth =$this->dateformonth.'2'.'_';
            }
            $tablename = $this->dateformonth.'codata';
            $isok = M($tablename)->where($selectwhere)->select();
            if($isok == null)
            {
               $isok =  M($tablename)->data($data)->add();
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
            $outletsinfo = M('outletslist')->select();
            $egtlist = \PublicData::$egtlist;
            $statuslist = \PublicData::$openstatic;
            $this->assign('outletsinfo',$outletsinfo);
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
               $group = 'outletname,egt';
               $order = 'msggold desc';
               $field = 'id,outletname,time';
               $field = $field.',day,egt,sum(msggold) msggold,sum(badgold) badgold ';
               $info = $model2->order($order)->where($selectwhere)->field($field)->group($group)->select();
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
                   $data['outlets'] = $value['outletname'];
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
        //        $info = M($tablename)->where(array("id"=>$data['id']))->select();

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
            $time = $data['time'];
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
    public function settlement()
    {
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth =  $this->dateformonth.'2'.'_';
        $time1 = '';
        $time2 = '';
        $outletsinfo = M('outletslist')->select();
        $egtlist = \PublicData::$egtlist;
        $statuslist = \PublicData::$openstatic;
        if(isset($_GET['timestart'])){
            $time1    =  (string)$_GET['timestart'].' 00:00:00';
            $time1 = strtotime($time1);
        }
        else
        {
            $t = time();
            $t = $t - 60*60*24;
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
            $t = $t - 60*60*24;
            $time2 = date("Y-m-d",$t).' 23:59:59'; 
            $time2 = strtotime($time2);
        }
        $isold = $_GET['isold'];
        $data = array('outlets','status','egt');
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'name');
        $selectwhere = $selectwhere_map[0];
        $map = $selectwhere_map[1];
        $outletsinfo = array();
        $this->auto_user($selectwhere,$outletsinfo,'outlets');
        $data = array('outlets'=>$selectwhere['outlets'],'status'=>$selectwhere['status'],'egt'=>$selectwhere['egt'],'isold'=>$isold);
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
        if($map == null)
        {
            $info   =   $this->lists($model2->where($selectwhere),$selectwhere,'outlets desc');
            $sum_fee = $model2->where($selectwhere)->sum('msggold');
        }
        else
        {
            $info   =   $this->lists($model2->where($map),$map,'outlets desc');
            $sum_fee = $model2->where($selectwhere)->sum('msggold');
        }
        $index = -1;
      //  $outletsinfo = M('outletslist')->select();

        foreach($info as $value)
        {
            $index++;
            $date =date('Y-m-d',$value['time']);
            $info[$index]['time2'] = $date;
            $info[$index]['egt'] = \PublicData::$egtlist[$info[$index]['egt']]['name'];
            $info[$index]['statusname'] = \PublicData::$openstatic[$info[$index]['status']]['name'];
            foreach($outletsinfo as $value3)
            {
                if($value['outlets'] == $value3['id'])
                {
                    $info[$index]['outlets'] = $value3['name'];
                }
            }
        }        
        $this->assign('time1',$time1);
        $this->assign('data',$data);
        $this->assign('isold',$isold);

        $this->assign('time_start',$datatime1s);
        $this->assign('time_end',$datatime2s);
        $this->assign('sum_fee',$sum_fee);
        $this->assign('egtlist',$egtlist);
        $this->assign('statuslist',$statuslist);
        $this->assign('outletname',$outletsinfo);
        $this->assign("list",$info);
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->display();
    }
    public function order()
    {
        $time1 = '';
        $time2 = '';
        $time = time();
        //$this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        //$this->dateformonth =  $this->dateformonth.'2'.'_';

        if(isset($_GET['timestart'])){
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
        //$this->dateformonth = \PublicFunction::getTimeForYH($time).'_';        
        //$this->dateformonth =  $this->dateformonth.'2'.'_';

        //$tablename = $this->dateformonth.'order';
        //$model2 = M($tablename);
        $data = array('status','orderstatus','Iskl','telecom','paycode');
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'utf8name');
        $selectwhere = $selectwhere_map[0];
        $map = $selectwhere_map[1];
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
        if($map == null)
        {
            $info   =   $this->lists($model2->where($selectwhere),$selectwhere);
        }
        else
        {
            $info   =   $this->lists($model2->where($map),$map);
        }
        $telecomtypeinfo = M('telecomtypelist')->select();
        $statuslist = \PublicData::$vnstatus;
        $statuslist2 = \PublicData::$openstatic;
        $paycodeinfo = \PublicData::$paygoldlist;
        $gamelist = M('gamelist')->field('id,name')->select(); 
        $colist = M('colist')->field('id,name')->select(); 
        $proplist = M('proplist')->field('id,name')->select(); 
        foreach($info as $k=>$value)
        {
            $k++;
            foreach($telecomtypeinfo as $value2)
            {
                if($value['type'] == $value2['id'])
                {
                    $info[$k]['type'] = $value2['name'];
                }
            }
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
        $this->assign('is_outlets_visible',$is_outlets_visible);
        $this->assign('is_telecom_visible',$is_telecom_visible);
        ///
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
    //public function outlets()
    //{
    //    $time = time();
    //    $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
    //    $time1 = '';
    //    $time2 = '';
    //    if(isset($_GET['timestart'])){
    //        $time1    =  (string)$_GET['timestart'].' 00:00:00';
    //        $time1 = strtotime($time1);
    //    }
    //    else
    //    {
    //        $time1    =  (string)$_GET['timestart'].' 00:00:00';
    //        $t = time();
    //        $time1 = date("Y-m-d",$t);
    //        $this->assign('time_start',$time1);
    //        $time1 = $time1.' 00:00:00'; 
    //        $time1 = strtotime($time1);
    //    }
    //    if(isset($_GET['timestart2'])){
            
    //        $time2    =   (string)$_GET['timestart2'].' 23:59:59';
    //        $time2 = strtotime($time2);
    //    }
    //    else
    //    {
    //        $time2    =   (string)$_GET['timestart2'].' 23:59:59';
    //        $time2 = strtotime($time2);
    //        $t = time();
    //        $time2 = date("Y-m-d",$t).' 23:59:59'; 
    //        I('timestart2',$time1);
    //        $time2 = strtotime($time2);

    //    }
    //    $model2 = M($this->dateformonth.'orderoutlets');
    //   $data = array('outlets','outletsname','ishour','isday','iscode','paycode','telecomname');
    //   $selectwhere_map = \PublicFunction::getClickSeachData($data,'utf8name');
    //   $selectwhere = $selectwhere_map[0];
    //   $map = $selectwhere_map[1];
    //    if($time1!='' && $time2!='')
    //    {
    //        $datatime1 = (string)date("Y-m-d",$time1); 
    //        $datatime2 =(string)date("Y-m-d",$time2);
    //        $datatime1 = explode('-',$datatime1);
    //        $datatime2 = explode('-',$datatime2);
    //        if($datatime1[1] != $datatime2[1])
    //        {
    //            $this->error('不允许跨月查询');
    //        }
    //        $this->dateformonth ='';
    //        foreach($datatime1 as $k=>$value)
    //        {
    //            if($k != 2)
    //            $this->dateformonth = $this->dateformonth.$value.'_';
    //        }
    // //       $tablename = $this->dateformonth.'orderoutletsforday';
    //        $model2 = M($this->dateformonth.'orderoutletsforday');

    //        if((int)$time1 > (int)$time2)
    //        {
    //            $this->error('操作失误');
    //        }
    //        $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));            
    //    }
    //    if($map == null)
    //    {
    //        $info   =   $this->lists($model2->where($selectwhere),$selectwhere,'msggold desc');
    //        $sum_order = $model2->where($selectwhere)->sum('payresult');
    //        $sum_fee = $model2->where($selectwhere)->sum('msggold');
    //        $sum_success_order = $model2->where($selectwhere)->sum('paysuccess');

    //    }
    //    else
    //    {
    //        $info   =   $this->lists($model2->where($map),$map,'msggold desc');
    //        $sum_order = $model2->where($map)->sum('payresult');
    //        $sum_fee = $model2->where($map)->sum('msggold');
    //        $sum_success_order = $model2->where($map)->sum('paysuccess');
    //    }
    //    $data = array('sum_order'=>$sum_order,'sum_fee'=>$sum_fee,'sum_success_order'=>$sum_success_order,'isday'=>$selectwhere['isday']
    //        ,'ishour'=>$selectwhere['ishour'],'isoutlets'=>$selectwhere['isoutlets'],'iscode'=>$selectwhere['iscode']);
    //    $this->assign('data',$data);
    //    $this->assign('time_start',(string)$_GET['timestart']);
    //    $this->assign('time_end',(string)$_GET['timestart2']);
    //    $outletsinfo = M('outletslist')->select();
    //    $telecominfo = M('telecomlist')->select();
    //    $paycodeinfo = \PublicData::$paygoldlist;
        
    //    $index = -1;
    //    foreach($info as $value)
    //    {
    //        $index++;
    //        foreach($outletsinfo as $value3)
    //        {
    //            if($value['outlets'] == $value3['id'])
    //            {
    //                $info[$index]['outlets'] = $value3['name'];
    //            }
    //        }
    //        $info[$index]['time'] = date("Y-m-d H:i:s", $value['time']);

    //    }
    //    $this->assign("outletsname",$outletsinfo);
    //    $this->assign("telecomname",$telecominfo);
    //    $this->assign("paycode",$paycodeinfo);
    //    $this->assign("list",$info);
    //    $this->display();
    //}
    public function history($id = '')
    {
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth =  $this->dateformonth.'2'.'_';

        if($id == '')
        {
            $time1 = '';
            $time2 = '';
            $selectwhere = array();
            if(isset($_GET['timestart'])){
                $time1    =  (string)$_GET['timestart'].' 00:00:00';
                $time1 = strtotime($time1);
            }
            if(isset($_GET['timestart2'])){
                $time2    =   (string)$_GET['timestart2'].' 23:59:59';
                $time2 = strtotime($time2);
            }
            $model2 = M( $this->dateformonth.'orderhistory');
            $data = array('status','logtype');
            $selectwhere_map = \PublicFunction::getClickSeachData($data,'oid');
            $selectwhere = $selectwhere_map[0];
            $map = $selectwhere_map[1];
            if($time1!='' && $time2!='')
            {
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
                    $this->dateformonth = $this->dateformonth.$value.'_';
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
    public function makecountexcel($iscpgame = 0,$istelecom = 0,$ishour = 0,$iscity=0,$isegt = 0,$ischilddata = 0,$time_start = '',$time_end = '')
    {
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
        if($iscpgame == 1 || $istelecom == 1 || $ishour == 1 || $iscity == 1 || $isegt = 1)
        {
            $field = 'id';
            if($iscpgame == 1)
            {
                $group = $group.'outletname';
                $order = 'msggold desc';
                $field = $field.',outletname';
            }
            if($iscpgame != 1 && $_GET['outletname']!= null)
            {
                $group = $group.'outletname';
                $order = 'msggold desc';
                $field = $field.',outletname';
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
            $iscpgame = 1;
            $group = $group.'outletname';
            $order = 'msggold desc';
            $field = $field.'id,outletname,time';
            $field = $field.',day,sum(payresult) payresult,sum(xysuccess) xysuccess,sum(paysuccess)paysuccess,sum(msggold) msggold, sum(badvalue) badvalue,sum(badgold) badgold ';
        }
        $data[0] = array('time'=>'日期','day'=>'天','msggold'=>'信息费','payresult'=>'计费请求','xysuccess'=>'协议成功','xyfaild'=>'协议失败'
,'paysuccess'=>'计费成功','payfaild'=>'计费失败','success'=>'成功率%','outletssuccess'=>'渠道成功率','outletsuccess'=>'渠道成功条'
,'outletsmsggoldsuccess'=>'渠道信息费','badvalue'=>'bad(条)','badgold'=>'bad(元)','bads'=>'bad(比)');
        if($iscpgame == 1)
        {
            $data[0]['outletname'] = '渠道名称';
        }
        if($istelecom == 1)
        {
            $data[0]['telecomname'] = '通道名称';
        }
        if($isegt == 1)
        {
            $data[0]['egt'] = '运营商';
        }
        if($iscity == 1)
        {
            $data[0]['city'] = "省市";
        }
        if($ishour == 1)
        {
            $data[0]['hour'] = "小时";
        }
        if($ischilddata == 1)
        {
            $this->error('明细表不支持导出！！！');
            exit();
        }
        $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));            
        $info = $model2->field($field)->order($order)->where($selectwhere)->group($group)->select();
        $index = -1;
        $index2 = 0;
        $outletsinfo = M('outletslist')->select();
        $telecominfo = M('telecomlist')->select();
        $citylist = \PublicData::$city;
        foreach($info as $value)
        {
            $index++;
            $index2++;
            $value['xyfaild']= $value['payresult'] - $value['xysuccess'];
            $value['payfaild']= $value['xysuccess'] - $value['paysuccess'];
            $paysuccess = $value['paysuccess'];
            $payresult = $value['xysuccess'];
            $success = $paysuccess/$payresult;
            $success = $success *100;
            $success = substr($success,0,5);
            $value['success']= $success;
            $outletspaysuccess = $paysuccess -  $value['badvalue'];
            $success = $outletspaysuccess/$payresult;
            $success = $success *100;
            $success = substr($success,0,5);
            $value['outletssuccess']= $success;
            $outletsmsggoldsuccess = $value['msggold'] - $value['badgold'];
            $value['outletsmsggoldsuccess'] = $outletsmsggoldsuccess;
            $paysuccess = $paysuccess;
            $badvalue = $value['badvalue'];
            $bads = $badvalue/$paysuccess;
            $bads = $bads *100;
            $bads = substr($bads,0,5);
            $value['bads'] = $bads;
            $outletsuccess = $outletspaysuccess;

            $value['outletsuccess'] = $outletsuccess;
            $data[$index2] = array('time'=>$value['time'],'day'=>$value['day'],'msggold'=>$value['msggold'],'payresult'=>$value['payresult'],'xysuccess'=>$value['xysuccess']
,'xyfaild'=>$value['xyfaild'],'paysuccess'=>$value['paysuccess'],'payfaild'=>$value['payfaild'],'success'=>$value['success'],'outletssuccess'=>$value['outletssuccess']
,'outletsuccess'=>$value['outletsuccess'],'outletsmsggoldsuccess'=>$value['outletsmsggoldsuccess'],'badvalue'=>$value['badvalue']
,'badgold'=>$value['badgold'],'bads'=>$value['bads']);

            if($iscpgame == 1)
            {
                foreach($outletsinfo as $value3)
                {
                    if($value['outletname'] == $value3['id'])
                    {
                        $value['outletname'] = $value3['name'];
                    }
                }
                $data[$index2]['outletname'] = $value['outletname'];
            }
            if($istelecom == 1)
            {
                foreach($telecominfo as $value4)
                {
                    if($value['telecomname'] == $value4['id'])
                    {
                        $value['telecomname'] = $value4['name'];
                    }
                }
                $data[$index2]['telecomname'] = $value['telecomname'];
            }
            if($isegt == 1)
            {
                $value['egt'] = \PublicData::$egtlist[$info[$index]['egt']]['name'];
                $data[$index2]['egt'] = $value['egt'];
            }
            if($iscity == 1)
            {
                $cityid = $info[$index]['city'];
                if($cityid == 50)
                    $value['city']='其他';
                else
                {
                    foreach($citylist as $value5)
                    {
                        $id = $value5['id'];
                        if((int)$cityid == $id)
                        {
                            $value['city'] = $value5['city'];
                        }
                    }
                }
                $data[$index2]['city'] = $value['city'];
            }
            if($ishour == 1)
            {
                $data[$index2]['hour'] = $value['hour'];
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
        //$this->dateformonth =  $this->dateformonth.'2'.'_';
        if($oid != 1)
        {
            $this->dateformonth =  $this->dateformonth.'2'.'_';
        }
        $day = \PublicFunction::getTimeForDay($time1);
        $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));            

        $tablename = $this->dateformonth.'codata';
        $info = M($tablename)->where($selectwhere)->select();
        
        $data[0] = array('time'=>'日期','day'=>'天','outlets'=>'渠道','egt'=>'运营商','msggold'=>'信息费');
        $index = 0;
        $index2 = -1;
        foreach($info as $value)
        {
            $index++;
            $index2++;
            $value['time'] = date('Y-m-d',$value['time']);
            $info[$index2]['egt'] = \PublicData::$egtlist[$info[$index2]['egt']]['name'];
        //    $info[$index]['statusname'] = \PublicData::$openstatic[$info[$index]['status']]['name'];
            foreach($outletsinfo as $value3)
            {
                if($value['outlets'] == $value3['id'])
                {
                    $info[$index2]['outlets'] = $value3['name'];
                }
            }
            $data[$index] = array('time'=>$value['time'],'day'=>$value['day'],'outlets'=>$info[$index2]['outlets'],'egt'=>$info[$index2]['egt'],'msggold'=>$value['msggold']);
        }
        phpExcel($data,$time_start.'至'.$time_end.'结算数据');
    }
   // <a class="btn" href="{:U('makeorderexcel?Iskl='.$data['Iskl'].'&status='.$data['status'].'&outlets='.$data['outlets'].'&telecom='.$data['telecom'].'&paycode='.$data['paycode'].'&orderstatus='.$data['orderstatus'].'&time_start='.$time_start.'&time_end='.$time_end)}">生成excel</a>

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
        ////var_dump($Iskl);
        ////var_dump($status);
        ////var_dump($outlets);
        ////var_dump($telecom);
        ////var_dump($paycode);
        ////var_dump($orderstatus);
        ////var_dump($time_start);
        ////var_dump($time_end);
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
        if($outlets != 'Array' and $outlets != '')
        {
            $selectwhere['outlets'] = $outlets;
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
        $info = $model2->where($selectwhere)->select();
        $data[0] = array('id'=>'订单号','outlets'=>'渠道','telecom'=>'通道','paycode'=>'计费额','mobile'=>'用户识别码','extern'=>'透传参数','orderstatus'=>'订单状态'
,'status'=>'同步状态','value'=>'同步次数','Iskl'=>'是否扣量','time'=>'时间');
        //$telecomtypeinfo = M('telecomtypelist')->select();
        $telecominfo = M('telecomlist')->select();
        $statuslist = \PublicData::$vnstatus;
        $statuslist2 = \PublicData::$openstatic;

        $index = -1;
        $index2 = 0;
        foreach($info as $value)
        {
            $index++;
            $index2++;
            $value['time'] = date("Y-m-d H:i:s", $value['time']);

            $data[$index2] = array('id'=>$value['id'],'outlets'=>$value['outlets'],'telecom'=>$value['telecom'],'paycode'=>$value['paycode'],'mobile'=>$value['mobile'],'extern'=>$value['extern'],'orderstatus'=>$value['orderstatus']
    ,'status'=>$value['status'],'value'=>$value['value'],'Iskl'=>$value['Iskl'],'time'=>$value['time']);

            //foreach($telecomtypeinfo as $value2)
            //{
            //    if($value['type'] == $value2['id'])
            //    {
            //        $data[$index2]['type'] = $value2['name'];
            //    }
            //}
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
        //$auth_user = M('auth_user')->where(array('uid'=>UID))->select();
        //$isbad_visible = $auth_user[0]['isbad_visible'];
        //$this->assign('isbad_visible',$isbad_visible);
        //$this->assign("list",$info);
        //$this->assign("telecom",$telecominfo);
        //$this->assign("outlets",$outletsinfo);
        //$this->assign("Iskl",$statuslist2);
        //$this->assign("orderstatus",$statuslist);
        //$this->assign("status",$statuslist);
        //$this->assign("paycode",$paycodeinfo);
        //$this->assign('time_start',$datatimes1);
        //$this->assign('time_end',$datatimes2);
        

        //      $this->assign("list",$info);
        phpExcel($data,$time_start.'至'.$time_end.'订单查询表');

   //     $this->display();
    }
}
