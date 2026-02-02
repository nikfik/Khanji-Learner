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
        
        // Dodaj rzeczywisty postęp użytkownika do każdego modułu
        foreach ($modules as &$module) {
            if ($userId) {
                // Pobierz rzeczywisty postęp z user_progress
                $progress = $this->moduleRepository->getUserModuleProgress($userId, $module['id']);
                $module['progress'] = $progress['percent'];
                $module['learned_count'] = $progress['mastered'];
                $module['total_count'] = $progress['total'];
            } else {
                // Nielogowany użytkownik - bez postępu
                $module['progress'] = 0;
                $module['learned_count'] = 0;
                $module['total_count'] = 0;
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
        $userId = $_SESSION['user_id'] ?? null;
        
        if ($level === 'all') {
            $modules = $this->moduleRepository->getAllModules();
        } else {
            $modules = $this->moduleRepository->getModulesBySpecificLevel($level);
        }
        
        // Dodaj postęp do każdego modułu
        foreach ($modules as &$module) {
            if ($userId) {
                $progress = $this->moduleRepository->getUserModuleProgress($userId, $module['id']);
                $module['progress'] = $progress['percent'];
            } else {
                $module['progress'] = 0;
            }
        }
        
        echo json_encode($modules);
    }
    
    /**
     * API endpoint do wyszukiwania modułów
     */
    public function searchModules() {
        header('Content-Type: application/json');
        
        $searchTerm = $_GET['q'] ?? '';
        $userId = $_SESSION['user_id'] ?? null;
        
        if (empty($searchTerm)) {
            $modules = $this->moduleRepository->getAllModules();
        } else {
            $modules = $this->moduleRepository->searchModules($searchTerm);
        }
        
        // Dodaj postęp do każdego modułu
        foreach ($modules as &$module) {
            if ($userId) {
                $progress = $this->moduleRepository->getUserModuleProgress($userId, $module['id']);
                $module['progress'] = $progress['percent'];
            } else {
                $module['progress'] = 0;
            }
        }
        
        echo json_encode($modules);
    }
}
