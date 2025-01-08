<?php
/**
 * The main file with all routes.
 */

//Requires the Route.php file.
require_once('Route.php');

//Requires the Account.php file.
require_once('model/Account.php');

//Requires the User.php file.
require_once('model/User.php');

//Requires the Token.php file.
require_once('model/Token.php');

//Requires the Transfer.php file.
require_once('model/Transfer.php');

//Requires the LoginRequest.php file.
require_once('class/LoginRequest.php');

//Requires the LoginResponse.php file.
require_once('class/LoginResponse.php');

//Requires the AccountDetailsRequest.php file.
require_once('class/AccountDetailsRequest.php');

//Requires the AccountDetailsResponse.php file.
require_once('class/AccountDetailsResponse.php');

//Requires the TransfersRequest.php file.
require_once('class/TransfersRequest.php');

//Requires the TransfersResponse.php file.
require_once('class/TransfersResponse.php');

//TODO:
$db = new mysqli('localhost', 'root', '', 'bankAPI');
//TODO:
$db->set_charset('utf8');

//Uses the Route.php file.
use Steampixel\Route;
//Uses the Account.php file.
use BankAPI\Account;
//Uses the LoginRequest.php file.
use BankAPI\LoginRequest;
//Uses the LoginResponse.php file.
use BankAPI\LoginResponse;
//Uses the AccountDetailsRequest.php file.
use BankAPI\AccountDetailsRequest;
//Uses the AccountDetailsResponse.php file.
use BankAPI\AccountDetailsResponse;
//Uses the TransfersRequest.php file.
use BankAPI\TransfersRequest;
//Uses the TransfersResponse.php file.
use BankAPI\TransfersResponse;

//Adds a route.
Route::add('/', function() {
  //Types Hello world! on the site.
  echo 'Hello world!';
});

//Adds a login route.
Route::add('/login', function() use($db){
  //A login request.
  $request = new LoginRequest();
  
  //Try method.
  try{
    //Variable that gets the users login.
    $id = User::login($request->getLogin(), $request->getPassword(), $db);
    //No idea.
    $ip = $_SERVER['REMOTE_ADDR'];
    //Generates a new token.
    $token = Token::new($ip, $id, $db);
 
    //A login response.
    $response = new LoginResponse($token, "");
    //Sends the login response.
    $response->send();
  }
  catch(Exception $e){
    //An error login response.
    $response = new LoginResponse("", $e->getMessage());
    //Sends the error login response.
    $response->send();
    //Returns nothing.
    return;
  }
}, 'post');

//Adds a account/([0-9]* route.
Route::add('/account/([0-9]*)', function($accountNo) use($db) {
  //Variable that gets the users account.
    $account = Account::getAccount($accountNo, $db);
    //Makes the site header.
    header('Content-Type: application/json');
    //Encodes the array
    return json_encode($account->getArray());
});

//Adds a account/details route.
Route::add('/account/details', function() use($db){
  //Account details request.
  $request = new AccountDetailsRequest();
  //Account details response
  $response = new AccountDetailsResponse();
  
  //If loop that makes an error response.
  if(!Token::check($request->getToken(), $_SERVER['REMOTE_ADDR'], $db)) {
    $response->setError('Invalid token');
  }

  //Gets the users id.
  $userId = Token::getUserId($request->getToken(), $db);
  //Gets the users account number.
  $accountNo = Account::getAccountNo($userId, $db);
  //Gets the user account.
  $account = Account::getAccount($accountNo, $db);

  //Makes a response.
  $response->setAccount($account->getArray());
  //Sends the response.
  $response->send();
}, 'post');

//Adds a transfer/new route.
Route::add('/transfer/new', function() use($db){
  //Gets the content.
  $data = file_get_contents('php://input');
  //Decodes the content.
  $dataArray = json_decode($data, true);
  //Gets the token.
  $token = $dataArray['token'];

  //If loop that makes a header and returns an error.
  if(!Token::check($token, $_SERVER['REMOTE_ADDR'], $db)){
    //Makes a header.
    header('HTTP/1.1 401 Unauthorized');
    //Returns the error.
    return json_encode(['error' => 'Invalid token']);
  }

  //Gets users id.
  $userId = Token::getUserId($token, $db);
  //Gets users account number.
  $source = Account::getAccountNo($userId, $db);
  //Gets the target.
  $target = $dataArray['target'];
  //Gets the ammount.
  $amount = $dataArray['amount'];

  //If loop that makes a header and returns an error.
  if($amount <= 0){
    //Makes a header.
    header('HTTP/1.1 401 Unauthorized');
    //Returns the error.
    return json_encode(['error' => 'Invalid amount']);
  }

  //If loop that makes a header and returns an error.
  if(Account::getAccountAmount($source, $db) < $amount){
    //Makes a header.
    header('HTTP/1.1 401 Unauthorized');
    //Returns the error.
    return json_encode(['error' => 'Invalid amount']);
  }
  
  //Makes a new transfer
  Transfer::new($source, $target, $amount, $db);
  
  //Makes a header.
  header('Status: 200');
  //Returns the status.
  return json_encode(['status' => 'OK']);
}, 'post');

//Adds a transfer/history route.
Route::add('/transfer/history', function() use($db){
  //Transfer request.
  $request = new TransfersRequest();
  //Transfer response.
  $response = new TransfersResponse();

  //If loop that makes a header and returns an error.
  if(!Token::check($request->getToken(), $_SERVER['REMOTE_ADDR'], $db)){
    //Makes a header.
    header('HTTP/1.1 401 Unauthorized');
    //Returns the error.
    return json_encode(['error' => 'Invalid amount']);
  }

  //Gets the users id.
  $userId = Token::getUserId($request->getToken(), $db);
  //Gets the users account number.
  $accountNo = Account::getAccountNo($userId, $db);

  //Sets a transfer.
  $response->setTransfers(Transfer::getTransfers($accountNo, $db));
  //Sends the transfer to the database.
  $response->send();
}, 'post');

//Runs the /bankAPI route.
Route::run('/bankAPI');

//Closes connection to the database.
$db->close();
?>