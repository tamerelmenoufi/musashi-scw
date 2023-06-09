<?php
require_once "../lib/maxipago/Autoload.php"; // Remove if using a globa autoloader
require_once "../lib/maxiPago.php";

try {

    $maxiPago = new maxiPago;

    // Before calling any other methods you must first set your credentials
    // Define Logger parameters if preferred
    // Do *NOT* use 'DEBUG' for Production environment as Credit Card details WILL BE LOGGED
    // Severities INFO and up are safe to use in Production as Credi Card info are NOT logged
    $maxiPago->setLogger(dirname(__FILE__).'/logs','INFO');
    
    // Set your credentials before any other transaction methods
    $maxiPago->setCredentials("100", "merchant_key");

    $maxiPago->setDebug(true);
    $maxiPago->setEnvironment("TEST");
    $data = array(
        "transactionID" => "421365", // REQUIRED - TransactionID created by maxiPago! //
    );
    $maxiPago->pullReport($data);

    if ($maxiPago->isErrorResponse()) {
        echo "Request has failed<br>Error message: ".$maxiPago->getMessage();
    }
    
    else {
        if ($maxiPago->getTotalNumberOfRecords() > 0) { print_r($maxiPago->getReportResult()); }
        else { echo "Query was executed sucessfully but no transactions were found."; }
    }

}

catch (Exception $e) { echo $e->getMessage()." in ".$e->getFile()." on line ".$e->getLine(); }
?>
