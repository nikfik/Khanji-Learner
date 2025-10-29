<?php
require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/DashboardController.php';
class Routing{

    public static $routes=[
        'login'=>[
            'controller'=>"SecurityController",
            'action'=>'login'
        ],
        'register'=>[
            'controller'=>"SecurityController",
            'action'=>'register'
        ],
        'dashboard'=>[
            'controller'=>"DashboardController",
            'action'=>'index'
        ]
    ];
public static function run(string $path) {
    switch($path){//regex aby przetworzyc np dashboard/5467 
        case 'dashboard':
            //include 'public/views/dashboard.html';
            //break;
        case 'login':
            $controller = new  Routing::$routes[$path]['controller'];//zmienic na singleton
            $action = Routing::$routes[$path]['action'];
            $controller->$action();
            break;
        default:
        include 'public/views/404.html';
        break;
    }
}
}