<?php namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Home extends ResourceController
{

	use ResponseTrait;

	public function index()
	{
		# code...
		$db = \Config\Database::connect();
		$builder = $db->table('home');
		$data = $builder->get()->getResult();
		if($data != null){
			$response = [
				'status' => 200,
				'error' => null,
				'data' => $data,
				'message' => 'Connected!'
			];
		}else{
			$response = [
				'status' => 200,
				'error' => true,
				'data' => $data,
				'message' => 'Server under maintenance!'
			];
		}
		return $this->respond($response);
	}

	//--------------------------------------------------------------------

}
