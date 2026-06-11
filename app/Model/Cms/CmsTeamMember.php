<?php

declare(strict_types=1);

namespace App\Model\Cms;

use Carbon\Carbon;
use Hyperf\DbConnection\Model\Model as MineModel;

/**
 * @property int $id
 * @property string $name
 * @property string|null $position
 * @property string|null $phone
 * @property string|null $avatar
 * @property int $sort
 * @property int $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class CmsTeamMember extends MineModel
{
    public const STATUS_DISABLED = 0;

    public const STATUS_ENABLED = 1;

    protected ?string $table = 'cms_team_member';

    protected array $fillable = [
        'id', 'name', 'position', 'phone', 'avatar', 'sort', 'status', 'created_at', 'updated_at',
    ];

    protected array $casts = [
        'id' => 'integer',
        'sort' => 'integer',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
