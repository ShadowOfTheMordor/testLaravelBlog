<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;


class PostTest extends TestCase
{
    use RefreshDatabase;

    /**
     
     * logged or unlogged
     *
     
     */
    public function test_a_guest_redirects_to_post()
    {
        //проверка редиректа
        $response=$this->get("/");
        $response->assertStatus(302);
    }
    
    public function test_a_guest_can_see_posts()
    {
        //может видеть посты
        $response=$this->get("/post");
        $response->assertOk();
    }
    
    /*
     * проверки логина и регистрации
     */
    public function test_a_user_can_not_see_login_form_when_authenticated()
    {
        $user=User::factory()->create();
        $response=$this->actingAs($user)->get(route("login"))->assertRedirect(route("post.index"));
    }
    
    
    public function test_a_user_can_see_login_form_when_not_authenticated()
    {
        $response=$this->from(route("post.index"))->get(route("register"))->assertOk();
    }
    
    public function test_a_user_can_see_password_request_when_not_authenticated()
    {
        $user=User::factory()->create();
        $response=$this->from(route("login"))->get(route("password.request"))->assertOk();
    }
    
    public function test_a_user_can_login_with_correct_credentials()
    {
        
        $user=User::factory()->create([
                "name" => "existing user",
                "email" => "some@mail",
                "password" => Hash::make($password="my new password")
                
        ]);
        //заодно проверка может ли он из show попасть в регистрацию и вернуться обратно
        $this->session(["backUrl" => route("post.show",["id" => 1])]);
        $response=$this->post(route("login"),[
            "email" => $user->email,
            "password" => $password
        ]);
        
        $post=Post::factory()->create(["post_id" => 1, "author_id" => $user->id]);
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route("post.show",["id" => 1]));
        $response->assertSessionHas("success");
    }
    
    public function test_a_user_can_not_login_with_incorrect_password()
    {
        $user=User::factory()->create([
                "password" => Hash::make($password="my new password")
        ]);
        $response=$this->from(route("login"))->post(route("login"),[
            "email" => $user->email,
            "password" => "password"
        ]);
       $response->assertRedirect(route("login"));
       $response->assertSessionHasErrors("email");
       $this->assertTrue(session()->hasOldInput("email"));
       $this->assertFalse(session()->hasOldInput("password"));
       $this->assertGuest();
    }
    
    public function test_a_user_cannot_login_with_non_existant_email()
    {
        $response=$this->from(route("login"))->post(route("login"),[
            "email" => "some@mail",
            "password" => "password"
        ]);
       $response->assertRedirect(route("login"));
       $response->assertSessionHasErrors("email");
       $this->assertTrue(session()->hasOldInput("email"));
       $this->assertFalse(session()->hasOldInput("password"));
       $this->assertGuest();
    }
    
    public function test_login_remember_me_function()
    {
        $user = User::factory()->create([
            "password" => Hash::make($password = "password"),
        ]);

        $response = $this->post(route("login"), [
            "email" => $user->email,
            "password" => $password,
            "remember" => 'on',
        ]);

        $user = $user->fresh();

        $response->assertRedirect("/");
        $response->assertCookie(Auth::guard()->getRecallerName(), vsprintf('%s|%s|%s', [
            $user->id,
            $user->getRememberToken(),
            $user->password,
        ]));
        $this->assertAuthenticatedAs($user);
    }
    
    public function test_logged_in_can_logout()
    {
        $user=User::factory()->create();
        $this->actingAs($user);
        $response=$this->post(route("logout"));
        $response->assertRedirect(route("post.index"));
        $this->assertGuest();
        $response->assertSessionHas("success");
    }
    
    public function test_not_logged_in_cannot_logout()
    {
        $response=$this->post(route("logout"));
        $response->assertRedirect(route("post.index"));
        $this->assertGuest();
    }
    
    /*
     * register
     */
    public function test_a_user_can_not_see_register_form_when_authenticated()
    {
        $user=User::factory()->create();
        $response=$this->actingAs($user)->get(route("register"))->assertRedirect(route("post.index"));
    }

    public function test_a_user_can_see_register_form_when_not_authenticated()
    {
        $response=$this->get(route("register"))->assertOk();
    }
    
    private function register_data()
    {
        return [
            "name" => "Some Name",
            "email" => "some@mail",
            "password" => "password",
            "password_confirmation" => "password"
        ];
    }
    
    public function test_a_user_can_register()
    {
        //использует внешний метод с данными, т.к. повторяется неск. раз ниже
        Event::fake();
//        $this->fol
        $response=$this->from(route("register"))->post(route("register"),$this->register_data());
        $response->assertRedirect(route("post.index"));
        $response->assertSessionHas("success");
        $users=User::all();
        $this->assertCount(1,$users);
        $user=$users->first();
        $this->assertAuthenticatedAs($user);
        $this->assertEquals("Some Name",$user->name);
        $this->assertEquals("some@mail", $user->email);
        $this->assertTrue(Hash::check("password", $user->password));
/*        Event::assertDispatched(Registered::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });*/
        $response->assertSessionHas("success");
    }
    
    public function test_a_user_cannot_register_without_name()
    {
        //использует внешний метод с данными, заменяя имя пустой строкой
        $response=$this->from(route("register"))->post(route("register"),array_merge($this->register_data(),["name" => ""]));
        $users=User::all();
        $response->assertRedirect(route("register"));
        $response->assertSessionHasErrors("name");
        $this->assertCount(0,$users);
//        $this->assertTrue(session()->hasOldInput("name"));
        $this->assertTrue(session()->hasOldInput("email"));
        $this->assertFalse(session()->hasOldInput("password"));
        $this->assertGuest();
    }
            
    public function test_a_user_cannot_register_without_email()
    {
        $response=$this->from(route("register"))->post(route("register"),array_merge($this->register_data(),["email" => ""]));
        $users=User::all();
        $response->assertRedirect(route("register"));
        
        $response->assertSessionHasErrors("email");
        $this->assertCount(0,$users);
        $this->assertTrue(session()->hasOldInput("name"));
//        $this->assertTrue(session()->hasOldInput("email"));
        $this->assertFalse(session()->hasOldInput("password"));
        $this->assertGuest();
    }

    public function test_a_user_cannot_register_without_password()
    {
        $response=$this->from(route("register"))->post(route("register"),array_merge($this->register_data(),["password" => ""]));
        $users=User::all();
        $response->assertRedirect(route("register"));
        $response->assertSessionHasErrors("password");
        $this->assertCount(0,$users);
        $this->assertTrue(session()->hasOldInput("name"));
        $this->assertTrue(session()->hasOldInput("email"));
        $this->assertFalse(session()->hasOldInput("password"));
        $this->assertGuest();
    }

    public function test_a_user_cannot_register_without_password_confirmation()
    {
        $response=$this->from(route("register"))->post(route("register"),array_merge($this->register_data(),[
            "password_confirmation" => ""
        ]));
        $users=User::all();
        $response->assertRedirect(route("register"));
        $response->assertSessionHasErrors("password");
        $this->assertCount(0,$users);
        $this->assertTrue(session()->hasOldInput("name"));
        $this->assertTrue(session()->hasOldInput("email"));
        $this->assertFalse(session()->hasOldInput("password"));
        $this->assertGuest();
    }
    
    public function test_a_user_cannot_register_when_passwords_not_match()
    {
        $response=$this->from(route("register"))->post(route("register"), array_merge($this->register_data(),[
            "password_confirmation" => "wrong password"
        ]));
        $users=User::all();
        $response->assertRedirect(route("register"));
        $response->assertSessionHasErrors("password");
        $this->assertCount(0,$users);
        $this->assertTrue(session()->hasOldInput("name"));
        $this->assertTrue(session()->hasOldInput("email"));
        $this->assertFalse(session()->hasOldInput("password"));
        $this->assertGuest();
    }
    
    /*
     * проверки переходов
     */
    
    /*
     * незалогиненых пользователей перебросит на страницу входа
     */
    public function test_a_user_cannot_see_create_when_not_authenticated()
    {
        $response=$this->get(route("post.create"))->assertRedirect(route("login"));
        
    }
    
    public function test_a_user_can_see_create_when_authenticated()
    {
        $user=User::factory()->create();
        $this->actingAs($user);
        $response=$this->get(route("post.create"))->assertOk();
    }
    
    public function test_a_user_can_create_a_post_when_authenticated()
    {
        $user=User::factory()->create();
        $post=Post::factory()->make([
            "author_id" => $user->id
        ]);
//        dd($post->toArray());
        $response=$this->actingAs($user)->from(route("post.create"))->post(route("post.store"),$post->toArray());
        $posts=Post::all();
//        $this->assertEquals(1,$posts->count());
//        dd(Post::all()->count());
        $this->assertEquals(1,Post::all()->count());
        $response->assertRedirect(route("post.index"));
        $response->assertSessionHas("success");
    }
    
    public function test_a_user_can_see_show_post_at_any_existing_post()
    {
        $user=User::factory()->create();
        $post=Post::factory()->create([
            "post_id" => 1,
            "author_id" => $user->id
        ]);
        $response=$this->get(route("post.show",["id" => $post->post_id]));
        $response->assertOk();
    }
    
    public function test_a_user_cannot_see_show_post_when_post_nonexistant()
    {
        $user=User::factory()->create();
        $post=Post::factory()->create([
            "post_id" => 1,
            "author_id" => $user->id
        ]);
        $response=$this->get(route("post.show",["id" => 2]));
        $response->assertRedirect(route("post.index"));
        $response->assertSessionHasErrors();
    }
    
    public function test_a_user_can_not_see_edit_post_when_not_authenticated()
    {
        $user=User::factory()->create();
        $post=Post::factory()->create([
            "author_id" => $user->id
        ]);
        $response=$this->get(route("post.edit",["id" => $post->post_id]));
        $response->assertRedirect(route("login"));
    }
    
    public function test_a_user_cannot_see_edit_post_if_post_doesnt_exists_when_authenticated()
    {
        $user=User::factory()->create();
        $post=Post::factory()->create([
            "post_id" => 1,
            "author_id" => $user->id
        ]);
        $response=$this->actingAs($user)->get(route("post.edit",["id" => 2]));
        $response->assertRedirect(route("post.index"));
        $response->assertSessionHasErrors();
    }
    
    public function test_a_user_can_see_edit_post_when_authenticated()
    {
        $user=User::factory()->create();
        $post=Post::factory()->create([
            "author_id" => $user->id
        ]);
        $response=$this->actingAs($user)->get(route("post.edit",["id" => $post->post_id]))->assertOk();
    }
    
    public function test_a_user_can_not_see_edit_post_when_authenticated_but_not_author_of_post()
    {
        $user=User::factory()->create(["id" => 1]);
        $post=Post::factory()->create([
            "author_id" => $user->id
        ]);
        $user2=User::factory()->create(["id" => 2]);
        $response=$this->actingAs($user2)->get(route("post.edit",["id" => $post->post_id]))->assertRedirect(route("post.index"));
        $response->assertSessionHasErrors();
    }
    
    public function test_a_user_cannot_update_post_when_not_authenticated()
    {
        $user=User::factory()->create();
        $post=Post::factory()->create([
            "author_id" => $user->id
        ]);
//        $response=$this->from(route("post.show",["id" => $post->post_id]))->post(route("post.update"),$post->toArray());
        $response=$this->put(route("post.update",["id" => $post->post_id]),$post->toArray());
        
        $response->assertRedirect(route("login"));
    }
    
    public function test_a_user_cannot_update_post_when_authenticated_but_not_author_of_post()
    {
        $user=User::factory()->create([
            "id" => 1
        ]);
        $post=Post::factory()->create([
            "author_id" => $user->id
        ]);
        $user2=User::factory()->create([
            "id" => 2
        ]);
        $response=$this->actingAs($user2)->put(route("post.update",["id" => $post->post_id],$post->toArray()));
        $response->assertRedirect("/");
        $response->assertSessionHasErrors();
    }
    
    public function test_a_user_can_update_his_own_post_when_authenticated()
    {
        $user=User::factory()->create();
        $post=Post::factory()->create([
            "author_id" => $user->id
        ]);
        $post->title="A New Title";
        $response=$this->actingAs($user)->put(route("post.update",["id" => $post->post_id]),$post->toArray());
//        $response->assertOk();
        $response->assertRedirect(route("post.show",["id" => $post->post_id]));
        $this->assertDatabaseHas("posts", ["post_id" => $post->post_id, "title" => "A New Title"]);
        $response->assertSessionHas("success");
    }

    public function test_a_user_cannot_update_post_if_post_doesnt_exists_when_authenticated()
    {
        $user=User::factory()->create();
        $post=Post::factory()->create([
            "post_id" => 1,
            "author_id" => $user->id
        ]);
        $response=$this->actingAs($user)->put(route("post.update",["id" => 2]),array_merge($post->toArray(),[
            "post_id" => 2
        ]));
        $response->assertRedirect(route("post.index"));
        $response->assertSessionHasErrors();
    }
    
    public function test_a_user_cannot_delete_post_when_not_authenticated()
    {
        $user=User::factory()->create();
        $post=Post::factory()->create([
            "author_id" => $user->id
        ]);
        $response=$this->delete(route("post.destroy",["id" => $post->post_id]));
        $response->assertRedirect(route("login"));
    }
    
    public function test_a_user_cannot_delete_not_his_own_post_when_authenticated()
    {
        $user=User::factory()->create([
            "id" => 1
        ]);
        $post=Post::factory()->create([
            "author_id" => $user->id,
            "post_id" => 1
        ]);
        $user2=User::factory()->create([
            "id" => 2
        ]);
        $post2=Post::factory()->create([
            "author_id" => $user2->id,
            "post_id" => 2
        ]);
        $response=$this->actingAs($user2)->delete(route("post.destroy",["id" => $post->post_id]));
        $response->assertRedirect(route("post.index"));
        $response->assertSessionHasErrors();
    }
    
    public function test_a_user_can_delete_his_own_post_when_authenticated()
    {
        $user=User::factory()->create();
        $post=Post::factory()->create([
            "author_id" => $user->id
        ]);
        $response=$this->actingAs($user)->delete(route("post.destroy",["id" => $post->post_id]));
//        $response->assertOk();
        $response->assertRedirect(route("post.index"));
        $response->assertSessionHas("success");
    }
    
    
    /*
     * валидация
     */
    private function post_data(int $user_id)
    {
        $faker=\Faker\Factory::create();
        $created_at=$faker->dateTimeBetween("-30 days", "-1 days");
        return [
            "title" => "A Title",
            "short_title" => "A Title",
            "author_id" => $user_id,
            "description" => "a full description",
            "created_at" => $created_at,
            "updated_at" => $created_at

        ];
    }
    
    public function test_a_user_cannot_create_post_with_too_short_title()
    {
        $user=User::factory()->create();
        $this->actingAs($user);
        $response=$this->from(route("post.create"))->post(route("post.store"),array_merge($this->post_data($user->id),[
            "title" => "ti"
        ]));
        $posts=Post::all();
        $response->assertRedirect(route("post.create"));
        $response->assertSessionHasErrors("title");
        $this->assertCount(0,$posts);
        $this->assertTrue(session()->hasOldInput("title"));
        $this->assertTrue(session()->hasOldInput("description"));
        $response->assertSessionHasErrors();
    }

    public function test_a_user_cannot_create_post_with_too_long_title()
    {
        $user=User::factory()->create();
        $this->actingAs($user);
        $faker=\Faker\Factory::create();
        $response=$this->from(route("post.create"))->post(route("post.store"),array_merge($this->post_data($user->id),[
            "title" => $faker->realText(50)
        ]));
        $posts=Post::all();
        $response->assertRedirect(route("post.create"));
        $response->assertSessionHasErrors("title");
        $this->assertCount(0,$posts);
        $this->assertTrue(session()->hasOldInput("title"));
        $this->assertTrue(session()->hasOldInput("description"));
        $response->assertSessionHasErrors();
    }
    
    public function test_a_user_cannot_create_post_with_too_short_description()
    {
        $user=User::factory()->create();
        $this->actingAs($user);
        $response=$this->from(route("post.create"))->post(route("post.store"),array_merge($this->post_data($user->id),[
            "description" => "descr"
        ]));
        $posts=Post::all();
        $response->assertRedirect(route("post.create"));
        $response->assertSessionHasErrors("description");
        $this->assertCount(0,$posts);
        $this->assertTrue(session()->hasOldInput("title"));
        $this->assertTrue(session()->hasOldInput("description"));
        $response->assertSessionHasErrors();
    }

    public function test_a_user_cannot_create_post_with_too_long_description()
    {
        $user=User::factory()->create();
        $this->actingAs($user);
        $faker=\Faker\Factory::create();
        $response=$this->from(route("post.create"))->post(route("post.store"),array_merge($this->post_data($user->id),[
            "description" => $faker->realText(600)
        ]));
        $posts=Post::all();
        $response->assertRedirect(route("post.create"));
        $response->assertSessionHasErrors("description");
        $this->assertCount(0,$posts);
        $this->assertTrue(session()->hasOldInput("title"));
        $this->assertTrue(session()->hasOldInput("description"));
        $response->assertSessionHasErrors();
    }
    
    public function test_a_user_cannot_create_post_with_wrong_type_of_file()
    {
        //Storage::fake("public");
        $user=User::factory()->create();
        $file=UploadedFile::fake()->create("file.pdf", 4000);
        $response=$this->actingAs($user)->post(route("post.store"),array_merge($this->post_data($user->id),[
            "img" => $file
        ]));
        $this->assertEquals(0,Post::all()->count());
        $response->assertSessionHasErrors();
    }
    
    public function test_a_user_cannot_create_post_with_too_large_image()
    {
        $user=User::factory()->create();
        $file=UploadedFile::fake()->create("file.img", 6000);
        $response=$this->actingAs($user)->post(route("post.store"),array_merge($this->post_data($user->id),[
            "img" => $file
        ]));
        $this->assertEquals(0,Post::all()->count());
        $response->assertSessionHasErrors();
    }
    
/*    public function test_a_user_can_create_post_with_file()
    {
        
        Storage::fake("public");
        $user=User::factory()->create();
//        $file=UploadedFile::fake()->create("file.img", 4000);
//        $file=UploadedFile::fake()->create("file.img", 4000);
//        $file=UploadedFile::fake()->image("file.img");
        $file=new \Symfony\Component\HttpFoundation\File\UploadedFile(url("file.img"), "file.img", "image/jpeg", null, true);
        //$file=new \Symfony\Component\HttpFoundation\File\UploadedFil
//                (__DIR__."/file.img",)
        $response=$this->actingAs($user)->post(route("post.store"),array_merge($this->post_data($user->id),[
            "img" => $file
        ]));
        $response->assertSessionHas("success");
        $response->assertRedirect(route("post.index"));
    }*/
//        $this->assertEquals(1,Post::all()->count());
//        dd($file->hashName());
//        dd(__DIR__);
//        Storage::disk("public")->assertExists("file.img");
//        $response->assertSessionHasErrors();
/*        
        Storage::fake();
        $user=User::factory()->create();

        $response=$this->actingAs($user)->post(route("post.store"),array_merge($this->post_data($user->id), ["img" => UploadedFile::fake()->image("test_imh.img")]));
//        $response=$this->actingAs($user)->post(route("post.store"),$this->post_data($user->id));
//        $response->assertStatus(200);
        $response->dump();
        $response->assertSessionHasErrors();
//        $errors=session('errors');
        $errors=request()->session()->get("errors");
        foreach ($errors as $error)
        {
            echo "inside";
            var_dump($error);
        }
//    $this->assertSessionHasErrors();
//    $this->assertEquals($errors->get('name')[0],"Your error message for validation");
//        $this->assertEquals(1,Post::all()->count());
        
    }
    */
    
    /*
     *  админский функционал
     */
    
    public function test_admin_can_see_edit_form_for_any_user()
    {
        $user=User::factory()->create();
        $post=Post::factory()->create([
            "post_id" => 1,
            "author_id" => $user->id
        ]);
        $admin=User::factory()->create([
            "is_admin" => 1
        ]);
        $response=$this->actingAs($admin)->from(route("post.show",["id" => $post->post_id]))->get(route("post.edit",["id" => $post->post_id]));
        $response->assertOk();
        
        
    }
    
    public function test_admin_can_update_post_from_any_user()
    {
        $user=User::factory()->create();
        $post=Post::factory()->create([
            "post_id" => 1,
            "author_id" => $user->id
        ]);
        $admin=User::factory()->create([
            "is_admin" => 1
        ]);
        $post->description="a super new description";
        $response=$this->actingAs($admin)->put(route("post.update",["id" => $post->post_id]),$post->toArray());
        $response->assertRedirect(route("post.show",["id" => $post->post_id]));
        $response->assertSessionHas("success");
    }
    
    public function test_admin_can_delete_any_post()
    {
        $user=User::factory()->create();
        $post=Post::factory()->create([
            "post_id" => 1,
            "author_id" => $user->id
        ]);
        $admin=User::factory()->create([
            "is_admin" => 1
        ]);
        $response=$this->actingAs($admin)->from(route("post.show",["id" => $post->post_id]))->delete(route("post.destroy",["id" => $post->post_id]));
        $response->assertRedirect(route("post.index"));
        $response->assertSessionHas("success");
}
    
    /*
     * проверка локализации
     */
    public function test_localization_middleware()
    {
        //посредник
        $request=new Request();
        
        $middleware=new \App\Http\Middleware\Localization();
        $middleware->handle($request, function($req){
            $this->assertEquals(App::getLocale(), session()->get("app.locale"));
        });
    }

    public function test_localization_controller_changes_session_data()
    {
        //контроллер - меняет данные в сессии(юнит тест не подходит потому)
        $localization=new \App\Http\Controllers\LocalizationController();
        $this->session(["app.locale" => "ru"]);
        $localization->index("us");
        $this->assertEquals("us",\Illuminate\Support\Facades\Session::get("app.locale"));
    }
}
