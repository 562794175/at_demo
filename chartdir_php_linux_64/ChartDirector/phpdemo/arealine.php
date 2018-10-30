<?php
require_once("../lib/phpchartdir.php");

# In this example, we simply use random data for the 2 data series.
$r = new RanSeries(127);
$data0 = $r->getSeries(180, 70, -5, 5);
$data1 = $r->getSeries(180, 150, -15, 15);
$timeStamps = $r->getDateSeries(180, chartTime(2014, 3, 1), 86400);

# Create a XYChart object of size 640 x 420 pixels
$c = new XYChart(640, 420);

# Add a title box using grey (0x555555) 20pt Arial Bold font
$c->addTitle("   Plasma Stabilizer Energy Usage", "arialbd.ttf", 20, 0x555555);

# Set the plotarea at (70, 70) and of size 540 x 320 pixels, with transparent background and border
# and light grey (0xcccccc) horizontal grid lines
$c->setPlotArea(70, 70, 540, 320, -1, -1, Transparent, 0xcccccc);

# Add a legend box with horizontal layout above the plot area at (70, 32). Use 12pt Arial Bold dark
# grey (0x555555) font, transparent background and border, and line style legend icon.
$b = $c->addLegend(70, 32, false, "arialbd.ttf", 12);
$b->setFontColor(0x555555);
$b->setBackground(Transparent, Transparent);
$b->setLineStyleKey();

# Set axis label font to 12pt Arial
$c->xAxis->setLabelStyle("arial.ttf", 12);
$c->yAxis->setLabelStyle("arial.ttf", 12);

# Set the x and y axis stems to transparent, and the x-axis tick color to grey (0xaaaaaa)
$c->xAxis->setColors(Transparent, TextColor, TextColor, 0xaaaaaa);
$c->yAxis->setColors(Transparent);

# Set the major/minor tick lengths for the x-axis to 10 and 0.
$c->xAxis->setTickLength(10, 0);

# For the automatic axis labels, set the minimum spacing to 80/40 pixels for the x/y axis.
$c->xAxis->setTickDensity(80);
$c->yAxis->setTickDensity(40);

# Use "mm/yyyy" as the x-axis label format for the first plotted month of a year, and "mm" for other
# months
$c->xAxis->setMultiFormat(StartOfYearFilter(), "{value|mm/yyyy} ", StartOfMonthFilter(),
    "{value|mm}");

# Add a title to the y axis using dark grey (0x555555) 12pt Arial Bold font
$c->yAxis->setTitle("Energy (kWh)", "arialbd.ttf", 14, 0x555555);

# Add a line layer with 2-pixel line width
$layer0 = $c->addLineLayer($data0, 0xcc0000, "Power Usage");
$layer0->setXData($timeStamps);
$layer0->setLineWidth(2);

# Add an area layer using semi-transparent blue (0x7f0044cc) as the fill color
$layer1 = $c->addAreaLayer($data1, 0x7f0044cc, "Effective Load");
$layer1->setXData($timeStamps);
$layer1->setBorderColor(SameAsMainColor);

# Output the chart
header("Content-type: image/png");
print($c->makeChart2(PNG));
?>
