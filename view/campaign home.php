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
        <meta http-equiv="refresh" content="5"/>
    </head>
    <body>
        <a id='logout_link' class='right' href='/TimeTracker/?action=Logout'>Logout</a>
        <a id='home_link' href='/TimeTracker/campaign/?action=leaveCampaign'>Campaign List</a>
        <article>
            <h1><?php echo $_SESSION['campaignInfo']['campaignName'] ?></h1>
            <noscript>
                <p><strong>JavaScript Must Be Enabled to Use this Page.</strong></p>
            </noscript>
            <?php 
                if($_SESSION['campaignInfo']['gameMasterId'] == $_SESSION['userData']['userId'] && $_SESSION['campaignInfo']['inCombat'] == 0)
                {
                    echo "<form method='POST' action='/TimeTracker/campaign/'>";
                    echo "<button type='submit'>Edit Campaign</button>";
                    echo "<input type='hidden' name='action' value='edit'>";
                    echo "</form>";
                    echo "<form method='POST' action='/TimeTracker/campaign/'>";
                    echo "<button type='submit'>Start Combat</button>";
                    echo "<input type='hidden' name='action' value='startCombat'>";
                    echo "<input type='hidden' id='campaignId' name='campaignId' value='".$_SESSION['campaignInfo']['campaignId']."'>";
                    echo "</form>";
                    echo "<div id='wrapper'>";
                    echo "<button type='button' id='secs'>6 seconds</button>";
                    echo "<button type='button' id='min'>1 minute</button>";
                    echo "<button type='button' id='mins'>30 minutes</button>";
                    echo "<button type='button' id='hour'>1 hour</button>";
                    echo "<button type='button' id='hours'>8 hours</button>";
                    echo "</div>";
                }
                elseif($_SESSION['campaignInfo']['gameMasterId'] == $_SESSION['userData']['userId'])
                {
                    echo "<form method='POST' action='/TimeTracker/campaign/'>";
                    echo "<button type='submit'>End Combat</button>";
                    echo "<input type='hidden' name='action' value='endCombat'>";
                    echo "<input type='hidden' name='campaignId' value='".$_SESSION['campaignInfo']['campaignId']."'>";
                    echo "</form>";
                }
            ?>
            <p id="time">Time: <?php echo $_SESSION['campaignInfo']['currentHours'] ?>:<?php echo $_SESSION['campaignInfo']['currentMinutes'] ?>:<?php echo $_SESSION['campaignInfo']['currentSeconds'] ?></p>
            <form method='POST' action='/TimeTracker/campaign/'>
                <button type='submit' id='addTracker'>Add duration tracker</button>
                <input type='hidden' name='action' value='addTrackerView'>
            </form>
            <?php
                if($_SESSION['campaignInfo']['inCombat'] == 0)
                {
                    echo "<table id='trackers'>";
                    echo "<thead>Duration Trackers</thead>";
                    echo "<tbody>";
                    echo $trackerList;
                    echo "</tbody>";
                    echo "</table>";
                }
                else
                {
                    echo "<form method='POST' action='/TimeTracker/campaign/'>";
                    echo "<button type='submit'>End Turn</button>";
                    echo "<input type='hidden' name='action' value='endTurn'>";
                    echo "<input type='hidden' name='campaignId' value='".$_SESSION['campaignInfo']['campaignId']."'>";
                    echo "</form>";
                    echo "<form method='POST' action='/TimeTracker/campaign/'>";
                    echo "<button type='submit'>Add combatant</button>";
                    echo "<input type='hidden' name='action' value='addCombatantView'>";
                    echo "</form>";
                    echo "<table id='turnOrder'>";
                    echo "<thead>Turn Order</thead>";
                    echo "<tbody>";
                    echo $turnOrder;
                    echo "</tbody>";
                    echo "</table>";
                }
            ?>
        </article>
        <script src="../js/campaigns.js"></script>
    </body>
</html>