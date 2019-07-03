<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    private $roles =
    [
        'admin',
        'volunteer',
        'medical',
        'fire',
        'banned',
        'ranger',
        'ranger-khaki',
        'event-admin',
        'department-lead',
        'photography',
        'board-member',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->roles as $name)
        {
            try
            {
                factory(Role::class)->create([
                    'name' => $name,
                ]);

                dump("New role created: {$name}");
            }
            catch (Exception $exception)
            {
                // Get the MySQL error number
                $error = $exception->getPrevious()->errorInfo[1];

                // Duplicate?
                if ($error == 1062)
                {
                    dump("Role already exists: {$name}");
                }
                else
                {
                    dump("MySQL Error!", $exception);
                }
            }
        }
    }
}
