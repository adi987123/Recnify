<?php
    require("../../common/classes/Database.php");
    if( isset($_POST["function"]) && !empty($_POST["function"]) )
    {
        switch( $database->escape_string($_POST["function"]) )
        {
            case    'login' :   if( isset($_POST["user_email"]) && !empty($_POST["user_email"]) )
                        {
                            require("../../common/classes/LoginSession.php");
                            $LoginSession = new LoginSession();
                            $result = $LoginSession->processLogin( $database->escape_string($_POST["user_email"]) );
                            echo $result;
                        }
            break;
            
            case    'register' :   if( isset($_POST["user_name"]) && !empty($_POST["user_name"]) && isset($_POST["user_email"]) && !empty($_POST["user_email"]) )
                        {
                            require("../../common/classes/Register.php");
                            $Register = new Register();
                            $result = $Register->processRegister( $database->escape_string($_POST["user_name"]) , $database->escape_string($_POST["user_email"]) );
                            echo $result;
                        }
            break;
        }
    }
?>