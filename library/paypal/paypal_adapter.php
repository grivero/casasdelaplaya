<?php
class PaypalAdapter extends Zend_Http_Client{
	
      private $_api_version 	= '86.0';
      private $_api_username 	= '[]]';
      //private $_api_password 	= '[]]';
      //private $_api_signature = '[]]';
      private $_api_password 	= '[]]';
      private $_api_signature 	= '[]]';
      private $_maxamt			= 5000;
                       
      public $api_ops 			= 'https://api-3t.sandbox.paypal.com/nvp';
      //public $api_ops 			= 'https://api-3t.paypal.com/nvp';
      
      public $api_expresscheckout_uri 	= 'https://www.sandbox.paypal.com/webscr';
      //public $api_expresscheckout_uri = 'https://www.paypal.com/webscr';

      
	function __construct( $uri = null, $options = null ){
		
	      parent::__construct($uri, $options);
	 
	      $this->setUri( $this->api_ops );	      
	      
	      // NOTE: Parameters must always be url encoded, as per PayPal documentation.
	      $this->setParameterPost('USER', 		urlencode($this->_api_username));
	      $this->setParameterPost('PWD', 		urlencode($this->_api_password));
	      $this->setParameterPost('SIGNATURE',	urlencode($this->_api_signature));
	      $this->setParameterPost('VERSION', 	urlencode($this->_api_version));
	}
	
	/**
	 * Calls the 'DoDirectPayment' API call. Note - Keep track of the
	 * transaction ID on success!
	 *
	 * @param float $amount
	 * @param string $credit_card_type
	 * @param string $credit_card_number
	 * @param string $expiration_month
	 * @param string $expiration_year
	 * @param string $cvv2
	 * @param string $first_name
	 * @param string $last_name
	 * @param string $address1
	 * @param string $address2
	 * @param string $city
	 * @param string $state
	 * @param string $zip
	 * @param string $country
	 * @param string $currency_code		// 'USD'-dolar 'GBP'-pound 'EUR'-euro
	 * @param string $ip_address		// $_SERVER['REMOTE_ADDR']; => Get the IP Address, assuming we are in a LAMP environment
	 * @param string $payment_action Can be 'Authorization' (default) or 'Sale'
	 *
	 * @return Zend_Http_Response -> with transaction ID to save, i need it to lookup the transaction details
	 * @throws Zend_Http_Client_Exception
	 */
	function doDirectPayment(
	      $amount,
	      $credit_card_type,
	      $credit_card_number,
	      $expiration_month,
	      $expiration_year,
	      $cvv2,
	      $first_name,
	      $last_name,
	      $address1,
	      $address2,
	      $city,
	      $state,
	      $zip,
	      $country,
	      $currency_code,
	      $ip_address,
	      $payment_action = 'Sale'
	) {
	      $this->setParameterPost('METHOD', 'DoDirectPayment');
	 
	      $expiration_date = str_pad($expiration_month, 2, STR_PAD_LEFT) .
	            $expiration_year;
	 
	      $this->setParameterPost('PAYMENTACTION', urlencode($payment_action)); // Can be 'Authorization', or 'Sale'
	      $this->setParameterPost('AMT', urlencode($amount));
	      $this->setParameterPost('CREDITCARDTYPE', urlencode($credit_card_type));
	      $this->setParameterPost('ACCT', urlencode($credit_card_number));
	      $this->setParameterPost('EXPDATE', urlencode($expiration_date));
	      $this->setParameterPost('CVV2', urlencode($cvv2));
	      $this->setParameterPost('FIRSTNAME', urlencode($first_name));
	      $this->setParameterPost('LASTNAME', urlencode($last_name));
	      $this->setParameterPost('STREET', urlencode($address1));
	 	      	 
	      $this->setParameterPost('CITY', urlencode($city));
	      $this->setParameterPost('STATE', urlencode($state));
	      $this->setParameterPost('ZIP', urlencode($zip));
	      $this->setParameterPost('COUNTRYCODE', urlencode($country));
	      $this->setParameterPost('CURRENCYCODE', urlencode($currency_code));
	      $this->setParameterPost('IPADDRESS', urlencode($ip_address));
	      
	      $this->setParameterPost('URI', urlencode($this->api_ops));
	 
	      return $this->request(Zend_Http_Client::POST);
	 
	}
	
	public function getRealIpAddr()
	{
	    if (!empty($_SERVER['HTTP_CLIENT_IP'])){   //check ip from share internet		
	      $ip = $_SERVER['HTTP_CLIENT_IP'];
	    }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){   //to check ip is pass from proxy 	    	   
	      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    }else{
	      $ip = $_SERVER['REMOTE_ADDR'];
	    }
	    return $ip;
	}
	
 
	/**
	* Request an authorization token.
	*
	* @param float $paymentAmount
	* @param string $returnURL
	* @param string $cancelURL
	* @param string $currencyID
	* @param string $payment_action Can be 'Authorization', 'Sale', or 'Order'. Default is 'Authorization'
	* @return Zend_Http_Response
	*/
	function ecSetExpressCheckout($paymentAmount, $returnURL, $cancelURL, $currencyID, $payment_action = 'Authorization') {
		
		$this->setParameterPost('METHOD', 'SetExpressCheckout');
		$this->setParameterPost('L_PAYMENTREQUEST_0_NAME0','Pago 50% estadia en Casas de la Playa');
		$this->setParameterPost('L_PAYMENTREQUEST_0_DESC0','Reserva inmediata');
		$this->setParameterPost('L_PAYMENTREQUEST_0_AMT0',$paymentAmount);		
		$this->setParameterPost('PAYMENTREQUEST_0_AMT', $paymentAmount);
		//$this->setParameterPost('PAYMENTREQUEST_0_CURRENCYCODE', $currencyID); -> needs especification if is not in dollars
		$this->setParameterPost('PAYMENTREQUEST_0_PAYMENTACTION', $payment_action);		
		$this->setParameterPost('MAXAMT',$this->_maxamt);
		$this->setParameterPost('ALLOWNOTE','1');				
		//style of land page
		//$this->setParameterPost('PAGESTYLE','rubia');//si editamos una pagina en account profile, la levantamos desde aca con el nombre
		//$this->setParameterPost('HDRIMG','http://www.casasdelaplayadelapedrera.com/images/f_logo.jpg');				
		
		//result pages		
		$this->setParameterPost('RETURNURL', $returnURL);
		$this->setParameterPost('CANCELURL', $cancelURL);			 					
		
		return $this->request(Zend_Http_Client::POST);
	}
	
	/**
	 * Request for sell detail to show before confirm de doExpressCheckput method
	 * 
	 * @param string $token
	 * 
	 * @return Zend_Http_Response
	 * @throws Zend_Http_Client_Exception
	 */
	function getExpressCheckoutDetails($token){
		
		$this->setParameterPost('METHOD', 'GetExpressCheckoutDetails');			 		
		$this->setParameterPost('TOKEN', $token);
		
		return $this->request(Zend_Http_Client::POST);
	 				
	}
	
	/**
	*
	* Calls the 'ECDoExpressCheckout' API call. Requires a token that can
	* be obtained using the 'SetExpressCheckout' API call. The payer_id is
	* obtained from the 'SetExpressCheckout' or 'GetExpressCheckoutDetails' API call.
	*
	* @param string $token
	* @param string $payer_id
	* @param float  $payment_amount
	* @param string $currency_code
	* @param string $payment_action Can be 'Authorization', 'Sale', or 'Order'
	*
	* @return Zend_Http_Response
	* @throws Zend_Http_Client_Exception
	*/
	function ecDoExpressCheckout($token, $payer_id, $payment_amount, $currency_code, $payment_action = 'Sale') {
		
		$this->setParameterPost('METHOD', 'DoExpressCheckoutPayment');
		$this->setParameterPost('AMT', $payment_amount);
		$this->setParameterPost('TOKEN', $token);
		$this->setParameterPost('PAYERID', $payer_id);
		$this->setParameterPost('PAYMENTACTION', $payment_action);		
	 
		return $this->request(Zend_Http_Client::POST);
	}
	
	/**
	 * Calls the 'GetTransactionDetails' API call. Requires only the transaction_id
	 * 
	 * @param string $transaction_id
	 * 
	 * @return Zend_Http_Response
	 * @throws Zend_Http_Client_Exception
	 */
	function ecGetTransactionDetails( $transaction_id ){
		
		$this->setParameterPost('METHOD','GetTransactionDetails');
		$this->setParameterPost('TRANSACTIONID',$transaction_id);
		
		return $this->request(Zend_Http_Client::POST);
	}
	
	/**
	* Parse a Name-Value Pair response into an object.
	* @param string $response
	* @return object Returns an object representation of the response.
	*/
	public static function parse($response) {
	 
		$responseArray = explode("&", $response);
	 
		$result = array();
	 
		if (count($responseArray) > 0) {
			foreach ($responseArray as $i => $value) {
	 
				$keyValuePair = explode("=", $value);
	 
				if(sizeof($keyValuePair) > 1) {
					$result[$keyValuePair[0]] = urldecode($keyValuePair[1]);
				}
			}
		}
	 
		if (empty($result)) {
			$result = null;
		} else {
			$result = (object) $result;
		}
	 
		return $result;
	}
	
	
	/**
     * Validates the format of a transaction id
     * 
     * @return boolean
     */
    public static function validateTransactionId($transactionId){
    	
    	$validatorChain = new Zend_Validate();
            
        $validatorChain->addValidator(new Zend_Validate_StringLength(17))
                       ->addValidator(new Zend_Validate_Alnum());
        
        return $validatorChain->isValid($transactionId);
    }
    
	
	/**
     * Validate a 3-letter ISO currency code
     * 
     * @param  string $code
     * @return boolean
     */
    public static function validateCurrencyCode($code){
    	 
    	$currency = new Zend_Currency();
         
        return preg_match('|^[A-Z]{3}$|', $code) &&
            in_array($code, array_keys($currency->getCurrencyList()));
    }	

}
