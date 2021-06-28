<?php
namespace src\Controllers;

use Src\Tables\Appointment;

class AppointmentController{
    private $db;
    private $requestMethod;

    private $appointment;

    public function __construct($db, $requestMethod)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;

        $this->appointment = new Appointment($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $response = $this->getAllAppointments();
                break;
            case 'POST':
                $response = $this->createAppointmentFromRequest();
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
    private function getAllAppointments()
    {
        $result = $this->appointment->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 ';      
        $response['body'] = json_encode($result);
        return $response;
    }
    private function createAppointmentFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateContact($input)) {
            return $this->unprocessableEntityResponse();
        }
        if ($this->appointment->insert($input)){
            $response['status_code_header'] = 'HTTP/1.1 200 ';
            $data = ["StatusCode"=>200,"message"=>"created appointment successful"];
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
        if (! isset($input['fullname'])&&! isset($input['appointment_at'])&&! isset($input['email'])) {
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

