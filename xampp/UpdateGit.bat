@echo off

REM Go to repo dir
cd /d "D:\SchoolDataCenter"

REM Give up all change in local repo
git checkout -- .

REM Update Code From Github
git pull origin main


del "D:\SchoolDataCenter\xampp\mysql\data\mysqld.dmp"

rd /S /Q D:\SchoolDataCenter\htdocs\webroot

powershell -Command "Expand-Archive -Path D:\SchoolDataCenter\htdocs\output\webroot.zip -DestinationPath D:\SchoolDataCenter\htdocs -Force"

powershell -command "$shell = New-Object -ComObject Shell.Application; $zip = $shell.NameSpace('D:\SchoolDataCenter\htdocs\output\webroot.zip'); $dest = $shell.NameSpace('D:\SchoolDataCenter\htdocs'); $dest.CopyHere($zip.Items(), 16)"


git status

pause