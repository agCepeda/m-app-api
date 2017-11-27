<?php

namespace App\Repositories;

use App\User;

class UserRepository extends Repository
{
    public function search($q, $size = 30, $page = 1)
    {
        $user = app(User::class);

        if ($q != null && $q != '') {
            $fields = ['*'];

            $qBuilder = User::with(['card'])
                        ->where('id', '<>', $user->id)
                        ->where(
                            function ($query) use ($q) {
                                $query
                                    ->orWhere('name', 'like', '%' . $q . '%')
                                    ->orWhere('last_name', 'like', '%' . $q . '%');
                            }
                        )
                        ->orWhere('profession', 'like', "%{$q}%");

            if ($user->latitude && $user->longitude) {
                $fields[count($fields)] = app('db')->raw(
                    ""
                    . "111.111 * "
                    . "DEGREES(ACOS(COS(RADIANS({$user->latitude})) "
                    . "* COS(RADIANS(latitude)) "
                    . "* COS(RADIANS({$user->longitude} - longitude)) "
                    . "+ SIN(RADIANS({$user->latitude})) "
                    . "* SIN(RADIANS(latitude)))) AS distance "
                );

                $qBuilder->orderBy('distance', 'asc');
            }

            return $qBuilder->paginate($size, $fields, 'page', $page);
        }

        return $this->defaultSearch($size, $page);
    }

    public function defaultSearch($size = 30, $page = 1)
    {
        $rand = rand(1, 10);
        $qBuilder = User::with(['card'])
                        ->select([
                            '*',
                            app('db')->raw("FLOOR(RAND({$rand}) * 100) AS criteria")
                        ])
                        ->orderBy('criteria', 'asc');
        return $qBuilder->paginate();
    }

    public function loadLocation()
    {

        if (count($address) > 0) {
            $strAddress = implode(' ', $address);
            $strAddress = preg_replace('/\s+/', '+', $strAddress);

            $client = new Client();
            $res    = $client->get('https://maps.googleapis.com/maps/api/geocode/json', [
                'query' => [
                    'address' => $strAddress
                ]
            ]);
            if ($res->getStatusCode() == 200) {
                $resObject = json_decode($res->getBody());
                if (count($resObject->results) > 1) {
                    $location  = $resObject->results[0]->geometry->location;

                    $user->latitude  = $location->lat;
                    $user->longitude = $location->lng;

                    Log::info(json_encode($location));
                }
            }
        }
    }

    public function updateProfile(
        array $profileData,
        $logoFile = null,
        $profileFile = null
    ) {
        $user = app(User::class);

        $user->fill(
            extractKeysOfArray(
                $user->fillable,
                $profileData
            )
        );

        if (isset($logoFile) && $logoFile != null) {
            $randomName = generateRandomString(20);

            if ($logoFile->move('public/logo', $randomName . '.jpeg')) {
                $user->logo = 'public/logo/' . $randomName . '.jpeg';
            }
        }

        if (isset($profilePicture) && $profilePicture != null) {
            $randomName = generateRandomString(20);

            if ($profilePicture->move('public/profile_picture', $randomName . '.jpeg')) {
                $user->profile_picture = 'public/profile_picture/' . $randomName . '.jpeg';
            }
        }

        $user->save();
        
        return User::where('id', $user->id)
                ->with('card')
                ->first();
    }
}
