<?php
// Create or access a Session
session_start();

// Get the database user info
require_once '../databaseInfo.php';
// Get the database connection file
require_once '../library/connections.php';
// Get the functions library
require_once '../library/functions.php';
// Get the accounts model
require_once '../model/accounts-model.php';
// Get the campaigns model
require_once '../model/campaigns-model.php';

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL)
{
    $action = filter_input(INPUT_GET, 'action');
}

switch ($action)
{
    case 'creation start':
        include '../view/campaign creation.php';
        exit;

    case 'Create':
        // Filter and store the data
        $campaignName = trim(filter_input(INPUT_POST, 'campaignName', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $startingHours = trim(filter_input(INPUT_POST, 'startingHours', FILTER_SANITIZE_NUMBER_INT));
        $startingMinutes = trim(filter_input(INPUT_POST, 'startingMinutes', FILTER_SANITIZE_NUMBER_INT));
        $startingSeconds = trim(filter_input(INPUT_POST, 'startingSeconds', FILTER_SANITIZE_NUMBER_INT));
    
        // Check for existing campaign
        $existingCampaign = checkExistingCampaign($campaignName);
        if($existingCampaign)
        {
            $message = '<p class="notice">That campaign name is already in use. Please pick a new name.</p>';
            include '../view/campaign creation.php';
            exit;
        }
    
        // Check for missing data
        if(empty($campaignName))
        {
            $message .= '<p>Please provide a name for your campaign.</p>';
            include 'view/campaign creation.php';
            exit; 
        }

        $outcome = createCampaign($campaignName, $startingHours, $startingMinutes, $startingSeconds);

    default:
        include '../view/user home.php';
        break;
}
?>