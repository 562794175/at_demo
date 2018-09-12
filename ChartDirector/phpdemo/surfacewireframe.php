<?php
require_once("../lib/phpchartdir.php");

# This script can draw different charts depending on the chartIndex
$chartIndex = (int)($_REQUEST["img"]);

# The x and y coordinates of the grid
$dataX = array(-2, -1, 0, 1, 2);
$dataY = array(-2, -1, 0, 1, 2);

# The values at the grid points. In this example, we will compute the values using the formula z =
# square_root(15 - x * x - y * y).
$dataZ = array_pad(array(), count($dataX) * count($dataY), 0);
for($yIndex = 0; $yIndex < count($dataY); ++$yIndex) {
    $y = $dataY[$yIndex];
    for($xIndex = 0; $xIndex < count($dataX); ++$xIndex) {
        $x = $dataX[$xIndex];
        $dataZ[$yIndex * count($dataX) + $xIndex] = sqrt(15 - $x * $x - $y * $y);
    }
}

# Create a SurfaceChart object of size 380 x 340 pixels, with white (ffffff) background and grey
# (888888) border.
$c = new SurfaceChart(380, 340, 0xffffff, 0x888888);

# Demonstrate various wireframes with and without interpolation
if ($chartIndex == 0) {
    # Original data without interpolation
    $c->addTitle("5 x 5 Data Points\nStandard Shading", "arialbd.ttf", 12);
    $c->setContourColor(0x80ffffff);
} else if ($chartIndex == 1) {
    # Original data, spline interpolated to 40 x 40 for smoothness
    $c->addTitle("5 x 5 Points - Spline Fitted to 40 x 40\nStandard Shading", "arialbd.ttf", 12);
    $c->setContourColor(0x80ffffff);
    $c->setInterpolation(40, 40);
} else if ($chartIndex == 2) {
    # Rectangular wireframe of original data
    $c->addTitle("5 x 5 Data Points\nRectangular Wireframe");
    $c->setShadingMode(RectangularFrame);
} else if ($chartIndex == 3) {
    # Rectangular wireframe of original data spline interpolated to 40 x 40
    $c->addTitle("5 x 5 Points - Spline Fitted to 40 x 40\nRectangular Wireframe");
    $c->setShadingMode(RectangularFrame);
    $c->setInterpolation(40, 40);
} else if ($chartIndex == 4) {
    # Triangular wireframe of original data
    $c->addTitle("5 x 5 Data Points\nTriangular Wireframe");
    $c->setShadingMode(TriangularFrame);
} else {
    # Triangular wireframe of original data spline interpolated to 40 x 40
    $c->addTitle("5 x 5 Points - Spline Fitted to 40 x 40\nTriangular Wireframe");
    $c->setShadingMode(TriangularFrame);
    $c->setInterpolation(40, 40);
}

# Set the center of the plot region at (200, 170), and set width x depth x height to 200 x 200 x 150
# pixels
$c->setPlotRegion(200, 170, 200, 200, 150);

# Set the plot region wall thichness to 5 pixels
$c->setWallThickness(5);

# Set the elevation and rotation angles to 20 and 30 degrees
$c->setViewAngle(20, 30);

# Set the data to use to plot the chart
$c->setData($dataX, $dataY, $dataZ);

# Output the chart
header("Content-type: image/jpeg");
print($c->makeChart2(JPG));
?>
