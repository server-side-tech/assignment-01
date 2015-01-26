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
            Username: <input type="text" name="username" value="'.$name.'"><br>
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
        $state = Status::BlANK_FIELD;
        
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
            $state = Status::INVALID_PASSWORD;            
        }
        
        if(empty($errors)){
            /*clear html content*/
            '<script type="text/javascript"> document.body.innerHTML = ""; </script>';
            
            /* set status to success*/
            $state = Status::LOGIN_SUCCESS;
        }
    }
    
    return array($state, $errors, $username);
}

/*****************************************************************************
                        Functions of second form
 *****************************************************************************/
function createUpdateInfoForm($newName, $email){
    return '<form name="updateInfo" action="index.php" method="get">'
    . 'Name: <input type="text" name="name" value="'.$newName.'"><br>
            Email: <input type="email" name="email" value="'.$email.'"><br>
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

function validateNewName(){
    $state="";
    $errors=array();
    $newName = getNewName();
    if (empty(trim($newName))){
        $errors["name"] = "New name can't be blank";
        $state = Status::BlANK_FIELD;

    }else{
        /* Validate new does not contain any special characters or numbers */
        if(!preg_match('/^[A-Za-z][[A-Za-z ]*$/', $newName)){
            $errors["name"] = "New name must start with alphabetet character & might contain spaces. No numbers or special characters";
            $state = Status::INVALID_NEW_NAME;
            
            /* Encode any HTML special characters that user might entered (like <>) to display again as it is.*/
            $newName = htmlspecialchars($newName);
        }
    }
    
    return array($state,$errors,$newName);
}

function validateEmail($errors){
    $state="";
    $email = getEmail();
    if (empty(trim($email))){
        $errors["email"] = "Email can't be blank";
        $state = Status::BlANK_FIELD;

    }else{
        /* Validate new does not contain any special characters or numbers */
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors["email"] = "Email must have following format example@domain.com";
            $state = Status::INVALID_EMAIL;
            
            /* Encode any HTML special characters that user might entered (like <>) to display again as it is.*/
            $email = htmlspecialchars($email);
        }
    }
    
    return array($state,$errors,$email);    
    
}

function processUpdateInfoForm(){
    $output = "";
    $state = NULL;
    $errors = array();
    $newName = "";
    $email = "";
    
    if(isFormSubmitted()){
        
        $state = Status::FORM_SUBMITTED;
        list($state,$errors,$newName) = validateNewName();
        list($state, $errors, $email) = validateEmail($errors);
        switch (getPrivacy()){
            case "Public":
                $output = "New Name: ".$newName."<br>";
                $output .= "Email: ".$email."<br>";
                $output .= "Privacy: Public";
                break;
            case "Private":
                $output = "user info is private";
                break;
            default :
                $status = Status::INVALID_PRIVACY;
                $errors["privacy"] = "Invalid Privacy";
        }
        
        $state = empty($errors)? Status::UPDATE_INFO_SUCCESS:$state;
    }else{
        $state = Status::FormNotSubmitted;
    }
    
    return array($state, $errors,$output,$newName,$email);
}

list($loginState, $loginErrors, $username) = processLoginForm();
list($updateState, $updateErrors,$formOutput,$newName,$email) = processUpdateInfoForm();

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
            if(Status::LOGIN_SUCCESS !== $loginState){
               
                if(empty($updateErrors)){
                    /* This case means either user did not log in or user logged in but password is 
                     * incorrect. In both cases, display the login form again */
                   echo formErrors($loginErrors).createLoginForm($username);

                   /* Display the output after processing second form.*/
                   if(Status::UPDATE_INFO_SUCCESS === $updateState){
                       echo "<br>".$formOutput;
                   }
                }else{
                   
                       /* This case means that user entered wrong input in the second form while updating
                        * the new information*/
                      echo formErrors($updateErrors).createUpdateInfoForm($newName, $email);
                   }
            }else{
                /* If user logged in successfully, display second form to update the information. */
                echo createUpdateInfoForm($newName,$email);
            }
        ?>
    </body>
</html>
