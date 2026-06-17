/**
 * RH Consent, Frontend.
 *
 * Zeigt den Banner bis eine Entscheidung getroffen ist, speichert sie im Cookie
 * (widerrufbar über window.rhConsentOpen()) und aktiviert eingewilligte Skripte:
 * <script type="text/plain" data-rh-consent="statistics|marketing"> wird beim
 * Zustimmen zu echtem JS. Dispatcht 'rh-consent-change' am document.
 *
 * "Alle akzeptieren" und "Alle ablehnen" sind gleichwertige Buttons (kein Dark Pattern).
 */
(function (config) {
	'use strict';

	if (!config) {
		return;
	}

	var COOKIE = config.cookie || 'rh_consent';
	var CATEGORIES = Array.isArray(config.categories) ? config.categories : [];
	var banner = document.getElementById('rh-consent');
	if (!banner) {
		return;
	}

	var options = banner.querySelector('.rh-consent__options');
	var saveBtn = banner.querySelector('[data-rh-consent-action="save"]');

	function readCookie() {
		var match = document.cookie.match(new RegExp('(?:^|; )' + COOKIE + '=([^;]*)'));
		if (!match) {
			return null;
		}
		try {
			return JSON.parse(decodeURIComponent(match[1]));
		} catch (e) {
			return null;
		}
	}

	function writeCookie(state) {
		var expires = new Date();
		expires.setTime(expires.getTime() + 180 * 24 * 60 * 60 * 1000);
		document.cookie = COOKIE + '=' + encodeURIComponent(JSON.stringify(state)) +
			'; expires=' + expires.toUTCString() + '; path=/; SameSite=Lax' +
			(location.protocol === 'https:' ? '; Secure' : '');
	}

	function activateScripts(state) {
		CATEGORIES.forEach(function (cat) {
			if (!state[cat]) {
				return;
			}
			var nodes = document.querySelectorAll('script[type="text/plain"][data-rh-consent="' + cat + '"]');
			nodes.forEach(function (old) {
				var s = document.createElement('script');
				for (var i = 0; i < old.attributes.length; i++) {
					var a = old.attributes[i];
					if (a.name === 'type' || a.name === 'data-rh-consent') {
						continue;
					}
					s.setAttribute(a.name, a.value);
				}
				s.type = 'text/javascript';
				if (!old.src) {
					s.textContent = old.textContent;
				}
				old.parentNode.replaceChild(s, old);
			});
		});
	}

	function apply(state) {
		activateScripts(state);
		document.dispatchEvent(new CustomEvent('rh-consent-change', { detail: state }));
	}

	function decide(state) {
		writeCookie(state);
		apply(state);
		hide();
	}

	function hide() {
		banner.hidden = true;
	}

	function show() {
		var current = readCookie();
		CATEGORIES.forEach(function (cat) {
			var box = banner.querySelector('[data-rh-consent-cat="' + cat + '"]');
			if (box) {
				box.checked = !!(current && current[cat]);
			}
		});
		banner.hidden = false;
	}

	function allState(value) {
		var state = { necessary: true };
		CATEGORIES.forEach(function (cat) { state[cat] = value; });
		return state;
	}

	function selectedState() {
		var state = { necessary: true };
		CATEGORIES.forEach(function (cat) {
			var box = banner.querySelector('[data-rh-consent-cat="' + cat + '"]');
			state[cat] = !!(box && box.checked);
		});
		return state;
	}

	banner.addEventListener('click', function (e) {
		var action = e.target && e.target.getAttribute('data-rh-consent-action');
		if (!action) {
			return;
		}
		if (action === 'accept') {
			decide(allState(true));
		} else if (action === 'reject') {
			decide(allState(false));
		} else if (action === 'save') {
			decide(selectedState());
		} else if (action === 'settings') {
			if (options) { options.hidden = false; }
			if (saveBtn) { saveBtn.hidden = false; }
		}
	});

	// Erneut öffnen (z.B. Footer-Link "Cookie-Einstellungen").
	window.rhConsentOpen = show;

	var saved = readCookie();
	if (saved) {
		apply(saved);
	} else {
		show();
	}
})(window.rhConsentConfig);
