<?php
/**
 * Copyright © 2013 NEILSEN·CHAN. All rights reserved.
 * 
 * @date: 7/30/13
 * @description: DAO_Mysql.php
 */ 
class DAO_Mysql 
{
    const MYSQL_SUCCESS_CONNECT = 100;

    const MYSQL_ERROR_CONNECT = 102;

    private $_connection;

    private $_connectionSet = array();

    private $_assoc = FALSE;

    /**
     * @param mixed $connection
     */
    public function setConnection($connection)
    {
        $this->_connection = $connection;
    }

    /**
     * @return mixed
     */
    public function getConnection()
    {
        return $this->_connection;
    }

    /**
     * @param array $connectionSet
     */
    public function setConnectionSet($connectionSet)
    {
        $this->_connectionSet = $connectionSet;
    }

    /**
     * @return array
     */
    public function getConnectionSet()
    {
        return $this->_connectionSet;
    }

    /**
     * @param boolean $assoc
     */
    public function setAssoc($assoc)
    {
        $this->_assoc = $assoc;
    }

    /**
     * @return boolean
     */
    public function getAssoc()
    {
        return $this->_assoc;
    }

    public function createInstanceRO()
    {
        return $this->connect(RO_DB_SCHEMA, RO_DB_USERNAME, RO_DB_PASSWORD, RO_DB_HOST, RO_DB_PORT);
    }

    public function createInstanceRW()
    {
        return $this->connect(RW_DB_SCHEMA, RW_DB_USERNAME, RW_DB_PASSWORD, RW_DB_HOST, RW_DB_PORT);
    }

    public function emptyLinks()
    {
        if(!empty($this->_connectionSet))
        {
            foreach($this->_connectionSet as $connLink)
            {
                @mysql_close($connLink);
            }
        }
        return true;
    }

    public function getRow($sql)
    {
        $resource = $this->_query($sql);
        $row = $this->_fetch($resource);
        return $row;
    }

    public function getRows($sql)
    {
        $resource = $this->_query($sql);
        $rows = array();
        while(($row = $this->_fetch($resource)))
        {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getRowsWithNum($sql)
    {
        $resource = $this->_query($sql);
        $num = @mysql_num_rows($resource);
        $rows = array(
            'count' => $num,
            'rows' => array(),
        );
        while(($row = $this->_fetch($resource)))
        {
            $rows['rows'][] = $row;
        }
        return $rows;
    }

    public function getCol($sql)
    {
        $resource = $this->_query($sql);
        $col = array();
        while(($row = $this->_fetch($resource)))
        {
            $col[] = $row[0];
        }
        return $col;
    }

    public function getMappingRows($sql, $firstDim)
    {
        $resource = $this->_query($sql);
        $rows = array();
        if($firstDim)
        {
            while($row = $this->_fetch($resource))
            {
                $rows[$row[$firstDim]] = $row;
            }
            return $rows;
        }
        else
        {
            throw new Exception('Invalid parameters');
        }
    }

    public function getVal($sql)
    {
        $row = $this->getRow($sql);
        return $row[0];
    }

    public function update($sql)
    {
        $resource = $this->_query($sql);
        if($resource)
        {
            return @mysql_affected_rows($resource);
        }
    }

    public function insert($sql)
    {
        $resource = $this->_query($sql);
        if($resource)
        {
            return @mysql_insert_id($resource);
        }
        else
        {
            return false;
        }
    }

    public function delete($sql)
    {
        $resource = $this->_query($sql);
        if($resource)
        {
            return @mysql_affected_rows($resource);
        }
        else
        {
            return false;
        }
    }

    private function connect($dbSchema = '', $dbUser = '', $dbPassword = '',
                             $dbHost = '', $dbPort = '')
    {
        $cacheKey = md5('@link_' . $dbSchema . $dbUser . $dbPassword . $dbHost . $dbPort);
        if(in_array($cacheKey, array_keys($this->_connectionSet)))
        {
            @mysql_ping($this->_connectionSet[$cacheKey]);
            $this->_connection = $this->_connectionSet[$cacheKey];
            return $this->_connectionSet[$cacheKey];
        }
        $link = @mysql_connect($dbHost.':'.$dbPort, $dbUser, $dbPassword);
        if(!$link)
        {
            return self::MYSQL_ERROR_CONNECT;
        }
        else
        {
            $this->_connectionSet[$cacheKey] = $link;
            $this->_connection = $link;
            @mysql_select_db($dbSchema, $this->_connectionSet[$cacheKey]);
            @mysql_query("set names utf8");
            return self::MYSQL_SUCCESS_CONNECT;
        }
    }

    private function _query($sql)
    {
        return @mysql_query($sql, $this->_connection);
    }

    private function _fetch(&$resource)
    {
        $rows = array();
        if(!$this->_assoc)
        {
            $rows = @mysql_fetch_array($resource);
        }
        else
        {
            $rows = @mysql_fetch_assoc($resource);
        }
        @mysql_free_result($resource);
        return $rows;
    }

}
