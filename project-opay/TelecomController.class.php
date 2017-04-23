<?php
namespace Admin\Controller;
include_once "PublicData.php";
include_once"PublicFunction.php";
include_once"ModelNameList.php";
include_once'JSHttpRequest.php';
/**
 * TelecomCorpController short summary.
 *
 * TelecomCorpController description.
 *
 * @version 1.0
 * @author admin
 */
//通道列表部分代码
class TelecomController extends AdminController
{
    public function index()
    {   
        $where = array('vinfo'=>3);
        $isok = M('telecom')->where($where)->data(array('vinfo'=>0))->save();
        $data = array('telecomtype','status','egt','telecomcorp');
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'utf8name');
        $selectwhere = $selectwhere_map[0];
        $map = $selectwhere_map[1];
        $data = array('telecomcorp'=>$selectwhere['telecomcorp'],'egt'=>$selectwhere['egt'],
            'status'=>$selectwhere['status'],'telecomtype'=>$selectwhere['telecomtype']);
        //模拟数据库操作
        $model = \ModelNameList::$telecom;
        $model || $this->error('模型名标识必须！');
        $model = D($model);
        if($map == null)
            $list = $model->order('id DESC')->where($selectwhere)->select();
        else
            $list = $model->where($map)->where($selectwhere)->select();
        $egtlist =  \PublicData::$egtlist;
        $isrepeat = \PublicData::$isrepeat;
        $model = M(\ModelNameList::$telecomcorp);
        $telecomcorplist = $model->select();
        $telecomtypelist =  M(\ModelNameList::$telecomtypelist)->select();
        $telecomlist = M(\ModelNameList::$telecomlist)->select();
        $index = -1;
        $resulttypelist = \PublicData::$resulttypelist;
        $statuslist = \PublicData::$openstatic;
        foreach($list as $value)
        {
            $statusid = $value['status'];
            $watchid = $value['iswatch'];
            $repeat = $value['isrepeatcode'];
            $egtid = $value['egt'];
            $resulttype = $value['resulttype'];
            $index++;
            $list[$index]['isrepeatcode'] = $isrepeat[$repeat]['name'];
            $list[$index]['status'] = $statuslist[$statusid]['name'];
            //$list[$index]['iswatch'] = $statuslist[$watchid]['name'];
            $list[$index]['egt'] = $egtlist[$egtid]['name'];
            $list[$index]['telecomcorpid'] = $list[$index]['telecomcorp'];   
            $list[$index]['resulttype'] = $resulttypelist[$resulttype]['name'];

            $tid = $list[$index]['telecomcorpid'];
        //    $tname = $list[$index]['telecomname'];
            $list[$index]['telecomnameid'] = $list[$index]['telecomname'];
            foreach($telecomcorplist as $telecomcorplistvalue)
            {
                if($tid == $telecomcorplistvalue['id'])
                    $list[$index]['telecomcorp'] = $telecomcorplistvalue['telecomname'];
            }
            $list[$index]['telecomname'] = $list[$index]['utf8name'];
            foreach($telecomtypelist as $telecomtypevalue)
            {
                if($list[$index]['telecomtype'] == $telecomtypevalue['id'])
                {
                    $list[$index]['telecomtype'] = $telecomtypevalue['name'];
                }
            }
        }
        $this->assign('data',$data);
        $this->assign("list",$list);
        $this->assign("telecomlist",$telecomlist);
        $this->assign("statuslist",$statuslist);
        $this->assign("egtlist",$egtlist);
        $this->assign('telecomtypelist',$telecomtypelist);
        $this->assign('telecomcorplist',$telecomcorplist);
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->display();
    }
    public function add()
    {
        if(IS_POST)
        {
            $model = M();
            $data   =   I('post.');         //获取URL传过来的值
            $this->ischeckonenextfordata(array($data['telecomname'],$data['telecomabb'],$data['telecomcode']));
            $selectwhere = array('name'=>$data['telecomname'],'egt'=>$data['egt']);
            $id = 0;
            $telecomlisttablename= C('DB_PREFIX').\ModelNameList::$telecomlist;    
            $model->startTrans();
            $this->adddataforsql($model,$telecomlisttablename,$selectwhere,false,$id);
            $data['utf8name'] = $data['telecomname'] ;
            $data['telecomname'] = $id;
            for($i = 0;$i<32;$i++)
            {
                $citylist[$i] = 0;
            }
            foreach($data['provinces'] as $k=>$value)
            {
                $citylist[$value-1] = $value;
            }
            $data['provinces'] = '';
            foreach($citylist as $value)
            {
                $data['provinces'] =$data['provinces'].'_'.$value;
            }
            $telecomtablename= C('DB_PREFIX').\ModelNameList::$telecom;    
            $this->adddataforsql($model,$telecomtablename,$data,true,$id);             
        }
        else
        {
            Cookie('__forward__',$_SERVER['REQUEST_URI']);
            $model = M(\ModelNameList::$telecomcorp);
            $telecomlist = $model->select();
            $isrepeat = \PublicData::$isrepeat;

            $telecomtypelist =  M(\ModelNameList::$telecomtypelist)->select();
            $citylist = \PublicData::$city;
            $city_check = explode('_',$info['provinces']);
            unset($city_check[0]);
            foreach($city_check as $k=>$value)
            {
                $citylist[$k-1]['id2'] = $value;
            }
            $resulttypelist = \PublicData::$resulttypelist;
            $this->assign('resulttypelist',$resulttypelist);
            $this->assign('citylist',$citylist);
            $this->assign('telecomlist',$telecomlist);
            $egtlist =  \PublicData::$egtlist;
            $statuslist = \PublicData::$openstatic;
            $this->assign('egtlist',$egtlist);
            $this->assign('isrepeat',$isrepeat);
            $this->assign('statuslist',$statuslist);
            $this->assign('telecomtypelist',$telecomtypelist);
            $this->meta_title = '添加通道列表';
            $this->display();
        }
    }
    public function addtype()
    {
        if(IS_POST)
        {
            $model = M();
            $data = I('post.');
            $telecomtablename= C('DB_PREFIX').\ModelNameList::$telecomtypelist;    
            $this->adddataforsql($model,$telecomtablename,$data,true,$id);      
        }
        else
        {
            Cookie('__forward__',$_SERVER['REQUEST_URI']);

            $model = M('telecomtypelist');
            $info = $model->order('id DESC')->select();
            $this->assign('info',$info);
            $this->display();
        }
    }
    public function edit($id = 0,$oldid = 0)
    {
        if(IS_POST)
        {
            $model = M();
            $data   =   I('post.');         //获取URL传过来的值
            $this->ischeckonenextfordata(array($data['telecomname'],$data['telecomabb'],$data['telecomcode']));
            $telecomname = $data['telecomname'];
            $data2 = array('id'=>$oldid,'name'=>$telecomname,'egt'=>$data['egt']);
            $telecomlisttablename= C('DB_PREFIX').\ModelNameList::$telecomlist;    
            $model->startTrans();
            $this->updatedataforsql($model,$telecomlisttablename,$data2,false,$oldid);
            for($i = 0;$i<32;$i++)
            {
                $citylist[$i] = 0;
            }
            foreach($data['provinces'] as $k=>$value)
            {
                $citylist[$value-1] = $value;
            }
            $data['provinces'] = '';
            foreach($citylist as $value)
            {
                $data['provinces'] =$data['provinces'].'_'.$value;
            }
            for($i = 0;$i<31;$i++)
            {
                $citylist[$i] = 0;
            }
            foreach($data['prioritycity'] as $k=>$value)
            {
                if($value ==null or $value == '')
                {
                    $value =0;
                }
                $citylist[$k] = $value;
            }
            $data['prioritycity'] = '';
            foreach($citylist as $value)
            {
                $data['prioritycity'] =$data['prioritycity'].'_'.$value;
            }
            foreach($data as $k=>$v)
            {
                if($k != "oldid")
                {
                    $updatedata[$k] = $v;
                }
                else
                {
                    $updatedata['telecomname'] = $data['oldid'];
                }
            }
            $updatedata['utf8name'] = $telecomname;
            $telecomtablename= C('DB_PREFIX').\ModelNameList::$telecom;    
            $data = array('telecomcode'=>$updatedata['telecomcode'],'telecomtype'=>$updatedata['telecomtype'],'priority'=>$updatedata['priority'],'provinces'=>$updatedata['provinces'],'status'=>$updatedata['status'],'dayupvaluefortelecom'=>$updatedata['dayupvalue'],'prioritycity'=>$updatedata['prioritycity'],'resulttype'=>$updatedata['resulttype']);
            $selectwhere = array('telecomname'=>$updatedata['telecomname']);
            $paycodelisttablename =  C('DB_PREFIX').\ModelNameList::$paycodelist;  
            
            $this->updatedatawhereforsql($model,$paycodelisttablename,$data,$selectwhere,false,$id);
            $this->updatedataforsql($model,$telecomtablename,$updatedata,true,$id);
        }
        else
        {
            $info = M('telecom')->where(array('id'=>$id))->select();
            $info = $info[0];
            $telecomid = $info['telecomname'];
            $telecominfo =M(\ModelNameList::$telecomlist)->field(true)->find($telecomid);
            $info['telecomname'] = $telecominfo['name'];
            $info['oldid'] = $telecominfo['id'];
            
            $model = M(\ModelNameList::$telecomcorp);
            $telecomlist = $model->select();
            $isrepeat = \PublicData::$isrepeat;
            $egtlist =  \PublicData::$egtlist;
            $statuslist = \PublicData::$openstatic;
            $telecomtypelist =  M(\ModelNameList::$telecomtypelist)->select();
            $citylist = \PublicData::$city;
            $city_check = explode('_',$info['provinces']);
            $prioritycity =  explode('_',$info['prioritycity']);
            unset($city_check[0]);
            unset($prioritycity[0]);
            foreach($city_check as $k=>$value)
            {
                if($k <= 32)
                {
                    $citylist[$k-1]['id2'] = $value;
                }
            }
            foreach($prioritycity as $k=>$value)
            {
                if($k <= 32)
                {
                    $citylist[$k-1]['id3'] = $value;
                }
            }
            $resulttypelist = \PublicData::$resulttypelist;
            $this->assign('resulttypelist',$resulttypelist);
            $this->assign('citylist',$citylist);
            $this->assign('info',$info);
            $this->assign('telecomlist',$telecomlist);
            $this->assign('egtlist',$egtlist);
            $this->assign('isrepeat',$isrepeat);
            $this->assign('statuslist',$statuslist);
            $this->assign('telecomtypelist',$telecomtypelist);
            $this->meta_title = '编辑通道列表';
            $this->display();
        }
    }

    public function info($telecomid = 0)
    {
        //模拟数据库操作
        $telecominfo =M(\ModelNameList::$telecomlist)->field(true)->find($telecomid);
     //   $info['telecomname'] = $telecominfo['name'];
        $selectwhere = array('telecomname'=>$telecominfo['id']);
        $info = M(\ModelNameList::$telecom)->where($selectwhere)->select();
        $info = $info[0];
        $info['telecomname'] = $telecominfo['name'];
      //  $info['oldid'] = $telecominfo['id'];
        $isrepeat = \PublicData::$isrepeat;
        $egtlist =  \PublicData::$egtlist;
        $statuslist = \PublicData::$openstatic;
        $model = M(\ModelNameList::$telecomcorplist);
        $telecomlist = $model->select();
        $telecomtypelist =  M(\ModelNameList::$telecomtypelist)->select();
        $reid =   $info['isrepeatcode'] ;
        $info['isrepeatcode'] = $isrepeat[$reid]['name'];
        $reid =   $info['telecomtype'] -1;
        $info['telecomtype'] = $telecomtypelist[$reid]['name'];
        $reid =   $info['egt'] ;
        $info['egt'] = $egtlist[$reid]['name'];
        $this->assign('info',$info);
        $this->assign('telecomlist',$telecomlist);
        $this->assign('egtlist',$egtlist);
        $this->assign('isrepeat',$isrepeat);
        $this->assign('statuslist',$statuslist);
        $this->assign('telecomtypelist',$telecomtypelist);
        $this->meta_title = '编辑通道列表';
        $this->display();
    }
    public function citys($id = 0)
    {
        if(IS_POST)
        {
            $model = M();
            $data   =   I('post.');         //获取URL传过来的值
            $index= 0;
            foreach($data['prioritycity'] as $k=>$value)
            {
                $index++;
                $selectwhere = array('telecomname'=>$k);
                for($i = 0;$i<32;$i++)
                {
                    $citylist[$i] = 0;
                }
                foreach($value as $k3=>$value2)
                {
                    if($value2 ==null or $value2 == '')
                    {
                        $value2 ="0";
                    }
                    $citylist[$k3] = $value2;
                }
                $data2['prioritycity'] = '';
                foreach($citylist as $value3)
                {
                    $data2['prioritycity'] =$data2['prioritycity'].'_'.$value3;
                }
                $updatedata['prioritycity'] = $data2['prioritycity'];
                $telecomtablename= C('DB_PREFIX').\ModelNameList::$telecom;    
                $paycodelisttablename =  C('DB_PREFIX').\ModelNameList::$paycodelist;  
                $model->startTrans();
                $this->updatedatawhereforsqls($model,$paycodelisttablename,$updatedata,$selectwhere,false,$id,false);
                if($index === count($data['prioritycity']))
                {
                    $this->updatedatawhereforsqls($model,$telecomtablename,$data2,$selectwhere,true,$id,false);
                }
                else
                {
                    $this->updatedatawhereforsqls($model,$telecomtablename,$data2,$selectwhere,false,$id,false);
                }
            }
            $index= 0;
            foreach($data['provinces'] as $k=>$value)
            {
                $index++;
                $selectwhere = array('telecomname'=>$k);
                for($i = 0;$i<32;$i++)
                {
                    $citylist[$i] = 0;
                }
                foreach($value as $k=>$value2)
                {
                    if($value2 ==null or $value2 == '')
                    {
                        $value2 ="0";
                    }
                    $citylist[$value2-1] = $value2;
                }
                $data2['provinces'] = '';
                foreach($citylist as $value)
                {
                    $data2['provinces'] =$data2['provinces'].'_'.$value;
                }
                $updatedata['provinces'] = $data2['provinces'];
                $telecomtablename= C('DB_PREFIX').\ModelNameList::$telecom;    
                $paycodelisttablename =  C('DB_PREFIX').\ModelNameList::$paycodelist;  
                $model->startTrans();
                $this->updatedatawhereforsqls($model,$paycodelisttablename,$updatedata,$selectwhere,false,$id,false);
                if($index === count($data['provinces']))
                {
                    $this->updatedatawhereforsqls($model,$telecomtablename,$data2,$selectwhere,true,$id,true);
                }
                else
                {
                    $this->updatedatawhereforsqls($model,$telecomtablename,$data2,$selectwhere,true,$id,false);
                }
            }
        }
        else
        {

            $data = array('telecomtype','status','egt');
            $selectwhere_map = \PublicFunction::getClickSeachData($data,'utf8name');
            $selectwhere = $selectwhere_map[0];
            $data = array('telecomtype'=>$selectwhere['telecomtype'],'status'=>$selectwhere['status'],
            'egt'=>$selectwhere['egt']);
            $model2 = M('telecom');
            if($id != 0)
            {
                $telecompools = M('telecompools')->field('telecoms')->where(array('id'=>$id))->select();
                $telecoms = $telecompools['0']['telecoms'];
                $telecoms = explode('_',$telecoms);
                $selectwhere['telecomname'] = array('in',$telecoms);
            }
            $infoids = $this->lists3($model2->where($selectwhere),$selectwhere,'id desc');
            if($id != 0)
            {
                //$telecompools = M('telecompools')->field('telecoms')->where(array('id'=>$id))->select();
                //$telecoms = $telecompools['0']['telecoms'];
                //$telecoms = explode('_',$telecoms);
                //$temp = array();
                //foreach($telecoms as $k=>$v)
                //{
                //    foreach($infoids as $k2=>$v2)
                //    {
                //        if($v2['telecomname'] == $v)
                //        {
                //            $temp[] = $v2;
                //        }
                //    }
                //}
                //$infoids = $temp;
            }
            $telecominfo = array();
            $citylist = \PublicData::$city;
            foreach($infoids as $k=>$value)
            {
                $id = $value['id'];
                $telecomid = $value['telecomname'];
                $info =M(\ModelNameList::$telecomlist)->field(true)->find($telecomid);
                
                $telecominfo[$k]['name'] = $info['name'];
                $telecominfo[$k]['id'] = $telecomid;
                $city_check = explode('_',$value['provinces']);
                $prioritycity =  explode('_',$value['prioritycity']);
                unset($city_check[0]);
         //       unset($city_check[32]);
                unset($prioritycity[0]);
                //unset($prioritycity[32]);
                $telecominfo[$k]['status'] = $value['status'];
                foreach($city_check as $k2=>$value)
                {
                    if($k2 <= 32)
                    {
                        $citylist[$k2-1]['id2'] = $value;
                    }
                }
                foreach($prioritycity as $k3=>$value)
                {
                    if($k3 <= 32)
                    {
                        $citylist[$k3-1]['id3'] = $value;
                    }
                }
                $telecominfo[$k]['citylist'] = $citylist;
            }
            $egtlist =  \PublicData::$egtlist;
            $statuslist = \PublicData::$openstatic;
            $isrepeat = \PublicData::$isrepeat;
            $model = M(\ModelNameList::$telecomcorplist);
            $telecomcorplist = $model->select();
            $telecomtypelist =  M(\ModelNameList::$telecomtypelist)->select();
            $telecomlist = M(\ModelNameList::$telecomlist)->select();
            $this->assign('data',$data);
            $this->assign('telecominfo',$telecominfo);
            $this->assign('telecomtypelist',$telecomtypelist);
            $this->assign('statuslist',$statuslist);
            $this->assign('egtlist',$egtlist);
            $this->meta_title = '编辑通道省份';
            Cookie('__forward__',$_SERVER['REQUEST_URI']);
            $this->display();
        }
    }
    public function delete($id = 0)
    {
        $selectwhere = array('id'=>$id);
        $model = M('telecom');
        $telecominfo = $model->where($selectwhere)->select();
        $telecomid = $telecominfo[0]['telecomname'];
        $telecomtype = $telecominfo[0]['telecomtype'];

        $isnext = $model->where($selectwhere)->delete();
        $model = M('telecomlist');
        $selectwhere = array('id'=>$telecomid);
        $isnext = $model->where($selectwhere)->delete();

        $model = M('telecomtypelist');
        $selectwhere = array('id'=>$telecomtype);

        $isnext = $model->where($selectwhere)->delete();

        
        $selectwhere = array('telecomname'=>$telecomid);
        
        $model = M('paycodelist');
        $modelinfo = $model->where($selectwhere)->select();
        $paycodenameid = $modelinfo[0]['paycodename'];
        $isnext = $model->where($selectwhere)->delete();

        $selectwhere = array('id'=>$paycodenameid);
        $model = M('paycodenamelist');
        $isnext = $model->where($selectwhere)->delete();

        $selectwhere = array('paycode'=>$paycodenameid);
        $model = M('pseudocode');
        $isnext = $model->where($selectwhere)->delete();
        $this->success("删除完成");

        var_dump("wocalefljks");
    }
    public function warning()
    {
    //    $tablename= C('DB_PREFIX').'outletslist';  
        $model =M('telecom');
        if(IS_POST)
        {
            $time = time();
            $data   =   I('post.');         //获取URL传过来的值
            $waroutlets = $data['waroutlets'];
            $waroutletstime = $data['waroutletstime'];
            $dayupforoutlets = $data['dayupvalue'];
            foreach($dayupforoutlets as $k=>$v)
            {
                $selectwhere = array('telecomname'=>$k);
                $data = array('warbegintime'=>0,'warlongtime'=>0);
                $isok = $outletslist = $model->where($selectwhere)->data($data)->save();
                $data = array('dayupvalue'=>$v);
                $outletslist = $model->where($selectwhere)->data($data)->save();
            }
            foreach($waroutlets as $k=>$v)
            {
                $selectwhere = array('telecomname'=>$v);
                $data = array('warbegintime'=>$time);
                $outletslist = $model->where($selectwhere)->data($data)->save();
                $wocao = $outletslist;
                if($waroutletstime[$v] <= 0)
                {
                    $this->error("请填写渠道预警时间，已选择渠道的预警时间不能等于0");
                    exit();
                }
            }
            foreach($waroutletstime as $k=>$v)
            {
                $selectwhere = array('telecomname'=>$k);
                $data = array('warlongtime'=>$v);
                $isok = $model->where($selectwhere)->data($data)->save();
            }
            $this->success('预警系统生效');
        }
        else
        {
            $outletslist = $model->select();
            $index = -1;
            //$index2 = 0;
            foreach($outletslist as $k=>$v)
            {
                $index++;
                if($v['warbegintime'] != 0)
                {
                    $outletslist[$index]['id2'] = $v['telecomname'];
                }
                $outletslist[$index]['id3'] = $v['warlongtime'];
            }
            $this->assign('outletslist',$outletslist);
            $this->display(); 
        }

    }    
    public function pool()
    {
        $id     =   I('get.id',1);
        $poolinfo = M('telecompools')->where(array('egt'=>$id))->select();
        $tablelist = array(array('id'=>1,'name'=>'电信'),array('id'=>2,'name'=>'联通'),array('id'=>3,'name'=>'移动'),array('id'=>4,'name'=>'第三方支付平台'));
        $this->assign('tablelist',$tablelist);
        $statuslist = \PublicData::$openstatic;
        //print "<script language=\"JavaScript\">alert(\"不能删除这个通道，因为如下code用到了这个通道池\");</script>"; 

        foreach($poolinfo as $k=>$v)
        {
            $statusid = $v['status'];
            $poolinfo[$k]['status'] = $statuslist[$statusid]['name'];
        }
        $this->assign('poolinfo',$poolinfo);
        $this->assign('tableid',$id);
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->display();
    }
    public function addpool($tableid='')
    {
        if(IS_POST)
        {   
            $data = I('post.');
            $priority = $data['priority'];
            if($data['tableid']==4){
              $data['telecoms'] = '';
                $tpaytelecoms = $data['tpaytelecoms'];
                $wchartelecoms = $data['wchartelecoms'];
                $ucardtelecoms = $data['ucardtelecoms'];
                $telecoml = M('telecomlist')->select();
                foreach($telecoml as $k=>$v){
                    foreach($tpaytelecoms as $v1){
                        if($v['id'] == $v1 ){
                            $tpay .= '_'.$v1;
                            $telename .='_'.$v['name']; 
                        }
                    }
                    foreach($wchartelecoms as $v2){
                        if($v['id'] == $v2 ){
                            $wchar .= '_'.$v2;
                            $telename .= '_'.$v['name']; 
                        }
                    }
                    foreach($ucardtelecoms as $v3){
                        if($v['id'] == $v3 ){
                            $ucard .=  '_'.$v3;
                            $telename .= '_'.$v['name'];
                        }
                    }
                }
                $data['telecoms_name'] = $telename;
                $data['tpaytelecoms'] = $tpay ;
                $data['wchartelecoms'] = $wchar;
                $data['ucardtelecoms'] = $ucard;
                $priority = $data['priority'];
                foreach($priority as $v){
                    $pro .= '_'.$v; 
                }
                $data['prioritys'] = $pro;
            }else{
                $telecoms = $data['telecoms'];
                $whereegt='egt='.$tableid.' or egt=4';
                $telecoml = M('telecomlist')->where($whereegt)->select();
                $telecomn_s = '';
                foreach($telecoms as $k=>$v)
                {
                    foreach($telecoml as $k2=>$v2)
                    {
                        if($v2['id'] == $v)
                        {
                            $telecomn_s = $telecomn_s.'_'.$v2['name'];
                        }
                    }
                    foreach($priority as $k3=>$v3)
                    {
                        if($k3 == $v)
                        {
                            $prioritys[] = $v3;
                        }
                    }
                }
                $data['telecoms_name'] = $telecomn_s;
                $telecoms_s = '';
                foreach($telecoms as $k=>$v)
                {
                    $telecoms_s = $telecoms_s.'_'.$v;
                }
                $prioritys_s = '';
                foreach($prioritys as $k=>$v)
                {
                    $prioritys_s = $prioritys_s.'_'.$v;
                }
                $data['prioritys'] = $prioritys_s;
                $data['telecoms'] = $telecoms_s;
            }
            $isok = M('telecompools')->data($data)->add();
            if($isok !== false)
                $this->success('操作成功', Cookie('__forward__'));
            else
                $this->error("操作失败");
        }
        else
        {   
            $egtlist =  \PublicData::$egtlist1;
            if($tableid==4){
                foreach($egtlist as $k=>$v)
                {
                    if($k != $tableid)
                    {
                        unset($egtlist[$k]);
                    }
                }
            $paylist = M('telecomlist')->field('id,name')->where('egt=4')->select();
            $wechatlist =  M('telecomlist')->field('id,name')->where('egt=5')->select();
            $banklist =  M('telecomlist')->field('id,name')->where('egt=6')->select();
            //支付宝
            $this->assign('paylist',$paylist);
            //微信
            $this->assign('wechat',$wechatlist);
            //银联
            $this->assign('banklist',$banklist);
            }else{
                $tableid = $tableid;
                foreach($egtlist as $k=>$v)
                {
                    if($k != $tableid)
                    {
                        unset($egtlist[$k]);
                    }
                }
               
                $whereegt='egt='.$tableid;
                $telecoms = M('telecomlist')->where($whereegt)->select();
                $this->assign('telecoms',$telecoms);
             
            }
            $statuslist = \PublicData::$openstatic;
            //运营商
            $this->assign('egtlist',$egtlist);
            //状态
            $this->assign('statuslist',$statuslist);
            //id
            $this->assign('egtid',$tableid);
            $this->display();
        }

    }
    public function editpool($id = '')
    {
        $model  = M('telecompools');
        $poolinfo = $model->where(array('id'=>$id))->select();
        $poolinfo = $poolinfo['0'];
        $egtlist =  \PublicData::$egtlist;
        $tableid = $poolinfo['egt'];
        $telecoms = $poolinfo['telecoms'];
        if(IS_POST)
        {
            $data = I('post.');
            $priority = $data['priority'];
            if($data['egt']==4){
                $data['telecoms'] = '';
                $tpaytelecoms = $data['tpaytelecoms'];
                $wchartelecoms = $data['wchartelecoms'];
                $ucardtelecoms = $data['ucardtelecoms'];
                $telecoml = M('telecomlist')->select();
                foreach($telecoml as $k=>$v){
                    foreach($tpaytelecoms as $v1){
                        if($v['id'] == $v1 ){
                            $tpay .= '_'.$v1;
                            $telename .='_'.$v['name']; 
                        }
                    }
                    foreach($wchartelecoms as $v2){
                        if($v['id'] == $v2 ){
                            $wchar .= '_'.$v2;
                            $telename .= '_'.$v['name']; 
                        }
                    }
                    foreach($ucardtelecoms as $v3){
                        if($v['id'] == $v3 ){
                            $ucard .=  '_'.$v3;
                            $telename .= '_'.$v['name'];
                        }
                    }
                }
                $data['telecoms_name'] = $telename;
                $data['tpaytelecoms'] = $tpay ;
                $data['wchartelecoms'] = $wchar;
                $data['ucardtelecoms'] = $ucard;
                $priority = $data['priority'];
                foreach($priority as $v){
                    $pro .= '_'.$v; 
                }
                $data['prioritys'] = $pro;
            }else{
                $telecoms = $data['telecoms'];
                $whereegt='egt='.$tableid.' or egt=4';
                $telecoml = M('telecomlist')->where($whereegt)->select();
                $telecomn_s = '';
                foreach($telecoms as $k=>$v)
                {
                    foreach($telecoml as $k2=>$v2)
                    {
                        if($v2['id'] == $v)
                        {
                            $telecomn_s = $telecomn_s.'_'.$v2['name'];
                        }
                    }
                    foreach($priority as $k3=>$v3)
                    {
                        if($k3 == $v)
                        {
                            $prioritys[] = $v3;
                        }
                    }
                }
                $data['telecoms_name'] = $telecomn_s;
                $telecoms_s = '';
                foreach($telecoms as $k=>$v)
                {
                    $telecoms_s = $telecoms_s.'_'.$v;
                }
                $prioritys_s = '';
                foreach($prioritys as $k=>$v)
                {
                    $prioritys_s = $prioritys_s.'_'.$v;
                }
                $data['prioritys'] = $prioritys_s;
                $data['telecoms'] = $telecoms_s;
            }
            $selectwhere = array('id'=>$id);
            $isok = M('telecompools')->where($selectwhere)->data($data)->save();
            if($isok !== false)
                $this->success('更新成功', Cookie('__forward__'));
            else
                $this->error("操作失败");
        }
        else
        {
            if($tableid==4){
              foreach($egtlist as $k=>$v)
                {
                    if($k != $tableid)
                    {
                        unset($egtlist[$k]);
                    }
                }
            $paylist = M('telecomlist')->field('id,name')->where('egt=4')->select();
            $pay =  explode('_',$poolinfo['tpaytelecoms']);
            unset($pay[0]);
            foreach($paylist as $k=>$v){
            foreach($pay as $v1){
                if(  $paylist[$k]['id'] == $v1 ){
                    $paylist[$k]['id2'] = $v1; 
            }
            }
           }
            $wechatlist =  M('telecomlist')->field('id,name')->where('egt=5')->select();
            $wechar =  explode('_',$poolinfo['wchartelecoms']);
            unset($wechar[0]);
            foreach($wechatlist as $k=>$v){
                foreach($wechar as $v1){
                    if($v1 == $v['id']){
                        $wechatlist[$k]['id2'] = $v1; 
                    }
                }
            }
            $banklist =  M('telecomlist')->field('id,name')->where('egt=6')->select();
            $ucard =  explode('_',$poolinfo['ucardtelecoms']);
            unset($ucard[0]);
            foreach($banklist as $k=>$v){
                foreach($ucard as $v1){
                    if($v['id'] == $v1 ){
                        $banklist[$k]['id2'] = $v1; 
                    }
                }
            }
            //支付宝
            $this->assign('paylist',$paylist);
            //微信
            $this->assign('wechat',$wechatlist);
            //银联
            $this->assign('banklist',$banklist);
            }else{
            foreach($egtlist as $k=>$v)
            {
                if($k != $tableid)
                {
                    unset($egtlist[$k]);
                }
            }
            $prioritys = $poolinfo['prioritys'];
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
            $telecoms = $telecoms_n;
            $whereegt='egt='.$tableid.' or egt=4';
            $telecoml = M('telecomlist')->where($whereegt)->select();
            foreach($telecoml as $k=>$v)
            {
                foreach($telecoms as $k2=>$v2)
                {
                    if($v2['telecomid'] == $v['id'])
                    {
                        $telecoml[$k]['id2'] = $v2['telecomid'];
                        $telecoml[$k]['id3'] = $v2['priority'];
                    }
                }
            }
            }
            $this->assign('telecoms',$telecoml);
            $statuslist = \PublicData::$openstatic;
            $this->assign('poolinfo',$poolinfo);
            $this->assign('egtlist',$egtlist);
            $this->assign('statuslist',$statuslist);
            $this->display();
        }

    }
    public function checkinfo(&$info=array(),&$count = 0,&$telecomid)
    {
        if(count($info) > 1)
        {
            $str = '删除通道异常001！！！，请及时联系写代码的.并记录好当前删除通道的ID，ID为：_'.$telecomid.'_以方便排查问题';
            $data['str'] = $str;
            $this->ajaxReturn($data);
        }
    }
    public function deletetelecom($telecomid = '')
    {
        $id = $telecomid;
        $paycodeinfo = M('paycodelist')->field('id')->where(array('telecomname'=>$telecomid))->select();
        $str = '通道存在于以下模块中（如果没有则会直接删除）！你可以强制删除（强制删除系统将删除依赖于本通道的所有项）';
        $isnull = true;
        if($paycodeinfo != null)
        {
            $str = $str.'__________计费代码列表________';
            $isnull = false;
        }
        $pseudocodeinfo = M('pseudocode')->field('id')->where(array('telecomname'=>$telecomid))->select();
        
        if($pseudocodeinfo != null)
        {
            $str = $str.'代码分配列表,如果删除将关联删除伪代码的code';
            $isnull = false;
        }
        if($isnull == true)
        {
            $selectwhere = array('telecomname'=>$telecomid);
            $info = M('telecom')->where($selectwhere)->select();

            $info = M('telecomlist')->where($selectwhere)->select();
            if(count($info) > 1)
            {
                $str = '删除通道异常001！！！，请及时联系写代码的.并记录好当前删除通道的ID，ID为：_'.$telecomid.'_以方便排查问题';
                $data['str'] = $str;
                $this->ajaxReturn($data);
            }
            $isok = M('telecom')->where($selectwhere)->delete();
            if(!$isok)
            {
                $str = '删除通道异常！！！，请及时联系写代码的.并记录好当前删除通道的ID，ID为：_'.$telecomid.'_以方便排查问题';
                $data['str'] = $str;
                $this->ajaxReturn($data);
            }
            $selectwhere = array('id'=>$telecomid);
            $isok = M('telecomlist')->where($selectwhere)->delete();
            if(!$isok)
                $str = '删除通道异常！！！，请及时联系写代码的.并记录好当前删除通道的ID，ID为：_'.$telecomid.'_以方便排查问题';
            else
                $str = '操作成功！';
        }
        $data['str'] = $str;
        $this->ajaxReturn($data);

    }
    public function deletepool($poolid = '')
    {
        $check_nullinfo = M('pseudocode')->field('utf8name')->where(array('telecompool'=>$poolid))->select();
        if($check_nullinfo != null)
        {
            $str = '你暂时不能删除这个通道池，检测到如下code占用:_';
            foreach($check_nullinfo as $value)
            {
                $str = $str.'_'.$value['utf8name'];
            }
        }
        else
        {
            $isok = M('telecompools')->where(array('id'=>$poolid))->delete();
            if($isok !== false)
                $str = '删除成功';
            else
                $str = '此为异常！请联系写代码的';
        }
        $data = array('str'=>$str);
        $this->ajaxReturn($data);

    }
}
