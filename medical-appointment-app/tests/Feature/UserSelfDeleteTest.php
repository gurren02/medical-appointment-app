<?php
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('un usuario no puede eliminarse a sí mismo', function () {
    //1) crear un usuario en bd
    $user = USer::factory()->create(
        [
        'email_verified_at' => now()
        ]
    );

    //2) simular que el usuario esta inicio sesion
    $this->actingAs($user, 'web');

    //3) simular que el usuario intenta borrar
    $response = $this->delete(route('admin.users.destroy', $user));

    //4) esperar que el servidor bloquee la accion
    $response->assertStatus(403);

    //5) verificamos que el usuario siga existiendo
    $this->assertDatabaseHas('users',[
        'id' => $user->id,
    ]);
});
