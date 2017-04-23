<?php
namespace Admin\Controller;
class VnController extends AdminController
{  
    public function order(){
        //$a = '{"state":0,"T1":{"zygs":2,"zy":{"19":{"time":"1","spid":"5","write":"wo\/we\/wewe","icon":"http:\/\/localhost:7160\/Uploads\/20170113\/58784bdeb89ac.jpg","apk":"http:\/\/localhost:7160\/Uploads\/20170113\/58784bdec1266.jpg"},"20":{"time":"1","spid":"2","write":"qwe \/qwe\/ewqe","icon":"http:\/\/localhost:7160\/Uploads\/20170113\/58784c0cc641d.jpg","apk":"http:\/\/localhost:7160\/Uploads\/20170113\/58784c0cc6fd5.jpg"}}},"T2":{"zygs":2,"zy":{"18":{"time":"5","spid":"5","write":"\u6211\u662f\u597d\u4eba\/\u6211\u662f\u597d\u4eba\/\u6211\u662f\u597d\u4eba","icon":"http:\/\/localhost:7160\/Uploads\/20170113\/587872a49ee4e.jpg","apk":"http:\/\/localhost:7160\/Uploads\/20170113\/587872a49fdee.jpg"},"21":{"time":"5","spid":"1","write":"\u533a\u59d4\u533a\u59d4\/\u8bf7\u95ee\/\u8bf7\u95ee","icon":"http:\/\/localhost:7160\/Uploads\/20170113\/587872660bb6b.jpg","apk":"http:\/\/localhost:7160\/Uploads\/20170113\/587872661403d.jpg"}}},"T3":{"zygs":2,"zy":{"22":{"time":"10","spid":"2","write":"\u65b9\u60f3\/\u5361\u724c\/\u795e\u65cf","icon":"http:\/\/localhost:7160\/Uploads\/20170113\/587872cf9b7e3.jpg","apk":"http:\/\/localhost:7160\/Uploads\/20170113\/587872cfa4c55.jpg"},"23":{"time":"10","spid":"6","write":"\u79e6\u5931\u5176\u9e7f\/\u9ed1\u9f99\/\u8d64\u7802\u7cd6","icon":"http:\/\/localhost:7160\/Uploads\/20170113\/58787302750fc.jpg","apk":"http:\/\/localhost:7160\/Uploads\/20170113\/5878730278b95.jpg"}}},"T4":{"zygs":2,"zy":{"24":{"time":"4","spid":"1","write":"\u5929\u8695\u571f\u8c46\/\u5c0f\u767d\u6587\/\u5c0f\u767d","icon":"http:\/\/localhost:7160\/Uploads\/20170113\/5878734844d99.jpg","apk":"http:\/\/localhost:7160\/Uploads\/20170113\/5878734845951.jpg"},"25":{"time":"4","spid":"3","write":"\u795e\/\u9b54\/\u4ed9","icon":"http:\/\/localhost:7160\/Uploads\/20170113\/587873684e55e.jpg","apk":"http:\/\/localhost:7160\/Uploads\/20170113\/587873684f116.jpg"}}},"T5":{"zygs":2,"zy":{"26":{"time":"5","spid":"4","write":"\u963f\u5446\/\u963f\u5446\/\u963f\u5446","icon":"http:\/\/localhost:7160\/Uploads\/20170113\/587873a593c9d.jpg","apk":"http:\/\/localhost:7160\/Uploads\/20170113\/587873a59a9ff.jpg"},"27":{"time":"5","spid":"5","write":"\u5510\u5bb6\u4e09\u5c11\/\u5510\u5bb6\u4e09\u5c11\/\u5510\u5bb6\u4e09\u5c11","icon":"http:\/\/localhost:7160\/Uploads\/20170113\/587873caf0e38.jpg","apk":"http:\/\/localhost:7160\/Uploads\/20170113\/587873caf3931.jpg"}}},"T6":{"zygs":3,"zy":{"34":[{"time":"2","spid":"2","write":"\u5c0f\u65f6\/\u5361\u724c\/\u5361\u724c","icon":"http:\/\/localhost:7160\/Uploads\/20170113\/587874766f85e.jpg","apk":"http:\/\/localhost:7160\/Uploads\/20170113\/5878747670416.jpg"},{"time":"2","spid":"4","write":"\u5510\u5bb6\u4e09\u5c11\/\u5510\u5bb6\u4e09\u5c11\/\u5510\u5bb6\u4e09\u5c11","icon":"http:\/\/localhost:7160\/Uploads\/20170113\/58787497a6237.jpg","apk":"http:\/\/localhost:7160\/Uploads\/20170113\/58787497a8948.jpg"}],"35":[{"time":"5","spid":"4","write":"\u5510\u5bb6\u4e09\u5c11\/\u5510\u5bb6\u4e09\u5c11\/\u5510\u5bb6\u4e09\u5c11","icon":"http:\/\/localhost:7160\/Uploads\/20170113\/58787497a6237.jpg","apk":"http:\/\/localhost:7160\/Uploads\/20170113\/58787497a8948.jpg"}],"36":[{"time":"10","spid":"2","write":"\u5c0f\u65f6\/\u5361\u724c\/\u5361\u724c","icon":"http:\/\/localhost:7160\/Uploads\/20170113\/587874766f85e.jpg","apk":"http:\/\/localhost:7160\/Uploads\/20170113\/5878747670416.jpg"},{"time":"10","spid":"4","write":"\u5510\u5bb6\u4e09\u5c11\/\u5510\u5bb6\u4e09\u5c11\/\u5510\u5bb6\u4e09\u5c11","icon":"http:\/\/localhost:7160\/Uploads\/20170113\/58787497a6237.jpg","apk":"http:\/\/localhost:7160\/Uploads\/20170113\/58787497a8948.jpg"},{"time":"10","spid":"2","write":"\u963f\u8428\u5fb7\/sad\/\u5341\u5927","icon":"http:\/\/localhost:7160\/Uploads\/20170113\/58787543997c0.jpg","apk":"http:\/\/localhost:7160\/Uploads\/20170113\/587875439c2b9.jpg"}]}}}';
        //$c[] = '1'; 
        //$b = json_encode($a,$c);
        //exit();
        
        
            if(!$_GET['p']){
                $_GET['p'] = 0;
            }
            $date = date('Y_m',time());
            $table = $date.'_vnorder';
            $list = M($table)->field("*,FROM_UNIXTIME(time,'%Y-%m-%d %H:%i:%S') as time")->page($_GET['p'].',20')->order('id desc')->select();
            $count = M($table)->count();// 查询满足要求的总记录数
            $Page = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
            $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $show = $Page->show();// 分页显示输出
            foreach($list as $k=>$v){
                if($v['pagenum']==1){
                    $list[$k]['pagenum'] = '发现';
                }elseif($v['pagenum']==2){
                    $list[$k]['pagenum'] = '精品';
                }elseif($v['pagenum']==3){
                    $list[$k]['pagenum'] = '排行榜';
                }
                if($v['types']==1){
                    $list[$k]['types'] = 'app';
                }elseif($v['types']==2){
                    $list[$k]['types'] = 'web';
                }
                if($v['bkstype']==1){
                    $list[$k]['bkstype'] = '首页';
                }elseif($v['bkstype']==2){
                    $list[$k]['bkstype'] = '商品';
                }
            }
            $this->assign('page',$show);
            $this->assign('list',$list);
            $this->display();
            
    }
    
    public function histroy(){
        $hid = I('get.bksid');
        if(!$_GET['p']){
            $_GET['p'] = 0;
        }
        $date = date('Y_m',time());
        $table = $date.'_vnhistory';
        if($hid!=''){
            $list = M($table)->field("*,FROM_UNIXTIME(time,'%Y-%m-%d %H:%i:%S') as time")->where(array('orderid'=>$hid))->page($_GET['p'].',20')->order('id desc')->select();
            $count = M($table)->where(array('orderid'=>$hid))->count();// 查询满足要求的总记录数
        }else{
            $list = M($table)->field("*,FROM_UNIXTIME(time,'%Y-%m-%d %H:%i:%S') as time")->page($_GET['p'].',20')->order('id desc')->select();
            $count = M($table)->count();// 查询满足要求的总记录数
        }
        $Page = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        $show = $Page->show();// 分页显示输出
        foreach($list as $k=>$v){
            if($v['type']==1){
                $list[$k]['type'] = '首页';
            }elseif($v['type']==2){
                $list[$k]['type'] = '商品';
            }elseif($v['type']==3){
                $list[$k]['type'] = '点击';
            }elseif($v['type']==4){
                $list[$k]['type'] = '下载';
            }
        }
        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->display();
    
    
    
    }

}





