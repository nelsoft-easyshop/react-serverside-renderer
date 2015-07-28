<?php

namespace React\Bundle\ServerSideRendererBundle\Twig;

use React\Bundle\ServerSideRendererBundle\Service\Renderer;

class ReactTwigExtension extends \Twig_Extension
{
    /**
     * Node.js renderer service
     * @var React\Bundle\ServerSideRendererBundle\Service\Renderer
     */
    private $renderer;

    /**
     * Directory of React Components
     * @var string
     */
    private $sourcePath;

    /**
     * Extension Constructor
     * @param Renderer $renderer
     */
    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Sets directory of React Components
     */
    public function setSourcePath($sourcePath)
    {
        $this->sourcePath = $sourcePath;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('react', array($this, 'reactWithProps'), ['is_safe' => ['html']]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            'react' => new \Twig_Function_Method($this, 'reactWithoutProps', ['is_safe' => ['html']]),
        ];
    }

    /**
     * Renders a React component with props
     * @param  mixed $props
     * @param  string $component
     * @return string
     */
    public function reactWithProps($props, $component)
    {
        return '<div react-component-name="'. $component .'" react-props="'. htmlspecialchars(json_encode($props)) .'">'. 
            $this->renderMarkup($component, $props)
        .'</div>';
    }

    /**
     * Renders a React component without props
     * @param  string $component
     * @return string
     */
    public function reactWithoutProps($component)
    {
        return '<div react-component-name="'. $component .'">'. 
            $this->renderMarkup($component)
        .'</div>';
    }

    /**
     * Method that calls the Node.js server for component rendering
     * @param  string $component
     * @param  mixed $props
     * @return string
     */
    private function renderMarkup($component, $props = [])
    {
        return $this->renderer->generateReactMarkup($this->sourcePath.$component, $props);
    }

    /**
     * {@inheritdoc}
     */
    public function getName() 
    {
        return 'React Twig Extensions';
    }
}
