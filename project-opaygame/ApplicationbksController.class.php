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
class ApplicationbksController extends Bks
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
    public function indext1(){
        $type = 1;
        $bt = 'T1';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicindex($type,$bt);
    }
    public function uploadbks(){
        $this->publicupload();
    }
    public function edituploadbks(){
        $this->publicedit();
    }
    public function urlt1(){
       $urltype = 1;
       $bt = 'T1';
       Cookie('__forward__',$_SERVER['REQUEST_URI']);
       $this->publicurlindex($urltype,$bt);
    }
    
    public function urladd(){
        $this->publicurladd();
       
    }
    
    public function uploaddelete(){
        //if(IS_POST){
            $id = I('get.id');
            $deleteid = M('resources')->where(array('id'=>$id))->delete();
            $a = M('resources')->_Sql();
            if($deleteid){
                $this->success('删除成功', Cookie('__url__'));
            }
        //}
    }

    
    public function editurl(){
        $this->publicediturl();
        }
   

    public function indext2(){
        $type = 2;
        $bt = 'T2';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicindex($type,$bt);
    }
    public function urlt2(){
        $urltype = 2;
        $bt = 'T2';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicurlindex($urltype,$bt);
    }
    public function indext3(){
        $type = 3;
        $bt = 'T3';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicindex($type,$bt);
    }
    public function urlt3(){
        $urltype = 3;
        $bt = 'T3';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicurlindex($urltype,$bt);
    }
    public function indext4(){
        $type = 4;
        $bt = 'T4';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicindex($type,$bt);
    }
    public function urlt4(){
        $urltype = 4;
        $bt = 'T4';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicurlindex($urltype,$bt);
    }
    public function indext5(){
        $type = 5;
        $bt = 'T5';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicindex($type,$bt);
    }
    public function urlt5(){
        $urltype = 5;
        $bt = 'T5';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicurlindex($urltype,$bt);
    }
    public function indext6(){
        $type = 6;
        $bt = 'T6';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicindex($type,$bt);
    }
    public function urlt6(){
        $urltype = 6;
        $bt = 'T6';
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->publicurlindex($urltype,$bt);
    }
}
    
    //public function resourcesbks(){
    //  $uploadlist = M('uploadbks')->select();
    //  $this->assign('uploadbks',$uploadlist);
        
    //  $this->display();
    
    //}
    
    
 

