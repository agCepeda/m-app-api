<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response  as Http;

class ApiController extends Controller
{
	protected function responseSuccessful(
		$data = [],
		$statusCode = Http::HTTP_OK
	) {
		return response()->json($data, $statusCode);
	}
/*
	protected function responseWithError(
		$message,
		$statusCode = 
	) {

	}*/
}