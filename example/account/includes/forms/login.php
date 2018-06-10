<!-- Login Form -->
<div class="card-body text-center">
    <div class="ml-4 mr-5 mb-3 md-form" id="form">
        <input type="email" name="user_email" id="user_email" class="form-control">
        <label for="user_email">Email</label>
        <div id="email-error"></div>
    </div>
    
    <div class="offset-md-1" id="img_camera" style="width:390px; height:275px;">
    </div>
    <!-- CALL to loginData() (functionality.js) -->
    <a class="btn btn-elegant" id="login" onclick="loginData()"><h4 class="mt-2"><i class="fas fa-sign-in-alt"></i> Login</h4></a>
    <div class="text-center" id="extern">
    <!-- AJAX CALL to SignUpForm() to switch forms on UI (pages.js) -->
        Don't have account ? <a onclick="SignUpForm()"><strong>Sign Up Here</strong></a>
    </div>
</div>