<?php 
define('INDEX',TRUE);
/*Telegram Bot Token*/
$token = "";
/*IP Check Access Key*/
$ak = "";
if(@$_GET['token'] === $token) {
	$msg = @file_get_contents('php://input');
	$msg_json = json_decode($msg,true);
	$chat_id = $msg_json['message']['chat']['id'];
	$chat_username = $msg_json['message']['chat']['username'];
	$chat_type = $msg_json['message']['chat']['type'];
	$chat_text = $msg_json['message']['text'];
	$entities_type_command = $msg_json['message']['entities'][0]['type'];//bot_command
	
	/*校验是否IP查询请求*/
	if(preg_match('/\/iploc/i', $chat_text) && $entities_type_command === "bot_command") {
		/*校验会话是否授权*/
		if($chat_username === "scusec") {
			/*正则匹配提取IP*/
			preg_match("/[\d\.]{7,15}/",$chat_text,$getip);
			$ip = $getip[0] ? $getip[0] : '127.0.0.1';
			if($ip === "127.0.0.1"){
				$reply = urlencode("IP格式有误");
			} else {
				/*请求查询数据*/
				$ak = md5($ip.$ak);
				$reply = @file_get_contents("/ipLoc/index.php?ip=".$ip."&ak=".$ak);
				$reply = $reply ? $reply : '接口调用失败';
              			if($reply !==  "接口调用失败")$reply_json = json_decode($reply,true);
              			if($reply_json['status'] ===  "1")$reply = "IPIP-NET:\n".$reply_json['data']['ipipnet']."\n百度:\n".$reply_json['data']['baidu']."\nIPPLUS360:\n".$reply_json['data']['ipplus360']."\nRTBAsia:\n".$reply_json['data']['rtbasia']."\n高德:\n".$reply_json['data']['amap']."\n搜狗:\n".$reply_json['data']['gomap'];
				$reply = urlencode("IP:".$ip."\n".$reply);
			}
		} else {
			$reply = urlencode("此群组或用户未授权");
		}
		$callback = @file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?chat_id=".$chat_id."&text=".$reply);
	}
	
	/*校验是否番号查询请求*/
	if(preg_match('/\/jav/i', $chat_text) && $entities_type_command === "bot_command") {
		if($chat_username === "scusec") {
			$reply = urlencode("本群未开放此接口，请尝试私聊bot");
			$callback = @file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?chat_id=".$chat_id."&text=".$reply);
		} else {
		/*正则匹配番号*/
		$code = preg_replace('/\/jav/i',"",$chat_text);
		$code = trim($code);
		/*请求查询数据*/
		$reply = @file_get_contents("pornsearch.php?inf=1&url=1&code=".$code);
		$photo = @file_get_contents("pornsearch.php?img=1&code=".$code);
		$reply = $reply ? $reply : '无番剧信息';
		$photo = $photo ? $photo : '无图片信息';
		$reply = urlencode("#NSFW\n".$reply);
		$photo = urlencode("\n".$photo);
		if($chat_type === "private"){
			$callback = @file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?chat_id=".$chat_id."&text=".$reply.$photo);
        } else {
        	$callback = @file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?chat_id=".$chat_id."&text=".$reply);
        }
		}
	}
	
	/*校验是否飙车请求*/
	if(preg_match('/\/nsfw/i', $chat_text) && $entities_type_command === "bot_command") {
		if($chat_username === "scusec") {
			$reply = urlencode("本群未开放此接口，请尝试私聊bot");
			$callback = @file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?chat_id=".$chat_id."&text=".$reply);
		} else {
		/*正则匹配番号*/
		$code = preg_replace('/\/nsfw/i',"",$chat_text);
		$code = trim($code);
		/*请求查询数据*/
		$reply = @file_get_contents("pornsearch.php?inf=1&url=2&code=".$code);
		$photo = @file_get_contents("pornsearch.php?img=1&code=".$code);
		$reply = $reply ? $reply : '无番剧信息';
		$photo = $photo ? $photo : '无图片信息';
		$reply = urlencode("#NSFW\n".$reply);
		$photo = urlencode("\n".$photo);
		$callback = @file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?chat_id=".$chat_id."&text=".$reply.$photo);
		}
	}
	
	/*校验是否有码番剧关键词查询请求*/
	if(preg_match('/\/pornso/i', $chat_text) && $entities_type_command === "bot_command") {
		if($chat_username === "scusec") {
			$reply = urlencode("本群未开放此接口，请尝试私聊bot");
			$callback = @file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?chat_id=".$chat_id."&text=".$reply);
		} else {
		/*正则匹配关键词*/
		$porn_key = preg_replace('/\/pornso/i',"",$chat_text);
		$porn_key = trim($porn_key);
		/*请求查询数据*/
		$reply = @file_get_contents("pornsearch.php?key=".$porn_key."&type=1");
		$reply = $reply ? $reply : '无番剧信息';
		$reply = urlencode("#NSFW\n".$reply);
		$callback = @file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?parse_mode=markdown&chat_id=".$chat_id."&text=".$reply);
		}
	}
	
	/*校验是否无码番剧关键词查询请求*/
	if(preg_match('/\/ucpornso/i', $chat_text) && $entities_type_command === "bot_command") {
		if($chat_username === "scusec") {
			$reply = urlencode("本群未开放此接口，请尝试私聊bot");
			$callback = @file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?chat_id=".$chat_id."&text=".$reply);
		} else {
		/*正则匹配关键词*/
		$porn_key = preg_replace('/\/ucpornso/i',"",$chat_text);
		$porn_key = trim($porn_key);
		/*请求查询数据*/
		$reply = @file_get_contents("pornsearch.php?uckey=".$porn_key);
		$reply = $reply ? $reply : '无番剧信息';
		$reply = urlencode("#NSFW\n".$reply);
		$callback = @file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?parse_mode=markdown&chat_id=".$chat_id."&text=".$reply);
		}
	}
	
	/*校验是否根据导演信息查询番剧请求*/
	if(preg_match('/\/porndir/i', $chat_text) && $entities_type_command === "bot_command") {
		if($chat_username === "scusec") {
			$reply = urlencode("本群未开放此接口，请尝试私聊bot");
			$callback = @file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?chat_id=".$chat_id."&text=".$reply);
		} else {
		/*正则匹配关键词*/
		$porn_key = preg_replace('/\/porndir/i',"",$chat_text);
		$porn_key = trim($porn_key);
		/*请求查询数据*/
		$reply = @file_get_contents("pornsearch.php?key=".$porn_key."&type=2");
		$reply = $reply ? $reply : '无番剧信息';
		$reply = urlencode("#NSFW\n".$reply);
		$callback = @file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?parse_mode=markdown&chat_id=".$chat_id."&text=".$reply);
		}
	}
	
	/*校验是否根据制作商信息查询番剧请求*/
	if(preg_match('/\/pornmake/i', $chat_text) && $entities_type_command === "bot_command") {
		if($chat_username === "scusec") {
			$reply = urlencode("本群未开放此接口，请尝试私聊bot");
			$callback = @file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?chat_id=".$chat_id."&text=".$reply);
		} else {
		/*正则匹配关键词*/
		$porn_key = preg_replace('/\/pornmake/i',"",$chat_text);
		$porn_key = trim($porn_key);
		/*请求查询数据*/
		$reply = @file_get_contents("pornsearch.php?key=".$porn_key."&type=3");
		$reply = $reply ? $reply : '无番剧信息';
		$reply = urlencode("#NSFW\n".$reply);
		$callback = @file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?parse_mode=markdown&chat_id=".$chat_id."&text=".$reply);
		}
	}
	
	/*校验是否根据发行商信息查询番剧请求*/
	if(preg_match('/\/pornissue/i', $chat_text) && $entities_type_command === "bot_command") {
		if($chat_username === "scusec") {
			$reply = urlencode("本群未开放此接口，请尝试私聊bot");
			$callback = @file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?chat_id=".$chat_id."&text=".$reply);
		} else {
		/*正则匹配关键词*/
		$porn_key = preg_replace('/\/pornissue/i',"",$chat_text);
		$porn_key = trim($porn_key);
		/*请求查询数据*/
		$reply = @file_get_contents("pornsearch.php?key=".$porn_key."&type=4");
		$reply = $reply ? $reply : '无番剧信息';
		$reply = urlencode("#NSFW\n".$reply);
		$callback = @file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?parse_mode=markdown&chat_id=".$chat_id."&text=".$reply);
		}
	}
	
	/*校验是否根据影片系列信息查询番剧请求*/
	if(preg_match('/\/pornser/i', $chat_text) && $entities_type_command === "bot_command") {
		if($chat_username === "scusec") {
			$reply = urlencode("本群未开放此接口，请尝试私聊bot");
			$callback = @file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?chat_id=".$chat_id."&text=".$reply);
		} else {
		/*正则匹配关键词*/
		$porn_key = preg_replace('/\/pornser/i',"",$chat_text);
		$porn_key = trim($porn_key);
		/*请求查询数据*/
		$reply = @file_get_contents("pornsearch.php?key=".$porn_key."&type=5");
		$reply = $reply ? $reply : '无番剧信息';
		$reply = urlencode("#NSFW\n".$reply);
		$callback = @file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?parse_mode=markdown&chat_id=".$chat_id."&text=".$reply);
		}
	}
	
	/*定时删除	*/
	if(isset($callback) && $chat_type !== "private") {
		$callback = json_decode($callback,true);
		$chat_id = $callback['result']['chat']['id'];
		$message_id = $callback['result']['message_id'];
		system("php bot_callback_cli.php -k ".$token." -c ".$chat_id." -m ".$message_id." >> /tmp/telegramBot.log &");
	}
}
?>
