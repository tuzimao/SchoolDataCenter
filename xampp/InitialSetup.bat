chcp 65001
@echo off

echo ################################# Begin XAMPP Test #################################
echo [XAMPP]: Test php.exe with php\php.exe -n -d output_buffering=0 --version ...
php\php.exe -n -d output_buffering=0 --version
if %ERRORLEVEL% GTR 0 (
  echo:
	echo [ERROR]: Test php.exe Failed !!!
	echo [ERROR]: Perhaps the Microsoft C++ 2008 runtime package Not Install.  
  echo [ERROR]: please install MS VC++ 2008 Redistributable Package
  echo [ERROR]: http://www.microsoft.com/en-us/download/details.aspx?id=5582
  echo:
  echo ################################# End XAMPP Test ###################################
  echo:
  pause
  exit 1
)
echo [XAMPP]: Test OK
echo ################################# End XAMPP Test ###################################
echo: 


if "%1" == "sfx" (
    cd xampp
)
if exist php\php.exe GOTO Normal
if not exist php\php.exe GOTO Abort

:Abort
echo Not found php cli!
pause
GOTO END

:Normal
set PHP_BIN=php\php.exe
set CONFIG_PHP=install\install.php
%PHP_BIN% -n -d output_buffering=0 %CONFIG_PHP%
GOTO END

:END
pause
