<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class RegisterController extends Controller
{
    use RegistersUsers;
    protected $redirectTo = '/';
    public function __construct()
    {
        $this->middleware('guest');
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:user,seller'],
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    }
    protected function create(array $data)
    {
        $avatarPath = 'assets/img/users/default.png'; // صورة افتراضية
        if (isset($data['avatar']) && $data['avatar']->isValid()) {
            $imageName = Str::uuid()->toString() . '.' . $data['avatar']->getClientOriginalExtension();
            $data['avatar']->move(public_path('assets/img/users'), $imageName);
            $avatarPath = 'assets/img/users/' . $imageName;
        }
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'avatar' => $avatarPath,
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);
}

}
