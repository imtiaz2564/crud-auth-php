<?php

namespace Router;


use Controller\HomeController;
use Controller\AuthController;
use Controller\SportController;
use DatabaseConfig\DatabaseConfig;
use DB\MysqlDatabaseService;
use Repository\LoginRepository;
use Repository\SportRepository;

class Router
{
    private AuthController $authCtrl;
    private SportController $sportCtrl;
    private HomeController $homeCtrl;

    public function __construct()
    {
        // 1. Create database configuration
        $config = new DatabaseConfig();

        // 2. Create database service
        $db = new MysqlDatabaseService($config);

        // 3. Create login repository
        $loginRepo = new LoginRepository($db);
        $sportRepo = new SportRepository($db);

        // 4. Inject repository into controller
        $this->authCtrl = new AuthController($loginRepo);
        $this->sportCtrl = new SportController($sportRepo);
        $this->homeCtrl = new HomeController();
    }

    public function handle(): void
    {
        $act = $_GET['act'] ?? 'home';

        if (in_array($act, ['registration', 'login', 'logout', 'sportInsert', 'updateSport', 'deleteSport'], true)) {
            Csrf::validatePost();
        }
       
        switch ($act) {

            case 'registration':
                $this->authCtrl->registration();
                break;

            case 'login':
                $this->authCtrl->login();
                break;
            
            case 'sports':
                $this->sportCtrl->getSportList();
                break;

            case 'logout':
                $this->authCtrl->logout();
                break;

            case 'registrationView':
                $this->authCtrl->registrationView();
                break;

            case 'loginView':
                $this->authCtrl->loginView();
                break;

            case 'sportInsertView':
                $this->sportCtrl->sportInsertView();
                break;
            
            case 'sportInsert':
                $this->sportCtrl->sportInsert();
                break;

            case 'updateInfo':

                $id = (int) ($_GET['id'] ?? 0);

                if ($id > 0) {
                    $this->sportCtrl->editSport($id);
                }

                break;


            case 'updateSport':

                $this->sportCtrl->updateSport();

                break;
            
            case 'deleteSport':

                $id = (int) ($_POST['id'] ?? 0);

                if ($id > 0) {
                    $this->sportCtrl->sportDelete($id);
                }

                break;
            case 'viewDetail':
                $id = (int) ($_GET['id'] ?? 0);

                if ($id > 0) {
                    $this->sportCtrl->viewSport($id);
                }
                break;    
            case 'home':
                $this->homeCtrl->index();
                //include __DIR__ . '/views/home.php';
                break; 
                  
            default:
                //$this->authCtrl->loginView();
                //$this->authCtrl->registrationView();
                include dirname(__DIR__) . '/views/home.php';
                break;
        }
    }
}
