<?php
include ("../lib/jpgraph/jpgraph.php");
include ("../lib/jpgraph/jpgraph_bar.php");
//include ("../lib/jpgraph/jpgraph_line.php");
include ("../lib/jpgraph/jpgraph_pie.php");
include ("../lib/jpgraph/jpgraph_pie3d.php");

// Some data
$databary=unserialize(base64_decode($data));
$title=base64_decode($title);
$databarx=unserialize(base64_decode($mon));

if(!$x)
    $x=500;
if(!$y)
    $y=300;
    
if (!isset($style))
	$style = "";
	
if (!isset($xangle))
	$xangle = "";

if (!isset($ystyle))
	$ystyle = "";    
		
if (!isset($xtitle))
	$xtitle = "";    
	
// New graph with a drop shadow
if($style!='pie') {
    $graph = new Graph($x,$y,'auto');
    #$graph->SetShadow();

    // Use a "text" X-scale
    $graph->SetScale("textlin");
    if($xangle==90) {
	$graph->xaxis->SetLabelAngle(90);
        $graph->xaxis->SetTitleMargin(15);
    }
    // Specify X-labels
    $graph->xaxis->SetTickLabels($databarx);
    if($ytitle)
	$ytitle=base64_decode($ytitle);
    else $ytitle="Sales Total";
    $graph->yaxis->title->Set($ytitle);
    //$graph->yaxis->SetLabelFormat("$%01.0f");
    if($ystyle=='dollar')
	$graph->yaxis->SetLabelFormat("$%u");
    else $graph->yaxis->SetLabelFormat("%u");
    $graph->yaxis->SetTitleMargin(40);

    if($xtitle)
	$xtitle=base64_decode($xtitle);
    else $xtitle="";
    $graph->xaxis->title->Set($xtitle);
}
else {
    $graph = new PieGraph($x,$y,"auto");
    $graph->SetShadow();
}

// Set title and subtitle

$graph->title->Set($title);

// Use built in font
$graph->title->SetFont(FF_FONT1,FS_BOLD);

// Create the bar plot by default
if(!$style)
    $b1 = new BarPlot($databary);
/* otherwise we create a line plot */
else if($style=='line') {
    $b1 = new LinePlot($databary);
    $b1->value->Show();
    $b1->value->SetColor("red");
    $b1->value->SetFont(FF_FONT1,FS_BOLD);
#    $b1->value->SetFormat('%u');
    $b1->value->SetFormat('');
}
else if($style=='pie') {
#echo "<hr>mark1";
    $b1 = new PiePlot3D($databary);
    $b1->ExplodeSlice(1);
    $b1->SetCenter(0.45);
    $b1->SetLegends($databarx);
}

#$b1->SetLegend("Sales");

//$b1->SetAbsWidth(6);
//$b1->SetShadow();

// The order the plots are added determines who's ontop
$graph->Add($b1);

// Finally output the  image
$graph->Stroke();

?>
