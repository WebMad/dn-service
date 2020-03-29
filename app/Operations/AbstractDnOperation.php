<?php


namespace App\Operations;


use GuzzleHttp\Client;

/**
 * Class AbstractDnOperation
 * @package App\Operations
 */
abstract class AbstractDnOperation
{
    /**
     * @var Client
     */
    protected $dn_client;

    /**
     * AbstractDnOperation constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     *
     */
    private function init()
    {
        $this->dn_client = new Client([
            'base_uri' => 'https://api.dnevnik.ru/v2.0/'
        ]);
    }
}
