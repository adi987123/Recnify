<?php
    require("../../common/classes/Database.php");
    if( !isset($_SESSION["question_id"]) )
    {
      $_SESSION["question_id"] = array();
      array_push( $_SESSION["question_id"] , 0 );
    }
    if( !isset($_SESSION["verification"]) )
    {
        $_SESSION["verification"] = true;
    }
    if( isset($_POST["function"]) && !empty($_POST["function"]) )
    {
        switch($_POST["function"])
        {
            case    'verifyUser' :
                            require("../../common/classes/Session.php");
                            $Session = new Session();
                            $Session->processVerifyUser();
            break;
            
            case    'loadPagination'  :
                            require("../../common/classes/Session.php");
                            $Session = new Session();
                            $Session->processLoadPagination();
            break;
            
            case    'checkSessionActive'  :
                            require("../../common/classes/Session.php");
                            $Session = new Session();
                            $Session->processCheckSessionActive();
            break;
            
            case    'sessionResult'  :
                            require("../../common/classes/Session.php");
                            $Session = new Session();
                            $Session->processSessionResult();
            break;
            
            case    'endSession'  :
                            require("../../common/classes/Session.php");
                            $Session = new Session();
                            $Session->processEndSession();
            break;
            
            case    'loadTimer'  :
                            require("../../common/classes/Timer.php");
                            $Timer = new Timer();
                            $Timer->processLoadTimer();
            break;
            
            case    'saveTimer'  :
                            require("../../common/classes/Timer.php");
                            $Timer = new Timer();
                            if( isset($_POST["value"]) && !empty($_POST["value"]) )
                            {
                                $Timer->processSaveTimer($_POST["value"]);
                            }
            break;

            case    'loadQuestion'  :
                            require("../../common/classes/Examination.php");
                            $Examination = new Examination();
                            $Examination->processLoadQuestions();
            break;

            case    'loadPreviousQuestion'  :
                            require("../../common/classes/Examination.php");
                            $Examination = new Examination();
                            if( isset($_POST["value"]) && !empty($_POST["value"]) )
                            {
                                $Examination->processLoadPreviousQuestions($_POST["value"]);
                            }
            break;
            
            case    'saveQuestion'  :
                            require("../../common/classes/Examination.php");
                            $Examination = new Examination();
                            if( isset($_POST["radioValue"]) && (!empty($_POST["radioValue"]) || $_POST["radioValue"] == 0) )
                            {
                                $Examination->processSaveQuestion($_POST["radioValue"]);
                            }
            break;
        }
    }
?>