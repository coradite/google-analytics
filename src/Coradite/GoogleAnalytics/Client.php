<?php

/*
 * This file is adapted from a Wid'op package.
 *
 * (c) Wid'op <contact@widop.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Coradite\GoogleAnalytics;

use Widop\HttpAdapter\HttpAdapterInterface;

/**
 * Google analytics client.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class Client
{
    /** @const The google OAuth scope. */
    const SCOPE = 'https://www.googleapis.com/auth/analytics.readonly';

    /** @var string */
    protected $clientId;

    /** @var string */
    protected $privateKeyFile;

    /** @var \Widop\HttpAdapter\HttpAdapterInterface */
    protected $httpAdapter;

    /** @var string */
    protected $url;

    /** @var string */
    protected $accessToken;

    /**
     * Creates a client.
     *
     * @param string $clientId The client ID.
     *
     * @param string $privateKeyFile The absolute private key file path.
     *
     * @param \Widop\HttpAdapter\HttpAdapterInterface $httpAdapter The http adapter.
     *
     * @param string $url The google analytics service url.
     *
     * @throws GoogleAnalyticsException
     */
    public function __construct(
        $clientId,
        $privateKeyFile,
        HttpAdapterInterface $httpAdapter,
        $url = 'https://www.googleapis.com/oauth2/v3/token'
    ) {
        $this->setClientId($clientId);
        $this->setPrivateKeyFile($privateKeyFile);
        $this->setHttpAdapter($httpAdapter);
        $this->setUrl($url);
    }

    /**
     * Gets the client ID.
     *
     * @return string The client ID.
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Sets the client ID.
     *
     * @param string $clientId The client ID.
     *
     * @return Client The client.
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Gets the absolute private key file path.
     *
     * @return string The absolute private key file path.
     */
    public function getPrivateKeyFile()
    {
        return $this->privateKeyFile;
    }

    /**
     * Sets the absolute private key file path.
     *
     * @param string $privateKeyFile The absolute private key file path.
     *
     * @return Client If the private key file does not exist.
     *
     * @throws GoogleAnalyticsException
     */
    public function setPrivateKeyFile($privateKeyFile)
    {
        if (!file_exists($privateKeyFile)) {
            throw GoogleAnalyticsException::invalidPrivateKeyFile($privateKeyFile);
        }

        $this->privateKeyFile = $privateKeyFile;

        return $this;
    }

    /**
     * Gets the http adapter.
     *
     * @return \Widop\HttpAdapter\HttpAdapterInterface The http adapter.
     */
    public function getHttpAdapter()
    {
        return $this->httpAdapter;
    }

    /**
     * Sets the http adapter.
     *
     * @param \Widop\HttpAdapter\HttpAdapterInterface $httpAdapter The http adapter.
     *
     * @return Client The client.
     */
    public function setHttpAdapter(HttpAdapterInterface $httpAdapter)
    {
        $this->httpAdapter = $httpAdapter;

        return $this;
    }

    /**
     * Gets the google analytics service url.
     *
     * @return string The google analytics service url.
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the google analytics service url.
     *
     * @param string $url The google analytics service url.
     *
     * @return Client The client.
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Gets the google OAuth access token.
     *
     * @throws GoogleAnalyticsException If the access token can not be retrieved.
     *
     * @return string The access token.
     */
    public function getAccessToken()
    {
        if ($this->accessToken === null) {
            $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
            $content = array(
                'grant_type'     => 'assertion',
                'assertion_type' => 'http://oauth.net/grant_type/jwt/1.0/bearer',
                'assertion'      => $this->generateJsonWebToken(),
            );

            $response = json_decode($this->httpAdapter->postContent($this->url, $headers, $content));

            if (isset($response->error)) {
                throw GoogleAnalyticsException::invalidAccessToken($response->error);
            }

            $this->accessToken = $response->access_token;
        }

        return $this->accessToken;
    }

    /**
     * Generates the JWT in order to get the access token.
     *
     * @return string The Json Web Token (JWT).
     */
    protected function generateJsonWebToken()
    {
        $exp = new \DateTime('+1 hours');
        $iat = new \DateTime();

        $jwtHeader = base64_encode(json_encode(array('alg' => 'RS256', 'typ' => 'JWT')));

        $jwtClaimSet = base64_encode(
            json_encode(
                array(
                    'iss'   => $this->clientId,
                    'scope' => self::SCOPE,
                    'aud'   => $this->url,
                    'exp'   => $exp->getTimestamp(),
                    'iat'   => $iat->getTimestamp(),
                )
            )
        );

        $jwtSignature = base64_encode($this->generateSignature($jwtHeader.'.'.$jwtClaimSet));

        return sprintf('%s.%s.%s', $jwtHeader, $jwtClaimSet, $jwtSignature);
    }

    /**
     * Generates the JWT signature according to the private key file and the JWT content.
     *
     * @param string $jsonWebToken The JWT content.
     *
     * @throws GoogleAnalyticsException If an error occured when generating the signature.
     *
     * @return string The JWT signature.
     */
    protected function generateSignature($jsonWebToken)
    {
        if (!function_exists('openssl_x509_read')) {
            throw GoogleAnalyticsException::invalidOpenSslExtension();
        }

        $certificate = file_get_contents($this->privateKeyFile);

        $certificates = array();
        if (!openssl_pkcs12_read($certificate, $certificates, 'notasecret')) {
            throw GoogleAnalyticsException::invalidPKCS12File();
        }

        if (!isset($certificates['pkey']) || !$certificates['pkey']) {
            throw GoogleAnalyticsException::invalidPKCS12Format();
        }

        $ressource = openssl_pkey_get_private($certificates['pkey']);

        if (!$ressource) {
            throw GoogleAnalyticsException::invalidPKCS12PKey();
        }

        $signature = null;
        if (!openssl_sign($jsonWebToken, $signature, $ressource, 'sha256')) {
            throw GoogleAnalyticsException::invalidPKCS12Signature();
        }

        openssl_pkey_free($ressource);

        return $signature;
    }
}
