# dapi-php

A PHP library that talks to the [Dapi](https://dapi.co) [API](https://api.dapi.co).

## Quickstart

### Configure Project

First add the library module to your project using composer. Add the following in your `composer.json`

```
"require": {
    "dapi-co/dapi-php": "1.0.0",
},
```

### Configure Library

1. Create a DapiClient with your App Secret. 

```php

$dapiClient = new DapiPhp\DapiClient('APP_SECRET');
```

2. Now you can use any of the functions of the products available on the client (`data` for example) instance to call Dapi with your `appSecret`.

```php 
$accessToken = 'ACCESS_TOKEN'; 
$userSecret = 'USER_SECRET'; 
$accounts = $dapiClient->data->getAccounts($accessToken, $userSecret);
echo PHP_EOL . 'Accounts' . PHP_EOL . PHP_EOL;
echo (json_encode($accounts, JSON_PRETTY_PRINT));
```

3. Or, you can use the `handleSDKRequests` function of the client instance inside an endpoint in your server. Our code will basically update the request to add your app's `appSecret` to it, and forward the request to Dapi, then return the result.

```php
<?php

// Assuming that Dapi library is already autoloaded. If not, manually include/require it here. 

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Initialize DapiClient with your appSecret here
$dapiClient = new DapiClient('APP_SECRET');

$headers = getallheaders();
$body = json_decode(file_get_contents("php://input"), true);

// Make dapiClient automatically handle your SDK requests
if (!empty($body)) {
  echo json_encode($dapiClient->handleSDKRequests($body, $headers)); 
} else {
  http_response_code(400);
  echo "Bad Request: No data sent or wrong request";
}
```

## Reference

### BaseResponse

All the responses have the fields described here. Meaning all the responses described below in the document will have following fields besides the ones specific to each response.

| Parameter | Type | Description |
|---|---|---|
| operationID | `string` | Unique ID generated to identify a specific operation. |
| success | `boolean` | Returns true if request is successful and false otherwise. |
| status | `string` | The status of the job. <br><br> `done` - Operation Completed. <br> `failed` - Operation Failed. <br> `user_input_required` - Pending User Input. <br> `initialized` - Operation In Progress. <br><br> For further explanation see [Operation Statuses](https://dapi-api.readme.io/docs/operation-statuses). |
| userInputs | `array` | Array of `userInput` objects, that are needed to complete this operation. <br><br> Specifies the type of further information required from the user before the job can be completed. <br><br> Note: It's only returned if operation status is `user_input_required` |
| type | `string` | Type of error encountered. <br><br> Note: It's only returned if operation status is `failed` |
| msg | `string` | Detailed description of the error. <br><br> Note: It's only returned if operation status is `failed` |
#### userInput Object

| Parameter | Type                 | Description                                                                                                                                        |
| --------- | -------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------- |
| id        | `string` | Type of input required. <br><br> You can read more about user input types on [User Input Types](https://dapi-api.readme.io/docs/user-input-types). |
| query     | `string`             | Textual description of what is required from the user side.                                                                                        |
| index     | `int`                | Is used in case more than one user input is requested. <br> Will always be 0 If only one input is requested.                                       |
| answer    | `string`             | User input that must be submitted. In the response it will always be empty.                                                                        |

### Methods

#### auth->exchangeToken

Method is used to obtain user's permanent access token by exchanging it with access code received during the user authentication (user login).

##### Note:

You can read more about how to obtain a permanent token on [Obtain an Access Token](https://dapi-api.readme.io/docs/get-an-access-token).

##### Method Description

```php
function exchangeToken($accessCode, $connectionID)
```

##### Input Parameters

| Parameter                        | Type     | Description                                                                                          |
| -------------------------------- | -------- | ---------------------------------------------------------------------------------------------------- |
| **accessCode** <br> _REQUIRED_   | `string` | Unique code for a user’s successful login to **Connect**. Returned in the response of **UserLogin**. |
| **connectionID** <br> _REQUIRED_ | `string` | The `connectionID` from a user’s successful log in to **Connect**.                                   |

##### Response

In addition to the fields described in the BaseResponse, it has the following fields, which will only be returned if the status is `done`:

| Parameter       | Type     | Description                                  |
| --------------- | -------- | -------------------------------------------- |
| **AccessToken** | `string` | A unique permanent token linked to the user. |

---

#### data->getIdentity

Method is used to retrieve personal details about the user.

##### Method Description

```php
function getIdentity($accessToken, $userSecret, $userInputs = [], $operationID = "")
```

##### Input Parameters

| Parameter                       | Type          | Description                                                                                                                                                                                                                                                                                        |
| ------------------------------- | ------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **accessToken** <br> _REQUIRED_ | `string`      | Access Token obtained using the `ExchangeToken` method.                                                                                                                                                                                                                                            |
| **userSecret** <br> _REQUIRED_  | `string`      | The `userSecret` from a user’s successful log in to **Connect**.                                                                                                                                                                                                                                   |
| **operationID** <br> _OPTIONAL_ | `string`      | The `OperationID` from a previous call's response. <br> Required only when resuming a previous call that responded with `user_input_required` status, to provided user inputs.                                                                                                                     |
| **userInputs** <br> _OPTIONAL_  | `array` | Array of `UserInput` object, that are needed to complete this operation. <br> Required only if a previous call responded with `user_input_required` status. <br><br> You can read more about user inputs specification on [Specify User Input](https://dapi-api.readme.io/docs/specify-user-input) |

###### userInput Object

| Parameter | Type                 | Description                                                                                                                                        |
| --------- | -------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------- |
| Id        | `string` | Type of input required. <br><br> You can read more about user input types on [User Input Types](https://dapi-api.readme.io/docs/user-input-types). |
| Index     | `int`                | Is used in case more than one user input is requested. <br> Will always be 0 If only one input is requested.                                       |
| Answer    | `string`             | User input that must be submitted.                                                                                                                 |

##### Response

In addition to the fields described in the BaseResponse, it has the following fields, which will only be returned if the status is `done`:

| Parameter | Type       | Description                                         |
| --------- | ---------- | --------------------------------------------------- |
| identity  | `array` | An object (associative array) containing the identity data of the user. |

---

#### data->getAccounts

Method is used to retrieve list of all the bank accounts registered on the user. The list will contain all types of bank accounts.

##### Method Description

```php
function getAccounts($accessToken, $userSecret, $userInputs = [], $operationID = "")
```
##### Input Parameters

| Parameter                       | Type          | Description                                                                                                                                                                                                                                                                                        |
| ------------------------------- | ------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **accessToken** <br> _REQUIRED_ | `string`      | Access Token obtained using the `ExchangeToken` method.                                                                                                                                                                                                                                            |
| **userSecret** <br> _REQUIRED_  | `string`      | The `userSecret` from a user’s successful log in to **Connect**.                                                                                                                                                                                                                                   |
| **operationID** <br> _OPTIONAL_ | `string`      | The `OperationID` from a previous call's response. <br> Required only when resuming a previous call that responded with `user_input_required` status, to provided user inputs.                                                                                                                     |
| **userInputs** <br> _OPTIONAL_  | `array` | Array of `UserInput` object, that are needed to complete this operation. <br> Required only if a previous call responded with `user_input_required` status. <br><br> You can read more about user inputs specification on [Specify User Input](https://dapi-api.readme.io/docs/specify-user-input) |

###### userInput Object

| Parameter | Type                 | Description                                                                                                                                        |
| --------- | -------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------- |
| id        | `string` | Type of input required. <br><br> You can read more about user input types on [User Input Types](https://dapi-api.readme.io/docs/user-input-types). |
| index     | `int`                | Is used in case more than one user input is requested. <br> Will always be 0 If only one input is requested.                                       |
| answer    | `string`             | User input that must be submitted.                                                                                                                 |

##### Response

In addition to the fields described in the BaseResponse, it has the following fields, which will only be returned if the status is `done`:

| Parameter | Type        | Description                                        |
| --------- | ----------- | -------------------------------------------------- |
| Accounts  | `array` | An array containing the accounts data of the user. |

---

#### data->getBalance

Method is used to retrieve balance on specific bank account of the user.

##### Method Description

```php
function getBalance($accessToken, $userSecret, $accountID, $userInputs = [], $operationID = "")
```

##### Input Parameters

| Parameter                       | Type          | Description                                                                                                                                                                                                                                                                                        |
| ------------------------------- | ------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **accountID** <br> _REQUIRED_   | `string`      | The bank account ID which its balance is requested. <br> Retrieved from one of the accounts returned from the `GetAccounts` method.                                                                                                                                                                |
| **accessToken** <br> _REQUIRED_ | `string`      | Access Token obtained using the `ExchangeToken` method.                                                                                                                                                                                                                                            |
| **userSecret** <br> _REQUIRED_  | `string`      | The `userSecret` from a user’s successful log in to **Connect**.                                                                                                                                                                                                                                   |
| **operationID** <br> _OPTIONAL_ | `string`      | The `OperationID` from a previous call's response. <br> Required only when resuming a previous call that responded with `user_input_required` status, to provided user inputs.                                                                                                                     |
| **userInputs** <br> _OPTIONAL_  | `array` | Array of `UserInput` object, that are needed to complete this operation. <br> Required only if a previous call responded with `user_input_required` status. <br><br> You can read more about user inputs specification on [Specify User Input](https://dapi-api.readme.io/docs/specify-user-input) |

###### userInput Object

| Parameter | Type                 | Description                                                                                                                                        |
| --------- | -------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------- |
| id        | `string` | Type of input required. <br><br> You can read more about user input types on [User Input Types](https://dapi-api.readme.io/docs/user-input-types). |
| index     | `int`                | Is used in case more than one user input is requested. <br> Will always be 0 If only one input is requested.                                       |
| answer    | `string`             | User input that must be submitted.                                                                                                                 |

##### Response

In addition to the fields described in the BaseResponse, it has the following fields, which will only be valid if the status is `done`:

| Parameter | Type      | Description                                             |
| --------- | --------- | ------------------------------------------------------- |
| Balance   | `Balance` | An object (associative array) containing the account's balance information. |

---

#### data->getTransactions

Method is used to retrieve transactions that user has performed over a specific period of time from their bank account. The transaction list is unfiltered, meaning the response will contain all the transactions performed by the user (not just the transactions performed using your app).

Date range of the transactions that can be retrieved varies for each bank. The range supported by the users bank is shown in the response parameter `transactionRange` of Get Accounts Metadata endpoint.

##### Method Description

```php
function getTransactions($accessToken, $userSecret, $accountID, $fromDate, $toDate, $userInputs = [], $operationID = "")
```

##### Input Parameters

| Parameter                       | Type          | Description                                                                                                                                                                                                                                                                                        |
| ------------------------------- | ------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **accountID** <br> _REQUIRED_   | `string`      | The bank account ID which its transactions are requested. <br> Retrieved from one of the accounts returned from the `getAccounts` method.                                                                                                                                                          |
| **fromDate** <br> _REQUIRED_    | `string`   | The start date of the transactions wanted. <br> It should be in this format: `YYYY-MM-DD`                                                                                                                                                                                                                                                  |
| **toDate** <br> _REQUIRED_      | `string`   | The end date of the transactions wanted. <br> It should be in this format: `YYYY-MM-DD`                                                                                                                                                                                                                                                     |
| **accessToken** <br> _REQUIRED_ | `string`      | Access Token obtained using the `ExchangeToken` method.                                                                                                                                                                                                                                            |
| **userSecret** <br> _REQUIRED_  | `string`      | The `userSecret` from a user’s successful log in to **Connect**.                                                                                                                                                                                                                                   |
| **operationID** <br> _OPTIONAL_ | `string`      | The `OperationID` from a previous call's response. <br> Required only when resuming a previous call that responded with `user_input_required` status, to provided user inputs.                                                                                                                     |
| **userInputs** <br> _OPTIONAL_  | `array` | Array of `UserInput` object, that are needed to complete this operation. <br> Required only if a previous call responded with `user_input_required` status. <br><br> You can read more about user inputs specification on [Specify User Input](https://dapi-api.readme.io/docs/specify-user-input) |

###### userInput Object

| Parameter | Type                 | Description                                                                                                                                        |
| --------- | -------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------- |
| id        | `string` | Type of input required. <br><br> You can read more about user input types on [User Input Types](https://dapi-api.readme.io/docs/user-input-types). |
| index     | `int`                | Is used in case more than one user input is requested. <br> Will always be 0 If only one input is requested.                                       |
| answer    | `string`             | User input that must be submitted.                                                                                                                 |

##### Response

In addition to the fields described in the BaseResponse, it has the following fields, which will only be valid if the status is `done`:

| Parameter    | Type            | Description                                                                                    |
| ------------ | --------------- | ---------------------------------------------------------------------------------------------- |
| Transactions | `array` | Array containing the transactional data for the specified account within the specified period. |

---

#### payment->getBeneficiaries

Method is used to retrieve list of all the beneficiaries already added for a user within a financial institution.

##### Method Description

```php
function getBeneficiaries($accessToken, $userSecret, $userInputs = [], $operationID = "")
```

##### Input Parameters

| Parameter                       | Type          | Description                                                                                                                                                                                                                                                                                        |
| ------------------------------- | ------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **accessToken** <br> _REQUIRED_ | `string`      | Access Token obtained using the `ExchangeToken` method.                                                                                                                                                                                                                                            |
| **userSecret** <br> _REQUIRED_  | `string`      | The `userSecret` from a user’s successful log in to **Connect**.                                                                                                                                                                                                                                   |
| **operationID** <br> _OPTIONAL_ | `string`      | The `OperationID` from a previous call's response. <br> Required only when resuming a previous call that responded with `user_input_required` status, to provided user inputs.                                                                                                                     |
| **userInputs** <br> _OPTIONAL_  | `array` | Array of `UserInput` object, that are needed to complete this operation. <br> Required only if a previous call responded with `user_input_required` status. <br><br> You can read more about user inputs specification on [Specify User Input](https://dapi-api.readme.io/docs/specify-user-input) |

###### userInput Object

| Parameter | Type                 | Description                                                                                                                                        |
| --------- | -------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------- |
| id        | `string` | Type of input required. <br><br> You can read more about user input types on [User Input Types](https://dapi-api.readme.io/docs/user-input-types). |
| index     | `int`                | Is used in case more than one user input is requested. <br> Will always be 0 If only one input is requested.                                       |
| answer    | `string`             | User input that must be submitted.                                                                                                                 |

##### Response

In addition to the fields described in the BaseResponse, it has the following fields, which will only be returned if the status is `done`:

| Parameter     | Type            | Description                                      |
| ------------- | --------------- | ------------------------------------------------ |
| Beneficiaries | `array` | An array containing the beneficiary information. |

---

#### payment->createBeneficiary

Method is used to retrieve list of all the beneficiaries already added for a user within a financial institution.

##### Method Description

```go
function createBeneficiary($accessToken, $userSecret, $beneficiaryData, $userInputs = [], $operationID = "")
```

##### Input Parameters

| Parameter                       | Type                            | Description                                                                                                                                                                                                                                                                                        |
| ------------------------------- | ------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **beneficiaryData** <br> _REQUIRED_ | `array` | An object (associative array) that contains info about the beneficiary that should be added.                                                                                                                                                                                                                           |
| **accessToken** <br> _REQUIRED_ | `string`                        | Access Token obtained using the `ExchangeToken` method.                                                                                                                                                                                                                                            |
| **userSecret** <br> _REQUIRED_  | `string`                        | The `userSecret` from a user’s successful log in to **Connect**.                                                                                                                                                                                                                                   |
| **operationID** <br> _OPTIONAL_ | `string`                        | The `OperationID` from a previous call's response. <br> Required only when resuming a previous call that responded with `user_input_required` status, to provided user inputs.                                                                                                                     |
| **userInputs** <br> _OPTIONAL_  | `array`                   | Array of `UserInput` object, that are needed to complete this operation. <br> Required only if a previous call responded with `user_input_required` status. <br><br> You can read more about user inputs specification on [Specify User Input](https://dapi-api.readme.io/docs/specify-user-input) |

###### userInput Object

| Parameter | Type                 | Description                                                                                                                                        |
| --------- | -------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------- |
| id        | `string` | Type of input required. <br><br> You can read more about user input types on [User Input Types](https://dapi-api.readme.io/docs/user-input-types). |
| index     | `int`                | Is used in case more than one user input is requested. <br> Will always be 0 If only one input is requested.                                       |
| answer    | `string`             | User input that must be submitted.                                                                                                                 |

###### beneficiaryData object

| Parameter                         | Type                              | Description                                                                                                                   |
| --------------------------------- | --------------------------------- | ----------------------------------------------------------------------------------------------------------------------------- |
| **name** <br> _REQUIRED_          | `string`                          | Name of the beneficiary.                                                                                                      |
| **accountNumber** <br> _REQUIRED_ | `string`                          | Account number of the beneficiary.                                                                                            |
| **iban** <br> _REQUIRED_          | `string`                          | Beneficiary's IBAN number.                                                                                                    |
| **swiftCode** <br> _REQUIRED_     | `string`                          | Beneficiary's financial institution's SWIFT code.                                                                             |
| **type** <br> _REQUIRED_          | `string` | Type of beneficiary. <br> For further explanation see [Beneficiary Types](https://dapi-api.readme.io/docs/beneficiary-types). |
| **address** <br> _REQUIRED_       | `array`     | An object (associative arry) containing the address information of the beneficiary.                                                              |
| **country** <br> _REQUIRED_       | `string`                          | Name of the country in all uppercase letters.                                                                                 |
| **branchAddress** <br> _REQUIRED_ | `string`                          | Address of the financial institution’s specific branch.                                                                       |
| **branchName** <br> _REQUIRED_    | `string`                          | Name of the financial institution’s specific branch.                                                                          |
| **phoneNumber** <br> _OPTIONAL_   | `string`                          | Beneficiary's phone number.                                                                                                   |
| **routingNumber** <br> _OPTIONAL_ | `string`                          | Beneficiary's Routing number, needed only for US banks accounts.                                                              |

###### Address Object

| Parameter                 | Type     | Description                                                                              |
| ------------------------- | -------- | ---------------------------------------------------------------------------------------- |
| **line1** <br> _REQUIRED_ | `string` | Street name and number. Note: value should not contain any commas or special characters. |
| **line2** <br> _REQUIRED_ | `string` | City name. Note: value should not contain any commas or special characters.              |
| **line3** <br> _REQUIRED_ | `string` | Country name. Note: value should not contain any commas or special characters.           |

##### Response

Method returns only the fields defined in the BaseResponse.

---

#### payment->createTransfer

Method is used to initiate a new payment from one account to another account.

##### Important

We suggest you use `TransferAutoflow` method instead to initiate a payment. `TransferAutoFlow` abstracts all the validations and processing logic, required to initiate a transaction using `CreateTransfer` method.

You can read about `TransferAutoFlow` further in the document.

##### Method Description

```php
function createTransfer($accessToken, $userSecret, $transferData, $userInputs = [], $operationID = "")
```

##### Input Parameters

| Parameter                       | Type             | Description                                                                                                                                                                                                                                                                                        |
| ------------------------------- | ---------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **transferData** <br> _REQUIRED_    | `array` | An object (associative array) that contains info about the transfer that should be initiated.                                                                                                                                                                                                                          |
| **accessToken** <br> _REQUIRED_ | `string`         | Access Token obtained using the `ExchangeToken` method.                                                                                                                                                                                                                                            |
| **userSecret** <br> _REQUIRED_  | `string`         | The `userSecret` from a user’s successful log in to **Connect**.                                                                                                                                                                                                                                   |
| **operationID** <br> _OPTIONAL_ | `string`         | The `OperationID` from a previous call's response. <br> Required only when resuming a previous call that responded with `user_input_required` status, to provided user inputs.                                                                                                                     |
| **userInputs** <br> _OPTIONAL_  | `array`    | Array of `UserInput` object, that are needed to complete this operation. <br> Required only if a previous call responded with `user_input_required` status. <br><br> You can read more about user inputs specification on [Specify User Input](https://dapi-api.readme.io/docs/specify-user-input) |

###### userInput Object

| Parameter | Type                 | Description                                                                                                                                        |
| --------- | -------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------- |
| id        | `string` | Type of input required. <br><br> You can read more about user input types on [User Input Types](https://dapi-api.readme.io/docs/user-input-types). |
| index     | `int`                | Is used in case more than one user input is requested. <br> Will always be 0 If only one input is requested.                                       |
| answer    | `string`             | User input that must be submitted.                                                                                                                 |

###### transferData Object

| Parameter                         | Type      | Description                                                                                                                                                                                                                                                                                                     |
| --------------------------------- | --------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **senderID** <br> _REQUIRED_      | `string`  | The id of the account which the money should be sent from. <br> Retrieved from one of the accounts array returned from the getAccounts method.                                                                                                                                                                  |
| **amount** <br> _REQUIRED_        | `float64` | The amount of money which should be sent.                                                                                                                                                                                                                                                                       |
| **receiverID** <br> _OPTIONAL_    | `string`  | The id of the beneficiary which the money should be sent to. <br> Retrieved from one of the beneficiaries array returned from the getBeneficiaries method. <br> Needed only when creating a transfer from a bank that requires the receiver to be already registered as a beneficiary to perform a transaction. |
| **name** <br> _OPTIONAL_          | `string`  | The name of receiver. <br> Needed only when creating a transfer from a bank that handles the creation of beneficiaries on its own, internally, and doesn't require the receiver to be already registered as a beneficiary to perform a transaction.                                                             |
| **accountNumber** <br> _OPTIONAL_ | `string`  | The Account Number of the receiver's account. <br> Needed only when creating a transfer from a bank that handles the creation of beneficiaries on its own, internally, and doesn't require the receiver to be already registered as a beneficiary to perform a transaction.                                     |
| **iban** <br> _OPTIONAL_          | `string`  | The IBAN of the receiver's account. <br> Needed only when creating a transfer from a bank that handles the creation of beneficiaries on its own, internally, and doesn't require the receiver to be already registered as a beneficiary to perform a transaction.                                               |

##### Response

In addition to the fields described in the BaseResponse, it has the following fields, which will only be returned if the status is `done`:

| Parameter | Type     | Description                                        |
| --------- | -------- | -------------------------------------------------- |
| reference | `string` | Transaction reference string returned by the bank. |

---

#### payment->transferAutoflow

Method is used to initiate a new payment from one account to another account, without having to care nor handle any special cases or scenarios.

##### Method Description

```php
function transferAutoflow($accessToken, $userSecret, $transferAutoFlowData, $userInputs = [], $operationID = "")
```

##### Input Parameters

| Parameter                       | Type               | Description                                                                                                                                                                                                                                                                                        |
| ------------------------------- | ------------------ | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **transferAutoflowData** <br> _REQUIRED_    | `array` | An object (associative array) that contains info about the transfer that should be initiated, and any other details that's used to automate the operation.                                                                                                                                                             |
| **accessToken** <br> _REQUIRED_ | `string`           | Access Token obtained using the `ExchangeToken` method.                                                                                                                                                                                                                                            |
| **userSecret** <br> _REQUIRED_  | `string`           | The `userSecret` from a user’s successful log in to **Connect**.                                                                                                                                                                                                                                   |
| **operationID** <br> _OPTIONAL_ | `string`           | The `OperationID` from a previous call's response. <br> Required only when resuming a previous call that responded with `user_input_required` status, to provided user inputs.                                                                                                                     |
| **userInputs** <br> _OPTIONAL_  | `array`      | Array of `UserInput` object, that are needed to complete this operation. <br> Required only if a previous call responded with `user_input_required` status. <br><br> You can read more about user inputs specification on [Specify User Input](https://dapi-api.readme.io/docs/specify-user-input) |

###### userInput Object

| Parameter | Type                 | Description                                                                                                                                        |
| --------- | -------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------- |
| id        | `string` | Type of input required. <br><br> You can read more about user input types on [User Input Types](https://dapi-api.readme.io/docs/user-input-types). |
| index     | `int`                | Is used in case more than one user input is requested. <br> Will always be 0 If only one input is requested.                                       |
| answer    | `string`             | User input that must be submitted.                                                                                                                 |

###### transferAutoflowData Object

| Parameter                       | Type                      | Description                                                                                                                                    |
| ------------------------------- | ------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- |
| **senderID** <br> _REQUIRED_    | `string`                  | The id of the account which the money should be sent from. <br> Retrieved from one of the accounts array returned from the getAccounts method. |
| **amount** <br> _REQUIRED_      | `float`                 | The amount of money which should be sent.                                                                                                      |
| **beneficiary** <br> _REQUIRED_ | `array` | An object (associative) that holds the info about the beneficiary which the money should be sent to.                                                         |
| **bankID** <br> _REQUIRED_      | `string`                  | The bankID of the user which is initiating this transfer.                                                                                      |

###### beneficiary object

| Parameter                         | Type                              | Description                                                                                                                   |
| --------------------------------- | --------------------------------- | ----------------------------------------------------------------------------------------------------------------------------- |
| **name** <br> _REQUIRED_          | `string`                          | Name of the beneficiary.                                                                                                      |
| **accountNumber** <br> _REQUIRED_ | `string`                          | Account number of the beneficiary.                                                                                            |
| **iban** <br> _REQUIRED_          | `string`                          | Beneficiary's IBAN number.                                                                                                    |
| **swiftCode** <br> _REQUIRED_     | `string`                          | Beneficiary's financial institution's SWIFT code.                                                                             |
| **type** <br> _REQUIRED_          | `string` | Type of beneficiary. <br> For further explanation see [Beneficiary Types](https://dapi-api.readme.io/docs/beneficiary-types). |
| **address** <br> _REQUIRED_       | `array`     | An object (associative arry) containing the address information of the beneficiary.                                                              |
| **country** <br> _REQUIRED_       | `string`                          | Name of the country in all uppercase letters.                                                                                 |
| **branchAddress** <br> _REQUIRED_ | `string`                          | Address of the financial institution’s specific branch.                                                                       |
| **branchName** <br> _REQUIRED_    | `string`                          | Name of the financial institution’s specific branch.                                                                          |
| **phoneNumber** <br> _OPTIONAL_   | `string`                          | Beneficiary's phone number.                                                                                                   |
| **routingNumber** <br> _OPTIONAL_ | `string`                          | Beneficiary's Routing number, needed only for US banks accounts.                                                              |

###### address Object

| Parameter                 | Type     | Description                                                                              |
| ------------------------- | -------- | ---------------------------------------------------------------------------------------- |
| **line1** <br> _REQUIRED_ | `string` | Street name and number. Note: value should not contain any commas or special characters. |
| **line2** <br> _REQUIRED_ | `string` | City name. Note: value should not contain any commas or special characters.              |
| **line3** <br> _REQUIRED_ | `string` | Country name. Note: value should not contain any commas or special characters.           |


##### Response

In addition to the fields described in the BaseResponse, it has the following fields, which will only be returned if the status is `done`:

| Parameter | Type     | Description                                        |
| --------- | -------- | -------------------------------------------------- |
| reference | `string` | Transaction reference string returned by the bank. |

---
