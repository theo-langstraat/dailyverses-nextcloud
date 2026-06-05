<?php

declare(strict_types=1);

namespace OCA\DailyVerses\Dashboard;

use OCP\AppFramework\Services\IInitialState;
use OCP\Dashboard\IAPIWidget;
use OCP\IL10N;

use OCA\DailyVerses\AppInfo\Application;
use OCP\Util;

class DailyVersesWidget implements IAPIWidget {

	private $l10n;
	private $initialStateService;
	private $userId;

	public function __construct(IL10N $l10n,
								IInitialState $initialStateService,
								?string $userId) {
		$this->l10n = $l10n;
		$this->initialStateService = $initialStateService;
		$this->userId = $userId;
	}

	public function getId(): string {
		return 'dailyverses-widget';
	}

	public function getTitle(): string {
		return $this->l10n->t('Daily Verses');
	}

	public function getOrder(): int {
		return 10;
	}

	public function getIconClass(): string {
		return 'icon-dailyverses';
	}

	public function getUrl(): ?string {
		return null;
	}

	public function load(): void {
		if ($this->userId !== null) {
			$items = $this->getItems($this->userId);
			$this->initialStateService->provideInitialState('dashboard-widget-items', $items);
		}

		Util::addScript(Application::APP_ID, Application::APP_ID . '-dashboardDailyVerses');
		Util::addStyle(Application::APP_ID, 'dashboard');
	}
}