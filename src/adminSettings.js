import { generateUrl } from '@nextcloud/router'

document.addEventListener('DOMContentLoaded', () => {

	// Root element (admin of personal)
	const root = document.getElementById('catgifsdashboard-settings')

	// Als er geen settings-container is → niets doen
	if (!root) {
		return
	}

	// Data-attributes uitlezen (CSP-safe)
	const settings = {
		language: root.dataset.language ?? 'nl',
		version: root.dataset.version ?? 'hsv',
		showLink: root.dataset.showlink ?? '1',
		mode: root.dataset.mode ?? 'daily',
		allowPersonal: root.dataset.allowpersonal ?? '1',
	}

	// DOM-elementen ophalen (sommige bestaan niet in personal disabled view)
	const langSelect = document.getElementById('languageSelect')
	const versionSelect = document.getElementById('versionSelect')
	const showLinkCheckbox = document.getElementById('showLink')
	const modeDaily = document.getElementById('modeDaily')
	const modeRandom = document.getElementById('modeRandom')
	const allowPersonalCheckbox = document.getElementById('allowPersonal')

	const saveBtn = document.getElementById('saveSettings')
	const saveStatus = document.getElementById('saveStatus')

	//
	// 1. OPTIES LADEN UIT PHP
	//

	let languageOptions = {}
	let languageLabels = {}
	let versionLabels = {}

	try {
		const response = await fetch(generateUrl('/apps/dailyverses/settings/options'))
		const data = await response.json()

		languageOptions = data.languageOptions
		languageLabels = data.languageLabels
		versionLabels = data.versionLabels

	} catch (e) {
		console.error('Error loading DailyVerses options:', e)
		return
	}

	//
	// INITIALISATIE
	//

	if (showLinkCheckbox) {
		showLinkCheckbox.checked = settings.showLink === '1'
	}

	if (modeDaily && modeRandom) {
		modeDaily.checked = settings.mode === 'daily'
		modeRandom.checked = settings.mode === 'random'
	}

	if (allowPersonalCheckbox) {
		allowPersonalCheckbox.checked = settings.allowPersonal === '1'
	}

	//
	// SELECTS VULLEN
	//

	if (langSelect) {
		Object.keys(languageOptions).forEach((lang) => {
			const opt = document.createElement('option')
			const label = languageLabels[lang]

			opt.value = lang
			opt.textContent = `${label.native} (${label.english})`

			if (settings.language === lang) {
				opt.selected = true
			}

			langSelect.append(opt)
		})
	}

	function fillVersions(lang) {
		if (!versionSelect) {
			return
		}

		versionSelect.innerHTML = ''

		languageOptions[lang].forEach((v) => {
			const opt = document.createElement('option')
			opt.value = v.url
			opt.textContent = versionLabels[v.url] ?? v.version

			if (settings.version === v.url) {
				opt.selected = true
			}

			versionSelect.append(opt)
		})
	}

	if (versionSelect) {
		fillVersions(settings.language)
	}

	if (langSelect) {
		langSelect.addEventListener('change', () => {
			fillVersions(langSelect.value)
		})
	}

	//
	// OPSLAAN (alleen in admin view)
	//

	if (saveBtn) {
		saveBtn.addEventListener('click', () => {

			const mode = (modeRandom && modeRandom.checked) ? 'random' : 'daily'

			fetch(generateUrl('/apps/catgifsdashboard/settings/save-admin'), {
				method: 'POST',
				headers: {
					requesttoken: OC.requestToken,
					'Content-Type': 'application/json',
				},
				body: JSON.stringify({
					language: langSelect ? langSelect.value : settings.language,
					version: versionSelect ? versionSelect.value : settings.version,
					showLink: showLinkCheckbox && showLinkCheckbox.checked ? '1' : '0',
					mode,
					allowPersonal: allowPersonalCheckbox && allowPersonalCheckbox.checked ? '1' : '0',
				}),
			})
				.then(() => {
					if (saveStatus) {
						saveStatus.textContent = 'Settings saved'
						setTimeout(() => {
							saveStatus.textContent = ''
						}, 2000)
					}
				})
				.catch((err) => {
					console.error('Error saving settings:', err)
					if (saveStatus) {
						saveStatus.textContent = 'Error saving settings'
					}
				})
		})
	}
})
