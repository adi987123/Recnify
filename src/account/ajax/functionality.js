// webcam settings (https://github.com/jhuckaby/webcamjs/blob/master/DOCS.md)
Webcam.set({
    width: 390,
    height: 275,
    image_format: 'jpeg',
    jpeg_quality: 100
});

// regex for email format validation (StackOverflow)
var regexEmail = /^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!\.)){0,61}[a-zA-Z0-9]?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!$)){0,61}[a-zA-Z0-9]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/;

// function to Login Account
function loginData() {
    Webcam.snap( function(data_uri) {
        if($('#user_email').val().length == 0)
        {
            document.getElementById("email-error").innerHTML = '<h6 class="red-text mt-2 ml-2"><i class="fas fa-times"></i> <strong>Type Email Credentials</strong></h6>';
            return false;
        }
        else if(!$('#user_email').val().match(regexEmail))
        {
            document.getElementById("email-error").innerHTML = '<h6 class="text-warning mt-2 ml-2"><i class="fas fa-exclamation-triangle"></i> <strong>Improper Email Format</strong></h6>';
            return false;
        }
        else
        {
            var user_email = $('#user_email').val();
            document.getElementById('form').remove();
            document.getElementById('login').remove();
            document.getElementById('extern').remove();
            document.getElementById('img_camera').innerHTML = '<div class="loader text-center"></div>';
            Webcam.upload( data_uri, 'includes/temp-image.php' );
            Webcam.on( 'uploadComplete', function(code, text) {

                // AJAX CALL to Login Account
                $.ajax({
                    type: 'POST',
                    url: 'includes/function.php',
                    data: 'function=login&user_email=' + user_email
                }).done(function (response) {
                    window.location = "?error="+ response;
                })
                
            } );
        }
    } );
}

// function to Signup Account
function registerData() {
    Webcam.snap( function(data_uri) {
        
        var user_name = $('#user_name').val();
        var user_email = $('#user_email').val();

        if(user_email.length == 0)
        {
            document.getElementById("email-error").innerHTML = '<h6 class="red-text mt-2 ml-2"><i class="fas fa-times"></i> <strong>Type Email Credentials</strong></h6>';
            return false;
        }
        else if( user_email.length > 0 && user_email.length < 8 )
        {
            document.getElementById("email-error").innerHTML = '<h6 class="text-warning mt-2 ml-2"><i class="fas fa-exclamation-triangle"></i> <strong>8 Credential characters required</strong></h6>';
            return false;
        }
        else if(!user_email.match(regexEmail))
        {
            document.getElementById("email-error").innerHTML = '<h6 class="text-warning mt-2 ml-2"><i class="fas fa-exclamation-triangle"></i> <strong>Improper Email Format</strong></h6>';
            return false;
        }
        else
        {
            // AJAX CALL to validate Email
            $.ajax({
                type: 'POST',
                url: 'includes/formValidate.php',
                data: 'user_email=' + user_email
            }).done(function (response) {
                $( document ).ready(function() {
                    document.getElementById("email-error").innerHTML = response;
                    if(response.match("red-text"))
                    {
                        return false;
                    }
                    else
                    {
                        document.getElementById('form').remove();
                        document.getElementById('register').remove();
                        document.getElementById('extern').remove();
                        document.getElementById('img_camera').innerHTML = '<div class="loader text-center"></div>';
                        Webcam.upload( data_uri, 'includes/temp-image.php' );
                        Webcam.on( 'uploadComplete', function(code, text) {
            
                            // AJAX CALL to Signup Account
                            $.ajax({
                                type: 'POST',
                                url: 'includes/function.php',
                                data: 'function=register&user_email=' + user_email + '&user_name=' + user_name
                            }).done(function (response) {
                                document.getElementById("result").innerHTML = response;
                            })
                            
                        } );
                    }
                });
            })
        }
    } );
}