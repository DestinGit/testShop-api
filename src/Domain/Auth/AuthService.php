<?php

namespace App\Domain\Auth;


use App\Domain\User\User;
use DateTime;
use Firebase\JWT\JWT;
use Tuupola\Base62;

class AuthService
{
	/**
	 * @param string $email
	 * @param string $password
	 * @return mixed
	 */
	public function attempt(string $email, string $password)
	{
		$user = User::where('email', $email)->first();
		if(!$user) {
			return false;
		}

		if(md5($password) === $user->password) {
			$_SESSION['user'] = $user->id;
			return $user;
		}
		return false;
	}

	public function check()
	{
		if (isset($_SESSION['user'])) {
			return isset($_SESSION['user']);
		}
	}

	public function exist($email): bool
	{
		$userExist = User::where('email', $email)->first();
		return !empty($userExist);
	}

	public function create(array $data)
	{
		$user = User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => md5($data['password'])
		]);

//		$newPassword = $user->id . md5($data['password']);
		$newPassword = $user->id . md5('bonjour');
		User::find($user->id)->update(['password' => $newPassword]);
		return $user;
	}

	public function logout()
	{
		unset($_SESSION['user']);
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public function getToken(): string
	{
		$now = new DateTime();
		$future = new DateTime("now +2 hours");
//		$jti = Base62::encode(random_bytes(16));
		$jti = (new Base62())->encode(random_bytes(16));

		$secret = "WORD_SECRET";

		$payload = [
			"jti" => $jti,
			"iat" => $now->getTimeStamp(),
			"nbf" => $future->getTimeStamp()
		];

		return JWT::encode($payload, $secret, "HS256");
	}
}