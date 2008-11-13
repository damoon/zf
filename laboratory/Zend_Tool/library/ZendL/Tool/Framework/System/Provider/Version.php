<?php

require_once 'Zend/Version.php';

/**
 * Version Provider
 *
 */
class ZendL_Tool_Framework_System_Provider_Version implements ZendL_Tool_Framework_Provider_Interface
{

    const MODE_MAJOR = 'major';
    const MODE_MINOR = 'minor';
    const MODE_MINI  = 'mini';
    
    protected $_specialties = array('MajorPart', 'MinorPart', 'MiniPart');
    
    /**
     * Show Action
     *
     * @param string $mode The mode switch can be one of: major, minor, or mini (default)
     * @param bool $nameincluded
     */
    public function show($mode = self::MODE_MINI, $nameincluded = true)
    {

        $versionInfo = $this->_splitVersion();
        
        switch($mode) {
            case self::MODE_MINOR:
                unset($versionInfo['mini']);
                break;
            case self::MODE_MAJOR:
                unset($versionInfo['mini'], $versionInfo['minor']);
                break;
        }
        
        $output = implode('.', $versionInfo);
        
        if ($nameincluded) {
            $output = 'Zend Framework Version: ' . $output;
        }
        
        ZendL_Tool_Framework_Endpoint_Registry::getInstance()->response->appendContent($output);
    }
    
    public function displayAction()
    {
        $this->show();
    }

    public function showMajorPart($nameincluded = true)
    {
        $versionNumbers = $this->_splitVersion();
        $output = (($nameincluded == true) ? 'ZF Major Version: ' : null) . $versionNumbers['major'];
        ZendL_Tool_Framework_Endpoint_Registry::getInstance()->response->appendContent($output);
    }
    
    public function displayMajorPart($nameincluded = true)
    {
        $versionNumbers = $this->_splitVersion();
        $output = (($nameincluded == true) ? 'ZF Major Version: ' : null) . $versionNumbers['major'];
        ZendL_Tool_Framework_Endpoint_Registry::getInstance()->response->appendContent($output);
    }
    
    public function showMinorPart($nameincluded = true)
    {
        $versionNumbers = $this->_splitVersion();
        $output = (($nameincluded == true) ? 'ZF Minor Version: ' : null) . $versionNumbers['minor'];
        ZendL_Tool_Framework_Endpoint_Registry::getInstance()->response->appendContent($output);
    }
    
    public function showMiniPart($nameincluded = true)
    {
        $versionNumbers = $this->_splitVersion();
        $output = (($nameincluded == true) ? 'ZF Mini Version: ' : null)  . $versionNumbers['mini'];
        ZendL_Tool_Framework_Endpoint_Registry::getInstance()->response->appendContent($output);
    }
    
    protected function _splitVersion()
    {
        list($major, $minor, $mini) = explode('.', Zend_Version::VERSION);
        return array('major' => $major, 'minor' => $minor, 'mini' => $mini);
    }
    
}
