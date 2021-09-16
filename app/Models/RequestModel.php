<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * 
 */
class RequestModel extends Model
{
    protected $table = "requests";
    protected $primaryKey = 'request_id';
    protected $allowedFields = [
        'request_id', 'item_name', 'item_id', 'username', 'request_quantity', 'date_created', 'date_modified', 'admin', 'messages', 'status'
    ];
}
