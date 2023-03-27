# Worksome Socialite Adapter

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-github-actions]][link-github-actions]

A Worksome provider for Laravel Socialite

## Install

Via Composer

```shell
composer require worksome/socialite
```

Please see the [Base Installation Guide](https://socialiteproviders.com/usage), then follow the provider specific instructions below.

### Add configuration to `config/services.php`

```php
'worksome' => [
    'client_id' => env('WORKSOME_CLIENT_ID'),
    'client_secret' => env('WORKSOME_CLIENT_SECRET'),
    'redirect' => env('WORKSOME_REDIRECT_URI'),

    // Optional
    'auth_uri' => env('WORKSOME_AUTH_URI', 'https://auth.worksome.com'),
    'auth_redirect_uri' => env('WORKSOME_AUTH_REDIRECT_URI', 'https://auth.worksome.test'),
],
```

### Add provider event listener

Configure the package's listener to listen for `SocialiteWasCalled` events.

Add the event to your `listen[]` array in `app/Providers/EventServiceProvider`. See the [Base Installation Guide](https://socialiteproviders.com/usage) for detailed instructions.

```php
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // ... other providers
        \Worksome\Socialite\WorksomeExtendSocialite::class,
    ],
];
```

## Usage

You should now be able to use the provider like you would regularly use Socialite (assuming you have the facade installed):

```php
return Socialite::driver('worksome')->redirect();
```

### Returned User fields

- `id`: The id of the authenticated user
- `name`: The name of the authenticated user
- `email`: The email address of the authenticated user

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Owen Voke](https://github.com/owenvoke)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/worksome/socialite.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-github-actions]: https://img.shields.io/github/actions/workflow/status/worksome/socialite-worksome/tests.yml?branch=main&style=flat-square

[link-packagist]: https://packagist.org/packages/worksome/socialite
[link-github-actions]: https://github.com/worksome/socialite/actions
