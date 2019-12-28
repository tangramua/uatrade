<?php

namespace Modules\Company\Traits;

trait Ownable
{
    abstract public function getEmployees();

    /**
     * @param int $userId
     * @return bool
     */
    public function checkOwner(int $userId)
    {
        if ($employees = $this->getEmployees()) {
            foreach ($employees as $employee) {
                $employeeUserId = ($employeeUser = $employee->getUser()) ? $employeeUser->getId() : null;

                if ($userId === $employeeUserId) {
                    return true;
                }
            }
        }
        return false;
    }
}
