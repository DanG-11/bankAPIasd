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
$db = new mysqli('localhost', 'root', '', 'bankAPI');
//TODO:
$db->set_charset('utf8');

//TODO:
use Steampixel\Route;
//TODO:
use BankAPI\Account;

//TODO:
Route::add('/', function() {
  //TODO:
  echo 'Hello world!';
});

//TODO:
Route::add('/login', function() use($db){
  //TODO:
  $data = file_get_contents('php://input');
  //TODO:
  $data = json_decode($data, true);
  //TODO:
  $ip = $_SERVER['REMOTE_ADDR'];
  
  //TODO:
  try{
    //TODO:
    $id = User::login($data['login'], $data['password'], $db);
    //TODO:
    $token = Token::new($ip, $id, $db);
    //TODO:
    header('Content-Type: application/json');
    //TODO:
    echo json_encode(['token' => $token]);
  }
  catch(Exception $e){
    //TODO:
    header('HTTP/1.1 401 Unauthorized');
    //TODO:
    echo json_encode(['error' => 'Invalid login or password']);
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
  $accountNo = Account::getAccountNo($userId, $db);
  //TODO:
  $account = Account::getAccount($accountNo, $db);

  //TODO:
  header('Content-Type: application/json');
  //TODO:
  return json_encode($account->getArray());
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