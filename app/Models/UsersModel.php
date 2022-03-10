<?php
namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model{
    protected $table        = 'users';
    protected $id           = 'userID';
    protected $primaryKey   = 'userID';
    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = [
        'userName',
        'firstName',
        'lastName',
        'ci',
        'email',
        'phone',
        'password',
        'businessID',
        'status',
        'rolID',
        'sex',
        'dateRegister'
    ];
}