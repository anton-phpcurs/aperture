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

class AccountsController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}

/*


class AccountsController extends AbstractActionController
{
    //------------------------------------------------------------------------------------------------------------------
    public function indexAction()
    {
        $view = new ViewModel();
        $view->setTemplate('application/index/index');
        return $view;

        //$this->redirect()->toUrl('/accounts/edit');
    }

    //------------------------------------------------------------------------------------------------------------------
    public function editAction()
    {
        $view = new ViewModel();
        $view->setTemplate('application/index/index');
        return $view;
    }

    //------------------------------------------------------------------------------------------------------------------
    public function registrationAction()
    {
        $form = new RegisterForm();
        $view = new ViewModel(array('form' =>$form));
        $view->setTemplate('registration');
        return $view;
    }

    //------------------------------------------------------------------------------------------------------------------
    public function loginAction()
    {
        $form = new LoginForm();
        $view = new ViewModel(array('form' => $form));
        $view->setTemplate('login');
        return $view;
    }
}
*/