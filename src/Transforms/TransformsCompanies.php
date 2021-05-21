<?php

namespace TestMonitor\Custify\Transforms;

use TestMonitor\Custify\Validator;
use TestMonitor\Custify\Resources\Company;
use TestMonitor\Custify\Resources\CustomAttributes;

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
            'signed_up_at' => $company['signed_up_at'] ?? '',
            'website' => $company['website'] ?? '',
            'industry' => $company['industry'] ?? '',
            'size' => $company['size'] ?? '',
            'plan' => $company['plan'] ?? '',
            'churned' => $company['churned'] ?? '',
            'ownersAccount' => $company['owners_account'] ?? '',
            'ownersCsm' => $company['owners_csm'] ?? '',

            'custom_attributes' => new CustomAttributes($company['custom_attributes'] ?? []),
        ]);
    }

    /**
     * @param Company $company
     *
     * @return array
     */
    protected function toCustifyCompany(Company $company): array
    {
        return array_filter([
            'company_id' => $company->company_id,
            'name' => $company->name,
            'website' => $company->website,
            'industry' => $company->industry,
            'size' => $company->size,
            'plan' => $company->plan,
            'signed_up_at' => $company->signedUpAt,
            'churned' => $company->churned,
            'owners_account' => $company->ownersAccount,
            'owners_csm' => $company->ownersCsm,

            'custom_attributes' => $company->customAttributes->toArray(),
        ]);
    }
}
