<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UsersModel;

class Users extends ResourceController
{
	protected $modelName = 'App\Models\UsersModel';

    public function __construct(){
        $this->validation = \Config\Services::validation();
    }

    public function index(){
        $users = $this->model->findAll();
        /*
            $data = [
                'title_meta' => view('partials/title-meta', ['title' => 'Quadrants/Escamots']), // Document title
                'page_title' => view('partials/page-title', ['title' => 'Quadrants/Escamots', 'pagetitle' => 'Main']), // Breadcrumbs
                'scripts'    => view('partials/add-scripts',[
                    'scripts'=> [
                        '/assets/js/pages/bags/bags.js',
                        '/assets/libs/jquery.repeater/jquery.repeater.min.js'
                    ]
                ])
            ];
        */

        $data = [
            'script' => 'js/pages/login/login.js',
            'users' => $users
        ];

		return view('/pages/users/index', $data);
    }

    public function signin(){
        try{
            $input = $this->request->getPost();
            $username = $input['username'];
            // $password = md5($input['password']);
            $password = $input['password'];
            
            $user = $this->model
                        ->where('userName', $username)
                        ->where('password', $password)
                        ->first();
    
            if(empty($user)) return $this->fail('Estas credenciales no coinciden con nuestros registros!');
            
            return $this->respond(['user' => $user]);
        }catch(Exception $e){
            return $this->respond($e, 401);
        }
    }

    public function list(){
        $users = $this->model>findAll();
        /*
            $data = [
                'title_meta' => view('partials/title-meta', ['title' => 'Quadrants/Escamots']), // Document title
                'page_title' => view('partials/page-title', ['title' => 'Quadrants/Escamots', 'pagetitle' => 'Main']), // Breadcrumbs
                'scripts'    => view('partials/add-scripts',[
                    'scripts'=> [
                        '/assets/js/pages/bags/bags.js',
                        '/assets/libs/jquery.repeater/jquery.repeater.min.js'
                    ]
                ])
            ];
        */

        $data = [
            'script' => 'js/pages/login/login.js',
            'users' => $users
        ];

		return view('/pages/users/index', $data);
    }

    public function create(){
        $input = $this->request->getPost();

        $input['password'] = md5($input['password']);

        $userID = $this->model->insert($input);
        
        return $this->response(['userID' => $userID]);
    }

    public function update($id = null){
        if($id == null) return $this->fail('No se proporciono ninguna idenficacion de usuario');

        $oldData = $this->model->find($id);
        if(empty($oldData)) return $this->fail('El usuario no existe');

        $input = $this->request->getPost();

        if (!empty($input['password'])) {
            $input['password'] = md5($input['password']);
        }

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
