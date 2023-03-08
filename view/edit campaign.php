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
        <title>Edit <?php echo $_SESSION['campaignInfo']['campaignName'] ?></title>
        <link rel="stylesheet" href="/TimeTracker/css/styles.css" media="screen">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <article>
            <h1>Edit <?php echo $_SESSION['campaignInfo']['campaignName'] ?></h1>
            <?php
                if (isset($message)) 
                {
                    echo $message;
                }
            ?>
            <form id='campaignEditter' method='POST' action='/TimeTracker/campaign/'>
                <label id='nameLabel' for='name'>Campaign Name</label>
                    <input type='text' name='name' id='name' value='<?php echo $_SESSION['campaignInfo']['campaignName'] ?>'>
                <label id='hoursLabel' for='hours'>Set Current Hours</label>
                    <input type='number' name='hours' id='hours' value='<?php echo $_SESSION['campaignInfo']['currentHours'] ?>'>
                <label id='minutesLabel' for='minutes'>Set Current Minutes</label>
                    <input type='number' name='minutes' id='minutes' value='<?php echo $_SESSION['campaignInfo']['currentMinutes'] ?>'>
                <label id='secondsLabel' for='seconds'>Set Current Seconds</label>
                    <input type='number' name='seconds' id='seconds' value='<?php echo $_SESSION['campaignInfo']['currentSeconds'] ?>'>
                <button type='submit'>Edit</button>
                <input type='hidden' name='action' value='editCampaign'>
            </form>
            <form method='POST' action='/TimeTracker/campaign/'>
                <button type='submit'>Cancel</button>
                <input type='hidden' name='action' value='home'>
            </form>
            <h2>Add Players</h2>
            <?php
                if (isset($message)) 
                {
                    echo $message;
                }
            ?>
            <form id='addPlayers' method='POST' action='/TimeTracker/campaign/'>
                <label id='playerEmailLabel' for='playerEmail'>Player Email</label>
                    <input type='email' name='playerEmail' id='playerEmail' required>
                <button type='submit'>Add Player</button>
                <input type='hidden' name='action' value='addPlayer'>
                <input type='hidden' name='campaignId' value='<?php echo $_SESSION['campaignInfo']['campaignId'] ?>'>
            </form>
        </article>
    </body>
</html>