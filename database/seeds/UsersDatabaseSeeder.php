<?php

use App\Models\User;
use Faker\Provider\ru_RU\Person;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;

class UsersDatabaseSeeder extends Seeder
{

    /**
     * @var Faker
     */
    private $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->faker->addProvider(new Person($this->faker));

        for ($i = 0; $i <= 50; $i++) {
            $fullName = explode(' ', $this->faker->name);
            $user = User::create([
                'name' => $fullName[0],
                'last_name' => $fullName[1],
                'patronymic' => $fullName[2],
                'email' => $this->faker->unique()->safeEmail,
                'password' => Hash::make('password'),
            ]);
            $user->save();
            if ($i % 2 === 0) {
                $user->assignRole('customer');
            } elseif ($i % 10) {
                $user->assignRole('employee');
            } else {
                $user->assignRole('admin');
            }

        }
//        factory(User::class, 50)->create();

        $admin = User::create([
            'name' => 'Админ',
            'last_name' => 'Админ',
            'patronymic' => 'Админ',
            'email' => 'admin',
            'password' => Hash::make('admin'),
        ]);
        $admin->save();
        $admin->assignRole('admin');

        $employee = User::create([
            'name' => 'EMPLOYEE',
            'last_name' => 'EMPLOYEE',
            'patronymic' => 'EMPLOYEE',
            'email' => 'employee',
            'password' => Hash::make('employee'),
        ]);
        $employee->save();
        $employee->assignRole('employee');

        $customer = User::create([
            'name' => 'CUSTOMER',
            'last_name' => 'CUSTOMER',
            'patronymic' => 'CUSTOMER',
            'email' => 'customer',
            'password' => Hash::make('customer'),
        ]);
        $customer->save();
        $customer->assignRole('customer');
    }
}
