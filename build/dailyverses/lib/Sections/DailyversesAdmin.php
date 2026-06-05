<?php

declare(strict_types=1);

namespace OCA\DailyVerses\Sections;

// for use Application::APP_ID
use OCA\DailyVerses\AppInfo\Application;

use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\Settings\IIconSection;

class DailyversesAdmin implements IIconSection {
    private IL10N $l;
    private IURLGenerator $urlGenerator;

    public function __construct(IL10N $l, IURLGenerator $urlGenerator) {
        $this->l = $l;
        $this->urlGenerator = $urlGenerator;
    }

    public function getIcon(): string {
        return $this->urlGenerator->imagePath(Application::APP_ID, 'app-dark.svg');
    }

    public function getID(): string {
        return 'dailyverses-admin';
    }

    public function getName(): string {
        return $this->l->t('Daily Verses');
    }

    public function getPriority(): int {
        return 0;
    }
}
