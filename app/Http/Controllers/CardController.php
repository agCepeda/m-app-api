<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Card;
use App\User;

use Log;

use Endroid\QrCode\QrCode;

class CardController extends Controller 
{
	/**
	 * Crea una nueva instancia del controllador
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('auth.user');
	}

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

	public function show($cardId, Request $request)
	{
		$card = Card::findOrFail($cardId);
		return $card;
	}

	public function showImage($cardId, Request $request)
	{
		$card = Card::findOrFail($cardId);
		return response()
			->download($card->path);
	}

	public function showQr($userId, Request $request)
	{	
		Log::info('Descarga de Imagen de codigo qr ' . $userId);
		$qrCode = new QrCode();
		$image = $qrCode->setText(url('/user/' . $userId))
		                ->setSize(169)
		                ->setPadding(10)
		                ->setErrorCorrection('high')
		                ->setImageType('png');

		$content = $image->get('png');

		return response($content)
		    ->header('Content-Type','image/png')
		    ->header('Content-Description','File Transfer')
		    ->header('Content-Type','application/octet-stream')
		    ->header('Content-Disposition', 'attachment; filename=qr'. $userId .'.png')
		    ->header('Content-Transfer-Encoding', 'binary')
		    ->header('Connection', 'Keep-Alive')
		    ->header('Expires', '0')
		    ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
		    ->header('Pragma', 'public');
	}

	public function showLogo($userId, Request $request) 
	{
		Log::info('Descarga de Logo ' . $userId);
		$user = User::findOrFail($userId);
		return response()
				->download($user->logo, null, ['Cache-Control' => 'no-cache, must-revalidate',
											   'Pragma'        => 'no-cache']);
	}

}