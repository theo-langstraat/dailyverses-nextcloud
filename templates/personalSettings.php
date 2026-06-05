<?php

declare(strict_types=1);

use OCA\DailyVerses\Service\LanguageLabels;
use OCA\DailyVerses\Service\VersionLabels;

use OCA\DailyVerses\AppInfo\Application;

script(Application::APP_ID, Application::APP_ID . '-settings');
style(Application::APP_ID, 'settings');

$langLabels = LanguageLabels::get();
$verLabels = VersionLabels::get();

$adminLangLabel = $langLabels[$_['adminLanguage']]['native']
    . ' (' . $langLabels[$_['adminLanguage']]['english'] . ')';

$adminVersionLabel = $verLabels[$_['adminVersion']] ?? $_['adminVersion'];

?>

<div id="<?php p(Application::APP_ID); ?>-settings"
     data-context="personal"
     data-language="<?php p($_['language']); ?>"
     data-version="<?php p($_['version']); ?>"
     data-showlink="<?php p($_['showLink']); ?>"
     data-mode="<?php p($_['mode']); ?>"
     data-allowpersonal="<?php p($_['allowPersonal']); ?>">

    <div class="dv-admin-container">
        <h2 class="dv-title"><?= $l->t('DailyVerses instellingen') ?></h2>

        <?php if (!$_['allowPersonal']): ?>

            <div class="dv-disabled-box">
                <p>
                    <strong><?= $l->t('Persoonlijke instellingen zijn uitgeschakeld.') ?></strong><br>
                    <?= $l->t('De onderstaande instellingen worden beheerd door uw beheerder.') ?>
                </p>
            </div>

            <details class="dv-collapsed">
                <summary><?= $l->t('Toon globale instellingen') ?></summary>

                <div class="dv-disabled-fields">

                    <div class="dv-field">
                        <label class="dv-label"><?= $l->t('Taal') ?></label>
                        <select class="dv-select" disabled>
                            <option><?php p($adminLangLabel); ?></option>
                        </select>
                    </div>

                    <div class="dv-field">
                        <label class="dv-label"><?= $l->t('Bijbelvertaling') ?></label>
                        <select class="dv-select" disabled>
                            <option><?php p($adminVersionLabel); ?></option>
                        </select>
                    </div>

                    <div class="dv-field dv-checkbox">
                        <label>
                            <input type="checkbox" disabled <?php if ($_['adminShowLink'] === '1') echo 'checked'; ?>>
                            <?= $l->t('Toon link naar DailyVerses.net') ?>
                        </label>
                    </div>

                    <div class="dv-field">
                        <label class="dv-label"><?= $l->t('Modus') ?></label>

                        <label class="dv-radio">
                            <input type="radio" disabled <?php if ($_['adminMode'] === 'daily') echo 'checked'; ?>>
                            <?= $l->t('Verse of the Day') ?>
                        </label>

                        <label class="dv-radio">
                            <input type="radio" disabled <?php if ($_['adminMode'] === 'random') echo 'checked'; ?>>
                            <?= $l->t('Random Verse') ?>
                        </label>
                    </div>

                </div>
            </details>

        <?php else: ?>

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

        <?php endif; ?>

    </div>

</div>
