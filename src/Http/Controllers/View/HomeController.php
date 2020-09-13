<?php

namespace E4nar\Xm\Http\Controllers\View;

use E4nar\Xm\Contracts\Nasdaq\CompanyInfoApi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller {
	
	protected $companyInfoApi;
	
	/**
	 * HomeController constructor.
	 *
	 * @param CompanyInfoApi $companyInfoApi Accept any object implements CompanyInfoApi
	 */
	public function __construct(CompanyInfoApi $companyInfoApi) {
		$this->companyInfoApi = $companyInfoApi;
	}
	
	/**
	 * IndexAction loads content in browser where user can fill in the form
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
	 * @throws \Exception
	 */
	public function indexAction(Request $request) {
		
		$nasdaqCompanySymbols = $this->companyInfoApi->getCompanyNameWithSymbols();
		
		// loading and passing parameters to view file
		return view('xm::home', ['nasdaq_company_symbols' => $nasdaqCompanySymbols]);
		
	}
	
}