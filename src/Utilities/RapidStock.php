<?php

namespace E4nar\Xm\Utilities;

use E4nar\Xm\Contracts\Nasdaq\StockInfoApi;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class RapidStock implements StockInfoApi {
	
	protected $api_source_url;
	
	protected $api_service_key;
	
	protected $client;
	
	/**
	 * Initialization of parameters.
	 */
	public function __construct(Client $client) {
		
		// initialize api source url
		$this->api_source_url  = config('rapidstock.api_source_url');
		
		// initialize api service key
		$this->api_service_key = config('rapidstock.api_service_key');
		
		// initialize client for http request
		$this->client          = $client;
	}
	
	/**
	 * Get rapid service url
	 *
	 * @return String
	 */
	public function getApiSourceUrl() {
		return $this->api_source_url;
	}
	
	/**
	 * Get rapid service key (token)
	 *
	 * @return String
	 */
	public function getApiServiceKey() {
		return $this->api_service_key;
	}
	
	/**
	 * Return client object
	 *
	 * @return \GuzzleHttp\Client
	 */
	public function getClient() {
		return $this->client;
	}
	
	/**
	 * Get stock data for a symbol between a date period
	 *
	 * @param String $symbol    Company symbol
	 * @param String $startDate Period date to start fetching data
	 * @param String $endDate   Period date to end fetching data
	 *
	 * @return Array
	 * @throws \Exception
	 */
	public function getStockInfoBetweenDates(String $symbol, String $startDate, String $endDate) {
		
		if (empty($symbol) || empty($startDate) || empty($endDate)) {
			throw new \Exception('One or more parameters are empty');
		}
		
		// transform date to timestamp
		$startDate = Carbon::parse($startDate)->timestamp;
		$endDate   = Carbon::parse($endDate)->timestamp;
		
		try {
			// make request to service
			$stockdata = $this->requestData($symbol, $startDate, $endDate);
			
			// get prices from result
			$prices = Arr::get($stockdata, 'prices', []);
			
			// convert result
			$returnData = collect($prices)->map(function($stock) {
				return [
					"date"   => Carbon::createFromTimestamp($stock['date'])->format('Y-m-d'), // convert timestamp to date
					"open"   => Arr::get($stock, 'open', ''),
					"high"   => Arr::get($stock, 'high', ''),
					"low"    => Arr::get($stock, 'low', ''),
					"close"  => Arr::get($stock, 'close', ''),
					"volume" => Arr::get($stock, 'volume', ''),
				];
			});
			
			return $returnData;
			
		} catch (Exception $ex) {
			return [];
		}
	}
	
	/**
	 * Make http request to service, to get stock data
	 *
	 * @param String $symbol              Valid company symbol
	 * @param Int    $startDateTimestamp  Start date as timestamp
	 * @param Int    $endDateTimestamp    End date as timestamp
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	protected function requestData(String $symbol, Int $startDateTimestamp, Int $endDateTimestamp) {
		
		if (empty($this->api_source_url)) {
			
			Log::error('RapidStock: Empty api source url in config');
			
			throw new \Exception('Empty api source url in config');
		}
		
		$guzzleParams = [
			'debug'           => false, // Disable debug output
			
			'verify'          => false, // Enable SSL certificate verification and use the default CA bundle provided by operating system
			
			'connect_timeout' => 15,    // The number of seconds to wait while trying to connect to a server
			
			'headers'         => [      // Headers to send
				'Content-Type'    => 'application/json',  						// accept json type
				'X-RapidAPI-Host' => 'apidojo-yahoo-finance-v1.p.rapidapi.com', // required headers for service
				'X-RapidAPI-Key'  => $this->api_service_key                     // required headers for service
			],
			
			'query'           => [     // User parameters from form
				'symbol'  => $symbol,
				'period1' => $startDateTimestamp,
				'period2' => $endDateTimestamp,
			]
		];
		
		try {
			
			// connection to api source url
			$response = $this->client->get($this->api_source_url, $guzzleParams);
			
			if (in_array($response->getStatusCode(), [200, 201])) {
				
				$data = $response->getBody()->getContents();
				
				$stockData = json_decode($data, true);
				
				return $stockData;
			}
			
		} catch (\GuzzleHttp\Exception\GuzzleException $ex) {
			
			Log::error('RapidStock: Unable to fetch nasdaq stock data, error: '.$ex->getMessage());
			
			throw new \Exception('Cannot fetch data');
		}
		
	}
	
}