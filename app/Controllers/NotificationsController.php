<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\NotificationsModel;

/**
 * 
 */
class NotificationsController extends ResourceController
{
	use ResponseTrait;

	public function index()
	{
		# code...
		$model = new NotificationsModel();
		return $this->respond($model->findAll(),200);
	}

	public function show($id = null)
	{
		# code...
		$model = new NotificationsModel();
		$data = $model->getWhere(['notification_id' => $id])->getResult();
		if ($data) {
			# code...
			return $this->respond($data,200);
		}else{
			return $this->failNotFound('ID Not Found '.$id);
		}
	}

	public function create()
	{
		# code...
		$model = new NotificationsModel();
		$notification_id = uniqid();
		$receiver_id = $this->request->getPost('receiver_id');
		$sender_id = $this->request->getPost('sender_id');
		$title = $this->request->getPost('title');
		$description = $this->request->getPost('description');
		$type = $this->request->getPost('type');
		$date_created = date("Y-m-d H:m:s");
		$date_modified = date("Y-m-d H:m:s");
		$status = $this->request->getPost('status');

		$data = array(
			'notification_id' => $notification_id,
			'receiver_id' => $receiver_id,
			'sender_id' => $sender_id,
			'title' => $title,
			'description' => $description,
			'type' => $type,
			'date_created' => $date_created,
			'date_modified' => $date_modified,
			'status' => $status
		);

		if ($data != null) {
			# code...
			$model->insert($data);
			$response = [
            	'status' => 200,
            	'data' => $data,
            	'messages' => [
                	'success' => 'Notification Created'
            	]
        	];
		}else{
			$response = [
            	'status' => 400,
            	'data' => $data,
            	'messages' => [
                	'error' => 'No data found!'
            	]
        	];
		}

		return $this->respond($response);
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