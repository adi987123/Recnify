<?php
  ob_start();
  session_start();
  if( isset($_SESSION["user_email"]) && !empty($_SESSION['user_email']) )
  {
    header("Location: ".(isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST']."/example/");
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

<?php
  require_once("../common/includes/main-content.php");    //    includes Main HTML body
  require_once("../common/includes/footer.php");    //    includes FOOTER contents
  require_once("../common/includes/scripts.php");   //    includes all JS Scripts
  require_once("../common/includes/toastrError.php");   //    Toaster Notification / Error when needed.
?>

</body>
</html>