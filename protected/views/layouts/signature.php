<tr>
  <td colspan="2" id="sigPad_<?php echo $field->name; ?>">
    <div style="width:300px;">
      <label for="name"><b>Print your name</b></label>
      <input type="text" name="signature_name_<?php echo $field->name; ?>" id="signature_name" class="signature_name signature_input" value="<?php echo $formNode->{'signature_name_'.$field->name}; ?>"/>
      <p class="drawItDesc">Draw your signature</p>
      <ul class="sigNav">
        <li class="drawIt"><a href="#draw-it">Draw It</a></li>
        <li class="clearButton"><a href="#clear">Clear</a></li>
      </ul>
      <div class="sig sigWrapper">
        <div class="typed"></div>
        <canvas class="pad" width="298" height="80"></canvas>
        <input type="hidden" name="signature_draw_<?php echo $field->name; ?>" class="signature_draw">
      </div>
    </div>
    
    <script type="text/javascript">
    $(document).ready(function () {
      $sigHandle = $('#sigPad_<?php echo $field->name; ?>').signaturePad({
        name : '.signature_name',
        output : '.signature_draw',
        drawOnly : true,
        errorMessageDraw : 'Please sign the form'
      });

      <?php if($formNode->{'signature_draw_'.$field->name}){ ?>
      $sigHandle.regenerate('<?php echo $formNode->{'signature_draw_'.$field->name}; ?>');
      <?php } ?>
    });
    </script>
  </td>
</tr>
