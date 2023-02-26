<?php
function TimeTrackerConnect()
{
  $server = 'localhost';
  $dbname= 'TimeTracker';
  $userInfo = getinfo();
  $dsn = "mysql:host=$server;dbname=$dbname";
  $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

  // Create the actual connection object and assign it to a variable
  try
  {
    $link = new PDO($dsn, $userInfo['$username'], $userInfo['password'], $options);
    return $link;
  } catch(PDOException $e) 
  {
    header('Location: /TimeTracker/view/500.php');
    exit;
  }
}
?>