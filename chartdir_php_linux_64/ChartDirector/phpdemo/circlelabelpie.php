<?php
require_once("../lib/phpchartdir.php");

# This script can draw different charts depending on the chartIndex
$chartIndex = (int)($_REQUEST["img"]);

# The data for the pie chart
$data = array(42, 18, 8);

# The labels for the pie chart
$labels = array("Agree", "Disagree", "Not Sure");

# The colors to use for the sectors
$colors = array(0x66ff66, 0xff6666, 0xffff00);

# Create a PieChart object of size 300 x 300 pixels. Set the background to a gradient color from
# blue (aaccff) to sky blue (ffffff), with a grey (888888) border. Use rounded corners and soft drop
# shadow.
$c = new PieChart(300, 300);
$c->setBackground($c->linearGradientColor(0, 0, 0, $c->getHeight() / 2, 0xaaccff, 0xffffff),
    0x888888);
$c->setRoundedFrame();
$c->setDropShadow();

if ($chartIndex == 0) {
#============================================================
#    Draw a pie chart where the label is on top of the pie
#============================================================

    # Set the center of the pie at (150, 150) and the radius to 120 pixels
    $c->setPieSize(150, 150, 120);

    # Set the label position to -40 pixels from the perimeter of the pie (-ve means label is inside
    # the pie)
    $c->setLabelPos(-40);

} else {
#============================================================
#    Draw a pie chart where the label is outside the pie
#============================================================

    # Set the center of the pie at (150, 150) and the radius to 80 pixels
    $c->setPieSize(150, 150, 80);

    # Set the sector label position to be 20 pixels from the pie. Use a join line to connect the
    # labels to the sectors.
    $c->setLabelPos(20, LineColor);

}

# Set the pie data and the pie labels
$c->setData($data, $labels);

# Set the sector colors
$c->setColors2(DataColor, $colors);

# Use local gradient shading, with a 1 pixel semi-transparent black (bb000000) border
$c->setSectorStyle(LocalGradientShading, 0xbb000000, 1);

# Output the chart
header("Content-type: image/png");
print($c->makeChart2(PNG));
?>
