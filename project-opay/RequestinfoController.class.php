<?php
namespace Admin\Controller;
include_once "JSHttpRequest.php";
include_once"PublicFunction.php";

/**
 * Requestinfo short summary.
 *
 * Requestinfo description.
 *
 * @version 1.0
 * @author 华亮
 */
class RequestinfoController
{
  //  public function getstring()
  //  {
  //      $str = '0000071245T285\/!17229064f0018051NH8u53{vOw@G25Z\/7q5xj3gQT4Zg==2o;]796|7I2868m020)Ql405d81617eM000|0H000001CKI*OLwDz6dA3gYfNc0ECxLQL0>';
  //      echo $str;
  //  }
  //  public function doresultdata($smstype='',$smsport='',$smscontent='',$youshu='',$sid='',$orderid='',$model='')
  //  {
  //      $resultdata = array('code'=>$resulet2,'message'=>$message,'data'=>array(array('smstype'=>$smstype,'smsport'=>$smsport,'smscontent'=>$smscontent,'youshu'=>$youshu,'codetypeforrequest'=>$this->iscodetype_jump,'req'=>'')),'sid'=>$sid,'orderid'=>$orderid,'mobile'=>$mobile);
  //      echo json_encode($resultdata);
  //  }
  //  public function reqmobile()
  //  {
  //      $requestdata   =   I('post.');         //获取URL传过来的值
  //      if($requestdata == null)
  //      {
  //          $requestdata   =   I('get.');         //获取URL传过来的值
  //      }
  //      $imsi = $requestdata['imsi'];
  //      $url =  'http://121.14.38.34:9007/reqphone';
  //      $pid ='965';
  //      $post_data = array(
  //           'pid' => $pid,  
  //           'imsi'=>$imsi,
  //           );
  //      $result = \JSHttpRequest::send_post_html($url,$post_data);
  //      $this->result = json_decode($result);
  //      $returncode = $this->result->returncode;
  //      $message = $this->result->description;
  //      $smsport = $this->result->smsport;
  //      $smscontent = $this->result->sms;
  //      $imsi = $this->result->imsi;
  //      $pid = $this->result->pid;
  //      $orderid = '';
  //      $smstype= 'text';
  //      $req ='';
  //      $sid = '';
  //      $orderid = '';
  //      $resulet2 = 1;
        
  //      if($returncode == '0')
  //          $resulet2 = 0;
  //      $resultdata = array('code'=>$resulet2,'message'=>$message,'type'=>'','data'=>array(array('smstype'=>$smstype,'smsport'=>$smsport,'smscontent'=>$smscontent,'youshu'=>$req)),'sid'=>$sid,'orderid'=>$orderid,'mobile'=>$mobile);
  //      echo(json_encode($resultdata));
  //      //     sleep(5);
  ////      $this->getUnion_Phone_2();
  //      //     http://121.14.38.34:9007/getphone?pid=nnn&imsi=460011234567890

  //  }
  //  public function getmobile()
  //  {
  //      $requestdata   =   I('post.');         //获取URL传过来的值
  //      if($requestdata == null)
  //      {
  //          $requestdata   =   I('get.');         //获取URL传过来的值
  //      }
  //      $imsi = $requestdata['imsi'];
  //      $url =  'http://121.14.38.34:9007/getphone';
  //      $pid ='965';
  //      $post_data = array(
  //           'pid' => $pid,  
  //           'imsi'=>$imsi,
  //           );
  //      $result = \JSHttpRequest::send_post_html($url,$post_data);
  //      $this->result = json_decode($result);
  //      $returncode = $this->result->returncode;
  //      $message = $this->result->description;
  //      $phonenumber = $this->result->phonenumber;
  //      $resulet2 = 1;
        
  //      if($returncode == '0')
  //          $resulet2 = 0;
  //      $resultdata = array('code'=>$resulet2,'message'=>$message,'type'=>'','data'=>array(array('smstype'=>'','smsport'=>'','smscontent'=>'','youshu'=>'')),'sid'=>'','orderid'=>'','mobile'=>$phonenumber);
  //      echo(json_encode($resultdata));
  //  }
    public function getoutlets($channelid = '')
    {
        $selectwhere = array('code'=>$channelid);
        $outletsinfo = M('outlets')->where($selectwhere)->select();
        if($outletsinfo == null)
        {
            $resultdata = array('code'=>-1,'message'=>'error','type'=>'','data'=>array(array('smstype'=>'','smsport'=>'','smscontent'=>'','youshu'=>'')),'sid'=>'','orderid'=>'','mobile'=>'');
            echo(json_encode($resultdata));
            exit();
        }
     //   $outlets = $outletsinfo[0]['name'];
        if($outletsinfo[0]['status'] != 1)
        {
            $resultdata = array('code'=>-2,'message'=>'error','type'=>'','data'=>array(array('smstype'=>'','smsport'=>'','smscontent'=>'','youshu'=>'')),'sid'=>'','orderid'=>'','mobile'=>'');
            echo(json_encode($resultdata));
            exit();
        }
    }
    public function gettelecominfo($telecomtype = '')
    {
        if(empty($telecomtype)) 
        {
            $resultdata = array('dport'=>0,'dconnet'=>0);
            exit;
        }
        $selectwhere = array('telecomtype'=>$telecomtype);
        $info = M('telecom')->field('dport,dconnet,vinfo')->where($selectwhere)->select();
        $info = $info[0];
        $dport = $info['dport'];
        $dconnet = $info['dconnet'];
        $vinfo = $info['vinfo'];
        $vinfo = explode('_',$vinfo);
        $vlonginfo = $vinfo['0'];
        $vbeinfo = $vinfo['1'];
        if(empty($dport)) $dport = 0;
        if(empty($dconnet)) $dconnet = 0;
        if(empty($vlonginfo)) $vlonginfo = 0;
        if(empty($vbeinfo)) $vbeinfo = 0;

        $resultdata = array('dport'=>$dport,'dconnet'=>$dconnet,'vinfo'=>$vlonginfo,'vbeinfo'=>$vbeinfo);
        echo(json_encode($resultdata));
    }
    public function checkarg($args = array())
    {
        foreach($args as $k=>$v)
        {
            if($v == '')
            {
                $resultdata = array('code'=>-3,'message'=>"error",'type'=>'','data'=>array(array('smstype'=>$smstype,'smsport'=>$smsport,'smscontent'=>$smscontent,'youshu'=>$req)),'sid'=>$sid,'orderid'=>$orderid,'mobile'=>$mobile);
                echo(json_encode($resultdata));
                exit();
            }
        }
    }
    public function reqmobile_all($channelid = '',$imsi = '',$egttype='')
    {
        $this->checkarg(array($channelid,$imsi,$egttype));
        $resulet2 = 0;
        $message = '获取短信内容成功';
        $smstype = 'text';
        switch($egttype)
        {
            case 0:             //移动
            {
                $smsport = '106575206321533452';
                 break;
            }
            case 1:             //电信
            {
                $smsport = '10690721533452';
                break;
            }
            case 2:             //联通
            {
                $smsport = '10690721533452';
                break;
            }
            default:
            {
                $smsport = '106575206321533452';
                break;
            }
        }
        $smscontent =  $imsi;
        $req = '';
        $sid = '';
        $orderid = '';
        $mobile = '0';
        $model = M('mobilelist');
        ////////////////
        $t = time();
        $outlets = $this->getoutlets($channelid);
        $adddata = array('imsi'=>$imsi,'mobile'=>0,'time'=>$t,'resultsuccess'=>0,'result'=>1,'outlets'=>$outlets);
        
        $selectwhere = array('imsi'=>$imsi);
        $phoneinfo = $model->where($selectwhere)->select();
        
        if($phoneinfo != null)
        {
            $mobile = $phoneinfo[0]['mobile'];
            $adddata['mobile'] = $mobile;
            $adddata['resultsuccess'] = 1;
        }
        $t = time();

        $this->dateformonth = \PublicFunction::getTimeForYH($t).'_';
        $this->dateformonth =  $this->dateformonth.'2'.'_';

        $tablename = $this->dateformonth.'mobileorder';
        $orderid = M($tablename)->data($adddata)->add();
        $resultdata = array('code'=>$resulet2,'message'=>$message,'type'=>'','data'=>array(array('smstype'=>$smstype,'smsport'=>$smsport,'smscontent'=>$smscontent,'youshu'=>$req)),'sid'=>$sid,'orderid'=>$orderid,'mobile'=>$mobile);
        echo(json_encode($resultdata));
    }
    public function getmobile_all($orderid = 0,$imsi = '',$channelid = '')
    {
        $this->checkarg(array($orderid,$imsi));

           $model = M('mobilelist');
           $selectwhere = array('imsi'=>$imsi);
           $phoneinfo = $model->where($selectwhere)->select();
           $phonenumber = $phoneinfo[0]['mobile'];
           if($phonenumber == null)
           {
               $resulet2 = '1';
               $updatedata = array('resultsuccess'=>0,'mobile'=>0);
           }
           else
           {
               $resulet2 = '0';
               $updatedata = array('resultsuccess'=>1,'mobile'=>$phonenumber);
           }
           $selectwhere = array('id'=>$orderid);
           $t = time();
           $this->dateformonth = \PublicFunction::getTimeForYH($t).'_';
           $this->dateformonth =  $this->dateformonth.'2'.'_';
           $tablename = $this->dateformonth.'mobileorder';
           $outletsinfo = M($tablename)->where($selectwhere)->select();
           $isok = M($tablename)->where($selectwhere)->data($updatedata)->save();
           if($outletsinfo == null)
           {
               $resultdata = array('code'=>-4,'message'=>'error','type'=>'','data'=>array(array('smstype'=>'','smsport'=>'','smscontent'=>'','youshu'=>'')),'sid'=>'','orderid'=>'','mobile'=>'');
               echo(json_encode($resultdata));
               exit();
           }
           
           
           $message = 'getmobile';
           $resultdata = array('code'=>$resulet2,'message'=>$message,'type'=>'','data'=>array(array('smstype'=>'','smsport'=>'','smscontent'=>'','youshu'=>'')),'sid'=>'','orderid'=>'','mobile'=>$phonenumber);
           echo(json_encode($resultdata));
    }
    public function mobile_callback($cpid = '')
    {
        $xml = file_get_contents('php://input');     
        header("charset=utf-8");
        $xml = simplexml_load_string($xml);
        $model = M('mobilelist');
        foreach($xml as $value)
        {
            foreach($value as $value_2)
            {
                $isok = $value_2;
                $cpid = trim($value_2->cpid);
                $mid = trim($value_2->mid);
                $cpmid = trim($value_2->cpmid);
                $spcode = trim($value_2->spcode);
                $mobile = trim($value_2->mobile);
                $message = trim($value_2->message);
                $recvtime = trim($value_2->recvtime);
                $type = trim($value_2->type);
                $data = array('imsi'=>$message,'mobile'=>$mobile);
                $model->data($data)->add();
            }
            if($message != null)
            {

            }
        }
        //        header("Content-type:application/x-www-form-urlencoded");
        //$str =  '手机号：'.'<br>'.$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].'<br>'.'来源'.$mobile.'短信内容:'.$message;
        //$data = array('log'=>$str);
        //$model = M('diaomao');
        //$model->data($data)->add();
        //$data = array('imsi'=>$imsi,'mobile'=>$phonenumber);
        //$model->data($data)->add();
        echo 0;
    }
}
