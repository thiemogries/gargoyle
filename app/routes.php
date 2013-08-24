<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::model('user', 'User');
Route::model('link', 'Link');

Route::get('/', function()
{
	return View::make('hello');
});

Route::any('/login', function() {
	$name = Input::get('name', '');
	$password = Input::get('password', '');

	if (Auth::attempt(array('name' => $name, 'password' => $password), true)) {
		$status = 'success';
	} else {
		$status = 'failed';
	}

	return Response::json(array('status' => $status));
});

Route::any('/logout', function() {
	Auth::logout();
});

Route::any('/user/new', function() {
	$email = Input::get('email', '');
	$name = Input::get('name', '');
	$password = Input::get('password', '');

	if (strlen($name) > 0 and strlen($password) > 0) {
		$user = new User;
		$user->email = $email;
		$user->name = $name;
		$user->password = Hash::make($password);
		$user->save();

		$user->follower()->attach($user);
		$user->save();

		return $user;
	}
});

Route::any('/user/{user}', array('before' => 'auth', function(User $user) {
	return $user;
}));

Route::any('/push', array('before' => 'auth', function() {
	$user = Auth::user();
	$url = Input::get('url', '');

	if (strlen($url) > 0) {
		$link = new Link;
		$link->url = $url;
		$link->user()->associate($user);
		$link->save();
		return $link;
	}
}));

Route::any('/delete/{link}', array('before' => 'auth', function(Link $link) {
	$link->delete();
}));

Route::any('/follow/{user}', array('before' => 'auth', function(User $user) {
	$follower = Auth::user();
	$user->follower()->attach($follower);
}));

Route::any('/unfollow/{user}', array('before' => 'auth', function(User $user) {
	$follower = Auth::user();
	$user->follower()->detach($follower);
}));

Route::any('/feed', array('before' => 'auth', function() {
	$user = Auth::user();
	$result = DB::table('links')
            ->join('follows', 'links.user_id', '=', 'follows.followed_id')
            ->where('follows.follower_id', '=', $user->id)
            ->select('links.id', 'links.url')
            ->get();

    $links = array();
    foreach ($result as $link) {
    	$links[] = array(
    		'linkid' => $link->id,
    		'url' => $link->url
    	);
    }

    return json_encode($links);
}));

Route::any('/feed/me', array('before' => 'auth', function() {
	return Auth::user()->links;
}));