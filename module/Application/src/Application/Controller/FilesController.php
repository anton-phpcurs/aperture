<?php
/**
 * Created by PhpStorm.
 * User: abrosimov
 * Date: 02.10.15
 * Time: 18:16
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\File\Count;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

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

        $folderName = '/files/' . md5($_SESSION['id']) . '/';
        $fileName = md5(basename($tmpFileName));

        $newFolder = './public' . $folderName ;

        if (!file_exists($newFolder)) {
            mkdir($newFolder);
        }

        $newFileName = $newFolder . $fileName . $ext;

        //die($newFileName);

        rename($tmpFileName, $newFileName);

        $model = new ViewModel(array(
            'width' => $file[0],
            'height' => $file[1],
            'folder' => $folderName,
            'name' => $fileName,
            'ext' => $ext
        ));
        $model->setTemplate('files/edit');
        return $model;
    }

    //------------------------------------------------------------------------------------------------------------------
    public function viewAction()
    {
        $file_name = $this->params()->fromRoute('id');
        $file = $this->getFilesTable()->getOneBy(array('name' => $file_name));

        $filePrev = $this->getFilesTable()->getNext(array(new \Zend\Db\Sql\Predicate\Operator('id', '>', $file['id'])));
        $fileNext = $this->getFilesTable()->getPrev(array(new \Zend\Db\Sql\Predicate\Operator('id', '<', $file['id'])));

        $comments = $this->getCommentsTable()->getComments(array('file_name' => $file['name']));
        $commentsCount = count($comments);
        $likes = $file['likes'];

        $cc['id'] = $file['id'];
        $cc['comments'] = $commentsCount;
        $cc['views'] = $file['views'] + 1;
        $this->getFilesTable()->save($cc);

        $buttonLike = false;
        $post['file_name'] = $file_name;
        $post['profile_name'] = $_SESSION['profile_name'];

        if ($this->getLikesTable()->getOneBy($post)) {
            $buttonLike = true;
        }

        $profile = $this->getUsersTable()->getOneBy(array('id' => $file['id_user']));

        $model = new ViewModel(array(
            'profile' => $profile,
            'file' => $file,
            'next' => $fileNext,
            'prev' => $filePrev,
            'comments' => $comments,
            'viewsCount' => $file['views'] + 1,
            'likesCount' => $likes,
            'commentsCount' => $commentsCount,
            'buttonLike' => $buttonLike,
            'folder' => $file['folder'],
            'name' => $file['name'],
            'ext' => $file['ext'],

        ));
        $model->setTemplate('files/view');
        return $model;
    }

    //------------------------------------------------------------------------------------------------------------------
    public function galeryAction()
    {
        $file_name = $this->params()->fromRoute('id');
        $file = $this->getFilesTable()->getOneBy(array('name' => $file_name));

        $filePrev = $this->getFilesTable()->getNext(array('id_user' => $file['id_user'], new \Zend\Db\Sql\Predicate\Operator('id', '>', $file['id'])));
        $fileNext = $this->getFilesTable()->getPrev(array('id_user' => $file['id_user'], new \Zend\Db\Sql\Predicate\Operator('id', '<', $file['id'])));

        $comments = $this->getCommentsTable()->getComments(array('file_name' => $file['name']));
        $commentsCount = count($comments);
        $likes = $file['likes'];

        $cc['id'] = $file['id'];
        $cc['comments'] = $commentsCount;
        $cc['views'] = $file['views'] + 1;
        $this->getFilesTable()->save($cc);

        $buttonLike = false;
        $post['file_name'] = $file_name;
        $post['profile_name'] = $_SESSION['profile_name'];

        if ($this->getLikesTable()->getOneBy($post)) {
            $buttonLike = true;
        }

        $profile = $this->getUsersTable()->getOneBy(array('id' => $file['id_user']));

        $model = new ViewModel(array(
            'profile' => $profile,
            'file' => $file,
            'next' => $fileNext,
            'prev' => $filePrev,
            'comments' => $comments,
            'viewsCount' => $file['views'] + 1,
            'likesCount' => $likes,
            'commentsCount' => $commentsCount,
            'buttonLike' => $buttonLike,
            'folder' => $file['folder'],
            'name' => $file['name'],
            'ext' => $file['ext'],

        ));
        $model->setTemplate('files/view');
        return $model;
    }

    //------------------------------------------------------------------------------------------------------------------
    public function newsAction()
    {
        $file_name = $this->params()->fromRoute('id');
        $file = $this->getFilesTable()->getOneBy(array('name' => $file_name));

        $fileNext = $this->getFilesTable()->getNextNews($file['id']);
        $filePrev = $this->getFilesTable()->getPrevNews($file['id']);

        $comments = $this->getCommentsTable()->getComments(array('file_name' => $file['name']));
        $commentsCount = count($comments);
        $likes = $file['likes'];

        $cc['id'] = $file['id'];
        $cc['comments'] = $commentsCount;
        $cc['views'] = $file['views'] + 1;
        $this->getFilesTable()->save($cc);

        $buttonLike = false;
        $post['file_name'] = $file_name;
        $post['profile_name'] = $_SESSION['profile_name'];

        if ($this->getLikesTable()->getOneBy($post)) {
            $buttonLike = true;
        }

        $profile = $this->getUsersTable()->getOneBy(array('id' => $file['id_user']));

        $model = new ViewModel(array(
            'profile' => $profile,
            'file' => $file,
            'next' => $fileNext,
            'prev' => $filePrev,
            'comments' => $comments,
            'viewsCount' => $file['views'] + 1,
            'likesCount' => $likes,
            'commentsCount' => $commentsCount,
            'buttonLike' => $buttonLike,
            'folder' => $file['folder'],
            'name' => $file['name'],
            'ext' => $file['ext'],

        ));
        $model->setTemplate('files/view');
        return $model;
    }

    //------------------------------------------------------------------------------------------------------------------
    public function commentAction()
    {
        $commentText = $this->request->getPost('comment');
        $fileName = $this->request->getPost('file_name');

        $post['file_name'] = $fileName;
        $post['profile_name'] = $_SESSION['profile_name'];
        $post['text'] = $commentText;
        $post['date'] = date("Y-m-d");

        $this->getCommentsTable()->save($post);

        $file = $this->getFilesTable()->getOneBy(array('name' => $fileName));
        $cc['id'] = $file['id'];
        $cc['comments'] = $this->getCommentsTable()->getManyBy(array('file_name' => $fileName))->count();//$commentsCount;
        $this->getFilesTable()->save($cc);

        $comments = $this->getCommentsTable()->getComments(array('file_name' => $post['file_name']));

        foreach($comments as $comment) {
            $result[] = $comment;
        }

        $view = new JsonModel(array(
            'result' => $result,
            'success'=>true,
        ));

        return $view;
    }

    //------------------------------------------------------------------------------------------------------------------
    public function saveAction()
    {
        if (isset($_POST['avatar'])) {
            $target_w = 150;
            $target_h = 150;
        } else {
            $target_w = 600;
            $target_h = 600;
        }

        $jpeg_quality = 95;

        $src = APP_DIR . '/public' . $_POST['folder'] . $_POST['name'] . $_POST['ext'];
        $folderGlobal = APP_DIR . '/public/files/' . md5($_SESSION['id']);
        $folderLocal = '/files/' . md5($_SESSION['id']);

        $img_r = $this->imageCreateFromAny($src);
        $file = getimagesize ($src);

        if ($file[2] == 3) {
            $img_r = imageCreateFromPng($src);
            $dst_r = ImageCreateTrueColor($target_w, $target_h);

            imagealphablending($dst_r, false);
            $color = imagecolorallocatealpha($dst_r, 255, 255, 255, 127);
            imagefilledrectangle($dst_r, 0, 0, $target_w, $target_h, $color);

            imagealphablending($dst_r, true);
            imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'],
                $target_w, $target_h, $_POST['w'], $_POST['h']);

            imagealphablending($dst_r,false);
            imagesavealpha($dst_r, true);

            if(isset($_POST['avatar'])) {
                $avatarPath = $folderGlobal .'/avatar'.$_POST['ext'];
                if (!file_exists($folderGlobal)) {
                    mkdir($folderGlobal);
                }
                imagepng($dst_r, $avatarPath, 1);
            } else {
                imagepng($dst_r, $src, 1);
            }
        } else {
            $dst_r = ImageCreateTrueColor($target_w, $target_h);
            imagecolorallocate($dst_r, 255, 255, 255);
            imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'],
                $target_w, $target_h, $_POST['w'], $_POST['h']);

#           header('Content-type: image/jpeg');
#           imagejpeg($dst_r);
            if(isset($_POST['avatar'])) {
                $avatarPath = $folderGlobal .'/avatar'.$_POST['ext'];
                if (!file_exists($folderGlobal)) {
                    mkdir($folderGlobal);
                }
                imagejpeg($dst_r, $avatarPath, $jpeg_quality);
            } else {
                imagejpeg($dst_r, $src, $jpeg_quality);
            }
        }

        if (isset($_POST['avatar'])) {
            $profile['id'] = $_SESSION['id'];
            $profile['avatar_path'] = $folderLocal .'/avatar'.$_POST['ext'];
            $this->getUsersTable()->save($profile);

            return $this->redirect()->toUrl('/'. $_SESSION['profile_name']);
        }

        $post['id_user'] = $_SESSION['id'];
        $post['folder'] = $_POST['folder'];
        $post['name'] = $_POST['name'];
        $post['ext'] = $_POST['ext'];

        $this->getFilesTable()->save($post);

        $files = $this->getFilesTable()->getManyBy(array('id_user' => $_SESSION['id']));
        $filesCount['id'] = $_SESSION['id'];
        $filesCount['count_photos'] = count($files);
        $this->getUsersTable()->save($filesCount);

        return $this->redirect()->toUrl('/'. $_SESSION['profile_name']);
    }

    //------------------------------------------------------------------------------------------------------------------
    public function likeAction()
    {
        $file_name = $this->params()->fromRoute('id');
        $file = $this->getFilesTable()->getOneBy(array('name' => $file_name));

        $post['file_name'] = $file_name;
        $post['profile_name'] = $_SESSION['profile_name'];

        if ($this->getLikesTable()->getOneBy($post)) {
            $this->redirect()->toUrl('/files/'. $file_name);
        }

        $this->getLikesTable()->save($post);

        $likes = $this->getLikesTable()->getManyBy(array('file_name' => $file_name));
        $likesCount['id'] = $file['id'];
        $likesCount['likes'] = count($likes);

        $this->getFilesTable()->save($likesCount);

        $this->redirect()->toUrl('/files/view/'. $file_name);
    }

    //------------------------------------------------------------------------------------------------------------------
    public function dislikeAction()
    {
        $file_name = $this->params()->fromRoute('id');
        $file = $this->getFilesTable()->getOneBy(array('name' => $file_name));

        $post['file_name'] = $file_name;
        $post['profile_name'] = $_SESSION['profile_name'];

        if ($this->getLikesTable()->getOneBy($post)) {
            $this->redirect()->toUrl('/files/'. $file_name);
        }

        $this->getLikesTable()->delete($post);

        $likes = $this->getLikesTable()->getManyBy(array('file_name' => $file_name));
        $likesCount['id'] = $file['id'];
        $likesCount['likes'] = count($likes);

        $this->getFilesTable()->save($likesCount);

        $this->redirect()->toUrl('/files/view/'. $file_name);
    }

    //------------------------------------------------------------------------------------------------------------------
    public function deleteAction()
    {
        $fileName = $this->params()->fromRoute('id');
        $this->getFilesTable()->delete(array('name' => $fileName));
        $this->getCommentsTable()->delete(array('file_name' => $fileName));

        $files = $this->getFilesTable()->getManyBy(array('id_user' => $_SESSION['id']));
        $filesCount['id'] = $_SESSION['id'];
        $filesCount['count_photos'] = count($files);
        $this->getUsersTable()->save($filesCount);

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
    public function getCommentsTable()
    {
        return $this->getServiceLocator()->get('CommentsTable');
    }

    //------------------------------------------------------------------------------------------------------------------
    public function getLikesTable()
    {
        return $this->getServiceLocator()->get('LikesTable');
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
