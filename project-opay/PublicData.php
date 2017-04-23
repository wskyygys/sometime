<?php

/**
 * PublicData short summary.
 *
 * PublicData description.
 *
 * @version 1.0
 * @author admin
 */
class PublicData
{
    static $needmobiletypelist = array(136,500003,);

    static $status2 = array(1=>array('id'=>1,'name'=>'检查数据'),array('id'=>2,'name'=>'成功定单'),array('id'=>3,'name'=>'下家同步'),
        array('id'=>4,'name'=>'失败订单'));
    static $logtype = array(1=>array('id'=>1,'name'=>'支付提交'),array('id'=>2,'name'=>'支付返回'),array('id'=>3,'name'=>'确定指令'),
    array('id'=>4,'name'=>'客户端请求'));
    static $logtype1 = array(1=>array('id'=>1,'name'=>'客户端提交'),array('id'=>2,'name'=>'广告返回'));
    static $isrepeat = array(1=>array('id'=>1,'name'=>'是'),array('id'=>2,'name'=>'否'));
    static $openstatic = array(1=>array('id'=>1,'name'=>'允许'),array('id'=>2,'name'=>'关闭'));
    static $advstatic = array(1=>array('id'=>'kpad','name'=>'开屏广告'),array('id'=>'fhad','name'=>'返回广告'),array('id'=>'cpad','name'=>'插屏广告'),array('id'=>'installad','name'=>'安装下载'),array('id'=>'telad','name'=>'通知栏'),array('id'=>'kplog','name'=>'开屏LOGO横'),array('id'=>'kploge','name'=>'开屏LOGO竖'));
    static $advnum = array(1=>array('id'=>'1','name'=>'开屏广告'),array('id'=>'2','name'=>'返回广告'),array('id'=>'3','name'=>'插屏广告'),array('id'=>'4','name'=>'安装下载'),array('id'=>'5','name'=>'通知栏安装下载'),array('id'=>'6','name'=>'通知栏推荐更新'),array('id'=>'7','name'=>'通知栏推荐使用'));
    static $openstatic2 = array(1=>array('id'=>1,'name'=>'通道池'),array('id'=>2,'name'=>'选择指定通道'),array('id'=>3,'name'=>'全局通道'));
    static $openstaticapi = array(1=>array('id'=>4,'name'=>'第三方支付'));
    static $vnstatus = array(1=>array('id'=>1,'name'=>'成功'),array('id'=>2,'name'=>'失败'),array('id'=>3,'name'=>'初始'));       //订单状态
    static $advstatus = array(1=>array('id'=>1,'name'=>'成功'));       //广告协议状态
    static $advstatus2 = array(1=>array('id'=>1,'name'=>'成功'),array('id'=>3,'name'=>'初始'));    //广告订单状态
    static $paycodestatus = array(1=>array('id'=>1,'name'=>'开始'),array('id'=>2,'name'=>'暂停'),array('id'=>3,'name'=>'终止合作'));
    static $binds = array(1=>array('id'=>1,'name'=>'未绑定'),array('id'=>1,'name'=>'一次'),array('id'=>1,'name'=>'多个'));
    static $egtlist = array(1=>array('id'=>1,'name'=>'电信'),array('id'=>2,'name'=>'联通'),array('id'=>3,'name'=>'移动'),array('id'=>4,'name'=>'支付宝'),array('id'=>5,'name'=>'微信'),array('id'=>6,'name'=>'银联'),array('id'=>7,'name'=>'全网'),array('id'=>-1,'name'=>'未知'));
    static $adegtlist = array(1=>array('id'=>1,'name'=>'电信'),array('id'=>2,'name'=>'联通'),array('id'=>3,'name'=>'移动'),array('id'=>-1,'name'=>'未知'));
    static $egtlist1 = array(1=>array('id'=>1,'name'=>'电信'),array('id'=>2,'name'=>'联通'),array('id'=>3,'name'=>'移动'),array('id'=>4,'name'=>'第三方支付平台'));
    static $adstatus=array(1=>array('id'=>0,'name'=>'关闭'),array('id'=>1,'name'=>'开启'));
    static $cpagstatus=array(1=>array('id'=>1,'name'=>'屏头'),array('id'=>2,'name'=>'屏中'),array('id'=>3,'name'=>'屏下'));
    static $teltype = array(1=>array('id'=>1,'name'=>'下载'),array('id'=>2,'name'=>'推荐更新'),array('id'=>3,'name'=>'推荐使用'));
    static $paystatus = array(1=>array('id'=>1,'name'=>'是'),array('id'=>2,'name'=>'否'));
    static $userstatus = array(1=>array('id'=>1,'name'=>'是'),array('id'=>2,'name'=>'否'));
    static $os_info = array(1=>array('id'=>1,'name'=>'android 1.1'),array('id'=>2,'name'=>'android 2.1'),array('id'=>3,'name'=>'android 3.1'),array('id'=>4,'name'=>'android 4.1'),array('id'=>5,'name'=>'ios5.1'),array('id'=>6,'name'=>'ios6.1'),array('id'=>7,'name'=>'ios7.1'));
    static $os_mobile = array(1=>array('id'=>1,'name'=>'诺基亚'),array('id'=>2,'name'=>'中兴'),array('id'=>3,'name'=>'华为'),array('id'=>4,'name'=>'魅族'),array('id'=>5,'name'=>'1+'),array('id'=>6,'name'=>'小米'),array('id'=>7,'name'=>'锤子'));
    static $net_info  =  array(1=>array('id'=>1,'name'=>'wifi'),array('id'=>2,'name'=>'2G'),array('id'=>3,'name'=>'3G'),array('id'=>4,'name'=>'4G'));
    static $adtime = array(1=>array('id'=>1,'name'=>'1s'),array('id'=>2,'name'=>'2s'),array('id'=>3,'name'=>'3s'),array('id'=>4,'name'=>'4s'),array('id'=>5,'name'=>'5s'));
    static $usertab=array(array('id'=>1,'name'=>'次日留存'),array('id'=>2,'name'=>'三日留存'),array('id'=>3,'name'=>'七日留存'));
    static $usertabuser=array(array('id'=>1,'name'=>'留存图'));
    static $outletstype = array(
        1=>array('id'=>1,'name'=>'SDK'),
        2=>array('id'=>2,'name'=>'API(一)'),
        3=>array('id'=>3,'name'=>'API(二)'),
    );
    static $eachename = array(
       1=>array('id'=>1,'name'=>'编号'),
       2=>array('id'=>2,'name'=>'手机号'),
       3=>array('id'=>3,'name'=>'手机品牌'),
       4=>array('id'=>4,'name'=>'手机型号'),
       5=>array('id'=>5,'name'=>'省份'),
   );
    static $gameusertype = array(
      array('id'=>1,'name'=>'新增用户'),
       array('id'=>2,'name'=>'活跃用户'),
       array('id'=>3,'name'=>'付费用户'),
       array('id'=>3,'name'=>'充值金额'),
       array('id'=>4,'name'=>'AP值')
   );
    static $dayusertype = array(
      array('id'=>1,'name'=>'次日留存'),
       array('id'=>2,'name'=>'三日留存'),
       array('id'=>3,'name'=>'七日留存'),
   );
    static $uersertype = array(
     array('id'=>1,'name'=>'用户数'),
      array('id'=>2,'name'=>'信息费'),
      array('id'=>3,'name'=>'ARPU')
  );
    static $resulttypelist = array(
        1=>array('id'=>1,'name'=>'一次'),
        2=>array('id'=>2,'name'=>'二次'),
    );
  //  static $servename = '120.25.92.169';
    static $servename = '120.75.76.199';

    /**
     * Summary of $errorcodelist
     *     1001	OutletsClose	渠道关闭
    *1002	TelecomProvinceClose	通道关闭、省份屏蔽
    *1003	MissParameters	参数不完整
    *1004	TelecomQuota	通道限额已满
    *1005	TimeOut	请求超时
    *1006    code关闭
    *1007    找不到可用通道(请求类型)
    *1100    OtherErr    其他错误
    *9999    BlackList  黑名单
    *0	Success	请求成功
     * @var mixed
     */
    static $errorcodelist = array(
        1=>array('id'=>'1001','name'=>'1001'),
        2=>array('id'=>'1002','name'=>'1002'),
        3=>array('id'=>'1003','name'=>'1003'),
        4=>array('id'=>'1004','name'=>'1004'),
        5=>array('id'=>'1005','name'=>'1005'),
        6=>array('id'=>'1006','name'=>'1006'),
        7=>array('id'=>'1007','name'=>'1007'),
        8=>array('id'=>'1100','name'=>'1100'),
        9=>array('id'=>'9999','name'=>'9999'),
        10=>array('id'=>'-3','name'=>'-3'),
        11=>array('id'=>'0','name'=>'0'),


);
    static $paygoldlist = array(
        1=>array('id'=>1,'name'=>0.001)
        ,2=>array('id'=>2,'name'=>1)
        ,3=>array('id'=>3,'name'=>10)
        ,4=>array('id'=>4,'name'=>100)
        ,5=>array('id'=>5,'name'=>200)
        ,6=>array('id'=>6,'name'=>300)
        ,7=>array('id'=>7,'name'=>400)
        ,8=>array('id'=>8,'name'=>500)
        ,9=>array('id'=>9,'name'=>600)
        ,10=>array('id'=>10,'name'=>700)
        ,11=>array('id'=>11,'name'=>800)
        ,12=>array('id'=>12,'name'=>900)
        ,13=>array('id'=>13,'name'=>1000)
        ,14=>array('id'=>14,'name'=>1100)
        ,15=>array('id'=>15,'name'=>1200)
        ,16=>array('id'=>16,'name'=>1300)
        ,17=>array('id'=>17,'name'=>1400)
        ,18=>array('id'=>18,'name'=>1500)
        ,19=>array('id'=>19,'name'=>1600)
        ,20=>array('id'=>20,'name'=>1700)
        ,21=>array('id'=>21,'name'=>1800)
        ,22=>array('id'=>22,'name'=>1900)
        ,23=>array('id'=>23,'name'=>2000)
        ,24=>array('id'=>24,'name'=>2500)
        ,25=>array('id'=>1,'name'=>3000)
        );
    public static $city_telecom = 
        array(
            array('id'=>1,'areacode'=>'311','city'=>'河北省(电信)'),  
            array('id'=>1,'areacode'=>'310','city'=>'河北省(电信)'),  
            array('id'=>1,'areacode'=>'319','city'=>'河北省(电信)'),  
            array('id'=>1,'areacode'=>'312','city'=>'河北省(电信)'),  
            array('id'=>1,'areacode'=>'313','city'=>'河北省(电信)'),  
            array('id'=>1,'areacode'=>'314','city'=>'河北省(电信)'),  
            array('id'=>1,'areacode'=>'315','city'=>'河北省(电信)'),  
            array('id'=>1,'areacode'=>'335','city'=>'河北省(电信)'),  
            array('id'=>1,'areacode'=>'317','city'=>'河北省(电信)'),  
            array('id'=>1,'areacode'=>'316','city'=>'河北省(电信)'),  
            array('id'=>1,'areacode'=>'318','city'=>'河北省(电信)'),  
            
            array('id'=>2,'areacode'=>'010','city'=>'北京市(电信)'),  
            
            array('id'=>3,'areacode'=>'022','city'=>'天津市(电信)'), 
            
            array('id'=>4,'areacode'=>'351','city'=>'山西省(电信)'),  
            array('id'=>4,'areacode'=>'352','city'=>'山西省(电信)'),  
            array('id'=>4,'areacode'=>'353','city'=>'山西省(电信)'),  
            array('id'=>4,'areacode'=>'355','city'=>'山西省(电信)'),  
            array('id'=>4,'areacode'=>'349','city'=>'山西省(电信)'),  
            array('id'=>4,'areacode'=>'354','city'=>'山西省(电信)'),  
            array('id'=>4,'areacode'=>'358','city'=>'山西省(电信)'),  
            array('id'=>4,'areacode'=>'357','city'=>'山西省(电信)'),  
            array('id'=>4,'areacode'=>'359','city'=>'山西省(电信)'),  
            array('id'=>4,'areacode'=>'356','city'=>'山西省(电信)'),  
            array('id'=>4,'areacode'=>'350','city'=>'山西省(电信)'),  
            
            array('id'=>5,'areacode'=>'471','city'=>'内蒙古省(电信)'),  
            array('id'=>5,'areacode'=>'472','city'=>'内蒙古省(电信)'),  
            array('id'=>5,'areacode'=>'476','city'=>'内蒙古省(电信)'),  
            array('id'=>5,'areacode'=>'479','city'=>'内蒙古省(电信)'),  
            array('id'=>5,'areacode'=>'470','city'=>'内蒙古省(电信)'),  
            array('id'=>5,'areacode'=>'475','city'=>'内蒙古省(电信)'),  
            array('id'=>5,'areacode'=>'477','city'=>'内蒙古省(电信)'),  
            array('id'=>5,'areacode'=>'482','city'=>'内蒙古省(电信)'),  
            array('id'=>5,'areacode'=>'473','city'=>'内蒙古省(电信)'),  
            array('id'=>5,'areacode'=>'478','city'=>'内蒙古省(电信)'),  
            array('id'=>5,'areacode'=>'474','city'=>'内蒙古省(电信)'),  
            array('id'=>5,'areacode'=>'483','city'=>'内蒙古省(电信)'),  
            
            array('id'=>6,'areacode'=>'024','city'=>'辽宁省(电信)'),  
            array('id'=>6,'areacode'=>'411','city'=>'辽宁省(电信)'),  
            array('id'=>6,'areacode'=>'412','city'=>'辽宁省(电信)'),  
            array('id'=>6,'areacode'=>'413','city'=>'辽宁省(电信)'),  
            array('id'=>6,'areacode'=>'414','city'=>'辽宁省(电信)'),  
            array('id'=>6,'areacode'=>'415','city'=>'辽宁省(电信)'),  
            array('id'=>6,'areacode'=>'416','city'=>'辽宁省(电信)'),  
            array('id'=>6,'areacode'=>'417','city'=>'辽宁省(电信)'),  
            array('id'=>6,'areacode'=>'418','city'=>'辽宁省(电信)'),  
            array('id'=>6,'areacode'=>'419','city'=>'辽宁省(电信)'),  
            array('id'=>6,'areacode'=>'410','city'=>'辽宁省(电信)'),  
            array('id'=>6,'areacode'=>'024','city'=>'辽宁省(电信)'),  
            array('id'=>6,'areacode'=>'427','city'=>'辽宁省(电信)'),  
            array('id'=>6,'areacode'=>'421','city'=>'辽宁省(电信)'),  
            array('id'=>6,'areacode'=>'429','city'=>'辽宁省(电信)'),  
            
            array('id'=>7,'areacode'=>'431','city'=>'吉林省(电信)'),  
            array('id'=>7,'areacode'=>'432','city'=>'吉林省(电信)'),  
            array('id'=>7,'areacode'=>'434','city'=>'吉林省(电信)'),  
            array('id'=>7,'areacode'=>'437','city'=>'吉林省(电信)'),  
            array('id'=>7,'areacode'=>'435','city'=>'吉林省(电信)'),  
            array('id'=>7,'areacode'=>'439','city'=>'吉林省(电信)'),  
            array('id'=>7,'areacode'=>'436','city'=>'吉林省(电信)'),  
            array('id'=>7,'areacode'=>'433','city'=>'吉林省(电信)'),  
            array('id'=>7,'areacode'=>'440','city'=>'吉林省(电信)'),  
            array('id'=>7,'areacode'=>'438','city'=>'吉林省(电信)'),  
            
            array('id'=>8,'areacode'=>'451','city'=>'黑龙江省(电信)'),  
            array('id'=>8,'areacode'=>'452','city'=>'黑龙江省(电信)'),  
            array('id'=>8,'areacode'=>'459','city'=>'黑龙江省(电信)'),  
            array('id'=>8,'areacode'=>'458','city'=>'黑龙江省(电信)'),  
            array('id'=>8,'areacode'=>'453','city'=>'黑龙江省(电信)'),  
            array('id'=>8,'areacode'=>'454','city'=>'黑龙江省(电信)'),  
            array('id'=>8,'areacode'=>'455','city'=>'黑龙江省(电信)'),  
            array('id'=>8,'areacode'=>'457','city'=>'黑龙江省(电信)'),  
            array('id'=>8,'areacode'=>'456','city'=>'黑龙江省(电信)'),  
            array('id'=>8,'areacode'=>'467','city'=>'黑龙江省(电信)'),  
            array('id'=>8,'areacode'=>'468','city'=>'黑龙江省(电信)'),  
            array('id'=>8,'areacode'=>'469','city'=>'黑龙江省(电信)'),  
            array('id'=>8,'areacode'=>'464','city'=>'黑龙江省(电信)'),  
            
            array('id'=>9,'areacode'=>'021','city'=>'上海市(电信)'),  
            
            array('id'=>10,'areacode'=>'025','city'=>'江苏省(电信)'),  
            array('id'=>10,'areacode'=>'516','city'=>'江苏省(电信)'),  
            array('id'=>10,'areacode'=>'518','city'=>'江苏省(电信)'),  
            array('id'=>10,'areacode'=>'517','city'=>'江苏省(电信)'),  
            array('id'=>10,'areacode'=>'527','city'=>'江苏省(电信)'),  
            array('id'=>10,'areacode'=>'515','city'=>'江苏省(电信)'),  
            array('id'=>10,'areacode'=>'514','city'=>'江苏省(电信)'),  
            array('id'=>10,'areacode'=>'513','city'=>'江苏省(电信)'),  
            array('id'=>10,'areacode'=>'511','city'=>'江苏省(电信)'),  
            array('id'=>10,'areacode'=>'519','city'=>'江苏省(电信)'),  
            array('id'=>10,'areacode'=>'510','city'=>'江苏省(电信)'),  
            array('id'=>10,'areacode'=>'512','city'=>'江苏省(电信)'),  
            array('id'=>10,'areacode'=>'523','city'=>'江苏省(电信)'),  

            array('id'=>11,'areacode'=>'571','city'=>'浙江省(电信)'),  
            array('id'=>11,'areacode'=>'574','city'=>'浙江省(电信)'),  
            array('id'=>11,'areacode'=>'573','city'=>'浙江省(电信)'),  
            array('id'=>11,'areacode'=>'572','city'=>'浙江省(电信)'),  
            array('id'=>11,'areacode'=>'575','city'=>'浙江省(电信)'),  
            array('id'=>11,'areacode'=>'579','city'=>'浙江省(电信)'),  
            array('id'=>11,'areacode'=>'570','city'=>'浙江省(电信)'),  
            array('id'=>11,'areacode'=>'580','city'=>'浙江省(电信)'),  
            array('id'=>11,'areacode'=>'577','city'=>'浙江省(电信)'),  
            array('id'=>11,'areacode'=>'576','city'=>'浙江省(电信)'),  
            array('id'=>11,'areacode'=>'578','city'=>'浙江省(电信)'),  
            
            array('id'=>12,'areacode'=>'551','city'=>'安徽省(电信)'),  
            array('id'=>12,'areacode'=>'554','city'=>'安徽省(电信)'),  
            array('id'=>12,'areacode'=>'552','city'=>'安徽省(电信)'),  
            array('id'=>12,'areacode'=>'555','city'=>'安徽省(电信)'),  
            array('id'=>12,'areacode'=>'556','city'=>'安徽省(电信)'),  
            array('id'=>12,'areacode'=>'559','city'=>'安徽省(电信)'),  
            array('id'=>12,'areacode'=>'550','city'=>'安徽省(电信)'),  
            array('id'=>12,'areacode'=>'557','city'=>'安徽省(电信)'),  
            array('id'=>12,'areacode'=>'565','city'=>'安徽省(电信)'),  
            array('id'=>12,'areacode'=>'563','city'=>'安徽省(电信)'),  
            array('id'=>12,'areacode'=>'553','city'=>'安徽省(电信)'),  
            array('id'=>12,'areacode'=>'561','city'=>'安徽省(电信)'),  
            array('id'=>12,'areacode'=>'562','city'=>'安徽省(电信)'),  
            array('id'=>12,'areacode'=>'558','city'=>'安徽省(电信)'),  
            array('id'=>12,'areacode'=>'564','city'=>'安徽省(电信)'),  
            array('id'=>12,'areacode'=>'566','city'=>'安徽省(电信)'),  
            array('id'=>12,'areacode'=>'558','city'=>'安徽省(电信)'),  
            
            array('id'=>13,'areacode'=>'591','city'=>'福建省(电信)'),  
            array('id'=>13,'areacode'=>'592','city'=>'福建省(电信)'),  
            array('id'=>13,'areacode'=>'598','city'=>'福建省(电信)'),  
            array('id'=>13,'areacode'=>'594','city'=>'福建省(电信)'),  
            array('id'=>13,'areacode'=>'595','city'=>'福建省(电信)'),  
            array('id'=>13,'areacode'=>'596','city'=>'福建省(电信)'),  
            array('id'=>13,'areacode'=>'599','city'=>'福建省(电信)'),  
            array('id'=>13,'areacode'=>'593','city'=>'福建省(电信)'),  
            array('id'=>13,'areacode'=>'597','city'=>'福建省(电信)'),  
            array('id'=>13,'areacode'=>'597','city'=>'福建省(电信)'),  
            array('id'=>13,'areacode'=>'597','city'=>'福建省(电信)'),  
            array('id'=>13,'areacode'=>'597','city'=>'福建省(电信)'),  
            array('id'=>13,'areacode'=>'597','city'=>'福建省(电信)'),  
            array('id'=>13,'areacode'=>'597','city'=>'福建省(电信)'),  
            
            array('id'=>14,'areacode'=>'791','city'=>'江西省(电信)'),  
            array('id'=>14,'areacode'=>'798','city'=>'江西省(电信)'),  
            array('id'=>14,'areacode'=>'790','city'=>'江西省(电信)'),  
            array('id'=>14,'areacode'=>'792','city'=>'江西省(电信)'),  
            array('id'=>14,'areacode'=>'701','city'=>'江西省(电信)'),  
            array('id'=>14,'areacode'=>'793','city'=>'江西省(电信)'),  
            array('id'=>14,'areacode'=>'795','city'=>'江西省(电信)'),  
            array('id'=>14,'areacode'=>'794','city'=>'江西省(电信)'),  
            array('id'=>14,'areacode'=>'796','city'=>'江西省(电信)'),  
            array('id'=>14,'areacode'=>'797','city'=>'江西省(电信)'),  
            array('id'=>14,'areacode'=>'799','city'=>'江西省(电信)'),  

            array('id'=>15,'areacode'=>'531','city'=>'山东省(电信)'),  
            array('id'=>15,'areacode'=>'532','city'=>'山东省(电信)'),  
            array('id'=>15,'areacode'=>'533','city'=>'山东省(电信)'),  
            array('id'=>15,'areacode'=>'536','city'=>'山东省(电信)'),  
            array('id'=>15,'areacode'=>'535','city'=>'山东省(电信)'),  
            array('id'=>15,'areacode'=>'631','city'=>'山东省(电信)'),  
            array('id'=>15,'areacode'=>'537','city'=>'山东省(电信)'),  
            array('id'=>15,'areacode'=>'633','city'=>'山东省(电信)'),  
            array('id'=>15,'areacode'=>'534','city'=>'山东省(电信)'),  
            array('id'=>15,'areacode'=>'530','city'=>'山东省(电信)'),  
            array('id'=>15,'areacode'=>'538','city'=>'山东省(电信)'),  
            array('id'=>15,'areacode'=>'632','city'=>'山东省(电信)'),  
            array('id'=>15,'areacode'=>'546','city'=>'山东省(电信)'),  
            array('id'=>15,'areacode'=>'543','city'=>'山东省(电信)'),  
            array('id'=>15,'areacode'=>'635','city'=>'山东省(电信)'),  
            array('id'=>15,'areacode'=>'539','city'=>'山东省(电信)'),  
            array('id'=>15,'areacode'=>'634','city'=>'山东省(电信)'),  

            array('id'=>16,'areacode'=>'371','city'=>'河南省(电信)'),  
            array('id'=>16,'areacode'=>'378','city'=>'河南省(电信)'),  
            array('id'=>16,'areacode'=>'379','city'=>'河南省(电信)'),  
            array('id'=>16,'areacode'=>'373','city'=>'河南省(电信)'),  
            array('id'=>16,'areacode'=>'393','city'=>'河南省(电信)'),  
            array('id'=>16,'areacode'=>'370','city'=>'河南省(电信)'),  
            array('id'=>16,'areacode'=>'377','city'=>'河南省(电信)'),  
            array('id'=>16,'areacode'=>'394','city'=>'河南省(电信)'),  
            array('id'=>16,'areacode'=>'396','city'=>'河南省(电信)'),  
            array('id'=>16,'areacode'=>'375','city'=>'河南省(电信)'),  
            array('id'=>16,'areacode'=>'372','city'=>'河南省(电信)'),  
            array('id'=>16,'areacode'=>'392','city'=>'河南省(电信)'),  
            array('id'=>16,'areacode'=>'391','city'=>'河南省(电信)'),  
            array('id'=>16,'areacode'=>'374','city'=>'河南省(电信)'),  
            array('id'=>16,'areacode'=>'395','city'=>'河南省(电信)'),  
            array('id'=>16,'areacode'=>'398','city'=>'河南省(电信)'),  
            array('id'=>16,'areacode'=>'376','city'=>'河南省(电信)'),  
            array('id'=>16,'areacode'=>'391','city'=>'河南省(电信)'),  

            array('id'=>17,'areacode'=>'027','city'=>'湖北省(电信)'),  
            array('id'=>17,'areacode'=>'276','city'=>'湖北省(电信)'),  
            array('id'=>17,'areacode'=>'714','city'=>'湖北省(电信)'),  
            array('id'=>17,'areacode'=>'710','city'=>'湖北省(电信)'),  
            array('id'=>17,'areacode'=>'719','city'=>'湖北省(电信)'),  
            array('id'=>17,'areacode'=>'717','city'=>'湖北省(电信)'),  
            array('id'=>17,'areacode'=>'714','city'=>'湖北省(电信)'),  
            array('id'=>17,'areacode'=>'712','city'=>'湖北省(电信)'),  
            array('id'=>17,'areacode'=>'713','city'=>'湖北省(电信)'),  
            array('id'=>17,'areacode'=>'718','city'=>'湖北省(电信)'),  
            array('id'=>17,'areacode'=>'716','city'=>'湖北省(电信)'),  
            array('id'=>17,'areacode'=>'711','city'=>'湖北省(电信)'),  
            array('id'=>17,'areacode'=>'722','city'=>'湖北省(电信)'),  
            array('id'=>17,'areacode'=>'715','city'=>'湖北省(电信)'),  
            array('id'=>17,'areacode'=>'728','city'=>'湖北省(电信)'),  

            array('id'=>18,'areacode'=>'731','city'=>'湖南省(电信)'),  
            array('id'=>18,'areacode'=>'733','city'=>'湖南省(电信)'),  
            array('id'=>18,'areacode'=>'732','city'=>'湖南省(电信)'),  
            array('id'=>18,'areacode'=>'734','city'=>'湖南省(电信)'),  
            array('id'=>18,'areacode'=>'730','city'=>'湖南省(电信)'),  
            array('id'=>18,'areacode'=>'736','city'=>'湖南省(电信)'),  
            array('id'=>18,'areacode'=>'735','city'=>'湖南省(电信)'),  
            array('id'=>18,'areacode'=>'737','city'=>'湖南省(电信)'),  
            array('id'=>18,'areacode'=>'746','city'=>'湖南省(电信)'),  
            array('id'=>18,'areacode'=>'745','city'=>'湖南省(电信)'),  
            array('id'=>18,'areacode'=>'744','city'=>'湖南省(电信)'),  
            array('id'=>18,'areacode'=>'739','city'=>'湖南省(电信)'),  
            array('id'=>18,'areacode'=>'738','city'=>'湖南省(电信)'),  
            array('id'=>18,'areacode'=>'743','city'=>'湖南省(电信)'),  

            array('id'=>19,'areacode'=>'020','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'208','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'755','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'756','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'754','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'751','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'752','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'769','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'760','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'757','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'759','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'758','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'750','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'668','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'753','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'660','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'762','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'662','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'763','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'768','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'663','city'=>'广东省(电信)'),  
            array('id'=>19,'areacode'=>'766','city'=>'广东省(电信)'),  

            array('id'=>20,'areacode'=>'771','city'=>'广西省(电信)'),  
            array('id'=>20,'areacode'=>'772','city'=>'广西省(电信)'),  
            array('id'=>20,'areacode'=>'773','city'=>'广西省(电信)'),  
            array('id'=>20,'areacode'=>'774','city'=>'广西省(电信)'),  
            array('id'=>20,'areacode'=>'779','city'=>'广西省(电信)'),  
            array('id'=>20,'areacode'=>'777','city'=>'广西省(电信)'),  
            array('id'=>20,'areacode'=>'770','city'=>'广西省(电信)'),  
            array('id'=>20,'areacode'=>'775','city'=>'广西省(电信)'),  
            array('id'=>20,'areacode'=>'776','city'=>'广西省(电信)'),  
            array('id'=>20,'areacode'=>'774','city'=>'广西省(电信)'),  
            array('id'=>20,'areacode'=>'778','city'=>'广西省(电信)'),  
            array('id'=>20,'areacode'=>'772','city'=>'广西省(电信)'),  
            array('id'=>20,'areacode'=>'771','city'=>'广西省(电信)'),  

            array('id'=>21,'areacode'=>'898','city'=>'海南省(电信)'),  
            array('id'=>21,'areacode'=>'899','city'=>'海南省(电信)'),  
            array('id'=>21,'areacode'=>'890','city'=>'海南省(电信)'),  

            array('id'=>22,'areacode'=>'028','city'=>'四川省(电信)'),  
            array('id'=>22,'areacode'=>'812','city'=>'四川省(电信)'),  
            array('id'=>22,'areacode'=>'838','city'=>'四川省(电信)'),  
            array('id'=>22,'areacode'=>'816','city'=>'四川省(电信)'),  
            array('id'=>22,'areacode'=>'813','city'=>'四川省(电信)'),  
            array('id'=>22,'areacode'=>'832','city'=>'四川省(电信)'),  
            array('id'=>22,'areacode'=>'833','city'=>'四川省(电信)'),  
            array('id'=>22,'areacode'=>'830','city'=>'四川省(电信)'),  
            array('id'=>22,'areacode'=>'831','city'=>'四川省(电信)'),  
            array('id'=>22,'areacode'=>'837','city'=>'四川省(电信)'),  
            array('id'=>22,'areacode'=>'836','city'=>'四川省(电信)'),  
            array('id'=>22,'areacode'=>'834','city'=>'四川省(电信)'),  
            array('id'=>22,'areacode'=>'839','city'=>'四川省(电信)'),  
            array('id'=>22,'areacode'=>'825','city'=>'四川省(电信)'),  
            array('id'=>22,'areacode'=>'028','city'=>'四川省(电信)'),  
            array('id'=>22,'areacode'=>'817','city'=>'四川省(电信)'),  
            array('id'=>22,'areacode'=>'818','city'=>'四川省(电信)'),  
            array('id'=>22,'areacode'=>'835','city'=>'四川省(电信)'),  
            array('id'=>22,'areacode'=>'826','city'=>'四川省(电信)'),  
            array('id'=>22,'areacode'=>'827','city'=>'四川省(电信)'),  

            array('id'=>23,'areacode'=>'851','city'=>'贵州省(电信)'),  
            array('id'=>23,'areacode'=>'852','city'=>'贵州省(电信)'),  
            array('id'=>23,'areacode'=>'853','city'=>'贵州省(电信)'),  
            array('id'=>23,'areacode'=>'858','city'=>'贵州省(电信)'),  
            array('id'=>23,'areacode'=>'857','city'=>'贵州省(电信)'),  
            array('id'=>23,'areacode'=>'856','city'=>'贵州省(电信)'),  
            array('id'=>23,'areacode'=>'859','city'=>'贵州省(电信)'),  
            array('id'=>23,'areacode'=>'855','city'=>'贵州省(电信)'),  
            array('id'=>23,'areacode'=>'854','city'=>'贵州省(电信)'),  

            array('id'=>24,'areacode'=>'871','city'=>'云南省(电信)'),  
            array('id'=>24,'areacode'=>'870','city'=>'云南省(电信)'),  
            array('id'=>24,'areacode'=>'874','city'=>'云南省(电信)'),  
            array('id'=>24,'areacode'=>'877','city'=>'云南省(电信)'),  
            array('id'=>24,'areacode'=>'879','city'=>'云南省(电信)'),  
            array('id'=>24,'areacode'=>'888','city'=>'云南省(电信)'),  
            array('id'=>24,'areacode'=>'873','city'=>'云南省(电信)'),  
            array('id'=>24,'areacode'=>'878','city'=>'云南省(电信)'),  
            array('id'=>24,'areacode'=>'877','city'=>'云南省(电信)'),  
            array('id'=>24,'areacode'=>'875','city'=>'云南省(电信)'),  
            array('id'=>24,'areacode'=>'883','city'=>'云南省(电信)'),  
            array('id'=>24,'areacode'=>'692','city'=>'云南省(电信)'),  
            array('id'=>24,'areacode'=>'886','city'=>'云南省(电信)'),  
            array('id'=>24,'areacode'=>'887','city'=>'云南省(电信)'),  
            array('id'=>24,'areacode'=>'872','city'=>'云南省(电信)'),  
            array('id'=>24,'areacode'=>'878','city'=>'云南省(电信)'),  
            array('id'=>24,'areacode'=>'873','city'=>'云南省(电信)'),  
            array('id'=>24,'areacode'=>'876','city'=>'云南省(电信)'),  
            array('id'=>24,'areacode'=>'691','city'=>'云南省(电信)'),  
            
            array('id'=>25,'areacode'=>'891','city'=>'西藏省(电信)'),  
            array('id'=>25,'areacode'=>'892','city'=>'西藏省(电信)'),  
            array('id'=>25,'areacode'=>'893','city'=>'西藏省(电信)'),  
            array('id'=>25,'areacode'=>'895','city'=>'西藏省(电信)'),  
            array('id'=>25,'areacode'=>'897','city'=>'西藏省(电信)'),  
            array('id'=>25,'areacode'=>'894','city'=>'西藏省(电信)'),  
            array('id'=>25,'areacode'=>'896','city'=>'西藏省(电信)'),  

            array('id'=>26,'areacode'=>'029','city'=>'陕西省(电信)'),  
            array('id'=>26,'areacode'=>'919','city'=>'陕西省(电信)'),  
            array('id'=>26,'areacode'=>'917','city'=>'陕西省(电信)'),  
            array('id'=>26,'areacode'=>'913','city'=>'陕西省(电信)'),  
            array('id'=>26,'areacode'=>'914','city'=>'陕西省(电信)'),  
            array('id'=>26,'areacode'=>'911','city'=>'陕西省(电信)'),  
            array('id'=>26,'areacode'=>'912','city'=>'陕西省(电信)'),  
            array('id'=>26,'areacode'=>'915','city'=>'陕西省(电信)'),  
            array('id'=>26,'areacode'=>'916','city'=>'陕西省(电信)'),  

            array('id'=>27,'areacode'=>'931','city'=>'甘肃省(电信)'),  
            array('id'=>27,'areacode'=>'935','city'=>'甘肃省(电信)'),  
            array('id'=>27,'areacode'=>'938','city'=>'甘肃省(电信)'),  
            array('id'=>27,'areacode'=>'933','city'=>'甘肃省(电信)'),  
            array('id'=>27,'areacode'=>'937','city'=>'甘肃省(电信)'),  
            array('id'=>27,'areacode'=>'943','city'=>'甘肃省(电信)'),  
            array('id'=>27,'areacode'=>'936','city'=>'甘肃省(电信)'),  
            array('id'=>27,'areacode'=>'935','city'=>'甘肃省(电信)'),  
            array('id'=>27,'areacode'=>'932','city'=>'甘肃省(电信)'),  
            array('id'=>27,'areacode'=>'939','city'=>'甘肃省(电信)'),  
            array('id'=>27,'areacode'=>'934','city'=>'甘肃省(电信)'),  
            array('id'=>27,'areacode'=>'930','city'=>'甘肃省(电信)'),  
            array('id'=>27,'areacode'=>'941','city'=>'甘肃省(电信)'),  

            array('id'=>28,'areacode'=>'971','city'=>'青海省(电信)'),  
            array('id'=>28,'areacode'=>'972','city'=>'青海省(电信)'),  
            array('id'=>28,'areacode'=>'979','city'=>'青海省(电信)'),  
            array('id'=>28,'areacode'=>'975','city'=>'青海省(电信)'),  
            array('id'=>28,'areacode'=>'970','city'=>'青海省(电信)'),  
            array('id'=>28,'areacode'=>'973','city'=>'青海省(电信)'),  
            array('id'=>28,'areacode'=>'974','city'=>'青海省(电信)'),  
            array('id'=>28,'areacode'=>'976','city'=>'青海省(电信)'),  
            array('id'=>28,'areacode'=>'977','city'=>'青海省(电信)'),  

            array('id'=>29,'areacode'=>'951','city'=>'宁夏省(电信)'),  
            array('id'=>29,'areacode'=>'952','city'=>'宁夏省(电信)'),  
            array('id'=>29,'areacode'=>'953','city'=>'宁夏省(电信)'),  
            array('id'=>29,'areacode'=>'954','city'=>'宁夏省(电信)'),  
            array('id'=>29,'areacode'=>'955','city'=>'宁夏省(电信)'),  

            array('id'=>30,'areacode'=>'991','city'=>'新疆省(电信)'),  
            array('id'=>30,'areacode'=>'990','city'=>'新疆省(电信)'),  
            array('id'=>30,'areacode'=>'995','city'=>'新疆省(电信)'),  
            array('id'=>30,'areacode'=>'998','city'=>'新疆省(电信)'),  
            array('id'=>30,'areacode'=>'908','city'=>'新疆省(电信)'),  
            array('id'=>30,'areacode'=>'996','city'=>'新疆省(电信)'),  
            array('id'=>30,'areacode'=>'902','city'=>'新疆省(电信)'),  
            array('id'=>30,'areacode'=>'997','city'=>'新疆省(电信)'),  
            array('id'=>30,'areacode'=>'903','city'=>'新疆省(电信)'),  
            array('id'=>30,'areacode'=>'994','city'=>'新疆省(电信)'),  
            array('id'=>30,'areacode'=>'909','city'=>'新疆省(电信)'),  
            array('id'=>30,'areacode'=>'999','city'=>'新疆省(电信)'),  
            array('id'=>30,'areacode'=>'993','city'=>'新疆省(电信)'),  
            array('id'=>30,'areacode'=>'906','city'=>'新疆省(电信)'),  
            array('id'=>30,'areacode'=>'903','city'=>'新疆省(电信)'),  
            array('id'=>30,'areacode'=>'909','city'=>'新疆省(电信)'),  
            array('id'=>31,'areacode'=>'023','city'=>'重庆市(电信)'),  
        );
    
        public static $city_mobile = array(
                array('id'=>1,'areacode'=>'03','city'=>'河北省(移动)'),
                array('id'=>2,'areacode'=>'01','city'=>'北京市(移动)'),
                array('id'=>3,'areacode'=>'02','city'=>'天津市(移动)'),
                array('id'=>4,'areacode'=>'04','city'=>'山西省(移动)'),
                array('id'=>5,'areacode'=>'05','city'=>'内蒙古省(移动)'),
                array('id'=>6,'areacode'=>'06','city'=>'辽宁省(移动)'),
                array('id'=>7,'areacode'=>'07','city'=>'吉林省(移动)'),
                array('id'=>8,'areacode'=>'08','city'=>'黑龙江(移动)'),
                array('id'=>9,'areacode'=>'09','city'=>'上海市(移动)'),
                array('id'=>10,'areacode'=>'10','city'=>'江苏省(移动)'),
                array('id'=>11,'areacode'=>'11','city'=>'浙江省(移动)'),
                array('id'=>12,'areacode'=>'12','city'=>'安徽省(移动)'),
                array('id'=>13,'areacode'=>'13','city'=>'福建省(移动)'),
                array('id'=>14,'areacode'=>'14','city'=>'江西省(移动)'),
                array('id'=>15,'areacode'=>'15','city'=>'山东省(移动)'),
                array('id'=>16,'areacode'=>'16','city'=>'河南省(移动)'),
                array('id'=>17,'areacode'=>'18','city'=>'湖南省(移动)'),
                array('id'=>18,'areacode'=>'17','city'=>'湖北省(移动)'),

                array('id'=>19,'areacode'=>'19','city'=>'广东省(移动)'),
                array('id'=>20,'areacode'=>'20','city'=>'广西省(移动)'),
                array('id'=>21,'areacode'=>'21','city'=>'海南省(移动)'),
                array('id'=>22,'areacode'=>'22','city'=>'四川省(移动)'),
                array('id'=>23,'areacode'=>'23','city'=>'贵州省(移动)'),
                array('id'=>24,'areacode'=>'24','city'=>'云南省(移动)'),
                array('id'=>25,'areacode'=>'25','city'=>'西藏省(移动)'),
                array('id'=>26,'areacode'=>'26','city'=>'陕西省(移动)'),
                array('id'=>27,'areacode'=>'27','city'=>'甘肃省(移动)'),
                array('id'=>28,'areacode'=>'28','city'=>'青海省(移动)'),
                array('id'=>29,'areacode'=>'29','city'=>'宁夏省(移动)'),
                array('id'=>30,'areacode'=>'30','city'=>'新疆省(移动)'),
                array('id'=>31,'areacode'=>'31','city'=>'重庆省(移动)'),
            );
            public static $city_union = array(
                array('id'=>1,'areacode'=>'18','city'=>'河北省(联通)'),
                array('id'=>2,'areacode'=>'11','city'=>'北京市(联通)'),
                array('id'=>3,'areacode'=>'13','city'=>'天津市(联通)'),
                array('id'=>4,'areacode'=>'19','city'=>'山西省(联通)'),
                array('id'=>5,'areacode'=>'10','city'=>'内蒙古省(联通)'),
                array('id'=>6,'areacode'=>'91','city'=>'辽宁省(联通)'),
                array('id'=>7,'areacode'=>'90','city'=>'吉林省(联通)'),
                array('id'=>8,'areacode'=>'97','city'=>'黑龙江省(联通)'),
                array('id'=>9,'areacode'=>'31','city'=>'上海市(联通)'),
                array('id'=>10,'areacode'=>'34','city'=>'江苏省(联通)'),
                array('id'=>11,'areacode'=>'36','city'=>'浙江省(联通)'),
                array('id'=>12,'areacode'=>'30','city'=>'安徽省(联通)'),
                array('id'=>13,'areacode'=>'38','city'=>'福建省(联通)'),
                array('id'=>14,'areacode'=>'75','city'=>'江西省(联通)'),
                array('id'=>15,'areacode'=>'17','city'=>'山东省(联通)'),
                array('id'=>16,'areacode'=>'76','city'=>'河南省(联通)'),
                array('id'=>17,'areacode'=>'74','city'=>'湖南省(联通)'),
                array('id'=>18,'areacode'=>'71','city'=>'湖北省(联通)'),
                array('id'=>19,'areacode'=>'51','city'=>'广东省(联通)'),
                array('id'=>20,'areacode'=>'59','city'=>'广西省(联通)'),
                array('id'=>21,'areacode'=>'50','city'=>'海南省(联通)'),
                array('id'=>22,'areacode'=>'81','city'=>'四川省(联通)'),
                array('id'=>23,'areacode'=>'85','city'=>'贵州省(联通)'),
                array('id'=>24,'areacode'=>'86','city'=>'云南省(联通)'),
                array('id'=>25,'areacode'=>'79','city'=>'西藏省(联通)'),
                array('id'=>26,'areacode'=>'84','city'=>'陕西省(联通)'),
                array('id'=>27,'areacode'=>'87','city'=>'甘肃省(联通)'),
                array('id'=>28,'areacode'=>'70','city'=>'青海省(联通)'),                
                array('id'=>29,'areacode'=>'88','city'=>'宁夏省(联通)'),
                array('id'=>30,'areacode'=>'89','city'=>'新疆省(联通)'),
                array('id'=>31,'areacode'=>'83','city'=>'重庆市(联通)'),
                );
            public static $city = array(
                array('id'=>1,'areacode'=>'18','city'=>'河北'),
                array('id'=>2,'areacode'=>'11','city'=>'北京'),
                array('id'=>3,'areacode'=>'13','city'=>'天津'),
                array('id'=>4,'areacode'=>'19','city'=>'山西'),
                array('id'=>5,'areacode'=>'10','city'=>'内蒙古'),
                array('id'=>6,'areacode'=>'91','city'=>'辽宁'),
                array('id'=>7,'areacode'=>'90','city'=>'吉林'),
                array('id'=>8,'areacode'=>'97','city'=>'黑龙江'),
                array('id'=>9,'areacode'=>'31','city'=>'上海'),
                array('id'=>10,'areacode'=>'34','city'=>'江苏'),
                array('id'=>11,'areacode'=>'36','city'=>'浙江'),
                array('id'=>12,'areacode'=>'30','city'=>'安徽'),
                array('id'=>13,'areacode'=>'38','city'=>'福建'),
                array('id'=>14,'areacode'=>'75','city'=>'江西'),
                array('id'=>15,'areacode'=>'17','city'=>'山东'),
                array('id'=>16,'areacode'=>'76','city'=>'河南'),
                array('id'=>17,'areacode'=>'74','city'=>'湖南'),
                array('id'=>18,'areacode'=>'71','city'=>'湖北'),
                array('id'=>19,'areacode'=>'51','city'=>'广东'),
                array('id'=>20,'areacode'=>'59','city'=>'广西'),
                array('id'=>21,'areacode'=>'50','city'=>'海南'),
                array('id'=>22,'areacode'=>'81','city'=>'四川'),
                array('id'=>23,'areacode'=>'85','city'=>'贵州'),
                array('id'=>24,'areacode'=>'86','city'=>'云南'),
                array('id'=>25,'areacode'=>'79','city'=>'西藏'),
                array('id'=>26,'areacode'=>'84','city'=>'陕西'),
                array('id'=>27,'areacode'=>'87','city'=>'甘肃'),
                array('id'=>28,'areacode'=>'70','city'=>'青海'),                
                array('id'=>29,'areacode'=>'88','city'=>'宁夏'),
                array('id'=>30,'areacode'=>'89','city'=>'新疆'),
                array('id'=>31,'areacode'=>'83','city'=>'重庆'),
                array('id'=>32,'areacode'=>'83','city'=>'其他'),

                );
//            10内蒙古 11北京 13天津 17山东 18河北 19山西 30安徽 31上海 34江苏
//36浙江 38福建 50海南 51广东 59广西 70青海 71湖北 74湖南 75江西
//76河南 79西藏 81四川 83重庆 84陕西 85贵州 86云南 87甘肃 88宁夏
//89新疆 90吉林 91辽宁 97黑龙江
            public static $city_pysx = array(
                array('sx'=>'ah','city'=>'安徽省'),
                array('sx'=>'bj','city'=>'北京市'),
                array('sx'=>'fj','city'=>'福建省'),
                array('sx'=>'gs','city'=>'甘肃省'),
                array('sx'=>'gd','city'=>'广东省'),
                array('sx'=>'gx','city'=>'广西省'),
                array('sx'=>'gz','city'=>'贵州省'),
                array('sx'=>'hain','city'=>'海南省'),
                array('sx'=>'heb','city'=>'河北省'),
                array('sx'=>'hen','city'=>'河南省'),
                array('sx'=>'hlj','city'=>'黑龙江省'),
                array('sx'=>'hub','city'=>'湖北省'),
                array('sx'=>'ah','city'=>'湖南省'),
                array('sx'=>'jl','city'=>'吉林省'),
                array('sx'=>'js','city'=>'江苏省'),
                array('sx'=>'jx','city'=>'江西省'),
                array('sx'=>'ln','city'=>'辽宁省'),
                array('sx'=>'nmg','city'=>'内蒙古省'),
                array('sx'=>'nx','city'=>'宁夏省'),
                array('sx'=>'qh','city'=>'青海省'),
                array('sx'=>'sd','city'=>'山东省'),
                array('sx'=>'sx','city'=>'山西省'),
                array('sx'=>'shxi','city'=>'陕西省'),
                array('sx'=>'sh','city'=>'上海市'),
                array('sx'=>'sc','city'=>'四川省'),
                array('sx'=>'tj','city'=>'天津市'),
                array('sx'=>'xz','city'=>'西藏省'),
                array('sx'=>'xj','city'=>'新疆省'),
                array('sx'=>'yn','city'=>'云南省'),
                array('sx'=>'zj','city'=>'浙江省'),
                array('sx'=>'cq','city'=>'重庆市'),
                array('sx'=>'oth','city'=>'其他'),
                );
            //翼支付
            static $telecomYZF = array(
                    array('gold'=>'0.1','smstext'=>'ZDZF&02110108040107522&10&&BF0001&1&&1289A'),
                    array('gold'=>'1','smstext'=>'ZDZF&02110108040107522&100&&BF0002&1&3&&F004D'),
                    array('gold'=>'6','smstext'=>'ZDZF&02110108040107522&600&&BF0003&1&3&&024FA'),
                    array('gold'=>'8','smstext'=>'ZDZF&02110108040107522&800&&BF0004&1&3&&AA8EC'),
                    array('gold'=>'10','smstext'=>'ZDZF&02110108040107522&1000&&BF0005&1&3&&34502'),
                    array('gold'=>'15','smstext'=>'ZDZF&02110108040107522&1500&&BF0006&1&3&&38092'),
                    array('gold'=>'20','smstext'=>'ZDZF&02110108040107522&2000&&BF0007&1&3&&4D45E'),
                    array('gold'=>'30','smstext'=>'ZDZF&02110108040107522&3000&&BF0008&1&3&&E1894'),
                    array('gold'=>'5','smstext'=>'ZDZF&02110108040107522&500&&BF0010&1&3&&2D875'),
                    array('gold'=>'4','smstext'=>'ZDZF&02110108040107522&400&&BF0012&1&3&&14AFA')
                );
            //电信城市
            static $telecomDXCS = array(
                array('gold'=>'2','smstest'=>'131000Na000001B001ns001100025','port'=>'10659879020'),
                array('gold'=>'4','smstest'=>'131000Na000001B001nu001100025','port'=>'10659879040'),
                array('gold'=>'6','smstest'=>'131000Na000001B001nw001100025','port'=>'10659879060'),
                array('gold'=>'8','smstest'=>'131000Na000001B001ny001100025','port'=>'10659879080'),
                array('gold'=>'10','smstest'=>'131000Na000001B001o0001100025','port'=>'10659879100'),
                array('gold'=>'12','smstest'=>'131000Na000001B001o1001100025','port'=>'10659879120'),
                array('gold'=>'14','smstest'=>'131000Na000001B001o2001100025','port'=>'10659879140'),
                array('gold'=>'15','smstest'=>'131000Na000001B001o3001100025','port'=>'10659879150'),
                array('gold'=>'16','smstest'=>'131000Na000001B001o4001100025','port'=>'10659879160'),
                array('gold'=>'20','smstest'=>'131000Na000001B001o6001100025','port'=>'10659879200'),
                array('gold'=>'25','smstest'=>'131000Na000001B001o7001100025','port'=>'10659879250'),
                array('gold'=>'30','smstest'=>'131000Na000001B001o8001100025','port'=>'10659879300'),
                array('gold'=>'5','smstest'=>'131000Na000001B001nv001100025','port'=>'10659879050'),

                );
                static $emailuserlist = array(
                    array('name'=>'振宇','email'=>'15915307741@139.com'),
               );

                
                
                static $ydmm2citylist = array(
                    array('sx'=>'1','city'=>'北京市'),
                    array('sx'=>'2','city'=>'天津市'),
                    array('sx'=>'3','city'=>'河北省'),
                    array('sx'=>'4','city'=>'山西省'),
                    array('sx'=>'5','city'=>'内蒙古'),
                    array('sx'=>'6','city'=>'辽宁省'),
                    array('sx'=>'7','city'=>'吉林省'),
                    array('sx'=>'8','city'=>'黑龙江'),
                    array('sx'=>'9','city'=>'上海市'),
                    array('sx'=>'10','city'=>'江苏省'),
                    array('sx'=>'11','city'=>'浙江省'),
                    array('sx'=>'12','city'=>'安徽省'),
                    array('sx'=>'13','city'=>'福建省'),
                    array('sx'=>'14','city'=>'江西省'),
                    array('sx'=>'15','city'=>'山东省'),
                    array('sx'=>'16','city'=>'河南省'),
                    array('sx'=>'17','city'=>'湖北省'),
                    array('sx'=>'18','city'=>'湖南省'),
                    array('sx'=>'19','city'=>'广东省'),
                    array('sx'=>'20','city'=>'广西省'),
                    array('sx'=>'21','city'=>'海南省'),
                    array('sx'=>'22','city'=>'重庆市'),
                    array('sx'=>'23','city'=>'四川省'),
                    array('sx'=>'24','city'=>'贵州省'),
                    array('sx'=>'25','city'=>'云南省'),
                    array('sx'=>'26','city'=>'西藏省'),
                    array('sx'=>'27','city'=>'陕西省'),
                    array('sx'=>'28','city'=>'甘肃省'),
                    array('sx'=>'29','city'=>'青海省'),
                    array('sx'=>'30','city'=>'宁夏省'),
                    array('sx'=>'31','city'=>'新疆省'),
                    array('sx'=>'32','city'=>'香港特别行政区'),
                    array('sx'=>'32','city'=>'澳门特别行政区'),
                    array('sx'=>'32','city'=>'台湾省'),
                    );
                    static $zhltxwcitylist = array(
                    array('sx'=>'1','city'=>'上海市'),
                    array('sx'=>'2','city'=>'云南省'),
                    array('sx'=>'3','city'=>'内蒙古'),
                    array('sx'=>'4','city'=>'北京市'),
                    array('sx'=>'5','city'=>'吉林省'),
                    array('sx'=>'6','city'=>'四川省'),
                    array('sx'=>'7','city'=>'天津市'),
                    array('sx'=>'8','city'=>'宁夏省'),
                    array('sx'=>'9','city'=>'安徽省'),
                    array('sx'=>'10','city'=>'山东省'),
                    array('sx'=>'11','city'=>'山西省'),
                    array('sx'=>'12','city'=>'广东省'),
                    array('sx'=>'13','city'=>'广西省'),
                    array('sx'=>'14','city'=>'新疆省'),
                    array('sx'=>'15','city'=>'江苏省'),
                    array('sx'=>'16','city'=>'江西省'),
                    array('sx'=>'17','city'=>'河北省'),
                    array('sx'=>'18','city'=>'河南省'),
                    array('sx'=>'19','city'=>'浙江省'),
                    array('sx'=>'20','city'=>'海南省'),
                    array('sx'=>'21','city'=>'湖北省'),
                    array('sx'=>'22','city'=>'湖南省'),
                    array('sx'=>'23','city'=>'甘肃省'),
                    array('sx'=>'24','city'=>'福建省'),
                    array('sx'=>'25','city'=>'西藏省'),
                    array('sx'=>'26','city'=>'贵州省'),
                    array('sx'=>'27','city'=>'辽宁省'),
                    array('sx'=>'28','city'=>'重庆市'),
                    array('sx'=>'29','city'=>'陕西省'),
                    array('sx'=>'30','city'=>'青海省'),
                    array('sx'=>'31','city'=>'黑龙江'),
                    );
//26 西藏自治区	27 陕西省
//28 甘肃省
//29 青海省
//30 宁夏回族自治区
//31 新疆维吾尔族自治区
//32 香港特别行政区
//33 澳门特别行政区
//34 台湾省


}
