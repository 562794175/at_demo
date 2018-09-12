<?php
require_once("../lib/phpchartdir.php");

# The data for the chart
$data0 = array(42, 49, NoValue, 38, 64, 56, 29, 41, 44, 57);
$data1 = array(65, 75, 47, 34, 42, 49, 73, NoValue, 90, 69, 66, 78);
$data2 = array(NoValue, NoValue, 25, 28, 38, 20, 22, NoValue, 25, 33, 30, 24);
$labels = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

# Create a XYChart object of size 600 x 360 pixels. Set background color to brushed silver, with a 2
# pixel 3D border. Use rounded corners.
$c = new XYChart(600, 360, brushedSilverColor(), Transparent, 2);
$c->setRoundedFrame();

# Add a title using 18pt Times New Roman Bold Italic font. #Set top/bottom margins to 6 pixels.
$title = $c->addTitle("Product Line Global Revenue", "timesbi.ttf", 18);
$title->setMargin2(0, 0, 6, 6);

# Add a separator line just under the title
$c->addLine(10, $title->getHeight(), $c->getWidth() - 11, $title->getHeight(), LineColor);

# Add a legend box where the top-center is anchored to the horizontal center of the chart, just
# under the title. Use horizontal layout and 10 points Arial Bold font, and transparent background
# and border.
$legendBox = $c->addLegend($c->getWidth() / 2, $title->getHeight(), false, "arialbd.ttf", 10);
$legendBox->setAlignment(TopCenter);
$legendBox->setBackground(Transparent, Transparent);

# Tentatively set the plotarea at (70, 75) and of 460 x 240 pixels in size. Use transparent border
# and black (000000) grid lines
$c->setPlotArea(70, 75, 460, 240, -1, -1, Transparent, 0x000000, -1);

# Set the x axis labels
$c->xAxis->setLabels($labels);

# Show the same scale on the left and right y-axes
$c->syncYAxis();

# Set y-axis tick density to 30 pixels. ChartDirector auto-scaling will use this as the guideline
# when putting ticks on the y-axis.
$c->yAxis->setTickDensity(30);

# Set all axes to transparent
$c->xAxis->setColors(Transparent);
$c->yAxis->setColors(Transparent);
$c->yAxis2->setColors(Transparent);

# Set the x-axis margins to 15 pixels, so that the horizontal grid lines can extend beyond the
# leftmost and rightmost vertical grid lines
$c->xAxis->setMargin(15, 15);

# Set axis label style to 8pt Arial Bold
$c->xAxis->setLabelStyle("arialbd.ttf", 8);
$c->yAxis->setLabelStyle("arialbd.ttf", 8);
$c->yAxis2->setLabelStyle("arialbd.ttf", 8);

# Add axis title using 10pt Arial Bold Italic font
$c->yAxis->setTitle("Revenue in USD millions", "arialbi.ttf", 10);
$c->yAxis2->setTitle("Revenue in USD millions", "arialbi.ttf", 10);

# Add the first line. The missing data will be represented as gaps in the line (the default
# behaviour)
$layer0 = $c->addLineLayer2();
$dataSetObj = $layer0->addDataSet($data0, 0xff0000, "Quantum Computer");
$dataSetObj->setDataSymbol(GlassSphere2Shape, 11);
$layer0->setLineWidth(3);

# Add the second line. The missing data will be represented by using dash lines to bridge the gap
$layer1 = $c->addLineLayer2();
$dataSetObj = $layer1->addDataSet($data1, 0x00ff00, "Atom Synthesizer");
$dataSetObj->setDataSymbol(GlassSphere2Shape, 11);
$layer1->setLineWidth(3);
$layer1->setGapColor($c->dashLineColor(0x00ff00));

# Add the third line. The missing data will be ignored - just join the gap with the original line
# style.
$layer2 = $c->addLineLayer2();
$dataSetObj = $layer2->addDataSet($data2, 0xff6600, "Proton Cannon");
$dataSetObj->setDataSymbol(GlassSphere2Shape, 11);
$layer2->setLineWidth(3);
$layer2->setGapColor(SameAsMainColor);

# layout the legend so we can get the height of the legend box
$c->layoutLegend();

# Adjust the plot area size, such that the bounding box (inclusive of axes) is 15 pixels from the
# left edge, just under the legend box, 16 pixels from the right edge, and 25 pixels from the bottom
# edge.
$c->packPlotArea(15, $legendBox->getTopY() + $legendBox->getHeight(), $c->getWidth() - 16,
    $c->getHeight() - 25);

# Output the chart
header("Content-type: image/jpeg");
print($c->makeChart2(JPG));
?>
