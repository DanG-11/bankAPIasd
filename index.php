<?php
/**
 * TODO: Dopisać komentarze dla dokumentacji dla całego pliku
 */

//TODO:
require_once('Route.php');

//TODO:
require_once('model/Account.php');

//TODO:
require_once('model/User.php');

//TODO:
require_once('model/Token.php');

//TODO:
require_once('model/Transfer.php');

//TODO:
require_once('class/LoginRequest.php');

//TODO:
require_once('class/LoginResponse.php');

//TODO:
require_once('class/AccountDetailsRequest.php');

//TODO:
require_once('class/AccountDetailsResponse.php');

//TODO:
$db = new mysqli('localhost', 'root', '', 'bankAPI');
//TODO:
$db->set_charset('utf8');

//TODO:
use Steampixel\Route;
//TODO:
use BankAPI\Account;
//TODO:
use BankAPI\LoginRequest;
//TODO:
use BankAPI\LoginResponse;
//TODO:
use BankAPI\AccountDetailsRequest;
//TODO:
use BankAPI\AccountDetailsResponse;

//TODO:
Route::add('/', function() {
  //TODO:
  echo 'Hello world!';
});

//TODO:
Route::add('/login', function() use($db){
  //utwórz obiekt rządania
  $request = new LoginRequest();
  
  //TODO:
  try{
    //TODO:
    $id = User::login($request->getLogin(), $request->getPassword(), $db);
    //Wygeneruj nowy token dla tego użytkownika i tego IP
    $ip = $_SERVER['REMOTE_ADDR'];
    $token = Token::new($ip, $id, $db);
 
    //Stwórz obiekt odpowiedzi
    $response = new LoginResponse($token, "");
    $response->send();
  }
  catch(Exception $e){
    //Stwórz obiekt odpowiedzi
    $response = new LoginResponse("", $e->getMessage());
    $response->send();
    //TODO:
    return;
  }
}, 'post');

//TODO:
Route::add('/account/([0-9]*)', function($accountNo) use($db) {
  //TODO:
    $account = Account::getAccount($accountNo, $db);
    //TODO:
    header('Content-Type: application/json');
    //TODO:
    return json_encode($account->getArray());
});

//TODO:
Route::add('/account/details', function() use($db){
  $request = new AccountDetailsRequest();
  $response = new AccountDetailsResponse();
  
  //TODO:
  if(!Token::check($request->getToken(), $_SERVER['REMOTE_ADDR'], $db)) {
    $response->setError('Invalid token');
  }

  //TODO:
  $userId = Token::getUserId($request->getToken(), $db);
  //TODO:
  $accountNo = Account::getAccountNo($userId, $db);
  //TODO:
  $account = Account::getAccount($accountNo, $db);

  //ładujemy dane o koncie do odpowiedzi
  $response->setAccount($account->getArray());
  //wysyłamy odpowiedź
  $response->send();
}, 'post');

//TODO:
Route::add('/transfer/new', function() use($db){
  //TODO:
  $data = file_get_contents('php://input');
  //TODO:
  $dataArray = json_decode($data, true);
  //TODO:
  $token = $dataArray['token'];

  //TODO:
  if(!Token::check($token, $_SERVER['REMOTE_ADDR'], $db)){
    //TODO:
    header('HTTP/1.1 401 Unauthorized');
    //TODO:
    return json_encode(['error' => 'Invalid token']);
  }

  //TODO:
  $userId = Token::getUserId($token, $db);
  //TODO:
  $source = Account::getAccountNo($userId, $db);
  //TODO:
  $target = $dataArray['target'];
  //TODO:
  $amount = $dataArray['amount'];

  //TODO:
  if($amount <= 0){
    //TODO:
    header('HTTP/1.1 401 Unauthorized');
    //TODO:
    return json_encode(['error' => 'Invalid amount']);
  }

  //TODO:
  if(Account::getAccountAmount($source, $db) < $amount){
    //TODO:
    header('HTTP/1.1 401 Unauthorized');
    //TODO:
    return json_encode(['error' => 'Invalid amount']);
  }
  
  //TODO:
  Transfer::new($source, $target, $amount, $db);
  
  //TODO:
  header('Status: 200');
  //TODO:
  return json_encode(['status' => 'OK']);
}, 'post');

//TODO:
Route::run('/bankAPI');

//TODO:
$db->close();
?>