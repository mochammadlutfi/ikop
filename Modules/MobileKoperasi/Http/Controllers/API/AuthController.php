<?php

namespace Modules\MobileKoperasi\Http\Controllers\API;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;
use Hash;
class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating admin users for the application and
    | redirecting them to your admin dashboard.
    |
     */

    
     /**
     * Logout the admin.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
    }


    /**
     * Login the admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        
        $input = $request->all();

        $rules = [
            'no_hp' => 'required',
            'password' => 'required|string'
        ];

        $pesan = [
            'no_hp.required' => 'Alamat no_hp Wajib Diisi!',
            'password.required' => 'Password Wajib Diisi!',
        ];
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'msg' => 'Terdapat Kesalahan Di Form!',
                'errors' => $validator->errors(),
            ], 401);
            
            // $this->sendError('User not found', 401);
        }else{
            if(auth()->attempt($request->only('no_hp','password')))
            {
                $data = auth()->user();
                $data->save();
                $data->api_token = auth()->user()->createToken('authToken')->accessToken;
                $data->nama = $data->anggota->nama;
                // $data->makeVisible('secure_pin');
                
                // return $this->sendResponse($data, 'User retrieved successfully', 200);
                return response()->json([
                    'data' => $data,
                    'fail' => false,
                ], 200);
            }else{
                $gagal['password'] = array('Password salah!');
                return response()->json([
                    'fail' => true,
                    'errors' => $gagal,
                ], 401);
            }
        }
    }

    public function setup_pin(Request $request)
    {
        $rules = [
            'secure_code' => 'required',
        ];

        $pesan = [
            'secure_code.required' => 'PIN Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'msg' => 'Terdapat Kesalahan Di Form!',
                'errors' => $validator->errors(),
            ], 401);
        }else{

            $user = User::find($request->user()->id);
            $user->secure_code = bcrypt($request->secure_code);
            $user->save();

            return response()->json([
                'data' => $user,
                'fail' => false,
            ], 200);
        }
    }

    public function pin_access(Request $request)
    {
        $rules = [
            'secure_code' => 'required',
        ];

        $pesan = [
            'secure_code.required' => 'PIN Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'msg' => 'Terdapat Kesalahan Di Form!',
                'errors' => $validator->errors(),
            ], 401);
        }else{
            
            $user = User::find($request->user()->id);
            if (Hash::check($request->secure_code, $user->secure_code)) {
                return response()->json([
                    'access' => true,
                    'fail' => false,
                ], 200);

            }else{
                $gagal['password'] = array('Password salah!');
                return response()->json([
                    'access' => false,
                    'errors' => $gagal,
                ], 401);
            }
        }
    }

    /**
     * Logout the admin.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('login');
    }


    /**
     * Redirect back after a failed login.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function loginFailed(){
        return redirect()
            ->back()
            ->withInput()
            ->with('error','Login failed, please try again!');
    }
}
