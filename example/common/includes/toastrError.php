<!-- Toast Notification / Error (eg. account/ajax/functionality.js -> line 41) -->
<?php
    if(isset($_GET["error"]))
    {
        switch($_GET["error"])
        {
            case    '' : ;
            break;
            
            case    password_verify( "user-unauthorized" , $_GET["error"] ) : echo "<script>$(document).ready(function(){toastr.warning('User Unauthorized');})</script>";
            break;
            
            case    password_verify( "image-recognition-error" , $_GET["error"] ) : echo "<script>$(document).ready(function(){toastr.error('Unable to Recognize Image');})</script>";
            break;
            
            case    password_verify( "user-not-found" , $_GET["error"] ) : echo "<script>$(document).ready(function(){toastr.error('User Not Found');})</script>";
            break;
        }
    }
?>