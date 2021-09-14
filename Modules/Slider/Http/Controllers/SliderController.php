<?php
 
namespace Modules\Slider\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;
use DB;
use Carbon\Carbon;

 
use Modules\Slider\Entities\Slider;

class SliderController extends Controller
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
     * Show Admin Dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Slider::orderBy('created_at', 'DESC')->paginate(10);
            return response()->json($data);
        }

        return view('slider::index');
    }

    public function tambah()
    {
        return view('slider::form');
    }


    public function simpan(Request $request)
    {
        // dd($request->all());
        $rules = [
            'type' => 'required',
            'featured_img' => 'required',
        ];

        $pesan = [
            'type.required' => 'Jenis Slide Wajib Diisi!',
            'featured_img.required' => 'Foto Slide Wajib Diisi!',
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
                $data = new Slider();
                $data->type = $request->type;
                if($request->type === '1' || $request->type === '2')
                {
                    
                    $data->url = $request->slug;

                }else if($request->type === '3')
                {
                    $data->url = $request->link;
                }


                if(!empty($request->featured_img))
                {
                    $folderPath = 'uploads/slider/';
                    $imageName = $folderPath . uniqid() . '.jpg';
                    list($baseType, $image) = explode(';', $request->featured_img);
                    list(, $image) = explode(',', $image);
                    $image = base64_decode($image);
                    $p = Storage::disk('public')->put($imageName, $image);
                    $data->img = $imageName;
                }

                $data->is_active = $request->status;
                $data->save();
            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => $e,
                ]);
            }
            DB::commit();
            return response()->json([
                'fail' => false,
                'pesan' => 'Slide Baru Berhasil Disimpan!',
            ]);
        }
    }

    public function edit($id){
        $data = Slider::find($id);
        if($data->type == '1'){
            // $post = Post::where('slug', $data->url)->first();
            // $data->slug = $post->judul;
        }else if($data->type == '2'){
            // $page = Page::where('slug', $data->url)->first();
            // $data->slug = $page->judul;
        }
        // dd($data);
        return view('slider::edit', compact('data'));
    }

    public function update(Request $request)
    {

        $rules = [
            'type' => 'required',
        ];

        $pesan = [
            'type.required' => 'Jenis Slide Wajib Diisi!',
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
                $data = Slider::find($request->id);

                if(!empty($request->featured_img))
                {
                    $cek = Storage::disk('public')->exists($data->img);
                    if($cek)
                    {
                        Storage::disk('public')->delete($data->img);
                    }

                    $folderPath = 'slider/';
                    $imageName = $folderPath . uniqid() . '.jpg';
                    list($baseType, $image) = explode(';', $request->featured_img);
                    list(, $image) = explode(',', $image);
                    $image = base64_decode($image);
                    $p = Storage::disk('umum')->put($imageName, $image);
                    $data->img = 'uploads/'.$imageName;
                }

                $data->type = $request->type;
                if($request->type === '1' || $request->type === '2')
                {
                    
                    $data->url = $request->slug;

                }else if($request->type === '3')
                {
                    $data->url = $request->link;
                }
                $data->is_active = $request->status;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => $e,
                ]);
            }
            DB::commit();
            return response()->json([
                'fail' => false,
                'pesan' => 'Slide Berhasil Diperbaharui!',
            ]);
        }
    }

    public function hapus($id)
    {
        DB::beginTransaction();
        try{
            $data = Slider::find($id);
            $delete = Storage::disk('public')->delete($data->img);
            Slider::destroy($data->id);
        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'fail' => true,
                'pesan' => $e,
            ]);
        }
        DB::commit();
        return response()->json([
            'fail' => false,
        ]);
    }
}
