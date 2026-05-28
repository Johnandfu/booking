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

namespace App\Model;

use Carbon\Carbon;
use Hyperf\Database\Model\Events\Creating;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id 主键
 * @property string $ip 登录IP地址
 * @property string $os 操作系统
 * @property string $browser 浏览器
 * @property int $status 状态 (1成功 2失败)
 * @property string $remark 备注
 */
class UserFilterLog extends Model
{
    public bool $timestamps = true;

    const STATUS_SUCCESS = 1;
    const STATUS_FAIL = 0;

    const STATUS = [
        self::STATUS_SUCCESS => '成功',
        self::STATUS_FAIL => '失败'
    ];
    /**
     * The attributes that are mass assignable.
     */
    protected array $guarded = ['id'];
}
