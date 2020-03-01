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
                $user->assignRole('user');
            } elseif ($i % 10) {
                $user->assignRole('moderator');
            } else {
                $user->assignRole('writer');
            }

        }
//        factory(User::class, 50)->create();

        $admin = User::create([
            'name' => 'Админ',
            'last_name' => 'Админ',
            'patronymic' => 'Админ',
            'email' => 'admin',
            'password' => Hash::make('password'),
        ]);
        $admin->save();
        $admin->assignRole('admin');
    }
}
