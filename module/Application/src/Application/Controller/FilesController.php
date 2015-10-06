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
    public function editAction()
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


        if (!$formUpload->isValid()) {
            $model = new ViewModel(array(
                'formUpload' => $formUpload,
                'message' => '',
                'error' => true,
            ));
            $model->setTemplate('files/upload');
            return $model;
        }

        $tmpFileName = $post['file']['tmp_name'];
        var_dump($post['file']);
        $file = getimagesize ($tmpFileName);

        if(!$file || ($file[2] > 3)){
            $model = new ViewModel(array(
                'formUpload' => $formUpload,
                'message' => 'Invalid format file. <br> Uploding only .JPG, .PNG, .GIF',
                'error' => true,
            ));
            $model->setTemplate('files/upload');
            return $model;
        }

        switch ($file[2]) {
            case 1 : $ext = '.gif'; break;
            case 2 : $ext = '.jpg'; break;
            case 3 : $ext = '.png'; break;
        }

        $data = $formUpload->getData();
        $tmpFileName = $data['file']['tmp_name'];

        $folderName = '/files/' . md5($_SESSION['id']);
        $fileName = md5(basename($tmpFileName)) . $ext;

        $newFolder = './public' . $folderName;

        if (!file_exists($newFolder)) {
            mkdir($newFolder);
        }

        $newFileName = $newFolder . '/' . $fileName;

        rename($tmpFileName, $newFileName);

        $model = new ViewModel(array(
            'width' => $file[0],
            'height' => $file[1],
            'path' => $folderName .'/'.$fileName,
        ));
        $model->setTemplate('files/edit');
        return $model;
    }

    //------------------------------------------------------------------------------------------------------------------
    public function saveAction()
    {
        $target_w = 600;
        $target_h = 600;
        $jpeg_quality = 95;

        $src = APP_DIR . '/public' .$_POST['path'];
        $img_r = $this->imageCreateFromAny($src);
        $dst_r = ImageCreateTrueColor($target_w, $target_h);

        imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'],
                                $target_w, $target_h, $_POST['w'], $_POST['h']);

#        header('Content-type: image/jpeg');
#        imagejpeg($dst_r);
        imagejpeg($dst_r, $src, $jpeg_quality);

        $post['id_user'] = $_SESSION['id'];
        $post['path'] = $_POST['path'];
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

    //------------------------------------------------------------------------------------------------------------------
    function imageCreateFromAny($filepath) {
        $type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize()
        $allowedTypes = array(
            1,  // [] gif
            2,  // [] jpg
            3,  // [] png
        );
        if (!in_array($type, $allowedTypes)) {
            return false;
        }
        switch ($type) {
            case 1 :
                $im = imageCreateFromGif($filepath);
                break;
            case 2 :
                $im = imageCreateFromJpeg($filepath);
                break;
            case 3 :
                $im = imageCreateFromPng($filepath);
                break;
        }
        return $im;
    }
}
