<?php

namespace src\Extensions;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\ORM\DataExtension;

class SiteConfigExtension extends DataExtension
{
    private static $db = [
        'JobStructuredDataEnabled' => 'Boolean(1)',
        'JobStructuredDataGlobalAddressEnabled' => 'Boolean(0)',
        'JobStructuredDataGlobalAddress' => 'Text',
        'JobStructuredDataGlobalCity' => 'Text',
        'JobStructuredDataGlobalZip' => 'Text',
        'JobStructuredDataGlobalAddressLocality' => 'Text',
        'JobStructuredDataGlobalCountry' => 'Text',
    ];

    private static $has_one = [
        'Logo' => Image::class
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->removeByName([
            'JobStructuredDataGlobalAddressEnabled',
            'JobStructuredDataGlobalAddress',
            'JobStructuredDataGlobalCity',
            'JobStructuredDataGlobalZip',
            'JobStructuredDataGlobalAddressLocality',
            'JobStructuredDataGlobalCountry',
        ]);

        $fields->addFieldsToTab('Root.Jobs', [
            LiteralField::create('JobStructuredDataInfo', '<p>Die strukturierten Daten für Jobs werden in den Suchergebnissen von Google angezeigt.</p>'),
            CheckboxField::create('JobStructuredDataEnabled', 'Strukturierte Daten für Jobs aktivieren?'),
            UploadField::create('Logo', 'Logo')
                ->setFolderName('Logos'),
            CheckboxField::create('JobStructuredDataGlobalAddressEnabled', 'Globale Adresse verwenden'),
        ]);

        if($this->owner->JobStructuredDataGlobalAddressEnabled){
            $fields->addFieldsToTab('Root.Jobs', [
                ToggleCompositeField::create('Address', 'Adresse', [
                    TextField::create('JobStructuredDataGlobalAddress', 'Straße und Hausnummer'),
                    TextField::create('JobStructuredDataGlobalCity', 'Stadt'),
                    TextField::create('JobStructuredDataGlobalZip', 'PLZ'),
                    TextField::create('JobStructuredDataGlobalAddressLocality', 'Region')
                        ->setDescription('z.B. Bundesland'),
                    TextField::create('JobStructuredDataGlobalCountry', 'Land')
                ])
            ]);
        }
    }
}