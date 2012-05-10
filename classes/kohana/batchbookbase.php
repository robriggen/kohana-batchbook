<?php

class Kohana_Batchbookbase
{
    protected $_account;

    protected $_token;

    public function __construct()
    {
        $config = Kohana::$config->load('batchbook');

        $this->_account = $config->get('account');
        $this->_token = $config->get('token');
    }

}
