<?php

namespace Keacefull\MeituanShangou;

use Keacefull\MeituanShangou\Request\Act;
use Keacefull\MeituanShangou\Request\BaseRequest;
use Keacefull\MeituanShangou\Request\Comment;
use Keacefull\MeituanShangou\Request\Goods;
use Keacefull\MeituanShangou\Request\GroupBuy;
use Keacefull\MeituanShangou\Request\Im;
use Keacefull\MeituanShangou\Request\Image;
use Keacefull\MeituanShangou\Request\Medicine;
use Keacefull\MeituanShangou\Request\Order;
use Keacefull\MeituanShangou\Request\Poi;
use Keacefull\MeituanShangou\Request\Retail;
use Keacefull\MeituanShangou\Request\Shipping;
use Keacefull\MeituanShangou\Request\Token;
use Exception;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Class Application.
 *
 * @property Comment $comment
 * @property Act $act
 * @property Image $image
 * @property Medicine $medicine
 * @property Order $order
 * @property Poi $poi
 * @property Retail $retail
 * @property Shipping $shipping
 * @property GroupBuy $groupBuy
 * @property Im $im
 * @property Goods $goods
 * @property Token $token
 */
class Application
{
    private $config;

    public function __construct($config)
    {
        $this->config = new Config($config);
        $this->client = HttpClient::create();
    }

    public function setHttpClient($client): self
    {
        $this->client = $client;

        return $this;
    }

    public function __get($name)
    {
        if (! isset($this->$name)) {
            $class_name = ucfirst($name);
            $application = "\\Keacefull\\MeituanShangou\\Request\\{$class_name}";
            if (! class_exists($application)) {
                throw new Exception($class_name.'不存在');
            }
            $this->$name = new $application($this->config, $this->client);
        }

        return $this->$name;
    }
}
