<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to version 1.0 of the Zend Framework
 * license, that is bundled with this package in the file LICENSE, and
 * is available through the world-wide-web at the following URL:
 * http://www.zend.com/license/framework/1_0.txt. If you did not receive
 * a copy of the Zend Framework license and are unable to obtain it
 * through the world-wide-web, please send a note to license@zend.com
 * so we can mail you a copy immediately.
 *
 * @package    Zend_Mail
 * @copyright  Copyright (c) 2005-2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://www.zend.com/license/framework/1_0.txt Zend Framework License version 1.0
 */


/**
 * Zend_Mail_Abstract
 */
require_once 'Zend/Mail/Abstract.php';

/**
 * Zend_Mail_Transport_Pop3
 */
require_once 'Zend/Mail/Transport/Imap.php';

/**
 * Zend_Mail_Folder_Interface
 */
require_once 'Zend/Mail/Folder/Interface.php';

/**
 * Zend_Mail_Folder
 */
require_once 'Zend/Mail/Folder.php';

/**
 * Zend_Mail_Message
 */
require_once 'Zend/Mail/Message.php';

/**
 * Zend_Mail_Exception
 */
require_once 'Zend/Mail/Exception.php';

/**
 * @package    Zend_Mail
 * @copyright  Copyright (c) 2005-2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://www.zend.com/license/framework/1_0.txt Zend Framework License version 1.0
 */
class Zend_Mail_Imap extends Zend_Mail_Abstract implements Zend_Mail_Folder_Interface
{
    private $_protocol;
    private $_currentFolder = '';


    /**
     *
     * Count messages all messages in current box
     * No flags are supported by POP3 (exceptions is thrown)
     *
     * @param int filter by flags
     * @return int number of messages
     * @throws Zend_Mail_Exception
     */
    public function countMessages($flags = null)
    {
        if(!$this->_currentFolder) {
            throw new Zend_Mail_Exception('No selected folder to count');
        }
        $result = $this->_protocol->examine($this->_currentFolder);
        return $result['exists'];
    }

    /**
     * get a list of messages with number and size
     *
     * @param int number of message
     * @return int|array size of given message of list with all messages as array(num => size)
     */
    public function getSize($id = 0)
    {
        if($id) {
            return $this->_protocol->fetch('RFC822.SIZE', $id);
        }
        return $this->_protocol->fetch('RFC822.SIZE', 1, INF);
    }

    /**
     *
     * get a message with headers and body
     *
     * @param int number of message
     * @return Zend_Mail_Message
     */
    public function getMessage($id)
    {
        $message = $this->_protocol->fetch(array('RFC822.HEADER', 'RFC822.TEXT'), $id);
        return new Zend_Mail_Message($message['RFC822.TEXT'], $message['RFC822.HEADER']);
    }

    /**
     *
     * get a message with only header and $bodyLines lines of body
     *
     * @param int number of message
     * @param int also retrieve this number of body lines
     * @return Zend_Mail_Message
     */
    public function getHeader($id, $bodyLines = 0)
    {
        if($bodyLines) {
            throw new Zend_Mail_Exception('body lines not yet supported');
        }
        $message = $this->_protocol->fetch('RFC822.HEADER', $id);
        return new Zend_Mail_Message('', $message);
    }

    /**
     *
     * create instance with parameters
     * Supported paramters are
     *   - host hostname or ip address of IMAP server
     *   - user username
     *   - password password for user 'username' [optional, default = '']
     *   - port port for IMAP server [optional, default = 110]
     *   - ssl 'SSL' or 'TLS' for secure sockets
     *   - folder select this folder [optional, default = 'INBOX']
     *
     * @param  $params array  mail reader specific parameters
     * @throws Zend_Mail_Exception
     */
    public function __construct($params)
    {
        if($params instanceof Zend_Mail_Transport_Imap) {
            $this->_protocol = $params;
            try {
                $this->selectFolder('INBOX');
            } catch(Zend_Mail_Exception $e) {
                throw Zend_Mail_Exception('cannot select INBOX, is this a valid transport?');
            }
            return;
        }

        if(!isset($params['host']) || !isset($params['user'])) {
            throw new Zend_Mail_Exception('need at least a host an user in params');
        }
        $params['password'] = isset($params['password']) ? $params['password'] : '';
        $params['port']     = isset($params['port'])     ? $params['port']     : null;
        $params['ssl']      = isset($params['ssl']) ? $params['ssl'] : false;

        $this->_protocol = new Zend_Mail_Transport_Imap();
        $this->_protocol->connect($params['host'], $params['port'], $params['ssl']);
        if(!$this->_protocol->login($params['user'], $params['password'])) {
            throw new Zend_Mail_Exception('cannot login, user or password wrong');
        }
        $this->selectFolder(isset($params['folder']) ? $params['folder'] : 'INBOX');
    }


    /**
     *
     * public destructor
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     *
     * Close resource for mail lib. If you need to control, when the resource
     * is closed. Otherwise the destructor would call this.
     *
     */
    public function close()
    {
        $this->_currentFolder = '';
        $this->_protocol->logout();
    }

    /**
     *
     * Keep the server busy.
     *
     */
    public function noop()
    {
        // TODO: real noop
        return false;
//        return $this->_protocol->noop();
    }

    /**
     *
     * Remove a message from server. If you're doing that from a web enviroment
     * you should be careful and use a uniqueid as parameter if possible to
     * identify the message.
     *
     * @param int number of message
     */
    public function removeMessage($id)
    {
        // TODO: real remove
        return false;
//        $this->_protocol->delete($id);
    }

    /**
     *
     * Special handling for hasTop. The headers of the first message is
     * retrieved if Top wasn't needed/tried yet.
     *
     * @see Zend_Mail_Abstract:__get()
     */
    public function __get($var)
    {
        return parent::__get($var);
    }

    /**
     * get root folder or given folder
     *
     * @param string $rootFolder get folder structure for given folder, else root
     * @return Zend_Mail_Folder root or wanted folder
     */
    public function getFolders($rootFolder = null)
    {
        $folders = $this->_protocol->listMailbox((string)$rootFolder);
        if(!$folders) {
            throw new Zend_Mail_Exception('folder not found');
        }

        ksort($folders, SORT_STRING);
        $root = new Zend_Mail_Folder('/', '/', false);
        $stack = array(null);
        $folderStack = array(null);
        $parentFolder = $root;
        $parent = '';

        foreach($folders as $globalName => $data) {
            do {
                if(!$parent || strpos($globalName, $parent) === 0) {
                    $pos = strrpos($globalName, $data['delim']);
                    if($pos === false) {
                        $localName = $globalName;
                    } else {
                        $localName = substr($globalName, $pos + 1);
                    }
                    $selectable = !$data['flags'] || !in_array('\\Noselect', $data['flags']);

                    array_push($stack, $parent);
                    $parent = $globalName . $data['delim'];
                    $folder = new Zend_Mail_Folder($localName, $globalName, $selectable);
                    $parentFolder->$localName = $folder;
                    array_push($folderStack, $parentFolder);
                    $parentFolder = $folder;
                    break;
                } else if($stack) {
                    $parent = array_pop($stack);
                    $parentFolder = array_pop($folderStack);
                }
            } while($stack);
            if(!$stack) {
                throw new Zend_Mail_Exception('error while constructing folder tree');
            }
        }

        return $root;
    }

    /**
     * select given folder
     *
     * folder must be selectable!
     *
     * @param Zend_Mail_Folder|string global name of folder or instance for subfolder
     * @throws Zend_Mail_Exception
     */
    public function selectFolder($globalName)
    {
        $this->_currentFolder = $globalName;
        if(!$this->_protocol->select($this->_currentFolder)) {
            $this->_currentFolder = '';
            throw new Zend_Mail_Exception('cannot change folder, maybe it does not exist');
        }
    }


    /**
     * get Zend_Mail_Folder instance for current folder
     *
     * @return Zend_Mail_Folder instance of current folder
     * @throws Zend_Mail_Exception
     */
    public function getCurrentFolder()
    {
        return $this->_currentFolder;
    }
}
