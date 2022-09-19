<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    //
           /**
     * @OA\Info(
     *   title="Sera Test API",
     *   version="1.0",
     *   @OA\Contact(
     *     email="ibnu.muntoha@gmail.com",
     *     name="Ibnu Muntoha"
     *   )
     * )
     */
     /**
  * @OA\SecurityScheme(
     *    securityScheme="bearerAuth",
     *    in="header",
     *    name="bearerAuth",
     *    type="http",
     *    scheme="bearer",
     * 
     * )
 */
}
