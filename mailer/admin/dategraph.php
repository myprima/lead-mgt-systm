<?php
set_include_path("../lib");

include ("jpgraph/jpgraph.php");
include ("jpgraph/jpgraph_bar.php");

//$s = isset($_GET['s']) ? intval($_GET['s']) : 50;
//$r = isset($_GET['r']) ? intval($_GET['r']) : 50;
// Some data
$data = array($s, $r);

// Create the Pie Graph.
$graph = new BarPlot();
$graph->SetScale("textint");
$graph->SetShadow();
$graph->SetFrame(false); // No border around the graph


//$graph->SetShadow(1,2);
$graph->SetAntiAliasing();

// Set A title for the plot
$graph->title->Set("Email Statistics Graphs");
$graph->title->SetFont(FF_FONT2,FS_BOLD,12);
$graph->title->SetColor("black");
$graph->legend->Pos(0.5,0.15);

// Create pie plot
$p1 = new PiePlot3d($data);
$p1->SetTheme("sand");

$p1->SetCenter(0.6, 0.65);
$p1->SetAngle(29);
$p1->value->SetFont(FF_FONT1,FS_NORMAL,10);
$p1->SetSliceColors(array('#ff00ff', '#0000ff'));
$p1->SetLegends(array("Successfully emails sent", "Rejected emails"));

$graph->Add($p1);
$graph->Stroke();

?>