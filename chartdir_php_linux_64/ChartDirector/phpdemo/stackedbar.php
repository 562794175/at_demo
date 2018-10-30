<?php
require_once("../lib/phpchartdir.php");

# The data for the bar chart
$data0 = array(100, 115, 165, 107, 67);
$data1 = array(85, 106, 129, 161, 123);
$data2 = array(67, 87, 86, 167, 157);

# The labels for the bar chart
$labels = array("Mon", "Tue", "Wed", "Thu", "Fri");

# Create a XYChart object of size 600 x 360 pixels
$c = new XYChart(600, 360);

# Set the plotarea at (70, 20) and of size 400 x 300 pixels, with transparent background and border
# and light grey (0xcccccc) horizontal grid lines
$c->setPlotArea(70, 20, 400, 300, Transparent, -1, Transparent, 0xcccccc);

# Add a legend box at (480, 20) using vertical layout and 12pt Arial font. Set background and border
# to transparent and key icon border to the same as the fill color.
$b = $c->addLegend(480, 20, true, "arial.ttf", 12);
$b->setBackground(Transparent, Transparent);
$b->setKeyBorder(SameAsMainColor);

# Set the x and y axis stems to transparent and the label font to 12pt Arial
$c->xAxis->setColors(Transparent);
$c->yAxis->setColors(Transparent);
$c->xAxis->setLabelStyle("arial.ttf", 12);
$c->yAxis->setLabelStyle("arial.ttf", 12);

# Add a stacked bar layer
$layer = $c->addBarLayer2(Stack);

# Add the three data sets to the bar layer
$layer->addDataSet($data0, 0xaaccee, "Server # 1");
$layer->addDataSet($data1, 0xbbdd88, "Server # 2");
$layer->addDataSet($data2, 0xeeaa66, "Server # 3");

# Set the bar border to transparent
$layer->setBorderColor(Transparent);

# Enable labelling for the entire bar and use 12pt Arial font
$layer->setAggregateLabelStyle("arial.ttf", 12);

# Enable labelling for the bar segments and use 12pt Arial font with center alignment
$textBoxObj = $layer->setDataLabelStyle("arial.ttf", 10);
$textBoxObj->setAlignment(Center);

# For a vertical stacked bar with positive data, the first data set is at the bottom. For the legend
# box, by default, the first entry at the top. We can reverse the legend order to make the legend
# box consistent with the stacked bar.
$layer->setLegendOrder(ReverseLegend);

# Set the labels on the x axis.
$c->xAxis->setLabels($labels);

# For the automatic y-axis labels, set the minimum spacing to 40 pixels.
$c->yAxis->setTickDensity(40);

# Add a title to the y axis using dark grey (0x555555) 14pt Arial Bold font
$c->yAxis->setTitle("Y-Axis Title Placeholder", "arialbd.ttf", 14, 0x555555);

# Output the chart
header("Content-type: image/png");
print($c->makeChart2(PNG));
?>
