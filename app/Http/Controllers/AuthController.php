<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Hash;
class AuthController extends BaseController
{
           
    public function __construct()
    {
        
        $this->middleware('auth:api', ['except' => ['login','register','me']]);
//         $router->post('/auth/login', ['prefix' => 'api', 'uses' =>  'AuthController@login']);
// $router->post('/auth/user-check', ['prefix' => 'api', 'uses' =>  'AuthController@me']);
// $router->post('/auth/register', ['prefix' => 'api', 'uses' =>  'AuthController@register']);
// $router->post('/auth/logout', ['prefix' => 'api', 'uses' =>  'AuthController@logout']);

    }
    public function login()
    {
     /**
     * @OA\Post(
     *     path="/auth/login",
     *     summary="Login",
     *     operationId="login",
     *     description="Login anf get jwt token",
     *     tags={"JWT Test"},
     *     @OA\RequestBody(
     *         description="to login",
     *         required=true,
     *      @OA\JsonContent(
     *           @OA\Property(property="email", type="string", format="text", example="ibnu.muntoha@gmail.com"),
     *           @OA\Property(property="password", type="string", format="text", example="test123"),
     *      ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="response",
     *         @OA\JsonContent(
     *              @OA\Property(property="access_token", type="string", example="token_id"),
     *              @OA\Property(property="token_type", type="string", example="bearer"),
     *              @OA\Property(property="expires_in", type="string", example="3600"),
     *          ),
     *     )
     * )
     */
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized!!!'], 401);
        }

        return $this->respondWithToken($token);
    }
    public function logout()
    {
     /**
     * @OA\Post(
     *     path="/auth/logout",
     *     summary="User Logout",
     *     operationId="logout",
     *     description="Using token to logout",     
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     tags={"JWT Test"},
     *     @OA\Response(
     *         response=200,
     *         description="response",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Successfully logged out"),
     *          ),
     *     )
     * )
     */
        auth()->logout();
        auth()->invalidate();
        return response()->json(['message' => 'Successfully logged out']);
    }
    public function register()
    {
        $hasher = app()->make('hash');
   
        $user = new User;
        $id = $this->generateRandomString();

        $user->_id = $id;
        $user->email = "ibnu@gmail.com";
        $user->password = $hasher->make("1234567");

        
        $user->save();
        if($user){
            $array['success'] = true;
            $array['message'] = 'Success Created';
            $array['_id'] = $id;
        }
        else{
            $array['success'] = false;
            $array['message'] = 'Failed Created';
        }
        return response()->json($array);
    }
    public function me()
    {

             /**
     * @OA\Post(
     *     path="/auth/user-check",
     *     summary="User Check",
     *     operationId="login",
     *     description="Using token to get User data",  
     *       security={
     *           {"bearerAuth": {}}
     *       },
     *     tags={"JWT Test"},
     *     @OA\Response(
     *         response=200,
     *         description="response",
     *         @OA\JsonContent(
     *              @OA\Property(property="success", type="bool", example="true"),
     *              @OA\Property(property="message", type="string", example="check succeded"),
     *              @OA\Property(property="user", type="string", example="data user"),
     *          ),
     *     )
     * )
     */
        //return response()->json(auth()->user());
        if( auth()->user()){
            $array['success'] = true;
            $array['message'] = 'check succeded';
            $array['user'] = auth()->user();
        }else{
            $array['success'] = false;
            $array['message'] = 'check failed';
        }
        return response()->json($array);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    function generateRandomString($length = 24) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}