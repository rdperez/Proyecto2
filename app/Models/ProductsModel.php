<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductsModel extends Model{
    protected $table        = 'products';
    protected $id           = 'productID';
    protected $primaryKey   = 'productID';
    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = [
        'name',
        'access',
        'status'
    ];
}