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

class CommentsTable
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
    public function getComments($where = null)
    {
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('comments');
        $select->where($where);
        $select->order('id DESC');

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

        if (isset($dataIn['id']))           {$dataOut['id']             = $dataIn['id'];}
        if (isset($dataIn['file_name']))    {$dataOut['file_name']      = $dataIn['file_name'];}
        if (isset($dataIn['profile_name'])) {$dataOut['profile_name']   = $dataIn['profile_name'];}
        if (isset($dataIn['text']))         {$dataOut['text']           = $dataIn['text'];}
        if (isset($dataIn['date']))        {$dataOut['date']           = $dataIn['date'];}

        return $dataOut;
    }
}