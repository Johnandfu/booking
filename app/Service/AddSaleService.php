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

namespace App\Service;

use App\Model\Attachment;
use App\Repository\AttachmentRepository;
use Mine\Upload\UploadInterface;
use FriendsOfHyperf\Http\Client\Http;
use Psr\SimpleCache\CacheInterface;

/**
 * @extends IService<Attachment>
 */
final class AddSaleService extends IService
{
    public function __construct(

        protected readonly UploadInterface $upload,
        protected readonly  CacheInterface $cache
    ) {}

    /**
     * addsale
     */
    public function getAddSale($id = 0, $clear = false)
    {

        $all_data = $this->cache->get('addsale', []);
        if ($clear || empty($all_data)) {
            $data = Http::withHeaders(['key' => config('pdm.pdm_secret'), 'key' => config('pdm.pdm_key')])->retry(3, 300)
                ->get('https://pdm.jialenet.com/rest/custom/addsale')->json();
            if (data_get($data, 'error') !== 0) {
                return [];
            }
            $data = data_get($data, 'data') ?: [];
            if ($data) {
                $data = array_column($data, null, 'id');
                $this->cache->set('addsale', $data, 3600 * 24 * 30);
            }
            return array_values($data);
        } else {

            $data = $id ? data_get($all_data, $id) : array_values($all_data);
        }

        return $data;
    }
}
