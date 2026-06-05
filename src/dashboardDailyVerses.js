import { versionLabels } from './language-options.js'

async function loadSettings() {
	const response = await fetch(OC.generateUrl('/apps/dailyverses/settings/load'))
	return await response.json()
}

async function initWidget(el) {
	const settings = await loadSettings()
	renderWidget(el, settings)
}

// https://dailyverses.net/get/random.js?language=nbv
// https://dailyverses.net/get/verse?language=niv&date=2025-9-30

function renderWidget(el, settings) {

	// --- WRAPPER ---
	const wrapper = document.createElement('div')
	wrapper.id = 'dailyVersesWrapper'
	wrapper.style.marginTop = '1rem'
	el.append(wrapper)

	function getDailyVersesUrl() {
		if (settings.mode === 'random') {
			return `https://dailyverses.net/get/random.js?language=${settings.version}`
		}

		return `https://dailyverses.net/get/verse.js?language=${settings.version}`
	}

	function loadDailyVerse() {
		wrapper.innerHTML = ''

		const oldScript = el.querySelector('script[data-dailyverses]')
		if (oldScript) oldScript.remove()

		const observer = new MutationObserver(() => {
			const link = wrapper.querySelector('.dailyVerses.bibleVerse a')
			if (link) {
				const parent = link.parentElement

				const verseRef = link.textContent
				const versionName = versionLabels[settings.version] ?? settings.version

				// Maak subtitel container
				const subtitle = document.createElement('div')
				subtitle.className = 'dv-verse-subtitle'

				if (settings.showLink === '1') {
					// Link behouden → maar tekst opschonen
					link.textContent = verseRef

					// Voeg link + versie toe in één regel
					subtitle.appendChild(link)
					subtitle.insertAdjacentText('beforeend', ` – ${versionName}`)
				} else {
					// Geen link → gewone tekst
					subtitle.textContent = `${verseRef} – ${versionName}`
				}

				// Vervang de originele inhoud
				parent.innerHTML = ''
				parent.appendChild(subtitle)

				observer.disconnect()
			}
		})

		observer.observe(wrapper, { childList: true, subtree: true })

		const script = document.createElement('script')
		script.src = getDailyVersesUrl()
		script.async = true
		script.defer = true
		script.dataset.dailyverses = '1'
		el.append(script)
	}

	loadDailyVerse()
}

document.addEventListener('DOMContentLoaded', () => {
	OCA.Dashboard.register('dailyverses-widget', (el) => {
		initWidget(el)
	})
})
