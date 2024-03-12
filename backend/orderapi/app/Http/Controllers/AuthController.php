<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $rules = [
        'name' => 'required|string|max:225',
        'email' => 'required|string|email|unique:users',
        'password' => 'required|string|min:8|max:255',
        'password_confirmation' => 'required|same:password'
    ];

    private $traductionAttributes = array(
        'name' => 'Nombre',
        'password' => 'Contraseña'
    );
    
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
    
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if(Auth::attempt($credentials))
        {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            return response()->json([
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ], Response::HTTP_OK);            
        }
        else 
        {
            return response()->json([
                'message' => 'Credenciales Invalidas'
            ], Response::HTTP_UNAUTHORIZED);
        }

    }

    //Cierra Una Sesión y Borra El Token
    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Sesión Cerrada'
        ], Response::HTTP_OK);
    }

    public function register(Request $request)
    {
        $data = $this->applyValidator($request);
        if (!empty($data)) {
            return $data;
        }

        $request['password'] = bcrypt($request['password']);
        $user = User::create($request->all());
        $response = [
            'message' => 'Registro Creado Exitosamente',
            'user' => $user
        ];
        return response()->json($response, Response::HTTP_CREATED);

    }
}