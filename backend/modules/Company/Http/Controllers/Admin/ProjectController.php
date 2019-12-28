<?php

namespace Modules\Company\Http\Controllers\Admin;

use App\Exceptions\NotFoundException;
use Modules\Company\Entities\Doctrine\Company;
use Modules\Company\Entities\Doctrine\Employee;
use Modules\Company\Http\Controllers\Base\ProjectController as BaseProjectController;
use Modules\Core\Traits\RelationMapper;
use RepoFactory;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class ProjectController extends BaseProjectController
{
    use RelationMapper;

    /**
     * @param $project_id
     * @return \Illuminate\Http\JsonResponse
     * @throws NotFoundException
     */
    public function membersList($project_id)
    {
        if ($project = $this->repository->find($project_id)){
            $members = $project->getMembers()->map(function ($value){
                return $value->toArray();
            });
        }else{
            throw new NotFoundException();
        }
        return $this->responseOkStatus('OK', 200, ['data'=>$members->toArray()]);
    }

    public function addEmployeeToProject(...$args)
    {
        return $this->addRelation($this->repository, RepoFactory::create(Employee::class), $args[0], $args[1], 'setMember');
    }

    public function removeEmployeeFromProject(...$args)
    {
        return $this->removeRelation($this->repository, RepoFactory::create(Employee::class), $args[0], $args[1], 'removeMember');
    }

    public function addCompanyToProject(...$args)
    {
        return $this->addRelation($this->repository, RepoFactory::create(Company::class), $args[0], $args[1], 'setCompany');
    }

    public function removeCompanyFromProject(...$args)
    {
        $this->repository->find($args[0])->removeCompany();
        return $this->responseOkStatus('OK');
    }

    public function listProjectFromCompany($company_id)
    {
        $companies= collect($this->repository->findBy(['company'=>$company_id]))
            ->map(function($value):array{
                return $value->toArray() ;
            });
        return $this->responseOkStatus('OK', 200, ['data'=>$companies->toArray()]);
    }

    public function listProjectFromEmployee($employee_id)
    {
        $employee = RepoFactory::create(Employee::class)->find($employee_id);
        $project = $employee->getProjects()
            ->map(function($value){
                return $value->toArray();
            });
        return $this->responseOkStatus('OK', 200, ['data'=>$project->toArray()]);
    }

}
