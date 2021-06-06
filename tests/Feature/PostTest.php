<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PostTest extends TestCase
{

    public function createUser()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Hash::make($this->faker->password(8)),
        ];

        return $user = User::create($userData);
    }

    public function test_show_post_unauthorized()
    {
        $response = $this->postJson('/api/posts/1');

        $response->assertStatus(401);
    }

    public function test_show_post_not_found()
    {
        $user = $this->createUser();

        $token = $user->createToken('ios')->plainTextToken;

        $response = $this->actingAs($user)
            ->postJson('/api/posts/2');

        $response->assertStatus(404);
    }

    public function test_show_post_forbidden()
    {
        $user = $this->createUser();
        $token = $user->createToken('ios')->plainTextToken;

        $response = $this->actingAs($user)->postJson('/api/posts/1');

        $response->assertStatus(403);
    }

    public function test_show_post_success()
    {
        $user = $this->createUser();
        $token = $user->createToken('ios')->plainTextToken;
        $postForm = [
            'title' => $this->faker->title,
            'body' => $this->faker->text,
            'user_id'=> $user->id
        ];

        $response = $this->actingAs($user)->postJson('/api/posts/create', $postForm);
        $response = $this->actingAs($user)->postJson('/api/posts/1');
        $response->assertStatus(200);
    }
    public function test_create_post_success()
    {
        $user = $this->createUser();
        $token = $user->createToken('ios')->plainTextToken;

        $postForm = [
            'title' => $this->faker->title,
            'body' => $this->faker->text,
            'user_id'=> $user->id
        ];

        $response = $this->actingAs($user)->postJson('/api/posts/create', $postForm);
        $response->assertStatus(201);
    }
    public function test_create_post_no_login()
    {

        $postForm = [
            'title' => $this->faker->title,
            'body' => $this->faker->text
        ];

        $response = $this->postJson('/api/posts/create', $postForm);
        $response->assertStatus(401);
    }
    public function test_create_post_no_input()
    {
        $user = $this->createUser();
        $token = $user->createToken('ios')->plainTextToken;

        $postForm = [
            'body' => $this->faker->text
        ];

        $response = $this->actingAs($user)->postJson('/api/posts/create', $postForm);
        $response->assertStatus(422);
    }
    public function test_delete_post_success()
    {
        $user = $this->createUser();
        $token = $user->createToken('ios')->plainTextToken;

        $postForm = [
            'id' => 1,
            'title' => $this->faker->title,
            'body' => $this->faker->text,
            'user_id'=> $user->id
        ];

        $response = $this->actingAs($user)->postJson('/api/posts/create', $postForm);
        $response = $this->actingAs($user)->postJson('/api/posts/delete/1');
        
        $response->assertStatus(200);
    }
    public function test_delete_no_login()
    {

        $response = $this->postJson('/api/posts/delete/1');
        
        $response->assertStatus(401);
    }
    public function test_delete_not_autorise()
    {

        $user = $this->createUser();
        $token = $user->createToken('ios')->plainTextToken;

        $postForm = [
            'id' => 10,
            'title' => $this->faker->title,
            'body' => $this->faker->text,
            'user_id'=> 123456789
        ];

        $response = $this->actingAs($user)->postJson('/api/posts/create', $postForm);
        $response = $this->actingAs($user)->postJson('/api/posts/delete/10');
        
        $response->assertStatus(403);
    }
    public function test_delete_not_found()
    {

        $user = $this->createUser();
        $token = $user->createToken('ios')->plainTextToken;

        $response = $this->actingAs($user)->postJson('/api/posts/delete/123456789123456789');
        
        $response->assertStatus(404);
    }
    public function test_show_posts_success()
    {
        $user = $this->createUser();
        $token = $user->createToken('ios')->plainTextToken;

        $response = $this->actingAs($user)->postJson('/api/posts');
        $response->assertStatus(201);
    }
    public function test_show_posts_no_login()
    {

        $response = $this->postJson('/api/posts');
        $response->assertStatus(401);
    }
    public function test_edit_post_success()
    {
        $user = $this->createUser();
        $token = $user->createToken('ios')->plainTextToken;

        $postForm = [
            'id' => 1,
            'title' => $this->faker->title,
            'body' => $this->faker->text,
            'user_id'=> $user->id
        ];

        $response = $this->actingAs($user)->postJson('/api/posts/create', $postForm);
        $editForm = [
            'title' => $this->faker->title,
            'body' => $this->faker->text,
        ];
        $response = $this->actingAs($user)->postJson('/api/posts/update/1', $editForm);
        
        $response->assertStatus(200);
    }
    public function test_edit_post_no_login()
    {

        $editForm = [
            'title' => $this->faker->title,
            'body' => $this->faker->text,
        ];
        $response = $this->postJson('/api/posts/update/1', $editForm);
        
        $response->assertStatus(401);
    }
    public function test_edit_post_not_access()
    {
        $user = $this->createUser();
        $token = $user->createToken('ios')->plainTextToken;

        $postForm = [
            'id' => 1245,
            'title' => $this->faker->title,
            'body' => $this->faker->text,
            'user_id'=> 1456
        ];

        $response = $this->actingAs($user)->postJson('/api/posts/create', $postForm);
        $editForm = [
            'title' => $this->faker->title,
            'body' => $this->faker->text,
        ];
        $response = $this->actingAs($user)->postJson('/api/posts/update/1245', $editForm);
        
        $response->assertStatus(403);
    }
    public function test_edit_post_not_found()
    {
        $user = $this->createUser();
        $token = $user->createToken('ios')->plainTextToken;

        $editForm = [
            'title' => $this->faker->title,
            'body' => $this->faker->text,
        ];
        $response = $this->actingAs($user)->postJson('/api/posts/update/99', $editForm);
        
        $response->assertStatus(404);
    }



    

}
