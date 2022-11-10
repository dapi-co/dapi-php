<?php

require('dapi.php');

$userSecret = 'USER_SECRET';
$accessToken = 'ACCESS_TOKEN';
$dapiClient = new DapiClient('APP_SECRET');

$identity = $dapiClient->data->getIdentity($accessToken, $userSecret);

echo (json_encode($identity, JSON_PRETTY_PRINT) . "\n");

$accounts = $dapiClient->data->getAccounts($accessToken, $userSecret);
echo PHP_EOL . 'Accounts!' . PHP_EOL . PHP_EOL;
echo (json_encode($accounts, JSON_PRETTY_PRINT));

$accountID = $accounts['accounts'][0]['id'];
echo PHP_EOL . "accountID: " . $accountID; 

$balance = $dapiClient->data->getBalance($accessToken, $userSecret, $accountID); 
echo PHP_EOL . 'Balance!' . PHP_EOL . PHP_EOL;
echo (json_encode($balance, JSON_PRETTY_PRINT));

$transactions = $dapiClient->data->getTransactions($accessToken, $userSecret, $accountID, "2021-02-14", "2021-05-11"); 
echo PHP_EOL . 'Transactions!' . PHP_EOL . PHP_EOL;
echo (json_encode($transactions, JSON_PRETTY_PRINT));

$categorizedTransactions = $dapiClient->data->getCategorizedTransactions($accessToken, $userSecret, $accountID, "2021-02-14", "2021-05-11"); 
echo PHP_EOL . 'Categorized Transactions!' . PHP_EOL . PHP_EOL;
echo (json_encode($categorizedTransactions, JSON_PRETTY_PRINT));

$enrichedTransactions = $dapiClient->data->getEnrichedTransactions($accessToken, $userSecret, $accountID, "2021-02-14", "2021-05-11"); 
echo PHP_EOL . 'Enriched Transactions!' . PHP_EOL . PHP_EOL;
echo (json_encode($enrichedTransactions, JSON_PRETTY_PRINT));

$metadata = $dapiClient->metadata->getAccountsMetadata($accessToken, $userSecret); 
echo PHP_EOL . 'Accounts Metadata!' . PHP_EOL . PHP_EOL;
echo (json_encode($transactions, JSON_PRETTY_PRINT));

