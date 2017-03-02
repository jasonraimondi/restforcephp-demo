<?php
namespace EventFarm\RestforceDemo;

use EventFarm\Restforce\Oauth\AccessToken;
use EventFarm\Restforce\TokenRefreshInterface;
use Silex\Application;

class TokenRefresh implements TokenRefreshInterface
{
    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function tokenRefreshCallback(AccessToken $accessToken)
    {
        $this->app['cache']->store('accessToken', $accessToken);
    }
}