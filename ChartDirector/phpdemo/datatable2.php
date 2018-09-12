<?php
require_once("../lib/phpchartdir.php");

# The data for the line chart
$data0 = array(410, 420, 500, 590);
$data1 = array(500, 370, 680, 850);
$labels = array("Q1", "Q2", "Q3", "Q4");

# Create a XYChart object of size 600 x 400 pixels
$c = new XYChart(600, 400);

# Add a title to the chart using 18pt Times Bold Italic font
$title = $c->addTitle("Product Line Global Revenue", "timesbi.ttf", 18);

# Tentatively set the plotarea at (50, 55) and of (chart_width - 100) x (chart_height - 150) pixels
# in size. Use a vertical gradient color from sky blue (aaccff) t0 light blue (f9f9ff) as
# background. Set both horizontal and vertical grid lines to dotted semi-transprent black
# (aa000000).
$plotArea = $c->setPlotArea(50, 55, $c->getWidth() - 100, $c->getHeight() - 150,
    $c->linearGradientColor(0, 55, 0, 55 + $c->getHeight() - 150, 0xaaccff, 0xf9fcff), -1, -1,
    $c->dashLineColor(0xaa000000, DotLine), -1);

# Set y-axis title using 12 points Arial Bold Italic font, and set its position 10 pixels from the
# axis.
$c->yAxis->setTitle("Revenue (USD millions)", "arialbi.ttf", 12);
$c->yAxis->setTitlePos(Left, 10);

# Set y-axis label style to 10 points Arial Bold and axis color to transparent
$c->yAxis->setLabelStyle("arialbd.ttf", 10);
$c->yAxis->setColors(Transparent);

# Set y-axis tick density to 30 pixels. ChartDirector auto-scaling will use this as the guideline
# when putting ticks on the y-axis.
$c->yAxis->setTickDensity(30);

# Add a bar layer to the chart with side layout
$layer = $c->addBarLayer2(Side);

# Add two data sets to the bar layer
$layer->addDataSet($data0, 0xff6600, "FY 2007");
$layer->addDataSet($data1, 0x0088ff, "FY 2008");

# Use soft lighting effect with light direction from the left
$layer->setBorderColor(Transparent, softLighting(Left));

# Set the x axis labels
$c->xAxis->setLabels($labels);

# Convert the labels on the x-axis to a CDMLTable
$table = $c->xAxis->makeLabelTable();

# Set the default left/right margins to 5 pixels and top/bottom margins to 3 pixels. Set the default
# font size to 10 points
$cellStyle = $table->getStyle();
$cellStyle->setMargin2(5, 5, 4, 3);
$cellStyle->setFontSize(10);

# Set the first row to use Arial Bold font, with a light grey (eeeeee) background.
$firstRowStyle = $table->getRowStyle(0);
$firstRowStyle->setFontStyle("arialbd.ttf");
$firstRowStyle->setBackground(0xeeeeee, LineColor);

#
# We can add more information to the table. In this sample code, we add the data series and the
# legend icons to the table.
#

# Add 3 more rows to the table. Set the background of the 2nd row to light grey (eeeeee).
$table->appendRow();
$appendRowObj = $table->appendRow();
$appendRowObj->setBackground(0xeeeeee, LineColor);
$table->appendRow();

# Put the values of the 2 data series in the first 2 rows. Put the percentage differences in the 3rd
# row.
for($i = 0; $i < count($data0); ++$i) {
    $table->setText($i, 1, $data0[$i]);
    $table->setText($i, 2, $data1[$i]);

    $percentageDiff = 100.0 * ($data1[$i] - $data0[$i]) / $data0[$i];

    # Use red or green color depending on whether the difference is positive or negative
    $formatString = "<*color=008800*>+{value|1}%";
    if ($percentageDiff < 0) {
        $formatString = "<*color=cc0000*>{value|1}%";
    }
    $table->setText($i, 3, $c->formatValue($percentageDiff, $formatString));
}

# Insert a column on the left for the legend icons using Arial Bold font.
$insertColObj = $table->insertCol(0);
$insertColObj->setFontStyle("arialbd.ttf");

# The top cell is set to transparent, so it is invisible
$cellObj = $table->getCell(0, 0);
$cellObj->setBackground(Transparent, Transparent);

# The next 2 cells are set to the legend icons and names of the 2 data series
$table->setText(0, 1, sprintf("%s  FY 2007", $layer->getLegendIcon(0)));
$table->setText(0, 2, sprintf("%s  FY 2008", $layer->getLegendIcon(1)));

# The last cell is set to "Change"
$table->setText(0, 3, "Change");

# Append a column on the right for the total values.
$table->appendCol();

# Put "Total" in the top cell as the heading of this column
$table->setText($table->getColCount() - 1, 0, "Total");

# The next two cells are the total of the data series
$tmpArrayMath1 = new ArrayMath($data0);
$total0 = $tmpArrayMath1->sum();
$tmpArrayMath1 = new ArrayMath($data1);
$total1 = $tmpArrayMath1->sum();
$table->setText($table->getColCount() - 1, 1, $total0);
$table->setText($table->getColCount() - 1, 2, $total1);

# The last cell is the percentage differences of the total
$totalPercentageDiff = ($total1 - $total0) / $total0 * 100;

# Use red or green color depending on whether the difference is positive or negative
$totalFormatString = "<*color=008800*>+{value|1}%";
if ($totalPercentageDiff < 0) {
    $totalFormatString = "<*color=cc0000*>{value|1}%";
}
$table->setText($table->getColCount() - 1, 3, $c->formatValue($totalPercentageDiff,
    $totalFormatString));

#
# We now demonstrate how to adjust the plot area positions, to allow space for the newly inserted
# left and right columns in the table.
#

# We layout the axis first in order to get the axis metrics (including table metrics)
$c->layoutAxes();

# If the first column is wider than the left y-axis, we need to reserve for some left margin to
# ensure the first column stays within the chart.
$leftMargin = 0;
if ($table->getColWidth(0) > $c->yAxis->getThickness()) {
    $leftMargin = $table->getColWidth(0) - $c->yAxis->getThickness();
}

# Similarly, we need to reserve some right margin for the last column
$rightMargin = $table->getColWidth($table->getColCount() - 1);

# Adjust the plot area size, such that the bounding box (inclusive of axes) using the given left and
# right margin, plus 2 more pixels. Put the plot area 10 pixels below the title and use 2 pixels as
# the bottom margin. from the left, right and bottom edge, and is just under the legend box.
$c->packPlotArea($leftMargin + 2, $title->getHeight() + 10, $c->getWidth() - 3 - $rightMargin,
    $c->getHeight() - 3);

# After determining the exact plot area position, we may adjust title position so that it is
# centered relative to the plot area (instead of the chart)
$title->setPos($plotArea->getLeftX() + ($plotArea->getWidth() - $title->getWidth()) / 2,
    $title->getTopY());

# Output the chart
header("Content-type: image/png");
print($c->makeChart2(PNG));
?>
