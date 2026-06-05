<?php

namespace OCA\DailyVerses\Util;

use OCA\DailyVerses\AppInfo\Application;

class LanguageHelper {
    public static function getLanguageLabel(string $lang): string {
        $labels = include __DIR__ . '/../../js/language-labels.php';
        if (!isset($labels[$lang])) return $lang;

        return $labels[$lang]['native'] . ' (' . $labels[$lang]['english'] . ')';
    }

    public static function getVersionLabel(string $version): string {
        $labels = include __DIR__ . '/../../js/version-labels.php';
        return $labels[$version] ?? $version;
    }
}
