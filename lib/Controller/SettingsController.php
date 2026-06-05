<?php

declare(strict_types=1);

namespace OCA\DailyVerses\Controller;

// for use Application::APP_ID
use OCA\DailyVerses\AppInfo\Application;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;
use OCP\IConfig;
use OCP\IUserSession;

class SettingsController extends Controller {

	private IConfig $config;
	private IUserSession $userSession;

	public function __construct(
		string $appName,
		IRequest $request,
		IConfig $config,
		IUserSession $userSession
	) {
		parent::__construct($appName, $request);
		$this->config = $config;
		$this->userSession = $userSession;
	}

	private function getUserId(): string {
		$user = $this->userSession->getUser();
		if ($user !== null) {
			return $user->getUID();
		}

		// Fallback voor dashboard widgets
		return \OC_User::getUser();
	}

	/**
	 * @NoAdminRequired
	 * @UserRequired
	 * @NoCSRFRequired
	 */
	public function load(): JSONResponse {
		$uid = $this->userSession->getUser()?->getUID();

		// Admin settings
		$admin = [
			'language'     => $this->config->getAppValue(Application::APP_ID, 'language', 'nl'),
			'version'      => $this->config->getAppValue(Application::APP_ID, 'version', 'nbv'),
			'showLink'     => $this->config->getAppValue(Application::APP_ID, 'showLink', '1'),
			'mode'         => $this->config->getAppValue(Application::APP_ID, 'mode', 'daily'),
			'allowPersonal'=> $this->config->getAppValue(Application::APP_ID, 'allowPersonal', '1'),
		];

		// User settings (fallback naar admin)
		$user = [
			'language' => $this->config->getUserValue($uid, Application::APP_ID, 'language', $admin['language']),
			'version'  => $this->config->getUserValue($uid, Application::APP_ID, 'version',  $admin['version']),
			'showLink' => $this->config->getUserValue($uid, Application::APP_ID, 'showLink', $admin['showLink']),
			'mode'     => $this->config->getUserValue($uid, Application::APP_ID, 'mode',     $admin['mode']),
		];

		return new JSONResponse([
			'admin' => $admin,
			'user'  => $user,
		]);
	}

	/**
	 * @NoAdminRequired
	 * @UserRequired
	 * @NoCSRFRequired
	 */
	public function save(): DataResponse {
		$uid = $this->getUserId();

		// Personal settings opslaan
		$params = $this->request->getParams();

		$language = $params['language'] ?? 'nl';
		$version  = $params['version'] ?? 'nbv';
		$showLink = $params['showLink'] ?? '1';
		$mode     = $params['mode'] ?? 'daily';

		$this->config->setUserValue($uid, Application::APP_ID, 'language', $language);
		$this->config->setUserValue($uid, Application::APP_ID, 'version', $version);
		$this->config->setUserValue($uid, Application::APP_ID, 'showLink', $showLink);
		$this->config->setUserValue($uid, Application::APP_ID, 'mode', $mode);

		return new DataResponse(['status' => 'ok']);
	}

	/**
	 * @AdminRequired
	 * @CSRFRequired
	 */
	public function saveAdmin(): DataResponse {

		$params = $this->request->getParams();

		$language      = $params['language']      ?? 'nl';
		$version       = $params['version']       ?? 'nbv';
		$showLink      = $params['showLink']      ?? '1';
		$mode          = $params['mode']          ?? 'daily';
		$allowPersonal = $params['allowPersonal'] ?? '1';

		// Globale admin-instellingen opslaan
		$this->config->setAppValue(Application::APP_ID, 'language', $language);
		$this->config->setAppValue(Application::APP_ID, 'version', $version);
		$this->config->setAppValue(Application::APP_ID, 'showLink', $showLink);
		$this->config->setAppValue(Application::APP_ID, 'mode', $mode);
		$this->config->setAppValue(Application::APP_ID, 'allowPersonal', $allowPersonal);

		return new DataResponse(['status' => 'ok']);
	}

	/**
	 * @NoAdminRequired
	 * @UserRequired
	 * @NoCSRFRequired
	 */
	public function getOptions(): DataResponse {
		return new DataResponse([
			'languageOptions' => \OCA\DailyVerses\Service\LanguageOptions::get(),
			'languageLabels'  => \OCA\DailyVerses\Service\LanguageLabels::get(),
			'versionLabels'   => \OCA\DailyVerses\Service\VersionLabels::get(),
		]);
	}
}