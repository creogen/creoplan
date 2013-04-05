<?php

class Creogen_Mail
{
    private $_globalConfig;

    public function __construct($config)
    {
        $this->_globalConfig = $config;
    }

    public function sendMail($to, $subject, $body)
    {
        require_once 'Zend/Mail.php';
        require_once 'Zend/Mail/Transport/Smtp.php';

        $config = array(
            'auth' => 'login',
            'username' => $this->_globalConfig['username'],
            'password' => $this->_globalConfig['password'],
            'port' => 25,
//            'port'      => 465,
//            'ssl'       => 'tls'
        );

        $ret = 0;
        try {
            $transport = new Zend_Mail_Transport_Smtp($this->_globalConfig['smtp_host'], $config);

            $mail = new Zend_Mail('utf-8');
            $mail->setBodyHtml($body, 'utf-8');
            $mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
            $mail->setFrom($this->_globalConfig['from_email'], $this->_globalConfig['from_title']);
            $mail->addTo($to);
            $mail->setSubject($subject);
            $mail->send($transport);

            $ret = 1;
        } catch (Zend_Mail_Protocol_Exception $e) {

        }

        return $ret;
    }
}