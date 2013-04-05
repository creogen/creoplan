<?php

/**
 * @property Zend_Controller_Request_Http $_request
 */
class LoginController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        if ($this->_request->isPost()) {
            $login = $this->_request->getPost('login');
            $password = $this->_request->getPost('password');
            $redirect = $this->_request->getPost('redirect');

            if ($this->doAuth($login, $password)) {
                $this->_redirect($redirect);
            }
        }
    }

    protected function doAuth($login, $password)
    {
        $preparedPassword = sprintf("%s%s", 'creoplanner', $password);

        $auth = Zend_Auth::getInstance();
        $authAdapter = new  Zend_Auth_Adapter_DbTable();
        $authAdapter->setTableName('users')
            ->setIdentityColumn('login')
            ->setCredentialColumn('password')
            ->setCredentialTreatment('sha1(?)')
            ->setIdentity($login)
            ->setCredential($preparedPassword);

        $result = $auth->authenticate($authAdapter);

        if ($result->isValid()) {
            $data = $authAdapter->getResultRowObject(null, 'password');
            $auth->getStorage()->write($data);
            return true;
        }

        return false;
    }


}

