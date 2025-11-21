<?php
require_once __DIR__ . '/../dao/EmployerDAO.php';
class CompanyService
{
    private $employerDAO;

    public function __construct()
    {
        $this->employerDAO = new EmployerDAO();
    }

    public function getEmployerByUserId($userId)
    {
        return $this->employerDAO->getEmployerByUserId($userId);
    }
    public function createCompanyProfile($userId, $data)
    {
        // Validate data
        if (empty($data['name']))
            throw new Exception("Company name is required.");
        if (empty($data['contact']))
            throw new Exception("Contact person is required.");
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL))
            throw new Exception("Valid email required.");
        if (empty($data['phone']))
            throw new Exception("Contact phone required.");
        if (empty($data['website']) || !filter_var($data['website'], FILTER_VALIDATE_URL))
            throw new Exception("Valid website required.");
        if (empty($data['description']))
            throw new Exception("Description required.");
        if (empty($data['logo']))
            throw new Exception("Logo required.");
        // Create Employer object
        $employer = new Employer(
            null,
            $data['name'],
            $data['website'],
            $data['logo'],
            $data['phone'],
            $data['email'],
            $data['contact'],
            $data['description'],
            $userId
        );
        // Call DAO
        return $this->employerDAO->create($employer);
    }
    public function updateCompanyProfile($employer, $data)
    {
        // Validate data
        if (empty($data['name']))
            throw new Exception("Company name is required.");
        if (empty($data['contact']))
            throw new Exception("Contact person is required.");
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL))
            throw new Exception("Valid email required.");
        if (empty($data['phone']))
            throw new Exception("Contact phone required.");
        if (empty($data['website']) || !filter_var($data['website'], FILTER_VALIDATE_URL))
            throw new Exception("Valid website required.");
        if (empty($data['description']))
            throw new Exception("Description required.");
        if (empty($data['logo']))
            throw new Exception("Logo required.");

        $employer->setCompanyName($data['name']);
        $employer->setContactPerson($data['contact']);
        $employer->setContactEmail($data['email']);
        $employer->setContactPhone($data['phone']);
        $employer->setWebsite($data['website']);
        $employer->setDescription($data['description']);
        $employer->setLogo($data['logo']);

        return $this->employerDAO->updateCompanyProfile($employer);
    }
}