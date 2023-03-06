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
        <title><?php echo $_SESSION['campaignInfo']['campaignName'] ?></title>
        <link rel="stylesheet" href="/TimeTracker/css/styles.css" media="screen">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <a id='logout_link' class='right' href='/TimeTracker/?action=Logout'>Logout</a>
        <a id='home_link' href='/TimeTracker/campaign/?action=leaveCampaign'>Campaign List</a>
        <article>
            <h1><?php echo $_SESSION['campaignInfo']['campaignName'] ?></h1>
            <?php 
                if($_SESSION['campaignInfo']['gameMasterId'] == $_SESSION['userData']['userId'])
                {
                    echo "<form method='POST' action='/TimeTracker/campaign/'>";
                    echo "<button type='submit'>Edit Campaign</button>";
                    echo "<input type='hidden' name='action' value='edit'>";
                    echo "</form>";
                    echo "<button type='button' id='secs'>6 seconds</button>";
                    echo "<button type='button' id='min'>1 minute</button>";
                    echo "<button type='button' id='mins'>30 minutes</button>";
                    echo "<button type='button' id='hour'>1 hour</button>";
                    echo "<button type='button' id='hours'>8 hours</button>";
                }
            ?>
            <p id="time">Time: <?php echo $_SESSION['campaignInfo']['currentHours'] ?>:<?php echo $_SESSION['campaignInfo']['currentMinutes'] ?>:<?php echo $_SESSION['campaignInfo']['currentSeconds'] ?></p>
            <table id='trackers'></table>
        </article>
    </body>
</html>