<?php
    class LoginSession
    {
        private $connection;
        private $false = 0;

        public function __construct()
        {
            global $database;
            $this->connection = $database->getConnection();
        }
        
        public function processLogin( $user_email )
        {
            $query = "SELECT id FROM users WHERE user_email = ? AND is_deleted = ?";
            $preparedStatement = $this->connection->prepare( $query );
            $preparedStatement->bind_param( "si" , $user_email , $this->false );
            $preparedStatement->execute();
            $preparedStatement->store_result();
            $count = $preparedStatement->num_rows();
            
            if( $count == 1 )
            {
                $preparedStatement->bind_result( $id );
                $preparedStatement->fetch();
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
                    if( $user_email === $obj['images'][0]['transaction']['subject_id'] )
                    {
                        echo password_hash( 'success' , PASSWORD_BCRYPT );
                        $_SESSION["user_id"] = $id;
                        $_SESSION["user_email"] = $user_email;
                        $_SESSION['login'] =  true;
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
            else
            {
                echo password_hash( 'user-not-found' , PASSWORD_BCRYPT );
            }
        }

        public function processLogout()
        {
            $_SESSION["user_email"] = null;
            $_SESSION['login'] =  null;
            $_SESSION["user_id"] = null;
            session_destroy();
            header("Location: http://localhost/src/account/");
        }
    }
?>