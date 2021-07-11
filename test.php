<?php

require('dapi.php');

$userSecret = 'KQH8oHLFda4ZbV/hyygu2RTBPmUd6kyV0UVUQFSs40l25Pm3Fe133JkGCr1K8DMCdeV+lQ/gHOzffrcFj7h6xDMyDlZ9Ff2aXhGpsjrIURIzTaDa9YWGrW2MLk+aS2tnliM7wXlsyMwUB3DiDJl8+SS9sPEQuuDVyAo0a20T4DZr/+ysMHY+bPSHm1xAo+F+vBz3s4CFS/NioIS7bSJqEI2HGsbtQzQnmeqs92z2cP/6s/xBm6KGiAy0A8/v7Wk3gVXmiKK1Powbux9QH3uiYId0i+pRMYYAUhEVRE25Z/boe66Thg0Pd10rsybJBnQNXyoL/Tqrh+HYJiTT4az8lvqxRcL3BHy1VedOjQODuBBB+sHinvxpUTfZcMyULvMb31q1YAcfZQt5ibnhH0uOURq2ZgkmC0PhleIFbM8/+X3IYDEO3kNxT0s0htnrwPZiYcBnn4YSsDZ9IAghcdDAbT+AChLZTavWN6EeRZOZTz/LUlhfViXcue4rXOYvswhCKw4LGRt4C6SaK9mmuZYjYN4ce1p7O0VI3cxyt4r7er3kdZN/hwCAMbOGG7D+vT6ZF6GQblAK07x+wwKXn8LdPX2daUCNZy5OwpXZKyJvVUbG6G5STqbeb+IXuiCi/qsPrppqAg3HajrMFoiCt6MAZfVX+6sBaPe/rHp+hbC0GLU=';
$accessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcHBLZXkiOiJiY2JmZjJhOGQ5MDk3MDJlYTY2YmM2ZGFlZWZlZjBlNTFiYWQ0OTFmODAzOWZkZTIzMzU3ZTc0ZDJkMDAwMjYyIiwiYXV0aG9yaXphdGlvbnMiOiJ7XCJhdXRoXCI6dHJ1ZSxcImlkXCI6dHJ1ZSxcImFjY291bnRzXCI6dHJ1ZSxcImJhbGFuY2VcIjp0cnVlLFwidHJzXCI6dHJ1ZSxcImJlbmVmXCI6dHJ1ZSxcImJlbmVmQ3JlYXRlXCI6dHJ1ZSxcInRyYW5zZmVyXCI6dHJ1ZSxcImFjY1R5cGVzXCI6W1wicmV0YWlsXCJdLFwiY291bnRyaWVzXCI6W1wiQUVcIl19IiwiaWF0IjoxNjI1NzI5MDk5LCJqdGkiOiIxYzFhZWFhZi05ZDFmLTQyMDEtOWM0NC04MWZjOTVlMzdhYjIiLCJ1dWlkIjoiREFQSUJBTktfQUVfTElWOmRhODA3NGRhLTFiOGItNDk1Ny04NTA1LWJhZGU0OTY3YmIzYyJ9.JbJ8FBKrYpWHX-GthdjvMwZ0p3B5YAvZm-EE1DiiPIA';
$dapiClient = new DapiClient('b6abdc363bfb141b6045fe9c007715703174bf8b4b2100e2b676c88b7136b299');

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

$metadata = $dapiClient->metadata->getAccountsMetadata($accessToken, $userSecret); 
echo PHP_EOL . 'Accounts Metadata!' . PHP_EOL . PHP_EOL;
echo (json_encode($transactions, JSON_PRETTY_PRINT));

