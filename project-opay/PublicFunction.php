<?php

/**
 * PublicFunction short summary.
 *
 * PublicFunction description.
 *
 * @version 1.0
 * @author admin
 */
class PublicFunction
{
    static public function getUTF8ForEnglish($utf8)
    {
        
    }
    static public function getClickSeachData($info=array(),$serchname = '')
    {
        $map = array();
        $selectwhere = array();
        foreach($info as $value)
        {
            if(isset($_GET[$value])){
                $selectwhere[$value]    =  (string)$_GET[$value];   //将$_get=1 的值 赋值给 如: $selectwhere['telecomname']=1
            }
        }
        if(isset($_GET[$serchname])){
            $map[$serchname]    =   array('like', '%'.(string)$_GET[$serchname].'%');
        }
        //$map[$serchname] = array('like', '%'.(string)$_GET[$serchname].'%');
        //if(isset($_GET['name'])){
        //    $map['utf8name']    =   array('like', '%'.(string)$_GET['name'].'%');
        //}
        //if(isset($_GET['status_id'])){
        //    $selectwhere['status_id']    =   $_GET['status_id'];
        //}
        return array($selectwhere,$map);
    }
    /**
     * Summary of ischeckonenext 检测数据，如果不符合条件则直接退出代码执行
     * @param mixed $infos 要检测的数据，类型为数组
     * @return mixed
     */
    static public function ischeckonenext(&$infos)
    {
        foreach($infos as $value)
        {
            if($value == '')
            {
                return false;
            }
        }
        return true;
    }
    static public function ischeckrepeat(&$model,&$selectwhere,&$reson,&$oldid)
    {
        $isrepeat = $model->where($selectwhere)->select();
        
        
        if($reson === 'add')
        {
            if($isrepeat !== null) return false;
        }
        else if($reson === 'edit')
        {
            if($isrepeat !== null && $isrepeat[0]['id'] !== $oldid) return false;
        }

        return true;
    }
    static public function makemd5string($maxid = 0,$long = '')
    {
        if($maxid == 0)
            return 0;      
        $extData2 = md5($maxid);
        $extData2 = substr($extData2,1,8);
        return $extData2;
    }
    static public function returnAction($time,$iswhere = 'hour')
    {
    //    $time = $vncountinfo[0]['time'];
        $nowdate = \PublicFunction::getTimeForYHD(time());
        $olddate = \PublicFunction::getTimeForYHD($time);
        $nowmonth = \PublicFunction::getTimeForMonth(time());
        $oldmonth = \PublicFunction::getTimeForMonth($time);
        $nowday = \PublicFunction::getTimeForDay(time());
        $oldday = \PublicFunction::getTimeForDay($time);
        $hour = \PublicFunction::getTimeForHour($time);
        $oldhour = \PublicFunction::getTimeForHour(time());

            if($oldday != $nowday)
            {
            //    M('ordertelecom')->data($data)->add();
                return 'add';
            }
            if($iswhere == 'day')
            {
                return 'update';
            }
            if($oldhour != $hour)
            {
                return 'add';
            }
        
        return 'update';
    }
    static public function getTimeForString($time)
    {
        $time = date('Y-m-d H:i:s',$time);
     //   return $time;
        return strtotime($time);
    }
    //获取当前时间戳 ——小时
    static public function getTimeForHour($time)   
    {
        $time = date('H:i:s',$time);
        $time = explode(':',$time);
        $time = $time[0];
        return $time;
    }
    //获取当前时间戳时间 ——分
    static public function getTimeForMin($time)
    {
        $time = date('H:i:s',$time);
        $time = explode(':',$time);
        $time = $time[1];
        return $time;
    }
    static public function getTimeForSec($time)
    {
        $time = date('H:i:s',$time);
        $time = explode(':',$time);
        $time = $time[2];
        return $time;
    }
    //获取当前时间戳时间 ——日
    static public function getTimeForDay($time)
    {
        $time = date('Y-m-d',$time);
        //   return $time;
        $time = explode('-',$time);
        $time = $time[2];
        return $time;
    }
    //获取当前时间戳时间 ——月
    static public function getTimeForMonth($time)
    {
        $time = date('Y-m-d',$time);
        //   return $time;
        $time = explode('-',$time);
        $time = $time[1];
        return $time;
    }
    //获取当前时间戳时间 ——年
    static public function getTimeForYear($time)
    {
        $time = date('Y-m-d',$time);
        //   return $time;
        $time = explode('-',$time);
        $time = $time[0];
        return $time;
    }
    //获取当前时间戳时间 ——年月日
    static public function getTimeForYHD($time)
    {
        $time = date('Y-m-d',$time);
        //   return $time;
      //  $time = explode('-',$time);
        return $time;
    }
    //获取当前时间戳时间 ——年月日
    static public function getTimeForYH($time)
    {
        $time = date('Y_m',$time);
        //   return $time;
        //  $time = explode('-',$time);
        return $time;
    }
    //////
    static public function   xml_encode($data, $charset = 'utf-8', $root = 'so') {
        $xml = '<?xml version="1.0" encoding="' . $charset .'"?>';
        $xml .= "<{$root}>";
        $xml .= PublicFunction::array_to_xml($data);   
        $xml .= "</{$root}>";
        return $xml;
    }

    static public function xml_decode($xml, $root = 'so') {
        $search = '/<(' . $root . ')>(.*)<\/\s*?\\1\s*?>/s';
        $array = array();
        if(preg_match($search, $xml, $matches)){
            $array =PublicFunction::xml_to_array($matches[2]);
        }
        return $array;
    }

    static function array_to_xml($array) {
        if(is_object($array)){
            $array = get_object_vars($array);
        }
        $xml = '';
        foreach($array as $key => $value){
            $_tag = $key;
            $_id = null;
            if(is_numeric($key)){
                $_tag = 'item';
                $_id = ' id="' . $key . '"';
            }
            $xml .= "<{$_tag}{$_id}>";
            $xml .= (is_array($value) || is_object($value)) ?PublicFunction::array_to_xml($value) : htmlentities($value);
            $xml .= "</{$_tag}>";
        }
        return $xml;
    }

    static function xml_to_array($xml) {
        $search = '/<(\w+)\s*?(?:[^\/>]*)\s*(?:\/>|>(.*?)<\/\s*?\\1\s*?>)/s';
        $array = array ();
        if(preg_match_all($search, $xml, $matches)){
            foreach ($matches[1] as $i => $key) {
                $value = $matches[2][$i];
                if(preg_match_all($search, $value, $_matches)){
                    $array[$key] =PublicFunction::xml_to_array($value);
                }else{
                    if('ITEM' == strtoupper($key)){
                        $array[] = html_entity_decode($value);
                    }else{
                        $array[$key] = html_entity_decode($value);
                    }
                }
            }
        }
        return $array;
    }
    static function checkegtnum($egt)
    {
        //3 移动
        //1 电信
        //2 联通
        if($egt == 1) $egt=1;
        else if($egt == 2) $egt=2;
        else if($egt == 3) $egt = 3;
        return $egt;
    }
}