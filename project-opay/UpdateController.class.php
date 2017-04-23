<?php

namespace Admin\Controller;

/**
 * update short summary.
 *
 * update description.
 *
 * @version 1.0
 * @author 华亮
 */
class UpdateController
{
    public function resultdata($info = null)
    {
        //   var_dump($info);
        $index = 0;
        foreach($info as $value)
        {
            $index++;
            if($index == 1)
            {
                $data = array('md5'=>$value['md5'],'classname'=>$value['classname'],'uid'=>$value['uid'],'url'=>$value['url']);
            }
            if($index == 2)
            {
                $data2 = array('md5'=>$value['md5'],'classname'=>$value['classname'],'uid'=>$value['uid'],'url'=>$value['url']);
            }
        }
        $resultdata2 = array('code'=>0,'data'=>array($data,$data2));
        echo(json_encode($resultdata2));
    }
    /**
     * Summary of check
     * @param mixed $version /版本号
     * @param mixed $status cs 测试 sy 商用
     * @param mixed $outletsversion 当值为空为不自定义渠道版本
     */
    public function check($version = '',$status='',$outletsversion='')
    {
        $model = 'sdkversion';
        $model = D($model);
        if($version == '')
        {
            echo('error');
            return;
        }
        if($outletsversion != '')
        {
            $uid = $outletsversion;
            $selectwhere = array('uid'=>$uid);
            $info = $model->where($selectwhere)->select();
            $this->resultdata($info);
            return;
        }
        $uid = $this->sxversion($version,$status);
        $version = 'v'.$version;     
        $selectwhere = array('version'=>$version,'uid'=>$uid);
        $info = $model->where($selectwhere)->select();
        $this->resultdata($info);
    }
    /**
     * Summary of sxversion
     * @param mixed $version 总版本号
     * @param mixed $status 当前SDK的状态，测试OR外放
     * @return mixed
     */
    public function sxversion($version,$status)
    {
        if($status =  'cs')    
        {
            $uid = 'v1.1.1';
            switch((int)$version)
            {
                case 1:
                    {
                        $uid = 'v1.1.1';
                        break;
                    }
                case 2:
                    {
                        $uid = 'v2.1.1';
                        break;
                    }
                default:
                    {
                        break;
                    }
            }
            
        }
        else
        {
            $uid = 'v1.1.1';
            switch((int)$version)
            {
                case 1:
                    {
                        $uid = 'v1.1.1';
                        break;
                    }
                case 2:
                    {
                        $uid = 'v2.1.1';
                        break;
                    }
                default:
                    {
                        break;
                    }
            }
        }
        return $uid;
    }

}
