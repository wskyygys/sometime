<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;
include_once"PublicData.php";
include_once"RequestData.php";
include_once"PublicFunction.php";
/**
 * 文件控制器
 * 主要用于下载模型的文件上传和下载
 */
//     cpid(*)  00000001
//      gameid(*)  通道代码
//      gameno(*)  计费代码 _后面的值
//      fee(*)      价格(元)
//      extparams(*)    CP自定义参数 长度十位（Length）
//      ip(*),iccid,tel   客户端传值  括号*为必填值
/*
 * error_status 说明
 * 1    ：IP为空
 * 2    : code错误
 * 3    : code代码不存在
 */

class FunController extends RequestData
{
 //imsi=460036481313440&imei=99000661501875&code=201510300002&mobile=&extData=text&iccid=89860315947552648753&ip=14.16.252.24
    /**
     * Summary of order1
     * @param mixed $imsi 国际移动用户识别码
     * @param mixed $imei 用户手机唯一标识
     * @param mixed $mobile 手机号
     * @param mixed $extData 扩展字段
     * @param mixed $iccid 
     * @param mixed $ip 客户端ip
     * @param mixed $os_info 操作系统版本
     * @param mixed $os_model 手机型号
     * @param mixed $net_info 联网方式
     * @param mixed $province 所在省份
     * @param mixed $coid 厂商ID
     * @param mixed $mark 所属手机品牌  
     * @param mixed $appid 游戏ID
     * @param mixed  $propid 道具ID
     *  @param mixed $operators 运营商 1： 电信 2：联通 3：移动  -1或者其他错误 
     */
    public function order1($imsi = '',$imei = '',$mobile='',$extData='',$iccid='',$ip='',$os_info='',$os_model='',$net_info='',$mark='',$province='',$bksen='',$coid='',$appid='',$propid='',$operators='')
    {
        $imsi=I('get.imsi');
        $imei=I('get.imei');
        $mobile=I('get.mobile');
        $extData=I('get.extData');
        $iccid=I('get.iccid');
        $ip=I('get.ip');
        $os_info=I('get.os_info');
        $os_model=I('get.os_model');
        $net_info=I('get.net_info');
        $mark=I('get.mark');
        $province=I('get.province');
        $bksen=I('get.bksen');
        $coid=I('get.coid');
        $appid=I('get.appid');
        $propid=I('get.propid');
        $operators=I('get.operators');
        $selectwhereco = array('coid'=>$coid,'appid'=>$appid);
        $colistarr = M('cogameset')->field('status,appid,coid')->where($selectwhereco)->select();       //查询厂商游戏定制表
        if($colistarr==null){       //$colistarr 为空就走厂商表 否则走厂商游戏定制
            $this->bksen = $bksen;
            $this->init_val($imsi,$imei,$mobile,$extData,$iccid,$ip,$province,$os_info,$os_model,$net_info,$mark,$coid,$appid,$propid,$operators);
            $this->CoData_once();   //厂商检测
             //$this->checkData_once();     //code检测 
            $this->init();
        }else{
            $this->bksen = $bksen;
            $this->cogameinit_val($imsi,$imei,$mobile,$extData,$iccid,$ip,$province,$os_info,$os_model,$net_info,$mark,$coid,$appid,$propid,$operators);
            $this->CogameData_once();   //游戏检测
            $this->init();
        }
    }
    public function bks_order1($imsi = '',$imei = '',$code = '',$mobile='',$extData='',$iccid='',$ip='',$os_info='',$os_model='',$net_info='',$province='',$bksen='')
    {
        $this->bksen = $bksen;
        G('begin3');    
        $this->init_val($imsi,$imei,$code,$mobile,$extData,$iccid,$ip,$province,$os_info,$os_model,$net_info);
        $this->checkData_once();
        $this->init();
        G('end3');
        $time =  G('begin3','end3',10);
      //  var_dump($time);
      //  var_dump($this->extData2);
    }
    /**
     * Summary of order1
     * @param mixed $imsi 国际移动用户识别码
     * @param mixed $imei 用户手机唯一标识
     * @param mixed $mobile 手机号
     * @param mixed $extData 扩展字段
     * @param mixed $iccid 
     * @param mixed $ip 客户端ip
     * @param mixed $os_info 操作系统版本
     * @param mixed $os_model 手机型号
     * @param mixed $net_info 联网方式
     * @param mixed $province 所在省份
     * @param mixed $coid 厂商ID
     * @param mixed $appid 游戏ID
     * @param mixed  $propid 道具ID
     *  @param mixed $operators 运营商 1： 电信 2：联通 3：移动  -1或者其他错误 
     */
    //广告栏目
    public function advapp($imsi='',$imei='',$mobile='',$extData='',$iccid='',$ip='',$province='',$os_info='',$os_model='',$net_info='',$mark='',$coid='',$appid='',$operators='')
    {
        //广告初始化
        $this->initsdk($imsi,$imei,$mobile,$extData,$iccid,$ip,$province,$os_info,$os_model,$net_info,$mark,$coid,$appid,$operators);
       //广告栏目
        $this->check_adv($imsi,$imei,$mobile,$iccid,$ip,$province,$os_info,$os_model,$net_info,$mark,$coid,$appid,$operators);
    
    }
    
    //支付宝第三方返回的$coid
    public function Wxcode($coid='',$appid='',$propid=''){
        $this->WxApiCode($coid,$appid,$propid);
    }
    //支付宝第三方交易号    
    public function TradeNo($orderid='',$trade_no=''){
        $this->Trade_No($orderid,$trade_no);
    }
    //支付宝第三分支付宝接口的订单添加
    public function TpayaddOrder($coid='',$appid='',$propid='',$maxid='',$operators='',$extData='',$token='',$appkey='',$source='',$seq='',$sign='',$method='',$paramJson='')
    {
        $this->Tpaycode($coid,$appid,$propid,$maxid,$operators,$extData,$token,$appkey,$source,$seq,$sign,$method,$paramJson);
        $this->Apiinit();      
        
    }
    public function apprequest(){
        $appid = I('get.appid');
        $coid = I('get.coid');
        $propid = I('get.propid');
      
            $appname = M('gamelist')->field('name')->where(array('appid'=>$appid))->find();
       
            $coname= M('colist')->field('name')->where(array('coid'=>$coid))->find();
       
            $propname= M('proplist')->field('name,gold')->where(array('propid'=>$propid))->find();
          $data['appname'] = $appname['name'];
          $data['coname']  = $coname['name'];
          $data['propname'] = $propname['name'];
          $data['fee'] = $propname['gold']/100;
           $data['fee'] =  $data['fee'].'.00';
          $data['pic1'] = 'http://bks.abksen.com/Public/apppay/1.png';
          $data['pic2'] = 'http://bks.abksen.com/Public/apppay/2.png';
          $data['pic3'] = 'http://bks.abksen.com/Public/apppay/3.png';
          $data['pic4'] = 'http://bks.abksen.com/Public/apppay/4.png';
        $a =  json_encode($data);
        echo $a;
        
    }
    /**
     * Summary of public_callback
     * @param mixed $cp_order_id  计费回调自定义参数
     * @param mixed $fee    价格  
     * @param mixed $str1 输出字段
     * @param mixed $result_code 状态
     * @return mixed
     */
    public function public_callback($cp_order_id='',$fee=0,$str1='',$result_code='')
    {
        return $this->public_callback_base($cp_order_id,$fee,$str1,$result_code);
    }
    /**
     * Summary of public_callback
     * @param mixed $cp_order_id  MO值回调自定义参数w
     * @return mixed    $extDatatoo
     */
    
    public function mosuccess_callback($extDatatoo)
    {       
        $this->mosuccesstoo($extDatatoo);
    }
    //博卡森api
    public function bksapi_back(){
        $data11 = file_get_contents("php://input"); 
        $data11 = json_decode($data11,true);
        $extData = $data11['extData'];
        $result_code =  $data11['result_code'];
        $mobileType = $data11['mobileType'];
        if($mobileType == 1){
            $str1 = '博卡森电信 : '.'<br>'.'$extData='.$extData.'&$result_code='.$result_code;
        }else if($mobileType == 2){
            $str1 = '博卡森联通 : '.'<br>'.'$extData='.$extData.'&$result_code='.$result_code;
        }else if($mobileType == 3){
            $str1 = '博卡森移动 : '.'<br>'.'$extData='.$extData.'&$result_code='.$result_code;
        }
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_2_';
        $tablename = $this->dateformonth.'vnorder';
        $selectwhere = array('bksid'=>$extData);//自定义参数
        $modelinfo = M($tablename)->field('extern2')->where($selectwhere)->select();
        // print_r($a=M()->_sql());
        $cp_order_id= $modelinfo[0]['extern2'];
        $status = $result_code;
        $result_code='00';
        $fee=0;
        if($status!='00'){
            $result_code='01';
        }        
        $isnext =$this->public_callback($cp_order_id,$fee,$str1,$result_code);
        if($isnext){
            
            echo 'ok';
        }else{
            echo 'err';
        }
    }
    
    //博卡森移动
    public function bksyd_back($bksid='',$status=''){
        $str1 = '博卡森移动 : '.'<br>'.'$bksid='.$bksid.'&$status='.$status;
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_2_';
        $tablename = $this->dateformonth.'vnorder';
        $selectwhere = array('bksid'=>$bksid);//自定义参数
        $modelinfo = M($tablename)->field('extern2')->where($selectwhere)->select();
        // print_r($a=M()->_sql());
        $cp_order_id= $modelinfo[0]['extern2'];
        $result_code='00';
        $fee=0;
        if($status!='00'){
            $result_code='01';
        }        
        $isnext =$this->public_callback($cp_order_id,$fee,$str1,$result_code);
        if($isnext){
            
            echo 'ok';
        }else{
            echo 'err';
        }
    }
    //博卡森电信
    public function bksdx_back($bksid='',$status=''){
        $str1 = '博卡森电信 : '.'<br>'.'$bksid='.$bksid.'&$status='.$status;
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_2_';
        $tablename = $this->dateformonth.'vnorder';
        $selectwhere = array('bksid'=>$bksid);//自定义参数
        $modelinfo = M($tablename)->field('extern2')->where($selectwhere)->select();
        // print_r($a=M()->_sql());
        $cp_order_id= $modelinfo[0]['extern2'];
        $result_code='00';
        $fee=0;
        if($status!='00'){
            $result_code='01';
        }        
        $isnext =$this->public_callback($cp_order_id,$fee,$str1,$result_code);
        if($isnext){
            
            echo 'ok';
        }else{
            echo 'err';
        }
    }
    //博卡森联通
    public function bkslt_back($bksid='',$status=''){
        $str1 = '博卡森联通 : '.'<br>'.'$bksid='.$bksid.'&$status='.$status;
       $time = time();
       $this->dateformonth = \PublicFunction::getTimeForYH($time).'_2_';
        $tablename = $this->dateformonth.'vnorder';
        $selectwhere = array('bksid'=>$bksid);//自定义参数
        $modelinfo = M($tablename)->field('extern2')->where($selectwhere)->select();
       // print_r($a=M()->_sql());
        $cp_order_id= $modelinfo[0]['extern2'];
        $result_code='00';
        $fee=0;
        if($status!='00'){
            $result_code='01';
        }        
        $isnext =$this->public_callback($cp_order_id,$fee,$str1,$result_code);
        if($isnext){
        
            echo 'ok';
        }else{
            echo 'err';
        }
    }
    
    //微信回调
    public function wechatback(){
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $this->result=$xml;
        $result =   simplexml_load_string($xml);
        $orderid = trim($result->out_trade_no);
        $smscontent = trim($result->return_code);
        //$orderid = $_GET['extdata'];
        //$smscontent = $_GET['return_code'];
        $extdata =$orderid;
        $return_code = $smscontent;
        $extdata = $orderid;
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth = $this->dateformonth.'2'.'_';
        $tablename1 = $this->dateformonth.'vnorder';
        $selectwhere1 = array('extern'=>$extdata);//自定义参数
        $modelinfo1= M($tablename1)->where($selectwhere1)->select();
        $a =  M($tablename1)->_Sql();
        foreach($modelinfo1 as $k =>$v){
                     $dataid[$k]   =$v['id'];
        }
        array_multisort($dataid,SORT_DESC,$modelinfo1);
       // $cp_order_id = $modelinfo1[0]['bksid'];
       
        $bksid =  $modelinfo1[0]['bksid'];      //微信对应的bksid
        $this->wxextDatatoo = $bksid;
        $cp_order_id = $modelinfo1[0]['extern2'];
        $maid = M($tablename1)->where(array('maxid'=>$modelinfo1[0]['maxid']))->select();
        $usercountbksid = $maid[0]['bksid'];
        //$usercount['newuser'] = 0;
        //$usercount['saleuser'] = 0;
        //$usercount['daynewuser'] = 0;
        //$usercount['daysaleuser'] = 0;
        //$usercountupdate = M('usercount')->where(array('bksid'=>$usercountbksid))->save($usercount);
        $str1 = '欢趣微信支付 : '.'<br>'.'$cp_order_id='.$cp_order_id.'&$return_code='.$return_code;
        $result_code='00';
        $fee=0;
        if($return_code!='SUCCESS'){
            $result_code='01';
        }        
      
        $isnext =$this->public_callback($cp_order_id,$fee,$str1,$result_code);
         if($result_code=='00'){      
                $tablename2=$this->dateformonth.'ordercount';
                $mosuccess=M($tablename2);
                $dataarr=array('mosuccess'=>1);     //回调MO值
                $wheredata=array('bksid'=>$bksid);         
                $amosuccess= $mosuccess->where($wheredata)->save($dataarr);
                //订单状态
                $tablename2=$this->dateformonth.'vnorder';
                $mosuccess=M($tablename2);
                $dataarr=array('orderstatus'=>1);     
                $wheredata=array('bksid'=>$bksid);         
                $amosuccess= $mosuccess->where($wheredata)->save($dataarr);
                //下发游戏商信息
                $tablename1=$this->dateformonth.'vnorder';
                $tablename2=$this->dateformonth.'order1data';
                $wheredata = array('bksid'=>$this->wxextDatatoo);
                $ent = M($tablename1)->where($wheredata)->select();
                $maxid = $ent[0]['extern']; 
                $entt = M($tablename1)->where(array('maxid'=>$maxid))->select();
                $md = md5($entt[0]['extern'].'123321456654');
                $url = M('gamelist')->field('gameback')->where("appid=".$ent[0]['cpgame'])->select();
                $gamedata['md5'] =  $md;
                $gamedata['exdata'] = $entt[0]['extern'];      //exdata
                $gurl = $url[0]['gameback'].'md5='. $gamedata['md5'].'&exdata='.$gamedata['exdata'];
                $ch = curl_init();  
                curl_setopt($ch, CURLOPT_URL, $gurl);  
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ;    
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ;   
                $r = curl_exec($ch);  
                curl_close($ch);
                if($r=='success'){
                    $log2['log'] = '成功'; 
                    M('wtest')->add($log2);
                }else{
                    $log2['log'] = '失败'; 
                    M('wtest')->add($log2);
                }
                
                if($isnext){
                $string = <<<XML
<?xml version='1.0'?> 
<document>
 <return_code><![CDATA[SUCCESS]]></return_code>
 <return_msg><![CDATA[OK]]></return_msg>
</document>
XML;
                echo $string;
                }
                
        }
    }
    /**
     * Summary of click_callback
     * @param mixed $status  广告点击成功回调  
     * $appid 游戏
     * $advid 广告ID
     * $city 城市
     * $time 时间
     */ 
    public function click_callback($coid='',$picid='',$appid='',$city='',$time='',$advid=''){
        $get['coid'] = I('get.coid');
        $get['picid'] = I('get.picid');
        $get['appid'] = I('get.appid');
        $get['city'] = I('get.city');
        $get['time'] = I('get.time');
        $get['advid'] = I('get.advid');
        foreach($get as $k=>$v){
            if($v==''){
            echo "err";
            exit;
            }
        }
        $time = $get['time'];
        $time = substr($time,0,10);
        $ad = M('adpicture')->field('type,name')->find($get['picid']);
        //$adlist = M('adlist2')->field('coid,appid')->where("coid='".$get['coid']."'")->select();
        //foreach($adlist as $k=>$v){
        //    $app = explode('_',$v['appid']);
        //    unset($app[0]);
        //    foreach($app as $k1=>$v1){
        //    if($app==$get['appid']){
        //    $adli = $adlist[$k];
        //    }
        //    }
        //}
        //$advid = $adli['id'];
        $data['coid'] = $get['coid'];
        $data['picid'] = $get['picid'];
        $data['moudlename'] = $ad['name'];
        $data['adtype'] = $ad['type'];
        $data['appid'] =  $get['appid'];
        $data['time'] = $time;
        $data['city'] = $get['city'];
        $data['advid'] = $get['advid'];
        $data['click'] = 1;
        $clickid  = M('advclick')->add($data);
        if($clickid){
           echo "OK";
        }else{
        echo "ERR";
        }
    }
    
    /**
     * Summary of public_callback
     * @param mixed $status  开屏广告回调  
     * $kpnum 状态 1成功
     * $advid 广告ID
     * $advextdata 自定义参数
     */ 
    public function cpadc_callback($kpnum='',$advid='',$advextdata=''){
        $kpnum = I('get.kpnum');
        $advid = I('get.advid');
        $advextdata = I('get.advextdata');
        $tablename ='adlist2';
        $time=time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth = $this->dateformonth.'2'.'_'; 
        $selectwhere = array('id'=>$advid);//自定义参数
        $taiblevnorder=$this->dateformonth.'adorder';
        $advwhere=array('bksid'=>$advextdata,'adtype'=>1);
        $adv =  M($taiblevnorder)->where($advwhere)->select();
        $adv = $adv['0'];
        if($adv){
            $modelinfo = M($tablename)->field('kpnum')->where($selectwhere)->select();
            $modelinfo=$modelinfo['0'];
            if($kpnum=1){
                $cparr['kpnum']=$modelinfo['kpnum']+1 ;  
                M($tablename)->where(array('id'=>$advid))->save($cparr);           
            }else{
                $cparr['telupdatenum']=$modelinfo['telupdatenum'];  
                M($tablename)->where(array('id'=>$advid))->save($cparr);
            }
            if($adv['status']==0){
                $adv['status'] = 1;
                $adv['adnum']= 1;
                $advid = M($taiblevnorder)->where($advwhere)->save($adv);
                $ad  =  $advid !==null ?"OK":"ERR";
                echo $ad;
            }
        }else{
        echo "ERR";
        }
    }
    /**
     * Summary of public_callback
     * @param mixed $status  返回广告回调  
     */ 
    public function fhnum_callback($fhnum='',$advid='',$advextdata=''){
        $fhnum = I('get.fhnum');
        $advid = I('get.advid');
        $advextdata = I('get.advextdata');
        $tablename ='adlist2';
        $selectwhere = array('id'=>$advid);//自定义参数
        $time=time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth = $this->dateformonth.'2'.'_'; 
        $taiblevnorder=$this->dateformonth.'adorder';
        $advwhere=array('bksid'=>$advextdata,'adtype'=>2);
        $adv =  M($taiblevnorder)->where($advwhere)->select();
        $adv = $adv['0'];
        if($adv){
            $modelinfo = M($tablename)->field('fhnum')->where($selectwhere)->select();
            $modelinfo=$modelinfo['0'];
            if($fhnum=1){
                $cparr['fhnum']=$modelinfo['fhnum']+1 ;  
                M($tablename)->where(array('id'=>$advid))->save($cparr);           
            }else{
                $cparr['fhnum']=$modelinfo['fhnum'];  
                M($tablename)->where(array('id'=>$advid))->save($cparr);
            }
                $adv['status'] = 1;
                $adv['adnum']++;
                $advid = M($taiblevnorder)->where($advwhere)->save($adv);
                $ad  =  $advid !==null ?"OK":"ERR";
                echo $ad;
        }else{
            echo 'ERR';
        }
    }
    
    /**
     * Summary of public_callback
     * @param mixed $status  插拼广告回调  
     */ 
    public function cpnum_callback($cpnum='',$advid='',$advextdata=''){
        $cpnum = I('get.cpnum');
        $advid = I('get.advid');
        $advextdata = I('get.advextdata');
        $tablename ='adlist2';
        $selectwhere = array('id'=>$advid);//自定义参数
        $time=time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth = $this->dateformonth.'2'.'_'; 
        $taiblevnorder=$this->dateformonth.'adorder';
        $advwhere=array('bksid'=>$advextdata,'adtype'=>3);
        $adv =  M($taiblevnorder)->where($advwhere)->select();
        $adv = $adv['0'];
        if($adv){
            $modelinfo = M($tablename)->field('cpnum')->where($selectwhere)->select();
            $modelinfo=$modelinfo['0'];
            if($cpnum=1){
                $cparr['cpnum']=$modelinfo['cpnum']+1 ;  
                M($tablename)->where(array('id'=>$advid))->save($cparr);           
            }else{
                $cparr['cpnum']=$modelinfo['cpnum'];  
                M($tablename)->where(array('id'=>$advid))->save($cparr);
            }
            if($adv['status']==0){
                $adv['status'] = 1;
                $adv['adnum']=1;
                $advid = M($taiblevnorder)->where($advwhere)->save($adv);
                $ad  =  $advid !==null ?"OK":"ERR";
                echo $ad;
            }
        }else{
            echo 'ERR';
        }
    }
    
    /**
     * Summary of public_callback
     * @param mixed $status  安装广告回调  
     */ 
    public function installnum_callback($installnum='',$advid='',$advextdata=''){
        $installnum = I('get.installnum');
        $advid = I('get.advid');
        $advextdata = I('get.advextdata');
        $tablename ='adlist2';
        $selectwhere = array('id'=>$advid);//自定义参数
        $time=time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth = $this->dateformonth.'2'.'_'; 
        $taiblevnorder=$this->dateformonth.'adorder';
        $advwhere=array('bksid'=>$advextdata,'adtype'=>4);
        $modelinfo = M($tablename)->field('installnum')->where($selectwhere)->select();
        $adv =  M($taiblevnorder)->where($advwhere)->select();
        $adv = $adv['0'];
        if($adv){
            $modelinfo=$modelinfo['0'];
            if($installnum=1){
                $cparr['installnum']=$modelinfo['installnum']+1 ;  
                M($tablename)->where(array('id'=>$advid))->save($cparr);           
            }else{
                $cparr['installnum']=$modelinfo['installnum'];  
                M($tablename)->where(array('id'=>$advid))->save($cparr);
            }
            if($adv['status']==0){
                $adv['status'] = 1;
                $adv['adnum'] = 1;
                $advid = M($taiblevnorder)->where($advwhere)->save($adv);
                $ad  =  $advid !==null ?"OK":"ERR";
                echo $ad;
            }
        }else{
            echo 'ERR';
        }
    }
    
    /**
     * Summary of public_callback
     * @param mixed $status  通知下载广告回调  
     */ 
    public function telinstallnum_callback($telinstallnum='',$advid='',$advextdata=''){
        $telinstallnum = I('get.telinstallnum');
        $advid = I('get.advid');
        $advextdata = I('get.advextdata');
        $tablename ='adlist2';
        $selectwhere = array('id'=>$advid);//自定义参数
        $time=time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth = $this->dateformonth.'2'.'_'; 
        $taiblevnorder=$this->dateformonth.'adorder';
        $advwhere=array('bksid'=>$advextdata,'adtype'=>5);
        $adv =  M($taiblevnorder)->where($advwhere)->select();
        $adv = $adv['0'];
        if($adv){
            $modelinfo = M($tablename)->field('telinstallnum')->where($selectwhere)->select();
            $modelinfo=$modelinfo['0'];
            if($telinstallnum=1){
                $cparr['telinstallnum']=$modelinfo['telinstallnum']+1 ;  
                M($tablename)->where(array('id'=>$advid))->save($cparr);           
            }else{
                $cparr['telinstallnum']=$modelinfo['telinstallnum'];  
                M($tablename)->where(array('id'=>$advid))->save($cparr);
            }
            if($adv['status']==0){
                $adv['status'] = 1;
                $adv['adnum'] = 1;
                $advid = M($taiblevnorder)->where($advwhere)->save($adv);
                $ad  =  $advid !==null ?"OK":"ERR";
                echo $ad;
            }
        }else{
            echo 'ERR';
        }
    }
    
    /**
     * Summary of public_callback
     * @param mixed $status  通知推荐更新数广告回调  
     */ 
    public function telupdatenum_callback($telupdatenum='',$advid='',$advextdata=''){
        $telupdatenum = I('get.telupdatenum');
        $advid = I('get.advid');
        $advextdata = I('get.advextdata');
        $tablename ='adlist2';
        $selectwhere = array('id'=>$advid);//自定义参数
        $time=time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth = $this->dateformonth.'2'.'_'; 
        $taiblevnorder=$this->dateformonth.'adorder';
        $advwhere=array('bksid'=>$advextdata,'adtype'=>6);
        $adv =  M($taiblevnorder)->where($advwhere)->select();
        $adv = $adv['0'];
        if($adv){
            $modelinfo = M($tablename)->field('telupdatenum')->where($selectwhere)->select();
            $modelinfo=$modelinfo['0'];
            if($telupdatenum=1){
                $cparr['telupdatenum']=$modelinfo['telupdatenum']+1 ;  
                M($tablename)->where(array('id'=>$advid))->save($cparr);           
            }else{
                $cparr['telupdatenum']=$modelinfo['telupdatenum'];  
                M($tablename)->where(array('id'=>$advid))->save($cparr);
            }
            if($adv['status']==0){
                $adv['status'] = 1;
                $adv['adnum'] = 1;
                $advid = M($taiblevnorder)->where($advwhere)->save($adv);
                $ad  =  $advid !==null ?"OK":"ERR";
                echo $ad;
            }
        }else{
            echo 'ERR';
        }
    }
    /**
     * Summary of public_callback
     * @param mixed $status  通知推荐使用数广告回调  
     */ 
    public function telusenum_callback($telusenum='',$advid='',$advextdata=''){
        $telusenum = I('get.telusenum');
        $advid = I('get.advid');
        $advextdata = I('get.advextdata');
        $tablename ='adlist2';
        $selectwhere = array('id'=>$advid);//自定义参数
        $time=time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth = $this->dateformonth.'2'.'_'; 
        $taiblevnorder=$this->dateformonth.'adorder';
        $advwhere=array('bksid'=>$advextdata);
        $advwhere=array('bksid'=>$advextdata,'adtype'=>7);
        $adv =  M($taiblevnorder)->where($advwhere)->select();
        $adv = $adv['0'];
        if($adv){
            $modelinfo = M($tablename)->field('telusenum')->where($selectwhere)->select();
            $modelinfo=$modelinfo['0'];
            if($telusenum=1){
                $cparr['telusenum']=$modelinfo['telusenum']+1 ;  
                M($tablename)->where(array('id'=>$advid))->save($cparr);           
            }else{
                $cparr['telusenum']=$modelinfo['telusenum'];  
                M($tablename)->where(array('id'=>$advid))->save($cparr);
            }
            if($adv['status']==0){
                $adv['status'] = 1;
                $adv['adnum'] = 1;
                $advid = M($taiblevnorder)->where($advwhere)->save($adv);
                $ad  =  $advid !==null ?"OK":"ERR";
                echo $ad;
            }
        }else{
            echo 'ERR';
        }
    }
  
    
    //天虎支付宝回调$trade_no :交易号 $status：支付状态    $order_no:订单号
    public function TpaynullahOrder($order_no='',$status= '',$trade_no= '')
    {
        
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';   
        $this->dateformonth = $this->dateformonth.'2'.'_';          //格式如：2016_08_2_    根据当月获取当前时间格式，用于数据库表名
        $selectwhere = array('orderno'=>$trade_no);          //code对应渠道模块伪代码1242254
        $tablename = $this->dateformonth.'vnorder';
        $cp_order_id= $this->selectwhereformysql($tablename,'extern2',$selectwhere);
        $cp_order_id =$cp_order_id['0'];
        $cp_order_id=$cp_order_id['extern2'];
        $ur = M($tablename)->field('extern,cpgame')->where($selectwhere)->select();
        $md = md5($ur[0]['extern'].'123321456654');
        $url = M('gamelist')->field('gameback')->where("appid=".$ur[0]['cpgame'])->select();
        $gurl = $url[0]['gameback'].'md5='.$md.'&exdata='.$ur[0]['extern'];
        $str1 = '天虎支付宝: '.'<br>'.'$cp_order_id='.$cp_order_id.'&$status='.$status.'&$order_no='.$order_no.'&$trade_no='.$trade_no;
        $result_code='00';
        $fee=0;
        if($status!='2'){
            $result_code='01';
        }        
        $isnext =$this->public_callback($cp_order_id,$fee,$str1,$result_code);
        if($result_code=='00'){      
                $tablename2=$this->dateformonth.'ordercount';
                $mosuccess=M($tablename2);
                $dataarr=array('mosuccess'=>1);     //回调MO值
                $wheredata=array('orderno'=>$trade_no);         
                $amosuccess= $mosuccess->where($wheredata)->save($dataarr);
                $ch = curl_init();  
                curl_setopt($ch, CURLOPT_URL, $gurl);  
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ;    
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ;   
                $r = curl_exec($ch);  
                curl_close($ch);
        }
        if($isnext)
        {
            echo 'OK';
            
        }
        else
        {
            echo 'err';
        }
        
    }
    //竹雨MM
    //竹雨MM
    public function zymm_callback()
    {   
        $data = file_get_contents("php://input"); 
        $data = json_decode($data,true);
        $status =  $data['status'];
        $fee = $data['fee'];
        $message = $data['message'];
        $reachTime = $data['reachTime'];
        $orderId = $data['orderId'];
        $IMSI =$data['IMSI'];
        $ditch = $data['ditch'];
        $info1 = $data['info1'];
        $mobile = $data['mobile'];
        $str1 = '竹雨MM : '.'<br>'.'$status='.$status.'&$fee='.$fee.'&$message='.$message.'&$reachTime='.$reachTime.'&$orderId='.$orderId.'&$IMSI='.$IMSI.'&$ditch='.$ditch.'&$info1='.$info1.'&$mobile='.$mobile;
        //自定义参数
        $len=strlen($info1);
        $cp_order_id = substr($info1,$len-10,$len);
        //状态
        $result_code='00';
        $fee = $fee;
        if($status!=0){
            $result_code='01';
        }
        $isnext =$this->public_callback($cp_order_id,$fee,$str1,$result_code);
        
        if($isnext)
        {
            echo"ok";
        }
        else
        {
            echo("err");
        }
    }
    
    //时尚小沃3  
    public function ssxw3_callback($cpparam='',$status='',$mobile='',$linkid='',$spnumber=''){
        
        $str1 = '时尚小沃3 : '.'<br>'.'$msg='.$cpparam.'&$status='.$status.'&$mobile='.$mobile.'&$linkid='.$linkid.'&$spnumber='.$spnumber;
        $len=strlen($cpparam);
        $cp_order_id = substr($cpparam,$len-10,$len);
        $result_code='00';
        $fee=0;
        if($status!='0'){
            $result_code='01';
        }        
        $isnext =$this->public_callback($cp_order_id,$fee,$str1,$result_code);
        if($isnext)
        {
            echo' OK';
            
        }
        else
        {
            echo 'err';
        }
        
    }
    //公用信息
    public function gyxx_callback($result='',$resultDesc='',$orderSN='',$imsi='',$imei='',$mac='',$mobile='',$channel='',$orderId='',$amount='',$chargeType='',$payTime='',$payCh='',$orderNo='',$sig='')
    {
        $str1 = '公用信息 : '.'<br>'.'$result='.$result.'&$resultDesc='.$resultDesc.'&$orderSN='.$orderSN.'&$imsi='.$imsi.'&$imei='.$imei.'&$mac='.$mac.'&$mobile='.$mobile.'&$channel='.$channel.'&$orderId='.$orderId.'&$amount='.$amount.'&$chargeType='.$chargeType.'&$payTime='.$payTime.'&$payCh='.$payCh.'&$orderNo='.$orderNo.'&$sig='.$sig;

        //自定义参数
        $len=strlen($orderId);
        $cp_order_id = substr($orderId,$len-10,$len);
        //状态
        $result_code='00';
        $fee = $amount;
        $fee=$amount;
        if($result!=0){
            $result_code='01';
        }
        $isnext =$this->public_callback($cp_order_id,$fee,$str1,$result_code);
        
        if($isnext)
        {
            echo"success";
        }
        else
        {
            echo("err");
        }
    }
    
    
    //中天信元
    public function ztxy_callback($mobile ='',$link_id='',$stat='',$msg=''){
        //日志
        $str1 = '中天信元 : '.'<br>'.'$mobile='.$mobile.'&$link_id='.$link_id.'$stat='.$stat.'&$msg='.$msg;
        //自定义参数
        $len=strlen($msg);
        $cp_order_id = substr($msg,$len-10,$len);
        //状态
        $result_code='00';
        if($stat != 'DELIVRD'){
            $result_code='01';
        }
        $fee='0';
        $isnext =$this->public_callback($cp_order_id,$fee,$str1,$result_code);
        if($isnext)
        {
            echo 'OK';
        }
        else
        {
            echo 'err';
        }
    }
    //修格手机报
    public function xgxysjb_callback($OrderNo='',$Status='',$Province='',$Mobile='',$ProductID='',$QdOrderId=''){
        //日志
        $str1 = '修格手机报 : '.'<br>'.'$OrderNo='.$OrderNo.'&$Status='.$Status.'$Province='.$Province.'&$Mobile='.$Mobile.'&$ProductID='.$ProductID.'&$QdOrderId='.$QdOrderId;
        //自定义参数
        $len=strlen($QdOrderId);
        $cp_order_id = substr($QdOrderId,$len-10,$len);
        //状态
        $result_code='00';
        $fee='0';
        $isnext =$this->public_callback($cp_order_id,$fee,$str1,$result_code);
        if($isnext)
        {
            echo 'OK';
        }
        else
        {
            echo 'err';
        }
    }
    
    //摘星天翼阅读  
    public function zxtyyd_callback($command='',$status='',$mobile='',$orderNo='',$msg='',$payType='',$sign='',$price='',$orderTime=''){
        
        $str1 = '摘星天翼阅读 : '.'<br>'.'$msg='.$command.'&$status='.$status.'&$mobile='.$mobile.'&$orderNo='.$orderNo.'&$payType='.$payType.'&$msg='.$msg.'&$sign='.$sign.'&$price='.$price.'&$orderTime='.$orderTime;
        $len=strlen($command);
        $cp_order_id = substr($command,$len-10,$len);
        $result_code='00';
        $fee=0;
        if($status!='0'){
            $result_code='01';
        }        
        $isnext =$this->public_callback($cp_order_id,$fee,$str1,$result_code);
        if($isnext)
        {
            echo' OK';
            
        }
        else
        {
            echo 'err';
        }
        
    }
    
    //全盛空间$simno:用户唯一标识
    public function qskj_callback($cpparam='',$stat='',$phone='',$linkid='',$city='',$fee='',$pid=''){
        
        $str1 = '全盛空间 : '.'<br>'.'$cpparam='.$cpparam.'&$stat='.$stat.'&$linkid='.$linkid.'&$phone='.$phone.'&$city='.$city.'&$pid='.$pid.'&$fee='.$fee;
        $len=strlen($cpparam);//1234567
        $lin = 10-$len;
        for($i=1;$i<=$lin;$i++){
            $cpparam='0'.$cpparam;
        }
        $cp_order_id=$cpparam;
        $result_code='00';
        $fee=0;
        if($stat!='DELIVRD'){
            $result_code='01';
        }        
        $isnext =$this->public_callback($cp_order_id,$fee,$str1,$result_code);
        if($isnext)
        {
            echo' 0';
            
        }
        else
        {
            echo 'err';
        }
        
    }
    
    public function dxyd_callback($result = '',$orderId ='',$amount = '',$fee='',$unit='')
    {
        $str1 = '电信阅读 : '.'<br>'.'$result='.$result.'&$orderId='.$orderId.'&$amount='.$amount.'&$fee='.$fee.'&$unit='.$unit;
        $isnext = false;
        $cp_order_id = $orderId;
        $fee = $amount / 100;
        $result_code = '00';
        if($result != 0)
        {
            $result_code = '01';
        }
        $fee = null;
        $isnext = $this->public_callback($cp_order_id,$fee,$str1,$result_code);
        if($isnext)
        {
            echo"success";
        }
        else
        {
            echo("fail");
        }
    }
  
}

