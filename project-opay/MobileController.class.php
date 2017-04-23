<?php
namespace Admin\Controller;
include_once"PublicData.php";
include_once"PublicFunction.php";
/**
 * ChinaMobile short summary.
 *
 * ChinaMobile description.
 *
 * @version 1.0
 * @author Administrator
 */
class MobileController extends AdminController
{
    public function index($imsi = '',$imei = '',$code = '',$mobile='',$extData='',$iccid='',$ip='')
    {
        $time1 = '';
        $time2 = '';
        $selectwhere = array();
        $seltcteach=$_GET['selectall'];
        $eachename= \PublicData::$eachename;
        if(isset($_GET['name'])){
                if($seltcteach == 'imei')
                {
                    $map['imei']    =   (string)$_GET['name'];
                }
                else if($seltcteach == 'imsi')
                {
                    $map['imsi']    =   (string)$_GET['name'];
                }
                else if($seltcteach == 'id')
                {
                    $map['id']    =  $_GET['name'];
                    $seltcteach =$eachename[1]['name'];
                }
                else if($seltcteach == 'mobile')
                {
                    $map['mobile']    =  $_GET['name'];
                    $seltcteach =$eachename[2]['name'];
                }
                else if($seltcteach == 'mark')
                {
                    $map['mark']    =  array('like', '%'.(string)$_GET['name'].'%');
                    $seltcteach =$eachename[3]['name'];
                }
                else if($seltcteach == 'os_model')
                {
                    $map['os_model']    =  array('like', '%'.(string)$_GET['name'].'%');
                    $seltcteach =$eachename[4]['name'];
                }
                else if($seltcteach == 'city')
                {
                    $map['city']    = array('like', '%'.(string)$_GET['name'].'%');
                    $seltcteach =$eachename[5]['name'];
                }
            //$len = strlen($_GET['name']);
            
            //if($len == 11)
            //{
            //    $map['mobile']    =  array('like', '%'.(string)$_GET['name'].'%');
            //}
            //else
            //{
            //    $map['imsi']    =  array('like', '%'.(string)$_GET['name'].'%');
            //}
        }
        if(isset($_GET['status'])){
            $selectwhere['status']    =   (string)$_GET['status'];
        }
        if(isset($_GET['cityname'])){
            $selectwhere['city']    =   (string)$_GET['cityname'];
        }
        $time = time();
        $this->dateformonth = \PublicFunction::getTimeForYH($time).'_';
        $this->dateformonth =  $this->dateformonth.'2'.'_';

  //      $tablename = $this->dateformonth.'vnorder';
        $model2 = M('mobilelist');
        $outletsinfo = M('outletslist')->select();

        $data = array('status'=>$selectwhere['status'],'selectall'=>$seltcteach,'imei'=>$map['imei'],
            'imsi'=>$map['imsi'],'id'=>$seltcteach,'mobile'=>$seltcteach,'mark'=>$seltcteach,'os_model'=>$seltcteach,'city'=>$seltcteach,'iccid_cityt'=>$selectwhere['city']);
        if($map == null)
        {
            $info   =   $this->lists($model2->where($selectwhere),$selectwhere);
            $a=M()->_sql();
        }
        else
        {
            $info   =   $this->lists($model2->where($map),$map);
        }
        $telecomtypeinfo = M('telecomtypelist')->select();
        $telecominfo = M('telecomlist')->select();
        $statuslist = \PublicData::$openstatic;
        $citylist = \PublicData::$city;        //获取省份
        $index = -1;
        foreach($info as $k=>$value)
        {
            $index++;
            $cityid = $info[$k]['city_id'];
            if($cityid == 50)
                $info[$k]['city']='其他';
            else
            {
                foreach($citylist as $value6)
                {
                    $id = $value6['id'];
                    if((int)$cityid == $id)
                    {
                        $info[$k]['city'] = $value6['city'];
                    }
                }
            }
            $id = $value['status'];
            $info[$index]['status'] = $statuslist[$id]['name']; 
            $info[$index]['time'] = date("Y-m-d H:i:s", $value['time']);
        }
       // print_r($info);
       // print_r($data);
        $this->assign('data',$data);
        $this->assign("list",$info);
        $this->assign("citylist",$citylist);
        $this->assign("type",$telecomtypeinfo);
        $this->assign("outlets",$outletsinfo);
        $this->assign("telecom",$telecominfo);
        $this->assign("status",$statuslist);

        $this->display();        
    }
    public function delete($id='')
    {
        $model = M('mobilelist');
        $selectwhere = array('id'=>$id);
        $isok = $model->where($selectwhere)->delete();
        if($isok !== false)
        {
            $this->success("删除成功");
        }
        else
        {
            $this->success("删除失败");
        }
  //      var_dump($id);
    }
    public function openthe($id = '')
    {
        $model = M('mobilelist');
        $selectwhere = array('id'=>$id);
        $updatedata = array('status'=>1);
        $isok = $model->where($selectwhere)->data($updatedata)->save();
        if($isok !== false)
        {
            $this->success("删除成功");
        }
        else
        {
            $this->success("删除失败");
        }
    }
    public function closethe($id = '')
    {
        $model = M('mobilelist');
        $selectwhere = array('id'=>$id);
        $updatedata = array('status'=>2);
        $isok = $model->where($selectwhere)->data($updatedata)->save();
        if($isok !== false)
        {
            $this->success("删除成功");
        }
        else
        {
            $this->success("删除失败");
        }
    }
    public function openall($ids = '')
    {
        $model = M('mobilelist');

        foreach($ids as $k=>$v)
        {
            $selectwhere = array('id'=>$v);
            $updatedata = array('status'=>1);
            $isok = $model->where($selectwhere)->data($updatedata)->save();
        }
        if($isok !== false)
        {
            $this->success("删除成功");
        }
        else
        {
            $this->success("删除失败");
        }
    }
    public function closeall($ids = '')
    {
        $model = M('mobilelist');

        foreach($ids as $k=>$v)
        {
            $selectwhere = array('id'=>$v);
            $updatedata = array('status'=>2);
            $isok = $model->where($selectwhere)->data($updatedata)->save();
        }
        if($isok !== false)
        {
            $this->success("删除成功");
        }
        else
        {
            $this->success("删除失败");
        }
    }
    public function deleteall($ids = '')
    {
        $model = M('mobilelist');

        foreach($ids as $k=>$v)
        {
            $selectwhere = array('id'=>$v);
            $isok = $model->where($selectwhere)->delete();
        }
        if($isok !== false)
        {
            $this->success("删除成功");
        }
        else
        {
            $this->success("删除失败");
        }
    }
}
