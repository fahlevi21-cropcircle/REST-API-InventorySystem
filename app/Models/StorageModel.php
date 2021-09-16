<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * 
 */
class StorageModel extends Model
{
    protected $table = "storages";
    protected $primaryKey = 'storage_no';
    protected $allowedFields = [
        'storage_no', 'current_space', 'max_space',
        'storage_name', 'storage_description','date_modified',
        'date_created'
    ];
}
