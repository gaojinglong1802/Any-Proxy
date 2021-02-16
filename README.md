> Any-Proxy可以帮助你完美地反向代理浏览任意网站  
> 免去复杂的程序，仅需8KB文件即可兼容性极好地高速代理访问任意网站  
  
> 需配置伪静态，nginx伪静态规则如下：  
> if ( !-e $request_filename) {  
>     rewrite ^/(.*)$ /index.php?$1 last;  
>     break;  
> }  
  
> 支持POST、Cookie，https/http均可使用  
> 支持伪造IP，$anyip值为1发送服务器IP头，值为2则发送随机IP，值为3发送客户端IP
  
> 在当前链接末尾输入 *q 可以退出当前页面回到首页  
> 在域名后面加上链接地址即可访问  
> http://xxx.com/http://+需访问的链接 （必须添加http(s)://）  
  
> 如 ：http://xxx.com/http://ip38.com/  
  
  
  
  
  
![Image](https://p.pstatp.com/origin/1386c00047b0dffbf5283)  

> 基于：https://github.com/koala0529/reverse-proxy-php 修改  
