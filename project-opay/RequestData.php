<?php
namespace Admin\Controller;

include_once 'PublicFunction.php';
include_once "JSHttpRequest.php";
include_once "CodeModel1Control.php";
/**
 * RequestData short summary.
 *
 * RequestData description.
 *
 * @version 1.0
 * @author JieSen DaoGe <fhldwyyx@163.com>
 */
class RequestData extends CodeModel1Control
{   
    var $xystatus = '';
    var $xystatus_int = 0;
    var $str1 = '';
    var $ordermaxid = 0;
    var $tableordername = array();
    var $tableorderhistoryname = array();
    var $dateformonth = '';

    var $clientupdata = array();
    var $payupdata = array();
    var $returndata = array();
    var $logdowndata = array();
    /**
     * Summary of public_callback_base
     * @param mixed $cp_order_id 回调自定义参数
     * @param mixed $fee    价格  
     * @param mixed $str1 输出字段
     * @param mixed $result_code 状态
     * @return mixed
     */
    public function public_callback_base($cp_order_id='',$fee=0,$str1='',$result_code='')
    {
        $data = array('log'=>$str1);
        $model = M('telback');
        $model->data($data)->add();  //回调日志
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth = $this->dateformonth.'2'.'_';
        //if(empty($fee))  $fee = 0;
        if(empty($cp_order_id)) return false;
        
        $tablename = $this->dateformonth.'vnorder';
        $selectwhere = array('extern2'=>$cp_order_id);//自定义参数
        $modelinfo = M($tablename)->field('type,telecom,paycode,extern,co,bksid,prop,cpgame,imsi,imei')->where($selectwhere)->select();
       //$A = M($tablename)->_sql();
        if(empty($modelinfo))  return false;
        $modelinfo = $modelinfo['0'];
        $this->cpconame = $modelinfo['co'];
        $this->imsi = $modelinfo['imsi'];
        $this->imei = $modelinfo['imei'];
        $this->cpgamename = $modelinfo['cpgame'];
        $this->cpgamepropname = $modelinfo['prop'];
        $this->telecomtype = $modelinfo['type'];
        $this->telecomname = $modelinfo['telecom'] ;
        $this->paycode = $modelinfo['paycode'];
        $this->extData = $modelinfo['extern'];
        $bks = $modelinfo['bksid'];
        $this->extDatatoo = $bks;
        if(empty($this->cpconame)) return false;
        if(empty($this->cpgamename)) return false;
        if(empty($this->cpgamepropname)) return false;
        if(empty($this->telecomtype)) return false;
        if(empty($this->telecomname)) return false;
        if(empty($this->paycode)) return false;     
        if(empty($this->extData)) return false;
        if(empty($this->extDatatoo)) return false;
        if(empty($this->imsi)) return false;
        if(empty($this->imei)) return false;
        $this->result_code = $result_code;
        $this->extData2 = $cp_order_id;
        $this->str1 = $str1;
        $selectwhere = array('paycodename'=>$this->paycode);
        $paycodeinfo = M('paycodelist')->field('news,payglod,egt')->where($selectwhere)->select();
        $gamepropname = $paycodeinfo['0']['news'];
        $gamepropname = explode('_',$gamepropname);
        $this->gamename = $gamepropname['0'];
        $this->propname = $gamepropname['1'];
        $fee = $paycodeinfo['0']['payglod'];
        $this->fee=$fee/100;
        $this->egt =  $paycodeinfo['0']['egt'] ;
        if(empty($this->fee)) return false;
        $this->model = M();
        $this->model->startTrans();
        $this->iskouliang();
        $this->DataHandleCenter();
        if($this->egt == 5){
            $this->updateuser1();        //微信用户统计
        }else if($this->egt == 4){
            $this->updateuser2();        //支付宝用户统计
        }else{
            $this->updateuser();        //短代用户统计
        }
        $this->updateordercoutforpay();
        $this->SqlHandleCenter();
        $this->callbackupdateDataforvnorderindex();
        $this->callbacksendmsgtooutletsforurl();        //下家同步
        return true;
    }
    /**
     * Summary of addordercount    向ordercount表中更新mosuccess
     */
    public function mosuccesstoo($extDatatootoo=''){
  
        
        $time = time();       
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth = $this->dateformonth.'2'.'_'; 
        $tablename=$this->dateformonth.'ordercount';
        $mosuccess=M($tablename);
        $dataarr=array('mosuccess'=>1);
        $wheredata=array('bksid'=>$extDatatootoo);         
        $amosuccess= $mosuccess->where($wheredata)->save($dataarr);
        $a =  M($tablename)->_sql();
        $tablename1=$this->dateformonth.'vnorder';
        $ent = M($tablename1)->where($wheredata)->select();
        $md = md5($ent[0]['extern'].'123321456654');
        $url = M('gamelist')->field('gameback')->where("appid=".$ent[0]['cpgame'])->select();
        $a =  M('gamelist')->_sql();
        $gurl = $url[0]['gameback'].'md5='.$md.'&exdata='.$ent[0]['extern'];
        if($amosuccess!=false){
           $ch = curl_init();  
           curl_setopt($ch, CURLOPT_URL, $gurl);  
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ;    
           curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ;   
           $r = curl_exec($ch);  
           curl_close($ch);
           echo 'OK';
           
        }else{
            echo 'ERR';
       }      
        return;
    }
    
    /**
     * Summary of addhistory    向表中添加破解履历
     */
    public function addhistory()
    {
        if($this->isreques == true)
        {
            $isreques = '二次';
            $this->updateDatafororderAsxystatus($this->xystatus_int);
        }
        else
        {
            $isreques = '';
            $this->updateDatafororderAsxystatus($this->xystatus_int);
        }
        $time = \PublicFunction::getTimeForString(time());
        $client_ip = $_SERVER['REMOTE_ADDR']; 
        $client_posthttps =  $isreques.'客户端提交明细：'.'<br>'.$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].'<br>'.'来源IP：'.$client_ip;
        $clientupstring = $client_posthttps;
        $this->clientupdata = array('time'=>$time,'orderid'=>$this->maxid,'type'=>$this->telecomtype,'status'=>4,'log'=>$clientupstring,'maxid'=>$this->maxid2);
        $vnhistorytablename = C('DB_PREFIX').$this->dateformonth.'vnhistory';
        $payupstring = $this->send_url_data_string;
        $payupstring = $this->codetelecomname.$isreques." 支付提交:".'提交服务器IP：'.\PublicData::$servename."<br>".$payupstring;
        $this->payupdata = array('time'=>$time,'orderid'=>$this->maxid,'type'=>$this->telecomtype,'status'=>1,'log'=>$payupstring,'maxid'=>$this->maxid2);
        $returnstring = $this->codetelecomname.$isreques." 支付返回:"."<br>".json_encode($this->result);
        $this->returndata = array('time'=>$time,'orderid'=>$this->maxid,'type'=>$this->telecomtype,'status'=>2,'log'=>$returnstring,'maxid'=>$this->maxid2);
        $logdowndata = json_encode($this->resultdata);
        $logdowndata = $this->codetelecomname.$isreques." 登录下发:".'<br>'.$logdowndata;
        $this->logdowndata = array('time'=>$time,'orderid'=>$this->maxid,'type'=>$this->telecomtype,'status'=>3,'log'=>$logdowndata,'maxid'=>$this->maxid2);
        $this->adddataforsql($vnhistorytablename,$this->clientupdata,false,$id);
        $this->adddataforsql($vnhistorytablename,$this->payupdata,false,$id);
        $this->adddataforsql($vnhistorytablename,$this->returndata,false,$id);
        $this->adddataforsql($vnhistorytablename,$this->logdowndata,true,$id);
    }
    /**
     * Summary of group_user    用户统计
     */
    public function group_user()
    {
        $data = array('co'=>$this->cpconame,'cpgame'=>$this->cpgamename,'imei'=>$this->imei,'imsi'=>$this->imsi,'time'=>time());    
        $isok = M('group_user')->data($data)->add();
    }
    /**
     * Summary of doextData 生成自定义参数
     */
    public function doextData()
    {
        $tablename = C('DB_PREFIX').$this->dateformonth.'extdata';
        $id = 0;
        $data = array('coorderid'=>$this->extData);
        $this->adddataforsql($tablename,$data,true,$id);
        $this->maxid = $id;
        $selectwhere =array('id'=>$id);   
        $tablename2=$this->dateformonth.'extdata';
        $this->coinfo = $this->selectwhereformysql($tablename2,'bksid',$selectwhere);
        $this->coinfo = $this->coinfo['0'];
        $this->extDatatoo=$this->coinfo['bksid'];
        $this->extDatatoo =$this->extDatatoo;
        
    }
    //用户新增
    public function user_count($arrinfo)
    {
        
        //foreach($arrinfo as $value)
        //{
        //    if($value == null)
        //    {
        //        $this->requesterrforid(11111111,'useradd');
        //    }
        //}
        $selectwhere = array('imei'=>$this->imei,'appid'=>$this->cpgamename,'coid'=>$this->cpconame);
        $time=time();
        //$time2=date('Y-m-d',$time);
        $time1 = date("Y-m-d",$time).' 00:00:00'; 
        $time2 = date("Y-m-d",$time).' 23:59:59'; 
        $daytime = array(array('egt',$time1),array('elt',$time2));
        $selectwhere2 = array('imei'=>$this->imei,'time'=>$daytime,'appid'=>$this->cpgamename,'coid'=>$this->cpconame,'baduser'=>0,'initstatus'=>1);//计费中的活跃用户判断
        //$selectwhere3 = array('imei'=>$this->imei,'time'=>$time2,'appid'=>$this->cpgamename,'coid'=>$this->cpconame,'baduser'=>1);

        $selectwhere4 = array('imei'=>$this->imei,'appid'=>$this->cpgamename,'coid'=>$this->cpconame,'baduser'=>0,'initstatus'=>1);//计费新增用户判断
       
        $model = M('usercount');
        //G('begin');
      
        $phoneinfo2 = $model->field('daysaleuser,daynewuser')->where($selectwhere2)->select(); //daysaleuser 付款用户  //daynewuser 日新用户
        //print_r(M()->_sql());
        //$phoneinfo3 = $model->field('daysaleuser,daynewuser')->where($selectwhere3)->select(); //bad标记

        $phoneinfo4 = $model->field('newuser')->where($selectwhere4)->select();  //daynewuser 新用户
        //print_r(M()->_sql());
        $sql = $model->_sql();
        //G('end');
        //$time =  G('begin2','end',10);
      //  var_dump($sql);
        //////var_dump($time);
        $newuser=0;//新增用户
 
            if ($phoneinfo4 != null){//新用户bad判断 
                $newuser=0;             //老用户
            }else{
                $newuser=1;     //新用户
            };
      

        $daynewuser=0;//活跃用户
      
           
       if ($phoneinfo2 != null){//活跃用户bad判断  
                               $daynewuser=0;    //非活跃用户
          }else{
                               $daynewuser=1;    //活跃用户
           };
        
           $data = array('mobile'=>$this->mobile,'coid'=>$this->coid,'appid'=>$this->appid,'egt'=>$this->egt,'propid'=>$this->propid,'bksid'=>$this->extDatatoo,'extdata'=>$this->extData,'iccid'=>$this->iccid,'city'=>$this->city,'os_info'=>$this->os_info,'os_model'=>$this->os_model,'net_info'=>$this->net_info,'mark'=>$this->mark,'ip'=>$this->ip,'imsi'=>$this->imsi,'imei'=>$this->imei,'newuser'=>$newuser,'saleuser'=>0,'daynewuser'=>$daynewuser,'daysaleuser'=>0,'initstatus'=>1,'city'=>$this->iccid_city);
          // if( $this->paycodemodel!=null){
                $a = $model->data($data)->add();
            // }
    }
    
    //支付宝微信用户新增
    public function user_count1($arrinfo)
    {
        
        //foreach($arrinfo as $value)
        //{
        //    if($value == null)
        //    {
        //        $this->requesterrforid(11111111,'useradd');
        //    }
        //}
        $selectwhere = array('imei'=>$this->imei,'appid'=>$this->cpgamename,'coid'=>$this->cpconame);
        $time=time();
        $time1 = date("Y-m-d",$time).' 00:00:00'; 
        $time2 = date("Y-m-d",$time).' 23:59:59'; 
        $initstatusu8ser=array('in','2,3');
        $daytime = array(array('egt',$time1),array('elt',$time2));
        $selectwhere2 = array('imei'=>$this->imei,'time'=>$daytime,'appid'=>$this->cpgamename,'coid'=>$this->cpconame,'baduser'=>0,'initstatus'=>$initstatusu8ser);//计费中的活跃用户判断
        //$selectwhere3 = array('imei'=>$this->imei,'time'=>$time2,'appid'=>$this->cpgamename,'coid'=>$this->cpconame,'baduser'=>1);
         $selectwhere4 = array('imei'=>$this->imei,'appid'=>$this->cpgamename,'coid'=>$this->cpconame,'baduser'=>0,'initstatus'=>$initstatusu8ser);//计费新增用户判断
        
        $model = M('usercount');
        //G('begin');
        
        $phoneinfo2 = $model->field('daysaleuser,daynewuser')->where($selectwhere2)->select(); //daysaleuser 付款用户  //daynewuser 日新用户
       //$bbbb= M()->_sql();
        //$phoneinfo3 = $model->field('daysaleuser,daynewuser')->where($selectwhere3)->select(); //bad标记

        $phoneinfo4 = $model->field('newuser')->where($selectwhere4)->select();  //daynewuser 新用户
        //print_r(M()->_sql());
        $sql = $model->_sql();
        //G('end');
        //$time =  G('begin2','end',10);
        //  var_dump($sql);
        //////var_dump($time);
        $newuser=0;//新增用户
        
        if ($phoneinfo4 != null){//新用户bad判断 
            $newuser=0;             //老用户
        }else{
            $newuser=1;     //新用户
        };
        

        $daynewuser=0;//活跃用户
        
        
        if ($phoneinfo2 != null){//活跃用户bad判断  
            $daynewuser=0;    //非活跃用户
        }else{
            $daynewuser=1;    //活跃用户
        };
        
        $data = array('mobile'=>$this->mobile,'coid'=>$this->coid,'appid'=>$this->appid,'egt'=>$this->egt,'propid'=>$this->propid,'bksid'=>$this->extDatatoo,'extdata'=>$this->extData,'iccid'=>$this->iccid_city,'city'=>$this->city,'os_info'=>$this->os_info,'os_model'=>$this->os_model,'net_info'=>$this->net_info,'mark'=>$this->mark,'ip'=>$this->ip,'imsi'=>$this->imsi,'imei'=>$this->imei,'newuser'=>$newuser,'saleuser'=>0,'daynewuser'=>$daynewuser,'daysaleuser'=>0,'initstatus'=>$this->initstatus);
       $b= $model->data($data)->add();
      // print_r($a=M()->_sql());
        
    }
    
    public function  init()
    {
        $this->model->startTrans();
        //第一次请求
        if($this->isreques == false)
        {
           // $this->doextData(); //生成自定义参数
          // $this->group_user(); //用户统计
            $this->user_count(array($this->mobile, $this->extData,$this->iccid,$this->city,$this->os_info,$this->os_model,$this->net_info,$this->mark,$this->ip,$this->imsi,$this->imei,$this->newuser,$this->saleuser,$this->appid,$this->coid,$this->egt,$this->propid));
        }
        ////////////////////////////////////////////////////////////////////////
        $this->model->startTrans();
        if($this->iserr == false)
        {
            $this->checktype();     //检测通道类型
        }
        //$this->model->startTrans();
        if($this->isreques == false)
        {
            $this->addOrdercount();     //添加数据到订单明细表
            $this->addDataforvnorder();     //添加数据到破解订单吧
        }
            $this->updateOrdercount();       //更新订单 如果失败则把 协议成功次数为0 成功为1
            if($this->resulet2 != 0)        //当请求失败时
            {
                $this->xystatus = 'xyfaild';
                $this->xystatus_int = 2;
                $this->addhistory();
                echo json_encode($this->resultdata);    //失败下发数据
                return;
            } 
            $this->xystatus = 'xysuccess';
            $this->xystatus_int = 1;
            $this->addhistory();
            echo json_encode($this->resultdata);        //成功下发数据
        
         //   $this->check_outletswarning();
            $this->check_telecomwarning();
            $this->check_paycodewarning();
    }
  //微信
    public function wxinit()
    {
        $this->model = M(); 
        $this->model->startTrans();  //开启事务
  
        if($this->iserr == false)
        {
            $this->checktype();     //检测通道类型
        }
        if($this->isreques == false)
        {
            $this->addOrdercount();     //添加数据到订单明细表
            $this->addDataforvnorder();     //添加数据到破解订单
        }
        $this->updateOrdercount();  //协议成功
        
        if($this->resulet2 != 0)    //当请求失败时
        {
            $this->xystatus = 'xyfaild';
            $this->xystatus_int = 2;
            $this->addhistory();
            return;
        } 
        $this->xystatus = 'xysuccess';
        $this->xystatus_int = 1;
        $this->addhistory();
        
        //      $this->check_outletswarning();
        $this->check_telecomwarning();
        $this->check_paycodewarning();
    }
    //天虎支付宝 
    public function Apiinit()
    {
        $this->model = M(); 
        $this->model->startTrans();  //开启事务
        if($this->isreques == false)
        {
            $this->doextData(); //生成自定义参数
            $this->user_count1(array($this->mobile, $this->extData,$this->iccid,$this->city,$this->os_info,$this->os_model,$this->net_info,$this->mark,$this->ip,$this->imsi,$this->imei,$this->newuser,$this->saleuser,$this->appid,$this->coid,$this->egt,$this->propid));
        }
        if($this->iserr == false)
        {
            $this->checktype();     //检测通道类型
        }
        if($this->isreques == false)
        {
            $this->addOrdercount();     //添加数据到订单明细表
            $this->addDataforvnorder();     //添加数据到破解订单
        }
        $this->updateOrdercount();  //协议成功
        
        if($this->resulet2 != 0)    //当请求失败时
        {
            $this->xystatus = 'xyfaild';
            $this->xystatus_int = 2;
            $this->addhistory();
            return;
        } 
        $this->xystatus = 'xysuccess';
        $this->xystatus_int = 1;
        $this->addhistory();
        
  //      $this->check_outletswarning();
        $this->check_telecomwarning();
        $this->check_paycodewarning();
    }


    /**
     * Summary of iskouliang    判断是否bad
     */
    public function iskouliang()
    {
        if($this->result_code == '00')
        {
            
            $seelectwhere = array('coid'=>$this->cpconame);
            $model = M('colist');
            $pseudocode = $model->field('bad,badvalue,badid')->where($seelectwhere)->select();
            $kouliangbi = $pseudocode['0']['bad'];
            $kouliangvalue = $pseudocode['0']['badvalue'];
            $kouliangid = $pseudocode['0']['badid'];
            $kouliangid = explode('_',$kouliangid);
            if($kouliangbi!=0)
            {
                if($kouliangvalue == 11)
                {
                    $kouliangvalue = 1;
                }                
                foreach($kouliangid as $value)
                {
                    if($kouliangvalue == $value)
                    {
                        $this->iskouliang = true;
                    }
                }
                $kouliangvalue++;
            }    
            $upatadata = array('badvalue'=>$kouliangvalue);
            $tablename = 'bks_colist';
            $id = 0;
            $this->updatewheredataforsql($tablename,$seelectwhere,$upatadata,false,$id);
        }
    }
   /**
    * Summary of addDataforvnorder  添加破解订单
    */
   public function addDataforvnorder()  
   {
       $orderid=$this->orderid;
       if($orderid != null && $orderid != '')
       {
           $orderid = $orderid;
       }
       if($this->telecomtype==''){
          
           $this->telecomtype='0';
           $this->paycode='0';
       }
        $time = \PublicFunction::getTimeForString(time());
        $data = array('id'=>$this->maxid,'type'=>$this->telecomtype,'paycode'=>$this->paycode,'telecom'=>$this->telecomname,
            'co'=>$this->coid,'cpgame'=>$this->cpgamename,'egt'=>$this->egt,'prop'=>$this->propid,
        'extern'=>$this->extData,'city'=>$this->city,'xystatus'=>3,'status'=>1,'orderstatus'=>3,'time'=>$time,'iccid_city'=>$this->iccid_city,'imsi'=>$this->imsi,'imei'=>$this->imei,'extern2'=>$this->extData2,'bksid'=>$this->extDatatoo,'errorcode'=>$this->resulet2,'orderid'=>$orderid,'maxid'=>$this->maxid2);
        $tablename = C('DB_PREFIX').$this->dateformonth.'vnorder';
        $this->adddataforsql($tablename,$data,false,$id);
    }
    /**
     * Summary of updateDatafororder    更新订单查询的唯一识别码
     * @param mixed $maxid 
     */
    //public function updateDatafororder()
    //{
    //    $data = array('extern2'=>$this->extData2);
    //    $selectwhere = array('id'=>$this->maxid);
    //    $tablename = C('DB_PREFIX').$this->dateformonth.'vnorder';
    //    $this->updatewheredataforsql($tablename,$selectwhere,$data,false,$id);
    //}
   /**
    * Summary of updateDatafororderAsxystatus
    * @param mixed $xystatus 
    */
    public function updateDatafororderAsxystatus($xystatus ='')
    {
        $selectwhere = array('id'=>$this->maxid);
        $data = array('xystatus'=>$xystatus);
        $tablename = C('DB_PREFIX').$this->dateformonth.'vnorder';
        $this->updatewheredataforsql($tablename,$selectwhere,$data,false,$id);
    }
    public function addOrdercount()
    {
        $time = \PublicFunction::getTimeForString(time());
        $day = \PublicFunction::getTimeForDay($time);
        $hour = \PublicFunction::getTimeForHour($time);
        $city = $this->iccid_city_id;
        if($city == null && $city == '')
        {
            $city = $this->city_id;
        }
        if($city == null & $city == '')
        {
            $city = 50;
        }
        $orderid=$this->orderid;
        if($orderid != null && $orderid != '')
        {
            $orderid = $orderid;
        }
        
        $data = array('id'=>$this->maxid,'time'=>$time,'day'=>$day,'hour'=>(int)$hour,'egt'=>$this->egt,'telecomname'=>$this->telecomname,
            'co'=>$this->coid,'cpgame'=>$this->cpgamename,'prop'=>$this->propid,'paycode'=>(int)$this->paycode,
            'msggold'=>0,'payresult'=>1,'status'=>1,'xysuccess'=>0,'paysuccess'=>0,'badvalue'=>0,'badgold'=>0,'extern2'=>$this->extData2,'bksid'=>$this->extDatatoo,'city'=>$city,'apiorderid'=>$orderid);
        $tablename = C('DB_PREFIX').$this->dateformonth.'ordercount';
        $id = 0;
        $this->adddataforsql($tablename,$data,false,$id);
    }
    public function updateOrdercount()
    {
        if($this->resulet2 == 0)
        {
            $xysuccess = 1;
            $Mosuccess = 0;
        } else
        {
            $xysuccess = 0;
            $Mosuccess = 0;
        }
        $updatedata = array('xysuccess'=>$xysuccess,'mosuccess'=>$Mosuccess);
        $tablename = C('DB_PREFIX').$this->dateformonth.'ordercount';
        $selectwhere = array('id'=>$this->maxid);
        $id = 0;
        $this->updatewheredataforsql($tablename,$selectwhere,$updatedata,false,$id);
        //$time = \PublicFunction::getTimeForString(time());
        //$day = \PublicFunction::getTimeForDay($time);
        //$hour = \PublicFunction::getTimeForHour($time);
        //$data = array('id'=>$this->maxid,'day'=>$day,'hour'=>$hour,'telecomname'=>$this->telecomname,'outletsname'=>$this->outletsname,'paycode'=>$this->paycode,
        //    'msggold'=>$this->fee,'payresult'=>1,'xysuccess'=>0,'paysuccess'=>0,'badvalue'=>0,'badgold'=>0,'bads'=>0,'extern2'=>$this->extData2);
    }


    /**
     * Summary of addDataforordertelecom    添加通道查询数据
     * @return mixed
     * 
     */
    public function addDataforordertelecomAndDay_Public($modelname = '')
    {
        $time = \PublicFunction::getTimeForString(time());
        $oldday = \PublicFunction::getTimeForDay($time);
        $hour = \PublicFunction::getTimeForHour($time);
        if($modelname =='ordertelecom')
            $selectwhere = array('telecomname'=>$this->telecomname,'outletsname'=>$this->outletsname,'paycode'=>$this->paycode);
        else
            $selectwhere = array('telecomname'=>$this->telecomname,'outletsname'=>$this->outletsname);
        $tablename = C('DB_PREFIX').$modelname;
        $vncountinfo = $this->model->table($tablename)->order('id DESC')->where($selectwhere)->select();
        $tablename = C('DB_PREFIX').'telecomlist';
        $lsinfo =  $this->model->table($tablename)->where(array('id'=>$this->telecomname))->select();
        $utf8name = $lsinfo['0']['name'];
        if($modelname =='ordertelecom')
            $data = array('time'=>$time,'day'=>$oldday,'hour'=>$hour,'telecomname'=>$this->telecomname,'outletsname'=>$this->outletsname,'paycode'=>$this->paycode,
                'msggold'=>'00.00','payresult'=>0,'paysuccess'=>0,'success'=>'00.00','outletssuccess'=>'00.00',
                'klvalue'=>0,'klgold'=>'00.00','kls'=>'00.00','extern2'=>$this->extData2,'utf8name'=>$utf8name);
        else
            $data = array('time'=>$time,'day'=>$oldday,'telecomname'=>$this->telecomname,'outletsname'=>$this->outletsname,'paycode'=>$this->paycode,
                'msggold'=>'00.00','payresult'=>0,'paysuccess'=>0,'success'=>'00.00','outletssuccess'=>'00.00',
                'klvalue'=>0,'klgold'=>'00.00','kls'=>'00.00','extern2'=>$this->extData2,'utf8name'=>$utf8name);
        if($vncountinfo == null)
        {
            $tablename = C('DB_PREFIX').$modelname;
            $this->adddataforsql($tablename,$data,false,$id);
        }
        else
        {
            $time = $vncountinfo['0']['time'];
            if($modelname =='ordertelecom')
            {
                $action = \PublicFunction::returnAction($time);
            }
            else
            {
                $action = \PublicFunction::returnAction($time,'day');
            }
            if($action == 'add')
            {
                $tablename = C('DB_PREFIX').$modelname;
                $this->adddataforsql($tablename,$data,false,$id);
            }
            else
            {
                if($modelname =='ordertelecom')
                {
                    $selectwhere = array('telecomname'=>$this->telecomname,'outletsname'=>$this->outletsname,'paycode'=>$this->paycode,'time'=>$time);
                }
                else
                {
                    $selectwhere = array('telecomname'=>$this->telecomname,'outletsname'=>$this->outletsname,'time'=>$time);
                }
                $time = time();
                if($modelname =='ordertelecom')
                $data = array('time'=>$time,'extern2'=>$this->extData2,'paycode'=>$this->paycode);
                else
                $data = array('time'=>$time,'day'=>$oldday,'extern2'=>$this->extData2);	
                $tablename = C('DB_PREFIX').$modelname;
                $this->updatewheredataforsql($tablename,$selectwhere,$data,false,$id);
            }
        }
        return $vncountinfo;
    }
    //短代
    function updateuser()
    {
        $time=time();
        $datatime1s = (string)date("Y-m-d",$time); 
        $time1 = $datatime1s.' '.'00:00:00';
        $time2 = $datatime1s.' '.'23:59:59';
        $selectwherearr = array('imei'=>$this->imei,'time'=>array(array('egt',$time1),array('elt',$time2)),'daysaleuser'=>'1','appid'=>$this->cpgamename,'coid'=>$this->cpconame);//付费用户判断    
        $tablename2 = 'usercount';
        $modelinfo = M($tablename2)->where($selectwherearr)->select();

      
        $a = M()->_sql();
        if($this->result_code == '00')
        {
            $saleuser=1;
            if($modelinfo!= null){ //老用户              
                    if($this->iskouliang == false){//非bad值
                        $dasaleuser=0;  //天的老用户
                        $updatedata['daysaleuser'] =$dasaleuser;  //0  
                        $updatedata['saleuser'] = $saleuser;       //1
                        $updatedata['newuser'] = 0;          //0
                        $updatedata['daynewuser'] = 0; 
                    }else{ //bad值
                        $dasaleuser=0; //付费用户 
                        $newuser=0;//新用户
                        $daynewuser=0;//活跃用户
                        $updatedata['saleuser'] = $saleuser;        //1
                        $updatedata['daysaleuser'] = $dasaleuser;   //0
                        $updatedata['newuser'] = $newuser;          //0
                        $updatedata['daynewuser'] = $daynewuser;    //0      
                        $updatedata['baduser']=1;
                    }
            }else{ //新用户 
                    if($this->iskouliang == false){//非bad值
                        $dasaleuser=1;          //天的付费用户
                        $updatedata['daysaleuser'] =$dasaleuser;    //1
                        $updatedata['saleuser'] = $saleuser;        //1
                        $updatedata['daynewuser'] = 1; 
                        //$updatedata['newuser']=1;
                    }else{  //bad值

                        $dasaleuser=0; //付费用户 
                        $newuser=0;//新用户
                        $daynewuser=0;//活跃用户
                        $updatedata['saleuser'] = $saleuser;    //1
                        $updatedata['daysaleuser'] = $dasaleuser;   //0
                        $updatedata['newuser'] = $newuser;      //0
                        $updatedata['daynewuser'] = $daynewuser;       //0 
                        $updatedata['baduser']=1;
                    }
                                  
            }
        }
        $tablename =C('DB_PREFIX').'usercount';
        $selectwhere = array('bksid'=>$this->extDatatoo);   
        $this->updatewheredataforsql($tablename,$selectwhere,$updatedata,false,$id);
    }
    //微信
    function updateuser1()
    {
        $time=time();
        $datatime1s = (string)date("Y-m-d",$time); 
        $time1 = $datatime1s.' '.'00:00:00';
        $time2 = $datatime1s.' '.'23:59:59';
        $selectwherearr = array('imei'=>$this->imei,'time'=>array(array('egt',$time1),array('elt',$time2)),'appid'=>$this->cpgamename,'coid'=>$this->cpconame,'daysaleuser'=>1);//付费用户判断  
        $selectwherearr3 = array('imei'=>$this->imei,'time'=>array(array('egt',$time1),array('elt',$time2)),'appid'=>$this->cpgamename,'coid'=>$this->cpconame);//付费用户判断
        $selectwherearr2 = array('imei'=>$this->imei,'appid'=>$this->cpgamename,'coid'=>$this->cpconame);//付费用户判断    
        $tablename2 = 'usercount';
        $modelinfo = M($tablename2)->where($selectwherearr)->select();
        $modelinfo2 = M($tablename2)->where($selectwherearr2)->select();
        $modelinfo3 = M($tablename2)->where($selectwherearr3)->select();
        $a = M()->_sql();
        if($this->result_code == '00')
        {
            $updatedata['initstatus']=2;
            if($modelinfo!= null){ //老用户              
                    $updatedata['saleuser'] = 1;        //1
                    $updatedata['daysaleuser'] = 0;   //0
                    $updatedata['newuser'] = 0;          //0
                        
            }else{ //新用户 
                    $updatedata['saleuser'] = 1;    //1
                    $updatedata['daysaleuser'] = 1;   //1
                    if($modelinfo2!= null){// 判读是否历史新老用户
                        $updatedata['newuser'] = 0;      //1
                    }else{
                        $updatedata['newuser'] = 1;      //1
                    }
                   
           }

            if($modelinfo3!= null){// 判读是否历史新老用户
                $updatedata['daynewuser'] = 0;       //1 
            }else{
                $updatedata['daynewuser'] = 1;       //1 
            }

        }
        $tablename =C('DB_PREFIX').'usercount';
        $selectwhere = array('bksid'=>$this->extDatatoo);   
        $this->updatewheredataforsql($tablename,$selectwhere,$updatedata,false,$id);
    }
    //支付宝
    function updateuser2()
    {
        $time=time();
        $datatime1s = (string)date("Y-m-d",$time); 
        $time1 = $datatime1s.' '.'00:00:00';
        $time2 = $datatime1s.' '.'23:59:59';
        $selectwherearr = array('imei'=>$this->imei,'time'=>array(array('egt',$time1),array('elt',$time2)),'appid'=>$this->cpgamename,'coid'=>$this->cpconame,'daysaleuser'=>1);//付费用户判断    
        $selectwherearr2 = array('imei'=>$this->imei,'appid'=>$this->cpgamename,'coid'=>$this->cpconame);//付费用户判断  
        $selectwherearr3 = array('imei'=>$this->imei,'time'=>array(array('egt',$time1),array('elt',$time2)),'appid'=>$this->cpgamename,'coid'=>$this->cpconame);//付费用户判断
        $tablename2 = 'usercount';
        $modelinfo = M($tablename2)->where($selectwherearr)->select();  //$aaavvv=M()->_sql();
        $modelinfo2 = M($tablename2)->where($selectwherearr2)->select();
        $modelinfo3 = M($tablename2)->where($selectwherearr3)->select();
        $a = M()->_sql();
        if($this->result_code == '00')
        {
            $updatedata['initstatus']=3;
            if($modelinfo!= null){ //老用户              
                $updatedata['saleuser'] = 1;        //1
                $updatedata['daysaleuser'] = 0;   //0
                $updatedata['newuser'] = 0;          //0
                   
            }else{ //新用户 
                $updatedata['saleuser'] = 1;    //1
                $updatedata['daysaleuser'] = 1;   //1
                if($modelinfo2!= null){// 判读是否历史新老用户
                    $updatedata['newuser'] = 0;      //1
                }else{
                    $updatedata['newuser'] = 1;      //1
                }
                
            }

            if($modelinfo3!= null){// 判读是否历史新老用户
                $updatedata['daynewuser'] = 0;       //1 
            }else{
                $updatedata['daynewuser'] = 1;       //1 
            }

        }
        $tablename =C('DB_PREFIX').'usercount';
        $selectwhere = array('bksid'=>$this->extDatatoo);   
        $this->updatewheredataforsql($tablename,$selectwhere,$updatedata,false,$id);
    }
    
    function updateordercoutforpay()
    {
        $tablename = C('DB_PREFIX').$this->dateformonth.'ordercount';
        if($this->result_code == '00')
        {
            $paysuccess = 1;

            $fee = $this->fee;
        }
        else
        {
            $paysuccess = 0;
            $fee = 0;
        }
        $updatedata = array('paysuccess'=>$paysuccess,'msggold'=>$fee);       
        if($this->result_code == '00')
        {
            if($this->iskouliang == true)
            {
                $badvalue = 1;  //bad条
                $badgold = $this->fee;  //bad值(元)
                $data2 = array('badvalue'=>$badvalue,'badgold'=>$badgold);
                $updatedata = array_merge($updatedata,$data2);
            }
        }

        $tablename = C('DB_PREFIX').$this->dateformonth.'ordercount';
        $selectwhere = array('extern2'=>$this->extData2);
        $id = 0;
        $this->updatewheredataforsql($tablename,$selectwhere,$updatedata,false,$id);
    }
    function DataHandleCenter()
    {
        $time = \PublicFunction::getTimeForString(time());
        $iskl = 2;
        if($this->iskouliang == true)
            $iskl = 1;
        if($this->mobile == null)
        {
            $this->mobile = '';
        }
        $time = \PublicFunction::getTimeForString(time());
        if($this->result_code === '00')
        {
            $this->str1 = '成功订单'.'<br>'.$this->str1;
            $orderstatic = 1;
        }
        else
        {
            $this->str1 = '失败订单'.'<br>'.$this->str1;
            $orderstatic = 2;
        }
    //    $extern = '222';
        $this->tableordername = array('time'=>$time,'co'=>$this->cpconame,'cpgame'=>$this->cpgamename,'prop'=>$this->cpgamepropname,'telecom'=>$this->telecomname,'paycode'=>$this->fee,'mobile'=>$this->mobile,'orderstatus'=>$orderstatic,
        'status'=>2,'value'=>0,'Iskl'=>$iskl,'extern2'=>$this->extData2,'bksid'=>$this->extDatatoo,'extern'=>$this->extData);
        $this->tableorderhistoryname = array('time'=>$time,'oid'=>$this->maxid,'logtype'=>$this->telecomtype,'status'=>1,'log'=>$this->str1);
    }
    public function SqlHandleCenter()
    {
        $tablename = C('DB_PREFIX').$this->dateformonth.'order';        //extern2  这个字段为唯一的 这里起去重作用
        $id = 0;
        $this->adddataforsql($tablename,$this->tableordername,false,$id);
        $this->maxid = $id;
        $this->tableorderhistoryname['oid'] = $this->maxid;
        $tablename =  C('DB_PREFIX'). $this->dateformonth.'orderhistory';
        $this->adddataforsql($tablename,$this->tableorderhistoryname,false,$id);
    }
   
  public function callbackupdateDataforvnorderindex()
   {
        if($this->result_code == '00')
        {
            $orderstatus = 1;
            $xystatus= 1;
        }
        else
        {
            $orderstatus = 2;
            $xystatus = 2;
        }
        $selectwhere = array('extern2'=>$this->extData2);
        $data = array('orderstatus'=>$orderstatus,'xystatus'=>$xystatus);
        $tablename = C('DB_PREFIX').$this->dateformonth.'vnorder';
        $this->updatewheredataforsql($tablename,$selectwhere,$data,false,$id);
   }

   public function callbacksendmsgtooutletsforurl()
    {
        $selectwhere = array('extern2'=>$this->extData2);
        $tablename = C('DB_PREFIX'). $this->dateformonth.'vnorder';
        $info = $this->model->table($tablename)->where($selectwhere)->select();
        $info = $info['0'];
        $extData = $info['extern'];
        $outletsid = $info['co'];
        $gameappid= $info['cpgame'];
        $this->coid=$outletsid;
        $this->appid=$gameappid;
        //$telecomid = $info['telecom'];
        //$selectwhere = array('telecomname'=>$telecomid);
        //$tablename = C('DB_PREFIX').'telecom';
        //$telecom = $this->model->table($tablename)->field('')->where($selectwhere)->select();
        $selectwhere = array('coid'=>$outletsid);
        $tablename = C('DB_PREFIX').'colist';
        $outlets = $this->model->table($tablename)->where($selectwhere)->select();
        $outlets = $outlets['0'];
        $selectwhere2 = array('cpgame'=>$gameappid);
        $tablename2 = C('DB_PREFIX').'gamelist';
        $outappid = $this->model->table($tablename2)->where($selectwhere2)->select();
        $outappid = $outappid['0'];
        $appurl=$outappid['gameback'];
        //$egt = $telecom['0']['egt'];
        $egt = $this->egt;
        $url = $outlets['callurl'];  //厂商的URL
        $egt = \PublicFunction::checkegtnum($egt);      //1、电信 2、联通 3、移动  
        $fee = $this->fee * 100;  //分
        if($this->result_code == '00')
        {
            $this->model->commit();
            RequestData::callback($fee,$extData,$egt,$url);     //厂商回调URL
        }
        if($this->result_code == '00'){
            $this->model->commit();
            RequestData::callback2($fee,$extData,$egt,$appurl);     //游戏回调URL
        }
        else
        {
            $this->model->commit();
        }
    }
  
   public function callback2($fee = '',$extData='',$egt='',$url = '')        //回调给厂商下家数据
   {
       $date  = time();
       $time = \PublicFunction::getTimeForString(time());
       $data = array('timestamp'=>(string)$date,'extData'=>$extData,'fee'=>(string)$fee,'mobileType'=>(string)$egt);

       if($this->iskouliang == true)
       {
           $this->model->startTrans();
           $isos = 'bad data';
           $isos = str_replace('<','',$isos); 
           $data['appid'] = $this->appid;
           $str = '下家同步'.'<br>'.$url.'<br>'.json_encode($data).'<br>'.'返回：'.$isos;
           $appname='游戏';
           $data = array('time'=>$time,'oid'=>$this->maxid,'logtype'=>$this->telecomtype,'status'=>3,'log'=>$str,'appname'=>$appname);
           $tablename = C('DB_PREFIX'). $this->dateformonth.'orderhistory';
           $this->adddataforsql($tablename,$data,true,$id);
           return;
       }
       else
       {
           $isos = \JSHttpRequest::curl_file_get_contents($url,json_encode($data));
           $isok = strpos($isos,'success');    //查找success 在$isos第一次出现的位置	返回字符串在另一字符串中第一次出现的位置,没有没有FALSE。字符串位置从 0 开始，不是从 1 开始。
           if($isok === false)
           {
               for($index = 0;$index<3;$index++)
               {
                   $isos = \JSHttpRequest::curl_file_get_contents($url,json_encode($data));
                   $isos = (string)$isos;
                   $isok = strpos($isos,'success');
               }
           }
           $this->model->startTrans();
           if($isok === false)
           {
               $isos = str_replace('<','',$isos);
               $data['appid'] = $this->appid;
               $str = '下家同步'.'<br>'.$url.'<br>'.json_encode($data).'<br>'.'返回：'.$isos;
               $appname='游戏';
               $data = array('time'=>$time,'oid'=>$this->maxid,'logtype'=>$this->telecomtype,'status'=>3,'log'=>$str,'appname'=>$appname);
               $tablename = C('DB_PREFIX'). $this->dateformonth.'orderhistory';
               $this->adddataforsql($tablename,$data,true,$id);
               return;
           }
           else
           {
               $data['coid'] = $this->coid;        //同步下家成功
               $str = '下家同步'.'<br>'.$url.'<br>'.json_encode($data).'<br>'.'返回：'.$isos;
               $data = array('time'=>$time,'oid'=>$this->maxid,'logtype'=>$this->telecomtype,'status'=>3,'log'=>$str);
               $tablename = C('DB_PREFIX'). $this->dateformonth.'orderhistory';
               $this->adddataforsql($tablename,$data,false,$id);
               $selectwhere = array('id'=>$this->maxid);
               $tablename = C('DB_PREFIX'). $this->dateformonth.'order';
               $info = $this->model->table($tablename)->field('value')->where($selectwhere)->select();
               $info = $info['0'];
               $value = $info['value'];
               $value+=1;
               $data = array('value'=>$value,'status'=>1);
               $this->updatewheredataforsql($tablename,$selectwhere,$data,true,$id);
               //      $this->model->table($tablename)->where($selectwhere)->data($data)->save();
               return;
           }        
       }  
   }
   
   
 //  public $time = 0;
   public function callback($fee = '',$extData='',$egt='',$url = '')        //回调给厂商下家数据
    {
        $date  = time();
        $time = \PublicFunction::getTimeForString(time());
        $data = array('timestamp'=>(string)$date,'extData'=>$extData,'fee'=>(string)$fee,'mobileType'=>(string)$egt);

        if($this->iskouliang == true)
        {
            $this->model->startTrans();
            $isos = 'bad data';
            $isos = str_replace('<','',$isos); 
            $data['coid'] = $this->coid;
            $str = '下家同步'.'<br>'.$url.'<br>'.json_encode($data).'<br>'.'返回：'.$isos;
            $data = array('time'=>$time,'oid'=>$this->maxid,'logtype'=>$this->telecomtype,'status'=>3,'log'=>$str);
            $tablename = C('DB_PREFIX'). $this->dateformonth.'orderhistory';
            $this->adddataforsql($tablename,$data,true,$id);
            return;
        }
        else
        {
                $isos = \JSHttpRequest::curl_file_get_contents($url,json_encode($data));
                $isok = strpos($isos,'success');    //查找success 在$isos第一次出现的位置	返回字符串在另一字符串中第一次出现的位置,没有没有FALSE。字符串位置从 0 开始，不是从 1 开始。
                if($isok === false)
                {
                    for($index = 0;$index<3;$index++)
                    {
                        $isos = \JSHttpRequest::curl_file_get_contents($url,json_encode($data));
                        $isos = (string)$isos;
                        $isok = strpos($isos,'success');
                    }
                }
                $this->model->startTrans();
                if($isok === false)
                {
                    $isos = str_replace('<','',$isos);
                    $data['coid'] = $this->coid;
                    $str = '下家同步'.'<br>'.$url.'<br>'.json_encode($data).'<br>'.'返回：'.$isos;
                    $data = array('time'=>$time,'oid'=>$this->maxid,'logtype'=>$this->telecomtype,'status'=>3,'log'=>$str);
                    $tablename = C('DB_PREFIX'). $this->dateformonth.'orderhistory';
                    $this->adddataforsql($tablename,$data,true,$id);
                    return;
                }
                else
                {
                    $data['coid'] = $this->coid;        //同步下家成功
                    $str = '下家同步'.'<br>'.$url.'<br>'.json_encode($data).'<br>'.'返回：'.$isos;
                    $data = array('time'=>$time,'oid'=>$this->maxid,'logtype'=>$this->telecomtype,'status'=>3,'log'=>$str);
                    $tablename = C('DB_PREFIX'). $this->dateformonth.'orderhistory';
                    $this->adddataforsql($tablename,$data,false,$id);
                    $selectwhere = array('id'=>$this->maxid);
                    $tablename = C('DB_PREFIX'). $this->dateformonth.'order';
                    $info = $this->model->table($tablename)->field('value')->where($selectwhere)->select();
                    $info = $info['0'];
                    $value = $info['value'];
                    $value+=1;
                    $data = array('value'=>$value,'status'=>1);
                    $this->updatewheredataforsql($tablename,$selectwhere,$data,true,$id);
                //      $this->model->table($tablename)->where($selectwhere)->data($data)->save();
                    return;
                }        
        }  
   }
}