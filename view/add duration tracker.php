<?php
if(!isset($_SESSION['loggedin']))
{
    if($_SERVER['HTTP_HOST'] == 'localhost')
    {
        header('Location: /TimeTracker/');
    }
    else
    {
        header('Location: /');
    }
}
if(!isset($_SESSION['campaignInfo']))
{
    if($_SERVER['HTTP_HOST'] == 'localhost')
    {
        header('Location: /TimeTracker/campaign/');
    }
    else
    {
        header('Location: /campaign/');
    }
}
?><!DOCTYPE html>
<html lang="en">
    <head>
        <title>Add Duration Tracker</title>
        <link rel="stylesheet" href="/<?php if($_SERVER['HTTP_HOST'] == 'localhost'){echo "TimeTracker/";} ?>css/styles.css" media="screen">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <article>
            <h1>Add Duration Tracker For <?php echo $_SESSION['campaignInfo']['campaignName'] ?></h1>
            <?php
                if (isset($message)) 
                {
                    echo $message;
                }
            ?>
            <form id='createTracker' method="POST" action="/<?php if($_SERVER['HTTP_HOST'] == 'localhost'){echo "TimeTracker/";} ?>campaign/">
                <label for='trackerName'>Name for Duration Tracker</label>
                <input type='text' name='trackerName' required <?php if(isset($trackerName)){ echo "value='$trackerName'";} ?>>
                <label for='remainingHours'>Hours</label>
                <input type='number' name='remainingHours' min='0' <?php if(isset($remainingHours)){ echo "value='$remainingHours'";} ?>>
                <label for='remainingMinutes'>minutes</label>
                <input type='number' name='remainingMinutes' min='0' max='60' <?php if(isset($remainingMinutes)){ echo "value='$remainingMinutes'";} ?>>
                <label for='remainingSeconds'>seconds</label>
                <input type='number' name='remainingSeconds' min='0' max='60' <?php if(isset($remainingSeconds)){ echo "value='$remainingSeconds'";} ?>>
                <?php
                    if($_SESSION['campaignInfo']['inCombat'] == 1)
                    {
                        echo "<label for='initiative'>initiative</label>";
                        echo "<input type='number' name='initiative' "; if(isset($initiative)){echo "value='$initiative'";} echo " >";
                    }
                ?>
                <button type='submit'>Create</button>
                <input type='hidden' name='action' value='CreateTracker'>
            </form>
            <form method='POST' action='/<?php if($_SERVER['HTTP_HOST'] == 'localhost'){echo "TimeTracker/";} ?>campaign/'>
                <button type='submit'>Cancel</button>
                <input type='hidden' name='action' value='returnHome'>
            </form>
        </article>
    </body>
</html>