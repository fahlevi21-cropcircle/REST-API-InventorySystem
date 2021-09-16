<?php

namespace App\Models;
use CodeIgniter\Model;

/**
 * 
 */
class NewsModel extends Model
{
	protected $table = "maintenance_news";
    protected $primaryKey = 'news_id';
    protected $allowedFields = ['news_id','username','department','date_created','date_modified','title','description','solution','image','status'];
}


?>