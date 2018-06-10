<?php
    class Session
    {
        private $connection;
        private $false = 0;

        public function __construct()
        {
            global $database;
            $this->connection = $database->getConnection();
        }

        private function processSessionQuestionSolved()
        {
            $true = 1;
            $query = "SELECT question_id FROM examination , examination_session WHERE examination.session_id = examination_session.session_id AND user_id = ? AND session_status = ? AND is_deleted = ?";
            $preparedStatement = $this->connection->prepare( $query );
            $preparedStatement->bind_param( "iii" , $_SESSION["user_id"] , $true , $this->false );
            $preparedStatement->execute();
            $preparedStatement->store_result();
            $count = $preparedStatement->num_rows();

            if( $count != 0 )
            {
                $preparedStatement->bind_result( $question_id );
                while($preparedStatement->fetch())
                {
                    array_push( $_SESSION["question_id"] , $question_id );
                }
            }
        }

        public function processVerifyUser()
        {
            //  -----   Start API Functionality to Recognize Face   -----
            $queryUrl = "http://api.kairos.com/recognize";
            $recognize_object = '{
                "image":"'.base64_encode($_SESSION['temp_image']).'",
                "gallery_name":"KAIROS_GALLERY_NAME"
            }';
            $APP_ID = "KAIROS_API_ID";      //APP_ID given by Kairos Account
            $APP_KEY = "KAIROS_APP_KEY";      //APP_KEY given by Kairos Account
            $request = curl_init($queryUrl);
            
            curl_setopt($request, CURLOPT_POST, true);
            curl_setopt($request,CURLOPT_POSTFIELDS, $recognize_object);
            curl_setopt($request, CURLOPT_HTTPHEADER, array(
                    "Content-type: application/json",
                    "app_id:" . $APP_ID,
                    "app_key:" . $APP_KEY
                )
            );
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($request);
            $temp_img = $response;
            curl_close($request);
            //  -----   End API Functionality to Recognize Face   -----
            
            $_SESSION['temp_image'] = null;      //Deleting Temp Image
            
            $obj = json_decode( $temp_img , true );     //Decoding JSON Response of API

            // Result of Image Recognition as 'success' or 'failure'
            if( $obj['images'][0]['transaction']['status'] === 'success' )
            {
                // Comparing Subject_ID of API to Email ID in Database as both should MATCH
                if( $_SESSION["user_email"] === $obj['images'][0]['transaction']['subject_id'] )
                {
                    $_SESSION['verification'] =  true;
                }
                else
                {
                    echo password_hash( 'user-unauthorized' , PASSWORD_BCRYPT );
                }
            }
            else
            {
                echo password_hash( 'image-recognition-error' , PASSWORD_BCRYPT );
            }
        }

        public function processLoadPagination()
        {
            $true = 1;
            $query = "SELECT examination.question_id , examination_id ,selected_option_id FROM examination , examination_session , questions WHERE questions.question_id = examination.question_id AND examination.session_id = examination_session.session_id AND user_id = ? AND session_status = ? AND examination_session.is_deleted = ? AND questions.is_deleted = ? GROUP BY examination_id";
            $preparedStatement = $this->connection->prepare( $query );
            $preparedStatement->bind_param( "iiii" , $_SESSION["user_id"] , $true , $this->false , $this->false );
            $preparedStatement->execute();
            $preparedStatement->store_result();
            $count = $preparedStatement->num_rows();

            echo '
            <nav aria-label="pagination">
                <ul class="pagination pagination-lg pg-primary mb-0 justify-content-center border border-elegant">';
            if( $count != 0 )
            {
                $num = 1;
                $preparedStatement->bind_result( $question_id , $examination_id , $selected_option_id );
                while($preparedStatement->fetch())
                {
                    if($question_id == $_SESSION["current_question_id"] && $selected_option_id == 0)
                    {
                        echo '<li class="page-item active ml-1 mr-1 mt-1 mb-1"><a onclick="loadPreviousData('.$question_id.')" class="page-link">'.$num.'</a></li>';
                    }
                    else if($question_id == $_SESSION["current_question_id"] && $selected_option_id != 0)
                    {
                        echo '<li class="page-item active ml-1 mr-1 mt-1 mb-1"><a onclick="loadPreviousData('.$question_id.')" class="page-link text-white">'.$num.'</a></li>';
                    }
                    else if($selected_option_id != 0)
                    {
                        echo '<li class="page-item grey darken-1 ml-1 mr-1 mt-1 mb-1"><a onclick="loadPreviousData('.$question_id.')" class="page-link text-white">'.$num.'</a></li>';
                    }
                    else
                    {
                        echo '<li class="page-item bg-light ml-1 mr-1 mt-1 mb-1"><a onclick="loadPreviousData('.$question_id.')" class="page-link">'.$num.'</a></li>';
                    }
                    $num++;
                }
            }
            echo '</ul>
                <ul class="pagination pagination-lg pg-red mb-0 mt-2 justify-content-center">
                    <li class="page-item active ml-1 mr-1 mt-1 mb-1"><a onclick="skipData()" class="page-link">Skip Question</a></li>
                    <li class="page-item active ml-1 mr-1 mt-1 mb-1"><a onclick="endSession()" class="page-link">End Examination</a></li>
                    </ul>
                </nav>';
        }
        
        public function processCheckSessionActive()
        {
            $query = "SELECT session_id , session_status FROM examination_session WHERE user_id = ? AND is_deleted = ?";
            $preparedStatement = $this->connection->prepare( $query );
            $preparedStatement->bind_param( "ii" , $_SESSION["user_id"] , $this->false );
            $preparedStatement->execute();
            $preparedStatement->store_result();
            $count = $preparedStatement->num_rows();

            if( $count != 0 )
            {
                $preparedStatement->bind_result( $session_id , $session_status );
                $preparedStatement->fetch();
                $_SESSION["session_id"] = $session_id;
                
                if( !isset($_SESSION["question_id"]) || empty($_SESSION["question_id"]) )
                {
                    $this->processSessionQuestionSolved();
                }

                if( !isset($_SESSION["total_question_count"]) || empty($_SESSION["total_question_count"]) )
                {
                    require("Examination.php");
                    $Examination = new Examination();
                    $Examination->setTotalQuestionCount();
                }

                echo $session_status;
            }
        }

        public function processSessionResult()
        {
            $query = "SELECT is_deleted , session_status , batch_id FROM examination_session WHERE user_id = ?";
            $preparedStatement = $this->connection->prepare( $query );
            $preparedStatement->bind_param( "i" , $_SESSION["user_id"] );
            $preparedStatement->execute();
            $preparedStatement->store_result();
            $count = $preparedStatement->num_rows();

            if( $count != 0 )
            {
                $preparedStatement->bind_result( $is_deleted , $session_status , $batch_id );
                $preparedStatement->fetch();
                
                if($is_deleted)
                {
                    $question_attempt = 0;
                    $question_correct = 0;
                    $query = "SELECT questions.question_id as 'question_id' FROM questions , examination WHERE questions.question_id = examination.question_id AND questions.is_deleted = 0 AND examination.session_id = (SELECT session_id FROM examination_session WHERE user_id = ?)";
                    $preparedStatement = $this->connection->prepare( $query );
                    $preparedStatement->bind_param( "i" , $_SESSION["user_id"] );
                    $preparedStatement->execute();
                    $preparedStatement->store_result();
                    $count = $preparedStatement->num_rows();
                    if( $count != 0 )
                    {
                        $question_attempt = $count;
                        $preparedStatement->bind_result( $question_id );
                        while($preparedStatement->fetch())
                        {
                            // $query = "SELECT is_correct FROM options , examination WHERE options.option_id = examination.selected_option_id AND options.question_id = ?";
                            // $preparedStatement = $this->connection->prepare( $query );
                            // $preparedStatement->bind_param( "i" , $question_id );

                            // // if(!$preparedStatement = $this->connection->prepare($query))   //Debugging
                            // // {
                            // //     die($this->connection->error);
                            // // }

                            // $preparedStatement->execute();
                            // if($preparedStatement->num_rows() != 0)
                            // {
                            //     $question_correct++;
                            // }
                            
                            $sql = "SELECT is_correct FROM options , examination WHERE options.option_id = examination.selected_option_id AND options.question_id = $question_id";
                            $result_set = $this->connection->query($sql);
                            if( mysqli_num_rows($result_set) != 0 )
                            {
                                $question_correct++;
                            }
                        }
                        echo '<div class="text-center">
                        <h1><i class="fas fa-gift fa-3x"></i></h1>
                        <h3 class="text-uppercase bg-dark text-white d-inline-block p-3"><strong>'.$question_correct.'/'.$question_attempt.'</strong></h3><hr>
                        <a href="'.(isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST']."/example/logout.php".'"><h5><i class="fas fa-sign-out-alt"></i> Logout</h5></a>
                        </div>';
                    }
                }
                else
                {
                    echo '<div class="text-center">
                    <h1><i class="fas fa-stopwatch fa-3x"></i></h1>
                    <h3 class="text-uppercase text-danger"><strong>Session Inactive</strong></h3><hr>
                    <a href="'.(isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST']."/example/logout.php".'"><h5><i class="fas fa-sign-out-alt"></i> Logout</h5></a>
                    </div>';
                }
            }
        }

        public function processEndSession()
        {
            $true = 1;
            $query = "UPDATE examination_session SET session_status = ? , is_deleted = ? WHERE session_id = ? AND is_deleted = ?";
            $preparedStatement = $this->connection->prepare( $query );
            $preparedStatement->bind_param( "iiii" , $this->false , $true , $_SESSION["session_id"] , $this->false );
            $preparedStatement->execute();
        }
    }
?>
