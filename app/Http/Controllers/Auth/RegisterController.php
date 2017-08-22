<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\Factory;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/';
    private $factory;
    private $user;

    public function __construct(Factory $factory, User $user)
    {
        $this->middleware('guest');
        $this->factory = $factory;
        $this->user = $user;
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data): Validator
    {
        return $this->factory->make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data): User
    {
        return $this->user->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password'])
            ]
        );
    }
}
