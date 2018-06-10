<?php
ob_start();
session_start();
if( isset( $_SESSION["user_id"] ) )
{
    require_once("common/classes/Database.php");
    require_once("common/classes/LoginSession.php");
    $LoginSession = new LoginSession();
    $LoginSession->processLogout();
}
else
{
    header("Location: ".(isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST']."/example/");
}
?>
