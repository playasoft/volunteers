# Laravel VolDB
A volunteer database for events written using the Laravel 5.6 framework


## Dependencies

1. A webserver that supports PHP (```nginx``` and ```php-fpm``` recommended)
2. ```mysql```
3. ```node.js``` and ```npm``` installed on your system
4. ```php```
    * ext-mbstring (```apt install php-mbstring```)
    * ext-dom (```apt install php-dom```)
    * ext-mysql (```apt instal php-mysql```)
5. ```composer```, the PHP package manager
6. ```laravel```, the projects PHP framework
6. ```redis```, if you want to use websockets


## Installing

1. Git clone this repo
2. Set **laravel/public/** as your document root
3. Run ```composer install``` within the **laravel** folder
4. Run ```npm install``` within the **laravel** folder (_Note_: currently only works with Node v10)
5. Run ```cp .env.example .env``` and configure ```DB_DATABASE```, ```DB_USERNAME```, and ```DB_PASSWORD```
    * _Note_: You must set this up ahead of time, an [easy guide for Ubuntu users is available](#mysql).
6. Run ```php artisan migrate``` within the **laravel** folder


## <a name="configuration"></a> Setup / Configuration

1. Configure your database and email settings in the **.env** file
2. run `php artisan key:generate` to generate an application key for Laravel
3. Optionally, configure your queue and broadcast drivers. If you want to use websockets, you'll need to use redis for broadcasting
4. In the **laravel/resources/js/** folder, copy **config.example.js** and rename it to **config.js**
5. Optionally, you may configure your websocket server to use a specific hostname, however by default it will use the current domain of the site
6. Run ```npm run build``` within the **laravel** folder.
7. Run ```php artisan db:seed``` within the **laravel** folder to populate the database with user roles


Alright! Now everything is compiled and the site is functional. You can register accounts, create events, and sign up for shifts.
If you want to use websockets for a couple extra features (auto-updating when shifts are taken or departments are changed), follow these steps:


## Extra websockets steps

1. In your **.env** file, make sure ```redis``` is installed and configured as the broadcast driver, and that the variable WEBSOCKETS_ENABLED is set to true
2. Run ```npm install``` within the **node** folder
3. Ensure that the websocket parameters in  ```laravel/resources/js/config.js``` are correct
4. Run ```node websocket-server.js``` within the **node** folder
5. Use a ```screen``` session or a process manager like ```pm2``` to keep the websocket server running indefinitely


## <a name="mysql"></a> MySQL Server Setup (Ubuntu)
1. Run ```sudo mysql -u root``` and enter your root password
2. Run ```create database $DB_DATABASE;```, replacing ```$DB_DATABASE``` with your database name
3. Run ```GRANT ALL PRIVILEGES ON $DB_DATABASE.* TO '$DB_USERNAME'@'localhost' IDENTIFIED BY '$DB_PASSWORD';```, replacing ```$DB_DATABASE``` with the database name picked previously, and ```$DB_USERNAME``` and ```$DB_PASSWORD``` with what you like
4. Run ```FLUSH PRIVILEGES;``` so the changes take effect
5. Edit your ```.env``` file to reflect the ```$DB_DATABASE```, ```$DB_USERNAME```, ```$DB_PASSWORD``` you picked.


## Writing Test Cases and Factories

The reason we have three levels of creating models: ```insert()```, ```factory()```,
and ```factoryWithSetup()``` is that they all have particular uses.

* ```insert()``` is good for creating a customized model to exact specification.
* ```factory()``` is good for developing code, only concentrating on the models
foreign-key dependencies.
* ```factoryWithSetup()``` is good for writing test cases.

The reason you might use ```insert()``` is that you have a very clear idea of a
particular model that you need.
While ```factory()``` is better if your primary concern is how the dependencies
between models are fulfilled.
For Example: You're developing a seeder for a client wants to get an idea of how
their event is going to be planned out with volDB. Visuals help and volDB gives
time graphs for it. They don't care about the users in them or the event name,
they only care about visualizing how all the schedule slots they've planned
look so they can adjust time in-between shifts, see any overlaps they may have
missed, etc. Instead of inserting all manually, ```factory()``` does all this for
you, while still making you aware of the dependencies between the what models
you're using. Later if they want to edit the schedules, event name, etc. you can
fill all that in via "overriding" without putting redundent work in ahead of time.


### Test Cases

Test cases should be written with readability in the forefront of your mind.
There are a couple tools to help with that. Below is an illustrated example
to help you on your way.

For this case we'll test something trivial like...

If **slots are put on the same schedule**, then **they should be on
the same schedule**.

We can split that into a _Condition_ and an _Assertion_
* The Condition: Slots are put on the same schedule
* The Assertion: The slots are on the same schedule

We'll start by creating the test using ``` php artisan make:test SlotTest --unit ```.
``` --unit ``` is just a better way to say we're testing internal functionality.

Then in our ```tests\Unit\SlotTest.php```, you'll see something like this...

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

From there we can start making some changes. Like adding the ``` RefreshDatabase ```
trait that makes sure before the test suite is run, it clears the database first.
Clearing your database before running tests is always a good practice.

_Note_: ``` RefreshDatabase ``` does not reset the database after each test, but
after each suite/class.

We can also remove the "test" from ``` testExample() ``` and place the ``` @test ```
directive in the comment block above. They both tell PHPUnit that the function is
a test. This may seem like a pain, but declaring smaller helper functions would
throw a lot of warnings if we didn't make this disctinction. Its pretty handy.
It also gives us the freedom to name the function whatever we like. So lets go
ahead and rename ``` testExample() ``` to something more descriptive of what
we're trying to achieve. We'll call it...

``` slots_created_on_the_same_schedule_are_on_the_same_schedule() ```

...it's a little long, but it tells you exactly what's being tested. You'll also
notice we use ``` snake_case ``` instead of ``` camelCase ``` here. This is
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
Let's start by using a new utility that comes with our framework...

```
$slots = factoryWithSetup(Slot::class, 2)->create();
$first_slots_schedule = $slot[0]->schedule;
$second_slots_schedule = $slot[1]->schedule;
```

``` factoryWithSetup() ``` allows us to create a quick model similar
to ``` factory() ``` without having to worry about the foreign key dependecies
that would usually throw ``` PDO: ConstraintError: Foreign Key cannot be null ```.

Once we have that for setup, we can then test if they share the same schedule...

``` $this->assertEquals($first_slots_schedule->id, $second_slots_schedule->id); ```

...and this should pass, **but it doesn't pass**.

The reason why is that when you use ``` factoryWithSetup() ```, it creates the
given model, but in it's own isolated strand of randomly generated models to
support its existance. So how do we connect the two? We easily change it to...

```
$schedule = factoryWithSetup(Schedule::class)->create();
$slots = factoryWithSetup(Slot::class, 2)->create([
  'schedule_id' => $schedule->id;
]);
```

...this still randomly fills both ```Slot``` and ```Schedule``` instances, but
this time you've overridden the slots randomly generated schedules with a common
one. This also prevents the random schedules from being generated in the first place.
So now we run it again and **success**!

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
      $schedule = factoryWithSetup(Schedule::class)->create();

      // When
      $slots = factoryWithSetup(Slot::class, 2)->create([
        'schedule_id' => $schedule->id;
      ]);

      $first_slots_schedule = $slot[0]->schedule;
      $second_slots_schedule = $slot[1]->schedule;

      // Then
      $this->assertEquals($first_slots_schedule->id, $second_slots_schedule->id);
    }
}
```

In actuality **this would still fail and should**. The functionality shouldn't
even exist for defining the model at this point. You should be defining the
expected behavior ahead of time in the test and then fill out the implementation
later. This is called **Test Driven Development** or **TDD**. It's a fantastic
tool to add to your development process if you haven't already and if you're ready
you should look into it's bigger brother **Behavior Driven Development (BDD)**.

### Factories

Writing factories is a great way to generate a lot of test data. When writing
code that requires no throwaway data and for things to be empty, it's still
correct to use ```insert()``` or some variant of it.

You would write factories similarly to how you would write
[laravel explains](https://laravel.com/docs/5.8/database-testing),
but with an added ```with_setup``` state. This allows foreign key dependencies
to be auto-filled with it's own dependent models that cascade, creating it's
own strand of models only for the sole purpose of making sure the original
model can exist

```php
<?php

$factory->state(Department::class, 'with_setup', function (Faker $faker)
{
    return
    [
        'event_id' => function ()
        {
            return factory(Event::class)->states('with_setup')->create()->id;
        },
    ];
});
```

In this example the ```Department``` model can be generated with either
```factory(Department::class)->states('with_setup')``` or
```factoryWithSetup(Department::class)```. The ```Event``` model creates it's
own models if it needs to in order to exist, thus supporting ```Department```'s
existance.

``` factory() ``` should fulfill and non-null constraints on primitives with
random data.
