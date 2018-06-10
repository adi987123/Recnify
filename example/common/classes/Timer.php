<?php
    class Timer
    {
        private $connection;
        private $false = 0;

        public function __construct()
        {
            global $database;
            $this->connection = $database->getConnection();
        }

        public function processLoadTimer()
        {
            $query = "SELECT CONVERT(TIME_TO_SEC(session_timer),UNSIGNED INTEGER) as 'session_timer' FROM examination_session WHERE session_id = ? AND is_deleted = ?";
            $preparedStatement = $this->connection->prepare( $query );
            $preparedStatement->bind_param( "ii" , $_SESSION["session_id"] , $this->false );
            $preparedStatement->execute();
            $preparedStatement->store_result();
            $count = $preparedStatement->num_rows();

            if( $count != 0 )
            {
                $preparedStatement->bind_result( $session_timer );
                $preparedStatement->fetch();
                echo $session_timer;
            }
        }

        public function processSaveTimer($value)
        {
            $query = "UPDATE examination_session SET session_timer = ? WHERE session_id = ? AND is_deleted = ?";
            $preparedStatement = $this->connection->prepare( $query );
            $preparedStatement->bind_param( "sii" , $value , $_SESSION["session_id"] , $this->false );
            $preparedStatement->execute();
        }
    }
?>