var saveTimerData ;
var checkExamSessionActive = setInterval(function(){ sessionactive() }, 10000);

// webcam settings (https://github.com/jhuckaby/webcamjs/blob/master/DOCS.md)
Webcam.set({
    width: 390,
    height: 275,
    image_format: 'jpeg',
    jpeg_quality: 100
});

$( document ).ready(function() {
    if(document.getElementById("img_camera"))
    {
        Webcam.attach( '#img_camera' );
    }
    sessionactive();
});

function verifyData()
{
    // ----- Verify Webcam Image With Session User -----
    Webcam.snap( function(data_uri) {
        document.getElementById('img_camera').innerHTML = '<div class="loader text-center"></div>';
        Webcam.upload( data_uri, 'includes/temp-image.php' );
        Webcam.on( 'uploadComplete', function(code, text) {

            // AJAX CALL to Verify User Face
            $.ajax({
                type: 'POST',
                url: 'includes/function.php',
                data: 'function=verifyUser'
            }).done(function (response) {
                if(!''.match( response.replace(/\s/g,'') ))
                {
                    window.location = location.protocol+"//"+document.domain+"/mcq/"+'dashboard/?error=' + response;
                }
                else
                {
                    window.location = location.protocol+"//"+document.domain+"/mcq/"+'dashboard/';
                }
            })
            
        } );
    } );
}

function loadPagination()
{
    // ----- Load Pagination Links -----
    $.ajax({
        type: 'POST',
        url: 'includes/function.php',
        data: 'function=loadPagination'
    }).done(function (response) {    
        $( document ).ready(function() {
            document.getElementById("pagination").innerHTML = response;
        });
    })
}

function sessionactive()
{
    // ----- Main Process -----
    $.ajax({
        type: 'POST',
        url: 'includes/function.php',
        data: 'function=checkSessionActive'
    }).done(function (response) {
        if(response.match('1'))
        {
            document.getElementById('countdownExample').style.visibility = "visible";
            document.getElementById('save').style.display = 'inline-block';
            loadData();
            loadTimer();
            clearInterval(checkExamSessionActive);
        }
        else
        {
            sessionResult();
        }
    })
}

function sessionResult()
{
    // ----- Getting Sesstion Data when Session Not Active -----
    $.ajax({
        type: 'POST',
        url: 'includes/function.php',
        data: 'function=sessionResult'
    }).done(function (response) {
        document.getElementById("form").innerHTML = response;
        document.getElementById('countdownExample').style.visibility = "hidden";
        document.getElementById('save').style.display = 'none';
    });
}

function endSession()
{
    // ----- Ending Main Session -----
    $.ajax({
        type: 'POST',
        url: 'includes/function.php',
        data: 'function=endSession'
    }).done(function (response) {
        document.getElementById('countdownExample').remove();
        document.getElementById('pagination').remove();
        document.getElementById('save').remove();
        clearInterval(saveTimerData);
        sessionactive();
    });
}

function loadData()
{
    // ----- loads Questions -----
    $.ajax({
        type: 'POST',
        url: 'includes/function.php',
        data: 'function=loadQuestion'
    }).done(function (response) {    
        $( document ).ready(function() {
            if(!''.match(response.replace(/\s/g,'')))
            {
                document.getElementById("form").innerHTML = response;
            }
            else
            {
                $.ajax({
                    type: 'POST',
                    url: 'includes/function.php',
                    data: 'function=saveQuestion&radioValue=0'
                }).done(function (response){
                    loadData();
                });
            }
            loadPagination();
        });
    })
}

function skipData()
{
    // ----- Skipping Question Data by saving Radio Resut By 0 -----
    saveData(0);
    loadData();
}

function loadPreviousData(examination_id)
{
    // ----- Load Previous Links -----
    $.ajax({
        type: 'POST',
        url: 'includes/function.php',
        data: 'function=loadPreviousQuestion&value=' + examination_id
    }).done(function (response) {    
        $( document ).ready(function() {
            document.getElementById("form").innerHTML = response;
            loadPagination();
        });
    })
}

function saveData(radioValue)
{
    // ----- Saves Answers -----
    $.ajax({
        type: 'POST',
        url: 'includes/function.php',
        data: 'function=saveQuestion&radioValue=' + radioValue
    }).done(function(response){
        window.location = location.protocol+"//"+document.domain+"/mcq/"+'dashboard/';
    });
}

function nextData()
{
    // ----- Saves Answers and Loads New Question -----
    if(document.querySelector('input[name="options"]:checked'))
    {
        saveData(document.querySelector('input[name="options"]:checked').value);
    }
    loadData();
}

function loadTimer()
{
    // ----- Load's Stored Timer -----
    $.ajax({
        type: 'POST',
        url: 'includes/function.php',
        data: 'function=loadTimer'
    }).done(function (response) {
        manageTimer(parseInt(response));
    })
}

function saveTimer(time)
{
    // ----- Saves Stored Timer -----
    $.ajax({
        type: 'POST',
        url: 'includes/function.php',
        data: 'function=saveTimer&value=' + time
    });
}

function manageTimer(time)
{
    // --- Display Timer API ---
    var timer = new Timer();
    timer.start({countdown: true, startValues: {seconds: time}});
    $('#countdownExample .values').html(timer.getTimeValues().toString());
    timer.addEventListener('secondsUpdated', function (e) {
        $('#countdownExample .values').html(timer.getTimeValues().toString());
    });
    timer.addEventListener('targetAchieved', function (e) {
        $('#countdownExample .values').html('');
        endSession();
    });

    saveTimerData = setInterval(function(){ saveTimer(timer.getTimeValues().toString()); }, 5000);
}