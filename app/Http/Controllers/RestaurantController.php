<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class RestaurantController extends BaseController
{
    //

    public function index($id)
    {
    /**
     * @OA\Get(
     *     path="/restaurant/{id}",
     *     operationId="/retaurant/id",
     *     tags={"Restaurant Using MongoDB"},
     *      summary="Get Restaurant By Id",
     *      description="Get Data Restaurant By spesicif document id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The id parameter in path is document id in mongodb ex: SURvO6l5ILojZ9i3QeYfn8k0",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns restaurant data",
     *         @OA\JsonContent(
     *                   @OA\Property(property="success", type="bool", example="true"),
     *                   @OA\Property(property="message", type="string", example="Success Get Data"),
     *                   @OA\Property(property="data", type="object", example="some restaurant  data"),
     *              )
     *          )
     *     )
     * )
     */
        $restaurants = Restaurant::find($id);
        
        if ($restaurants) {
            $array['success'] = true;
            $array['message'] = "success get data";
            $array['data'] = $restaurants;
      
            return response()->json($array);
        }else{
            $array['success'] = false;
            $array['message'] = 'Cannot find restaurant!';
            $array['data'] = $restaurants;
      
            return response()->json($array);
        }
       
    }
    public function store(Request $request)
    {
      /**
     * @OA\Post(
     *     path="/restaurant/",
     *     summary="Create Restaurant By Id",
     *     operationId="Add Restaurant",
     *     description="Creates a new Restaurant data",
     *     tags={"Restaurant Using MongoDB"},
     *     @OA\RequestBody(
     *         description="to add restaurant data",
     *         required=true,
     *      @OA\JsonContent(
     *          @OA\Property(property="borough", type="email", format="text", example="Jakarta"),
     *           @OA\Property(property="cuisine", type="string", format="text", example="Betawi"),
     *           @OA\Property(property="restaurant_id", type="string", format="text", example="14025"),
     *           @OA\Property(property="name", type="string", format="text", example="Solaria"),
     *           @OA\Property(property="address", type="object",
     *               @OA\Property(property="building", type="string", format="text", example="230"),
     *               @OA\Property(property="street", type="string", format="text", example="Jl.Kemerdekaan"),
     *               @OA\Property(property="zipcode", type="string", format="text", example="14502"),
     *          ),
     *      ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="pet response",
     *         @OA\JsonContent(
     *              @OA\Property(property="success", type="bool", example="true"),
     *              @OA\Property(property="message", type="string", example="Success Created"),
     *              @OA\Property(property="_id", type="string", example="azgZaZT7bCdInSAymsHgyVy0"),
     *          ),
     *     )
     * )
     */

        $resto = new Restaurant;
        $id = $this->generateRandomString();

        $resto->_id = $id;
        //$resto->address = $request->address;
        $resto->borough = $request->borough;
        $resto->cuisine = $request->cuisine;
        $resto->name = $request->name;
        $resto->restaurant_id = $request->restaurant_id;
        $resto->address = $request->address;

        
        $resto->save();
        if($resto){
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
    public function update(Request $request, $id)
    {
        /**
     * @OA\Put(
     *     path="/restaurant/{id}",
     *     operationId="Update Restaurant",
     *      summary="Update Restaurant By Id",
     * 
     *     description="Update Restaurant data",
     *     tags={"Restaurant Using MongoDB"},
     *     @OA\Parameter(
     *          name="id",
     *          description="Document id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         description="to update restaurant data",
     *         required=true,
     *      @OA\JsonContent(
     *          @OA\Property(property="borough", type="email", format="text", example="Jakarta"),
     *           @OA\Property(property="cuisine", type="string", format="text", example="Betawi"),
     *           @OA\Property(property="restaurant_id", type="string", format="text", example="14025"),
     *           @OA\Property(property="name", type="string", format="text", example="Solaria"),
     *           @OA\Property(property="address", type="object",
     *               @OA\Property(property="building", type="string", format="text", example="230"),
     *               @OA\Property(property="street", type="string", format="text", example="Jl.Kemerdekaan"),
     *               @OA\Property(property="zipcode", type="string", format="text", example="14502"),
     *          ),
     *      ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="pet response",
     *         @OA\JsonContent(
     *              @OA\Property(property="success", type="bool", example="true"),
     *              @OA\Property(property="message", type="string", example="Success Updated"),
     *          ),
     *     )
     * )
     */
        $resto = Restaurant::find($id);
        if ($resto) {
            $resto->borough = $request->borough;
            $resto->cuisine = $request->cuisine;
            $resto->name = $request->name;
            $resto->restaurant_id = $request->restaurant_id;
            $resto->address = $request->address;
            $resto->save();

            if($resto){
                $array['success'] = true;
                $array['message'] = 'Success Updated';
            }
            else{
                $array['success'] = false;
                $array['message'] = 'Failed Updated';
            }
            return response()->json($array);
        }
    }
    
    public function delete($id)
    {
        /**
     * @OA\DELETE(
     *     path="/restaurant/{id}",
     *     operationId="/retaurant/id",
     *     tags={"Restaurant Using MongoDB"},
     *      summary="Delete Restaurant By Id",
     *      description="Delete Data Restaurant By spesicif document id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The id parameter in path is document id in mongodb ex: SURvO6l5ILojZ9i3QeYfn8k0",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns restaurant data",
     *         @OA\JsonContent(
     *                   @OA\Property(property="success", type="bool", example="true"),
     *                   @OA\Property(property="message", type="string", example="Success Deleted"),
     *              )
     *          )
     *     )
     * )
     */
        $resto = Restaurant::find($id);
        if($resto){
            $resto->delete();
            $array['success'] = true;
            $array['message'] = 'Success Deleted';
        }
        else{
            $array['success'] = false;
            $array['message'] = 'Failed Deleted';
        }
        return response()->json($array);
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
