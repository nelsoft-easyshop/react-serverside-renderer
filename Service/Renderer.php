<?php

namespace React\Bundle\ServerSideRendererBundle\Service;

class Renderer
{
    /**
     * The nodejs render server
     *
     * @var string
     */
    private $renderServer;

    /**
     * Is react rendered enabled
     *
     * @var boolean
     */
    private $isEnabled;

    /**
     * Set the Renderer class connfigurations
     *
     * @param string $renderServer
     * @param boolean $isEnabled
     */
    public function setConfig($renderServer, $isEnabled = true)
    {
        $this->renderServer = $renderServer;
        $this->isEnabled = $isEnabled;
    }

    /**
     * Generate reactJS Markup
     * 
     * @param string $module
     * @param mixed $props
     * @return string
     */
    public function generateReactMarkup($module, $props) 
    {
        $propertiesJson = json_encode($props);
        $serverMarkup = null;
        if($this->isEnabled){
            $serverMarkup = @file_get_contents(
                $this->renderServer .
                '?module=' .
                urlencode($module) .
                '&props=' .
                urlencode($propertiesJson)
            );     
        }
        
        return $serverMarkup;
    }
}
