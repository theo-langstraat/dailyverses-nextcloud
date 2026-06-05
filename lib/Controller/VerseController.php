<?php

declare(strict_types=1);

namespace OCA\DailyVerses\Controller;

use OCA\DailyVerses\AppInfo\Application;
use OCA\DailyVerses\Service\VerseService;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\IRequest;
use OCP\IUserSession;

class VerseController extends Controller {

    public function __construct(
        string $appName,
        IRequest $request,
        private IUserSession $userSession,
        private VerseService $verseService,
    ) {
        parent::__construct($appName, $request);
    }

    /**
     * Endpoint:
     * GET /apps/dailyverses/api/verse?language=nl&version=hsv
     */
    /**
     * @NoAdminRequired
     * @UserRequired
     * @NoCSRFRequired
     */
    public function getVerse(): DataResponse {
        $user = $this->userSession->getUser();
        $uid = $user?->getUID() ?? 'guest';

        $language = $this->request->getParam('language', 'nl');
        $version  = $this->request->getParam('version', 'hsv');
        $mode     = $this->request->getParam('mode', 'daily');   // ← NIEUW

        $data = $this->verseService->getDailyVerse($language, $version, $mode);

        return new DataResponse([
            'uid'      => $uid,
            'language' => $language,
            'version'  => $version,
            'mode'     => $mode,
            'data'     => $data,
        ]);
    }

}
