<?php

namespace Modules\Company\Http\Controllers\Base;

use Modules\Core\Http\Controllers\BaseController;
use Modules\User\Entities\Doctrine\Role;
use Modules\User\Entities\Doctrine\User;
use RepoFactory;
use Modules\Event\Entities\Doctrine\Event;
use Modules\Company\Entities\Doctrine\Employee;
use Modules\Company\Entities\Doctrine\Company;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Repositories\Doctrine\Helper\QueryMapper;
use Illuminate\Support\Collection;

use EntityManager;

class EmployeeController extends BaseController
{
    protected $entityClassName = 'Modules\Company\Entities\Doctrine\Employee';
    protected $validationRules = [
        'company' => 'required|exists:Modules\Company\Entities\Doctrine\Company,id',
        'name' => 'required|unique:Modules\User\Entities\Doctrine\User,username|alpha_dash|min:3|max:255',
        'first_name' => 'required|string|min:1|max:255',
        'last_name' => 'required|string|min:1|max:255',
        // optional fields
        'password' => 'min:6|max:255',
//        'secret_word' => 'string|min:3|max:255',
        'middle_name' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'email' => 'email|max:255',
        'position' => 'nullable|string|min:1|max:255',
        'photo' => 'nullable|mimes:jpeg,png,bmp,gif|max:10240', //10 MB
        'date_birth' => 'nullable|date_format:d-m-Y',
        'skype' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:100',
        'mphone' => 'nullable|string|max:100',
        'fax' => 'nullable|string|max:100',
        'account' => 'nullable|numeric|max:100',
        'wechat_login' => 'nullable|string|min:3|max:255',
        'important' => 'nullable|boolean',
        'locale' => 'string|min:2|max:255'
    ];

    protected $entityFilesFields = ['photo'];
    protected $filesLocationPath = 'company/{{company}}/employee/{{id}}';

    protected $eventFieldNameInUser = 'events';
    protected $userFieldNameInEvent = 'speakers';

    /**
     * @param object $employee
     * @param array $data
     * @return object
     */
    public function storePostProcessing($employee, $data)
    {
        $employee = parent::storePostProcessing($employee, $data);
        $employee->getUser()->addRole(RepoFactory::create(Role::class)->findOneBy(['name' => 'client']));
        return $employee;
    }

    /**
     * @param object $employee
     * @param array $data
     * @return object
     */
    public function updatePostProcessing($employee, $data)
    {
        parent::storePostProcessing($employee, $data);
        $user = $employee->getUser();
        $user->setData($data);
        RepoFactory::create(User::class)->updateModel($user);
        return $employee;
    }

    /**
     * @param $id
     * @return array
     */
    public function getValidationRulesForUpdate($id): array
    {
        if (count($this->validationRulesForUpdate)) return $this->validationRulesForUpdate;

        $this->validationRulesForUpdate = $this->validationRules;
        foreach ($this->validationRules as $key => $value) {
            $this->validationRulesForUpdate[$key] = str_replace('required|', '', $value);
            $this->validationRulesForUpdate[$key] = preg_replace('/(unique:[\w, \\\]+)(\|)/i', '', $this->validationRulesForUpdate[$key]);
        }

        return $this->validationRulesForUpdate;
    }

    /**
     * @return mixed
     * @throws \App\Exceptions\NotFoundException
     */
    public function paginate()
    {
        Employee::$exportCompanyRelation = true;
        Company::$hideDescription = true;
        Company::$hideProductsDescription = true;

        return parent::paginate();
    }

    /**
     * @param LengthAwarePaginator $paginationResult
     * @return LengthAwarePaginator
     * @throws \App\Exceptions\DoctrineException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function paginatePostProcessing(LengthAwarePaginator $paginationResult)
    {
        if (Employee::$loadFirstEvent) {
            $paginationResult = $this->addFirstEventToPaginatorResult($paginationResult);
        }

        return $paginationResult;
    }

    /**
     * @param LengthAwarePaginator $paginationResult
     * @return LengthAwarePaginator
     * @throws \App\Exceptions\DoctrineException
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function addFirstEventToPaginatorResult(LengthAwarePaginator $paginationResult)
    {
        $employees = $paginationResult->items();

        $queryMapper = new QueryMapper(Event::class);

        $data = $this->preparePaginateDataForEvent();

        foreach ($employees as $employee) {
            Employee::$loadFirstEvent = true; //because is set to false inside $firstEvent->toArray()

            $data['criteria'] = array_merge($data['criteria'], [
                $this->userFieldNameInEvent . '.id' => $employee->getId()
            ]);

            $query = $queryMapper->buildQuery($data['criteria'], $data['order_by'], $data['limit']);
            $events = $query->getResult();

            $firstEvent = is_array($events) ? array_shift($events) : null;
            $employee->firstEvent = $firstEvent ? $firstEvent->toArray() : null;
        }

        $paginationResult->setCollection(new Collection($employees));

        return $paginationResult;
    }

    /**
     * @return array
     */
    protected function preparePaginateDataForEvent(): array
    {
        return [
            'criteria' => $this->prepareFilterCriteriaForEvent(),
            'order_by' => [
                'start_date' => 'asc'
            ],
            'limit' => 1,
        ];
    }

    /**
     * @return array
     */
    protected function prepareFilterCriteriaForEvent(): array
    {
        $criteriaForEvent = [];
        $criteriaForEmployee = request()->input('filter');

        if ($criteriaForEmployee) {
            foreach ($criteriaForEmployee as $key => $value) {
                if (strpos($key, $this->eventFieldNameInUser . '.') === 0) {
                    $keyForEvent = str_replace($this->eventFieldNameInUser . '.', '', $key);

                    $criteriaForEvent[$keyForEvent] = $value;
                }
            }
        }

        return $criteriaForEvent;
    }
}
