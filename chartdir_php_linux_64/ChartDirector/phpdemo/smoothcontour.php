<?php
require_once("../lib/phpchartdir.php");

# The x and y coordinates of the grid
$dataX = array(-10, -9, -8, -7, -6, -5, -4, -3, -2, -1, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
$dataY = array(-10, -9, -8, -7, -6, -5, -4, -3, -2, -1, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);

# The values at the grid points. In this example, we will compute the values using the formula z =
# Sin(x / 2) * Sin(y / 2).
$dataZ = array_pad(array(), count($dataX) * count($dataY), 0);
for($yIndex = 0; $yIndex < count($dataY); ++$yIndex) {
    $y = $dataY[$yIndex];
    for($xIndex = 0; $xIndex < count($dataX); ++$xIndex) {
        $x = $dataX[$xIndex];
        $dataZ[$yIndex * count($dataX) + $xIndex] = sin($x / 2.0) * sin($y / 2.0);
    }
}

# Create a XYChart object of size 600 x 500 pixels
$c = new XYChart(600, 500);

# Add a title to the chart using 18 points Times New Roman Bold Italic font
$c->addTitle("Nano Lattice Twister Field Intensity        ", "timesbi.ttf", 18);

# Set the plotarea at (75, 40) and of size 400 x 400 pixels. Use semi-transparent black (80000000)
# dotted lines for both horizontal and vertical grid lines
$c->setPlotArea(75, 40, 400, 400, -1, -1, -1, $c->dashLineColor(0x80000000, DotLine), -1);

# Set x-axis and y-axis title using 12 points Arial Bold Italic font
$c->xAxis->setTitle("Lattice X Direction (nm)", "arialbi.ttf", 12);
$c->yAxis->setTitle("Lattice Y Direction (nm)", "arialbi.ttf", 12);

# Set x-axis and y-axis labels to use Arial Bold font
$c->xAxis->setLabelStyle("arialbd.ttf");
$c->yAxis->setLabelStyle("arialbd.ttf");

# When auto-scaling, use tick spacing of 40 pixels as a guideline
$c->yAxis->setTickDensity(40);
$c->xAxis->setTickDensity(40);

# Add a contour layer using the given data
$layer = $c->addContourLayer($dataX, $dataY, $dataZ);

# Set the contour color to transparent
$layer->setContourColor(Transparent);

# Move the grid lines in front of the contour layer
$plotAreaObj = $c->getPlotArea();
$plotAreaObj->moveGridBefore($layer);

# Add a color axis (the legend) in which the left center point is anchored at (495, 240). Set the
# length to 370 pixels and the labels on the right side.
$cAxis = $layer->setColorAxis(495, 240, Left, 370, Right);

# Add a bounding box to the color axis using light grey (eeeeee) as the background and dark grey
# (444444) as the border.
$cAxis->setBoundingBox(0xeeeeee, 0x444444);

# Add a title to the color axis using 12 points Arial Bold Italic font
$cAxis->setTitle("Twist Pressure (Twist-Volt)", "arialbi.ttf", 12);

# Set color axis labels to use Arial Bold font
$cAxis->setLabelStyle("arialbd.ttf");

# Use smooth gradient coloring
$cAxis->setColorGradient(true);

# Output the chart
header("Content-type: image/jpeg");
print($c->makeChart2(JPG));
?>
