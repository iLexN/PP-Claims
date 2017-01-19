<?php

namespace PP\WebPortal\Module\Model;

use PP\WebPortal\Module\Model\AbstractClass\ModelAbstract;

class ClaimModel extends ModelAbstract
{
    public function __construct($data)
    {
        $this->data = $data;
        $this->init();
    }

    public function init()
    {
        $this->breakTreatmentDate();
        $this->checkBank();
        $this->checkCheque();
    }

    private function breakTreatmentDate()
    {
        $ar = explode('-', $this->data['date_of_treatment']);
        $this->data['treatment_yyyy'] = $ar[0];
        $this->data['treatment_mm'] = $ar[1];
        $this->data['treatment_dd'] = $ar[2];
    }

    public function checkCheque()
    {
        if (isset($this->data['cheque']) && empty($this->data['cheque'])) {
            unset($this->data['cheque']);
        }
    }

    public function checkBank()
    {
        if (isset($this->data['bank']) && empty($this->data['bank'])) {
            unset($this->data['bank']);
        }
    }

    public function checkAmount()
    {
        if (isset($this->data['amount']) && empty($this->data['amount'])) {
            $this->data['amount'] = 0;
        }
    }
}
