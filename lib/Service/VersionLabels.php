<?php

declare(strict_types=1);

namespace OCA\DailyVerses\Service;

class VersionLabels {
    public static function get(): array {
        return [
            // English
            'esv' => 'English Standard Version (ESV)',
            'kjv' => 'King James Version (KJV)',
            'nasb' => 'New American Standard Bible 2020 (NASB)',
            'ncb' => 'New Catholic Bible (NCB)',
            'niv' => 'New International Version (NIV)',
            'nkjv' => 'New King James Version (NKJV)',
            'nlt' => 'New Living Translation (NLT)',
            'nrsv' => 'New Revised Standard Version (NRSV)',
            'web' => 'World English Bible (WEB)',

            // Spanish
            'lbla' => 'La Biblia de las Américas (LBLA)',
            'nvi' => 'Nueva Versión Internacional (NVI)',
            'rvr60' => 'Reina-Valera 1960 (RVR60)',
            'rvr95' => 'Reina-Valera 1995 (RVR95)',

            // Portuguese
            'ara' => 'Almeida Revista e Atualizada (ARA)',
            'arc' => 'Almeida Revista e Corrigida (ARC)',
            'nvi_pt' => 'Nova Versão Internacional (NVI)',

            // German
            'elb' => 'Elberfelder Bibel (ELB)',
            'lu12' => 'Luther 1912 (LU12)',
            'lut' => 'Luther 2017 (LUT)',
            'neu' => 'Neue evangelistische Übersetzung (NEÜ)',

            // French
            'bds' => 'Bible du Semeur (BDS)',
            'sg21' => 'Segond 21 (SG21)',

            // Italian
            'cei' => 'Conferenza Episcopale Italiana (CEI)',
            'nr06' => 'Nuova Riveduta 2006 (NR06)',

            // Polish
            'bw1975' => 'Biblia Warszawska 1975 (BW1975)',
            'ubg' => 'Uwspółcześniona Biblia Gdańska (UBG)',

            // Arabic
            'avd' => 'الكتاب المقدس (AVD)',
            'keh' => 'ترجمة كتاب الحياة (KEH)',

            // Dutch
            'bb' => 'BasisBijbel (BB)',
            'bgt' => 'Bijbel in Gewone Taal (BGT)',
            'hsv' => 'Herziene Statenvertaling (HSV)',
            'nbg' => 'Nederlands Bijbelgenootschap (NBG)',
            'nbv' => 'Nieuwe Bijbelvertaling 2021 (NBV)',

            // Afrikaans
            'afr53' => 'Afrikaans 1933/1953 (AFR53)',

            // Bengali
            'rovu' => 'পবিত্র বাইবেল (ROVU)',

            // Chinese
            'cuv' => '和合本聖經 (CUV)',
            'cuvs' => '和合本圣经 (CUVS)',

            // Czech
            'b21' => 'Bible 21 (B21)',
            'cep' => 'Český ekumenický překlad (ČEP)',

            // Danish
            'bdan' => 'Bibelen på Hverdagsdansk (BDAN)',
            'da1871' => 'Danske Bibel 1871/1907 (DA1871)',

            // Finnish
            'kr38' => 'Kirkkoraamattu 1933/1938 (KR38)',
            'kr92' => 'Raamattu 1992 (KR92)',

            // Greek
            'tgv' => 'Η Αγία Γραφή (TGV)',

            // Hindi
            'hhbd' => 'पवित्र बाइबिल (HHBD)',

            // Hungarian
            'uf' => 'Magyar újfordítású Biblia (UF)',

            // Malagasy
            'mg1865' => 'Malagasy 1865 (MG1865)',

            // Persian
            'pcb' => 'کتاب مقدس، ترجمۀ معاصر (PCB)',

            // Russian
            'rst' => 'Синодальный перевод (RST)',

            // Slovak
            'kat' => 'Katolícky preklad (KAT)',

            // Southern Sotho
            'sso89' => 'BIBELE (SSO89)',

            // Urdu
            'urd' => 'کِتابِ مُقادّس (URD)',

            // Xhosa
            'xho96' => 'IBHAYIBHILE (XHO96)',

            // Zulu
            'zul59' => 'Zulu 1959 (ZUL59)',
        ];
    }
}
