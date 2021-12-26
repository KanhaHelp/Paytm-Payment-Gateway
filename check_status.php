<?php
	header("Pragma: no-cache");
	header("Cache-Control: no-cache");
	header("Expires: 0");

	// following files need to be included
	require_once("./lib/paytm_config.php");
	require_once("./lib/paytm_api.php");

	$ORDER_ID = "";
	$requestParamList = array();
	$responseParamList = array();

	if (isset($_POST["ORDER_ID"]) && $_POST["ORDER_ID"] != "") {

		// In Test Page, we are taking parameters from POST request. In actual implementation these can be collected from session or DB. 
		$ORDER_ID = $_POST["ORDER_ID"];

		// Create an array having all required parameters for status query.
		$requestParamList = array("MID" => PAYTM_MERCHANT_MID , "ORDERID" => $ORDER_ID);  
		
		$StatusCheckSum = getChecksumFromArray($requestParamList,PAYTM_MERCHANT_KEY);
		
		$requestParamList['CHECKSUMHASH'] = $StatusCheckSum;

		// Call the PG's getTxnStatusNew() function for verifying the transaction status.
		$responseParamList = getTxnStatusNew($requestParamList);
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>

    <title>Payment Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body>
    <h2 class="text-center">Payment Status</h2>
    <form method="post" action="">
        <table class="table table-striped table-hover mt-2">
            <tbody>
                <tr>
                    <td><label>ORDER_ID::*</label></td>
                    <td><input id="ORDER_ID" tabindex="1" maxlength="20" size="20" name="ORDER_ID" autocomplete="off"
                            value="<?php echo $ORDER_ID ?>">


                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td> <input value="Check Status" type="submit" class="btn btn-success" onclick=""></td>
                </tr>
            </tbody>
        </table>
        <br /></br />
        <?php
		if (isset($responseParamList) && count($responseParamList)>0 )
		{ 
		?>
        <h2 class="text-center">Order Status : </h2>
        <div class="m-2">

            <table class="table table-striped table-hover mt-2">
                <tbody>
                    <?php
					foreach($responseParamList as $paramName => $paramValue) {
				?>
                    <tr>
                        <td style="border: 1px solid"><label><?php echo $paramName?></label></td>
                        <td style="border: 1px solid"><?php echo $paramValue?></td>
                    </tr>
                    <?php
					}
				?>
                </tbody>
            </table>
        </div>

        <?php
		}
		?>
    </form>
</body>

</html>