<?php

return [
	
	'route_prefix' => '',
	
	'route_middleware' => '',
	
	'datahub_nasdaq' => [
		
		// The cache key that will be used to store data
		'cache_key' => 'nasdaq_listing',
		
		// Cache time (in seconds) to remember nasdaq list information in application
		'cache_time_seconds' => 3600,
		
		// Url where Nasdaq Company information can be found
		'api_source_url' => 'https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json',
	
	],
	
	'rapid_stock' => [
		
		// Url where Nasdaq Stock Historical data can be found
		'api_source_url' => 'https://apidojo-yahoo-finance-v1.p.rapidapi.com/stock/v2/get-historical-data',
		
		// Api service key for requests
		'api_service_key' => '',
	],
	
];
