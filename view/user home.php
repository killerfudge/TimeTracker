<?php
if(!isset($_SESSION['loggedin']))
{
    header('Location: /TimeTracker/');
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
            <h2>Game Master</h2>
            <noscript>
                <p><strong>JavaScript Must Be Enabled to Use this Page.</strong></p>
            </noscript>
            <table id="gmCampaignDisplay">
                <thead>Campaign Name</thead>
                <tbody>
                    <?php echo $gmCampaignList ?>
                </tbody>
            </table>
        </article>
        <script src="../js/campaigns.js"></script>
    </body>
</html>