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
use OT\Bks;
class BoutiqueController extends Bks
{   
    //修改状态
    public function changeoff(){
        $id = I('get.id');
        $status = I('get.status');
        if($id){
            $chang = M('resources')->where(array('id'=>$id))->save(array('status'=>$status));
            if(isset($chang)){
                $this->success('操作成功', Cookie('__forward__'));
            }
        }
    }
    //url状态
    public function changeurl(){
        $this->publicchangeurl();
    }
    public function indexj1(){
        $type = 21;
        $bt = 'J1';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicindex($type,$bt);
    }
    public function uploadbks(){
        $this->publicupload();
    }
    public function edituploadbks(){
        $this->publicedit();
    }
    public function urlj1(){
        $urltype = 21;
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $bt = 'J1';
        $this->publicurlindex($urltype,$bt);
    }
    
    public function urladd(){
        $this->publicurladd();
        
    }
    public function editurl(){
        $this->publicediturl();
    }
    

    public function indexj2(){
        $type = 22;
        $bt = 'J2';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicindex($type,$bt);
    }
    public function urlj2(){
        $urltype = 22;
        $bt = 'J2';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicurlindex($urltype,$bt);
    }
    public function indexj3(){
        $type = 23;
        $bt = 'J3';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicindex($type,$bt);
    }
    public function urlj3(){
        $urltype = 23;
        $bt = 'J3';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicurlindex($urltype,$bt);
    }
    public function indexj4(){
        $type = 24;
        $bt = 'J4';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicindex($type,$bt);
    }
    public function urlj4(){
        $urltype = 24;
        $bt = 'J4';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicurlindex($urltype,$bt);
    }
    public function indexj5(){
        $type = 25;
        $bt = 'J5';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicindex($type,$bt);
    }
    public function urlj5(){
        $urltype = 25;
        $bt = 'J5';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicurlindex($urltype,$bt);
    }
    public function indexj6(){
        $type = 26;
        $bt = 'J6';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicindex($type,$bt);
    }
    public function urlj6(){
        $urltype = 26;
        $bt = 'J6';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicurlindex($urltype,$bt);
    }
}




