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
        ],
         'dashboardmain'=>[
            'controller'=>"DashboardController",
            'action'=>'index'
        ],
        'characters'=>[
            'controller'=>"DashboardController",
            'action'=>'characters'
        ],
        'api/learning/start'=>[
            'controller'=>"DashboardController",
            'action'=>'startLearning'
        ],
        'api/learning/finish'=>[
            'controller'=>"DashboardController",
            'action'=>'finishLearning'
        ]
    ];
public static function run(string $path) {
    switch($path){//regex aby przetworzyc np dashboard/5467 
        case 'dashboardmain':
        case 'dashboard':
        case 'register':
        case 'login':
        case 'characters':
        case 'api/learning/start':
        case 'api/learning/finish':
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