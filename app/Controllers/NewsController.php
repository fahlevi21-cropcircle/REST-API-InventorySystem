<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\NewsModel;

/**
 * 
 */
class NewsController extends ResourceController
{
	use ResponseTrait;

	public function index()
	{
		# code...
		$model = new NewsModel();
		return $this->respond($model->findAll(),200);
	}

	public function show($id = null)
	{
		# code...
		$model = new NewsModel();
		$data = $model->getWhere(['news_id' => $id])->getResult();
		if ($data) {
			# code...
			return $this->respond($data,200);
		}else{
			return $this->failNotFound('ID Not Found '.$id);
		}
	}

	public function highlight()
	{
		# code...
		$model = new NewsModel();
		$model->where('date_modified >= "'.date('Y:m:d').'"');
		return $this->respond($model->get(5)->getResult(),200);
	}

	public function create()
	{
		# code...
		$model = new NewsModel();
    	$news_id = uniqid();
    	$username = $this->request->getPost('username');
    	$department = $this->request->getPost('department');
    	$date_created = date('Y:m:d H:m:s');
    	$date_modified = date('Y:m:d H:m:s');
    	$title = $this->request->getPost('title');
    	$description = $this->request->getPost('description');
    	$solution = $this->request->getPost('solution');
    	$images = $this->uploadImg($news_id);
    	$status = $this->request->getPost('status');

    	$data = array(
    		'news_id' => $news_id,
    		'username' => $username,
    		'department' => $department,
    		'date_created' => $date_created,
    		'date_modified' => $date_modified,
    		'title' => $title,
    		'description' => $description,
    		'solution' => $solution,
    		'image' => $images,
    		'status' => $status
    	);

    	if ($data != null) {
    		# code...
    		$model->insert($data);
			$response = [
            	'status' => 200,
            	'data' => $data,
            	'messages' => [
                	'success' => 'success, data created!'
            	]
        	];
    	}else{
    		$response = array(
            	'status' => 400,
            	'data' => null,
            	'message' => [
                	'Error' => 'Error, No data!'
            	]
    		);
    	}

    	return $this->respond($response);
		
	}

	public function writeNews()
	{
		# code...

		$model = new NewsModel();
    	$news_id = uniqid();
    	$username = $this->request->getPost('username');
    	$department = $this->request->getPost('department');
    	$date_created = date('Y:m:d H:m:s');
    	$date_modified = date('Y:m:d H:m:s');
    	$title = $this->request->getPost('title');
    	$description = $this->request->getPost('description');
    	$solution = $this->request->getPost('solution');
    	$images = $this->uploadImg($news_id);
    	$status = $this->request->getPost('status');

    	$data = array(
    		'news_id' => $news_id,
    		'username' => $username,
    		'department' => $department,
    		'date_created' => $date_created,
    		'date_modified' => $date_modified,
    		'title' => $title,
    		'description' => $description,
    		'solution' => $solution,
    		'image' => $images,
    		'status' => $status
    	);

    	if ($data != null) {
    		# code...
    		$model->insert($data);
			$response = [
            	'status' => 200,
            	'data' => $data,
            	'messages' => [
                	'success' => 'success, data created!'
            	]
        	];
    	}else{
    		$response = array(
            	'status' => 400,
            	'data' => null,
            	'message' => [
                	'Error' => 'Error, No data!'
            	]
    		);
    	}

    	return $this->respond($response);
	}

	private function uploadImg($news_id = null)
	{

		# code...
		$upload = $this->request->getFile('image');
		$data = $news_id.'_news.'.$upload->getClientExtension();
		$upload->move(ROOTPATH. 'public/uploads/images/news',$data);

		return $upload->getName();
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