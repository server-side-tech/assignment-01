<?php
function isUserLoggedIn(){
    return filter_input(INPUT_POST, "login");
}

function isFormSubmitted(){
    return filter_input(INPUT_POST, "submit");
}

function isValidPassword(){
    define("FORM_PASSWORD", "pass123");
    if(isUserLoggedIn()){
        return FORM_PASSWORD === filter_input(INPUT_POST, "password");
    }  else { // case form is not submitted, return false.
        return FALSE;
    }
}

function dumpPostArray($required){
    if($required && isset($_POST)){
        var_dump($_POST);
        echo "<br>";
    }
}

function createLoginForm(){
    return '<form name="userLogin" action="index.php" method="post">
            Username: <input type="text" name="username" value=""><br>
            Password: <input type="password" name="password" value=""><br>
            <input type="submit" name="login" value="Log in">
        </form>';
}
//dumpPostArray(TRUE);
if(isUserLoggedIn()){
    if(isValidPassword()){
        $loginOutput = '<p class="success">Password is valid</p>';
    }else{
        $loginOutput = '<p class="fail">Password was incorrect</p>';
    }
}else{
    $loginOutput = "";
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Basic Form</title>
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body>
<!--        Create initial login form -->
        <?php
            echo $loginOutput;
            echo createLoginForm();
        ?>
    </body>
</html>
