<?php

namespace PP\WebPortal\Module\Model;

use PP\WebPortal\Module\Model\UserModel;
use PP\WebPortal\Module\Model\ClaimModel;
use PP\WebPortal\Module\Model\AbstractClass\ModelAbstract;

class PolicyModel extends ModelAbstract
{
    private $currency;

    public $holder;

    public $dependents;

    public $claimList = [
        'Save' => [],
        'Submit' => [],
    ];

    public function __construct($data, $currency)
    {
        $this->data = $data;
        $this->currency = $currency;
        $this->init();
    }

    public function getKey()
    {
        return $this->data['pivot']['id'];
    }

    private function init()
    {
        $this->data['medical_currency_display'] = $this->data['medical_currency'].' '.$this->currency[$this->data['medical_currency']];
        foreach ($this->data['policyuser'] as $user) {
            $this->setUser($user);
        }
    }

    private function setUser($user)
    {
        if ($user['relationship'] ===  'PolicyHolder') {
            $this->holder = new UserModel($user);
        } else {
            $this->dependents[$user['ppmid']] = new UserModel($user);
        }
    }

    public function isHolder($id)
    {
        return $id === $this->holder['ppmid'];
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->data['status'] === 'Active' ? true : false;
    }

    /**
     * @return string
     */
    public function getPremiumPaid()
    {
        return $this->data['pivot']['premium_paid'];
    }

    /**
     * @return array
     */
    public function getAdvisor()
    {
        return $this->data['advisor'];
    }

    public function setClaimList($ar){
        foreach ( $ar as $status => $claims){
            foreach ( $claims as $claim) {
                $this->claimList[$status][] = new ClaimModel($claim);
            }
        }
    }

    /*
    public function getDependents()
    {
        return array_filter($this->data['policyuser'], function ($ar) {
            return $ar['relationship'] !==  'PolicyHolder';
        });
    }

    public function getHolder()
    {
        return array_filter($this->data['policyuser'], function ($ar) {
            return $ar['relationship'] ===  'PolicyHolder';
        });
    }
 */
}
