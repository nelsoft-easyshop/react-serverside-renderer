<?php

namespace React\Bundle\ServerSideRendererBundle\Tests\Service;

use React\Bundle\ServerSideRendererBundle\Service\Renderer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Unit Tests for ReactJS Server Side Renderer
 */
class RendererTest extends WebTestCase
{

    protected $client = null;
    protected $container = null;

    /**
     * Setup before test runs
     */
    public function setUp()
    {
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
    }

    /**
     * Test if the react mark-up is generated
     * Note that this does not test if the nodejs server is running, it just tests if a well formed
     * string markup is returned
     */
    public function testGenerateReactMarkup()
    {
        $reactServerUrl = "http://localhost:3000";
        $isReactServerOnline = true;
        try {
            $buzzClient = $this->container->get('buzz');
            $response = $buzzClient->get($reactServerUrl);
        }
        catch (\Exception $e) {
            $isReactServerOnline = false;
        }

        if ($isReactServerOnline) {
            $renderer = new Renderer();
            $renderer->setConfig($reactServerUrl);
         
            $reactMarkup = $renderer->generateReactMarkup('./reactjs/src/hello', [
                               'name' => 'Derp',
                            ]);

            $this->assertTrue($reactMarkup !== false);
        }
    }
}
