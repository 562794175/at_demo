<?php
require_once("../lib/phpchartdir.php");

# This script can draw different charts depending on the chartIndex
$chartIndex = (int)($_REQUEST["img"]);

# The value to display on the meter
$value = 75.35;

# The background, border and bar colors of the meters
$bgColor = array(0xbbddff, 0xccffcc, 0xffddff, 0xffffaa, 0xffdddd, 0xeeeeee);
$borderColor = array(0x000088, 0x006600, 0x880088, 0xee6600, 0x880000, 0x666666);
$barColor = array(0x0088ff, 0x00cc00, 0x8833dd, 0xff8800, 0xee3333, 0x888888);

# Create a LinearMeter object of size 260 x 80 pixels with a 3-pixel thick rounded frame
$m = new LinearMeter(260, 80, $bgColor[$chartIndex], $borderColor[$chartIndex]);
$m->setRoundedFrame(Transparent);
$m->setThickFrame(3);

# Set the scale region top-left corner at (18, 24), with size of 222 x 20 pixels. The scale labels
# are located on the top (implies horizontal meter)
$m->setMeter(18, 24, 222, 20, Top);

# Set meter scale from 0 - 100, with a tick every 10 units
$m->setScale(0, 100, 10);

if ($chartIndex % 4 == 0) {
    # Add a 5-pixel thick smooth color scale at y = 48 (below the meter scale)
    $smoothColorScale = array(0, 0x0000ff, 25, 0x0088ff, 50, 0x00ff00, 75, 0xdddd00, 100, 0xff0000);
    $m->addColorScale($smoothColorScale, 48, 5);
} else if ($chartIndex % 4 == 1) {
    # Add a 5-pixel thick step color scale at y = 48 (below the meter scale)
    $stepColorScale = array(0, 0x00cc00, 50, 0xffdd00, 80, 0xff3333, 100);
    $m->addColorScale($stepColorScale, 48, 5);
} else if ($chartIndex % 4 == 2) {
    # Add a 5-pixel thick high/low color scale at y = 48 (below the meter scale)
    $highLowColorScale = array(0, 0x0000ff, 40, Transparent, 60, Transparent, 100, 0xff0000);
    $m->addColorScale($highLowColorScale, 48, 5);
} else {
    # Add a 5-pixel thick high only color scale at y = 48 (below the meter scale)
    $highColorScale = array(70, Transparent, 100, 0xff0000);
    $m->addColorScale($highColorScale, 48, 0, 48, 8);
}

# Add a bar from 0 to value with glass effect and 4 pixel rounded corners
$m->addBar(0, $value, $barColor[$chartIndex], glassEffect(NormalGlare, Top), 4);

# Add a label right aligned to (243, 65) using 8pt Arial Bold font
$m->addText(243, 65, "Temperature C", "arialbd.ttf", 8, TextColor, Right);

# Add a text box left aligned to (18, 65). Display the value using white (0xffffff) 8pt Arial Bold
# font on a black (0x000000) background with depressed rounded border.
$t = $m->addText(18, 65, $m->formatValue($value, "2"), "arial.ttf", 8, 0xffffff, Left);
$t->setBackground(0x000000, 0x000000, -1);
$t->setRoundedCorners(3);

# Output the chart
header("Content-type: image/png");
print($m->makeChart2(PNG));
?>
