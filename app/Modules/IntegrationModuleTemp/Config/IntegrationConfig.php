<?php 
namespace Syndiconnect\Config;

use CodeIgniter\Config\BaseConfig;

class IntegrationConfig extends BaseConfig
{

    public $emailConfig = [
        'protocol'    => 'smtp',
        'SMTPHost'    => 'ssl0.ovh.net',
        'SMTPPort'    => 465,
        'SMTPCrypto'  => 'ssl',
        'SMTPUser'    => '',
        'SMTPPass'    => '',
        'mailType'    => 'html',
        'charset'     => 'utf8',
        'wordWrap'    => true,
       
        'From'    => '',

    ];
}