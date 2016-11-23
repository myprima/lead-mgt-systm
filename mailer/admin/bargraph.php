<?php

set_include_path("../lib");

include ("jpgraph/jpgraph.php");
include ("jpgraph/jpgraph_bar.php");


$dataArray1 = explode(",",$d1);
$dataArray2 = explode(",",$d2);
$dataArray3 = explode(",",$d3);
$xtitle = $range;

$graph = new Graph(500,300,'auto');
$graph->SetScale('textlin');

$barplot1 = new BarPlot($dataArray1);
$barplot1 ->SetFillColor ("blue"); 
$barplot1->legend = " Subscribers";

$barplot2 = new BarPlot($dataArray2);
$barplot2 ->SetFillColor ("red"); 
$barplot2->legend = " Un-Subscribers";

//$barplot3 = new BarPlot($dataArray3);
//$barplot3 ->SetFillColor ("yellow"); 
//$barplot3->legend = " Total Bounces";


$gbplot  = new GroupBarPlot (array($barplot1 ,$barplot2));
$gbplot->SetWidth(0.8);

$a  = $gDateLocale ->GetShortMonth (); 
$graph->xaxis-> SetTickLabels($a); 

$graph->title->Set("Subscriber Summary");
$graph->title->SetColor('blue');
$graph ->yaxis->scale-> SetGrace(20);


//$graph->yaxis->SetTitleMargin(20);
//$graph->yaxis->title->Set("y title");

$graph->xaxis->SetTitleMargin(15);
$graph->xaxis->title->Set($xtitle);

$graph ->ygrid->Show(true,true);
$graph ->xgrid->Show(true);
$graph ->SetShadow();

$graph->img->SetMargin(50,40,30,70);

$graph->title->SetFont(FF_FONT2,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT2,FS_BOLD);

$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(.40, 0.87, 'center'); 

$graph->Add($gbplot);


$graph->Stroke();



?>