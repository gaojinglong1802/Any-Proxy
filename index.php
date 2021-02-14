<?php
if (strstr($_SERVER["REQUEST_URI"], "*q") !== false) {
    setcookie("urlss", "", "0", "/");
    header("Location: https://" . $_SERVER['HTTP_HOST']);
    exit;
}
if (substr($_SERVER["REQUEST_URI"], 1, 4) == "http" || $_POST['urlss']) {
    if ($_POST['urlss']) {
        $url = $_POST['urlss'];
    } else {
        $url = substr($_SERVER["REQUEST_URI"], 1);
    }
    $pageURL = parse_url($url);
    $pageURL["query"] ? $query = "?" . $pageURL["query"] : $query = "";
    $http = $pageURL['scheme'] . "://";
    $pageURLs = $http . $_SERVER['HTTP_HOST'];
    $pageURLs = $pageURLs . $pageURL["path"] . $query;
    setcookie("urlss", $http . $pageURL["host"], "0", "/");
    header("Location: " . $pageURLs);
    exit;
}
if (!$_COOKIE['urlss']) {
    exit('<html><head><meta charset="utf-8"><meta name="viewport" content="width=520, user-scalable=no, target-densitydpi=device-dpi"><title>代理访问_Any-Proxy</title><link rel="stylesheet" type="text/css" href="//s0.pstatp.com/cdn/expire-1-M/bootswatch/3.4.0/paper/bootstrap.min.css"><style type="text/css">.row{margin-top:100px}.page-header{margin-bottom:90px}.expand-transition{margin-top:150px;-webkit-transition:all.5s ease;transition:all.5s ease}</style></head><body><div id="app" class="container"><div class="alert top top-xs alert-dismissible alert-success expand-transition" style="display:none" id="success-tips"></div><div class="alert top top-xs alert-dismissible alert-danger expand-transition" style="display:none" id="error-tips"></div><div class="row row-xs"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-10 col-xs-offset-1 col-sm-offset-3 col-md-offset-3 col-lg-offset-3"><div class="page-header"><h3 class="text-center h3-xs">Any-Proxy</h3></div><form method="post"><div class="form-group " id="input-wrap"><label class="control-label" for="inputContent">请输入需访问的链接：</label><input type="text" id="inputContent" class="form-control" name="urlss" placeholder="http://"></div><div class="text-right"><input type="submit" class="input_group_addon btn btn-primary" value="GO"></div></div></form></div></div><div align="center" class="expand-transition"><p>你可以直接在当前链接后面输入 *q 退出当前页面返回首页</p><p>可直接在此域名后面加上链接地址访问，如 https://' . $_SERVER['HTTP_HOST'] . '/http://ip38.com </p></div></div><footer class="footer navbar-fixed-bottom" style="text-align:center"><div class="container"><p>请勿访问您当地法律所禁止的网页，否则后果自负。</p><p>©Powered by <a href="https://github.com/yitd/Any-Proxy">Any-Proxy</a></p></div></footer></body></html>');
}
//代理的域名及使用的协议最后不用加/
$target_host = $_COOKIE['urlss'];
if (strstr($target_host, "http") === false) {
    $target_host = "http://" . $target_host;
}
//处理代理的主机得到协议和主机名称
$protocal_host = parse_url($target_host);
//以.分割域名字符串
$rootdomain = explode(".", $_SERVER['HTTP_HOST']);
//获取数组的长度
$lenth = count($rootdomain);
//获取顶级域名
$top = "." . $rootdomain[$lenth - 1];
//获取主域名
$root = "." . $rootdomain[$lenth - 2];
$aAccess = curl_init();
// --------------------
// set URL and other appropriate options
curl_setopt($aAccess, CURLOPT_URL, $protocal_host['scheme'] . "://" . $protocal_host['host'] . $_SERVER["REQUEST_URI"]);
curl_setopt($aAccess, CURLOPT_HEADER, true);
curl_setopt($aAccess, CURLOPT_RETURNTRANSFER, true);
curl_setopt($aAccess, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($aAccess, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($aAccess, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($aAccess, CURLOPT_TIMEOUT, 60);
curl_setopt($aAccess, CURLOPT_BINARYTRANSFER, true);
//if(!empty($_SERVER['HTTP_REFERER']))
//curl_setopt($aAccess,CURLOPT_REFERER,$_SERVER['HTTP_REFERER']) ;
//关系数组转换成字符串，每个键值对中间用=连接，以; 分割
function array_to_str($array) {
    $string = "";
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            if (!empty($string)) $string.= "; " . $key . "=" . $value;
            else $string.= $key . "=" . $value;
        }
    } else {
        $string = $array;
    }
    return urldecode($string);
}
if ($_SERVER["HTTP_REFERER"]) {
    $referer = str_replace("http://" . $_SERVER['HTTP_HOST'], $protocal_host['scheme'] . "://" . $protocal_host['host'], $_SERVER["HTTP_REFERER"]);
}
if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $remoteip = $_SERVER['REMOTE_ADDR'];
} else {
    $remoteip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
//$headers = get_client_header();
$headers = array();
$headers[] = "Accept-language: " . $_SERVER['HTTP_ACCEPT_LANGUAGE'];
$headers[] = "Referer: " . $referer;
$headers[] = "CLIENT-IP: " . $remoteip;
$headers[] = "X-FORWARDED-FOR: " . $remoteip;
$headers[] = "Cookie: " . array_to_str($_COOKIE);
$headers[] = "user-agent: " . $_SERVER['HTTP_USER_AGENT'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $headers[] = "Content-Type: " . $_SERVER['CONTENT_TYPE'];
    curl_setopt($aAccess, CURLOPT_POST, 1);
    curl_setopt($aAccess, CURLOPT_POSTFIELDS, http_build_query($_POST));
}
curl_setopt($aAccess, CURLOPT_HTTPHEADER, $headers);
// grab URL and pass it to the browser
$sResponse = curl_exec($aAccess);
list($headerstr, $sResponse) = parse_header($sResponse);
$headarr = explode("\r\n", $headerstr);
foreach ($headarr as $h) {
    if (strlen($h) > 0) {
        if (strpos($h, 'Content-Length') !== false) continue;
        if (strpos($h, 'Transfer-Encoding') !== false) continue;
        if (strpos($h, 'Connection') !== false) continue;
        if (strpos($h, 'HTTP/1.1 100 Continue') !== false) continue;
        if (strpos($h, 'Set-Cookie') !== false) {
            $targetcookie = $h . ";";
            $res_cookie = preg_replace("/domain=.*?;/", "domain=" . $root . $top . ";", $targetcookie);
            $h = substr($res_cookie, 0, strlen($res_cookie) - 1);
        }
        header($h);
    }
}
function get_client_header() {
    $headers = array();
    foreach ($_SERVER as $k => $v) {
        if (strpos($k, 'HTTP_') === 0) {
            $k = strtolower(preg_replace('/^HTTP/', '', $k));
            $k = preg_replace_callback('/_\w/', 'header_callback', $k);
            $k = preg_replace('/^_/', '', $k);
            $k = str_replace('_', '-', $k);
            if ($k == 'Host') continue;
            $headers[] = "$k: $v";
        }
    }
    return $headers;
}
function header_callback($str) {
    return strtoupper($str[0]);
}
function parse_header($sResponse) {
    list($headerstr, $sResponse) = explode("\r\n\r\n", $sResponse, 2);
    $ret = array($headerstr, $sResponse);
        if (preg_match('/^HTTP\/1\.1 \d{3}/', $sResponse)) {
            $ret = parse_header($sResponse);
        }
    return $ret;
}
// close cURL resource, and free up system resources
$sResponse = str_replace("http://" . $protocal_host['host']."/", "https://" . $_SERVER['HTTP_HOST']."/", $sResponse);
$sResponse = str_replace("https://" . $protocal_host['host']."/", "https://" . $_SERVER['HTTP_HOST']."/", $sResponse);
curl_close($aAccess);
echo $sResponse;
?>
