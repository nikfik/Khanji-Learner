<?php
require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/DashboardController.php';
require_once 'src/controllers/CharacterController.php';
require_once 'src/controllers/ModuleController.php';
class Routing{

    public static $routes=[
        'login'=>[
            'controller'=>"SecurityController",
            'action'=>'login'
        ],
        'logout'=>[
            'controller'=>"SecurityController",
            'action'=>'logout'
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
        ],
        'api/learning/saveDrawing'=>[
            'controller'=>"CharacterController",
            'action'=>'saveDrawing'
        ],
        'api/profile/update'=>[
            'controller'=>"DashboardController",
            'action'=>'updateProfile'
        ],
        'api/profile/getMoreSessions'=>[
            'controller'=>"DashboardController",
            'action'=>'getMoreSessions'
        ],
        'modules'=>[
            'controller'=>"ModuleController",
            'action'=>'modules'
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
        case 'modules':
        case 'api/learning/start':
        case 'api/learning/finish':
        case 'api/learning/saveDrawing':
        case 'api/profile/update':
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