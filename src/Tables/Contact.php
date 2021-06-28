<?php
namespace Src\Tables;

class Contact{
    private $db =null;

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
            contact;";

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
        insert into contact values(:fullname,:email,:message);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'fullname' => $input['fullname'],
                'email'  => $input['email'],
                'message'  => $input['message'],
                
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

}