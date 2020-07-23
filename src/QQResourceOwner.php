<?php
namespace Hehongyuanlove\AuthQQ;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class QQResourceOwner implements ResourceOwnerInterface {
    /**
     * Raw response
     *
     * @var array
     */
    protected $response;
    /**
     * Creates new resource owner.
     *
     * @param array  $response
     */
    public function __construct(array $response = array()) {
        $this->response = $response;
    }
    /**
     * Get resource owner id
     *
     * @return string|null
     */
    public function getId(){
        return;
    }
    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray(){
        return $this->response;
    }
}