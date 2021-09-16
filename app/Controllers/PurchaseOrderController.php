<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\PurchaseOrderModel;

/**
 * 
 */
class PurchaseOrderController extends ResourceController
{
	use ResponseTrait;

	public function index()
	{
		# code...
		$model = new PurchaseOrderModel();
		return $this->respond($model->findAll(),200);
	}

	public function show($id = null)
	{
		# code...
		$model = new PurchaseOrderModel();
		$data = $model->getWhere(['order_id' => $id])->getResult();
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
		/*$model = new PartsModel();
		$data = [
			'parts_id' => uniqid(),
			'description' => $this->request->getPost('description'),
			'quantity' => $this->request->getPost('quantity'),
			'storage_no' => $this->request->getPost('storage_no'),
			'category' => $this->request->getPost('category'),
			'minimum' => $this->request->getPost('minimum')
		];
		$insert = $model->insert($data);
		$response = [
			'status' => 200,
			'error' => null,
			'data' => $data,
			'message' => 'success!, data created'
		];

		return $this->respondCreated($response);*/
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