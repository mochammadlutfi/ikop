<?php

namespace Modules\Keuangan\Http\Controllers;



use Modules\Bank\Entities\Bank;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Storage;
class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $keyword  = $request->keyword;

            $data = Bank::orderBy('created_at', 'DESC')
            ->paginate(20);

            return response()->json($data, 200);
        }

        return view('keuangan::bank');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('keuangan::bank.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $rules = [
            'atas_nama' => 'required|max:150',
            'rekening' => 'required|numeric|digits:13',
            'nama' => 'required',
            'kode' => 'required',
        ];

        $pesan = [
            'atas_nama.required' => 'Atas Nama Rekening Wajib Diisi!',
            'rekening.required' => 'No Rekening Wajib Diisi!',
            'rekening.numeric' => 'No Rekening Harus Angka!',
            'rekening.digits' => 'No Rekening Harus 13 Angka!',
            'nama.required' => 'Nama Bank Wajib Diisi!',
            'kode.required' => 'Kode Bank Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{
            DB::beginTransaction();
            try{
                $data = new Bank();
                $data->nama = $request->nama;
                $data->kode = $request->kode;
                $data->atas_nama = $request->atas_nama;
                $data->no_rekening = $request->rekening;
                
                if(!empty($request->file('icon')))
                {
                    $cek = Storage::disk('public')->exists($data->icon);
                    if($cek)
                    {
                        Storage::disk('public')->delete($data->icon);
                    }
                    $p = Storage::disk('public')->putFile(
                        'rekening',
                        $request->file('icon'),
                    );
                    $data->logo = $p;
                }
                $data->save();
            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Error Menyimpan Data',
                    'log' => $e,
                ]);
            }
            DB::commit();
            return response()->json([
                'fail' => false,
            ]);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('keuangan::bank.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('keuangan::bank.edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {

    }
}
