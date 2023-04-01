<?php
// Create or access a Session
session_start();
// Get the database connection file
require_once 'library/connections.php';
// Get the functions library
require_once 'library/functions.php';
// Get the accounts model
require_once 'model/accounts-model.php';
// Get the accounts model
require_once 'model/campaigns-model.php';

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
    
    case 'register':
        // Filter and store the data
        $userEmail = trim(filter_input(INPUT_POST, 'userEmail', FILTER_SANITIZE_EMAIL));
        $userPassword = trim(filter_input(INPUT_POST, 'userPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $userEmail = checkEmail($userEmail);
        $checkPassword = checkPassword($userPassword);
    
        // Check for existing email
        $existingEmail = checkExistingEmail($userEmail);
        if($existingEmail)
        {
            $message = '<p class="notice">That email address already exists. Do you want to login instead?</p>';
            include 'view/home.php';
            exit;
        }
    
        // Check for missing data
        if(empty($userEmail) || empty($checkPassword))
        {
            $message = '<p>Please provide information for all empty form fields.</p>';
            include 'view/home.php';
            exit; 
        }
    
        // Hash the checked password
        $hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT);
    
        // Send the data to the model
        $regOutcome = regUser($userEmail, $hashedPassword);
    
        // Check and report the result
        // if($regOutcome === 1)
        // {
        //     $_SESSION['message'] = "<p>Thanks for registering. Please use your email and password to login.</p>";
        //     header('Location: /TimeTracker/view/home.php');
        //     exit;
        // } else 
        // {
        //     $message = "<p>Sorry, but the registration failed. Please try again.</p>";
        //     include 'view/home.php';
        //     exit;
        // }
        echo 'register';
        break;
    
    case 'Login':
        // Filter and store the data
        $userEmail = trim(filter_input(INPUT_POST, 'userEmail', FILTER_SANITIZE_EMAIL));
        $userPassword = trim(filter_input(INPUT_POST, 'userPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $userEmail = checkEmail($userEmail);
        $checkPassword = checkPassword($userPassword);
    
        // Check for missing data
        if(empty($userEmail) || empty($checkPassword))
        {
            $message .= '<p>Please provide information for all empty form fields.</p>';
            include 'view/home.php';
            exit; 
        }
      
        // A valid password exists, proceed with the login process
        // Query the user data based on the email address
        $userData = getUser($userEmail);
        // Compare the password just submitted against
        // the hashed password for the matching user
        $hashCheck = password_verify($userPassword, $userData['userPassword']);
        // If the hashes don't match create an error
        // and return to the login view
        if(!$hashCheck) {
            $message = '<p class="notice">Please check your password and try again.</p>';
            include 'view/home.php';
            exit;
        }
        // A valid user exists, log them in
        $_SESSION['loggedin'] = TRUE;
        // Remove the password from the array
        // the array_pop function removes the last
        // element from an array
        array_pop($userData);
        // Store the array into the session
        $_SESSION['userData'] = $userData;
        // Create lists for their campaigns
        $gmCampaignList = createGMCampaignList();
        $inviteCampaignList = createInviteCampaignList();
        $playerCampaignList = createPlayerCampaignList();
        // Send them to their home page
        //include 'view/user home.php';
        echo 'login';
        exit;
        break;
    
        case 'Logout':
            unset($_SESSION['userData']);
            unset($_SESSION['loggedin']);
            unset($_SESSION['campaignData']);
            session_destroy();
            echo 'logout';
            //header('location: /TimeTracker/');
            exit;
    
    default:
        include 'view/home.php';
        break;
}
?>