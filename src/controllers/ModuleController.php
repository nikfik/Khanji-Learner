<?php
require_once 'AppController.php';
require_once __DIR__.'/../repository/ModuleRepository.php';

class ModuleController extends AppController {
    
    private $moduleRepository;
    
    public function __construct() {
        $this->moduleRepository = new ModuleRepository();
    }
    
    /**
     * Główny widok modułów/zajęć
     */
    public function modules() {
        $userId = $_SESSION['user_id'] ?? null;
        
        // Pobierz wszystkie moduły
        $modules = $this->moduleRepository->getAllModules();
        
        // Dodaj postęp użytkownika do każdego modułu
        foreach ($modules as &$module) {
            if ($userId) {
                // TODO: Implementacja faktycznego postępu użytkownika
                // Na razie ustawiamy losowe wartości dla demonstracji
                $progress = rand(0, 100);
                $module['progress'] = $progress;
                $module['learned_count'] = (int)($module['character_count'] * $progress / 100);
            } else {
                $module['progress'] = 0;
                $module['learned_count'] = 0;
            }
        }
        
        $this->render('modules', ['modules' => $modules]);
    }
    
    /**
     * API endpoint do filtrowania modułów po poziomie
     */
    public function filterByLevel() {
        header('Content-Type: application/json');
        
        $level = $_GET['level'] ?? 'all';
        
        if ($level === 'all') {
            $modules = $this->moduleRepository->getAllModules();
        } else {
            $modules = $this->moduleRepository->getModulesBySpecificLevel($level);
        }
        
        echo json_encode($modules);
    }
    
    /**
     * API endpoint do wyszukiwania modułów
     */
    public function searchModules() {
        header('Content-Type: application/json');
        
        $searchTerm = $_GET['q'] ?? '';
        
        if (empty($searchTerm)) {
            $modules = $this->moduleRepository->getAllModules();
        } else {
            $modules = $this->moduleRepository->searchModules($searchTerm);
        }
        
        echo json_encode($modules);
    }
}
