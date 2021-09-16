<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

/**
 * 
 */
class RegisterController extends ResourceController
{
	use ResponseTrait;

	public function show($username = null)
	{
		# code...
		$model = new UserModel();
		$data = $model->getWhere(['username' => $username])->first();
		if ($data != null) {
			# code...
			if (password_verify(password, hash)) {
				# code...
			}
			return $this->respond($data,200);
		}else{
			return $this->failNotFound('User Not Found '.$id);
		}
	}

	public function create()
	{
		# code...
		//init model object
		$model = new UserModel();

		//get data from post
		$username = $this->request->getPost('username');
		$password =  $this->request->getPost('password');
		$nik = $this->request->getPost('nik');
		$department = $this->request->getPost('department');
		$modifier = $this->request->getPost('modifier');
		$user_id = uniqid();

		//insert the data to array
		$data = [
			'user_id' => $user_id,
			'nik' => $nik,
			'username' => $username,
			'password' => password_hash($password, PASSWORD_DEFAULT),
			'department' => $department,
			'modifier' => $modifier,
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
				'error' => null,
				'username' => $this->request->getPost('username'),
				'message' => 'failed!, Username exists'
			];
		}else{
			if (empty($username) || empty($password) || 
				empty($nik) || empty($department)) {
				# if field is empty...
				$response = [
					'status' => 400,
					'error' => null,
					'data' => $data,
					'message' => 'failed!, field cannot be empty!'
				];
			}elseif (strlen($username) < 6 || strlen($username) > 15) {
				# if username length is less than 6 char and more than 15 char...
				$response = [
					'status' => 400,
					'error' => null,
					'data' => $data,
					'message' => 'failed!, username min length 6 and max 15!'
				];
			}elseif (strlen($password) < 6) {
				# if password is less than 6 char...
				$response = [
					'status' => 400,
					'error' => null,
					'data' => $data,
					'message' => 'failed!, password minimum 6 char!'
				];
			}else{
				$insert = $model->insert($data);
				$response = [
					'status' => 200,
					'error' => null,
					'data' => $data,
					'message' => 'success!, User created'
				];
			}
		}
		

		return $this->respondCreated($response);
	}

	public function process()
    {
 
        if ($this->request->getMethod() !== 'post') {
        }
 
        $validated = $this->validate([
            'file_upload' => 'uploaded[file_upload]|mime_in[file_upload,image/jpg,image/jpeg,image/gif,image/png]|max_size[file_upload,4096]'
        ]);
  
        if ($validated == FALSE) {
             
            // Kembali ke function index supaya membawa data uploads dan validasi
            return $this->index();
 
        } else {
 
            $avatar = $this->request->getFile('file_upload');
            $avatar->move(ROOTPATH . 'public/uploads');
 
            $data = [
                'gambar' => $avatar->getName()
            ];
     
            $this->model_upload->insert_gambar($data);
            return redirect()->to(base_url('upload'))->with('success', 'Upload successfully'); 
        }
 
    }

	public function update($id = null)
    {
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
        $response = [
            'status' => 200,
            'error' => null,
            'data' => $data,
            'messages' => [
                'success' => 'Data Updated'
            ]
        ];
        return $this->respond($response);*/
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