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
use Zend\View\Model\JsonModel;

use Application\Form\RegistrationForm;
use Application\Form\LoginForm;

use Application\Form\EmailForm;
use Application\Form\PasswordForm;
use Application\Form\OtherForm;

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

        $formOther = new OtherForm();
        $formOther->setData($profile);

        $formEmail = new EmailForm();
        $formEmail->setData($profile);

        $formPassword = new PasswordForm();
        $formPassword->setData($profile);

        $view = new ViewModel(array(
                'formEmail' => $formEmail,
                'formPassword' => $formPassword,
                'formOther' => $formOther,
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

        // Validation form ----
        $formLogin = new LoginForm();
        $formLogin->setData($post);

        if (!$formLogin->isValid()) {
            $model = new ViewModel(array(
                'formLogin' => $formLogin,
                'message' => '111',
                'error' => true,
            ));
            $model->setTemplate('accounts/login');
            return $model;
        }

        // Validation auth
        $profile = $this->getUsersTable()->getOneBy(array(
            'profile_name' => $post['profile_name'],
            'password'     => md5($post['password'])
        ));

        if (!$profile) {
            $view = new ViewModel(array(
                    'formLogin'  => $formLogin,
                    'message' => 'Incorrect Profile name or Password.',
                    'error' => true
                )
            );
            $view->setTemplate('accounts/login');
            return $view;
        }

        $_SESSION['id']           = $profile['id'];
        $_SESSION['profile_name'] = $profile['profile_name'];
        $_SESSION['email']        = $profile['email'];
        $_SESSION['password']     = $profile['password'];

        $this->redirect()->toUrl('/' . $profile['profile_name']);
    }

    //------------------------------------------------------------------------------------------------------------------
    public function emailAction()
    {
        if (!$this->getRequest()->isPost()){
            $this->redirect()->toUrl('/accounts/edit');
        }
        $post = $this->request->getPost();
        $profile = $this->getUsersTable()->getOneBy(array('id' => $_SESSION['id']));
        $profile['email_current'] = $profile['email'];

        // Validation forms ----
        $formEmail = new EmailForm();
        $formEmail->setData($post);

        $formPassword = new PasswordForm();
        $formPassword->setData($profile);

        $formOther = new OtherForm();
        $formOther->setData($profile);

        if (!$formEmail->isValid()) {
            $model = new ViewModel(array(
                'formEmail' => $formEmail,
                'formPassword' => $formPassword,
                'formOther' => $formOther,
                'message' => '',
                'error' => true,
            ));
            $model->setTemplate('accounts/edit');
            return $model;
        }

        // Validation email in db
        $post['id'] = $profile['id'];
        $profile_exist = $this->getUsersTable()->getOneBy(array('email' => $post['email']));

        if ($profile_exist && ($profile_exist->id != $post['id'])) {
            $model = new ViewModel(array(
                'formEmail' => $formEmail,
                'formPassword' => $formPassword,
                'formOther' => $formOther,
                'message' => 'E-mail address already exists.',
                'error' => true,
            ));
            $model->setTemplate('accounts/edit');
            return $model;
        }

        $this->getUsersTable()->save($post);
        $_SESSION['email'] = $post['email'];

        $this->redirect()->toUrl('/accounts');
    }

    //------------------------------------------------------------------------------------------------------------------
    public function passwordAction()
    {
        if (!$this->getRequest()->isPost()){
            $this->redirect()->toUrl('/accounts/edit');
        }
        $post = $this->request->getPost();
        $profile = $this->getUsersTable()->getOneBy(array('id' => $_SESSION['id']));
        $profile['email_current'] = $profile['email'];
        $profile['email'] = '';

        // Validation forms ----
        $formEmail = new EmailForm();
        $formEmail->setData($profile);

        $formPassword = new PasswordForm();
        $formPassword->setData($post);

        $formOther = new OtherForm();
        $formOther->setData($profile);

        if (!$formPassword->isValid()) {
            $model = new ViewModel(array(
                'formEmail' => $formEmail,
                'formPassword' => $formPassword,
                'formOther' => $formOther,
                'message' => '',
                'error' => true,
            ));
            $model->setTemplate('accounts/edit');
            return $model;
        }

        // Validation password in db
        $post['password_current'] = md5($post['password_current']);
        $post['password']         = md5($post['password']);
        $post['id']               = $profile['id'];

        if ($profile['password'] != $post['password_current']) {
            $model = new ViewModel(array(
                'formEmail' => $formEmail,
                'formPassword' => $formPassword,
                'formOther' => $formOther,
                'message' => 'Password is not changed. <br> Access denied.',
                'error' => true,
            ));
            $model->setTemplate('accounts/edit');
            return $model;
        }

        $this->getUsersTable()->save($post);
        $_SESSION['password'] = $post['password'];

        $this->redirect()->toUrl('/accounts');
    }

    //------------------------------------------------------------------------------------------------------------------
    public function otherAction()
    {
        if (!$this->getRequest()->isPost()){
            $this->redirect()->toUrl('/accounts/login');
        }

        $profile = $this->getUsersTable()->getOneBy(array('id' => $_SESSION['id']));
        $post = $this->request->getPost();
        $post['id'] = $profile['id'];

        $this->getUsersTable()->save($post);
        $this->redirect()->toUrl('/accounts/');
    }

    //------------------------------------------------------------------------------------------------------------------
    public function searchAction()
    {
        $profile_name = $this->request->getPost('search');

        $users = $this->getUsersTable()->search($profile_name);

        foreach($users as $user) {
            $result[] = $user['profile_name'];
        }

        $view = new JsonModel(array(
            'result' => $result,
            'success'=>true,
        ));

        return $view;
/*


        //$profile_name = $this->request->getPost('search');
        $profile_name = 'anton';

        $profiles = $this->getUsersTable()->getManyBy(array('profile_name' => $profile_name));

        foreach($profiles as $profiles) {
           $result[] = $profiles['profile_name'];
        }


        //echo 'sdfgsdfg';
        //die();
        //echo "I get param1 = ".$_POST['param1']." and param2 = ".$_POST['param2'];

        $view = new JsonModel(array(
            'result' => $result,
            //'result' => ['an', 'anton', 'alex'],
            'success'=>true,
        ));

        //var_dump($view);
        //die($view);

        return $view;*/
    }

    //------------------------------------------------------------------------------------------------------------------
    public function logoutAction()
    {
        session_destroy();
        $this->redirect()->toUrl('/');
    }

    // Helper function =================================================================================================
    //------------------------------------------------------------------------------------------------------------------
    public function getUsersTable()
    {
        return $this->getServiceLocator()->get('UsersTable');
    }
}
