<?php

namespace App\Models;
use CodeIgniter\Model;

/**
 * 
 */
class NotificationsModel extends Model
{
	protected $table = "notifications";
    protected $primaryKey = 'notification_id';
    protected $allowedFields = ['notification_id','title','description'];
}


?>