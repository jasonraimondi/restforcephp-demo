<?php
namespace EventFarm\RestforceDemo;

use EventFarm\Restforce\RestforceClient;
use EventFarm\Restforce\RestforceClientInterface;
use Silex\Application;
use Stevenmaguire\OAuth2\Client\Provider\Salesforce;
use Stevenmaguire\OAuth2\Client\Token\AccessToken;

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

    public static function getProviderWithDefaults(): Salesforce
    {
        return new Salesforce([
            'clientId' => getenv('SALESFORCE_CLIENT_ID'),
            'clientSecret' => getenv('SALESFORCE_CLIENT_SECRET'),
            'redirectUri' => getenv('SALESFORCE_REDIRECT_URL'),
        ]);
    }

    public function getRestforceClient(AccessToken $accessToken): RestforceClientInterface
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
