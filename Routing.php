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
        'logout'=>[
            'controller'=>"SecurityController",
            'action'=>'logout'
        ],
        'dashboard'=>[
            'controller'=>"DashboardController",
            'action'=>'index'
        ],
         'dashboardmain'=>[
            'controller'=>"DashboardController",
            'action'=>'index'
        ],
        'characters'=>[
            'controller'=>"DashboardController",
            'action'=>'characters'
        ],
        'profile'=>[
            'controller'=>"DashboardController",
            'action'=>'profile'
        ]
    ];
    
public static function run(string $path) {
    switch($path){
        case 'dashboardmain':
        case 'dashboard':
        case 'register':
        case 'login':
        case 'logout':
        case 'characters':
        case 'profile':
            $controllerName = Routing::$routes[$path]['controller'];
            $action = Routing::$routes[$path]['action'];
            $controller = new $controllerName();
            $controller->$action();
            break;
        default:
            // WYTYCZNA #21: Zwracam sensowne kody HTTP
            http_response_code(404);
            include 'public/views/404.html';
            break;
    }
}
}