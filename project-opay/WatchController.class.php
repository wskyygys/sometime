<?php
namespace Admin\Controller;

/**
 * Watch short summary.
 *
 * Watch description.
 *
 * @version 1.0
 * @author admin
 */
//监控控制器
class WatchController extends AdminController
{
    public function index()
    {
        //模拟数据库操作
        $info = array(array("id"=>0,"num"=>"60874","date"=>"2016-02-23","telecom"=>"SZLPBKS",
            "outlet"=>"电信翼支付 飞天冒险夜","waring"=>"警告(15分)","seecont"=>"无成功回调","dois"=>"否")
            );
        $this->assign("list",$info);
        $this->display();
    }
}
