<?php
require_once("../lib/phpchartdir.php");

# This script can draw different charts depending on the chartIndex
$chartIndex = (int)($_REQUEST["img"]);

# The data for the pie chart
$data = array(18, 30, 20, 15);

# The colors to use for the sectors
$colors = array(0x66aaee, 0xeebb22, 0xbbbbbb, 0x8844ff);

# Create a PieChart object of size 200 x 200 pixels. Use a vertical gradient color from blue
# (0000cc) to deep blue (000044) as background. Use rounded corners of 16 pixels radius.
$c = new PieChart(200, 200);
$c->setBackground($c->linearGradientColor(0, 0, 0, $c->getHeight(), 0x0000cc, 0x000044));
$c->setRoundedFrame(0xffffff, 16);

# Set donut center at (100, 100), and outer/inner radii as 80/40 pixels
$c->setDonutSize(100, 100, 80, 40);

# Set the pie data
$c->setData($data);

# Set the sector colors
$c->setColors2(DataColor, $colors);

# Draw the pie in 3D with a pie thickness of 20 pixels
$c->set3D(20);

# Demonstrates various shading modes
if ($chartIndex == 0) {
    $c->addTitle("Default Shading", "bold", 12, 0xffffff);
} else if ($chartIndex == 1) {
    $c->addTitle("Flat Gradient", "bold", 12, 0xffffff);
    $c->setSectorStyle(FlatShading);
} else if ($chartIndex == 2) {
    $c->addTitle("Local Gradient", "bold", 12, 0xffffff);
    $c->setSectorStyle(LocalGradientShading);
} else if ($chartIndex == 3) {
    $c->addTitle("Global Gradient", "bold", 12, 0xffffff);
    $c->setSectorStyle(GlobalGradientShading);
} else if ($chartIndex == 4) {
    $c->addTitle("Concave Shading", "bold", 12, 0xffffff);
    $c->setSectorStyle(ConcaveShading);
} else if ($chartIndex == 5) {
    $c->addTitle("Rounded Edge", "bold", 12, 0xffffff);
    $c->setSectorStyle(RoundedEdgeShading);
} else if ($chartIndex == 6) {
    $c->addTitle("Radial Gradient", "bold", 12, 0xffffff);
    $c->setSectorStyle(RadialShading);
} else if ($chartIndex == 7) {
    $c->addTitle("Ring Shading", "bold", 12, 0xffffff);
    $c->setSectorStyle(RingShading);
}

# Disable the sector labels by setting the color to Transparent
$c->setLabelStyle("", 8, Transparent);

# Output the chart
header("Content-type: image/png");
print($c->makeChart2(PNG));
?>
