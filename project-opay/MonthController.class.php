<?php
namespace Admin\Controller;

/**
 * Month short summary.
 *
 * Month description.
 *
 * @version 1.0
 * @author admin
 */
//配置页面
class MonthController extends AdminController
{
    var $model = '';
    public function index()
    {
        $model = M('monthlist');
        $info = $model->select();
        $this->assign('list',$info);
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->display();
    }
    public function addmysqltable_2($sql ='',$isend = false)
    {
        $c = $this->model->execute($sql,true);
     //   print_r($a=$this->model->_sql());
        if($c !== false)
        {
        }
        else
        {
            $this->error('新增失败,创建表失败'); 
            $this->model->rollback();
            exit();
        }
    }
    public function addmysqltabel($tabelname = '')
    {
        //破解订单
        $string = 'CREATE TABLE `tnserve_n`.`'.$tabelname;
        // 
        //$sql = $string.'orderid` (
        //  `id` INT NOT NULL AUTO_INCREMENT,
        //  `name` INT(11) NOT NULL,
        //   index `name` (`name`),
        //   PRIMARY KEY (`id`));';
        //$this->addmysqltable_2($sql);
        //破解订单
        //$string = 'CREATE TABLE `tnserve`.`'.$tabelname;
        $sql = $string.'vnorder` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `type` varchar(45) NOT NULL,
          `co` varchar(45) NOT NULL,
          `cpgame` varchar(45) NOT NULL,
          `prop` varchar(45) NOT NULL,
          `telecom` varchar(45) NOT NULL,
          `extern` varchar(100) NOT NULL,
          `extern2` varchar(100) NOT NULL,
          `bksid` varchar(100) NOT NULL,
          `city` varchar(30) NOT NULL,
          `iccid_city` varchar(10) NOT NULL,
          `xystatus` int(1) NOT NULL,
          `orderstatus` int(1) NOT NULL,
          `time` int(11) NOT NULL,
          `egt` varchar(2) DEFAULT NULL,
          `paycode` varchar(45) NOT NULL,
          `imei` varchar(45) NOT NULL,
          `imsi` varchar(45) NOT NULL,
          `errorcode` int(5) NOT NULL,
          `status` int(2) NOT NULL DEFAULT 1,
          `adtype` int(2) DEFAULT NULL,
          `orderid` varchar(45) DEFAULT NULL,
          `orderno` varchar(45) DEFAULT NULL,
          `maxid` varchar(45) DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `extern2` (`extern2`),
          KEY `errorcode` (`errorcode`),
          KEY `cpgame` (`cpgame`),
          KEY `prop` (`prop`),
          KEY `co` (`co`)
          );';
        $this->addmysqltable_2($sql);
        //破解履历
        $sql = $string.'vnhistory` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `time` int(10) NOT NULL,
          `orderid` int(11) NOT NULL,
          `type` int(10) NOT NULL,
          `status` int(1) NOT NULL,
          `log` text NOT NULL,
          `adstatus` varchar(45) DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `orderid` (`orderid`),
          KEY `type` (`type`)
          );';         
        $this->addmysqltable_2($sql);
        //订单查询
        $sql = $string.'order` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `co` varchar(45) NOT NULL,
              `cpgame` varchar(45) NOT NULL,
              `prop` varchar(45) NOT NULL,
              `telecom` varchar(45) NOT NULL,
              `paycode` varchar(45) NOT NULL,
              `mobile` varchar(45) NOT NULL,
              `orderstatus` int(1) NOT NULL,
              `status` int(1) NOT NULL,
              `value` int(1) NOT NULL,
              `Iskl` int(1) NOT NULL,
              `extern2` varchar(100) NOT NULL,
              `bksid` varchar(100) NOT NULL,
              `extern` varchar(100) NOT NULL,
              `time` int(11) NOT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `extern2` (`extern2`),
              KEY `cpgame` (`cpgame`),
              KEY `prop` (`prop`),
              KEY `co` (`co`)
              );';
        $this->addmysqltable_2($sql);
        //数据统计-明细
        $sql = $string.'ordercount` (
              `id` int(11) NOT NULL,
              `day` int(2) NOT NULL,
              `hour` int(2) NOT NULL,
              `telecomname` varchar(45) NOT NULL,
              `co` varchar(45) NOT NULL,
              `cpgame` varchar(45) NOT NULL,
              `prop` varchar(45) NOT NULL,
              `paycode` varchar(45) NOT NULL,
              `msggold` double(10,2) NOT NULL,
              `payresult` int(10) NOT NULL,
              `xysuccess` int(10) NOT NULL,
              `paysuccess` int(10) NOT NULL,
              `mosuccess` int(10) NOT NULL DEFAULT 0,
              `badvalue` int(10) NOT NULL,
              `badgold` double(10,2) NOT NULL,
              `extern2` varchar(100) NOT NULL,
              `bksid` varchar(100) NOT NULL,
              `time` int(11) NOT NULL,
              `city` int(11) NOT NULL,
              `egt` int(11) NOT NULL,
              `status` int(2) DEFAULT 1,
              `apiorderid` varchar(45) DEFAULT NULL,
              `orderno` varchar(45) DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `id_UNIQUE` (`id`),
              KEY `telecomname` (`telecomname`),
              KEY `time` (`time`),
              KEY `paycode` (`paycode`),
              KEY `cpgame` (`cpgame`),
              KEY `prop` (`prop`),
              KEY `co` (`co`)
              );';
        $this->addmysqltable_2($sql);
        //订单履历
        $sql = $string.'orderhistory` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `oid` int(10) NOT NULL,
          `time` int(11) NOT NULL,
          `logtype` int(5) NOT NULL,
          `status` int(1) NOT NULL,
          `log` text NOT NULL,
          `appname` varchar(20) DEFAULT NULL,
          `coname` varchar(45) DEFAULT NULL,
          PRIMARY KEY (`id`)
          );';
        $this->addmysqltable_2($sql);
        //结算数据
        $sql = $string.'codata` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `time` int(11) NOT NULL,
          `day` int(2) NOT NULL,
          `egt` int(1) NOT NULL,
          `msggold` double(10,2) NOT NULL,
          `status` int(1) NOT NULL,
          `co` varchar(45) NOT NULL,
          `appid` varchar(45) NOT NULL,
          PRIMARY KEY (`id`)
          );';
        $this->addmysqltable_2($sql);
        //记录第一步请求数据
        $sql = $string.'order1data` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `orderid` varchar(45) NOT NULL,
          `mobile` varchar(45) NOT NULL,
          `telecomtype` varchar(45) NOT NULL,
          `code` varchar(45) NOT NULL,
          `coid` varchar(45) NOT NULL,
          `appid` varchar(45) NOT NULL,
          `propid` varchar(45) NOT NULL,
          `egt` varchar(45) NOT NULL,
          `returncode` varchar(45) DEFAULT NULL,
          PRIMARY KEY (`id`)
          );';
        $this->addmysqltable_2($sql);
        //自定义参数表
        $sql = $string.'extdata` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `coorderid` varchar(100) NOT NULL DEFAULT init,
          `bksid` varchar(100) NOT NULL,
          `uptime` datetime DEFAULT CURRENT_TIMESTAMP,
          `telid` varchar(45) DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `bksid_UNIQUE` (`bksid`)
          );';
        //手机号请求统计
        $sql = $string.'mobileorder` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `outlets` int(11) NOT NULL,
          `result` int(11) NOT NULL,
          `resultsuccess` int(11) NOT NULL,
          `time` int(11) NOT NULL,
          PRIMARY KEY (`id`)
          );';
        $this->addmysqltable_2($sql);
        //广告表
        $sql = $string.'adorder` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `type` varchar(45) DEFAULT NULL,
          `co` varchar(45) NOT NULL,
          `cpgame` varchar(45) NOT NULL,
          `teluse` varchar(45) DEFAULT 0,
          `telupdate` varchar(45) DEFAULT 0,
          `extern` varchar(100) DEFAULT NULL,
          `extern2` varchar(100) DEFAULT NULL,
          `bksid` varchar(100) NOT NULL,
          `telinstallnum` varchar(30) DEFAULT 0,
          `iccid_city` varchar(10) NOT NULL,
          `installnum` int(1) DEFAULT 0,
          `cpnum` int(1) DEFAULT 0,
          `time` int(11) NOT NULL,
          `egt` varchar(2) DEFAULT NULL,
          `fhnum` varchar(45) DEFAULT 0,
          `imei` varchar(45) NOT NULL,
          `imsi` varchar(45) NOT NULL,
          `kpnum` int(5) DEFAULT 0,
          `status` int(2) NOT NULL DEFAULT 0,
          `adtype` int(2) DEFAULT NULL,
          `orderid` varchar(45) DEFAULT NULL,
          `orderno` varchar(45) DEFAULT NULL,
          `adnum` int(10) DEFAULT 0,
          `adsnum` int(10) DEFAULT 0,
          PRIMARY KEY (`id`),
          UNIQUE KEY `extern2` (`extern2`),
          KEY `errorcode` (`kpnum`),
          KEY `cpgame` (`cpgame`),
          KEY `prop` (`teluse`),
          KEY `co` (`co`)
          );';
        $this->addmysqltable_2($sql);
    
        
        
        //CREATE TABLE `tnserve`.`bks_2016_06_mobileorder` (
    }
    public function add()
    {
        if(IS_POST)
        {
            
            //  $model = M('monthlist');
            
            //    $data = $model->create();
            $data   =   I('post.');         //获取URL传过来的值
            
            $this->model = M();
            $this->model->startTrans();
            if($data['month'] == '')
                $this->error("请选择月份");
            //    $this->error("实验数据-你暂时还不能对添加计费周期进行操作");
            $tablename =  C('DB_PREFIX').$data['month'].'_'.'2_';
            $this->addmysqltabel($tablename);
            $tablename =  C('DB_PREFIX').'monthlist';
            $this->adddataforsql($this->model,$tablename,$data,true,$id);
            //        $this->addmysqltabel();
            
            //  $isok = $this->model->table($tablename)->data($data)->add();
            //if($isok !== true)
            //{
            //    action_log('添加月份信息', '__forward__', UID);
            //    $this->success('新增成功', Cookie('__forward__'));
            //}
            //else
            //{
            //    $this->error("添加失败");
            //}
        }
        else
        {
            $this->meta_title = '添加计费周期';
            $this->display();
        }
    }
    
    /////////////下面是处理方法
    
    public function rmdirr($dirname) {
        if (!file_exists($dirname)) {
            return false;
        }
        if (is_file($dirname) || is_link($dirname)) {
            return unlink($dirname);
        }
        $dir = dir($dirname);
        if($dir){
            while (false !== $entry = $dir->read()) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                //递归
                $this->rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
            }
        }
        $dir->close();
        return rmdir($dirname);
    }
    public function other()
    {
        header("Content-type: text/html; charset=utf-8");
        //清文件缓存
        $dirs = array('./Runtime/');
        @mkdir('Runtime',0777,true);
        //清理缓存
        foreach($dirs as $value) {
            $this->rmdirr($value);
        }
        //     $this->assign("jumpUrl","__ROOT__/");
        $this->success('系统缓存清除成功！');
    }
}
