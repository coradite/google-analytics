# Usage

You can query the api in the following ways. 


```php

    // Return Response data
    
    $report = Yii::app()->gapi->createCommand()
      ->setStartDate($date)
      ->setEndDate($date)
      ->setMetrics([
        'visits' => 'ga:sessions',
        'visitors' => 'ga:users',
        'newVisits' => 'ga:newUsers',
        'pageviews' => 'ga:pageviews',

      ])      
      ->setDimensions([
        'browser'=>'ga:browser'
      ])
      ->query(true); // Enter true to return entire Coradite\GoogleAnalytics\Response object.

    
    // Return Response rows with meaningful column keys
    // If keys are not entered for metrics and dimensions column keys will be the ga:param name.
    
    $return = Yii::app()->gapi->createCommand()
      ->setStartDate($date)
      ->setEndDate($date)
      ->setMetrics([
        'visits' => 'ga:sessions',
        'visitors' => 'ga:users',
        'newVisits' => 'ga:newUsers',
        'pageviews' => 'ga:pageviews',

      ])
      ->setDimensions([
        'browser'=>'ga:browser'
      ])
      ->queryAll();

    // Return the first row of data with meaningful column keys.
    // If keys are not entered for metrics and dimensions column keys will be the ga:param name.
    
    $return = Yii::app()->gapi->createCommand()
      ->setStartDate($date)
      ->setEndDate($date)
      ->setMetrics([
        'visits' => 'ga:sessions',
        'visitors' => 'ga:users',
        'newVisits' => 'ga:newUsers',
        'pageviews' => 'ga:pageviews',

      ])
      ->setDimensions([
        'browser'=>'ga:browser'
      ])
      ->queryRow();


    // Return first or specific column of response data
    
    $report = Yii::app()->gapi->createCommand()
      ->setStartDate($date)
      ->setEndDate($date)
      ->setMetrics([
        'visits' => 'ga:sessions',
        'visitors' => 'ga:users',
        'newVisits' => 'ga:newUsers',
        'pageviews' => 'ga:pageviews',

      ])
      ->setDimensions([
        'browser'=>'ga:browser'
      ])
      ->queryColumn($key); // Optional column number (starts at 0) or ga:param. 
                              Will return first column if no column specified.


    // Returns the value of the first column and row.
    
    $report = Yii::app()->gapi->createCommand()
      ->setStartDate($date)
      ->setEndDate($date)
      ->setMetrics([
        'visits' => 'ga:sessions',
        'visitors' => 'ga:users', //visitors
        'newVisits' => 'ga:newUsers', //new visitors
        'pageviews' => 'ga:pageviews',

      ])
      ->setDimensions([
        'browser'=>'ga:browser'
      ])
      ->queryScalar();

```
The first query allows you to return the `Coradite\GoogleAnalytics\Response` object as detailed below.

## Response

The response is a `Widop\GoogleAnalytics\Response` object which wraps all available informations:

``` php
$profileInfo = $response->getProfileInfo();
$kind = $response->getKind();
$id = $response->getId();
$query = $response->getQuery();
$selfLink = $response->getSelfLink();
$previousLink = $response->getPreviousLink();
$nextLink = $response->getNextLink();
$startIndex = $response->getStartIndex();
$itemsPerPage = $response->getItemsPerPage();
$totalResults = $response->getTotalResults();
$containsSampledData = $response->containsSampledData();
$columnHeaders = $response->getColumnHeaders();
$totalForAllResults = $response->getTotalsForAllResults();
$hasRows = $response->hasRows();
$rows = $response->getRows();
$rows = $response->getResults(); // All rows with meaningful column names
$response->getFirstRow() // First row with meaningful column names
$response->getColumn($key) // A specific column Excepts (A column number or ga:param name). Defaults to first column.
$response->getFirstValue() // The value of the first column and row.
```
