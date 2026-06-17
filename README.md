# RH Consent

Schlanker, DSGVO/TDDDG-konformer Cookie-Consent mit Script-Gating. Teil der rh-blueprint Kollektion.

Zeigt einen Banner nur, wenn er wirklich gebraucht wird (Default aus). Konform nach TDDDG §25 und DSGVO: Akzeptieren und Ablehnen gleichwertig auf der ersten Ebene, granulare Kategorien ohne Vorauswahl, widerrufbar.

## Was es macht

- **Konformer Banner**: „Alle akzeptieren" und „Alle ablehnen" gleich prominent (kein Dark Pattern), plus Einstellungen-Ebene für einzelne Kategorien.
- **Granulare Kategorien**: Notwendig immer an, Statistik und Marketing opt-in, keine vorangekreuzten Boxen.
- **Script-Gating**: Skripte laufen erst nach Einwilligung.
- **Widerrufbar**: `window.rhConsentOpen()` öffnet den Banner erneut (z.B. über einen Footer-Link „Cookie-Einstellungen").

## Einstellungen

Im Backend unter **RH Blueprint → Consent**: Banner aktivieren, Banner-Text, Link zur Datenschutzerklärung, sowie an/aus für die Kategorien Statistik und Marketing.

## Für Entwickler

Skript einwilligungspflichtig machen:

```html
<script type="text/plain" data-rh-consent="statistics">
  /* läuft erst nach Einwilligung in "Statistik" */
</script>
```

- Server-seitig prüfen: `rh_consent_allows( 'statistics' )` (bool, `necessary` immer true).
- Event am document: `rh-consent-change` (detail = Kategorie-Zustand).
- Cookie `rh_consent` (JSON, 180 Tage, SameSite=Lax).

## Hinweis

Viele rh-blueprint-Stacks sind bewusst consent-frei (Umami cookieless, GlitchTip self-hosted, Fonts lokal). Dann braucht es diesen Banner nicht, das Modul ist die Vorhaltung, falls ein einwilligungspflichtiger Dienst dazukommt.

## Installation

ZIP hochladen und aktivieren. Der geteilte Core ist gebündelt.

## Voraussetzungen

WordPress 6.5+, PHP 8.1+.
