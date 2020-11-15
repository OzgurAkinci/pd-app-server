<?php namespace App\Controllers;

use App\Models\User_model;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Psr\Log\LoggerInterface;

class User extends ResourceController
{
	protected $format = 'json';
	/**
	 * @var User_model
	 */
	private $m;
	protected $globalEmailError;

	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		parent::initController($request, $response, $logger);
		$this->m = new User_model();
	}

	public function deleteById($id) {
		return $this->m->delete($id);
	}

	public function changePassword() {
		try{
			$json = $this->request->getJSON();
			$id = $json->id;
			$email = $json->email;
			$password = $json->password;
			$hashRandPass = password_hash($password, PASSWORD_DEFAULT);
			if($email && $password && strlen($password)>=6 && $this->sendMail($email, $password)){
				return $this->response->setJSON($this->m->changePassword($id, $hashRandPass));
			}else{
				return $this->respond(false, 500);
			}
		}catch (\Exception $e){
			die('Error: '.$e);
		}
	}

	public function save()
	{
		try {
			$json = $this->request->getJSON();
			$email = $json->email;
			$firstname = $json->firstname;
			$lastname = $json->lastname;

			if($json->id){
				$data = [
					'id' => $json->id,
					'first_name'    => $firstname,
					'last_name'    => $lastname,
					'email'    => $email,
					'password' => '$2y$10$8eYQGogPScobj5K6xb7kWue.VHdU5Bt1m.OnbWe9ZH0zuBL86rEB6'
				];
				$this->m->save($data);
			}else{
				$randPass = $this->rand_string(6);
				$hashRandPass = password_hash($randPass, PASSWORD_DEFAULT);
				$data = [
					'first_name'    => $firstname,
					'last_name'    => $lastname,
					'email'    => $email,
					'password' => $hashRandPass
				];

				if($this->sendMail($email, $randPass)){
					$this->m->save($data);
				}else {
					return $this->response->setJSON($this->globalEmailError);
				}
			}
			return true;
		} catch (\Exception $e) {
			die('Error: '.$e);
		}
	}

	function sendMail($receiver, $randPass)
	{
		try{
			$email = \Config\Services::email();
			$email->setFrom('akincior@gmail.com', 'Özgür Akıncı');
			$email->setTo($receiver);
			$email->setSubject('App - User Information');
			$body = 'E-mail: ' . $receiver . ', Password: ' . $randPass;
			$email->setMessage($body);

			if(!$email->send()){
				$this->globalEmailError = $email->printDebugger(['headers']);
				return false;
			}else{
				return true;
			}
		}catch (\Exception $e){
			$this->globalEmailError = $e;
			return false;
		}
	}

	public function list()
	{
		try{
			$result = [];
			$data = $this->m->findAll($offset = 0, $limit = 10);
			foreach ($data as $d){
				array_push($result, (object)[
					'id' => intval ($d["id"]),
					'firstname' => $d["first_name"],
					'lastname' => $d["last_name"],
					'email' => $d["email"]
				]);
			}
			return $this->response->setJSON($result);
		}catch (\Exception $e){
			die('Error: '.$e->getMessage());
		}
	}

	function rand_string( $length ) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		return substr(str_shuffle($chars),0,$length);

	}
}
