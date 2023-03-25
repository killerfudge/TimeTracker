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
        <title>Add Combatant</title>
        <link rel="stylesheet" href="/TimeTracker/css/styles.css" media="screen">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <article>
            <h1>Add Combatant For <?php echo $_SESSION['campaignInfo']['campaignName'] ?></h1>
            <?php
                if (isset($message)) 
                {
                    echo $message;
                }
            ?>
            <form id='createTracker' method="POST" action="/TimeTracker/campaign/">
                <label for='combatantName'>Name</label>
                <input type='text' name='combatantName' required <?php if(isset($combatantName)){ echo "value='$combatantName'";} ?>>
                <label for='initiative'>initiative</label>
                <input type='number' name='initiative' min='0' <?php if(isset($initiative)){ echo "value='$initiative'";} ?>>
                <button type='submit'>Create</button>
                <input type='hidden' name='action' value='createCombatant'>
            </form>
            <form method='POST' action='/TimeTracker/campaign/'>
                <button type='submit'>Cancel</button>
                <input type='hidden' name='action' value='returnHome'>
            </form>
        </article>
    </body>
</html>