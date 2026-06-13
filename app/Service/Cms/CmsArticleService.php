<?php

declare(strict_types=1);

namespace App\Service\Cms;

use App\Model\Cms\CmsArticle;
use App\Repository\Cms\CmsArticleRepository;
use App\Service\IService;
use App\Support\Slugifier;
use Hyperf\Collection\Collection;
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
        $paginator = $this->articlesByCategory($categoryId, $page, 9, $name);

        return [
            'paginator' => $paginator,
            'pageNumbers' => range(1, $paginator->lastPage()),
        ];
    }

    public function serviceList(int $categoryId, int $page, string $name = ''): array
    {
        $paginator = $this->articlesByCategory($categoryId, $page, 12, $name);

        return [
            'paginator' => $paginator,
            'pageNumbers' => range(1, $paginator->lastPage()),
        ];
    }

    public function articleBySlugAndCategory(string $slug, int $categoryId): ?CmsArticle
    {
        return $this->repository->findPublishedBySlugAndCategory($slug, $categoryId);
    }

    /**
     * @return Collection<int, CmsArticle>
     */
    public function latestArticlesByCategory(int $categoryId, int $limit = 1): Collection
    {
        return $this->repository->latestPublishedByCategory($categoryId, $limit);
    }

    /**
     * @return Collection<int, CmsArticle>
     */
    public function articlesByCategoryAll(int $categoryId): Collection
    {
        return $this->repository->publishedByCategory($categoryId);
    }

    public function incrementViews(int $id): void
    {
        $this->repository->incrementViews($id);
    }

    public function create(array $data): mixed
    {
        $title = (string) ($data['title'] ?? '');
        $inputSlug = $data['slug'] ?? null;
        unset($data['slug']);

        $data['slug'] = 'pending-' . uniqid('', true);
        $article = parent::create($data);

        $source = is_string($inputSlug) && $inputSlug !== '' ? $inputSlug : $title;
        $slug = Slugifier::unique($source, (int) $article->id, fn (string $s, int $id) => $this->repository->existsBySlug($s, $id));
        if ($slug !== $article->slug) {
            $this->repository->updateById($article->id, ['slug' => $slug]);
            $article->slug = $slug;
        }

        return $article;
    }

    public function updateById(mixed $id, array $data): mixed
    {
        if (array_key_exists('slug', $data)) {
            $article = $this->findById($id);
            $inputSlug = $data['slug'];
            $source = is_string($inputSlug) && $inputSlug !== '' ? $inputSlug : (string) ($data['title'] ?? $article?->title ?? '');
            $data['slug'] = Slugifier::unique($source, (int) $id, fn (string $s, int $articleId) => $this->repository->existsBySlug($s, $articleId));
        }

        return parent::updateById($id, $data);
    }
}
