---
icon: Set up custom authentication for your published content
---

### SchoolAI 统一身份认证系统
#### 第三方应用客户端集成OAuth Demo演示程序

PHP代码: https://github.com/SmartSchoolAI/SchoolDataCenter/tree/main/htdocs/api/oauth/client
代码说明:
config.inc.php 配置信息填写
index.php 第三方应用系统的首页
login.php 第三方应用系统登录页面, 如果没有登录, 会直接跳转到OAuth服务器端进行身份认证
callback.php 当OAuth服务器认证通过以后, 会得到一个Code, 然后在这个文件里面把Code转换为用户信息, 最后处理第三方应用系统自己的身份授权信息

#### 第三方应用客户端配置信息示例:
oauth2AppUrl = http://localhost/client
oauth2Redirect_uri = http://localhost/client/callback.php
oauth2UrlPrefix = http://demoapi.dandian.net/api/oauth
oauth2clientid  = xxxxxx
oauth2clientsecret = xxxxxx
oauth2LoginUrl = ${oauth2UrlPrefix}/authorization.php?response_type=code&client_id=${oauth2clientid}&redirect_uri=${oauth2Redirect_uri}&state=xyz
oauth2TokenUrl = ${oauth2UrlPrefix}/codeToAccessToken.php
oauth2ResourceUrl = ${oauth2UrlPrefix}/accessTokenToUserInfo.php
uidKey = id

#### API 接口说明
##### http://demoapi.dandian.net/api/oauth/codeToAccessToken.php
作用：把code转为access token
方法：POST
提交表单对像： [
    'grant_type' => 'authorization_code',
    'code' => $_GET['code'],
    'redirect_uri' => $redirect_uri,
    'client_id' => $client_id,
    'client_secret' => $client_secret
  ]
返回：[ 'access_token' => $access_token, .... ]
Demo PHP Code: https://github.com/SmartSchoolAI/SchoolDataCenter/blob/main/htdocs/api/oauth/client/callback.php

##### http://demoapi.dandian.net/api/oauth/accessTokenToUserInfo.php
作用：根据access token转换为用户信息
方法：POST
提交表单对像： [ 'access_token' => $RS['access_token'] ]
Header: []
返回：[ 'id' => $id, .... ];
Demo PHP Code: https://github.com/SmartSchoolAI/SchoolDataCenter/blob/main/htdocs/api/oauth/client/callback.php



| 系统截图  | 系统截图 |
|-------|-----------|
| <img src="./images/01.png" > | <img src="./images/02.png" > |
| <img src="./images/03.png" > | <img src="./images/04.png" > |
| <img src="./images/05.png" > | <img src="./images/06.png" > |
| <img src="./images/07.png" > | <img src="./images/08.png" > |
| <img src="./images/09.png" > | |