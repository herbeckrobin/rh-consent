<?php

declare(strict_types=1);

namespace RhConsent;

use RhBlueprint\Core\Core;
use RhBlueprint\Core\Settings\SettingsPage;
use RhConsent\Admin\ConsentGroup;

/**
 * Bootstrap von rh-consent. Hängt am Core-Hook `rh-blueprint/core/booted`. Braucht nur den Core.
 */
final class Plugin
{
    public static function boot(): void
    {
        if (class_exists(UpdateChecker::class)) {
            (new UpdateChecker())->boot();
        }

        add_action('rh-blueprint/core/booted', [self::class, 'onCoreBooted']);
    }

    public static function onCoreBooted(Core $core): void
    {
        $core->settings()->registerTab('consent', __('Consent', 'rh-consent'), 75);
        $core->settings()->registerGroup(new ConsentGroup());

        (new Consent())->boot();

        add_filter('rh-blueprint/dashboard/quick_links', static function (array $links): array {
            $links[] = [
                'label' => __('Consent', 'rh-consent'),
                'url' => admin_url('admin.php?page=' . SettingsPage::MENU_SLUG . '&tab=consent'),
                'icon' => 'privacy',
            ];
            return $links;
        });
    }
}
