<?php
require_once("../lib/phpchartdir.php");

# The value to display on the meter
$value = 74.35;

# Create an LinearMeter object of size 70 x 240 pixels with a very light grey (0xeeeeee) background,
# and a rounded 3-pixel thick light grey (0xcccccc) border
$m = new LinearMeter(70, 240, 0xeeeeee, 0xcccccc);
$m->setRoundedFrame(Transparent);
$m->setThickFrame(3);

# Set the scale region top-left corner at (28, 18), with size of 20 x 205 pixels. The scale labels
# are located on the left (default - implies vertical meter).
$m->setMeter(28, 18, 20, 205);

# Set meter scale from 0 - 100, with a tick every 10 units
$m->setScale(0, 100, 10);

# Add a smooth color scale to the meter
$smoothColorScale = array(0, 0x6666ff, 25, 0x00bbbb, 50, 0x00ff00, 75, 0xffff00, 100, 0xff0000);
$m->addColorScale($smoothColorScale);

# Add a blue (0x0000cc) pointer at the specified value
$m->addPointer($value, 0x0000cc);

# Output the chart
header("Content-type: image/png");
print($m->makeChart2(PNG));
?>
