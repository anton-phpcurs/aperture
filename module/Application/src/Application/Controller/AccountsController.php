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
use Application\Form\EditForm;

use Application\Form\EmailForm;
use Application\Form\PasswordForm;

class AccountsController extends AbstractActionController
{
    //------------------------------------------------------------------------------------------------------------------
    public function indexAction()
    {
        $this->redirect()->toUrl('/accounts/edit');
    }

    // Visible actions =================================================================================================
    //------------------------------------------------------------------------------------------------------------------
    public function registrationAction()
    {
        $formRegistration = new RegistrationForm('registration');
        $view = new ViewModel(array('formRegistration' =>$formRegistration));
        $view->setTemplate('accounts/registration');
        return $view;
    }

    //------------------------------------------------------------------------------------------------------------------
    public function loginAction()
    {
        $formLogin = new LoginForm('login');
        $view = new ViewModel(array('formLogin' => $formLogin));
        $view->setTemplate('accounts/login');
        return $view;
    }

    //------------------------------------------------------------------------------------------------------------------
    public function editAction()
    {
        if (!isset($_SESSION['profile_name'])) {
            $this->redirect()->toUrl('/accounts/login');
        };

        $profile = $this->getUsersTable()->getOneBy(array('id' => $_SESSION['id']));
        $profile['email_current'] = $profile['email'];
        $profile['email'] = '';

        $formEdit = new EditForm();
        $formEdit->setData($profile);

        $formEmail = new EmailForm();
        $formEmail->setData($profile);

        $formPassword = new PasswordForm();
        $formPassword->setData($profile);

        $view = new ViewModel(array(
                'formEmail' => $formEmail,
                'formPassword' => $formPassword,
                'formEdit' => $formEdit,
            )
        );
        $view->setTemplate('accounts/edit');
        return $view;
    }

    // Invisible actions ===============================================================================================
    //------------------------------------------------------------------------------------------------------------------
    public function signupAction()
    {
        if (!$this->getRequest()->isPost()){
            $this->redirect()->toUrl('/accounts/registration');
        }
        $post = $this->request->getPost();

        $formRegistration = new RegistrationForm('registration');
        $formRegistration->setData($post);

        if (!$formRegistration->isValid()) {
            $model = new ViewModel(array(
                'formRegistration' => $formRegistration,
                'message' => 'Test Error',
                'error' => true,
            ));
            $model->setTemplate('accounts/registration');
            return $model;
        }

        $profileName = $post['profile_name'];
        $email       = $post['email'];

        if ($this->getUsersTable()->getOneBy(array('profile_name' => $profileName))) {
            $model = new ViewModel(array(
                'formRegistration' => $formRegistration,
                'message' => 'Имя профиля уже существует.',
                'error' => true,
            ));
            $model->setTemplate('registration');
            return $model;
        }

        if ($this->getUsersTable()->getOneBy(array('email' => $email))) {
            $model = new ViewModel(array(
                'formRegistration' => $formRegistration,
                'message' => 'E-mail уже существует.',
                'error' => true,
            ));
            $model->setTemplate('registration');
            return $model;
        }

        $profile = $formRegistration->getData();
        $profile['password'] = md5($profile['password']);

        $this->getUsersTable()->save($profile);

        $profile = $this->getUsersTable()->getOneBy(array('profile_name' => $profile['profile_name']));

        $_SESSION['id']             = $profile['id'];
        $_SESSION['profile_name']   = $profile['profile_name'];
        $_SESSION['email']          = $profile['email'];
        $_SESSION['password']       = $profile['password'];

        $this->redirect()->toUrl('/accounts');
    }

    //------------------------------------------------------------------------------------------------------------------
    public function signinAction()
    {
        if (!$this->getRequest()->isPost()){
            $this->redirect()->toUrl('/accounts/login');
        }
        $post = $this->request->getPost();

        $userInfo = $this->getUsersTable()->getOneBy(array(
            'profile_name' => $post['profile_name'],
            'password'     => md5($post['password'])
        ));

        if ($userInfo) {
            $_SESSION['id']             = $userInfo->id;
            $_SESSION['profile_name']   = $userInfo->profile_name;
            $_SESSION['email']          = $userInfo->email;
            $_SESSION['password']       = $userInfo->password;
            $this->redirect()->toUrl('/' . $userInfo->profile_name);
        }

        $formLogin = new LoginForm();
        $formLogin->setData($post);

        $view = new ViewModel(array(
                'formLogin'  => $formLogin,
                'error' => true
            )
        );
        $view->setTemplate('accounts/login');
        return $view;
    }

    // Helper function =================================================================================================
    //------------------------------------------------------------------------------------------------------------------
    public function getUsersTable()
    {
        return $this->getServiceLocator()->get('UsersTable');
    }
}
