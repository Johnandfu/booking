<?php

declare(strict_types=1);

namespace App\Service\Cms;

use App\Model\Cms\CmsArticle;
use App\Repository\Cms\CmsArticleRepository;
use App\Service\IService;
use Hyperf\Contract\LengthAwarePaginatorInterface;

/**
 * @extends IService<CmsArticle>
 */
final class CmsArticleService extends IService
{
    public function __construct(
        protected readonly CmsArticleRepository $repository
    ) {}

    public function articlesByCategory(int $categoryId, int $page, int $pageSize = 10, string $name = ''): LengthAwarePaginatorInterface
    {
        return $this->repository->paginatePublishedByCategory($categoryId, $page, $pageSize, $name);
    }

    public function newsList(int $categoryId, int $page, string $name = ''): array
    {
        $paginator = $this->articlesByCategory($categoryId, $page, 10, $name);
        $items = $paginator->items();

        return [
            'paginator' => $paginator,
            'featuredItems' => array_slice($items, 0, 2),
            'listingItems' => $page > 1 ? $items : array_slice($items, 2),
        ];
    }

    public function serviceList(int $categoryId, int $page, string $name = ''): array
    {
        $paginator = $this->articlesByCategory($categoryId, $page, 12, $name);
        $items = $paginator->items();

        return [
            'paginator' => $paginator,
            'featuredItems' => array_slice($items, 0, 2),
            'listingItems' => array_slice($items, 2),
            'pageNumbers' => range(1, $paginator->lastPage()),
        ];
    }

    public function articleBySlugAndCategory(string $slug, int $categoryId): ?CmsArticle
    {
        return $this->repository->findPublishedBySlugAndCategory($slug, $categoryId);
    }

    public function incrementViews(int $id): void
    {
        $this->repository->incrementViews($id);
    }
}
