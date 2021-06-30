<?php

namespace Modules\MobileKoperasi\Http\Controllers\API;

use Modules\Simpanan\Entities\Wallet;


use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;

class SimpananController extends Controller
{

    
     /**
     * Get Saldo Simla.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getSimlaSaldo(Request $request)
    {
        $wallet = Wallet::where('anggota_id', $request->anggota_id)->first();

        return response()->json([
            'data' => currency($wallet->sukarela),
            'fail' => false,
        ], 200);
    }

    public function getSimpanan(Request $request)
    {
        $wallet = Wallet::where('anggota_id', $request->anggota_id)->first();
        $simpanan = collect([
            [
                'program' => 'Simpanan Pokok',
                'saldo' => currency($wallet->pokok),
                'slug' => 'pokok',
            ],
            [
                'program' => 'Simpanan Wajib',
                'saldo' => currency($wallet->wajib),
                'slug' => 'wajib',
            ],
            [
                'program' => 'Simpanan Sosial',
                'saldo' => currency($wallet->sosial),
                'slug' => 'sosial',
            ],
            [
                'program' => 'Simpanan Sukarela',
                'saldo' => currency($wallet->sukarela),
                'slug' => 'sukarela',
            ],
        ]);
        return response()->json([
            'data' => $simpanan,
            'fail' => false,
        ], 200);
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
