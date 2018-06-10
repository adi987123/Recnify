// Adding Listener to Login Button
function addLoginListener()
{
    $( document ).ready(function() {
        var input = document.getElementById("user_email");
        input.addEventListener("keyup", function(event) {
            event.preventDefault();
            if (event.keyCode === 13) {
                document.getElementById("login").click();
            }
        });
    });
}

// loading Login Form on UI via Ajax Call
function LoginForm()
{
    $.ajax({
        url: 'includes/forms/login.php'
    }).done(function (response) {    
        $( document ).ready(function() {
            document.getElementById("account").innerHTML = response;
            // attaching webcam to form
            Webcam.attach( '#img_camera' );
            // registering / updating cookie
            Cookies.set('form', 'login');
            addLoginListener();
        });
    })
}

// loading Signup Form on UI via Ajax Call
function SignUpForm()
{
    $.ajax({
        url: 'includes/forms/register.php'
    }).done(function (response) {    
        $( document ).ready(function() {
            document.getElementById("account").innerHTML = response;
            // attaching webcam to form
            Webcam.attach( '#img_camera' );
            // registering / updating cookie
            Cookies.set('form', 'signup');
        });
    })
}

// switching forms on UI depending on Cookie
switch(Cookies.get('form'))
{
    case 'login'    :   LoginForm();
    break;

    case 'signup'    :   SignUpForm();
    break;

    default :   LoginForm();
}

