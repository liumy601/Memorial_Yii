<?php
//============================================================+
// File name   : example_054.php
// Begin       : 2009-09-07
// Last Update : 2010-08-08
//
// Description : Example 054 for TCPDF class
//               XHTML Forms
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               Manor Coach House, Church Hill
//               Aldershot, Hants, GU12 4RQ
//               UK
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: XHTML Forms
 * @author Nicola Asuni
 * @since 2009-09-07
 */

require_once('../config/lang/eng.php');
require_once('../tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 054');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 054', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// IMPORTANT: disable font subsetting to allow users editing the document
$pdf->setFontSubsetting(false);

// set font
$pdf->SetFont('helvetica', '', 10, '', false);

// add a page
$pdf->AddPage();

// create some HTML content
$html = <<<EOD
<h1>Image alignments on HTML table</h1>
<table cellpadding="1" cellspacing="1" border="1" style="text-align:center;">
<tr><td><img src="../images/logo_example.png" border="0" height="41" width="41" /></td></tr>
<tr style="text-align:left;"><td><img src="../images/logo_example.png" border="0" height="41" width="41" align="top" /></td></tr>
<tr style="text-align:center;"><td><img src="../images/logo_example.png" border="0" height="41" width="41" align="middle" /></td></tr>
<tr style="text-align:right;"><td><img src="../images/logo_example.png" border="0" height="41" width="41" align="bottom" /></td></tr>
<tr><td style="text-align:left;"><img src="../images/logo_example.png" border="0" height="41" width="41" align="top" /></td></tr>
<tr><td style="text-align:center;"><img src="../images/logo_example.png" border="0" height="41" width="41" align="middle" /></td></tr>
<tr><td style="text-align:right;"><img src="../images/logo_example.png" border="0" height="41" width="41" align="bottom" /></td></tr>
</table>
EOD;

// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0);

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_054.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
