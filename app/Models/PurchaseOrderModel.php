<?php

namespace App\Models;
use CodeIgniter\Model;

/**
 * 
 */
class PurchaseOrderModel extends Model
{
	protected $table = "purchase_order";
    protected $primaryKey = 'order_id';
    protected $allowedFields = ['order_id','parts_name','parts_description','quantity','unit_price','total','supplier_name','order_date','date_modified','approval','status','details'];
}


?>