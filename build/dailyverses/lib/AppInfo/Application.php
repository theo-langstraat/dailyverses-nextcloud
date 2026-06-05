<?php

declare(strict_types=1);

namespace OCA\DailyVerses\AppInfo;

use OCA\DailyVerses\Dashboard\DailyversesWidget;
use OCA\DailyVerses\Service\LanguageOptions;
use OCA\DailyVerses\Service\VerseService;
use OCA\DailyVerses\Controller\VerseController;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

class Application extends App implements IBootstrap {
    public const APP_ID = 'dailyverses';

    public function __construct(array $urlParams = []) {
        parent::__construct(self::APP_ID, $urlParams);

        $container = $this->getContainer();

        $container->registerService(VerseService::class, function($c) {
            return new VerseService(
                $c->query(\OCP\ICacheFactory::class),
                $c->query(\OCP\IUserSession::class),
            );
        });

        $container->registerService(VerseController::class, function($c) {
            return new VerseController(
                Application::APP_ID,
                $c->query(\OCP\IRequest::class),
                $c->query(\OCP\IUserSession::class),
                $c->query(VerseService::class),
            );
        });
    }
    public function register(IRegistrationContext $context): void {

        // 1. Widget registreren
        $context->registerDashboardWidget(DailyversesWidget::class);

        // 2. Initial state injecteren via DI-container callback
        $context->registerService('InitialStateLoader', function($c) {
            $initialState = $c->query(\OCP\IInitialStateService::class);
            $config       = $c->query(\OCP\IConfig::class);
            $userSession  = $c->query(\OCP\IUserSession::class);

            $user = $userSession->getUser();
            $uid = $user?->getUID();

            // Admin override
            $allowPersonal = $config->getAppValue(Application::APP_ID, 'allowPersonal', '1');

            if ($allowPersonal === '1') {
                // User settings met admin defaults als fallback
                $language = $config->getUserValue(
                    $uid, Application::APP_ID, 'language',
                    $config->getAppValue(Application::APP_ID, 'language', 'nl')
                );

                $version = $config->getUserValue(
                    $uid, Application::APP_ID, 'version',
                    $config->getAppValue(Application::APP_ID, 'version', 'nbv')
                );

                $showLink = $config->getUserValue(
                    $uid, Application::APP_ID, 'showLink',
                    $config->getAppValue(Application::APP_ID, 'showLink', '1')
                );

                $mode = $config->getUserValue(
                    $uid, Application::APP_ID, 'mode',
                    $config->getAppValue(Application::APP_ID, 'mode', 'daily')
                );

            } else {
                // Alleen admin settings
                $language = $config->getAppValue(Application::APP_ID, 'language', 'nl');
                $version  = $config->getAppValue(Application::APP_ID, 'version', 'nbv');
                $showLink = $config->getAppValue(Application::APP_ID, 'showLink', '1');
                $mode     = $config->getAppValue(Application::APP_ID, 'mode', 'daily');
            }

            // LanguageOptions laden
            $options = LanguageOptions::get();

            // Initial state beschikbaar maken voor frontend
            $initialState->provideInitialState(Application::APP_ID, 'languageOptions', $options);
            $initialState->provideInitialState(Application::APP_ID, 'settings', [
                'language' => $language,
                'version'  => $version,
                'showLink' => $showLink,
                'mode'     => $mode,
            ]);
        });
    }

    public function boot(IBootContext $context): void {
        // Niet gebruikt
    }
}
