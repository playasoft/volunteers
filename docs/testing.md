# Writing Test Cases and Factories

Create a `.env.testing` file for running tests in a separate
test database. After creating this file, you may also need to run
the following command before your settings will take effect:

```sh
php artisan config:cache --env=testing
```

**NOTE:** Use `npm run test` to run tests.

Writing tests are a powerful way to minimize the amount of time you spend developing
solutions by having checks to see if functionality still succeeds when adding or
changing parts of the project while avoiding nonessential additions.

* `insert()` is good for creating a customized model to exact specification.
* `factory()` is good for writing test cases and writing seeders.

## Test Cases

Test cases should be written with readability in the forefront of your mind.
There are a couple tools to help with that. Below is an illustrated example
to help you on your way.

For this case we'll test something trivial like...

If **slots are put on the same schedule**, then **they should be on
the same schedule**.

We can split that into a _Condition_ and an _Assertion_

* The Condition: Slots are put on the same schedule
* The Assertion: The slots are on the same schedule

We'll start by creating the test using `php artisan make:test SlotTest --unit`.
`--unit` is just a better way to say we're testing internal functionality.

Then in our `tests\Unit\SlotTest.php`, you'll see something like this...

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlotTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
}
```

From there we can start making some changes. Like adding the `RefreshDatabase`
trait that makes sure before the test suite is run, it clears the database first.
Clearing your database before running tests is always a good practice.

_Note_: `RefreshDatabase` does not reset the database after each test, but
after each suite/class.

We can also remove the "test" from `testExample()` and place the `@test`
directive in the comment block above. They both tell PHPUnit that the function is
a test. This may seem like a pain, but declaring smaller helper functions would
throw a lot of warnings if we didn't make this disctinction. Its pretty handy.
It also gives us the freedom to name the function whatever we like. So lets go
ahead and rename `testExample()` to something more descriptive of what
we're trying to achieve. We'll call it...

`slots_created_on_the_same_schedule_are_on_the_same_schedule()`

...it's a little long, but it tells you exactly what's being tested. You'll also
notice we use `snake_case` instead of `camelCase` here. This is
just because we're, writing a sentence and it's much more readable that way
when function names get lengthy.

Now we should have something like this...

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlotTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function slots_created_on_the_same_schedule_are_on_the_same_schedule()
    {
        $this->assertTrue(true);
    }
}
```

Great! We have the skeleton, now we need to fill it with a meaningful test.
Let's start by making a few slots...

```php
$slots = factory(Slot::class, 2)->create();
```

Once we have that for setup, we can then test if they share the same schedule...

```php
$first_slots_schedule = $slot[0]->schedule;
$second_slots_schedule = $slot[1]->schedule;
$this->assertEquals($first_slots_schedule->id, $second_slots_schedule->id);
```

...and this should pass, **but it doesn't pass**.

The reason why is that when you use `factory()`, it creates the
given model but in it's own isolated strand of randomly generated models to
support its existance. So how do we connect the two? We easily change it to...

```php
$schedule = factory(Schedule::class)->create();
$slots = factory(Slot::class, 2)->create([
  'schedule_id' => $schedule->id;
]);
```

...this still randomly fills both `Slot` and `Schedule` instances, but
this time you've overridden the slots randomly generated schedules with a common
one. This also prevents the random schedules from being generated in the first place.
So now we run it again and **success**!

_Note_: Using factory without `FACTORY_WARNINGS` set to false will spawn
warnings to make you aware of randomly generated models used to fill any
dependencies the factory model may have.

The final file should look something like this...

```php
<?php

namespace Tests\Feature;

use App\Models\Schedule;
use App\Models\Slot;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlotTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function slots_created_on_the_same_schedule_are_on_the_same_schedule()
    {
      // Given
      $schedule = factory(Schedule::class)->create();

      // When
      $slots = factory(Slot::class, 2)->create([
        'schedule_id' => $schedule->id;
      ]);

      $first_slots_schedule = $slot[0]->schedule;
      $second_slots_schedule = $slot[1]->schedule;

      // Then
      $this->assertEquals($first_slots_schedule->id, $second_slots_schedule->id);
    }
}
```

## Factories

Writing factories is a great way to generate a lot of test data. When writing
code that requires no throwaway data and for things to be empty, it's still
correct to use `insert()` or some variant of it.

You would write factories similarly to how
[laravel explains](https://laravel.com/docs/5.8/database-testing),
but you would make sure to add factories that fill out their own dependencies. This allows foreign key dependencies, but isn't great at letting the user know what's happening
in to the behavior of their models which can lead to a lot of assumptions. Though
correct, it gives off a "magic" vibe that can leave a lot of room for assumptions.

To combat this, it's important you add warnings when they don't fill out the
dependencies themselves. That way you save them a lot of headache trying to
figure out why their two Slots aren't apart of the same Schedule, and probably
give them enough time to have a good lunch to rethink their assumptions of your
code. I know I've definitely fallen into that trap before.

```php
<?php

$factory->define(Department::class, function (Faker $faker, array $data)
{
    if(env('APP_DEBUG') && !isset($data['event_id']))
    {
        Log::warning("Using Factory[Department] without setting event_id");
    }

    return
    [
        'name' => $faker->company,
        'description' => $faker->bs,
        'event_id' => function ()
        {
            return factory(Event::class)->create()->id;
        },
    ];
});
```