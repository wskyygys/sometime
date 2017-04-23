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
 * CosdkControllerclass short summary.
 *
 * CosdkControllerclass description.
 *  SDK 二次确认弹框
 *  
 * @version 1.0
 * @author '
 */
class CosdkController extends RequestData{   
    /**
     * @param mixed $coid ID
     */
    public function check($coid='',$appid='',$city='',$iccid=''){
        $city_info= \PublicData::$city; 
        foreach($city_info as $value)
        {
            if($value['city'] == $city)
            {
                $this->city = $value['city'];                
                $this->city_id = $value['id'];
            }
        }
        if($this->city==null){
            foreach(\publicdata::$ydmm2citylist as $k=>$v)
            {
                if($v['city'] == $city)
                {
                    $this->city = $v['city'];    
                    $this->city_id = $v['sx'];        //SKD过来的城市
                }                        
            }       
            
        }
        if($iccid != ''){
            $this->checkcityforiccd($iccid);
        }
        $model = 'sdkversion';
        $model = D($model);
        $colistmodel = 'colist';
        $colistmodel = D($colistmodel);
        $applistmodel = 'gamelist';
        $applistmodel = D($applistmodel);
        if($coid== ''||$appid=='')      //大版本号
        {
            echo('error');
            return;
        }
        if($coid!=''&&$coid!=''){        //小版本号
            $co=$coid;
            $app=$appid;
            $selectwhereco = array('coid'=>$co);  //厂商
            $selectwhereapp = array('appid'=>$app);  //游戏
            $selectwherecogame = array('coid'=>$coid,'appid'=>$appid);  
            $selectwhereprop= array('propid'=>$propid,'appid'=>$appid);  
            $appinfo = $applistmodel->field('version')->where($selectwhereapp)->select();       //获取游戏版本号
            $appinfo=$appinfo['0'];
            $colistarr = M('cogameset')->field('status,appid,coid')->where($selectwherecogame)->select();   //获取DIY分配
            if($colistarr==null){
                $coinfo = $colistmodel->field('version,reconfirm,status,sdkmoudle')->where($selectwhereco)->select();       ///获取游戏的二次弹框 
            }else{
                $coinfo = M('cogameset')->field('version,reconfirm,status,sdkmoudle')->where($selectwherecogame)->select();      //获取游戏分配的二次弹框    
            }
          //  $propinfo = M('proplist')->field('sdkmoudle,gold')->where($selectwhereprop)->select();      //获取道具的 SDK模板
            //$propinfo=$propinfo['0'];
            $coinfo=$coinfo['0'];
            //$fee = $propinfo['gold'];       //道具对应金额
            $uid = $appinfo['version'];     //游戏版本号
            //$uid = $coinfo['version'];   
            $version=explode(',',$uid);
            $reconfirm=$coinfo['reconfirm'];
            $status=$coinfo['status'];
            $sdkmoudle=explode('_',$coinfo['sdkmoudle']);
            unset($sdkmoudle['0']);
            $sdkmoudlearr=array();
            foreach($sdkmoudle as $k1=>$v1){
                $selectwheresdk=array('id'=>$v1);
                $sdkmoudleinfo[$k1] = M('sdkmoudle')->field('moudlename,moudlepic,provinces')->where($selectwheresdk)->select();
                  $sdkmoudleinfo= $sdkmoudleinfo[$k1]['0'];
                  $sdkmoudlearr[]=$sdkmoudleinfo;
            }
            $sdk=$sdkmoudlearr;
            $sdkdata=array();
                          if($this->iccid_city_id==null){           //iccid优先
                                    foreach($sdk as $k2 =>$v2){
                                        $sdkinfo =explode('_',$v2['provinces']);
                                        unset($sdkinfo['0']);
                                        foreach($sdkinfo  as $k3 =>$v3){
                                                if($v3==$this->city_id){                                    
                                                  //  $sdkdata['moudlename']=$v2['moudlename'];
                                                    $sdkdatainfo=$v2['moudlepic'];         //URL  
                                                   $sdkdata[]=$sdkdatainfo;
                                                }
                                            }
                                     }
                           }else{
                                 foreach($sdk as $k2 =>$v2){
                                         $sdkinfo =explode('_',$v2['provinces']);
                                         unset($sdkinfo['0']);
                                         foreach($sdkinfo  as $k3 =>$v3){
                                             if($v3==$this->iccid_city_id){                                    
                                                 //  $sdkdata['moudlename']=$v2['moudlename'];
                                                 $sdkdatainfo=$v2['moudlepic'];         //URL  
                                                 $sdkdata[]=$sdkdatainfo;
                                             }
                                         }
                                     }
                          }
            $datapico=$sdkdata;  
            //$mun=count($datapico);
            //$ran= mt_rand(0,$mun-1); //随机取数
            // $datapico2=array();
            // $datapico2[] = $datapico[$ran];
            
            $datapico2 = $datapico;
            if(count($datapico2)==2){
                    $pice[] = $datapico2['1'];  
                    unset($datapico2['1']);
            }else{
                $pice = array(); 
            }
            if($datapico2[0]==null){
                 $reconfirm=2; 
            }
            //$pic_url=$datapico2[0][0];
            //$pic_url1=$datapico2[0][1];
            $infoarr=array();
            foreach($version as $k=>$v){
                    $selectwhere = array('uid'=>$v);  //版本号uid
                    $info[$k] = $model->where($selectwhere)->select();
                   $info=$info[$k]['0'];
                   $infoarr[]=$info;
            }   
        //    $datainfo=array('status'=>$status,'reconfirm'=>$reconfirm);
            $this->resultdata($infoarr,$reconfirm,$status,$datapico2,$pice);
            return;
        }
    
    }
    
    
    public function resultdata($info = null,$reconfirm=null,$status=null,$pic_url=null,$pice=null)
    {
               $arrdata=array();
                foreach($info as $value)
                {
                    $data = array('md5'=>$value['md5'],'classname'=>$value['classname'],'uid'=>$value['uid'],'url'=>$value['url']);
                    $arrdata[]=$data;
                }
          $data2= $arrdata;
          $resultdata2 = array('code'=>0,'status'=>$status,'reconfirm'=>$reconfirm,'pic'=>$pic_url,'pice'=>$pice,'data'=>$data2);
          echo(json_encode($resultdata2));
    }
       //       $index++;
       //     if($index == 1)
       //     {
       //         $data = array('md5'=>$value['md5'],'classname'=>$value['classname'],'uid'=>$value['uid'],'url'=>$value['url']);
       //     }
       //     if($index == 2)
       //     {
       //         $data2 = array('md5'=>$value['md5'],'classname'=>$value['classname'],'uid'=>$value['uid'],'url'=>$value['url']);
       //     }
       //     if($index == 3)
       //     {
       //         $data2 = array('md5'=>$value['md5'],'classname'=>$value['classname'],'uid'=>$value['uid'],'url'=>$value['url']);
       //     }
    
    
    ///**
    // * Summary of sxversion
    // * @param mixed $version 总版本号
    // * @param mixed $status 当前SDK的状态，测试OR外放
    // * @return mixed
    // */
    //public function sxversion($version,$status)
    //{
    //    if($status =  'cs')    
    //    {
    //        $uid = 'v1.1.1';
    //        switch((int)$version)
    //        {
    //            case 1:
    //                {
    //                    $uid = 'v1.1.1';
    //                    break;
    //                }
    //            case 2:
    //                {
    //                    $uid = 'v2.1.1';
    //                    break;
    //                }
    //            default:
    //                {
    //                    break;
    //                }
    //        }
            
    //    }
    //    else
    //    {
    //        $uid = 'v1.1.1';
    //        switch((int)$version)
    //        {
    //            case 1:
    //                {
    //                    $uid = 'v1.1.1';
    //                    break;
    //                }
    //            case 2:
    //                {
    //                    $uid = 'v2.1.1';
    //                    break;
    //                }
    //            default:
    //                {
    //                    break;
    //                }
    //        }
    //    }
    //    return $uid;
    //}
    
}
