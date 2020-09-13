<?php

namespace E4nar\Xm\Http\Controllers\Api;

use E4nar\Xm\Contracts\Nasdaq\CompanyInfoApi;
use E4nar\Xm\Contracts\Nasdaq\StockInfoApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use E4nar\Xm\Mail\FormSubmittedMail;
use Illuminate\Support\Facades\Mail;

class ApiController extends Controller {

	// extra helper function are inside this trait
	use \App\Traits\ApiController;
	
	
	protected $stockInfoApi;
	
	protected $companyInfoApi;
	
	/**
	 * ApiController constructor.
	 *
	 * @param StockInfoApi   $stockInfoApi    Accept any object that implements StockInfoApi
	 * @param CompanyInfoApi $companyInfoApi  Accept any object that implements CompanyInfoApi
	 */
	function __construct(StockInfoApi $stockInfoApi, CompanyInfoApi $companyInfoApi) {

		$this->stockInfoApi = $stockInfoApi;
		
		$this->companyInfoApi = $companyInfoApi;
	}
	
	/**
	 * Return stock data for specific symbol
	 * between the willing dates
	 *
	 * @param  Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getHistoricalData(Request $request) {
		
		try {
			
			// check validation
			$this->validateRequest($request);
			
			// take data
			$data = $request->all();
			
			// make request to get stock info
			$stockData = $this->stockInfoApi->getStockInfoBetweenDates($data['symbol'], $data['startDate'], $data['endDate']);
			
			// get company name fro specified symbol
			$data['company_name'] = $this->getCompanyNameForSymbol($data['symbol']);
			
			// send email async in order not to affect execution time for response
			Mail::to($data['email'])->queue(new FormSubmittedMail($data));
			
			return response()->json([
				'status' => true,
				'error'  => '',
				'data'   => $stockData
			]);
			
		} catch (\Exception $ex) {
			
			return response()->json([
				'status' => false,
				'error'  => $ex->getMessage(),
				'data'   => []
			]);
			
		}
		
	}
	
}