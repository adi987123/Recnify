<?php
    class Register
    {
        private $connection;
        private $false = 0;

        public function __construct()
        {
            global $database;
            $this->connection = $database->getConnection();
        }
        
        public function processRegister( $user_name , $user_email )
        {
            if( !$this->processCheckEmailExistance($user_email) )
            {
                // Inserting New user Data if not Existing
                $time = date("Y-m-d h:i:sa");
                $query = "INSERT INTO users ( user_name , user_email , created_at , is_deleted ) VALUES ( ? , ? , ? , ? )";
                $preparedStatement = $this->connection->prepare( $query );
                $preparedStatement->bind_param( "sssi" , $user_name , $user_email , $time , $this->false );
                $preparedStatement->execute();
    
                //  -----   Start API Functionality to Recognize Face   -----
                $queryUrl = "https://api.kairos.com/enroll";
                $enroll_object = '{"image":"'.base64_encode($_SESSION['temp_image']).'",
                "subject_id":"'.$user_email.'",
                "gallery_name":"KAIROS_GALLERY_NAME"}';
                $APP_ID = "KAIROS_API_ID";      //APP_ID given by Kairos Account
                $APP_KEY = "KAIROS_APP_KEY";      //APP_KEY given by Kairos Account
                $request = curl_init($queryUrl);
                
                curl_setopt($request, CURLOPT_POST, true);
                curl_setopt($request,CURLOPT_POSTFIELDS, $enroll_object);
                curl_setopt($request, CURLOPT_HTTPHEADER, array(
                        "Content-type: application/json",
                        "app_id:" . $APP_ID,
                        "app_key:" . $APP_KEY
                    )
                );
                curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($request);
                $image1 = $response;
                curl_close($request);
                //  -----   End API Functionality to Recognize Face   -----
    
                $_SESSION['temp_image'] = null;      //Deleting Temp Image
                
                $this->processNotifyRegister( $user_name , $user_email );   // Check Email Existance in Database
            }
            else
            {
                echo '
                <div class="text-center ml-5">
                    <h1><i class="fas fa-user-times fa-5x"></i></h1>
                    <h3 class="text-uppercase">User Email Already Exists</h3>
                    <a href="http://localhost/example/account/?page=register"><h6><i class="fas fa-chevron-left"></i> Back to home</h6></a>
                </div>';
            }
        }

        private function processCheckEmailExistance( $user_email )
        {
            // Check Email Existance in Database
            $query = "SELECT * FROM users WHERE user_email = ? AND is_deleted = ?";
            $preparedStatement = $this->connection->prepare( $query );
            $preparedStatement->bind_param( "si" , $user_email , $this->false );
            $preparedStatement->execute();
            $preparedStatement->store_result();
            $count = $preparedStatement->num_rows();
            
            if( $count != 0 )
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        private function processNotifyRegister( $user_name , $user_email )
        {
            // Notifying User Registration Successful through Email
            require '../../common/PHPMailer-master/PHPMailerAutoload.php';
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->IsHTML(true);
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = "tls";                 
            $mail->Host       = "smtp.gmail.com";
            $mail->Port       = 587;
            $mail->Username   = "agpatel.xyz@gmail.com";
            $mail->Password   = "adi987123@gmail.com";
            $mail->SetFrom( 'agpatel.xyz@gmail.com' , 'AGPATEL.XYZ' );
            $mail->Subject = "Account Registration";
            $mail->Body = "Welcome ".strtoupper($user_name)." to AGPATEL.XYZ.".'<html><head></head><body>
            User Account Registration Successful
            <br></body></html>';
            $mail->AddAddress( $user_email );

            if( !$mail->Send() )
            {
                echo "Mailer Error: " . $mail->ErrorInfo;
            }
            else
            {
                session_destroy();
                echo '
                <div class="text-center ml-5">
                    <h1><i class="fas fa-user-check fa-5x"></i></h1>
                    <h3 class="text-uppercase">Registration Successful</h3>
                    <a href="http://localhost/example/account/?page=login"><h6><i class="fas fa-chevron-left"></i> Back to home</h6></a>
                </div>';
                exit();
            }
        }
    }
?>