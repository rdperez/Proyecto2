<?php
namespace App\Models;

use CodeIgniter\Model;

class RolesModel extends Model{
    protected $table        = 'roles';
    protected $id           = 'rolID';
    protected $primaryKey   = 'rolID';
    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = [
        'name',
        'access',
        'status'
    ];

}