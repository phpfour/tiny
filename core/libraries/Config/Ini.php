<?php

class Config_Ini
{
    private $_dataArray = array();
    
    protected $_nestSeparator = '.';
    protected $_sectionSeparator = ':';

    public function load($filename)
    {
        $iniArray = $this->_loadIniFile($filename);

        $this->_dataArray = array();
        foreach ($iniArray as $sectionName => $sectionData) {
            if(!is_array($sectionData)) {
                $this->_dataArray = array_merge_recursive($this->_dataArray, $this->_processKey(array(), $sectionName, $sectionData));
            } else {
                $this->_dataArray[$sectionName] = $this->_processSection($iniArray, $sectionName);
            }
        }

        return $this->_dataArray;
    }

    private function _loadIniFile($filename)
    {
        $loaded = parse_ini_file($filename, true);

        $iniArray = array();
        foreach ($loaded as $key => $data)
        {
            $pieces = explode($this->_sectionSeparator, $key);
            $thisSection = trim($pieces[0]);

            switch (count($pieces)) {

                case 1:
                    $iniArray[$thisSection] = $data;
                    break;

                case 2:
                    $extendedSection = trim($pieces[1]);
                    $iniArray[$thisSection] = array_merge(array(';extends'=>$extendedSection), $data);
                    break;

                default:
                    throw new Exception("Section '$thisSection' may not extend multiple sections in $filename");
            }
        }

        return $iniArray;
    }

    protected function _processSection($iniArray, $section, $config = array())
    {
        $thisSection = $iniArray[$section];

        foreach ($thisSection as $key => $value) {
            if (strtolower($key) == ';extends') {
                if (isset($iniArray[$value])) {
                    $config = $this->_processSection($iniArray, $value, $config);
                } else {
                    throw new Exception("Parent section '$section' cannot be found");
                }
            } else {
                $config = $this->_processKey($config, $key, $value);
            }
        }
        return $config;
    }

    protected function _processKey($config, $key, $value)
    {
        if (strpos($key, $this->_nestSeparator) !== false) {
            $pieces = explode($this->_nestSeparator, $key, 2);
            if (strlen($pieces[0]) && strlen($pieces[1])) {
                if (!isset($config[$pieces[0]])) {
                    if ($pieces[0] === '0' && !empty($config)) {
                        // convert the current values in $config into an array
                        $config = array($pieces[0] => $config);
                    } else {
                        $config[$pieces[0]] = array();
                    }
                } elseif (!is_array($config[$pieces[0]])) {
                    throw new Exception("Cannot create sub-key for '{$pieces[0]}' as key already exists");
                }
                $config[$pieces[0]] = $this->_processKey($config[$pieces[0]], $pieces[1], $value);
            } else {
                throw new Exception("Invalid key '$key'");
            }
        } else {
            $config[$key] = $value;
        }
        return $config;
    }
}