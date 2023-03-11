<?php
// This function will handle registering new users to the site
function regUser($userEmail, $userPassword)
{
    // Create a connection object using the TimeTracker connection function
    $db = TimeTrackerConnect();
    // The SQL statement
    $sql = 'INSERT INTO user (userEmail, userPassword)
        VALUES (:userEmail, :userPassword)';
    // Create the prepared statement using the TimeTracker connection
    $stmt = $db->prepare($sql);
    // The next four lines replace the placeholders in the SQL
    // statement with the actual values in the variables
    // and tells the database the type of data it is
    $stmt->bindValue(':userEmail', $userEmail, PDO::PARAM_STR);
    $stmt->bindValue(':userPassword', $userPassword, PDO::PARAM_STR);
    // Insert the data
    $stmt->execute();
    // Ask how many rows changed as a result of our insert
    $rowsChanged = $stmt->rowCount();
    // Close the database interaction
    $stmt->closeCursor();
    // Return the indication of success (rows changed)
    return $rowsChanged;
}
// This function will check if an email already exists
function checkExistingEmail($userEmail)
{
    // Create a connection object using the TimeTracker connection function
    $db = TimeTrackerConnect();
    // The SQL statement
    $sql = 'SELECT userEmail FROM user WHERE userEmail = :userEmail';
    // Create the prepared statement using the TimeTracker connection
    $stmt = $db->prepare($sql);
    // The next four lines replace the placeholders in the SQL
    // statement with the actual values in the variables
    // and tells the database the type of data it is
    $stmt->bindValue(':userEmail', $userEmail, PDO::PARAM_STR);
    // Insert the data
    $stmt->execute();
    // Get the data from the database
    $matchEmail = $stmt->fetch(PDO::FETCH_NUM);
    // Close the database interaction
    $stmt->closeCursor();
    // Return the indication of success 
    if(empty($matchEmail))
    {
        return 0;
    } else 
    {
        return 1;
    }
}
// Get user data based on an email address
function getUser($userEmail)
{
    $db = TimeTrackerConnect();
    $sql = 'SELECT userId, userEmail, userPassword FROM user WHERE userEmail = :userEmail';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':userEmail', $userEmail, PDO::PARAM_STR);
    $stmt->execute();
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $userData;
}
?>