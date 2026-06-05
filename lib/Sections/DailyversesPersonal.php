<?php

declare(strict_types=1);

namespace OCA\DailyVerses\Sections;

// for use Application::APP_ID
use OCA\DailyVerses\AppInfo\Application;

use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\Settings\IIconSection;
use OCP\IConfig;

class DailyVersesPersonal implements IIconSection {

    public function __construct(
        private IL10N $l,
        private IURLGenerator $urlGenerator,
        private IConfig $config
    ) {}

    public function getID(): string {
        return 'dailyverses-personal';
    }

    public function getName(): string {
        return $this->l->t('Daily Verses');
    }

    public function getPriority(): int {
        
        return 10;
    }

    public function getIcon(): string {
        return $this->urlGenerator->imagePath(Application::APP_ID, 'app-dark.svg');
    }
}
