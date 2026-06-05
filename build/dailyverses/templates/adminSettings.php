<?php

declare(strict_types=1);

use OCA\DailyVerses\AppInfo\Application;

script(Application::APP_ID, Application::APP_ID . '-settings');
style(Application::APP_ID, 'settings');

?>

<div id="<?php p(Application::APP_ID); ?>-settings"
     data-context="admin"
     data-language="<?php p($_['language']); ?>"
     data-version="<?php p($_['version']); ?>"
     data-showlink="<?php p($_['showLink']); ?>"
     data-mode="<?php p($_['mode']); ?>"
     data-allowpersonal="<?php p($_['allowPersonal']); ?>">

    <div class="dv-admin-container">

        <h2 class="dv-title"><?= $l->t('DailyVerses instellingen') ?></h2>

        <!-- Admin override -->
        <div class="dv-field dv-checkbox">
            <label>
                <input type="checkbox" id="allowPersonal">
                <?= $l->t('Persoonlijke instellingen toestaan') ?>
            </label>
        </div>

        <div class="dv-field">
            <label for="languageSelect" class="dv-label"><?= $l->t('Taal') ?></label>
            <select id="languageSelect" class="dv-select"></select>
        </div>

        <div class="dv-field">
            <label for="versionSelect" class="dv-label"><?= $l->t('Bijbelvertaling') ?></label>
            <select id="versionSelect" class="dv-select"></select>
        </div>

        <div class="dv-field dv-checkbox">
            <label>
                <input type="checkbox" id="showLink">
                <?= $l->t('Toon link naar DailyVerses.net') ?>
            </label>
        </div>

        <div class="dv-field">
            <label class="dv-label"><?= $l->t('Modus') ?></label>

            <label class="dv-radio">
                <input type="radio" name="mode" value="daily" id="modeDaily">
                <?= $l->t('Verse of the Day') ?>
            </label>

            <label class="dv-radio">
                <input type="radio" name="mode" value="random" id="modeRandom">
                <?= $l->t('Random Verse') ?>
            </label>
        </div>

        <button id="saveSettings" class="dv-button"><?= $l->t('Opslaan') ?></button>
        <div id="saveStatus" class="dv-status"></div>

    </div>
</div>
<?php
