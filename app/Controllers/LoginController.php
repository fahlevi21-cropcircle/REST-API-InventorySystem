<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

/**
 * 
 */
class LoginController extends ResourceController
{
	use ResponseTrait;

	public function create()
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
					'password' => $password,
					'message' => 'Failed!, User is logged in'
				];

				return $this->respond($response);
			}else{
				if (password_verify($password, $data->password)) {
					# code...
					$response = [
						'status' => 200,
						'error' => null,
						'data' => $data,
						'message' => 'success!, User logged in'
					];

					//update status
					$model->update($data->user_id,['status' => 1]);

					return $this->respond($response);
				}else{
					$response = [
						'status' => 400,
						'error' => null,
						'password' => $password,
						'message' => 'Failed!, Wrong password'
					];

					return $this->respond($response);
				}
			}
			
		}elseif (empty($username)) {
			# code...
			$response = [
				'status' => 400,
				'error' => null,
				'message' => 'Failed!, Field Empty'
			];

			return $this->respond($response);
		}else{
			$response = [
				'status' => 400,
				'error' => null,
				'username' => $username,
				'message' => 'There is no '.$username.' in our database!'
			];

			return $this->respond($response);
		}
		
	}

	public function logout($id = null)
	{
		# code...
		$model = new UserModel();
		$data = $model->getWhere(['user_id' => $id])->getRow();
		if ($data != null) {
			# code...
			$status = array('status' => 0);
			$model->update(['user_id' => $id],$status);
			$response = [
            	'status' => 200,
            	'error' => null,
            	'user_id' => $id,
            	'messages' => [
                	'success' => 'User Logged out!'
            	]
        	];
		}else{
			$response = [
            	'status' => 401,
            	'error' => true,
            	'user_id' => $id,
            	'messages' => [
                	'error' => 'User id not found/something wrong!'
            	]
        	];
		}
		return $this->respond($response);
	}	

	public function update($id = null)
    {
    	$model = new UserModel();
		//$data = $model->getWhere(['user_id' => $id])->getRow();
		$model->update($id,['status' => 0]);
		$response = [
            'status' => 200,
            'error' => null,
            'user_id' => $id,
            'messages' => [
                'success' => 'User Logged out!'
            ]
        ];
        return $this->respond($response);
        /*$model = new PartsModel();
        $json = $this->request->getJSON();
        if($json){
            $data = [
            	'parts_id' => $id,
				'description' => $json->description,
				'quantity' => $json->quantity,
				'storage_no' => $json->storage_no,
				'category' => $json->category,
				'minimum' => $json->minimum
            ];
        }else{
            $input = $this->request->getRawInput();
            $data = [
                'parts_id' => $input['parts_id'],
				'description' => $input['description'],
				'quantity' => $input['quantity'],
				'storage_no' => $input['storage_no'],
				'category' => $input['category'],
				'minimum' => $input['minimum']
            ];
        }
        // Insert to Database
        $model->update($id, $data);
        */
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
}



?>