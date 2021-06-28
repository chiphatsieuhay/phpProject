<?php
namespace Src\Tables;

class Appointment{
    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {

        $statement = "
            SELECT 
                *
            FROM
            appointment;";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(Array $input)
    {
        $statement = "
        insert into appointment values(:fullname,:email,:phoneNumber,:appointment_at,:message);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'fullname' => $input['fullname'],
                'email'  => $input['email'],
                'appointment_at'  => $input['appointment_at'],
                'phoneNumber' => $input['phoneNumber'] ?? null,
                'message' => $input['message'] ?? null,
                
            ));
          
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}