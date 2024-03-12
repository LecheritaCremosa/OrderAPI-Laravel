<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    
    private $rules =[
        'legalization_date' =>'required|date',
        'address' => 'required|string|max:255|min:3',
        'city' => 'required|string|max:255|min:3',
        'observation_id' => 'required|numeric',
        'causal_id' => 'required|numeric'
    ];

    private $traductionAttributes = [
        'legalization_date' => 'fecha de legalización',
        'address' => 'dirección',
        'city' => 'ciudad',
        'observation_id' => 'observación',
        'causal_id' => 'causal'
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
        $orders = Order::all();
        $orders->load(['observation', 'causal']);
        return response()->json($orders, Response::HTTP_OK);
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
        $order = Order::create($request->all());
        $response = [
            'message' => 'Registro Creado Exitosamente',
            'orders' => $order
        ];
        return response()->json($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['observation', 'causal']);
        return response()->json($order, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $data = $this->applyValidator($request);
        if (!empty($data)) {
            return $data;
        }
        $order->update($request->all());
        $response = [
            'message' => 'Registro Actualizado Exitosamente',
            'orders' => $order
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        $response = [
            'message' => 'Registro Eliminado Exitosamente',
            'order' => $order->id
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function add_activity(Order $order, Activity $activity)
    {
        $order->activities()->attach($activity->id);
        $response = [
            'message' => 'Actividad Agregada Exitosamente',
            'order_activity' => $order->activities
        ];
        return response()->json($response, Response::HTTP_CREATED);
    }

    public function remove_activity(Order $order, Activity $activity)
    {
        $order->activities()->detach($activity->id);
        $response = [
            'message' => 'Actividad Removida Exitosamente',
            'order_activity' => $order->activities
        ];
        return response()->json($response, Response::HTTP_CREATED);
    }
}
