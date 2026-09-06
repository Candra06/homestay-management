<?php

namespace App\Http\Controllers;

use App\Helper\Helpers;
use App\Models\Facility;
use App\Models\RoomFacilities;
use App\Models\RoomTypes;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RoomTypesController extends Controller
{
    public $dataPage = [
        'forms' => [
            [
                'name' => 'type_name',
                'title' => 'Tipe Kamar',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Tipe Kamar',
            ],

            [
                'name' => 'original_price',
                'title' => 'Harga Normal',
                'type' => 'currency',
                'other-attr' => ' data-type=currency ',
                'required' => true,
                'placeholder' => 'Harga Normal',
            ],
            [
                'name' => 'base_price',
                'title' => 'Harga Pemesanan',
                'type' => 'currency',
                'other-attr' => ' data-type=currency ',
                'required' => true,
                'placeholder' => 'Harga Pemesanan',
            ],
            [
                'name' => 'kapasitas',
                'title' => 'Kapasitas Tamu',
                'type' => 'number',
                'other-attr' => ' min=0 ',
                'required' => true,
                'placeholder' => 'Kapasitas Tamu',
            ],
            [
                'name' => 'wide',
                'title' => 'Luas Kamar',
                'type' => 'number',
                'other-attr' => ' min=0 ',
                'required' => true,
                'placeholder' => 'Luas Kamar (m2)',
            ],
            [
                'name' => 'bed_type',
                'title' => 'Jenis Bed',
                'type' => 'select',
                'required' => true,
                'class' => 'select2-no-search',
                'placeholder' => 'Pilih tipe kasur',
                'data' => [
                    [
                        'id' => 'king',
                        'val' => 'Single (1)',
                    ],
                    [
                        'id' => 'twin',
                        'val' => 'Double (2)',
                    ],
                ],
            ],
            [
                'name' => 'tagline',
                'title' => 'Tagline',
                'type' => 'textarea',
                'required' => false,
                'placeholder' => 'Opsional (Ditampilkan pada informasi kamar)',
            ],
            [
                'name' => 'description',
                'title' => 'Deskripsi Kamar',
                'type' => 'textarea',
                'required' => false,
                'placeholder' => 'Opsional (Ditampilkan pada informasi kamar)',
            ],
            
        ],
        'route' => [
            'index' => 'room-type.index',
            'add' => 'room-type.create',
            'show' => 'room-type.show',
            'update' => 'room-type.update',
            'edit' => 'room-type.edit',
            'store' => 'room-type.store',
            'detail' => 'room-type.detail',
            'delete' => 'room-type.destroy',
        ],
        'tableHead' => ['No', 'Tipe Kamar', 'Kapasitas', 'Jenis Bed', 'Slug','Harga', 'aksi'],
        'tableColumns' => ['DT_RowIndex', 'type_name', 'kapasitas', 'bed_type', 'slug', 'base_price', 'action'],
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $dataPage = $this->dataPage;
            $list = RoomTypes::with('attachments')->get();
            
            $akses = request()->attributes->get('hakAkses');
            if ($akses['access_edit'] != 'Y' && $akses['access_delete'] != 'Y') {
                unset($dataPage['tableHead'][6]);
                unset($dataPage['tableColumns'][6]);
            }
            $data = (object) [
                'title' => 'Tipe Kamar',
                'createBtn' => $akses['access_create'] == 'Y',
                'tableHead' => $dataPage['tableHead'],
                'tableColumns' => Helpers::tableColumns($dataPage['tableColumns']),
                'routeAdd' => route($dataPage['route']['add']),
                'routeData' => route($dataPage['route']['index']),
                'data' => $list,
            ];

            if (request()->ajax()) {
                return $this->ajax($list);
            }

            // return $data;
            return view('pages.room-types.index', compact('data'));
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
        $facility = Facility::where('type', 'room')->get();

        foreach ($facility as $f) {
            $forms[] = [
                'name' => 'facility[]',
                'title' => $f->nama_fasilitas,
                'type' => 'checkbox',
                'required' => false,
                'custom-class-wrapper' => 'col-md-2 col-4',
                'value' => $f->id,
                'placeholder' => '',
            ];
        }
        $forms[] = [
                'name' => 'image',
                'title' => 'Foto Tipe Kamar',
                'type' => 'file',
                'custom-class-wrapper' => 'col-12',
                'class_input' => 'dropify',
                'other-attr' => ' multiple ',
                'required' => false,
                'placeholder' => 'Pilih Gambar',
        ];
        $data = (object) [
            'title' => 'Tambah Data Tipe Kamar',
            'subtitle' => 'Tipe Kamar',
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
        try {
            $imagePaths = [];
            $files = $request->file('image');
            if (!empty($files)) {
                if (!is_array($files)) {
                    $files = [$files];
                }
                foreach ($files as $file) {
                    if ($file && $file->isValid()) {
                        $path = storage_path('app/public/room_types');
                        $photo = 'room_types/' . $this->compress($file, $path, 50);
                        $imagePaths[] = [
                            'file_path'=>$photo,
                            'file_name'=> basename($photo),
                            'original_name'=>$file->getClientOriginalName(),
                            'file_size'=>Storage::disk('public')->size($photo),
                            'mime_type'=>Storage::disk('public')->mimeType($photo)
                        ];
                    }
                }
            }
            
            $input = [
                'type_name' => $request->type_name,
                'kapasitas' => $request->kapasitas,
                'bed_type' => $request->bed_type,
                'base_price' => str_replace('.', '', $request->base_price),
                'slug' => Str::slug($request->type_name).'-'.$request->bed_type,
            ];
           
            $room = RoomTypes::create($input);
    
    
            if ($request->has('facility') && is_array($request->facility)) {
                $insertRoomFacilities = [];
                foreach ($request->facility as $key => $value) {
                    $insertRoomFacilities[] = [
                        'id_room' => $room->id,
                        'id_facility' => $value,
                    ];
                }
                RoomFacilities::insert($insertRoomFacilities);
            }
            if (count($imagePaths) > 0) {
                $insertAttachment = [];
                foreach ($imagePaths as $key => $value) {
                    $insertAttachment[] = [
                        'reff_feature' => 'room-types',
                        'file_url' => $value['file_path'],
                        'file_name' => $value['file_name'],
                        'original_name' => $value['original_name'],
                        'mime_type' => $value['mime_type'],
                        'reff_id' => $room->id,
                        'file_size' => $value['file_size'],
                    ];
                }
                Attachment::insert($insertAttachment);
            }
            DB::commit();

            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menambah data tipe kamar');
        } catch (\Throwable $th) {
            DB::rollback();

            return back()->with('error', 'Gagal menambah data tipe kamar: ' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomTypes $room_type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoomTypes $room_type)
    {
        try {
            $page = $this->dataPage;
            $dataForm = $room_type->load('attachments')->toArray();
            $forms = $page['forms'];
            $facility = Facility::where('type', 'room')->get();

            foreach ($facility as $f) {
                $forms[] = [
                    'name' => 'facility[]',
                    'title' => $f->nama_fasilitas,
                    'type' => 'checkbox',
                    'required' => false,
                    'custom-class-wrapper' => 'col-md-2 col-4',
                    'other-attr' => in_array($f->id, $room_type->facilities->pluck('id_facility')->toArray()) ? 'checked' : '',
                    'value' => $f->id,
                    'placeholder' => '',
                ];
            }
            $forms[] = [
                'name' => 'image',
                'title' => 'Foto Tipe Kamar',
                'type' => 'file',
                'custom-class-wrapper' => 'col-12',
                'class_input' => 'dropify',
                'other-attr' => ' multiple ',
                'required' => false,
                'placeholder' => 'Pilih Gambar',
            ];
            
            $data = (object) [
                'title' => 'Edit Data Tipe Kamar',
                'subtitle' => 'Tipe Kamar',
                'type' => 'edit',
                'action' => route($page['route']['update'], ['room_type' => $room_type]),
                'data' => $dataForm,
                'forms' => $forms,
            ];

            return view('template.form', compact('data'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoomTypes $room_type)
    {
        DB::beginTransaction();
        try {
            $input = [
                'type_name' => $request->type_name,
                'kapasitas' => $request->kapasitas,
                'bed_type' => $request->bed_type,
                'base_price' => str_replace('.', '', $request->base_price),
                'slug' => Str::slug($request->type_name).'-'.$request->bed_type,
            ];

            RoomTypes::where('id', $room_type->id)->update($input);

            RoomFacilities::where('id_room', $room_type->id)->delete();
            if ($request->has('facility') && is_array($request->facility)) {
                $insertRoomFacilities = [];
                foreach ($request->facility as $key => $value) {
                    $insertRoomFacilities[] = [
                        'id_room' => $room_type->id,
                        'id_facility' => $value,
                    ];
                }
                RoomFacilities::insert($insertRoomFacilities);
            }

            $imagePaths = [];
            $files = $request->file('image');
            if (!empty($files)) {
                if (!is_array($files)) {
                    $files = [$files];
                }
                foreach ($files as $file) {
                    if ($file && $file->isValid()) {
                        $path = storage_path('app/public/room_types');
                        $photo = 'room_types/' . $this->compress($file, $path, 50);
                        $imagePaths[] = [
                            'file_path' => $photo,
                            'file_name' => basename($photo),
                            'original_name' => $file->getClientOriginalName(),
                            'file_size' => Storage::disk('public')->size($photo),
                            'mime_type' => Storage::disk('public')->mimeType($photo)
                        ];
                    }
                }
            }

            if (count($imagePaths) > 0) {
                // Delete old physical files and attachment records
                $oldAttachments = Attachment::where('reff_feature', 'room-types')
                    ->where('reff_id', $room_type->id)
                    ->get();

                foreach ($oldAttachments as $old) {
                    if ($old->file_url && Storage::disk('public')->exists($old->file_url)) {
                        Storage::disk('public')->delete($old->file_url);
                    }
                }

                Attachment::where('reff_feature', 'room-types')
                    ->where('reff_id', $room_type->id)
                    ->delete();

                // Insert new attachment records
                $insertAttachment = [];
                foreach ($imagePaths as $key => $value) {
                    $insertAttachment[] = [
                        'reff_feature' => 'room-types',
                        'file_url' => $value['file_path'],
                        'file_name' => $value['file_name'],
                        'original_name' => $value['original_name'],
                        'mime_type' => $value['mime_type'],
                        'reff_id' => $room_type->id,
                        'file_size' => $value['file_size'],
                    ];
                }
                Attachment::insert($insertAttachment);
            }

            DB::commit();

            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil memperbarui data tipe kamar');
        } catch (\Throwable $th) {
            DB::rollback();

            return back()->with('error', 'Gagal memperbarui data tipe kamar: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomTypes $room_type)
    {
        try {
            DB::beginTransaction();

            $oldAttachments = Attachment::where('reff_feature', 'room-types')
                ->where('reff_id', $room_type->id)
                ->get();

            foreach ($oldAttachments as $old) {
                if ($old->file_url && Storage::disk('public')->exists($old->file_url)) {
                    Storage::disk('public')->delete($old->file_url);
                }
            }

            Attachment::where('reff_feature', 'room-types')
                ->where('reff_id', $room_type->id)
                ->delete();

            $room_type->delete();
            DB::commit();

            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menghapus data tipe kamar');
        } catch (\Throwable $th) {
            DB::rollback();

            return back()->with('error', 'Gagal menghapus data tipe kamar');
        }
    }

    public function ajax($list)
    {
        return DataTables::of($list)
            ->addIndexColumn()
            ->smart(false)
            ->addColumn('bed_type', function ($row) {
                return $row->bed_type == 'king' ? 'King (1 kasur)' : 'Twin (2 kasur)';
            })
            ->addColumn('base_price', function ($row) {
                return 'Rp. '.number_format($row->base_price, 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                $akses = request()->attributes->get('hakAkses');
                $editRoute = route($this->dataPage['route']['edit'], $row->id);
                $detailRoute = route($this->dataPage['route']['show'], $row->id);
                $deleteRoute = route($this->dataPage['route']['delete'], $row->id);
                $message = 'Apakah Anda yakin untuk menghapus tipe kamar '.$row->type_name.' ?';

                $actionBtn = $akses['access_edit'] != 'Y' ? '' : '<a href="'.$editRoute.'"><button class="btn-sm me-2 btn" style="font-size:24px;"><span class="fe fe-edit"></span></button></a>';
                $actionBtn .= $akses['access_delete'] != 'Y' ? '' : '<button class="btn-sm mr-2 modal-effect btn" data-bs-effect="effect-scale" data-bs-toggle="modal" style="font-size:24px;" onclick="deleteData(\''.$deleteRoute.'\', \''.$message.'\')" href="#modal-delete"><span class="fe fe-trash"></span></button>';

                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
