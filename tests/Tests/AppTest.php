<?php

namespace microCMS\Tests;

require_once __DIR__.'/../../vendor/autoload.php';

use Silex\WebTestCase;

class AppTest extends WebTestCase
{
    /**
     * Basic, application-wide functional test inspired by Symfony best practices.
     * Simply checks that all application URLs load successfully.
     * During test execution, this method is called for each URL returned by the provideUrls method.
     *
     * @dataProvider provideUrls
     */
    public function testPageIsSuccessful($url)
    {
        $client = $this->createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * {@inheritDoc}
     */
    public function createApplication()
    {
        $app = new \Silex\Application();

        require __DIR__.'/../../app/config/dev.php';
        require __DIR__.'/../../app/app.php';
        require __DIR__.'/../../app/routes.php';

        // Generate raw exceptions instead of HTML pages if errors occur
        unset($app['exception_handler']);
        // Simulate sessions for testing
        $app['session.test'] = true;
        // Enable anonymous access to admin zone
        $app['security.access_rules'] = array();

        return $app;
    }

    /**
     * Provides all valid application URLs.
     *
     * @return array The list of all valid application URLs.
     */
    public function provideUrls()
    {
        return array(
            array('/'),
            array('/article/1'),
            array('/article/1/comment/1'),
            array('/article/1/comment/1/moderation'),
            array('/article/1/comment/1/accepter'),
            array('/login'),
            array('/admin'),
            array('/admin/article/ajouter'),
            array('/admin/article/1/editer'),
            array('/admin/article/1/supprimer'),
            array('/admin/comment/{id}/editer'),
            array('/admin/comment/{id}/supprimer'),
            array('/admin/comment/1/editer'),
            array('/admin/user/ajouter'),
            array('/admin/user/1/supprimer'),
            array('/admin/user/1/edit'),
        );
    }
}