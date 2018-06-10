<?php
    class Examination
    {
        private $connection;
        private $false = 0;

        public function __construct()
        {
            global $database;
            $this->connection = $database->getConnection();
        }
        
        public function setTotalQuestionCount()
        {
            $query = "SELECT count(*) as 'total_question_count' FROM questions";
            $preparedStatement = $this->connection->prepare( $query );
            $preparedStatement->execute();
            $preparedStatement->store_result();
            $count = $preparedStatement->num_rows();
            
            if( $count != 0 )
            {
                $preparedStatement->bind_result( $total_question_count );
                $preparedStatement->fetch();
                $_SESSION["total_question_count"] = $total_question_count;
            }
        }

        public function processSaveQuestion($radioValue)
        {
            if( !in_array( $_SESSION["current_question_id"] , $_SESSION["question_id"] ) )
            {
                array_push( $_SESSION["question_id"] , $_SESSION["current_question_id"] );
            }
            
            $query = "SELECT * FROM examination WHERE question_id = ? AND session_id = ?";
            $preparedStatement = $this->connection->prepare( $query );
            $preparedStatement->bind_param( "ii" , $_SESSION["current_question_id"] , $_SESSION["session_id"] );
            $preparedStatement->execute();
            $preparedStatement->store_result();
            $count = $preparedStatement->num_rows();

            if( $count != 0 )
            {
                if($radioValue != 0)
                {
                    $query = "UPDATE examination SET selected_option_id = ? WHERE question_id = ? AND session_id = ?";
                    $preparedStatement = $this->connection->prepare( $query );
                    $preparedStatement->bind_param( "iii" , $radioValue , $_SESSION["current_question_id"] , $_SESSION["session_id"] );
                    $preparedStatement->execute();
                    $_SESSION["verification"] = false;
                }
            }
            else
            {
                $query = "INSERT INTO examination ( session_id , question_id , selected_option_id ) VALUES ( ? , ? , ? )";
                $preparedStatement = $this->connection->prepare( $query );
                $preparedStatement->bind_param( "iii" , $_SESSION["session_id"] , $_SESSION["current_question_id"] , $radioValue );
                $preparedStatement->execute();
                if($radioValue != 0)
                {
                    $_SESSION["verification"] = false;
                }
            }
            $_SESSION["current_question_id"] = null;
        }

        public function processLoadQuestions()
        {
            if( !isset($_SESSION["total_question_count"]) || empty($_SESSION["total_question_count"]) )
            {
                $this->setTotalQuestionCount();
            }
            if(isset($_SESSION["total_question_count"]) && isset($_SESSION["question_id"]) && (($_SESSION["total_question_count"]+1) == count($_SESSION["question_id"])) )
            {
                echo '
                <div class="text-center ml-5">
                    <h1><i class="far fa-newspaper fa-5x"></i></h1>
                    <h3 class="text-uppercase">Online Examination Completed</h3><hr>
                    <h4><span class="badge cyan">Check All Questions</span></h4>
                </div>';
            }
            else if( !isset($_SESSION["current_question_id"]) || empty($_SESSION["current_question_id"]) )
            {
                while(true)
                {
                    if( !in_array( $random = rand ( 1 , $_SESSION["total_question_count"] ) , $_SESSION["question_id"] ) )
                    {
                        $_SESSION["current_question_id"] = $random;
                        $this->processGenerateQuestions();
                        break;
                    }
                }
            }
            else
            {
                $this->processGenerateQuestions();
            }
        }

        public function processLoadPreviousQuestions($value)
        {
            $_SESSION["current_question_id"] = $value;
            $this->processGenerateQuestions();
        }

        private function processGenerateQuestions()
        {
            $query = "SELECT question_text , IF(is_deleted = ? , TRUE, FALSE) as bool FROM questions WHERE question_id = ?";
            $preparedStatement = $this->connection->prepare( $query );
            $preparedStatement->bind_param( "ii" , $this->false , $_SESSION["current_question_id"] );
            $preparedStatement->execute();
            $preparedStatement->store_result();
            $count = $preparedStatement->num_rows();

            if( $count != 0 )
            {
                $preparedStatement->bind_result( $question_text , $bool );
                $preparedStatement->fetch();

                if($bool)
                {
                
                    echo "
                    <h1><i class='fas fa-file-alt'></i> Questions</h1><hr>
                    <div class='row'>
                        <div class='col-md-12 text-center' id='question'><h4>$question_text</h4><hr></div>
                    </div>
                    <div class='row mt-5' id='options'>";
                
                    $query = "SELECT selected_option_id FROM examination WHERE session_id = ? AND question_id = ?";
                    $preparedStatement = $this->connection->prepare( $query );
                    $preparedStatement->bind_param( "ii" , $_SESSION["session_id"] , $_SESSION["current_question_id"] );
                    $preparedStatement->execute();
                    $preparedStatement->store_result();
                    $count = $preparedStatement->num_rows();
        
                    if( $count != 0 )
                    {
                        $preparedStatement->bind_result( $selected_option_id );
                        $preparedStatement->fetch();
                    }
                    
                    $query = "SELECT option_id , option_text FROM options WHERE question_id = ? AND is_deleted = ?";
                    $preparedStatement = $this->connection->prepare( $query );
                    $preparedStatement->bind_param( "si" , $_SESSION["current_question_id"] , $this->false );
                    $preparedStatement->execute();
                    $preparedStatement->store_result();
                    $count = $preparedStatement->num_rows();
        
                    if( $count != 0 )
                    {
                        $preparedStatement->bind_result( $option_id , $option_text );
                        while( $preparedStatement->fetch() )
                        {
                            if(isset($selected_option_id) && isset($option_id) && ($selected_option_id == $option_id) )
                            {
                                echo "
                                    <div class='col-md-6 mb-5'>
                                        <label class='offset-md-2 text-dark' id='$option_id' style='cursor:pointer;'><h5><input type='radio' name='options' id='$option_id' value='$option_id' checked> $option_text</h5></label>
                                    </div>";
                            }
                            else
                            {
                                echo "
                                    <div class='col-md-6 mb-5'>
                                        <label class='offset-md-2 text-dark' id='$option_id' style='cursor:pointer;'><h5><input type='radio' name='options' id='$option_id' value='$option_id'> $option_text</h5></label>
                                    </div>";
                            }
                        }
                        echo "</div>";
                    }
                }
            }
            else
            {
                $_SESSION["current_question_id"] = null;
            }
        }
    }
?>