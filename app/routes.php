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
Route::model('follower', 'User');

Route::get('/', function()
{
	return View::make('hello');
});

Route::any('/user/new', array('as' => 'user.new', function() {
	$email = Input::get('email', rand(1, 1000) . '@world.de');
	$name = Input::get('name', 'Nikola Tesla');
	$password = Input::get('password', '123456');

	if (strlen($email) > 0 and strlen($name) > 0 and strlen($password) > 0) {
		$user = new User;
		$user->email = $email;
		$user->name = $name;
		$user->password = $password;
		$user->save();

		$user->follower()->attach($user);

		$user->save();

		return $user->id;
	}
}));

Route::any('/user/{user}', array('as' => 'user', function(User $user) {
	return $user;
}));

Route::any('/push/{user}', array('as' => 'push', function(User $user) {

	$url = Input::get('url', 'bla');

	if (strlen($url) > 0) {
		$link = new Link;
		$link->url = $url;
		$link->user()->associate($user);
		$link->save();
	}
}));

Route::any('/feed/{user}/me', array('as' => 'feed.me', function(User $user) {
	return $user->links;
}));

Route::any('/follow/{user}/by/{follower}', function(User $user, User $follower) {
	$user->follower()->attach($follower);
});

Route::any('/feed/{user}', array('as' => 'follow', function(User $user) {
	$urls = DB::table('links')
            ->join('follows', 'links.user_id', '=', 'follows.followed_id')
            ->where('follows.follower_id', '=', $user->id)
            ->select('links.url')
            ->get();

    foreach ($urls as $url) {
    	var_dump($url);
    }
}));