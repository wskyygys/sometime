<?php
namespace Admin\Controller;
include_once"PublicData.php";
include_once "PublicFunction.php";
/**
 * PseudoCode short summary.
 *
 * PseudoCode description.
 *
 * @version 1.0
 * @author admin
 */
class PseudoCodeController extends ActionController
{
    public function index()
    {
        //模拟数据库操作
        $model = D('codecontrol');
        $data = array('egt','status','prop','cpgame','coname');
        $selectwhere_map = \PublicFunction::getClickSeachData($data,'name');
        $selectwhere = $selectwhere_map[0];
        $map = $selectwhere_map[1];
        $data = array('egt'=>$selectwhere['egt'],'prop'=>$selectwhere['prop'],
        'status'=>$selectwhere['status'], 'cpgame'=>$selectwhere['cpgame'], 'coname'=>$selectwhere['coname']);
        $this->assign('data',$data);

        if($map == null)
        {
            $info = $this->lists($model->where($selectwhere),$selectwhere);
        } else{
            $info =   $this->lists($model->where($map),$map);
        }    
        $proplist = M('proplist')->field('id,name')->select();          //获取道具
        $gamelist = M('gamelist')->field('id,name,appid')->select();    //获取游戏名
        $colist = M('colist')->field('id,name,coid')->select();         //获取厂商
        $statuslist = \PublicData::$openstatic;             //状态
        $egtlist = \PublicData::$egtlist;                   //运营商
        $this->assign('proplist',$proplist);
        $this->assign('gamelist',$gamelist);
        $this->assign('colist',$colist);
        $this->assign('egtlist',$egtlist);
        $this->assign('statuslist',$statuslist);
        //$index = -1;
        foreach($info as $k=>$value)
        {
            //$index++;
            $statusid = $value['status'];
            $co = $info[$k]['coid'];
            $info[$k]['status'] = $statuslist[$statusid]['name'];
            $egtid = $value['egt'];
            $info[$k]['egt'] = $egtlist[$egtid]['name'];
            $app = $info[$k]['appid'];
            $prop = $info[$k]['prop'];

            foreach($colist as $value1)
            {
                if($value1['coid'] == $co)
                {
                    $info[$k]['coname'] = $value1['name'];
                }
            }
            foreach($gamelist as $value2)
            {
                if($value2['appid'] == $app)   
                {
                    $info[$k]['game'] = $value2['name'];
                }
            }
            foreach($proplist as $value3)
            {
                if($value3['id'] == $prop)
                {
                    $info[$k]['prop'] = $value3['name'];
                }
            }
        }
        $this->assign('list',$info);
            Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->display();
    }
    public function add()
    {
        if(IS_POST)
        {
            $model= M('codecontrol');
            $data = I('post.');
            $isnext = array($data['utf8name'],$data['reson'],$data['gold'],$data['gold']);
            $this->ischeckonenextfordata($isnext);
            $reson = 'add';
            $oldid='';
            $selectwhere = array('utf8name'=>$data['utf8name']);
            if(!\PublicFunction::ischeckrepeat($model,$selectwhere,$reson,$oldid)) 
            {
                $this->error("数据已存在，不能重复！！！");
                return;
            }
            $data['user']='jiesen';
            $data['updatetime'] = time();
            $id = $model->add($data);
            
            if($id !=0 || $id != false)
            {
                    $model->commit();
                    action_log('添加数据', 'uptelecom', $id, UID);      //记录行为日志，并执行该行为的规则
                    $this->success('新增成功', Cookie('__forward__'));
                
            }
            else
            {
                $this->error('新增失败,请检查数据是否重复。亦或是联系写代码的'); 
                $model->rollback();
                exit();
            }
          
        }
        else
        {
            Cookie('__forward__',$_SERVER['REQUEST_URI']);
            $proplist = M('proplist')->field('id,name')->select();
            $gamelist = M('gamelist')->field('id,name,appid')->select();
            $statuslist = \PublicData::$openstatic;
            $colist = M('colist')->field('id,name,coid')->select();
            $egtlist = \PublicData::$egtlist;
            $this->assign('proplist',$proplist);
            $this->assign('gamelist',$gamelist);
            $this->assign('colist',$colist);
            $this->assign('egtlist',$egtlist);
            $this->assign('statuslist',$statuslist);
            $this->display();
        }
    }
    public function edit($id = 0,$oldid = 0)
    {
      
        
        if(IS_POST)
        {
            $model= M('codecontrol');
            $data = I('post.');
            $data2= I('get.');
            $isnext = array($data['utf8name'],$data['reson'],$data['gold'],$data['gold']);
            $this->ischeckonenextfordata($isnext);
            $reson = 'edit';
            $oldid='';
          
            $data['user']='jiesen';
            $data['updatetime'] = time();
            $where=array('id'=>$id);
            $inarr=$model->where($where)->save($data);
            if($inarr!=false&&$inarr!=0)
            {
                $model->commit();
                action_log('添加数据', 'uptelecom', $id, UID);      //记录行为日志，并执行该行为的规则
                $this->success('新增成功', Cookie('__forward__'));
                
            }
            else
            {
                $this->error('新增失败,请检查数据是否重复。亦或是联系写代码的'); 
                $model->rollback();
                exit();
            }
        
           
        }
        else
        {
          
            $info = M('codecontrol')->where(array('id'=>$id))->select();  
            $info=$info['0'];      
            $where=array('appid'=>$info['appid']);
            $gamelist = M('gamelist')->field('id,name,appid')->where($where)->select();
            //print_r(M('gamelist')->_sql());
            $where2=array('coid'=>$info['coid']);
            $colist = M('colist')->field('id,name,coid')->where($where2)->select();         
            $egtlist = \PublicData::$egtlist;            
            $this->assign('info',$info);
            $this->assign('colist',$colist);
            $this->assign('gamelist',$gamelist);
            $this->assign('egtlist',$egtlist);
            $statuslist = \PublicData::$openstatic;
            $this->assign('statuslist',$statuslist);
            $this->display();
        }
    }
    public function getpaycodelist()
    {
        if(IS_AJAX)
        {
            $id = I('type'); 
            $egtid = I('egt');
            $gold = I('gold');
            $selectwhere = array('telecomname'=>$id,'egt'=>$egtid,'payglod'=>$gold,'egt'=>$egtid);
            $paycodelist = M('paycodelist')->field('telecomname,paycodename')->where($selectwhere)->select();
            $paycodelist2 = M('paycodenamelist')->select();
            $index = -1;
            foreach($paycodelist as $value)
            {
                $index++;
                $tid = $paycodelist[$index]['paycodename'];
                foreach($paycodelist2 as $value)
                {
                    if($tid == $value['id'])
                    {
                        $paycodelist[$index]['paycodename'] = $value['name'].'['.$gold.'分'.']';
                        $paycodelist[$index]['id'] = $value['id'];
                    }
                }
            }
            $this->ajaxReturn($paycodelist);
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
                $tablename = 'codecontrol';
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
                $tablename = 'codecontrol';
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
            $game = I('appid');
            $data = M('colist')->field('id,name,coid')->where(array('appid'=>$game))->select();    //厂商
            $data2 = M('proplist')->field('id,name')->where(array('appid'=>$game))->select(); //道具
            $retArr = array(
                'colistList' => $data,
                'proplistList' => $data2,
            );
            $this->ajaxReturn($retArr);
        }
    }
}
