<?php
class AppController{

    protected function isGet():bool
    {
        return $_SERVER['REQUEST_METHOD'] =='GET';
    }

    protected function isPost():bool
    {
        return $_SERVER['REQUEST_METHOD'] =='POST';
    }

    // WYTYCZNA #19: Dane wyświetlane w widokach są escapowane (ochrona przed XSS)
    protected function render(?string $template = null, array $variables =[]){
        $templatePath = 'public/views/'.$template.'.html';
        $templatePath404 = 'public/views/404.html';
        $output = "";

        if(file_exists($templatePath)){
            // WYTYCZNA #19: Escapowanie wszystkich zmiennych przed przekazaniem do widoku
            $escapedVariables = array_map(function($value) {
                if (is_string($value)) {
                    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                }
                return $value;
            }, $variables);

            extract($escapedVariables);

            ob_start();
            include $templatePath;
            $output= ob_get_clean();
        } else {
                ob_start();
                include $templatePath404;
                $output= ob_get_clean();
        }
        echo $output;
    }
}