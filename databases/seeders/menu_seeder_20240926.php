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
use App\Model\Permission\Menu;
use App\Model\Permission\Meta;
use Hyperf\Database\Seeders\Seeder;
use Hyperf\DbConnection\Db;

class MenuSeeder20240926 extends Seeder
{
    public const BASE_DATA = [
        'name' => '',
        'path' => '',
        'component' => '',
        'redirect' => '',
        'created_by' => 0,
        'updated_by' => 0,
        'remark' => '',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::truncate();
        if (env('DB_DRIVER') === 'odbc-sql-server') {
            Db::unprepared('SET IDENTITY_INSERT [' . Menu::getModel()->getTable() . '] ON;');
        }
        $this->create($this->data());
        if (env('DB_DRIVER') === 'odbc-sql-server') {
            Db::unprepared('SET IDENTITY_INSERT [' . Menu::getModel()->getTable() . '] OFF;');
        }
    }

    /**
     * Database seeds data.
     */
    public function data(): array
    {
        return [
            [
                'name' => 'permission',
                'path' => '/permission',
                'meta' => new Meta([
                    'title' => '权限管理',
                    'i18n' => 'baseMenu.permission.index',
                    'icon' => 'ri:git-repository-private-line',
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
                    [
                        'name' => 'permission:user',
                        'path' => '/permission/user',
                        'component' => 'base/views/permission/user/index',
                        'meta' => new Meta([
                            'type' => 'M',
                            'title' => '用户管理',
                            'i18n' => 'baseMenu.permission.user',
                            'icon' => 'material-symbols:manage-accounts-outline',
                            'hidden' => 0,
                            'componentPath' => 'modules/',
                            'componentSuffix' => '.vue',
                            'breadcrumbEnable' => 1,
                            'copyright' => 1,
                            'cache' => 1,
                            'affix' => 0,
                        ]),
                        'children' => [
                            [
                                'name' => 'permission:user:index',
                                'meta' => new Meta([
                                    'title' => '用户列表',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.permission.userList',
                                ]),
                            ],
                            [
                                'name' => 'permission:user:save',
                                'meta' => new Meta([
                                    'title' => '用户保存',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.permission.userSave',
                                ]),
                            ],
                            [
                                'name' => 'permission:user:update',
                                'meta' => new Meta([
                                    'title' => '用户更新',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.permission.userUpdate',
                                ]),
                            ],
                            [
                                'name' => 'permission:user:delete',
                                'meta' => new Meta([
                                    'title' => '用户删除',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.permission.userDelete',
                                ]),
                            ],
                            [
                                'name' => 'permission:user:password',
                                'meta' => new Meta([
                                    'title' => '用户初始化密码',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.permission.userPassword',
                                ]),
                            ],
                            [
                                'name' => 'user:get:roles',
                                'meta' => new Meta([
                                    'title' => '获取用户角色',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.permission.getUserRole',
                                ]),
                            ],
                            [
                                'name' => 'user:set:roles',
                                'meta' => new Meta([
                                    'title' => '用户角色赋予',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.permission.setUserRole',
                                ]),
                            ],
                        ],
                    ],
                    [
                        'name' => 'permission:menu',
                        'path' => '/permission/menu',
                        'component' => 'base/views/permission/menu/index',
                        'meta' => new Meta([
                            'title' => '菜单管理',
                            'i18n' => 'baseMenu.permission.menu',
                            'icon' => 'ph:list-bold',
                            'hidden' => 0,
                            'type' => 'M',
                            'componentPath' => 'modules/',
                            'componentSuffix' => '.vue',
                            'breadcrumbEnable' => 1,
                            'copyright' => 1,
                            'cache' => 1,
                            'affix' => 0,
                        ]),
                        'children' => [
                            [
                                'name' => 'permission:menu:index',
                                'meta' => new Meta([
                                    'title' => '菜单列表',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.permission.menuList',
                                ]),
                            ],
                            [
                                'name' => 'permission:menu:create',
                                'meta' => new Meta([
                                    'title' => '菜单保存',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.permission.menuSave',
                                ]),
                            ],
                            [
                                'name' => 'permission:menu:save',
                                'meta' => new Meta([
                                    'title' => '菜单更新',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.permission.menuUpdate',
                                ]),
                            ],
                            [
                                'name' => 'permission:menu:delete',
                                'meta' => new Meta([
                                    'title' => '菜单删除',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.permission.menuDelete',
                                ]),
                            ],
                        ],
                    ],
                    [
                        'name' => 'permission:role',
                        'path' => '/permission/role',
                        'component' => 'base/views/permission/role/index',
                        'meta' => new Meta([
                            'title' => '角色管理',
                            'i18n' => 'baseMenu.permission.role',
                            'icon' => 'material-symbols:supervisor-account-outline-rounded',
                            'hidden' => 0,
                            'type' => 'M',
                            'componentPath' => 'modules/',
                            'componentSuffix' => '.vue',
                            'breadcrumbEnable' => 1,
                            'copyright' => 1,
                            'cache' => 1,
                            'affix' => 0,
                        ]),
                        'children' => [
                            [
                                'name' => 'permission:role:index',
                                'meta' => new Meta([
                                    'title' => '角色列表',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.permission.roleList',
                                ]),
                            ],
                            [
                                'name' => 'permission:role:save',
                                'meta' => new Meta([
                                    'title' => '角色保存',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.permission.roleSave',
                                ]),
                            ],
                            [
                                'name' => 'permission:role:update',
                                'meta' => new Meta([
                                    'title' => '角色更新',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.permission.roleUpdate',
                                ]),
                            ],
                            [
                                'name' => 'permission:role:delete',
                                'meta' => new Meta([
                                    'title' => '角色删除',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.permission.roleDelete',
                                ]),
                            ],
                            [
                                'name' => 'permission:get:role',
                                'meta' => new Meta([
                                    'title' => '获取角色权限',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.permission.getRolePermission',
                                ]),
                            ],
                            [
                                'name' => 'permission:set:role',
                                'meta' => new Meta([
                                    'title' => '赋予角色权限',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.permission.setRolePermission',
                                ]),
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'log',
                'path' => '/log',
                'meta' => new Meta([
                    'title' => '日志管理',
                    'i18n' => 'baseMenu.log.index',
                    'icon' => 'ph:instagram-logo',
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
                    [
                        'name' => 'log:userLogin',
                        'path' => '/log/userLoginLog',
                        'component' => 'base/views/log/userLogin',
                        'meta' => new Meta([
                            'title' => '用户登录日志管理',
                            'type' => 'M',
                            'hidden' => 0,
                            'icon' => 'ph:user-list',
                            'i18n' => 'baseMenu.log.userLoginLog',
                            'componentPath' => 'modules/',
                            'componentSuffix' => '.vue',
                            'breadcrumbEnable' => 1,
                            'copyright' => 1,
                            'cache' => 1,
                            'affix' => 0,
                        ]),
                        'children' => [
                            [
                                'name' => 'log:userLogin:list',
                                'path' => '/log/userLoginLog',
                                'meta' => new Meta([
                                    'title' => '用户登录日志列表',
                                    'i18n' => 'baseMenu.log.userLoginLogList',
                                    'type' => 'B',
                                ]),
                            ],
                            [
                                'name' => 'log:userLogin:delete',
                                'meta' => new Meta([
                                    'title' => '删除用户登录日志',
                                    'i18n' => 'baseMenu.log.userLoginLogDelete',
                                    'type' => 'B',
                                ]),
                            ],
                        ],
                    ],
                    [
                        'name' => 'log:userOperation',
                        'path' => '/log/operationLog',
                        'component' => 'base/views/log/userOperation',
                        'meta' => new Meta([
                            'title' => '操作日志管理',
                            'type' => 'M',
                            'hidden' => 0,
                            'icon' => 'ph:list-magnifying-glass',
                            'i18n' => 'baseMenu.log.operationLog',
                            'componentPath' => 'modules/',
                            'componentSuffix' => '.vue',
                            'breadcrumbEnable' => 1,
                            'copyright' => 1,
                            'cache' => 1,
                            'affix' => 0,
                        ]),
                        'children' => [
                            [
                                'name' => 'log:userOperation:list',
                                'meta' => new Meta([
                                    'title' => '用户操作日志列表',
                                    'i18n' => 'baseMenu.log.userOperationLog',
                                    'type' => 'B',
                                ]),
                            ],
                            [
                                'name' => 'log:userOperation:delete',
                                'meta' => new Meta([
                                    'title' => '删除用户操作日志',
                                    'i18n' => 'baseMenu.log.userOperationLogDelete',
                                    'type' => 'B',
                                ]),
                            ],
                        ],
                    ],
                ],
            ],
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
                    [
                        'name' => 'cms:category',
                        'path' => '/cms/category',
                        'component' => 'cms/views/category/index',
                        'meta' => new Meta([
                            'title' => '栏目管理',
                            'i18n' => 'baseMenu.cms.category',
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
                        'children' => [
                            [
                                'name' => 'cms:category:list',
                                'meta' => new Meta([
                                    'title' => '栏目列表',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.cms.categoryList',
                                ]),
                            ],
                            [
                                'name' => 'cms:category:create',
                                'meta' => new Meta([
                                    'title' => '栏目新增',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.cms.categoryCreate',
                                ]),
                            ],
                            [
                                'name' => 'cms:category:update',
                                'meta' => new Meta([
                                    'title' => '栏目更新',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.cms.categoryUpdate',
                                ]),
                            ],
                            [
                                'name' => 'cms:category:delete',
                                'meta' => new Meta([
                                    'title' => '栏目删除',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.cms.categoryDelete',
                                ]),
                            ],
                        ],
                    ],
                    [
                        'name' => 'cms:article',
                        'path' => '/cms/article',
                        'component' => 'cms/views/article/index',
                        'meta' => new Meta([
                            'title' => '文章管理',
                            'i18n' => 'baseMenu.cms.article',
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
                        'children' => [
                            [
                                'name' => 'cms:article:list',
                                'meta' => new Meta([
                                    'title' => '文章列表',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.cms.articleList',
                                ]),
                            ],
                            [
                                'name' => 'cms:article:create',
                                'meta' => new Meta([
                                    'title' => '文章新增',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.cms.articleCreate',
                                ]),
                            ],
                            [
                                'name' => 'cms:article:update',
                                'meta' => new Meta([
                                    'title' => '文章更新',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.cms.articleUpdate',
                                ]),
                            ],
                            [
                                'name' => 'cms:article:delete',
                                'meta' => new Meta([
                                    'title' => '文章删除',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.cms.articleDelete',
                                ]),
                            ],
                        ],
                    ],
                  
                    [
                        'name' => 'cms:banner',
                        'path' => '/cms/banner',
                        'component' => 'cms/views/banner/index',
                        'meta' => new Meta([
                            'title' => '轮播管理',
                            'i18n' => 'baseMenu.cms.banner',
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
                        'children' => [
                            [
                                'name' => 'cms:banner:list',
                                'meta' => new Meta([
                                    'title' => '轮播列表',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.cms.bannerList',
                                ]),
                            ],
                            [
                                'name' => 'cms:banner:create',
                                'meta' => new Meta([
                                    'title' => '轮播新增',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.cms.bannerCreate',
                                ]),
                            ],
                            [
                                'name' => 'cms:banner:update',
                                'meta' => new Meta([
                                    'title' => '轮播更新',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.cms.bannerUpdate',
                                ]),
                            ],
                            [
                                'name' => 'cms:banner:delete',
                                'meta' => new Meta([
                                    'title' => '轮播删除',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.cms.bannerDelete',
                                ]),
                            ],
                        ],
                    ],
                    [
                        'name' => 'cms:contact-message',
                        'path' => '/cms/contact-message',
                        'component' => 'cms/views/contact-message/index',
                        'meta' => new Meta([
                            'title' => '留言管理',
                            'i18n' => 'baseMenu.cms.contactMessage',
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
                        'children' => [
                            [
                                'name' => 'cms:contact-message:list',
                                'meta' => new Meta([
                                    'title' => '留言列表',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.cms.contactMessageList',
                                ]),
                            ],
                            [
                                'name' => 'cms:contact-message:update',
                                'meta' => new Meta([
                                    'title' => '留言更新',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.cms.contactMessageUpdate',
                                ]),
                            ],
                            [
                                'name' => 'cms:contact-message:delete',
                                'meta' => new Meta([
                                    'title' => '留言删除',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.cms.contactMessageDelete',
                                ]),
                            ],
                        ],
                    ],
                    [
                        'name' => 'cms:frontend-i18n',
                        'path' => '/cms/frontend-i18n',
                        'component' => 'cms/views/frontend-i18n/index',
                        'meta' => new Meta([
                            'title' => '前台多语言',
                            'i18n' => 'baseMenu.cms.frontendI18n',
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
                        'children' => [
                            [
                                'name' => 'cms:frontend-i18n:list',
                                'meta' => new Meta([
                                    'title' => '前台多语言列表',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.cms.frontendI18nList',
                                ]),
                            ],
                            [
                                'name' => 'cms:frontend-i18n:create',
                                'meta' => new Meta([
                                    'title' => '前台多语言新增',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.cms.frontendI18nCreate',
                                ]),
                            ],
                            [
                                'name' => 'cms:frontend-i18n:update',
                                'meta' => new Meta([
                                    'title' => '前台多语言更新',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.cms.frontendI18nUpdate',
                                ]),
                            ],
                            [
                                'name' => 'cms:frontend-i18n:delete',
                                'meta' => new Meta([
                                    'title' => '前台多语言删除',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.cms.frontendI18nDelete',
                                ]),
                            ],
                        ],
                    ],
                ],
            ],
            /* [
                'name' => '工具',
                'code' => 'devTools',
                'icon' => 'ma-icon-tool',
                'path' => 'devTools',
                'hidden' => '2',
                'type' => 'M',
                'children' => [
                    [
                        'name' => '代码生成器',
                        'code' => 'setting:code',
                        'icon' => 'ma-icon-code',
                        'path' => 'code',
                        'component' => 'setting/code/index',
                        'hidden' => '2',
                        'type' => 'M',
                        'children' => [
                            [
                                'name' => '预览代码',
                                'code' => 'setting:code:preview',
                                'type' => 'B',
                            ],
                            [
                                'name' => '装载数据表',
                                'code' => 'setting:code:loadTable',
                                'type' => 'B',
                            ],
                            [
                                'name' => '删除业务表',
                                'code' => 'setting:code:delete',
                                'type' => 'B',
                            ],
                            [
                                'name' => '同步业务表',
                                'code' => 'setting:code:sync',
                                'type' => 'B',
                            ],
                            [
                                'name' => '生成代码',
                                'code' => 'setting:code:generate',
                                'type' => 'B',
                            ],
                        ],
                    ],
                    [
                        'name' => '数据源管理',
                        'code' => 'setting:datasource',
                        'icon' => 'icon-storage',
                        'path' => 'setting/datasource',
                        'component' => 'setting/datasource/index',
                        'hidden' => '2',
                        'type' => 'M',
                        'children' => [
                            [
                                'name' => '数据源管理列表',
                                'code' => 'setting:datasource:index',
                                'type' => 'B',
                            ],
                            [
                                'name' => '数据源管理保存',
                                'code' => 'setting:datasource:save',
                                'type' => 'B',
                            ],
                            [
                                'name' => '数据源管理更新',
                                'code' => 'setting:datasource:update',
                                'type' => 'B',
                            ],
                            [
                                'name' => '数据源管理读取',
                                'code' => 'setting:datasource:read',
                                'type' => 'B',
                            ],
                            [
                                'name' => '数据源管理删除',
                                'code' => 'setting:datasource:delete',
                                'type' => 'B',
                            ],
                            [
                                'name' => '数据源管理导入',
                                'code' => 'setting:datasource:import',
                                'type' => 'B',
                            ],
                            [
                                'name' => '数据源管理导出',
                                'code' => 'setting:datasource:export',
                                'type' => 'B',
                            ],
                        ],
                    ],
                    [
                        'name' => '系统接口',
                        'code' => 'systemInterface',
                        'icon' => 'icon-compass',
                        'path' => 'systemInterface',
                        'component' => 'setting/systemInterface/index',
                        'hidden' => '2',
                        'type' => 'M',
                    ],
                ],
            ],*/
        ];
    }

    public function create(array $data, int $parent_id = 0): void
    {
        foreach ($data as $v) {
            $_v = $v;
            if (isset($v['children'])) {
                unset($_v['children']);
            }
            $_v['parent_id'] = $parent_id;
            $menu = Menu::create(array_merge(self::BASE_DATA, $_v));
            if (isset($v['children']) && count($v['children'])) {
                $this->create($v['children'], $menu->id);
            }
        }
    }
}
