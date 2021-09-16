<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\PartsModel;
use App\Models\StorageModel;

/**
 * 
 */
class PartsController extends ResourceController
{
	use ResponseTrait;

	//for response
	private $responseCode;
	private $responseError;
	private $responseData;
	private $responseMessage;

	private $imgDir = ROOTPATH . 'public/uploads/images/parts';

	public function index()
	{
		# code...
		$model = new PartsModel();
		return $this->respond($model->findAll(), 200);
	}

	public function getByStorage($id = null)
	{
		# code...
		$model = new PartsModel();
		$data = $model->getWhere(['storage_no' => $id])->getResult();
		if ($data == null) {
			$response = [
				'status' => 404,
				'error' => null,
				'data' => null,
				'message' => 'Not found!'
			];
		} else {
			$response = [
				'status' => 200,
				'error' => null,
				'data' => $data,
				'message' => 'OK!'
			];
		}

		return $this->respond($response);
	}

	public function show($id = null)
	{
		# code...
		$model = new PartsModel();
		$data = $model->getWhere(['item_id' => $id])->getRow();
		if ($data) {
			# code...
			$response = $this->createResponse(200, false, $data, 'OK!');
			return $this->respond($response);
		} else {
			return $this->failNotFound('ID Not Found ' . $id);
		}
	}

	public function create()
	{
		# code...
		$model = new PartsModel();
		$storage_model = new StorageModel();

		$itemId = uniqid("item_");
		$itemName = $this->request->getPost('item_name');
		$itemDescription = $this->request->getPost('item_description');
		$itemQuantity = $this->request->getPost('item_quantity');
		$unit = $this->request->getPost('unit');
		$minQuantity = $this->request->getPost('min_quantity');
		$itemCategory = $this->request->getPost('item_category');
		$dateCreated = date('Y:m:d H:m:s');
		$dateModified = date('Y:m:d H:m:s');
		$itemImage = $this->uploadImg($itemId);
		$storageNo = $this->request->getPost('storage_no');

		$data = [
			'item_id' => $itemId,
			'item_name' => $itemName,
			'item_description' => $itemDescription,
			'item_quantity' => $itemQuantity,
			'unit' => $unit,
			'min_quantity' => $minQuantity,
			'item_category' => $itemCategory,
			'date_created' => $dateCreated,
			'date_modified' => $dateModified,
			'item_image' => $itemImage,
			'storage_no' => $storageNo
		];

		if ($data != null) {
			# code...
			//update current selected storage
			//get current storage based on selected storage
			//Max storage is necessary to decided if the storage is full
			$curr_storage = $storage_model->getWhere(['storage_no' => $storageNo])->getRow();

			if ($curr_storage != null) {
				$curr_storage_space = $curr_storage->current_space;
				$max_storage_space = $curr_storage->max_space;

				//increase the storage by one, right now per item is counted as 1
				//even the quantity is more than 1
				$storage_count = $curr_storage_space + 1;

				if ($curr_storage >= $max_storage_space) {
					# code...
					$model->insert($data);
					//update the storage
					$storage_model->update(['storage_no' => $storageNo], ['current_space' => $storage_count]);
					$this->responseCode = 200;
					$this->responseError = false;
					$this->responseData = null;
					$this->responseMessage = 'Success!, data created';
					$response = $this->createResponse($this->responseCode, $this->responseError, $this->responseData, $this->responseMessage);
				} else {
					$this->responseCode = 400;
					$this->responseError = false;
					$this->responseData = null;
					$this->responseMessage = 'Failed!, insufficent storage';
					$response = $this->createResponse($this->responseCode, $this->responseError, $this->responseData, $this->responseMessage);
				}
			} else {
				$this->responseCode = 400;
				$this->responseError = false;
				$this->responseData = null;
				$this->responseMessage = 'Failed!, storage not found';
				$response = $this->createResponse($this->responseCode, $this->responseError, $this->responseData, $this->responseMessage);
			}
		} else {
			$this->responseCode = 400;
			$this->responseError = false;
			$this->responseData = null;
			$this->responseMessage = 'Failed!, data is empty';
			$response = $this->createResponse($this->responseCode, $this->responseError, $this->responseData, $this->responseMessage);
		}

		return $this->respondCreated($response);
	}

	private function uploadImg($partsId = null)
	{
		# code...
		$upload = $this->request->getFile('image');
		if ($upload != null) {
			$data = $partsId . '_parts.' . $upload->getClientExtension();
			$upload->move(ROOTPATH . 'public/uploads/images/parts', $data);
			$file = $upload->getName();
			return $file;
		} else {
			return 'default.jpg';
		}
	}

	private function deleteImg($path = null)
	{
		# code...
		delete_files($path);
	}

	public function update($id = null)
	{
		$model = new PartsModel();
		$json = $this->request->getJSON();
		if ($json) {
			$data = [
				'item_name' => $json->item_name,
				'item_description' => $json->item_description,
				'item_quantity' => $json->item_quantity,
				'item_category' => $json->item_category,
				'min_quantity' => $json->min_quantity,
				'unit' => $json->unit,
				'date_modified' => date('Y-m-d H:m:s')
			];

			/* if ($json->image != null) {
				# for image...
				$data = [
					'item_description' => $json->item_description,
					'item_quantity' => $json->item_quantity,
					'item_category' => $json->item_category,
					'min_quantity' => $json->min_quantity,
					'item_image' => $json->image,
					'unit' => $json->unit,
					'date_modified' => date('Y-m-d H:m:s')
				];
			} else {
				$data = [
					'item_description' => $json->item_description,
					'item_quantity' => $json->item_quantity,
					'item_category' => $json->item_category,
					'min_quantity' => $json->min_quantity,
					'unit' => $json->unit,
					'date_modified' => date('Y-m-d H:m:s')
				];
			} */
		} else {
			$input = $this->request->getRawInput();
			$data = [
				'item_description' => $input['item_description'],
				'item_quantity' => $input['item_quantity'],
				'item_category' => $input['item_category'],
				'min_quantity' => $input['min_quantity'],
				'unit' => $input['unit'],
				'date_modified' => date('Y-m-d H:m:s')
			];
			/* if ($input['image'] != null) {
				# for image...
				$data = [
					'item_description' => $input['description'],
					'item_quantity' => $input['quantity'],
					'item_category' => $input['category'],
					'min_quantity' => $input['minimum'],
					'item_image' => $input['image'],
					'unit' => $input['unit'],
					'date_modified' => date('Y-m-d H:m:s')
				];
			}else{
				$data = [
					'item_description' => $input['description'],
					'item_quantity' => $input['quantity'],
					'item_category' => $input['category'],
					'min_quantity' => $input['minimum'],
					'unit' => $input['unit'],
					'date_modified' => date('Y-m-d H:m:s')
				];
			} */
		}
		// update to Database
		$model->update($id, $data);
		$response = $this->createResponse(200, false, null, 'success!, data updated');
		return $this->respond($response);
	}
	public function delete($id = null)
	{
		# code...
		$model = new PartsModel();
		$data = $model->where('item_id', $id)->delete($id);
		if ($data) {
			# code...
			$response = [
				'status' => 200,
				'error' => null,
				'message' => 'success!, data deleted'
			];
			return $this->respondDeleted($response);
		} else {
			return $this->failNotFound('ID Not Found');
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
}
