<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'service';
        if($request->ajax()){
            $service = Service::get();
            return DataTables::of($service)
                ->addIndexColumn()
                ->addColumn('date_crea',function($service){
                    return date_format(date_create($service->date_crea),"d M,Y");
                })
                ->addColumn('action', function ($row) {
                    $editbtn = '<a href="'.route("service.edit", $row->id).'" class="editbtn"><button class="btn btn-primary"><i class="fas fa-edit"></i></button></a>';
                    $deletebtn = '<a data-id="'.$row->id.'" data-route="'.route('service.destroy', $row->id).'" href="javascript:void(0)" id="deletebtn"><button class="btn btn-danger"><i class="fas fa-trash"></i></button></a>';
                    if (!auth()->user()->hasPermissionTo('update-service')) {
                        $editbtn = '';
                    }
                    if (!auth()->user()->hasPermissionTo('delete-service')) {
                        $deletebtn = '';
                    }
                    $btn = $editbtn.' '.$deletebtn;
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.service.index',compact(
            'title'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'create service';
        return view('admin.service.create',compact(
            'title'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'libelle'=>'required|max:255',
        ]);
        Service::create([
            'libelle'=>$request->libelle,
        ]);
        $notification = notify("Service has been added");
        return redirect()->route('service.index')->with($notification);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \app\Models\Service $Service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        $title = 'edit service';
        return view('admin.service.edit',compact(
            'title','service'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \app\Models\Service $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $this->validate($request,[
            'libelle'=>'required|max:255',
        ]);
        $service->update([
            'libelle'=>$request->libelle,
        ]);
        $notification = notify("Service has been added");
        return redirect()->route('service.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        return Service::findOrFail($request->id)->delete();
    }
}
