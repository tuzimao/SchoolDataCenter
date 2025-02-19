<?php

    // Remove all warning messages
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_WARNING & ~E_NOTICE);
    $array = $slashrootreal = $backslashrootreal = $doublebackslashrootreal = array();

	$curdir = getcwd();
	$usbstick="0";

	list($partition, $nonpartition) = preg_split ("/:/", $curdir); //Fix by Wiedmann
	$partwampp = substr(realpath(__FILE__), 0, strrpos(dirname(realpath(__FILE__)), '\\'));

	$directorwampp = NULL;
	if ($usbstick == "1" ) {
	  $dirpartwampp=$nonpartition;
    } else {
  	  $dirpartwampp=$partwampp;
    }

	$awkpart = str_replace("&", "\\\\&", preg_replace ("/\\\\/i", "\\\\\\\\", $dirpartwampp)); //Fix by Wiedmann
	$awkpartdoublebackslash = str_replace("&", "\\\\&", preg_replace ("/\\\\/i", "\\\\\\\\\\\\\\\\", $dirpartwampp)); //Fix by Wiedmann
	$awkpartslash = str_replace("&", "\\\\&", preg_replace ("/\\\\/", "/", $dirpartwampp)); //Fix by Wiedmann

	date_default_timezone_set('UTC');
	echo "\r\n  ########################################################################\n";
	echo "  # ApacheFriends XAMPP setup win32 Version                              #\r\n";
	echo "  #----------------------------------------------------------------------#\r\n";
	echo "  # Copyright (c) 2002-".date("Y")." Apachefriends                          #\r\n";
	echo "  #----------------------------------------------------------------------#\r\n";
	echo "  # Authors: Kay Vogelgesang <kvo@apachefriends.org>                     #\r\n";
	echo "  #          Carsten Wiedmann <webmaster@wiedmann-online.de>             #\r\n";
	echo "  ########################################################################\r\n\r\n";
	
	//替换文件
	$confhttpdroot = $partwampp."\apache\\conf\\httpd.conf";
	if(is_file($confhttpdroot)) {
		$InstallDir = str_replace("/xampp", "", $awkpartslash);
		$Content 	= file_get_contents($confhttpdroot);
		$Content 	= str_replace("D:/SchoolDataCenter", $InstallDir, $Content);
		file_put_contents($confhttpdroot, $Content);
		print "$confhttpdroot path replace finished.\n\n";
	}
	
	//替换文件
	$confhttpdroot = $partwampp."\apache\\conf\\extra\\httpd-xampp.conf";
	if(is_file($confhttpdroot)) {
		$InstallDir = str_replace("/xampp", "", $awkpartslash);
		$Content 	= file_get_contents($confhttpdroot);
		$Content 	= str_replace("D:/SchoolDataCenter", $InstallDir, $Content);
		file_put_contents($confhttpdroot, $Content);
		print "$confhttpdroot path replace finished.\n\n";
	}
	
	//替换文件
	$confhttpdroot = $partwampp."\apache\\conf\\extra\\httpd-ssl.conf";
	if(is_file($confhttpdroot)) {
		$InstallDir = str_replace("/xampp", "", $awkpartslash);
		$Content 	= file_get_contents($confhttpdroot);
		$Content 	= str_replace("D:/SchoolDataCenter", $InstallDir, $Content);
		file_put_contents($confhttpdroot, $Content);
		print "$confhttpdroot path replace finished.\n\n";
	}
	
	//替换文件
	$confhttpdroot = $partwampp."\php\\php.ini";
	if(is_file($confhttpdroot)) {
		$InstallDir = str_replace("/xampp", "", $awkpartslash);
		$Content 	= file_get_contents($confhttpdroot);
		$Content 	= str_replace("D:\SchoolDataCenter", $InstallDir, $Content);
		file_put_contents($confhttpdroot, $Content);
		print "$confhttpdroot path replace finished.\n\n";
	}
	
	//替换文件
	$confhttpdroot = $partwampp."\mysql\\bin\\my.ini";
	if(is_file($confhttpdroot)) {
		$InstallDir = str_replace("/xampp", "", $awkpartslash);
		$Content 	= file_get_contents($confhttpdroot);
		$Content 	= str_replace("D:/SchoolDataCenter", $InstallDir, $Content);
		file_put_contents($confhttpdroot, $Content);
		print "$confhttpdroot path replace finished.\n\n";
	}
	
	exit;
?>
