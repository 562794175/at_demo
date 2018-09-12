<?php
require_once("../lib/phpchartdir.php");

#
# Data to draw the chart. In this demo, the data buffer will be filled by a random data generator.
# In real life, the data is probably stored in a buffer (eg. a database table, a text file, or some
# global memory) and updated by other means.
#

# We use a data buffer to emulate the last 240 samples.
$sampleSize = 240;
$dataSeries1 = array_pad(array(), $sampleSize, 0);
$dataSeries2 = array_pad(array(), $sampleSize, 0);
$dataSeries3 = array_pad(array(), $sampleSize, 0);
$timeStamps = array_pad(array(), $sampleSize, 0);

# Our pseudo random number generator
$firstDate = chartTime2(time()) - count($timeStamps);
for($i = 0; $i < count($timeStamps); ++$i) {
    $p = $firstDate + $i;
    $timeStamps[$i] = $p;
    $dataSeries1[$i] = cos($p * 2.1) * 10 + 1 / (cos($p) * cos($p) + 0.01) + 20;
    $dataSeries2[$i] = 100 * sin($p / 27.7) * sin($p / 10.1) + 150;
    $dataSeries3[$i] = 100 * cos($p / 6.7) * cos($p / 11.9) + 150;
}

# Create an XYChart object 600 x 320 pixels in size
$c = new XYChart(600, 320);

# Set the plotarea at (55, 60) and of size 520 x 235 pixels with transparent background and border.
# Enable both horizontal and vertical grids by setting their colors to grey (cccccc). Set clipping
# mode to clip the data lines to the plot area.
$c->setPlotArea(55, 60, 520, 235, -1, -1, Transparent, 0xcccccc, 0xcccccc);
$c->setClipping();

# Add a title to the chart using dark grey (0x333333) 20pt Arial Bold font
$c->addTitle("Realtime Chart with Snapshot", "arialbd.ttf", 20, 0x333333);

# Add a legend box at the top of the plot area using horizontal layout. Use 10pt Arial Bold font,
# transparent background and border, and line style legend icon.
$b = $c->addLegend(55, 30, false, "arialbd.ttf", 10);
$b->setBackground(Transparent, Transparent);
$b->setLineStyleKey();

# Set the x and y axis stems to transparent and the label font to 10pt Arial
$c->xAxis->setColors(Transparent);
$c->yAxis->setColors(Transparent);
$c->xAxis->setLabelStyle("arial.ttf", 10);
$c->yAxis->setLabelStyle("arial.ttf", 10);

# Add y-axis title using 12pt Arial font
$c->yAxis->setTitle("Y-Axis Title Placeholder", "arial.ttf", 12);

# For the automatic x and y axis labels, set the minimum spacing to 75 and 30 pixels.
$c->xAxis->setTickDensity(75);
$c->yAxis->setTickDensity(30);

# Set the x-axis label format
$c->xAxis->setLabelFormat("{value|hh:nn:ss}");

# Create a line layer to plot the lines
$layer = $c->addLineLayer2();

# The x-coordinates are the timeStamps.
$layer->setXData($timeStamps);

# The 3 data series are used to draw 3 lines. Here we put the latest data values as part of the data
# set name, so you can see them updated in the legend box.
$layer->addDataSet($dataSeries1, 0xff0000, $c->formatValue($dataSeries1[count($dataSeries1) - 1],
    "Alpha: {value|2}"));
$layer->addDataSet($dataSeries2, 0x00cc00, $c->formatValue($dataSeries2[count($dataSeries2) - 1],
    "Beta: {value|2}"));
$layer->addDataSet($dataSeries3, 0x0000ff, $c->formatValue($dataSeries3[count($dataSeries3) - 1],
    "Gamma: {value|2}"));

# Check if is download request
$downloadFormat = isset($_REQUEST["download"]) ? $_REQUEST["download"] : null;
if (!(($downloadFormat == null || $downloadFormat == ""))) {
    $fname = sprintf("demo_%s", $c->formatValue($timeStamps[count($timeStamps) - 1],
        "yyyymmddhhnnss"));
    if ($downloadFormat == "pdf") {
        # Output in PDF and stream as attachment
        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=\"$fname.pdf\"");
        print($c->makeChart2(PDF));
        exit();
    } else {
        # Output in PNG and stream as attachment
        header("Content-type: image/png");
        header("Content-Disposition: attachment; filename=\"$fname.png\"");
        print($c->makeChart2(PNG));
        exit();
    }
}

# Output the chart
header("Content-type: image/png");
print($c->makeChart2(PNG));
?>
