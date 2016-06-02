<?php
/**
 * Copyright © 2013 JEROMECHAN. All rights reserved.
 * 
 * @author chenjinlong
 * @date 13-8-6
 * @time 上午11:53
 * @description DAO_Mysqli.php
 */ 
class DAO_Mysqli 
{
    private $_mysqli;

    private $_mysqliSet = array();

    private $_assoc = false;

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

    /**
     * @param mixed $mysqli
     */
    public function setMysqli($mysqli)
    {
        $this->_mysqli = $mysqli;
    }

    /**
     * @return mixed
     */
    public function getMysqli()
    {
        return $this->_mysqli;
    }

    /**
     * @param array $mysqliSet
     */
    public function setMysqliSet($mysqliSet)
    {
        $this->_mysqliSet = $mysqliSet;
    }

    /**
     * @return array
     */
    public function getMysqliSet()
    {
        return $this->_mysqliSet;
    }

    public function createInstanceRO()
    {
        return $this->_connect(RO_DB_SCHEMA, RO_DB_USERNAME, RO_DB_PASSWORD, RO_DB_HOST, RO_DB_PORT);
    }

    public function createInstanceRW()
    {
        return $this->_connect(RW_DB_SCHEMA, RW_DB_USERNAME, RW_DB_PASSWORD, RW_DB_HOST, RW_DB_PORT);
    }

    private function _connect($dbHost='', $dbPort = '', $dbUser = '', $dbPassword = '', $dbName = '')
    {
        $cacheKey = md5('@link_' . $dbName . $dbUser . $dbPassword . $dbHost . $dbPort);
        if(in_array($cacheKey, array_keys($this->_mysqliSet)) && is_resource($this->_mysqliSet[$cacheKey]))
        {
            @mysqli_ping($this->_mysqliSet[$cacheKey]);
            $this->_mysqli = $this->_mysqliSet[$cacheKey];
            return $this->_mysqliSet[$cacheKey];
        }
        else
        {
            $this->_mysqli = new mysqli($dbHost, $dbUser, $dbPassword, $dbName, $dbPort);
            //$this->_mysqli->set_charset('utf8');
            @mysqli_set_charset($this->_mysqli, 'utf8');
            $this->_mysqliSet[$cacheKey] = $this->_mysqli;
            return $this->_mysqli;
        }
    }

    public function emptyLinks()
    {
        if(!empty($this->_mysqliSet))
        {
            foreach($this->_mysqliSet as $connLink)
            {
                @mysqli_close($connLink);
            }
        }
        return true;
    }

    public function getRow($sql, $bindParams)
    {
        $resource = $this->_query($sql, $bindParams);
        $row = $this->_fetch($resource);
        return $row;
    }

    public function getRows($sql, $bindParams)
    {
        $resource = $this->_query($sql, $bindParams);
        $rows = array();
        while($row = $this->_fetch($resource))
        {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getRowsWithNum($sql, $bindParams)
    {
        $resource = $this->_query($sql, $bindParams);
        $num = @mysqli_num_rows($resource);
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

    public function getMappingRows($sql, $bindParams, $firstDim)
    {
        $resource = $this->_query($sql, $bindParams);
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

    public function getCol($sql, $bindParams)
    {
        $resource = $this->_query($sql, $bindParams);

        $col = array();
        while(($row = $this->_fetch($resource)))
        {
            $col[] = $row[0];
        }
        return $col;
    }

    public function getVal($sql, $bindParams)
    {
        $row = $this->getRow($sql, $bindParams);
        return $row[0];
    }

    public function insert($sql, $bindParams)
    {
        $resource = $this->_query($sql, $bindParams);
        if($resource)
        {
            return @mysqli_stmt_insert_id($resource);
        }
        else
        {
            return false;
        }
    }

    public function update($sql, $bindParams)
    {
        $resource = $this->_query($sql, $bindParams);
        if($resource)
        {
            return @mysqli_affected_rows($resource);
        }
        else
        {
            return false;
        }
    }

    public function delete($sql, $bindParams)
    {
        $resource = $this->_query($sql, $bindParams);
        if($resource)
        {
            return @mysqli_affected_rows($resource);
        }
        else
        {
            return false;
        }
    }

    private function genMappingRoutes($sql, $bindParams)
    {
        preg_match_all('/:[\w]+/', $sql, $match);
        $mappingKeys = $match[0];

        $typeBind = '';
        $itemBind = array();
        if(!empty($mappingKeys))
        {
            foreach($mappingKeys as $key)
            {
                if(is_int($bindParams[$key]))
                {
                    $typeBind .= 'i';
                    $itemBind[] = $bindParams[$key];
                    continue;
                }
                if(is_double($bindParams[$key]))
                {
                    $typeBind .= 'd';
                    $itemBind[] = $bindParams[$key];
                    continue;
                }
                if(is_string($bindParams[$key]))
                {
                    $typeBind .= 's';
                    $itemBind[] = "'" . $bindParams[$key] . "'";
                    continue;
                }
            }
        }
        return array(
            'type' => $typeBind,
            'item' => $itemBind,
        );
    }

    private function _query($sql, $bindParams)
    {
        $mappingRoutes = $this->genMappingRoutes($sql, $bindParams);
        $typeBind = $mappingRoutes['type'];
        $itemBind = $mappingRoutes['item'];

        $stmt = $this->_mysqli->prepare($sql);
        if($typeBind)
        {
            $appendParams = implode(',', $itemBind);
            $stmt->bind_param($typeBind, $appendParams);
        }
        $stmt->execute();
        $resource = $stmt->get_result();
        return $resource;
    }

    private function _fetch(&$resource)
    {
        $rows = array();
        if($this->_assoc)
        {
            $rows = $resource->fetch_array(MYSQLI_ASSOC);
        }
        else
        {
            $rows = $resource->fetch_array(MYSQL_BOTH);
        }
        @mysqli_free_result($resource);
        return $rows;
    }

}
