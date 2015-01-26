<?php

abstract class Status{
    const __default = self::FormNotSubmitted;

    /* Common status codes between both forms. */
    const FormNotSubmitted = 20;
    const BlankField =21;
    
    /* status codes for login form. */
    const UserLoggedInSuccess = 0;
    const PasswordNotMatched= 1;
    const UserNotLoggedIn = 2;
    const INVALID_USERNAME =3;
    
    /* status codes for update information form. */
    const InvalidEmail = 10;
    const InvalidName = 11;
    const InvalidPrivacy = 12;
    const UpdateInfoSucess = 13;

}
?>

