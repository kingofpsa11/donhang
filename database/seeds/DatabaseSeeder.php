<?php

use Illuminate\Database\Seeder;
use App\Permission;
use App\Role;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        DB::table('customers')->insert([
//            [
//                'name' => 'Công ty CPCS Bắc Hapulico',
//                'short_name' => 'BHPL'
//            ],
//            [
//                'name' => 'Công ty CPCS Nam Hapulico',
//                'short_name' => 'NHPL'
//            ],
//            [
//                'name' => 'Công ty CP vật tư công nghiệp Hà Nội',
//                'short_name' => 'VTCN'
//            ],
//            [
//                'name' => 'Chi nhánh Đà Nẵng',
//                'short_name' => 'CNDN'
//            ],
//            [
//                'name' => 'Phòng Kế hoạch Kinh doanh',
//                'short_name' => 'KHKD'
//            ],
//            [
//                'name' => 'Xí nghiệp quản lý điện chiếu sáng',
//                'short_name' => 'XNVH'
//            ]
//        ]);
//
//        DB::table('categories')->insert([
//            [
//                'name' => 'Cột thép <12m',
//            ],
//            [
//                'name' => 'Cột cao (đến 17m, 2 đoạn)',
//            ],
//            [
//                'name' => 'Cột THGT có tay vươn',
//            ],
//            [
//                'name' => 'Cần đèn, lọng',
//            ],
//            [
//                'name' => 'Cột monopole, thân nâng hạ',
//            ],
//            [
//                'name' => 'Tay lắp đèn cầu trên cột',
//            ],
//            [
//                'name' => 'Cột trên đế gang',
//            ],
//            [
//                'name' => 'Cột sử dụng các đoạn ống mua sẵn',
//            ],
//            [
//                'name' => 'Đèn đường phố',
//            ],
//            [
//                'name' => 'Đèn sân vườn',
//            ],
//            [
//                'name' => 'Đèn pha',
//            ],
//            [
//                'name' => 'Đèn Led',
//            ]
//        ]);


//        // Ask for db migration refresh, default is no
//        if ($this->command->confirm('Do you wish to refresh migration before seeding, it will clear all old data ?')) {
//            // Call the php artisan migrate:refresh
//            $this->command->call('migrate:refresh');
//            $this->command->warn("Data cleared, starting from blank database.");
//        }

        // Seed the default permissions
        $permissions = Permission::defaultPermissions();

        foreach ($permissions as $perms) {
            Permission::firstOrCreate(['name' => $perms]);
        }

//        $this->command->info('Default Permissions added.');
        $role = Role::firstOrCreate(['name' => 'Admin']);
        $role->syncPermissions(Permission::all());
        $user = User::find(1);
        $user->assignRole('Admin');
        // Confirm roles needed
        if ($this->command->confirm('Create Roles for user, default is admin and user? [y|N]', true)) {

            // Ask for roles from input
            $input_roles = $this->command->ask('Enter roles in comma separate format.', 'Admin,User');

            // Explode roles
            $roles_array = explode(',', $input_roles);


            // add roles
            foreach($roles_array as $role) {
                $role = Role::firstOrCreate(['name' => trim($role)]);

                if( $role->name == 'Admin' ) {
                    // assign all permissions
                    $role->syncPermissions(Permission::all());
                    $this->command->info('Admin granted all the permissions');
                } else {
                    // for others by default only read access
                    $role->syncPermissions(Permission::where('name', 'LIKE', 'view_%')->get());
                }

                // create one user for each role
                $this->createUser($role);
            }
//
            $this->command->info('Roles ' . $input_roles . ' added successfully');

        } else {
            Role::firstOrCreate(['name' => 'User']);
            $this->command->info('Added only default user role.');
        }

//         //now lets seed some posts for demo
//        factory(\App\Contract::class, 30)->create();
//        $this->command->info('Some Posts data seeded.');
//        $this->command->warn('All done :)');
    }

        /**
         * Create a user with given role
         *
         * @param $role
         */
        private function createUser($role)
        {
            $user = factory(User::class)->create();
            $user->assignRole($role->name);

            if( $role->name == 'Admin' ) {
                $this->command->info('Here is your admin details to login:');
                $this->command->warn($user->email);
                $this->command->warn('Password is "secret"');
            }
        }
}
