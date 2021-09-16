<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\RequestModel;
use App\Models\PartsModel;
use App\Models\StorageModel;

/**
 * 
 */
class RequestController extends ResourceController
{
	use ResponseTrait;

	public function index()
	{
		# code...

		/* inner join
		SELECT requests.request_id, requests.username, requests.status, requests.messages, requests.admin, items.item_name, users.image FROM `requests`
INNER JOIN items ON items.item_id = requests.item_id
INNER JOIN users ON users.user_id = requests.request_id
		
		*/
		$model = new RequestModel();
		$response = $this->createResponse(200, false, $model->findAll(), 'OK!');
		return $this->respond($response);
	}

	public function getByStatus($status = null)
	{
		# code...
		$model = new RequestModel();
		$data = $model->getWhere(['status' => $status]);
		if ($data != null) {
			# code...
			$response = $this->createResponse(200, false, $data, 'OK!');
		}else {
			# code...
			$response = $this->createResponse(200, true, null, 'No data found!');
		}
		
		return $this->respond($response);
	}

	public function show($id = null)
	{
		# code...
		$model = new RequestModel();
		$data = $model->getWhere(['request_id' => $id])->getRow();
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
		$model = new RequestModel();
		$requestId = uniqid('request_');
		$itemName = $this->request->getPost('item_name');
		$itemId = $this->request->getPost('item_id');
		$username = $this->request->getPost('username');
		$requestQuantity = $this->request->getPost('request_quantity');
		$data = [
			'request_id' => $requestId,
			'item_name' => $itemName,
			'item_id' => $itemId,
			'username' => $username,
			'request_quantity' => $requestQuantity
		];
		$insert = $model->insert($data);
		if ($insert) {
			$response = $this->createResponse(
				400,
				true,
				null,
				'failed!, data not created!'
			);
		} else {
			$response = $this->createResponse(
				200,
				false,
				null,
				'Success!, data created!'
			);
		}

		return $this->respond($response);
	}

	public function update($id = null)
	{
		$model = new RequestModel();
		$partsModel = new PartsModel();
		$json = $this->request->getJSON();
		if ($json) {
			//get JSON data from client request
			$itemId = $json->item_id;
			$requestQuantity = $json->request_quantity;
			$admin = $json->admin;
			$messages = $json->messages;
			$status = $json->status;

			//wrap in array
			$dataJSON = [
				'admin' => $admin,
				'messages' => $messages,
				'status' => $status,
				'date_modified' => date('Y:m:d H:m:s')
			];

			//check item
			$itemData = $partsModel->getWhere(['item_id' => $itemId])->getRow();
			if ($itemData != null) {
				# if the item data is available in storage
				if (!$itemData->item_quantity < $requestQuantity) {
					# if the item quantity is not less than the request quantity (item is available for request)
					//do update request
					$model->update($id, $dataJSON);
					$response = $this->createResponse(
						200,
						false,
						null,
						'Success!, request updated! (RAW)'
					);
					# update item quantity
					$updatedQty = $itemData->item_quantity - $requestQuantity;
					$partsModel->update(['item_id' => $itemId], ['item_quantity' => $updatedQty]);

					# update storage space if item quantity is 0
					if ($updatedQty == 0) {
						# if the item quantity is 0 (item is not in storage anymore)
						$storageModel = new StorageModel();
						$storageData = $storageModel->getWhere(['storage_no' => $itemData->storage_no])->getRow();
						$currentSpace = $storageData->current_space - 1;
						$storageModel->update($itemData->storage_no, ['current_space' => $currentSpace]);
					}
				} else {
					# the item quantity is lower than the request quantity (item is not available for request)
					$response = $this->createResponse(
						400,
						true,
						null,
						'failed!, request item not allowed!'
					);
				}
			} else {
				# if the item is not available
				$response = $this->createResponse(
					400,
					true,
					$itemId,
					'failed!, item not found!'
				);
			}
		} else {
			//get POST data from client request
			$raw = $this->request->getRawInput();
			$itemId = $raw['item_id'];
			$requestQuantity = $raw['request_quantity'];
			$admin = $raw['admin'];
			$messages = $raw['messages'];
			$status = $raw['status'];

			//wrap in array
			$dataJSON = [
				'admin' => $admin,
				'messages' => $messages,
				'status' => $status,
				'date_modified' => date('Y:m:d H:m:s')
			];

			//check item
			$itemData = $partsModel->getWhere(['item_id' => $itemId])->getRow();
			if ($itemData != null) {
				# if the item data is available in storage
				if (!$itemData->item_quantity < $requestQuantity) {
					# if the item quantity is not less than the request quantity (item is available for request)
					//do update request
					$model->update($id, $dataJSON);
					$response = $this->createResponse(
						200,
						false,
						null,
						'Success!, request updated! (RAW)'
					);
					# update item quantity
					$updatedQty = $itemData->item_quantity - $requestQuantity;
					$partsModel->update(['item_id' => $itemId], ['item_quantity' => $updatedQty]);

					# update storage space if item quantity is 0
					if ($updatedQty == 0) {
						# if the item quantity is 0 (item is not in storage anymore)
						$storageModel = new StorageModel();
						$storageData = $storageModel->getWhere(['storage_no' => $itemData->storage_no])->getRow();
						$currentSpace = $storageData->current_space - 1;
						$storageModel->update($itemData->storage_no, ['current_space' => $currentSpace]);
					}
				} else {
					# the item quantity is lower than the request quantity (item is not available for request)
					$response = $this->createResponse(
						400,
						true,
						null,
						'failed!, request item not allowed!'
					);
				}
			} else {
				# if the item is not available
				$response = $this->createResponse(
					400,
					true,
					$itemId,
					'failed!, item not found!'
				);
			}
		}

		//Insert to Database
		return $this->respond($response);
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
