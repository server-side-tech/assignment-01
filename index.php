<?php

include_once './error_codes.php';

function formErrors($errors=array()){
    $output = "";
    if(!empty($errors)){
        $output .= '<div class="fail">Pleae fix the following errors:<br>';
        $output .= '<ul>';
        foreach ($errors as $key => $value){
            $output .= ("<li>".$value."</li>");
        }
        $output .= "</ul></div><br>";
    }
    
    return $output;
}

/*****************************************************************************
                        Functions of first form
 *****************************************************************************/
function createLoginForm($name=""){
    return '<form name="userLogin" action="index.php" method="post">
            Username: <input type="text" name="username" value='.$name.'><br>
            Password: <input type="password" name="password" value=""><br>
            <input type="submit" name="login" value="Log in">
        </form>';
}

function isUserLoggedIn(){
    return filter_input(INPUT_POST, "login");
}

function isValidPassword(){
    define("FORM_PASSWORD", "pass123");
    return FORM_PASSWORD === filter_input(INPUT_POST, "password");
}

function getUsername(){
    return $_POST["username"];
}

function validateUserName(){
    $state="";
    $errors=array();
    $username = getUsername();
    if (empty(trim($username))){
        $errors["username"] = "username can't be blank";
        $state = Status::BlankField;
        
        /* For security purpose only, add Password error as well even if it is corrcect.*/
        $errors["password"] = "Password was incorrect";
    }else{
        /* Validate username does not contain special characters except underscore. */
        if(!preg_match('/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/', $username)){
            $errors["username"] = "username must start with alphabetet character & might followed by underscore or numbers";
            $state = Status::INVALID_USERNAME;
            
            /* Encode any HTML special characters that user might entered (like <>) to display again as it is.*/
            $username = htmlspecialchars($username);
        }
    }
    
    return array($state, $errors, $username);
}

function processLoginForm(){
    $errors = array();
    $username = "";
    /* Check if the user cicks on log in button to submit the first form. */
    if(!isUserLoggedIn()){
        $state = Status::FormNotSubmitted;
    }else{
        
        list($state, $errors,$username) = validateUserName();
        
        if(!isValidPassword()){
            $errors["password"] = "Password was incorrect";
            $state = Status::PasswordNotMatched;            
        }
        
        if(empty($errors)){
            /*clear html content*/
            '<script type="text/javascript"> document.body.innerHTML = ""; </script>';
            
            /* set status to success*/
            $state = Status::UserLoggedInSuccess;
        }
    }
    
    return array($state, $errors, $username);
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
    $state = NULL;
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

list($loginState, $errors, $username) = processLoginForm();
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
                echo formErrors($errors).createLoginForm($username);
                
                /* Display the output after processing second form.*/
                if(Status::UpdateInfoSucess === $updateState){
                    echo $formOutput;
                }
                
            }else{
                /* If user logged in successfully, display second form to update the information. */
                echo formErrors($errors).createUpdateInfoForm();
            }
        ?>
    </body>
</html>
