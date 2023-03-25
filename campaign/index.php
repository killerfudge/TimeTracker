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
            $message = '<p>Please provide a name for your campaign.</p>';
            include '../view/campaign creation.php';
            exit; 
        }

        $outcome = createCampaign($campaignName, $startingHours, $startingMinutes, $startingSeconds);

        if($outcome)
        {
            include '../view/campaign home.php';
            exit;
        }
        else
        {
            $message = '<p>Campaign creation unsuccessful. Please try again.';
            include '../view/campaign creation.php';
            exit;
        }
        break;

    case 'home':
        if(isset($_SESSION['campaignInfo']))
        {
            unset($_SESSION['campaignInfo']);
        }
        if(isset($_SESSION['trackers']))
        {
            unset($_SESSION['trackers']);
        }
        $campaignId = trim(filter_input(INPUT_GET, 'campaignId', FILTER_SANITIZE_NUMBER_INT));
        $campaignInfo = getCampaignById($campaignId);
        $_SESSION['campaignInfo'] = $campaignInfo;
        if($_SESSION['campaignInfo']['inCombat'] == 0)
        {
            $trackers = getTrackersByCampaignId($_SESSION['campaignInfo']['campaignId']);
            $_SESSION['trackers'] = $trackers;
            $trackerList = createListOfTrackers();
        }
        else
        {
            $initiative = getInitiativeByCampaignId($campaignId);
            $_SESSION['combat'] = $initiative;
            $turnOrder = createInitiative();
        }
        include '../view/campaign home.php';
        exit;

    case 'returnHome':
        header("location: /TimeTracker/campaign/index.php?action=home&campaignId=".$_SESSION['campaignInfo']['campaignId']);
        exit;

    case 'leaveCampaign':
        unset($_SESSION['campaignInfo']);
        $gmCampaignList = createGMCampaignList();
        $inviteCampaignList = createInviteCampaignList();
        $playerCampaignList = createPlayerCampaignList();
        include '../view/user home.php';
        exit;

    case 'editCampaign':
        // Filter and store the data
        $campaignName = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $currentHours = trim(filter_input(INPUT_POST, 'hours', FILTER_SANITIZE_NUMBER_INT));
        $currentMinutes = trim(filter_input(INPUT_POST, 'minutes', FILTER_SANITIZE_NUMBER_INT));
        $currentSeconds = trim(filter_input(INPUT_POST, 'seconds', FILTER_SANITIZE_NUMBER_INT));
    
        // Check for existing campaign
        if($campaignName != $_SESSION['campaignInfo']['campaignName'])
        {
            $existingCampaign = checkExistingCampaign($campaignName);
            if($existingCampaign)
            {
                $message = '<p class="notice">That campaign name is already in use. Please pick a new name.</p>';
                include '../view/edit campaign.php';
                exit;
            }
        }

        $outcome = updateCampaign($campaignName, $currentHours, $currentMinutes, $currentSeconds);

        if($outcome)
        {
            include '../view/campaign home.php';
            exit;
        }
        else
        {
            $message = '<p>Campaign update unsuccessful. Please try again.';
            include '../view/edit campaign.php';
            exit;
        }
        break;

    case 'edit':
        include '../view/edit campaign.php';
        exit;

    case 'addPlayer':
        // Filter and store the data
        $playerEmail = trim(filter_input(INPUT_POST, 'playerEmail', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $campaignId = trim(filter_input(INPUT_POST, 'campaignId', FILTER_SANITIZE_NUMBER_INT));
        $playerEmail = checkEmail($playerEmail);
    
        // Check for missing data
        if(empty($playerEmail))
        {
            $message = '<p>Please provide information for all empty form fields.</p>';
            include '../view/edit campaign.php';
            exit; 
        }

        $userId = getUserIdByEmail($playerEmail);

        if($userId)
        {
            $outcome = invitePlayer($userId['userId'], $campaignId);
        }
        else
        {
            $message = '<p>Account not found. Please confirm you have the correct email.</p>';
            include '../view/edit campaign.php';
            exit;
        }

        if($outcome)
        {
            $message = '<p>Player invited.</p>';
            include '../view/edit campaign.php';
            exit;
        }
        else
        {
            $message = '<p>There was a problem inviting the player. Please try again.';
            include '../view/edit campaign.php';
            exit;
        }
        break;

    case 'accept':
        // Filter and store the data
        $campaignId = trim(filter_input(INPUT_GET, 'campaignId', FILTER_SANITIZE_NUMBER_INT));

        $accept = acceptInvite($campaignId);

        if($accept)
        {
            $campaignInfo = getCampaignById($campaignId);
            $_SESSION['campaignInfo'] = $campaignInfo;
            include '../view/campaign home.php';
            exit;
        }
        else
        {
            include '../view/500.php';
            exit;
        }

    case 'getCampaignInfo':
        // Get the campaign id 
        $campaignId = filter_input(INPUT_GET, 'campaignId', FILTER_SANITIZE_NUMBER_INT); 
        // Fetch the campaign by campaignId from the DB 
        $campaign = getCampaignById($campaignId); 
        // Convert the array to a JSON object and send it back 
        echo json_encode($campaign);
        break;

    case 'setCampaignTime':
        // Get the time to set
        $currentSeconds = filter_input(INPUT_GET, 'seconds', FILTER_SANITIZE_NUMBER_INT);
        $currentMinutes = filter_input(INPUT_GET, 'minutes', FILTER_SANITIZE_NUMBER_INT);
        $currentHours = filter_input(INPUT_GET, 'hours', FILTER_SANITIZE_NUMBER_INT);
        $campaignName = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        updateCampaign($campaignName, $currentHours, $currentMinutes, $currentSeconds);
        header('location: /TimeTracker/view/campaign home.php');
        break;

    case 'addTrackerView':
        include '../view/add duration tracker.php';
        exit;
        break;

    case 'CreateTracker':
        $trackerName = filter_input(INPUT_POST, 'trackerName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $remainingSeconds = filter_input(INPUT_POST, 'remainingSeconds', FILTER_SANITIZE_NUMBER_INT);
        $remainingMinutes = filter_input(INPUT_POST, 'remainingMinutes', FILTER_SANITIZE_NUMBER_INT);
        $remainingHours = filter_input(INPUT_POST, 'remainingHours', FILTER_SANITIZE_NUMBER_INT);
    
        // Check for missing data
        if(empty($trackerName) || empty($remainingSeconds) || empty($remainingMinutes) || empty($remainingHours))
        {
            $message = '<p>Please provide information for all empty form fields.</p>';
            include '../view/add duration tracker.php';
            exit; 
        }

        $checkTracker = checkExistingTracker($trackerName);
        if($checkTracker)
        {
            $message = 'A duration tracker with that name already exists';
            include '../view/add duration tracker.php';
            exit;
        }
        else
        {
            addTracker($trackerName, $remainingHours, $remainingMinutes, $remainingSeconds, $_SESSION['campaignInfo']['campaignId']);
            if($_SESSION['campaignInfo']['inCombat'] == 1)
            {
                $initiative = filter_input(INPUT_POST, 'initiative', FILTER_SANITIZE_NUMBER_INT);
                $tracker = getTrackerByName($trackerName);
                addInitiative($tracker['campaignId'], $tracker['trackerId'], $tracker['trackerName'], $initiative, 0);
            }
            header("location: /TimeTracker/campaign/?action=home&campaignId=" . $_SESSION['campaignInfo']['campaignId']);
        }
        break;

    case 'deleteTracker':
        $trackerId = filter_input(INPUT_GET, 'trackerId', FILTER_SANITIZE_NUMBER_INT);
        deleteTracker($trackerId);
        header("location: /TimeTracker/campaign/?action=home&campaignId=" . $_SESSION['campaignInfo']['campaignId']);
        break;

    case 'updateTracker':
        $trackerId = filter_input(INPUT_GET, 'trackerId', FILTER_SANITIZE_NUMBER_INT);
        $remainingSeconds = filter_input(INPUT_GET, 'seconds', FILTER_SANITIZE_NUMBER_INT);
        $remainingMinutes = filter_input(INPUT_GET, 'minutes', FILTER_SANITIZE_NUMBER_INT);
        $remainingHours = filter_input(INPUT_GET, 'hours', FILTER_SANITIZE_NUMBER_INT);
        updateTracker($trackerId, $remainingHours, $remainingMinutes, $remainingSeconds);
        break;

    case 'getTrackerInfo':
        $campaignId = filter_input(INPUT_GET, 'campaignId', FILTER_SANITIZE_NUMBER_INT); 
        $trackers = getTrackersByCampaignId($campaignId);
        // Convert the array to a JSON object and send it back 
        echo json_encode($trackers);
        break;

    case 'endCombat':
        $campaignId = trim(filter_input(INPUT_POST, 'campaignId', FILTER_SANITIZE_NUMBER_INT));
        endCombat($campaignId);
        deleteCombat($campaignId);
        header("location: /TimeTracker/campaign/index.php?action=home&campaignId=".$_SESSION['campaignInfo']['campaignId']);
        break;

    case 'startCombat':
        $campaignId = trim(filter_input(INPUT_POST, 'campaignId', FILTER_SANITIZE_NUMBER_INT));
        $trackers = getTrackersByCampaignId($campaignId);
        setStartingCombat($trackers); //set up the combat table with existing trackers
        setCombatStart($campaignId); //update campaign table to set combat variable
        header("location: /TimeTracker/campaign/index.php?action=home&campaignId=".$_SESSION['campaignInfo']['campaignId']);
        break;

    case 'addCombatantView':
        include '../view/add combatant.php';
        exit;

    case 'createCombatant':
        $combatantName = trim(filter_input(INPUT_POST, 'combatantName', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $initiative = trim(filter_input(INPUT_POST, 'initiative', FILTER_SANITIZE_NUMBER_INT));
    
        // Check for missing data
        if(empty($combatantName) || empty($initiative))
        {
            $message = '<p>Please provide information for all empty form fields.</p>';
            include '../view/add combatant.php';
            exit; 
        }

        addInitiative($_SESSION['campaignInfo']['campaignId'], NULL, $combatantName, $initiative, 0);
        header("location: /TimeTracker/campaign/index.php?action=home&campaignId=".$_SESSION['campaignInfo']['campaignId']);
        break;

    case 'endTurn':
        nextTurn();
        header("location: /TimeTracker/campaign/index.php?action=home&campaignId=".$_SESSION['campaignInfo']['campaignId']);
        break;

    case 'deleteInitiative':
        $combatId = filter_input(INPUT_GET, 'combatId', FILTER_SANITIZE_NUMBER_INT);
        deleteInitiative($combatId);
        header("location: /TimeTracker/campaign/index.php?action=home&campaignId=".$_SESSION['campaignInfo']['campaignId']);
        break;
        
    default:
        $gmCampaignList = createGMCampaignList();
        $inviteCampaignList = createInviteCampaignList();
        $playerCampaignList = createPlayerCampaignList();
        include '../view/user home.php';
        break;
}
?>