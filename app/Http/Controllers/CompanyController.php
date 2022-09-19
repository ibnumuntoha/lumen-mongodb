<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use \Kreait\Firebase\Contract\Firestore;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Google\Cloud\Firestore\FirestoreClient;
class CompanyController extends BaseController
{
   
    public function store(Request $request)
    {
       /**
     * @OA\Post(
     *     path="/company/",
     *     summary="Create company By Id",
     *     operationId="Add company",
     *     description="Creates a new company data",
     *     tags={"Company Using Firestore"},
     *     @OA\RequestBody(
     *         description="to add company data",
     *         required=true,
     *      @OA\JsonContent(
     *           @OA\Property(property="city", type="string", format="text", example="Jakarta"),
     *           @OA\Property(property="name", type="string", format="text", example="Jaya Abadi"),
     *           @OA\Property(property="address", type="object",
     *               @OA\Property(property="building", type="string", format="text", example="230"),
     *               @OA\Property(property="street", type="string", format="text", example="Jl.Kemerdekaan"),
     *               @OA\Property(property="zipcode", type="string", format="text", example="14502"),
     *          ),
     *      ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="response",
     *         @OA\JsonContent(
     *              @OA\Property(property="success", type="bool", example="true"),
     *              @OA\Property(property="message", type="string", example="Success Created"),
     *              @OA\Property(property="collection_id", type="string", example="azgZaZT7bCdInSAymsHgyVy0"),
     *          ),
     *     )
     * )
     */
        $firestore = app('firebase.firestore');
        $id = $this->generateRandomString();
        $firestore->database()->collection('Company')->document($id)->set(
            [
                'address' =>[
                        'building' => $request['address']['building'],
                        'street'   => $request['address']['street'],
                        // 'coord'    => [
                        //         $request['address']['coord'][0],$request['address']['coord'][1]
                        //     ], 
                        ] ,
                'name' => $request['name'],
                'city' => $request['city']
            ]
        );
        
        if($firestore){
            $array['success'] = true;
            $array['message'] = 'Success Created';
            $array['collection_id'] = $id;
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
     *     path="/company/{id}",
     *     operationId="Update company",
     *      summary="Update company By Id",
     * 
     *     description="Update company data",
     *     tags={"Company Using Firestore"},
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
     *         description="to update company data",
     *         required=true,
     *      @OA\JsonContent(
     *           @OA\Property(property="city", type="string", format="text", example="Jakarta"),
     *           @OA\Property(property="name", type="string", format="text", example="Jaya Abadi"),
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
        $firestore = app('firebase.firestore');
        $docRef = $firestore->database()->collection('Company')->document($id);
        $snapshot = $docRef->snapshot();

        if ($snapshot->exists()) {
           
            $firestore->database()->collection('Company')->document($id)->set(
                [
                    'address' =>[
                            'building' => $request['address']['building'],
                            'street'   => $request['address']['street'],
                            // 'coord'    => [
                            //         $request['address']['coord'][0],$request['address']['coord'][1]
                            //     ], 
                            ] ,
                    'name' => $request['name'],
                    'city' => $request['city']
                ]
            );
            if($firestore){
                $array['success'] = true;
                $array['message'] = 'Success Updated';
            }
            else{
                $array['success'] = false;
                $array['message'] = 'Failed Updated';
            }
            return response()->json($array);
        }
        else{
            
                $array['success'] = false;
                $array['message'] = 'Failed Updated, Document is not found';
            
            return response()->json($array);
        }
    }
    
    public function delete($id)
    {
            /**
     * @OA\DELETE(
     *     path="/company/{id}",
     *     operationId="/retaurant/id",
     *     tags={"Company Using Firestore"},
     *      summary="Delete company By Id",
     *      description="Delete Data company By spesicif document id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The id parameter in path is document id ex: SURvO6l5ILojZ9i3QeYfn8k0",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns company data",
     *         @OA\JsonContent(
     *                   @OA\Property(property="success", type="bool", example="true"),
     *                   @OA\Property(property="message", type="string", example="Success Deleted"),
     *              )
     *          )
     *     )
     * )
     */
        $firestore = app('firebase.firestore');
        $docRef = $firestore->database()->collection('Company')->document($id);
        $snapshot = $docRef->snapshot();

        if ($snapshot->exists()) {
            $firestore->database()->collection('Company')->document($id)->delete();

            if($firestore){
                $array['success'] = true;
                $array['message'] = 'Success Deleted';
            }
            else{
                $array['success'] = false;
                $array['message'] = 'Failed Deleted';
                
                return response()->json($array);
            }
        }else{
            
                $array['success'] = false;
                $array['message'] = 'Failed Deleted, Document is not found';
            
                return response()->json($array);
            
        }
        
    }
    public function getCompany($id)
    {
    /**
     * @OA\Get(
     *     path="/company/{id}",
     *     operationId="/retaurant/id",
     *     tags={"Company Using Firestore"},
     *      summary="Get Company By Id",
     *      description="Get Data company By spesicif document id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The id parameter in path is document id in Firestore ex: SURvO6l5ILojZ9i3QeYfn8k0",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns Company data",
     *         @OA\JsonContent(
     *                   @OA\Property(property="success", type="bool", example="true"),
     *                   @OA\Property(property="message", type="string", example="Success Get Data"),
     *                   @OA\Property(property="data", type="object", example="some company  data"),
     *              )
     *          )
     *     )
     * )
     */
        $firestore = app('firebase.firestore');
        $docRef = $firestore->database()->collection('Company')->document($id);
        $snapshot = $docRef->snapshot();

        if ($snapshot->exists()) {
            $array['success'] = true;
            $array['message'] = 'Success Get Document';
            $array['data'] = $snapshot->data();
            return response()->json($array);
        }else{
            $array['success'] = false;
            $array['message'] = 'Failed Get Document';
            $array['data'] = [];
            return response()->json($array);
            
        }
        
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
