> Any-Proxy可以帮助你匿名反向代理浏览任何网站  
  
需配置伪静态，nginx伪静态规则如下：  
if ( !-e $request_filename) {  
    rewrite ^/(.*)$ /index.php?$1 last;  
    break;  
}  
  
支持POST、cookie  
需配置好https再使用  
建立好后你可以直接在当前链接后面输入 *q 退出当前页面返回首页  

也可以直接 http://你的域名/http://加需访问链接  
  
如 ：http://xxx.com/http://www.ip38.com/  
  
  
  
基于：https://github.com/koala0529/reverse-proxy-php 修改  
  
