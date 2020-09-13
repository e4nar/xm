<?php

namespace E4nar\Xm\Contracts\Nasdaq;

interface StockInfoApi {
	
	/**
	 * Gets stock data for a symbol between a period
	 *
	 * @param String $symbol    Valid company symbol
	 * @param String $startDate Starting period date in 'YYYY-mm-dd' format
	 * @param String $endDate   Ending period date in 'YYYY-mm-dd' format
	 *
	 * @return
	 */
	public function getStockInfoBetweenDates(String $symbol, String $startDate, String $endDate);
	
}