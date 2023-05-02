<?php 
error_reporting(1);

echo "Salt:".$salt = '5b30e70d63752e6b73b76d864b665e6ad7f904f2'; //Pass your SALT here
echo "<br>";
echo "API:".$api_key='059e85c9-7e53-4b96-85d7-da926a7d9023';

$_POST['api_key'] = '059e85c9-7e53-4b96-85d7-da926a7d9023'; 
$_POST['merchant_reference_number'] ='1204202201093030';
$_POST['amount'] ="100";
$_POST['account_name'] = '1000';
$_POST['account_number'] = '1000';
$_POST['ifsc_code'] = 'BARB0SECTOC';
$_POST['bank_name'] ="1000"; 
$_POST['transfer_type']="IMPS";



//Pass your API KEY here
echo "<br>";
echo "hash:".$hash = hashCalculate($salt, $_POST);
echo "<br>";
echo "<pre>";
print_r($_POST);

function hashCalculate($salt,$input) {

    /* Columns used for hash calculation, Donot add or remove values from $hash_columns array */

    $hash_columns = ['api_key','merchant_reference_number','amount','account_name','account_number','ifsc_code','bank_name','transfer_type','bank_branch','upi_id',];

    sort($hash_columns);
    $hash_data = $salt;
    foreach ($hash_columns as $column) {
        if (isset($input[$column])) {
            if (strlen($input[$column]) > 0) {
                $hash_data .= '|' . trim($input[$column]);
            }
        }
    }

    $hash = strtoupper(hash("sha512", $hash_data));
    return $hash;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Test Payout</title>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.4.1.js"></script> 
</head>
<body>
<div class="container">
<div class="row">
<div class="col-lg-8 col-offset-2">
<div class="page-header">
<h2>Testing For Payout</h2>
</div>
<span id="error" style="display: none"></span>
<form action="javascript:void(0)" method="post" id="ajax-form">
<div class="form-group">
<input type="text" name="hash" style="width: 100%" value="<?php echo $hash; ?>"><br>
    <input type="text" style="width: 100%" value="<?php echo $_POST['api_key'];?>" name="api_key"/><br>
    <input type="text" style="width: 100%" value="<?php echo $_POST['merchant_reference_number'];?>" name="merchant_reference_number"/><br>
    <input type="text" style="width: 100%" value="<?php echo $_POST['amount'];?>" name="amount"/><br>
    <input type="text" style="width: 100%" value="<?php echo $_POST['account_name'];?>" name="account_name"/><br>
    <input type="text" style="width: 100%" value="<?php echo $_POST['account_number'];?>" name="account_number"/><br>
    <input type="text" style="width: 100%" value="<?php echo $_POST['ifsc_code'];?>" name="ifsc_code"/><br>
    <input type="text" style="width: 100%" value="<?php echo $_POST['bank_name'];?>" name="bank_name"/><br>
    <input type="text" style="width: 100%" value="<?php echo $_POST['transfer_type'];?>" name="transfer_type"/><br><br>
<cente style="width: 100;" >
<input type="submit" class="btn btn-primary" name="submit" value="submit">
</cente>
</form>
</div>
</div>    
</div>
<script type="text/javascript">
$(document).ready(function($){
// hide messages 
$("#error").hide();
// on submit...
$('#ajax-form').submit(function(e){
e.preventDefault();
$("#error").hide();


// ajax
$.ajax({
type:"POST",
url: "https://biz.payflash.in/v3/fundtransfer",
data: $(this).serialize(), // get all form field value in serialize form

success: function (data) {
                $("#output").text(data);
                console.log("SUCCESS : ", data);
                $("#btnSubmit").prop("disabled", false);
 
            },
 error: function (e) {
 
                $("#output").text(e.responseText);
                console.log("ERROR : ", e);
                $("#btnSubmit").prop("disabled", false);
 
            }
});
});  
return false;
});
</script>
</body>
</html>