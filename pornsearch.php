<?php 
define('INDEX',TRUE);
if(@$_GET['code']) {
	$pornCode = $_GET['code'];
} else {
	exit('番号为空');
}
$headers = randIp();
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,"https://www.javbus.com/".$pornCode);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.157 Safari/537.36");
curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);//构造HTTP头
curl_setopt($ch, CURLOPT_TIMEOUT, 300);//设置超时限制防止死循环 
$getPornPage = curl_exec($ch);
$httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
curl_close($ch);
if($getPornPage === FALSE||$httpCode >= "300" ){
	echo "未查询到番剧内容";
	} else {
		if(@$_GET['img'] === '1') {
			/*提取图片信息*/
			preg_match_all('/<a.class=\"bigImage\".href=\"(.*?)\">/is',$getPornPage,$moviePic);
			echo $moviePic[1][0];
		}
		if(@$_GET['inf'] === '1') {
			/*提取影片信息*/
			preg_match_all('/<h3>(.*?)<\/h3>/is',$getPornPage,$movieTitle);
			preg_match_all('/識別碼:<\/span>.<span.style=\".*?\">([\s\S]*?)<\/span>/is',$getPornPage,$movieCode);
			preg_match_all('/發行日期:<\/span>(.*?)<\/p>/is',$getPornPage,$movieDate);
			preg_match_all('/長度:<\/span>(.*?)<\/p>/is',$getPornPage,$movieTime);
			preg_match_all('/<div.class=\"star-name\"><a.*?>([\s\S]*?)<\/a><\/div>/is',$getPornPage,$movieActor);
			preg_match_all('/導演:<\/span>.<a.*?>(.*?)<\/a>/is',$getPornPage,$movieDirector);
			preg_match_all('/製作商:<\/span>.<a.*?>(.*?)<\/a>/is',$getPornPage,$movieMake);
			preg_match_all('/發行商:<\/span>.<a.*?>(.*?)<\/a>/is',$getPornPage,$movieIssue);
			preg_match_all('/<span.class=\"genre\"><a.href=\"https:\/\/www.javbus.com\/genre\/[^\s]*">([\s\S]*?)<\/a>/is',$getPornPage,$movieTag);
			$act = '';
			$tag = '';
			foreach($movieActor[1] as $actor){
				$act = $actor." ".$act; 
			} 
			foreach($movieTag[1] as $mTag){ 
				$tag = $mTag." ".$tag; 
			}
			echo $movieTitle[1][0]."\n番号:".$movieCode[1][0]."\n發行:".$movieDate[1][0]." ".$movieTime[1][0]."\n導演:".$movieDirector[1][0]."\n演员:".$act."\n製作商:".$movieMake[1][0]."  發行商:".$movieIssue[1][0]."\n标签:".$tag;
		}
		if(@$_GET['url'] === '1') {
			/*获取磁力链接并返回前两条*/
			$getGid = preg_match('/(var\sgid\s+)=.\d+/i',$getPornPage,$getPorn);
			$getGid = preg_match('/\d+/', $getPorn[0], $pornGid);
			if($getGid) {
				$gid = $pornGid[0];
				$headers = randIp();
				$ch = curl_init();
				$referer = "https://www.javbus.com/".$pornCode;
				curl_setopt($ch,CURLOPT_URL,"https://www.javbus.com/ajax/uncledatoolsbyajax.php?uc=0&gid=".$gid);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
				curl_setopt($ch,CURLOPT_HEADER,0);
				curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.157 Safari/537.36");
				curl_setopt($ch,CURLOPT_REFERER,$referer);
				curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);//构造HTTP头
				curl_setopt($ch, CURLOPT_TIMEOUT, 300);//设置超时限制防止死循环 
				$getUrlPage = curl_exec($ch);
				$httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
				curl_close($ch);
				if($getUrlPage === FALSE||$httpCode >= "300" ){
					echo 'Bad Requests';
				} else {
					preg_match_all('/<td.width=\".*?\">\s+<a .*?href="(.*?)".*?>[(^\s*)|(\s*$)]([\s\S]*?)\s+[<a|<\/a>]/is',$getUrlPage,$getUrl);
					echo "\n《".trim($getUrl[2][0])."》链接:\n".urldecode($getUrl[1][0])."\n《".trim($getUrl[2][1])."》链接:\n".urldecode($getUrl[1][1]);
				}
			} else {
				echo 'Bad Requests';
			}
		}
}

/*生成随机IP的HTTP头*/
function randIP(){
	$ip_long = array(
		array('607649792', '608174079'), //36.56.0.0-36.63.255.255
		array('1038614528', '1039007743'), //61.232.0.0-61.237.255.255
		array('1783627776', '1784676351'), //106.80.0.0-106.95.255.255
		array('2035023872', '2035154943'), //121.76.0.0-121.77.255.255
		array('2078801920', '2079064063'), //123.232.0.0-123.235.255.255
		array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255
		array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255
		array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255
		array('-770113536', '-768606209'), //210.25.0.0-210.47.255.255
		array('-569376768', '-564133889'), //222.16.0.0-222.95.255.255
	);
	$rand_key = mt_rand(0, 9);
	$ip_rank = long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));
	$headers['CLIENT-IP'] = $ip_rank; 
	$headers['X-FORWARDED-FOR'] = $ip_rank; 
	$headers['Accept'] = 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
	$headers['Accept-Encoding'] = 'Accept-Encoding';
	$headers['Accept-Language'] = 'zh-CN,en-US;q=0.7,en;q=0.3';
	$headers['Cache-Control'] = 'no-cache';
	$headers['Connection'] = 'keep-alive';
	$headers['Host'] = 'www.javbus.com';
	$headers['Pragma'] = 'no-cache';
	$headers['Upgrade-Insecure-Requests'] = '1';
	$headers['X-Requested-With'] = 'XMLHttpRequest';
	$headerArr = array();
	foreach( $headers as $n => $v ) { 
		$headerArr[] = $n .':' . $v;  
	}
	return $headerArr;
} 
?>
