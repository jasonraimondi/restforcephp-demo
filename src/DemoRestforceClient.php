<?php
namespace EventFarm\RestforceDemo;

use EventFarm\Restforce\Oauth\AccessTokenInterface;
use EventFarm\Restforce\Oauth\SalesforceProviderInterface;
use EventFarm\Restforce\Oauth\StevenMaguireSalesforceProvider;
use EventFarm\Restforce\RestforceClient;
use EventFarm\Restforce\RestforceClientInterface;
use Silex\Application;

class DemoRestforceClient
{
    /** @var RestforceClientInterface */
    private $restforce;
    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public static function getProviderWithDefaults(): SalesforceProviderInterface
    {
        return StevenMaguireSalesforceProvider::createDefaultProvider(
            getenv('SALESFORCE_CLIENT_ID'),
            getenv('SALESFORCE_CLIENT_SECRET'),
            getenv('SALESFORCE_REDIRECT_URL'),
            'https://login.salesforce.com'
        );
    }

    public function getRestforceClient(AccessTokenInterface $accessToken): RestforceClientInterface
    {
        if (empty($this->restforce)) {
            $this->restforce = RestforceClient::withDefaults(
                $accessToken->getToken(),
                $accessToken->getRefreshToken(),
                $accessToken->getInstanceUrl(),
                $accessToken->getValues()['id'],
                getenv('SALESFORCE_CLIENT_ID'),
                getenv('SALESFORCE_CLIENT_SECRET'),
                getenv('SALESFORCE_REDIRECT_URL'),
                new TokenRefresh($this->app)
            );
        }

        return $this->restforce;
    }
}
