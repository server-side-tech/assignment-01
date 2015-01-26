<?php

include_once './error_codes.php';
/*****************************************************************************
                        Functions of first form
 *****************************************************************************/
function createLoginForm(){
    return '<form name="userLogin" action="index.php" method="post">
            Username: <input type="text" name="username" value=""><br>
            Password: <input type="password" name="password" value=""><br>
            <input type="submit" name="login" value="Log in">
        </form>';
}

function isUserLoggedIn(){
    return filter_input(INPUT_POST, "login");
}

function isValidPassword(){
    define("FORM_PASSWORD", "pass123");
    if(isUserLoggedIn()){
        return FORM_PASSWORD === filter_input(INPUT_POST, "password");
    }  else { // case form is not submitted, return false.
        return FALSE;
    }
}

function processLoginForm(){
    //$output = "";
    
    if(isUserLoggedIn()){
        if(isValidPassword()){
            /*clear html content*/
            '<script type="text/javascript"> document.body.innerHTML = ""; </script>';
            
            /* set status to success*/
            $state = Status::UserLoggedInSuccess;
        }else{
            echo '<p class="fail">Password was incorrect</p>';
            $state = Status::PasswordNotMatched;
        }
    }else{
        $state = Status::FormNotSubmitted;
    }
    
    return $state;
}

/*****************************************************************************
                        Functions of second form
 *****************************************************************************/
function createUpdateInfoForm(){
    return '<form name="updateInfo" action="index.php" method="get">
            Name: <input type="text" name="name" value=""><br>
            Email: <input type="email" name="email" value=""><br>
            <select name="privacy">
                <option value="Public">Public</option>
                <option value="Private">Private</option>
            </select><br>
            <input type="submit" name="update" value="Update">
        </form>';
}

function isFormSubmitted(){
    return filter_input(INPUT_GET, "update");
}

function getPrivacy(){
    return filter_input(INPUT_GET, "privacy");
}
function getNewName(){
    return filter_input(INPUT_GET, "name");
}

function getEmail(){
    return filter_input(INPUT_GET, "email");
}

function processUpdateInfoForm(){
    $output = "";
    if(isFormSubmitted()){
        $state = Status::UpdateInfoSucess;
        
        switch (getPrivacy()){
            case "Public":
                $output = "New Name: ".getNewName()."<br>";
                $output .= "Email: ".getEmail()."<br>";
                $output .= "Privacy: Public";
                break;
            case "Private":
                $output = "user info is private";
                break;
            default :
                $status = Status::InvalidPrivacy;
        }
    }else{
        $state = Status::FormNotSubmitted;
    }
    
    return array($state, $output);
}

$loginState = processLoginForm();
list($updateState, $formOutput) = processUpdateInfoForm();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Basic Form</title>
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body>
        <?php
            if(Status::UserLoggedInSuccess !== $loginState){
                /* This case means either user did not log in or user logged in but password is 
                 * incorrect. In both cases, display the login form again */
                echo createLoginForm();
                
                /* Display the output after processing second form.*/
                if(Status::UpdateInfoSucess === $updateState){
                    echo $formOutput;
                }
                
            }else{
                /* If user logged in successfully, display second form to update the information. */
                echo createUpdateInfoForm();
            }
        ?>
    </body>
</html>
