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
        $campaignsList .= "<tr><td>$id[campaignName]</td>";
        $campaignsList .= "<td><a href='/TimeTracker/campaign?action=home&campaignId=$id[campaignId]' title='Click to go to campaign page'>Enter</a></td>";
    }
    return $campaignsList;
}
function createInviteCampaignList()
{
    $ids = getInviteCampaignIds();
    $campaignsList = '';
    foreach($ids as $id)
    {
        $campaign = getCampaignById($id['campaignId']);
        $campaignsList .= "<tr><td>$campaign[campaignName]</td>";
        $campaignsList .= "<td><a href='/TimeTracker/campaign?action=accept&campaignId=$id[campaignId]' title='Click to go to campaign page'>Accept Invite</a></td>";
    }
    return $campaignsList;
}
function createPlayerCampaignList()
{
    $ids = getPlayerCampaignIds();
    $campaignsList = '';
    foreach($ids as $id)
    {
        $campaign = getCampaignById($id['campaignId']);
        $campaignsList .= "<tr><td>$campaign[campaignName]</td>";
        $campaignsList .= "<td><a href='/TimeTracker/campaign?action=home&campaignId=$id[campaignId]' title='Click to go to campaign page'>Enter</a></td>";
    }
    return $campaignsList;
}
function createListOfTrackers()
{
    $trackerList = '';
    foreach($_SESSION['trackers'] as $tracker)
    {
        $trackerList .= "<tr><td>$tracker[trackerName]</td>";
        $trackerList .= "<td><p>Time Remaining: " . $tracker['remainingHours'] .= ":" . $tracker['remainingMinutes'] .= ":" . $tracker['remainingSeconds'] .= "</p></td>";
        $trackerList .= "<td><a href='/TimeTracker/campaign/index.php?action=deleteTracker&trackerId=$tracker[trackerId]' title='Click to delete this tracker'>Delete</a></td>";
    }
    return $trackerList;
}
?>