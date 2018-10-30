<?php
require_once("../lib/phpchartdir.php");

# The data for the pyramid chart
$data = array(156, 123, 211, 179);

# The labels for the pyramid chart
$labels = array("Funds", "Bonds", "Stocks", "Cash");

# Create a PyramidChart object of size 360 x 360 pixels
$c = new PyramidChart(360, 360);

# Set the pyramid center at (180, 180), and width x height to 150 x 180 pixels
$c->setPyramidSize(180, 180, 150, 300);

# Set the pyramid data and labels
$c->setData($data, $labels);

# Add labels at the center of the pyramid layers using Arial Bold font. The labels will have two
# lines showing the layer name and percentage.
$c->setCenterLabel("{label}\n{percent}%", "arialbd.ttf");

# Output the chart
header("Content-type: image/png");
print($c->makeChart2(PNG));
?>
