<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Tag
 * @subpackage Cloud
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @see Zend_Tag_Item
 */
require_once 'Zend/Tag/Item.php';

/**
 * @category   Zend
 * @package    Zend_Tag
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Tag_Cloud
{
    /**
     * Decorator for the cloud
     *
     * @var Zend_Tag_Cloud_Decorator_Cloud
     */
    protected $_cloudDecorator = null;

    /**
     * Decorator for the tags
     *
     * @var Zend_Tag_Cloud_Decorator_Tag
     */
    protected $_tagDecorator = null;

    /**
     * List of all tags
     *
     * @var array
     */
    protected $_tags;

    /**
     * Plugin loader for decorators
     *
     * @var Zend_Loader_PluginLoader
     */
    protected $_pluginLoader = null;

    /**
     * Option keys to skip when calling setOptions()
     *
     * @var array
     */
    protected $_skipOptions = array(
        'options',
        'config',
    );

    /**
     * Create a new tag cloud with options
     *
     * @param mixed $options
     */
    public function __construct($options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        } else if ($options instanceof Zend_Config) {
            $this->setConfig($options);
        }

        if ($this->_cloudDecorator === null) {
            $this->setCloudDecorator('cloudHtml');
        }

        if ($this->_tagDecorator === null) {
            $this->setTagsDecorator('tagHtml');
        }
    }

    /**
     * Set options from array
     *
     * @param  array $options Configuration for Zend_Tag_Cloud
     * @return Zend_Tag_Cloud
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            if (in_array(strtolower($key), $this->_skipOptions)) {
                continue;
            }

            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        return $this;
    }

    /**
     * Set options from config object
     *
     * @param  Zend_Config $config Configuration for Zend_Tag_Cloud
     * @return Zend_Tag_Cloud
     */
    public function setConfig(Zend_Config $config)
    {
        return $this->setOptions($config->toArray());
    }

    /**
     * Set the tags for the tag cloud.
     *
     * $tags should be an array containing single tags as array. Each tag
     * array should at least contain the keys 'title' and 'weight'. Optionally
     * you may supply the key 'url', to which the tag links to. Any additional
     * parameter in the array is silently ignored and can be used by custom
     * decorators.
     *
     * @param  array $tags
     * @return Zend_Tag_Cloud
     */
    public function setTags(array $tags)
    {
        // Validate and cleanup the tags
        $this->_tags = array();
        
        foreach ($tags as &$tag) {
            if ($tag instanceof Zend_Tag_Item) {
                $this->_tags[] = $tag;
            } else {
                if (!is_array($tag)) {
                    require_once 'Zend/TagCloud/Exception.php';
                    throw new Zend_Tag_Cloud_Exception('Tag is no array');
                } else if (!isset($tag['title'])) {
                    require_once 'Zend/TagCloud/Exception.php';
                    throw new Zend_Tag_Cloud_Exception('Tag contains no title');
                } else if (!isset($tag['weight'])) {
                    require_once 'Zend/TagCloud/Exception.php';
                    throw new Zend_Tag_Cloud_Exception('Tag contains no weight');
                } else if (!isset($tag['url'])) {
                    $tag['url'] = null;
                }
                
                $this->_tags[] = new Zend_Tag_Item($tag['title'], $tag['weight'], $tag['url']);
            }
        }

        return $this;
    }

    /**
     * Set the decorator for the cloud
     *
     * @param  mixed $decorator
     * @return Zend_Tag_Cloud
     */
    public function setCloudDecorator($decorator)
    {
        $options = null;

        if (is_array($decorator)) {
            if (isset($decorator['options'])) {
                $options = $decorator['options'];
            }

            if (isset($decorator['decorator'])) {
                $decorator = $decorator['decorator'];
            }
        }

        if (is_string($decorator)) {
            $classname = $this->getPluginLoader()->load($decorator);
            $decorator = new $classname($options);
        }

        if (!($decorator instanceof Zend_Tag_Cloud_Decorator_Cloud)) {
            require_once 'Zend/TagCloud/Exception.php';
            throw new Zend_Tag_Cloud_Exception('Decorator is no instance of Zend_Tag_Cloud_Decorator_Cloud');
        }

        $this->_cloudDecorator = $decorator;

        return $this;
    }

    /**
     * Get the decorator for the cloud
     *
     * @return Zend_Tag_Cloud_Decorator_Cloud
     */
    public function getCloudDecorator()
    {
        return $this->_cloudDecorator;
    }

    /**
     * Set the decorator for the tags
     *
     * @param  mixed $decorator
     * @return Zend_Tag_Cloud
     */
    public function setTagDecorator($decorator)
    {
        $options = null;

        if (is_array($decorator)) {
            if (isset($decorator['options'])) {
                $options = $decorator['options'];
            }

            if (isset($decorator['decorator'])) {
                $decorator = $decorator['decorator'];
            }
        }

        if (is_string($decorator)) {
            $classname = $this->getPluginLoader()->load($decorator);
            $decorator = new $classname($options);
        }

        if (!($decorator instanceof Zend_Tag_Cloud_Decorator_Tag)) {
            require_once 'Zend/TagCloud/Exception.php';
            throw new Zend_Tag_Cloud_Exception('Decorator is no instance of Zend_Tag_Cloud_Decorator_Tag');
        }

        $this->_tagsDecorator = $decorator;

        return $this;
    }

    /**
     * Get the decorator for the tags
     *
     * @return Zend_Tag_Cloud_Decorator_Tag
     */
    public function getTagDecorator()
    {
        return $this->_tagDecorator;
    }

    /**
     * Get the plugin loader for decorators
     *
     * @return Zend_Loader_PluginLoader
     */
    public function getPluginLoader()
    {
        if ($this->_pluginLoader === null) {
            $prefix     = 'Zend_Tag_Cloud_Decorator_';
            $pathPrefix = 'Zend/TagCloud/Decorator/';

            require_once 'Zend/Loader/PluginLoader.php';
            $this->_pluginLoader = new Zend_Loader_PluginLoader(array($prefix => $pathPrefix));
        }

        return $this->_pluginLoader;
    }

    /**
     * Render the tag cloud
     *
     * @return string
     */
    public function render()
    {
        if (count($this->_tags) === 0) {
            require_once 'Zend/TagCloud/Exception.php';
            throw new Zend_Tag_Cloud_Exception('No tags are defined');
        }
        
        // Calculate min- and max-weight
        $minWeight = null;
        $maxWeight = null;
        
        foreach ($this->_tags as $tag) {
            if ($minWeight === null && $maxWeight === null) {
                $minWeight = $tag->getWeight();
                $maxWeight = $tag->getWeight();
            } else {
                $minWeight = min($minWeight, $tag->getWeight());
                $maxWeight = max($maxWeight, $tag->getWeight());                
            }
        }
        
        // Calculate the thresholds
        $weightList = $this->_tagDecorator->getWeightList();
        $steps      = count($weightList);
        $delta      = ($maxWeight - $minWeight) / $steps;
        $thresholds = array();
        
        for ($i = 0; $i <= $steps; $i++) {
            $thresholds[$i] = 100 * log(($minWeight + $i * $delta) + 2);
        }

        // Then assign the weight values 
        foreach ($this->_tags as &$tag) {
            $threshold = 100 * log($tag[$this->_weightKey] + 2); 
            for ($i = 0; $i <= $steps; $i++) {
                if ($threshold <= $thresholds[$i]) {
                    $tag->setWeightValue($weightList[$i]);
                    break;
                }
            }
        }
        
        $tagsResult  = $this->_tagDecorator->render($this->_tags);
        $cloudResult = $this->_cloudDecorator->render($tagsResult);

        return $cloudResult;
    }

    /**
     * Render the tag cloud
     *
     * @return string
     */
    public function __toString()
    {
        try {
            $result = $this->render();
            return $result;
        } catch (Exception $e) {
            $message = "Exception caught by tagCloud: " . $e->getMessage()
                     . "\nStack Trace:\n" . $e->getTraceAsString();
            trigger_error($message, E_USER_WARNING);
            return '';
        }
    }
}
