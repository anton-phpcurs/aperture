<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        if (!isset($_SESSION['profile_name'])) {
            return new ViewModel();
        };

        $files = $this->getFilesTable()->getNews();

        $view = new ViewModel(array('files' => $files));
        $view->setTemplate('application/news');
        return $view;
    }

    // Helper function =================================================================================================
    public function getFilesTable()
    {
        return $this->getServiceLocator()->get('FilesTable');
    }
}
