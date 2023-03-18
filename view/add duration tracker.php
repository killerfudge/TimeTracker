<?php
if(!isset($_SESSION['loggedin']))
{
    header('Location: /TimeTracker/');
}
if(!isset($_SESSION['campaignInfo']))
{
    header('Location: /TimeTracker/campaign/');
}
?><!DOCTYPE html>
<html lang="en">
    <head>
        <title>Add Duration Tracker</title>
        <link rel="stylesheet" href="/TimeTracker/css/styles.css" media="screen">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <article>
            <h1>Add Duration Tracker For <?php echo $_SESSION['campaignInfo']['campaignName'] ?></h1>
            <form id='createTracker' method="POST" action="/TimeTracker/campaign/">
                <label for='trackerName'>Name for Duration Tracker</label>
                <input type='text' name='trackerName' required <?php if(isset($trackerName)){ echo "value='$trackerName'";} ?>>
                <label for='remainingHours'>Hours</label>
                <input type='number' name='remainingHours' min='0' <?php if(isset($remainingHours)){ echo "value='$remainingHours'";} ?>>
                <label for='remainingMinutes'>minutes</label>
                <input type='number' name='remainingMinutes' min='0' max='60' <?php if(isset($remainingMinutes)){ echo "value='$remainingMinutes'";} ?>>
                <label for='remainingSeconds'>seconds</label>
                <input type='number' name='remainingSeconds' min='0' max='60' <?php if(isset($remainingSeconds)){ echo "value='$remainingSeconds'";} ?>>
                <button type='submit'>Create</button>
                <input type='hidden' name='action' value='CreateTracker'>
            </form>
        </article>
    </body>
</html>