<?php

namespace TestMonitor\Custify\Actions;

use TestMonitor\Custify\Validator;
use TestMonitor\Custify\Resources\Company;
use TestMonitor\Custify\Exceptions\NotFoundException;
use TestMonitor\Custify\Transforms\TransformsCompanies;

trait ManagesCompanies
{
    use TransformsCompanies;

    /**
     * Get a list of of companies.
     *
     * @param int $page
     * @param int $limit
     *
     * @throws \TestMonitor\Custify\Exceptions\InvalidDataException
     * @return \TestMonitor\Custify\Resources\Company[]
     */
    public function companies($page = 1, $limit = 10): array
    {
        $response = $this->get('company/all', [
            'query' => ['itemsPerPage' => $limit, 'page' => $page],
        ]);

        return $this->fromCustifyCompanies($response['companies']);
    }

    /**
     * Get a single company.
     *
     * @param string $id
     *
     * @throws \TestMonitor\Custify\Exceptions\NotFoundException
     * @throws \TestMonitor\Custify\Exceptions\InvalidDataException
     * @return \TestMonitor\Custify\Resources\Company
     */
    public function company(string $id): Company
    {
        $response = $this->get("company/{$id}");

        Validator::keysExists($response, ['companies']);

        // Simulate a not found response when the id does not exists.
        if (empty($response['companies'])) {
            throw new NotFoundException();
        }

        return $this->fromCustifyCompany($response['companies'][0]);
    }

    /**
     * Get a single company by company id.
     *
     * @param string $companyId
     *
     * @throws \TestMonitor\Custify\Exceptions\InvalidDataException
     * @throws \TestMonitor\Custify\Exceptions\NotFoundException
     * @return \TestMonitor\Custify\Resources\Company
     */
    public function companyByCompanyId(string $companyId): Company
    {
        $response = $this->get("company?company_id={$companyId}");

        Validator::keysExists($response, ['companies']);

        // Simulate a not found response when the company_id does not exists.
        if (empty($response['companies'])) {
            throw new NotFoundException();
        }

        return $this->fromCustifyCompany($response['companies'][0]);
    }

    /**
     * Create or update a company.
     *
     * @param \TestMonitor\Custify\Resources\Company $company
     *
     *@throws \TestMonitor\Custify\Exceptions\InvalidDataException
     * @return \TestMonitor\Custify\Resources\Company
     */
    public function createOrUpdateCompany(Company $company): Company
    {
        $response = $this->post('company', ['json' => $this->toCustifyCompany($company)]);

        return $this->fromCustifyCompany($response);
    }

    /**
     * Delete a company.
     *
     * @param \TestMonitor\Custify\Resources\Company $company
     *
     * @return bool
     */
    public function deleteCompany(Company $company)
    {
        $response = $this->delete("company/{$company->id}");

        return (bool) ($response['deleted'] ?? false);
    }

    /**
     * Delete a company by its company id.
     *
     * @param string $companyId
     *
     * @return bool
     */
    public function deleteCompanyByCompanyId(string $companyId)
    {
        $response = $this->delete("company?company_id={$companyId}");

        return (bool) ($response['deleted'] ?? false);
    }
}
