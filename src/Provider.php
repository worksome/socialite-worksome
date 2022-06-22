<?php

namespace Worksome\Socialite;

use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider
{
    public const IDENTIFIER = 'WORKSOME';
    public const URL = 'https://auth.worksome.com';

    /** {@inheritdoc} */
    protected $scopes = [''];

    /** {@inheritdoc} */
    protected $scopeSeparator = ' ';

    /** {@inheritdoc} */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getUri('/oauth/authorize'), $state);
    }

    /** {@inheritdoc} */
    protected function getTokenUrl()
    {
        return $this->getUri('/oauth/token');
    }

    /** {@inheritdoc} */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getUri('/api/user'), [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /** {@inheritdoc} */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => Arr::get($user, 'id'),
            'name' => Arr::get($user, 'name'),
            'email' => Arr::get($user, 'email'),
        ]);
    }

    /** {@inheritdoc} */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }

    protected function getUri(string $path = ''): string
    {
        return $this->getConfig('auth_uri', static::URL).$path;
    }

    /** {@inheritdoc} */
    public static function additionalConfigKeys()
    {
        return ['auth_uri'];
    }
}
