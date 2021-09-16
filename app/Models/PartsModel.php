<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * 
 */
class PartsModel extends Model
{
    protected $table = "items";
    protected $primaryKey = 'item_id';
    protected $allowedFields = [
        'item_id', 'item_name', 'item_description', 'item_quantity',
        'unit', 'min_quantity', 'item_category', 'date_created', 'date_modified', 'item_image',
        'storage_no'
    ];
}
