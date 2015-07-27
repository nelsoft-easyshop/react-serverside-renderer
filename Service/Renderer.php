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
     * Set the Renderer class connfigurations
     *
     * @param string $renderServer
     */
    public function setConfig($renderServer)
    {
        $this->renderServer = $renderServer;
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

        $serverMarkup = @file_get_contents(
            $this->renderServer .
            '?module=' .
            urlencode($module) .
            '&props=' .
            urlencode($propertiesJson)
        );     

        return $serverMarkup;
    }
}
