<?php
class BlacklistController extends BaseController
{
    /**
     * "/user/list" Endpoint - Get list of users
     */
    public function listGET()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
 
        if (strtoupper($requestMethod) == 'GET') {
			try {
                $model = new BlacklistModel($this->dbhost, $this->dbuser, $this->dbpass, $this->database);
 
                $intLimit = 10;
                if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                    $intLimit = $arrQueryStringParams['limit'];
                }
				//echo "intLimit $intLimit<br/>";
                $arrUsers = $model->getBlacklist($intLimit);
				//var_dump($arrUsers);
                $responseData = json_encode($arrUsers);
				//var_dump($responseData);
				//echo $responseData;
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
 
        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
	
	public function listPOST()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
 
		if (strtoupper($requestMethod) == 'POST') {
			try {
                $model = new BlacklistModel($this->dbhost, $this->dbuser, $this->dbpass, $this->database);
 
				$request_body = file_get_contents('php://input');
				//var_dump($request_body);
				$data = json_decode($request_body);
				//var_dump($data);
				//print_r($data);
				foreach($data as $key => $email ) {
					//echo "$$ email " . $email->email . "<br/>";
					try {
						$model->postBlacklist($email->email);         
					} catch (mysqli_sql_exception $e) {
						$strErrorDesc = $e->getMessage().' Email ' . $email->email . ' already inserted.';
						$strErrorHeader = 'HTTP/1.1 500 Internal Server Error'; 
					}					
				}
                //$arrUsers = $model->postBlacklist($intLimit);
				//var_dump($arrUsers);
                //$responseData = json_encode($arrUsers);
				//var_dump($responseData);
				//echo $responseData;
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
			}
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
 
        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                "",
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}