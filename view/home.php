<!DOCTYPE html>
<html lang="en">
    <head>
        <title>TimeTracker</title>
        <link rel="stylesheet" href="/<?php if($_SERVER['HTTP_HOST'] == 'localhost'){echo "TimeTracker/";} ?>css/styles.css" media="screen">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <article>
            <h1 class="center">TimeTracker</h1>
            <h2>Login</h2>
            <?php
                if (isset($message)) 
                {
                    echo $message;
                }
            ?>
            <form id="login" class="center" method="POST" action="/<?php if($_SERVER['HTTP_HOST'] == 'localhost'){echo "TimeTracker/";} ?>">
                <label id="loginEmail" for="userEmail" class="left">Email</label>
                <input name="userEmail" id="userEmail" type="email" required <?php if(isset($userEmail)){echo "value='$userEmail'";}  ?>>
                <span id="passwordSpan" class="left">Password must be at least 8 characters and have at least 1 uppercase character, 1 number and 1 special character.</span>
                <label id="loginPassword" for="userPassword" class="left">Password</label>
                <input name="userPassword" id="userPassword" type="password" required pattern="(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$">
                <button type="submit" id="loginButton">login</button>
                <input type="hidden" name="action" value="Login">
            </form>
            <h2>No Account? Register Now!</h2>
            <form id="register" method="POST" action="/<?php if($_SERVER['HTTP_HOST'] == 'localhost'){echo "TimeTracker/";} ?>">
                <label id="emailRegister" for="userEmail">Email</label>
                <input name="userEmail" id="userEmail" type="email" required <?php if(isset($userEmail)){echo "value='$userEmail'";}  ?>>
                <span id="passwordSpan">Password must be at least 8 characters and have at least 1 uppercase character, 1 number and 1 special character.</span>
                <label id="passwordRegister" for="userPassword">Password</label>
                <input name="userPassword" id="userPassword" type="password" required pattern="(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$">
                <button type="submit">Register</button>
                <!-- Add the action name - value pair -->
                <input type="hidden" name="action" value="register">
            </form>
        </article>
    </body>
</html>
<?php unset($_SESSION['message']); ?>