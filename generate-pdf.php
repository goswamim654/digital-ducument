<?php
include 'dbconnect.php';
$regex = '/%%input,([a-zA-Z0-9]+),([0-9]+)%%/is';
$id = $_GET['id'];
$image = $_POST['image'];
$image = preg_replace('#^data:image/\w+;base64,#i', '', $image);
function custom_replace($regex, $value, $content) {
	$content = preg_replace($regex, $value, $content, 1);
	return $content;
}

// generate pdf

function generatePdf($data, $id, $image) {
	// Include the main TCPDF library (search for installation path).
	require_once('plugins/tcpdf/tcpdf.php');

	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// Add a page
	// This method has several options, check the source code documentation for more information.
	$pdf->AddPage();

	// Example of Image from data stream ('PHP rules')
	$imgdata = base64_decode($image);

	// Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
	// The '@' character is used to indicate that follows an image data stream and not an image file name
	$pdf->Image('@'.$imgdata, 15, 100, 80, 50, 'PNG', '', '', false, 150, '', false, false, 1, false, false, false);

// Set some content to print
$html = <<<EOD
$data
EOD;

	// Print text using writeHTMLCell()
	$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

	// ---------------------------------------------------------

	// save pdf 

	$filename= $id.".pdf"; 
	//$filelocation = getcwd().'\pdf';
	$filelocation = getcwd().'/pdf'; //Linux

	//$fileNL = $filelocation."\\".$filename;//Windows
	$fileNL = $filelocation."/".$filename; //Linux
	$pdf->Output($fileNL, 'FI');

}


$sql = "SELECT * from test_digital_document_templates WHERE id=$id";
$res = $con->query($sql);
$row = $res->fetch_object();
$content =  $row->content;
foreach ($_POST as $key => $value) {
	//echo $value;
	if($key != 'image') {
		$value = '<i><b>'.$value.'</b></i>';
		$content = custom_replace($regex, $value, $content);
	}
		
}
// generate pdf
//$content = $content."<img src=$image>";
//echo $content;
generatePdf($content, $id, $image);

?>