<?php

/**
 * EJabberSender class file.
 *
 * PHP Version 5.3
 * 
 * @package  Component
 * @author   Segoddnja <segoddnja@i.ua>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT
 * @since    1.0
 */

Yii::import('application.extensions.EJabberSender.XMPPHP.*');

/**
 * EJabberSender helps send a message with Jabber protocol.
 *
 * EJabberSender encapsulates the {@link http://code.google.com/p/xmpphp/ 
 * PHP XMPP Library}.
 *
 * To use this component, you may insert the following code in a config file:
 * <pre>
 * 'components'=>array(
 *      ...
 *      'jabberSender'=>array(
 *          'class' => 'application.extensions.EJabberSender.EJabberSender',
 *          'host' => 'talk.google.com',
 *          'port' => 5222,
 *          'user' => 'username',
 *          'password' => '*******',
 *          'server' => 'gmail.com'
 *      ),
 *      ...
 * )
 * </pre>
 * 
 * And in controller use the code
 * <pre>
 *      Yii::app()->jabberSender->sendMessage('user@gmail.com', 'Test, test, test');
 * </pre>
 *
 * @author segoddnja <segoddnja@i.ua>
 */

class EJabberSender extends CApplicationComponent
{
    /**
     * @var string jabber host
     */
    public $host;
    
    /**
     * @var int port
     */
    public $port = 5222;
    
    /**
     * @var string user name
     */
    public $user;
    
    /**
     * @var string user password
     */
    public $password;
    
    /**
     * @var string server
     */
    public $server;
    
    /**
     * @var boolean use encryption
     */
    public $useEncryption = true;
    
    /**
     * @var XMPPHP_XMPP connection handler
     */
    private $conn;
    
    public function __destruct() {
        if($this->conn)
            $this->stopSender();
    }


    /**
     * This method prepare component to send messages
     */
    public function prepareSender(){
        if($this->conn)
        {
            $this->stopSender();
        }
        $this->conn = new XMPPHP_XMPP($this->host, $this->port, $this->user, $this->password, 'xmpphp', $this->server);
        $this->conn->useEncryption($this->useEncryption);
        $this->conn->connect();
        $this->conn->processUntil('session_start');
    }
    
    /**
     * This method close connection to jabber server
     */
    public function stopSender(){
        $this->conn->disconnect();
        $this->conn = NULL;
    }

    /**
     * This method sends message
     * @param string $to
     * @param string $message
     */
    public function sendMessage($to, $message){ 
        if(!$this->conn)
            $this->prepareSender();
        try {
            $this->conn->message($to, $message);            
        } catch(XMPPHP_Exception $e) {
            die($e->getMessage());
        }
    }
}
?>
