<?php

declare(strict_types=1);

/**
 * Hyperf Crontab.
 * @see https://hyperf.wiki/3.1/#/zh-cn/crontab
 */
return [
    'enable' => (bool) env('CRONTAB_ENABLE', true),
];
