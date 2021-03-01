<?php

namespace TestMonitor\Custify\Transforms;

use TestMonitor\Custify\Validator;
use TestMonitor\Custify\Resources\Company;

trait TransformsCompanies
{
    /**
     * @param array $companies
     *
     * @throws \TestMonitor\Custify\Exceptions\InvalidDataException
     * @return \TestMonitor\Custify\Resources\Company[]
     */
    protected function fromCustifyCompanies($companies): array
    {
        Validator::isArray($companies);

        return array_map(function ($company) {
            return $this->fromCustifyCompany($company);
        }, $companies);
    }

    /**
     * @param array $company
     *
     * @throws \TestMonitor\Custify\Exceptions\InvalidDataException
     * @return \TestMonitor\Custify\Resources\Company
     */
    protected function fromCustifyCompany($company): Company
    {
        Validator::keysExists($company, ['id', 'company_id']);

        return new Company([
            'id' => $company['id'],
            'company_id' => $company['company_id'],
            'name' => $company['name'],
        ]);
    }

    /**
     * @param Company $company
     *
     * @return array
     */
    protected function toCustifyCompany(Company $company): array
    {
        return [
            'company_id' => $company->company_id,
            'name' => $company->name,
        ];
    }
}
