<?php

declare(strict_types=1);

namespace App\Model\Cms;

use Carbon\Carbon;
use Hyperf\DbConnection\Model\Model as MineModel;

/**
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property string $content
 * @property string|null $ip
 * @property string|null $user_agent
 * @property int $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class CmsContactMessage extends MineModel
{
    public const STATUS_PENDING = 0;

    public const STATUS_HANDLED = 1;

    protected ?string $table = 'cms_contact_message';

    protected array $fillable = [
        'id', 'name', 'email', 'phone', 'content', 'ip', 'user_agent', 'status', 'created_at', 'updated_at',
    ];

    protected array $casts = [
        'id' => 'integer',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
