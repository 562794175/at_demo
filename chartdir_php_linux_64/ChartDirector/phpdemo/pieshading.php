<?php
require_once("../lib/phpchartdir.php");

# This script can draw different charts depending on the chartIndex
$chartIndex = (int)($_REQUEST["img"]);

# The data for the pie chart
$data = array(18, 30, 20, 15);

# The colors to use for the sectors
$colors = array(0x66aaee, 0xeebb22, 0xbbbbbb, 0x8844ff);

# Create a PieChart object of size 200 x 220 pixels. Use a vertical gradient color from blue
# (0000cc) to deep blue (000044) as background. Use rounded corners of 16 pixels radius.
$c = new PieChart(200, 220);
$c->setBackground($c->linearGradientColor(0, 0, 0, $c->getHeight(), 0x0000cc, 0x000044));
$c->setRoundedFrame(0xffffff, 16);

# Set the center of the pie at (100, 120) and the radius to 80 pixels
$c->setPieSize(100, 120, 80);

# Set the pie data
$c->setData($data);

# Set the sector colors
$c->setColors2(DataColor, $colors);

# Demonstrates various shading modes
if ($chartIndex == 0) {
    $c->addTitle("Default Shading", "bold", 12, 0xffffff);
} else if ($chartIndex == 1) {
    $c->addTitle("Local Gradient", "bold", 12, 0xffffff);
    $c->setSectorStyle(LocalGradientShading);
} else if ($chartIndex == 2) {
    $c->addTitle("Global Gradient", "bold", 12, 0xffffff);
    $c->setSectorStyle(GlobalGradientShading);
} else if ($chartIndex == 3) {
    $c->addTitle("Concave Shading", "bold", 12, 0xffffff);
    $c->setSectorStyle(ConcaveShading);
} else if ($chartIndex == 4) {
    $c->addTitle("Rounded Edge", "bold", 12, 0xffffff);
    $c->setSectorStyle(RoundedEdgeShading);
} else if ($chartIndex == 5) {
    $c->addTitle("Radial Gradient", "bold", 12, 0xffffff);
    $c->setSectorStyle(RadialShading);
}

# Disable the sector labels by setting the color to Transparent
$c->setLabelStyle("", 8, Transparent);

# Output the chart
header("Content-type: image/png");
print($c->makeChart2(PNG));
?>
