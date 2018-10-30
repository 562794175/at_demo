<?php
require_once("../lib/phpchartdir.php");

# This script can draw different charts depending on the chartIndex
$chartIndex = (int)($_REQUEST["img"]);

# The data for the pie chart
$data = array(25, 18, 15, 12, 8, 30, 35);

# The labels for the pie chart
$labels = array("Labor", "Licenses", "Taxes", "Legal", "Insurance", "Facilities", "Production");

# Create a PieChart object of size 280 x 240 pixels
$c = new PieChart(280, 240);

# Set the center of the pie at (140, 130) and the radius to 80 pixels
$c->setPieSize(140, 130, 80);

# Add a title to the pie to show the start angle and direction
if ($chartIndex == 0) {
    $c->addTitle("Start Angle = 0 degrees\nDirection = Clockwise");
} else {
    $c->addTitle("Start Angle = 90 degrees\nDirection = AntiClockwise");
    $c->setStartAngle(90, false);
}

# Draw the pie in 3D
$c->set3D();

# Set the pie data and the pie labels
$c->setData($data, $labels);

# Explode the 1st sector (index = 0)
$c->setExplode(0);

# Output the chart
header("Content-type: image/png");
print($c->makeChart2(PNG));
?>
