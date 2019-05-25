<?php
ini_set('date.timezone','Asia/Shanghai');
echo "日期：<input type='text' style='width:80px' value='".date("Y-m-d")."' />&ensp;";
echo "周期：<select>";
echo "<option>1H</option>";
echo "<option>4H</option>";
echo "<option>1D</option>";
echo "</select>&ensp;";
echo "时间：<input type='text' style='width:50px' value='".date("H:i")."' />&ensp;";
echo "易象：";
echo "<hr>";

