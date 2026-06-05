import { t } from '@nextcloud/l10n'
import { generateUrl } from '@nextcloud/router'

document.addEventListener('DOMContentLoaded', async () => {

	const root = document.getElementById('dailyverses-settings')
	if (!root) return

	const context = root.dataset.context // "admin" of "personal"

	const settings = {
		language: root.dataset.language ?? 'nl',
		version: root.dataset.version ?? 'hsv',
		showLink: root.dataset.showlink ?? '1',
		mode: root.dataset.mode ?? 'daily',
		allowPersonal: root.dataset.allowpersonal ?? '1',
	}

	// DOM elementen
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
	// 2. INITIALISATIE
	//

	showLinkCheckbox && (showLinkCheckbox.checked = settings.showLink === '1')

	if (modeDaily && modeRandom) {
		modeDaily.checked = settings.mode === 'daily'
		modeRandom.checked = settings.mode === 'random'
	}

	allowPersonalCheckbox && (allowPersonalCheckbox.checked = settings.allowPersonal === '1')

	//
	// 3. SELECTS VULLEN
	//

	function fillLanguages() {
		if (!langSelect) return

		langSelect.innerHTML = ''

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
		if (!versionSelect) return

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

	fillLanguages()
	fillVersions(settings.language)

	langSelect?.addEventListener('change', () => {
		fillVersions(langSelect.value)
	})

	//
	// 4. OPSLAAN
	//

	saveBtn?.addEventListener('click', () => {

		const mode = modeRandom?.checked ? 'random' : 'daily'

		const payload = {
			language: langSelect?.value ?? settings.language,
			version: versionSelect?.value ?? settings.version,
			showLink: showLinkCheckbox?.checked ? '1' : '0',
			mode,
		}

		if (context === 'admin') {
			payload.allowPersonal = allowPersonalCheckbox?.checked ? '1' : '0'
		}

		const endpoint = context === 'admin'
			? '/apps/dailyverses/settings/save-admin'
			: '/apps/dailyverses/settings/save'

		fetch(generateUrl(endpoint), {
			method: 'POST',
			headers: {
				requesttoken: OC.requestToken,
				'Content-Type': 'application/json',
			},
			body: JSON.stringify(payload),
		})
			.then(() => {
				if (saveStatus) {
					saveStatus.textContent = t('dailyverses', 'Settings saved')
					setTimeout(() => {
						saveStatus.textContent = ''
					}, 2000)
				}
			})
			.catch((err) => {
				console.error('Error saving settings:', err)
				saveStatus && (saveStatus.textContent = t('dailyverses', 'Error saving settings'))
			})
	})
})
