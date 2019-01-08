<?php 

require_once 'operation_2.php';
require_once 'operation_3.php';
require_once 'operation_4.php';

$Oral=getOral();
$Written=getWritten();
$Recursive=getRecursive();
main($Oral,$Written,$Recursive);


function getOral() {
    $items=getTwoMulti(50);
    $items.=getThreeOperation(5);
    $td = "<style type='text/css'>#to ul{list-style:none;width:750px;overflow:hidden;} #to li{width:150px;float:left}</style>";
    $td .= "<td><ul id='to'>".$items."<ul></td>";
    return $td;
}

function getWritten() {
    $count=mt_rand(1,5);
    $items=getTwoMulti($count);
     for($i=0;$i<5-$count;$i++) {
        $li=getTwoDivision();
        $items.=$li;
    }
    $td = "<style type='text/css'>#wt ul{list-style:none;width:750px;overflow:hidden;} #wt li{width:150px;float:left;height:100px}</style> ";
    $td .= "<td><ul id='wt'>".$items."</ul></td>";
    return $td;
}

function getRecursive() {
    $ct = getFourOperation(12);
    $td = "<style type='text/css'>#fo ul{list-style:none;width:800px;overflow:hidden;} #fo li{width:200px;float:left;height:100px}</style>";
    $td .= "<td><ul id='fo'>".$ct."</ul></td>";
    return $td;
}

function main($Oral,$Written,$Recursive) {
    
    $content= "<table width=100%>";
    $content.= "<tr><th align=left>1.口算</th></tr>";
    $content.= "<tr>".$Oral."</tr>";
    $content.= "<tr><th align=left>2.笔算</th></tr>";
    $content.= "<tr>".$Written."</tr>";
    $content.= "<tr><th align=left>3.递等式计算</th></tr>";
    $content.= "<tr>".$Recursive."</tr>";
    $content.= "</table>";
    echo $content;
}