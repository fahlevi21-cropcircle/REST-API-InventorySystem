<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * 
 */
class UserModel extends Model
{
    protected $table = "users";
    protected $primaryKey = 'user_id';
    protected $allowedFields = [
        'user_id', 'nik', 'username', 'password',
        'department', 'modifier', 'image',
        'date_created', 'date_modified', 'status'
    ];
}
