<?php

it('can render the register page', function () {
    $response = $this->get('/admin/register');

    $response->assertStatus(200);
});