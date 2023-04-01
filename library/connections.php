<?php
function TimeTrackerConnect()
{
  $server = 'localhost';
  $dbname= 'TimeTracker';
  $userInfo = array('username' => 'IClient', 'password' => 'gBYOzz_F1MaCpUys');
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