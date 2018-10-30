<html>
<body style="margin:5px 0px 0px 5px">
<div style="font-size:18pt; font-family:verdana; font-weight:bold">
    Simple Clickable XY Chart Handler
</div>
<hr style="border:solid 1px #000080" />
<div style="font-size:10pt; font-family:verdana; margin-bottom:20px">
    <a href="viewsource.php?file=<?php echo $_SERVER["SCRIPT_NAME"]?>">View Source Code</a>
</div>
<div style="font-size:10pt; font-family:verdana;">
<b>You have clicked on the following chart element :</b><br />
<ul>
    <li>Data Set : <?php echo $_REQUEST["dataSetName"]?></li>
    <li>X Position : <?php echo $_REQUEST["x"]?></li>
    <li>X Label : <?php echo $_REQUEST["xLabel"]?></li>
    <li>Data Value : <?php echo $_REQUEST["value"]?></li>
</ul>
</div>
</body>
</html>
