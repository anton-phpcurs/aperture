<?php
/**
 * Created by PhpStorm.
 * User: neonum
 * Date: 04.10.15
 * Time: 11:38
 */

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;
#use Zend\Db\Sql\Sql;

class UsersTable
{
    protected $tableGateway;

    //------------------------------------------------------------------------------------------------------------------
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    //------------------------------------------------------------------------------------------------------------------
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    //------------------------------------------------------------------------------------------------------------------
    public function getManyBy($where = null)
    {
        return $this->tableGateway->select($where);
    }

    //------------------------------------------------------------------------------------------------------------------
    public function getOneBy($where = null)
    {
        return $this->tableGateway->select($where)->current();
    }

    //------------------------------------------------------------------------------------------------------------------
    public function save($data)
    {
        $data = $this->exchangeArray($data);
        $id = (isset($data['id'])) ? (int) $data['id'] : 0;

        if ($id == 0) {
            $this->tableGateway->insert($data);
        } elseif ($this->getOneBy(array('id' => $id))) {
            $this->tableGateway->update($data, array('id' => $id));
        } else {
            die('ErrorUser');
            throw new Exception('Form id does not exist');
        }
    }

    // Helper function =================================================================================================
    //------------------------------------------------------------------------------------------------------------------
    public function exchangeArray($dataIn)
    {
        $dataOut = [];

        if (isset($dataIn['id']))             {$dataOut['id']           = $dataIn['id'];}
        if (isset($dataIn['profile_name']))   {$dataOut['profile_name'] = $dataIn['profile_name'];}
        if (isset($dataIn['email']))          {$dataOut['email']        = $dataIn['email'];}
        if (isset($dataIn['password']))       {$dataOut['password']     = $dataIn['password'];}
        if (isset($dataIn['full_name']))      {$dataOut['full_name']    = $dataIn['full_name'];}
        if (isset($dataIn['bio']))            {$dataOut['bio']          = $dataIn['bio'];}
        if (isset($dataIn['link_vk']))        {$dataOut['link_vk']      = $dataIn['link_vk'];}
        if (isset($dataIn['link_fb']))        {$dataOut['link_fb']      = $dataIn['link_fb'];}
        if (isset($dataIn['link_tw']))        {$dataOut['link_tw']      = $dataIn['link_tw'];}
        if (isset($dataIn['link_skype']))     {$dataOut['link_fb']      = $dataIn['link_skype'];}
        if (isset($dataIn['link_site']))      {$dataOut['link_fb']      = $dataIn['link_site'];}

        return $dataOut;
    }
}