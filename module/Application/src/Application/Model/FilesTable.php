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

class FilesTable
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
    public function getNext($where = null)
    {
        $sql = new Sql($this->tableGateway->adapter);

        $select = $sql->select();
        $select->from('files');
        $select->where($where);
        $select->order('id');
        $select->limit(1);

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        return $results->current();
    }

    //------------------------------------------------------------------------------------------------------------------
    public function getPrev($where = null)
    {
        $sql = new Sql($this->tableGateway->adapter);

        $select = $sql->select();
        $select->from('files');
        $select->where($where);
        $select->order('id DESC');
        $select->limit(1);

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        return $results->current();
    }

    //------------------------------------------------------------------------------------------------------------------
    public function getNews()
    {
        $sql = new Sql($this->tableGateway->adapter);

        $select = $sql->select();
        $select->from('files');
        $select->order('id DESC');
        $select->limit(10);

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

    //------------------------------------------------------------------------------------------------------------------
    public function delete($where = null)
    {
        return $this->tableGateway->delete($where);
    }

    // Helper function =================================================================================================
    //------------------------------------------------------------------------------------------------------------------
    public function exchangeArray($dataIn)
    {
        $dataOut = [];

        if (isset($dataIn['id']))       {$dataOut['id']      = $dataIn['id'];}
        if (isset($dataIn['id_user']))  {$dataOut['id_user'] = $dataIn['id_user'];}
        if (isset($dataIn['folder']))   {$dataOut['folder']  = $dataIn['folder'];}
        if (isset($dataIn['name']))     {$dataOut['name']    = $dataIn['name'];}
        if (isset($dataIn['ext']))      {$dataOut['ext']     = $dataIn['ext'];}
        if (isset($dataIn['likes']))    {$dataOut['likes']   = $dataIn['likes'];}
        if (isset($dataIn['comments'])) {$dataOut['comments']= $dataIn['comments'];}
        if (isset($dataIn['views']))    {$dataOut['views']   = $dataIn['views'];}

        return $dataOut;
    }
}