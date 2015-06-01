<?php
/**
 * Author:  Rachel Kellett
 * Company: Veneficus Ltd.
 * Date:    27/05/2015

 * [Magic Freebies Redesign] VGoogleAnalytics.php
 */

namespace Coradite\GoogleAnalytics;

use Coradite\GoogleAnalytics\Client;
use Coradite\GoogleAnalytics\Query;
use Widop\HttpAdapter\CurlHttpAdapter;

/**
 * Wrapper for timgws\GoogleAnalytics\API
 *
 * Class VGoogleAnalytics
 */
class Connection extends \CApplicationComponent {


  //public $clientId;
  public $clientEmail;
  public $privateKeyFile;
  public $defaultProfileId;

  private $_service;


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
   * @param mixed $query the DB query to be executed. This can be either a string representing a SQL statement,
   * or an array representing different fragments of a SQL statement. Please refer to {@link CDbCommand::__construct}
   * for more details about how to pass an array as the query. If this parameter is not given,
   * you will have to call query builder methods of {@link CDbCommand} to build the DB query.
   * @return CDbCommand the DB command
   */
  public function createQuery($query=null, $profileId=null)
  {

    $profileId = $profileId ? $profileId : $this->defaultProfileId;

    if (substr( $profileId, 0, 3 ) !== "ga:") {
      $profileId = 'ga:'.$profileId;
    }

    return new Query($this, $profileId, $query);//CDbCommand($this,$query);
  }





}

