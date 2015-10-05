<?php
/**
 * Created by PhpStorm.
 * User: abrosimov
 * Date: 02.10.15
 * Time: 18:16
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Form\RegistrationForm;
use Application\Form\LoginForm;

use Application\Form\EmailForm;
use Application\Form\PasswordForm;
use Application\Form\OtherForm;

class ProfilesController extends AbstractActionController
{
    // Visible actions =================================================================================================
    //------------------------------------------------------------------------------------------------------------------
    public function indexAction()
    {
        if (!isset($_SESSION['profile_name'])) {
            $this->redirect()->toUrl('/accounts/login');
        }

        $profile_name = $this->params()->fromRoute('profile_name');
        $profile = $this->getUsersTable()->getOneBy(array('profile_name' => $profile_name));

        if (!$profile) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $files = $this->getFilesTable()->getManyBy(array('id_user' => $profile['id']));

        $view = new ViewModel(array('profile_name' => $profile_name, 'files' => $files));
        $view->setTemplate('profiles/profile');
        return $view;
    }

    // Helper function =================================================================================================
    //------------------------------------------------------------------------------------------------------------------
    public function getUsersTable()
    {
        return $this->getServiceLocator()->get('UsersTable');
    }

    //------------------------------------------------------------------------------------------------------------------
    public function getFilesTable()
    {
        return $this->getServiceLocator()->get('FilesTable');
    }
}
