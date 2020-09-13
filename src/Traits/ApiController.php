<?php

namespace E4nar\Xm\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait ApiController {
	
	/**
	 * Validate api request data
	 *
	 * @param Request $request
	 *
	 * @throws \Exception
	 */
	protected function validateRequest(Request $request) {
		
		$data = $request->all();
		
		$symbols = $this->getValidSymbols();
		
		// apply validation rules
		$validator = Validator::make($data, [
			'symbol'    => 'required|'.Rule::in($symbols),
			'startDate' => 'required|date|date_format:Y-m-d|before_or_equal:endDate|before_or_equal:today',
			'endDate'   => 'required|date|date_format:Y-m-d|after_or_equal:startDate|before_or_equal:today',
			'email'     => 'required|email'
		]);
		
		if ($validator->fails()) {
			throw new \Exception($validator->errors()->first());
		}
		
	}
	
	/**
	 * Get Valid symbols
	 *
	 * @return array
	 *
	 * @throws \Exception
	 */
	protected function getValidSymbols() {
		return $this->companyInfoApi->getSymbols()->all();
	}
	
	/**
	 * Get company name for given symbol
	 *
	 * @param String $symbol
	 *
	 * @return String
	 * @throws \Exception
	 */
	protected function getCompanyNameForSymbol($symbol) {
		
		if (empty($symbol)) {
			throw new \Exception('Empty symbol');
		}
		
		return $this->companyInfoApi
			->getCompanyNameWithSymbols()
			->where('symbol', $symbol)
			->pluck('company_name')
			->first();
	}
	
}