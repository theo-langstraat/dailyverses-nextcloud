<?php

declare(strict_types=1);

namespace OCA\DailyVerses\Settings;

// for use Application::APP_ID
use OCA\DailyVerses\AppInfo\Application;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\Settings\ISettings;
use OCP\IUserSession;

class DailyversesPersonal implements ISettings {

    private IConfig $config;
    private IUserSession $userSession;

    public function __construct(IConfig $config, IUserSession $userSession) {
        $this->config = $config;
        $this->userSession = $userSession;
    }

    public function getForm(): TemplateResponse {
        $user = $this->userSession->getUser();
        $userId = $user ? $user->getUID() : null;
        $allow = $this->config->getAppValue(Application::APP_ID, 'allowPersonal', '0');

        $adminLanguage = $this->config->getAppValue(Application::APP_ID, 'language', 'en');
        $adminVersion = $this->config->getAppValue(Application::APP_ID, 'version', 'niv');
        $adminShowLink = $this->config->getAppValue(Application::APP_ID, 'showLink', '1');
        $adminMode = $this->config->getAppValue(Application::APP_ID, 'mode', 'daily');

        return new TemplateResponse(Application::APP_ID, 'personalSettings', [

            // user values
            'allowPersonal' => $allow === '1',
            'language' => $this->config->getUserValue($userId, Application::APP_ID, 'language', 'nl'),
            'version'  => $this->config->getUserValue($userId, Application::APP_ID, 'version', 'nbv'),
            'showLink' => $this->config->getUserValue($userId, Application::APP_ID, 'showLink', '1'),
            'mode'     => $this->config->getUserValue($userId, Application::APP_ID, 'mode', 'daily'),
            
            // admin values
            'adminLanguage' => $adminLanguage,
            'adminVersion' => $adminVersion,
            'adminShowLink' => $adminShowLink,
            'adminMode' => $adminMode,
        ]);
    }

    public function getSection(): string {
        return 'dailyverses-personal'; // naam van jouw sectie onder Persoonlijk
    }

    public function getPriority(): int {
        return 10;
    }
}
