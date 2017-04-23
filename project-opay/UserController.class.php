<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use User\Api\UserApi;

/**
 * 后台用户控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class UserController extends AdminController {

    /**
     * 用户管理首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        //$nickname       =   I('nickname');
        //$map['status']  =   array('egt',0);
        //if(is_numeric($nickname)){
        //    $map['uid|nickname']=   array(intval($nickname),array('like','%'.$nickname.'%'),'_multi'=>true);
        //}else{
        //    $map['nickname']    =   array('like', '%'.(string)$nickname.'%');
        //}
        //$list   = $this->lists('Member', $map);
        //int_to_string($list);
        $time = date('Ymdhsm',time());

        $list = M('member')->select();
        $this->assign('_list', $list);
        $this->meta_title = '用户信息';
        $this->display();
    }

    /**
     * 修改昵称初始化
     * @author huajie <banhuajie@163.com>
     */
    public function updateNickname(){
        $nickname = M('Member')->getFieldByUid(UID, 'nickname');
        $this->assign('nickname', $nickname);
        $this->meta_title = '修改昵称';
        $this->display();
    }

    /**
     * 修改昵称提交
     * @author huajie <banhuajie@163.com>
     */
    public function submitNickname(){
        //获取参数
        $nickname = I('post.nickname');
        $password = I('post.password');
        $uid = I('post.uid');
        empty($uid) && $uid = 0;

        empty($nickname) && $this->error('请输入昵称');
        empty($password) && $this->error('请输入密码');

        //密码验证
        $User   =   new UserApi();
        if($uid!=0)
            $uid    =   $User->login($uid, $password, 4);
        else
            $uid    =   $User->login(UID, $password, 4);
        ($uid == -2) && $this->error('密码不正确');

        $Member =   D('Member');
        $data   =   $Member->create(array('nickname'=>$nickname));
        if(!$data){
            $this->error($Member->getError());
        }

        $res = $Member->where(array('uid'=>$uid))->save($data);

        if($res){
            $user               =   session('user_auth');
            $user['username']   =   $data['nickname'];
            session('user_auth', $user);
            session('user_auth_sign', data_auth_sign($user));
            $this->success('修改昵称成功！');
        }else{
            $this->error('修改昵称失败！');
        }
    }

    /**
     * 修改密码初始化
     * @author huajie <banhuajie@163.com>
     */
    public function updatePassword(){
        $this->meta_title = '修改密码';
        $this->display();
    }
    /**
     * 修改密码初始化
     * @author huajie <banhuajie@163.com>
     */
    public function updatePassword_admin($uid = 0){
        $this->meta_title = '修改密码_管理员模式';
        $this->assign('uid',$uid);
        $this->display();
    }
    /**
     * 修改昵称初始化
     * @author huajie <banhuajie@163.com>
     */
    public function updateNickname_admin($uid = 0){
        $nickname = M('Member')->getFieldByUid(UID, 'nickname');
        $this->assign('nickname', $nickname);
        $this->meta_title = '修改昵称';
        $this->assign('uid',$uid);

        $this->display();
    }
    /**
     * 修改密码提交
     * @author huajie <banhuajie@163.com>
     */
    public function submitPassword(){
        //获取参数
        $password   =   I('post.old');
        $uid = I('post.uid');
        empty($uid) && $uid = 0;
        empty($password) && $this->error('请输入原密码');
        $data['password'] = I('post.password');
        empty($data['password']) && $this->error('请输入新密码');
        $repassword = I('post.repassword');
        empty($repassword) && $this->error('请输入确认密码');

        if($data['password'] !== $repassword){
            $this->error('您输入的新密码与确认密码不一致');
        }
        //$data = array('password'=>think_ucenter_md5($password, UC_AUTH_KEY));
        // //    $newp = think_ucenter_md5($password_in, UC_AUTH_KEY);

        //$isok = M('ucenter_member')->where(array('id'=>1))->save($data);
        //if($isok)
        //{
        //      $this->success('修改密码成功！');
        //}
        //else
        //{
        //    $this->error("验证失败");
        //}

        $Api    =   new UserApi();
        if($uid != 0)
            $res    =   $Api->updateInfo($uid, $password, $data);
        else
            $res    =   $Api->updateInfo(UID, $password, $data);
        if($res['status']){
            $this->success('修改密码成功！');
            
        }else{
            $errorinfo = $res['info'];
            $this->error("验证失败");
        }
    }
    /**
     * 修改密码提交
     * @author huajie <banhuajie@163.com>
     */
    public function editauth($uid = 0){

        if(IS_POST)
        {
            $data = I('post.');

            $channels_visible = $data['channels_visible'];  //厂商通道勾选的
            $telecom_visible = $data['telecom_visible'];    //通道勾选的
            $game_visible = $data['game_visible'];

            $isbad_visible = $data['isbad_visible']|0;
            if($isbad_visible ===0)
                $isbad_visible = 2;
            
            $channels_visible_s = '';
            foreach($channels_visible as $k=>$v)
            {
                $channels_visible_s = $channels_visible_s.'_'.$v;
            }
            $telecom_visible_s = '';
            
            foreach($telecom_visible as $k=>$v)
            {
                $telecom_visible_s = $telecom_visible_s.'_'.$v;
            }
            foreach($game_visible as $k=>$v)
            {
                $game_visible_s = $game_visible_s.'_'.$v;
            }
            $group_info = M('auth_group_access')->where(array('uid'=>$uid))->select();
            $group_id = $group_info[0]['group_id'];
            switch ($group_id)
            {
                case 7:
                    {
                        $telecomlist = M('telecomlist')->select();
                        $updatedata = array('telecom_visible'=>$telecom_visible_s,'isbad_visible'=>$isbad_visible,'games_visible'=>$game_visible_s);

                        break;
                    }
                case 6:
                    {
                                $telecomlist = M('telecomlist')->select();
                                $updatedata = array('telecom_visible'=>$telecom_visible_s,'isbad_visible'=>$isbad_visible,'games_visible'=>$game_visible_s);
                    } 
                case 3:
                    {
                        $outletlist = M('colist')->select();
                        $updatedata = array('channels_visible'=>$channels_visible_s,'isbad_visible'=>$isbad_visible,'games_visible'=>$game_visible_s);
                        break;
                    }
                default:
                    {
                        $updatedata = array('telecom_visible'=>$telecom_visible_s,'channels_visible'=>$channels_visible_s,'isbad_visible'=>$isbad_visible,'games_visible'=>$game_visible_s);
                        break;
                    }
            }
     //       $updatedata = array('telecom_visible'=>$telecom_visible_s,'channels_visible'=>$channels_visible_s,'isbad_visible'=>$isbad_visible);
            $isok = M('auth_user')->where(array('uid'=>$uid))->data($updatedata)->save();
            if($isok !== false)
            {
                $this->success('修改成功' ,U('index'));
            }
            else
            {
                $this->error('联系你妈逼的写代码的。请烧纸');
            }
        }
        else   
        {
            $group_info = M('auth_group_access')->where(array('uid'=>$uid))->select();
            $group_id = $group_info[0]['group_id'];
            switch ($group_id)
            {
                case 7: //通道组
                {
                    $telecomlist = M('telecomlist')->select();
                    $gamelist = M('gamelist')->select();
                    break;
                }
                case 6: //商务组2
                {
                        $telecomlist = M('telecomlist')->select();
                        $gamelist = M('gamelist')->select();
                        break;
                 }
                case 3:     //商务组
                {
                    $outletlist = M('colist')->select();
                    $gamelist = M('gamelist')->select();
                    break;
                }
                default:    //1管理员、4运营组、5、测试组 6、Cashier
                {
                    $outletlist = M('colist')->select();
                    $gamelist = M('gamelist')->select();
                    $telecomlist = M('telecomlist')->select();
                    break;
                }
            }
            $authuser = M('auth_user')->where(array('uid'=>$uid))->select();
            $telecom_visible = $authuser[0]['telecom_visible'];
            $telecom_visible = explode('_',$telecom_visible);
            unset($channels_visible[0]);
            if($telecomlist != null)
            {
                foreach($telecomlist as $k=>$v)
                { 
                    foreach($telecom_visible as $k2=>$v2)
                    {
                        if($v2 === $v['id'])
                        {
                            $telecomlist[$k]['id2'] = $v2;
                        }
                    }
                }
            }
            
            $authuser = M('auth_user')->where(array('uid'=>$uid))->select();
            $game_visible = $authuser[0]['games_visible'];
            $game_visible = explode('_',$game_visible);
            unset($channels_visible[0]);
            if($gamelist != null)
            {
                foreach($gamelist as $k=>$v)
                { 
                    foreach($game_visible as $k2=>$v2)
                    {
                        if($v2 === $v['appid'])
                        {
                            $gamelist[$k]['id2'] = $v2;
                        }
                    }
                }
            }

            ///////////////////
            $authuser = M('auth_user')->where(array('uid'=>$uid))->select();
            $channels_visible = $authuser[0]['channels_visible'];
            $isbad_visible = $authuser[0]['isbad_visible'];
            $channels_visible = explode('_',$channels_visible);         //厂商
            unset($channels_visible[0]);
            if($outletlist != null)
            {
                foreach($outletlist as $k=>$v)
                { 
                    foreach($channels_visible as $k2=>$v2)
                    {
                        if($v2 === $v['coid'])
                        {
                            $outletlist[$k]['id2'] = $v2;
                        }
                    }
                }
            }

            $this->assign('telecomlist',$telecomlist);
            $this->assign('colist',$outletlist);
            $this->assign('gamelist',$gamelist);
            $this->assign('isbadvalue',$isbad_visible);
            $this->assign('uid',$uid);
            $this->assign('channels_visible',$channels_visible);
            $this->assign('isbadvisible',$isbad_visible);
            $this->display();
        }

    }
    /**
     * 用户行为列表
     * @author huajie <banhuajie@163.com>
     */
    public function action(){
        //获取列表数据
        $Action =   M('Action')->where(array('status'=>array('gt',-1)));
        $list   =   $this->lists($Action);
        int_to_string($list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->assign('_list', $list);
        $this->meta_title = '用户行为';
        $this->display();
    }

    /**
     * 新增行为
     * @author huajie <banhuajie@163.com>
     */
    public function addAction(){
        $this->meta_title = '新增行为';
        $this->assign('data',null);
        $this->display('editaction');
    }

    /**
     * 编辑行为
     * @author huajie <banhuajie@163.com>
     */
    public function editAction(){
        $id = I('get.id');
        empty($id) && $this->error('参数不能为空！');
        $data = M('Action')->field(true)->find($id);

        $this->assign('data',$data);
        $this->meta_title = '编辑行为';
        $this->display();
    }

    /**
     * 更新行为
     * @author huajie <banhuajie@163.com>
     */
    public function saveAction(){
        $res = D('Action')->update();
        if(!$res){
            $this->error(D('Action')->getError());
        }else{
            $this->success($res['id']?'更新成功！':'新增成功！', Cookie('__forward__'));
        }
    }

    /**
     * 会员状态修改
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function changeStatus($method=null){
        $id = array_unique((array)I('id',0));
        if( in_array(C('USER_ADMINISTRATOR'), $id)){
            $this->error("不允许对超级管理员执行该操作!");
        }
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['uid'] =   array('in',$id);
        switch ( strtolower($method) ){
            case 'forbiduser':
                $this->forbid('Member', $map );
                break;
            case 'resumeuser':
                $this->resume('Member', $map );
                break;
            case 'deleteuser':
                $this->delete('Member', $map );
                break;
            default:
                $this->error('参数非法');
        }
    }

    public function add($username = '', $password = '', $repassword = '', $email = ''){
        if(IS_POST){
            /* 检测密码 */
            if($password != $repassword){
                $this->error('密码和重复密码不一致！');
            }

            /* 检测密码 */
            if($password != $repassword){
                $this->error('密码和重复密码不一致！');
            }

            /* 调用注册接口注册用户 */
            $User   =   new UserApi;
            $uid    =   $User->register($username, $password, $email);
            if(0 < $uid){ //注册成功
                $user = array('uid' => $uid, 'nickname' => $username, 'status' => 1);
                if(!M('Member')->add($user)){
                    $this->error('用户添加失败！');
                } else {
             //       $user = array('uid' => $uid, 'nickname' => $username, 'status' => 1);

                    $this->success('用户添加成功！',U('index'));
                }
            } else { //注册失败，显示错误信息
                $this->error($this->showRegError($uid));
            }
        } else {
            $this->meta_title = '新增用户';
            $this->display();
        }
    }

    /**
     * 获取用户注册错误信息
     * @param  integer $code 错误编码
     * @return string        错误信息
     */
    private function showRegError($code = 0){
        switch ($code) {
            case -1:  $error = '用户名长度必须在16个字符以内！'; break;
            case -2:  $error = '用户名被禁止注册！'; break;
            case -3:  $error = '用户名被占用！'; break;
            case -4:  $error = '密码长度必须在6-30个字符之间！'; break;
            case -5:  $error = '邮箱格式不正确！'; break;
            case -6:  $error = '邮箱长度必须在1-32个字符之间！'; break;
            case -7:  $error = '邮箱被禁止注册！'; break;
            case -8:  $error = '邮箱被占用！'; break;
            case -9:  $error = '手机格式不正确！'; break;
            case -10: $error = '手机被禁止注册！'; break;
            case -11: $error = '手机号被占用！'; break;
            default:  $error = '未知错误';
        }
        return $error;
    }

}
