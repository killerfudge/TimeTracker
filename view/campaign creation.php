<?php
if(!isset($_SESSION['loggedin']))
{
    header('Location: /TimeTracker/');
}
?><!DOCTYPE html>
<html lang="en">
    <head>
        <title>Create Campaign</title>
        <link rel="stylesheet" href="/TimeTracker/css/styles.css" media="screen">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <article>
            <h1>Create Campaign</h1>
            <?php
                if (isset($message)) 
                {
                    echo $message;
                }
            ?>
            <form id='create_form' method="POST" action="/TimeTracker/campaign/">
                <label for='campaignName'>Campaign Name</label>
                <input type='text' name='campaignName' required>
                <label for='startingHours'>Starting campaign hours</label>
                <input type='number' name='startingHours' min='0' max='24'>
                <label for='startingMinutes'>Starting campaign minutes</label>
                <input type='number' name='startingMinutes' min='0' max='60'>
                <label for='startingSeconds'>Starting campaign seconds</label>
                <input type='number' name='startingSeconds' min='0' max='60'>
                <button type='submit'>Create</button>
                <input type='hidden' name='action' value='Create'>
            </form>
        </article>
    </body>
</html>