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
                                'name' => 'permission:user:getRole',
                                'meta' => new Meta([
                                    'title' => '获取用户角色',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.permission.getUserRole',
                                ]),
                            ],
                            [
                                'name' => 'permission:user:setRole',
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
                                'name' => 'permission:role:getMenu',
                                'meta' => new Meta([
                                    'title' => '获取角色权限',
                                    'type' => 'B',
                                    'i18n' => 'baseMenu.permission.getRolePermission',
                                ]),
                            ],
                            [
                                'name' => 'permission:role:setMenu',
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
                'name' => 'dataCenter',
                'path' => '/dataCenter',
                'meta' => new Meta([
                    'title' => '数据中心',
                    'i18n' => 'baseMenu.dataCenter.index',
                    'icon' => 'ri:database-line',
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
                        'name' => 'dataCenter:attachment',
                        'path' => '/dataCenter/attachment',
                        'component' => 'base/views/dataCenter/attachment/index',
                        'meta' => new Meta([
                            'title' => '附件管理',
                            'type' => 'M',
                            'hidden' => 0,
                            'icon' => 'ri:attachment-line',
                            'i18n' => 'baseMenu.dataCenter.attachment',
                            'componentPath' => 'modules/',
                            'componentSuffix' => '.vue',
                            'breadcrumbEnable' => 1,
                            'copyright' => 1,
                            'cache' => 1,
                            'affix' => 0,
                        ]),
                        'children' => [
                            [
                                'name' => 'dataCenter:attachment:list',
                                'meta' => new Meta([
                                    'title' => '附件列表',
                                    'i18n' => 'baseMenu.dataCenter.attachmentList',
                                    'type' => 'B',
                                ]),
                            ],
                            [
                                'name' => 'dataCenter:attachment:upload',
                                'meta' => new Meta([
                                    'title' => '上传附件',
                                    'i18n' => 'baseMenu.dataCenter.attachmentUpload',
                                    'type' => 'B',
                                ]),
                            ],
                            [
                                'name' => 'dataCenter:attachment:delete',
                                'meta' => new Meta([
                                    'title' => '删除附件',
                                    'i18n' => 'baseMenu.dataCenter.attachmentDelete',
                                    'type' => 'B',
                                ]),
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'booking',
                'path' => '/booking',
                'meta' => new Meta([
                    'title' => '预约管理',
                    'i18n' => 'baseMenu.booking.index',
                    'icon' => 'ri:calendar-check-line',
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
                        'name' => 'booking:booking',
                        'path' => '/booking/list',
                        'component' => 'booking/views/booking/index',
                        'meta' => new Meta([
                            'title' => '预约单',
                            'type' => 'M',
                            'hidden' => 0,
                            'icon' => 'ri:calendar-schedule-line',
                            'i18n' => 'baseMenu.booking.booking',
                            'componentPath' => 'modules/',
                            'componentSuffix' => '.vue',
                            'breadcrumbEnable' => 1,
                            'copyright' => 1,
                            'cache' => 1,
                            'affix' => 0,
                        ]),
                        'children' => [
                            [
                                'name' => 'booking:booking:list',
                                'meta' => new Meta([
                                    'title' => '预约列表',
                                    'i18n' => 'baseMenu.booking.bookingList',
                                    'type' => 'B',
                                ]),
                            ],
                            [
                                'name' => 'booking:booking:cancel',
                                'meta' => new Meta([
                                    'title' => '取消预约',
                                    'i18n' => 'baseMenu.booking.bookingCancel',
                                    'type' => 'B',
                                ]),
                            ],
                        ],
                    ],
                    [
                        'name' => 'booking:calendar',
                        'path' => '/booking/calendar',
                        'component' => 'booking/views/calendar/index',
                        'meta' => new Meta([
                            'title' => '日历',
                            'type' => 'M',
                            'hidden' => 0,
                            'icon' => 'ri:calendar-2-line',
                            'i18n' => 'baseMenu.booking.calendar',
                            'componentPath' => 'modules/',
                            'componentSuffix' => '.vue',
                            'breadcrumbEnable' => 1,
                            'copyright' => 1,
                            'cache' => 1,
                            'affix' => 0,
                        ]),
                        'children' => [
                            [
                                'name' => 'booking:calendar:list',
                                'meta' => new Meta([
                                    'title' => '日历数据',
                                    'i18n' => 'baseMenu.booking.calendarList',
                                    'type' => 'B',
                                ]),
                            ],
                        ],
                    ],
                    [
                        'name' => 'booking:shop',
                        'path' => '/booking/shops',
                        'component' => 'booking/views/shop/index',
                        'meta' => new Meta([
                            'title' => '店铺',
                            'type' => 'M',
                            'hidden' => 0,
                            'icon' => 'ri:store-2-line',
                            'i18n' => 'baseMenu.booking.shop',
                            'componentPath' => 'modules/',
                            'componentSuffix' => '.vue',
                            'breadcrumbEnable' => 1,
                            'copyright' => 1,
                            'cache' => 1,
                            'affix' => 0,
                        ]),
                        'children' => [
                            [
                                'name' => 'booking:shop:list',
                                'meta' => new Meta([
                                    'title' => '店铺列表',
                                    'i18n' => 'baseMenu.booking.shopList',
                                    'type' => 'B',
                                ]),
                            ],
                        ],
                    ],
                    [
                        'name' => 'booking:service',
                        'path' => '/booking/services',
                        'component' => 'booking/views/service/index',
                        'meta' => new Meta([
                            'title' => '服务',
                            'type' => 'M',
                            'hidden' => 0,
                            'icon' => 'ri:service-line',
                            'i18n' => 'baseMenu.booking.service',
                            'componentPath' => 'modules/',
                            'componentSuffix' => '.vue',
                            'breadcrumbEnable' => 1,
                            'copyright' => 1,
                            'cache' => 1,
                            'affix' => 0,
                        ]),
                        'children' => [
                            ['name' => 'booking:service:list', 'meta' => new Meta(['title' => '服务列表', 'i18n' => 'baseMenu.booking.serviceList', 'type' => 'B'])],
                            ['name' => 'booking:service:save', 'meta' => new Meta(['title' => '保存服务', 'i18n' => 'baseMenu.booking.serviceSave', 'type' => 'B'])],
                            ['name' => 'booking:service:delete', 'meta' => new Meta(['title' => '删除服务', 'i18n' => 'baseMenu.booking.serviceDelete', 'type' => 'B'])],
                        ],
                    ],
                    [
                        'name' => 'booking:location',
                        'path' => '/booking/locations',
                        'component' => 'booking/views/location/index',
                        'meta' => new Meta([
                            'title' => '门店',
                            'type' => 'M',
                            'hidden' => 0,
                            'icon' => 'ri:map-pin-line',
                            'i18n' => 'baseMenu.booking.location',
                            'componentPath' => 'modules/',
                            'componentSuffix' => '.vue',
                            'breadcrumbEnable' => 1,
                            'copyright' => 1,
                            'cache' => 1,
                            'affix' => 0,
                        ]),
                        'children' => [
                            ['name' => 'booking:location:list', 'meta' => new Meta(['title' => '门店列表', 'i18n' => 'baseMenu.booking.locationList', 'type' => 'B'])],
                            ['name' => 'booking:location:save', 'meta' => new Meta(['title' => '保存门店', 'i18n' => 'baseMenu.booking.locationSave', 'type' => 'B'])],
                            ['name' => 'booking:location:delete', 'meta' => new Meta(['title' => '删除门店', 'i18n' => 'baseMenu.booking.locationDelete', 'type' => 'B'])],
                        ],
                    ],
                    [
                        'name' => 'booking:staff',
                        'path' => '/booking/staff',
                        'component' => 'booking/views/staff/index',
                        'meta' => new Meta([
                            'title' => '员工',
                            'type' => 'M',
                            'hidden' => 0,
                            'icon' => 'ri:user-star-line',
                            'i18n' => 'baseMenu.booking.staff',
                            'componentPath' => 'modules/',
                            'componentSuffix' => '.vue',
                            'breadcrumbEnable' => 1,
                            'copyright' => 1,
                            'cache' => 1,
                            'affix' => 0,
                        ]),
                        'children' => [
                            ['name' => 'booking:staff:list', 'meta' => new Meta(['title' => '员工列表', 'i18n' => 'baseMenu.booking.staffList', 'type' => 'B'])],
                            ['name' => 'booking:staff:save', 'meta' => new Meta(['title' => '保存员工', 'i18n' => 'baseMenu.booking.staffSave', 'type' => 'B'])],
                            ['name' => 'booking:staff:delete', 'meta' => new Meta(['title' => '删除员工', 'i18n' => 'baseMenu.booking.staffDelete', 'type' => 'B'])],
                        ],
                    ],
                    [
                        'name' => 'booking:schedule',
                        'path' => '/booking/schedules',
                        'component' => 'booking/views/schedule/index',
                        'meta' => new Meta([
                            'title' => '排班',
                            'type' => 'M',
                            'hidden' => 0,
                            'icon' => 'ri:time-line',
                            'i18n' => 'baseMenu.booking.schedule',
                            'componentPath' => 'modules/',
                            'componentSuffix' => '.vue',
                            'breadcrumbEnable' => 1,
                            'copyright' => 1,
                            'cache' => 1,
                            'affix' => 0,
                        ]),
                        'children' => [
                            ['name' => 'booking:schedule:list', 'meta' => new Meta(['title' => '排班列表', 'i18n' => 'baseMenu.booking.scheduleList', 'type' => 'B'])],
                            ['name' => 'booking:schedule:save', 'meta' => new Meta(['title' => '保存排班', 'i18n' => 'baseMenu.booking.scheduleSave', 'type' => 'B'])],
                            ['name' => 'booking:schedule:delete', 'meta' => new Meta(['title' => '删除排班', 'i18n' => 'baseMenu.booking.scheduleDelete', 'type' => 'B'])],
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
