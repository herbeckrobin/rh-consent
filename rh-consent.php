<?php

/**
 * Plugin Name:       RH Consent
 * Plugin URI:        https://github.com/herbeckrobin/rh-consent
 * Update URI:        https://github.com/herbeckrobin/rh-consent
 * Description:       Schlanker, DSGVO-freundlicher Cookie-Consent-Banner mit Script-Gating. Teil der rh-blueprint Kollektion.
 * Version:           0.1.3
 * Requires at least: 6.5
 * Requires PHP:      8.1
 * Author:            Robin Herbeck
 * Author URI:        https://robinherbeck.de
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       rh-consent
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

define('RHCONSENT_VERSION', '0.1.3');
define('RHCONSENT_PLUGIN_FILE', __FILE__);
define('RHCONSENT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('RHCONSENT_PLUGIN_URL', plugin_dir_url(__FILE__));

$rhconsent_autoload = RHCONSENT_PLUGIN_DIR . 'vendor/autoload.php';

if (! is_readable($rhconsent_autoload)) {
    add_action('admin_notices', static function (): void {
        echo '<div class="notice notice-error"><p><strong>RH Consent:</strong> Composer-Dependencies fehlen. Bitte <code>composer install</code> im Plugin-Verzeichnis ausführen.</p></div>';
    });
    return;
}

require_once $rhconsent_autoload;

RhConsent\Plugin::boot();
