<?php
require_once 'AppController.php';
class SecurityController extends AppController{

    public function login() {
        if(!$this->isPost()){
            return $this->render("login",['message'=>'']);
        }
       // var_dump($_POST);
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';//konstrukcja  coalesing operator, elwis operator
        
        if($email===''){
            return $this->render("login",['message'=>'Podaj Email']);
        }
        
        var_dump($email, $password);
        //TODO pobieramy z formularza email,haslo
        //sprawdzamy czy taki user istnieje w db
        //jesli nie istnieje to zwracamy odpowiedni omunikat
        // jestli istnieje to przekierujemy go to dashboarda
        //header("Location: /dashboard");
        return $this->render("dashboard");
    }
    public function register(){
        if(!$this->isPost()){
            return $this->render("register");
        } 
        return $this->render("login");
    }
}