<?php
 // +----------------------------------------------------------------------
 // | OneThink [ WE CAN DO IT JUST THINK IT ]
 // +----------------------------------------------------------------------
 // | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
 // +----------------------------------------------------------------------
 // | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
 // +----------------------------------------------------------------------
namespace Admin\Controller;
include_once"ModelNameList.php";
include_once "RequestData.php";
include_once"PublicFunction.php";
/**
 * ChinaMobile short summary.
 *
 * ChinaMobile description.
 *
 * @version 1.0
 * @author Administrator      
 */
/**
 * Summary of Fun2Controller    两次请求接口
 */

class Fun2Controller extends RequestData
{
    public function order1($imsi = '',$imei = '',$mobile='',$extData='',$iccid='',$ip='',$os_info='',$os_model='',$net_info='',$province='',$mark='',$bksen='')
    {
        $this->bksen = $bksen;
        $this->isreques = false;
        $this->init_val($imsi,$imei,$mobile,$extData,$iccid,$ip,$province,$os_info,$os_model,$net_info,$mark);
        $this->checkData_once();
        $this->init();
    }
    /**
     * Summary of order2
     * @param mixed $sid 按第一步获取的SID回传
     * @param mixed $maxid 最大订单号，第一步请求回传
     * @param mixed $orderid  订单号
     * @param mixed $mobile     手机号
     * @param mixed $telecomtype    通道类型
     * @param mixed $coid           厂商ID
     * @param mixed $appid         游戏ID
     * @param mixed $propid       道具ID
     * @param mixed $operators     运营商
     * @param mixed $reserve 预留值，如果有些类型需要验证吗则这里是验证吗
     */
    public function order2($sid='',$orderid='',$reserve='',$mobile = '',$telecomtype='',$coid='',$appid='',$propid='',$operators='')
    {
        $isgetinfo = $this->checkData_order2(array($sid,$reserve,$orderid,$mobile,$telecomtype,$coid,$appid,$propid,$operators));
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth = $this->dateformonth.'2'.'_';
        $this->model = M();
        $this->isreques = true;
        $this->maxid = $sid;
        $this->extData = $reserve;
        $tablename = $this->dateformonth.'order1data';
        $mobile = M($tablename);
        $info = $mobile->where(array('id'=>$this->maxid))->select();
        $info = $info['0'];
        $this->maxid2 = $info['maxid'];
        if($isgetinfo == true)
        {
           
            $this->sid = $info['orderid'];
            $this->coid = $info['coid'];
            $this->code = $info['code'];
            $this->appid = $info['appid'];
            $this->egt = $info['egt'];
            $this->propid = $info['propid'];
            $this->mobile = $info['mobile'];
            $this->telecomtype = $info['telecomtype'];
            $this->maxid = $info['maxid'];
        }
        else
        {
            $this->sid = $orderid;
            $this->mobile = $mobile;
            $this->telecomtype = $telecomtype;
            $this->coid = $coid;
            $this->propid = $propid;
            $this->appid = $appid;
            $this->egt = $operators;
        }
  //      $this->model->startTrans();

        //$this->sid = $orderid;
        //$this->code = $code;
        //$this->mobile = $mobile;
        //$this->telecomtype = $telecomtype;
       // $this->checkData_once();
        $selectwhereco = array('coid'=>$this->coid,'appid'=>$this->appid);
        $colistarr = M('cogameset')->field('status,appid,coid')->where($selectwhereco)->select();
        if($colistarr==null){
             $this->CoData_once(); 
        }else{
            $this->CogameData_once();   //游戏检测
        }
        $this->init();
    }
//    ptid	String	否	合作游戏ID，由我方分配
//orderno	String	否	在步骤2.1中由我方返回的参数
//status	int	否	status=0表示计费成功，其他值失败
//imsi	String	否	用户终端的imsi
//amount	int	否	付费金额
//cpparam	String	否	渠道自定义参数
//mobile	String	是	如果移动传输的话
//paycode	String	否	计费点代码
    /**
     * Summary of public_callback
     * @param mixed $cp_order_id cp自定义参数
     * @param mixed $fee 金额默认元
     * @param mixed $str1 回调log
     * @param mixed $result_code 计费状态
     * @return mixed
     */
    public function public_callback($cp_order_id='',$fee=0,$str1='',$result_code='')
    {
        $isok =  $this->public_callback_base($cp_order_id,$fee,$str1,$result_code);
        return $isok;
    }
    
    
    //博卡森API
    public function bksapi_callback($timestamp='',$fee='',$extData='',$mobileType='',$code='')
    {
        $str1 = '博卡森API : '.'<br>'.'$timestamp='.$timestamp.'&$fee='.$fee.'&$extData='.$extData.'&$mobileType='.$mobileType.'&$code='.$code;
        $len = strlen($extData);    //自定义参数的长度
        $begin = $len - 10;       
        $customer = substr($extData,$begin,$len);
        $cp_order_id = $customer;
        $fee = $fee;
        $result_code ='00';
        if($code!=0){
            $result_code='01';
        }
        $isnext = $this->public_callback($cp_order_id,$fee,$str1,$result_code);
        if($isnext)
        {
            echo"ok";
        }
        else
        {
            echo("err");
        }
    }
    
    
    //联通包月
    public function ctltby_callback($linkid='',$type='',$merchantid='',$code='',$mr_amount='',$province='',$status='',$cpparam='',$ext1='')
    {
        $str1 = '联通包月 : '.'<br>'.'$linkid='.$linkid.'&$type='.$type.'&$merchantid='.$merchantid.'&$code='.$code.'&$mr_amount='.$mr_amount.'&$province='.$province.'&$status='.$status.'&$cpparam='.$cpparam.'&$ext1='.$ext1;
        $len = strlen($cpparam);    //自定义参数的长度
        $begin = $len - 10;       
        $customer = substr($cpparam,$begin,$len);
        $cp_order_id = $customer;
        $fee = $mr_amount;
        $result_code ='00';
        if($status!=0){
            $result_code='01';
        }
        $isnext = $this->public_callback($cp_order_id,$fee,$str1,$result_code);
        if($isnext)
        {
            echo"ok";
        }
        else
        {
            echo("err");
        }
    }
    //美奇RDO
    public function mqrdo_callback($status='',$spnumber='',$msg='',$mobile='',$linkid=''){
        
        $str1 = '美奇RDO : '.'<br>'.'$status='.$status.'&$spnumber='.$spnumber.'&$msg='.$msg.'&$mobile='.$mobile.'&$linkid='.$linkid;
        $len = strlen($msg);
        $begin = $len - 10;
        $customer = substr($msg,$begin,$len);
        $cp_order_id = $customer;
        $fee=0;
        if($status='DELIVRD'){
            $result_code = '00';
        }
        $isnext = $this->public_callback($cp_order_id,$fee,$str1,$result_code);
        if($isnext)
        {
            echo"OK";
        }
        else
        {
            echo("err");
        }
        
    }
    
    //修格RDO
    public function xgrdo_callback($orderno='',$feecode='',$cm='',$mobile='',$price='',$channelid='',$outorderid='')
    {
        $str1 = '修格RDO : '.'<br>'.'$outorderid='.$outorderid.'&$orderno='.$orderno.'&$feecode='.$feecode.'&$cm='.$cm.'&$price='.$price.'&$mobile='.$mobile.'&$channelid='.$channelid;
        $len = strlen($outorderid);    //自定义参数的长度
        $begin = $len - 10;       
        $customer = substr($outorderid,$begin,$len);
        $cp_order_id = $customer;
        $fee = $price;
        $result_code ='00';
        $isnext = $this->public_callback($cp_order_id,$fee,$str1,$result_code);
        if($isnext)
        {
            echo"success";
        }
        else
        {
            echo("err");
        }
    }
    

}

