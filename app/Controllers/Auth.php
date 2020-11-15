<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Firebase\JWT\JWT;
use App\Models\Auth_model;

class Auth extends ResourceController
{

	protected $format = 'json';
	/**
	 * @var Auth_model
	 */
	private $auth;

	public function __construct()
	{
		$this->auth = new Auth_model();
	}

	public function create()
	{
		/**
		 * JWT claim types
		 * https://auth0.com/docs/tokens/concepts/jwt-claims#reserved-claims
		 */

		$email = $this->request->getPost('email');
		$password = $this->request->getPost('password');

		$cek_login = $this->auth->cek_login($email);

		// add code to fetch through db and check they are valid
		// sending no email and password also works here because both are empty
		if(password_verify($password, $cek_login['password'])){
			$secret_key = Services::getSecretKey();
			$issuer_claim = "THE_CLAIM"; // this can be the servername. Example: https://domain.com
			$audience_claim = "THE_AUDIENCE";
			$issuedat_claim = time(); // issued at
			$notbefore_claim = $issuedat_claim + 10; //not before in seconds
			$expire_claim = $issuedat_claim + 3600; // expire time in seconds
			$token = array(
				"iss" => $issuer_claim,
				"aud" => $audience_claim,
				"iat" => $issuedat_claim,
				"nbf" => $notbefore_claim,
				"exp" => $expire_claim,
				"data" => array(
					"id" => $cek_login['id'],
					"firstname" => $cek_login['first_name'],
					"lastname" => $cek_login['last_name'],
					"email" => $cek_login['email']
				)
			);

			$token = JWT::encode($token, $secret_key);

			$output = [
				'status' => 200,
				'message' => 'Login successful',
				"token" => $token,
				"email" => $email,
				"firstname" => $cek_login['first_name'],
				"lastname" => $cek_login['last_name'],
				"expireAt" => $expire_claim,
				"roles" => ["USER", "ADMIN"]
			];
			return $this->respond($output, 200);
		} else {
			$output = [
				'status' => 401
			];
			return $this->respond($output, 401);
		}
	}
}
