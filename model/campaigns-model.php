<?php
function createCampaign($campaignName, $startingHours, $startingMinutes, $startingSeconds)
{
    // Create a connection object using the TimeTracker connection function
    $db = TimeTrackerConnect();
    // The SQL statement
    $sql = 'INSERT INTO campaign (campaignName, currentHours, currentMinutes, currentSeconds)
        VALUES (:campaignName, :startingHours, :startingMinutes, :startingSeconds)';
    // Create the prepared statement using the TimeTracker connection
    $stmt = $db->prepare($sql);
    // The next four lines replace the placeholders in the SQL
    // statement with the actual values in the variables
    // and tells the database the type of data it is
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

    // Get campaign data to set the campaign gm lookup table
    $campaignData = getCampaign($campaignName);
    // Create a connection object using the TimeTracker connection function
    $db = TimeTrackerConnect();
    // The SQL statement
    $sql = 'INSERT INTO campaign gm lookup (campaignId, userId)
        VALUES (:campaignId, :userId)';
    // Create the prepared statement using the TimeTracker connection
    $stmt = $db->prepare($sql);
    // The next two lines replace the placeholders in the SQL
    // statement with the actual values in the variables
    // and tells the database the type of data it is
    $stmt->bindValue(':campaignId', $campaignData['campaignId'], PDO::PARAM_INT);
    $stmt->bindValue(':userId', $_SESSION['userData']['userId'], PDO::PARAM_INT);
    // Insert the data
    $stmt->execute();
    // Ask how many rows changed as a result of our insert
    $campainGMLookupRowsChanged = $stmt->rowCount();
    // Close the database interaction
    $stmt->closeCursor();

    // Return the indication of success
    if($campainRowsChanged == 1 && $campainGMLookupRowsChanged == 1)
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
function getCampaign($campaignName)
{
    $db = TimeTrackerConnect();
    $sql = 'SELECT campaignId, campaignName, currentHours, currentMinutes, currentSeconds
            FROM campaign WHERE campaignName = :campaignName';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':campaignName', $campaignName, PDO::PARAM_STR);
    $stmt->execute();
    $campaignData = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $campaignData;
}
?>