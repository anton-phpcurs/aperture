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

use Application\Form\UploadForm;

class FilesController extends AbstractActionController
{
    // Visible actions =================================================================================================
    //------------------------------------------------------------------------------------------------------------------
    public function uploadAction()
    {
        $formUpload = new UploadForm('upload');
        $view = new ViewModel(array('formUpload' => $formUpload));
        $view->setTemplate('files/upload');
        return $view;
    }

    // Invisible actions ===============================================================================================
    //------------------------------------------------------------------------------------------------------------------
    public function saveAction()
    {
        if (!$this->getRequest()->isPost()){
            $this->redirect()->toUrl('/accounts/login');
        }

        $post = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
        );

        $formUpload = new UploadForm();
        $formUpload->setData($post);

        switch ($post['file']['type']) {
            case 'image/jpeg': $ext = '.jpg'; break;
            case 'image/png' : $ext = '.png'; break;
            case 'image/gif' : $ext = '.gif'; break;
            default:
                $model = new ViewModel(array(
                    'formUpload' => $formUpload,
                    'message' => 'Invalid format file. <br> Uploding only .JPG, .PNG, .GIF',
                    'error' => true,
                ));
                $model->setTemplate('files/upload');
                return $model;
        }

        if (!$formUpload->isValid()) {
            $model = new ViewModel(array(
                'formUpload' => $formUpload,
                'message' => '',
                'error' => true,
            ));
            $model->setTemplate('files/upload');
            return $model;
        }

        $data = $formUpload->getData();
        $oldPath = $data['file']['tmp_name'];

        $folderName = '/files/' . md5($_SESSION['id']);
        $fileName = md5(basename($oldPath)) . $ext;

        $newPath = './public/' . $folderName;

        if (!file_exists($newPath)) {
            mkdir($newPath);
        }

        rename($oldPath, $newPath . '/' . $fileName);

        $post['id_user'] = $_SESSION['id'];
        $post['path'] = $folderName . '/' . $fileName;
        $this->getFilesTable()->save($post);

        return $this->redirect()->toUrl('/'. $_SESSION['profile_name']);
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
