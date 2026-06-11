<?php

declare(strict_types=1);

namespace App\Http\Admin\Controller\Cms;

use App\Http\Admin\Controller\AbstractController;
use App\Http\Admin\Middleware\PermissionMiddleware;
use App\Http\Admin\Request\Cms\TeamMemberRequest;
use App\Http\Common\Middleware\AccessTokenMiddleware;
use App\Http\Common\Middleware\OperationMiddleware;
use App\Http\Common\Result;
use App\Service\Cms\CmsTeamMemberService;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Swagger\Annotation\Delete;
use Hyperf\Swagger\Annotation\Get;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\Post;
use Hyperf\Swagger\Annotation\Put;
use Mine\Access\Attribute\Permission;

#[HyperfServer(name: 'http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
#[Middleware(middleware: OperationMiddleware::class, priority: 98)]
final class TeamMemberController extends AbstractController
{
    public function __construct(
        private readonly CmsTeamMemberService $service
    ) {}

    #[Get(path: '/admin/cms/team-member/list', operationId: 'cmsTeamMemberList', summary: 'CMS 团队成员列表', tags: ['CMS'])]
    #[Permission(code: 'cms:team-member:list')]
    public function list(): Result
    {
        return $this->success($this->service->page($this->getRequestData(), $this->getCurrentPage(), $this->getPageSize()));
    }

    #[Post(path: '/admin/cms/team-member', operationId: 'cmsTeamMemberCreate', summary: '创建 CMS 团队成员', tags: ['CMS'])]
    #[Permission(code: 'cms:team-member:create')]
    public function create(TeamMemberRequest $request): Result
    {
        $this->service->create($request->validated());
        return $this->success();
    }

    #[Put(path: '/admin/cms/team-member/{id}', operationId: 'cmsTeamMemberUpdate', summary: '更新 CMS 团队成员', tags: ['CMS'])]
    #[Permission(code: 'cms:team-member:update')]
    public function update(int $id, TeamMemberRequest $request): Result
    {
        $this->service->updateById($id, $request->validated());
        return $this->success();
    }

    #[Delete(path: '/admin/cms/team-member/{id}', operationId: 'cmsTeamMemberDelete', summary: '删除 CMS 团队成员', tags: ['CMS'])]
    #[Permission(code: 'cms:team-member:delete')]
    public function delete(int $id): Result
    {
        $this->service->deleteById($id);
        return $this->success();
    }
}
