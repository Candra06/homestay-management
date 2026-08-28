<?php

namespace App\Http\Controllers;

use App\Helper\Helpers;
use App\Models\Facility;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Attachment;

class FacilityController extends Controller
{
    public $dataPage = [
        "forms" => [
            [
                'name' => 'nama_fasilitas',
                'title' => 'Nama Fasilitas',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Nama Fasilitas',
            ],
            
            [
                'name' => 'icon',
                'title' => 'Icon',
                'type' => 'text',
                'required' => false,
                'placeholder' => 'Icon Fasilitas',
            ],
            [
                'name' => 'type',
                'title' => 'Tipe',
                'type' => 'select',
                'required' => true,
                'class' => 'select2-no-search',
                'placeholder' => 'Pilih tipe fasilitas',
                 'data' => [
                    [
                        'id' => 'room',
                        'val' => 'Kamar',
                    ],
                    [
                        'id' => 'general',
                        'val' => 'Umum',
                    ],
                ],
            ],
            [
                'name' => 'deskripsi',
                'title' => 'Deskripsi',
                'type' => 'textarea',
                'required' => false,
                'placeholder' => 'Deskripsi singkat fasilitas',
            ],
            [
                'name' => 'image',
                'title' => 'Foto Fasilitas',
                'type' => 'file',
                'custom-class-wrapper' => 'col-12',
                'class_input' => 'dropify',
                'required' => false,
                'placeholder' => 'Pilih Gambar',
            ],
        ],
        "route" => [
            'index' => 'facility.index',
            'add' => 'facility.create',
            'show' => 'facility.show',
            'update' => 'facility.update',
            'edit' => 'facility.edit',
            'store' => 'facility.store',
            'detail' => 'facility.detail',
            'delete' => 'facility.destroy',
        ],
        "tableHead" => ["No", "Nama Fasilitas", "Jenis Fasilitas", "Deskripsi Singkat","Icon","aksi"],
        "tableColumns" => ["DT_RowIndex", "nama_fasilitas", "type", "deskripsi","icon","action"],
    ];
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         try {
            $dataPage = $this->dataPage;
            $list = Facility::get();
            $akses = request()->attributes->get("hakAkses");
            if ($akses['access_edit'] != 'Y' && $akses['access_delete'] != 'Y') {
                unset($dataPage['tableHead'][6]);
                unset($dataPage['tableColumns'][6]);
            }
            $data = (object)[
                'title' => 'Data Fasilitas',
                "createBtn" => $akses['access_create'] == 'Y',
                'tableHead' => $dataPage['tableHead'],
                'tableColumns' => Helpers::tableColumns($dataPage['tableColumns']),
                "routeAdd" => route($dataPage['route']['add']),
                "routeData" => route($dataPage['route']['index']),
                'data' => $list,
            ];

            if (request()->ajax()) {
                return $this->ajax($list);
            }
            // return $data;
            return view('pages.facility.index', compact('data'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $forms = $this->dataPage['forms'];

        $data = (object) [
            'title' => 'Tambah Data Fasilitas',
            'subtitle' => 'Data Fasilitas',
            'base_title' => 'Tambah Data',
            'type' => 'add',
            'action' => route($this->dataPage['route']['store']),
            'data' => [],
            'forms' => $forms,
        ];
        return view('template.form', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $imagePaths = '';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = storage_path('app/public/facility');
            $photo = 'facility/' . $this->compress($file, $path, 50);
            $imagePaths = [
                        'file_path'=>$photo,
                        'file_name'=> basename($photo),
                        'original_name'=>$file->getClientOriginalName(),
                        'file_size'=>Storage::disk('public')->size($photo),
                        'mime_type'=>Storage::disk('public')->mimeType($photo)
                    ];
        }
        $input = [
            'nama_fasilitas'=> $request->nama_fasilitas,
            'type' =>$request->type,
            'deskripsi' =>$request->deskripsi,
            'icon' =>$request->icon,
        ];
        $facility = Facility::create($input);
        if ($request->hasFile('image')) {
            $inputAttachment = [
                    'reff_feature' => 'facility',
                    'file_url' => $imagePaths['file_path'],
                    'file_name' => $imagePaths['file_name'],
                    'original_name' => $imagePaths['original_name'],
                    'mime_type' => $imagePaths['mime_type'],
                    'reff_id' => $facility->id,
                    'file_size' => $imagePaths['file_size'],
                ];
            Attachment::create($inputAttachment);
        }
        try {
            DB::commit();
            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menambah data fasilitas');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Gagal menambah data fasilitas');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Facility $facility)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facility $facility)
    {
        try {
            $page = $this->dataPage;
            $dataForm = [];
            $dataForm = $facility->load('attachments')->toArray();
            // return $dataForm;
            $data = (object) [
                'title' => 'Data Fasilitas',
                'subtitle' => 'Edit Data',
                'type' => 'edit',
                'action' => route($page['route']['update'], ['facility' => $facility]),
                'data' => $dataForm,
                'forms' => $page['forms'],
            ];
            // return $data;
            return view('template.form', compact('data'));
       } catch (\Throwable $th) {
        throw $th;
       }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Facility $facility)
    {
        DB::beginTransaction();
        try {
            $input = [
                'nama_fasilitas'=> $request->nama_fasilitas,
                'type' =>$request->type,
                'deskripsi' =>$request->deskripsi,
                'icon' =>$request->icon,
            ];
            $facility->update($input);
            if ($request->hasFile('image')) {
                $imagePaths = '';
                $file = $request->file('image');
                $path = storage_path('app/public/facility');
                $photo = 'facility/' . $this->compress($file, $path, 50);
                $imagePaths = [
                    'file_path'=>$photo,
                    'file_name'=> basename($photo),
                    'original_name'=>$file->getClientOriginalName(),
                    'file_size'=>Storage::disk('public')->size($photo),
                    'mime_type'=>Storage::disk('public')->mimeType($photo)
                ];
            }
            if ($request->hasFile('image')) {
                $oldAttachments = Attachment::where('reff_feature', 'facility')
                    ->where('reff_id', $facility->id)
                    ->get();

                foreach ($oldAttachments as $old) {
                    if ($old->file_url && Storage::disk('public')->exists($old->file_url)) {
                        Storage::disk('public')->delete($old->file_url);
                    }
                }

                Attachment::where('reff_feature', 'facility')
                    ->where('reff_id', $facility->id)
                    ->delete();

                $inputAttachment = [
                        'reff_feature' => 'facility',
                        'file_url' => $imagePaths['file_path'],
                        'file_name' => $imagePaths['file_name'],
                        'original_name' => $imagePaths['original_name'],
                        'mime_type' => $imagePaths['mime_type'],
                        'reff_id' => $facility->id,
                        'file_size' => $imagePaths['file_size'],
                    ];
                Attachment::create($inputAttachment);
            }
            DB::commit();
            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menambah data fasilitas');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Gagal menambah data fasilitas');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facility $facility)
    {
         try {
            DB::beginTransaction();
            $oldAttachments = Attachment::where('reff_feature', 'facility')
                ->where('reff_id', $facility->id)
                ->get();

            foreach ($oldAttachments as $old) {
                if ($old->file_url && Storage::disk('public')->exists($old->file_url)) {
                    Storage::disk('public')->delete($old->file_url);
                }
            }

            Attachment::where('reff_feature', 'facility')
                ->where('reff_id', $facility->id)
                ->delete();
                
            $facility->delete();
            DB::commit();
            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menghapus data fasilitas');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Gagal menghapus data fasilitas');
        }
    }

    function ajax($list)
    {
        return DataTables::of($list)
            ->addIndexColumn()
            ->smart(false)
            ->addColumn("type", function ($row) {
                return $row->type == 'room' ? 'Kamar' : 'Umum';
            })
            ->addColumn("action", function ($row) {
                $akses = request()->attributes->get("hakAkses");
                $editRoute = route($this->dataPage['route']['edit'], $row->id);
                $detailRoute = route($this->dataPage['route']['show'], $row->id);
                $deleteRoute = route($this->dataPage['route']['delete'], $row->id);
                $message = 'Apakah Anda yakin untuk menghapus fasilitas ' . $row->nama_fasilitas . ' ?';

                $actionBtn = $akses["access_edit"] != 'Y'? '': '<a href="'. $editRoute .'"><button class="btn-sm me-2 btn" style="font-size:24px;"><span class="fe fe-edit"></span></button></a>';
                $actionBtn  .= $akses["access_delete"] != 'Y'? '': '<button class="btn-sm mr-2 modal-effect btn" data-bs-effect="effect-scale" data-bs-toggle="modal" style="font-size:24px;" onclick="deleteData(\'' . $deleteRoute . '\', \'' . $message . '\')" href="#modal-delete"><span class="fe fe-trash"></span></button>' ;

                return $actionBtn;
            })
            ->rawColumns(["action"])
            ->make(true);
    }
}
