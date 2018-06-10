<?php
  ob_start();
  session_start();
  if( !isset($_SESSION["user_email"]) || empty($_SESSION['user_email']) )
  {
    header("Location: ".(isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST']."/example/account/");
    exit();
  }
?>
<!DOCTYPE html>
<html lang="en">

<?php
  $page = "index";
  require_once("includes/header.php");    //    include all HTML header content
?>

<body>

<!-- THIS IS TEST APPLICATION WHERE AFTER EVERY ANSWER SAVED USER FACE IS CHECKED IF VERIFIED CONTINUED OR ELSE VERIFICATION PAGE RELOADS -->
<?php
  if( isset($_SESSION["verification"]) && $_SESSION["verification"] == false )
  {
    require_once("includes/forms/verifyUser.php");
  }
  else
  {
    require_once("../common/includes/main-content.php");    //    includes Main HTML body
  }
  require_once("../common/includes/footer.php");    //    includes FOOTER contents
  require_once("../common/includes/scripts.php");   //    includes all JS Scripts
  require_once("../common/includes/toastrError.php");   //    Toaster Notification / Error when needed.
?>
</body>
</html>