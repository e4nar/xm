<?php

namespace E4nar\Xm\Utilities;

use E4nar\Xm\Contracts\Nasdaq\CompanyInfoApi;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DatahubNasdaq implements CompanyInfoApi {
	
	protected $cache_key;
	
	protected $cache_time_seconds;
	
	protected $api_source_url;
	
	protected $client;
	
	/**
	 * Initialization of parameters.
	 */
	public function __construct(Client $client) {
		
		// initialize cache key (default: nasdal_info)
		$this->cache_key          = config('datahubnasdaq.cache_key', 'nasdal_info');
		
		// initialize cache time (default: 300 seconds)
		$this->cache_time_seconds = config('datahubnasdaq.cache_time_seconds', 300);
		
		// initialize api source url
		$this->api_source_url     = config('datahubnasdaq.api_source_url');
		
		// initialize client for http request
		$this->client             = $client;
	}
	
	
	/**
	 * Get the key name of cache item that stores data
	 *
	 * @return String
	 */
	public function getCacheKey(){
		return $this->cache_key;
	}
	
	/**
	 * Get the configured TTL seconds for cache storage
	 *
	 * @return Int
	 */
	public function getCacheTimeSeconds() {
		return $this->cache_time_seconds;
	}
	
	/**
	 * Get the service url where Nasdaq company information can be found
	 *
	 * @return String
	 */
	public function getApiSourceUrl() {
		return $this->api_source_url;
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
	 * Check if there is already a fresh list in the application with Nasdaq Company information.
	 * If there is no list, it requests new data from external source.
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function getData() {
		
		// if cache key does not exist in cache or stored value is expired
		if (!Cache::has($this->cache_key)) {
			
			// then a request will fetch new data
			$this->getFreshData();
		}
		
		// finally get the data from the stored cache key. Use array as a default value
		$data = Cache::get($this->cache_key, []);
		
		return $data;
	}
	
	
	/**
	 * Return only the company names and symbols from all data
	 *
	 * @return \Illuminate\Support\Collection
	 * @throws \Exception
	 */
	public function getCompanyNameWithSymbols() {
		
		$data = $this->getData();
		
		$minData = collect($data)->map(function($companyData) {
			
			return [
				'company_name' => Arr::get($companyData, 'Company Name', ''),
				'symbol'       => Arr::get($companyData, 'Symbol', ''),
			];
			
		});
		
		return $minData;
	}
	
	/**
	 * Return only the symbols from all data
	 *
	 * @return \Illuminate\Support\Collection
	 * @throws \Exception
	 */
	public function getSymbols() {
		
		$data = $this->getData();
		
		$minData = collect($data)->map(function($companyData) {
			
			return Arr::get($companyData, 'Symbol', '');
			
		});
		
		return $minData;
	}
	
	/**
	 * Download new data and use cache as storage
	 *
	 * @throws \Exception
	 */
	protected function getFreshData() {
		
		if (empty($this->api_source_url)) {
			throw new \Exception('Empty api source url in config');
		}
		
		$guzzleParams = [
			'debug'           => false, // Disable debug output
			
			'verify'          => false,  // Enable SSL certificate verification and use the default CA bundle provided by operating system
			
			'connect_timeout' => 15,    // The number of seconds to wait while trying to connect to a server
			
			'headers'         => [      // Headers to send
				'Content-Type' => 'application/json',  //accept json type
			],
		];
		
		try {
			
			// connection to api source url
			$response = $this->client->get($this->api_source_url, $guzzleParams);
			
			if (in_array($response->getStatusCode(), [200, 201])) {
				
				$data = $response->getBody()->getContents();
				
				$companyInformation = json_decode($data, true);
				
				Cache::put($this->cache_key, $companyInformation, $this->cache_time_seconds);
			}
			
		} catch (\GuzzleHttp\Exception\GuzzleException $ex) {
			
			Log::error('Unable to fetch nasdaq company data, error: '.$ex->getMessage());
			
			throw new \Exception('Cannot fetch data');
		}
		
	}
	
}