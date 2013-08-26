<?php
/**
 * Copyright © 2013 NEILSEN·CHAN. All rights reserved.
 * 
 * @author chenjinlong
 * @date 13-8-1
 * @time 下午7:10
 * @description ActionConfig.php
 */ 
class ActionConfig 
{
    private $_actionConfigs = array();

    /**
     * @param array $actionConfigs
     */
    public function setActionConfigs($actionConfigs)
    {
        $this->_actionConfigs = $actionConfigs;
    }

    /**
     * @return array
     */
    public function getActionConfigs()
    {
        return $this->_actionConfigs;
    }

    function __construct(&$actionConfigs)
    {
        $this->_actionConfigs = &$actionConfigs;
    }

}
