<?php
/**
 * CodeController short summary.
 *
 * CodeController description.
 *
 * @version 1.0
 * @author bksen_sjl
 */
namespace Admin\Controller;
class OrderController extends AdminController
{   
    public function count(){
        $time = time();
        $startime = I('get.timestart');
        $endtime = I('get.timestart2');
        if($startime){
            $search['startime'] = $startime;
            $startime = $startime.' 00:00:00';
            $startime = strtotime($startime);
        }else{
            $startime = date('Y-m-d',$time);
            $search['startime'] = $startime;
            $startime = $startime.' 00:00:00';
            $startime = strtotime($startime);
        }
        if($endtime){
            $search['endtime'] = $endtime;
            $endtime = $endtime.' 23:59:59';
            $endtime = strtotime($endtime);
        }else{
            $endtime = date('Y-m-d',$time);
            $search['endtime'] = $endtime;
            $endtime = $endtime.' 23:59:59';
            $endtime = strtotime($endtime);
        }
        $selectwhere = " time > $startime and time < $endtime ";
        
        $table = date('Y_m',$time).'_count';
        if(!$_GET['p']){
            $_GET['p'] = 0;
        }
        $field = "FROM_UNIXTIME(time,'%Y-%m-%d') as day,FROM_UNIXTIME(time,'%k') as hour,FROM_UNIXTIME(time,'%Y-%m-%d %H:%i:%S') as time,id,bksid,spid,column,province,requesttype,click,download,requestnum";
        $search['spid'] = I('get.spid'); 
        if($search['spid']!=''){
            $where[] = "`spid`=".$search['spid'];
        }
        $search['column'] = I('get.column'); 
        if( $search['column']!=''){
            $where[] = "`column`=".$search['column'];
        }
        if($where){
            foreach($where as $k=>$v){
                $www .= $v.' and ';
            }   
            $selectwhere = $www.$selectwhere;
        }
        
        $search['hour'] = I('get.ishour'); 
        if( $search['hour']!=''){
            $group[] = 'hour';
        }
        $search['day'] = I('get.isday'); 
        if( $search['day']!=''){
            $group[] = 'day';
        }
        if($group){
            foreach($group as $k=>$v){
                $groupb .= $v.',';
            }
            $groupb = rtrim($groupb,',');
            $field = "FROM_UNIXTIME(time,'%Y-%m-%d') as day,FROM_UNIXTIME(time,'%k') as hour,FROM_UNIXTIME(time,'%Y-%m-%d %H:%i:%S') as time,id,bksid,spid,column,province,requesttype,sum(click) as click,sum(download) as download,sum(requestnum) as requestnum";
        }
        //if($www){
        $list = M($table)->field($field)->where($selectwhere)->group($groupb)->page($_GET['p'].',20')->order('id desc')->select();
            $a = M($table)->_Sql();
            $listcount =  M($table)->field($field)->where($selectwhere)->group($groupb)->select();// 查询满足要求的总记录数
            $count =  M($table)->field($field)->where($selectwhere)->group($groupb)->count();// 查询满足要求的总记录数
        //}else{
        //    $list = M($table)->field($field)->page($_GET['p'].',20')->group($groupb)->order('id desc')->select();
        //    $aaa= M($table)->_sql();
        //    $count =  M($table)->field($field)->group($groupb)->count();// 查询满足要求的总记录数
        //    $listcount =  M($table)->field($field)->group($groupb)->select();// 查询满足要求的总记录数
        //}
       
        $Page = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        $goods = M('findgoods')->select();
        foreach($list as $k=>$v){
            if($v['column']==1){
                $list[$k]['column'] = '发现';
            }elseif($v['column']==2){
                $list[$k]['column'] = '精品';
            }elseif($v['column']==3){
                $list[$k]['column'] = '排行榜';
            }
            if($v['requesttype']==1){
                $list[$k]['requesttype'] = 'app';
            }elseif($v['requesttype']==2){
                $list[$k]['requesttype'] = 'web';
            }
            foreach($goods as $k1=>$v1){
                if($v['spid']==$v1['id']){
                $list[$k]['spid'] = $v1['goodname'];
                }
            }
        }
        $statistics['requestnum'] = 0;
        $statistics['download'] = 0;
        $statistics['click'] = 0;
        foreach($listcount as $k=>$v){
            $statistics['requestnum'] += $v['requestnum'];
            $statistics['download'] += $v['download'];
            $statistics['click'] += $v['click'];
        }
        $this->assign('statistics',$statistics);        
        $this->assign('countsearch',$search);
        $this->assign('goods',$goods);
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('list',$list);
        $this->display();
    }
}






