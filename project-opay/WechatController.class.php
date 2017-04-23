<?php

/**
 * WechatController short summary.
 *
 * WechatController description.
 *
 * @version 1.0
 * @author bksen_sjl
 */
namespace Admin\Controller;
include_once"PublicData.php";
include_once"RequestData.php";
include_once"PublicFunction.php";
class WechatController extends RequestData
{

    //第三方微信支付
    public function wxpay($coid='',$appid='',$propid='',$extData='',$telecom='',$state='',$sid='')
    {
        $this->Tpaycodeweixin($coid,$appid,$propid,$extData,$telecom,$state,$sid);
    }
    
    public function wxpay2($coid='',$appid='',$propid='',$extData='',$telecom='',$state=''){
        
        $this->Tpaycodeweixin1($coid,$appid,$propid,$extData,$telecom,$state);
        $this->wxinit();   
    }
    public function Tpaycodeweixin1($coid='',$appid='',$propid='',$extData='',$telecom='',$state=''){
        $coidarr = I('coid');
        $appid = I('appid');
        $propid = I('propid');
        $extData = I('extData');
        $telecom = I('telecom');
        $state = I('state');
        $time = time();
        
        $this->dateformonth1 = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth1 = $this->dateformonth1.'2'.'_'; 
        $wxtablename1 = $this->dateformonth1.'vnorder';
        $wxlist = M($wxtablename1)->where(array('maxid'=>$extData))->select();
        $usebksid = $wxlist[0]['bksid'];
        $uselist = M('usercount')->where(array('bksid'=>$usebksid))->select();
        $this->mobile= $uselist[0]['mobile'];
        $this->extData= $uselist[0]['extdata'];
        $this->iccid= $uselist[0]['iccid'];
        $this->os_info= $uselist[0]['os_info'];
        $this->os_model= $uselist[0]['os_model'];
        $this->net_info= $uselist[0]['net_info'];
        $this->city= $uselist[0]['city'];
        $this->mark= $uselist[0]['mark'];
        $this->ip= $uselist[0]['ip'];
        $this->imsi= $uselist[0]['imsi'];
        $this->imei= $uselist[0]['imei'];
        $this->newuser= $uselist[0]['newuser'];
        $this->saleuser= $uselist[0]['saleuser'];
        $this->coid= $uselist[0]['coid'];
        $this->appid= $uselist[0]['appid'];
        $this->egt= 5;
        $this->propid= $uselist[0]['propid'];
        $usercount['newuser'] = 0;
        $usercount['saleuser'] = 0;
        $usercount['daynewuser'] = 0;
        $usercount['daysaleuser'] = 0;
        $usercountupdate = M('usercount')->where(array('bksid'=>$usebksid))->save($usercount);
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth = $this->dateformonth.'2'.'_';  
        $wxtablename = $this->dateformonth.'extdata';
        $id = 0;
        $wxdata = array('coorderid'=>$extData);
        $id = M($wxtablename)->add($wxdata);
        $b =  M($wxtablename)->_Sql();
        $this->maxid = $id;
        $selectwhere =array('id'=>$id);   
        $tablename2=$this->dateformonth.'extdata';
        $this->coinfo = $this->selectwhereformysql($tablename2,'bksid',$selectwhere);
        $this->coinfo = $this->coinfo['0'];
        $this->extDatatoo= $this->coinfo['bksid'];
        $wxextData = $this->extDatatoo;
        $this->cpgamename =  $this->appid;
        $this->cpconame = $this->coid;
        $this->initstatus=2;
        $this->user_count1(array($this->mobile, $this->extData,$this->iccid,$this->city,$this->os_info,$this->os_model,$this->net_info,$this->mark,$this->ip,$this->imsi,$this->imei,$this->newuser,$this->saleuser,$this->appid,$this->coid,$this->egt,$this->propid));
        $this->coid=$coidarr;
        $this->appid=$appid;
        $this->propid=$propid;
        $this->extData=$extData;
        $this->maxid2=$this->extData;
        $this->egt=5;
        $this->wxstatus=$state;
        $time = time();
        $imsi = $wxlist[0]['imsi'];
        $imei = $wxlist[0]['imei'];
        $city = $wxlist[0]['city'];
        $iccid_city = $wxlist[0]['iccid_city'];
        $this->imsi=$imsi;
        $this->imei=$imei;
        $this->city=$city;
        $this->iccid_city = $iccid_city;
        
        $selectwherecogame = array('coid'=>$coid,'appid'=>$appid);
        $colistarr = M('cogameset')->field('status,appid,coid')->where($selectwherecogame)->select();
        if($colistarr==null){
            $selectwhereco = array('coid'=>$this->coid); //code对应渠道模块伪代码
            $cotablename =  'colist'; //代码分配表
            $telecomco = $this->selectwhereformysql($cotablename,'appid,coid,tpaypool',$selectwhereco);
            $this->telecomco=$telecomco['0'];
            $this->telecompool = $this->telecomco['tpaypool']; 
            $appid2= explode('_',$this->telecomco['appid']); 
            unset($appid2['0']);         
            foreach($appid2 as $k=>$value){
                if($value==$this->appid){
                    $this->cpgamename = $value; //游戏ID
                }
            }  
        }else{
            $cotablename =  'cogameset'; //代码分配表
            $telecomco = $this->selectwhereformysql($cotablename,'appid,coid,tpaypool',$selectwherecogame);
            $this->telecomco=$telecomco['0'];
            $this->telecompool = $this->telecomco['tpaypool']; 
            $this->cpgamename = $this->telecomco['appid']; 
        
        }
        $selectwhereprop = array('appid'=> $this->cpgamename,'propid'=>$this->propid); 
        $proptablename =  'proplist'; 
        $telecomprop = $this->selectwhereformysql($proptablename,'propid,gold,payprop,news',$selectwhereprop);
        $telecomprop=$telecomprop['0'];
        $this->telecomprop=$telecomprop;
        $fee=$this->telecomprop['gold'];
        $this->fee=$fee/100;
        $this->gameno = $this->telecomprop['payprop']; //计费代码
        $this->gameno = explode('_',$this->gameno);    
        $gamepropname = $this->telecomprop['news'];  //备注
        $gamepropname = explode('_',$gamepropname);
        $this->gamename = $gamepropname['0'];  //游戏名
        $arrm = "\r\n";
        $this->propname=str_replace($arrm,"",$gamepropname['1']);     //道具名
        $wechat = M('telecom')->field('news')->where(array('telecomname'=>$telecom))->select();
        // $a =  M('telecom')->_sql();
        $wechat = $wechat[0]['news'];
        $wechatlist = explode('_',$wechat);
        unset($wechatlist[0]);
        $appid = $wechatlist[1];
        $mch_id = $wechatlist[3];
        $body = $wechatlist[6];
        $key = $wechatlist[5];
       
        $url = 'http://bks.abksen.com/index.php/admin/fun/wechatback';
        $this->resultinfo= array('fee'=>$this->fee,'skuid'=>$this->gameno['0'],'mshopld'=>$this->gameno['1'],'info'=>$body,'paypic'=>$paypic,'appid'=>$appid,'mch_id'=>$mch_id,'body'=>$body,'key'=>$key,'wxextData'=>$wxextData,'url'=>$url);
        $datahqddz='wxhqddz';
        $this->codepattern='telecompool';
        $this->init_telecompool($htzfb,$datahqddz); //通道池自动化
        
       // echo json_encode($this->resultinfo);
        return;
        
    }

    public function Tpaycodeweixin($coidarr='',$appid='',$propid='',$extData='',$telecom='',$state='',$sid=''){
        $this->coid=$coidarr;
        $this->appid=$appid;
        $this->propid=$propid;
        $this->extData=$extData;
        $this->egt=5;
        $this->sid=$sid;
        $this->wxstatus=$state;
        $this->imsi='imsi';
        $this->imei='imei';
        $this->city='city';
        $this->iccid_city = "iccid_city";
        $selectwhereco = array('coid'=>$this->coid); //code对应渠道模块伪代码
        $cotablename =  'colist'; //代码分配表
        $telecomco = $this->selectwhereformysql($cotablename,'appid,coid,tpaypool',$selectwhereco);
        $this->telecomco=$telecomco['0'];
        $this->telecompool = $this->telecomco['tpaypool']; 
        $appid2= explode('_',$this->telecomco['appid']); 
        unset($appid2['0']);         
        foreach($appid2 as $k=>$value){
            if($value==$this->appid){
                $this->cpgamename = $value; //游戏ID
            }
        }      
        $selectwhereprop = array('appid'=> $this->cpgamename,'propid'=>$this->propid); 
        $proptablename =  'proplist'; 
        $telecomprop = $this->selectwhereformysql($proptablename,'propid,gold,payprop,news',$selectwhereprop);
        $telecomprop=$telecomprop['0'];
        $this->telecomprop=$telecomprop;
        $fee=$this->telecomprop['gold'];
        $this->fee=$fee/100;
        $this->gameno = $this->telecomprop['payprop']; //计费代码
        $this->gameno = explode('_',$this->gameno);    
        $gamepropname = $this->telecomprop['news'];  //备注
        $gamepropname = explode('_',$gamepropname);
        $this->gamename = $gamepropname['0'];  //游戏名
        $arrm = "\r\n";
        $this->propname=str_replace($arrm,"",$gamepropname['1']);     //道具名
          $wechat = M('telecom')->field('news')->where(array('telecomname'=>$telecom))->select();
       // $a =  M('telecom')->_sql();
        $wechat = $wechat[0]['news'];
        $wechatlist = explode('_',$wechat);
        unset($wechatlist[0]);
        $appid = $wechatlist[1];
        $mch_id = $wechatlist[3];
        $body = $wechatlist[6];
        $key = $wechatlist[5];
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth = $this->dateformonth.'2'.'_';  
        $wxtablename = $this->dateformonth.'extdata';
        $id = 0;
        $wxdata = array('coorderid'=>$this->extData);
        $id = M($wxtablename)->add($wxdata);
        $b =  M($wxtablename)->_Sql();
        $this->maxid = $id;
        $selectwhere =array('id'=>$id);   
        $tablename2=$this->dateformonth.'extdata';
        $this->coinfo = $this->selectwhereformysql($tablename2,'bksid',$selectwhere);
        $this->coinfo = $this->coinfo['0'];
        $this->extDatatoo=$this->coinfo['bksid'];
        $this->extDatatoo =$this->extDatatoo;
        $wxextData = $this->sid;
        $url = 'http://bks.abksen.com/index.php/admin/fun/wechatback';
        
        
        $this->resultinfo= array('fee'=>$this->fee,'skuid'=>$this->gameno['0'],'mshopld'=>$this->gameno['1'],'info'=>$body,'paypic'=>$paypic,'appid'=>$appid,'mch_id'=>$mch_id,'body'=>$body,'key'=>$key,'wxextData'=>$wxextData,'url'=>$url);
        $datahqddz='wxhqddz';
        $this->codepattern='telecompool';
        $this->init_telecompool($htzfb,$datahqddz); //通道池自动化
        
        echo json_encode($this->resultinfo);
        return;
    
    }
    
    
}
