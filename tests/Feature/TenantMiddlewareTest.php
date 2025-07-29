<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TenantMiddlewareTest extends TestCase
{

    use RefreshDatabase;

    //** @test */
    public function user_with_organization_can_access_route()
    {
        $user = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $user->id]);
        $user->organization()->associate($org)->save();

        $response = $this->actingAs($user, 'api')->get('/dashboard');

        $response->assertStatus(200);
    }


    //** @test */
    public function user_without_organization_can_access_route()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->get('/dashboard');

        $response->assertStatus(200);
    }
}
