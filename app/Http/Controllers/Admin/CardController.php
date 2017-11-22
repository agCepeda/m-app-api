<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Card;

class CardController extends Controller 
{
	public function index(Request $request) 
	{
		$queryBuilder = Card::query();

		$scope = $request->get('scope', null);
		if ($scope != null) {
			$scope = explode(',', $scope);

			if (in_array('fields', $scope)) {
				$queryBuilder->with('cardFields.field');
			}
		}
		
		return $queryBuilder->get();
	}

	public function show(Request $request, $cardId) 
	{
		$queryBuilder = Card::query();
		$queryBuilder->where('id', $cardId);

		$scope = $request->get('scope', null);

		if ($scope != null) {
			$scope = explode(',', $scope);

			if (in_array('fields', $scope)) {
				$queryBuilder->with('cardFields.field');
			}

		}

		return $queryBuilder->first();
	}
}