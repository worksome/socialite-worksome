<?php

declare(strict_types=1);

namespace Worksome\Socialite;

use SocialiteProviders\Manager\SocialiteWasCalled;

class WorksomeExtendSocialite
{
    public function __invoke(SocialiteWasCalled $socialiteWasCalled): void
    {
        $socialiteWasCalled->extendSocialite('worksome', Provider::class);
    }
}
