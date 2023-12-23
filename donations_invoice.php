<?php
    $hooks_dir = dirname(__FILE__);
     include("$hooks_dir/lib.php");
     include_once("$hooks_dir/header.php");
 
    /* grant access to all users who have access to the Supporters table */
    $Supporters_from = get_sql_from('Supporters');
    if(!$Supporters_from) exit(error_message('Access denied!', false));

/* get donations */
$SupporterID = intval($_REQUEST['SupporterID']);
if(!$SupporterID) exit(error_message('Invalid SupporterID!', false));

/* retrieve supporter info */
$supporter_fields = get_sql_fields('Supporters');
$res = sql("select {$supporter_fields} from {$Supporters_from} and ID={$SupporterID}", $eo);
if(!($supporter = db_fetch_assoc($res))) exit(error_message('Supporter Not Found!', false));
$address1 = $supporter['Address1'];
$address2 = $supporter['Address2'];
$newAddress = $address1 . "\\n" . $address2;
if($supporter['Business'] !=NULL){
    $address_name = $supporter['Business'];
} else {$address_name = $supporter['MailingNameFull'];

};

//var_dump($supporter);

/* retrieve donations */
$donation_total = 0;
$Year = $_REQUEST['DonationYear'];
$items = array();
$donation_fields = get_sql_fields('Donations');
$donation_from = get_sql_from('Donations');
$res = sql("select {$donation_fields} from {$donation_from} and Donations.SupporterID={$SupporterID} and Donations.DonationYear={$Year}",$eo);
while($row = db_fetch_assoc($res)){
    $items[] = $row;
    $donation_total += str_replace(array('$', ','), array('', ''), $row['Amount']);

}

//var_dump($items);

?>
<!-- non-printable buttons for printing and closing invoice -->
<div class="btn-group hidden-print pull-right">
        <button type="button" class="btn btn-default btn-lg" onclick="history.back();">
			<i class="glyphicon glyphicon-chevron-left"></i> <?php echo html_attr($Translation['Back']); ?>
		<button type="button" class="btn btn-primary btn-lg" onclick="window.print();">
			<i class="glyphicon glyphicon-print"></i> <?php echo $Translation['Print']; ?>
		</button>
	</div>
	<div class="clearfix"></div>
<!-- end of buttons -->

<div class="container">
    <div class="page-header">
        <div class="row">
        <div class="col-md-12">
            <div class="col-md-4 text-center">
            <img src="images/cmri.jpg">
            </div>
            <div class="col-md-4 text-center">
            <!-- company Info -->
            <h4><b>Children's Ministry Resources International</b></h4>
            <h5>13501 NE 12th Pl,<br>Bellevue, WA 98005</h5>
     <hr>
            <h5><b>Year-End Contribution Statement</b> - <?php echo $Year; ?></h5>
            </div>
        </div>
        </div>
    </div>


    <div class="row col-md-10 col-md-offset-1">
            <div class="col-md-5 text-left">
            <h4><b><?php echo $address_name; ?></b></h4>
            <h5><?php if($address2 !=NULL){
                    $newAddress=$address1 ." ". $address2;
                } else {$newAddress=$address1;}; echo $newAddress; ?>
            <br><?php if($address1 ==NULL){
                echo $address1;
            } else {echo $supporter['City']; ?>, <?php echo $supporter['State']; ?> <?php echo $supporter['Zip'];} ?></h5>
            </div>

    </div>
</div>

<hr>

<!-- donations -->
<table class="table table-striped table-bordered">
    <thead>
        <th class="text-center">ID</th>
        <th>Donation Date</th>
        <th>Description</th>
        <th>Campaign</th>
        <th>Payment Method</th>
        <th>Number</th>
        <th>Amount</th>
    </thead>


    <tbody>
        <?php foreach($items as $i => $item){ ?>
            <tr>
                <td class="text-center"><?php echo $item['ID']; ?></td>
                <td><?php echo $item['DonationDate']; ?></td>
                <td><?php echo $item['Description']; ?></td>
                <td><?php echo $item['CampaignID']; ?></td>
                <td><?php echo $item['Paytype']; ?></td>
                <td><?php echo $item['Number']; ?></td>
                <td class="text-right"><?php echo $item['Amount']; ?></td>
            </tr>
        <?php } ?>
    </tbody>

    <tfoot>
        <tr>
            <td colspan="6" class="text-right"><?php echo $Year; ?> Total Donation</td>
            <td class="text-right">$<?php echo number_format($donation_total, 2); ?></td>
        </tr>
    </tfoot>
</table>

<hr>
<div class="container">
    <div class="panel panel-body">
        <div class="row">
            <div class="text-center">
            <p>Children’s Ministry Resources International (CMRI), a nonprofit 501 (c) (3) organization, received no goods or
 services in exchange for this donation. EIN: 68-0673591</p>
            </div>        
        </div>
        <div class="row">
            <div class="text-center">
            <p>This is the official receipt for tax purposes. Please retain for your tax records.</p>
            </div>
        </div>
    </div>
</div>
<?php

    include_once("$hooks_dir/footer.php");
?>