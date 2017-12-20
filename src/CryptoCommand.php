<?php

namespace Cashlion;

use Zttp\Zttp;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CryptoCommand extends Command
{
    public $coins = [
        'bitcoin', 'ethereum', 'litecoin'
    ];

    public function configure()
    {
        $this->setName('prices')
            ->setDescription('Get Latest Prices for Cryptocurrencies');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->getPrices();

        (new Table($output))
            ->setHeaders(['Symbol','USD'])
            ->setRows($data)
            ->render();
    }

    private function getPrices()
    {
        return collect($this->coins)->map(function ($coin) {
            $response = Zttp::get("https://api.coinmarketcap.com/v1/ticker/{$coin}/")->json();
            return [ $response[0]['symbol'], number_format($response[0]['price_usd'], 2)];
        })->toArray();
    }
}
