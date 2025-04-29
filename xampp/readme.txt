安装向导:

当前环境版本: Apache: 2.4.56  PHP: 8.2.4 Mysql: MariaDB 10.4

安装步骤:

1 如果本地没有安装Redis，则需要单独安装Redis服务端。Redis安装文件路径：安装目录\xampp\redis\Redis-x64-3.0.504.msi

2 双击 MySchoolDataCenter.XXX.exe， 程序安装过程中你可以指定安装路径，但要求是英文路径，不能使用中文路径。

3 程序安装完成以后，弹出一个命令对话框，此部分主要会把相关的路径替换为您解压的位置，执行完成以后，直接按任意键结束。

4 系统会打开xampp-control.exe， 然后手工启动Apache和Mysql，如果有提示端口冲突，可以更换为其它的端口。

5 APACHE和MYSQL正常启动以后，请在浏览器打开 http://localhost:8888 就可以看到系统的界面了。

6 测试账户
管理员: admin / 密码: Abcd1234!
系部: xibu / 密码: Abcd1234!
班主任: banzhuren / 密码: Abcd1234!
学生: 20230101 / 密码: Abcd1234!

7 如果在启动Apache的时候，提示VCRUNTIME140.dll错误，请安装如下文件： 安装目录\xampp\support\vcredist2022_x64.exe


# #000000，#E05757，#F5FD59，#61D328，#589AFD，#E05757，#7664FA，#65E5EC，#FFFFFF，#FA920A