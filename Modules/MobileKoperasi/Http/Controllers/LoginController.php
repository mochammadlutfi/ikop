<?php

namespace Modules\MobileKoperasi\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
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
        // Auth::guard('admin')->logout();
        // return redirect()->route('login');
        echo 'dsaknlkdsa';
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
