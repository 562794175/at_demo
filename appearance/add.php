<?php
require_once("function.php");
$sDate=date("Y-m-d");
$sTime=date("H:i");
$aPeriod=getPeriodMap();
?>
日期：<input type='text' style='width:80px' value='<?php echo $sDate; ?>' />&ensp;
周期：<select><option>1H</option><option>4H</option><option>1D</option></select>&ensp;
时间：<input type='text' style='width:50px' value='11:50' />&ensp;
<button type='button'>Click Me!</button>
<hr><img src='58edd9176cd25.jpg'><hr>
