<?php

declare(strict_types=1);

namespace App\Filesystem;

use Hyperf\Filesystem\Contract\AdapterFactoryInterface;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\UnixVisibility\PortableVisibilityConverter;

final class LocalAdapterFactory implements AdapterFactoryInterface
{
    public function make(array $options)
    {
        $visibility = PortableVisibilityConverter::fromArray([
            'file' => [
                'public' => 0644,
                'private' => 0644,
            ],
            'dir' => [
                'public' => 0755,
                'private' => 0755,
            ],
        ]);

        return new LocalFilesystemAdapter($options['root'], $visibility);
    }
}
