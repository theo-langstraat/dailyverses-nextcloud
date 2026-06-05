<?php

declare(strict_types=1);

namespace OCA\DailyVerses\Service;

class LanguageLabels {
    public static function get(): array {
        return [
            'en' => ['native' => 'English', 'english' => 'English'],
            'es' => ['native' => 'Español', 'english' => 'Spanish'],
            'pt' => ['native' => 'Português', 'english' => 'Portuguese'],
            'de' => ['native' => 'Deutsch', 'english' => 'German'],
            'fr' => ['native' => 'Français', 'english' => 'French'],
            'it' => ['native' => 'Italiano', 'english' => 'Italian'],
            'pl' => ['native' => 'Polski', 'english' => 'Polish'],
            'ar' => ['native' => 'العربية', 'english' => 'Arabic'],
            'nl' => ['native' => 'Nederlands', 'english' => 'Dutch'],
            'af' => ['native' => 'Afrikaans', 'english' => 'Afrikaans'],
            'bn' => ['native' => '(বাংলা)', 'english' => 'Bengali'],
            'zh' => ['native' => '中文', 'english' => 'Chinese'],
            'cs' => ['native' => 'Čeština', 'english' => 'Czech'],
            'da' => ['native' => 'Dansk', 'english' => 'Danish'],
            'fi' => ['native' => 'Suomi', 'english' => 'Finnish'],
            'el' => ['native' => 'Ελληνικά', 'english' => 'Greek'],
            'hi' => ['native' => 'हिन्दी', 'english' => 'Hindi'],
            'hu' => ['native' => 'Magyar', 'english' => 'Hungarian'],
            'mg' => ['native' => 'Malagasy', 'english' => 'Malagasy'],
            'fa' => ['native' => 'فارسی', 'english' => 'Persian'],
            'ru' => ['native' => 'Русский', 'english' => 'Russian'],
            'sk' => ['native' => 'Slovenčina', 'english' => 'Slovak'],
            'st' => ['native' => 'Sesotho', 'english' => 'Southern Sotho'],
            'ur' => ['native' => 'اردو', 'english' => 'Urdu'],
            'xh' => ['native' => 'isiXhosa', 'english' => 'Xhosa'],
            'zu' => ['native' => 'isiZulu', 'english' => 'Zulu'],
        ];
    }
}
