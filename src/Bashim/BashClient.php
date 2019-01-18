<?php

namespace Bashim;

use Exception;
use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;

class BashClient
{
    /** @var Client  */
    private $guzzle;
    /** @var int */
    private $tries;

    public function __construct(int $tries = 3)
    {
        $this->guzzle = new Client([
            'base_uri' => 'https://bash.im',
        ]);
        $this->tries = $tries;
    }

    public function getRandomQuote(): string
    {
        for ($i = 1; $i <= $this->tries; $i++) {
            try {
                $response = $this->guzzle->get('/random');
                $html = HtmlDomParser::str_get_html($response->getBody());
                $quote = $html->find('.quote', 0)->find('.text', 0)->text();

                return mb_convert_encoding($quote, 'UTF-8', 'windows-1251');
            } catch (Exception $exception) {
                continue;
            }
        }

        return 'Баш лежит, сорян';
    }
}
