<?php
namespace Hehongyuanlove\AuthQQ;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class QQ extends AbstractProvider{
    use BearerAuthorizationTrait;

    /**
     * @var
     */
    public $openid;
    /**
     * @var string
     */
    public $domain = "https://graph.qq.com";

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl () {
        return $this->domain . '/oauth2.0/authorize';
    }
    // https://graph.qq.com/oauth2.0/authorize?response_type=token&client_id=101890009&redirect_uri=%2F%2Ffl.himi3d.cn%2Fauth%2Fqq&scope=get_user_info
	

	protected function getAuthorizationParameters(array $options)
    {
        $options['client_id'] = $this->clientId;

        if (!isset($options['redirect_uri'])) {
            $options['redirect_uri'] = $this->redirectUri;
        }

        $options += [
            'response_type' => 'code'
        ];

        if (empty($options['scope'])) {
            $options['scope'] = 'get_user_info';
        }

        if (empty($options['state'])) {
            $options['state'] = $this->getRandomState();
        }

        // Store the state as it may need to be accessed later on.
        $this->state = $options['state'];
        
        return $options;
    }

    /**
     * Get access token url to retrieve token
     * @param array $params
     * @return string
     */
    public function getBaseAccessTokenUrl (array $params) {
        return $this->domain . '/oauth2.0/token';
    }

    /**
     * Get provider url to fetch user details
     *
     * @param AccessToken $token
     * @return string
     * @throws IdentityProviderException
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token) {}


    /**
     * Get openid url to fetch it
     * @param AccessToken $token
     * @return string
     */
    protected function getOpenidUrl(AccessToken $token) {
        // $uid = $token->getValues()['uid'] ?? 0;
        return $this->domain . '/oauth2.0/me?access_token=' . $token . '&fmt=json';
    }


    /**
     * Get openid
     *
     * @param AccessToken $token
     * @return mixed
     * @throws IdentityProviderException
     */
    public function fetchOpenid(AccessToken $token) {
        $url     = $this->getOpenidUrl($token);
        $request = $this->getAuthenticatedRequest(self::METHOD_GET, $url, $token);
        $data    = $this->getSpecificResponse($request);
        return $data;
    }
    
    // https://graph.qq.com/user/get_user_info?access_token=YOUR_ACCESS_TOKEN&oauth_consumer_key=YOUR_APP_ID&openid=YOUR_OPENID
    public function fetchUesrInfo($token,$openid) {
      $url     = $this->domain.'/user/get_user_info?access_token='. $token .'&oauth_consumer_key='.$this->clientId.'&openid='.$openid;
      $request = $this->getAuthenticatedRequest(self::METHOD_GET, $url, $token);
      $data    = $this->getSpecificResponse($request);
      return $data;
    }

    /**
     * get accesstoken
     *
     * The Content-type of server's returning is 'text/html;charset=utf-8'
     * so it has to be rewritten
     *
     * @param mixed $grant
     * @param array $options
     * @return AccessTokenInterface
     * @throws IdentityProviderException
     */
    public function getAccessToken($grant, array $options = []) {
        $grant = $this->verifyGrant($grant);
        $params = [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
            'grant_type'=>'authorization_code',
            'fmt'=>'json',
        ];
         
        $params   = $grant->prepareRequestParameters($params, $options);
        $request  = $this->getAccessTokenRequest($params);
        $response = $this->getParsedResponse($request);
        // "access_token=57D809EAE467FC290B9B65A89B6AC1F7&expires_in=7776000&refresh_token=B854970A0F9069B5CE6202F7DB531437
        /*
    		["access_token"]=> string(32) "57D809EAE467FC290B9B65A89B6AC1F7" 
    		["expires_in"]=> string(7) "7776000" 
    		["refresh_token"]=> string(32) "B854970A0F9069B5CE6202F7DB531437" }
       */
        if(is_null($response)){
            throw new \UnexpectedValueException(
                'Invalid response received from Authorization Server. Expected JSON.'
            );
        }
        $prepared = $this->prepareAccessTokenResponse($response);
        
        $token    = $this->createAccessToken($prepared, $grant);
        return $token;
    }
    
    public function getUserInfo($grant, array $options = []) {
        $grant = $this->verifyGrant($grant);
        $params = [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
            'grant_type'=>'authorization_code',
            'fmt'=>'json',
        ];
         
        $params   = $grant->prepareRequestParameters($params, $options);
        $request  = $this->getAccessTokenRequest($params);
        $response = $this->getParsedResponse($request);
        // "access_token=57D809EAE467FC290B9B65A89B6AC1F7&expires_in=7776000&refresh_token=B854970A0F9069B5CE6202F7DB531437
        /*
    		["access_token"]=> string(32) "57D809EAE467FC290B9B65A89B6AC1F7" 
    		["expires_in"]=> string(7) "7776000" 
    		["refresh_token"]=> string(32) "B854970A0F9069B5CE6202F7DB531437" }
       */
        if(is_null($response)){
            throw new \UnexpectedValueException(
                'Invalid response received from Authorization Server. Expected JSON.'
            );
        }
        $prepared = $this->prepareAccessTokenResponse($response);
        
        $token    = $this->createAccessToken($prepared, $grant);
        return $token;
    }
    
   

    /**
     * @param RequestInterface $request
     * @return mixed
     * @throws IdentityProviderException
     */
    protected function getSpecificResponse(RequestInterface $request) {
        $response = $this->getResponse($request);
        $parsed   = $this->parseSpecificResponse($response);
        $this->checkResponse($response, $parsed);
        return $parsed;
    }


    /**
     * A specific parseResponse function
     * @param ResponseInterface $response
     * @return mixed
     */
    protected function parseSpecificResponse(ResponseInterface $response) {
        $content = (string)$response->getBody();
        return json_decode($content, true);
    }


    /**
     * Check a provider response for errors.
     *
     * @throws IdentityProviderException
     * @param  ResponseInterface $response
     * @param  void $data Parsed response data
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data) {
        if(isset($data['error'])) {
            throw new IdentityProviderException($data['error_description'], $response->getStatusCode(), $response);
        }
    }
    /**
     * Get the default scopes used by this provider.
     *
     * This should not be a complete list of all scopes, but the minimum
     * required for the provider user interface!
     *
     * @return array
     */
    protected function getDefaultScopes() {
        return [];
    }
    /**
     * Generate a user object from a successful user details request.
     * @param array $response
     * @param AccessToken $token
     * @return QQResourceOwner
     */
    protected function createResourceOwner(array $response, AccessToken $token) {
        return new QQResourceOwner($response);
    }
    
    /**
     * combineURL
     * 拼接url
     * @param string $baseURL   基于的url
     * @param array  $keysArr   参数列表数组
     * @return string           返回拼接的url
     */
    protected function combineURL($baseURL,$keysArr){
        $combined = $baseURL."?";
        $valueArr = array();

        foreach($keysArr as $key => $val){
            $valueArr[] = "$key=$val";
        }

        $keyStr = implode("&",$valueArr);
        $combined .= ($keyStr);
        
        return $combined;
    }
}
