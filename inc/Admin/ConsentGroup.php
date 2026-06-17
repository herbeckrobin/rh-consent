<?php

declare(strict_types=1);

namespace RhConsent\Admin;

use RhBlueprint\Core\Settings\GroupInterface;
use RhBlueprint\Core\Settings\SettingField;

/**
 * Settings-Gruppe für den Cookie-Consent.
 *
 * Default AUS: Robins Stacks sind oft bewusst consent-frei (Umami cookieless,
 * GlitchTip self-hosted, Fonts lokal). Wird scharf geschaltet, wenn doch ein
 * einwilligungspflichtiger Dienst dazukommt. Konform nach TDDDG §25/DSGVO:
 * Akzeptieren und Ablehnen gleichwertig, granulare Kategorien, widerrufbar.
 */
final class ConsentGroup implements GroupInterface
{
    public const GROUP_ID = 'consent';

    public const FIELD_ENABLED = 'enabled';
    public const FIELD_MESSAGE = 'message';
    public const FIELD_PRIVACY_URL = 'privacy_url';
    public const FIELD_PRIVACY_LABEL = 'privacy_label';
    public const FIELD_OFFER_STATISTICS = 'offer_statistics';
    public const FIELD_OFFER_MARKETING = 'offer_marketing';

    public function id(): string
    {
        return self::GROUP_ID;
    }

    public function tab(): string
    {
        return 'consent';
    }

    public function title(): string
    {
        return __('Cookie-Consent', 'rh-consent');
    }

    public function description(): string
    {
        return __('Einwilligungs-Banner für einwilligungspflichtige Dienste. Nur aktivieren, wenn solche Dienste eingebunden sind (cookieless Analytics braucht keinen Banner).', 'rh-consent');
    }

    public function fields(): array
    {
        return [
            new SettingField(
                id: self::FIELD_ENABLED,
                type: SettingField::TYPE_BOOLEAN,
                label: __('Consent-Banner aktivieren', 'rh-consent'),
                description: __('Zeigt den Banner und gated Skripte mit type="text/plain" data-rh-consent.', 'rh-consent'),
                default: false,
                keywords: ['consent', 'cookie', 'banner', 'dsgvo'],
            ),
            new SettingField(
                id: self::FIELD_MESSAGE,
                type: SettingField::TYPE_TEXTAREA,
                label: __('Banner-Text', 'rh-consent'),
                description: __('Kurzer Hinweis auf die Cookie-Nutzung.', 'rh-consent'),
                default: 'Wir nutzen Cookies und ähnliche Technologien. Notwendige sind immer aktiv, optionale (z.B. Statistik) nur mit deiner Einwilligung. Du kannst frei wählen und jederzeit widerrufen.',
                keywords: ['text', 'banner', 'hinweis'],
            ),
            new SettingField(
                id: self::FIELD_PRIVACY_URL,
                type: SettingField::TYPE_URL,
                label: __('Link zur Datenschutzerklärung', 'rh-consent'),
                default: '',
                keywords: ['datenschutz', 'privacy', 'link'],
            ),
            new SettingField(
                id: self::FIELD_PRIVACY_LABEL,
                type: SettingField::TYPE_TEXT,
                label: __('Beschriftung des Datenschutz-Links', 'rh-consent'),
                default: 'Datenschutzerklärung',
                keywords: ['datenschutz', 'label'],
            ),
            new SettingField(
                id: self::FIELD_OFFER_STATISTICS,
                type: SettingField::TYPE_BOOLEAN,
                label: __('Kategorie "Statistik" anbieten', 'rh-consent'),
                description: __('Für Analyse-Dienste. Skripte mit data-rh-consent="statistics" laufen erst nach Einwilligung.', 'rh-consent'),
                default: true,
                keywords: ['statistik', 'analytics', 'kategorie'],
            ),
            new SettingField(
                id: self::FIELD_OFFER_MARKETING,
                type: SettingField::TYPE_BOOLEAN,
                label: __('Kategorie "Marketing" anbieten', 'rh-consent'),
                description: __('Für Werbe-/Remarketing-Dienste. Skripte mit data-rh-consent="marketing" laufen erst nach Einwilligung.', 'rh-consent'),
                default: true,
                keywords: ['marketing', 'werbung', 'kategorie'],
            ),
        ];
    }
}
