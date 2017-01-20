<?php

namespace PP\WebPortal\Module\Model;

use PP\WebPortal\Module\Model\AbstractClass\ModelAbstract;

class BankModel extends ModelAbstract
{
    private $default = [
        'nick_name'                        => '',
        'currency'                         => '',
        'account_user_name'                => '',
        'account_number'                   => '',
        'iban'                             => '',
        'branch_code'                      => '',
        'bank_swift_code'                  => '',
        'bank_name'                        => '',
        'additional_information'           => '',
        'intermediary_bank_swift_code'     => '',
        'nick_name'                        => '',
    ];

    private $currency;

    public function __construct($data, $currency)
    {
        $this->data = array_replace_recursive($this->default, $data);
        $this->currency = $currency;
        $this->init();
    }

    private function init()
    {
        $this->data['currency_display'] = $this->data['currency'].' '.$this->currency[$this->data['currency']];
    }
}
