<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\Contact;
use Log;
use App\Notifications\Publisher;

class ContactController extends Controller {

	public function __construct()
	{
		$this->middleware('auth.user');
	}

	public function index($userId, Request $request) 
	{
		$user = User::findOrFail($userId); 

        $contacts2 = Contact::where('contact1', $user->id)
            ->select(['contact2'])
            ->map(function($item) {
                return $item->contact2;
            });

        return User::whereIn('id', $contacts2)
                ->orderBy('show_name', 'ASC')
                ->with('profession','card')
                ->get();
	}

	public function getContacts(User $user, Request $request)
	{
        $contacts2 = Contact::where('contact1', $user->id)
            ->get(['contact2'])
            ->map(function($item) {
                return $item->contact2;
            });

        return User::whereIn('id', $contacts2)
                ->orderBy('show_name', 'ASC')
                ->with('profession','card')
                ->get();
	}

	public function addContact(
		User $user,
		Request $request,
		Publisher $publisher
	) {
		app('db')->beginTransaction();
		$contactId = $request->get('contact_id');

		Log::debug("Add Contact {$contactId}");

		try {
			app('db')->table('contacts')
				->insert([
					'contact1' => $user->id,
					'contact2' => $contactId 
				]);

			$publisher->sendFollowerNotificationToUser($user->id, $contactId);

			app('db')->commit();
		} catch (\Exception $ex) {
			app('db')->rollBack();
			app('log')->error($ex);
			return response()->json(['message' => ''], 400);
		}
	}

	public function removeContact($contactId, User $user, Request $request)
	{
		app('db')->table('contacts')
			->where('contact1', $user->id)
			->where('contact2', $contactId)
			->delete();
/*
		app('db')->table('contacts')
			->where('contact1', $contactId)
			->where('contact2', $user->id)
			->delete();*/
	}
	
}