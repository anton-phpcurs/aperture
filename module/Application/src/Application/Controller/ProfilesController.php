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

        if ($profile['is_active'] == 0) {
            if ($profile['activation'] != '') {
                $this->redirect()->toUrl('/accounts/activation');
            };

            $following = $this->getFollowsTable()->getFollowing($profile['id']);
            $followers = $this->getFollowsTable()->getFollowers($profile['id']);

            $view = new ViewModel(array(
                'profile' => $profile,
                'following' => $following,
                'followers' => $followers
            ));
            $view->setTemplate('profiles/profile');
            return $view;
        }

        //$files = $this->getFilesTable()->getManyBy(array('id_user' => $profile['id']));
        $files = $this->getFilesTable()->getGalery(array('id_user' => $profile['id']));

        if ($_SESSION['profile_name'] == $profile_name) {
            $buttonType = 1;
        } else {
            $follow =$this->getFollowsTable()->getOneBy(array(
                'id_user' => $profile['id'],
                'id_follower' => $_SESSION['id'],
            ));
            if ($follow) {
                $buttonType = 3;
            } else {
                $buttonType = 2;
            }
        }

        $following = $this->getFollowsTable()->getFollowing($profile['id']);
        $followers = $this->getFollowsTable()->getFollowers($profile['id']);

        $view = new ViewModel(array(
            'profile' => $profile,
            'files' => $files,
            'buttonType' => $buttonType,
            'following' => $following,
            'followers' => $followers
         ));
        $view->setTemplate('profiles/profile');
        return $view;
    }


    public function newsAction()
    {
        if (!isset($_SESSION['profile_name'])) {
            return new ViewModel();
        };

        $files = $this->getFilesTable()->getMyNews();

        $view = new ViewModel(array('files' => $files));
        $view->setTemplate('profiles/news');
        return $view;
    }

    //------------------------------------------------------------------------------------------------------------------
    public function aboutAction()
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

        if ($_SESSION['profile_name'] == $profile_name) {
            $buttonType = 1;
        } else {
            $follow =$this->getFollowsTable()->getOneBy(array(
                'id_user' => $profile['id'],
                'id_follower' => $_SESSION['id'],
            ));
            if ($follow) {
                $buttonType = 3;
            } else {
                $buttonType = 2;
            }
        }

        $following = $this->getFollowsTable()->getFollowing($profile['id']);
        $followers = $this->getFollowsTable()->getFollowers($profile['id']);

        //die($profile['id']);

        $view = new ViewModel(array(
            'profile' => $profile,
            'buttonType' => $buttonType,
            'following' => $following,
            'followers' => $followers,
        ));

        $view->setTemplate('profiles/about');
        return $view;
    }

    //------------------------------------------------------------------------------------------------------------------
    public function followAction()
    {
        $profile_name = $this->params()->fromRoute('profile_name');
        $profile = $this->getUsersTable()->getOneBy(array('profile_name' => $profile_name));

        $post['id_user'] = $profile['id'];
        $post['id_follower'] = $_SESSION['id'];

        $follow =$this->getFollowsTable()->getOneBy(array(
            'id_user' => $post['id_user'],
            'id_follower' => $post['id_follower'],
        ));

        if ($follow) {
            $this->redirect()->toUrl('/accounts/'. $profile_name);
        }

        $this->getFollowsTable()->save($post);

        $following = $this->getFollowsTable()->getManyBy(array('id_follower' => $_SESSION['id']));
        $foollowingCount['id'] = $_SESSION['id'];
        $foollowingCount['count_following'] = count($following);
        $this->getUsersTable()->save($foollowingCount);

        $follower = $this->getFollowsTable()->getManyBy(array('id_user' => $post['id_user']));
        $foollowerCount['id'] = $post['id_user'];
        $foollowerCount['count_followers'] = count($follower);
        $this->getUsersTable()->save($foollowerCount);

        $this->redirect()->toUrl('/'. $profile_name);
    }

    //------------------------------------------------------------------------------------------------------------------
    public function unfollowAction()
    {
        $profile_name = $this->params()->fromRoute('profile_name');
        $profile = $this->getUsersTable()->getOneBy(array('profile_name' => $profile_name));

        $post['id_user'] =  $profile['id'];
        $post['id_follower'] = $_SESSION['id'];

        $follow =$this->getFollowsTable()->getOneBy(array(
            'id_user' => $post['id_user'],
            'id_follower' => $post['id_follower'],
        ));

        $profile = $this->getFollowsTable()->getOneBy(array('id_user' => $post['id_user']));

        if (!$follow) {
            $this->redirect()->toUrl('/accounts/'. $profile['profile_name'] );
        }

        $this->getFollowsTable()->delete($post);

        $following = $this->getFollowsTable()->getManyBy(array('id_follower' => $_SESSION['id']));
        $foollowingCount['id'] = $_SESSION['id'];
        $foollowingCount['count_following'] = count($following);
        $this->getUsersTable()->save($foollowingCount);

        $follower = $this->getFollowsTable()->getManyBy(array('id_user' => $post['id_user']));
        $foollowerCount['id'] = $post['id_user'];
        $foollowerCount['count_followers'] = count($follower);
        $this->getUsersTable()->save($foollowerCount);

        $this->redirect()->toUrl('/'. $profile_name);
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

    //------------------------------------------------------------------------------------------------------------------
    public function getFollowsTable()
    {
        return $this->getServiceLocator()->get('FollowsTable');
    }
}