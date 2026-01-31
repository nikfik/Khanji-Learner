<?php
require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/DashboardController.php';
require_once 'src/controllers/CharacterController.php';
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
        'characters'=>[
            'controller'=>"DashboardController",
            'action'=>'characters'
        ],
        'profile'=>[
            'controller'=>"DashboardController",
            'action'=>'profile'
        ],
          'dashboard'=>[
            'controller'=>"DashboardController",
            'action'=>'dashboard'
        ],
        'dashboardmain'=>[
            'controller'=>"DashboardController",
            'action'=>'dashboard'
        ],
        'api/learning/start'=>[
            'controller'=>"CharacterController",
            'action'=>'startLearning'
        ],
        'api/learning/finish'=>[
            'controller'=>"CharacterController",
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
        case 'profile':
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