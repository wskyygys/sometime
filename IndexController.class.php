<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
		//接收参数 nonce timestamp signature echostr
		$token     = 'wskyygyso';
		$nonce     = $_GET['nonce'];
		$timestamp = $_GET['timestamp'];
		$signature = $_GET['signature'];
		$echostr   = $_GET['echostr'];
		//将参数进行拼接,加密;
		$array     = array($token,$nonce,$timestamp);
		sort($array);
		$temstr    = implode('',$array);
		$tstr      = sha1($temstr);
		if($tstr == $signature && $echostr){
			echo $echostr;
			exit;
		}else{
			$this->reponseMsg();
		}
	}
	
	public function reponseMsg(){
		//获取数据 POST XML类型;
		$postData  = $GLOBALS['HTTP_RAW_POST_DATA'];
		//处理消息类型,并设置回复类型和内容
		$postObj = simplexml_load_string($postData);
		//$postObj->ToUserName = '';
		//$postObj->FromUserName = '';
		//$postObj->CreateTime = '';
		//$postObj->Msgtype = ''; 
		//$postObj->Event = '';
		if(strtolower($postObj->MsgType) == 'event'){
			//如果是关注事件 subscribe
			if(strtolower($postObj->Event) == 'subscribe'){
				//回复用户消息
				$toUser   = $postObj->FromUserName;
				$fromUser = $postObj->ToUserName;
				$time     = time();
				$msgtype  = 'text';
				$content  = '欢迎关注我的微信!';
				$template = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					</xml>";
				$info = sprintf($template,$toUser,$fromUser,$time,$msgtype,$content);
				echo $info;
			}
			//自定义菜单回复
			if(strtolower($postObj->Event) == 'click'){
					if(strtolower($postObj->EventKey) == 'lishi'){
						$y = date('m');
						$r = date('d');
						$ct = 'http://api.avatardata.cn/HistoryToday/LookUp?key=70df7d5736c14c3fb8063d92972a4894&yue='.$y.'&ri='.$r.'&type=1&page=1&rows=10';
						$ch = curl_init();
						curl_setopt($ch,CURLOPT_URL,$ct);
						curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
						if(curl_errno($ch)){
							print_r(curl_errno($ch));
						}
						// $result = json_decode($result,true);
						$result = curl_exec($ch);
						$result = json_decode($result,true);
						$content ='';
						foreach($result['result'] as $k=>$v){
							$content .= $k.'~'.$v['year'].'-'. $v['month'].'-'. $v['day'].':'.$v['title'].'-----';
						}
						//回复用户消息
				$toUser   = $postObj->FromUserName;
				$fromUser = $postObj->ToUserName;
				$time     = time();
				$msgtype  = 'text';
				$template = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					</xml>";
				$info = sprintf($template,$toUser,$fromUser,$time,$msgtype,$content);
				echo $info;
					}
					if(strtolower($postObj->EventKey) == 'tianqi'){
						$access_token = $this->getAccesstoken();
						$toUser   = $postObj->FromUserName;
						$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$toUser.'&lang=zh_CN';
						
						$ch = curl_init();
						curl_setopt($ch,CURLOPT_URL,$url);
						curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
						$result = curl_exec($ch);
						$result = json_decode($result,true);
						$city = urlencode($result['city']);
						$ct = 'http://api.avatardata.cn/Weather/Query?key=29c10c89368243e69c38d346816a85cc&cityname='.$city;
						$ch = curl_init();
						curl_setopt($ch,CURLOPT_URL,$ct);
						curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
						$result = curl_exec($ch);
						$result = json_decode($result,true);
						$content = $result['result']['realtime']['city_name'].':吹'.$result['result']['realtime']['wind']['direct'].'~风力:'.$result['result']['realtime']['wind']['power'].'~'.$result['result']['realtime']['weather']['info'].'~'.$result['result']['realtime']['weather']['temperature'].'°C';
						//回复用户消息
				$toUser   = $postObj->FromUserName;
				$fromUser = $postObj->ToUserName;
				$time     = time();
				$msgtype  = 'text';
				$template = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					</xml>";
				$info = sprintf($template,$toUser,$fromUser,$time,$msgtype,$content);
				echo $info;	
						}
				
			}
			if(strtolower($postObj->EventKey) == 'xinwen'){
					$arrdata = array(
				array(
					'title'=>'想去看场雪',
					'description'=>'很久没见过雪了',
					'picUrl'=>'http://123.207.20.81/Public/1463111671524.jpg',
					'url'=>'http://www.baidu.com'
				),
					array(
					'title'=>'已经没有雪',
					'description'=>'她已走远',
					'picUrl'=>'http://123.207.20.81/Public/1463111671524.jpg',
					'url'=>'http://www.baidu.com'
				),
			);
			$template = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
				<ArticleCount>".count($arrdata)."</ArticleCount>
				<Articles>";
			foreach($arrdata as $k=>$v){
					$template .= "<item>
						<Title><![CDATA[".$v['title']."]]></Title> 
						<Description><![CDATA[".$v['description']."]]></Description>
						<PicUrl><![CDATA[".$v['picUrl']."]]></PicUrl>
						<Url><![CDATA[".$v['url']."]]></Url>
						</item>";
			}//foreach end
				
			$template .="</Articles>
				</xml>";
				$toUser   = $postObj->FromUserName;
				$fromUser = $postObj->ToUserName;
				$time     = time();
				$MsgType  = 'news';
				$info = sprintf($template,$toUser,$fromUser,$time,$MsgType);
				echo $info;
				}
			}
		
		//图文消息
		if(strtolower($postObj->MsgType) == 'text' && trim($postObj->Content)=='新闻'){
			$arrdata = array(
				array(
					'title'=>'想去看场雪',
					'description'=>'很久没见过雪了',
					'picUrl'=>'http://123.207.20.81/Public/1463111671524.jpg',
					'url'=>'http://www.baidu.com'
				),
					array(
					'title'=>'已经没有雪',
					'description'=>'她已走远',
					'picUrl'=>'http://123.207.20.81/Public/1463111671524.jpg',
					'url'=>'http://www.baidu.com'
				),
			);
			$template = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
				<ArticleCount>".count($arrdata)."</ArticleCount>
				<Articles>";
			foreach($arrdata as $k=>$v){
					$template .= "<item>
						<Title><![CDATA[".$v['title']."]]></Title> 
						<Description><![CDATA[".$v['description']."]]></Description>
						<PicUrl><![CDATA[".$v['picUrl']."]]></PicUrl>
						<Url><![CDATA[".$v['url']."]]></Url>
						</item>";
			}//foreach end
				
			$template .="</Articles>
				</xml>";
				$toUser   = $postObj->FromUserName;
				$fromUser = $postObj->ToUserName;
				$time     = time();
				$MsgType  = 'news';
				$info = sprintf($template,$toUser,$fromUser,$time,$MsgType);
				echo $info;
			// $toUser = $postObj->FromUserName;
			// $fromUser = $postObj->ToUserName;
			// $arr = array(
				// array(
					// 'title'=>'想去看场雪',
					// 'description'=>'很久没见过雪了',
					// 'picUrl'=>'http://123.207.20.81/Public/1463111671524.jpg',
					// 'url'=>'http://www.baidu.com'
				// ),
					// array(
					// 'title'=>'已经没有雪',
					// 'description'=>'她已走远',
					// 'picUrl'=>'http://123.207.20.81/Public/1463111671524.jpg',
					// 'url'=>'http://www.baidu.com'
				// ),
			// );
			// $template = "<xml>
						// <ToUserName><![CDATA[%s]]></ToUserName>
						// <FromUserName><![CDATA[%s]]></FromUserName>
						// <CreateTime>%s</CreateTime>
						// <MsgType><![CDATA[%s]]></MsgType>
						// <ArticleCount>".count($arr)."</ArticleCount>
						// <Articles>";
			// foreach($arr as $k=>$v){
				// $template .="<item>
							// <Title><![CDATA[".$v['title']."]]></Title> 
							// <Description><![CDATA[".$v['description']."]]></Description>
							// <PicUrl><![CDATA[".$v['picUrl']."]]></PicUrl>
							// <Url><![CDATA[".$v['url']."]]></Url>
							// </item>";
			// }
			
			// $template .="</Articles>
						// </xml> ";
			// echo sprintf($template, $toUser, $fromUser, time(), 'news');

			//注意：进行多图文发送时，子图文个数不能超过10个
			
			}//if end
			else{
				//关键字
			switch(trim($postObj->Content)){
				case '历史':
				$host = "http://jisuxhdq.market.alicloudapi.com";
				$path = "/xiaohua/text";
				$method = "GET";
				$appcode = "36d05f2169284e6088d6faf3431dc03e";
				$headers = array();
				array_push($headers, "Authorization:APPCODE " . $appcode);
				$querys = "pagenum=1&pagesize=1&sort=addtime";
				$bodys = "";
				$url = $host . $path . "?" . $querys;

				$curl = curl_init();
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($curl, CURLOPT_FAILONERROR, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_HEADER, false);
				if (1 == strpos("$".$host, "https://"))
				{
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
				}
				$content = curl_exec($curl);
				$content1 = json_decode($content,true);
				$content2 = $content1['result']['list'][0]['content'];
				$content  = $content2 ;
				break;
				case '天气';
				$content  = '今天天气不错';
				break;
				case '地理';
				$content  = '你在地球上';
				break;
				case '百度':
				$content  = '<a href="http://baidu.com">你在地球上</a>';
				break;
				
			}//switch end
				$toUser   = $postObj->FromUserName;
				$fromUser = $postObj->ToUserName;
				$time     = time();
				$msgtype  = 'text';
				$template = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					</xml>";
				$info = sprintf($template,$toUser,$fromUser,$time,$msgtype,$content);
				echo $info;
				
				
			}//else edf 
			}//function end
			
		function http_curl($url,$method,$data=array()){
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			if($method=='post'){
				curl_setopt($ch,CURLOPT_POST,1);
				curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
			}
			$result = curl_exec($ch);
			if(curl_errno($ch)){
				echo curl_exec($ch);
				exit;
			}
			return $result = json_decode($result,true);
		}
		
		 public function getAccesstoken(){
			if($_SESSION['access_token'] && $_SESSION['token_time']>time()){
				return $_SESSION['access_token'] ;
			}else{
				$appid = 'wx10857a8763f55c16';
				$appSecret = '9bb172859ec1dd9bf51e3292186a9abe';
				$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appSecret;
				$method = 'get';
				$result =  $this->http_curl($url,$method,$data);
				$_SESSION['access_token'] = $result['access_token'] ;
				$_SESSION['token_time'] = time() + 7000 ;
				return $result['access_token'] ;
			}
		}//end getAccesstoken
		
		public function getMenu(){
			header('content-type:text/html;charset=utf-8');
			$access_token = $this->getAccesstoken();
			$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
			$data = array(
				'button'=>array(
					array(
						'type'=>'click',
						'name'=>urlencode('历史上的今天'),
						'key'=>'lishi',
					),//第一个菜单
					array(
						'type'=>'click',
						'name'=>urlencode('天气'),
						'key'=>'tianqi',
					),//第二个菜单
					array(
						'type'=>'click',
						'name'=>urlencode('新闻'),
						'key'=>'xinwen',
					),//第三个菜单
				),
			);
			$data = urldecode(json_encode($data));
			$method = 'post';
			
			//$result2 = $this->http_curl($url,$method,$data);
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
			$result = curl_exec($ch);
			$result = json_decode($result,true);
			if($result['errcode'] != '0'){
				print_r($result['errcode']);
			}else{
				print_r($url);
				echo '</br>';
				print_r($data);
				echo '</br>';
				print_r($result);
			}
			
		}
		
		public function deletemenu(){
			header('content-type:text/html;charset=utf-8');
			session_destroy();
			$access_token = $this->getAccesstoken();
			$url = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.$access_token;
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			$result = curl_exec($ch);
			if($result['errcode'] != '0'){
				echo $result['errcode'];
				exit;
			}else{
				print_r($result2);
			}
			
		}
	public function webAccess(){
		$appid = 'wx10857a8763f55c16';
		$redir_url = urlencode('http://www.wskyygyso.com/index.php/home/index/webdisplay');
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redir_url.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
		$result = $this->http_curl($url,'get',$data);
		//$this->redirect('http://123.207.20.81/index.php/home/index/webdisplay');
		header('Location:'.$url);
	}
	public function webdisplay(){
		$appid = 'wx10857a8763f55c16';
		$appSecret = '9bb172859ec1dd9bf51e3292186a9abe';
		$code = $_GET['code'];
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appSecret.'&code='.$code.'&grant_type=authorization_code';
		$result = $this->http_curl($url);
		$access_token = $result['access_token'];
		$opid = $result['openid'];
		$opurl = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$opid.'&lang=zh_CN';
		$res = $this->http_curl($opurl);
		print_r($result);
	}
	public function issetsession(){
		session_destroy();
	}
	public function ceshi(){
		$day = date('d');
		$thismonth = date('m');
		$thisyear = date('Y');
		echo $day .'--'.$thismonth.'--'.$thisyear ;
	}
}