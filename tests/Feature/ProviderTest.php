<?php

declare(strict_types=1);

use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Http\Request as HttpRequest;
use Laravel\Socialite\Contracts\Factory as SocialiteFactoryContract;
use Laravel\Socialite\SocialiteManager;
use Mockery as m;
use SocialiteProviders\Manager\Config;
use SocialiteProviders\Manager\Contracts\Helpers\ConfigRetrieverInterface;
use SocialiteProviders\Manager\SocialiteWasCalled;
use Worksome\Socialite\Provider;

beforeEach(fn () => $this->config = [
    'client_id' => 'test',
    'client_secret' => 'test',
    'redirect' => 'test',
]);

it('allows a custom configuration to be provided', function () {
    $provider = new Provider(
        m::mock(HttpRequest::class),
        'client id',
        'client secret',
        'redirect url'
    );

    $result = $provider->setConfig(
        new Config('key', 'secret', 'callback uri')
    );

    expect($result)->toBe($provider);
});

it('can use the Worksome provider', function () {
    $provider = m::mock(Provider::class);
    $provider->shouldReceive('setConfig');

    $socialite = m::mock(SocialiteManager::class);
    $socialite
        ->shouldReceive('buildProvider')
        ->once()
        ->withArgs([Provider::class, test()->config])
        ->andReturn($provider);
    $socialite
        ->shouldReceive('extend')
        ->once()
        ->withArgs([
            'worksome',
            m::on(function (Closure $closure) {
                expect($closure())->toBeInstanceOf(Provider::class);

                return is_callable($closure);
            }),
        ]);
    $socialite
        ->shouldReceive('driver')
        ->once()
        ->withArgs([
            'worksome',
        ])
        ->andReturn($provider);

    $config = new Config('test', 'test', 'test');

    $app = m::mock(ContainerContract::class);
    $app
        ->shouldReceive('make')
        ->with(SocialiteFactoryContract::class)
        ->andReturn($socialite);
    $app
        ->shouldReceive('make')
        ->with('SocialiteProviders.config.worksome')
        ->andReturn($config);
    $configRetriever = m::mock(ConfigRetrieverInterface::class);
    $configRetriever->shouldReceive('fromServices')
        ->with('worksome', Provider::additionalConfigKeys())
        ->andReturn($config);

    $event = new SocialiteWasCalled($app, $configRetriever);

    $event->extendSocialite('worksome', Provider::class);

    expect($socialite->driver('worksome'))->toBeInstanceOf(Provider::class);
});
