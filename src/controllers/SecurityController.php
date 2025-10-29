<?php
require_once 'AppController.php';
class SecurityController extends AppController{

    public function login() {

        //TODO pobieramy z formularza email,haslo
        //sprawdzamy czy taki user istnieje w db
        //jesli nie istnieje to zwracamy odpowiedni omunikat
        // jestli istnieje to przekierujemy go to dashboarda
       return $this->render("login");
    }
}