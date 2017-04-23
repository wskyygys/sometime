<?php
namespace Admin\Controller;
include_once "PublicData.php";
include_once"PublicFunction.php";
include_once"ModelNameList.php";
include_once'JSHttpRequest.php';

/**
 * Class1 short summary.
 *
 * Class1 description.
 *
 * @version 1.0
 * @author pyz
 */
class CanvasController extends AdminController
{
  

    
    //通道图表
    public function indextime(){
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth = $this->dateformonth.'2'.'_';         
        $tablename =$this->dateformonth.'ordercount';
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
        $data = array('ishour','isday','telecomname','istelecom','egt','isegt'); 
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'utf8name'); 
        $selectwhere=$selectwhere_map['0'];         //条件为勾选框的条件  //将$_get=1 的值 赋值给 如: $selectwhere['telecomname']=1
        $map=$selectwhere_map['1'];             //条件为 输出口的条件
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
        $telecominfo =array();
        $outletsinfo = array();
        $keynames = array('telecomname','colist');
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
         //   $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));   
        } 
        $group = '';
        $field = '';
        $order = '';
        //if($istelecom == 1 || $ishour == 1 || $iscity == 1 ||  $_GET['telecomname'] != null || $isday != null || $isegt != null){
        //    if($istelecom == 1)
        //    {
        //        $field = 'id';
        //        if($group != '')
        //            $group = $group.',';
        //              $group = $group.'t.telecomname';
        //              $field = $field.',telecomname';
                
        //    }
        //    if($istelecom != 1 && $_GET['telecomname']!= null)
        //    {
        //        if($group != '')
        //            $group = $group.',';
        //        $group = $group.'t.telecomname';
        //        $field = $field.',t.telecomname';
        //    }
        //    if($ishour == 1)
        //    {
        //        if($group != '')
        //            $group = $group.',';
        //        $group = $group.'t.hour';
        //        $field = $field.',t.hour';
        //    }
            
        //    if($iscity == 1)
        //    {
        //        if($group != '')
        //            $group = $group.',';
        //        $group = $group.'t.city';
        //        $field = $field.',t.city';
        //    }
        //    if($isday == 1)
        //    {
        //        if($group != '')
        //            $group = $group.',';
        //        $group = $group.'t.day';
        //        $field = $field.',t.day';
        //    }
        //    if($isegt == 1)
        //    {
        //        if($group != '')
        //            $group = $group.',';
        //        $group = $group.'t.egt';
        //        $field = $field.',t.egt,t.hour,t.telecomname,ceil(ifnull(format(SUM(t.paysuccess)/SUM(t.xysuccess),2),0))+2 as payl,sum(t.payresult) as payresult,SUM(t.xysuccess)+2 as xysuccess';
        //    }
        
        //}else{
        //    if($is_outlets_visible == 1)
        //    {
        //        $istelecom = 1;
        //        $group = $group.'telecomname';
        //        $field = $field.'id,t.telecomname';
        //        $field = $field.',t.hour,t.telecomname,ceil(ifnull(format(SUM(t.paysuccess)/SUM(t.xysuccess),2),0))+2 as payl,sum(t.payresult) as payresult,SUM(t.xysuccess)+2 as xysuccess';
        //    }
        
        //}
        $selectwhere['day']=$datatime2[2];
        $selectwhere['status']=1;
        $datainfo= M($tablename.' t')->where($selectwhere)->field('t.hour,t.telecomname,ceil(ifnull(format(SUM(t.paysuccess)/SUM(t.xysuccess),2),0))+2 as payl,sum(t.payresult) as payresult,SUM(t.xysuccess)+2 as xysuccess')->GROUP('t.telecomname,t.hour,t.day,t.egt')->select();
      // print_r(M()->_sql());
        // $datainfo= M($tablename.' t')->where($selectwhere)->field($field)->GROUP($group)->select();
        foreach($datainfo as $k=>$v){
                $datainfo[$k]['hour']    =(int)$v['hour'];
                $datainfo[$k]['payl']    =(int)$v['payl'];
                $datainfo[$k]['payresult']    =(int)$v['payresult'];
                $datainfo[$k]['xysuccess']    =(int)$v['xysuccess'];
            
            }  
         $data = array('isday'=>$selectwhere['isday'],'ishour'=>$selectwhere['ishour'],'telecomname'=>$selectwhere['telecomname'],'egt'=>$selectwhere['egt']);
         $egtlist = \PublicData::$egtlist;  
         $this->assign('egtlist',$egtlist);
         $this->assign('data',$data);
         $this->assign('list',$datainfo);
         $this->assign('time_start',$datatime1s);
         $this->assign('time_end',$datatime2s);
         $this->assign('telecomname',$telecominfo);
         $this->assign('isbad_visible2',$isbad_visible2);
         $this->assign('is_outlets_visible',$is_outlets_visible);
         $this->assign('is_telecom_visible',$is_telecom_visible);
         $this->assign('isbad_visible',$isbad_visible);
         $this->display();  
    }
    
    //省份信息比
    public function indexcity(){
                
        $this->dateformonth = \PublicFunction::getTimeForYH($time1).'_';
        $isold =  $_GET['isold']|0;
        if($isold !== 1)  //当老数据勾选后 值为1
        {
            $this->dateformonth =  $this->dateformonth.'2'.'_'; //新数据
        }
        $tablename = $this->dateformonth.'ordercount';      //获取数据表
        $datainfo= M($tablename.' t')->where($selectwhere)->field('t.hour,t.telecomname,ceil(ifnull(format(SUM(t.paysuccess)/SUM(t.xysuccess),2),0))+2 as payl,sum(t.payresult) as payresult,SUM(t.xysuccess)+2 as xysuccess')->GROUP('t.telecomname,t.hour,t.day,t.egt')->select();
        $this->display();
    }
    
    //通道曲线
    public function indexline(){
        
        
        $this->display();
    }
    
    //用户图表（小时）
    public function indextable(){
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
        $datatime1s = (string)date("Y-m-d",$time1); 
     //  $dayno= date('Y-m-01', strtotime(date("Y-m-d")));
       // $dayno = (string)date("Y-m-01",$time1);
        $datatime2s =(string)date("Y-m-d",$time2);
        $data = array('appid','coid','egt'); 
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'utf8name'); 
        $selectwhere=$selectwhere_map['0'];         //条件为勾选框的条件  //将$_get=1 的值 赋值给 如: $selectwhere['telecomname']=1
        $wheregame=explode(',',$selectwhere['appid']);
        $whereco=explode(',',$selectwhere['coid']);
        $counco=count($whereco);
        $coungame=count($wheregame);
        if($counco>$coungame){
               $councois=1; 
        }else{
               $coungameis=1; 
        }
        if($wheregame[0]!="null" &&$wheregame[0]!=""){
            $wherearr['appid']=array('in',$wheregame);
        }
        if($whereco[0]!="null"  &&$whereco[0]!=""){
            $wherearr['coid']=array('in',$whereco);
        }
        //if($selectwhere['egt']!="null"&&$wheregame[0]!=""){
        //    $wherearr['egt']=$selectwhere['egt'];
        //}
        $this->dateformonth = \PublicFunction::getTimeForYH($time1).'_';
        $this->dateformonth =  $this->dateformonth.'2'.'_'; //新数据 
        $wherearr['time'] = array(array('egt',$datatime1s.' 00:00:00'),array('elt',$datatime2s.' 23:59:59')); 
        $data = I('get.');
        $id     =   I('get.id',1);
        $tablename='usercount';
        $tablenametoo=$this->dateformonth.'ordercount';
        $gametablename='gamelist';
        $cotablename='colist';
        $gameusertype= \PublicData::$gameusertype;
        $gamelist =  M($gametablename)->select();       //游戏列表
        $colist =  M($cotablename)->select();       //厂商列表
        $wherearr2=' t.appid is not null and t.appid <>"" ';
        if($coungameis==1 && $selectwhere['coid']=="null"){    //多选游戏    
            $game =  M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.appid,hour(t.time) as time, SUM(t.initnewuser) as newuser')->GROUP('t.appid')->order('t.appid')->select();
        }elseif($councois==1 && $selectwhere['appid']=="null"){       //多选厂商
            $game =  M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.coid,hour(t.time) as time, SUM(t.initnewuser) as newuser')->GROUP('t.coid')->order('t.coid')->select();
        }else{      //厂商和游戏都选
            $game =  M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.coid, t.appid,hour(t.time) as time, SUM(t.initnewuser) as newuser')->GROUP('t.appid')->order('t.appid')->select();
        }
        if($id==1){ //新增用户
            if($coungameis==1 && $selectwhere['coid']=="null"){    //多选游戏
                $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.appid,t.egt,hour(t.time) as time, SUM(t.initnewuser) as newuser')->GROUP('t.coid,hour(t.time)')->select();
             }elseif($councois==1 && $selectwhere['appid']=="null"){       //多选厂商
                 $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.coid,t.egt,hour(t.time) as time, SUM(t.initnewuser) as newuser')->GROUP('t.coid,hour(t.time)')->select();
             }else{  //厂商和游戏都选
                 $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt,t.coid,t.appid,hour(t.time) as time, SUM(t.initnewuser) as newuser')->GROUP('t.appid,hour(t.time)')->select();
             }
            $addname=$gameusertype[0]['name'];
          // $a=M()->_sql();
        }
        if($id==2 ){    //活跃用户
            if($whereco[0]!="null"  &&$whereco[0]!=""&&$wherearr['appid']==null){
                $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt, t.coid,hour(t.time) as time, SUM(t.initdayuser) as daynewuser')->GROUP('t.coid,hour(t.time)')->select();
            }elseif($councois==1){
                $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt, t.coid,hour(t.time) as time, SUM(t.initdayuser) as daynewuser')->GROUP('t.coid,hour(t.time)')->select();
            }else{ 
                $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt, t.appid,hour(t.time) as time, SUM(t.initdayuser) as daynewuser')->GROUP('t.appid,hour(t.time)')->select();          
            }
            $addname=$gameusertype[1]['name'];
          //$a=M()->_sql();
        }
        if($id==3 ){        //付费用户
             if($whereco[0]!="null"  &&$whereco[0]!=""&&$wherearr['appid']==null){
                 $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt, t.coid,hour(t.time) as time, SUM(t.daysaleuser) as daysaleuser')->GROUP('t.coid,hour(t.time)')->select();
             }elseif($councois==1){
                 $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt, t.coid,hour(t.time) as time, SUM(t.daysaleuser) as daysaleuser')->GROUP('t.coid,hour(t.time)')->select();
             }else{ 
               $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt, t.appid,hour(t.time) as time, SUM(t.daysaleuser) as daysaleuser')->GROUP('t.appid,hour(t.time)')->select();
             }
            $addname=$gameusertype[2]['name'];
            //  print_r($a=M()->_sql());
        }
        if($id==4 ){        //成交金额              
              if($whereco[0]!="null"  &&$whereco[0]!=""&&$wherearr['appid']==null){
                  if($whereco[0]!="null"  &&$whereco[0]!=""){
                      $wherearrinfo['co']=array('in',$whereco);
                  }
                  $datainfo= M($tablenametoo.' t')->where($wherearrinfo)->field('sum(t.msggold)as msggold,hour,t.co,t.egt')->GROUP('t.co,hour(t.time)')->select();
              }elseif($councois==1){
                  if($whereco[0]!="null"  &&$whereco[0]!=""){
                      $wherearrinfo['co']=array('in',$whereco);
                  }
                  $datainfo= M($tablenametoo.' t')->where($wherearrinfo)->field('sum(t.msggold)as msggold,hour,t.co,t.egt')->GROUP('t.co,hour(t.time)')->select();
              }else{ 
                   if($wheregame[0]!="null" &&$wheregame[0]!=""){
                     $wherearrinfo['cpgame']=array('in',$wheregame);
                  }
                 $wherearr2info=' t.cpgame is not null and t.cpgame <>"" ';
                 $datainfo= M($tablenametoo.' t')->where($wherearrinfo)->where($wherearr2info)->field('sum(t.msggold) as msggold,hour,t.cpgame,t.egt')->GROUP('t.cpgame,hour(t.time)')->select();
             }
            $addname=$gameusertype[3]['name'];
           //  print_r($a=M()->_sql());
        }
        if($id==5 ){        //ARPU
              if($whereco[0]!="null"  &&$whereco[0]!=""&&$wherearr['appid']==null){
                  if($whereco[0]!="null"  &&$whereco[0]!=""){
                      $wherearrinfo['co']=array('in',$whereco);
                  }
                  $wherearrinfo['t.time']=array(array('egt',$time1),array('elt',$time2)); 
                  $tablenameuser='bks_usercount';                
                  $datainfo= M($tablenametoo.' t')
                      ->join("LEFT JOIN $tablenameuser b ON (b.bksid = t.bksid)") 
                      ->where($wherearrinfo)
                      ->field('t.hour time, t.co,t.egt, FORMAT(CASE SIGN(SUM(t.msggold - t.badgold) / SUM(b.initdayuser)) WHEN - 1 THEN 0 WHEN 1 THEN SUM(t.msggold - t.badgold) / SUM(b.initdayuser) ELSE 0 END, 4) AS arpu')
                      ->GROUP('t.co,t.hour')
                      ->ORDER('t.co  DESC')
                      ->select();
              }elseif($councois==1){
                  if($whereco[0]!="null"  &&$whereco[0]!=""){
                      $wherearrinfo['co']=array('in',$whereco);
                  }
                  $wherearrinfo['t.time']=array(array('egt',$time1),array('elt',$time2)); 
                  $tablenameuser='bks_usercount';                
                  $datainfo= M($tablenametoo.' t')
                      ->join("LEFT JOIN $tablenameuser b ON (b.bksid = t.bksid)") 
                      ->where($wherearrinfo)
                      ->field('t.hour time, t.co,t.egt, FORMAT(CASE SIGN(SUM(t.msggold - t.badgold) / SUM(b.initdayuser)) WHEN - 1 THEN 0 WHEN 1 THEN SUM(t.msggold - t.badgold) / SUM(b.initdayuser) ELSE 0 END, 4) AS arpu')
                      ->GROUP('t.co,t.hour')
                      ->ORDER('t.co  DESC')
                      ->select();
              }else{ 
                if($whereco[0]!="null"  &&$whereco[0]!=""){
                    $wherearrinfo['co']=array('in',$whereco);
                }
                if($wheregame[0]!="null"&&$wheregame[0]!=""){
                    $wherearrinfo['cpgame']=array('in',$wheregame);
                }
                $wherearrinfo['t.time']=array(array('egt',$time1),array('elt',$time2)); 
                $tablenameuser='bks_usercount';
                $wherearr2info=' t.cpgame is not null and t.cpgame <>"" ';
                $datainfo= M($tablenametoo.' t')
                    ->join("LEFT JOIN $tablenameuser b ON (b.bksid = t.bksid)") 
                    ->where($wherearrinfo)->where($wherearr2info)
                    ->field('t.hour time, t.cpgame,t.egt, FORMAT(CASE SIGN(SUM(t.msggold - t.badgold) / SUM(b.initdayuser)) WHEN - 1 THEN 0 WHEN 1 THEN SUM(t.msggold - t.badgold) / SUM(b.initdayuser) ELSE 0 END, 4) AS arpu')
                    ->GROUP('t.cpgame,t.hour')
                    ->ORDER('t.cpgame  DESC')
                    ->select();
          }
          $a=M()->_sql();
            $addname=$gameusertype[4]['name'];
        }
        for($i = 0;$i<24;$i++)
        {
            $hourlist[$i] = $i;     //小时
        }  
        $houruserarr=array();
        foreach($game as $k3=>$V3){    //游戏
                 if($coungameis==1 && $selectwhere['coid']=="null"){         //多选游戏
                     $ganmetn=$V3['appid']; 
                     $ganmetnarr[]=$V3['appid'];
                 }elseif($councois==1 && $selectwhere['appid']=="null"){   //多选厂商
                      $ganmetn=$V3['coid']; 
                      $ganmetnarr[]=$V3['coid'];
                  }else{        //选游戏和厂商  
                      if($councois==1){           // 厂商多选游戏单选 
                          $ganmetn=$V3['coid']; 
                          $ganmetnarr[]=$V3['coid'];
                          $ganmetnarrappid=$V3['appid'];
                      }else{          // 多选游戏单选厂商
                        $ganmetn=$V3['appid']; 
                        $ganmetnarr[]=$V3['appid'];
                        $ganmetnarrcoid=$V3['coid'];
                      } 
                }  
             foreach($hourlist as $k=>$v){   //小时
                    foreach($datainfo as $k2=>$v2){
                             if($councois==1 && $selectwhere['appid']=="null"){      //多选厂商     
                                    if($id==4 ||$id==5){
                                         $appgame =$v2['co'];  
                                    }else{
                                        $appgame =$v2['coid'];  
                                    }
                              }elseif($coungameis==1 && $selectwhere['coid']=="null"){   //多选游戏
                                         if($id==4 ||$id==5){
                                             $appgame =$v2['cpgame'];  
                                         }else{
                                             $appgame =$v2['appid'];  
                                         }
                              }else{     //厂商和游戏都选
                                     if($councois==1){         // 厂商多选游戏单选
                                         if($id==4 ||$id==5){
                                             $appgame =$v2['co'];  
                                         }else{
                                             $appgame =$v2['coid'];  
                                         }
                                     }else{     // 多选游戏单选厂商
                                           if($id==4 ||$id==5){
                                               $appgame =$v2['cpgame'];  
                                           }else{
                                               $appgame =$v2['appid'];  
                                           }
                                     } 
                                 }
                               if($ganmetn==$appgame){
                                 if($v2['time']==$v){
                                     if($id==1){
                                                 $houruserarr[$ganmetn][$v]['newuser']=(int)$v2['newuser'];         //新增用户
                                     }else if($id==2){
                                                $houruserarr[$ganmetn][$v]['daynewuser']=(int)$v2['daynewuser'];        //活跃用户
                                     }else if($id==3){
                                                 $houruserarr[$ganmetn][$v]['daysaleuser']=(int)$v2['daysaleuser'];         //付费用户
                                     }else if($id==4){
                                         $houruserarr[$ganmetn][$v]['msggold']=(int)$v2['msggold'];         //成交金额
                                     }else if($id==5){
                                         $houruserarr[$ganmetn][$v]['arpu']=(double)$v2['arpu'];         //ARPU
                                     }
                                     $houruserarr[$ganmetn][$v]['time']=(int)$v; 
                                         //foreach($gamelist as $k4 =>$v4){
                                         //    if( $datainfo[$k2]['appid']==$v4['appid']){
                                         //        $gamename = $gamelist[$k4]['appid'];      
                                         //    }
                                         //}
                                         //$houruserarr[$ganmetn][$v]['gamename'] = $gamename;
                                  }else{
                                      if($id==1){
                                          if(!$houruserarr[$ganmetn][$v]['newuser']){
                                                    $houruserarr[$ganmetn][$v]['newuser']=(int)0;
                                          }
                                      }else if($id==2){
                                          if(!$houruserarr[$ganmetn][$v]['daynewuser']){
                                              $houruserarr[$ganmetn][$v]['daynewuser']=(int)0; 
                                           }
                                      }else if($id==3){
                                          if(!$houruserarr[$ganmetn][$v]['daysaleuser']){
                                              $houruserarr[$ganmetn][$v]['daysaleuser']=(int)0; 
                                          }
                                      }else if($id==4){
                                          if(!$houruserarr[$ganmetn][$v]['msggold']){
                                              $houruserarr[$ganmetn][$v]['msggold']=(int)0; 
                                          }
                                      }else if($id==5){
                                          if(!$houruserarr[$ganmetn][$v]['arpu']){
                                              $houruserarr[$ganmetn][$v]['arpu']=(double)0; 
                                          }
                                      }
                                      
                                      if(!$houruserarr[$ganmetn][$v]['time']){
                                             $houruserarr[$ganmetn][$v]['time']=(int)$v;
                                      }
                                 
                                 }    
                            }
                    }
            }
        }
        if($councois==1 && $selectwhere['appid']=="null"){      //多选厂商
            $gametableconame='colist';
            $telistgame1 =  M($gametableconame)->field('name,coid')->where('coid="'.$ganmetnarr[0].'"')->select();
            $telistgame1 = $telistgame1[0]['name'];
            $telistgame2 =  M($gametableconame)->field('name,coid')->where('coid="'.$ganmetnarr[1].'"')->select();
            $telistgame2 = $telistgame2[0]['name'];
            $telistgame3 =  M($gametableconame)->field('name,coid')->where('coid="'.$ganmetnarr[2].'"')->select();
            $telistgame3 = $telistgame3[0]['name'];
            $telistgame4 =  M($gametableconame)->field('name,coid')->where('coid="'.$ganmetnarr[3].'"')->select();
            $telistgame4 = $telistgame4[0]['name'];
        }elseif($coungameis==1 && $selectwhere['coid']=="null"){    //多选游戏
            $telistgame1 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[0].'"')->select();
            $telistgame1 = $telistgame1[0]['name'];
            $telistgame2 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[1].'"')->select();
            $telistgame2 = $telistgame2[0]['name'];
            $telistgame3 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[2].'"')->select();
            $telistgame3 = $telistgame3[0]['name'];
            $telistgame4 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[3].'"')->select();
            $telistgame4 = $telistgame4[0]['name'];
        }else{
            if($councois==1){
                    $gametableconame='colist';
                    $telistgame1 =  M($gametableconame)->field('name,coid')->where('coid="'.$ganmetnarr[0].'"')->select();
                    $telistgame1 = $telistgame1[0]['name'];
                    $telistgame2 =  M($gametableconame)->field('name,coid')->where('coid="'.$ganmetnarr[1].'"')->select();
                    $telistgame2 = $telistgame2[0]['name'];
                    $telistgame3 =  M($gametableconame)->field('name,coid')->where('coid="'.$ganmetnarr[2].'"')->select();
                    $telistgame3 = $telistgame3[0]['name'];
                    $telistgame4 =  M($gametableconame)->field('name,coid')->where('coid="'.$ganmetnarr[3].'"')->select();
                    $telistgame4 = $telistgame4[0]['name'];
            }else{
                    $telistgame1 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[0].'"')->select();
                    $telistgame1 = $telistgame1[0]['name'];
                    $telistgame2 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[1].'"')->select();
                    $telistgame2 = $telistgame2[0]['name'];
                    $telistgame3 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[2].'"')->select();
                    $telistgame3 = $telistgame3[0]['name'];
                    $telistgame4 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[3].'"')->select();
                    $telistgame4 = $telistgame4[0]['name'];
            }
        }
        //$data[]=explode(',',$data['appid']);
        if($councois==1 && $selectwhere['appid']=="null"){        //多选厂商
                foreach($colist as $k=>$v){
                    foreach($ganmetnarr as $k1=>$v1){
                        if($k1>3){
                            break;
                        }else{
                            if($v['coid']==$v1){
                                $colist[$k]['id2'] = $v1;
                            }
                        }
                    }
                }
               
        }elseif($coungameis==1 && $selectwhere['coid']=="null"){    //多选游戏
            foreach($gamelist as $k=>$v){
                foreach($ganmetnarr as $k1=>$v1){
                    if($k1>3){
                        break;
                    }else{
                        if($v['appid']==$v1){
                            $gamelist[$k]['id2'] = $v1;
                        }
                    }
                }
            }
         }else{         //都选游戏和厂商
                if($councois==1){
                         foreach($colist as $k=>$v){
                             foreach($ganmetnarr as $k1=>$v1){
                                 if($k1>3){
                                     break;
                                 }else{
                                     if($v['coid']==$v1){
                                         $colist[$k]['id2'] = $v1;
                                     }
                                 }
                             }
                         }
                         foreach($gamelist as $k=>$v){
                             if($v['appid']==$ganmetnarrappid){
                                 $gamelist[$k]['id2'] = $ganmetnarrappid;
                             }
                         }
                }else{      
                     foreach($gamelist as $k=>$v){
                         foreach($ganmetnarr as $k1=>$v1){
                             if($k1>3){
                                 break;
                             }else{
                                 if($v['appid']==$v1){
                                     $gamelist[$k]['id2'] = $v1;
                                 }
                             }
                         }
                     }
                     foreach($colist as $k=>$v){                                            
                             if($v['coid']==$ganmetnarrcoid){
                                 $colist[$k]['id2'] = $ganmetnarrcoid;
                                 }
                             }                               
                }
         }
        $tablelist = array(array('id'=>1,'name'=>$gameusertype[0]['name']),array('id'=>2,'name'=>$gameusertype[1]['name']),array('id'=>3,'name'=>$gameusertype[2]['name']),array('id'=>4,'name'=>$gameusertype[3]['name']),array('id'=>5,'name'=>$gameusertype[4]['name']));
        $egtlist = \PublicData::$egtlist;
        $uersertype = \PublicData::$uersertype;
        $uersertype1=$uersertype[0]['name'];
        $uersertype2=$uersertype[1]['name'];
        $uersertype3=$uersertype[2]['name'];
       
     //   $data['cpgame']=explode(',',$data['appid']);
        $this->assign('addname',$addname);
        $this->assign('telistgame1',$telistgame1);
        $this->assign('telistgame2',$telistgame2);
        $this->assign('telistgame3',$telistgame3);
        $this->assign('telistgame4',$telistgame4);
        $this->assign('houruserarr1',$houruserarr[$ganmetnarr[0]]); //  print_r($selectinfo);
        $this->assign('houruserarr2',$houruserarr[$ganmetnarr[1]]);
        $this->assign('houruserarr3',$houruserarr[$ganmetnarr[2]]);
        $this->assign('houruserarr4',$houruserarr[$ganmetnarr[3]]);
        $this->assign('data',$data);
        $this->assign('uersertype1',$uersertype1);
        $this->assign('uersertype2',$uersertype2);
        $this->assign('uersertype3',$uersertype3);
        $this->assign('tablelist',$tablelist);
        $this->assign('gamelist',$gamelist);
        $this->assign('time_start',$datatime1s);
        $this->assign('time_end',$datatime2s);
        $this->assign('colist',$colist);
        $this->assign('egtlist',$egtlist);
        $this->assign('tableid',$id);
        $this->display();
    }
    
    public function indextableday(){
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
        $datatime1s = (string)date("Y-m-01",$time1); 
        //  $dayno= date('Y-m-01', strtotime(date("Y-m-d")));
        // $dayno = (string)date("Y-m-01",$time1);
        $datatime2s =(string)date("Y-m-d",$time2);
        $data = array('appid','coid','egt'); 
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'utf8name'); 
        $selectwhere=$selectwhere_map['0'];         //条件为勾选框的条件  //将$_get=1 的值 赋值给 如: $selectwhere['telecomname']=1
        $wheregame=explode(',',$selectwhere['appid']);
        $whereco=explode(',',$selectwhere['coid']);
        $counco=count($whereco);
        $coungame=count($wheregame);
        if($counco>$coungame){
            $councois=1; 
        }else{
            $coungameis=1; 
        }
        if($wheregame[0]!="null" &&$wheregame[0]!=""){
            $wherearr['appid']=array('in',$wheregame);
        }
        if($whereco[0]!="null"  &&$whereco[0]!=""){
            $wherearr['coid']=array('in',$whereco);
        }
        //if($selectwhere['egt']!="null"&&$wheregame[0]!=""){
        //    $wherearr['egt']=$selectwhere['egt'];
        //}
        $this->dateformonth = \PublicFunction::getTimeForYH($time1).'_';
        $this->dateformonth =  $this->dateformonth.'2'.'_'; //新数据 
        $wherearr['time'] = array(array('egt',$datatime1s.' 00:00:00'),array('elt',$datatime2s.' 23:59:59')); 
        $data = I('get.');
        $id     =   I('get.id',1);
        $tablename='usercount';
        $tablenametoo=$this->dateformonth.'ordercount';
        $gametablename='gamelist';
        $cotablename='colist';
        $gameusertype= \PublicData::$gameusertype;
        $gamelist =  M($gametablename)->select();       //游戏列表
        $colist =  M($cotablename)->select();       //厂商列表
        $wherearr2=' t.appid is not null and t.appid <>"" ';
        if($coungameis==1 && $selectwhere['coid']=="null"){    //多选游戏    
            $game =  M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.appid,day(t.time) as time, SUM(t.initnewuser) as newuser')->GROUP('t.appid')->order('t.appid')->select();
        }elseif($councois==1 && $selectwhere['appid']=="null"){       //多选厂商
            $game =  M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.coid,day(t.time) as time, SUM(t.initnewuser) as newuser')->GROUP('t.coid')->order('t.coid')->select();
        }else{      //厂商和游戏都选
            $game =  M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.coid, t.appid,day(t.time) as time, SUM(t.initnewuser) as newuser')->GROUP('t.appid')->order('t.appid')->select();
        }
        if($id==1){ //新增用户
            if($coungameis==1 && $selectwhere['coid']=="null"){    //多选游戏
                $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.appid,t.egt,day(t.time) as time, SUM(t.initnewuser) as newuser')->GROUP('t.coid,day(t.time)')->select();
            }elseif($councois==1 && $selectwhere['appid']=="null"){       //多选厂商
                $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.coid,t.egt,day(t.time) as time, SUM(t.initnewuser) as newuser')->GROUP('t.coid,day(t.time)')->select();
            }else{  //厂商和游戏都选
                $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt,t.coid,t.appid,day(t.time) as time, SUM(t.initnewuser) as newuser')->GROUP('t.appid,day(t.time)')->select();
            }
            $addname=$gameusertype[0]['name'];
            // $a=M()->_sql();
        }
        if($id==2 ){    //活跃用户
            if($whereco[0]!="null"  &&$whereco[0]!=""&&$wherearr['appid']==null){
                $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt, t.coid,day(t.time) as time, SUM(t.initdayuser) as daynewuser')->GROUP('t.coid,day(t.time)')->select();
            }elseif($councois==1){
                $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt, t.coid,day(t.time) as time, SUM(t.initdayuser) as daynewuser')->GROUP('t.coid,day(t.time)')->select();
            }else{ 
                $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt, t.appid,day(t.time) as time, SUM(t.initdayuser) as daynewuser')->GROUP('t.appid,day(t.time)')->select();          
            }
            $addname=$gameusertype[1]['name'];
            //$a=M()->_sql();
        }
        if($id==3 ){        //付费用户
            if($whereco[0]!="null"  &&$whereco[0]!=""&&$wherearr['appid']==null){
                $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt, t.coid,day(t.time) as time, SUM(t.daysaleuser) as daysaleuser')->GROUP('t.coid,day(t.time)')->select();
            }elseif($councois==1){
                $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt, t.coid,day(t.time) as time, SUM(t.daysaleuser) as daysaleuser')->GROUP('t.coid,day(t.time)')->select();
            }else{ 
                $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt, t.appid,day(t.time) as time, SUM(t.daysaleuser) as daysaleuser')->GROUP('t.appid,day(t.time)')->select();
            }
            $addname=$gameusertype[2]['name'];
            //  print_r($a=M()->_sql());
        }
        if($id==4 ){        //成交金额              
            if($whereco[0]!="null"  &&$whereco[0]!=""&&$wherearr['appid']==null){
                if($whereco[0]!="null"  &&$whereco[0]!=""){
                    $wherearrinfo['co']=array('in',$whereco);
                }
                $datainfo= M($tablenametoo.' t')->where($wherearrinfo)->field('sum(t.msggold)as msggold,day,t.co,t.egt')->GROUP('t.co,day(t.time)')->select();
            }elseif($councois==1){
                if($whereco[0]!="null"  &&$whereco[0]!=""){
                    $wherearrinfo['co']=array('in',$whereco);
                }
                $datainfo= M($tablenametoo.' t')->where($wherearrinfo)->field('sum(t.msggold)as msggold,day,t.co,t.egt')->GROUP('t.co,day(t.time)')->select();
            }else{ 
                if($wheregame[0]!="null" &&$wheregame[0]!=""){
                    $wherearrinfo['cpgame']=array('in',$wheregame);
                }
                $wherearr2info=' t.cpgame is not null and t.cpgame <>"" ';
                $datainfo= M($tablenametoo.' t')->where($wherearrinfo)->where($wherearr2info)->field('sum(t.msggold) as msggold,day,t.cpgame,t.egt')->GROUP('t.cpgame,day(t.time)')->select();
            }
            $addname=$gameusertype[3]['name'];
            //  print_r($a=M()->_sql());
        }
        if($id==5 ){        //ARPU
            if($whereco[0]!="null"  &&$whereco[0]!=""&&$wherearr['appid']==null){
                if($whereco[0]!="null"  &&$whereco[0]!=""){
                    $wherearrinfo['co']=array('in',$whereco);
                }
                $wherearrinfo['t.time']=array(array('egt',$time1),array('elt',$time2)); 
                $tablenameuser='bks_usercount';                
                $datainfo= M($tablenametoo.' t')
                    ->join("LEFT JOIN $tablenameuser b ON (b.bksid = t.bksid)") 
                    ->where($wherearrinfo)
                    ->field('t.day time, t.co,t.egt, FORMAT(CASE SIGN(SUM(t.msggold - t.badgold) / SUM(b.initdayuser)) WHEN - 1 THEN 0 WHEN 1 THEN SUM(t.msggold - t.badgold) / SUM(b.initdayuser) ELSE 0 END, 4) AS arpu')
                    ->GROUP('t.co,t.day')
                    ->ORDER('t.co  DESC')
                    ->select();
            }elseif($councois==1){
                if($whereco[0]!="null"  &&$whereco[0]!=""){
                    $wherearrinfo['co']=array('in',$whereco);
                }
                $wherearrinfo['t.time']=array(array('egt',$time1),array('elt',$time2)); 
                $tablenameuser='bks_usercount';                
                $datainfo= M($tablenametoo.' t')
                    ->join("LEFT JOIN $tablenameuser b ON (b.bksid = t.bksid)") 
                    ->where($wherearrinfo)
                    ->field('t.day time, t.co,t.egt, FORMAT(CASE SIGN(SUM(t.msggold - t.badgold) / SUM(b.initdayuser)) WHEN - 1 THEN 0 WHEN 1 THEN SUM(t.msggold - t.badgold) / SUM(b.initdayuser) ELSE 0 END, 4) AS arpu')
                    ->GROUP('t.co,t.day')
                    ->ORDER('t.co  DESC')
                    ->select();
            }else{ 
                if($whereco[0]!="null"  &&$whereco[0]!=""){
                    $wherearrinfo['co']=array('in',$whereco);
                }
                if($wheregame[0]!="null"&&$wheregame[0]!=""){
                    $wherearrinfo['cpgame']=array('in',$wheregame);
                }
                $wherearrinfo['t.time']=array(array('egt',$time1),array('elt',$time2)); 
                $tablenameuser='bks_usercount';
                $wherearr2info=' t.cpgame is not null and t.cpgame <>"" ';
                $datainfo= M($tablenametoo.' t')
                    ->join("LEFT JOIN $tablenameuser b ON (b.bksid = t.bksid)") 
                    ->where($wherearrinfo)->where($wherearr2info)
                    ->field('t.day time, t.cpgame,t.egt, FORMAT(CASE SIGN(SUM(t.msggold - t.badgold) / SUM(b.initdayuser)) WHEN - 1 THEN 0 WHEN 1 THEN SUM(t.msggold - t.badgold) / SUM(b.initdayuser) ELSE 0 END, 4) AS arpu')
                    ->GROUP('t.cpgame,t.day')
                    ->ORDER('t.cpgame  DESC')
                    ->select();
            }
            $a=M()->_sql();
            $addname=$gameusertype[4]['name'];
        }
        for($i = 0;$i<24;$i++)
        {
            $hourlist[$i] = $i;     //小时
        }  
        $houruserarr=array();
        foreach($game as $k3=>$V3){    //游戏
            if($coungameis==1 && $selectwhere['coid']=="null"){         //多选游戏
                $ganmetn=$V3['appid']; 
                $ganmetnarr[]=$V3['appid'];
            }elseif($councois==1 && $selectwhere['appid']=="null"){   //多选厂商
                $ganmetn=$V3['coid']; 
                $ganmetnarr[]=$V3['coid'];
            }else{        //选游戏和厂商  
                if($councois==1){           // 厂商多选游戏单选 
                    $ganmetn=$V3['coid']; 
                    $ganmetnarr[]=$V3['coid'];
                    $ganmetnarrappid=$V3['appid'];
                }else{          // 多选游戏单选厂商
                    $ganmetn=$V3['appid']; 
                    $ganmetnarr[]=$V3['appid'];
                    $ganmetnarrcoid=$V3['coid'];
                } 
            }  
            foreach($hourlist as $k=>$v){   //小时
                foreach($datainfo as $k2=>$v2){
                    if($councois==1 && $selectwhere['appid']=="null"){      //多选厂商     
                        if($id==4 ||$id==5){
                            $appgame =$v2['co'];  
                        }else{
                            $appgame =$v2['coid'];  
                        }
                    }elseif($coungameis==1 && $selectwhere['coid']=="null"){   //多选游戏
                        if($id==4 ||$id==5){
                            $appgame =$v2['cpgame'];  
                        }else{
                            $appgame =$v2['appid'];  
                        }
                    }else{     //厂商和游戏都选
                        if($councois==1){         // 厂商多选游戏单选
                            if($id==4 ||$id==5){
                                $appgame =$v2['co'];  
                            }else{
                                $appgame =$v2['coid'];  
                            }
                        }else{     // 多选游戏单选厂商
                            if($id==4 ||$id==5){
                                $appgame =$v2['cpgame'];  
                            }else{
                                $appgame =$v2['appid'];  
                            }
                        } 
                    }
                    if($ganmetn==$appgame){
                        if($v2['time']==$v){
                            if($id==1){
                                $houruserarr[$ganmetn][$v]['newuser']=(int)$v2['newuser'];         //新增用户
                            }else if($id==2){
                                $houruserarr[$ganmetn][$v]['daynewuser']=(int)$v2['daynewuser'];        //活跃用户
                            }else if($id==3){
                                $houruserarr[$ganmetn][$v]['daysaleuser']=(int)$v2['daysaleuser'];         //付费用户
                            }else if($id==4){
                                $houruserarr[$ganmetn][$v]['msggold']=(int)$v2['msggold'];         //成交金额
                            }else if($id==5){
                                $houruserarr[$ganmetn][$v]['arpu']=(double)$v2['arpu'];         //ARPU
                            }
                            $houruserarr[$ganmetn][$v]['time']=(int)$v; 
                            //foreach($gamelist as $k4 =>$v4){
                            //    if( $datainfo[$k2]['appid']==$v4['appid']){
                            //        $gamename = $gamelist[$k4]['appid'];      
                            //    }
                            //}
                            //$houruserarr[$ganmetn][$v]['gamename'] = $gamename;
                        }else{
                            if($id==1){
                                if(!$houruserarr[$ganmetn][$v]['newuser']){
                                    $houruserarr[$ganmetn][$v]['newuser']=(int)0;
                                }
                            }else if($id==2){
                                if(!$houruserarr[$ganmetn][$v]['daynewuser']){
                                    $houruserarr[$ganmetn][$v]['daynewuser']=(int)0; 
                                }
                            }else if($id==3){
                                if(!$houruserarr[$ganmetn][$v]['daysaleuser']){
                                    $houruserarr[$ganmetn][$v]['daysaleuser']=(int)0; 
                                }
                            }else if($id==4){
                                if(!$houruserarr[$ganmetn][$v]['msggold']){
                                    $houruserarr[$ganmetn][$v]['msggold']=(int)0; 
                                }
                            }else if($id==5){
                                if(!$houruserarr[$ganmetn][$v]['arpu']){
                                    $houruserarr[$ganmetn][$v]['arpu']=(double)0; 
                                }
                            }
                            
                            if(!$houruserarr[$ganmetn][$v]['time']){
                                $houruserarr[$ganmetn][$v]['time']=(int)$v;
                            }
                            
                        }    
                    }
                }
            }
        }
        if($councois==1 && $selectwhere['appid']=="null"){      //多选厂商
            $gametableconame='colist';
            $telistgame1 =  M($gametableconame)->field('name,coid')->where('coid="'.$ganmetnarr[0].'"')->select();
            $telistgame1 = $telistgame1[0]['name'];
            $telistgame2 =  M($gametableconame)->field('name,coid')->where('coid="'.$ganmetnarr[1].'"')->select();
            $telistgame2 = $telistgame2[0]['name'];
            $telistgame3 =  M($gametableconame)->field('name,coid')->where('coid="'.$ganmetnarr[2].'"')->select();
            $telistgame3 = $telistgame3[0]['name'];
            $telistgame4 =  M($gametableconame)->field('name,coid')->where('coid="'.$ganmetnarr[3].'"')->select();
            $telistgame4 = $telistgame4[0]['name'];
        }elseif($coungameis==1 && $selectwhere['coid']=="null"){    //多选游戏
            $telistgame1 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[0].'"')->select();
            $telistgame1 = $telistgame1[0]['name'];
            $telistgame2 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[1].'"')->select();
            $telistgame2 = $telistgame2[0]['name'];
            $telistgame3 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[2].'"')->select();
            $telistgame3 = $telistgame3[0]['name'];
            $telistgame4 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[3].'"')->select();
            $telistgame4 = $telistgame4[0]['name'];
        }else{
            if($councois==1){
                $gametableconame='colist';
                $telistgame1 =  M($gametableconame)->field('name,coid')->where('coid="'.$ganmetnarr[0].'"')->select();
                $telistgame1 = $telistgame1[0]['name'];
                $telistgame2 =  M($gametableconame)->field('name,coid')->where('coid="'.$ganmetnarr[1].'"')->select();
                $telistgame2 = $telistgame2[0]['name'];
                $telistgame3 =  M($gametableconame)->field('name,coid')->where('coid="'.$ganmetnarr[2].'"')->select();
                $telistgame3 = $telistgame3[0]['name'];
                $telistgame4 =  M($gametableconame)->field('name,coid')->where('coid="'.$ganmetnarr[3].'"')->select();
                $telistgame4 = $telistgame4[0]['name'];
            }else{
                $telistgame1 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[0].'"')->select();
                $telistgame1 = $telistgame1[0]['name'];
                $telistgame2 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[1].'"')->select();
                $telistgame2 = $telistgame2[0]['name'];
                $telistgame3 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[2].'"')->select();
                $telistgame3 = $telistgame3[0]['name'];
                $telistgame4 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[3].'"')->select();
                $telistgame4 = $telistgame4[0]['name'];
            }
        }
        //$data[]=explode(',',$data['appid']);
        if($councois==1 && $selectwhere['appid']=="null"){        //多选厂商
            foreach($colist as $k=>$v){
                foreach($ganmetnarr as $k1=>$v1){
                    if($k1>3){
                        break;
                    }else{
                        if($v['coid']==$v1){
                            $colist[$k]['id2'] = $v1;
                        }
                    }
                }
            }
            
        }elseif($coungameis==1 && $selectwhere['coid']=="null"){    //多选游戏
            foreach($gamelist as $k=>$v){
                foreach($ganmetnarr as $k1=>$v1){
                    if($k1>3){
                        break;
                    }else{
                        if($v['appid']==$v1){
                            $gamelist[$k]['id2'] = $v1;
                        }
                    }
                }
            }
        }else{         //都选游戏和厂商
            if($councois==1){
                foreach($colist as $k=>$v){
                    foreach($ganmetnarr as $k1=>$v1){
                        if($k1>3){
                            break;
                        }else{
                            if($v['coid']==$v1){
                                $colist[$k]['id2'] = $v1;
                            }
                        }
                    }
                }
                foreach($gamelist as $k=>$v){
                    if($v['appid']==$ganmetnarrappid){
                        $gamelist[$k]['id2'] = $ganmetnarrappid;
                    }
                }
            }else{      
                foreach($gamelist as $k=>$v){
                    foreach($ganmetnarr as $k1=>$v1){
                        if($k1>3){
                            break;
                        }else{
                            if($v['appid']==$v1){
                                $gamelist[$k]['id2'] = $v1;
                            }
                        }
                    }
                }
                foreach($colist as $k=>$v){                                            
                    if($v['coid']==$ganmetnarrcoid){
                        $colist[$k]['id2'] = $ganmetnarrcoid;
                    }
                }                               
            }
        }
        $tablelist = array(array('id'=>1,'name'=>$gameusertype[0]['name']),array('id'=>2,'name'=>$gameusertype[1]['name']),array('id'=>3,'name'=>$gameusertype[2]['name']),array('id'=>4,'name'=>$gameusertype[3]['name']),array('id'=>5,'name'=>$gameusertype[4]['name']));
        $egtlist = \PublicData::$egtlist;
        $uersertype = \PublicData::$uersertype;
        $uersertype1=$uersertype[0]['name'];
        $uersertype2=$uersertype[1]['name'];
        $uersertype3=$uersertype[2]['name'];
        
        //   $data['cpgame']=explode(',',$data['appid']);
        $this->assign('addname',$addname);
        $this->assign('telistgame1',$telistgame1);
        $this->assign('telistgame2',$telistgame2);
        $this->assign('telistgame3',$telistgame3);
        $this->assign('telistgame4',$telistgame4);
        $this->assign('houruserarr1',$houruserarr[$ganmetnarr[0]]); //  print_r($selectinfo);
        $this->assign('houruserarr2',$houruserarr[$ganmetnarr[1]]);
        $this->assign('houruserarr3',$houruserarr[$ganmetnarr[2]]);
        $this->assign('houruserarr4',$houruserarr[$ganmetnarr[3]]);
        $this->assign('data',$data);
        $this->assign('uersertype1',$uersertype1);
        $this->assign('uersertype2',$uersertype2);
        $this->assign('uersertype3',$uersertype3);
        $this->assign('tablelist',$tablelist);
        $this->assign('gamelist',$gamelist);
        $this->assign('time_start',$datatime1s);
        $this->assign('time_end',$datatime2s);
        $this->assign('colist',$colist);
        $this->assign('egtlist',$egtlist);
        $this->assign('tableid',$id);
        $this->display();
    }
    
    public function indextablecity(){
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
        $datatime1s = (string)date("Y-m-01",$time1); 
        $datatime2s =(string)date("Y-m-d",$time2);
        $data = array('appid','coid','egt'); 
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'utf8name'); 
        $selectwhere=$selectwhere_map['0'];         //条件为勾选框的条件  //将$_get=1 的值 赋值给 如: $selectwhere['telecomname']=1
        $wheregame=explode(',',$selectwhere['appid']);
         $whereco=explode(',',$selectwhere['coid']);
        if($wheregame[0]!="null" &&$wheregame[0]!=""){
            $wherearr['appid']=array('in',$wheregame);
        }
        if($whereco[0]!="null"&&$whereco[0]!=""){
            $wherearr['coid']=array('in',$whereco);
        }
        //if($selectwhere['egt']!="null" &&$wheregame[0]!=""){
        //    $wherearr['egt']=$selectwhere['egt'];
        //}
        $wherearr['time'] = array(array('egt',$datatime1s.' 00:00:00'),array('elt',$datatime2s.' 23:59:59')); 
        $id     =   I('get.id',1);
        $tablename='usercount';
        $this->dateformonth = \PublicFunction::getTimeForYH($time1).'_';
        $this->dateformonth =  $this->dateformonth.'2'.'_'; //新数据
        $tablenametoo=$this->dateformonth.'ordercount';  
        $gametablename='gamelist';
        $cotablename='colist';
        $gameusertype= \PublicData::$gameusertype;
        $gamelist =  M($gametablename)->select();       //游戏列表
        $colist =  M($cotablename)->select();       //厂商列表
        $wherearr2=' t.appid is not null and t.appid <>"" ';
        if($whereco[0]!="null"  &&$whereco[0]!=""&&$wherearr['appid']==null){
            $game =  M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.coid,t.city, SUM(t.initnewuser) as newuser')->GROUP('t.appid')->order('t.appid')->select();
        }else{
            $game =  M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.coid, t.appid,t.city, SUM(t.initnewuser) as newuser')->GROUP('t.appid')->order('t.appid')->select();
        }
        if($id==1){ //新增用户
            if($whereco[0]!="null"  &&$whereco[0]!=""&&$wherearr['appid']==null){
                $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.coid,t.egt,t.city, SUM(t.initnewuser) as newuser')->GROUP('t.coid,t.city')->select();
            }else{ 
                $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt, t.appid,t.city, SUM(t.initnewuser) as newuser')->GROUP('t.appid,t.city')->select();
            }
            $addname=$gameusertype[0]['name'];
             $a=M()->_sql();
        }
        if($id==2 ){    //活跃用户
            if($whereco[0]!="null"  &&$whereco[0]!=""&&$wherearr['appid']==null){
                $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt, t.coid,t.city, SUM(t.initdayuser) as daynewuser')->GROUP('t.coid,t.city')->select();
            }else{ 
                $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt, t.appid,t.city, SUM(t.initdayuser) as daynewuser')->GROUP('t.appid,t.city')->select();          
            }
            $addname=$gameusertype[1]['name'];
            $a=M()->_sql();
        }
        if($id==3 ){        //付费用户
            if($whereco[0]!="null"  &&$whereco[0]!=""&&$wherearr['appid']==null){
                $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt, t.coid,t.city, SUM(t.daysaleuser) as daysaleuser')->GROUP('t.coid,t.city')->select();
            }else{ 
                $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt, t.appid,t.city, SUM(t.daysaleuser) as daysaleuser')->GROUP('t.appid,t.city')->select();
            }
            $addname=$gameusertype[2]['name'];
            //  print_r($a=M()->_sql());
        }
        if($id==4 ){        //成交金额              
            $wherearrgame['time'] = array(array('egt',$time1),array('elt',$time2)); 
            if($whereco[0]!="null"  &&$whereco[0]!=""&&$wherearr['appid']==null){
                if($whereco[0]!="null"  &&$whereco[0]!=""){
                    $wherearrinfo['co']=array('in',$whereco);
                }
                $datainfo= M($tablenametoo.' t')->where($wherearrinfo)->where($wherearrgame)->field('sum(t.msggold)as msggold,t.city,t.co,t.egt')->GROUP('t.co,t.city')->select();
            }else{ 
                if($wheregame[0]!="null" &&$wheregame[0]!=""){
                    $wherearrinfo['cpgame']=array('in',$wheregame);
                }
                if($whereco[0]!="null"  &&$whereco[0]!=""){
                    $wherearrinfo['co']=array('in',$whereco);
                }
                $wherearr2info=' t.cpgame is not null and t.cpgame <>"" ';
                $datainfo= M($tablenametoo.' t')->where($wherearrinfo)->where($wherearr2info)->where($wherearrgame)->field('sum(t.msggold) as msggold,t.city,t.cpgame,t.egt')->GROUP('t.cpgame,t.city')->select();
                    $a=M()->_sql();
            }
            $addname=$gameusertype[3]['name'];
            //  print_r($a=M()->_sql());
        }
        if($id==5 ){        //ARPU
            if($whereco[0]!="null"  &&$whereco[0]!=""&&$wherearr['appid']==null){
                if($whereco[0]!="null"  &&$whereco[0]!=""){
                    $wherearrinfo['co']=array('in',$whereco);
                }
                $wherearrinfo['t.time']=array(array('egt',$time1),array('elt',$time2)); 
                $tablenameuser='bks_usercount';                
                $datainfo= M($tablenametoo.' t')
                    ->join("LEFT JOIN $tablenameuser b ON (b.bksid = t.bksid)") 
                    ->where($wherearrinfo)
                    ->field('t.city, t.co,t.egt, FORMAT(CASE SIGN(SUM(t.msggold - t.badgold) / SUM(b.initdayuser)) WHEN - 1 THEN 0 WHEN 1 THEN SUM(t.msggold - t.badgold) / SUM(b.initdayuser) ELSE 0 END, 4) AS arpu')
                    ->GROUP('t.co,t.city')
                    ->ORDER('t.co  DESC')
                    ->select();
            }else{ 
                if($whereco[0]!="null"  &&$whereco[0]!=""){
                    $wherearrinfo['co']=array('in',$whereco);
                }
                if($wheregame[0]!="null"&&$wheregame[0]!=""){
                    $wherearrinfo['cpgame']=array('in',$wheregame);
                }
                $wherearrinfo['t.time']=array(array('egt',$time1),array('elt',$time2)); 
                $tablenameuser='bks_usercount';
                $wherearr2info=' t.cpgame is not null and t.cpgame <>"" ';
                $datainfo= M($tablenametoo.' t')
                    ->join("LEFT JOIN $tablenameuser b ON (b.bksid = t.bksid)") 
                    ->where($wherearrinfo)->where($wherearr2info)
                    ->field('t.city, t.cpgame,t.egt, FORMAT(CASE SIGN(SUM(t.msggold - t.badgold) / SUM(b.initdayuser)) WHEN - 1 THEN 0 WHEN 1 THEN SUM(t.msggold - t.badgold) / SUM(b.initdayuser) ELSE 0 END, 4) AS arpu')
                    ->GROUP('t.cpgame,t.city')
                    ->ORDER('t.cpgame  DESC')
                    ->select();
            }
            $a=M()->_sql();
            $addname=$gameusertype[4]['name'];
        }
        //$wherearr2=' t.appid is not null and t.appid <>"" ';
        //$game =  M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.appid,t.city, SUM(t.newuser) as initnewuser')->GROUP('t.appid')->order('t.appid')->select();
        ////     print_r($a=M()->_sql());
        //if($id==1){ //新增用户
        //    $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt, t.appid,t.city, SUM(t.initnewuser) as newuser')->GROUP('t.appid,t.city')->select();
        //    $addname=$gameusertype[0]['name'];
        //   // print_r($a=M()->_sql());
        //}
        //if($id==2 ){    //活跃用户
        //    $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt, t.appid,t.city, SUM(t.initdayuser) as daynewuser')->GROUP('t.appid,t.city')->select();
        //    $addname=$gameusertype[1]['name'];
        //    //  print_r($a=M()->_sql());
        //}
        //if($id==3 ){        //付费用户
        //    $datainfo= M($tablename.' t')->where($wherearr)->where($wherearr2)->field('t.egt, t.appid,t.city, SUM(t.daysaleuser) as daysaleuser')->GROUP('t.appid,t.city')->select();
        //    $addname=$gameusertype[2]['name'];
        //    //  print_r($a=M()->_sql());
        //}
        //if($id==4 ){        //成交金额
        //    if($wheregame[0]!="null"){
        //        $wherearrinfo['cpgame']=array('in',$wheregame);
        //    }
        //    $wherearr2info=' t.cpgame is not null and t.cpgame <>"" ';
        //    $datainfo= M($tablenametoo.' t')->where($wherearrinfo)->where($wherearr2info)->field('sum(t.msggold),t.city,t.cpgame,t.egt')->GROUP('t.cpgame,t.city')->select();
        //    $addname=$gameusertype[3]['name'];
        //    //  print_r($a=M()->_sql());
        //}
        //if($id==5 ){        //ARPU
        //    if($wheregame[0]!="null"){
        //        $wherearrinfo['cpgame']=array('in',$wheregame);
        //    }
        //    $tablenameuser='bks_usercount';
        //    $wherearr2info=' t.cpgame is not null and t.cpgame <>"" ';
        //    $datainfo= M($tablenametoo.' t')
        //        ->join("LEFT JOIN $tablenameuser b ON (b.bksid = t.bksid)") 
        //        ->where($wherearrinfo)->where($wherearr2info)
        //        ->field('t.city, t.cpgame,t.egt, FORMAT(CASE SIGN(SUM(t.msggold - t.badgold) / SUM(b.initdayuser)) WHEN - 1 THEN 0 WHEN 1 THEN SUM(t.msggold - t.badgold) / SUM(b.initdayuser) ELSE 0 END, 4) AS arpu')
        //        ->GROUP('t.cpgame,t.city')
        //        ->ORDER('t.cpgame  DESC')
        //        ->select();
        //    //  print_r($a=M()->_sql());
        //    $addname=$gameusertype[4]['name'];
        //    //  print_r($a=M()->_sql());
        //}
        $city=  \PublicData::$city; 
        $houruserarr=array();
        foreach($game as $k3=>$V3){    //游戏
            if($whereco[0]!="null"  &&$whereco[0]!=""&&$wherearr['appid']==null){
                $ganmetn=$V3['coid']; 
                $ganmetnarr[]=$V3['coid'];
            }else{
                $ganmetn=$V3['appid']; 
                $ganmetnarr[]=$V3['appid'];
                $ganmetnco=$V3['coid']; 
                $ganmetnarrconame[]=$V3['coid'];
            }  
            foreach($city as $k1=>$v){           //省份
                $citn = $v['id'];
                foreach($datainfo as $k2=>$v2){
                    if($whereco[0]!="null"  &&$whereco[0]!=""&&$wherearr['appid']==null){   
                        if($id==4 ||$id==5){
                            $appgame =$v2['co'];  
                        }else{
                            $appgame =$v2['coid'];  
                        }
                    }else{
                        if($id==4 ||$id==5){
                            $appgame =$v2['cpgame'];  
                        }else{
                            $appgame =$v2['appid'];  
                        }
                    }
                        if($ganmetn==$appgame){
                          $usercityinfo=mb_substr($v2['city'],0,2,'utf-8');
                              if($id==4 ||$id==5){
                                   $cityid= $v['id'];
                              }else{
                                   $cityid= $v['city'];  
                              }
                          if($usercityinfo== $cityid){
                            if($id==1){
                                $houruserarr[$ganmetn][$citn]['newuser']=(int)$v2['newuser'];         //新增用户
                            }else if($id==2){
                                $houruserarr[$ganmetn][$citn]['daynewuser']=(int)$v2['daynewuser'];        //活跃用户
                            }else if($id==3){
                                $houruserarr[$ganmetn][$citn]['daysaleuser']=(int)$v2['daysaleuser'];         //付费用户
                            }else if($id==4){
                                $houruserarr[$ganmetn][$citn]['msggold']=(double)$v2['msggold'];         //成交金额
                            }else if($id==5){
                                $houruserarr[$ganmetn][$citn]['arpu']=(int)$v2['arpu'];         //ARPU
                            }
                            $houruserarr[$ganmetn][$citn]['city']=$v['city']; 
                            //foreach($gamelist as $k4 =>$v4){
                            //    if( $datainfo[$k2]['appid']==$v4['appid']){
                            //        $gamename = $gamelist[$k4]['name'];      
                            //    }
                            //}
                            //$houruserarr[$ganmetn][$v]['gamename'] = $gamename;
                        }else{
                            if($id==1){
                                if(!$houruserarr[$ganmetn][$citn]['newuser']){
                                    $houruserarr[$ganmetn][$citn]['newuser']=(int)0;
                                }
                            }else if($id==2){
                                if(!$houruserarr[$ganmetn][$citn]['daynewuser']){
                                    $houruserarr[$ganmetn][$citn]['daynewuser']=(int)0; 
                                }
                            }else if($id==3){
                                if(!$houruserarr[$ganmetn][$citn]['daysaleuser']){
                                    $houruserarr[$ganmetn][$citn]['daysaleuser']=(int)0; 
                                }
                            }else if($id==4){
                                if(!$houruserarr[$ganmetn][$citn]['msggold']){
                                    $houruserarr[$ganmetn][$citn]['msggold']=(double)0; 
                                }
                            }else if($id==5){
                                if(!$houruserarr[$ganmetn][$citn]['arpu']){
                                    $houruserarr[$ganmetn][$citn]['arpu']=(int)0; 
                                }
                            }
                            
                            if(!$houruserarr[$ganmetn][$citn]['city']){
                                $houruserarr[$ganmetn][$citn]['city']=$v['city'];
                            }
                            
                        }    
                    }
                }
            }
        }
        if($whereco[0]!="null"  &&$whereco[0]!=""&&$wherearr['appid']==null){
            $gametableconame='colist';
            $telistgame1 =  M($gametableconame)->field('name,coid')->where('coid="'.$ganmetnarr[0].'"')->select();
            $telistgame1 = $telistgame1[0]['name'];
            $telistgame2 =  M($gametableconame)->field('name,coid')->where('coid="'.$ganmetnarr[1].'"')->select();
            $telistgame2 = $telistgame2[0]['name'];
            $telistgame3 =  M($gametableconame)->field('name,coid')->where('coid="'.$ganmetnarr[2].'"')->select();
            $telistgame3 = $telistgame3[0]['name'];
            $telistgame4 =  M($gametableconame)->field('name,coid')->where('coid="'.$ganmetnarr[3].'"')->select();
            $telistgame4 = $telistgame4[0]['name'];
        }else{
            $telistgame1 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[0].'"')->select();
            $telistgame1 = $telistgame1[0]['name'];
            $telistgame2 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[1].'"')->select();
            $telistgame2 = $telistgame2[0]['name'];
            $telistgame3 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[2].'"')->select();
            $telistgame3 = $telistgame3[0]['name'];
            $telistgame4 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[3].'"')->select();
            $telistgame4 = $telistgame4[0]['name'];
            
        }
        if($whereco[0]!="null"  &&$whereco[0]!=""&&$wherearr['appid']==null){
            foreach($colist as $k=>$v){
                foreach($ganmetnarr as $k1=>$v1){
                    if($k1>3){
                        break;
                    }else{
                        if($v['coid']==$v1){
                            $colist[$k]['id2'] = $v1;
                        }
                    }
                }
            }
        }else{   
            foreach($colist as $k=>$v){
                foreach($ganmetnarrconame as $k1=>$v1){
                    if($k1>3){
                        break;
                    }else{
                        if($v['coid']==$v1){
                            $colist[$k]['id2'] = $v1;
                        }
                    }
                }
            }
            foreach($gamelist as $k=>$v){
                foreach($ganmetnarr as $k1=>$v1){
                    if($k1>3){
                        break;
                    }else{
                        if($v['appid']==$v1){
                            $gamelist[$k]['id2'] = $v1;
                        }
                    }
                }
            }
            
            
        }
        
        $tablelist = array(array('id'=>1,'name'=>$gameusertype[0]['name']),array('id'=>2,'name'=>$gameusertype[1]['name']),array('id'=>3,'name'=>$gameusertype[2]['name']),array('id'=>4,'name'=>$gameusertype[3]['name']),array('id'=>5,'name'=>$gameusertype[4]['name']));
        $egtlist = \PublicData::$egtlist;
        $uersertype = \PublicData::$uersertype;
        $uersertype1=$uersertype[0]['name'];
        $uersertype2=$uersertype[1]['name'];
        $uersertype3=$uersertype[2]['name'];
        $data = I('get.');
        $this->assign('addname',$addname);
        $this->assign('telistgame1',$telistgame1);
        $this->assign('telistgame2',$telistgame2);
        $this->assign('telistgame3',$telistgame3);
        $this->assign('telistgame4',$telistgame4);
        $this->assign('houruserarr1',$houruserarr[$ganmetnarr[0]]); //  print_r($selectinfo);
        $this->assign('houruserarr2',$houruserarr[$ganmetnarr[1]]);
        $this->assign('houruserarr3',$houruserarr[$ganmetnarr[2]]);
        $this->assign('houruserarr4',$houruserarr[$ganmetnarr[3]]);
        $this->assign('data',$data);
        $this->assign('uersertype1',$uersertype1);
        $this->assign('uersertype2',$uersertype2);
        $this->assign('uersertype3',$uersertype3);
        $this->assign('tablelist',$tablelist);
        $this->assign('gamelist',$gamelist);
        $this->assign('time_start',$datatime1s);
        $this->assign('time_end',$datatime2s);
        $this->assign('colist',$colist);
        $this->assign('egtlist',$egtlist);
        $this->assign('tableid',$id);
        $this->display();
    }
    
    //用户留存(tab)
    public function indextableuser(){
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
        
        $datatime1s = (string)date("Y-m-01",$time1); 
        $datatime2s =(string)date("Y-m-d",$time2);
        $data = array('appid','coid'); 
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'utf8name'); 
        $selectwhere=$selectwhere_map['0'];         //条件为勾选框的条件  //将$_get=1 的值 赋值给 如: $selectwhere['telecomname']=1
        //$wheregame=explode(',',$selectwhere['appid']);
        //  $whereco=explode(',',$selectwhere['coid']);
        //if($wheregame[0]!="null"){
        //    $wherearr['appid']=array('in',$wheregame);
        //}
        //if($whereco[0]!="null"){
        //    $wherearr['coid']=array('in',$whereco);
        //}
        $selectwhere['stat_time'] = array(array('egt',$datatime1s),array('elt',$datatime2s)); 
        $id     =   I('get.id',1);
        $tablename='StatRemain';
        $tablenametoo='2016_11_2_ordercount';
        $gametablename='gamelist';
        $cotablename='colist';
        $dayusertype= \PublicData::$dayusertype;
        $gamelist =  M($gametablename)->select();       //游戏列表
        $colist =  M($cotablename)->select();       //厂商列表
        $game =  M($tablename.' t')->where($selectwhere)->field('t.appid,t.coid,t.stat_time,t.second_day')->select();
        //     print_r($a=M()->_sql());
        if($id==1){ //次日留存
            $datainfo= M($tablename.' t')->where($selectwhere)->field('t.appid,t.coid,t.stat_time,t.second_dru,second_day,t.dru')->select();
             //print_r($a=M()->_sql());
            $dayusertypename=$dayusertype[0]['name'];
        }
        if($id==2 ){    //三日留存
            $datainfo= M($tablename.' t')->where($selectwhere)->field('t.appid,t.coid,t.stat_time,t.dru,t.four_dru as third_dru,t.four_day as third_day')->select();
            $dayusertypename=$dayusertype[1]['name'];
        }
        if($id==3 ){        //七日留存
            $datainfo= M($tablename.' t')->where($selectwhere)->field('t.appid,t.coid,t.stat_time,t.dru,t.eight_dru as seventh_dru,t.eight_day as seventh_day')->select();
            $dayusertypename=$dayusertype[2]['name'];
        }
      
        for($i = 01;$i<32;$i++)
        {
            $hourlist[$i] = $i;     //天
        }  
        $houruserarr=array();

            foreach($hourlist as $k1=>$v){           //省份
                        foreach($datainfo as $k2=>$v2){
                             $userday=explode('-',$v2['stat_time']);
                                    if($userday[2]== $v){
                                        if($id==1){
                                            $houruserarr[$v]['second_day']=(double)$v2['second_day'];         //次日留存率
                                            $houruserarr[$v]['second_dru']=(int)$v2['second_dru'];                      //次日留存用户
                                            $houruserarr[$v]['dru']=(int)$v2['dru'];                  //用户数
                                        }else if($id==2){
                                            $houruserarr[$v]['third_day']=(double)$v2['third_day'];         //三日留存率
                                            $houruserarr[$v]['third_dru']=(int)$v2['third_dru'];                      //三日留存用户
                                            $houruserarr[$v]['dru']=(int)$v2['dru'];                  //用户数
                                        }else if($id==3){
                                            $houruserarr[$v]['seventh_day']=(double)$v2['seventh_day'];         //七日留存率
                                            $houruserarr[$v]['seventh_dru']=(int)$v2['seventh_dru'];                      //七日留存用户
                                            $houruserarr[$v]['dru']=(int)$v2['dru'];                  //用户数
                                        }
                                        $houruserarr[$v]['time']=$v; 
                                    }
                           }
                          if(! $houruserarr[$v]['time']){
                                $houruserarr[$v]['time']=$v; 
                            }
                            if($id==1){
                                if(! $houruserarr[$v]['second_day']){
                                    $houruserarr[$v]['second_day']=(double)0;       //次日留存率
                                   
                                }
                                if(!  $houruserarr[$v]['second_dru']){
                                    $houruserarr[$v]['second_dru'] =(int)0;         //次日留存用户
                                }
                                if(!  $houruserarr[$v]['dru']){
                                    $houruserarr[$v]['dru'] =(int)0;         //次日新登用户
                                }
                            }else if($id==2){
                                if(! $houruserarr[$v]['third_day']){
                                    $houruserarr[$v]['third_day']=(double)0;         //三日留存率
                                    
                                }
                                if(!  $houruserarr[$v]['third_dru']){
                                    $houruserarr[$v]['third_dru'] =(int)0;         //三日留存用户
                                }
                                if(! $houruserarr[$v]['dru']){
                                    $houruserarr[$v]['dru'] =(int)0;         //三日新登用户
                                }
                            }else if($id==3){
                                if(! $houruserarr[$v]['seventh_day']){
                                    $houruserarr[$v]['seventh_day']=(double)0;         //七日留存率
                                    
                                }
                                if(! $houruserarr[$v]['seventh_dru']){
                                    $houruserarr[$v]['seventh_dru'] =(int)0;         //七日留存用户
                                }
                                if(!  $houruserarr[$v]['dru']){
                                    $houruserarr[$v]['dru'] =(int)0;         //七日新登用户
                                }
                            }

              
            }
        $telistgame1 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[0].'"')->select();
        $telistgame1 = $telistgame1[0]['name'];
        $telistgame2 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[1].'"')->select();
        $telistgame2 = $telistgame2[0]['name'];
        $telistgame3 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[2].'"')->select();
        $telistgame3 = $telistgame3[0]['name'];
        $telistgame4 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[3].'"')->select();
        $telistgame4 = $telistgame4[0]['name'];
        $data = I('get.');
        foreach($gamelist as $k=>$v){
                  if($v['appid']==$data['appid']){
                      $gamelist[$k]['id2'] = $data['appid'];
                    }
        }
        foreach($colist as $k=>$v){
                    if($v['coid']==$data['coid']){
                        $colist[$k]['id2'] = $data['coid'];
                    }
        }
        
        $tablelist = \PublicData::$usertab; //tab列表名称
        $egtlist = \PublicData::$egtlist;
        $uersertype = \PublicData::$uersertype;
        $uersertype1=$uersertype[0]['name'];
        $uersertype2=$uersertype[1]['name'];
        $uersertype3=$uersertype[2]['name'];
     
        $this->assign('dayusertypename',$dayusertypename);      //留存名称
        $this->assign('telistgame1',$telistgame1);
        $this->assign('telistgame2',$telistgame2);
        $this->assign('telistgame3',$telistgame3);
        $this->assign('telistgame4',$telistgame4);
        $this->assign('houruserarr1',$houruserarr); //  print_r($selectinfo);
      //  $this->assign('houruserarr2',$houruserarr[$ganmetnarr[1]]);
      //  $this->assign('houruserarr3',$houruserarr[$ganmetnarr[2]]);
      //  $this->assign('houruserarr4',$houruserarr[$ganmetnarr[3]]);
        $this->assign('data',$data);
        $this->assign('uersertype1',$uersertype1);
        $this->assign('uersertype2',$uersertype2);
        $this->assign('uersertype3',$uersertype3);
        $this->assign('tablelist',$tablelist);
        $this->assign('gamelist',$gamelist);
        $this->assign('time_start',$datatime1s);
        $this->assign('time_end',$datatime2s);
        $this->assign('colist',$colist);
        $this->assign('egtlist',$egtlist);
        $this->assign('tableid',$id);
        
        
    
    
       $this->display();
    }
    
    //用户留存(tab)
    public function ajaxdatainfo(){
            $coidpost=I('post.coid');
            $colist= M('colist')->field('name,appid')->where(array('coid'=>$coidpost))->select();
            $appid=explode('_',$colist[0]['appid']);
             unset($appid[0]);
          //  print_r($a=M()->_sql());
             $jsdata=array();
            $gamelist= M('gamelist')->field('name,appid')->select();
              foreach($appid as $k=>$v){
                    foreach($gamelist as $k1=>$v1){
                           if($v1['appid']==$v){
                                       $jsdata[$k1]['appid']= $v;
                                       $jsdata[$k1]['name'] = $v1['name'];
                                        
                            }
                    }
                }                   
              $this->ajaxReturn($jsdata);
      
    
    }
    
    //留存图
    public function indexthirddayuser(){
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
            $datatime1s = (string)date("Y-m-d",$time1); 
            $datatime2s =(string)date("Y-m-d",$time2);
            $data = array('appid','coid'); 
            $selectwhere_map = \PublicFunction::getClickSeachData($data,'utf8name'); 
            $selectwhere=$selectwhere_map['0'];         //条件为勾选框的条件  //将$_get=1 的值 赋值给 如: $selectwhere['telecomname']=1
            //$wheregame=explode(',',$selectwhere['appid']);
            //  $whereco=explode(',',$selectwhere['coid']);
            //if($wheregame[0]!="null"){
            //    $wherearr['appid']=array('in',$wheregame);
            //}
            //if($whereco[0]!="null"){
            //    $wherearr['coid']=array('in',$whereco);
            //}
            $selectwhere['stat_time'] = array(array('egt',$datatime1s),array('elt',$datatime2s)); 
            $id     =   I('get.id',1);
            $tablename='StatRemain';
            $tablenametoo='2016_11_2_ordercount';
            $gametablename='gamelist';
            $cotablename='colist';
            $dayusertype= \PublicData::$dayusertype;
            $gamelist =  M($gametablename)->select();       //游戏列表
            $colist =  M($cotablename)->select();       //厂商列表
            $game =  M($tablename.' t')->where($selectwhere)->field('t.appid,t.coid,t.stat_time,t.second_day')->select();
            //     print_r($a=M()->_sql());
            $datainfo= M($tablename.' t')->where($selectwhere)->field('appid,t.coid,t.stat_time,t.second_day,t.second_dru,t.third_day,t.third_dru,t.four_day,t.four_dru,t.five_day,
            t.five_dru,t.six_day,t.six_dru,t.seventh_day,t.seventh_dru,t.eight_day,t.eight_dru,t.fourteen_day,t.fourteen_dru,t.thirty_day,t.thirty_dru')->select();    //留存
           $a=M()->_sql();
            $dayusertypename=$dayusertype[0]['name'];
            
             foreach($datainfo as $k=> $v2){
                       $houruserar[2]['second_day']=(double)$v2['second_day'];        //留存率
                        $houruserar[2]['third_day']=(double)$v2['third_day'];    //二留存率
                        $houruserar[2]['four_day']=(double)$v2['four_day'];    //三留存率
                        $houruserar[2]['five_day']=(double)$v2['five_day'];    //四留存率
                        $houruserar[2]['six_day']=(double)$v2['six_day'];    //五留存率
                        $houruserar[2]['seventh_day']=(double)$v2['seventh_day'];    //六留存率
                        $houruserar[2]['eight_day']=(double)$v2['eight_day'];    //七日留存率
                        $houruserar[2]['fourteen_day']=(double)$v2['fourteen_day'];    //14日留存率
                        $houruserar[2]['thirty_day']=(double)$v2['thirty_day'];    //30日留存率
                        $houruserar[2]['dru']=(int)$v2['second_dru'];         //次日留存
                        $houruserar[2]['third_dru']=(int)$v2['third_dru'];     //二日留存
                        $houruserar[2]['four_dru']=(int)$v2['four_dru'];      //三日留存
                        $houruserar[2]['five_dru']=(int)$v2['five_dru'];            //四日留存
                        $houruserar[2]['six_dru']=(int)$v2['six_dru'];        //五日留存
                        $houruserar[2]['seventh_dru']=(int)$v2['seventh_dru'];      //六日留存
                        $houruserar[2]['eight_dru']=(int)$v2['eight_dru'];            //七日留存
                        $houruserar[2]['fourteen_dru']=(int)$v2['fourteen_dru'];     //14日留存
                        $houruserar[2]['thirty_dru']=(int)$v2['thirty_dru'];            //30日留存
                        $houruserar[2]['time']=$v2['stat_time']; 
        
            }
            //for($i = 01;$i<32;$i++)
            //{
            //    $hourlist[$i] = $i;     //天
            //}  
            //     foreach($datainfo as $k2=>$v2){
            //                $userday=explode('-',$v2['stat_time']);
            //                $v=$userday[2];
            //                $datainfo[$k2]['stat_time'] = (int)$v;          //
            //     }
                 
            //     foreach($hourlist as $k=>$v){
            //            foreach($datainfo as $k2=>$v2){
            //                    if($datainfo[$k2]['stat_time'] == $v){
            //                        $dday[$k] = $datainfo[$k2];
            //                    }
            //             }
            //            if(!$dday[$k]){
            //                $dday[$k]['appid'] = 0;
            //                $dday[$k]['coid'] = 0;
            //                $dday[$k]['stat_time'] =$k;
            //                $dday[$k]['second_day'] = 0;
            //                $dday[$k]['second_dru'] = 0;
            //                $dday[$k]['third_day'] = 0;
            //                $dday[$k]['third_dru'] = 0;
            //                $dday[$k]['four_day'] = 0;
            //                $dday[$k]['four_dru'] = 0;
            //                $dday[$k]['five_day'] = 0;
            //                $dday[$k]['five_dru'] = 0;
            //                $dday[$k]['six_day'] = 0;
            //                $dday[$k]['six_dru'] = 0;
            //                $dday[$k]['seventh_day'] = 0;
            //                $dday[$k]['seventh_dru'] = 0;
            //                $dday[$k]['eight_day'] = 0;
            //                $dday[$k]['eight_dru'] = 0;
            //                $dday[$k]['fourteen_day'] = 0;
            //                $dday[$k]['fourteen_dru'] = 0;
            //                $dday[$k]['thirty_day'] = 0;
            //                $dday[$k]['thirty_dru'] = 0;
            //            }
            //     }
             
                // if($dday[$k]['stat_time'] =$datatime1s){
             //    foreach($dday as $k2=>$v2){
             //        if($v2['stat_time'] ==2 ){
             //                       $houruserar[2]['second_day']=(double)$v2['second_day'];        //留存率
             //                       $houruserar[2]['third_day']=(double)$v2['third_day'];    //二留存率
             //                       $houruserar[2]['four_day']=(double)$v2['four_day'];    //三留存率
             //                       $houruserar[2]['five_day']=(double)$v2['five_day'];    //四留存率
             //                       $houruserar[2]['six_day']=(double)$v2['six_day'];    //五留存率
             //                       $houruserar[2]['seventh_day']=(double)$v2['seventh_day'];    //六留存率
             //                       $houruserar[2]['eight_day']=(double)$v2['eight_day'];    //七日留存率
             //                       $houruserar[2]['fourteen_day']=(double)$v2['fourteen_day'];    //14日留存率
             //                       $houruserar[2]['thirty_day']=(double)$v2['thirty_day'];    //30日留存率
             //                       $houruserar[2]['dru']=(int)$v2['second_dru'];         //次日留存
             //                       $houruserar[2]['third_dru']=(int)$v2['third_dru'];     //二日留存
             //                       $houruserar[2]['four_dru']=(int)$v2['four_dru'];      //三日留存
             //                       $houruserar[2]['five_dru']=(int)$v2['five_dru'];            //四日留存
             //                       $houruserar[2]['six_dru']=(int)$v2['six_dru'];        //五日留存
             //                       $houruserar[2]['seventh_dru']=(int)$v2['seventh_dru'];      //六日留存
             //                       $houruserar[2]['eight_dru']=(int)$v2['eight_dru'];            //七日留存
             //                       $houruserar[2]['fourteen_dru']=(int)$v2['fourteen_dru'];     //14日留存
             //                       $houruserar[2]['thirty_dru']=(int)$v2['thirty_dru'];            //30日留存
             //                       $houruserar[2]['time']=$v2['stat_time']; 
             //        }elseif($v2['stat_time'] ==3 ){
             //                       $houruserar[3]['second_day']=(double)$v2['second_day'];        //留存率
             //                        $houruserar[3]['third_day']=(double)$v2['third_day'];    //二留存率
             //                        $houruserar[3]['four_day']=(double)$v2['four_day'];    //三留存率
             //                        $houruserar[3]['five_day']=(double)$v2['five_day'];    //四留存率
             //                        $houruserar[3]['six_day']=(double)$v2['six_day'];    //五留存率
             //                        $houruserar[3]['seventh_day']=(double)$v2['seventh_day'];    //六留存率
             //                        $houruserar[3]['eight_day']=(double)$v2['eight_day'];    //七日留存率
             //                        $houruserar[3]['fourteen_day']=(double)$v2['fourteen_day'];    //14日留存率
             //                        $houruserar[3]['thirty_day']=(double)$v2['thirty_day'];    //30日留存率
             //                        $houruserar[3]['dru']=(int)$v2['second_dru'];         //次日留存
             //                        $houruserar[3]['third_dru']=(int)$v2['third_dru'];     //二日留存
             //                        $houruserar[3]['four_dru']=(int)$v2['four_dru'];      //三日留存
             //                        $houruserar[3]['five_dru']=(int)$v2['five_dru'];            //四日留存
             //                        $houruserar[3]['six_dru']=(int)$v2['six_dru'];        //五日留存
             //                        $houruserar[3]['seventh_dru']=(int)$v2['seventh_dru'];      //六日留存
             //                        $houruserar[3]['eight_dru']=(int)$v2['eight_dru'];            //七日留存
             //                        $houruserar[3]['fourteen_dru']=(int)$v2['fourteen_dru'];     //14日留存
             //                        $houruserar[3]['thirty_dru']=(int)$v2['thirty_dru'];            //30日留存                                     
             //                        $houruserar[3]['time']=$v2['stat_time']; 
             //        }elseif($v2['stat_time'] ==4 ){
             //                       $houruserar[4]['second_day']=(double)$v2['second_day'];        //留存率
             //                        $houruserar[4]['third_day']=(double)$v2['third_day'];    //二留存率
             //                        $houruserar[4]['four_day']=(double)$v2['four_day'];    //三留存率
             //                        $houruserar[4]['five_day']=(double)$v2['five_day'];    //四留存率
             //                        $houruserar[4]['six_day']=(double)$v2['six_day'];    //五留存率
             //                        $houruserar[4]['seventh_day']=(double)$v2['seventh_day'];    //六留存率
             //                        $houruserar[4]['eight_day']=(double)$v2['eight_day'];    //七日留存率
             //                        $houruserar[4]['fourteen_day']=(double)$v2['fourteen_day'];    //14日留存率
             //                        $houruserar[4]['thirty_day']=(double)$v2['thirty_day'];    //30日留存率
             //                       $houruserar[4]['dru']=(int)$v2['second_dru'];         //次日留存
             //                       $houruserar[4]['third_dru']=(int)$v2['third_dru'];     //二日留存
             //                       $houruserar[4]['four_dru']=(int)$v2['four_dru'];      //三日留存
             //                       $houruserar[4]['five_dru']=(int)$v2['five_dru'];            //四日留存
             //                       $houruserar[4]['six_dru']=(int)$v2['six_dru'];        //五日留存
             //                       $houruserar[4]['seventh_dru']=(int)$v2['seventh_dru'];      //六日留存
             //                       $houruserar[4]['eight_dru']=(int)$v2['eight_dru'];            //七日留存
             //                       $houruserar[4]['fourteen_dru']=(int)$v2['fourteen_dru'];     //14日留存
             //                       $houruserar[4]['thirty_dru']=(int)$v2['thirty_dru'];            //30日留存
             //                       $houruserar[4]['time']=$v2['stat_time']; 
             //        }elseif($v2['stat_time'] ==5 ){
             //                        $houruserar[5]['second_day']=(double)$v2['second_day'];        //留存率
             //                        $houruserar[5]['third_day']=(double)$v2['third_day'];    //二留存率
             //                        $houruserar[5]['four_day']=(double)$v2['four_day'];    //三留存率
             //                        $houruserar[5]['five_day']=(double)$v2['five_day'];    //四留存率
             //                        $houruserar[5]['six_day']=(double)$v2['six_day'];    //五留存率
             //                        $houruserar[5]['seventh_day']=(double)$v2['seventh_day'];    //六留存率
             //                        $houruserar[5]['eight_day']=(double)$v2['eight_day'];    //七日留存率
             //                        $houruserar[5]['fourteen_day']=(double)$v2['fourteen_day'];    //14日留存率
             //                        $houruserar[5]['thirty_day']=(double)$v2['thirty_day'];    //30日留存率
             //                        $houruserar[5]['dru']=(int)$v2['second_dru'];         //次日留存
             //                        $houruserar[5]['third_dru']=(int)$v2['third_dru'];     //二日留存
             //                        $houruserar[5]['four_dru']=(int)$v2['four_dru'];      //三日留存
             //                        $houruserar[5]['five_dru']=(int)$v2['five_dru'];            //四日留存
             //                        $houruserar[5]['six_dru']=(int)$v2['six_dru'];        //五日留存
             //                        $houruserar[5]['seventh_dru']=(int)$v2['seventh_dru'];      //六日留存
             //                        $houruserar[5]['eight_dru']=(int)$v2['eight_dru'];            //七日留存
             //                        $houruserar[5]['fourteen_dru']=(int)$v2['fourteen_dru'];     //14日留存
             //                        $houruserar[5]['thirty_dru']=(int)$v2['thirty_dru'];            //30日留存
             //                        $houruserar[5]['time']=$v2['stat_time']; 
             //        }elseif($v2['stat_time'] ==6 ){
             //                        $houruserar[6]['second_day']=(double)$v2['second_day'];        //留存率
             //                        $houruserar[6]['third_day']=(double)$v2['third_day'];    //二留存率
             //                        $houruserar[6]['four_day']=(double)$v2['four_day'];    //三留存率
             //                        $houruserar[6]['five_day']=(double)$v2['five_day'];    //四留存率
             //                        $houruserar[6]['six_day']=(double)$v2['six_day'];    //五留存率
             //                        $houruserar[6]['seventh_day']=(double)$v2['seventh_day'];    //六留存率
             //                        $houruserar[6]['eight_day']=(double)$v2['eight_day'];    //七日留存率
             //                        $houruserar[6]['fourteen_day']=(double)$v2['fourteen_day'];    //14日留存率
             //                        $houruserar[6]['thirty_day']=(double)$v2['thirty_day'];    //30日留存率
             //                        $houruserar[6]['dru']=(int)$v2['second_dru'];         //次日留存
             //                        $houruserar[6]['third_dru']=(int)$v2['third_dru'];     //二日留存
             //                        $houruserar[6]['four_dru']=(int)$v2['four_dru'];      //三日留存
             //                        $houruserar[6]['five_dru']=(int)$v2['five_dru'];            //四日留存
             //                        $houruserar[6]['six_dru']=(int)$v2['six_dru'];        //五日留存
             //                        $houruserar[6]['seventh_dru']=(int)$v2['seventh_dru'];      //六日留存
             //                        $houruserar[6]['eight_dru']=(int)$v2['eight_dru'];            //七日留存
             //                        $houruserar[6]['fourteen_dru']=(int)$v2['fourteen_dru'];     //14日留存
             //                        $houruserar[6]['thirty_dru']=(int)$v2['thirty_dru'];            //30日留存
             //                        $houruserar[6]['time']=$v2['stat_time']; 
             //        }elseif($v2['stat_time'] ==7 ){
             //                        $houruserar[7]['second_day']=(double)$v2['second_day'];        //留存率
             //                        $houruserar[7]['third_day']=(double)$v2['third_day'];    //二留存率
             //                        $houruserar[7]['four_day']=(double)$v2['four_day'];    //三留存率
             //                        $houruserar[7]['five_day']=(double)$v2['five_day'];    //四留存率
             //                        $houruserar[7]['six_day']=(double)$v2['six_day'];    //五留存率
             //                        $houruserar[7]['seventh_day']=(double)$v2['seventh_day'];    //六留存率
             //                        $houruserar[7]['eight_day']=(double)$v2['eight_day'];    //七日留存率
             //                        $houruserar[7]['fourteen_day']=(double)$v2['fourteen_day'];    //14日留存率
             //                        $houruserar[7]['thirty_day']=(double)$v2['thirty_day'];    //30日留存率
             //                        $houruserar[7]['dru']=(int)$v2['second_dru'];         //次日留存
             //                        $houruserar[7]['third_dru']=(int)$v2['third_dru'];     //二日留存
             //                        $houruserar[7]['four_dru']=(int)$v2['four_dru'];      //三日留存
             //                        $houruserar[7]['five_dru']=(int)$v2['five_dru'];            //四日留存
             //                        $houruserar[7]['six_dru']=(int)$v2['six_dru'];        //五日留存
             //                        $houruserar[7]['seventh_dru']=(int)$v2['seventh_dru'];      //六日留存
             //                        $houruserar[7]['eight_dru']=(int)$v2['eight_dru'];            //七日留存
             //                        $houruserar[7]['fourteen_dru']=(int)$v2['fourteen_dru'];     //14日留存
             //                        $houruserar[7]['thirty_dru']=(int)$v2['thirty_dru'];            //30日留存
             //                        $houruserar[7]['time']=$v2['stat_time']; 
             //        }elseif($v2['stat_time'] ==8 ){
             //                        $houruserar[8]['second_day']=(double)$v2['second_day'];        //留存率
             //                        $houruserar[8]['third_day']=(double)$v2['third_day'];    //二留存率
             //                        $houruserar[8]['four_day']=(double)$v2['four_day'];    //三留存率
             //                        $houruserar[8]['five_day']=(double)$v2['five_day'];    //四留存率
             //                        $houruserar[8]['six_day']=(double)$v2['six_day'];    //五留存率
             //                        $houruserar[8]['seventh_day']=(double)$v2['seventh_day'];    //六留存率
             //                        $houruserar[8]['eight_day']=(double)$v2['eight_day'];    //七日留存率
             //                        $houruserar[8]['fourteen_day']=(double)$v2['fourteen_day'];    //14日留存率
             //                        $houruserar[8]['thirty_day']=(double)$v2['thirty_day'];    //30日留存率
             //                        $houruserar[8]['dru']=(int)$v2['second_dru'];         //次日留存
             //                        $houruserar[8]['third_dru']=(int)$v2['third_dru'];     //二日留存
             //                        $houruserar[8]['four_dru']=(int)$v2['four_dru'];      //三日留存
             //                        $houruserar[8]['five_dru']=(int)$v2['five_dru'];            //四日留存
             //                        $houruserar[8]['six_dru']=(int)$v2['six_dru'];        //五日留存
             //                        $houruserar[8]['seventh_dru']=(int)$v2['seventh_dru'];      //六日留存
             //                        $houruserar[8]['eight_dru']=(int)$v2['eight_dru'];            //七日留存
             //                        $houruserar[8]['fourteen_dru']=(int)$v2['fourteen_dru'];     //14日留存
             //                        $houruserar[8]['thirty_dru']=(int)$v2['thirty_dru'];            //30日留存                               
             //                        $houruserar[8]['time']=$v2['stat_time'];  
             //        }elseif($v2['stat_time']==15 ){
             //                        $houruserar[15]['second_day']=(double)$v2['second_day'];        //留存率
             //                        $houruserar[15]['third_day']=(double)$v2['third_day'];    //二留存率
             //                        $houruserar[15]['four_day']=(double)$v2['four_day'];    //三留存率
             //                        $houruserar[15]['five_day']=(double)$v2['five_day'];    //四留存率
             //                        $houruserar[15]['six_day']=(double)$v2['six_day'];    //五留存率
             //                        $houruserar[15]['seventh_day']=(double)$v2['seventh_day'];    //六留存率
             //                        $houruserar[15]['eight_day']=(double)$v2['eight_day'];    //七日留存率
             //                        $houruserar[15]['fourteen_day']=(double)$v2['fourteen_day'];    //14日留存率
             //                        $houruserar[15]['thirty_day']=(double)$v2['thirty_day'];    //30日留存率
             //                       $houruserar[15]['dru']=(int)$v2['second_dru'];         //次日留存
             //                       $houruserar[15]['third_dru']=(int)$v2['third_dru'];     //二日留存
             //                       $houruserar[15]['four_dru']=(int)$v2['four_dru'];      //三日留存
             //                       $houruserar[15]['five_dru']=(int)$v2['five_dru'];            //四日留存
             //                       $houruserar[15]['six_dru']=(int)$v2['six_dru'];        //五日留存
             //                       $houruserar[15]['seventh_dru']=(int)$v2['seventh_dru'];      //六日留存
             //                       $houruserar[15]['eight_dru']=(int)$v2['eight_dru'];            //七日留存
             //                       $houruserar[15]['fourteen_dru']=(int)$v2['fourteen_dru'];     //14日留存
             //                       $houruserar[15]['thirty_dru']=(int)$v2['thirty_dru'];            //30日留存
             //                       $houruserar[15]['time']=$v2['stat_time']; 
             //        }elseif($v2['stat_time'] == 31){
             //                        $houruserar[31]['second_day']=(double)$v2['second_day'];        //留存率
             //                        $houruserar[31]['third_day']=(double)$v2['third_day'];    //二留存率
             //                        $houruserar[31]['four_day']=(double)$v2['four_day'];    //三留存率
             //                        $houruserar[31]['five_day']=(double)$v2['five_day'];    //四留存率
             //                        $houruserar[31]['six_day']=(double)$v2['six_day'];    //五留存率
             //                        $houruserar[31]['seventh_day']=(double)$v2['seventh_day'];    //六留存率
             //                        $houruserar[31]['eight_day']=(double)$v2['eight_day'];    //七日留存率
             //                        $houruserar[31]['fourteen_day']=(double)$v2['fourteen_day'];    //14日留存率
             //                        $houruserar[31]['thirty_day']=(double)$v2['thirty_day'];    //30日留存率
             //                       $houruserar[31]['dru']=(int)$v2['second_dru'];         //次日留存
             //                       $houruserar[31]['third_dru']=(int)$v2['third_dru'];     //二日留存
             //                       $houruserar[31]['four_dru']=(int)$v2['four_dru'];      //三日留存
             //                       $houruserar[31]['five_dru']=(int)$v2['five_dru'];            //四日留存
             //                       $houruserar[31]['six_dru']=(int)$v2['six_dru'];        //五日留存
             //                       $houruserar[31]['seventh_dru']=(int)$v2['seventh_dru'];      //六日留存
             //                       $houruserar[31]['eight_dru']=(int)$v2['eight_dru'];            //七日留存
             //                       $houruserar[31]['fourteen_dru']=(int)$v2['fourteen_dru'];     //14日留存
             //                       $houruserar[31]['thirty_dru']=(int)$v2['thirty_dru'];            //30日留存
             //                       $houruserar[31]['time']=$v2['stat_time']; 
             //                   }
                            
             //}
            // print_r($houruserar);
            
            $telistgame1 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[0].'"')->select();
            $telistgame1 = $telistgame1[0]['name'];
            $telistgame2 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[1].'"')->select();
            $telistgame2 = $telistgame2[0]['name'];
            $telistgame3 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[2].'"')->select();
            $telistgame3 = $telistgame3[0]['name'];
            $telistgame4 =  M($gametablename)->field('name,appid')->where('appid="'.$ganmetnarr[3].'"')->select();
            $telistgame4 = $telistgame4[0]['name'];
            $data = I('get.');
            foreach($gamelist as $k=>$v){            
                         if($v['appid']==$data['appid']){
                             $gamelist[$k]['id2'] = $data['appid'];
                        }
            }
            foreach($colist as $k=>$v){

                       if($v['coid']==$data['coid']){
                           $colist[$k]['id2'] = $data['coid'];
                        }
            }
            
            //print_r($houruserarr);
            $tablelist = \PublicData::$usertabuser; //tab列表名称
            $egtlist = \PublicData::$egtlist;
            $uersertype = \PublicData::$uersertype;
            $uersertype1=$uersertype[0]['name'];
            $uersertype2=$uersertype[1]['name'];
            $uersertype3=$uersertype[2]['name'];
            $this->assign('dayusertypename',$dayusertypename);      //留存名称
            $this->assign('telistgame1',$telistgame1);
            //$this->assign('houruserarr',$houruserar[$datainfo[0]['stat_time']]); //  print_r($selectinfo);
            $this->assign('houruserarr',$houruserar[2]);
            $this->assign('data',$data);
            $this->assign('uersertype1',$uersertype1);
            $this->assign('uersertype2',$uersertype2);
            $this->assign('uersertype3',$uersertype3);
            $this->assign('tablelist',$tablelist);
            $this->assign('gamelist',$gamelist);
            $this->assign('time_start',$datatime1s);
            $this->assign('time_end',$datatime2s);
            $this->assign('colist',$colist);
            $this->assign('egtlist',$egtlist);
            $this->assign('tableid',$id);
            $this->display();
        
    }
    
    
}
