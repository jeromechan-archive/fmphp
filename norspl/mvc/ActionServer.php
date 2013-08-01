<?php
/**
 * Copyright © 2013 NEILSEN·CHAN. All rights reserved.
 * 
 * @author chenjinlong
 * @date 13-8-1
 * @time 下午7:18
 * @description ActionServer.php
 */ 
class ActionServer 
{
    private $_actionMap;

    private $_req;

    private $_resp;

    private $_form;

    /**
     * @param mixed $actionMap
     */
    public function setActionMap($actionMap)
    {
        $this->_actionMap = $actionMap;
    }

    /**
     * @return mixed
     */
    public function getActionMap()
    {
        return $this->_actionMap;
    }

    /**
     * @param mixed $req
     */
    public function setReq($req)
    {
        $this->_req = $req;
    }

    /**
     * @return mixed
     */
    public function getReq()
    {
        return $this->_req;
    }

    /**
     * @param mixed $resp
     */
    public function setResp($resp)
    {
        $this->_resp = $resp;
    }

    /**
     * @return mixed
     */
    public function getResp()
    {
        return $this->_resp;
    }

    /**
     * @param mixed $form
     */
    public function setForm($form)
    {
        $this->_form = $form;
    }

    /**
     * @return mixed
     */
    public function getForm()
    {
        return $this->_form;
    }

    public function init(&$actionConfig, $request, $post = array())
    {
        $this->_actionMap = $actionConfig;

        $this->_req = $request;

        foreach ($request as $k => $v)
        {
            if (is_array($v))
            {
                foreach ($v as $kk => $vv)
                {
                    $this->_req[$k][$kk] = urldecode(trim($vv));
                }
            }
            else
            {
                $this->_req[$k] = urldecode(trim($v));
            }
        }
        if (is_array($post))
        {
            foreach ($post as $k => $v)
            {
                if (is_array($v))
                {
                    foreach ($v as $kk => $vv)
                    {
                        $this->_form[$k][$kk] = trim($vv);
                    }
                }
                else
                {
                    $this->_form[$k] = trim($v);
                }
            }
        }
    }

    public function process()
    {
        $actionPath = $this->_actionMap['action'];
        include_once '/opt/www/project/' . $actionPath . '.php';

        $actionPathArr = explode('/', $actionPath);
        if(!class_exists($actionPathArr[1]))
        {
            throw new HttpUrlException('Class is not found!');
        }
        else
        {
            $className = $actionPathArr[1];
        }
        $actionObject = new $className;
        $actionResult = $actionObject->execute($this->_req, $this->_resp, $this->_form);
        $actionObject->base($this->_req, $this->_resp);

        if(empty($actionResult))
        {
            include_once '/opt/www/project/' . $actionResult['tpl'];
        }
        else
        {
            // Base on var named $forwardConfig
            // @TODO need to re-edit the field 'path' below.
            include_once($actionResult['action']);
        }
    }

}
