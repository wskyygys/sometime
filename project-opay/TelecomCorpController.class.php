<?php
namespace Admin\Controller;
use Think\Controller;
use Think;
include_once"PublicData.php";
include_once"PublicFunction.php";
include_once"ModelNameList.php";
/**
 * TelecomCorpController short summary.
 *
 * TelecomCorpController description.
 *
 * @version 1.0
 * @author admin
 */
class TelecomCorpController extends AdminController
{
    
    public function index()
    {
    //    phpinfo();
        //$STR = '0000188944"4150001';
        
        //$STR = substr($STR,0,10);
        $data = array('status_id');
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'utf8name');
        $selectwhere = $selectwhere_map[0];
        $map = $selectwhere_map[1];
        $model = M(\ModelNameList::$telecomcorp);
        if($map == null)
            $list = $model->order('id DESC')->where($selectwhere)->select();
        else
            $list = $model->order('id DESC')->where($map)->where($selectwhere)->select();
        $statuslist = \PublicData::$openstatic;
        $index =-1;
        foreach($list as $value)
        {
            $id = $value['status_id'];
            $index++;
            $list[$index]['status_id'] = $statuslist[$id]['name']; 
        }
        $data = array('status_id'=>$_GET['status_id']);
        $this->assign('data',$data);
        $this->assign('statuslist',$statuslist);
        $this->assign('list',$list);
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->display();
    }

    public function add()
    {
        if(IS_POST)
        {
            $model = M();
            $data   =   I('post.');         //获取URL传过来的值  
            $telecomcorpablename = C('DB_PREFIX').\ModelNameList::$telecomcorp;    
            $this->ischeckonenextfordata(array($data['telecomname'],$data['telecomcode'],$data['priority']));
            $model->startTrans();         
            $id = 0;
            $this->adddataforsql($model,$telecomcorpablename,$data,true,$id);
        }
        else
        {
            cookie('__TelecomCorpforward__',$_SERVER['HTTP_REFERER']);  //[HTTP_REFERER] = "http://localhost:19304/index.php?s=/TelecomCorp/index.html"
            $model = 'status';
            $model || $this->error('模型名标识必须！');
            $model = D($model);
            $statuslist = \PublicData::$openstatic;
            $this->assign('statuslist',$statuslist);
            $this->meta_title = '添加通道公司';
            $this->display();
        }
    }
    private function swaptelecomcorpidtoname(&$info = array())
    {
        $telecomid = $info['telecomname'];
        $cont = array('id'=>$telecomid);
        $model = M(\ModelNameList::$telecomcorplist);
        $telecomcorplist =  $model->where($cont)->select();
        $info['telecomname'] = $telecomcorplist[0]['name'];
    }
    public function edit($id = 0) 
    {
        if(IS_POST)
        {
            $model = M();
            $data   =   I('post.');         //获取URL传过来的值
            $telecomcorplisttablename= C('DB_PREFIX').\ModelNameList::$telecomcorp;    
            $telecomcorpablename = C('DB_PREFIX').\ModelNameList::$telecomcorp;    
            $ischeckinfo = array($data['telecomname'],$data['telecomcode'],$data['priority']);
            $this->ischeckonenextfordata($ischeckinfo);
            $model->startTrans();
            $updatedata = array('id'=>$data['oldid'],'telecomname'=>$data['telecomname']);    
            $this->updatedataforsql($model,$telecomcorplisttablename,$updatedata,false,$id);
            $telecomname = $data['telecomname'];
        //    $data['id'] = $id;
          //  $data['utf8name'] = $telecomname; 数据库中没有这字段
            foreach($data as $k=>$v)
            {
                if($k != "oldid")
                {
                    $updatedata[$k] = $v;
                }
                //下面逻辑有问题，修改了上面的$updatedata
            /*    else
                {
                    $updatedata['telecomname'] = $data['oldid'];
                } */
            }
            $this->updatedataforsql($model,$telecomcorpablename,$updatedata,true,$id);
        }
        else
        {
            Cookie('__forward__',$_SERVER['HTTP_REFERER']);
            $model = M(\ModelNameList::$telecomcorp);
            $info = $model->field(true)->find($id);
//            $info = M('telecomcorp')->field(true)->find($id);
            $telecomname = $info['telecomname'];
            $info['oldid'] = $telecomname;
            $this->swaptelecomcorpidtoname($info);
            if(false === $info){
                $this->error('获取编辑信息错误');
            }
           
            $this->assign('info', $info);
            $model = 'status';
            $model || $this->error('模型名标识必须！');
            $model = D($model);
            $statuslist = \PublicData::$openstatic;
            $this->assign('statuslist',$statuslist);
            $this->meta_title = '编辑通道公司信息';
            $this->display();
        }
    }
    public function info($id = 0)
    {
        /* 获取数据 */
        $selectwhere = array('telecomname'=>$id);
        $model = M(\ModelNameList::$telecomcorp);
        $info = $model->where($selectwhere)->select();

    //    $info = M('telecomcorp')->where($selectwhere)->select();
        $this->swaptelecomcorpidtoname($info);
   //     $this->assign($info);
        if(false === $info){
            $this->error('获取编辑信息错误');
        }
 //       $info = $info[0];
        $this->assign('info', $info[0]);
        $this->meta_title = '查看通道公司';
        $this->display();
    }
}
