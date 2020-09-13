<?php

namespace E4nar\Xm\Contracts\Nasdaq;

interface CompanyInfoApi {
	
	/**
	 * Get data from nasdaq companies
	 */
	public function getData();
	
	/**
	 * Get all company names and symbols
	 */
	public function getCompanyNameWithSymbols();
	
	/**
	 * Returns only the company symbols
	 */
	public function getSymbols();
	
}