<?php

use App\Models\User;

it("can create a poll", function () {
    actingAs(User::factory()->create());
});
