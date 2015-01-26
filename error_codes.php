<?php

abstract class Status{
    const __default = self::FormNotSubmitted;

    /* Common status codes between both forms. */
    const FormNotSubmitted = 20;
    const FORM_SUBMITTED =21;
    const BlANK_FIELD =22;
    
    /* status codes for login form. */
    const LOGIN_SUCCESS = 0;
    const INVALID_PASSWORD= 1;
    const INVALID_USERNAME =3;
    
    /* status codes for update information form. */
    const INVALID_EMAIL = 10;
    const INVALID_NEW_NAME = 11;
    const INVALID_PRIVACY = 12;
    const UPDATE_INFO_SUCCESS = 13;

}
?>

