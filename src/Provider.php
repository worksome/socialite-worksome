<?php

declare(strict_types=1);

namespace Worksome\Socialite;

use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider
{
    public const string IDENTIFIER = 'WORKSOME';

    public const string URL = 'https://auth.worksome.com';

    /** {@inheritdoc} */
    protected $scopes = [''];

    /** {@inheritdoc} */
    protected $scopeSeparator = ' ';

    /** {@inheritdoc} */
    protected function getAuthUrl($state): string
    {
        $redirectUri = $this->getConfig('auth_redirect_uri', $this->getUri());

        // @phpstan-ignore encapsedStringPart.nonString
        return $this->buildAuthUrlFromBase("{$redirectUri}/oauth/authorize", $state);
    }

    /** {@inheritdoc} */
    protected function getTokenUrl(): string
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

        // @phpstan-ignore return.type
        return json_decode((string) $response->getBody(), true);
    }

    /** {@inheritdoc} */
    protected function mapUserToObject(array $user): User
    {
        return (new User)->setRaw($user)->map([
            'id' => Arr::get($user, 'id'),
            'name' => Arr::get($user, 'name'),
            'email' => Arr::get($user, 'email'),
            'avatar' => Arr::get($user, 'avatar_path'),
        ]);
    }

    protected function getUri(string $path = ''): string
    {
        $url = $this->getConfig('auth_uri', static::URL);

        assert(is_string($url));

        return "{$url}{$path}";
    }

    /** {@inheritdoc} */
    public static function additionalConfigKeys(): array
    {
        return ['auth_uri', 'auth_redirect_uri'];
    }
}
