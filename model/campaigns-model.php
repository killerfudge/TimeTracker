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
    $sql = 'SELECT * FROM campaign WHERE campaignName = :campaignName';
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
    $sql = 'SELECT * FROM campaign WHERE campaignId = :campaignId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':campaignId', $campaignId, PDO::PARAM_INT);
    $stmt->execute();
    $campaignData = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $campaignData;
}
function getGmCampaignIds()
{
    $db = TimeTrackerConnect(); 
    $sql = 'SELECT campaignId, campaignName FROM campaign WHERE gameMasterId = :userId'; 
    $stmt = $db->prepare($sql); 
    $stmt->bindValue(':userId', $_SESSION['userData']['userId'], PDO::PARAM_INT); 
    $stmt->execute(); 
    $campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    $stmt->closeCursor(); 
    return $campaigns; 
}
function getInviteCampaignIds()
{
    $db = TimeTrackerConnect(); 
    $sql = 'SELECT campaignId, userId, playerStatus FROM player_lookup WHERE userId = :userId and playerStatus = 0';
    $stmt = $db->prepare($sql); 
    $stmt->bindValue(':userId', $_SESSION['userData']['userId'], PDO::PARAM_INT); 
    $stmt->execute(); 
    $campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    $stmt->closeCursor(); 
    return $campaigns; 
}
function getPlayerCampaignIds()
{
    $db = TimeTrackerConnect(); 
    $sql = 'SELECT campaignId, userId, playerStatus FROM player_lookup WHERE userId = :userId and playerStatus = 1';
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
function getUserIdByEmail($userEmail)
{
    $db = TimeTrackerConnect();
    $sql = 'SELECT userId FROM user WHERE userEmail = :userEmail';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':userEmail', $userEmail, PDO::PARAM_STR);
    $stmt->execute();
    $campaignData = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $campaignData;
}
function invitePlayer($userId, $campaignId)
{
    // Create a connection object using the TimeTracker connection function
    $db = TimeTrackerConnect();
    // The SQL statement
    $sql = 'INSERT INTO player_lookup (campaignId, userId, playerStatus) 
            VALUES (:campaignId, :userId, 0)';
    // Create the prepared statement using the TimeTracker connection
    $stmt = $db->prepare($sql);
    // The next four lines replace the placeholders in the SQL
    // statement with the actual values in the variables
    // and tells the database the type of data it is
    $stmt->bindValue(':campaignId', $campaignId, PDO::PARAM_INT);
    $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
    // Insert the data
    $stmt->execute();
    // Ask how many rows changed as a result of our insert
    $invite = $stmt->rowCount();
    // Close the database interaction
    $stmt->closeCursor();
    // Return the indication of success
    if($invite == 1)
    {
        return 1;
    } else {
        return 0;
    }
}
function acceptInvite($campaignId)
{
    // Create a connection object using the TimeTracker connection function
    $db = TimeTrackerConnect();
    // The SQL statement
    $sql = 'UPDATE player_lookup SET playerStatus = 1 WHERE userId = :userId and campaignId = :campaignId';
    // Create the prepared statement using the TimeTracker connection
    $stmt = $db->prepare($sql);
    // The next four lines replace the placeholders in the SQL
    // statement with the actual values in the variables
    // and tells the database the type of data it is
    $stmt->bindValue(':campaignId', $campaignId, PDO::PARAM_INT);
    $stmt->bindValue(':userId', $_SESSION['userData']['userId'], PDO::PARAM_INT);
    // Insert the data
    $stmt->execute();
    // Ask how many rows changed as a result of our insert
    $campaignRowsChanged = $stmt->rowCount();
    // Close the database interaction
    $stmt->closeCursor();

    // Return the indication of success
    if($campaignRowsChanged == 1)
    {
        return 1;
    } else {
        return 0;
    }
}
function addTracker($trackerName, $remainingHours, $remainingMinutes, $remainingSeconds, $campaignId)
{
    // Create a connection object using the TimeTracker connection function
    $db = TimeTrackerConnect();
    // The SQL statement
    $sql = 'INSERT INTO duration_trackers (campaignId, trackerName, remainingHours, remainingMinutes, remainingSeconds)
        VALUES (:campaignId, :trackerName, :remainingHours, :remainingMinutes, :remainingSeconds)';
    // Create the prepared statement using the TimeTracker connection
    $stmt = $db->prepare($sql);
    // The next four lines replace the placeholders in the SQL
    // statement with the actual values in the variables
    // and tells the database the type of data it is
    $stmt->bindValue(':campaignId', $campaignId, PDO::PARAM_INT);
    $stmt->bindValue(':trackerName', $trackerName, PDO::PARAM_STR);
    $stmt->bindValue(':remainingHours', $remainingHours, PDO::PARAM_INT);
    $stmt->bindValue(':remainingMinutes', $remainingMinutes, PDO::PARAM_INT);
    $stmt->bindValue(':remainingSeconds', $remainingSeconds, PDO::PARAM_INT);
    // Insert the data
    $stmt->execute();
    // Ask how many rows changed as a result of our insert
    $campainRowsChanged = $stmt->rowCount();
    // Close the database interaction
    $stmt->closeCursor();

    // Return the indication of success
    if($campainRowsChanged == 1)
    {
        return 1;
    } else {
        return 0;
    }
}
function getTrackersByCampaignId($campaignId)
{
    $db = TimeTrackerConnect(); 
    $sql = 'SELECT campaignId, trackerId, trackerName, remainingHours, remainingMinutes, remainingSeconds 
            FROM duration_trackers WHERE campaignId = :campaignId';
    $stmt = $db->prepare($sql); 
    $stmt->bindValue(':campaignId', $campaignId, PDO::PARAM_INT); 
    $stmt->execute(); 
    $trackers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor(); 
    return $trackers; 
}
function getTrackerByName($trackerName)
{
    $db = TimeTrackerConnect(); 
    $sql = 'SELECT campaignId, trackerId, trackerName, remainingHours, remainingMinutes, remainingSeconds 
            FROM duration_trackers WHERE trackerName = :trackerName';
    $stmt = $db->prepare($sql); 
    $stmt->bindValue(':trackerName', $trackerName, PDO::PARAM_STR); 
    $stmt->execute(); 
    $trackers = $stmt->fetch(PDO::FETCH_ASSOC); 
    $stmt->closeCursor(); 
    return $trackers; 
}
function deleteTracker($trackerId)
{
    // Create a connection object from the TimeTracker connection function
    $dataBase = TimeTrackerConnect(); 
    // The SQL statement to be used with the database 
    $sql = 'DELETE FROM duration_trackers WHERE trackerId = :trackerId'; 
    // The next line creates the prepared statement using the TimeTracker connection
    $stmt = $dataBase->prepare($sql);
    // The next lines replace the placeholders in the SQL
    // statement with the actual values in the variables
    // and tells the database the type of data it is
    $stmt->bindValue(':trackerId', $trackerId, PDO::PARAM_INT);
    // The next line runs the prepared statement 
    $stmt->execute(); 
    // Ask how many rows changed as a result of our insert
    $rowsChanged = $stmt->rowCount();
    // The next line closes the interaction with the database 
    $stmt->closeCursor(); 
    // Return the indication of success (rows changed)
    return $rowsChanged;
}
function updateTracker($trackerId, $remainingHours, $remainingMinutes, $remainingSeconds)
{
    // Create a connection object using the TimeTracker connection function
    $db = TimeTrackerConnect();
    // The SQL statement
    $sql = 'UPDATE duration_trackers SET remainingHours = :remainingHours, remainingMinutes = :remainingMinutes, remainingSeconds = :remainingSeconds WHERE trackerId = :trackerId';
    // Create the prepared statement using the TimeTracker connection
    $stmt = $db->prepare($sql);
    // The next four lines replace the placeholders in the SQL
    // statement with the actual values in the variables
    // and tells the database the type of data it is
    $stmt->bindValue(':trackerId', $trackerId, PDO::PARAM_INT);
    $stmt->bindValue(':remainingHours', $remainingHours, PDO::PARAM_INT);
    $stmt->bindValue(':remainingMinutes', $remainingMinutes, PDO::PARAM_INT);
    $stmt->bindValue(':remainingSeconds', $remainingSeconds, PDO::PARAM_INT);
    // Insert the data
    $stmt->execute();
    // Ask how many rows changed as a result of our insert
    $trackerRowsChanged = $stmt->rowCount();
    // Close the database interaction
    $stmt->closeCursor();

    // Return the indication of success
    if($trackerRowsChanged == 1)
    {
        return 1;
    } else {
        return 0;
    }
}
function getInitiativeByCampaignId($campaignId)
{
    $db = TimeTrackerConnect(); 
    $sql = 'SELECT * FROM combat WHERE campaignId = :campaignId';
    $stmt = $db->prepare($sql); 
    $stmt->bindValue(':campaignId', $campaignId, PDO::PARAM_INT); 
    $stmt->execute(); 
    $initiative = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    $stmt->closeCursor(); 
    return $initiative;
}
// This function will check if a tracker name already exists
function checkExistingTracker($trackerName)
{
    // Create a connection object using the TimeTracker connection function
    $db = TimeTrackerConnect();
    // The SQL statement
    $sql = 'SELECT trackerName FROM duration_trackers WHERE trackerName = :trackerName and campaignId = :campaignId';
    // Create the prepared statement using the TimeTracker connection
    $stmt = $db->prepare($sql);
    // The next four lines replace the placeholders in the SQL
    // statement with the actual values in the variables
    // and tells the database the type of data it is
    $stmt->bindValue(':trackerName', $trackerName, PDO::PARAM_STR);
    $stmt->bindValue(':campaignId', $_SESSION['campaignInfo']['campaignId'], PDO::PARAM_INT);
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
function addInitiative($campaignId, $trackerId, $combatName, $initiative, $currentTurn)
{
    // Create a connection object using the TimeTracker connection function
    $db = TimeTrackerConnect();
    // The SQL statement
    $sql = 'INSERT INTO combat (campaignId, trackerId, combatName, initiative, currentTurn)
        VALUES (:campaignId, :trackerId, :combatName, :initiative, :currentTurn)';
    // Create the prepared statement using the TimeTracker connection
    $stmt = $db->prepare($sql);
    // The next four lines replace the placeholders in the SQL
    // statement with the actual values in the variables
    // and tells the database the type of data it is
    $stmt->bindValue(':campaignId', $campaignId, PDO::PARAM_INT);
    $stmt->bindValue(':trackerId', $trackerId, PDO::PARAM_INT);
    $stmt->bindValue(':combatName', $combatName, PDO::PARAM_STR);
    $stmt->bindValue(':initiative', $initiative, PDO::PARAM_INT);
    $stmt->bindValue(':currentTurn', $currentTurn, PDO::PARAM_INT);
    // Insert the data
    $stmt->execute();
    // Ask how many rows changed as a result of our insert
    $campainRowsChanged = $stmt->rowCount();
    // Close the database interaction
    $stmt->closeCursor();

    // Return the indication of success
    if($campainRowsChanged == 1)
    {
        return 1;
    } else {
        return 0;
    }
}
function updateInitiative($combatId, $initiative, $currentTurn)
{
    // Create a connection object using the TimeTracker connection function
    $db = TimeTrackerConnect();
    // The SQL statement
    $sql = 'UPDATE combat SET initiative = :initiative, currentTurn = :currentTurn WHERE combatId = :combatId';
    // Create the prepared statement using the TimeTracker connection
    $stmt = $db->prepare($sql);
    // The next four lines replace the placeholders in the SQL
    // statement with the actual values in the variables
    // and tells the database the type of data it is
    $stmt->bindValue(':combatId', $combatId, PDO::PARAM_INT);
    $stmt->bindValue(':currentTurn', $currentTurn, PDO::PARAM_INT);
    $stmt->bindValue(':initiative', $initiative, PDO::PARAM_INT);
    // Insert the data
    $stmt->execute();
    // Ask how many rows changed as a result of our insert
    $trackerRowsChanged = $stmt->rowCount();
    // Close the database interaction
    $stmt->closeCursor();

    // Return the indication of success
    if($trackerRowsChanged == 1)
    {
        return 1;
    } else {
        return 0;
    }
}
function setCombatStart($campaignId)
{
    // Create a connection object using the TimeTracker connection function
    $db = TimeTrackerConnect();
    // The SQL statement
    $sql = 'UPDATE campaign SET inCombat = 1 WHERE campaignId = :campaignId';
    // Create the prepared statement using the TimeTracker connection
    $stmt = $db->prepare($sql);
    // The next four lines replace the placeholders in the SQL
    // statement with the actual values in the variables
    // and tells the database the type of data it is
    $stmt->bindValue(':campaignId', $campaignId, PDO::PARAM_INT);
    // Insert the data
    $stmt->execute();
    // Ask how many rows changed as a result of our insert
    $trackerRowsChanged = $stmt->rowCount();
    // Close the database interaction
    $stmt->closeCursor();

    // Return the indication of success
    if($trackerRowsChanged == 1)
    {
        return 1;
    } else {
        return 0;
    }
}
function deleteCombat($campaignId)
{
    // Create a connection object from the TimeTracker connection function
    $dataBase = TimeTrackerConnect(); 
    // The SQL statement to be used with the database 
    $sql = 'DELETE FROM combat WHERE campaignId = :campaignId'; 
    // The next line creates the prepared statement using the TimeTracker connection
    $stmt = $dataBase->prepare($sql);
    // The next lines replace the placeholders in the SQL
    // statement with the actual values in the variables
    // and tells the database the type of data it is
    $stmt->bindValue(':campaignId', $campaignId, PDO::PARAM_INT);
    // The next line runs the prepared statement 
    $stmt->execute(); 
    // Ask how many rows changed as a result of our insert
    $rowsChanged = $stmt->rowCount();
    // The next line closes the interaction with the database 
    $stmt->closeCursor(); 
    // Return the indication of success (rows changed)
    return $rowsChanged;
}
function deleteInitiative($combatId)
{
    // Create a connection object from the TimeTracker connection function
    $dataBase = TimeTrackerConnect(); 
    // The SQL statement to be used with the database 
    $sql = 'DELETE FROM combat WHERE combatId = :combatId'; 
    // The next line creates the prepared statement using the TimeTracker connection
    $stmt = $dataBase->prepare($sql);
    // The next lines replace the placeholders in the SQL
    // statement with the actual values in the variables
    // and tells the database the type of data it is
    $stmt->bindValue(':combatId', $combatId, PDO::PARAM_INT);
    // The next line runs the prepared statement 
    $stmt->execute(); 
    // Ask how many rows changed as a result of our insert
    $rowsChanged = $stmt->rowCount();
    // The next line closes the interaction with the database 
    $stmt->closeCursor(); 
    // Return the indication of success (rows changed)
    return $rowsChanged;
}
function endCombat($campaignId)
{
    // Create a connection object using the TimeTracker connection function
    $db = TimeTrackerConnect();
    // The SQL statement
    $sql = 'UPDATE campaign SET inCombat = 0 WHERE campaignId = :campaignId';
    // Create the prepared statement using the TimeTracker connection
    $stmt = $db->prepare($sql);
    // The next four lines replace the placeholders in the SQL
    // statement with the actual values in the variables
    // and tells the database the type of data it is
    $stmt->bindValue(':campaignId', $campaignId, PDO::PARAM_INT);
    // Insert the data
    $stmt->execute();
    // Ask how many rows changed as a result of our insert
    $trackerRowsChanged = $stmt->rowCount();
    // Close the database interaction
    $stmt->closeCursor();

    // Return the indication of success
    if($trackerRowsChanged == 1)
    {
        return 1;
    } else {
        return 0;
    }
}
?>