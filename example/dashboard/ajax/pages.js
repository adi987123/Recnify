function QuestionForm()
{
    $.ajax({
        url: 'includes/forms/question.php'
    }).done(function (response) {    
        $( document ).ready(function() {
            document.getElementById("panel").innerHTML = response;
            // registering / updating cookie
            Cookies.set('form', 'login');
        });
    })
}


// switching question forms on UI depending on Cookie
switch(Cookies.get('form'))
{
    case 'question'    :   QuestionForm();
    break;

    default :   QuestionForm();
}

