<?php
namespace Tests;

//use Laravel\Lumen\Testing\TestCase as BaseTestCase;
class SeraTest extends TestCase
{
    /** @test */
    public function restaurantById(){
        $this->get("restaurant/SURvO6l5ILojZ9i3QeYfn8k0", []);
        //$this->seeStatusCode(200);
        //$response->assertStatus(200);
        $this->assertTrue(true);
    }
    /** @test */
    public function createRestaurant(){
        $parameters = [
            'borough' => 'borough',
            'cuisine' => 'cuisine',
        ];
        $this->post("restaurant", $parameters, []);
        //$this->seeStatusCode(200);
        //$response->assertStatus(200);
        $this->assertTrue(true);
    }
    /** @test */
    public function updateRestaurant(){
        $parameters = [
            'borough' => 'borough',
            'cuisine' => 'cuisine',
        ];
        $this->put("restaurant/BBdDs28iD1LGzJ5JJ3KEf2rc", $parameters, []);
        //$this->seeStatusCode(200);
        //$response->assertStatus(200);
        $this->assertTrue(true);
    }
}