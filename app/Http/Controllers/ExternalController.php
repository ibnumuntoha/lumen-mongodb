<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class ExternalController extends BaseController
{
   
    public function register(Request $request)
    {
          /**
     * @OA\Post(
     *     path="/external/register",
     *     summary="Handle register",
     *     operationId="Handle register",
     *     description="Handle register",
     *     tags={"Handle External API"},
     *     @OA\RequestBody(
     *         description="Handle register",
     *         required=true,
     *      @OA\JsonContent(
     *           @OA\Property(property="email", type="string", format="text", example="eve.holt@reqres.in"),
     *           @OA\Property(property="password", type="string", format="text", example="cityslicka"),
     *
     *      ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="response",
     *         @OA\JsonContent(
     *              @OA\Property(property="success", type="bool", example="true"),
     *              @OA\Property(property="status", type="string", example="200"),
     *              @OA\Property(property="data", type="string", example=""),
     *          ),
     *     )
     * )
     */
        $response = Http::post('https://reqres.in/api/register', [
            'email' => $request->email,
            'password' => $request->password,
        ]);
        //$response->throw();
        if($response->serverError() ==true){
            $array['success'] = $response->successful();
            $array['status'] = $response->status() ;
            $array['message'] = 'error';
        }else{
            $array['success'] = $response->successful();
            $array['status'] = $response->status() ;
            $array['data'] = $response->getBody()->getContents();
        }
  
        return response()->json($array);
    }
     
    public function login(Request $request)
    {
      /**
     * @OA\Post(
     *     path="/external/login",
     *     summary="Handle Login",
     *     operationId="Handle Login",
     *     description="Handle Login",
     *     tags={"Handle External API"},
     *     @OA\RequestBody(
     *         description="Handle Login",
     *         required=true,
     *      @OA\JsonContent(
     *           @OA\Property(property="email", type="string", format="text", example="eve.holt@reqres.in"),
     *           @OA\Property(property="password", type="string", format="text", example="cityslicka"),
     *
     *      ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="response",
     *         @OA\JsonContent(
     *              @OA\Property(property="success", type="bool", example="true"),
     *              @OA\Property(property="status", type="string", example="200"),
     *              @OA\Property(property="data", type="string", example=""),
     *          ),
     *     )
     * )
     */
        $response = Http::post('https://reqres.in/api/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);
        //$response->throw();
        if($response->serverError() ==true){
            $array['success'] = $response->successful();
            $array['status'] = $response->status() ;
            $array['message'] = 'error';
        }else{
            $array['success'] = $response->successful();
            $array['status'] = $response->status() ;
            $array['data'] = $response->getBody()->getContents();
        }
  
        return response()->json($array);
    }
}
