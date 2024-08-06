<?php


namespace src\Extensions;

use Job\Job;
use SilverStripe\Control\Controller;
use SilverStripe\Dev\Debug;
use SilverStripe\ORM\DataExtension;
use SilverStripe\SiteConfig\SiteConfig;

class JobsPageExtension extends DataExtension
{
    public function MetaTags(&$tags)
    {
        if (!SiteConfig::current_site_config()->JobStructuredDataEnabled) {
            return $tags;
        }
        $controller = Controller::curr();
        $action = $controller->getAction();
        if ($action == 'job') {
            $params = $controller->getURLParams();
            $job = Job::get()->filter('URLSegment', $params['ID'])->first();
            $tags .= $this->owner->renderWith('JobMetaTags', ['Job' => $job, 'LogoLink' => $this->LogoLink()]);
        }
        return $tags;
    }

    private function LogoLink()
    {
        $siteConfig = SiteConfig::current_site_config();
        if ($siteConfig->LogoID > 0 && $siteConfig->Logo()->exists()) {
//            Debug::dump($siteConfig->Logo()->AbsoluteLink());die;
            return $siteConfig->Logo()->AbsoluteLink();
        }
        return null;
    }
}