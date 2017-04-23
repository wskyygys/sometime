<?php
namespace Admin\Controller;
use Think\Controller;
//΢��֧����
class WxController extends Controller {
    //��ȡaccess_token�����е���תuri��ͨ����ת��code����jsapi֧��ҳ��
    public function _initialize()
    {
        //����WxPayPubHelper
        vendor('Wx/WxPay.Data');
    }
    
    public function jsApiCall()
    {
        //ʹ��jsapi�ӿ�
        $jsApi = new \JsApi_pub();
        
        //=========����1����ҳ��Ȩ��ȡ�û�openid============
        //ͨ��code���openid
        if (!isset($_GET['code']))
        {
            //����΢�ŷ���code��
            $url = $jsApi->createOauthUrlForCode(C('WxPayConf_pub.JS_API_CALL_URL'));
            Header("Location: $url");
        }else
        {
            //��ȡcode�룬�Ի�ȡopenid
            $code = $_GET['code'];
            $jsApi->setCode($code);
            $openid = $jsApi->getOpenId();
        }
        
        //=========����2��ʹ��ͳһ֧���ӿڣ���ȡprepay_id============
        //ʹ��ͳһ֧���ӿ�
        $unifiedOrder = new \UnifiedOrder_pub();
        
        //����ͳһ֧���ӿڲ���
        //���ñ������
        //appid����,�̻������ظ���д
        //mch_id����,�̻������ظ���д
        //noncestr����,�̻������ظ���д
        //spbill_create_ip����,�̻������ظ���д
        //sign����,�̻������ظ���д
        $unifiedOrder->setParameter("openid",$openid);//��Ʒ����
        $unifiedOrder->setParameter("body","����һ��Ǯ");//��Ʒ����
        //�Զ��嶩���ţ��˴���������
        $timeStamp = time();
        $out_trade_no = C('WxPayConf_pub.APPID').$timeStamp;
        $unifiedOrder->setParameter("out_trade_no",$out_trade_no);//�̻�������
        $unifiedOrder->setParameter("total_fee","1");//�ܽ��
        $unifiedOrder->setParameter("notify_url",C('WxPayConf_pub.NOTIFY_URL'));//֪ͨ��ַ
        $unifiedOrder->setParameter("trade_type","JSAPI");//��������
        //�Ǳ���������̻��ɸ���ʵ�����ѡ��
        //$unifiedOrder->setParameter("sub_mch_id","XXXX");//���̻���
        //$unifiedOrder->setParameter("device_info","XXXX");//�豸��
        //$unifiedOrder->setParameter("attach","XXXX");//��������
        //$unifiedOrder->setParameter("time_start","XXXX");//������ʼʱ��
        //$unifiedOrder->setParameter("time_expire","XXXX");//���׽���ʱ��
        //$unifiedOrder->setParameter("goods_tag","XXXX");//��Ʒ���
        //$unifiedOrder->setParameter("openid","XXXX");//�û���ʶ
        //$unifiedOrder->setParameter("product_id","XXXX");//��ƷID
        
        $prepay_id = $unifiedOrder->getPrepayId();
        //=========����3��ʹ��jsapi����֧��============
        $jsApi->setPrepayId($prepay_id);
        
        $jsApiParameters = $jsApi->getParameters();
        
        $this->assign('jsApiParameters',$jsApiParameters);
        $this->display('pay');
        //echo $jsApiParameters;
    }
    
    public function notify()
    {
        //ʹ��ͨ��֪ͨ�ӿ�
        $notify = new \Notify_pub();
        
        //�洢΢�ŵĻص�
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);
        
        //��֤ǩ��������Ӧ΢�š�
        //�Ժ�̨֪ͨ����ʱ�����΢���յ��̻���Ӧ���ǳɹ���ʱ��΢����Ϊ֪ͨʧ�ܣ�
        //΢�Ż�ͨ��һ���Ĳ��ԣ���30���ӹ�8�Σ��������·���֪ͨ��
        //���������֪ͨ�ĳɹ��ʣ���΢�Ų���֤֪ͨ�����ܳɹ���
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//����״̬��
            $notify->setReturnParameter("return_msg","ǩ��ʧ��");//������Ϣ
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//���÷�����
        }
        $returnXml = $notify->returnXml();
        echo $returnXml;
        
        //==�̻�����ʵ�����������Ӧ�Ĵ������̣��˴���������=======
        
        //��log�ļ���ʽ��¼�ص���Ϣ
        //         $log_ = new Log_();
        $log_name= __ROOT__."/Public/notify_url.log";//log�ļ�·��
        
        log_result($log_name,"�����յ���notify֪ͨ��:\n".$xml."\n");
        
        if($notify->checkSign() == TRUE)
        {
            if ($notify->data["return_code"] == "FAIL") {
                //�˴�Ӧ�ø���һ�¶���״̬���̻�������ɾ����
                log_result($log_name,"��ͨ�ų���:\n".$xml."\n");
            }
            elseif($notify->data["result_code"] == "FAIL"){
                //�˴�Ӧ�ø���һ�¶���״̬���̻�������ɾ����
                log_result($log_name,"��ҵ�����:\n".$xml."\n");
            }
            else{
                //�˴�Ӧ�ø���һ�¶���״̬���̻�������ɾ����
                log_result($log_name,"��֧���ɹ���:\n".$xml."\n");
            }
            
            //�̻��������Ӵ�������,
            //���磺���¶���״̬
            //���磺���ݿ����
            //���磺����֧�������Ϣ
        }
    }
    
    //public function index(){
    //$this->display();
    //}
    
    //public function cs(){
    //    $this->display();
    //}
    //public function js_api_call() {
    //    $order_sn = I('get.order_sn', '');
    //    if (empty($order_sn)) {
    //        header('location:'.__ROOT__.'/');
    //    }
    //    vendor('WxPay.Data');
    //    //ʹ��jsapi�ӿ�
    //    $jsApi = new \JsApi_pub();
    //    //=========����1����ҳ��Ȩ��ȡ�û�openid============
    //    //ͨ��code���openid
    //    if (!isset($_GET['code'])){
    //        //����΢�ŷ���code��
    //        $url = $jsApi->createOauthUrlForCode('����/Wxpay/js_api_call?order_sn='.$order_sn);
    //        //$url = $jsApi->createOauthUrlForCode(\WxPayConf_pub::JS_API_CALL_URL);
    //        Header("Location: $url"); 
    //    }else{
    //        //��ȡcode�룬�Ի�ȡopenid
    //        $code = $_GET['code'];
    //        $jsApi->setCode($code);
    //        $openid = $jsApi->getOpenId();
    //    }
    //    $res = array(
    //        'order_sn' => '20150109113322',
    //        'order_amount' => 255
    //    );
    //    //=========����2��ʹ��ͳһ֧���ӿڣ���ȡprepay_id============
    //    //ʹ��ͳһ֧���ӿ� www.bcty365.com
    //    $unifiedOrder = new \UnifiedOrder_pub();
    //    //����ͳһ֧���ӿڲ���
    //    //���ñ������
    //    //appid����,�̻������ظ���д
    //    //mch_id����,�̻������ظ���д
    //    //noncestr����,�̻������ظ���д
    //    //spbill_create_ip����,�̻������ظ���д
    //    //sign����,�̻������ظ���д
    //    $total_fee = $res['order_amount']*100;
    //    //$total_fee = 1;
    //    $body = "����֧��{$res['order_sn']}";
    //    $unifiedOrder->setParameter("openid", "$openid");//�û���ʶ
    //    $unifiedOrder->setParameter("body", $body);//��Ʒ����
    //    //�Զ��嶩���ţ��˴���������
    //    $out_trade_no = $res['order_sn'];
    //    $unifiedOrder->setParameter("out_trade_no", $out_trade_no);//�̻������� 
    //    $unifiedOrder->setParameter("total_fee", $total_fee);//�ܽ��
    //    //$unifiedOrder->setParameter("attach", "order_sn={$res['order_sn']}");//�������� 
    //    $unifiedOrder->setParameter("notify_url", \WxPayConf_pub::NOTIFY_URL);//֪ͨ��ַ 
    //    $unifiedOrder->setParameter("trade_type", "JSAPI");//��������
    //    //�Ǳ���������̻��ɸ���ʵ�����ѡ��
    //    //$unifiedOrder->setParameter("sub_mch_id","XXXX");//���̻���  
    //    //$unifiedOrder->setParameter("device_info","XXXX");//�豸�� 
    //    //$unifiedOrder->setParameter("attach","XXXX");//�������� 
    //    //$unifiedOrder->setParameter("time_start","XXXX");//������ʼʱ��
    //    //$unifiedOrder->setParameter("time_expire","XXXX");//���׽���ʱ�� 
    //    //$unifiedOrder->setParameter("goods_tag","XXXX");//��Ʒ��� 
    //    //$unifiedOrder->setParameter("openid","XXXX");//�û���ʶ
    //    //$unifiedOrder->setParameter("product_id","XXXX");//��ƷID
    //    $prepay_id = $unifiedOrder->getPrepayId();
    //    //=========����3��ʹ��jsapi����֧��============
    //    $jsApi->setPrepayId($prepay_id);
    //    $jsApiParameters = $jsApi->getParameters();
    //    $wxconf = json_decode($jsApiParameters, true);
    //    if ($wxconf['package'] == 'prepay_id=') {
    //        $this->error('��ǰ���������쳣������ʹ��֧��');
    //    }
    //    $this->assign('res', $res);
    //    $this->assign('jsApiParameters', $jsApiParameters);
    //    $this->display('jsapi');
    //}
 
    ////�첽֪ͨurl���̻�����ʵ�ʿ��������趨
 
    //public function notify_url() {
    //    vendor('Weixinpay.WxPayPubHelper');
    //    //ʹ��ͨ��֪ͨ�ӿ�
    //    $notify = new \Notify_pub();
    //    //�洢΢�ŵĻص�
    //    $xml = $GLOBALS['HTTP_RAW_POST_DATA'];    
    //    $notify->saveData($xml);
    //    //��֤ǩ��������Ӧ΢�š�
    //    //�Ժ�̨֪ͨ����ʱ�����΢���յ��̻���Ӧ���ǳɹ���ʱ��΢����Ϊ֪ͨʧ�ܣ�
    //    //΢�Ż�ͨ��һ���Ĳ��ԣ���30���ӹ�8�Σ��������·���֪ͨ��
    //    //���������֪ͨ�ĳɹ��ʣ���΢�Ų���֤֪ͨ�����ܳɹ���
    //    if($notify->checkSign() == FALSE){
    //        $notify->setReturnParameter("return_code", "FAIL");//����״̬��
    //        $notify->setReturnParameter("return_msg", "ǩ��ʧ��");//������Ϣ
    //    }else{
    //        $notify->setReturnParameter("return_code", "SUCCESS");//���÷�����
    //    }
    //    $returnXml = $notify->returnXml();
    //    //==�̻�����ʵ�����������Ӧ�Ĵ������̣��˴���������=======
    //    //��log�ļ���ʽ��¼�ص���Ϣ
    //    //$log_name = "notify_url.log";//log�ļ�·��
    //    //$this->log_result($log_name, "�����յ���notify֪ͨ��:\n".$xml."\n");
    //    $parameter = $notify->xmlToArray($xml);
    //    //$this->log_result($log_name, "�����յ���notify֪ͨ��:\n".$parameter."\n");
    //    if($notify->checkSign() == TRUE){
    //        if ($notify->data["return_code"] == "FAIL") {
    //            //�˴�Ӧ�ø���һ�¶���״̬���̻�������ɾ����
    //            //$this->log_result($log_name, "��ͨ�ų���:\n".$xml."\n");
    //            //���¶������ݡ�ͨ�ų�����Ϊ��Ч����
    //            echo 'error';
    //        }
    //        else if($notify->data["result_code"] == "FAIL"){
    //            //�˴�Ӧ�ø���һ�¶���״̬���̻�������ɾ����
    //            //$this->log_result($log_name, "��ҵ�����:\n".$xml."\n");
    //            //���¶������ݡ�ͨ�ų�����Ϊ��Ч����
    //            echo 'error';
    //        }
    //        else{
    //            //$this->log_result($log_name, "��֧���ɹ���:\n".$xml."\n");
    //            //�������õ�һ��process�������ɹ��������ݺ������ص����ݾ�����Բο�΢�ŵ��ĵ�
    //            if ($this->process($parameter)) {
    //                //����ɹ������success��΢�žͲ������·�������
    //                echo 'success';
    //            }else {
    //                //û�д���ɹ���΢�Ż����ķ�������
    //                echo 'error';
    //            }
    //        }
    //    }
    //}
 
    ////��������www.bcty365.com
    //private function process($parameter) {
    //    //�˴�Ӧ�ø���һ�¶���״̬���̻�������ɾ����
    //    /*
    //    * ���ص��������������¼���
    //    * $parameter = array(
    //        'out_trade_no' => xxx,//�̻�������
    //        'total_fee' => XXXX,//֧�����
    //        'openid' => XXxxx,//������û�ID
    //    );
    //    */
    //    return true;
    //}
}
 
?>