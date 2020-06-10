<?php
/**
 * Olx-parser  - Simple parser of ads from olx.ua
 * PHP Version 7.4.
 *
 * @see https://github.com/eslavondigital/olx-parser The Olx-parser GitHub project
 *
 * @author    Vinogradov Victor <eslavon.work.victor@gmail.com>
 * @author    Eslavon Digital <eslavondigital@gmail.com>
 * @copyright 2020 Vinogradov Victor
 * @copyright 2020 Eslavon Digital
 * @license   https://github.com/eslavondigital/olx-parser/blob/master/LICENSE MIT License
 * @note      This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Eslavon\Olx;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class Parser
 * @package Eslavon\Olx
 */
class Parser
{
    /**
     * User agent
     * @var string $user_agent
     */
    private string $user_agent;

    /**
     * Ad url
     * @var string $url
     */
    private string $url;

    /**
     * Array of ad urls
     * @var array $url_array
     */
    private array $url_array= [];

    /**
     * Allowed URLs
     * @var array $allowed_url
     */
    private array $allowed_url = [
        'www.olx.ua', 'www.olx.com', 'www.olx.pl', 'www.olx.pt', 'www.olx.ua', 'www.olx.ro',
        'www.olx.uz', 'www.olx.uz', 'www.olx.bg', 'www.olx.kz', 'olx.ua', 'olx.com',
        'olx.pl', 'olx.pt', 'olx.ua', 'olx.ro', 'olx.uz', 'olx.uz', 'olx.bg', 'olx.kz'
    ];

    /**
     * Parser constructor.
     * @param string $user_agent
     */
    public function __construct(string $user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36')
    {
        $this->user_agent = $user_agent;
    }

    /**
     * Get User Agent
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->user_agent;
    }

    /**
     * Set User Agent
     * @param string $user_agent User Agent
     */
    public function setUserAgent(string $user_agent): void
    {
        $this->user_agent = $user_agent;
    }

    /**
     * Get ad url
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Get array of ad urls
     * @return array
     */
    public function getMultiUrl(): array
    {
        return $this->url_array;
    }

    /**
     * Set ad url
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        if ($this->validationUrl($url) === true) {
            $this->url = $url;
        }
    }

    /**
     * Set array of ad urls
     * @param array $url_array
     */
    public function setMultiUrl(array $url_array): void
    {
        $this->url_array = $this->clearUrl($url_array);
    }

    public function run(): array
    {
        $result = [];
        $client = HttpClient::create(['headers' => ['User-Agent' => $this->user_agent]]);
        if ($this->url) {
            $this->url_array[] = $this->url;
        }
        if (count($this->url_array) == 0) {
            return ['error' => 'URL not specified', 'code' => 1];
        }
        foreach ($this->url_array as $item => $url) {
            try {
                $response = $client->request('GET', $url);
            } catch (TransportExceptionInterface $e) {
                return ['error' => 'Request failed', 'code' => 2];
            }
            if ($response->getStatusCode() !== 200) {
                return ['error' => 'Request failed', 'code' => 2];
            }
            $crawler = new Crawler($response->getContent());
            $name = $crawler->filter('h1')->text();
            $amount = $crawler->filter('div.offer-titlebox__price')->text();
            $description = $crawler->filter('div.clr.lheight20.large')->text();
            $link = $crawler->filter('div.descgallery__image')->filter('img')->attr('src');
            $date = $crawler->filter('li.offer-bottombar__item')->text();
            $author = $crawler->filter('h4 > a')->text();
            $address = $crawler->filter('address')->text();
            $result[] = [
                'name' => $name,
                'amount' => $amount,
                'description' => $description,
                'img' => $link,
                'date' => $date,
                'author' => $author,
                'address' => $address
            ];
        }
        $this->url_array = [];
        $this->url = '';
        return $result;
    }

    /**
     * URL Validation
     * @param string $url - ad url
     * @return bool
     */
    public function validationUrl(string $url): bool
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false;
        }
        $parse_url = parse_url($url);
        if (in_array($parse_url['host'], $this->allowed_url)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove invalid ad URLs from the array
     * @param array $url_array
     * @return array
     */
    public function clearUrl(array $url_array): array
    {
        $clear_url_array = [];
        foreach ($url_array as $item => $url) {
            if ($this->validationUrl($url) === true) {
                $clear_url_array[] = $url;
            }
        }
        return $clear_url_array;
    }


}