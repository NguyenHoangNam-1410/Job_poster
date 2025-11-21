<?php
class Employer
{
    public $id;
    public $company_name;
    public $website;
    public $logo;
    public $contact_phone;
    public $contact_email;
    public $contact_person;
    public $description;
    public $user_id;

    public function __construct(
        $id = null,
        $company_name = null,
        $website = null,
        $logo = null,
        $contact_phone = null,
        $contact_email = null,
        $contact_person = null,
        $description = null,
        $user_id = null
    ) {
        $this->id = $id;
        $this->company_name = $company_name;
        $this->website = $website;
        $this->logo = $logo;
        $this->contact_phone = $contact_phone;
        $this->contact_email = $contact_email;
        $this->contact_person = $contact_person;
        $this->description = $description;
        $this->user_id = $user_id;
    }

    //Getters
    public function getId()
    {
        return $this->id;
    }
    public function getCompanyName()
    {
        return $this->company_name;
    }
    public function getWebsite()
    {
        return $this->website;
    }
    public function getLogo()
    {
        return $this->logo;
    }
    public function getContactPhone()
    {
        return $this->contact_phone;
    }
    public function getContactEmail()
    {
        return $this->contact_email;
    }
    public function getContactPerson()
    {
        return $this->contact_person;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getUserId()
    {
        return $this->user_id;
    }

    //Setters
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setCompanyName($company_name)
    {
        $this->company_name = $company_name;
    }
    public function setWebsite($website)
    {
        $this->website = $website;
    }
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }
    public function setContactPhone($contact_phone)
    {
        $this->contact_phone = $contact_phone;
    }
    public function setContactEmail($contact_email)
    {
        $this->contact_email = $contact_email;
    }
    public function setContactPerson($contact_person)
    {
        $this->contact_person = $contact_person;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
}