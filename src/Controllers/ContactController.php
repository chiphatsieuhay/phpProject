<?php
namespace src\Controllers;

use Src\Tables\Contact;

class ContactController{

    private $db;
    private $requestMethod;
    
    private $contact;

    public function __construct($db, $requestMethod )
    {
        
        $this->db = $db;
        $this->requestMethod = $requestMethod;

        $this->contact = new Contact($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $response = $this->getAllContacts();
                break;
            case 'POST':
                $response = $this->createContactFromRequest();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllContacts()
    {
        $result = $this->contact->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 ';      
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createContactFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateContact($input)) {
            return $this->unprocessableEntityResponse();
        }
        if ($this->contact->insert($input)>0){
            $response['status_code_header'] = 'HTTP/1.1 200 ';
            $data = ["StatusCode"=>200,"message"=>"created contact successful"];
            $response['body'] = json_encode($data);
        }else {
            $response['status_code_header'] = 'HTTP/1.1 404 ';
            $data = ["StatusCode"=>404,"message"=>"check your email address"];
            $response['body'] = json_encode($data);
        }
        
        
        return $response;
    }

    private function validateContact($input)
    {
        if (! isset($input['fullname'])) {
            return false;
        }
        if (! isset($input['email'])) {
            return false;
        }
        if (! isset($input['message'])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}