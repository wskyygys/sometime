<?php

/**
 * HttpRequest short summary.
 *
 * HttpRequest description.
 *
 * @version 1.0
 * @author 华亮
 */
class JSHttpRequest
{
    static public function send_post_html($durl='',$post_data='')
    {
   //     $ch = curl_init();
        $post_data = http_build_query($post_data);
        $url = $durl.'?'.$post_data;   //     $url='http://www.domain.com/';  
        $html = file_get_contents($url);  
    //    $html = json_decode($html);
    //    echo json_encode($html);  
        return $html;
    }
    
    static public function send_post_html22($durl='',$post_data='')
    {
        $post_data = http_build_query($post_data);
        $url = $durl.'?'.$post_data;                           
        $htmlarr = urldecode($url);     //对urlencode 进行解码
        $html = file_get_contents($htmlarr); 
        return $html;
    }
    
    static public function send_post_htmlinfo($durl='')
    {
        //     $ch = curl_init();
        // $post_data = http_build_query($post_data);
        $url = $durl; 
        $html = file_get_contents($url);  
        //    $html = json_decode($html);
        //    echo json_encode($html);  
        return $html;
    }
    static public function send_post(&$url, &$post_data) {
        $postdata = http_build_query($post_data);
        $options = array(
        'http' => array(
        'method' => 'POST',//or GET
        'header' => 'Content-type:application/x-www-form-urlencoded',
        'content' => $postdata,
        'timeout' => 60 *5 // 超时时间（单位:s）
        )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
    static public function send_post_get(&$url, &$post_data) {
        $postdata = http_build_query($post_data);
        $options = array(
        'http' => array(
        'method' => 'GET',//or GET
        'header' => 'Content-type:application/x-www-form-urlencoded',
        'content' => $postdata,
        'timeout' => 60 *5 // 超时时间（单位:s）
        )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
    static public function send_post_post_textjson(&$url, &$post_data) {
        $postdata = http_build_query($post_data);
        $options = array(
        'http' => array(
        'method' => 'POST',//or GET
        'header' => 'Content-type:application/json',
        'content' => $postdata,
        'timeout' => 60 *5 // 超时时间（单位:s）
        )
        );
        $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
        return $result;
    }
    static public function send_post_get_textjson(&$url, &$post_data) {
        $postdata = http_build_query($post_data);
        $options = array(
        'http' => array(
        'method' => 'GET',//or GET
        'header' => 'Content-type:application/json',
        'content' => $postdata,
        'timeout' => 60 *5 // 超时时间（单位:s）
        )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
    static public function send_post_get_html(&$url, &$post_data) {
        $postdata = http_build_query($post_data);
        $options = array(
        'http' => array(
        'method' => 'GET',//or GET
        'header' => 'Content-type:text/html; charset=utf-8 ',
        'content' => $postdata,
        'timeout' => 60 *5 // 超时时间（单位:s）
        )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
    static public function curl_file_get_contents(&$durl,&$post_data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $durl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
        curl_setopt($ch, CURLOPT_REFERER,_REFERER_);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8"));
        $r = curl_exec($ch);
        //    dump(curl_error($ch));
        curl_close($ch);
        return $r;
    }
    static public function curl_file_get_contents_xml(&$durl,&$post_data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $durl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
        curl_setopt($ch, CURLOPT_REFERER,_REFERER_);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml; charset=utf-8"));
        $r = curl_exec($ch);
        //    dump(curl_error($ch));
        curl_close($ch);
        return $r;
    }
    static public function curl_file_get_contents_json(&$durl,&$post_data){
        $ch = curl_init();
        $post_data = http_build_query($post_data);
        $url = $durl.'?'.$post_data;
        curl_init($ch, CURLOPT_URL, $durl);
        curl_init($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_init($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        $r = curl_exec($ch);
        //    dump(curl_error($ch));
        curl_close($ch);
        return $r;
    }
}
