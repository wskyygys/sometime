<?php
namespace Admin\Controller;
include_once"PublicData.php";
include_once"PublicFunction.php";
/**
 * PseudoCode short summary.
 *
 * PseudoCode description.
 *
 * @version 1.0
 * @author admin
 */
//代码分配
class PseudoController extends ActionController
{
    public function index($code = 0)
    {        
        $colist = M('colist')->field('id,name,coid')->select();
        $telecomlist = M('telecomlist')->select();
        $gamelist = M('gamelist')->field('id,name,appid')->select();    
        $proplist = M('proplist')->field('id,name')->select();
        $codecontrol = M('codecontrol')->select();        
        //$paycodenamelist = M('paycodenamelist')->select();
        $statuslist = \PublicData::$openstatic;
        $statuslist['2']['name'] = '自动化';
        $statuslist['1']['name'] = '手动';        
      //  $isrepeat = \PublicData::$isrepeat;
        if($code != 0)
        {
            $selectwhere = array('code'=>$code);
            $list = M('pseudocode')->where($selectwhere)->order('id DESC')->select();
        }
        else
        {
            $data = array('telecomname','paycode','coname','status','egt');
            $selectwhere_map = \PublicFunction::getClickSeachData($data,'utf8name');
            $selectwhere = $selectwhere_map[0];
            $map = $selectwhere_map[1];
            $data = array('telecomname'=>$selectwhere['telecomname'],'paycode'=>$selectwhere['paycode'],
            'outletsname'=>$selectwhere['outletsname'],'status'=>$selectwhere['status'],'egt'=>$selectwhere['egt']);
            $this->assign('data',$data);
            //模拟数据库操作
            $model = D('pseudocode');
            if($map == null)
            {
                $list = $this->lists($model->where($selectwhere),$selectwhere);

            }
            else
            {
                $list =   $this->lists($model->where($map),$map);
            }
        }
        $index = -1;
        $egtlist = \PublicData::$egtlist;
        $this->assign('egtlist',$egtlist);
        $telecompools = M('telecompools')->field('id,name')->select();
        foreach($list as $value)
        {
            $index++;
            $statusid = $value['status'];
            $list[$index]['status'] = $statuslist[$statusid]['name'];
            $list[$index]['telecomnameid'] = $value['telecomname'];            
            $egtid = $value['egt'];
            $list[$index]['egt'] = $egtlist[$egtid]['name'];
            foreach($telecompools as $value5)
            {
                if($value['telecompool'] == $value5['id'])
                $list[$index]['telecompool'] = $value5['name'];            

            }
            foreach($telecomlist as $value2)
            {
                if($value['telecomname'] == $value2['id'])
                {
                    $list[$index]['telecomname'] = $value2['name'];
                }
            }
            foreach($gamelist as $value2)
            {
                if($value['appid'] == $value2['appid'])
                {
                    $list[$index]['game'] = $value2['name'];
                }
            }
            foreach($colist as $value2)
            {
                if($value['coid'] == $value2['coid'])
                {
                    $list[$index]['coname'] = $value2['name'];
                }
            }
            foreach($proplist as $value2)
            {
                if($value['prop'] == $value2['id'])
                {
                    $list[$index]['prop'] = $value2['name'];
                }
            }
            foreach($colist as $value2)
            {
                if($value['coname'] == $value2['coid'])
                {
                    $list[$index]['coname'] = $value2['name'];
                }
            }
            foreach($paycodelist as $value2)
            {
                if($value['paycode'] == $value2['paycodename'])
                {
                    $list[$index]['paycode'] = $value2['utf8name'];
                }
            }
        }
        $this->assign('list',$list);
        $this->assign('outletsinfo',$outletsinfo);
        $this->assign('telecomlist',$telecomlist);
        $this->assign('statuslist',$statuslist);
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->display();
    }
    public function edit($id = 0,$egtid = 0,$numid = 0)
    {
        if(IS_POST)
        {
            $data = I('post.');
            $id = $data['id'];
            unset($data['id']);            
            unset($data['code']);
            unset($data['egt']);
            unset($data['gold']);
            $tablename = 'bks_'.'pseudocode';
            $selectwhere = array('id'=>$id);
            $model = M();
            $this->updatedatawhereforsql($model,$tablename,$data,$selectwhere,true,$id);
            
        }
        else
        {
            $pseudocodeinfo = M('pseudocode')->field(true)->find($id);
            $egtlist = \PublicData::$egtlist;
            $pseudocodeinfo['code'] =$pseudocodeinfo['code'];
            $egtid = $pseudocodeinfo['egt'];
            $pseudocodeinfo['egt'] = $egtlist[$egtid]['name'];
            $pseudocodeinfo['egtid'] = $egtid;
            $telecomlist = M('telecom')->field('telecomname,utf8name,egt')->select();
            foreach($telecomlist as $k=>$value)
            {
                if($value['egt'] != $egtid)
                {
                    unset($telecomlist[$k]);
                }
            }
            $gameid = $pseudocodeinfo['paaid'];
            $gamelist = M('gamelist')->field('id,name,appid')->where(array('appid'=>$gameid))->select();
            $pseudocodeinfo['gameid'] = $gameid;
            $pseudocodeinfo['game'] = $gamelist['0']['name'];
            $coid = $pseudocodeinfo['coid'];
            $gamelist = M('colist')->field('id,name,coid')->where(array('coid'=>$coid))->select();
             $gamelist= $gamelist['0'];
            $propid = $pseudocodeinfo['prop'];
            $proplist = M('proplist')->field('id,name')->where(array('id'=>$propid))->select();
            $pseudocodeinfo['propid'] = $propid;
            $pseudocodeinfo['prop'] = $proplist['0']['name'];
            $this->assign('telecomlist',$telecomlist);
            $isrepeat = \PublicData::$isrepeat;
            $this->assign('isrepeat',$isrepeat);
            $statuslist = \PublicData::$openstatic;
            $statuslist2 = \PublicData::$openstatic;

            $statuslist2['2']['name'] = '自动化';
            $statuslist2['1']['name'] = '手动';

            $this->assign('statuslist',$statuslist);
            $this->assign('statuslist2',$statuslist2);
            $telecompoos = M('telecompools')->field('id,name')->where(array('egt'=>$egtid))->select();;
            $this->assign('telecompoos',$telecompoos);
            $pseudocodeinfo['telecomnameid'] = $pseudocodeinfo['telecomname'];
            $this->assign('pseudocodeinfo',$pseudocodeinfo);
            $this->display();
        }
    }
    public function add($id = 0)
    {
        if(IS_POST)
        {
            $model = M('');
            $data = I('post.');
            if($data['telecomname'] == 0 || $data['paycode'] == 0)
            {
                $this->error('请选择通道和计费代码');
                return;
            }
            $tablename = 'bks_'.'pseudocode';
            $this->adddataforsql($model,$tablename,$data,true,$id);
        }
        else
        {
            Cookie('__forward__',$_SERVER['REQUEST_URI']);
           
            $codecontrol = M('codecontrol')->where(array('id'=>$id))->select();
            $info = $codecontrol['0'];
            $gamelist = M('gamelist')->field('id,name,appid')->where(array('appid'=>$info['appid']))->select();
            $colist = M('colist')->field('id,name,coid')->where(array('coid'=>$info['coid']))->select();
            $proplist = M('proplist')->field('id,name')->where(array('id'=>$info['prop']))->select();
            $egt = $info['egt'];
            $egtlist = \PublicData::$egtlist;
            $egtinfo['egtid'] = $egt;
            $pseudocodeinfo['egt'] = $egtlist[$egtinfo['egtid']]['name'];
            $pseudocodeinfo['egtid'] = $egtlist[$egtinfo['egtid']]['id'];
            $telecomlist = M('telecom')->field('telecomname,utf8name,egt')->select();
            foreach($telecomlist as $k=>$value)
            {
                if($value['egt'] != $egt)
                {
                    unset($telecomlist[$k]);
                }
            }

            $this->assign('telecomlist',$telecomlist);
            $statuslist = \PublicData::$openstatic;
            $statuslist['2']['name'] = '自动化';
            $this->assign('statuslist',$statuslist);
            $this->assign('gamelist',$gamelist);
            $this->assign('proplist',$proplist);
            $this->assign('egtlist',$egtlist);
            $this->assign('colist',$colist);
            $this->assign('info',$info);            
            $this->assign('pseudocodeinfo',$pseudocodeinfo);
            $this->display();
        }
    }
    public function openall()
    {
        $data = I('post.');
        $count = count($data);
        if($count !== 0)
        {
            foreach($data as $value)
            {
                $tablename = 'pseudocode';
                foreach($value as $value2)
                {
                    $upatedata = array('status'=>1);
                    $isok = M($tablename)->where(array("id"=>$value2))->data($upatedata)->save();
                }
            }
            if($isok === false)
            {
                $this->error("找写代码的解决");
                exit();
            }
            else
            {
                $id = 0;
                action_log('修改数据', 'uptelecom', $id, UID);
                $this->success('修改成功', Cookie('__forward__'));
            }
        }
        else
        {
            $this->error("请选择你要操作的数据");
        }
    }
    public function closeall()
    {
        $data = I('post.');
        $count = count($data);
        if($count !== 0)
        {
            foreach($data as $value)
            {
                $tablename = 'pseudocode';
                foreach($value as $value2)
                {
                    $upatedata = array('status'=>2);
                    $isok = M($tablename)->where(array("id"=>$value2))->data($upatedata)->save();
                }
            }
            if($isok == false)
            {
                $this->error("找写代码的解决");
                exit();
            }
            else
            {
                $id = 0;
                action_log('修改数据', 'uptelecom', $id, UID);
                $this->success('修改成功', Cookie('__forward__'));
            }
        }
        else
        {
            $this->error("请选择你要操作的数据");
        }
    }
    
    
    public function getgamelist()
    {
        if(IS_AJAX)
        {
            $id = I('type'); 
            $egtid = I('egtid');
            $gold = I('gold');
            $selectwhere = array('telecomname'=>$id);  // 需要优化'egt'=>$egtid,'payglod'=>$gold,'egt'=>$egtid
            $paycodelist = M('paycodelist')->field('telecomname,paycodename,paycode,utf8name')->where($selectwhere)->select();      
            $this->ajaxReturn($paycodelist);
        }
    }
    

    
    public function warning()
    {
        $model = M('codecontrol');
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
                $data = array('dayupforoutlets'=>$v);
                $outletslist = $model->where($selectwhere)->data($data)->save();
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
            }
            else
            {
                $paycodelist   =   $this->lists($model->where($map),$map);
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

