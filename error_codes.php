<?php

abstract class Status{
    const __default = self::UserNotLoggedIn;
    
    const UserLoggedInSuccess = 0;
    const PasswordNotMatched= 1;
    const UserNotLoggedIn = 2;

}
?>

