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
class RequestController extends AdminController
{
    public function echoo(){
    //$urldata['type'] = I('get.type');
    //$urldata['tid'] = I('get.tid');
    //$urldata['shid'] = I('get.shid');
    //foreach($urldata as $v){
    //    if($v == ''){
    //    $this->error('参数不全');
    //    }
    //}
    $data['state'] = '0';
    $data['zygs'] = '1';
    $data['sid'] = '1';
    $data['awritt'] = '一朵花';
    $data['aiconlj'] = 'http://120.24.246.100/Uploads/20170111/5875d9fce4095.jpg';
    $data['aaplij'] = 'http://120.24.246.100/Uploads/20170111/5875d9fce4095.apk';
    $data['aPicturel1'] = 'http://120.24.246.100/Uploads/20170111/5875d9fce4095.jpg';
    $data['aPicturel2'] = 'http://120.24.246.100/Uploads/20170111/5875d9fce4095.jpg';
    $data['aPicturel3'] = 'http://120.24.246.100/Uploads/20170111/5875d9fce4095.jpg';
    $data['aPicturel4'] = 'http://120.24.246.100/Uploads/20170111/5875d9fce4095.jpg';
    $data['aPicturel5'] = 'http://120.24.246.100/Uploads/20170111/5875d9fce4095.jpg';
    $data['atexti'] = '猫喜欢吃鱼，可猫不会游泳。鱼喜欢吃蚯蚓，可鱼又不能上岸。上天给了你许多诱惑，却不让你轻易得到。要想实现，就要自己奋斗。人生就像蒲公英，看似自由，却往往身不由己。生活没有如果，只有结果，自己尽力了，努力了，就好';
    $json = json_encode($data);
    echo $json;
    }
    
    
    
}
