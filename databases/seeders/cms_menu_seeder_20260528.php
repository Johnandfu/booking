<?php

declare(strict_types=1);

use App\Model\Permission\Menu;
use App\Model\Permission\Meta;
use Hyperf\Database\Seeders\Seeder;

class CmsMenuSeeder20260528 extends Seeder
{
    private const BASE_DATA = [
        'parent_id' => 0,
        'path' => '',
        'component' => '',
        'redirect' => '',
        'status' => 1,
        'sort' => 0,
        'created_by' => 0,
        'updated_by' => 0,
        'remark' => '',
    ];

    public function run(): void
    {
        $cmsMenu = Menu::query()->where('name', 'cms')->first();
        if ($cmsMenu !== null) {
            foreach ($this->data()[0]['children'] as $child) {
                if (! Menu::query()->where('name', $child['name'])->exists()) {
                    $this->create([$child], $cmsMenu->id);
                }
            }
            return;
        }

        $this->create($this->data());
    }

    private function data(): array
    {
        return [
            [
                'name' => 'cms',
                'path' => '/cms',
                'meta' => new Meta([
                    'title' => 'CMS 管理',
                    'i18n' => 'baseMenu.cms.index',
                    'icon' => 'ri:article-line',
                    'type' => 'M',
                    'hidden' => 0,
                    'componentPath' => 'modules/',
                    'componentSuffix' => '.vue',
                    'breadcrumbEnable' => 1,
                    'copyright' => 1,
                    'cache' => 1,
                    'affix' => 0,
                ]),
                'children' => [
                    $this->module('category', '栏目管理', '/cms/category', 'cms/views/category/index', ['list', 'create', 'update', 'delete']),
                    $this->module('article', '文章管理', '/cms/article', 'cms/views/article/index', ['list', 'create', 'update', 'delete']),
                    $this->module('banner', '轮播管理', '/cms/banner', 'cms/views/banner/index', ['list', 'create', 'update', 'delete']),
                    $this->module('page', '单页管理', '/cms/page', 'cms/views/page/index', ['list', 'create', 'update', 'delete']),
                    $this->module('contact-message', '留言管理', '/cms/contact-message', 'cms/views/contact-message/index', ['list', 'update', 'delete']),
                ],
            ],
        ];
    }

    private function module(string $name, string $title, string $path, string $component, array $actions): array
    {
        return [
            'name' => 'cms:' . $name,
            'path' => $path,
            'component' => $component,
            'meta' => new Meta([
                'title' => $title,
                'i18n' => 'baseMenu.cms.' . $name,
                'icon' => 'ri:file-list-line',
                'type' => 'M',
                'hidden' => 0,
                'componentPath' => 'modules/',
                'componentSuffix' => '.vue',
                'breadcrumbEnable' => 1,
                'copyright' => 1,
                'cache' => 1,
                'affix' => 0,
            ]),
            'children' => array_map(fn (string $action): array => [
                'name' => 'cms:' . $name . ':' . $action,
                'meta' => new Meta([
                    'title' => $title . $action,
                    'type' => 'B',
                    'i18n' => 'baseMenu.cms.' . $name . '.' . $action,
                ]),
            ], $actions),
        ];
    }

    private function create(array $data, int $parentId = 0): void
    {
        foreach ($data as $item) {
            $children = $item['children'] ?? [];
            unset($item['children']);

            $menu = Menu::create(array_merge(self::BASE_DATA, $item, ['parent_id' => $parentId]));
            if ($children !== []) {
                $this->create($children, $menu->id);
            }
        }
    }
}
