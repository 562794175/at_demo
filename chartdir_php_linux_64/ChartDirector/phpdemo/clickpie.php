<?php
require_once("../lib/phpchartdir.php");

# Get the selected year and month
$selectedYear = (int)($_REQUEST["year"]);
$selectedMonth = (int)($_REQUEST["x"]) + 1;

#
# In real life, the data may come from a database based on selectedYear. In this example, we just
# use a random number generator.
#
$seed = ($selectedYear - 1992) * (100 + 3 * $selectedMonth);
$rantable = new RanTable($seed, 1, 4);
$rantable->setCol(0, $seed * 0.003, $seed * 0.017);

$data = $rantable->getCol(0);

# The labels for the pie chart
$labels = array("Services", "Hardware", "Software", "Others");

# Create a PieChart object of size 600 x 240 pixels
$c = new PieChart(600, 280);

# Set the center of the pie at (300, 140) and the radius to 120 pixels
$c->setPieSize(300, 140, 120);

# Add a title to the pie chart using 18pt Times Bold Italic font
$c->addTitle("Revenue Breakdown for $selectedMonth/$selectedYear", "timesbi.ttf", 18);

# Draw the pie in 3D with 20 pixels 3D depth
$c->set3D(20);

# Set label format to display sector label, value and percentage in two lines
$c->setLabelFormat("{label}<*br*>\${value|2}M ({percent}%)");

# Set label style to 10pt Arial Bold Italic font. Set background color to the same as the sector
# color, with reduced-glare glass effect and rounded corners.
$t = $c->setLabelStyle("arialbi.ttf", 10);
$t->setBackground(SameAsMainColor, Transparent, glassEffect(ReducedGlare));
$t->setRoundedCorners();

# Use side label layout method
$c->setLabelLayout(SideLayout);

# Set the pie data and the pie labels
$c->setData($data, $labels);

# Create the image and save it in a temporary location
$chart1URL = $c->makeSession("chart1");

# Create an image map for the chart
$imageMap = $c->getHTMLImageMap("piestub.php", "", "title='{label}:US\$ {value|2}M'");
?>
<html>
<body style="margin:5px 0px 0px 5px">
<div style="font-size:18pt; font-family:verdana; font-weight:bold">
    Simple Clickable Pie Chart
</div>
<hr style="border:solid 1px #000080" />
<div style="font-size:10pt; font-family:verdana; margin-bottom:20">
    <a href="viewsource.php?file=<?php echo $_SERVER["SCRIPT_NAME"]?>">View Source Code</a>
</div>
<img src="getchart.php?<?php echo $chart1URL?>" border="0" usemap="#map1">
<map name="map1">
<?php echo $imageMap?>
</map>
</body>
</html>
