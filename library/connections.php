<?php
function TimeTrackerConnect()
{
  $server = 'us-cdbr-east-06.cleardb.net';
  $dbname = 'heroku_94a3c1b69b07e2e';
  $userInfo = array('username' => 'bcccd966ba8700', 'password' => '995e5c76');
  if($_SERVER['HTTP_HOST'] == 'localhost')
  {
    $server = 'localhost';
    $dbname= 'TimeTracker';
    $userInfo = array('username' => 'IClient', 'password' => 'gBYOzz_F1MaCpUys');
  }
  $dsn = "mysql:host=$server;dbname=$dbname";
  $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

  // Create the actual connection object and assign it to a variable
  $link = new PDO($dsn, $userInfo['username'], $userInfo['password'], $options);
  return $link;
  // try
  // {
  //   $link = new PDO($dsn, $userInfo['$username'], $userInfo['password'], $options);
  //   return $link;
  // } catch(PDOException $e) 
  // {
  //   header('Location: /TimeTracker/view/500.php');
  //   exit;
  // }
}
?>