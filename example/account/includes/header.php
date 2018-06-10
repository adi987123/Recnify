<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>AGP | Recnify</title>

  <!-- [if IE]
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![edndif] -->

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.1/css/mdb.min.css" />
  <link rel="stylesheet" href="../common/css/style.min.css">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  
  <!-- A simple, lightweight JavaScript API for handling cookies -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.2.0/js.cookie.min.js"></script>

  <!-- Simple javascript toast notifications -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <!-- HTML5 Webcam Image Capture Library with Flash Fallback -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
  
  <!-- Cookie to set page functionality between Login Form and Signup Form (refer pages.js for more Information) -->
<?php
    if(isset($_GET["page"]))
    {
?>
        <script>
        Cookies.set('form', "<?php echo $_GET["page"];?>");
        </script>
<?php
    }
?>
  <!-- ********************************************************************************************************** -->

  <!-- AJAX Functionality for form switching between Login Form and Signup Form -->
  <script src="ajax/pages.js"></script>
  <!-- Ajax Functionality for this Application -->
  <script src="ajax/functionality.js"></script>

</head>