<?php

namespace R4nkt\Manageable\Test;

use R4nkt\Manageable\Test\Models\Order;
use R4nkt\Manageable\Test\Models\User;

class ManageableTest extends TestCase
{
    /** @test */
    public function it_tracks_who_creates_an_order()
    {
        $user = $this->createUser(['name' => 'Harry']);

        $this->actingAs($user);

        $order = Order::create([
            'title' => 'some title',
        ]);

        tap($order->fresh(), function ($order) use ($user) {
            $this->assertTrue($order->creator->is($user));
            $this->assertTrue($order->editor->is($user));
            $this->assertTrue($order->hasCreator());
            $this->assertTrue($order->hasEditor());
        });
    }

    /** @test */
    public function it_tracks_who_updates_an_order()
    {
        $userA = $this->createUser(['name' => 'Harry']);

        $this->actingAs($userA);

        $order = Order::create([
            'title' => 'some title',
        ]);

        $userB = $this->createUser(['name' => 'Lloyd']);

        $this->actingAs($userB);

        $order->title = 'some new title';
        $order->save();

        tap($order->fresh(), function ($order) use ($userA, $userB) {
            $this->assertTrue($order->creator->is($userA));
            $this->assertTrue($order->editor->is($userB));
            $this->assertTrue($order->hasCreator());
            $this->assertTrue($order->hasEditor());
        });
    }

    /** @test */
    public function it_updates_the_editor()
    {
        $userA = $this->createUser(['name' => 'Harry']);

        $this->actingAs($userA);

        $order = Order::create([
            'title' => 'some title',
        ]);

        // Update with editor
        $userB = $this->createUser(['name' => 'Lloyd']);
        $this->actingAs($userB);

        $order->title = 'some new title';
        $order->save();

        tap($order->fresh(), function ($order) use ($userA, $userB) {
            $this->assertTrue($order->creator->is($userA));
            $this->assertTrue($order->editor->is($userB));
        });

        // Update again with different editor
        $userC = $this->createUser(['name' => 'Sea Bass']);
        $this->actingAs($userC);

        $order->title = 'another new title';
        $order->save();

        tap($order->fresh(), function ($order) use ($userA, $userC) {
            $this->assertTrue($order->creator->is($userA));
            $this->assertTrue($order->editor->is($userC));
            $this->assertTrue($order->hasCreator());
            $this->assertTrue($order->hasEditor());
        });
    }

    /** @test */
    public function it_does_not_track_creator_or_editor_on_create_if_a_user_is_not_authenticated()
    {
        $order = Order::create([
            'title' => 'some title',
        ]);

        $order->title = 'some new title';
        $order->save();

        tap($order->fresh(), function ($order) {
            $this->assertNull($order->created_by);
            $this->assertNull($order->updated_by);
            $this->assertFalse($order->hasCreator());
            $this->assertFalse($order->hasEditor());
        });
    }

    /** @test */
    public function it_tracks_null_editor_on_update_if_a_user_is_not_authenticated()
    {
        $userA = $this->createUser(['name' => 'Harry']);

        $order = Order::create([
            'title' => 'some title',
        ]);

        /*
         * Since we can't go from actingAs($user) to actingAs(null), we will
         * fake making the order manageable on creation.
         */
        $order->created_by = $userA->id;
        $order->updated_by = $userA->id;
        $order->saveQuietly(); // or else it will null out updated_by...

        tap($order->fresh(), function ($order) use ($userA) {
            $this->assertTrue($order->creator->is($userA));
            $this->assertTrue($order->editor->is($userA));
        });

        $order->title = 'some new title';
        $order->save();

        tap($order->fresh(), function ($order) use ($userA) {
            $this->assertTrue($order->creator->is($userA));
            $this->assertNull($order->updated_by);
            $this->assertTrue($order->hasCreator());
            $this->assertFalse($order->hasEditor());
        });
    }
}
