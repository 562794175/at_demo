<?php
require_once("../lib/phpchartdir.php");

# This script can draw different charts depending on the chartIndex
$chartIndex = (int)($_REQUEST["img"]);

# The value to display on the meter
$value = 72.55;

# Create an AngularMeter object of size 300 x 180 pixels with transparent background
$m = new AngularMeter(300, 180, Transparent);

# Set the default text and line colors to very dark grey (0x222222)
$m->setColor(TextColor, 0x222222);
$m->setColor(LineColor, 0x222222);

# Center at (150, 150), scale radius = 128 pixels, scale angle -90 to +90 degrees
$m->setMeter(150, 150, 128, -90, 90);

# Gradient color for the border to make it silver-like
$ringGradient = array(1, 0x999999, 0.5, 0xdddddd, 0, 0xf8f8f8, -0.5, 0xdddddd, -1, 0x999999);

# Background gradient color from white (0xffffff) at the center to light grey (0xdddddd) at the
# border
$bgGradient = array(0, 0xffffff, 0.75, 0xeeeeee, 1, 0xdddddd);

# Add a scale background of 148 pixels radius using the gradient background, with a 10 pixel thick
# silver border
$m->addScaleBackground(148, $m->relativeRadialGradient($bgGradient, 148), 10,
    $m->relativeLinearGradient($ringGradient, 45, 148));

# Add a 1 pixel light grey (0xbbbbbb) line at the inner edge of the thick silver border (radius =
# 138) to enhance its contrast with the background gradient
$m->addScaleBackground(138, Transparent, 1, 0xbbbbbb);

# Meter scale is 0 - 100, with major tick every 20 units, minor tick every 10 units, and micro tick
# every 5 units
$m->setScale(0, 100, 20, 10, 5);

# Set the scale label style to 15pt Arial Italic. Set the major/minor/micro tick lengths to 16/16/10
# pixels pointing inwards, and their widths to 2/1/1 pixels.
$m->setLabelStyle("ariali.ttf", 16);
$m->setTickLength(-16, -16, -10);
$m->setLineWidth(0, 2, 1, 1);

# Demostrate different types of color scales and putting them at different positions
$smoothColorScale = array(0, 0x3333ff, 25, 0x0088ff, 50, 0x00ff00, 75, 0xdddd00, 100, 0xff0000);
$stepColorScale = array(0, 0x00cc00, 60, 0xffdd00, 80, 0xee0000, 100);
$highLowColorScale = array(0, 0x00ff00, 70, Transparent, 100, 0xff0000);

if ($chartIndex == 0) {
    # Add the smooth color scale at the default position
    $m->addColorScale($smoothColorScale);
} else if ($chartIndex == 1) {
    # Add the smooth color scale starting at radius 128 with zero width and ending at radius 128
    # with 16 pixels inner width
    $m->addColorScale($smoothColorScale, 128, 0, 128, -16);
} else if ($chartIndex == 2) {
    # Add the smooth color scale starting at radius 70 with zero width and ending at radius 60 with
    # 20 pixels outer width
    $m->addColorScale($smoothColorScale, 70, 0, 60, 20);
} else if ($chartIndex == 3) {
    # Add the high/low color scale at the default position
    $m->addColorScale($highLowColorScale);
} else if ($chartIndex == 4) {
    # Add the step color scale at the default position
    $m->addColorScale($stepColorScale);
} else {
    # Add the smooth color scale at radius 60 with 15 pixels outer width
    $m->addColorScale($smoothColorScale, 60, 15);
}

# Add a text label centered at (150, 125) with 15pt Arial Italic font
$m->addText(150, 125, "CPU", "ariali.ttf", 15, TextColor, BottomCenter);

# Add a red (0xff0000) pointer at the specified value
$m->addPointer2($value, 0xff0000);

# Output the chart
header("Content-type: image/png");
print($m->makeChart2(PNG));
?>
