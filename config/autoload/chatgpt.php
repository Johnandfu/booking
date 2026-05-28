<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */
return [
    'token' => env('GPT_TOKEN', ''),
    'tukeli_token' => env('TKL_TOKEN'),
    'gemini_api_key' => env('GEMINI_API_KEY'),
    'gemini_api_url' => env('GEMINI_API_ENDPOINT', 'https://generativelanguage.googleapis.com/v1beta/models/'),
    'vertex_key' => env('VERTEX_AI_GEMINI_KEY'),
    'vertex_project_id' => env('VERTEX_AI_GEMINI_PROJECT_ID'),
    'vertex_config' => [
        ['key' => env('VERTEX_AI_GEMINI_KEY'),  'project_id' => env('VERTEX_AI_GEMINI_PROJECT_ID', '664562270453')],
        ['key' => env('VERTEX_AI_GEMINI_KEY1'),  'project_id' => env('VERTEX_AI_GEMINI_PROJECT_ID1', '97092624703')],
        ['key' => env('VERTEX_AI_GEMINI_KEY2'),  'project_id' => env('VERTEX_AI_GEMINI_PROJECT_ID2', '673087092909')],
        ['key' => env('VERTEX_AI_GEMINI_KEY3'),  'project_id' => env('VERTEX_AI_GEMINI_PROJECT_ID3', '863278397767')],
        ['key' => env('VERTEX_AI_GEMINI_KEY4'),  'project_id' => env('VERTEX_AI_GEMINI_PROJECT_ID4', '127358345238')],
    ],
];
