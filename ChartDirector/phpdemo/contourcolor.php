<?php
require_once("../lib/phpchartdir.php");

# This script can draw different charts depending on the chartIndex
$chartIndex = (int)($_REQUEST["img"]);

# The x and y coordinates of the grid
$dataX = array(-4, -3, -2, -1, 0, 1, 2, 3, 4);
$dataY = array(-4, -3, -2, -1, 0, 1, 2, 3, 4);

# Use random numbers for the z values on the XY grid
$r = new RanSeries(99);
$dataZ = $r->get2DSeries(count($dataX), count($dataY), -0.9, 0.9);

# Create a XYChart object of size 420 x 360 pixels
$c = new XYChart(420, 360);

# Set the plotarea at (30, 25) and of size 300 x 300 pixels. Use semi-transparent grey (0xdd000000)
# horizontal and vertical grid lines
$c->setPlotArea(30, 25, 300, 300, -1, -1, -1, 0xdd000000, -1);

# Set the x-axis and y-axis scale
$c->xAxis->setLinearScale(-4, 4, 1);
$c->yAxis->setLinearScale(-4, 4, 1);

# Add a contour layer using the given data
$layer = $c->addContourLayer($dataX, $dataY, $dataZ);

# Move the grid lines in front of the contour layer
$plotAreaObj = $c->getPlotArea();
$plotAreaObj->moveGridBefore($layer);

# Add a color axis (the legend) in which the top left corner is anchored at (350, 25). Set the
# length to 400 300 and the labels on the right side.
$cAxis = $layer->setColorAxis(350, 25, TopLeft, 300, Right);

if ($chartIndex == 1) {
    # Speicify a color gradient as a list of colors, and use it in the color axis.
    $colorGradient = array(0x0044cc, 0xffffff, 0x00aa00);
    $cAxis->setColorGradient(false, $colorGradient);
} else if ($chartIndex == 2) {
    # Specify the color scale to use in the color axis
    $colorScale = array(-1.0, 0x1a9850, -0.75, 0x66bd63, -0.5, 0xa6d96a, -0.25, 0xd9ef8b, 0,
        0xfee08b, 0.25, 0xfdae61, 0.5, 0xf46d43, 0.75, 0xd73027, 1);
    $cAxis->setColorScale($colorScale);
} else if ($chartIndex == 3) {
    # Specify the color scale to use in the color axis. Also specify an underflow color 0x66ccff
    # (blue) for regions that fall below the lower axis limit.
    $colorScale = array(0, 0xffff99, 0.2, 0x80cdc1, 0.4, 0x35978f, 0.6, 0x01665e, 0.8, 0x003c30, 1);
    $cAxis->setColorScale($colorScale, 0x66ccff);
}

# Output the chart
header("Content-type: image/png");
print($c->makeChart2(PNG));
?>
