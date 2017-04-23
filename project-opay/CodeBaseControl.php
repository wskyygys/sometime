<?php

/**
 * CodeBaseControl short summary.
 *
 * CodeBaseControl description.
 *
 * @version 1.0
 * @author 华亮
 */
class CodeBaseControl
{
    var $codetype = 0;
    ///////////////////////////////////////////////////////////////////
    
    ///////////////////////////////////////////////////////////////////
    var $codetypeforrequest = null;
    var $imsi = null;
    var $imei = null;
    var $code = null;
    var $mobile = null;
    var $extData = null;
    var $iccid = null;
    var $ip = null;
    var $city = null;
    var $city_id = null;
    var $os_info = null;
    var $operatorsegt= null; //运营商
    var $os_model = null;
    var $net_info = null;
    var $mark='';
    var $fee = 0;
    var $gamename = '';
    var $paycodemodel=null;  //计费点代码
    var $propname = '';
    var $codeinfo = null;
    var $type = null;
    var $pseudocodeinfo = null;
    var $coinfo=null;  //厂商数据
    var $telecompool = null;
    var $iskouliang = false;
    var $message = '';
    /////////////////////////////////////
    var $paycode = '';      //计费代码名称ID
    var $gameid = 0;
    var $telecomtype = 0;
    var $telecomname = 0;       //伪代码对应的通道名ID
    var $alltelecomname = 0;        //厂商对应的通道
    var $cotelecomname=0;       //厂商表对应的通道名称ID
    var $outletsname = 0;
    var $extData2='';       //自定义参数
    var $extDatatoo='';
    var $maxid = 0;
    var $maxid2=0;
    var $url = '';
    var $post_data= array();
    var $cpid = '';
    var $extparams = '';
    var $CtiyERR ='';       //iccoid屏蔽的省份
    var $cityskd='';        //sdk过来的省份 （已经屏蔽的值）
    var $paypriority=2;     //默认为短代优先
    var $paycodeapi='';
    var $resulet = 0;           //返回结果
    var $smstype = '';          //短信内容
    var $smsport = '';          //端口
    var $smscontent = '';          //指令内容
    var $resultdata = '';
    var $codetelecomname = '';
    var $result = '';
    var $result2 = '';
    var $iccid_city = '';           //iccid对应的省份 中文
    var $iccid_city_id = '';        //iccid对应的省份 ID
    var $result_code='';    //订单状态
    var $isreques = false;          //判断是否为第二步
    var $iscodetype_jump = 1;
    var $sid = 0;
    var $model = null;   //公共模型
    var $egtnum = 0;
    var $egtname = '';
    var $codepattern = 'mt';        //默认为手动模式
    var $iserr = false;   
    var $bksen = '';
    var $base = '';
    var $nomobile = false;
    var $dateformonth = '';
    var $updayvalue = 0;        //计费日限
    ///////////
    var $updayvalueforcode = 0;     //  CODE 日限
    var $outletsutf8name='';        //厂商名称
    var $gameutf8name='';       //游戏名称
    var $updayvalueforoutlets = 0;      //厂商日限  
    var $requesttype = 0;
    var $outletstype = 0;    
    var $outletstelecom = array();      //渠道指定通道
    var $prioritytelecom = array();
    //////////////////////
    var $cpgamepropname = 0;       //CP游戏道具名称
    var $cpconame = 0;         //CP公司名称
    var $cpgamename = 0;       //CP游戏名称
    ///////////////////
    var $newuser='0';       //新增用户  0:默认已有用户   1:新用户
    var $saleuser='0';      //计费用户  0:默认非计费用户         1:计费用户 
    var $accid='';           //厂商ID
    var $appid='';          //游戏ID
    var $wxstatus='';
    var $wxextDatatoo = '';
    var $initstatus=1;
    
    
    /**
     * Summary of init_val      计费初始化变量
     * @param mixed $imsi 
     * @param mixed $imei 
     * @param mixed $code 
     * @param mixed $mobile 
     * @param mixed $extData 
     * @param mixed $iccid 
     * @param mixed $ip 
     * @param mixed $city 
     * @param mixed $os_info 
     * @param mixed $os_model 
     * @param mixed $net_info 
     * @param mixed $newuser
     * @param mixed $saleuser
     * @param mixed $operators  //运营商
     */
    public function init_val($imsi='',$imei='',$mobile='',$extData='',$iccid='',$ip='',$city='',$os_info='',$os_model='',$net_info='',$mark='',$coid='',$appid='',$propid='',$operatorsegt='')
    {
        $this->os_info = $os_info;
        $this->os_model = $os_model;
        $this->net_info= $net_info;
        $this->mark = $mark;
        $this->model = M();
        $this->codetype = 0;
        $this->imsi = $imsi;
        $this->imei = $imei;
        $this->mobile = $mobile;
        $this->coid=$coid;
        $this->egt=$operatorsegt; //运营商
        $this->appid=$appid;
        $this->propid=$propid;
        if($extData==null ||$extData==''){
                 $this->extData = 'extData';
        }else{
                 $this->extData=$extData;
        }
        $this->iccid = $iccid;
        $this->ip = $ip;
        $this->city = $city;
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth = $this->dateformonth.'2'.'_';         
        $this->message = "success";
        $this->initstatus=1;
        $this->iccid_city = '';
        $this->iccid_city_id = 0;
        if($iccid != ''){
            $this->checkcityforiccd($iccid);
        }
        if($this->isreques == false)      //如果当前为第一步(Api1)的请求则执行
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
            $this->extDatatoo =$this->extDatatoo;           //生成bksid
            $this->checkData(array($this->ip,$this->imsi,$this->imei));  //检测参数并添加手机号码到表
            $len = strlen($this->mobile);   //获取手机长度   
            
            if($len != 11 || $this->mobile == '')
            {
                $this->nomobile = true;
            }
            if($this->egt== '-1'){
                    //第三方支付
                    $this->datainfo=2;
                    $whereco=array('coid'=>$this->coid);
                    $telecomsco = M('colist')->field('tpaypool')->where($whereco)->select();
                    $telecomsco=$telecomsco['0'];
                    $this->tpaypool=$telecomsco['tpaypool'];
                    $wherepool=array('id'=>$this->tpaypool);
                    $telecomspool = M('telecompools')->field('tpaytelecoms,wchartelecoms,ucardtelecoms')->where($wherepool)->select();
                    $telecomspool=$telecomspool['0'];
                    $tpaytelecoms=explode('_',$telecomspool['tpaytelecoms']);
                    $wchartelecoms=explode('_',$telecomspool['wchartelecoms']);
                    $ucardtelecoms=explode('_',$telecomspool['ucardtelecoms']);
                    unset($tpaytelecoms['0']);
                    unset($wchartelecoms['0']);
                    unset($ucardtelecoms['0']);
                    $paytelecoms=array('ZFB'=>$tpaytelecoms,'WX'=>$wchartelecoms,'YL'=>$ucardtelecoms);
                    $data['city']= $this->city  ? $this->city :'city';
                    $data['ip']= $this->ip  ? $this->ip :'ip';
                    $data['extdata']=$this->extData ? $this->extData:'extdata';
                    $data['os_info'] = $this->os_info ? $this->os_info:'os_info';
                    $data['iccid'] = $this->iccid ? $this->iccid:'iccid';
                    $data['os_model'] =$this->os_model ? $this->os_model:'os_model';
                    $data['net_info'] = $this->net_info ? $this->net_info:'net_info';
                    $data['mark'] =$this->mark ? $this->mark:'mark';
                    $data['model'] =$this->model ? $this->model:'model';
                    $data['codetype'] =$this->codetype ? $this->codetype:'codetype';
                    $data['imsi'] =$this->imsi ? $this->imsi:'imsi';
                    $data['imei'] =$this->imei ? $this->imei:'imei';
                    $data['mobile'] =$this->mobile ? $this->mobile:'mobile';
                    $data['coid'] =$this->coid ? $this->coid:'coid';
                    $data['egt'] =$this->egt ? $this->egt:'egt'; //运营商
                    $data['cpgame'] =$this->appid ? $this->appid:'appid';
                    $data['propid'] =$this->propid ? $this->propid:'propid';
                    $data['bksid'] =  $this->extDatatoo;
                    $a = M('usercount')->add($data);
                    //无卡走vnorder
                    $extern2 = $data['bksid'];
                    $paycode = $this->paycode ?  $this->paycode:0;
                    $errorcode = $this->resulet2 ? $this->resulet2:'1001';
                    $time = \PublicFunction::getTimeForString(time());
                    $data = array('id'=>$this->maxid,'type'=>$this->telecomtype,'paycode'=>$paycode,'telecom'=>$this->telecomname,
                        'co'=>$this->coid,'cpgame'=>$this->appid,'egt'=>$this->egt,'prop'=>$this->propid,
                    'extern'=>$this->extData,'city'=>$this->city,'xystatus'=>2,'status'=>1,'orderstatus'=>3,'time'=>$time,'iccid_city'=>$this->iccid_city,'imsi'=>$this->imsi,'imei'=>$this->imei,'extern2'=>$extern2,'bksid'=>$this->extDatatoo,'errorcode'=>$errorcode,'orderid'=>$orderid,'maxid'=>$this->maxid);
                    $tablename = $this->dateformonth.'vnorder';
                    $vnorder = M($tablename)->add($data);
                    $b =  M($tablename)->_Sql();
                    //生成下发数据
                    $this->resultegtcode = array('code'=>'egt','message'=>$this->message,'maxid'=>$id,
                        'mobile'=>$this->mobile,'apistatus'=>$this->datainfo,'contet'=>$paytelecoms,'bksid'=>$this->extDatatoo);
                    $history =  json_encode($this->resultegtcode);
                    //无卡添加vnhistory
                    //接收请求
                    $vnhistorytablename = c('db_prefix').$this->dateformonth.'vnhistory';
                    $_server = $_SERVER;
                    $time = \publicfunction::gettimeforstring(time());
                    $client_ip = $_server['REMOTE_ADDR']; 
                    $client_posthttps =  '无卡客户端提交明细：'.'<br>'.$_server['HTTP_HOST'].$_server["PHP_SELF"].'?'.$_server["QUERY_STRING"].'<br>'.'来源ip：'.$client_ip;
                    $this->clientupdata = array('time'=>$time,'orderid'=>$this->maxid,'type'=>$this->telecomtype,'status'=>1,'log'=>$client_posthttps,'maxid'=>$this->maxid);
                    //返回数据
                    $returnstring = " 无卡返回:"."<br>".$history;
                    $this->returndata = array('time'=>$time,'orderid'=>$this->maxid,'type'=>$this->telecomtype,'status'=>2,'log'=>$returnstring,'maxid'=>$this->maxid);
                    $this->adddataforsql($vnhistorytablename,$this->clientupdata,false,$id);
                    $this->adddataforsql($vnhistorytablename,$this->returndata,true,$id);
                    //下发数据
                    echo json_encode($this->resultegtcode);
                    exit();                    
           }               
                                   
        }
        
        
       
    }
    
    /**
     * Summary of init_val     游戏分配 计费初始化变量
     * @param mixed $imsi 
     * @param mixed $imei 
     * @param mixed $code 
     * @param mixed $mobile 
     * @param mixed $extData 
     * @param mixed $iccid 
     * @param mixed $ip 
     * @param mixed $city 
     * @param mixed $os_info 
     * @param mixed $os_model 
     * @param mixed $net_info 
     * @param mixed $newuser
     * @param mixed $saleuser
     * @param mixed $operators  //运营商
     */
    public function cogameinit_val($imsi='',$imei='',$mobile='',$extData='',$iccid='',$ip='',$city='',$os_info='',$os_model='',$net_info='',$mark='',$coid='',$appid='',$propid='',$operatorsegt='')
    {
        $this->os_info = $os_info;
        $this->os_model = $os_model;
        $this->net_info= $net_info;
        $this->mark = $mark;
        $this->model = M();
        $this->codetype = 0;
        $this->imsi = $imsi;
        $this->imei = $imei;
        $this->mobile = $mobile;
        $this->coid=$coid;
        $this->egt=$operatorsegt; //运营商
        $this->appid=$appid;
        $this->propid=$propid;
        if($extData==null ||$extData==''){
            $this->extData = 'extData';
        }else{
            $this->extData=$extData;
        }
        $this->iccid = $iccid;
        $this->ip = $ip;
        $this->city = $city;
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth = $this->dateformonth.'2'.'_';         
        $this->message = "success";
        $this->initstatus=1;
        $this->iccid_city = '';
        $this->iccid_city_id = 0;
        if($iccid != ''){
            $this->checkcityforiccd($iccid);
        }
        if($this->isreques == false)      //如果当前为第一步(Api1)的请求则执行
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
            $this->extDatatoo =$this->extDatatoo;           //生成bksid
            $this->checkData(array($this->ip,$this->imsi,$this->imei));  //检测参数并添加手机号码到表
            $len = strlen($this->mobile);   //获取手机长度   
            
            if($len != 11 || $this->mobile == '')
            {
                $this->nomobile = true;
            }
            if($this->egt== '-1'){
                //第三方支付
                $this->datainfo=2;
                $whereco=array('coid'=>$this->coid);
                $telecomsco = M('colist')->field('tpaypool')->where($whereco)->select();
                $telecomsco=$telecomsco['0'];
                $this->tpaypool=$telecomsco['tpaypool'];
                $wherepool=array('id'=>$this->tpaypool);
                $telecomspool = M('telecompools')->field('tpaytelecoms,wchartelecoms,ucardtelecoms')->where($wherepool)->select();
                $telecomspool=$telecomspool['0'];
                $tpaytelecoms=explode('_',$telecomspool['tpaytelecoms']);
                $wchartelecoms=explode('_',$telecomspool['wchartelecoms']);
                $ucardtelecoms=explode('_',$telecomspool['ucardtelecoms']);
                unset($tpaytelecoms['0']);
                unset($wchartelecoms['0']);
                unset($ucardtelecoms['0']);
                $paytelecoms=array('ZFB'=>$tpaytelecoms,'WX'=>$wchartelecoms,'YL'=>$ucardtelecoms);
                $data['city']= $this->city  ? $this->city :'city';
                $data['ip']= $this->ip  ? $this->ip :'ip';
                $data['extdata']=$this->extData ? $this->extData:'extdata';
                $data['os_info'] = $this->os_info ? $this->os_info:'os_info';
                $data['iccid'] = $this->iccid ? $this->iccid:'iccid';
                $data['os_model'] =$this->os_model ? $this->os_model:'os_model';
                $data['net_info'] = $this->net_info ? $this->net_info:'net_info';
                $data['mark'] =$this->mark ? $this->mark:'mark';
                $data['model'] =$this->model ? $this->model:'model';
                $data['codetype'] =$this->codetype ? $this->codetype:'codetype';
                $data['imsi'] =$this->imsi ? $this->imsi:'imsi';
                $data['imei'] =$this->imei ? $this->imei:'imei';
                $data['mobile'] =$this->mobile ? $this->mobile:'mobile';
                $data['coid'] =$this->coid ? $this->coid:'coid';
                $data['egt'] =$this->egt ? $this->egt:'egt'; //运营商
                $data['cpgame'] =$this->appid ? $this->appid:'appid';
                $data['propid'] =$this->propid ? $this->propid:'propid';
                $data['bksid'] =  $this->extDatatoo;
                $a = M('usercount')->add($data);
                //无卡走vnorder
                $extern2 = $data['bksid'];
                $paycode = $this->paycode ?  $this->paycode:0;
                $errorcode = $this->resulet2 ? $this->resulet2:'1001';
                $time = \PublicFunction::getTimeForString(time());
                $data = array('id'=>$this->maxid,'type'=>$this->telecomtype,'paycode'=>$paycode,'telecom'=>$this->telecomname,
                    'co'=>$this->coid,'cpgame'=>$this->appid,'egt'=>$this->egt,'prop'=>$this->propid,
                'extern'=>$this->extData,'city'=>$this->city,'xystatus'=>2,'status'=>1,'orderstatus'=>3,'time'=>$time,'iccid_city'=>$this->iccid_city,'imsi'=>$this->imsi,'imei'=>$this->imei,'extern2'=>$extern2,'bksid'=>$this->extDatatoo,'errorcode'=>$errorcode,'orderid'=>$orderid,'maxid'=>$this->maxid);
                $tablename = $this->dateformonth.'vnorder';
                $vnorder = M($tablename)->add($data);
                $b =  M($tablename)->_Sql();
                //生成下发数据
                $this->resultegtcode = array('code'=>'egt','message'=>$this->message,'maxid'=>$id,
                    'mobile'=>$this->mobile,'apistatus'=>$this->datainfo,'contet'=>$paytelecoms,'bksid'=>$this->extDatatoo);
                $history =  json_encode($this->resultegtcode);
                //无卡添加vnhistory
                //接收请求
                $vnhistorytablename = c('db_prefix').$this->dateformonth.'vnhistory';
                $_server = $_SERVER;
                $time = \publicfunction::gettimeforstring(time());
                $client_ip = $_server['REMOTE_ADDR']; 
                $client_posthttps =  '无卡客户端提交明细：'.'<br>'.$_server['HTTP_HOST'].$_server["PHP_SELF"].'?'.$_server["QUERY_STRING"].'<br>'.'来源ip：'.$client_ip;
                $this->clientupdata = array('time'=>$time,'orderid'=>$this->maxid,'type'=>$this->telecomtype,'status'=>1,'log'=>$client_posthttps,'maxid'=>$this->maxid);
                //返回数据
                $returnstring = " 无卡返回:"."<br>".$history;
                $this->returndata = array('time'=>$time,'orderid'=>$this->maxid,'type'=>$this->telecomtype,'status'=>2,'log'=>$returnstring,'maxid'=>$this->maxid);
                $this->adddataforsql($vnhistorytablename,$this->clientupdata,false,$id);
                $this->adddataforsql($vnhistorytablename,$this->returndata,true,$id);
                //下发数据
                echo json_encode($this->resultegtcode);
                exit();                    
            }               
            
        }
        
        
        
    }
    
    //初始化
    public function initsdk($imsi='',$imei='',$mobile='',$extData='',$iccid='',$ip='',$city='',$os_info2='',$os_model='',$net_info='',$mark='',$coidid='',$appid='',$operatorsegt=''){
        
        
        $this->os_info = $os_info2;
        $this->os_model = $os_model;
        $this->net_info= $net_info;
        $this->mark = $mark;
        $this->model = M();
        $this->codetype = 0;
        $this->imsi = $imsi;
        $this->imei = $imei;
        $this->mobile =$mobile;
        $this->coid=$coidid;
        $this->egt=$operatorsegt; //运营商
        $this->appid=$appid;
        $this->extData = $extData;
        $this->iccid = $iccid;
        $this->ip = $ip;
        $this->city = $city;
        $time = time();
        $time1 = date("Y-m-d",$time).' 00:00:00'; 
        $time2 = date("Y-m-d",$time).' 23:59:59'; 
        $time3 = date("Y-m-d-H",$time);
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth = $this->dateformonth.'2'.'_';  
        $daytime = explode('-',$time2);
        $daytime22 = explode('-',$time3);
        $day = $daytime22[2];     //天
        $hour = $daytime22[3];        //小时
        if($iccid != ''){
            $this->checkcityforiccd($iccid);
        }
        $tablenametoo = C('DB_PREFIX').$this->dateformonth.'extdata';
     
        $data0 = array('coorderid'=>$this->extData);
        $id2 = $this->model->table($tablenametoo)->add($data0);
        $a = $this->model->table($tablenametoo)->_sql();
        $this->maxid = $id2;
        $selectwhere =array('id'=>$id2);   
        $tablename2=$this->dateformonth.'extdata';
        $this->initstatus=0;
        $this->coinfo = $this->selectwhereformysql($tablename2,'bksid',$selectwhere);
        $this->coinfo = $this->coinfo['0'];
        $this->extDatatoo=$this->coinfo['bksid'];
        $this->extDatatoo =$this->extDatatoo;       //生成自定义参数
        $modeltable = M('usercount');
        $daytime = array(array('egt',$time1),array('elt',$time2));
        $selectwhere2 = array('imei'=>$this->imei,'time'=>$daytime,'appid'=>$this->appid,'coid'=>$this->coid,'initstatus'=>0);
        $selectwhere3 = array('imei'=>$this->imei,'appid'=>$this->appid,'coid'=>$this->coid,'initstatus'=>0);
        $phoneinfo3 = $modeltable->field('initdayuser,initnewuser')->where($selectwhere3)->select();
        $phoneinfo2 = $modeltable->field('initdayuser,initnewuser')->where($selectwhere2)->select();
       // print_r( $a=M()->_sql());
        if($phoneinfo2==null)
        {
               $initdayuser=1;
        }else{
            $initdayuser=0;
        }
        if($phoneinfo3==null)
        {
            $initnewuser=1;
        }else{
            $initnewuser=0;
        }

  
        $data1 = array('initnewuser'=>$initnewuser,'initdayuser'=>$initdayuser,'initstatus'=>0,'mobile'=>$this->mobile,'coid'=>$this->coid,'appid'=>$this->appid,'egt'=>$this->egt,'propid'=>$this->propid,'bksid'=>$this->extDatatoo,'extdata'=>$this->extData,'iccid'=>$this->iccid,'city'=>$this->city,'os_info'=>$this->os_info,'os_model'=>$this->os_model,'net_info'=>$this->net_info,'mark'=>$this->mark,'ip'=>$this->ip,'imsi'=>$this->imsi,'imei'=>$this->imei,'newuser'=>0,'saleuser'=>0,'daynewuser'=>0,'daysaleuser'=>0,'city'=>$this->iccid_city);
        $modeltable->add($data1); //usercount表

        $modeladv=M('adlist2');
        $wheredata=array('coid'=>$this->coid);
        $advname = $modeladv->where($wheredata)->select();
        if($advname==''){
           echo "厂商不存在";
		   exit;
        }
        $advdata = array();
        //判断厂商下的游戏,选择对应的信息
        foreach($advname as $k=>$v1){
            //截取appid
            $gamelist = explode('_',$v1['appid']);
            unset($gamelist[0]);
            //判断传入appid,相等,将查询出的厂商$v1赋值$advdata
            foreach($gamelist as $v2){
                if($v2 == $appid){
                    $advdata[] = $v1;
                }
            }         
        } 
        if(!$advdata){
           echo "厂商游戏广告不存在";
		   exit;
        }
        $wheredata['id']=$advdata['0']['id'];
        $numarr=$advdata['0']['advnum'] +1;
        $dataadv=array('advnum'=>$numarr,'bksid'=>$this->extDatatoo);        
        $modeladv->where($wheredata)->save($dataadv);
    
       // $id = 0;
       // $tablename2 = C('DB_PREFIX').$this->dateformonth.'order';
       // $this->tableordername = array('time'=>$time,'co'=>$this->coid,'cpgame'=>$this->appid,'prop'=>$this->propid,'telecom'=>0,'paycode'=>0,'mobile'=>0,'orderstatus'=>0,
       //'status'=>0,'value'=>0,'Iskl'=>0,'extern2'=>$this->extDatatoo,'bksid'=>$this->extDatatoo,'extern'=>$this->extData);
       // $this->adddataforsql($tablename2,$this->tableordername,false,$id);
       
        $this->model->startTrans();
        $data3 = array('id'=>$this->maxid,'time'=>$time,'day'=>(int)$day,'hour'=>(int)$hour,'egt'=>(int)$this->egt,'telecomname'=>1,
            'co'=>(int)$this->coid,'cpgame'=>(int)$this->appid,'prop'=>(int)$this->propid,'paycode'=>0,
            'msggold'=>0,'payresult'=>0,'xysuccess'=>0,'status'=>0,'paysuccess'=>0,'badvalue'=>0,'badgold'=>0,'extern2'=>$this->extDatatoo,'bksid'=>$this->extDatatoo,'city'=>$this->iccid_city);
        $tablename = C('DB_PREFIX').$this->dateformonth.'ordercount';       //订单明细表
        $this->adddataforsql($tablename,$data3,false,$id);
     //  print_r(M()->_sql());
      
        $data2 = array('id'=>$this->maxid,'type'=>'0','paycode'=>'0','telecom'=>'0',
            'co'=>(int)$this->coid,'cpgame'=>(int)$this->appid,'egt'=>$this->egt,'prop'=>(int)$this->propid,
        'extern'=>$this->extData,'city'=>$this->city,'xystatus'=>'1','status'=>0,'orderstatus'=>'3','time'=>$time,'iccid_city'=>$this->iccid_city,'imsi'=>$this->imsi,'imei'=>$this->imei,'extern2'=>$this->extDatatoo,'bksid'=>$this->extDatatoo,'errorcode'=>'init');
        $tablename = C('DB_PREFIX').$this->dateformonth.'vnorder';
        $this->adddataforsql($tablename,$data2,true,$id);
        return;
    
    }
    
    //天虎支付宝
    public function WxApiCode($coidarr,$appid='',$propid=''){
        $this->coid=$coidarr;
        $this->appid=$appid;
        $this->propid=$propid;
        $selectwhereco = array('coid'=>$this->coid); //code对应渠道模块伪代码
        $cotablename =  'colist'; //代码分配表
        $telecomco = $this->selectwhereformysql($cotablename,'appid,coid,telecompool',$selectwhereco);
        $this->telecomco=$telecomco['0'];
        $this->telecompool = $this->telecomco['telecompool']; 
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
        $paypic = array('pic_return'=>'http://bks.abksen.com/pic/sdkpay/pic_return.png','settings'=>'http://bks.abksen.com/pic/sdkpay/settings.png','zhifubao'=>'http://bks.abksen.com/pic/sdkpay/zhifubao.png','weixin2'=>'http://bks.abksen.com/pic/sdkpay/weixin2.png','yilianpay'=>'http://bks.abksen.com/pic/sdkpay/yilianpay.jpg');
        $this->resultinfo= array('fee'=>$this->fee,'skuid'=>$this->gameno['0'],'mshopld'=>$this->gameno['1'],'info'=>$this->gamename.$this->propname,'paypic'=>$paypic);

        echo json_encode($this->resultinfo);
        return;
        
    }
    
    //$order_no:交易号  $order_no:  SDK 传$orderid 来检索支付宝的订单 然后存入对应的值这个值$trade_no用来匹配回调
    public function Trade_No($orderid,$trade_no)
    {
        $this->orderid=$orderid;//123
        $this->order_no=$trade_no;
        $selectwhere = array('orderid'=>$this->orderid); 
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';   
        $this->dateformonth = $this->dateformonth.'2'.'_';
        $tablename =$this->dateformonth.'vnorder';
        $this->orderidall = $this->selectwhereformysql($tablename,'orderid',$selectwhere);
        $this->orderidall=$this->orderidall['0'];
        $order=$this->orderidall['orderid'];
        $updatedata['orderno']=$trade_no;
        $selectwhere2 = array('orderid'=>$order); 
        $M=M($tablename);
        $data=$M->where($selectwhere2)->save($updatedata);
        $selectwhere3 = array('apiorderid'=>$this->orderid); 
        $tablename2 =$this->dateformonth.'ordercount';
        $this->orderidallcount = $this->selectwhereformysql($tablename2,'apiorderid',$selectwhere3);
        $this->orderidallcount=$this->orderidallcount['0'];
        $apiorderid=$this->orderidallcount['apiorderid'];
        $selectwhere4 = array('apiorderid'=>$apiorderid); 
        $M2=M($tablename2);
        $data2=$M2->where($selectwhere4)->save($updatedata);
          //    print_r(M()->_sql());
        if($data){
            echo 'OK';
            
        }else{
            echo 'err';
        }
        // print_r($b=M()->_sql());
        return;
        
    }
    
    
    //广告
    /**
     * Summary of init_val      计费初始化变量
     * @param mixed $imsi 
     * @param mixed $imei 
     * @param mixed $mobile 
     * @param mixed $extData  透传参数
     * @param mixed $iccid  
     * @param mixed $ip 
     * @param mixed $province   省份
     * @param mixed $os_info    手机系统
     * @param mixed $os_model 手机型号
     * @param mixed $net_info 手机网络
     * @param mixed $mark
     * @param mixed $coid   
     * @param mixed $appid
     * @param mixed $operators  //运营商
     */
    public function check_adv($imsi,$imei,$mobile,$iccid,$ip,$province,$os_info,$os_model,$net_info,$mark,$coid,$appid,$operators)
     {  
         //广告用户统计 bks_admobilelist表
        $net_info = strtolower($net_info); //变成小写
        $mlist = M('admobilelist')->where('imei='."'".$imei."'")->find();
        if(!$mlist){
            if($imei != '' && $imsi != '' && $mark != '' && $os_model != '' && $os_info != '' &&$net_info != '' && $province != '' ){
                $admobilelist['time'] = time(); //时间
                $admobilelist['imei'] = $imei;  //imei
                $admobilelist['imsi'] = $imsi;  //imsi
                $admobilelist['mobilepp'] = $mark; //品牌
                $admobilelist['mobliexh'] = $os_model; //手机型号
                $admobilelist['mobliesys'] = $os_info; //手机系统
                $admobilelist['make'] = $net_info;  //手机网络
                $admobilelist['city'] = $province;  //省份
                $admobilelist['usernum'] += 1; //用户 默认1
                $mid = M('admobilelist')->add($admobilelist);
                if(!$mid){
                    exit('数据错误');
                }
            }
         }
       
        $this->coid=$coid;
        $this->appid=$appid;
        //检测iccid
        if($iccid != ''){
            $this->checkcityforiccd($iccid);
        }
        //检测广告存在否
        $this->adv_params(array($this->coid,$this->appid));//检测参数
        $model=M('adlist2');
        $wheredata=array('coid'=>$this->coid);
        $advname = $model->where($wheredata)->select();
        if($advname==''){
           echo "厂商ID不存在";
		   exit;
        }
        $advdata = array();
        //判断厂商下的游戏,选择对应的信息
        foreach($advname as $k=>$v1){
            //截取appid
            $gamelist = explode('_',$v1['appid']);
            unset($gamelist[0]);
            //判断传入appid,相等,将查询出的厂商$v1赋值$advdata
            foreach($gamelist as $v2){
                if($v2 == $appid){
                    $advdata[] = $v1;
                }
            }         
        }
        if(empty($advdata)){
            echo  "厂商对应的APPID不存在";
			exit;
        }
        
        //广告限制
        $adchange = $advdata['0'];
        //$province,$os_info,$os_model,$net_info,$mark,$coid,$appid
        //查手机系统限制是否存在
        $osinfo = M('androidverion')->field('id,androidversion')->where('androidversion='."'".$os_info."'")->select();
        //查手机型号限制是否存在
        $osmodel = M('moblieversion')->field('id,version')->where('version='."'".$os_model."'")->select();
        //查联网限制是否存在
        $netinfo = \PublicData::$net_info;
        foreach($netinfo as $k=>$v){
            if($v['name'] == $net_info){
            $wang = $netinfo[$k]['id'];
            }
        }
        //查城市限制是否存在
        $city = M('area')->field('zoneid,name,parent_id')->where("name='".$province."'")->select();
        //系统限制存在,则系统限制逻辑
        if($osinfo){
                //循环判断限制了那个系统
              foreach($osinfo as $k=>$v){
                  if($v['androidversion']==$os_info){
                    $os_info = $v['id'];
                }
             }
            //开屏 循环判断当前系统参数是否等于限制系统,等于赋值1
              $kpggsys =  explode('_',$adchange['kpggsys']);
              unset($kpggsys[0]);
              foreach($kpggsys as $v){
                  if($v == $os_info ){
                      $ad1['kpstatus'] = 1;           
                  }
              }
            //返回 循环判断当前系统参数是否等于限制系统,等于赋值1
              $fhggsys =  explode('_',$adchange['fhggsys']);
              unset($fhggsys[0]);
              foreach($fhggsys as $v){
                  if($v == $os_info){
                      $ad1['fhadstatus'] = 1;           
                  }
              }
            //插屏 循环判断当前系统参数是否等于限制系统,等于赋值1
              $cpggsys =  explode('_',$adchange['cpggsys']);
              unset($cpggsys[0]);
              foreach($cpggsys as $v){
                  if($v == $os_info){
                      $ad1['cpadstatus'] = 1;          
                  }
              }
            //安装下载 循环判断当前系统参数是否等于限制系统,等于赋值1
              $installsys =  explode('_',$adchange['installsys']);
              unset($installsys[0]);
              foreach($installsys as $v){
                  if($v == $os_info){
                      $ad1['installadstatus'] = 1;           
                  }
              }
            //通知栏安装下载 循环判断当前系统参数是否等于限制系统,等于赋值1
              $telinstallsys =  explode('_',$adchange['telinstallsys']);
              unset($telinstallsys[0]);
              foreach($telinstallsys as $v){
                  if($v == $os_info){
                      $tel1[1] = 1;           
                  }
              }
            //推荐更新 循环判断当前系统参数是否等于限制系统,等于赋值1
              $telupdatesys =  explode('_',$adchange['telupdatesys']);
              unset($telupdatesys[0]);
              foreach($telupdatesys as $v){
                  if($v == $os_info){
                       $tel1[2] = 1;            
                  }
              }
            //推荐使用 循环判断当前系统参数是否等于限制系统,等于赋值1
              $telusesys =  explode('_',$adchange['telusesys']);
              unset($telusesys[0]);
              foreach($telusesys as $v){
                  if($v == $os_info){
                       $tel1[3] = 1;        
                  }
              }
        }
        //手机型号 循环判断当前手机参数是否等于限制手机,等于赋值1
        if($osmodel){
            foreach($osmodel as $k=>$v){
                if($v['version']==$os_model){
                    $os_model = $v['id'];
                }
            }
            //开屏 循环判断当前手机参数是否等于限制手机,等于赋值1
            $kpggphone =  explode('_',$adchange['kpggphone']);
            unset($kpggphone[0]);
            foreach($kpggphone as $v){
                if($v == $os_model){
                    $ad2['kpstatus'] = 1;           
                }
            }
            //返回 循环判断当前手机参数是否等于限制手机,等于赋值1
            $fhggphone =  explode('_',$adchange['fhggphone']);
            unset($fhggphone[0]);
            foreach($fhggphone as $v){
                if($v == $os_model){
                    $ad2['fhadstatus'] = 1;           
                }
            }
            //插屏 循环判断当前手机参数是否等于限制手机,等于赋值1
            $cpggphone =  explode('_',$adchange['cpggphone']);
            unset($cpggphone[0]);
            foreach($cpggphone as $v){
                if($v == $os_model){
                    $ad2['cpadstatus'] = 1;          
                }
            }
            //安装下载 循环判断当前手机参数是否等于限制手机,等于赋值1
            $installphone =  explode('_',$adchange['installphone']);
            unset($installphone[0]);
            foreach($installphone as $v){
                if($v == $os_model){
                    $ad2['installadstatus'] = 1;           
                }
            }
            //通知栏安装下载 循环判断当前手机参数是否等于限制手机,等于赋值1
            $telinstallphone =  explode('_',$adchange['telinstallphone']);
            unset($telinstallphone[0]);
            foreach($telinstallphone as $v){
                if($v == $os_model){
                    $tel2[1] = 1;             
                }
            }
            //推荐更新 循环判断当前手机参数是否等于限制手机,等于赋值1
            $telupdatephone =  explode('_',$adchange['telupdatephone']);
            unset($telupdatephone[0]);
            foreach($telupdatephone as $v){
                if($v == $os_model){
                     $tel2[2] = 1;           
                }
            }
            //推荐使用 循环判断当前手机参数是否等于限制手机,等于赋值1
            $telusephone =  explode('_',$adchange['telusephone']);
            unset($telusephone[0]);
            foreach($telusephone as $v){
                if($v == $os_model){
                     $tel2[3] = 1;               
                }
            }
        }
        
        //联网方式  循环判断当前联网参数是否等于限制联网方式,等于赋值1    
        if($wang){
           $net_info = $wang;
            //开屏 循环判断当前联网参数是否等于限制联网方式,等于赋值1    
           $kpggphone =  explode('_',$adchange['kpggweb']);
            unset($kpggphone[0]);
            foreach($kpggphone as $v){
                if($v == $net_info){
                    $ad3['kpstatus'] = 1;           
                }
            }
            //返回 循环判断当前联网参数是否等于限制联网方式,等于赋值1    
            $fhggsys =  explode('_',$adchange['fhggweb']);
            unset($fhggsys[0]);
            foreach($fhggsys as $v){
                if($v == $net_info){
                    $ad3['fhadstatus'] = 1;           
                }
            }
            //插屏 循环判断当前联网参数是否等于限制联网方式,等于赋值1    
            $cpggsys =  explode('_',$adchange['cpggweb']);
            unset($cpggsys[0]);
            foreach($cpggsys as $v){
                if($v == $net_info){
                    $ad3['cpadstatus'] = 1;          
                }
            }
            //安装下载  循环判断当前联网参数是否等于限制联网方式,等于赋值1    
            $installsys =  explode('_',$adchange['installweb']);
            unset($installsys[0]);
            foreach($installsys as $v){
                if($v == $net_info){
                    $ad3['installadstatus'] = 1;           
                }
            }
            //通知栏安装下载 循环判断当前联网参数是否等于限制联网方式,等于赋值1 
            $telinstallsys =  explode('_',$adchange['telinstallweb']);
            unset($telinstallsys[0]);
            foreach($telinstallsys as $v){
                if($v == $net_info){
                    $tel3[1] = 1;           
                }
            }
            //推荐更新  循环判断当前联网参数是否等于限制联网方式,等于赋值1 
            $telupdatesys =  explode('_',$adchange['telupdateweb']);
            unset($telupdatesys[0]);
            foreach($telupdatesys as $v){
                if($v == $net_info){
                     $tel3[2] = 1;            
                }
            }
            //推荐使用  循环判断当前联网参数是否等于限制联网方式,等于赋值1 
            $telusesys =  explode('_',$adchange['teluseweb']);
            unset($telusesys[0]);
            foreach($telusesys as $v){
                if($v == $net_info){
                     $tel3[3] = 1;        
                }
            }
        }
        
        //省市    循环判断当前省市参数是否等于限制省市,等于赋值1
        if($city){
            foreach($city as $k=>$v){
                if($v['name']==$province){
                    $city1 = $v['name'];
                }
            }
            //开屏  循环判断当前省市参数是否等于限制省市,等于赋值1
            $kpggcity = '_'.$adchange['kpggcity'];
            $kpggcity =  explode('_',$kpggcity);
            unset($kpggcity[0]);
            foreach($kpggcity as $v){
                //判断传过来的是市还是省
                $kpcit = M('area')->where("name='".$v."'")->select();
                //市
                if($kpcit['0']['parent_id']==0){
                $kpc = M('area')->where('parent_id='.$kpcit['0']['zoneid'])->select();
                foreach($kpc as $k1=>$v1){
                    if($v1['name']==$city1){
                    $ad4['kpstatus'] = 1;   
                        }
                    }
                }else{
                    //省
                    if($v == $city1){
                    $ad4['kpstatus'] = 1;           
                }
                }
            }
            //返回 循环判断当前省市参数是否等于限制省市,等于赋值1
            $fhggcity = '_'.$adchange['fhggcity'];
            $fhggcity =  explode('_',$fhggcity);
            unset($fhggcity[0]);
            foreach($fhggcity as $v){
                //判断传过来的是市还是省
                $kpcit = M('area')->where("name='".$v."'")->select();
                //市
                if($kpcit['0']['parent_id']==0){
                    $kpc = M('area')->where('parent_id='.$kpcit['0']['zoneid'])->select();
                    foreach($kpc as $k1=>$v1){
                        if($v1['name']==$city1){
                            $ad4['fhadstatus'] = 1;   
                        }
                    }
                }else{
                    //省
                    if($v == $city1){
                        $ad4['fhadstatus'] = 1;           
                    }
                }
                
            }
            //插屏 循环判断当前省市参数是否等于限制省市,等于赋值1
            $cpggcity = '_'.$adchange['cpggcity'];
            $cpggcity =  explode('_',$cpggcity);
            unset($cpggcity[0]);
            foreach($cpggcity as $v){
                //判断传过来的是市还是省
                $kpcit = M('area')->where("name='".$v."'")->select();
                //市
                if($kpcit['0']['parent_id']==0){
                    $kpc = M('area')->where('parent_id='.$kpcit['0']['zoneid'])->select();
                    foreach($kpc as $k1=>$v1){
                        if($v1['name']==$city1){
                            $ad4['cpadstatus'] = 1;   
                        }
                    }
                }else{
                    //省
                    if($v == $city1){
                        $ad4['cpadstatus'] = 1;           
                    }
                }
                
            }
            //安装下载 循环判断当前省市参数是否等于限制省市,等于赋值1
            $installcity = '_'.$adchange['installcity'];
            $installcity =  explode('_',$installcity);
            unset($installcity[0]);
            foreach($installcity as $v){
                $kpcit = M('area')->where("name='".$v."'")->select();
                //市
                if($kpcit['0']['parent_id']==0){
                    $kpc = M('area')->where('parent_id='.$kpcit['0']['zoneid'])->select();
                    foreach($kpc as $k1=>$v1){
                        if($v1['name']==$city1){
                            $ad4['installadstatus'] = 1;   
                        }
                    }
                }else{
                    //省
                    if($v == $city1){
                        $ad4['installadstatus'] = 1;           
                    }
                }
            }
            //通知栏安装下载 循环判断当前省市参数是否等于限制省市,等于赋值1
            $telinstallcity = '_'.$adchange['telinstallcity'];
            $telinstallcity =  explode('_',$telinstallcity);
            unset($telinstallcity[0]);
            foreach($telinstallcity as $v){
                
                $kpcit = M('area')->where("name='".$v."'")->select();
                //市
                if($kpcit['0']['parent_id']==0){
                    $kpc = M('area')->where('parent_id='.$kpcit['0']['zoneid'])->select();
                    foreach($kpc as $k1=>$v1){
                        if($v1['name']==$city1){
                            $tel4[1] = 1;       
                        }
                    }
                }else{
                    //省
                    if($v == $city1){
                        $tel4[1] = 1;           
                    }
                }
            }
            //推荐更新 循环判断当前省市参数是否等于限制省市,等于赋值1
            $telupdatecity = '_'.$adchange['telupdatecity'];
            $telupdatecity =  explode('_',$telupdatecity);
            unset($telupdatecity[0]);
            foreach($telupdatecity as $v){
                $kpcit = M('area')->where("name='".$v."'")->select();
                //市
                if($kpcit['0']['parent_id']==0){
                    $kpc = M('area')->where('parent_id='.$kpcit['0']['zoneid'])->select();
                    foreach($kpc as $k1=>$v1){
                        if($v1['name']==$city1){
                            $tel4[2] = 1;            
                        }
                    }
                }else{
                    //省
                    if($v == $city1){
                        $tel4[2] = 1;              
                    }
                }
            }
            //推荐使用 循环判断当前省市参数是否等于限制省市,等于赋值1
            $telusecity = '_'.$adchange['telusecity'];
            $telusecity =  explode('_',$telusecity);
            unset($telusecity[0]);
            foreach($telusecity as $v){
                $kpcit = M('area')->where("name='".$v."'")->select();
                //市
                if($kpcit['0']['parent_id']==0){
                    $kpc = M('area')->where('parent_id='.$kpcit['0']['zoneid'])->select();
                    foreach($kpc as $k1=>$v1){
                        if($v1['name']==$city1){
                            $tel4[3] = 1;                    
                        }
                    }
                }else{
                    //省
                    if($v == $city1){
                        $tel4[3] = 1;                         
                    }
                }
            }
            
        }
        
        //根据前面判断赋值 如果存在赋值,则对应广告的限制成立,赋值广告状态值
        //开屏
        if($ad1['kpstatus']==1 || $ad2['kpstatus']==1 || $ad3['kpstatus']==1 || $ad4['kpstatus']==1){
            $advdata[0]['kpstatus'] = 0;
        }
        //返回 
        if($ad1['fhadstatus']==1 || $ad2['fhadstatus']==1 || $ad3['fhadstatus']==1 || $ad4['fhadstatus']==1){
            $advdata[0]['fhadstatus'] = 0;
        }
        //插屏
        if($ad1['cpadstatus']==1 || $ad2['cpadstatus']==1 || $ad3['cpadstatus']==1 || $ad4['cpadstatus']==1){
            $advdata[0]['cpadstatus'] = 0;
        }
        //安装下载
        if($ad1['installadstatus']==1 || $ad2['installadstatus']==1 || $ad3['installadstatus']==1 || $ad4['installadstatus']==1){
            $advdata[0]['installadstatus'] = 0;
        }
        //通知栏 安装下载
        if( $tel1[1]==1 || $tel2[1]==1 || $tel3[1]==1 || $tel4[1]==1){
            $tel[1] = '0';
        }
        //通知栏 推荐更新
        if( $tel1[2]==1 || $tel2[2]==1 || $tel3[2]==1 || $tel4[2]==1){
            $tel[2] = '0';
        }
        //通知栏 推荐使用
        if( $tel1[3]==1 || $tel2[3]==1 || $tel3[3]==1 || $tel4[3]==1){
            $tel[3] = '0';
        }
        //广告通知栏限制状态值
        $typestatus = explode('_',$adchange['teltype']);
        unset($typestatus[0]);
        //判断上一步通知栏限制赋值是否存在,不存在等于默认广告状态
        if(!isset($tel[1])){
        $tel[1] = $typestatus[1];
        }
        //判断上一步通知栏限制赋值是否存在,不存在等于默认广告状态
        if(!isset($tel[2])){
            $tel[2] = $typestatus[2];
        }
        //判断上一步通知栏限制赋值是否存在,不存在等于默认广告状态
        if(!isset($tel[3])){
            $tel[3] = $typestatus[3];
        }
        // 判断广告限制是否存在
        if($adchange['kpggsys']!='' || $adchange['kpggphone']!=''|| $adchange['kpggweb']!='' || $adchange['kpggcity']!=''
           || $adchange['fhggsys']!='' || $adchange['fhggphone']!=''|| $adchange['fhggweb']!='' 
           || $adchange['fhggcity']!=''|| $adchange['cpggsys']!='' || $adchange['cpggphone']!=''
           ||$adchange['cpggweb']!='' || $adchange['cpggcity']!=''||$adchange['installsys']!='' 
           || $adchange['installphone']!=''|| $adchange['installweb']!='' || $adchange['installcity']!=''
           ||$adchange['telinstallsys']!='' || $adchange['telinstallphone']!='' || $adchange['telinstallweb']!=''
           || $adchange['telinstallcity']!='' || $adchange['telupdatesys']!=''|| $adchange['telupdatephone']!='' 
           || $adchange['telupdateweb']!=''|| $adchange['telupdatecity']!='' || $adchange['telusesys']!=''
           || $adchange['telusephone']!=''|| $adchange['teluseweb']!='' || $adchange['telusecity']!=''
           ){
            //存在广告限制,而传入的参数为空,视为限制,全部都限制
            if($province=='' || $os_info=='' || $os_model=='' || $net_info=='' || $mark==''){
                $advdata[0]['kpstatus']=0;
                $advdata[0]['fhadstatus']=0;
                $advdata[0]['cpadstatus']=0;
                $advdata[0]['installadstatus']=0;
                $tel[1]='0';
                $tel[2]='0';
                $tel[3]='0';
            }
        }
        //拼接通知栏广告限制状态(状态已在上一步获取到),数据库里存的是_拼接的
        foreach($tel as $v){
            $te = '_'.$tel[1].'_'.$tel[2].'_'.$tel[3];
        }
        if($te){
            $advdata[0]['teltype'] = $te;
        }
        //广告限制完成
        //以下生成avorder记录 用户统计?
        $data['co'] = $this->coid;
        $data['cpgame'] = $this->appid;
        $data['imei'] = $this->imei;
        $data['imsi'] = $this->imsi;
        $data['iccid_city'] = $this->iccid_city;
		if($data['iccid_city']==''){
            $data['iccid_city']='其它';
        }
        $data['bksid'] = $this->extDatatoo;
        $data['time'] = time();
        $adorder =$this->dateformonth.'adorder';
        $arr = array(); //$arr广告下发数据sdk
        foreach($advdata as $key => $value){
            $arr['fullscreen']['status'] = $value['kpstatus'];
            //当状态开启,adorder进行统计
            if($value['kpstatus']==1){
                $kpdata = $data;
                $kpdata['adtype'] = 1; //广告类型
                $kpdata['adsnum'] = 1;//广告用户  用户统计时用sum count 数据库内kpnum  adnum等已经没用
                $kpid =  M($adorder)->add($kpdata);
                if(!$kpid){
                    exit('联系写代码的1');
                }
            }
            $arr['fullscreen']['time'] = $value['kptime'];//开屏广告时间
            //当广告图片数量不存在的时候,赋值0,不然sdk异常
            if($value['kpnumber']==''){
                $value['kpnumber'] = 0;
            }
            $arr['fullscreen']['picnumber'] = $value['kpnumber'];//开屏图片广告数量
            //获取开屏广告url 数据库_拼接
            $kurl = explode('_',$value['kpurl']);
            unset($kurl[0]);
            foreach($kurl as $k=>$v){
                $kplist = M('adpicture')->field('id,url,curl')->find($v);
                $kpd['id'.$k] = $kplist['id'];
                $kpurl['url'.$k] = $kplist['url'];
                $kpcurl['curl'.$k] = $kplist['curl'];
            }
            //获取通知栏状态 数据库_拼接
            $typestatus = explode('_',$value['teltype']);
            unset($typestatus[0]);
			if($value['telstatus']==0){
                $typestatus[1] = 0;
                $typestatus[2] = 0;
                $typestatus[3] = 0;
            }
            //获取通知栏inprocessstatus状态
            $telinprocessstatus = explode('_',$value['telinprocessstatus']);
            unset($telinprocessstatus[0]);
            //下发开屏数据
            $arr['fullscreen']['id'] = $kpd;
            $arr['fullscreen']['url'] = $kpurl;
            $arr['fullscreen']['curl'] = $kpcurl;
            $arr['fullscreen']['kpmr']['status'] = $value['kpmrstatus'];
            $kplog = M('adpicture')->field('url,curl')->find($value['kplog']);
            $kploge = M('adpicture')->field('url,curl')->find($value['kploge']);
            $arr['fullscreen']['kpmr']['heng'] = $kplog['url']; //横屏url
            $arr['fullscreen']['kpmr']['shu'] = $kploge['url'];  //竖屏url
            $arr['fullscreen']['kpmr']['time'] = $value['kpmrtime'];//时间
            $arr['fullscreen']['date'] = $value['date'];
            //下发返回广告数据
            $arr['suspend']['status'] = $value['fhadstatus'];
            $arr['suspend']['id'] = $value['fhadurl'];
            //返回广告状态开启,adorder进行统计
            if($value['fhadstatus']==1){
                $fhdata = $data;
                $fhdata['adtype'] = 2;//类型
                $fhdata['adsnum'] = 1;//用户
                $fhid =  M($adorder)->add($fhdata);
                if(!$fhid){
                    exit('联系写代码的2');
                }
            }
            $fhadurl = M('adpicture')->field('url,curl')->find($value['fhadurl']);
            $arr['suspend']['url'] =  $fhadurl['url'];
            $arr['suspend']['curl'] =  $fhadurl['curl'];
            //下发插屏数据
            $arr['plaque']['status'] = $value['cpadstatus'];
            $arr['plaque']['id'] = $value['cpadurl'];
            //插屏当状态开启,adorder进行统计
            if($value['cpadstatus']==1){
                $cpdata = $data;
                $cpdata['adtype'] = 3;//状态
                $cpdata['adsnum'] = 1;//用户
                $cpid =  M($adorder)->add($cpdata);
                if(!$cpid){
                    exit('联系写代码的3');
                }
            }
            $arr['plaque']['time'] = $value['cpadtime'];//时间
            $arr['plaque']['showtime'] = $value['cpadshowtime'];//显示时间
            $arr['plaque']['position'] = $value['cpadag'];
            $cpadurl = M('adpicture')->field('url,curl')->find($value['cpadurl']);
            $arr['plaque']['url'] = $cpadurl['url'];
            $arr['plaque']['curl'] = $cpadurl['curl'];
            //下发安装下载数据
            $arr['install']['status'] = $value['installadstatus'];
            //当状态开启,adorder进行统计
            if($value['installadstatus']==1){
                $installdata = $data;
                $installdata['adtype'] = 4;//类型
                $installdata['adsnum'] = 1;//用户
                $installid =  M($adorder)->add($installdata);
                if(!$installid){
                    exit('联系写代码的4');
                }
            }
            $arr['install']['inprocess'] = $value['installinprocess'];
            $arr['install']['showtime'] = $value['installshowtime'];
            $installadurl = M('adpicture')->field('url,curl')->find($value['installadurl']);
            $arr['install']['id'] = $value['installadurl'];
            $arr['install']['url'] = $installadurl['url'];
            $arr['install']['curl'] = $installadurl['curl'];
            //下发通知栏数据
            $arr['notice']['status'] = $value['telstatus'];
            $telapkurl = M('adpicture')->field('url')->find($value['telapkurl']);
            //通知栏安装下载数据
            $arr['notice']['apk']['id'] = $value['telapkurl'];
            $arr['notice']['apk']['apkurl'] = $telapkurl['url'];
            $telapkpicurl = M('adpicture')->field('url,curl')->find($value['telpicurl']);
            $arr['notice']['apk']['status'] = $typestatus[1];
            //当状态开启,adorder进行统计
            if( $typestatus[1]==1){
                $typinstalldata = $data;
                $typinstalldata['adtype'] = 5;//类型
                $typinstalldata['adsnum'] = 1;//用户
                $typinstallid =  M($adorder)->add($typinstalldata);
                if(!$typinstallid){
                    exit('联系写代码的5');
                }
            }
            $arr['notice']['apk']['picurl'] = $telapkpicurl['url'];
            $arr['notice']['apk']['curl'] = $telapkpicurl['curl'];
            $arr['notice']['apk']['time'] = $value['apkshowtime'];
            $arr['notice']['apk']['message'] = $value['apkmessage'];
            $arr['notice']['apk']['telinprocessstatus'] = $telinprocessstatus[1];
            $updateurl = M('adpicture')->field('url,curl')->find($value['updateurl']);
            //通知栏推荐更新数据
            $arr['notice']['update']['id'] = $value['updateurl'];
            $arr['notice']['update']['status'] = $typestatus[2];
            //当状态开启,adorder进行统计
            if( $typestatus[2]==1){
                $typupdatedata = $data;
                $typupdatedata['adtype'] = 6;//类型
                $typupdatedata['adsnum'] = 1;//用户
                $typupdatedataid =  M($adorder)->add($typupdatedata);
                if(!$typupdatedataid){
                    exit('联系写代码的6');
                }
            }
            $arr['notice']['update']['updateurl'] = $updateurl['url'];
            $arr['notice']['update']['curl'] = $updateurl['curl'];
            $updatepicurl = M('adpicture')->field('url')->find($value['updatepicurl']);
            $arr['notice']['update']['updatepicurl'] = $updatepicurl['url'];
            $arr['notice']['update']['time'] = $value['updateshowtime'];
            $arr['notice']['update']['message'] = $value['updatemessage'];
            $arr['notice']['update']['telinprocessstatus'] = $telinprocessstatus[2];
            //通知栏推荐使用数据
            $useurl = M('adpicture')->field('url,curl')->find($value['useurl']);
            $arr['notice']['use']['id'] = $value['useurl'];
            $arr['notice']['use']['useurl'] = $useurl['url'];
            $arr['notice']['use']['curl'] = $useurl['curl'];
            $usepicurl = M('adpicture')->field('url')->find($value['usepicurl']);
            $arr['notice']['use']['status'] = $typestatus[3];
            //当状态开启,adorder进行统计
            if( $typestatus[3]==1){
                $typeusedata = $data;
                $typeusedata['adtype'] = 7;
                $typeusedata['adsnum'] = 1;
                $typeusedataid =  M($adorder)->add($typeusedata);
                if(!$typeusedataid){
                    exit('联系写代码的7');
                }
            }
            $arr['notice']['use']['usepicurl'] = $usepicurl['url'];
            $arr['notice']['use']['time'] = $value['useshowtime'];
            $arr['notice']['use']['message'] = $value['usemessage'];
            $arr['notice']['use']['telinprocessstatus'] = $telinprocessstatus[3];
        }
        //sdk包版本,写死的
        $arr['re_confirm'] = '2';
        $arr['status'] = '2';
        $arr['qdversion'] = 'v1.1.6';
        //广告id
        $arr['extDatatoo'] = $value['id'];
        //广告自定义参数
        $arr['advextData']=$this->extDatatoo;
        //json 加密
        $adv =  json_encode($arr);
        //以上生成下发数据完成
        //以下 请求下发记录 vnhistory
        $vnhistorytablename = c('db_prefix').$this->dateformonth.'vnhistory';
        //接收请求
        $_server = $_SERVER;
        $time = \publicfunction::gettimeforstring(time());
        $client_ip = $_server['REMOTE_ADDR']; 
        $client_posthttps =  '客户端提交明细：'.'<br>'.$_server['HTTP_HOST'].$_server["PHP_SELF"].'?'.$_server["QUERY_STRING"].'<br>'.'来源ip：'.$client_ip;
        $this->clientupdata = array('time'=>$time,'orderid'=>$this->maxid,'type'=>$this->telecomtype,'status'=>1,'log'=>$client_posthttps,'adstatus'=>0);
        //返回数据
        $returnstring = " 广告返回:"."<br>".$adv;
        $this->returndata = array('time'=>$time,'orderid'=>$this->maxid,'type'=>$this->telecomtype,'status'=>2,'log'=>$returnstring,'adstatus'=>0);
        $this->adddataforsql($vnhistorytablename,$this->clientupdata,false,$id);
        $this->adddataforsql($vnhistorytablename,$this->returndata,true,$id);
       //下发数据
        echo $adv; 
        return;
    }
    
    public function addhistory()
    {   
        $_server = $_SERVER;
        $time = \publicfunction::gettimeforstring(time());
        $client_ip = $_server['remote_addr']; 
        $client_posthttps =  $isreques.'客户端提交明细：'.'<br>'.$_server['http_host'].$_server["php_self"].'?'.$_server["query_string"].'<br>'.'来源ip：'.$client_ip;
        $clientupstring = $client_posthttps;
        $this->clientupdata = array('time'=>$time,'orderid'=>$this->maxid,'type'=>$this->telecomtype,'status'=>4,'log'=>$clientupstring);
        $vnhistorytablename = c('db_prefix').$this->dateformonth.'vnhistory';
        $payupstring = $this->send_url_data_string;
        $payupstring = $this->codetelecomname.$isreques." 支付提交:".'提交服务器ip：'.\publicdata::$servename."<br>".$payupstring;
        $this->payupdata = array('time'=>$time,'orderid'=>$this->maxid,'type'=>$this->telecomtype,'status'=>1,'log'=>$payupstring);
       
        $logdowndata = $this->codetelecomname.$isreques." 登录下发:".'<br>'.$logdowndata;
        $this->logdowndata = array('time'=>$time,'orderid'=>$this->maxid,'type'=>$this->telecomtype,'status'=>3,'log'=>$logdowndata);
        $this->adddataforsql($vnhistorytablename,$this->clientupdata,false,$id);
        $this->adddataforsql($vnhistorytablename,$this->payupdata,false,$id);
        $this->adddataforsql($vnhistorytablename,$this->returndata,false,$id);
        $this->adddataforsql($vnhistorytablename,$this->logdowndata,true,$id);
    }
    /**
     * 指定位置插入字符串
     * @param $str  原字符串
     * @param $i    插入位置
     * @param $substr 插入字符串
     * @return string 处理后的字符串
     */
    function insertToStr($str='', $i='', $substr=''){
        //指定插入位置前的字符串
        $startstr="";
        for($j=0; $j<$i; $j++){
            $startstr .= $str[$j];
        }
        
        //指定插入位置后的字符串
        $laststr="";
        for ($j=$i; $j<strlen($str); $j++){
            $laststr .= $str[$j];
        }
        
        //将插入位置前，要插入的，插入位置后三个字符串拼接起来
        $str = $startstr . $substr . $laststr;
        
        //返回结果
        return $str;
    }
    /**
     * Summary of checkcityforiccid_For_Telecom  中国电信
     * @param mixed $iccid 
     */
    public function checkcityforiccid_For_Telecom(&$iccid = '')
    {
        $iccid = substr($iccid,10,3);
        $citylist = \PublicData::$city_telecom;
        foreach($citylist as $value)
        {
            if($value['areacode'] == $iccid)
            {
                $this->iccid_city = $value['city'];                
                $this->iccid_city_id = $value['id'];
            }
        }
    }
    /**
     * Summary of checkcityforiccid_For_Mobile 中国移动
     * @param mixed $iccid 
     */
    public function checkcityforiccid_For_Mobile($iccid ='')
    {
        $iccid = substr($iccid,8,2);
        $citylist = \PublicData::$city_mobile;
        foreach($citylist as $value)
        {
            if($value['areacode'] == $iccid)
            {
                $this->iccid_city = $value['city'];
                $this->iccid_city_id = $value['id'];
            }
        }
    }
    
    /**
     * Summary of checkcityforiccid_For_Unicom  中国联通
     * @param mixed $iccid 
     */
    public function checkcityforiccid_For_Unicom($iccid ='')
    {
        $iccid = substr($iccid,9,2);
        $citylist = \PublicData::$city_union;
        foreach($citylist as $value)
        {
            if($value['areacode'] == $iccid)
            {
                $this->iccid_city = $value['city'];
                $this->iccid_city_id = $value['id'];
            }
        }
    }
    /**
     * Summary of checkcityforiccd  根据iccid判断运营商和省市
     * @param mixed $iccid 
     */
    public function checkcityforiccd(&$iccid = '')
    {
        //     static $egtlist = array(1=>array('id'=>1,'name'=>'电信'),array('id'=>2,'name'=>'联通'),array('id'=>3,'name'=>'移动'));

        //   89860315947552648753
        $this->egtnum = substr($iccid,4,2);
        switch($this->egtnum)
        {
            case '03':          //中国电信
                {
                    $this->checkcityforiccid_For_Telecom($iccid);
                    $this->egtnum = 1;
                    break;
                }
            case '11':          //中国电信
                {
                    $this->checkcityforiccid_For_Telecom($iccid);
                    $this->egtnum = 1;
                    break;
                }
            case '01':          //中国联通
                {
                    $this->checkcityforiccid_For_Unicom($iccid);
                    $this->egtnum = 2;
                    break;
                }
            case '00':           //中国移动
                {
                    $this->checkcityforiccid_For_Mobile($iccid);
                    $this->egtnum = 3;
                    break;
                }
            case '02':      //中国移动
                {
                    $this->checkcityforiccid_For_Mobile($iccid);
                    $this->egtnum = 3;
                    break;
                }
        }
    }
    
    /**
     * Summary of requesterrforid   错误码返回区域
     * @param mixed $errid 
     * code	message	错误描述
     * -3   没有手机号
    *1001   计费代码状态关闭
    *1002	TelecomProvinceClose	通道关闭、省份屏蔽
    *1003	MissParameters	参数不完整
    *1004	TelecomQuota	通道限额已满
    *1005	TimeOut	请求超时
    *1006    code关闭
    *1007    找不到可用通道(请求类型)
    *1100    OtherErr    其他错误
    *9999    BlackList  黑名单
    *1009    厂商关闭
    *1010    通道池对应运营商错误
    *0	Success	请求成功
     */
    public function requesterrforid($errid,$message)
    {
        if($this->iserr != true)
        {
             $this->resulet2 = $errid;
            $this->message = $message;
            $this->doresultdata('','','','','','','');
            $this->iserr = true;
        }
    }
    /**
     * Summary of gettelecominfo
     * @param mixed $telecomtype 
     * dport 短信上行端口
     * dconnet 下发内容
     * 
     * @return array 返回的arr当中包括计费代码列表中设置的验证码信息，包括下发端口，下发短信内容，下发验证码设定的长度，下发验证码的前后关键字
     */
    public function gettelecominfo($telecomtype = '')
    {
        if(empty($telecomtype)) 
        {
            $resultdata = array('dport'=>0,'dconnet'=>0);
            //   exit;
        }
        $selectwhere = array('telecomtype'=>$telecomtype);
        $info = M('telecom')->field('dport,dconnet,vinfo')->where($selectwhere)->select();
        $info = $info[0];
        $dport = $info['dport'];
        $dconnet = $info['dconnet'];
        $vinfo = $info['vinfo'];
        $vinfo = explode('_',$vinfo);
        $vlonginfo = $vinfo['0'];
        $vbeinfo = $vinfo['1'];
        if(empty($dport)) $dport = 0;
        if(empty($dconnet)) $dconnet = 0;
        if(empty($vlonginfo)) $vlonginfo = 0;
        if(empty($vbeinfo)) $vbeinfo = 0;

        $resultdata = array('dport'=>$dport,'dconnet'=>$dconnet,'vinfo'=>$vlonginfo,'vbeinfo'=>$vbeinfo);
        return $resultdata;
    }

    /**
     *  生成自定义参数规则 保存到表中
     *  $extarr= M('test')->field("nextval('pyz') as id")->select();    //调用存储过程 拿出数据规则
     * */
    
    public function extdatatoo($extarr2)
    {            
        $extarr=$extarr2;  
        $this->extData2=$extarr['0']['id'];       //生成自定义参数
        $data = array('telid'=>$this->extData2,);
        $tablename = $this->dateformonth.'extdata';       
        $mobile = M($tablename);
        $aa=$mobile->where(array('bksid'=>$this->extDatatoo))->data($data)->save();   
       // $bbb=M()->_sql();
        return $this->extData2;
    }
    
    /**
     * Summary of doresultdata  生成返回数据(公共)
     * @param mixed $smstype 
     * @param mixed $smsport 
     * @param mixed $smscontent 
     * @param mixed $youshu 
     * @param mixed $sid 
     * @param mixed $orderid 
     * @param mixed $model 
     */
    public function doresultdata($smstype='',$smsport='',$smscontent='',$youshu='',$sid='',$orderid='',$returncode='',$model='')
    {
        empty($smstype) && $smstype = 'text';
        empty($smsport) && $smsport = '';
        empty($smscontent) && $smscontent = '';
        empty($youshu) && $youshu = '';
        empty($sid) && $sid = $this->maxid;
        empty($orderid) && $orderid = $this->maxid;
        empty($model) && $model = '';
        empty($returncode) && $returncode = 'returncode';
        $city=$this->CtiyERR!=''&&$this->cityskd!=''?1:0; //判断SDK过来的城市 和iccid的城市是否屏蔽 不为空则是屏蔽
        $city2=$this->CtiyERR!=''&&$this->cityskd==''?2:0;
        //根据获取到的通道类型取验证码信息
        $resultdata = $this->gettelecominfo($this->telecomtype);
             if($this->isreques == false){  
                $tablename = C('DB_PREFIX').$this->dateformonth.'extdata';
                $id = 0;
                $data = array('coorderid'=>$this->extData);
                $this->adddataforsql($tablename,$data,true,$id);
                $this->maxid2 = $id;
                $maxid=$this->maxid2;
             }
        
        if($this->extData2==''){
            $this->extData2=uniqid();
            $this->telecomtype='0';
            $this->paycode='0';
            $this->iscodetype_jump = 2;
        }
        if($this->isreques == false){  
            if($this->resulet2==0 &&$this->paypriority==1){              //  优先级 paypriority :1  是走第三方 
                    if( $this->iccid_city==null ||$this->iccid_city_id==null ||$this->iccid_city_id=='32' ||$city==1||$this->paycodeapi=='1001' ||$this->egt==4 ||$this->paypriority==1){
                       
                              //第三方支付
                               $this->datainfo=2;
                               $whereco=array('coid'=>$this->cpconame);
                               $telecomsco = M('colist')->field('tpaypool')->where($whereco)->select();
                               $telecomsco=$telecomsco['0'];
                               $this->tpaypool=$telecomsco['tpaypool'];
                               $wherepool=array('id'=>$this->tpaypool);
                               $telecomspool = M('telecompools')->field('tpaytelecoms,wchartelecoms,ucardtelecoms')->where($wherepool)->select();
                               $telecomspool=$telecomspool['0'];
                               $tpaytelecoms=explode('_',$telecomspool['tpaytelecoms']);
                               $wchartelecoms=explode('_',$telecomspool['wchartelecoms']);
                               $ucardtelecoms=explode('_',$telecomspool['ucardtelecoms']);
                               unset($tpaytelecoms['0']);
                               unset($wchartelecoms['0']);
                               unset($ucardtelecoms['0']);
                               $paytelecoms=array('ZFB'=>$tpaytelecoms,'WX'=>$wchartelecoms,'YL'=>$ucardtelecoms);  
                        
                   }else{
                       $this->datainfo =1;        //短信
                       $paytelecoms=$paytelecoms=array('ZFB'=>'ZFB','WX'=>'ZFB','YL'=>'ZFB');
                   }
                    
            }elseif($this->resulet2!=0 ||$this->paypriority==1){
                if($this->resulet2!=0||$this->iccid_city==null || $this->resulet2==1 || $city2==2||$this->iccid_city_id==null ||$this->iccid_city_id=='32' ||$city==1||$this->paycodeapi=='1001' ||$this->egt==4 || $this->paypriority==1)
                            {
                                //第三方支付
                                $this->datainfo=2;
                                $selectwhereco = array('coid'=>$this->cpconame,'appid'=>$this->cpgamename);
                                $colistarr = M('cogameset')->field('status,appid,coid')->where($selectwhereco)->select();
                                $whereco=array('coid'=>$this->cpconame);
                                 if($colistarr==null){
                                         $telecomsco = M('colist')->field('tpaypool')->where($whereco)->select();
                                 }else{
                                     $telecomsco = M('cogameset')->field('tpaypool')->where($selectwhereco)->select();
                                 }
                                $telecomsco=$telecomsco['0'];
                                $this->tpaypool=$telecomsco['tpaypool'];
                                $wherepool=array('id'=>$this->tpaypool);
                                $telecomspool = M('telecompools')->field('tpaytelecoms,wchartelecoms,ucardtelecoms')->where($wherepool)->select();
                                $telecomspool=$telecomspool['0'];
                                $tpaytelecoms=explode('_',$telecomspool['tpaytelecoms']);
                                $wchartelecoms=explode('_',$telecomspool['wchartelecoms']);
                                $ucardtelecoms=explode('_',$telecomspool['ucardtelecoms']);
                                unset($tpaytelecoms['0']);
                                unset($wchartelecoms['0']);
                                unset($ucardtelecoms['0']);
                                $paytelecoms=array('ZFB'=>$tpaytelecoms,'WX'=>$wchartelecoms,'YL'=>$ucardtelecoms);                   
                            }else{
                                $this->datainfo =1;        //短信
                                $paytelecoms=$paytelecoms=array('ZFB'=>'ZFB','WX'=>'ZFB','YL'=>'ZFB');
                            }
                
            }else{
                    $this->datainfo =1;        //短信
                    $paytelecoms=$paytelecoms=array('ZFB'=>'ZFB','WX'=>'ZFB','YL'=>'ZFB');
            }
            
            //$city_info= \PublicData::$city; 
            //foreach($city_info as $value)
            //{
            //    if($value['city'] == $this->city)
            //    {
            //        $this->city = $value['city'];                
            //        $this->city_id = $value['id'];
            //    }
            //}
            //if($this->city==null){
            //    foreach(\publicdata::$ydmm2citylist as $k=>$v)
            //    {
            //        if($v['city'] == $this->city)
            //        {
            //            $this->city = $v['city'];    
            //            $this->city_id = $v['sx'];        //SKD过来的城市
            //        }                        
            //    }       
                
            //}
            //if($this->iccid != ''){
            //    $this->checkcityforiccd($this->iccid);
            //}
            //$model = 'sdkversion';
            //$model = D($model);
            //$colistmodel = 'colist';
            //$colistmodel = D($colistmodel);
            //$applistmodel = 'gamelist';
            //$applistmodel = D($applistmodel);
            //if($this->cpconame== ''||$this->cpgamename=='')      //大版本号
            //{
            //    echo('error');
            //    return;
            //}
            //if($this->cpconame!=''&&$this->cpgamename!=''){        //小版本号
                
            //    $coid=$this->cpconame;
            //    $appid=$this->cpgamename;
            //    $propid=$this->propid;
            //    $selectwhereco = array('coid'=>$coid);  //厂商
            //    $selectwhereapp = array('appid'=>$appid);  //游戏
            //    $selectwherecogame = array('coid'=>$coid,'appid'=>$appid);  
            //    $selectwhereprop= array('propid'=>$propid,'appid'=>$appid);  
            //    $appinfo = $applistmodel->field('version')->where($selectwhereapp)->select();       //获取游戏版本号
            //    $appinfo=$appinfo['0'];
            //    $colistarr = M('cogameset')->field('status,appid,coid')->where($selectwherecogame)->select();   //获取DIY分配
            //    if($colistarr==null){
            //        $coinfo = $colistmodel->field('version,reconfirm,status,sdkmoudle')->where($selectwhereco)->select();       ///获取游戏的二次弹框 
            //    }else{
            //        $coinfo = M('cogameset')->field('version,reconfirm,status,sdkmoudle')->where($selectwherecogame)->select();      //获取游戏分配的二次弹框    
            //    }
            //      $propinfo = M('proplist')->field('sdkmoudle,gold')->where($selectwhereprop)->select();      //获取道具的 SDK模板
            //    $propinfo=$propinfo['0'];
            //    $coinfo=$coinfo['0'];
            //    //$fee = $propinfo['gold'];       //道具对应金额
            //    $uid = $appinfo['version'];     //游戏版本号
            //    //$uid = $coinfo['version'];   
            //    $version=explode(',',$uid);
            //    $reconfirm=$coinfo['reconfirm'];
            //    $status=$coinfo['status'];
            //    $sdkmoudle=explode('_',$propinfo['sdkmoudle']);
            //    unset($sdkmoudle['0']);
            //    $sdkmoudlearr=array();
            //    foreach($sdkmoudle as $k1=>$v1){
            //        $selectwheresdk=array('id'=>$v1);
            //        $sdkmoudleinfo[$k1] = M('sdkmoudle')->field('moudlename,moudlepic,provinces')->where($selectwheresdk)->select();
            //        $sdkmoudleinfo= $sdkmoudleinfo[$k1]['0'];
            //        $sdkmoudlearr[]=$sdkmoudleinfo;
            //    }
            //    $sdk=$sdkmoudlearr;
            //    $sdkdata=array();
            //    if($this->iccid_city_id==null){           //iccid优先
            //        foreach($sdk as $k2 =>$v2){
            //            $sdkinfo =explode('_',$v2['provinces']);
            //            unset($sdkinfo['0']);
            //            foreach($sdkinfo  as $k3 =>$v3){
            //                if($v3==$this->city_id){                                    
            //                    //  $sdkdata['moudlename']=$v2['moudlename'];
            //                    $sdkdatainfo=$v2['moudlepic'];         //URL  
            //                    $sdkdata[]=$sdkdatainfo;
            //                }
            //            }
            //        }
            //    }else{
            //        foreach($sdk as $k2 =>$v2){
            //            $sdkinfo =explode('_',$v2['provinces']);
            //            unset($sdkinfo['0']);
            //            foreach($sdkinfo  as $k3 =>$v3){
            //                if($v3==$this->iccid_city_id){                                    
            //                    //  $sdkdata['moudlename']=$v2['moudlename'];
            //                    $sdkdatainfo=$v2['moudlepic'];         //URL  
            //                    $sdkdata[]=$sdkdatainfo;
            //                }
            //            }
            //        }
            //    }
            //    $datapico=$sdkdata;  
            //    //$mun=count($datapico);
            //    //$ran= mt_rand(0,$mun-1); //随机取数
            //    // $datapico2=array();
            //    $datapico2[] = $datapico;
            //    $pic_url=$datapico2[0][0];
            //    $pic_url1=$datapico2[0][1];
            //    if($datapico2[0]==null){
            //        $reconfirm=2; 
            //    }
            //    $infoarr=array();
            //    foreach($version as $k=>$v){
            //        $selectwhere = array('uid'=>$v);  //版本号uid
            //        $info[$k] = $model->where($selectwhere)->select();
            //        $info=$info[$k]['0'];
            //        $infoarr[]=$info;
            //    }   
            //}
            //if(isset($infoarr)||isset($reconfirm)||isset($status)||isset($pic_url)){
            //        $arrdata=array();
            //        foreach($infoarr as $value)
            //        {
            //            $data = array('md5'=>$value['md5'],'classname'=>$value['classname'],'uid'=>$value['uid'],'url'=>$value['url']);
            //            $arrdata[]=$data;
            //        }
            //        //$data2= $arrdata;
            //        $resultdata2 = array('code'=>0,'status'=>$status,'reconfirm'=>$reconfirm,'pic_url'=>$pic_url,'pic_url1'=>$pic_url1);
            // }
           
            
        }
        if($this->iscodetype_jump == 2 )
        {
            //如果此请求是API2类型请求连接，且当前请求为第一步请求则执行以下代码，以下代码主要用于保留第二步需要的数据至order1data数据表中，
            //当用户发起第二步请求直接根据sid参数索引orderdata表
            if($this->isreques == false)
            {
                if($this->code!=null){
                        $sid2  =  $this->maxid;
                }else{
                      $sid2  =  $this->maxid;
                }
                $data = array('id'=>$sid2,'orderid'=>$orderid,'bksid'=>$this->extDatatoo,'code'=>$this->code,'mobile'=>$this->mobile,'telecomtype'=>$this->telecomtype,'appid'=>$this->cpgamename,'coid'=>$this->cpconame,'egt'=>$this->egt,'propid'=>$this->propid,'returncode'=>$returncode,'maxid'=>$maxid);
                $tablename = $this->dateformonth.'order1data';
                $mobile = M($tablename);
                $isok = $mobile->data($data)->add();
                $a =  $mobile->_sql();
                if($isok !=0 || $isok != false)
                {
                }
                else
                {
                    //如果添加数据失败，程序将在此步直接退出
                    echo('errdata');
                    exit();
                }
            }

        }
        if($this->paypriority==2){
                $this->datainfo=1;
        }
        //生成下发数据
        $this->resultdata = array('code'=>$this->resulet2,'message'=>$this->message,'data'=>array(array('smstype'=>$smstype,'smsport'=>$smsport,
            'smscontent'=>$smscontent,'youshu'=>$youshu,'codetypeforrequest'=>$this->iscodetype_jump,'req'=>'')),'sid'=>$sid,'maxid'=>$maxid,'orderid'=>$orderid,
            'mobile'=>$this->mobile,'telecomtype'=>$this->telecomtype
        ,'dport'=>$resultdata['dport'],'dconnet'=>$resultdata['dconnet'],'vinfo'=>$resultdata['vinfo'],'vbeinfo'=>$resultdata['vbeinfo'],'bksid'=>$this->extDatatoo,'apistatus'=>$this->datainfo,'contet'=>$paytelecoms);
        //下发数据
        //echo json_encode($this->resultdata);
      }
    
    public function doresultdata1($smstype='',$smsport='',$smscontent='',$youshu='',$sid='',$orderid='',$returncode='',$model='')
    {
        empty($smstype) && $smstype = 'text';
        empty($smsport) && $smsport = '';
        empty($smscontent) && $smscontent = '';
        empty($youshu) && $youshu = '';
        empty($sid) && $sid = $this->maxid;
        empty($orderid) && $orderid = $this->maxid;
        empty($model) && $model = '';
        empty($returncode) && $returncode = 'returncode';
        $city=$this->CtiyERR!=''&&$this->cityskd!=''?1:0; //判断SDK过来的城市 和iccid的城市是否屏蔽 不为空则是屏蔽
        $city2=$this->CtiyERR!=''&&$this->cityskd==''?2:0;
        //根据获取到的通道类型取验证码信息
        $resultdata = $this->gettelecominfo($this->telecomtype);
        if($this->isreques == false){  
            if($this->resulet2==0 &&$this->paypriority==1){              //  优先级 paypriority :1  是走第三方 
                if( $this->iccid_city==null ||$this->iccid_city_id==null ||$this->iccid_city_id=='32' ||$city==1||$this->paycodeapi=='1001' ||$this->egt==4 ||$this->paypriority==1){
                    
                    //第三方支付
                    $this->datainfo=2;
                    $whereco=array('coid'=>$this->cpconame);
                    $telecomsco = M('colist')->field('tpaypool')->where($whereco)->select();
                    $telecomsco=$telecomsco['0'];
                    $this->tpaypool=$telecomsco['tpaypool'];
                    $wherepool=array('id'=>$this->tpaypool);
                    $telecomspool = M('telecompools')->field('tpaytelecoms,wchartelecoms,ucardtelecoms')->where($wherepool)->select();
                    $telecomspool=$telecomspool['0'];
                    $tpaytelecoms=explode('_',$telecomspool['tpaytelecoms']);
                    $wchartelecoms=explode('_',$telecomspool['wchartelecoms']);
                    $ucardtelecoms=explode('_',$telecomspool['ucardtelecoms']);
                    unset($tpaytelecoms['0']);
                    unset($wchartelecoms['0']);
                    unset($ucardtelecoms['0']);
                    $paytelecoms=array('ZFB'=>$tpaytelecoms,'WX'=>$wchartelecoms,'YL'=>$ucardtelecoms);  
                    
                }else{
                    $this->datainfo =1;        //短信
                    $paytelecoms='';
                }
                
            }elseif($this->resulet2!=0 ||$this->paypriority==1){
                if($this->resulet2!=0||$this->iccid_city==null || $this->resulet2==1 || $city2==2||$this->iccid_city_id==null ||$this->iccid_city_id=='32' ||$city==1||$this->paycodeapi=='1001' ||$this->egt==4 || $this->paypriority==1)
                {
                    //第三方支付
                    $this->datainfo=2;
                    $whereco=array('coid'=>$this->cpconame);
                    $telecomsco = M('colist')->field('tpaypool')->where($whereco)->select();
                    $telecomsco=$telecomsco['0'];
                    $this->tpaypool=$telecomsco['tpaypool'];
                    $wherepool=array('id'=>$this->tpaypool);
                    $telecomspool = M('telecompools')->field('tpaytelecoms,wchartelecoms,ucardtelecoms')->where($wherepool)->select();
                    $telecomspool=$telecomspool['0'];
                    $tpaytelecoms=explode('_',$telecomspool['tpaytelecoms']);
                    $wchartelecoms=explode('_',$telecomspool['wchartelecoms']);
                    $ucardtelecoms=explode('_',$telecomspool['ucardtelecoms']);
                    unset($tpaytelecoms['0']);
                    unset($wchartelecoms['0']);
                    unset($ucardtelecoms['0']);
                    $paytelecoms=array('ZFB'=>$tpaytelecoms,'WX'=>$wchartelecoms,'YL'=>$ucardtelecoms);                   
                }else{
                    $this->datainfo =1;        //短信
                    $paytelecoms='';
                }
                
            }else{
                $this->datainfo =1;        //短信
                $paytelecoms='';
            }
        }
        if($this->iscodetype_jump == 2 )
        {
            //如果此请求是API2类型请求连接，且当前请求为第一步请求则执行以下代码，以下代码主要用于保留第二步需要的数据至order1data数据表中，
            //当用户发起第二步请求直接根据sid参数索引orderdata表
            if($this->isreques == false)
            {
                $data = array('id'=>$sid,'orderid'=>$orderid,'mobile'=>$this->mobile,'telecomtype'=>$this->telecomtype,'appid'=>$this->cpgamename,'coid'=>$this->cpconame,'egt'=>$this->egt,'propid'=>$this->propid,'returncode'=>$returncode);
                $tablename = $this->dateformonth.'order1data';
                $mobile = M($tablename);
                $isok = $mobile->data($data)->add();
                if($isok !=0 || $isok != false)
                {
                }
                else
                {
                    //如果添加数据失败，程序将在此步直接退出
                    echo('errdata');
                    exit();
                }
            }

        }
        //生成下发数据
        $this->resultdata = array('code'=>$this->resulet2,'message'=>$this->message,'data'=>array(array('smstype'=>$smstype,'smsport'=>$smsport,
            'smscontent'=>$smscontent,'youshu'=>$youshu,'codetypeforrequest'=>$this->iscodetype_jump,'req'=>'')),'sid'=>$sid,'orderid'=>$orderid,
            'mobile'=>$this->mobile,'telecomtype'=>$this->telecomtype
        ,'dport'=>$resultdata['dport'],'dconnet'=>$resultdata['dconnet'],'vinfo'=>$resultdata['vinfo'],'vbeinfo'=>$resultdata['vbeinfo'],'bksid'=>$this->extDatatoo,'apistatus'=>$this->datainfo,'contet'=>$paytelecoms);
        //下发数据
        echo json_encode($this->resultdata);
       
    }
    
    
    //第三方天虎订单添加接口
    public function Apiadd($token,$appkey,$source,$seq,$sign,$method,$paramJson){ 
        $this->seqadd = $seq;
        $this->token=$token;
        $this->appkey = $appkey;
        $this->source = $source;
        $this->signadd = $sign;
        $this->methoddd = $method;
        $this->paramJsonadd = $paramJson; 
        $paramJsondata=json_decode($paramJson,trun);
        $paramJsonorder=$paramJsondata['orders'];
        $paramJsonorders= json_decode($paramJsonorder,trun);
        $paramJsonorders=$paramJsonorders['0'];
        $data['freight']=$paramJsonorders['logisticsAmount'];
        $data['freightid']=$paramJsonorders['logisticsId'];
        $data['shopid']=$paramJsonorders['mshopId'];
        $data['ordertypeid']=$paramJsonorders['ordertypeId'];
        $data['payprice']=$paramJsonorders['payableAmount'];
        $data['totalprice']=$paramJsonorders['totalAmount'];
        $paramJsongoods=$paramJsonorders['goods']['0'];
        $data['buyprice']=$paramJsongoods['buyPrice'];  //商品价格
        $data['goodscount']=$paramJsongoods['goodsCount']; //商品数量
        $data['skuid']=$paramJsongoods['skuId'];       //商品skuId
        $params='method='.$this->methoddd.'&auth='.'1'.'&paramJson='.$paramJson;
        $url= 'http://182.140.144.65:8083/tyfoAPI/tyfo/soa';   //订单添加
        $headerLogin=array(
          "token:". $this->token,
          "sign:".$this->signadd,
          "source:".$this->source,
          "seq:".$this->seqadd,
          "appkey:".$this->appkey
             
          );
        $result= $this->curl_file_get_contentsarr($url,$params,$headerLogin); //获取
        $this->resultadd=$result;
        $resultdata=json_decode($result,trun);
        $items =$resultdata['content']['items'] ;
        $msg =$resultdata['msg'];
        $resultCode =$resultdata['resultCode'];
        $order=$resultdata['content']['items'];
        $arr=array("[","]");
        $data['orderid']=str_replace($arr,"",$order);
        $tablename = 'item';
        $mobile = M($tablename);
        $mobile->data($data)->add(); 
        $this->resultadd= array('content'=>array('items'=>$items),'msg'=>$msg,'resultCode'=>$resultCode);
        echo json_encode($this->resultadd);
        return  $result;
    }
    
    //第三方支付接口
    public function curl_file_get_contentsarr(&$durl,&$post_data,$header=''){
        //   "Cache-Control: no-cache",
        //   "Pragma: no-cache",
        //  "Content-Type: application/json;charset=utf-8",
        //  "Accept:application/json",
        // "Content-type: application/x-www-form-urlencoded",
        //   " Accept:application/octet-stream",
        // "Accept-Language:zh-CN,zh",
        //  'Content-Length: '.strlen($post_data)       //还有需不需要带上其它与权限相关的参数值        
        $curl = curl_init (); // 启动一个CURL会话
		curl_setopt ( $curl, CURLOPT_URL, $durl ); // 要访问的地址
		curl_setopt ( $curl, CURLOPT_SSLVERSION, 4 );
		curl_setopt ( $curl, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT'] ); // 模拟用户使用的浏览器
		curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 ); // 使用自动跳转
		curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
		curl_setopt ( $curl, CURLOPT_POST, 1 ); // 发送一个常规的Post请求
		curl_setopt ( $curl, CURLOPT_POSTFIELDS, $post_data); // Post提交的数据包
		curl_setopt ( $curl, CURLOPT_TIMEOUT, 30 ); // 设置超时限制防止死循环
		curl_setopt ( $curl, CURLOPT_HTTPHEADER, $header );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true); // 获取的信息以文件流的形式返回     
        $r = curl_exec($curl);
        curl_close($curl);
        return $r;
    }
    //第三方天虎订单添加接口
    public function ApiDoresultdata($smstype='',$smsport='',$smscontent='',$youshu='',$sid='',$orderid='',$model='')
    {
        empty($smstype) && $smstype = 'text';
        empty($smsport) && $smsport = '';
        empty($smscontent) && $smscontent = '';
        empty($youshu) && $youshu = '';
        empty($sid) && $sid = $this->maxid;
        empty($orderid) && $orderid = $this->maxid;
        empty($model) && $model = '';
        $resultdata = $this->gettelecominfo($this->telecomtype);
        if($this->iscodetype_jump == 2 )
        {
            if($this->isreques == false)
            {
                $data = array('id'=>$sid,'orderid'=>$orderid,'mobile'=>$this->mobile,'telecomtype'=>$this->telecomtype,'code'=>$this->code,'imsi'=>$this->imsi);
                $tablename = $this->dateformonth.'order1data';
                $mobile = M($tablename);
                $isok = $mobile->data($data)->add();
                // var_dump($isok);
                if($isok !=0 || $isok != false)
                {
                }
                else
                {
                    echo('errdata');
                    exit();
                }
            }

        }
        $this->resultdata = array('code'=>$this->resulet2,'message'=>$this->message,'data'=>array(array('smstype'=>$smstype,'smsport'=>$smsport,
            'smscontent'=>$smscontent,'youshu'=>$youshu,'codetypeforrequest'=>$this->iscodetype_jump,'req'=>'')),'sid'=>$sid,'orderid'=>$orderid,
            'mobile'=>$this->mobile,'telecomtype'=>$this->telecomtype
        ,'dport'=>$resultdata['dport'],'dconnet'=>$resultdata['dconnet'],'vinfo'=>$resultdata['vinfo'],'vbeinfo'=>$resultdata['vbeinfo']); //vinfo 验证码长度  //验证码最后一位
        //  echo json_encode($this->resultdata);
    }
    
    public function check_null($valuelist = array())
    {
        foreach($valuelist as $k=>$v)
        {
            if($v == null)
            {
                $valuelist[$k] = '';
            }
        }
    }
    public function checkupdayforoutletspaycode()
    {
        $t = time();
        $time1 = date("y-m-d",$t);
        $time1 = $time1.' 00:00:00'; 
        $time1 = strtotime($time1);
        $t = time();
        $time2 = date("Y-m-d",$t).' 23:59:59'; 
        $time2 = strtotime($time2);
        $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));        
        $selectwhere2 = array('outletname'=>$this->outletsname,'paycode'=>$this->paycode);
        $tablename = $this->dateformonth."ordercount";
        $model = M($tablename);
        $msggold = $model->table('bks_'.$tablename)->where($selectwhere)->where($selectwhere2)->sum("msggold");
        $fee =$this->updayvalueforcode - $this->fee;
        if($this->updayvalueforcode != 0)
        {
            if($msggold > $fee)
            {
                $emailtitle = ':你好.到量了!!!来自星星的邮件.博卡森科技';
                $emailcontrol = '_渠道:_'.$this->outletsutf8name.'_金额_:'.$this->fee.'(元)_计费点_:'.$this->codename.'已经到量，请及时处理_'.'当前峰值为:'.$this->updayvalueforcode.'(元)'.'_摘自深圳市博卡森科技有限公司';
                $emaillist = \PublicData::$emailuserlist;
                foreach($emaillist as $value)
                {
                    $isok = sendMail($value['email'],$value['name'].$emailtitle,$emailcontrol);
                }
                return;
            }
            else
            {
                $this->checkupdayforoutlets();
                return;
            }
        }
        else
        {
            $this->checkupdayforoutlets();
            return;
        }
    }
    public function checkupdayforoutlets()
    {
        $t = time();
        $time1 = date("y-m-d",$t);
        $time1 = $time1.' 00:00:00'; 
        $time1 = strtotime($time1);
        $t = time();
        $time2 = date("Y-m-d",$t).' 23:59:59'; 
        $time2 = strtotime($time2);
        $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));        
        $selectwhere2 = array('outletname'=>$this->outletsname);
        $tablename = $this->dateformonth."ordercount";
        $model = M($tablename);
        $msggold = $model->table('bks_'.$tablename)->where($selectwhere)->where($selectwhere2)->sum("msggold");
        $fee =$this->updayvalueforoutlets - $this->fee;
        if($this->updayvalueforoutlets != 0)
        {
            if($msggold > $fee)
            {
                $emailtitle = ':你好.到量了!!!来自星星的邮件.博卡森科技';
                $emailcontrol ='_渠道:_'.$this->outletsutf8name.'_'.'已经到量，请及时处理'.'当前峰值为:'.$this->updayvalueforoutlets.'(元)'.'摘自深圳市博卡森科技有限公司';
                $emaillist = \PublicData::$emailuserlist;
                foreach($emaillist as $value)
                {
                    $isok = sendMail($value['email'],$value['name'].$emailtitle,$emailcontrol);
                }
                return;
            }
            else
            {
                $this->checkupdayforpaycode();
                return;
            }
        }
        else
        {
            $this->checkupdayforpaycode();
            return;
        }
    }
    public function checkupdayforpaycode()
    {
        $t = time();
        $time1 = date("y-m-d",$t);
        $time1 = $time1.' 00:00:00'; 
        $time1 = strtotime($time1);
        $t = time();
        $time2 = date("Y-m-d",$t).' 23:59:59'; 
        $time2 = strtotime($time2);
        $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));        
        $selectwhere2 = array('telecomname'=>$this->telecomname,'paycode'=>$this->paycode);
        $tablename = $this->dateformonth."ordercount";
        $model = M($tablename);
        $msggold = $model->table('bks_'.$tablename)->where($selectwhere)->where($selectwhere2)->sum("msggold");
        $fee =$this->updayvalue - $this->fee;
        if($this->updayvalue != 0)
        {
            if($msggold > $fee)
            {
                $emailtitle = ':你好.到量了!!!来自星星的邮件.博卡森科技';
                $emailcontrol = '通道:_'.$this->telecomnameutf8name.'_'.'金额:'.$this->fee.'_计费点:'.$this->codename.'(元)已经到量，请及时处理'.'当前峰值为:'.$this->updayvalue.'(元)'.'摘自深圳市博卡森科技有限公司';
                $emaillist = \PublicData::$emailuserlist;
                foreach($emaillist as $value)
                {
                    sendMail($value['email'],$value['name'].$emailtitle,$emailcontrol);
                }
                return;
            }
            else
            {
                $this->checkupdayfortelecom();
                return;
            }
        }
        else
        {
            $this->checkupdayfortelecom();
            return;
        }
        

    }
    public function checkupdayfortelecom()
    {
        $t = time();
        $time1 = date("y-m-d",$t);
        $time1 = $time1.' 00:00:00'; 
        $time1 = strtotime($time1);
        $t = time();
        $time2 = date("Y-m-d",$t).' 23:59:59'; 
        $time2 = strtotime($time2);
        $selectwhere['time'] = array(array('egt',(int)$time1),array('elt',(int)$time2));        
        $selectwhere2 = array('telecomname'=>$this->telecomname);
        $tablename = $this->dateformonth."ordercount";
        $model = M($tablename);
        $msggold = $model->table('bks_'.$tablename)->where($selectwhere)->where($selectwhere2)->sum("msggold");
        $fee =  $this->updayvaluefortelecom - $this->fee;
        if($this->updayvaluefortelecom != 0)
        {
            if($msggold > $fee)
            {
                $uft8name = M('telecomlist')->where(array('id'=>$this->telecomname))->select();
                $uft8name = $uft8name[0]['name'];
                $emailtitle = ':你好.到量了!!!来自星星的邮件.博卡森科技';
                $emailcontrol = '通道:_'.$this->telecomnameutf8name.'_'.'已经到量，请及时处理'.'当前峰值为:'.$this->updayvaluefortelecom.'(元)'.'摘自深圳市博卡森科技有限公司';
                $emaillist = \PublicData::$emailuserlist;
                foreach($emaillist as $value)
                {
                    sendMail($value['email'],$value['name'].$emailtitle,$emailcontrol);
                }
            }
        }
    }
    public function check_paycodewarning()      //计费代码预警
    {
        $selectwhere = array('paycodename'=>$this->paycode);
        $time = time();
        $data = array('warbegintime'=>$time);
        $model2 = M('paycodelist');
        $outletsinfo = $model2->select();
        foreach($outletsinfo as $k=>$v)
        {
            if($v['warlongtime'] == 0)
            {
                unset($outletsinfo[$k]);
            }
        }
        foreach($outletsinfo as $k=>$v)
        {
            if($v['paycodename'] == $this->paycode)
            {
                $model2->where($selectwhere)->data($data)->save();
                return;
            }
            $otime = $v['warbegintime'];
            $time2 = $time-$otime;
            $endtime = $v['warlongtime'] * 60;
            if($time2 >= $endtime)
            {
                $selectwhere = array('paycodename'=>$v['paycodename']);
                $isok =$model2->where($selectwhere)->data($data)->save();
                $emailtitle = ':你好.通道计费代码异常!!!来自星星的邮件.博卡森科技';
                $emailcontrol = '_通道:_'.$this->telecomnameutf8name.'计费点'.$v['utf8name'].'异常:'.'超出预设预警时间'.$v['warlongtime'].'分'.'_摘自深圳市博卡森科技有限公司';
                $emaillist = \PublicData::$emailuserlist;
                foreach($emaillist as $value)
                {
                    $isok = sendMail($value['email'],$value['name'].$emailtitle,$emailcontrol);
                }
            }
        }
    }
    /**
     * Summary of check_outletswarning  通道预警
     */
    public function check_telecomwarning()
    {
        $selectwhere = array('telecomname'=>$this->telecomname);    //通道名称
        $time = time();
        $data = array('warbegintime'=>$time);
        $model2 = M('telecom');
        $outletsinfo = $model2->select();
        foreach($outletsinfo as $k=>$v)
        {
            if($v['warlongtime'] == 0)
            {
                unset($outletsinfo[$k]);
            }
        }
        foreach($outletsinfo as $k=>$v)
        {
            if($v['telecomname'] == $this->telecomname)
            {
                $model2->where($selectwhere)->data($data)->save();
                return;
            }
            $otime = $v['warbegintime'];
            $time2 = $time-$otime;
            $endtime = $v['warlongtime'] * 60;
            if($time2 >= $endtime)
            {
                $selectwhere = array('telecomname'=>$v['telecomname']);
                //$isok =$model2->where($selectwhere)->data($data)->save();
                $emailtitle = ':你好.项目二通道异常!!!来自星星的邮件.博卡森科技';
                $emailcontrol = '_通道:_'.$v['utf8name'].'异常:'.'超出预设预警时间'.$v['warlongtime'].'分'.'_摘自深圳市博卡森科技有限公司';
                $emaillist = \PublicData::$emailuserlist;
                foreach($emaillist as $value)
                {
                    $isok = sendMail($value['email'],$value['name'].$emailtitle,$emailcontrol);
                }
            }
        }

    }
  
    /**
     * Summary of check_outletsinfo     检测厂家游戏信息
     * @param mixed $codelist 
     */
     public function check_cogameinfo()
     {
         $selectwhere = array('coid'=>$this->cpconame,); //厂商
         $tablename = 'colist'; //厂商表
         $field ='status,type,telecoms,prioritytelecom,name,appname,prioritytelecom_status';
         $outletsinfo = $this->selectwhereformysql($tablename,$field,$selectwhere);
         $outletsinfo = $outletsinfo['0'];
         $telecoms = $outletsinfo['telecoms'];  //厂商指定通道
         $this->outletstelecom = explode('_',$telecoms); 
         unset($this->outletstelecom[0]);
         //var_dump($this->outletstelecom);
         $priorityforoutletss = $outletsinfo['prioritytelecom'];//通道优先级
         $priorityforoutletsarray = explode('_',$priorityforoutletss);
         foreach($priorityforoutletsarray as $k=>$v)
         {
             $v2 = explode('#',$v);
             $temp[] = $v2;
         }
         unset($temp[0]);
         $this->prioritytelecom = $temp; //通道和优先级
         //var_dump($this->prioritytelecom);
         if($outletsinfo['status'] == 2 ||$outletsinfo==null)  //2：为厂商关闭
         {
             $nameco='厂商关闭或厂商不匹配';
             $nameco1= rawurlencode(mb_convert_encoding($nameco, 'utf-8', 'utf-8'));
             $nameco2=  urldecode($nameco1);
             echo $nameco2;
             exit();
         }
         //渠道类型 2为:api1  3为:API2  1为:SDK    
         $outletstype = $outletsinfo['type'];
         $this->outletstype = $outletstype;
         switch($outletstype)
         {
             case 2:
                 {
                     $this->requesttype = 1; //API1
                     break;
                 }
             case 3:
                 {
                     $this->requesttype = 2; //PAI2
                     break;
                 }
             default:
                 {
                     $this->requesttype = 0;   //SKD
                     break;
                 }
         }
         $priorityforoutlets_status = $outletsinfo['prioritytelecom_status'];
         $this->priorityforoutlets_status = explode('_',$priorityforoutlets_status);  //渠道对应营运商的状态开关 1允许
         //var_dump($this->priorityforoutlets_status);
         $this->outletsutf8name = $outletsinfo['name'];  //厂商名称
         $this->gameutf8name = $outletsinfo['appname'];
  
     
     }
     
     /**
      * Summary of check_outletsinfo     检测游戏分配厂商信息
      * @param mixed $codelist 
      */
     public function cogamecheck_cogameinfo()
     {
         $selectwhere = array('coid'=>$this->cpconame,'appid'=>$this->cpgamename,); //游戏,厂商
         $tablename = 'cogameset'; //游戏分配表
         $field ='status,type,telecoms,prioritytelecom,coname,appname,prioritytelecom_status';
         $outletsinfo = $this->selectwhereformysql($tablename,$field,$selectwhere);
         $outletsinfo = $outletsinfo['0'];
         $telecoms = $outletsinfo['telecoms'];  //厂商指定通道
         $this->outletstelecom = explode('_',$telecoms); 
         unset($this->outletstelecom[0]);
         //var_dump($this->outletstelecom);
         $priorityforoutletss = $outletsinfo['prioritytelecom'];//通道优先级
         $priorityforoutletsarray = explode('_',$priorityforoutletss);
         foreach($priorityforoutletsarray as $k=>$v)
         {
             $v2 = explode('#',$v);
             $temp[] = $v2;
         }
         unset($temp[0]);
         $this->prioritytelecom = $temp; //通道和优先级
         //var_dump($this->prioritytelecom);
         if($outletsinfo['status'] == 2 ||$outletsinfo==null)  //2：为厂商关闭
         {
             $nameco='厂商关闭或厂商不匹配';
             $nameco1= rawurlencode(mb_convert_encoding($nameco, 'utf-8', 'utf-8'));
             $nameco2=  urldecode($nameco1);
             echo $nameco2;
             exit();
         }
         //渠道类型 2为:api1  3为:API2  1为:SDK    
         $outletstype = $outletsinfo['type'];
         $this->outletstype = $outletstype;
         switch($outletstype)
         {
             case 2:
                 {
                     $this->requesttype = 1; //API1
                     break;
                 }
             case 3:
                 {
                     $this->requesttype = 2; //PAI2
                     break;
                 }
             default:
                 {
                     $this->requesttype = 0;   //SKD
                     break;
                 }
         }
         $priorityforoutlets_status = $outletsinfo['prioritytelecom_status'];
         $this->priorityforoutlets_status = explode('_',$priorityforoutlets_status);  //渠道对应营运商的状态开关 1允许
         //var_dump($this->priorityforoutlets_status);
         $this->outletsutf8name = $outletsinfo['coname'];  //厂商名称
         $this->gameutf8name = $outletsinfo['appname'];
         
         
     }
    
    
    /**
     * Summary of check_outletsinfo     检测道具信息
     * @param mixed $codelist 
     */
    public function check_propinfo()
    {
        //$this->outletsname = $this->pseudocodeinfo['outletsname'];
        $selectwhere = array('propid'=>$this->cpgamepropname,'appid'=>$this->cpgamename);
        $tablename = 'proplist';
        $field = 'appid,name,propid,status';
        $outletsinfo = $this->selectwhereformysql($tablename,$field,$selectwhere);      //检测道具和道具中的游戏信息
        $selectwhere2 = array('appid'=>$this->cpgamename);
        $tablename2 = 'gamelist';
        $field2 = 'id,appid,name,status';
        $outappid = $this->selectwhereformysql($tablename2,$field2,$selectwhere2);        //检测游戏
        $outappid = $outappid['0'];
        $outletsinfo = $outletsinfo['0'];
        //$telecoms = $outletsinfo['telecoms'];
        //$this->outletstelecom = explode('_',$telecoms);
        //$priorityforoutletss = $outletsinfo['prioritytelecom'];
        //$priorityforoutletsarray = explode('_',$priorityforoutletss);
        //foreach($priorityforoutletsarray as $k=>$v)
        //{
        //    $v2 = explode('#',$v);
        //    $temp[] = $v2;
        //}
        //unset($temp[0]);
        //$this->prioritytelecom = $temp;
        //unset($this->outletstelecom[0]);
        if($this->extData==null){
            $nameextData='自定义参数为空';
            $nameextData2= rawurlencode(mb_convert_encoding($nameextData, 'utf-8', 'utf-8'));
            $nameextData3=  urldecode($nameextData2);
            echo $nameextData3;
            exit();    
        }
        if(isset($this->egt)){
            $nameegt1='营运商不正确';
            $arrnameegt1= rawurlencode(mb_convert_encoding($nameegt1, 'utf-8', 'utf-8'));
           $arrnameegt1=  urldecode($arrnameegt1);
            $nameegt2=  '营运商不能为空';
            $arrnameegt2 = rawurlencode(mb_convert_encoding($nameegt2, 'utf-8', 'utf-8' ));
            $arrnameegt2=  urldecode($arrnameegt2);
            switch($this->egt)
            {
                case 1:         
                        $this->egt=1;
                    break;
                case 2: 
                        $this->egt=2;
                    break;
                case 3:
                        $this->egt=3;
                    break;
                case 4:
                    $this->egt=4;
                    break;
                default:  exit ($arrnameegt1);
            }           
        }
        
        if($outappid['status'] == 2 ||$outappid==null)
        {
            $nameappid='游戏关闭或游戏不匹配';
            $nameappid2= rawurlencode(mb_convert_encoding($nameappid, 'utf-8', 'utf-8'));
            $nameextData3=  urldecode($nameappid2);
            echo $nameextData3;//游戏关闭或者没有游戏
            exit();
        }
        if($outletsinfo['status'] == 2 || $outletsinfo==null)
        {
            $nameletsinfo='道具关闭或道具不匹配';
            $nameletsinfo2= rawurlencode(mb_convert_encoding($nameletsinfo, 'utf-8', 'utf-8'));
            $nameletsinfo3=  urldecode($nameletsinfo2);
            echo $nameletsinfo3;//道具关闭或者没有游戏
            exit();
        }
        // 1:SDK 2:API
       //$outletstype = $outletsinfo['type'];
       //  $this->outletstype = $outletstype;
       // switch($outletstype)
       // {
       //     case 2:
       //     {
       //         $this->requesttype = 1;
       //         break;
       //     }
       //     case 3:
       //     {
       //         $this->requesttype = 2;
       //         break;
       //     }
       //     default:
       //     {
       //         $this->requesttype = 0;
       //         break;
       //     }
       // }
       // $priorityforoutlets_status = $outletsinfo['prioritytelecom_status'];
       // $this->priorityforoutlets_status = explode('_',$priorityforoutlets_status);
       // //if($this->outletstype == 2)
       // //{
       // //    $this->requesttype = 1;
       // //}
       // //else if($this->outletstype == 3)
       // //{
       // //    $this->requesttype = 2;
       // //}
       // //else
       // //    $this->requesttype = 0;
       // //$outletsinfo = M('outletslist')->where(array('id'=>$this->outletsname))->select();
       // $this->outletsutf8name = $outletsinfo['utf8name'];
       // ////var_dump($outletsinfo);
    }
    
    /**
     * Summary of check_outletsinfo     检测游戏分配道具信息
     * @param mixed $codelist 
     */
    public function cogamecheck_propinfo()
    {
        $selectwhere = array('propid'=>$this->cpgamepropname,'appid'=>$this->cpgamename);
        $tablename = 'proplist';
        $field = 'appid,name,propid,status';
        $outletsinfo = $this->selectwhereformysql($tablename,$field,$selectwhere);      //检测道具和道具中的游戏信息
        $selectwhere2 = array('appid'=>$this->cpgamename);
        $tablename2 = 'gamelist';
        $field2 = 'id,appid,name,status';
        $outappid = $this->selectwhereformysql($tablename2,$field2,$selectwhere2);        //检测游戏
        $outappid = $outappid['0'];
        $outletsinfo = $outletsinfo['0'];
        if($this->extData==null){
            $nameextData='自定义参数为空';
            $nameextData2= rawurlencode(mb_convert_encoding($nameextData, 'utf-8', 'utf-8'));
            $nameextData3=  urldecode($nameextData2);
            echo $nameextData3;
            exit();    
        }
        if(isset($this->egt)){
            $nameegt1='营运商不正确';
            $arrnameegt1= rawurlencode(mb_convert_encoding($nameegt1, 'utf-8', 'utf-8'));
            $arrnameegt1=  urldecode($arrnameegt1);
            $nameegt2=  '营运商不能为空';
            $arrnameegt2 = rawurlencode(mb_convert_encoding($nameegt2, 'utf-8', 'utf-8' ));
            $arrnameegt2=  urldecode($arrnameegt2);
            switch($this->egt)
            {
                case 1:         
                    $this->egt=1;
                    break;
                case 2: 
                    $this->egt=2;
                    break;
                case 3:
                    $this->egt=3;
                    break;
                case 4:
                    $this->egt=4;
                    break;
                default:  exit ($arrnameegt1);
            }           
        }
        
        if($outappid['status'] == 2 ||$outappid==null)
        {
            $nameappid='游戏关闭或游戏不匹配';
            $nameappid2= rawurlencode(mb_convert_encoding($nameappid, 'utf-8', 'utf-8'));
            $nameextData3=  urldecode($nameappid2);
            echo $nameextData3;//游戏关闭或者没有游戏
            exit();
        }
        if($outletsinfo['status'] == 2 || $outletsinfo==null)
        {
            $nameletsinfo='道具关闭或道具不匹配';
            $nameletsinfo2= rawurlencode(mb_convert_encoding($nameletsinfo, 'utf-8', 'utf-8'));
            $nameletsinfo3=  urldecode($nameletsinfo2);
            echo $nameletsinfo3;//道具关闭或者没有游戏
            exit();
        }

    }
    
    
    /**
     * Summary of cocheck_co    检测厂商信息
     * @return mixed
     */
    public function cocheck_co()
    {
        $selectwhere = array('coid'=>$this->coid);
        $colist = M('colist')->field('status,appid,coid')->where($selectwhere)->select();
        //  $codelist = M('pseudo')->field('status')->where($selectwhere)->select();
       // print_r( M('colist')->_sql());
        $colist = $colist[0];
        if($colist['status'] != 1)
        {
            $nameconame='厂商关闭';
            $nameconame1= rawurlencode(mb_convert_encoding($nameconame, 'utf-8', 'utf-8'));
            $nameconame2=  urldecode($nameconame1);
            echo $nameconame2;
            exit();
        }
        return $colist;
    }
    
    /**
     * Summary of cocheck_co    检测游戏配置厂商信息
     * @return mixed
     */
    public function cogamecheck_co()
    {
        $selectwhere = array('coid'=>$this->coid,'appid'=>$this->appid);
        $colist = M('cogameset')->field('status,appid,coid')->where($selectwhere)->select();
        //  $codelist = M('pseudo')->field('status')->where($selectwhere)->select();
        // print_r( M('colist')->_sql());
        $colist = $colist[0];
        if($colist['status'] != 1)
        {
            $nameconame='厂商关闭';
            $nameconame1= rawurlencode(mb_convert_encoding($nameconame, 'utf-8', 'utf-8'));
            $nameconame2=  urldecode($nameconame1);
            echo $nameconame2;
            exit();
        }
        return $colist;
    }
    /**
     * Summary of check_code    检测code信息
     * @return mixed
     */
    //public function check_code()
    //{
    //    $selectwhere = array('utf8name'=>$this->code);
    //    $codelist = M('codecontrol')->field('status,appid,coid')->where($selectwhere)->select();
    //  //  $codelist = M('pseudo')->field('status')->where($selectwhere)->select();

    //    $codelist = $codelist[0];
    //    if($codelist['status'] != 1)
    //    {
    //        $this->requesterrforid(1006,'CodeClose');
    //    }
    //    return $codelist;
    //}
    //第二步骤数据检测
    public function checkData_order2($params)
    {
        $isgetinfo = false;
        foreach($params as $k=>$value)
        {
            if($value == null)
            {
                if($k == 0)
                    $this->requesterrforid(1003,'missparameters');
                if($k > 1)
                {
                    $isgetinfo = true;
                }
            }
        }
        return $isgetinfo;
    }
    /**
     * Summary of checkData 检测参数
     * @param mixed $params 
     */
    public function checkData($params)
    {
        foreach($params as $value)
        {
            if($value == null)
            {   
                $this->cpconame=$this->coid;
                $this->requesterrforid(1003,'missparameters');
            }
        }
        $selectwhere = array('imsi'=>$this->imsi);
        $model = M('mobilelist');
        //G('begin');
        $phoneinfo = $model->field('mobile,status')->where($selectwhere)->select();
        //$sql = $model->_sql();
        //G('end');
        //$time =  G('begin2','end',10);
        //////var_dump($sql);
        //////var_dump($time);
        if($phoneinfo != null)
        {
            if($this->nomobile == true)
                $this->mobile = $phoneinfo[0]['mobile'];
            $mobilestatus = $phoneinfo[0]['status'];
            if($mobilestatus == 2)
            {
                $this->requesterrforid(9999,'sorry!error mobile');
            }
        }
        else
        {
            if($this->mobile != null && $this->imsi != null)
            {
                $imsilen = strlen($this->imsi);
                $mobilelen = strlen($this->mobile);
                if($imsilen === 15 && $mobilelen===11)
                {
                    $data = array('imsi'=>$this->imsi,'imei'=>$this->imei,'city_id'=>$this->iccid_city_id,'city'=>$this->iccid_city,'os_model'=>$this->os_model,'mark'=>$this->mark,'mobile'=>$this->mobile);
                    $model->data($data)->add();
                }
            }
        }
    }
    
    //广告参数的检测
    public function adv_params($params){
          foreach($params as $value)
                {
                    if($value == null)
                    {
                        echo ('参数不全');
                        exit;
                    }
                }
    } 
    /**
     * Summary of check_codepattern 检测厂商对应的通道池是否自动化
     */
    public function check_codepattern()
    {
        if($this->isreques == false)
        {
                if($this->coinfo['telestatus'] == 1)    //厂商是否通道池的状态   //通道池自动化
                {   
                    $telecompools = M('telecompools')->field('name,egt')->where(array('id'=>$this->telecompool))->select();                
                    if($this->egt==$telecompools['0']['egt'])
                    {
                        if($this->telecompool != 0)
                        {
                            $this->codepattern = 'telecompool'; 
                            return;
                        }
                     }else
                    {
                        $this->requesterrforid('1010','sorry!error egt_telecompools');  
                    }   
              
                }
                else if($this->coinfo['telestatus'] == 2)  //指定通道自动化
                {
                    $this->pass();
                }
                else   //全局通道
                {   
                    $this->wholepass();
                }
        }
        else
        {
            if($this->coinfo['telestatus'] == 1)    //厂商是否通道池的状态   //通道池自动化
            {   
                $telecompools = M('telecompools')->field('name,egt')->where(array('id'=>$this->telecompool))->select();                
                if($this->egt=$telecompools['0']['egt'])
                {
                    if($this->telecompool != 0)
                    {
                        $this->codepattern = 'telecompool'; 
                        return;
                    }
                }else
                {
                    $this->requesterrforid('1010','sorry!error egt_telecompools');  
                }   
                
            }
                else if($this->coinfo['telestatus'] == 2)  //指定通道自动化
                {
                    $this->pass();
                }
                else   //全局通道
                {   
                    $this->wholepass();
                }
            
        }

     
        //var_dump($this->pseudocodeinfo);
    }
    /**
     * Summary of adddataforsql    指定通道自动化
     * @param mixed $tablename 
     * @param mixed $adddata 
     * @param mixed $isend 
     * @param mixed $id 
     */
    public function pass(){
        switch($this->egt)
        {
            case 1:
                {
                    $this->egtname = '电信';
                    break;
                }
            case 2:
                {
                    $this->egtname = '联通';
                    break;
                }
            case 3:
                {
                    $this->egtname = '移动';
                    break;
                }
            default :
                    break;
        }
        $status = $this->priorityforoutlets_status[$this->egt];         //厂商编辑中和游戏所属通道的运营商（移动、电信、联通）优先级开关 1允许
        $telecominfo = M('telecom')->field('telecomname,egt')->select();  //查询出通道名称
        if($status == 1)
        {
            foreach($telecominfo as $k=>$v)
            {
                foreach($this->outletstelecom as $k2=>$v2)          //$this->outletstelecom 为厂商对对应的通道ID
                {
                    if($v['egt'] != $this->egt)
                    {
                        if($v2 == $v['telecomname'])
                        {
                            unset($this->outletstelecom[$k2]);
                        }
                    }
                }
            }
            if(count($this->outletstelecom) > 0)            //厂商对应通道
            {
                $this->codepattern = 'outletstelecom';      //指定通道自动化
            }
        }
    
    
    }
    
    /**
     * Summary of adddataforsql     全局自动化
     * @param mixed $tablename 
     * @param mixed $adddata 
     * @param mixed $isend 
     * @param mixed $id 
     */
    public function wholepass(){
        switch($this->egt)
        {
            case 1:
                {
                    $this->egtname = '电信';
                    break;
                }
            case 2:
                {
                    $this->egtname = '联通';
                    break;
                }
            case 3:
                {
                    $this->egtname = '移动';
                    break;
                }
        }
                
        $telecominfo = M('telecom')->field('egt')->where(array('egt'=>$this->egt))->select();  //查询出通道名称
     
          if(count($telecominfo) > 0)
            {
                $this->codepattern = 'at';
            }
        
    
    
    }  
    /**
     * Summary of adddataforsql     添加数据到数据库
     * @param mixed $tablename 
     * @param mixed $adddata 
     * @param mixed $isend 
     * @param mixed $id 
     */
    function adddataforsql($tablename='',$adddata='',$isend=false,&$id=0)
    {
        G('begin');
        $id = $this->model->table($tablename)->add($adddata);
        $a = $this->model->_sql();
        if($id !=0 || $id != false)
        {
            if($isend == true)
            {
                $this->model->commit();
            }
        }
        else
        {
            $this->model->rollback();
            echo('errdata');
            exit();
        }
        G('end');
        $time =  G('begin','end',10);
        //////var_dump($tablename.'----add:'.$time);
    }
    /**
     * Summary of updatewheredataforsql 更新数据到数据库
     * @param mixed $tablename 
     * @param mixed $selectwhere 
     * @param mixed $updatedata 
     * @param mixed $isend 
     * @param mixed $id 
     */
    function updatewheredataforsql(&$tablename = '',&$selectwhere,&$updatedata='',$isend='',&$id=0)
    {
        G('begin');
        $id = $this->model->table($tablename)->where($selectwhere)->data($updatedata)->save();
        $diaomao = $this->model->table($tablename)->_sql();
      //  var_dump($diaomao);
        $updatedata = array();
        if($id !== false )
        {
            if($isend == true)
            {
                $this->model->commit();
            }
        }
        else
        {
            $this->model->rollback();
            exit();
        }
        G('end');
        $time =  G('begin','end',10);
    }
    /**
     * Summary of selectwhereformysql       查询数据库数据
     * @param mixed $tablename 
     * @param mixed $fieldstr 
     * @param mixed $selectwhere 
     * @return mixed
     */
    function selectwhereformysql(&$tablename,$fieldstr,&$selectwhere)
    {
      //  $str = substr('0000123456',3,6);
      //  G('begin');
        $model = M($tablename);
        $info = $model->field($fieldstr)->where($selectwhere)->select();
     $a=$model->_sql();
      //  G('end');
     //   $time =  G('begin','end',10);
        //////var_dump($time);
        return $info;
    }
}
