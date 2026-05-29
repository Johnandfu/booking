<?php

declare(strict_types=1);

namespace App\Repository\Cms;

use App\Model\Cms\CmsMapSetting;
use App\Repository\IRepository;

/**
 * @extends IRepository<CmsMapSetting>
 */
final class CmsMapSettingRepository extends IRepository
{
    public function __construct(
        protected readonly CmsMapSetting $model
    ) {}

    public function first(): ?CmsMapSetting
    {
        return $this->model->newQuery()->orderBy('id')->first();
    }

    public function saveSetting(array $data): CmsMapSetting
    {
        return $this->model->newQuery()->updateOrCreate(['id' => 1], $data);
    }
}
