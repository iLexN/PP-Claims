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
        $this->isComplete();
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

    public function getDate()
    {
        return explode(' ', $this->data['created_at'])[0];
    }

    public function getTime()
    {
        return explode(' ', $this->data['created_at'])[1];
    }

    public function isSubmit()
    {
        return $this->data[status] === 'Submit' ? true : false;
    }

    public function haveFileUpload()
    {
        return !empty($this->data['file_attachments']['support_doc']) && !empty($this->data['file_attachments']['claim_form']);
    }

    public function haveReimburse()
    {
        if ($this->data['payment_method'] === 'Bank Transfer' && !empty($this->data['bank_info'])) {
            return true;
        }

        return false;
    }

    public function isComplete()
    {
        if ($this->haveFileUpload() && $this->haveReimburse()) {
            $this->data['isComplete'] = true;
        } else {
            $this->data['isComplete'] = false;
        }
    }

    public function getStep()
    {
        if ($this->data['isComplete']) {
            return 'Claim.ClaimS4';
        }

        if (!$this->haveFileUpload() && $this->haveReimburse()) {
            return 'Claim.ClaimS3';
        }
        //todo : check step1 than go s2?

        if (!$this->haveReimburse()) {
            return 'Claim.ClaimS1';
        }
    }
}
