<?php

declare(strict_types=1);

namespace RhConsent;

use RhConsent\Admin\ConsentGroup;

/**
 * Cookie-Consent-Banner + Script-Gating.
 *
 * Konform nach TDDDG §25/DSGVO: Akzeptieren und Ablehnen gleichwertig auf der
 * ersten Ebene, granulare Kategorien (Notwendig immer an, optionale opt-in, keine
 * Vorauswahl), widerrufbar (window.rhConsentOpen). Skripte mit
 * `type="text/plain" data-rh-consent="statistics|marketing"` laufen erst nach
 * Einwilligung. Default-Zustand ohne JS: nichts Optionales läuft (sicher).
 */
final class Consent
{
    public const COOKIE = 'rh_consent';

    public function boot(): void
    {
        if (! (bool) rhbp_setting(ConsentGroup::GROUP_ID, ConsentGroup::FIELD_ENABLED, false)) {
            return;
        }

        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
        add_action('wp_footer', [$this, 'renderBanner']);
    }

    /**
     * Optionale Kategorien, die angeboten werden (Notwendig ist implizit immer an).
     *
     * @return array<int, array{key: string, label: string, description: string}>
     */
    public function optionalCategories(): array
    {
        $cats = [];
        if ((bool) rhbp_setting(ConsentGroup::GROUP_ID, ConsentGroup::FIELD_OFFER_STATISTICS, true)) {
            $cats[] = [
                'key' => 'statistics',
                'label' => __('Statistik', 'rh-consent'),
                'description' => __('Anonyme Reichweitenmessung, um die Website zu verbessern.', 'rh-consent'),
            ];
        }
        if ((bool) rhbp_setting(ConsentGroup::GROUP_ID, ConsentGroup::FIELD_OFFER_MARKETING, true)) {
            $cats[] = [
                'key' => 'marketing',
                'label' => __('Marketing', 'rh-consent'),
                'description' => __('Dienste für Werbung und Remarketing.', 'rh-consent'),
            ];
        }

        return $cats;
    }

    public function enqueue(): void
    {
        $cssAbs = RHCONSENT_PLUGIN_DIR . 'assets/consent.css';
        if (file_exists($cssAbs)) {
            wp_enqueue_style('rh-consent', RHCONSENT_PLUGIN_URL . 'assets/consent.css', [], (string) filemtime($cssAbs));
        }
        $jsAbs = RHCONSENT_PLUGIN_DIR . 'assets/consent.js';
        if (file_exists($jsAbs)) {
            wp_enqueue_script('rh-consent', RHCONSENT_PLUGIN_URL . 'assets/consent.js', [], (string) filemtime($jsAbs), true);
            wp_localize_script('rh-consent', 'rhConsentConfig', [
                'cookie' => self::COOKIE,
                'categories' => array_map(static fn (array $c): string => $c['key'], $this->optionalCategories()),
            ]);
        }
    }

    public function renderBanner(): void
    {
        $message = (string) rhbp_setting(ConsentGroup::GROUP_ID, ConsentGroup::FIELD_MESSAGE, '');
        $privacyUrl = (string) rhbp_setting(ConsentGroup::GROUP_ID, ConsentGroup::FIELD_PRIVACY_URL, '');
        $privacyLabel = (string) rhbp_setting(ConsentGroup::GROUP_ID, ConsentGroup::FIELD_PRIVACY_LABEL, 'Datenschutzerklärung');
        $categories = $this->optionalCategories();

        echo '<div class="rh-consent" id="rh-consent" role="dialog" aria-live="polite" aria-label="' . esc_attr__('Cookie-Einwilligung', 'rh-consent') . '" hidden>';
        echo '<div class="rh-consent__inner">';

        echo '<p class="rh-consent__text">' . esc_html($message);
        if ($privacyUrl !== '') {
            echo ' <a class="rh-consent__link" href="' . esc_url($privacyUrl) . '">' . esc_html($privacyLabel) . '</a>';
        }
        echo '</p>';

        // Granulare Ebene (Notwendig fix an, optionale Checkboxen ohne Vorauswahl).
        echo '<div class="rh-consent__options" hidden>';
        echo '<label class="rh-consent__option"><input type="checkbox" checked disabled> <span>' . esc_html__('Notwendig (immer aktiv)', 'rh-consent') . '</span></label>';
        foreach ($categories as $cat) {
            echo '<label class="rh-consent__option"><input type="checkbox" data-rh-consent-cat="' . esc_attr($cat['key']) . '"> <span><strong>' . esc_html($cat['label']) . '</strong> ' . esc_html($cat['description']) . '</span></label>';
        }
        echo '</div>';

        echo '<div class="rh-consent__actions">';
        echo '<button type="button" class="rh-consent__btn rh-consent__btn--ghost" data-rh-consent-action="settings">' . esc_html__('Einstellungen', 'rh-consent') . '</button>';
        echo '<button type="button" class="rh-consent__btn rh-consent__btn--save" data-rh-consent-action="save" hidden>' . esc_html__('Auswahl speichern', 'rh-consent') . '</button>';
        echo '<button type="button" class="rh-consent__btn rh-consent__btn--primary" data-rh-consent-action="reject">' . esc_html__('Alle ablehnen', 'rh-consent') . '</button>';
        echo '<button type="button" class="rh-consent__btn rh-consent__btn--primary" data-rh-consent-action="accept">' . esc_html__('Alle akzeptieren', 'rh-consent') . '</button>';
        echo '</div>';

        echo '</div></div>';
    }
}
