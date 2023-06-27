<?php

namespace App\Controllers;

class Auth extends BaseController {

    public function __construct(){
        // used to display error messages when field don't match the validation
        helper(['url', 'form']); 

    }

    public function index()
    {
        return view('auth/login');
    }

    public function register()
    {
        return view('auth/register'); 
    }

    public function createAccount()
    {
        // validation of the form inputs 
        $validation = $this->validate([
            'email'=>'required|valid_email|is_unique[users.email]', 
            'password' => 'required', 
            'confirmPassword' => [
                'rules' => 'required|matches[password]', 
                'errors' => [
                   'required' => 'Please confirm your password.',
                   'matches' => 'Please ensure that it matches your password. ',
                ]
            ]
        ]); 

        // display errors if the form is not validated 
        if(!$validation){
            return view('auth/register', ['validation'=> $this->validator]);
        } else {
            // if the form is valid, store the values 
            $email = $this->request->getPost('email'); 
            $password = $this->request->getPost('password'); 

            $values = [
                'email' => $email, 
                'password' => $password, 
            ]; 

            $usersModel = new \App\Models\UsersModel(); 
            // insert the data into database
            $query = $usersModel ->insert($values); 
            // if registeration fails
            if(!$query){
                return redirect()->back()->with('fail', 'something went wrong.'); 
            } else {
                return redirect()->to('/auth')->with('success', 'account registered successfully.'); 
            }
        }
    }

    public function authenticate(){
        // validate requested inputs 
        $validation = $this->validate([
            'email'=>[
                'rules' => 'required|valid_email|is_not_unique[users.email]', 
                'errors' => [
                    'is_not_unique' => 'This account does not exist.'
                ]
            ], 
            'password' => 'required', 
        ]);

        if(!$validation){
            return view('auth/login',['validation' => $this -> validator]); 
        } else {

            // check if the user exist 
            $email = $this->request->getPost('email'); 
            $password = $this->request->getPost('password'); 

            // fetch the info
            $usersModel = new \App\Models\UsersModel();
            $user = $usersModel->where('email', $email)->first(); 
            if ($password == $user['password']) {
                // store it to the user session 
                $user_id = $user['id'];
                session()->set('loggedUser', $user_id); 
                echo 'slay'; 
            } else {
                session()->setFlashdata('fail', 'Incorrect Password');
                return redirect()->to('/auth')->withInput(); 
            } 
        }
    }

}
