<?php

namespace App\Controllers;

use App\Models\RolesModel;

class Roles extends BaseController
{
	protected $modelName = 'App\Models\RolesModel';

    public function __construct(){
        $this->validation = \Config\Services::validation();
    }

    public function index(){
        return 'hola2';
    }

    public function list(){
        $roles = $this->model>findAll();
        
        return $this->response(['roles' => $roles]);
    }

    public function create(){
        $input = $this->request->getPost();

        $userID = $this->model->insert($input);
        
        return $this->response(['userID' => $userID]);
    }

    public function update($id = null){
        if($id == null) return $this->fail('No se proporciono ninguna idenficacion de usuario');

        $oldData = $this->model->find($id);
        if(empty($oldData)) return $this->fail('El usuario no existe');

        $input = $this->request->getPost();

        $this->model->update($id, $input);
        
        return $this->response(['userID' => $id]);
    }

    public function delete($id = null){
        if($id == null) return $this->fail('No se proporciono ninguna idenficacion de usuario');

        $oldData = $this->model->find($id);
        if(empty($oldData)) return $this->fail('El usuario no existe');

        $this->model->delete($id);
        
        return $this->response(['userID' => $id]);
    }
}
