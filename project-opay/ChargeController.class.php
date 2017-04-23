<?php
namespace Admin\Controller;
include_once "PublicData.php";
include_once "PublicFunction.php";
include_once"ModelNameList.php";

/**
 * Charge short summary.
 *
 * Charge description.
 *
 * @version 1.0
 * @author admin
 */
//计费代码管理部分
class ChargeController extends AdminController
{
    public function index()
    {
       $model2 = M(\ModelNameList::$paycodelist);
       $data = array('egt','telecomtype','telecomname','payglod','status');
       $selectwhere_map = \PublicFunction::getClickSeachData($data,'utf8name');
       $selectwhere = $selectwhere_map[0];
     //  print_r($selectwhere);
       $map = $selectwhere_map[1];
       $data = array('egt'=>$selectwhere['egt'],'telecomtype'=>$selectwhere['telecomtype'],
    'telecomname'=>$selectwhere['telecomname'],'payglod'=>$selectwhere['payglod'],'status'=>$selectwhere['status']);
       if($map == null)
       {
           $info   =   $this->lists($model2->where($selectwhere),$selectwhere);
       }
       else
       {
           $info   =   $this->lists($model2->where($map),$map);
       }
       $sql = $model2->_sql();
      // print_r($info);
        $model = \ModelNameList::$telecomlist;
        $model || $this->error('模型名标识必须！');
        $model = D($model);
        $telecomlist = $model->order('id')->select();
        $paycodestatus = \PublicData::$paycodestatus;
        $binds = \PublicData::$binds;
        $paygoldlist = \PublicData::$paygoldlist;
        $telecomcorplist = M(\ModelNameList::$telecomcorp)->select();
        $telecomtypelist =  M(\ModelNameList::$telecomtypelist)->select();
        //print_r($telecomtypelist);
        $this->assign('data',$data);
        $this->assign('telecomtypelist',$telecomtypelist);
        $telecomlist = M(\ModelNameList::$telecomlist)->select();
        $this->assign('telecomlist',$telecomlist);
        $this->assign('binds',$binds);
        $this->assign('paycodestatus',$paycodestatus);
        $egtlist = \PublicData::$egtlist;
        $this->assign('egtlist',$egtlist);
        $this->assign('paygoldlist',$paygoldlist);
        $index = -1;
        foreach($info as $value)
        {
            $index++;
            $telecomcorp1 = M('telecomcorp')->select();
            foreach($telecomcorp1 as $v){
            if($value['telecomcorp'] =$v['id'] ){
            $info[$index]['telecomcorp'] = $v['telecomname'];
            }
            }
            $paystatusid = $value['status'];
            $info[$index]['status'] = $paycodestatus[$paystatusid]['name'];
            //$telecomcorpid = $value['telecomcorp'];
           // $info[$index]['telecomcorpid'] = $telecomcorpid;
            $telecomid = $value['telecomname'];
            $info[$index]['telecomnameid'] = $telecomid;
            $paycodenameid = $value['paycodename'];
            $info[$index]['paycodenameid'] = $paycodenameid;
            $egtid = $value['egt'];
            $info[$index]['egt'] = $egtlist[$egtid]['name'];
            foreach($telecomtypelist as $telecomtypevalue)
            {
                if($info[$index]['telecomtype'] == $telecomtypevalue['id'])
                {
                    $info[$index]['telecomtype'] = $telecomtypevalue['name'];
                }
            }
            foreach($telecomcorplist as $telecomcorplistvalue)
            {
                if($info[$index]['telecomcorp'] == $telecomcorplistvalue['id'])
                {
                    $info[$index]['telecomcorp'] = $telecomcorplistvalue['name'];
                }
            }
            foreach($telecomlist as $telecomlistvalue)
            {
                if($info[$index]['telecomname'] == $telecomlistvalue['id'])
                {
                    $info[$index]['telecomname'] = $telecomlistvalue['name'];
                }
            }
            $info[$index]['paycodename'] =  $info[$index]['utf8name'];
        }
        $this->assign("list",$info);
        //print_r($info);
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->display();    
    }
    public function add($id = 0)
    {
        if(IS_POST)
        {
            $data   =   I('post.');         //获取URL传过来的值
            $this->ischeckonenextfordata(array($data['paycodename'],$data['paycode'],$data['payglod'],$data['priority']));
            $adddata = array('name'=>$data['paycodename']);
            $paycodenamelistname= C('DB_PREFIX').\ModelNameList::$paycodenamelist;    
            $paycodelistname= C('DB_PREFIX').\ModelNameList::$paycodelist;    
            $model = M();
            $model->startTrans();
            $id = 0;
            $this->adddataforsql($model,$paycodenamelistname,$adddata,false,$id);
            $data['utf8name'] = $data['paycodename'];
            $data['paycodename'] = $id;
            $info  = M('telecom')->where(array('telecomname'=>$data['telecomname']))->select();
            $egt = $info[0]['egt'];
            $data['egt']= $egt;
            $this->adddataforsql($model,$paycodelistname,$data,true,$id);
        }        
        else
        {
            Cookie('__forward__',$_SERVER['REQUEST_URI']);
            $telecomcorp = M('telecomcorp')->select();
            $this->assign('telecomcorp',$telecomcorp);
            $telecomlist = M(\ModelNameList::$telecomlist)->select();
            $this->assign('telecomlist',$telecomlist);
            $paycodestatus = \PublicData::$paycodestatus;
            $this->assign('paycodestatus',$paycodestatus);
            $this->display();
        }
    }
    public function edit($id = 0,$oldpaycodenameid=0)
    {
        if(IS_POST)
        {
            $model = M();
            $model->startTrans();
            
            $data   =   I('post.');         //获取URL传过来的值
            $tablename = C('DB_PREFIX').\ModelNameList::$telecom;   
            //$selectwhere = array('telecomname'=>$data['telecomname']);
            //$telecominfo = $model->table($tablename)->where($selectwhere)->select();
            //$telecominfo = $telecominfo[0];
            //$data['telecomcode'] = $telecominfo['telecomcode'];
            $data2 = array('id'=>$data{'oldpaycodenameid'},'name'=>$data['paycodename']);
            $tablename = C('DB_PREFIX').\ModelNameList::$paycodenamelist;   
            $this->updatedataforsql($model,$tablename,$data2,false,$id);
            $info  = M('telecom')->where(array('telecomname'=>$data['telecomname']))->select();
            $egt = $info[0]['egt'];
            $data['egt']= $egt;
            $updatedata['utf8name'] = $data['paycodename'];
            foreach($data as $k=>$v)
            {
                if($k != "oldpaycodenameid")
                {
                    $updatedata[$k] = $v;
                }
                else
                {
                    $updatedata['paycodename'] = $data['oldpaycodenameid'];
                }
            }
            $tablename = C('DB_PREFIX').\ModelNameList::$paycodelist;   
            $this->updatedataforsql($model,$tablename,$updatedata,true,$id);
        }
        else
        {
            $info = M(\ModelNameList::$paycodelist)->field(true)->find($id);
            $pid = $info['paycodename'];
            $paycodename = M(\ModelNameList::$paycodenamelist)->field(true)->find($pid);
            $info['oldpaycodenameid'] =  $info['paycodename'];
            $info['paycodename'] = $paycodename['name'];
            $this->assign('info',$info);
            $telecomcorp = M('telecomcorp')->select();
            $this->assign('telecomcorp',$telecomcorp);
            $telecomlist = M(\ModelNameList::$telecomlist)->select();
    //        $telecomlist = M('telecomlist')->select();

            $this->assign('telecomlist',$telecomlist);
            $paycodestatus = \PublicData::$paycodestatus;
            $this->assign('paycodestatus',$paycodestatus);
            $this->display();
        }
    }
    public function info($id = 0)
    {
        $info = M(\ModelNameList::$paycodelist)->field(true)->find($id);
        $pid = $info['paycodename'];
        $paycodename = M(\ModelNameList::$paycodenamelist)->field(true)->find($pid);
        $info['paycodename'] = $paycodename['name'];
        $telecomcorplist = M('telecomcorplist')->select();
        $this->assign('telecomcorplist',$telecomcorplist);
   //     $telecomlist = M('telecomlist')->select();
        $telecomlist = M(\ModelNameList::$telecomlist)->select();
        $this->assign('telecomlist',$telecomlist);
        $paycodestatus = \PublicData::$paycodestatus;
        $this->assign('paycodestatus',$paycodestatus);
        $this->assign('info',$info);
        $this->display();
    }
    public function delete($ids = 0)
    {
        //$data = I('post.');
        //$data = $data['id'];
        //foreach($data as $k=>$v)
        //{
            
        //    $selectwhere = array('id'=>$v);
            
        //    $model = M('paycodelist');
        //    $modelinfo = $model->where($selectwhere)->select();
        //    $paycodenameid = $modelinfo[0]['paycodename'];
        //    $modelinfo = $model->where($selectwhere)->delete();

        //    $selectwhere = array('id'=>$paycodenameid);
        //    $model = M('paycodenamelist');
        //    $isnext = $model->where($selectwhere)->delete();
        //    $selectwhere = array('paycode'=>$paycodenameid);
        //    $model = M('pseudocode');
        //    $isnext = $model->where($selectwhere)->delete();
        //}
        //$this->success("删除完成");
        //var_dump("wocaolkefljk");
    }
    
    public function warning()
    {
        $model = M('paycodelist');
        if(IS_POST)
        {
            $time = time();
            $data   =   I('post.');         //获取URL传过来的值
            $waroutlets = $data['warpaycode'];
            $waroutletstime = $data['warpaycodetime'];
            $dayupforoutlets = $data['dayupforoutspaycode'];
            foreach($waroutletstime as $k=>$v)
            {
                $selectwhere = array('paycodename'=>$k);
                $data = array('warlongtime'=>$v);
                $outletslist = $model->where($selectwhere)->data($data)->save();
                
                $data = array('warbegintime'=>0);
                $isok = $outletslist = $model->where($selectwhere)->data($data)->save();
            }
            foreach($dayupforoutlets as $k=>$v)
            {
                $selectwhere = array('paycodename'=>$k);
                $data = array('dayupvalue'=>$v);
                $outletslist = $model->where($selectwhere)->data($data)->save($data);
                $sql = $model->_sql();
                print_r($sql);
            }

            foreach($waroutlets as $k=>$v)
            {
                if($waroutletstime[$v] <= 0)
                {
                    $this->error("请填写渠道预警时间，已选择渠道的预警时间不能等于0");
                    exit();
                }
                $selectwhere = array('paycodename'=>$v);
                $data = array('warbegintime'=>$time);
                $outletslist = $model->where($selectwhere)->data($data)->save();
                $sql = $model->_sql();
            }
            $this->success('预警系统生效');
        }
        else
        {

            //          $outletslist = M('outlets')->select();
        //    $model = D('paycodelist');
            $data = array('status','type');
            $selectwhere_map = \PublicFunction::getClickSeachData($data,'utf8name');
            $selectwhere = $selectwhere_map[0];
            $map = $selectwhere_map[1];
            $data = array('status'=>$selectwhere['status'],'type'=>$selectwhere['type']);
            //模拟数据库操作
            if($map == null)
            {
                $paycodelist   =   $this->lists($model->where($selectwhere),$selectwhere);
                $sql = $model->_sql();
               
            }
            else
            {
                $paycodelist   =   $this->lists($model->where($map),$map);
                $sql = $model->_sql();
            
            }
            $index = -1;
            //$index2 = 0;
            foreach($paycodelist as $k=>$v)
            {
                //$selectwhere = array('paycodename'=>$v['paycodename']);

                //$data = array('warbegintime'=>0);
                // $outletslist =$model->where($selectwhere)->data($data)->save();
                $index++;
                if($v['warbegintime'] != 0)
                {
                    $paycodelist[$index]['id2'] = $v['warbegintime'];
                }
                $paycodelist[$index]['id3'] = $v['warlongtime'];
            }
            $this->assign('list',$paycodelist);
            $this->display(); 
        }

    }
}

//路长漫漫，谁伴我同行。
//昨日已黄昏、今朝已如旧、明日当如何？
//我欲腾空万里，无奈羁绊丛生。
//看苍茫天涯，各路豪杰。而我......