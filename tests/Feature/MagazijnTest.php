<?php

use App\Models\User;
use App\Models\Product;

test('authenticated user can view magazijn overview', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('magazijn.overzicht'));

    $response->assertStatus(200);
    $response->assertSee('Overzicht Magazijn Jamin');
});

test('authenticated user can view levering info for product with stock', function () {
    $user = User::factory()->create();

    // Mintnopjes (Id: 1)
    $response = $this->actingAs($user)->get(route('magazijn.levering-info', 1));

    $response->assertStatus(200);
    $response->assertSee('Levering Informatie - Mintnopjes');
    $response->assertSee('Venco');
});

test('authenticated user sees no stock message and redirect for winegums', function () {
    $user = User::factory()->create();

    // Winegums (Id: 10)
    $response = $this->actingAs($user)->get(route('magazijn.levering-info', 10));

    $response->assertStatus(200);
    $response->assertSee('Er is van dit product op dit moment geen voorraad aanwezig');
});

test('authenticated user can view allergenen info for product with allergenen', function () {
    $user = User::factory()->create();

    // Zoute Ruitjes (Id: 13)
    $response = $this->actingAs($user)->get(route('magazijn.allergenen-info', 13));

    $response->assertStatus(200);
    $response->assertSee('Overzicht Allergenen - Zoute Ruitjes');
    $response->assertSee('Gluten');
});

test('authenticated user sees no allergenen message for cola flesjes', function () {
    $user = User::factory()->create();

    // Cola Flesjes (Id: 5)
    $response = $this->actingAs($user)->get(route('magazijn.allergenen-info', 5));

    $response->assertStatus(200);
    $response->assertSee('In dit product zitten geen stoffen die een allergische reactie kunnen veroorzaken');
});
