<?php
function checkEmail($email)
{
    $valEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    return $valEmail;
}
// Check the password for a minimum of 8 characters,
// at least one 1 capital letter, at least 1 number and
// at least 1 special character
function checkPassword($password)
{
    $pattern = '/^(?=.*[[:digit:]])(?=.*[[:punct:]\s])(?=.*[A-Z])(?=.*[a-z])(?:.{8,})$/';
    return preg_match($pattern, $password);
}
function createGMCampaignList()
{
    $ids = getGmCampaignIds();
    $campaignsList = '';
    foreach($ids as $id)
    {
        if($_SERVER['HTTP_HOST'] == 'localhost')
        {
            $url = "/TimeTracker/campaign?action=home&campaignId=$id[campaignId]";
        }
        else
        {
            $url = "/campaign?action=home&campaignId=$id[campaignId]";
        }
        $campaignsList .= "<tr><td>$id[campaignName]</td>";
        $campaignsList .= "<td><a href='$url' title='Click to go to campaign page'>Enter</a></td>";
    }
    return $campaignsList;
}
function createInviteCampaignList()
{
    $ids = getInviteCampaignIds();
    $campaignsList = '';
    foreach($ids as $id)
    {
        if($_SERVER['HTTP_HOST'] == 'localhost')
        {
            $url = "/TimeTracker/campaign?action=accept&campaignId=$id[campaignId]";
        }
        else
        {
            $url = "/campaign?action=accept&campaignId=$id[campaignId]";
        }
        $campaign = getCampaignById($id['campaignId']);
        $campaignsList .= "<tr><td>$campaign[campaignName]</td>";
        $campaignsList .= "<td><a href='$url' title='Click to go to campaign page'>Accept Invite</a></td>";
    }
    return $campaignsList;
}
function createPlayerCampaignList()
{
    $ids = getPlayerCampaignIds();
    $campaignsList = '';
    foreach($ids as $id)
    {
        if($_SERVER['HTTP_HOST'] == 'localhost')
        {
            $url = "/TimeTracker/campaign?action=home&campaignId=$id[campaignId]";
        }
        else
        {
            $url = "/campaign?action=home&campaignId=$id[campaignId]";
        }
        $campaign = getCampaignById($id['campaignId']);
        $campaignsList .= "<tr><td>$campaign[campaignName]</td>";
        $campaignsList .= "<td><a href='$url' title='Click to go to campaign page'>Enter</a></td>";
    }
    return $campaignsList;
}
function createListOfTrackers()
{
    $trackerList = '';
    foreach($_SESSION['trackers'] as $tracker)
    {
        if($_SERVER['HTTP_HOST'] == 'localhost')
        {
            $url = "/TimeTracker/campaign/index.php?action=deleteTracker&trackerId=$tracker[trackerId]";
        }
        else
        {
            $url = "/campaign/index.php?action=deleteTracker&trackerId=$tracker[trackerId]";
        }
        $trackerList .= "<tr><td>$tracker[trackerName]</td>";
        $trackerList .= "<td><p>Time Remaining: " . $tracker['remainingHours'] .= ":" . $tracker['remainingMinutes'] .= ":" . $tracker['remainingSeconds'] .= "</p></td>";
        $trackerList .= "<td><a href='$url' title='Click to delete this tracker'>Delete</a></td>";
    }
    return $trackerList;
}
function createInitiative()
{
    // sort initiative so highest initiative is at top
    $initiativeSort = array_column($_SESSION['combat'], 'initiative');
    array_multisort($initiativeSort, SORT_DESC, $_SESSION['combat']);
    // move through array until the current turn is at the top
    foreach($_SESSION['combat'] as $turn)
    {
        if($turn['currentTurn'] == 1)
        {
            break;
        }
        else
        {
            $catch = array_shift($_SESSION['combat']);
            array_push($_SESSION['combat'], $catch);
        }
    }
    $initiative = '';
    foreach($_SESSION['combat'] as $turn)
    {
        $initiative .= "<tr><td>$turn[combatName]</td>";
        $initiative .= "<td><p>Initiative: " . $turn['initiative'] .= "</p></td>";
        if($turn['trackerId'])
        {
            $tracker = getTrackerByName($turn['combatName']);
            $initiative .= "<td><p>Time Remaining: " . $tracker['remainingHours'] .= ":" . $tracker['remainingMinutes'] .= ":" . $tracker['remainingSeconds'] .= "</p></td>";
        }
        if($_SERVER['HTTP_HOST'] == 'localhost')
        {
            $url = "/TimeTracker/campaign/index.php?action=deleteInitiative&combatId=$turn[combatId]";
        }
        else
        {
            $url = "/campaign/index.php?action=deleteInitiative&combatId=$turn[combatId]";
        }
        $initiative .= "<td><a href='$url' title='Click to delete this initiative slot'>Delete</a></td>";
    }
    return $initiative;
}
function setStartingCombat($trackers)
{
    $count = 0;
    foreach($trackers as $tracker)
    {
        addInitiative($tracker['campaignId'], $tracker['trackerId'], $tracker['trackerName'], 0, 0);
        $count += 1;
    }
    addInitiative($_SESSION['campaignInfo']['campaignId'], NULL, 'roundStart', 0, 1);
}
function nextTurn()
{
    updateInitiative($_SESSION['combat'][0]['combatId'], $_SESSION['combat'][0]['initiative'], 0);
    $catch = array_shift($_SESSION['combat']);
    array_push($_SESSION['combat'], $catch);
    while($_SESSION['combat'][0]['trackerId'])
    {
        $tracker = getTrackerByName($_SESSION['combat'][0]['combatName']);
        $tracker['remainingSeconds'] -= 6;
        if($tracker['remainingSeconds'] < 0)
        {
            $tracker['remainingMinutes'] -= 1;
            if($tracker['remainingMinutes'] < 0)
            {
                $tracker['remainingHours'] -= 1;
                if($tracker['remainingHours'] < 0)
                {
                    deleteTracker($tracker['trackerId']);
                }
                else
                {
                    $tracker['remainingMinutes'] += 60;
                    $tracker['remainingSeconds'] += 60;
                }
            }
            else
            {
                $tracker['remainingSeconds'] += 60;
            }
        }
        updateTracker($tracker['trackerId'], $tracker['remainingHours'], $tracker['remainingMinutes'], $tracker['remainingSeconds']);
        $catch = array_shift($_SESSION['combat']);
        array_push($_SESSION['combat'], $catch);
    }
    if($_SESSION['combat'][0]['combatName'] == 'roundStart')
    {
        $campaign = getCampaignById($_SESSION['combat'][0]['campaignId']);
        $campaign['currentSeconds'] += 6;
        while($campaign['currentSeconds'] >= 60)
        {
            $campaign['currentMinutes'] += 1;
            $campaign['currentSeconds'] -= 60;
        }
        while($campaign['currentMinutes'] >= 60)
        {
            $campaign['currentHours'] += 1;
            $campaign['currentMinutes'] -= 60;
        }
        while($campaign['currentHours'] >= 24)
        {
            $campaign['currentHours'] -= 24;
        }
        updateCampaign($campaign['campaignName'], $campaign['currentHours'], $campaign['currentMinutes'], $campaign['currentSeconds']);
    }
    updateInitiative($_SESSION['combat'][0]['combatId'], $_SESSION['combat'][0]['initiative'], 1);
}
?>