<!-- form validator for Signup Form Email Input via AJAX Call ( functionality.js -> registerData() ) -->
<?php
    // for Database Conntectivity
    require_once("../../common/classes/Database.php");
    $connection = $database->getConnection();
    switch(true)
    {
        case    ''  :;
        break;
        
        case    isset($_POST["user_email"])   :

                            $query = "SELECT * FROM users WHERE user_email = ?";
                            $preparedStatement = $connection->prepare( $query );
                            $preparedStatement->bind_param( "s" , $_POST["user_email"] );
                            $preparedStatement->execute();
                            $preparedStatement->store_result();
                            $count = $preparedStatement->num_rows();
                            
                            if($count == 1)
                            {
                                echo '<h6 class="red-text mt-2 ml-2"><i class="fas fa-times"></i> <strong>Email Already Used</strong></h6>';
                            }
                            else
                            {
                                echo '<h6 class="green-text mt-2 ml-2"><i class="fas fa-user-check"></i> <strong>Email Credential Available</strong></h6>';
                            }

        break;

    }
?>