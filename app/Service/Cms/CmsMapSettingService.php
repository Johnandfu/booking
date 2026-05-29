<?php

declare(strict_types=1);

namespace App\Service\Cms;

use App\Model\Cms\CmsMapSetting;
use App\Repository\Cms\CmsMapSettingRepository;
use App\Service\IService;
use FriendsOfHyperf\Http\Client\Http;
use RuntimeException;

use function Hyperf\Support\env;

/**
 * @extends IService<CmsMapSetting>
 */
final class CmsMapSettingService extends IService
{
    private const DEFAULT_SETTING = [
        'title' => 'Brisbane, Queensland, Australia',
        'address' => 'Brisbane QLD Australia',
        'latitude' => null,
        'longitude' => null,
        'zoom' => 14,
    ];

    public function __construct(
        protected readonly CmsMapSettingRepository $repository
    ) {}

    public function setting(): array
    {
        $setting = $this->repository->first();
        if ($setting === null) {
            return self::DEFAULT_SETTING;
        }

        return array_merge(self::DEFAULT_SETTING, $setting->toArray());
    }

    public function saveSetting(array $data): CmsMapSetting
    {
        $data['zoom'] = min(20, max(1, (int) ($data['zoom'] ?? self::DEFAULT_SETTING['zoom'])));
        return $this->repository->saveSetting($data);
    }

    public function geocode(string $address): array
    {
        $apiKey = (string) env('GOOGLE_MAPS_API_KEY', '');
        if ($apiKey === '') {
            throw new RuntimeException('请先配置 GOOGLE_MAPS_API_KEY');
        }

        $response = Http::timeout(10)->get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $address,
            'key' => $apiKey,
        ])->json();

        if (($response['status'] ?? '') !== 'OK') {
            throw new RuntimeException('地址解析失败：' . ($response['error_message'] ?? $response['status'] ?? 'UNKNOWN'));
        }

        $location = $response['results'][0]['geometry']['location'] ?? null;
        if (! is_array($location) || ! isset($location['lat'], $location['lng'])) {
            throw new RuntimeException('地址解析结果缺少坐标');
        }

        return [
            'address' => $response['results'][0]['formatted_address'] ?? $address,
            'latitude' => (float) $location['lat'],
            'longitude' => (float) $location['lng'],
        ];
    }
}
