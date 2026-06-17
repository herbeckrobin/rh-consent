<?php

declare(strict_types=1);

/**
 * Globaler Helper von rh-consent. Über Composer (`autoload.files`) immer geladen,
 * damit Theme/Module server-seitig prüfen können, ob eine Kategorie eingewilligt ist.
 */

if (! function_exists('rh_consent_allows')) {
    /**
     * Hat der Besucher die Kategorie eingewilligt? "necessary" ist immer true.
     * Liest den Consent-Cookie (clientseitig gesetzt). Ohne Cookie: nur necessary.
     */
    function rh_consent_allows(string $category): bool
    {
        if ($category === '' || $category === 'necessary') {
            return true;
        }

        $raw = isset($_COOKIE['rh_consent']) ? wp_unslash($_COOKIE['rh_consent']) : '';
        if (! is_string($raw) || $raw === '') {
            return false;
        }

        $data = json_decode($raw, true);

        return is_array($data) && ! empty($data[$category]);
    }
}
