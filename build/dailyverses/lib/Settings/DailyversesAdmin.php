<?php

declare(strict_types=1);

namespace OCA\DailyVerses\Settings;

// for use Application::APP_ID
use OCA\DailyVerses\AppInfo\Application;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\Settings\ISettings;

class DailyVersesAdmin implements ISettings {

    public function __construct(
        private IConfig $config
    ) {}

    public function getForm(): TemplateResponse {

        // Globale admin-instellingen ophalen
        $language      = $this->config->getAppValue(Application::APP_ID, 'language', 'nl');
        $version       = $this->config->getAppValue(Application::APP_ID, 'version', 'nbv');
        $showLink      = $this->config->getAppValue(Application::APP_ID, 'showLink', '1');
        $mode          = $this->config->getAppValue(Application::APP_ID, 'mode', 'daily');
        $allowPersonal = $this->config->getAppValue(Application::APP_ID, 'allowPersonal', '1');

        return new TemplateResponse(Application::APP_ID, 'adminSettings', [
            'language'      => $language,
            'version'       => $version,
            'showLink'      => $showLink,
            'mode'          => $mode,
            'allowPersonal' => $allowPersonal,
        ]);
    }

    public function getSection(): string {
        return 'dailyverses-admin';
    }

    public function getPriority(): int {
        return 0;
    }
}
