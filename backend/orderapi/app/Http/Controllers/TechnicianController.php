<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TechnicianController extends Controller
{
   
    private $rules = [
        'document' => 'required|numeric|max:999999999|min:3',
        'name' => 'required|string|max:255',
        'especiality' => 'required|string|max:255',
        'phone' => 'required|numeric|max:9999999999999'
    ];
    
    private $traductionAttributes = [
        'document' => 'Documento',
        'name' => 'Nombre',
        'especiality' => 'Especialidad',
        'phone' => 'Telefono'
    ];
    
    public function applyValidator(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);
        $validator->setAttributeNames($this->traductionAttributes);
        $data = [];
        if ($validator->fails()) {
            $data = response()->json([
                'errors' => $validator->errors(),
                'data' => $request->all()
            ], Response::HTTP_BAD_REQUEST); 
        }
        return $data;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $technicians = Technician::all();
        return response()->json($technicians, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->applyValidator($request);
        if (!empty($data)) {
            return $data;
        }
        $technicians = Technician::create($request->all());
        $response = [
            'message' => 'Registro Creado Exitosamente',
            'technicians' => $technicians
        ];
        return response()->json($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Technician $technician)
    {
        return response()->json($technician, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Technician $technician)
    {
        $data = $this->applyValidator($request);
        if (!empty($data)) {
            return $data;
        }
        $technician->update($request->all());
        $response = [
            'message' => 'Registro Actualizado Exitosamente',
            'technician' => $technician
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Technician $technician)
    {
        $technician->delete();
        $response = [
            'message' => 'Registro Eliminado Exitosamente',
            'technician' => $technician->id
        ];
        return response()->json($response, Response::HTTP_OK);
    }
}
