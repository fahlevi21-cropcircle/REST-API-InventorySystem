<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\StorageModel;

/**
 * 
 */
class StorageController extends ResourceController
{
	use ResponseTrait;

	public function index()
	{
		# code...
		$model = new StorageModel();
		return $this->respond($model->findAll(), 200);
	}

	public function show($id = null)
	{
		# code...
		$model = new StorageModel();

		$data = $model->getWhere(['storage_no' => $id])->getRow();
		if ($data) {
			# code...
			return $this->respond($data, 200);
		} else {
			return $this->failNotFound('Storage Not Found ' . $id);
		}
	}

	public function create()
	{
		# code...
		$model = new StorageModel();
		$storage_no = $this->request->getPost('storage_no');
		$current_space = 0;
		$storage_image = $this->uploadImg($storage_no);
		$max_space = $this->request->getPost('max_space');
		$storage_name = $this->request->getPost('storage_name');
		$storage_description = $this->request->getPost('storage_description');
		$date_created = date('Y:m:d H:m:s');
		$date_modified = date('Y:m:d H:m:s');
		$data = [
			'storage_no' => $storage_no,
			'current_space' => $current_space,
			'max_space' => $max_space,
			'storage_name' => $storage_name,
			'storage_description' => $storage_description,
			'date_created' => $date_created,
			'date_modified' => $date_modified,
			'storage_image' => $storage_image
		];

		//check if storage exists
		$storage_list = $model->getWhere(['storage_no' => $storage_no])->getRow();
		if ($storage_list != null) {
			# code...
			$response = [
				'status' => 400,
				'error' => true,
				'data' => null,
				'message' => 'failed!, Storage is exists'
			];
		} else {
			$insert = $model->insert($data);
			$response = [
				'status' => 200,
				'error' => null,
				'data' => null,
				'message' => 'success!, Storage created'
			];
		}
		return $this->respondCreated($response);
	}

	public function update($id = null)
	{
		$model = new StorageModel();
		$json = $this->request->getJSON();
		if ($json) {
			$storageNo = $json->storage_no;
			$storageDescription = $json->storage_description;
			$storageName = $json->storage_name;
			$maxSpace = $json->max_space;
			//$storageImage = $this->uploadImg($storageNo);
			$data = [
				'storage_no' => $storageNo,
				'storage_description' => $storageDescription,
				'storage_name' => $storageName,
				'date_modified' => date('Y:m:d H:m:s'),
				'max_space' => $maxSpace
			];
			// Update to Database
			$model->update($id, $data);
			$response = $this->createResponse(200,false,null,'success!, Data Updated with JSON');
		} else {
			$input = $this->request->getRawInput();
			$data = [
				'storage_no' => $input['storage_no'],
				'storage_name' => $input['storage_name'],
				'storage_description' => $input['storage_description'],
				'date_modified' => date('Y:m:d H:m:s'),
				'max_space' => $input['max_space']
			];
			// Update to Database
			$model->update($id, $data);
			$response = $this->createResponse(200,false,null,'success!, Data Updated with RawInput');
		}
		return $this->respond($response);
	}
	public function delete($id = null)
	{
		# code...
		$model = new StorageModel();
		$data = $model->where('storage_no', $id)->delete($id);
		if ($data) {
			# code...
			$response = [
				'status' => 200,
				'error' => null,
				'message' => 'success!, data deleted'
			];
			return $this->respondDeleted($response);
		} else {
			return $this->failNotFound('Storage Not Found');
		}
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

	private function uploadImg($id = null)
	{

		# code...
		$upload = $this->request->getFile('image');
		if ($upload != null) {
			$data = $id . '.' . $upload->getClientExtension();
			$upload->move(ROOTPATH . 'public/uploads/images/storage', $data);
			$file = $upload->getName();
			return $file;
		}else{
			return 'default.jpg';
		}

	}
}
