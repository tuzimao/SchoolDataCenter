<?php
/*
* 基础架构: 单点低代码开发平台
* 版权所有: 郑州单点科技软件有限公司
* Email: moodle360@qq.com
* Copyright (c) 2007-2025
* License: 商业授权
*/
header("Content-Type: application/json");
require_once('cors.php');
require_once('include.inc.php');

CheckAuthUserLoginStatus();
$sql	= "select left(代码,2) as ProvinceID,行政区 as ProvinceName from edu_xingzhengdaima where right(代码,10)='0000000000'";
$rs		= $db->Execute($sql);
$rsp_a	= $rs->GetArray();
foreach((array)$rsp_a as $keyp=>$value)
{
    $provinceID     = $value['ProvinceID'];
    $ProvinceName   = $value['ProvinceName'];
    $sql	= "select left(代码,4) as CityID,行政区 as CityName from edu_xingzhengdaima where left(代码,2)='$provinceID' and substr(代码,3,2)<>'00' and right(代码,8)='00000000'";
    $rs		= $db->Execute($sql);
    $rsc_a	= $rs->GetArray();
    foreach((array)$rsc_a as $keyc=>$valuep)
    {
        $CityName = $valuep['CityName'];
        if($ProvinceName!=$CityName)  $CityName = str_replace($ProvinceName,"",$CityName);
        $CityID = $valuep['CityID'];
        $sql	= "select 代码 as DistrictID,行政区 as DistrictName from edu_xingzhengdaima where left(代码,4)='$CityID' and substr(代码,5,2)<>'00' and right(代码,6)='000000'";
        $rs		= $db->Execute($sql);
        $rsd_a	= $rs->GetArray();
        foreach((array)$rsd_a as $keyd=>$valued)
        {
            $valued['DistrictName'] = str_replace($CityName,"",$valued['DistrictName']);
            $result[$ProvinceName][$CityName][$keyd] = $valued;
        }
        if(sizeof($rsd_a)==0)
        {
            $cityID	= substr($cityID,0,2);
            $sql	= "select 代码 as DistrictID,行政区 as DistrictName from edu_xingzhengdaima where left(代码,4)='$CityID'  and right(代码,8)='00000000'";
            $rs		= $db->Execute($sql);
            $rsd_a	= $rs->GetArray();
            $result[$ProvinceName][$CityName][] = $rsd_a[0];
        }
    }
    if(sizeof($rsc_a)==0)
    {
        $sql	= "select left(代码,2) as CityID,行政区 as CityName from edu_xingzhengdaima where left(代码,2)='$provinceID'  and right(代码,10)='0000000000'";
        $rs		= $db->Execute($sql);
        $rsc_a	= $rs->GetArray();
        $CityName = $rsc_a[0]['CityName'];
        if($ProvinceName!=$CityName)  $CityName = str_replace($ProvinceName,"",$CityName);
        $CityID = $rsc_a[0]['CityID'].'01';
        $sql	= "select 代码 as DistrictID,行政区 as DistrictName from edu_xingzhengdaima where left(代码,4)='$CityID' and substr(代码,5,2)<>'00' and right(代码,6)='000000'";
        $rs		= $db->Execute($sql);
        $rsd_a	= $rs->GetArray();
        foreach((array)$rsd_a as $keyd=>$valued)
        {
            $valued['DistrictName'] = str_replace($CityName,"",$valued['DistrictName']);
            $result[$ProvinceName][$CityName][$keyd] = $valued;
        }
        if(sizeof($rsd_a)==0)
        {
            $cityID	= substr($cityID,0,2);
            $sql	= "select 代码 as DistrictID,行政区 as DistrictName from edu_xingzhengdaima where left(代码,4)='$CityID'  and right(代码,8)='00000000'";
            $rs		= $db->Execute($sql);
            $rsd_a	= $rs->GetArray();
            if(sizeof($rsd_a)>0) {
                $result[$ProvinceName][$CityName][] = $rsd_a[0];
            }
            else {
                $result[$ProvinceName][$CityName][] = ['DistrictID'=>$CityID,'DistrictName'=>$CityName];
            }
        }
    }

}

print_R(json_encode($result));

$sql    = "select * from edu_xingzhengdaima where 代码 like '%0000000000' order by 代码 asc";
$rs     = $db->Execute($sql) or print($sql);
$rsa    = $rs->GetArray();
foreach($rsa as $Line) {

}

?>
