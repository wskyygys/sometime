<?php
namespace Admin\Controller;
include_once "PublicData.php";
include_once"PublicFunction.php";
/**
 * Class1 short summary.
 *
 * Class1 description.
 *
 * @version 1.0
 * @author admin
 */
class PropController extends AdminController
{
    public function index()
    {
        
        $model = D('proplist');
        $data = array('status','name','appid','propid','gold');
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'sname');
        $M1 = I('get.sname');
        if(isset($_GET['sname'])){
            $len = strlen($_GET['sname']);
            if($len == 11)
            {
                $selectname = 'propid';
            }
            else
            {
                $selectname = 'name';
            }
        }

        $selecw = $selectwhere_map[0];
        $data = array('status'=>$selecw['status'],'propid'=>$selecw['propid'],'appid'=>$selecw['appid']);
        //print_r($data);
        $this->assign('data',$data);
        $selectwhere =array();
        foreach($selecw as $k=>$v){
         
         $k='bks_proplist.'.$k;
         $selectwhere[$k]=$v;
        
        }
    
      
      
        $map = $selectwhere_map[1];
        $where['name']=array('like',$M1); 
        //模拟数据库操作 
        if($map == null)
        {
           
            // 查询数据
            $m= M('proplist');
            $data1   = $m
                ->join("left join bks_gamelist ON bks_proplist.appid = bks_gamelist.appid")              
                /* 查询指定字段，不指定则查询所有字段 */
                ->field('bks_gamelist.name as gamename,bks_proplist.id,bks_proplist.name,bks_proplist.propid,bks_proplist.appid,bks_proplist.time,bks_proplist.user,bks_proplist.status,bks_proplist.gold')
                // 查询条件
                 ->where($selectwhere)
                /* 默认通过id逆序排列 */
                ->order("bks_proplist.id DESC")
                /* 数据分页  ->page($page, $row) */
               
                /* 执行查询 */
                ->select();    
        }
        else
        {
           
            // 查询数据
            $m= M('proplist');
            $data1   = $m
                ->join("left join bks_gamelist ON bks_proplist.appid = bks_gamelist.appid")
                /* 查询指定字段，不指定则查询所有字段 */
                ->field('bks_gamelist.name as gamename,bks_proplist.id,bks_proplist.name,bks_proplist.propid,bks_proplist.appid,bks_proplist.time,bks_proplist.user,bks_proplist.status,bks_proplist.gold')
                // 查询条件
                ->where('bks_proplist.'.$selectname.' LIKE '.'\'%'.$M1.'%\'')
                /* 默认通过id逆序排列 */
                ->order("bks_proplist.id DESC")
                /* 数据分页  ->page($page, $row) */
               
                /* 执行查询 */
                ->select();
           //  $info=$m->_sql(); 
             //print_r($info);die;
            
        }
        $in=$m->_sql(); 
      
        
        $index = -1;
         $typelist = M('proplist')->field('id,propid,name,gold')->select();
        $statuslist = \PublicData::$openstatic;
        foreach($data1 as $value){
            $index++;
            $statusid = $value['status'];
            $data1[$index]['status'] = $statuslist[$statusid]['name'];
        }
       

      // print_r($typelist);
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        //游戏名称
        $gamelist = M('gamelist')->field('id,appid,name')->select();
        //print_r($data1);
    
        $this->assign('gamelist',$gamelist);
        $this->assign('statuslist',$statuslist);
        $this->assign('typelist',$typelist);
        $this->assign("list",$data1);
        $this->display();
    }
    public function deduction()
    {
        if(IS_POST)
        {
            $model = M('codecontrol');
            $data = $model->create();
            $data   =   I('post.');         //获取URL传过来的值
            $outletsname = $data['outletsname'];
            $egt = $data['egt'];
            if($outletsname == 0)
            {
                $selectwhere = array('egt'=>$egt);
            }
            else
            {
                $selectwhere = array('outlets'=>$outletsname,'egt'=>$egt);
            }
            $klb = $data['kouliangbi'];
            if($klb == '')
            {
                $this->error("请输入bad");
                return;
            }
            $kouliang = $data['kouliang'];
            $i = $klb %10;
            if($i != 0)
            {
                $this->error("只支持10的倍数");
                return;
            }
            $numbers = range (1,10); 
            //shuffle 将数组顺序随即打乱 
            shuffle ($numbers); 
            //array_slice 取该数组中的某一段 
            $num=10; 
            $result = array_slice($numbers,0,$num); 
            //       var_dump($result); 
            if($klb / 10);
            $i = $klb / 10;
            $i = (int)$i;
            for($index = 0;$index <$i;$index++)
            {
                $kouliangid = $kouliangid.$result[$index].'_';
            }
            //      dump($kouliangid);
            $data = array('kouliangbi'=>$klb,'kouliang'=>$kouliang,'kouliangid'=>$kouliangid,'kouliangvalue'=>0);
            if($model->where($selectwhere)->select() == null)
            {
                $this->error('渠道没有分配源代码');
                return;
            }
            else
            {
                //     $model = M('pseudocode');
                //       $model->where($selectwhere)->data(array('kouliangid'=>$kouliangid,'kouliangbi'=>$kouliangid))->save();
                $isok = $model->where($selectwhere)->data($data)->save();
                if($isok !== false)
                    //       action_log('添加版本信息', 'Upgrade', $id, UID);
                    $this->success('新增成功', Cookie('__forward__'));
                else
                    $this->error('错误');
            }
        }
        else
        {
            $outletlist = M('outletslist')->select();
            $chagelist = M('telecomlist')->select();
            $openstatus = \PublicData::$openstatic;
            $egtlist = \PublicData::$egtlist;
            $this->assign('outletlist',$outletlist);
            $this->assign('egtlist',$egtlist);
            $this->assign('openstatus',$openstatus);
            $this->display();
        }
    }
    public function addtype()
    {
        if(IS_POST)
        {
            $data = I('post.');
            $model = M();
            $tablename= C('DB_PREFIX').'gametypelist';    
            $this->adddataforsql($model,$tablename,$data,true,$id);
        }
        else
        {
            $info = M('gametypelist')->select();
            $this->assign('info',$info);
            Cookie('__forward__',$_SERVER['REQUEST_URI']);

            $this->display();
        }
    }
    public function add()
    {
        if(IS_POST)
        {
            $data   =   I('post.');         //获取URL传过来的值
            $this->ischeckonenextfordata(array($data['name']));
            $tablename= C('DB_PREFIX').'proplist';    
            $model = M();
            $model->startTrans();
            $id = 0;
            $data['time'] = time();
            $user_name= session('user_auth.username');
            $data['user'] = $user_name;
            $this->adddataforsql($model,$tablename,$data,false,$id);
            //$appid = md5($id.'bks_proplist');
            $time=date('Ymd');
            $updateid=$time.$id;
            $updatedata['propid'] = $updateid;
            $selectwhere = array('id'=>$id);
            $this->updatedatawhereforsql($model,$tablename,$updatedata,$selectwhere,true,$id);
            //$data['utf8name'] = $data['name'];
            //$data['name'] = $id;
            //$data['code'] = substr(md5($id),1,5);
            //if($data['appkey'] === '')
            //    $data['appkey'] = '123456';
            //$str = $data['appkey'];
            //$data['appkey'] = md5($str);
            //$data['user']='jiesen';
            //$tablename= C('DB_PREFIX').'outlets';    
            //$this->adddataforsql($model,$tablename,$data,true,$id);
        }
        else
        {
            Cookie('__forward__',$_SERVER['REQUEST_URI']);
            $gamelist = M('gamelist')->field('appid,name')->select();
            $colist = M('colist')->field('coid,name')->select();
            $statuslist = \PublicData::$openstatic;

            $this->assign('statuslist',$statuslist);
            $this->assign('colist',$colist);
            $this->assign('gamelist',$gamelist);
            $this->display();
        }
    }
    public function edit($id = 0,$oldid = 0,$oldkey = '')
    {
        if(IS_POST)
        { 
            $data   =   I('post.');         //获取URL传过来的值
            $id = $data['id'];
            unset($data['id']);
            $model = M();
            $tablename= C('DB_PREFIX').'proplist';    
            $data['time'] = time();
            $user_name= session('user_auth.username');
            $data['user'] = $user_name;
            $updatedata = $data;
            $selectwhere = array('id'=>$id);
            $this->updatedatawhereforsql($model,$tablename,$updatedata,$selectwhere,true,$id);
        }
        else
        {
            $info = M('proplist')->where(array('id'=>$id))->select();
            $info = $info['0'];
            $gamelist = M('gamelist')->field('appid,name')->select();
            $colist = M('colist')->field('coid,name')->select();
            $statuslist = \PublicData::$openstatic;

            $this->assign('statuslist',$statuslist);
            $this->assign('colist',$colist);
            $this->assign('gamelist',$gamelist);
            $this->assign('info',$info);
            $this->display();
        }
    }
    public function info($id = 0)
    {
        $this->display();
    }
    public function warning()
    {
        //    $tablename= C('DB_PREFIX').'outletslist';    
        if(IS_POST)
        {
            $time = time();
            $data   =   I('post.');         //获取URL传过来的值
            $waroutlets = $data['waroutlets'];
            $waroutletstime = $data['waroutletstime'];
            $dayupforoutlets = $data['dayupforoutlets'];
            foreach($waroutletstime as $k=>$v)
            {
                $selectwhere = array('name'=>$k);
                $data = array('warlongtime'=>$v);
                $outletslist = M('outlets')->where($selectwhere)->data($data)->save();
                //$selectwhere = array('name');

                $data = array('warbegintime'=>0);
                $isok = $outletslist = m('outlets')->where($selectwhere)->data($data)->save();
            }
            foreach($dayupforoutlets as $k=>$v)
            {
                $selectwhere = array('name'=>$k);
                $data = array('dayupforoutlets'=>$v);
                $outletslist = M('outlets')->where($selectwhere)->data($data)->save();
            }

            foreach($waroutlets as $k=>$v)
            {
                if($waroutletstime[$v] <= 0)
                {
                    $this->error("请填写渠道预警时间，已选择渠道的预警时间不能等于0");
                    exit();
                }
                $selectwhere = array('name'=>$v);
                $data = array('warbegintime'=>$time);
                $outletslist = M('outlets')->where($selectwhere)->data($data)->save();

            }
            $this->success('预警系统生效');
        }
        else
        {
            //          $outletslist = M('outlets')->select();
            $model = D('outlets');
            $data = array('status','type');
            $selectwhere_map = \PublicFunction::getClickSeachData($data,'utf8name');
            $selectwhere = $selectwhere_map[0];
            $map = $selectwhere_map[1];
            $data = array('status'=>$selectwhere['status'],'type'=>$selectwhere['type']);
            //模拟数据库操作
            if($map == null)
            {
                $outletslist   =   $this->lists($model->where($selectwhere),$selectwhere);
            }
            else
            {
                $outletslist   =   $this->lists($model->where($map),$map);
            }
            $index = -1;
            //$index2 = 0;
            foreach($outletslist as $k=>$v)
            {
                $index++;
                if($v['warbegintime'] != 0)
                {
                    $outletslist[$index]['id2'] = $v['name'];
                }
                $outletslist[$index]['id3'] = $v['warlongtime'];
            }
            $this->assign('outletslist',$outletslist);
            $this->display(); 
        }

    }
    public function getgamelist()
    {
        if(IS_AJAX)
        {
            $appid = I('appid');
            $data = M('colist')->field('coid,name')->where(array('appid'=>$appid))->select();
            $this->ajaxReturn($data);

        }
    }
}

