<?php

declare(strict_types=1);

namespace OCA\DailyVerses\Service;

use OCA\DailyVerses\AppInfo\Application;
use OCP\ICache;
use OCP\ICacheFactory;
use OCP\IUserSession;

class VerseService {

    private ICache $cache;

    public function __construct(
        private ICacheFactory $cacheFactory,
        private IUserSession $userSession,
    ) {
        $this->cache = $this->cacheFactory->createDistributed(Application::APP_ID);
    }

    public function getDailyVerse(string $language, string $version, string $mode = 'daily'): array {
        $user = $this->userSession->getUser();
        $uid = $user?->getUID() ?? 'guest';

        $today = date('Y-m-d');

        // Settings-hash zodat cache ongeldig wordt bij wijziging
        $settingsHash = md5("$language|$version|$mode");

        // random mag niet op datum cachen
        $dateKey = ($mode === 'random') ? uniqid() : $today;

        $cacheKey = "verse:$uid:$settingsHash:$dateKey";

        $cached = $this->cache->get($cacheKey);
        if ($cached !== null) {
            return json_decode($cached, true);
        }

        $data = $this->fetchFromJsSnippet($version, $mode);

        $this->cache->set($cacheKey, json_encode($data), 86400);

        return $data;
    }

    private function fetchFromJsSnippet(string $version, string $mode): array {
        $base = $mode === 'random'
            ? "https://dailyverses.net/get/random.js?language="
            : "https://dailyverses.net/get/verse.js?language=";

        $url = $base . $version;

        $js = @file_get_contents($url);
        if (!$js) {
            return [
                'text' => 'Kan DailyVerses.net niet bereiken.',
                'reference' => '',
                'link' => '',
                'linkText' => '',
                'version' => $version,
            ];
        }

        // Extract innerHTML content between single quotes
        $start = strpos($js, "'");
        $end = strrpos($js, "'");
        $raw = substr($js, $start + 1, $end - $start - 1);

        // Decode unicode escapes
        $decoded = json_decode('"' . $raw . '"');

        // Clean HTML
        $html = trim($decoded, "';");

        // Extract text
        preg_match('/<div class=\\"dailyVerses bibleText\\">(.*?)<\\/div>/', $html, $textMatch);
        preg_match('/<div class=\\"dailyVerses bibleVerse\\">(.*?)<\\/div>/', $html, $verseMatch);
        preg_match('/<a[^>]*href=\\"(.*?)\\"/', $html, $linkMatch);
        preg_match('/<a[^>]*>(.*?)<\\/a>/', $html, $linkTextMatch);

        return [
            'text'      => $textMatch[1] ?? '',
            'reference' => $verseMatch[1] ?? '',
            'version'   => $version,
            'link'      => $linkMatch[1] ?? '',
            'linkText'  => $linkTextMatch[1] ?? '',
        ];
    }
}
