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

/**
 * Google Analytics Query.
 *
 * @author GeLo <geloen.eric@gmail.com>
 * @author Coradite <coradite@gmail.com>
 */
class Command
{
    /** @const The Google analytics service URL. */
    const URL = 'https://www.googleapis.com/analytics/v3/data/ga';

    /** @var string */
    protected $ids;

    /** @var \DateTime */
    protected $startDate;

    /** @var \DateTime */
    protected $endDate;

    /** @var array */
    protected $metrics;

    /** @var array */
    protected $dimensions;

    /** @var array */
    protected $sorts;

    /** @var array */
    protected $filters;

    /** @var string */
    protected $segment;

    /** @var integer */
    protected $startIndex;

    /** @var integer */
    protected $maxResults;

    /** @var boolean */
    protected $prettyPrint;

    /** @var string */
    protected $callback;


    /**
     * Creates a google analytics query.
     *
     * @param Connection $connection The connection object
     *
     * @param $profileIds The Analytics view 'profile' ID (see https://developers.google.com/analytics/devguides/reporting/core/v3/reference#ids)
     *
     */
    public function __construct($connection, $profileIds)
    {

        $this->_connection = $connection;

        $this->setIds($profileIds);

        $this->metrics = array();
        $this->dimensions = array();
        $this->sorts = array();
        $this->filters = array();
        $this->startIndex = 1;
        $this->maxResults = 10000;
        $this->prettyPrint = false;

        //@todo allow pass in raw query.

    }

    /**
     * Gets the google analytics query ids.
     *
     * @return string The google analytics query ids.
     */
    public function getIds()
    {
        return $this->ids;
    }

    /**
     * Sets the google analytics query ids.
     *
     * @param string $ids The google analytics query ids.
     *
     * @return Command The command.
     */
    public function setIds($ids)
    {
        $this->ids = $ids;

        return $this;
    }

    /**
     * Checks if the google analytics query has a start date.
     *
     * @return boolean TRUE if the google analytics query has a start date.
     */
    public function hasStartDate()
    {
        return $this->startDate !== null;
    }

    /**
     * Gets the google analytics query start date.
     *
     * @return \DateTime The google analytics query start date.
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Sets the google analytics query start date.
     *
     * @param \DateTime | string $startDate The google analytics query start date.
     *
     * @return Command The command.
     */
    public function setStartDate($startDate = null)
    {
        if ($startDate && is_string($startDate)) {
            $startDate = new \DateTime($startDate);
        }

        $this->startDate = $startDate;


        return $this;
    }

    /**
     * Checks if the google analytics query has an end date.
     *
     * @return boolean TRUE if the google analytics query has an end date else FALSE.
     */
    public function hasEndDate()
    {
        return $this->endDate !== null;
    }

    /**
     * Gets the google analytics query end date.
     *
     * @return \DateTime The google analytics query end date.
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Sets the google analytics query end date.
     *
     * @param \DateTime | string $endDate The google analytics query end date.
     *
     * @return Command The Command.
     */
    public function setEndDate($endDate = null)
    {
        if ($endDate && is_string($endDate)) {
            $endDate = new \DateTime($endDate);
        }

        $this->endDate = $endDate;


        return $this;
    }


    /**
     * Checks if the google analytics query has metrics.
     *
     * @return boolean TRUE if the google analytics query has metrics else FALSE.
     */
    public function hasMetrics()
    {
        return !empty($this->metrics);
    }

    /**
     * Gets the google analytics query metrics.
     *
     * @return array The google analytics query metrics.
     */
    public function getMetrics()
    {
        return $this->metrics;
    }

    /**
     * Sets the google analytics query metrics.
     *
     * @param array $metrics The google analytics query metrics.
     *
     * @return Command The command.
     */
    public function setMetrics(array $metrics)
    {
        $this->metrics = array();

        foreach ($metrics as $key => $metric) {
            $this->addMetric($metric, $key);
        }
        
        return $this;
    }

    /**
     * Adds a the google analytics metric to the query.
     *
     * @param string $metric The google analytics metric to add.
     *
     * @param string | null $key The key you would for the metric in the results.
     *
     * @return \Command The command.
     */
    public function addMetric($metric, $key=null)
    {

        if ($key) {
            $this->metrics[$key] = $metric;
        } else {
            $this->metrics[] = $metric;
        }

        
        return $this;
    }

    /**
     * Checks if the google analytics query has dimensions.
     *
     * @return boolean TRUE if the google analytics query has a dimensions else FALSE.
     */
    public function hasDimensions()
    {
        return !empty($this->dimensions);
    }

    /**
     * Gets the google analytics query dimensions.
     *
     * @return array The google analytics query dimensions.
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }

    /**
     * Sets the google analytics query dimensions.
     *
     * @param array $dimensions The google analytics query dimensions.
     *
     * @return Command The query.
     */
    public function setDimensions(array $dimensions)
    {
        $this->dimensions = array();

        foreach ($dimensions as $key => $dimension) {
            $this->addDimension($dimension, $key);
        }
        
        return $this;
    }

    /**
     * Adds a google analytics query dimension.
     *
     * @param string $dimension the google analytics dimension to add.
     *
     * @param string | null $key The key you would for the dimension in the results.
     *
     * @return Command The command.
     */
    public function addDimension($dimension, $key=null)
    {

        if ($key) {
            $this->dimensions[$key] = $dimension;
        } else {
            $this->dimensions[] = $dimension;
        }
        
        return $this;
    }

    /**
     * Checks if the google analytics query is ordered.
     *
     * @return boolean TRUE if the google analytics query is ordered else FALSE.
     */
    public function hasSorts()
    {
        return !empty($this->sorts);
    }

    /**
     * Gets the google analytics query sorts.
     *
     * @return array The google analytics query sorts.
     */
    public function getSorts()
    {
        return $this->sorts;
    }

    /**
     * Sets the google analytics query sorts.
     *
     * @param array $sorts The google analytics query sorts.
     *
     * @return Command The command.
     */
    public function setSorts(array $sorts)
    {
        $this->sorts = array();

        foreach ($sorts as $sort) {
            $this->addSort($sort);
        }
        
        return $this;
    }

    /**
     * Adds a google analytics query sort.
     *
     * @param string $sort A google analytics query sort to add.
     *
     * @return Command The command.
     */
    public function addSort($sort)
    {
        $this->sorts[] = $sort;
        
        return $this;
    }

    /**
     * Checks if the google analytics query has filters.
     *
     * @return boolean TRUE if the google analytics query has filters else FALSE.
     */
    public function hasFilters()
    {
        return !empty($this->filters);
    }

    /**
     * Gets the google analytics query filters.
     *
     * @return array The google analytics query filters.
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Sets the google analytics query filters.
     *
     * @param array $filters The google analytics query filters.
     *
     * @return Command The command.
     */
    public function setFilters(array $filters)
    {
        $this->filters = array();

        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }
        
        return $this;
    }

    /**
     * Adds the google analytics filter.
     *
     * @param string $filter the google analytics filter to add.
     *
     * @return Command The command.
     */
    public function addFilter($filter)
    {
        $this->filters[] = $filter;
        
        return $this;
    }

    /**
     * Checks of the google analytics query has a segment.
     *
     * @return boolean TRUE if the google analytics query has a segment else FALSE.
     */
    public function hasSegment()
    {
        return $this->segment !== null;
    }

    /**
     * Gets the google analytics query segment.
     *
     * @return string The google analytics query segment.
     */
    public function getSegment()
    {
        return $this->segment;
    }

    /**
     * Sets the google analytics query segment.
     *
     * @param string $segment The google analytics query segment.
     *
     * @return Command The query.
     */
    public function setSegment($segment)
    {
        $this->segment = $segment;
        
        return $this;
    }

    /**
     * Gets the google analytics query start index.
     *
     * @return integer The google analytics query start index.
     */
    public function getStartIndex()
    {
        return $this->startIndex;
    }

    /**
     * Sets the google analytics query start index.
     *
     * @param integer $startIndex The google analytics start index.
     *
     * @return Command The query.
     */
    public function setStartIndex($startIndex)
    {
        $this->startIndex = $startIndex;
        
        return $this;
    }

    /**
     * Gets the google analytics query max result count.
     *
     * @return integer The google analytics query max result count.
     */
    public function getMaxResults()
    {
        return $this->maxResults;
    }

    /**
     * Sets the google analytics query max result count.
     *
     * @param integer $maxResults The google analytics query max result count.
     *
     * @return Command The command.
     */
    public function setMaxResults($maxResults)
    {
        $this->maxResults = $maxResults;
        
        return $this;
    }

    /**
     * Gets the google analytics query prettyPrint option.
     *
     * @return boolean The google analytics query prettyPrint option.
     */
    public function getPrettyPrint()
    {
        return $this->prettyPrint;
    }

    /**
     * Sets the google analytics query prettyPrint option.
     *
     * @param boolean $prettyPrint The google analytics query pretty print option.
     *
     * @return Command The command.
     */
    public function setPrettyPrint($prettyPrint)
    {
        $this->prettyPrint = $prettyPrint;
        
        return $this;
    }

    /**
     * Checks the google analytics query for a callback.
     *
     * @return boolean TRUE if the google analytics query has a callback else FALSE.
     */
    public function hasCallback()
    {
        return !empty($this->callback);
    }

    /**
     * Gets the google analytics query callback.
     *
     * @return string The google analytics query callback.
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Sets the google analytics query callback.
     *
     * @param string The google analytics query callback.
     *
     * @return Command The command.
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
        
        return $this;
    }

    /**
     * Builds the query.
     *
     * @param string $accessToken The access token used to build the query.
     *
     * @return string The built query.
     */
    public function build($accessToken)
    {
        $query = array(
            'ids'          => $this->getIds(),
            'metrics'      => implode(',', $this->getMetrics()),
            'start-date'   => $this->getStartDate()->format('Y-m-d'),
            'end-date'     => $this->getEndDate()->format('Y-m-d'),
            'access_token' => $accessToken,
            'start-index'  => $this->getStartIndex(),
            'max-results'  => $this->getMaxResults(),
        );

        if ($this->hasSegment()) {
            $query['segment'] = $this->getSegment();
        }

        if ($this->hasDimensions()) {
            $query['dimensions'] = implode(',', $this->getDimensions());
        }

        if ($this->hasFilters()) {
            $query['filters'] = implode(',', $this->getFilters());
        }

        if ($this->hasSorts()) {
            $query['sort'] = implode(',', $this->getSorts());
        }

        if ($this->getPrettyPrint()) {
            $query['prettyPrint'] = 'true';
        }

        if ($this->hasCallback()) {
            $query['callback'] = $this->getCallback();
        }

        return sprintf('%s?%s', self::URL, http_build_query($query));
    }


    /**
     * Queries the google analytics service.
     *
     * @param bool $returnResponseObject Return the response (true) or the rows (false)
     *
     * @return Response The google analytics response.
     */
    public function query($returnResponseObject=false)
    {

        if ($returnResponseObject) {
            return $this->_connection->service->query($this);
        }

        $response = $this->_connection->service->query($this);

        return $response->getRows();

    }

    /**
     * Queries the google analytics service.
     *
     * @return array The response rows with meaningful column keys.
     */
    public function queryAll()
    {

        $response = $this->_connection->service->query($this);

        return $response->getResults();

    }


    /**
     * Queries the google analytics service.
     *
     * @return array The first row from response rows with meaningful column keys.
     */
    public function queryRow()
    {

        $response = $this->_connection->service->query($this);

        return $response->getFirstRow();

    }

    /**
     * Queries the google analytics service.
     *
     * @param int|string $column Optional column number (starts at 0) or ga:param
     *                           Will return first column if no column specified.
     *
     * @return array A column of the responses rows
     *
     * @throws \CException If the column wont match a dimension or metric in the query.
     */
    public function queryColumn($column = 0)
    {

        if (is_string($column) && !in_array($column, array_merge($this->metrics, $this->dimensions))) {
            throw new \CException('Column to fetch must be a column number or a metric/dimension of this command.');
        }

        $response = $this->_connection->service->query($this);

        return $response->getColumn($column);

    }

    /**
     * Queries the google analytics service.
     *
     * @return integer The value of the first column and row in the responses rows.
     */
    public function queryScalar()
    {

        $response = $this->_connection->service->query($this);

        return $response->getFirstValue();

    }


}
