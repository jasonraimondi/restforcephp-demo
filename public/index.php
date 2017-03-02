<?php

require_once __DIR__ . '/../bootstrap/app.php';

use EventFarm\Restforce\RestforceClientInterface;
use EventFarm\RestforceDemo\DemoRestforceClient;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Component\HttpFoundation\Request;


$demoRestforceClient = new DemoRestforceClient($app);
$provider = DemoRestforceClient::getProviderWithDefaults();
$reflection = new ReflectionClass(RestforceClientInterface::class);


$app->get('/', function() use ($app, $blogGenerator, $reflection) {
    $accessToken = $app['cache']->fetch('accessToken');
    if ($accessToken) {
        return $blogGenerator->renderView('pages/home', [
            'reflection' => $reflection
        ]);
    } else {
        return $app->redirect('/redirectToSalesforce');
    }
});

$app->get('/redirectToSalesforce', function() use ($app, $provider) {
    var_dump($provider->getAuthorizationUrl());
    header('Location: ' . $provider->getAuthorizationUrl());
    exit;
});

$app->get('/callback', function(Request $request) use ($app, $provider) {
    try {
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $request->get('code')
        ]);
        $app['cache']->store('accessToken', $accessToken);
    } catch (IdentityProviderException $e) {
        return $e->getMessage();
    }
    return $app->redirect('/');
});

foreach ($reflection->getMethods() as $method) {
    $app->get('/' . $method->getName(), function(Request $request) use ($app, $blogGenerator, $demoRestforceClient, $method) {
        $accessToken = $app['cache']->fetch('accessToken');
        if ($accessToken) {
            $restforceClient = $demoRestforceClient->getRestforceClient($accessToken);
            $data = call_user_func_array([$restforceClient, $method->getName()], $request->query->all());
            return $app->json($data);
        } else {
            return $app->redirect('/redirectToSalesforce');
        }
    });
}

$app->run();
