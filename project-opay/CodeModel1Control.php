<?php
namespace Admin\Controller;
include_once"ModelNameList.php";
include_once"PublicData.php";
include_once'CodeBaseControl.php';
include_once"emailController.php";
include_once"RequestData.php";
/**
 * CodeModel1Control short summary.
 *
 * CodeModel1Control description.
 *
 * @version 1.0
 * @author 华亮
 */

class CodeModel1Control extends \CodeBaseControl
{
    public function CoData_once()
    {
        $this->cocheck_co(); //检测商家状态
        $selectwhere =array('coid'=>$this->coid);
        $tablename =  'colist';//厂商表
        $this->coinfo = $this->selectwhereformysql($tablename,'paypriority,name,user,passkey,people,time,type,coadd,cotel,contact,appid,coid,coop,appname,prioritytelecom_status,telecoms,dayup,telestatus,telecompool',$selectwhere);
        $this->coinfo = $this->coinfo['0'];
        $this->updayvalueforoutlets = $this->coinfo['dayup']; //厂家日限              
        $this->telecompool = $this->coinfo['telecompool']; //通道池
        $this->paypriorityarr = $this->coinfo['paypriority'];  //第三方优先级 1是第三方优先  2是短代优先
        $paypriority_status = $this->paypriorityarr;
        $this->paypriority_status = explode('_',$paypriority_status); 
        $this->paypriority = $this->paypriority_status[$this->egt];       //运营商优先级 1为第三方优先
        $telecoms= explode('_',$this->coinfo['telecoms']);
        unset($telecoms['0']);          
        $appid= explode('_',$this->coinfo['appid']); 
        unset($appid['0']);         
        foreach($appid as $k=>$value){
             if($value==$this->appid){
                 $this->cpgamename = $value; //游戏ID
                }
        }           
        $this->cpconame = $this->coinfo['coid'];    //对应厂商表的ID 对应的名
        $selectwhere2 = array('propid'=>$this->propid); //原本为utf8name
        $tablename = 'proplist';//代码分配表 //道具表
        $this->pseudocodeinfo = $this->selectwhereformysql($tablename,'name,propid,appid,gold,user,status',$selectwhere2);   
        $this->pseudocodeinfo = $this->pseudocodeinfo['0'];
        $this->updayvalueforcode = $this->pseudocodeinfo['dayup']; //code日限(每天限定的code数量)       
        $this->fee = $this->pseudocodeinfo['gold']; //计费金额
        $this->cpgamepropname = $this->pseudocodeinfo['propid'];      //对应道具表的id
        $this->check_cogameinfo();        //检测厂家游戏信息
        $this->check_propinfo();        //检测信息
        $this->check_codepattern();      //检测通道池是否自动化
       
        if($this->codepattern =='at')  // 全局自动化 
        {
            $this->initdata_at();
        }
        else if($this->codepattern == 'outletstelecom')      // 厂商对应的通道自动化(指定通道)
        {           
            $this->initdata_outletstelecom();
        }
        else if($this->codepattern == 'telecompool')    //通道池自动化
        {
            $this->init_telecompool();
        }
        //else
        //{
        //    $this->initdata_mt();       //手动
        //}

        empty($this->gameno) &&$this->gameno = '';      //计费代码
        empty($this->gameid) && $this->gameid = '';     //通道代码
        empty($this->telecomtype) && $this->telecomtype = '';   //通道类型
        empty($this->updayvalue) && $this->updayvalue = '';     //计费日限
        empty($this->updayvaluefortelecom) && $this->updayvaluefortelecom = '';     //通道日限
        empty($this->codename) && $this->codename = '';     //计费代码名称
        empty($this->paycode) && $this->paycode = '';       //计费代码对应的ID
        empty($this->fee) && $this->fee = '';               //金额
        empty($this->telecomname) && $this->telecomname = '0';      //通道名称对应的ID
        empty($this->egt) && $this->egt = '';                   //运营商
        empty($this->cpgamename) &&$this->cpgamename = '';      //游戏名称
        empty($this->cpconame) && $this->cpconame = '';         //厂商名称
        empty($this->cpgamepropname) && $this->cpgamepropname = ''; //道具名称
    }
    //游戏分配检测
    public function CogameData_once()
    {
        $this->cogamecheck_co(); //检测商家状态
        $selectwhere =array('coid'=>$this->coid,'appid'=>$this->appid);
        $tablename =  'cogameset';//游戏分配厂商表
        $this->coinfo = $this->selectwhereformysql($tablename,'paypriority,time,type,appid,coid,appname,prioritytelecom_status,telecoms,dayup,telestatus,telecompool',$selectwhere);
        $this->coinfo = $this->coinfo['0'];
        $this->updayvalueforoutlets = $this->coinfo['dayup']; //厂家日限              
        $this->telecompool = $this->coinfo['telecompool']; //通道池       
        $this->paypriorityarr = $this->coinfo['paypriority'];  //第三方优先级 1是第三方优先  2是短代优先
        $paypriority_status = $this->paypriorityarr;
        $this->paypriority_status = explode('_',$paypriority_status); 
        $this->paypriority = $this->paypriority_status[$this->egt];     //运营商优先级
        $telecoms= explode('_',$this->coinfo['telecoms']);
        unset($telecoms['0']);            
        $this->cpgamename = $this->coinfo['appid'];  
        $this->cpconame = $this->coinfo['coid'];    //对应厂商表的ID 对应的名
        $selectwhere2 = array('propid'=>$this->propid); //原本为utf8name
        $tablename = 'proplist';//代码分配表 //道具表
        $this->pseudocodeinfo = $this->selectwhereformysql($tablename,'name,propid,appid,gold,user,status',$selectwhere2);   
        $this->pseudocodeinfo = $this->pseudocodeinfo['0'];
        $this->updayvalueforcode = $this->pseudocodeinfo['dayup']; //code日限(每天限定的code数量)       
        $this->fee = $this->pseudocodeinfo['gold']; //计费金额
        $this->cpgamepropname = $this->pseudocodeinfo['propid'];      //对应道具表的id
        $this->cogamecheck_cogameinfo();        //检测游戏分配信息
        $this->cogamecheck_propinfo();        //检测信息
        $this->check_codepattern();      //检测通道池是否自动化
        
        if($this->codepattern =='at')  // 全局自动化 
        {
            $this->initdata_at();
        }
        else if($this->codepattern == 'outletstelecom')      // 厂商对应的通道自动化
        {           
            $this->initdata_outletstelecom();
        }
        else if($this->codepattern == 'telecompool')    //通道池自动化
        {
            $this->init_telecompool();
        }
        empty($this->gameno) &&$this->gameno = '';      //计费代码
        empty($this->gameid) && $this->gameid = '';     //通道代码
        empty($this->telecomtype) && $this->telecomtype = '';   //通道类型
        empty($this->updayvalue) && $this->updayvalue = '';     //计费日限
        empty($this->updayvaluefortelecom) && $this->updayvaluefortelecom = '';     //通道日限
        empty($this->codename) && $this->codename = '';     //计费代码名称
        empty($this->paycode) && $this->paycode = '';       //计费代码对应的ID
        empty($this->fee) && $this->fee = '';               //金额
        empty($this->telecomname) && $this->telecomname = '0';      //通道名称对应的ID
        empty($this->egt) && $this->egt = '';                   //运营商
        empty($this->cpgamename) &&$this->cpgamename = '';      //游戏名称
        empty($this->cpconame) && $this->cpconame = '';         //厂商名称
        empty($this->cpgamepropname) && $this->cpgamepropname = ''; //道具名称
    }
    
   
    //通过code获取金额
    public function Tpaycode($coid2,$appid2,$propid2,$maxid,$operators2,$extData,$token,$appkey,$source,$seq,$sign,$method,$paramJson){
        $this->extData=$extData;
        if($this->extData==null){
            $this->extData='thzfbextdata';        
        }
        $this->maxid2=$maxid;       //短代对应的maxid
        $this->egt=$operators2;
        $this->token=$token;
        $this->appkey=$appkey;
        $this->source=$source;
        $this->seq=$seq;
        $this->sign=$sign;
        $this->method=$method;
        $this->paramJson=$paramJson;
        $this->coid=$coid2;
        $this->appid=$appid2;
        $this->propid=$propid2;
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';   
        $this->dateformonth = $this->dateformonth.'2'.'_';//格式如：2016_08_2_    根据当月获取当前时间格式，用于数据库表名
        $this->cocheck_co(); //检测商家状态
        $tablenamemaxid=$this->dateformonth.'vnorder';
        $selectwheremaxid =array('maxid'=>$this->maxid2);
        $this->coinfomaxid = $this->selectwhereformysql($tablenamemaxid,'bksid,imsi,imei,iccid_city,city',$selectwheremaxid);
        $this->coinfomaxid = $this->coinfomaxid['0'];
        $this->extDatatoomaxid=$this->coinfomaxid['bksid']; //短代的bksid
        $usercount['newuser'] = 0;
        $usercount['saleuser'] = 0;
        $usercount['daynewuser'] = 0;
        $usercount['daysaleuser'] = 0;
        $usercountupdate = M('usercount')->where(array('bksid'=>$this->extDatatoomaxid))->save($usercount);
        //print_r($a=M()->_sql());
        $this->imsi=$this->coinfomaxid['imsi'];
        $this->imei=$this->coinfomaxid['imei'];
        $this->iccid_city=$this->coinfomaxid['iccid_city'];
        if($this->iccid_city==null){
            $this->iccid_city='iccid_city';
        }
        $this->city=$this->coinfomaxid['city'];
        if($this->city==null){
            $this->city='city';
        }
        $selectwhereco = array('coid'=>$this->coid,'appid'=>$this->appid);
        $colistarr = M('cogameset')->field('status,appid,coid')->where($selectwhereco)->select();
        if($colistarr==null){
            $selectwhere =array('coid'=>$this->coid);
            $tablename =  'colist';//厂商表
            $this->coinfo = $this->selectwhereformysql($tablename,'tpaypool,name,user,passkey,people,time,type,coadd,cotel,contact,appid,coid,coop,appname,prioritytelecom_status,telecoms,dayup,telestatus,telecompool',$selectwhere);
            $this->coinfo = $this->coinfo['0'];
            $this->updayvalueforoutlets = $this->coinfo['dayup']; //厂家日限              
            $this->telecompool = $this->coinfo['tpaypool']; //通道池
            $telecoms= explode('_',$this->coinfo['telecoms']);
            unset($telecoms['0']);          
            $appid= explode('_',$this->coinfo['appid']); 
            unset($appid['0']);         
            foreach($appid as $k=>$value){
                if($value==$this->appid){
                    $this->cpgamename = $value; //游戏ID
                }
            }
        }else{
            $tablenamecigame='cogameset';
            $this->coinfo = $this->selectwhereformysql($tablenamecigame,'tpaypool,coname,appname,time,type,appid,coid,prioritytelecom_status,telecoms,dayup,telestatus,telecompool',$selectwhereco);
            $this->coinfo = $this->coinfo['0'];
            $this->updayvalueforoutlets = $this->coinfo['dayup']; //厂家日限              
            $this->telecompool = $this->coinfo['tpaypool']; //通道池
            $telecoms= explode('_',$this->coinfo['telecoms']);
            unset($telecoms['0']);  
            $this->cpgamename = $this->coinfo['appid'];
        }
        $this->cpconame = $this->coinfo['coid'];    //对应厂商表的ID 对应的名
        $selectwhere2 = array('propid'=>$this->propid); //原本为utf8name
        $tablename = 'proplist';//代码分配表 //道具表
        $this->pseudocodeinfo = $this->selectwhereformysql($tablename,'name,propid,appid,gold,user,status',$selectwhere2);   
        $this->pseudocodeinfo = $this->pseudocodeinfo['0'];      
        $this->fee = $this->pseudocodeinfo['gold']; //计费金额
        $this->cpgamepropname = $this->pseudocodeinfo['propid'];      //对应道具表的id
        $dataarr='thzfb';
        $this->codepattern='telecompool';
        $this->initstatus=3;
        $this->init_telecompool($dataarr); //通道池自动化
    }
    

 
    
    public function init_atpublic(&$iccid_city_id='',&$paycodemodel=array())
    {   
        if($this->codepattern !=='telecompool')
        {
            $model = M('paycodelist');
            //第一步为false 第二步为true
            if($this->isreques == true)
            {
                $paycodemodel= $model->where(array('payglod'=>$this->fee,'egt'=>$this->egt,'telecomtype'=>$this->telecomtype))->select();
            }
            else
            {
                $paycodemodel=  $model->where(array('payglod'=>$this->fee,'egt'=>$this->egt))->select();
            }
        }
        foreach($paycodemodel as $k=>$value)
        {
            if($value['status'] == 2 || $value['status'] == 3)
            {
                unset($paycodemodel[$k]);
            }
        }
        if(count($paycodemodel) == 0)
        {
            $this->paycodeapi='1001';
            $this->requesterrforid(1001,'PayClose');
        }
        //请求类型，一次两次
        //if($this->requesttype != 0)
        //{
        //    foreach($paycodemodel as $k=>$value)
        //    {
        //        if($value['resulttype'] != $this->requesttype)
        //        {
        //            unset($paycodemodel[$k]);
        //        }
        //    }
        //}
        //if(count($paycodemodel) == 0)
        //{
        //    $this->requesterrforid(1007,'request no telecom');
        //}
        //省份判断
        if($this->isreques == false)
        {
            $iccid_city_id = $this->iccid_city_id;      //   iccid获取到的省份ID
            if($this->iccid_city_id == null || $this->iccid_city_id ==0)
            {
                $iccid_city_id = 32;
                $this->city_id  = 32;
                foreach(\PublicData::$city as $value)
                {
                    
                    if($this->city == $value['city'])
                    {
                        $this->city_id = $value['id'];
                        $iccid_city_id  = $value['id'];
                    }
                }
            }
            foreach($paycodemodel as $k=>$value)
            {
                $provinces = $value['provinces'];//通道的屏蔽的省份
                $provinces = explode('_',$provinces);
                foreach($provinces as $value2)
                {
                    if($value2 == $iccid_city_id)   //iccid省份
                    {        
                        $temparray2 = array();
                        foreach($paycodemodel as $k2=>$value2)
                        {
                            foreach($this->outletstelecom as $k=>$value)
                            {
                                if($value === $value2['telecomname'])
                                {
                                    $temparray2 = $value2;
                                                    $city = $this->city; //SKD过来的城市
                                                    foreach(\publicdata::$city as $k=>$v)
                                                    {
                                                        if($v['city'] == $city)
                                                        {
                                                            $city_id = $v['id'];    //SKD过来的城市
                                                        }                       
                                                    }
                                                    if($city_id==''){
                                        
                                                        foreach(\publicdata::$ydmm2citylist as $k=>$v)
                                                        {
                                                            if($v['city'] == $city)
                                                            {
                                                                $city_id = $v['sx'];        //SKD过来的城市
                                                            }                        
                                                        }                       
                                                    }
                                              foreach($paycodemodel as $k=>$v4)
                                             {     
                                                    foreach($this->outletstelecom as $k=>$v5)
                                                   {
                                                          if($v5 === $v4['telecomname'])
                                                          {
                                                                $provi2 = $v4['provinces'];//通道的屏蔽的省份
                                                                  $provi2 = explode('_',$provi2);
                                                                 foreach($provi2 as $v6)
                                                                {
                                                                      if($v6 == $city_id)
                                                                    {                             
                                                                        $this->cityskd=$v6;         //  SDK过来的省份
                                                                       // $this->requesterrforid(1002,'SDKCtiyERR');//省份屏蔽
                                                                    }
                                                                  }
                                                          }
                                                    } 
                                            }
                                    unset($temparray2);
                                     foreach($paycodemodel as $k2=>$v1)
                                           {
                                                  foreach($this->outletstelecom as $k=>$v2)
                                                   {
                                                      if($v2 === $v1['telecomname'])
                                                      {
                                                          $provi1 = $v1['provinces'];//通道的屏蔽的省份
                                                          $provi1 = explode('_',$provi1);
                                                          foreach($provi1 as $v3)
                                                         {
                                                              if($v3 == $iccid_city_id)   //iccid省份
                                                             { 
                                                                       $this->CtiyERR = $iccid_city_id;             
                                                                       $this->requesterrforid(1002,'CtiyERR');//省份屏蔽
                                                              }
                                                         }
                                                      }
                                                  }
                                          }
                                }
                            }
                        }
                        unset($paycodemodel[$k]);
                    }
                }
            } 
            if(count($paycodemodel) == 0)
            {
               // echo('CtiyERR');
                $this->CtiyERR = $iccid_city_id;        //ICCID过来的省份
                $this->requesterrforid(1002,'CtiyERR');//省份屏蔽
            }
        }
        //手机号
        $needmobiletypelist = \PublicData::$needmobiletypelist;            
        $temppaycodemodel = $paycodemodel;
        if($this->nomobile == true)
        {
            foreach($temppaycodemodel as $k=>$value)
            {
                foreach($needmobiletypelist as $value2)
                {
                    //      $temppaycodemodel['telecomtype'] = $value;
                    if($value['telecomtype'] == $value2)
                    {
                        unset($temppaycodemodel[$k]);
                    }
                }
            }
        }
        if(count($temppaycodemodel) != 0)
        {
            $paycodemodel = $temppaycodemodel;
        }
        else
        {
            $this->requesterrforid(-3,'NoMobile');
      
        }
        return $paycodemodel;
    }
    
    public function init_atpublic3(&$iccid_city_id='')          //厂家对应通道
    {
        if($this->codepattern !=='telecompool')
        {
            $model = M('paycodelist');
            if($this->isreques == true)
            {
                $paycodemodel= $model->where(array('payglod'=>$this->fee,'egt'=>$this->egt,'telecomtype'=>$this->telecomtype))->select();
            }
            else
            {
                $paycodemodel=  $model->where(array('payglod'=>$this->fee,'egt'=>$this->egt))->select();
            }
        }
        foreach($paycodemodel as $k=>$value)
        {
            if($value['status'] == 2 || $value['status'] == 3)
            {
                unset($paycodemodel[$k]);
            }
        }
        if(count($paycodemodel) == 0)
        {
            $this->requesterrforid(1001,'PayClose');
        }
       
        //省份判断
        if($this->isreques == false)
        {
            $iccid_city_id = $this->iccid_city_id;
            if($this->iccid_city_id == null || $this->iccid_city_id ==0)
            {
                $iccid_city_id = 32;
                $this->city_id  = 32;
                foreach(\PublicData::$city as $value)
                {
                    
                    if($this->city == $value['city'])
                    {
                        $this->city_id = $value['id'];
                        $iccid_city_id  = $value['id'];
                    }
                }
            }
            foreach($paycodemodel as $k=>$value)
            {
                $provinces = $value['provinces'];//通道的屏蔽的省份
                $provinces = explode('_',$provinces);
                foreach($provinces as $value2)
                {
                    if($value2 == $iccid_city_id)
                    {
                        unset($paycodemodel[$k]);
                    }
                }
            } 
            if(count($paycodemodel) == 0)
            {
                $this->requesterrforid(1002,'CtiyERR');//省份屏蔽
            }
        }
        //手机号
        $needmobiletypelist = \PublicData::$needmobiletypelist;            
        $temppaycodemodel = $paycodemodel;
        if($this->nomobile == true)
        {
            foreach($temppaycodemodel as $k=>$value)
            {
                foreach($needmobiletypelist as $value2)
                {
                    //      $temppaycodemodel['telecomtype'] = $value;
                    if($value['telecomtype'] == $value2)
                    {
                        unset($temppaycodemodel[$k]);
                    }
                }
            }
        }
        if(count($temppaycodemodel) != 0)
        {
            $paycodemodel = $temppaycodemodel;
        }
        else
        {
            $this->requesterrforid(-3,'NoMobile');
            if($this->bksen == 'bksen')
            {
                exit();

            }
        }
        return $paycodemodel;
    }
    
    public function init_atpublic2($paycodemodel = array(),$ran='')
    {
        //最后选择
        $arrylen = count($paycodemodel);
        if($arrylen != 0)
        {
            if($arrylen > 1)
            {
                if($this->isreques == false)
                {
                    $key = mt_rand(0,$arrylen - 1);
                    $paycodemodel = $paycodemodel[$key];
                }
                else
                {
                    $tablename = $this->dateformonth.'vnorder';
                    $selectwhere = array('id'=>$this->maxid);
                    $codename = M($tablename)->field('paycode')->where($selectwhere)->select();   
                    $paycode = $codename['0']['paycode'];
                    foreach($paycodemodel as $k=>$value)
                    {
                        if($value['paycodename'] != $paycode)
                        {
                            unset($paycodemodel[$k]);
                        }
                        else
                        {
                            $paycodemodel = $paycodemodel[$k];
                        }
                    }
                }
            }
            else if($this->codepattern == 'telecompool' ||$this->codepattern=='outletstelecom')
            {
                $paycodemodel = $paycodemodel['0'];
            }else{
                $paycodemodel = $paycodemodel[$ran];
            }
        }
        else
        {
            $this->paycodeapi='1001';
            $this->requesterrforid(1008,'telecomprovinceClose');
        }
        $this->paycodemodel =$paycodemodel;
            $city = $this->city; //SKD过来的城市
            foreach(\publicdata::$city as $k=>$v)
            {
                if($v['city'] == $city)
                {
                    $city_id = $v['id'];
                }                       
            }
            if($city_id==''){
            
                foreach(\publicdata::$ydmm2citylist as $k=>$v)
                {
                    if($v['city'] == $city)
                    {
                        $city_id = $v['sx'];
                    }                        
                }                       
            }
            foreach($paycodemodel['provinces'] as $value3)
            {
                if($value3 == $city_id)
                {                             
                    $this->cityskd=$value3;         //  SDK过来的省份
                }
            }
        $this->paycode =$paycodemodel['paycodename']|'';
        $gamepropname = $paycodemodel['news'];
        if($gamepropname == null)
            $gamepropname = '0_0';
        $gamepropname = explode('_',$gamepropname);
        $this->gamename = $gamepropname['0'];
        $this->propname = $gamepropname['1'];

        $this->fee = $this->fee / 100;
        $this->telecomname = $paycodemodel['telecomname'];
        $this->gameno = $paycodemodel['paycode'];   //计费代码的0 在下划线左边gameno['0']表示指令  //计费代码中的右边为1 表示上行端口（号码）
        $this->gameno = explode('_',$this->gameno); 
        $this->gameid = $paycodemodel['telecomcode']; //通道代码
        $this->telecomtype = $paycodemodel['telecomtype'];  //通道类型
        $this->updayvalue = $paycodemodel['dayupvalue']; //计费日限
        $this->updayvaluefortelecom = $paycodemodel['dayupvaluefortelecom'];  //通道日限
        $this->codename = $paycodemodel['utf8name']; //计费代码中文名字
        $uft8name  = M('telecomlist')->where(array('id'=>$this->telecomname))->select();
        $this->telecomnameutf8name = $uft8name['0']['name'];
        //$codetype = $this->telecomtype;
        //$needmobiletypelist = \PublicData::$needmobiletypelist;
        ////$len = strlen($this->mobile);

        //foreach($needmobiletypelist as $value)
        //{
        //    if($codetype == $value)
        //    {
        //        if($this->nomobile == true )
        //        {
        //            $this->requesterrforid(-3,'NoMobile');
        //            if($this->bksen == 'bksen')
        //                exit();
        //        }
        //    }
        //}
    }
    public function init_telecompool($htzfb='',$datahqddz='')
    {
        $iccid_city_id = 0;
        $model = M('paycodelist');
        if($this->isreques == true)
        {
            $paycodemodel= $model->where(array('payglod'=>$this->fee,'egt'=>$this->egt,'telecomtype'=>$this->telecomtype))->select();
        }
        else if($htzfb=='thzfb'){
            $paycodemodel=  $model->where(array('payglod'=>$this->fee,'egt'=>$this->egt))->select();
        }else if($datahqddz=='wxhqddz'){
            $paycodemodel=  $model->where(array('payglod'=>$this->fee*100,'egt'=>$this->egt))->select();
        }else {
            $paycodemodel=  $model->where(array('payglod'=>$this->fee,'egt'=>$this->egt))->select();
        }
        $telecompoolinfo = M('telecompools')->field('prioritys,telecoms,tpaytelecoms,wchartelecoms')->where(array('id'=>$this->telecompool))->select();
    
        $prioritys = $telecompoolinfo['0']['prioritys'];//通道池的优先级
        if($htzfb=='thzfb'){
            $telecoms = $telecompoolinfo['0']['tpaytelecoms']; 
        }elseif($datahqddz=='wxhqddz'){
            $telecoms = $telecompoolinfo['0']['wchartelecoms']; 
        
        }else{
             $telecoms = $telecompoolinfo['0']['telecoms'];  //通道池选中的通道
        }
        $telecoms = explode('_',$telecoms);
        $prioritys = explode('_',$prioritys);
        unset($telecoms['0']);
        unset($prioritys['0']);
        $telecoms_n = array();
        foreach($telecoms as $k=>$v)
        {
            $temp = array('telecomid'=>$v,'priority'=>$prioritys[$k]);
            $telecoms_n[] = $temp;
        }
        $telecoms = $telecoms_n;        //通道优先级和优先级
        $temp = array();
        
        foreach($telecoms_n as $k=>$v)
        {
            //$info = M('telecom')->where(array('id'=>$v['telecomid']))->select();
            //$v['telecomid'] = $info['0']['telecomname'];
            foreach($paycodemodel as $k2=>$v2)
            {
                if($v2['telecomname'] == $v['telecomid'])
                {
                    $v2['priority'] = $v['priority'];           //通道池的通道优先级 和计费代码的优先级
                    $temp[] = $v2;
                }
            }
        }
        $paycodemodel = $temp;
        if(count($paycodemodel) == 0)
        {
            $this->paycodeapi='1001';
            $this->requesterrforid('2000','Not Find Telecom For TelecomPool');
        }
        $paycodemodel = $this->init_atpublic($iccid_city_id,$paycodemodel);
        $ages = array();
        foreach ($paycodemodel as $user) {
            $args2 = explode('_',$user['prioritycity']); //城市优先级

            unset($args2['0']);
            if($iccid_city_id !=50 )
            {
                $ages[] = $args2[$iccid_city_id];
            }
        }
        array_multisort($ages, SORT_DESC, $paycodemodel);
        foreach($paycodemodel as $k=>$value)
        {
            $args2 = explode('_',$value['prioritycity']);
            unset($args2['0']);

            if($args2[$iccid_city_id] != $ages['0'])
            {
                unset($paycodemodel[$k]);
            }
        }
        $ages = array();
        foreach ($paycodemodel as $user) {
            $ages[] = $user['priority'];
        }
        array_multisort($ages, SORT_DESC, $paycodemodel);
        $temppaycodemodel = $paycodemodel['0'];
        foreach($paycodemodel as $k=>$value)
        {
            if($value['priority'] != $temppaycodemodel['priority'])
            {
                unset($paycodemodel[$k]);
            }
        }
        //if(count($paycodemodel)==0)
        //    $this->requesterrforid(1001,'TelecomErr');
        $this->init_atpublic2($paycodemodel);



    }
    /**
     * Summary of initdata_outletstelecom      厂商的通道指定模式
     */
    public function initdata_outletstelecom()
    {
        $iccid_city_id = 0;        
        $paycodemodel = $this->init_atpublic($iccid_city_id);
        $temparray = array();
        foreach($paycodemodel as $k2=>$value2)
        {
            foreach($this->outletstelecom as $k=>$value)
            {
                if($value === $value2['telecomname'])
                {
                    $temparray[] = $value2;
                }
            }
        }
        $paycodemodel = $temparray;
        if(count($paycodemodel) == 0)
        {
            $this->paycodeapi='1001';
            $this->requesterrforid(1001,'PayClose');        //计费代码状态错误
        }
        foreach($this->prioritytelecom as $k=>$v)
        {
            foreach($paycodemodel as $k2=>$v2)
            {
                if($v['0'] == $v2['telecomname'])
                {
                    $paycodemodel[$k2]['priority'] = $v[1];  //计费代码的优先级
                }
            }
        }
        $ages = array();
        foreach ($paycodemodel as $user) {
            $args2 = explode('_',$user['prioritycity']);    //屏蔽省份的优先级

            unset($args2['0']);
            if($iccid_city_id !=50 )
            {
                $ages[] = $args2[$iccid_city_id];       //取出当前通道的省份城市
            }
        }
        array_multisort($ages, SORT_DESC, $paycodemodel);
        foreach($paycodemodel as $k=>$value)
        {
            $args2 = explode('_',$value['prioritycity']);
            unset($args2['0']);

            if($args2[$iccid_city_id] != $ages['0'])
            {
                unset($paycodemodel[$k]);
            }
        }
        $ages = array();
        foreach ($paycodemodel as $user) {
            $ages[] = $user['priority'];
        }
        array_multisort($ages, SORT_DESC, $paycodemodel);
        $temppaycodemodel = $paycodemodel['0'];
        foreach($paycodemodel as $k=>$value)
        {
            if($value['priority'] != $temppaycodemodel['priority'])
            {
                unset($paycodemodel[$k]);
            }
        }
        $this->init_atpublic2($paycodemodel);


    }
    /**
     * Summary of initdata_mt      自动模式
     */
    public function initdata_at($codelist = array())
    {
    //    $this->pseudocodeinfo = $codelist;
        G('begin2');
        $iccid_city_id = 0;

        $paycodemodel = $this->init_atpublic($iccid_city_id);
        $ages = array();
        foreach ($paycodemodel as $user) {
            $args2 = explode('_',$user['prioritycity']);  //通道城市的优先级

            unset($args2['0']);
            if($iccid_city_id !=50 )
            {
                $ages[] = $args2[$iccid_city_id];
            }
        }
        array_multisort($ages, SORT_DESC, $paycodemodel);
        foreach($paycodemodel as $k=>$value)
        {
            $args2 = explode('_',$value['prioritycity']);
            unset($args2['0']);

            if($args2[$iccid_city_id] != $ages['0'])
            {
                unset($paycodemodel[$k]);
            }
        }
        $ages = array();
        foreach ($paycodemodel as $user) {
            $ages[] = $user['priority'];
        }
       array_multisort($ages, SORT_DESC, $paycodemodel);
        $mun=count($paycodemodel);
        $ran= mt_rand(0,$mun-1); //随机取数
        $temppaycodemodel = $paycodemodel[$ran];    
        foreach($paycodemodel as $k=>$value)
        {
           if($value['priority'] != $temppaycodemodel['priority'])
           {
               unset($paycodemodel[$k]);
           }
        }
        $this->init_atpublic2($paycodemodel,$ran);
  //      $arrylen = count($paycodemodel);
  //      if($arrylen != 0)
  //      {
  //          if($arrylen > 1)
  //          {
  //                  //$key = mt_rand(0,$arrylen - 1);
  //                  //$paycodemodel = $paycodemodel[$key];
  //              if($this->isreques == false)
  //              {
  //                  $key = mt_rand(0,$arrylen - 1);
  //                  $paycodemodel = $paycodemodel[$key];
  //              }
  //              else
  //              {
  //                  $tablename = $this->dateformonth.'vnorder';
  //                  $selectwhere = array('id'=>$this->maxid);
  //                  $codename = M($tablename)->field('paycode')->where($selectwhere)->select();   
  //                  $paycode = $codename['0']['paycode'];
  //                  foreach($paycodemodel as $k=>$value)
  //                  { 
  //                      if($value['paycodename'] != $paycode)
  //                      {
  //                          unset($paycodemodel[$k]);
  //                      }
  //                      else
  //                      {
  //                          $paycodemodel = $paycodemodel[$k];
  //                      }
  //                  }

  //              }
  //          }
  //          else
  //          {
  //              $paycodemodel = $paycodemodel['0'];
  //          }
  //      }
  //      else
  //      {
  //          $this->requesterrforid(1008,'telecomprovinceClose');
  //      }
  ////      $this->outletsname = $this->pseudocodeinfo['outlets'];

  //      $this->paycode =$paycodemodel['paycodename']|'0';
  //      $gamepropname = $paycodemodel['news']|'';
  //      if($gamepropname == null)
  //          $gamepropname = '0_0';
  //      $gamepropname = explode('_',$gamepropname);
  //      $this->gamename = $gamepropname['0'];
  //      $this->propname = $gamepropname['1'];
  //      $this->fee = $this->fee / 100;
  //      $this->telecomname = $paycodemodel['telecomname'];
  //      $this->gameno = $paycodemodel['paycode'];
  //      $this->gameid = $paycodemodel['telecomcode'];
  //      $this->telecomtype = $paycodemodel['telecomtype'];
  //      $this->updayvalue = $paycodemodel['dayupvalue']|'';
  //      $this->updayvaluefortelecom = $paycodemodel['dayupvaluefortelecom'];
  //      $this->codename = $paycodemodel['utf8name'];

  //      $uft8name  = M('telecomlist')->where(array('id'=>$this->telecomname))->select();
  //      $this->telecomnameutf8name = $uft8name['0']['name'];
  //      $this->gameno = explode('_',$this->gameno);
  //      $codetype = $this->telecomtype;
  //      $needmobiletypelist = \PublicData::$needmobiletypelist;
  //      $len = strlen($this->mobile);

  //      foreach($needmobiletypelist as $value)
  //      {
  //          if($codetype == $value)
  //          {
  //              if($this->nomobile == true )
  //              {
  //                  $this->requesterrforid(-3,'NoMobile');
  //                  if($this->bksen == 'bksen')
  //                      exit();
  //              }
  //          }
  //      }
  //      G('end2');
  //      $time =  G('begin2','end2',9);
        //var_dump($time);
    }
    
    
    /**
     * Summary of checktype  检测当前代码类型  如果没有返回false
     */
    
    public function checktype()
    {
        $codetype = $this->telecomtype;
           switch($codetype)
            {
                case 500012:
                    {
                        $this->iscodetype_jump = 2;
                        if($this->isreques == false)
                        {
                            $this->resultFortTelecomType500012();       //	博卡森电信
                            $this->codetelecomname = '	博卡森电信';
                            if( $this->codetypeforrequest == 2){
                                $this->resulet2 = 1;
                            }
                            }
                        else
                        {
                            $this->resultFortTelecomType500012_2();              //	博卡森电信
                            $this->codetelecomname = '	博卡森电信';
                        }
                        break;
                    }
                case 500011:
                    {
                        $this->iscodetype_jump = 2;
                        if($this->isreques == false)
                        {
                                $this->resultFortTelecomType500011();       //	博卡森联通
                                $this->codetelecomname = '	博卡森联通';
                                if($this->codetypeforrequest == 2){
                                       $this->resulet2 = 1;
                                    }
                            }
                        else
                        {
                            $this->resultFortTelecomType500011_2();              //	博卡森联通
                            $this->codetelecomname = '	博卡森联通';
                        }
                        break;
                    }
                case 500010:
                    {
                        $this->iscodetype_jump = 2;
                        if($this->isreques == false)
                        {
                                $this->resultFortTelecomType500010();       //	博卡森移动
                                $this->codetelecomname = '	博卡森移动';
                                if($this->codetypeforrequest == 2){
                                        $this->resulet2 = 1;
                                 }
                            }
                        else
                        {
                            $this->resultFortTelecomType500010_2();              //	博卡森移动
                            $this->codetelecomname = '	博卡森移动';
                        }
                        break;
                    }
                case 500004:
                    {
                        $this->iscodetype_jump = 1;
                        $this->resultFortTelecomType500004();             //时尚小沃3	
                        $this->codetelecomname = '时尚小沃3';
                        break; 
                    }
                case 500007:
                    {
                        $this->iscodetype_jump = 1;
                        $this->resultFortTelecomType500007();             //	小沃2	
                        $this->codetelecomname = '	小沃2';
                        break; 
                    }
                case 500008:
                    {
                        $this->iscodetype_jump = 1;
                        $this->resultFortTelecomType500008 ();             //	移动111
                        $this->codetelecomname = '移动111';
                        break; 
                    }
                case 500009:
                    {
                        $this->iscodetype_jump = 1;
                        $this->resultFortTelecomType500009 ();             //	电信222(测试通道电信1)
                        $this->codetelecomname = '电信222';
                        break; 
                    }
                case 500005:
                    {
                        $this->iscodetype_jump = 1;
                        $this->resultFortTelecomType500005();             //	天虎支付宝 
                        $this->codetelecomname = '天虎支付宝';
                        break;
                    }
                case 500006:
                    {
                        $this->iscodetype_jump = 1;
                        
                        $this->resultFortTelecomTyp500006();             //		欢趣微信支付 
                        $this->codetelecomname = '	欢趣微信支付';
                        break;
                    }
                case 500013:
                    {
                        $this->iscodetype_jump = 1;
                        
                        $this->resultFortTelecomTyp500013();             //			鱼鱼微信支付 
                        $this->codetelecomname = '鱼鱼微信支付';
                        break;
                    }
                case 500003:
                    {
                        $this->iscodetype_jump = 2;
                        if($this->isreques == false)
                        {
                                $this->resultFortTelecomType500003();       //美奇RDO
                                $this->codetelecomname = '美奇RDO';
                                $this->resulet2 = 1;
                            }
                        else
                        {
                            $this->resultFortTelecomType500003_2();              //美奇RDO
                            $this->codetelecomname = '美奇RDO';
                        }
                        break;
                    }
                case 146:
                    {
                        $this->iscodetype_jump = 1;
                        $this->resultFortTelecomType146();             //全盛空间	
                        $this->codetelecomname = '全盛空间';
                        break; 
                    }
                case 136:
                    {
                        $this->iscodetype_jump = 2;
                        if($this->isreques == false)
                        {
                                $this->resultFortTelecomType136();       //	修格RDO
                                $this->codetelecomname = '	修格RDO';
                                $this->resulet2 = 1;
                            }
                        else
                        {
                            $this->resultFortTelecomType136_2();              //	修格RDO
                            $this->codetelecomname = '	修格RDO';
                        }
                        break;
                    }
                
                case 138:
                    {
                        $this->iscodetype_jump = 1;
                        $this->resultFortTelecomType138();             //电信阅读
                        $this->codetelecomname = '电信阅读';
                        break;
                    }
                case 147:
                    {
                        $this->iscodetype_jump = 1;
                        $this->resultFortTelecomType147();             //摘星天翼阅读
                        $this->codetelecomname = '摘星天翼阅读';
                        break; 
                    }

                case 157:
                    {
                        $this->iscodetype_jump = 1;
                        $this->resultFortTelecomType157();             //修格信元手机报
                        $this->codetelecomname = ' 修格信元手机报';
                        break; 
                    }
                case 160:
                    {
                        $this->iscodetype_jump = 1;
                        $this->resultFortTelecomType160();             //中天信元
                        $this->codetelecomname = ' 中天信元';
                        break; 
                    }
                case 237:
                    {
                        $this->iscodetype_jump = 1;
                        $this->resultFortTelecomType237();             //竹雨MM
                        $this->codetelecomname = ' 竹雨MM';
                        break; 
                    }
                case 235:
                    {
                        $this->iscodetype_jump = 2;
                        if($this->isreques == false)
                        {
                                $this->resultFortTelecomType235();       //	联通包月
                                $this->codetelecomname = '	联通包月';
                                $this->resulet2 = 1;
                            }
                        else
                        {
                            $this->resultFortTelecomType235_2();              //	联通包月
                            $this->codetelecomname = '	联通包月';
                        }
                        break;
                    }
                case 234:
                    {
                        $this->iscodetype_jump = 1;
                        $this->resultFortTelecomType234();             //公用信息
                        $this->codetelecomname = ' 公用信息';
                        break; 
                    }
              
                default:
                {
                    break;
                }
            }
         
        if($codetype !== 14 && $codetype !== 22 && $codetype != 11)     //通道类型
        {
            $post_data_string = http_build_query($this->post_data);
            $post_data_string = urldecode($post_data_string);
            $this->send_url_data_string = $this->url.'?'.$post_data_string;
        }
    }
    
    //博卡森联通2
    public function resultFortTelecomType500011_2(){
        $this->post_data=array( 
                'code'=>$this->code,
                 'sid' =>$this->maxid,
                 'telecomtype' =>$this->telecomtype,
                 'mobile' =>$this->mobile,
                 'reserve'=>$this->extData,
                 'orderid'=>$this->sid
     );
        $a= http_build_query( $this->post_data);
        $b = 'http://120.76.75.199/Admin/Fun2/order2?'.$a;
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $b);
        
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        //执行命令
        $result = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        //$this->url = '120.76.75.199/index.php/Admin/Fun/order1';
        //$result= \JSHttpRequest::send_post_html22($this->url,$this->post_data);
        $this->result=$result;
        $result = json_decode($result,TRUE);       
        $smstype=$result['data'][0]['smstype'];   
        $status=$result['code'];
        $smscontent=$result['data'][0]['smscontent'];
        $smsport=$result['data'][0]['smsport'];
        $orderid=$result['orderid'];
        $sid=$result['sid'];
        $this->resulet2 = 0;        
        if($status!='0')
        {
            $this->resulet2 = 1;
        }
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    //博卡森联通
    public function resultFortTelecomType500011(){  
        $extarr= M('test')->field("nextval('bkslt') as id")->select();   //其中csqd 是可变的 根据规则命名
        $this->extData2= $this->extdatatoo($extarr);
        switch ( $this->fee*100)
        {
            case 3000:
                $code = '216120801030';
                break;  
            case 2500:
                $code = '216120801025';
                break;  
            case 2000:
                $code = '216120801020';
                break;  
            case 1600:
                $code = '216120801016';
                break;  
            case 1500:
                $code = '216120801015';
                break;  
            case 1200:
                $code = '216120801012';
                break;  
            case 1000:
                $code = '216120801010';
                break; 
            case 800:
                $code = '216120801008';
                break;  
            case 600:
                $code = '216120801006';
                break;  
            case 400:
                $code = '216120801004';
                break; 
            case 200:
                $code = '216120801002';
                break;   
        }
        $this->post_data=array( 
                  'imsi'=>$this->imsi,
                 'imei'=>$this->imei,
                 'mobile'=>$this->mobile,
                 'code'=>$code,
                 'iccid'=>$this->iccid,
                 'extData'=>$this->extDatatoo,
                 'ip'=>$this->ip
      );
        $this->code = $code;
        $a= http_build_query($this->post_data);
        $this->url='http://120.76.75.199/index.php/Admin/Fun/order1';
        $b = 'http://120.76.75.199/index.php/Admin/Fun/order1?'.$a;
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $b);
        
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        //执行命令
        $result = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        //$this->url = '120.76.75.199/index.php/Admin/Fun/order1';
        //$result= \JSHttpRequest::send_post_html22($this->url,$this->post_data);
        $this->result=$result;
        $result = json_decode($result,TRUE);       
        $smstype=$result['data'][0]['smstype'];   
        $status=$result['code'];
        $smscontent=$result['data'][0]['smscontent'];
        $smsport=$result['data'][0]['smsport'];
        if($result['data'][0]['telecomtype']){
            $this->telecomtype = $result['data'][0]['telecomtype'];
        }
        $orderid=$result['orderid'];
        $sid=$result['sid'];
        $this->codetypeforrequest = $result['data'][0]['codetypeforrequest'];
        if($result['data'][0]['codetypeforrequest']){
            $this->iscodetype_jump = $result['data'][0]['codetypeforrequest'];
        }
        $this->resulet2 = 0;        
        if($status!='0')
        {
            $this->resulet2 = 1;
        }
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }  
    
    //博卡森电信2
    public function resultFortTelecomType500012_2(){
        $this->post_data=array( 
                'code'=>$this->code,
                 'sid' =>$this->maxid,
                 'telecomtype' =>$this->telecomtype,
                 'mobile' =>$this->mobile,
                 'reserve'=>$this->extData,
                 'orderid'=>$this->sid
     );
        $a= http_build_query( $this->post_data);
        $b = 'http://120.76.75.199/Admin/Fun2/order2?'.$a;
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $b);
        
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        //执行命令
        $result = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        //$this->url = '120.76.75.199/index.php/Admin/Fun/order1';
        //$result= \JSHttpRequest::send_post_html22($this->url,$this->post_data);
        $this->result=$result;
        $result = json_decode($result,TRUE);       
        $smstype=$result['data'][0]['smstype'];   
        $status=$result['code'];
        $smscontent=$result['data'][0]['smscontent'];
        $smsport=$result['data'][0]['smsport'];
        $orderid=$result['orderid'];
        $sid=$result['sid'];
        $this->resulet2 = 0;        
        if($status!='0')
        {
            $this->resulet2 = 1;
        }
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    
    //博卡森电信
    public function resultFortTelecomType500012(){  
        $extarr= M('test')->field("nextval('bksdx') as id")->select();   //其中csqd 是可变的 根据规则命名
        $this->extData2= $this->extdatatoo($extarr);
        switch ( $this->fee*100)
            {
                    case 3000:
                    $code = '116120801030';
                      break;  
                        case 2500:
                    $code = '116120801025';
                      break;  
                        case 2000:
                    $code = '116120801020';
                      break;  
                        case 1600:
                    $code = '116120801016';
                      break;  
                        case 1500:
                    $code = '116120801015';
                      break;  
                        case 1200:
                    $code = '116120801012';
                      break;  
                        case 1000:
                    $code = '116120801010';
                      break; 
                        case 800:
                    $code = '116120801008';
                      break;  
                        case 600:
                    $code = '116120801006';
                      break;  
                        case 400:
                    $code = '116120801004';
                      break; 
                          case 200:
                    $code = '116120801002';
                      break;  
                          case 10:
                    $code = '1161208010001';
                      break;  
            }
        $this->post_data=array( 
                  'imsi'=>$this->imsi,
                 'imei'=>$this->imei,
                 'mobile'=>$this->mobile,
                 'code'=>$code,
                 'iccid'=>$this->iccid,
                 'extData'=>$this->extDatatoo,
                 'ip'=>$this->ip
      );
        $this->code = $code;
        $a= http_build_query( $this->post_data);
        $b = 'http://120.76.75.199/index.php/Admin/Fun/order1?'.$a;
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $b);
        
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        //执行命令
        $result = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        //$this->url = '120.76.75.199/index.php/Admin/Fun/order1';
        //$result= \JSHttpRequest::send_post_html22($this->url,$this->post_data);
        $this->result=$result;
        $result = json_decode($result,TRUE);       
        $smstype=$result['data'][0]['smstype'];   
        $status=$result['code'];
        $smscontent=$result['data'][0]['smscontent'];
        $smsport=$result['data'][0]['smsport'];
        $orderid=$result['orderid'];
        $sid=$result['sid'];
        $this->codetypeforrequest = $result['data'][0]['codetypeforrequest'];
        if($result['data'][0]['codetypeforrequest']){
            $this->iscodetype_jump = $result['data'][0]['codetypeforrequest'];
        }
        
        if($result['data'][0]['telecomtype']){
            $this->telecomtype = $result['data'][0]['telecomtype'];
        }
        $this->resulet2 = 0;        
        if($status!='0')
        {
            $this->resulet2 = 1;
        }
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }  
    
    //博卡森移动2
    public function resultFortTelecomType500010_2(){
        $this->post_data=array( 
                'code'=>$this->code,
                 'sid' =>$this->maxid,
                 'telecomtype' =>$this->telecomtype,
                 'mobile' =>$this->mobile,
                 'reserve'=>$this->extData,
                 'orderid'=>$this->sid
     );
        $a= http_build_query( $this->post_data);
        $b = 'http://120.76.75.199/Admin/Fun2/order2?'.$a;
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $b);
        
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        //执行命令
        $result = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        //$this->url = '120.76.75.199/index.php/Admin/Fun/order1';
        //$result= \JSHttpRequest::send_post_html22($this->url,$this->post_data);
        $this->result=$result;
        $result = json_decode($result,TRUE);       
        $smstype=$result['data'][0]['smstype'];   
        $status=$result['code'];
        $smscontent=$result['data'][0]['smscontent'];
        $smsport=$result['data'][0]['smsport'];
        $orderid=$result['orderid'];
        $sid=$result['sid'];
        $this->resulet2 = 0;        
        if($status!='0')
        {
            $this->resulet2 = 1;
        }
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    //博卡森移动
    public function resultFortTelecomType500010(){
        $extarr= M('test')->field("nextval('bksyd') as id")->select();   //其中csqd 是可变的 根据规则命名
        $this->extData2= $this->extdatatoo($extarr);
        switch ( $this->fee*100)
        {
            case 3000:
                $code = '316120801030';
                break;  
            case 2500:
                $code = '316120801025';
                break;  
            case 2000:
                $code = '316120801020';
                break;  
            case 1600:
                $code = '316120801016';
                break;  
            case 1500:
                $code = '316120801015';
                break;  
            case 1200:
                $code = '316120801012';
                break;  
            case 1000:
                $code = '316120802010';
                break; 
            case 800:
                $code = '316120802010';
                break;  
            case 600:
                $code = '316120801006';
                break;  
            case 400:
                $code = '316120801004';
                break; 
            case 200:
                $code = '316120801002';
                break;  
        }
        $this->code = $code;
        $this->post_data=array( 
                  'imsi'=>$this->imsi,
                 'imei'=>$this->imei,
                 'mobile'=>$this->mobile,
                 'code'=>$code,
                 'iccid'=>$this->iccid,
                 'extData'=>$this->extDatatoo,
                 'ip'=>$this->ip
      );
        $a= http_build_query( $this->post_data);
        $b = 'http://120.76.75.199/index.php/Admin/Fun/order1?'.$a;
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $b);
        
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        //执行命令
        $result = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        //$this->url = '120.76.75.199/index.php/Admin/Fun/order1';
        //$result= \JSHttpRequest::send_post_html22($this->url,$this->post_data);
        $this->result=$result;
        $result = json_decode($result,TRUE);       
        $smstype=$result['data'][0]['smstype'];   
        $status=$result['code'];
        $smscontent=$result['data'][0]['smscontent'];
        $smsport=$result['data'][0]['smsport'];
        $orderid=$result['orderid'];
        $sid=$result['sid'];
        $this->codetypeforrequest = $result['data'][0]['codetypeforrequest'];
        if($result['data'][0]['codetypeforrequest']){
            $this->iscodetype_jump = $result['data'][0]['codetypeforrequest'];
        }
        if($result['data'][0]['telecomtype']){
            $this->telecomtype = $result['data'][0]['telecomtype'];
        }
      //  $this->telecomtype = $result['data'][0]['telecomtype'];
        $this->resulet2 = 0;        
        if($status!='0')
        {
            $this->resulet2 = 1;
        }
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    
    
    
    
    //公用信息
    public function resultFortTelecomType234(){
        $name1=$this->gamename;
        $name2=$this->propname;
        $arrname1= rawurlencode(mb_convert_encoding($name1, 'utf-8', 'utf-8'));  // urlencode(mb_convert_encoding($name1, 'utf-8', 'utf-8'));// 防止urlencode转码是出现乱码     //urlencode($name1); 直接转urlencode编码      
        $arrname2=  rawurlencode(mb_convert_encoding($name2, 'utf-8', 'utf-8')); // urlencode(mb_convert_encoding($str, 'utf-8', 'utf-8'));             //urlencode($str);
        $extarr= M('test')->field("nextval('gyxx') as id")->select();   //其中csqd 是可变的 根据规则命名
        $this->extData2= $this->extdatatoo($extarr);
        
        $this->post_data=array( 
                 'ver'=>'1.0',
                 'channel'=>'2036001001',
                 'imsi'=>$this->imsi,
                 'imei'=>$this->imei,
                 'mobile'=>$this->mobile,
                 'apName'=>'蓝贝',
                 'appName'=>$this->gamename,
                 'chargeName'=>$this->propname,
                 'chargeType'=>'1',
                 'orderId'=>$this->extData2,
                 'price'=>$this->fee*100,
                 'timestamp'=>time(),
      );
        $data = $this->post_data;
        ksort($data);
        $sig .= 'apName'.$data['apName'];
        $sig .= 'appName'.$data['appName'];
        $sig .= 'channel'.$data['channel'];
        $sig .= 'chargeName'.$data['chargeName'];
        $sig .= 'chargeType'.$data['chargeType'];
        $sig .= 'imei'.$data['imei'];
        $sig .= 'imsi'.$data['imsi'];
        $sig .= 'mobile'.$data['mobile'];
        $sig .= 'orderId'.$data['orderId'];
        $sig .= 'price'.$data['price'];
        $sig .= 'timestamp'.$data['timestamp'];
        $sig .= 'ver'.$data['ver'];
        $usig = urlencode($sig);
        $udata = sha1($usig.$channelkey);
        $data['sig'] = $udata;
        $this->url = 'http://ylcjf.tyfo.com:9003/API.RequestPay';
        $result= \JSHttpRequest::send_post($this->url,$data);       //
        $this->result=$result;
        $result = json_decode($result,ture);       
        $smstype='text'; 
        //状态
        $status=$result['resultCode'];
        //下行端口
        $smsport=$result['smsNum'];
        //下行参数
        $smscontent=$result['smsContent'];
        $youshu=$result['extinfo'];
        //服务端交易号
        $orderid=$result['pid'];
        $this->resulet2 = 0;        
        if($status!='0')
        {
            $this->resulet2 = 1;
        }
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    
    //承天联通包月
    public function resultFortTelecomType235_2(){
        $sid = $this->maxid;
        
        $table = $this->dateformonth.'order1data';
        $extdata = M($table)->field('exdata')->where("orderid='".$sid."'")->select();
        $this->post_data=array( 
                 'linkid'=>$extdata[0]['exdata'],
                 'type'=>'11',
                 'merchantid'=>'SZLB',
                 'vcode'=>$this->extData,
                 'code'=>'91'
      );
        $this->url = 'http://120.26.132.220:7980/codeout/order2.do';
        $result= \JSHttpRequest::send_post_html22($this->url,$this->post_data);       //
        $this->result=$result;
        $result = json_decode($result,ture);       
        $smstype='text';   
        //返回结果
        $status=$result['failcode'];
        $smsport=$result['spnumber1'];
        $smscontent=$result['extinfo'];
        $this->resulet2 = 0;        
        if($status!='0')
        {
            $this->resulet2 = 1;
        }
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    //承天联通包月
    public function resultFortTelecomType235(){
        $name1=$this->gamename;
        $name2=$this->propname;
        $arrname1= rawurlencode(mb_convert_encoding($name1, 'utf-8', 'utf-8'));  // urlencode(mb_convert_encoding($name1, 'utf-8', 'utf-8'));// 防止urlencode转码是出现乱码     //urlencode($name1); 直接转urlencode编码      
        $arrname2=  rawurlencode(mb_convert_encoding($name2, 'utf-8', 'utf-8')); // urlencode(mb_convert_encoding($str, 'utf-8', 'utf-8'));             //urlencode($str);
        $extarr= M('test')->field("nextval('ctltby') as id")->select();   //其中csqd 是可变的 根据规则命名
        $this->extData2= $this->extdatatoo($extarr);
        $this->post_data=array( 
                 'linkid'=>$this->extData2,
                 'type'=>'11',
                 'merchantid'=>'SZLB',
                 'code'=>'91',
                 'price'=>$this->fee*100,
                 'phone'=>$this->mobile,
                 'imsi'=>$this->imsi,
                 'imei'=>$this->imei,
                 'iccid'=>$this->iccid,
                 'ip'=>$this->ip,
                 'company'=>'BKS',
                 'gamename'=>$arrname1,
                 'feename' => $arrname2,
                 'cpparam'=>''
      );
        
        $this->url = 'http://120.26.132.220:7980/codeout/order1.do';
        $result= \JSHttpRequest::send_post_html22($this->url,$this->post_data);       //
        $this->result=$result;
        $result = json_decode($result,ture);       
        $smstype='text';   
        $status=$result['failcode'];
        $smsport=$result['spnumber1'];
        $smscontent=$result['sms1'];
        $youshu=$result['extinfo'];
        $orderid=$result['orderId'];
        $this->resulet2 = 0;        
        if($status!='0')
        {
            $this->resulet2 = 1;
        }
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    
    //竹雨MM
    public function resultFortTelecomType237(){
        $name1=$this->gamename;
        $name2=$this->propname;
        $arrname1= rawurlencode(mb_convert_encoding($name1, 'utf-8', 'utf-8'));  // urlencode(mb_convert_encoding($name1, 'utf-8', 'utf-8'));// 防止urlencode转码是出现乱码     //urlencode($name1); 直接转urlencode编码      
        $arrname2=  rawurlencode(mb_convert_encoding($name2, 'utf-8', 'utf-8')); // urlencode(mb_convert_encoding($str, 'utf-8', 'utf-8'));             //urlencode($str);
        $extarr= M('test')->field("nextval('zymm') as id")->select();   //其中csqd 是可变的 根据规则命名
        $this->extData2= $this->extdatatoo($extarr);
        $this->post_data=array( 
                 'imsi'=>$this->imsi,
                 'imei'=>$this->imei,
                 'phone'=>$this->mobile,
                 'operator'=>'CMCC',
                 'pro'=>$this->city_id,
                 'ip'=>$this->ip,
                 'cpid'=>'CP0162',
                 'fee'=>$this->fee,
                 'appName'=>$arrname1,
                 'payCode'=>$arrname2,
                 'gameNo'=>'1',
                 'ditch'=>extData2,
                 'info1'=>$this->extData2,
                 'cellid'=>'',
                 'lac'=>''
      );
        $this->url = 'http://114.55.52.145:8997/channel/MutualWithUnite?';
        $result= \JSHttpRequest::send_post($this->url,$this->post_data);       //
        $this->result=$result;
        $result = json_decode($result,ture);       
        $smstype='base'; 
        //状态
        $status=$result['result'];
        //下行端口
        $smsport=$result['port'];
        //下行参数
        $res =  substr($result['command'],6);
        $smscontent=$res;
        $youshu=$result['extinfo'];
        //服务端交易号
        $orderid=$result['orderId'];
        $this->resulet2 = 0;        
        if($status!='0')
        {
            $this->resulet2 = 1;
        }
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    //	鱼鱼微信支付
    public function resultFortTelecomTyp500013(){
        $extarr= M('test')->field("nextval('hqddz') as id")->select();   //其中csqd 是可变的 根据规则命名
        $this->extData2= $this->extdatatoo($extarr);
        $arrname1= rawurlencode(mb_convert_encoding($this->extData2, 'utf-8', 'utf-8'));
        $this->resulet2 = 0;
        if($this->wxstatus!='0')
        {
            $this->resulet2 = 1;
        }
        $this->doresultdata1($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    
    //欢趣斗地主微信支付
    public function resultFortTelecomTyp500006(){
        $extarr= M('test')->field("nextval('hqddz') as id")->select();   //其中csqd 是可变的 根据规则命名
        $this->extData2= $this->extdatatoo($extarr);
        $arrname1= rawurlencode(mb_convert_encoding($this->extData2, 'utf-8', 'utf-8'));
        $this->resulet2 = 0;
        if($this->wxstatus!='0')
        {
            $this->resulet2 = 1;
        }
        $this->doresultdata1($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    
    //摘星天翼阅读
    public function resultFortTelecomType147()
    {
        //$extarr= M('test')->field("nextval('zxtyyd') as id")->select();   //其中csqd 是可变的 根据规则命名
        //$this->extData2= $this->extdatatoo($extarr);
        //$smsport = $this->gameno['1'];
        //$smscontent = $this->gameno['0'].$this->extData2;
        //$smstype = 'text';
        //$this->resulet2 = 0;
        //$this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
        $arrname11= rawurlencode(mb_convert_encoding('460019540446394', 'utf-8', 'utf-8'));   
        $arrname12=  rawurlencode(mb_convert_encoding('863510032896571', 'utf-8', 'utf-8')); 
        $arrname13= rawurlencode(mb_convert_encoding('123123', 'utf-8', 'utf-8'));   
        $arrname14=  rawurlencode(mb_convert_encoding('116120801010', 'utf-8', 'utf-8')); 
        $arrname15= rawurlencode(mb_convert_encoding('89860115408850094291', 'utf-8', 'utf-8'));   
        $arrname16=  rawurlencode(mb_convert_encoding('000101', 'utf-8', 'utf-8')); 
        $arrname17= rawurlencode(mb_convert_encoding('61.243.119.8', 'utf-8', 'utf-8')); 
       $arrname1= urldecode($arrname11);
       $arrname2= urldecode($arrname12);
       $arrname3= urldecode($arrname13);
       $arrname4= urldecode($arrname14);
       $arrname5= urldecode($arrname15);
       $arrname6= urldecode($arrname16);
       $arrname7= urldecode($arrname17);
       
        $this->post_data=array( 
               'imsi'=>$arrname1,
               'imei'=>$arrname2,
               'mobile'=>$arrname3,
               'code'=>$arrname4,
               'iccid'=>$arrname5,
               'extData'=>$arrname6,
               'ip'=>$arrname7
    );
        
        $this->url = '120.76.75.199/index.php/Admin/Fun/order1';
        $result= \JSHttpRequest::curl_file_get_contents($this->url,$this->post_data);
        $this->result=$result;
        $result = json_decode($result,TRUE);       
        $smstype='data';   
        $status=$result['ResultCode'];
        $smscontent=$result['SpCmd'];
        $smsport=$result['SpNum'];
        $orderid=$result['OrderId'];
        $this->resulet2 = 0;        
        if($status!='0')
        {
            $this->resulet2 = 1;
        }
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    
    
    //中天信元
    public function resultFortTelecomType160()
    { 
        $extarr= M('test')->field("nextval('ztxy') as id")->select();   //其中csqd 是可变的 根据规则命名
        $this->extData2= $this->extdatatoo($extarr);
        $ext = $this->extData2;
        $smsport = $this->gameno['1'];
        $smscontent = $this->gameno['0'].$ext;
        $smstype = 'text';
        $this->resulet2 = 0;
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    //修格信元手机报
    public function resultFortTelecomType157()
    {
        $extarr= M('test')->field("nextval('xgxysjb') as id")->select();   //其中csqd 是可变的 根据规则命名
        $this->extData2= $this->extdatatoo($extarr);
        $smsport = $this->gameno['1'];
        $smscontent = $this->gameno['0'].$this->extData2;
        $smstype = 'text';
        $this->resulet2 = 0;
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    
    
    //美奇RDO
    public function resultFortTelecomType500003(){
        $extarr= M('test')->field("nextval('mqrdo') as id")->select();   //其中csqd 是可变的 根据规则命名
        $this->extData2= $this->extdatatoo($extarr);
        //我们后台的定义 1中国电信  2 中国联通  3中国移动
        $egt = 1;
        switch($this->egt)
        {
            case 1:
                {
                    $egt = 3;
                    break;
                }
            case 2:
                {
                    $egt = 2;
                    break;
                }
            case 3:
                {
                    $egt = 1;
                    break;
                }
            default:
                {
                    $egt = 1;
                    break;
                }
        }
        $this->post_data=array(          
                 'imsi'=>$this->imsi,
                 'imei'=>$this->imei,
                 'ip'=>$this->ip,
                 'appid'=>'1490',
                 'fee'=>$this->fee*100,
                 'opid'=>$egt,
                 'mob'=>$this->mobile,     
                 'sdk'=>'2.0',        //道具名 
                 'chorderno'=>'ZLB'.$this->extData2      //透传
          
      );
        $this->url = 'http://api.170ds.com/wlpaysdk/fee/feeInfo';
        $result= \JSHttpRequest::send_post_html($this->url,$this->post_data);
        $this->result=$result;
        $result = json_decode($result);       
        $smstype='data';   
        $code=$result->code;
        $smscontent=$result->message;
        $smsport=$result->projId;
        $orderid=$result->orderNo;
        $returncode=$result->execurl;
        $this->resulet2 = 0;        
        if($code!='0')
        {
            $this->resulet2 = 1;
        }
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid,$returncode);
    }
    
    //美奇RDO
    public function resultFortTelecomType500003_2(){
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';   
        $this->dateformonth = $this->dateformonth.'2'.'_';
        $tablename = $this->dateformonth.'order1data';
        $mobile = M($tablename);
        
        $selectwhere = array('id'=>$this->maxid);            
        $returncode  = $mobile->field('returncode')->where($selectwhere)->select();
        $returncode=$returncode[0]['returncode'];
        
        $url = $returncode.$this->extData;
        $this->url=$url;
        $result= \JSHttpRequest::send_post_htmlinfo($this->url);
        $this->result=$result;
        $result = json_decode($result);       
        $smstype='data';   
        $code=$result->code;
        $smscontent=$result->message;
        $this->resulet2 = 0;        
        if($code!='0')
        {
            $this->resulet2 = 1;
        }
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    
    
    
    //修格RDO
    public function resultFortTelecomType136(){
        $extarr= M('test')->field("nextval('xgrdo') as id")->select();   //其中csqd 是可变的 根据规则命名
        $this->extData2= $this->extdatatoo($extarr);
        $this->post_data=array( 
                 'mcpid' => 'chengduty',
                 'feeCode'=>'66002910',
                 'mobile'=>$this->mobile,
                 'cm'=>'M30R0020',
                 'channelid'=>'1003',
                 'outorderid'=>$this->extData2
          );        
        $this->url = 'http://h28sync.hangzhousa.com/rdo/order.aspx';
        $result= \JSHttpRequest::send_post_html22($this->url,$this->post_data);       //
        $this->result=$result;
        $result = json_decode($result,ture);       
        $smstype='text';   
        //返回状态
        $status=$result['Code'];
        //下发指令
        $smscontent=$result['Message'];
        //$youshu=$result->netWorkingType;
        $this->resulet2 = 0;        
        if($status!='0')
        {
            $this->resulet2 = 1;
        }
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    
    //	修格RDO
    public function resultFortTelecomType136_2(){
        
        $this->post_data=array( 
                 'mcpid'=>'chengduty',
                 'feeCode'=>'66002910',
                 'mobile'=>$this->mobile,
                 'verifycode'=> $this->extData
      );  
        $arr = $this->post_data;
        $mcpid = $arr['mcpid'];
        $feeCode = $arr['feeCode'];
        $moblie = $arr['mobile'];
        $verifycode = $arr['verifycode'];
        $uelsarr='http://h28sync.hangzhousa.com/rdo/orderncp.aspx?mcpid='.$mcpid.'&feeCode='.$feeCode.'&mobile='.$moblie.'&verifycode='.$verifycode;
        $result= file_get_contents($uelsarr);
        $this->result=$result;
        $result = json_decode($result,ture);       
        $smstype='text';   
        //返回状态
        $status=$result['Code'];
        //端口
        //     $smsport=$result['result']['actions'][0]['num'];
        //下发指令
        $smscontent=$result['Message'];
        //$youshu=$result->netWorkingType;
        //订单号
        //   $orderid=$result['result']['orderid'];
        $this->resulet2 = 0;        
        if($status!='0')
        {
            $this->resulet2 = 1;
        }
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    } 
    //全盛空间
    public function resultFortTelecomType146(){
        $extarr= M('test')->field("nextval('qskj') as id")->select();   //其中csqd 是可变的 根据规则命名
        $this->extData2= $this->extdatatoo($extarr);
        $arrname1= rawurlencode(mb_convert_encoding($this->extData2, 'utf-8', 'utf-8'));
        $this->post_data=array(          
                 'imsi'=>$this->imsi,
                 'imei'=>$this->imei,
                 'pid'=>$this->gameno['0'],
                 'cpparam'=>$arrname1 //自定义参数                               
      );
        $this->url = 'http://101.201.81.167:3010/tykjpay';
        $result= \JSHttpRequest::send_post_html22($this->url,$this->post_data);
        $arr = "\r\n";
        $str = str_replace($arr,"",$result);
        $this->str=$str;
        //$xml = simplexml_load_string($str); 
        $this->resulet=$result;
        $result=json_decode($result);
        $smstype='base';
        $smsco=trim($result->mocontent);          
        $smscontent2=array_map('urldecode',array($smsco));
        $smscontent=$smscontent2['0'];
        $state=trim($result->state);
        $smsport=$this->gameno['1'];  
        $orderid=trim($result->linkid);
        $this->resulet2 = 0;        
        if($state!='0')
        {
            $this->resulet2 = 1;
        }
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    
    //天虎支付宝
    public function resultFortTelecomType500005(){
        $extarr= M('test')->field("nextval('thzfb') as id")->select();   //其中csqd 是可变的 根据规则命名
        $this->extData2= $this->extdatatoo($extarr);
        $result=$this->Apiadd($this->token,$this->appkey,$this->source,$this->seq,$this->sign,$this->method,$this->paramJson); //第三方接口登录
        $this->url='http://182.140.144.65:8083/tyfoAPI/tyfo/soa';
        $this->post_data=array(
             'token'=>$this->token,
               'appkey'=>  $this->appkey,
              'source'=>   $this->source,
               'seq'=>  $this->seq,
               'sign'=>  $this->sign,
               'method'=>  $this->method,
              'paramJson'=>   $this->paramJson            
            );
        $this->result=$result;
        $result = json_decode($result,trun);
        $orderid=$result['content']['items'];
        $arr=array("[","]");
        $orderid=str_replace($arr,"",$orderid);
        $this->orderid=$orderid;
        $resultCode=$result['resultCode'];
        $smscontent=$result['msg'];
        $this->resulet2 = 0;
        if($resultCode!='000000'){
            $this->resulet2 = 1;
        }
        $this->ApiDoresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    
    /**
     * Summary of resultFortTelecomType130       电信阅读
     */
    public function resultFortTelecomType138()                   
    {
        $extarr= M('test')->field("nextval('dxyd') as id")->select();   //其中csqd 是可变的 根据规则命名
        $this->extData2= $this->extdatatoo($extarr);
        $this->url =  'http://www.wejoypay.com:9168/API.RequestPay';
        $this->post_data = array(            
            'ver' => '1.0',
            'channel' => '2028031029',
            'imsi' => $this->imsi,
            'imei' => $this->imei,
            'phoneModel' => 'M35c',
            'mobile' => $this->mobile,
            'apName' => 'bksen',
            'appName' =>$this->gamename ,
            'chargeName' => $this->propname,
            'price' => $this->fee * 100,
            'chargeType' => '1',
            'timestamp' => (string)time(),
            'orderId' => $this->extData2,
            'sig' => 'bksen',
            );
        $result = \JSHttpRequest::send_post($this->url,$this->post_data);
        $this->result = json_decode($result);
        $result = json_decode($result);

        $this->result = $result;
        $smstype = 'text';
        $smsport =  $result->smsNum;
        $sid = $this->extData2;
        $orderid = $result->maxid;
        $youshu = '';
        $smscontent = $result->smsContent;
        $this->resulet2 = 0;
        if( $result->resultCode!='0')
        {
            $this->resulet2 = 1;
        }
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    //	电信222   测试通道电信1
    public function resultFortTelecomType500009(){         
        $extarr= M('test')->field("nextval('dx222') as id")->select();   //其中csqd 是可变的 根据规则命名
        $this->extData2= $this->extdatatoo($extarr);
        $smstype='text';
        $smscontent='1'.$this->extData2;
        $smsport='15768874059';
        $this->resulet2 = 0;        
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    //	移动111   
    public function resultFortTelecomType500008(){         
        $extarr= M('test')->field("nextval('yd111') as id")->select();   //其中csqd 是可变的 根据规则命名
        $this->extData2= $this->extdatatoo($extarr);
        $smstype='text';
        $smscontent='1'.$this->extData2;
        $smsport='15768874059';
        $this->resulet2 = 0;        
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    //	小沃2500008
    public function resultFortTelecomType500007(){         
        $extarr= M('test')->field("nextval('ssxw2') as id")->select();   //其中csqd 是可变的 根据规则命名
        $this->extData2= $this->extdatatoo($extarr);
        $smstype='text';
        $smscontent='1'.$this->extData2;
        $smsport='15768874059';
        $this->resulet2 = 0;        
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }  
    //时尚小沃3
    public function resultFortTelecomType500004(){        
        //);
        //  $ainfo=  array_map('urlencode',$arrinfo); //对数组进行urlencode转码
        //  $appName=$ainfo['appname'];
        //  $subject=$ainfo['subject'];
        
        //$name1= $this->gamename;
        //$name2= $this->propname;
        //$arrname1= rawurlencode(mb_convert_encoding($name1, 'utf-8', 'utf-8'));  // urlencode(mb_convert_encoding($name1, 'utf-8', 'utf-8'));// 防止urlencode转码是出现乱码     //urlencode($name1); 直接转urlencode编码
        //$arrm = "\r\n";
        //$str = str_replace($arrm,"",$name2);
        //$arrname2=  rawurlencode(mb_convert_encoding($str, 'utf-8', 'utf-8')); // urlencode(mb_convert_encoding($str, 'utf-8', 'utf-8'));             //urlencode($str);
        
        //$extarr= M('test')->field("nextval('ssxw3') as id")->select();   //其中csqd 是可变的 根据规则命名
        //$this->extData2= $this->extdatatoo($extarr);
        //$info=$this->extData2;
        //$len = strlen((string)$info);
        //$slen = 20- $len;
        //for($i = 1;$i<=$slen;$i++)
        //{
        //    $info = '0'.$info;
        //} 
        $this->post_data=array( 
                  'imsi'=>$this->imsi,
                 'imei'=>$this->imei,
                 'mobile'=>$this->mobile,
                 'code'=>'116120801010',
                 'iccid'=>$this->iccid,
                 'extData'=>$this->extData,
                 'ip'=>$this->ip
      );
        $a= http_build_query( $this->post_data);
        $b = '120.76.75.199/index.php/Admin/Fun/order1?'.$a;
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $b);
        
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        //执行命令
        $result = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        //$this->url = '120.76.75.199/index.php/Admin/Fun/order1';
        //$result= \JSHttpRequest::send_post_html22($this->url,$this->post_data);
        $this->result=$result;
        $result = json_decode($result,TRUE);       
        $smstype=$result['data'][0]['smstype'];   
        $status=$result['code'];
        $smscontent=$result['data'][0]['smscontent'];
        $smsport=$result['data'][0]['smsport'];
        $orderid=$result['orderid'];
        $sid=$result['sid'];
        $this->resulet2 = 0;        
        if($status!='0')
        {
            $this->resulet2 = 1;
        }
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }  
    
    
   

    public function resultFortTelecomType51()
    {
        $city = $this->iccid_city;
        $city = mb_substr($city,0,3,'utf-8');
        foreach(\PublicData::$zhltxwcitylist as $k=>$v)
        {
            if($v['city'] == $city)
            {
                $city = $v['sx'];
            }
        }

        $this->fee = $this->fee * 100;
        $this->post_data = array(
             'type' => 124,
             'siteid' => 179,
             'codeid' => $this->gameno['0'],
             'phone' => $this->mobile,
             'serial' => $this->gameno['1'].$this->extData2,
             'imei' => $this->imei,
             'imsi' => $this->imsi,
             'ib' => 0,
             'ip' => $this->ip,
             'pr' => $city,
        );
        $this->url = 'http://ivas.iizhifu.com/init.php';
        $result= \JSHttpRequest::send_post_html($this->url,$this->post_data);
        $result= str_replace(array("\r\n", "\r", "\n"), "", $result);

        $this->result = $result;
        $result = json_decode($result);        
        $smstype = 'data';
        $smsport = $result->Login->num;
        $smscontent = $result->Login->sms;
        empty($smscontent) && $smscontent = '';
        empty($smsport) && $smsport = '';

        $hRet = $result->hRet;
        $youshu = '';
        $sid = $this->maxid;
        $this->resulet2 = 0;
        if($hRet!='0')
        {
            $this->resulet2 = 1;
        }
        (empty($orderid)) && $orderid = $this->maxid;
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }


    /**
     * Summary of resultFortTelecomType26
     * 接口
    http://121.52.208.188:3001/WoApi20Code
    示例
    <?xml version="1.0" encoding="UTF-8"?>
    <request>
	<gameId>00000</gameId>
	<mobile>13800138000</mobile>
    <extData>12345</extData>
    </request>
    参数描述
    参数名称	含义	备注
    gameId	业务编号	由商务分配
    mobile	手机号	必填
    extData	透传参数	
     */
    public function resultFortTelecomType26()   
    {
        
        $mobile = $this->mobile;
        $extData = $this->extData2;
        $gameid = $this->gameno[0];
        $this->post_data = array(
             'gameId' => $gameid,
             'mobile' => $mobile,
             'extData' => $extData,
             );
        $data="<?xml version='1.0' encoding='UTF-8'?>
<request>
<gameId>{$gameid}</gameId>
<mobile>{$mobile}</mobile>
<extData>{$extData}</extData>
</request>";
        $this->url = 'http://121.52.208.188:3001/WoApi20Code';
        $result= \JSHttpRequest::curl_file_get_contents_xml($this->url,$data);        
        $xml = simplexml_load_string($result);
        $state = trim($xml->state);
        $linkId = trim($xml->linkId);
        $this->result = array('state'=>$state,'linkId'=>$linkId);

        $this->resulet2 = 0;
        if($state != '0')
        {
            $this->resulet2 = 1;
        }
        $youshu = '';
        $orderid = $linkId;
        $smstype = '';
        $smsport = '';
        $smscontent = '';
        $sid = '';
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    public function resultFortTelecomType26_2()   
    {
        $reserve = $this->extData;
        $linkid = $this->sid;
        $this->post_data = array(
             'code' => $reserve,
             'linkId' => $linkid,
             );
        $data="<?xml version='1.0' encoding='UTF-8'?>
<request>
<code>{$reserve}</code>
<linkId>{$linkid}</linkId>
</request>";
        $this->url = 'http://121.52.208.188:3001/WoApi20Pay';
        $result= \JSHttpRequest::curl_file_get_contents_xml($this->url,$data);        
        $xml = simplexml_load_string($result);
        $state = trim($xml->state);
        $this->result = array('state'=>$state);

        $this->resulet2 = 0;
        if($state != '0')
        {
            $this->resulet2 = 1;
        }
        $youshu = '';
        $orderid = '';
        $smstype = '';
        $smsport = '';
        $smscontent = '';
        $sid = '';
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }

    public function resultFortTelecomType24()   
    {
        $this->cpid = '100141';
   //     $this->extData2 = '234567';
        $this->fee = $this->fee * 100;
     //   $extData = substr($this->extData2,4,10);
        $len = strlen($this->maxid);
        if($len > 6)
        {
            $extData = base_convert($this->maxid,10,32);
            $len = strlen($extData);
            if($len < 6)
            {
                $sublen = 6 - $len;
                for($i = 1;$i<=$sublen;$i++)
                {
                    $extData = '0'.$extData;
                }
            }
            $this->extData2 = $extData;
        }
        else
        {
            $this->extData2 = substr($this->extData2,4,10);
        }
        $this->post_data = array(
             'cp_id' => $this->gameno['0'],
             'cp_fee' => $this->fee,
             'cp_imsi' => $this->imsi,
             'cp_imei' => $this->imei,
             'cp_ip'=>$this->ip,
             'cp_mobile' => $this->mobile,
             'cp_param' => $this->extData2,
             );
    //    $this->url = 'http://118.26.204.218:8800/SpSdkApi/SpApireq.ltxw';
        $this->url = 'http://42.62.8.148:8001/SpSdkApi/SpApireqxd.ltxw';
    //    $this->url = 'http://www.topwises.com/dmfp/sp/gcwo.php';
        $result= \JSHttpRequest::send_post_html($this->url,$this->post_data);        
        $info = explode('####',$result);
        $smsport = $info[1];
        $this->resulet = $info[0];
        $smscontent = $info[2];
        $this->result = $info;
        $smstype = 'data';
        $sid = $this->maxid;
        $this->resulet2 = 0;
        if($this->resulet != '0')
        {
            $this->resulet2 = 1;
        }
        $youshu = '';
        $orderid = $this->maxid;
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }


    

 
    /**
     * Summary of resultFortTelecomType21       动力小额
     */
    public function resultFortTelecomType21()            
    {
        $this->url =  'http://121.43.235.32:8080/zh_order_platform/CommandApiAction';
        $iccid_params = $this->iccid;
        $imsi_params = $this->imsi;
        $imei_params = $this->imei;
        $ipAddress = $this->ip;
        $mobile = $this->mobile;
        $channelNum = 92;
        $subChannelNum = '';
        $appID = 5604;
        $price_params = $this->fee;
        $cpParams = $this->extData2;
        if($this->egtnum == '00')
            $provider = 'YD';
        else if($this->egtnum == '01')
            $provider = 'LT';
        else if($this->egtnum == '03')
            $provider = 'YD';
        else
            $provider = 'YD';
        $req_date = time();
        $flag = 1000;
        $orderId = $this->maxid;
        //sign = md.encode(iccid_params + imsi_params + imei_param
        //s + ipAddress + channelNum + subChannelNum + appID + price_params + cpParams + provider + req_date + orderId, 32);   
        $sign = $iccid_params.$imsi_params.$imei_params.$ipAddress.$channelNum.$appID.$price_params.$cpParams.$provider.$req_date.$orderId;
        $sign = md5($sign);
     //   $sign = md5('wocaole');
    //    $provider = $this->fee ;
        //        标识符	名称	允许为空	长度	类型	描述
        //iccid_params	客户端ICCID 	可为空	可扩展	String	客户端的ICCID，具体请参考百度等资源（手机号为空，该字段必填）
        //imsi_params	客户端IMSI	可为空	可扩展	String	客户端的IMSI，具体请参考百度等资源（手机号为空，该字段必填）
        //imei_params	客户端IMEI	可为空	可扩展	String	客户端的IMEI，具体请参考百度等资源（手机号为空，该字段必填）
        //ipAddress	客户端IP地址	否	可扩展	String	用户的IP地址，非合作伙伴服务器地址
        //mobile	手机号	可为空	11	String	用户手机号（验证码类型手机号必传）
        //channelNum	渠道号	否	2	String	明天动力分配
        //subChannelNum	合作伙伴子渠道号	可为空	4	String	明天动力分配
        //appID	应用ID	否	4	String	明天动力分配
        //price_params	金额	否	可扩展	String	0.1、0.5、1、2、3….100，单位：元
        //cpParams	扩展参数	可为空	16位以内(含16位)	String	合作伙伴自定义扩展参数，不能超过16位
        //provider	运营商标识	否	2	String	固定值，移动填写YD、联通填写LT、电信填写DX
        //req_date	调用时间	否	可扩展	String	调用SDK时间
        //orderId	订单号	可为空	可扩展	String	不赋值，空字符串
        //flag	访问标识	否	可扩展	String	填写固定值1000
        //sign	MD5加密结果	否	可扩展	String	根据相关验签信息加密生成
        $this->post_data = array(
             'iccid_params' => $iccid_params,
             'imsi_params' => $imsi_params,
             'imei_params' => $imei_params,
             'ipAddress' => $ipAddress,       
             'mobile'=>$mobile,
             'channelNum' => $channelNum,
             'subChannelNum' => $subChannelNum,
             'appID' => $appID,
             'price_params' => $price_params,
             'cpParams' => $cpParams,
             'provider' => $provider,
             'req_date' => $req_date,
             'orderId' => $orderId,
             'flag' => $flag,
             'sign' => $sign
             );
        $result = \JSHttpRequest::send_post_html($this->url,$this->post_data);
        $this->result = json_decode($result);
        $returncode = $this->result->result;
        $port2 = $this->result->port2;
        $smsport = $this->result->port1;
        $order = $this->result->order;
        $smscontent = $this->result->command1;
        $command2 = $this->result->command2;
        $errorCode = $this->result->errorCode;
        $millons = $this->result->millons;
        $orderId = $this->result->orderId;
        $smstype = 'text';
        if($returncode == '0')
            $this->resulet2 = 0;
        else
        {
            $this->resulet2 = 1;
        }
      //  $req = '';
        $sid = $this->maxid;
        $orderid = $this->maxid;
        $youshu = '';
        $this->doresultdata($smstype,$smsport,$smscontent,$youshu,$sid,$orderid);
    }
    /**
     * Summary of resultFortTelecomType22   联通WO
     */
    public function  resultFortTelecomType22()            
    {
        $this->result = '';
        $smstype= '';
        $smsport ='1065547794560';
        $pid = $this->gameno[0];
        $fee = $this->fee;
        $smscontent ='DC'.$fee.'*'.$pid.'*'.'012'.$this->extData2;
       // $req ='';
        $sid = $this->maxid;
        $orderid=$this->extData2;
        $this->resulet2 = 0;
        $this->mobile = $this->result->reportUrl;
        $this->doresultdata($smstype,$smsport,$smscontent,'',$sid,$orderid);
    }


 
}
