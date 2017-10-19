<?php
//============================================================+
// File name   : example_021.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 021 for TCPDF class
//               WriteHTML text flow
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: WriteHTML text flow.
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('wp-pdf-templates.php');

class AUTHPDF extends TCPDF
{
    protected $processId = 0;
    protected $header = '';
    protected $footer = '';
    static $errorMsg = '';

    /**
      * This method is used to override the parent class method.
    **/
    public function Header()
    {
       $this->writeHTMLCell($w='', $h='', $x='', $y='', $this->header, $border=0, $ln=0, $fill=0, $reseth=true, $align='L', $autopadding=true);
       $this->SetLineStyle( array( 'width' => 0.30, 'color' => array(112, 48, 160)));

       $this->Line(10, 10, $this->getPageWidth()-10, 10); 

       $this->Line($this->getPageWidth()-10, 10, $this->getPageWidth()-10,  $this->getPageHeight()-10);
       $this->Line(10, $this->getPageHeight()-10, $this->getPageWidth()-10, $this->getPageHeight()-10);
       $this->Line(10, 10, 10, $this->getPageHeight()-10);
    }
}

// create new PDF document
$pdf = new AUTHPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
// $pdf->SetAuthor('Nicola Asuni');
// $pdf->SetTitle('TCPDF Example 003');
// $pdf->SetSubject('TCPDF Tutorial');
// $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
//$pdf->SetFont('times', 'BI', 12);

// add a page
$pdf->AddPage();
//
// create some HTML content
$html = <<<EOD
<p><div style="font-family: Cambria (Headings); color: #7030a0;"><strong>MIGHTY LAB INSTRUMENTS<br />BRANCH OFF:NO:19/14E, VENU STREET, GUINDY-600 032.<br />PH: 2233 1485,CELL:9566222268.<br />MAIL:info@mightylab.in</strong><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong></div></p>
<p style="text-align: center;"><span style="color: #ff3399; font-family: Cambria (Headings);"><strong><span style="font-size: 18pt;"><strong>MIGHTY LAB INSTRUMENTS</strong></span></strong></span></p>
<div style="border-style: solid solid solid solid;border-width: 1px 1px 1px 1px;border-color:#7030a0;color: #FF3399;font-size: 12pt;text-align: center;"><span style="color: #ff3399; font-size: 12pt; font-family: Cambria (Headings); text-align: center;"><strong>Mfrs. Of&nbsp; Scientific &amp; Laboratory Equipments for Heating,&nbsp; Cooling, Testing &amp; Measuring<br /></strong></span></div>
<p style="text-align: center;"><span style="color: #ff3399; font-size: 14pt; font-family: Cambria (Headings);"><strong>ASH FURNACE(MI-1220)</strong></span></p>
<p style="text-align: left;"><span style="font-family: Cambria (Headings); font-size: 12pt;">&ldquo;<strong>MIGHTY INSTRUMENTS</strong>&rdquo; &nbsp;Ash Furnace are Designed to Double walled chamber, outer thick gauge mild steel shell and inner formed by grooved high alumina refractory bricks. Insulated with high alumina insulation brickwork and ceramic fibre mat. Heating elements will be Kanthal A1 coils. Temperature indicated and controlled by Digital temperature controller working in conjunction with Cr/Al thermocouple. Supplied complete with control panel.</span></p>
<p><span style="font-family: Cambria (Headings); font-size: 12pt; text-align: left;"><span style="color: #ff3399;">Maximum&nbsp; Temperature:</span> 1000&ordm; C &plusmn; 1&ordm;C</span></p>
<p><span style="font-family: Cambria (Headings); font-size: 12pt;"><strong>&nbsp;</strong></span></p>
<p><span style="color: #ff3399; font-family: Cambria (Headings); font-size: 12pt; text-align: left;"><strong>OPTIONAL ACCESSORIES:</strong></span></p>
<ul>
<li><span style="font-family: Cambria (Headings); font-size: 12pt;">Thyristor with Digital PID controller.</span></li>
<li><span style="font-family: Cambria (Headings); font-size: 12pt;">Safety controller</span></li>
</ul>
<p><span style="font-family: Cambria (Headings);">&nbsp;</span></p>
<p><span style="font-family: Cambria (Headings);"><strong>&nbsp;</strong></span></p>
<div>&nbsp;</div>
EOD;
// output the HTML content

$pdf->writeHTML($html, true, 0, true, 0);

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('Autoclave.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

echo "wdwed3wedw3e";