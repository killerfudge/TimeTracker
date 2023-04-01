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
?><!DOCTYPE html>
<html lang="en">
    <head>
        <title>Home Page</title>
        <link rel="stylesheet" href="/TimeTracker/css/styles.css" media="screen">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <a id='logout_link' class='right' href='/TimeTracker/?action=Logout'>Logout</a>
        <form id='create_form_button' method="POST" action="/TimeTracker/campaign/">
            <button id='create_campaign' type='submit'>Create Campaign</button>
            <input type="hidden" name="action" value="creation start">
        </form>
        <article>
            <h1>Campaigns</h1>
            <h2>Invites</h2>
            <table id="inviteCampaignDisplay">
                <thead>Campaign Name</thead>
                <tbody>
                    <?php echo $inviteCampaignList ?>
                </tbody>
            </table>
            <h2>Game Master</h2>
            <table id="gmCampaignDisplay">
                <thead>Campaign Name</thead>
                <tbody>
                    <?php echo $gmCampaignList ?>
                </tbody>
            </table>
            <h2>Player</h2>
            <table id='playerCampaignDisplay'>
                <thead>Campaign Name</thead>
                <tbody>
                    <?php echo $playerCampaignList ?>
                </tbody>
            </table>
        </article>
    </body>
</html>