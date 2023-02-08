<?php

namespace App\Services;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 
use App\Modules\User;
use Session;

class ApiService
{


    /**
     * The public variable.
     *
     * @var replacement
     */
    
	public $headers = 	['Accept' => 'application/json',
								'Version-Code'=>1,
								'Device-Type'=>'web'
						];
							//	'Authorization' => 'Bearer '.$request->token
    public $api_end_point = 'http://127.0.0.1:8000/api';
    


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
     
    }
	
	
	public function sendAuthorizationToken()
	{
		$loginData = array();
		$loginData = $this->getUserLoginData();
		$this->headers = array_merge($this->headers, array('Authorization'=>'Bearer '.$loginData['token']));
	}
	

/* ------------------------------------------------ This Below API use releted from User --------------------------------------*/

	public function getPageApi($slug = null)
    {
		$url = $this->api_end_point.'/page/'.$slug;
		return $this->httpGet($url);
    }
	
    public function sendOTPApi($data = array())
    {
    	dd($data);
		$url = $this->api_end_point.'/send-otp';
		return $this->httpPost($url, $data);
    }

	public function registerApi($data = array())
    {
		$url = $this->api_end_point.'/user/register';
		return $this->httpPost($url, $data);
    }
	
	public function loginApi($data = array())
    {
		$url = $this->api_end_point.'/user/login';
		return $this->httpPost($url, $data);
    }
	
	
	public function forgotPasswordApi($data = array())
    {
		$url = $this->api_end_point.'/user/forgot-password';
		return $this->httpPost($url, $data);
    }
	
	
	public function resetPasswordApi($data = array())
    {
		$url = $this->api_end_point.'/user/saveresetpassword';
		return $this->httpPost($url, $data);
    }
	
	
	public function contactUsApi($data = array())
    {
		$url = $this->api_end_point.'/user/contact-us';
		return $this->httpPost($url, $data);
    }
	
	
/* ------------------------------------------------ This Below API use releted from After Login User --------------------------------------*/	
	
	public function getDashboardApi()
    {
		$url = $this->api_end_point.'/user/dashboard';
		$this->sendAuthorizationToken();
		return $this->httpGet($url);
       
    }
	
	
	public function getProfileApi()
    {
		$url = $this->api_end_point.'/user/getprofile';
		$this->sendAuthorizationToken();
		return $this->httpGet($url);
       
    }
	
	
	public function updateProfileApi($request, $data = array())
    {
		$url = $this->api_end_point.'/user/editprofile';
		$this->sendAuthorizationToken();
		return $this->httpPostwithImage($url, $request, $data);
    }
	
	public function updatePasswordApi($data = array())
    {
		$url = $this->api_end_point.'/user/changepassword';
		$this->sendAuthorizationToken();
		return $this->httpPost($url, $data);
    }
	
	
	public function logoutApi()
    {
		$url = $this->api_end_point.'/user/logout';
		$this->sendAuthorizationToken();
		return $this->httpGet($url);
       
    }
	
	/* ------------------------------------------------ This Below API use releted from Vehicle --------------------------------------*/
	
	public function getVehicleListApi()
    {
		$url = $this->api_end_point.'/vehicle/list';
		$this->sendAuthorizationToken();
		return $this->httpGet($url);
    }
	
	public function getVehicleDetailsApi($id = null)
    {
		$url = $this->api_end_point.'/vehicle/details/'.$id;
		$this->sendAuthorizationToken();
		return $this->httpGet($url);
       
    }
	
	
	public function submitVehicleApi($data = array())
    {
		
		if(isset($data['id']) && $data['id']>0){
			$url = $this->api_end_point.'/vehicle/update';
		} else {
			$url = $this->api_end_point.'/vehicle/add';
		}
		$this->sendAuthorizationToken();
		return $this->httpPost($url, $data);
    }
	
	
	public function deleteVehicleApi($id = null)
    {
		$url = $this->api_end_point.'/vehicle/delete/'.$id;
		$this->sendAuthorizationToken();
		return $this->httpGet($url);
    }
	
	
	
	/* ------------------------------------------------ This Below API use releted from Order  --------------------------------------*/
	
	public function timeSlotList()
    {
		$url = $this->api_end_point.'/order/time_slot';
		$this->sendAuthorizationToken();
		return $this->httpGet($url);
    }
	
	public function amountCasesList()
    {
		$url = $this->api_end_point.'/order/amount_cases';
		$this->sendAuthorizationToken();
		return $this->httpGet($url);
    }
	
	public function applyDeliveryFees($data = array())
    {
		$url = $this->api_end_point.'/order/applyDeliveryFees';
		$this->sendAuthorizationToken();
		return $this->httpPost($url, $data);
    }
	
	public function applyOrderConditions($data = array())
    {
		$url = $this->api_end_point.'/order/applyOrderConditions';
		$this->sendAuthorizationToken();
		return $this->httpPost($url, $data);
    }
	
	
	public function orderPaymentProcess($data = array())
    {
		$url = $this->api_end_point.'/order/payment-process';
		$this->sendAuthorizationToken();
		return $this->httpPost($url, $data);
    }
	
	public function getOrderListApi()
    {
		$url = $this->api_end_point.'/order/list';
		$this->sendAuthorizationToken();
		return $this->httpGet($url);
    }
	
	public function getOrderSearchApi($searchData = array())
    {
		$url = $this->api_end_point.'/order/search';
		$this->sendAuthorizationToken();
		return $this->httpPost($url, $searchData);
    }
	
	public function getOrderDetailsApi($id = null)
    {
		$url = $this->api_end_point.'/order/details/'.$id;
		$this->sendAuthorizationToken();
		return $this->httpGet($url);
    }
	
	public function OrderCancelApi($data = null)
    {
		$url = $this->api_end_point.'/order/cancel';
		$this->sendAuthorizationToken();
		return $this->httpPost($url, $data);
    }
	
	
	/* ------------------------------- Below use common HTTP and API function --------------------------------------------------------*/
	
	public function httpPost($url, $data)
	{
		try {
			$http = new \GuzzleHttp\Client();  
			$response = $http->post($url, [
									'json'=>$data,
									//'form_params'=>$data,
									'headers' => $this->headers
								]);
				
			//return $response->getBody()->getContents();
			return json_decode((string) $response->getBody()->getContents(), true);
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$response = [
				'status' => false,
				'code' => 200,
				'message' => $e->getMessage(),
				'data'=>(object)[]
			];
			return $response;
		}	
	}		
	
	public function httpGet($url)
	{
		try {
			$http = new \GuzzleHttp\Client();  
			$response = $http->get($url, [
									'headers' => $this->headers
								]);
				
			//return $response->getBody()->getContents();
			return json_decode((string) $response->getBody()->getContents(), true);
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$response = [
				'status' => false,
				'code' => 200,
				'message' => $e->getMessage(),
				'data'=>(object)[]
			];
			return $response;
		}	
	}
	
	
	
	public function httpPostwithImage($url, $request, $data)
	{
		
		if(isset($data['profile_photo']) && !empty($request->file('profile_photo')->path())){
			//$data = array_merge($data, array('profile_photo'=>base64_encode(file_get_contents($request->file('profile_photo')->path()))));
		}
		
		//dd($data);
		//exit;
		
		
		
		 //'image' => base64_encode(file_get_contents($request->file('image')->path()))
		try {
			$http = new \GuzzleHttp\Client();  
			$response = $http->post($url, [
									'form_params'=>$data,
									'headers' => $this->headers
								]);
				
			//return $response->getBody()->getContents();
			return json_decode((string) $response->getBody()->getContents(), true);
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$response = [
				'status' => false,
				'code' => 200,
				'message' => $e->getMessage(),
				'data'=>(object)[]
			];
			return $response;
		}	
	}	
	
	
	
	/***** Below function use for session storate ****************/
	public function storeUserLoginData($data = array())
	{
		Session::put('loginUserData', $data);
	}
	
	public function getUserLoginData()
	{
		return Session::get('loginUserData');
	}
	
	public function removeUserLoginData()
	{
			Session::flush(); // removes all session data
			Session::forget('loginUserData'); // Removes a specific variable
	}
	
	public function mapArrayToObject($data = array())
	{
			return  (object) $data;
	}
	
	public function mapObjectToArray($data = array())
	{
			return  (array) $data;
	}

	public function storeUserOrderData($data = array())
	{
		Session::put('userOrderData', $data);
	}
	
	public function getUserOrderData()
	{
		return Session::get('userOrderData');
	}
	
	public function removeUserOrderData()
	{
			Session::forget('userOrderData'); // Removes a specific variable
	}
	
	
   
}