<?php
namespace Admin\Controller;
/**
 * 后台配置控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class UpgradeController extends AdminController {
    public function index(){
        $model = 'sdkversion';
        $model || $this->error('模型名标识必须！');
        $model = D($model);
        $list = $model->select();
        $this->assign('list',$list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        //$this->meta_title = $model['title'].'列表';
        $this->display();

    }
    
   public function add($uid = '',$md5 = '',$classname = '',$nexttime = '',$url = ''){
    //  public function add(){
        if(IS_POST)
        {
            $Menu = D('sdkversion');
            $data = $Menu->create();
            $version = $data['uid'];
            $version = substr($version,0,2);
            $data['version']=$version;
            $uid = $data['uid'];
            //      $uid = $data['classname'];
            $classname = $data['classname'];
            $selectwhere = array('uid'=>$uid,'classname'=>$data['classname']);
            if($data){
                $id = $Menu->where($selectwhere)->data($data)->add();
                if($id){
                    // S('DB_CONFIG_DATA',null);
                    //记录行为
                    action_log('添加版本信息', 'Upgrade', $id, UID);
                    $this->success('新增成功', Cookie('__forward__'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Menu->getError());
            }
        }
        else
        {
            $this->display('');
        }
    }

    public function edit($id = 0,$oldclassname='',$olduid='')
    {
        if(IS_POST)
        {
            $sdkv = D('sdkversion');
            $data = $sdkv->create();
            $version = $data['uid'];
            $version = substr($version,0,2);
            $data['version']=$version;
        //    $uid = $olduid;
      //      $uid = $data['classname'];
      //      $classname = $data['classname'];
            $selectwhere = array('uid'=>$olduid,'classname'=>$oldclassname);
            $info = $sdkv->where($selectwhere)->select();
            if($data)
            {
                if($sdkv->where($selectwhere)->data($data)->save()!==false)
                {
                    action_log('update_upgrade', 'Upgrade', $data['id'], UID);
                    $this->success('更新成功', Cookie('__forward__'));
                }
                else
                {
                    $this->error("更新失败");
                }
            }
            else
            {
                $this->error($sdkv->getError());
            }
        }
        else
        {
            $info = array();
            /* 获取数据 */
            $info = M('sdkversion')->field(true)->find($id);
            if(false === $info){
                $this->error('获取编辑信息错误');
            }
            $this->assign('info', $info);
            $this->meta_title = '编辑版本信息';
            $this->display();
        }

  //      $this->display();
    }
    public function info($id = 0)
    {
        $info = array();
        /* 获取数据 */
        $info = M('sdkversion')->field(true)->find($id);
        // $menus = M('Upgrade')->field(true)->select();
        // $menus = D('Common/Tree')->toFormatTree($menus);

        //  $menus = array_merge(array(0=>array('id'=>0,'title_show'=>'顶级菜单')), $menus);
        //  $this->assign('Menus', $menus);
        if(false === $info){
            $this->error('获取编辑信息错误');
        }
        $this->assign('info', $info);
        $this->meta_title = '编辑版本信息';
        $this->display();
    }
    public function check()
    {
        echo('wocaole');
    }
}
?>