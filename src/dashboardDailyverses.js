import { t } from '@nextcloud/l10n'

async function loadSettings() {
	try {
		const response = await fetch(OC.generateUrl('/apps/dailyverses/settings/load'))
		if (!response.ok) {
			throw new Error('Settings endpoint returned an error')
		}
		return await response.json()
	} catch (err) {
		console.error('Failed to load settings:', err)
		return {
			admin: {
				language: 'nl',
				version: 'hsv',
				showLink: '1',
				mode: 'daily',
				allowPersonal: '0',
			},
			user: {},
			versionLabels: {},
		}
	}
}

async function loadOptions() {
	try {
		const response = await fetch(OC.generateUrl('/apps/dailyverses/settings/options'))
		if (!response.ok) {
			throw new Error('Options endpoint returned an error')
		}
		return await response.json()
	} catch (err) {
		console.error('Failed to load options:', err)
		return {
			versionLabels: {},
			languageLabels: {},
			languageOptions: {},
		}
	}
}

async function loadVerse(settings) {
	const url = OC.generateUrl('/apps/dailyverses/api/verse')
		+ `?language=${settings.language}&version=${settings.version}&mode=${settings.mode}`

	try {
		const response = await fetch(url)
		if (!response.ok) {
			throw new Error('Verse endpoint returned an error')
		}

		const json = await response.json()
		return json.data

	} catch (err) {
		console.error('Failed to load verse:', err)
		return {
			text: t('dailyverses', 'Kan DailyVerses.net niet bereiken.'),
			reference: '',
			version: settings.version,
			link: '',
			linkText: '',
		}
	}
}

async function initWidget(el) {
	const settingsData = await loadSettings()
	const optionsData = await loadOptions()

	const settings = settingsData.admin.allowPersonal === '1'
		? settingsData.user
		: settingsData.admin

	const versionLabels = optionsData.versionLabels ?? {}

	const verse = await loadVerse(settings)

	renderWidget(el, settings, verse, versionLabels)
}

function renderWidget(el, settings, verse, versionLabels) {

	const wrapper = document.createElement('div')
	wrapper.id = 'dailyVersesWrapper'
	wrapper.setAttribute('dir', 'auto')
	el.append(wrapper)

	const versionName = versionLabels[settings.version] ?? settings.version
	let html

	const showLink = versionLabels[settings.showLink] ?? settings.showLink

	if (showLink === '1') {
		html = `
			<div class="dv-body">
				<div class="dv-verse-text">${verse.text}</div>
				<div class="dv-verse-subtitle">
					<a href="${verse.link}" target="_blank">${verse.linkText}</a>
					&nbsp;–&nbsp;${versionName}
				</div>
			</div>
		`
	} else {
		html = `
			<div class="dv-body">
				<div class="dv-verse-text">${verse.text}</div>
				<div class="dv-verse-subtitle">
					${verse.linkText}
					&nbsp;–&nbsp;${versionName}
				</div>
			</div>
		`
	}
	wrapper.innerHTML = html
}

document.addEventListener('DOMContentLoaded', () => {
	OCA.Dashboard.register('dailyverses-widget', (el) => {
		initWidget(el)
	})
})
