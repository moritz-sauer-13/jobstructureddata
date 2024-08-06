<?php

namespace JobStructuredData;

use SilverStripe\Control\Controller;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\SiteConfig\SiteConfig;

class JobExtension extends DataExtension
{
    private static $db = [
        'Address' => 'Text',
        'City' => 'Text',
        'Zip' => 'Text',
        'AddressLocality' => 'Text',
        'Country' => 'Text',
        'SalaryPerHour' => 'Float',
    ];

    public function updateJobCMSFields($fields)
    {
        $fields->removeByName([
            'Address',
            'City',
            'Zip',
            'AddressLocality',
            'Country',
        ]);
        $siteConfig = SiteConfig::current_site_config();
        if(!$siteConfig->JobStructuredDataGlobalAddressEnabled){
            $fields->addFieldsToTab('Root.Main', [
                ToggleCompositeField::create('Address', 'Adresse für Google', [
                    TextField::create('Address', 'Straße und Hausnummer'),
                    TextField::create('City', 'Stadt'),
                    TextField::create('Zip', 'PLZ'),
                    TextField::create('AddressLocality', 'Region')
                        ->setDescription('z.B. Bundesland'),
                    TextField::create('Country', 'Land')
                ])
            ]);
        }

        $fields->addFieldsToTab('Root.Main', [
            NumericField::create('SalaryPerHour', 'Stundenlohn')
                ->setDescription('Wird für die Stellenanzeigen bei Google verwendet.')
        ], 'Details');
    }

    public function AbsoluteLink(): string
    {
        return Controller::join_links($this->owner->JobsPage()->Link(), '/job/', $this->owner->URLSegment);
    }

    public function get_x_months_to_the_future(): string
    {
        $LastEditedTitle = time();
        if($this->owner->JobsPageID > 0){
            $EndeTime = date("Y-m-d H:i:s", strtotime("+5 Months", $LastEditedTitle));
        } else {
            $EndeTime = date("Y-m-d H:i:s", strtotime("-1 Months", $LastEditedTitle));
        }

        return $EndeTime;
    }

    public function contentForGoogle(): array|string
    {
        return (str_replace('"', '\"', $this->owner->Content));
    }

    public function getCurrentData($type)
    {
        $siteConfig = SiteConfig::current_site_config();
        if($siteConfig->JobStructuredDataGlobalAddressEnabled){
            $field = 'JobStructuredDataGlobal' . ucfirst($type);

            if ($siteConfig->$field) {
                return $siteConfig->$field;
            }
        }

        return $this->owner->$type;
    }

    public function currentAddress()
    {
        return $this->getCurrentData('Address');
    }

    public function currentCity()
    {
        return $this->getCurrentData('City');
    }

    public function currentZip()
    {
        return $this->getCurrentData('Zip');
    }

    public function currentAddressLocality()
    {
        return $this->getCurrentData('AddressLocality');
    }

    public function currentCountry()
    {
        return $this->getCurrentData('Country') ? $this->getCurrentData('Country') : 'Deutschland';
    }
}