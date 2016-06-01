<?php $this->renderFile('protected/views/layouts/header_subpanel.php', array('title'=>'Control Panel')); ?>

<?php // echo $company_id;exit;?>
<h1>Dropdown list</h1>

<h2>Contact</h2>
<a href="/admin/dropdown/op/update/module/contact/dropdownname/relationship/label/<?php echo urlencode('Relationship');?>">Relationship</a>

<h2>Task</h2>
<a href="/admin/dropdown/op/update/module/task/dropdownname/status/label/<?php echo urlencode('Status');?>">Status</a>

<h2>Customer</h2>
<a href="/admin/dropdown/op/update/module/customer/dropdownname/skfhfuneralhome/label/<?php echo urlencode('Location');?>">Location</a><br/><br/>
<a href="/admin/dropdown/op/update/module/customer/dropdownname/locationfuneralservice/label/<?php echo urlencode('Location Of Funeral Service');?>">Location Of Funeral Service</a><br/><br/>
<a href="/admin/dropdown/op/update/module/customer/dropdownname/locationvisitation/label/<?php echo urlencode('Location Of Visitation');?>">Location Of Visitation</a><br/><br/>
<a href="/admin/dropdown/op/update/module/customer/dropdownname/specialrites/label/<?php echo urlencode('Special Rites');?>">Special Rites</a><br/><br/>
<a href="/admin/dropdown/op/update/module/customer/dropdownname/highestleveleducation/label/<?php echo urlencode('Highest Level Of Education');?>">Highest Level Of Education</a><br/><br/>
<a href="/admin/dropdown/op/update/module/customer/dropdownname/maritalstatus/label/<?php echo urlencode('Marital Status');?>">Marital Status</a><br/><br/>
<a href="/admin/dropdown/op/update/module/customer/dropdownname/newspaperradio1/label/<?php echo urlencode('Newspaper####Radio 1');?>">Newspaper/Radio 1</a><br/><br/>
<a href="/admin/dropdown/op/update/module/customer/dropdownname/newspaperradio2/label/<?php echo urlencode('Newspaper####Radio 2');?>">Newspaper/Radio 2</a><br/><br/>
<a href="/admin/dropdown/op/update/module/customer/dropdownname/newspaperradio3/label/<?php echo urlencode('Newspaper####Radio 3');?>">Newspaper/Radio 3</a><br/><br/>
<a href="/admin/dropdown/op/update/module/customer/dropdownname/newspaperradio4/label/<?php echo urlencode('Newspaper####Radio 4');?>">Newspaper/Radio 4</a><br/><br/>
<a href="/admin/dropdown/op/update/module/customer/dropdownname/newspaperradio5/label/<?php echo urlencode('Newspaper####Radio 5');?>">Newspaper/Radio 5</a><br/><br/>
<a href="/admin/dropdown/op/update/module/customer/dropdownname/newspaperradio6/label/<?php echo urlencode('Newspaper####Radio 6');?>">Newspaper/Radio 6</a><br/><br/>
<a href="/admin/dropdown/op/update/module/customer/dropdownname/submitpicwithobit/label/<?php echo urlencode('Submit Pic With Obit');?>">Submit Pic With Obit</a><br/><br/>
<a href="/admin/dropdown/op/update/module/customer/dropdownname/militaryveteran/label/<?php echo urlencode('Military Veteran');?>">Military Veteran</a><br/><br/>
<a href="/admin/dropdown/op/update/module/customer/dropdownname/status/label/<?php echo urlencode('Status');?>">Status</a><br/><br/>

<?php $this->renderFile('protected/views/layouts/footer_subpanel.php'); ?>