<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Profession;

class ProfessionController extends Controller 
{
	public function __construct()
	{
	}

	public function index(Request $request) 
	{
		return Profession::all();
	}
}
