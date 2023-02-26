<?php
// Create or access a Session
session_start();

// Get the database user info
require_once 'databaseInfo.php';
// Get the database connection file
require_once 'library/connections.php';
// Get the functions library
require_once 'library/functions.php';

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL)
{
    $action = filter_input(INPUT_GET, 'action');
}

switch ($action)
{
    case 'template':
        include 'view/template.php';
        break;
    
    default:
        include 'view/home.php';
        break;
}
?>