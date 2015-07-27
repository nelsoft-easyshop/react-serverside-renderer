<?php

namespace React\Bundle\ServerSideRendererBundle\Tests\Twig;

use React\Bundle\ServerSideRendererBundle\Service\Renderer;
use React\Bundle\ServerSideRendererBundle\Twig\ReactTwigExtension;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReactTwigExtensionTest extends WebTestCase
{

    protected $client = null;
    protected $container = null;
    protected $isReactServerOnline = true;
    protected $reactServerUrl = "http://localhost:3000";

    /**
     * Setup before test runs
     */
    public function setUp()
    {
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        try {
            $buzzClient = $this->container->get('buzz');
            $response = $buzzClient->get($this->reactServerUrl);
        }
        catch (\Exception $e) {
            $this->isReactServerOnline = false;
        }
    }

    /**
     * Tests ReactWithProps Function
     */
    public function testReactWithProps()
    {
        $renderer = new Renderer();
        $renderer->setConfig($this->reactServerUrl);

        $extension = new ReactTwigExtension($renderer);
        $markup = $extension->reactWithProps(json_encode(['name' => 'Kylo Ren']), './reactjs/src/hello');

        $dom = new \DOMDocument();
        $dom->loadHTML($markup);

        $reactDiv = $dom->getElementsByTagName('div');
        $this->assertTrue($reactDiv->length === 1);

        $reactAttributes = $reactDiv->item(0)->attributes;
        $this->assertNotNull($reactAttributes->getNamedItem('react-component-name'));
        $this->assertNotNull($reactAttributes->getNamedItem('react-props'));
    }

    /**
     * Tests ReactWithoutProps Function
     */
    public function testReactWithoutProps()
    {
        if ($this->isReactServerOnline) {
            $renderer = new Renderer();
            $renderer->setConfig($this->reactServerUrl);

            $extension = new ReactTwigExtension($renderer);
            $markup = $extension->reactWithoutProps('./reactjs/src/countdown');

            $dom = new \DOMDocument();
            $dom->loadHTML($markup);

            $reactDiv = $dom->getElementsByTagName('div');

            // other div is generate inside `react-component` div
            // by react server so $reactDiv->length must be 2
            $this->assertTrue($reactDiv->length === 2);

            $reactAttributes = $reactDiv->item(0)->attributes;
            $this->assertNotNull($reactAttributes->getNamedItem('react-component-name'));
        }
    }
}
