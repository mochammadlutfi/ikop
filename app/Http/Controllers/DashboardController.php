<?php

namespace App\Http\Controllers;

use Modules\Keuangan\Entities\Pembayaran;
use Modules\Keuangan\Entities\Transaksi;
use Modules\Anggota\Entities\Anggota;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use DB;


use App\Models\User;
use App\Helpers\Notification;

class DashboardController extends Controller
{
    /**
     * Only Authenticated users for "admin" guard
     * are allowed.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {


        return view('dashboard.index');
    }
}
