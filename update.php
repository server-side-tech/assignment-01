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

dumpPostArray(TRUE);
if(isValidPassword()){
    echo 'Password is valid';
}else {
    echo "Username and Password entered don't match";
}
?>

