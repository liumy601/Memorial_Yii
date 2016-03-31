<?php
//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2010-08-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
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
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

require_once('../config/lang/eng.php');
require_once('../tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 001');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING);

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

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// Set some content to print
$html = <<<EOD
<div class="node">
        <form method="post" action="index.php">
          <input type="hidden" value="forms" name="page">
          <input type="hidden" value="Nominations" name="subpage">
          <input type="hidden" name="submit" value="1">

        <div id="form_Nominations" class="mit_forms">
          <h2>Nominations and Lock-Outs</h2>
          <table width="700" border="1" class="t1">
            <tbody><tr>
              <td width="50%" valign="top">
                To:  Audigy Group, LLC<br>
                Fax: 866.678.9706<br><br>
                
                For any questions, please contact your Operations Manager.
              </td>
              <td>
                Date: 05/20/2012<br>
                From: Janene Bauhofer<br>
                Practice Name: 1413 - Placer Speech &amp; Hearing Services<br>
                Contact Number: 530/906-2159              </td>
            </tr>
          </tbody></table>
          <div>
            <h1>NOMINATIONS</h1>
            We are asking you to nominate any of your peers that you believe would benefit from learning more about Audigy Group. <br>
            Please provide as much contact information as possible for three to five independent hearing care providers whom you believe would be strong candidates to attend an Audigy Group Guest Summit and possibly become a member..<br>
            <table width="auto" border="1" class="t1">
              <tbody><tr>
                <th>Nominee Name</th><th>Nominee Practice</th><th>City, State</th><th>Telephone</th><th>Preferred<br>First Contact</th>
              </tr>
              <tr>
                <td><table border="0" class="noborder"><tbody><tr><td><input name="first_name_c[]" size="10" type="text" value=""> </td><td><input name="last_name_c[]" size="10" type="text" value=""></td></tr></tbody></table></td>
                <td><input type="text" name="company_name_c[]" value="" size="15"></td>
                <td><table border="0" class="noborder"><tbody><tr><td><input type="text" name="city_c[]" value="" class="width60"> </td><td><select name="state_c[]">
<option label="" value=""></option>
<option label="Alabama" value="Alabama">Alabama</option>
<option label="Alaska" value="Alaska">Alaska</option>
<option label="Arizona" value="Arizona">Arizona</option>
<option label="Arkansas" value="Arkansas">Arkansas</option>
<option label="California" value="California">California</option>
<option label="Colorado" value="Colorado">Colorado</option>
<option label="Connecticut" value="Connecticut">Connecticut</option>
<option label="Delaware" value="Delaware">Delaware</option>
<option label="Florida" value="Florida">Florida</option>
<option label="Georgia" value="Georgia">Georgia</option>
<option label="Hawaii" value="Hawaii">Hawaii</option>
<option label="Idaho" value="Idaho">Idaho</option>
<option label="Illinois" value="Illinois">Illinois</option>
<option label="Indiana" value="Indiana">Indiana</option>
<option label="Iowa" value="Iowa">Iowa</option>
<option label="Kansas" value="Kansas">Kansas</option>
<option label="Kentucky" value="Kentucky">Kentucky</option>
<option label="Louisiana" value="Louisiana">Louisiana</option>
<option label="Maine" value="Maine">Maine</option>
<option label="Maryland" value="Maryland">Maryland</option>
<option label="Massachusetts" value="Massachusetts">Massachusetts</option>
<option label="Michigan" value="Michigan">Michigan</option>
<option label="Minnesota" value="Minnesota">Minnesota</option>
<option label="Mississippi" value="Mississippi">Mississippi</option>
<option label="Missouri" value="Missouri">Missouri</option>
<option label="Montana" value="Montana">Montana</option>
<option label="Nevada" value="Nevada">Nevada</option>
<option label="Nebraska" value="Nebraska">Nebraska</option>
<option label="New Hampshire" value="New Hampshire">New Hampshire</option>
<option label="New Jersey" value="New Jersey">New Jersey</option>
<option label="New Mexico" value="New Mexico">New Mexico</option>
<option label="New York" value="New York">New York</option>
<option label="North Carolina" value="North Carolina">North Carolina</option>
<option label="North Dakota" value="North Dakota">North Dakota</option>
<option label="Ohio" value="Ohio">Ohio</option>
<option label="Oklahoma" value="Oklahoma">Oklahoma</option>
<option label="Oregon" value="Oregon">Oregon</option>
<option label="Pennsylvania" value="Pennsylvania">Pennsylvania</option>
<option label="Rhode Island" value="Rhode Island">Rhode Island</option>
<option label="South Carolina" value="South Carolina">South Carolina</option>
<option label="South Dakota" value="South Dakota">South Dakota</option>
<option label="Tennessee" value="Tennessee">Tennessee</option>
<option label="Texas" value="Texas">Texas</option>
<option label="Utah" value="Utah">Utah</option>
<option label="Vermont" value="Vermont">Vermont</option>
<option label="Virginia" value="Virginia">Virginia</option>
<option label="Washington" value="Washington">Washington</option>
<option label="West Virginia" value="West Virginia">West Virginia</option>
<option label="Wisconsin" value="Wisconsin">Wisconsin</option>
<option label="Wyoming" value="Wyoming">Wyoming</option>
<option label="Washington DC" value="Washington DC">Washington DC</option>
</select></td></tr></tbody></table></td>
                <td><input type="text" name="phone_c[]" value="" size="15"></td>
                <td><select name="pref_first_contact[]"><option value="AG">AG</option><option value="You">You</option></select></td>
              </tr>
              <tr>
                <td><table border="0" class="noborder"><tbody><tr><td><input name="first_name_c[]" size="10" type="text" value=""> </td><td><input name="last_name_c[]" size="10" type="text" value=""></td></tr></tbody></table></td>
                <td><input type="text" name="company_name_c[]" value="" size="15"></td>
                <td><table border="0" class="noborder"><tbody><tr><td><input type="text" name="city_c[]" value="" class="width60"> </td><td><select name="state_c[]">
<option label="" value=""></option>
<option label="Alabama" value="Alabama">Alabama</option>
<option label="Alaska" value="Alaska">Alaska</option>
<option label="Arizona" value="Arizona">Arizona</option>
<option label="Arkansas" value="Arkansas">Arkansas</option>
<option label="California" value="California">California</option>
<option label="Colorado" value="Colorado">Colorado</option>
<option label="Connecticut" value="Connecticut">Connecticut</option>
<option label="Delaware" value="Delaware">Delaware</option>
<option label="Florida" value="Florida">Florida</option>
<option label="Georgia" value="Georgia">Georgia</option>
<option label="Hawaii" value="Hawaii">Hawaii</option>
<option label="Idaho" value="Idaho">Idaho</option>
<option label="Illinois" value="Illinois">Illinois</option>
<option label="Indiana" value="Indiana">Indiana</option>
<option label="Iowa" value="Iowa">Iowa</option>
<option label="Kansas" value="Kansas">Kansas</option>
<option label="Kentucky" value="Kentucky">Kentucky</option>
<option label="Louisiana" value="Louisiana">Louisiana</option>
<option label="Maine" value="Maine">Maine</option>
<option label="Maryland" value="Maryland">Maryland</option>
<option label="Massachusetts" value="Massachusetts">Massachusetts</option>
<option label="Michigan" value="Michigan">Michigan</option>
<option label="Minnesota" value="Minnesota">Minnesota</option>
<option label="Mississippi" value="Mississippi">Mississippi</option>
<option label="Missouri" value="Missouri">Missouri</option>
<option label="Montana" value="Montana">Montana</option>
<option label="Nevada" value="Nevada">Nevada</option>
<option label="Nebraska" value="Nebraska">Nebraska</option>
<option label="New Hampshire" value="New Hampshire">New Hampshire</option>
<option label="New Jersey" value="New Jersey">New Jersey</option>
<option label="New Mexico" value="New Mexico">New Mexico</option>
<option label="New York" value="New York">New York</option>
<option label="North Carolina" value="North Carolina">North Carolina</option>
<option label="North Dakota" value="North Dakota">North Dakota</option>
<option label="Ohio" value="Ohio">Ohio</option>
<option label="Oklahoma" value="Oklahoma">Oklahoma</option>
<option label="Oregon" value="Oregon">Oregon</option>
<option label="Pennsylvania" value="Pennsylvania">Pennsylvania</option>
<option label="Rhode Island" value="Rhode Island">Rhode Island</option>
<option label="South Carolina" value="South Carolina">South Carolina</option>
<option label="South Dakota" value="South Dakota">South Dakota</option>
<option label="Tennessee" value="Tennessee">Tennessee</option>
<option label="Texas" value="Texas">Texas</option>
<option label="Utah" value="Utah">Utah</option>
<option label="Vermont" value="Vermont">Vermont</option>
<option label="Virginia" value="Virginia">Virginia</option>
<option label="Washington" value="Washington">Washington</option>
<option label="West Virginia" value="West Virginia">West Virginia</option>
<option label="Wisconsin" value="Wisconsin">Wisconsin</option>
<option label="Wyoming" value="Wyoming">Wyoming</option>
<option label="Washington DC" value="Washington DC">Washington DC</option>
</select></td></tr></tbody></table></td>
                <td><input type="text" name="phone_c[]" value="" size="15"></td>
                <td><select name="pref_first_contact[]"><option value="AG">AG</option><option value="You">You</option></select></td>
              </tr>
              <tr>
                <td><table border="0" class="noborder"><tbody><tr><td><input name="first_name_c[]" size="10" type="text" value=""> </td><td><input name="last_name_c[]" size="10" type="text" value=""></td></tr></tbody></table></td>
                <td><input type="text" name="company_name_c[]" value="" size="15"></td>
                <td><table border="0" class="noborder"><tbody><tr><td><input type="text" name="city_c[]" value="" class="width60"> </td><td><select name="state_c[]">
<option label="" value=""></option>
<option label="Alabama" value="Alabama">Alabama</option>
<option label="Alaska" value="Alaska">Alaska</option>
<option label="Arizona" value="Arizona">Arizona</option>
<option label="Arkansas" value="Arkansas">Arkansas</option>
<option label="California" value="California">California</option>
<option label="Colorado" value="Colorado">Colorado</option>
<option label="Connecticut" value="Connecticut">Connecticut</option>
<option label="Delaware" value="Delaware">Delaware</option>
<option label="Florida" value="Florida">Florida</option>
<option label="Georgia" value="Georgia">Georgia</option>
<option label="Hawaii" value="Hawaii">Hawaii</option>
<option label="Idaho" value="Idaho">Idaho</option>
<option label="Illinois" value="Illinois">Illinois</option>
<option label="Indiana" value="Indiana">Indiana</option>
<option label="Iowa" value="Iowa">Iowa</option>
<option label="Kansas" value="Kansas">Kansas</option>
<option label="Kentucky" value="Kentucky">Kentucky</option>
<option label="Louisiana" value="Louisiana">Louisiana</option>
<option label="Maine" value="Maine">Maine</option>
<option label="Maryland" value="Maryland">Maryland</option>
<option label="Massachusetts" value="Massachusetts">Massachusetts</option>
<option label="Michigan" value="Michigan">Michigan</option>
<option label="Minnesota" value="Minnesota">Minnesota</option>
<option label="Mississippi" value="Mississippi">Mississippi</option>
<option label="Missouri" value="Missouri">Missouri</option>
<option label="Montana" value="Montana">Montana</option>
<option label="Nevada" value="Nevada">Nevada</option>
<option label="Nebraska" value="Nebraska">Nebraska</option>
<option label="New Hampshire" value="New Hampshire">New Hampshire</option>
<option label="New Jersey" value="New Jersey">New Jersey</option>
<option label="New Mexico" value="New Mexico">New Mexico</option>
<option label="New York" value="New York">New York</option>
<option label="North Carolina" value="North Carolina">North Carolina</option>
<option label="North Dakota" value="North Dakota">North Dakota</option>
<option label="Ohio" value="Ohio">Ohio</option>
<option label="Oklahoma" value="Oklahoma">Oklahoma</option>
<option label="Oregon" value="Oregon">Oregon</option>
<option label="Pennsylvania" value="Pennsylvania">Pennsylvania</option>
<option label="Rhode Island" value="Rhode Island">Rhode Island</option>
<option label="South Carolina" value="South Carolina">South Carolina</option>
<option label="South Dakota" value="South Dakota">South Dakota</option>
<option label="Tennessee" value="Tennessee">Tennessee</option>
<option label="Texas" value="Texas">Texas</option>
<option label="Utah" value="Utah">Utah</option>
<option label="Vermont" value="Vermont">Vermont</option>
<option label="Virginia" value="Virginia">Virginia</option>
<option label="Washington" value="Washington">Washington</option>
<option label="West Virginia" value="West Virginia">West Virginia</option>
<option label="Wisconsin" value="Wisconsin">Wisconsin</option>
<option label="Wyoming" value="Wyoming">Wyoming</option>
<option label="Washington DC" value="Washington DC">Washington DC</option>
</select></td></tr></tbody></table></td>
                <td><input type="text" name="phone_c[]" value="" size="15"></td>
                <td><select name="pref_first_contact[]"><option value="AG">AG</option><option value="You">You</option></select></td>
              </tr>
              <tr>
                <td><table border="0" class="noborder"><tbody><tr><td><input name="first_name_c[]" size="10" type="text" value=""> </td><td><input name="last_name_c[]" size="10" type="text" value=""></td></tr></tbody></table></td>
                <td><input type="text" name="company_name_c[]" value="" size="15"></td>
                <td><table border="0" class="noborder"><tbody><tr><td><input type="text" name="city_c[]" value="" class="width60"> </td><td><select name="state_c[]">
<option label="" value=""></option>
<option label="Alabama" value="Alabama">Alabama</option>
<option label="Alaska" value="Alaska">Alaska</option>
<option label="Arizona" value="Arizona">Arizona</option>
<option label="Arkansas" value="Arkansas">Arkansas</option>
<option label="California" value="California">California</option>
<option label="Colorado" value="Colorado">Colorado</option>
<option label="Connecticut" value="Connecticut">Connecticut</option>
<option label="Delaware" value="Delaware">Delaware</option>
<option label="Florida" value="Florida">Florida</option>
<option label="Georgia" value="Georgia">Georgia</option>
<option label="Hawaii" value="Hawaii">Hawaii</option>
<option label="Idaho" value="Idaho">Idaho</option>
<option label="Illinois" value="Illinois">Illinois</option>
<option label="Indiana" value="Indiana">Indiana</option>
<option label="Iowa" value="Iowa">Iowa</option>
<option label="Kansas" value="Kansas">Kansas</option>
<option label="Kentucky" value="Kentucky">Kentucky</option>
<option label="Louisiana" value="Louisiana">Louisiana</option>
<option label="Maine" value="Maine">Maine</option>
<option label="Maryland" value="Maryland">Maryland</option>
<option label="Massachusetts" value="Massachusetts">Massachusetts</option>
<option label="Michigan" value="Michigan">Michigan</option>
<option label="Minnesota" value="Minnesota">Minnesota</option>
<option label="Mississippi" value="Mississippi">Mississippi</option>
<option label="Missouri" value="Missouri">Missouri</option>
<option label="Montana" value="Montana">Montana</option>
<option label="Nevada" value="Nevada">Nevada</option>
<option label="Nebraska" value="Nebraska">Nebraska</option>
<option label="New Hampshire" value="New Hampshire">New Hampshire</option>
<option label="New Jersey" value="New Jersey">New Jersey</option>
<option label="New Mexico" value="New Mexico">New Mexico</option>
<option label="New York" value="New York">New York</option>
<option label="North Carolina" value="North Carolina">North Carolina</option>
<option label="North Dakota" value="North Dakota">North Dakota</option>
<option label="Ohio" value="Ohio">Ohio</option>
<option label="Oklahoma" value="Oklahoma">Oklahoma</option>
<option label="Oregon" value="Oregon">Oregon</option>
<option label="Pennsylvania" value="Pennsylvania">Pennsylvania</option>
<option label="Rhode Island" value="Rhode Island">Rhode Island</option>
<option label="South Carolina" value="South Carolina">South Carolina</option>
<option label="South Dakota" value="South Dakota">South Dakota</option>
<option label="Tennessee" value="Tennessee">Tennessee</option>
<option label="Texas" value="Texas">Texas</option>
<option label="Utah" value="Utah">Utah</option>
<option label="Vermont" value="Vermont">Vermont</option>
<option label="Virginia" value="Virginia">Virginia</option>
<option label="Washington" value="Washington">Washington</option>
<option label="West Virginia" value="West Virginia">West Virginia</option>
<option label="Wisconsin" value="Wisconsin">Wisconsin</option>
<option label="Wyoming" value="Wyoming">Wyoming</option>
<option label="Washington DC" value="Washington DC">Washington DC</option>
</select></td></tr></tbody></table></td>
                <td><input type="text" name="phone_c[]" value="" size="15"></td>
                <td><select name="pref_first_contact[]"><option value="AG">AG</option><option value="You">You</option></select></td>
              </tr>
              <tr>
                <td><table border="0" class="noborder"><tbody><tr><td><input name="first_name_c[]" size="10" type="text" value=""> </td><td><input name="last_name_c[]" size="10" type="text" value=""></td></tr></tbody></table></td>
                <td><input type="text" name="company_name_c[]" value="" size="15"></td>
                <td><table border="0" class="noborder"><tbody><tr><td><input type="text" name="city_c[]" value="" class="width60"> </td><td><select name="state_c[]">
<option label="" value=""></option>
<option label="Alabama" value="Alabama">Alabama</option>
<option label="Alaska" value="Alaska">Alaska</option>
<option label="Arizona" value="Arizona">Arizona</option>
<option label="Arkansas" value="Arkansas">Arkansas</option>
<option label="California" value="California">California</option>
<option label="Colorado" value="Colorado">Colorado</option>
<option label="Connecticut" value="Connecticut">Connecticut</option>
<option label="Delaware" value="Delaware">Delaware</option>
<option label="Florida" value="Florida">Florida</option>
<option label="Georgia" value="Georgia">Georgia</option>
<option label="Hawaii" value="Hawaii">Hawaii</option>
<option label="Idaho" value="Idaho">Idaho</option>
<option label="Illinois" value="Illinois">Illinois</option>
<option label="Indiana" value="Indiana">Indiana</option>
<option label="Iowa" value="Iowa">Iowa</option>
<option label="Kansas" value="Kansas">Kansas</option>
<option label="Kentucky" value="Kentucky">Kentucky</option>
<option label="Louisiana" value="Louisiana">Louisiana</option>
<option label="Maine" value="Maine">Maine</option>
<option label="Maryland" value="Maryland">Maryland</option>
<option label="Massachusetts" value="Massachusetts">Massachusetts</option>
<option label="Michigan" value="Michigan">Michigan</option>
<option label="Minnesota" value="Minnesota">Minnesota</option>
<option label="Mississippi" value="Mississippi">Mississippi</option>
<option label="Missouri" value="Missouri">Missouri</option>
<option label="Montana" value="Montana">Montana</option>
<option label="Nevada" value="Nevada">Nevada</option>
<option label="Nebraska" value="Nebraska">Nebraska</option>
<option label="New Hampshire" value="New Hampshire">New Hampshire</option>
<option label="New Jersey" value="New Jersey">New Jersey</option>
<option label="New Mexico" value="New Mexico">New Mexico</option>
<option label="New York" value="New York">New York</option>
<option label="North Carolina" value="North Carolina">North Carolina</option>
<option label="North Dakota" value="North Dakota">North Dakota</option>
<option label="Ohio" value="Ohio">Ohio</option>
<option label="Oklahoma" value="Oklahoma">Oklahoma</option>
<option label="Oregon" value="Oregon">Oregon</option>
<option label="Pennsylvania" value="Pennsylvania">Pennsylvania</option>
<option label="Rhode Island" value="Rhode Island">Rhode Island</option>
<option label="South Carolina" value="South Carolina">South Carolina</option>
<option label="South Dakota" value="South Dakota">South Dakota</option>
<option label="Tennessee" value="Tennessee">Tennessee</option>
<option label="Texas" value="Texas">Texas</option>
<option label="Utah" value="Utah">Utah</option>
<option label="Vermont" value="Vermont">Vermont</option>
<option label="Virginia" value="Virginia">Virginia</option>
<option label="Washington" value="Washington">Washington</option>
<option label="West Virginia" value="West Virginia">West Virginia</option>
<option label="Wisconsin" value="Wisconsin">Wisconsin</option>
<option label="Wyoming" value="Wyoming">Wyoming</option>
<option label="Washington DC" value="Washington DC">Washington DC</option>
</select></td></tr></tbody></table></td>
                <td><input type="text" name="phone_c[]" value="" size="15"></td>
                <td><select name="pref_first_contact[]"><option value="AG">AG</option><option value="You">You</option></select></td>
              </tr>
              
            </tbody></table>
            
            <h1>LOCK-OUTS</h1>
            Are there practices that you feel would not be a good fit for Audigy?  Indicate in broad terms your reasoning (i.e., “direct competitor,” “ethical questions,” “personal tensions”).<br>
            <table width="auto" border="1" class="t1">
              <tbody><tr>
                <th>Lock-Out Name</th><th>Lock-Out Practice</th><th>City, State</th><th>Reasoning</th>
              </tr>
              <tr>
                <td><input type="text" name="lock_out_name[]" value=""></td>
                <td><input type="text" name="lock_out_practice[]" value=""></td>
                <td><table border="0" class="noborder"><tbody><tr><td><input type="text" name="lock_out_city[]" value="" class="width60"> </td><td><select name="lock_out_city_state[]">
<option label="" value=""></option>
<option label="Alabama" value="Alabama">Alabama</option>
<option label="Alaska" value="Alaska">Alaska</option>
<option label="Arizona" value="Arizona">Arizona</option>
<option label="Arkansas" value="Arkansas">Arkansas</option>
<option label="California" value="California">California</option>
<option label="Colorado" value="Colorado">Colorado</option>
<option label="Connecticut" value="Connecticut">Connecticut</option>
<option label="Delaware" value="Delaware">Delaware</option>
<option label="Florida" value="Florida">Florida</option>
<option label="Georgia" value="Georgia">Georgia</option>
<option label="Hawaii" value="Hawaii">Hawaii</option>
<option label="Idaho" value="Idaho">Idaho</option>
<option label="Illinois" value="Illinois">Illinois</option>
<option label="Indiana" value="Indiana">Indiana</option>
<option label="Iowa" value="Iowa">Iowa</option>
<option label="Kansas" value="Kansas">Kansas</option>
<option label="Kentucky" value="Kentucky">Kentucky</option>
<option label="Louisiana" value="Louisiana">Louisiana</option>
<option label="Maine" value="Maine">Maine</option>
<option label="Maryland" value="Maryland">Maryland</option>
<option label="Massachusetts" value="Massachusetts">Massachusetts</option>
<option label="Michigan" value="Michigan">Michigan</option>
<option label="Minnesota" value="Minnesota">Minnesota</option>
<option label="Mississippi" value="Mississippi">Mississippi</option>
<option label="Missouri" value="Missouri">Missouri</option>
<option label="Montana" value="Montana">Montana</option>
<option label="Nevada" value="Nevada">Nevada</option>
<option label="Nebraska" value="Nebraska">Nebraska</option>
<option label="New Hampshire" value="New Hampshire">New Hampshire</option>
<option label="New Jersey" value="New Jersey">New Jersey</option>
<option label="New Mexico" value="New Mexico">New Mexico</option>
<option label="New York" value="New York">New York</option>
<option label="North Carolina" value="North Carolina">North Carolina</option>
<option label="North Dakota" value="North Dakota">North Dakota</option>
<option label="Ohio" value="Ohio">Ohio</option>
<option label="Oklahoma" value="Oklahoma">Oklahoma</option>
<option label="Oregon" value="Oregon">Oregon</option>
<option label="Pennsylvania" value="Pennsylvania">Pennsylvania</option>
<option label="Rhode Island" value="Rhode Island">Rhode Island</option>
<option label="South Carolina" value="South Carolina">South Carolina</option>
<option label="South Dakota" value="South Dakota">South Dakota</option>
<option label="Tennessee" value="Tennessee">Tennessee</option>
<option label="Texas" value="Texas">Texas</option>
<option label="Utah" value="Utah">Utah</option>
<option label="Vermont" value="Vermont">Vermont</option>
<option label="Virginia" value="Virginia">Virginia</option>
<option label="Washington" value="Washington">Washington</option>
<option label="West Virginia" value="West Virginia">West Virginia</option>
<option label="Wisconsin" value="Wisconsin">Wisconsin</option>
<option label="Wyoming" value="Wyoming">Wyoming</option>
<option label="Washington DC" value="Washington DC">Washington DC</option>
</select></td></tr></tbody></table></td>
                <td><input type="text" name="reasoning[]" value=""></td>
              </tr>
              <tr>
                <td><input type="text" name="lock_out_name[]" value=""></td>
                <td><input type="text" name="lock_out_practice[]" value=""></td>
                <td><table border="0" class="noborder"><tbody><tr><td><input type="text" name="lock_out_city[]" value="" class="width60"> </td><td><select name="lock_out_city_state[]">
<option label="" value=""></option>
<option label="Alabama" value="Alabama">Alabama</option>
<option label="Alaska" value="Alaska">Alaska</option>
<option label="Arizona" value="Arizona">Arizona</option>
<option label="Arkansas" value="Arkansas">Arkansas</option>
<option label="California" value="California">California</option>
<option label="Colorado" value="Colorado">Colorado</option>
<option label="Connecticut" value="Connecticut">Connecticut</option>
<option label="Delaware" value="Delaware">Delaware</option>
<option label="Florida" value="Florida">Florida</option>
<option label="Georgia" value="Georgia">Georgia</option>
<option label="Hawaii" value="Hawaii">Hawaii</option>
<option label="Idaho" value="Idaho">Idaho</option>
<option label="Illinois" value="Illinois">Illinois</option>
<option label="Indiana" value="Indiana">Indiana</option>
<option label="Iowa" value="Iowa">Iowa</option>
<option label="Kansas" value="Kansas">Kansas</option>
<option label="Kentucky" value="Kentucky">Kentucky</option>
<option label="Louisiana" value="Louisiana">Louisiana</option>
<option label="Maine" value="Maine">Maine</option>
<option label="Maryland" value="Maryland">Maryland</option>
<option label="Massachusetts" value="Massachusetts">Massachusetts</option>
<option label="Michigan" value="Michigan">Michigan</option>
<option label="Minnesota" value="Minnesota">Minnesota</option>
<option label="Mississippi" value="Mississippi">Mississippi</option>
<option label="Missouri" value="Missouri">Missouri</option>
<option label="Montana" value="Montana">Montana</option>
<option label="Nevada" value="Nevada">Nevada</option>
<option label="Nebraska" value="Nebraska">Nebraska</option>
<option label="New Hampshire" value="New Hampshire">New Hampshire</option>
<option label="New Jersey" value="New Jersey">New Jersey</option>
<option label="New Mexico" value="New Mexico">New Mexico</option>
<option label="New York" value="New York">New York</option>
<option label="North Carolina" value="North Carolina">North Carolina</option>
<option label="North Dakota" value="North Dakota">North Dakota</option>
<option label="Ohio" value="Ohio">Ohio</option>
<option label="Oklahoma" value="Oklahoma">Oklahoma</option>
<option label="Oregon" value="Oregon">Oregon</option>
<option label="Pennsylvania" value="Pennsylvania">Pennsylvania</option>
<option label="Rhode Island" value="Rhode Island">Rhode Island</option>
<option label="South Carolina" value="South Carolina">South Carolina</option>
<option label="South Dakota" value="South Dakota">South Dakota</option>
<option label="Tennessee" value="Tennessee">Tennessee</option>
<option label="Texas" value="Texas">Texas</option>
<option label="Utah" value="Utah">Utah</option>
<option label="Vermont" value="Vermont">Vermont</option>
<option label="Virginia" value="Virginia">Virginia</option>
<option label="Washington" value="Washington">Washington</option>
<option label="West Virginia" value="West Virginia">West Virginia</option>
<option label="Wisconsin" value="Wisconsin">Wisconsin</option>
<option label="Wyoming" value="Wyoming">Wyoming</option>
<option label="Washington DC" value="Washington DC">Washington DC</option>
</select></td></tr></tbody></table></td>
                <td><input type="text" name="reasoning[]" value=""></td>
              </tr>
              <tr>
                <td><input type="text" name="lock_out_name[]" value=""></td>
                <td><input type="text" name="lock_out_practice[]" value=""></td>
                <td><table border="0" class="noborder"><tbody><tr><td><input type="text" name="lock_out_city[]" value="" class="width60"> </td><td><select name="lock_out_city_state[]">
<option label="" value=""></option>
<option label="Alabama" value="Alabama">Alabama</option>
<option label="Alaska" value="Alaska">Alaska</option>
<option label="Arizona" value="Arizona">Arizona</option>
<option label="Arkansas" value="Arkansas">Arkansas</option>
<option label="California" value="California">California</option>
<option label="Colorado" value="Colorado">Colorado</option>
<option label="Connecticut" value="Connecticut">Connecticut</option>
<option label="Delaware" value="Delaware">Delaware</option>
<option label="Florida" value="Florida">Florida</option>
<option label="Georgia" value="Georgia">Georgia</option>
<option label="Hawaii" value="Hawaii">Hawaii</option>
<option label="Idaho" value="Idaho">Idaho</option>
<option label="Illinois" value="Illinois">Illinois</option>
<option label="Indiana" value="Indiana">Indiana</option>
<option label="Iowa" value="Iowa">Iowa</option>
<option label="Kansas" value="Kansas">Kansas</option>
<option label="Kentucky" value="Kentucky">Kentucky</option>
<option label="Louisiana" value="Louisiana">Louisiana</option>
<option label="Maine" value="Maine">Maine</option>
<option label="Maryland" value="Maryland">Maryland</option>
<option label="Massachusetts" value="Massachusetts">Massachusetts</option>
<option label="Michigan" value="Michigan">Michigan</option>
<option label="Minnesota" value="Minnesota">Minnesota</option>
<option label="Mississippi" value="Mississippi">Mississippi</option>
<option label="Missouri" value="Missouri">Missouri</option>
<option label="Montana" value="Montana">Montana</option>
<option label="Nevada" value="Nevada">Nevada</option>
<option label="Nebraska" value="Nebraska">Nebraska</option>
<option label="New Hampshire" value="New Hampshire">New Hampshire</option>
<option label="New Jersey" value="New Jersey">New Jersey</option>
<option label="New Mexico" value="New Mexico">New Mexico</option>
<option label="New York" value="New York">New York</option>
<option label="North Carolina" value="North Carolina">North Carolina</option>
<option label="North Dakota" value="North Dakota">North Dakota</option>
<option label="Ohio" value="Ohio">Ohio</option>
<option label="Oklahoma" value="Oklahoma">Oklahoma</option>
<option label="Oregon" value="Oregon">Oregon</option>
<option label="Pennsylvania" value="Pennsylvania">Pennsylvania</option>
<option label="Rhode Island" value="Rhode Island">Rhode Island</option>
<option label="South Carolina" value="South Carolina">South Carolina</option>
<option label="South Dakota" value="South Dakota">South Dakota</option>
<option label="Tennessee" value="Tennessee">Tennessee</option>
<option label="Texas" value="Texas">Texas</option>
<option label="Utah" value="Utah">Utah</option>
<option label="Vermont" value="Vermont">Vermont</option>
<option label="Virginia" value="Virginia">Virginia</option>
<option label="Washington" value="Washington">Washington</option>
<option label="West Virginia" value="West Virginia">West Virginia</option>
<option label="Wisconsin" value="Wisconsin">Wisconsin</option>
<option label="Wyoming" value="Wyoming">Wyoming</option>
<option label="Washington DC" value="Washington DC">Washington DC</option>
</select></td></tr></tbody></table></td>
                <td><input type="text" name="reasoning[]" value=""></td>
              </tr>
              <tr>
                <td><input type="text" name="lock_out_name[]" value=""></td>
                <td><input type="text" name="lock_out_practice[]" value=""></td>
                <td><table border="0" class="noborder"><tbody><tr><td><input type="text" name="lock_out_city[]" value="" class="width60"> </td><td><select name="lock_out_city_state[]">
<option label="" value=""></option>
<option label="Alabama" value="Alabama">Alabama</option>
<option label="Alaska" value="Alaska">Alaska</option>
<option label="Arizona" value="Arizona">Arizona</option>
<option label="Arkansas" value="Arkansas">Arkansas</option>
<option label="California" value="California">California</option>
<option label="Colorado" value="Colorado">Colorado</option>
<option label="Connecticut" value="Connecticut">Connecticut</option>
<option label="Delaware" value="Delaware">Delaware</option>
<option label="Florida" value="Florida">Florida</option>
<option label="Georgia" value="Georgia">Georgia</option>
<option label="Hawaii" value="Hawaii">Hawaii</option>
<option label="Idaho" value="Idaho">Idaho</option>
<option label="Illinois" value="Illinois">Illinois</option>
<option label="Indiana" value="Indiana">Indiana</option>
<option label="Iowa" value="Iowa">Iowa</option>
<option label="Kansas" value="Kansas">Kansas</option>
<option label="Kentucky" value="Kentucky">Kentucky</option>
<option label="Louisiana" value="Louisiana">Louisiana</option>
<option label="Maine" value="Maine">Maine</option>
<option label="Maryland" value="Maryland">Maryland</option>
<option label="Massachusetts" value="Massachusetts">Massachusetts</option>
<option label="Michigan" value="Michigan">Michigan</option>
<option label="Minnesota" value="Minnesota">Minnesota</option>
<option label="Mississippi" value="Mississippi">Mississippi</option>
<option label="Missouri" value="Missouri">Missouri</option>
<option label="Montana" value="Montana">Montana</option>
<option label="Nevada" value="Nevada">Nevada</option>
<option label="Nebraska" value="Nebraska">Nebraska</option>
<option label="New Hampshire" value="New Hampshire">New Hampshire</option>
<option label="New Jersey" value="New Jersey">New Jersey</option>
<option label="New Mexico" value="New Mexico">New Mexico</option>
<option label="New York" value="New York">New York</option>
<option label="North Carolina" value="North Carolina">North Carolina</option>
<option label="North Dakota" value="North Dakota">North Dakota</option>
<option label="Ohio" value="Ohio">Ohio</option>
<option label="Oklahoma" value="Oklahoma">Oklahoma</option>
<option label="Oregon" value="Oregon">Oregon</option>
<option label="Pennsylvania" value="Pennsylvania">Pennsylvania</option>
<option label="Rhode Island" value="Rhode Island">Rhode Island</option>
<option label="South Carolina" value="South Carolina">South Carolina</option>
<option label="South Dakota" value="South Dakota">South Dakota</option>
<option label="Tennessee" value="Tennessee">Tennessee</option>
<option label="Texas" value="Texas">Texas</option>
<option label="Utah" value="Utah">Utah</option>
<option label="Vermont" value="Vermont">Vermont</option>
<option label="Virginia" value="Virginia">Virginia</option>
<option label="Washington" value="Washington">Washington</option>
<option label="West Virginia" value="West Virginia">West Virginia</option>
<option label="Wisconsin" value="Wisconsin">Wisconsin</option>
<option label="Wyoming" value="Wyoming">Wyoming</option>
<option label="Washington DC" value="Washington DC">Washington DC</option>
</select></td></tr></tbody></table></td>
                <td><input type="text" name="reasoning[]" value=""></td>
              </tr>
              <tr>
                <td><input type="text" name="lock_out_name[]" value=""></td>
                <td><input type="text" name="lock_out_practice[]" value=""></td>
                <td><table border="0" class="noborder"><tbody><tr><td><input type="text" name="lock_out_city[]" value="" class="width60"> </td><td><select name="lock_out_city_state[]">
<option label="" value=""></option>
<option label="Alabama" value="Alabama">Alabama</option>
<option label="Alaska" value="Alaska">Alaska</option>
<option label="Arizona" value="Arizona">Arizona</option>
<option label="Arkansas" value="Arkansas">Arkansas</option>
<option label="California" value="California">California</option>
<option label="Colorado" value="Colorado">Colorado</option>
<option label="Connecticut" value="Connecticut">Connecticut</option>
<option label="Delaware" value="Delaware">Delaware</option>
<option label="Florida" value="Florida">Florida</option>
<option label="Georgia" value="Georgia">Georgia</option>
<option label="Hawaii" value="Hawaii">Hawaii</option>
<option label="Idaho" value="Idaho">Idaho</option>
<option label="Illinois" value="Illinois">Illinois</option>
<option label="Indiana" value="Indiana">Indiana</option>
<option label="Iowa" value="Iowa">Iowa</option>
<option label="Kansas" value="Kansas">Kansas</option>
<option label="Kentucky" value="Kentucky">Kentucky</option>
<option label="Louisiana" value="Louisiana">Louisiana</option>
<option label="Maine" value="Maine">Maine</option>
<option label="Maryland" value="Maryland">Maryland</option>
<option label="Massachusetts" value="Massachusetts">Massachusetts</option>
<option label="Michigan" value="Michigan">Michigan</option>
<option label="Minnesota" value="Minnesota">Minnesota</option>
<option label="Mississippi" value="Mississippi">Mississippi</option>
<option label="Missouri" value="Missouri">Missouri</option>
<option label="Montana" value="Montana">Montana</option>
<option label="Nevada" value="Nevada">Nevada</option>
<option label="Nebraska" value="Nebraska">Nebraska</option>
<option label="New Hampshire" value="New Hampshire">New Hampshire</option>
<option label="New Jersey" value="New Jersey">New Jersey</option>
<option label="New Mexico" value="New Mexico">New Mexico</option>
<option label="New York" value="New York">New York</option>
<option label="North Carolina" value="North Carolina">North Carolina</option>
<option label="North Dakota" value="North Dakota">North Dakota</option>
<option label="Ohio" value="Ohio">Ohio</option>
<option label="Oklahoma" value="Oklahoma">Oklahoma</option>
<option label="Oregon" value="Oregon">Oregon</option>
<option label="Pennsylvania" value="Pennsylvania">Pennsylvania</option>
<option label="Rhode Island" value="Rhode Island">Rhode Island</option>
<option label="South Carolina" value="South Carolina">South Carolina</option>
<option label="South Dakota" value="South Dakota">South Dakota</option>
<option label="Tennessee" value="Tennessee">Tennessee</option>
<option label="Texas" value="Texas">Texas</option>
<option label="Utah" value="Utah">Utah</option>
<option label="Vermont" value="Vermont">Vermont</option>
<option label="Virginia" value="Virginia">Virginia</option>
<option label="Washington" value="Washington">Washington</option>
<option label="West Virginia" value="West Virginia">West Virginia</option>
<option label="Wisconsin" value="Wisconsin">Wisconsin</option>
<option label="Wyoming" value="Wyoming">Wyoming</option>
<option label="Washington DC" value="Washington DC">Washington DC</option>
</select></td></tr></tbody></table></td>
                <td><input type="text" name="reasoning[]" value=""></td>
              </tr>
            </tbody></table>
          </div>
        </div>

        <input type="submit" value="Submit">
      </form>
        
    <br><br>

    
    <style type="text/css">
      table.t1{
          border:1px solid #cad9ea;
          color:#666;
          border-collapse: collapse;
          line-height:18px;
      }
      table.t1 th {
          background-repeat:repeat-x;
          height:30px;
      }
      table.t1 td,table.t1 th{
          border:1px solid #cad9ea;
          padding:2px 1em 2px;
      }
      table.t1 tr.a1{
          background-color:#f5fafe;
      }
      table.noborder td{
        border:0;
        padding:0;
      }
      .width60{
        width:60px;
      }
      .width80{
        width:80px;
      }
      input, textarea{
        font-family: verdana, arial;
      }
      .messages{
        clear:left;
        color:red;
        font-weight:bold;
      }
      div{
        line-height: 22px;
      }
    </style>
  
    
     
<div style="border-bottom:1px solid #3D77AE;"></div>



</div>
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('example_001.pdf', 'F');

//============================================================+
// END OF FILE
//============================================================+
