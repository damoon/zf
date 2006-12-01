<?php

/**
 * Zend_Mail_Folder
 */
require_once 'Zend/Mail/Folder.php';

/**
 * Zend_Mail_Folder_Interface
 */
require_once 'Zend/Mail/Folder/Interface.php';

/**
 * Zend_Mail_Mbox
 */
require_once 'Zend/Mail/Mbox.php';

/**
 * Zend
 */
require_once 'Zend.php';

class Zend_Mail_Folder_Mbox extends Zend_Mail_Mbox implements Zend_Mail_Folder_Interface
{
    /**
     * Zend_Mail_Folder root folder for folder structure
     */
    protected $_rootFolder;

    /**
     * rootdir of folder structure
     */
    protected $_rootdir;

    /**
     * name of current folder
     */
    protected $_currentFolder;

    /**
     * Create instance with parameters
     * Disallowed parameters are:
     *   - filename use Zend_Mail_Mbox for a single file
     * Supported parameters are:
     *   - rootdir rootdir of mbox structure
     *   - folder intial selected folder, default is 'INBOX'
     *
     * @param  $params              array mail reader specific parameters
     * @throws Zend_Mail_Exception
     */
    public function __construct($params)
    {
        if(isset($params['filename'])) {
            throw Zend::exception('Zend_Mail_Exception', 'use Zend_Mail_Mbox for a single file');
        }

        if(!isset($params['rootdir']) || !is_dir($params['rootdir'])) {
            throw Zend::exception('Zend_Mail_Exception', 'no valid rootdir given in params');
        }

        $this->_rootdir = rtrim($params['rootdir'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $this->_buildFolderTree($this->_rootdir);
        $this->selectFolder(!empty($params['folder']) ? $params['folder'] : 'INBOX');
        $this->_has['top'] = true;
    }

    /**
     * find all subfolders and mbox files for folder structure
     *
     * Result is save in Zend_Mail_Folder instances with the root in $this->_rootFolder.
     * $parentFolder and $parentGlobalName are only used internally for recursion.
     *
     * @params string $currentDir call with root dir, also used for recursion.
     * @params Zend_Mail_Folder|null $parentFolder used for recursion
     * @params string $parentGlobalName used for rescursion
     */
    private function _buildFolderTree($currentDir, $parentFolder = null, $parentGlobalName = '')
    {
        if(!$parentFolder) {
            $this->_rootFolder = new Zend_Mail_Folder('/', '/', false);
            $parentFolder = $this->_rootFolder;
        }

        $dh = @opendir($currentDir);
        if(!$dh) {
            throw Zend::Exception('Zend_Mail_Exception', "can't read dir $currentDir");
        }
        while(($entry = readdir($dh)) !== false) {
            $absoluteEntry = $currentDir . $entry;
            $globalName = $parentGlobalName . DIRECTORY_SEPARATOR . $entry;
            if(is_file($absoluteEntry) && $this->_isMboxFile($absoluteEntry)) {
                $parentFolder->$entry = new Zend_Mail_Folder($entry, $globalName);
                continue;
            }
            if(!is_dir($absoluteEntry) || $entry == '.' || $entry == '..') {
                continue;
            }
            $folder = new Zend_Mail_Folder($entry, $globalName, false);
            $parentFolder->$entry = $folder;
            $this->_buildFolderTree($absoluteEntry . DIRECTORY_SEPARATOR, $folder, $globalName);
        }

        closedir($dh);
    }

    /**
     * get root folder or given folder
     *
     * @params string $rootFolder get folder structure for given folder, else root
     * @return Zend_Mail_Folder root or wanted folder
     */
    public function getFolders($rootFolder = null)
    {
        if(!$rootFolder) {
            return $this->_rootFolder;
        }

        $currentFolder = $this->_rootFolder;
        $subname = trim($rootFolder, DIRECTORY_SEPARATOR);
        while($currentFolder) {
            @list($entry, $subname) = @explode('/', $subname, 2);
            $currentFolder = $currentFolder->$entry;
            if(!$subname) {
                break;
            }
        }

        if($currentFolder->getGlobalName() != rtrim($rootFolder, DIRECTORY_SEPARATOR)) {
            throw Zend::Exception('Zend_Mail_Exception', "folder $rootFolder not found");
        }
        return $currentFolder;
    }

    /**
     * select given folder
     *
     * folder must be selectable!
     *
     * @params Zend_Mail_Folder|string global name of folder or instance for subfolder
     * @throws Zend_Mail_Exception
     */
    public function selectFolder($globalName)
    {
        $this->_currentFolder = (string)$globalName;
        try {
            $this->_openMboxFile($this->_rootdir . $this->_currentFolder);
        } catch(Zend_Mail_Exception $e) {
            // check what went wrong
            // if folder does not exist getFolders() throws an exception
            if(!$this->getFolders($this->_currentFolder)->isSelectable()) {
                throw Zend::Exception('Zend_Mail_Exception', "{$this->_currentFolder} is not selectable");
            }
            // seems like file has vanished; rebuilding folder tree - but it's still an exception
            $this->_buildFolderTree($this->_rootdir);
            throw Zend::Exception('Zend_Mail_Exception', 'seems like the mbox file has vanished, I\'ve rebuild the ' .
                                                         'folder tree, search for an other folder and try again');
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

    /**
     * magic method for serialize()
     *
     * with this method you can cache the mbox class
     *
     * @return array name of variables
     */
    public function __sleep()
    {
        return array_merge(parent::__sleep(), array('_currentFolder', '_rootFolder', '_rootdir'));
    }

    /**
     * magic method for unserialize()
     *
     * with this method you can cache the mbox class
     */
    public function __wakeup()
    {
        // if cache is stall selectFolder() rebuilds the tree on error
        parent::__wakeup();
    }
}