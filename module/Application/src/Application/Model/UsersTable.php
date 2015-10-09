<?php
/**
 * Created by PhpStorm.
 * User: neonum
 * Date: 04.10.15
 * Time: 11:38
 */

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;

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
    public function search($profile_name)
    {
        $sql = new Sql($this->tableGateway->adapter);
        $where = new \Zend\Db\Sql\Where();

        $select = $sql->select();
        $select->from('users');

        $where->like('profile_name', '%'.$profile_name.'%');
        $select->where($where);

        $select->order('profile_name');
        $select->limit(15);

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        return $results;
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

        if (isset($dataIn['id']))              {$dataOut['id']              = $dataIn['id'];}
        if (isset($dataIn['is_active']))       {$dataOut['is_active']       = $dataIn['is_active'];}
        if (isset($dataIn['activation']))      {$dataOut['activation']      = $dataIn['activation'];}
        if (isset($dataIn['profile_name']))    {$dataOut['profile_name']    = $dataIn['profile_name'];}
        if (isset($dataIn['email']))           {$dataOut['email']           = $dataIn['email'];}
        if (isset($dataIn['password']))        {$dataOut['password']        = $dataIn['password'];}
        if (isset($dataIn['full_name']))       {$dataOut['full_name']       = $dataIn['full_name'];}
        if (isset($dataIn['bio']))             {$dataOut['bio']             = $dataIn['bio'];}
        if (isset($dataIn['link_vk']))         {$dataOut['link_vk']         = $dataIn['link_vk'];}
        if (isset($dataIn['link_fb']))         {$dataOut['link_fb']         = $dataIn['link_fb'];}
        if (isset($dataIn['link_tw']))         {$dataOut['link_tw']         = $dataIn['link_tw'];}
        if (isset($dataIn['link_skype']))      {$dataOut['link_skype']      = $dataIn['link_skype'];}
        if (isset($dataIn['link_site']))       {$dataOut['link_site']       = $dataIn['link_site'];}
        if (isset($dataIn['count_photos']))    {$dataOut['count_photos']    = $dataIn['count_photos'];}
        if (isset($dataIn['count_following'])) {$dataOut['count_following'] = $dataIn['count_following'];}
        if (isset($dataIn['count_followers'])) {$dataOut['count_followers'] = $dataIn['count_followers'];}

        return $dataOut;
    }
}