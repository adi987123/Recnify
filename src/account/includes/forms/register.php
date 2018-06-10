<!-- Signup Form -->
<div class="card-body text-center">

    <div id="form">
        <div class="ml-4 mr-5 mb-3 md-form">
            <input type="text" name="user_name" id="user_name" class="form-control">
            <label for="user_name">Name</label>
            <div id="name-error"></div>
        </div>
        
        <div class="ml-4 mr-5 mb-3 md-form">
            <input type="email" name="user_email" id="user_email" class="form-control">
            <label for="user_email">Email</label>
            <div id="email-error"></div>
        </div>
    </div>
    <div class="offset-md-1" id="img_camera" style="width:390px; height:275px;">
    </div>

    <!-- CALL to registerData() (functionality.js) -->
    <a class="btn btn-elegant" id="register" onclick="registerData()"><h4 class="mt-2"><i class="fas fa-user-plus"></i> Register</h4></a>
    <div class="text-center" id="extern">
    <!-- AJAX CALL to LoginForm() to switch forms on UI (pages.js) -->
        Already have account ? <a onclick="LoginForm()"><strong>Sign In Here</strong></a>
    </div>
</div>