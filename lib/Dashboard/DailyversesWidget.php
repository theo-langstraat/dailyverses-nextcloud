<?php

declare(strict_types=1);

namespace OCA\DailyVerses\Dashboard;

// for use Application::APP_ID
use OCA\DailyVerses\AppInfo\Application;

use OCP\AppFramework\Services\IInitialState;
use OCP\Dashboard\IAPIWidget;
use OCP\IL10N;
use OCP\Util;

class DailyversesWidget implements IAPIWidget {

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
		return Application::APP_ID . '-widget';
	}

	public function getTitle(): string {
		return $this->l10n->t('Daily Verses');
	}

	public function getOrder(): int {
		return 10;
	}

	public function getIconClass(): string {
		return 'icon-' . Application::APP_ID;
	}

	public function getUrl(): ?string {
		return null;
	}

	public function load(): void {
		Util::addScript(Application::APP_ID, Application::APP_ID . '-dashboardDailyverses');
		Util::addStyle(Application::APP_ID, 'dashboard');
	}

	public function getItems(string $userId, ?string $since = null, int $limit = 7): array {
		return [];
	}
}