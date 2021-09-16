<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

/**
 * 
 */
class UserController extends ResourceController
{
	use ResponseTrait;

	public function show($id = null)
	{
		# code...
		$model = new UserModel();
		$data = $model->getWhere(['user_id' => $id])->getRow();
		if ($data) {
			# code...
			return $this->respond($data, 200);
		} else {
			return $this->failNotFound('ID Not Found ' . $id);
		}
	}

	public function create()
	{
		# code...
		//init model object
		$model = new UserModel();

		//get data from post
		$user_id = uniqid();
		$username = $this->request->getPost('username');
		$password =  $this->request->getPost('password');
		$nik = $this->request->getPost('nik');
		$department = $this->request->getPost('department');
		$modifier = $this->request->getPost('modifier');
		$image = $this->uploadImg($user_id);

		//insert the data to array
		$data = [
			'user_id' => $user_id,
			'nik' => $nik,
			'username' => $username,
			'password' => password_hash($password, PASSWORD_DEFAULT),
			'department' => $department,
			'modifier' => $modifier,
			'image' => $image,
			'date_created' => date('Y:m:d H:m:s'),
			'date_modified' => date('Y:m:d H:m:s'),
			'status' => 0
		];

		//check is user is already registered
		$user_list = $model->getWhere(['username' => $username])->getRow();
		if ($user_list != null) {
			# code...
			$response = [
				'status' => 400,
				'error' => true,
				'data' => null,
				'message' => 'failed!, Username exists'
			];
		} else {
			if (
				empty($username) || empty($password) ||
				empty($nik) || empty($department)
			) {
				# if field is empty...
				$response = [
					'status' => 400,
					'error' => true,
					'data' => null,
					'message' => 'failed!, field cannot be empty!'
				];
			} elseif (strlen($username) < 6 || strlen($username) > 15) {
				# if username length is less than 6 char and more than 15 char...
				$response = [
					'status' => 400,
					'error' => true,
					'data' => null,
					'message' => 'failed!, username min length 6 and max 15!'
				];
			} elseif (strlen($password) < 6) {
				# if password is less than 6 char...
				$response = [
					'status' => 400,
					'error' => true,
					'data' => null,
					'message' => 'failed!, password minimum 6 char!'
				];
			} else {
				$insert = $model->insert($data);
				$response = [
					'status' => 200,
					'error' => false,
					'data' => $data,
					'message' => 'success!, User created'
				];
			}
		}


		return $this->respondCreated($response);
	}

	private function uploadImg($user_id = null)
	{

		# code...
		$upload = $this->request->getFile('image');
		if ($upload != null) {
			# code...
			$data = $user_id . '_profile.' . $upload->getClientExtension();
			$upload->move(ROOTPATH . 'public/uploads/images/profile', $data);
			return $upload->getName();
		}else{
			return 'default.jpg';
		}
	}

	public function login()
	{
		# code...
		$model = new UserModel();
		$username = $this->request->getPost('username');
		$password = $this->request->getPost('password');
		$data = $model->getWhere(['username' => $username])->getRow();
		if ($data != null) {
			# code...
			if ($data->status == "1") {
				# code...
				$response = [
					'status' => 400,
					'error' => null,
					'data' => null,
					'message' => 'Failed!, User is logged in'
				];

				return $this->respond($response);
			} else {
				if (password_verify($password, $data->password)) {
					# code...
					$response = [
						'status' => 200,
						'error' => null,
						'data' => $data,
						'message' => 'success!, User logged in'
					];

					//update status
					$model->update($data->user_id, ['status' => 1]);

					return $this->respond($response);
				} else {
					$response = [
						'status' => 400,
						'error' => null,
						'data' => null,
						'message' => 'Failed!, Wrong password'
					];

					return $this->respond($response);
				}
			}
		} elseif (empty($username)) {
			# code...
			$response = [
				'status' => 400,
				'error' => null,
				'data' => null,
				'message' => 'Failed!, Field Empty'
			];

			return $this->respond($response);
		} else {
			$response = [
				'status' => 400,
				'error' => null,
				'data' => null,
				'message' => $username . ' not found!'
			];

			return $this->respond($response);
		}
	}

	public function logout()
	{
		# code...
		$model = new UserModel();
		$id = $this->request->getPost('user_id');
		$data = $model->getWhere(['user_id' => $id])->getRow();
		if ($data != null) {
			# code...
			$status = array('status' => 0);
			$model->update(['user_id' => $id], $status);
			$response = [
				'status' => 200,
				'error' => null,
				'data' => null,
				'message' => 'User Logged out!'
			];
		} else {
			$response = [
				'status' => 401,
				'error' => true,
				'data' => null,
				'message' => 'User id not found/something wrong!'
			];
		}
		return $this->respond($response);
	}

	public function update($id = null)
	{
		$model = new UserModel();
		//$data = $model->getWhere(['user_id' => $id])->getRow();
		$model->update($id, ['status' => 0]);
		$response = [
			'status' => 200,
			'error' => null,
			'user_id' => $id,
			'messages' => [
				'success' => 'User Logged out!'
			]
		];
		return $this->createResponse(200, false, $id, 'User logged out!');
	}
	public function delete($id = null)
	{
		# code...
		/*$model = new PartsModel();
		$data = $model->where('parts_id',$id)->delete($id);
		if ($data) {
			# code...
			$response = [
				'status' => 200,
				'error' => null,
				'message' => 'success!, data deleted'
			];
			return $this->respondDeleted($response);
		}else{
			return $this->failNotFound('ID Not Found');
		}*/
	}

	private function createResponse(int $status, bool $error, $data, String $message)
	{
		# code...
		$response = [
			'status' => $status,
			'error' => $error,
			'data' => $data,
			'message' => $message
		];

		return $response;
	}
}
