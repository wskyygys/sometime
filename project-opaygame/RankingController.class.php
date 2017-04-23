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
class RankingController extends Bks
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
    public function indexp1(){
        $type = 41;
        $bt = 'P1';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicindex($type,$bt);
    }
    public function uploadbks(){
        $this->publicupload();
    }
    public function edituploadbks(){
        $this->publicedit();
    }
    public function urlp1(){
        $urltype = 41;
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $bt = 'P1';
        $this->publicurlindex($urltype,$bt);
    }
    
    public function urladd(){
        $this->publicurladd();
        
    }
 
    public function editurl(){
        $this->publicediturl();
    }
    

    public function indexp2(){
        $type = 42;
        $bt = 'P2';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicindex($type,$bt);
    }
    public function urlp2(){
        $urltype = 42;
        $bt = 'J2';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicurlindex($urltype,$bt);
    }
    public function indexp3(){
        $type = 43;
        $bt = 'P3';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicindex($type,$bt);
    }
    public function urlp3(){
        $urltype = 43;
        $bt = 'P3';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicurlindex($urltype,$bt);
    }
    public function indexp4(){
        $type = 44;
        $bt = 'P4';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicindex($type,$bt);
    }
    public function urlp4(){
        $urltype = 44;
        $bt = 'P4';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicurlindex($urltype,$bt);
    }
    public function indexp5(){
        $type = 45;
        $bt = 'P5';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicindex($type,$bt);
    }
    public function urlp5(){
        $urltype = 45;
        $bt = 'P5';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicurlindex($urltype,$bt);
    }
    public function indexp6(){
        $type = 46;
        $bt = 'P6';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicindex($type,$bt);
    }
    public function urlp6(){
        $urltype = 46;
        $bt = 'P6';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicurlindex($urltype,$bt);
    }
}






