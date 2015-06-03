<?php

/*
 * This file was added to the Wid'op package by Coradite.
 *
 * (c) Coradite <coradite@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Coradite\GoogleAnalytics;

use Widop\HttpAdapter\CurlHttpAdapter;

/**
 * Google Analytics Connection.
 */
class Connection extends \CApplicationComponent {

  /** @var string */
  public $clientEmail;

  /** @var string */
  public $privateKeyFile;

  /** @var integer If you are mostly going to be using one profile id then set this */
  public $defaultProfileId;

  /** @var Service */
  private $_service;


  /**
   * Get Service used to query the API.
   *
   * @return Service used to query the API
   */
  public function getService()
  {

    if (!$this->_service) {

      $httpAdapter = new CurlHttpAdapter();

      // Client contains the access token
      $client = new Client($this->clientEmail, $this->privateKeyFile, $httpAdapter, 'https://www.googleapis.com/oauth2/v3/token');
      $this->_service = new Service($client);

    }

    return $this->_service;

  }

  /**
   * Creates a command for execution.
   *
   * @param null $profileId The Analytics view 'profile' ID (see https://developers.google.com/analytics/devguides/reporting/core/v3/reference#ids)
   *
   * @return Command the DB command
   */
  public function createCommand($profileId=null)
  {

    $profileId = $profileId ? $profileId : $this->defaultProfileId;

    if (substr( $profileId, 0, 3 ) !== "ga:") {
      $profileId = 'ga:'.$profileId;
    }

    return new Command($this, $profileId);
  }





}

