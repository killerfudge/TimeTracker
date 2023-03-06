<?php
function createCampaign($campaignName, $startingHours, $startingMinutes, $startingSeconds)
{
    // Create a connection object using the TimeTracker connection function
    $db = TimeTrackerConnect();
    // The SQL statement
    $sql = 'INSERT INTO campaign (gameMasterId, campaignName, currentHours, currentMinutes, currentSeconds)
        VALUES (:userId, :campaignName, :startingHours, :startingMinutes, :startingSeconds)';
    // Create the prepared statement using the TimeTracker connection
    $stmt = $db->prepare($sql);
    // The next four lines replace the placeholders in the SQL
    // statement with the actual values in the variables
    // and tells the database the type of data it is
    $stmt->bindValue(':userId', $_SESSION['userData']['userId'], PDO::PARAM_INT);
    $stmt->bindValue(':campaignName', $campaignName, PDO::PARAM_STR);
    $stmt->bindValue(':startingHours', $startingHours, PDO::PARAM_INT);
    $stmt->bindValue(':startingMinutes', $startingMinutes, PDO::PARAM_INT);
    $stmt->bindValue(':startingSeconds', $startingSeconds, PDO::PARAM_INT);
    // Insert the data
    $stmt->execute();
    // Ask how many rows changed as a result of our insert
    $campainRowsChanged = $stmt->rowCount();
    // Close the database interaction
    $stmt->closeCursor();
    // Store campaign info in session to be used in campaign page
    $_SESSION['campaignInfo'] = getCampaignByName($campaignName); 

    // Return the indication of success
    if($campainRowsChanged == 1)
    {
        return 1;
    } else {
        return 0;
    }
}
// This function will check if a campaign name already exists
function checkExistingCampaign($campaignName)
{
    // Create a connection object using the TimeTracker connection function
    $db = TimeTrackerConnect();
    // The SQL statement
    $sql = 'SELECT campaignName FROM campaign WHERE campaignName = :campaignName';
    // Create the prepared statement using the TimeTracker connection
    $stmt = $db->prepare($sql);
    // The next four lines replace the placeholders in the SQL
    // statement with the actual values in the variables
    // and tells the database the type of data it is
    $stmt->bindValue(':campaignName', $campaignName, PDO::PARAM_STR);
    // Insert the data
    $stmt->execute();
    // Get the data from the database
    $matchName = $stmt->fetch(PDO::FETCH_NUM);
    // Close the database interaction
    $stmt->closeCursor();
    // Return the indication of success 
    if(empty($matchName))
    {
        return 0;
    } else 
    {
        return 1;
    }
}
function getCampaignByName($campaignName)
{
    $db = TimeTrackerConnect();
    $sql = 'SELECT gameMasterId, campaignId, campaignName, currentHours, currentMinutes, currentSeconds
            FROM campaign WHERE campaignName = :campaignName';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':campaignName', $campaignName, PDO::PARAM_STR);
    $stmt->execute();
    $campaignData = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $campaignData;
}
function getCampaignById($campaignId)
{
    $db = TimeTrackerConnect();
    $sql = 'SELECT gameMasterId, campaignId, campaignName, currentHours, currentMinutes, currentSeconds
            FROM campaign WHERE campaignId = :campaignId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':campaignId', $campaignId, PDO::PARAM_INT);
    $stmt->execute();
    $campaignData = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $campaignData;
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
function getGmCampaignIds()
{
    $db = TimeTrackerConnect(); 
    $sql = ' SELECT campaignId, campaignName FROM campaign WHERE gameMasterId = :userId'; 
    $stmt = $db->prepare($sql); 
    $stmt->bindValue(':userId', $_SESSION['userData']['userId'], PDO::PARAM_INT); 
    $stmt->execute(); 
    $campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    $stmt->closeCursor(); 
    return $campaigns; 
}
function updateCampaign($campaignName, $currentHours, $currentMinutes, $currentSeconds)
{
    // Create a connection object using the TimeTracker connection function
    $db = TimeTrackerConnect();
    // The SQL statement
    $sql = 'UPDATE campaign SET campaignName = :campaignName, currentHours = :currentHours, 
            currentMinutes = :currentMinutes, currentSeconds = :currentSeconds WHERE campaignId = :campaignId';
    // Create the prepared statement using the TimeTracker connection
    $stmt = $db->prepare($sql);
    // The next four lines replace the placeholders in the SQL
    // statement with the actual values in the variables
    // and tells the database the type of data it is
    $stmt->bindValue(':campaignName', $campaignName, PDO::PARAM_STR);
    $stmt->bindValue(':currentHours', $currentHours, PDO::PARAM_INT);
    $stmt->bindValue(':currentMinutes', $currentMinutes, PDO::PARAM_INT);
    $stmt->bindValue(':currentSeconds', $currentSeconds, PDO::PARAM_INT);
    $stmt->bindValue(':campaignId', $_SESSION['campaignInfo']['campaignId'], PDO::PARAM_INT);
    // Insert the data
    $stmt->execute();
    // Ask how many rows changed as a result of our insert
    $campainRowsChanged = $stmt->rowCount();
    // Close the database interaction
    $stmt->closeCursor();
    // Store campaign info in session to be used in campaign page
    $_SESSION['campaignInfo'] = getCampaignByName($campaignName); 

    // Return the indication of success
    if($campainRowsChanged == 1)
    {
        return 1;
    } else {
        return 0;
    }
}
?>