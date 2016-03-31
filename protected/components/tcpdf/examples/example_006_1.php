<?php
//============================================================+
// File name   : example_006.php
// Begin       : 2008-03-04
// Last Update : 2010-11-20
//
// Description : Example 006 for TCPDF class
//               WriteHTML and RTL support
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
 * @abstract TCPDF - Example: WriteHTML and RTL support
 * @author Nicola Asuni
 * @since 2008-03-04
 */

require_once('../config/lang/eng.php');
require_once('../tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Zyp');
$pdf->SetTitle('Nominations and Lock-Out');
$pdf->SetSubject('Nominations and Lock-Out');
$pdf->SetKeywords('Nominations, Lock-Out');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' Nominations and Lock-Out', PDF_HEADER_STRING);

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

// set font
$pdf->SetFont('dejavusans', '', 10);

// add a page
$pdf->AddPage();

// test some inline CSS
$html = '<h2>Nominations and Lock-Outs</h2>
<table width="680" border="1" class="t1" cellpadding="3">
  <tbody><tr>
    <td width="40%" valign="top">
      To:  Audigy Group, LLC<br>
      Fax: 866.678.9706<br><br>

      For any questions, please contact your Operations Manager.
    </td>
    <td>
      Date: 05/21/2012<br>
      From: Janene Bauhofer<br>
      Practice Name: 1413 - Placer Speech &amp; Hearing Services<br>
      Contact Number: 530/906-2159    </td>
  </tr>
</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Print a table


// Print some HTML Cells
$html = '<h1>NOMINATIONS</h1>
We are asking you to nominate any of your peers that you believe would benefit from learning more about Audigy Group. <br/>
Please provide as much contact information as possible for three to five independent hearing care providers whom you believe would be strong candidates to attend an Audigy Group Guest Summit and possibly become a member.';
$pdf->writeHTML($html, true, false, true, false, '');


// create some HTML content
$html = '<table width="680" border="1" class="t1" cellpadding="3">
    <tbody>
    <tr>
      <td width="140" align="center">Nominee Name</td>
      <td width="140" align="center">Nominee Practice</td>
      <td width="140" align="center">City, State</td>
      <td width="100" align="center">Telephone</td>
      <td width="" align="center">Preferred<br>First Contact</td>
    </tr>
    <tr>
      <td width="140" align="center"><b>sfjdf. klsajfjdf</b></td>
      <td width="140" align="center"><b>skjklj</b></td>
      <td width="140" align="center"><b>sdf Kentucky</b></td>
      <td width="100" align="center"> <b>245436536</b></td>
      <td width="" align="center"><b>AG</b></td>
    </tr>
    <tr>
      <td width="140" align="center"><b>sfjdf. klsajfjdf</b></td>
      <td width="140" align="center"><b>skjklj</b></td>
      <td width="140" align="center"><b>sdf Kentucky</b></td>
      <td width="100" align="center"> <b>245436536</b></td>
      <td width="" align="center"><b>AG</b></td>
    </tr>
    <tr>
      <td width="140" align="center"><b>sfjdf. klsajfjdf</b></td>
      <td width="140" align="center"><b>skjklj</b></td>
      <td width="140" align="center"><b>sdf Kentucky</b></td>
      <td width="100" align="center"> <b>245436536</b></td>
      <td width="" align="center"><b>AG</b></td>
    </tr>
    <tr>
      <td width="140" align="center"><b>sfjdf. klsajfjdf</b></td>
      <td width="140" align="center"><b>skjklj</b></td>
      <td width="140" align="center"><b>sdf Kentucky</b></td>
      <td width="100" align="center"> <b>245436536</b></td>
      <td width="" align="center"><b>AG</b></td>
    </tr>
    <tr>
      <td width="140" align="center"><b>sfjdf. klsajfjdf</b></td>
      <td width="140" align="center"><b>skjklj</b></td>
      <td width="140" align="center"><b>sdf Kentucky</b></td>
      <td width="100" align="center"> <b>245436536</b></td>
      <td width="" align="center"><b>AG</b></td>
    </tr>
  </tbody></table>';
$pdf->writeHTML($html, true, false, true, false, '');
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_006.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
