=== RH Consent ===
Contributors: robinherbeck
Tags: consent, cookie, dsgvo, gdpr, privacy
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 0.1.5
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lean, GDPR/TDDDG-compliant cookie consent banner with script gating. Equal accept/reject buttons, granular categories, revocable.

== Description ==

RH Consent shows a consent banner only when you actually need it (off by default, since cookieless analytics needs no banner). Compliant with TDDDG section 25 / GDPR: accept and reject are equally prominent on the first layer (no dark patterns), optional categories are opt-in with no pre-selection, and the choice is revocable via window.rhConsentOpen().

Gate scripts by writing them as <script type="text/plain" data-rh-consent="statistics"> ... </script>; they only become active once that category is granted. Server-side, use rh_consent_allows('statistics'). A 'rh-consent-change' event fires on the document.

Part of the rh-blueprint collection. Settings live under RH Blueprint > Consent.

== Changelog ==

= 0.1.5 =
* Update checks: use a GitHub token from RH_GITHUB_TOKEN (environment variable or wp-config constant) when one is set, which lifts the API limit from 60 to 5,000 requests per hour. Without a token nothing changes.
* Update checks: after a GitHub rate-limit response all rh modules on the site pause their checks until GitHub resets the limit, and the last known update is kept. Bundles core 2.7.1.

= 0.1.4 =
* Fix: bundle core 2.6.1. The 2.6.0 release bundled an incomplete core.

= 0.1.3 =
* Internal: shared building blocks from core 2.6.0. The update check no longer loads on regular front-end requests.

= 0.1.3 =
* Internal: shared building blocks from core 2.6.0. The update check no longer loads on regular front-end requests.

= 0.1.0 =
* Initial release: GDPR/TDDDG-compliant banner, granular categories, script gating, PHP + JS APIs.
