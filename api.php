<?php
/*
	Class: API
	This api class has functions to retrieve phone numbers for all or a certain customer or activating a number. 
*/
class Api
{
	
    private $db = null;
    private $requestMethod;
    private $customerId;
    private $phoneNumber;
	private $response = array();
	
	// Class constructor
	// @param string $requestMethod the request method
	// @param integer $customerId the customer ID to get the numbers of
	// @param $phoneNumber the phone number needed to be activated
    public function __construct($requestMethod, $customerId, $phoneNumber)
    {
		$this->requestMethod = $requestMethod;
		$this->customerId 	 = $customerId;
		$this->phoneNumber 	 = $phoneNumber;
		
		// Process the url regarding the information been given.
		$this->processRequest();
    }

	// Connects to the database.
    public function getConnection()
    {
		$host = 'localhost';
        $db   = 'mc';
        $user = 'root';
        $pass = '';

		try { 
			$this->db = new mysqli($host,$user,$pass,$db); 
		} catch (Exception $e) { 
			exit ($e->getMessage());
		} 
    }
	
	// Process the url details and return back json data or an error.
	public function processRequest()
    {
		$this->getConnection();
		
		// Check the request method.
        switch ($this->requestMethod) {
            case 'GET':
				// If the customer ID been mentioned then show the numbers related to else show all the numbers.
                if ($this->customerId) {
                    $response = $this->getCustomerPhoneNumbers($this->customerId);
                } else {
                    $response = $this->getAllPhoneNumbers();
                };
                break;
            case 'PUT':
				// If the request method is updating and the phone number been mentioned then activate it.
				if ($this->$phoneNumber) {
                    $response = $this->activatePhoneNumber($this->phoneNumber);
                }
                break;
            default:
				// No response if the details are not correct.
                $response = $this->notFound();
                break;
        }
		
		// Return the details as Json data.
        header($response['status_code_header']);
        if ($response['body']) {
           echo($response['body']);
        }
    }

	// retrieves the phone numbers for all the customers
    private function getAllPhoneNumbers()
    {
		$statement = "SELECT number FROM phones;";

        try {
            $query = $this->db->query($statement);
			$results = mysqli_fetch_all ($query, MYSQLI_ASSOC);
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
			$response['body'] = json_encode($results);
			return $response;
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }
	
	// retrieves the phone numbers for a specific customer
	// @param integer $id the customer ID
    private function getCustomerPhoneNumbers($id)
    {
        $statement = "SELECT number FROM phones WHERE customer_id = $id ;";

        try {
            $query = $this->db->query($statement);
            $results = mysqli_fetch_all ($query, MYSQLI_ASSOC);
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
			$response['body'] = json_encode($results);
			return $response;
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }
	
	// Ativates a specific phone number 
	// @param string $phoneNumber the phone number needed to be activated 
    private function activatePhoneNumber($phoneNumber)
    {
        $statement = "UPDATE phones SET status = 1 WHERE number = $phoneNumber ;";

        try {
            $query = $this->db->query($statement);
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
			$response['body'] = null;
			return $response;
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }
	
	// the page is not found
	private function notFoundResponse()
	{
		header("HTTP/1.1 404 Not Found");
		exit();
	}

}

