<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class UserLoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user  = new User();
        $user->name = "Amit Guru";
        $user->email = "amit.guru@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        
        $user  = new User();
        $user->name = "Shaleen Mishra";
        $user->email = "shaleen.mishra@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 4;
        $user->save();

        

        $user  = new User();
        $user->name = "Vikas Prajapati";
        $user->email = "vikas.prajapati@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 1;
        $user->save();

        
        $user  = new User();
        $user->name = "Parth Patel";
        $user->email = "parth.patel@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 8;
        $user->save();

        $user  = new User();
        $user->name = "Anshul Patel";
        $user->email = "anshul.patel@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 5;
        $user->save();

        

        $user  = new User();
        $user->name = "Amit Patel";
        $user->email = "amit.patel@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 2;
        $user->save();

        
        $user  = new User();
        $user->name = "Madhulika Mishra";
        $user->email = "madhulika.mishra@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 6;
        $user->save();

        

        $user  = new User();
        $user->name = "Jin Kim";
        $user->email = "jin.kim@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "Akash Asthana";
        $user->email = "akash.asthana@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 8;
        $user->save();


        $user  = new User();
        $user->name = "Hemlata Sahu";
        $user->email = "hemlata.sahu@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 9;
        $user->save();

        $user  = new User();
        $user->name = "Ruchi Yadav";
        $user->email = "ruchi.yadav@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 10;
        $user->save();

        $user  = new User();
        $user->name = "Shubham Meena";
        $user->email = "shubham.meena@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 11;
        $user->save();

        $user  = new User();
        $user->name = "Gaurav Meena";
        $user->email = "gaurav.meena@mydempsoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 12;
        $user->save();

        $user  = new User();
        $user->name = "Vishal Gupta";
        $user->email = "vishal.gupta@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 13;
        $user->save();

        $user  = new User();
        $user->name = "Sandhya Prajapati";
        $user->email = "sandhya.prajapati@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 14;
        $user->save();

        $user  = new User();
        $user->name = "Abhishek Meena";
        $user->email = "abhishek.meena@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 15;
        $user->save();

        $user  = new User();
        $user->name = "Gopal Sen";
        $user->email = "gopal.sen@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 16;
        $user->save();
        
        $user  = new User();
        $user->name = "User 1";
        $user->email = "user1fp@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "User 2";
        $user->email = "user2fp@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "User 3";
        $user->email = "user3fp@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "User 4";
        $user->email = "user4fp@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "User 5";
        $user->email = "user5fp@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "User 1";
        $user->email = "user1hod@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 4;
        $user->save();

        $user  = new User();
        $user->name = "User 2";
        $user->email = "user2hod@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 4;
        $user->save();

        $user  = new User();
        $user->name = "User 3";
        $user->email = "user3hod@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 4;
        $user->save();

        $user  = new User();
        $user->name = "User 4";
        $user->email = "user4hod@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 4;
        $user->save();

        $user  = new User();
        $user->name = "User 5";
        $user->email = "user5hod@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 4;
        $user->save();

        $user  = new User();
        $user->name = "User 1";
        $user->email = "user1app@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 1;
        $user->save();


        $user  = new User();
        $user->name = "User 2";
        $user->email = "user2app@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 1;
        $user->save();


        $user  = new User();
        $user->name = "User 3";
        $user->email = "user3app@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 1;
        $user->save();


        $user  = new User();
        $user->name = "User 4";
        $user->email = "user4app@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 1;
        $user->save();


        $user  = new User();
        $user->name = "User 5";
        $user->email = "user5app@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 1;
        $user->save();

        $user  = new User();
        $user->name = "User 1";
        $user->email = "user1cft@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 5;
        $user->save();

        $user  = new User();
        $user->name = "User 2";
        $user->email = "user2cft@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 5;
        $user->save();

        $user  = new User();
        $user->name = "User 3";
        $user->email = "user3cft@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 5;
        $user->save();

        $user  = new User();
        $user->name = "User 4";
        $user->email = "user4cft@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 5;
        $user->save();

        $user  = new User();
        $user->name = "User 5";
        $user->email = "user5cft@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 5;
        $user->save();

        $user  = new User();
        $user->name = "User 1";
        $user->email = "user1rep@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 2;
        $user->save();

        $user  = new User();
        $user->name = "User 2";
        $user->email = "user2rep@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 2;
        $user->save();

        $user  = new User();
        $user->name = "User 3";
        $user->email = "user3rep@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 2;
        $user->save();

        $user  = new User();
        $user->name = "User 4";
        $user->email = "user4rep@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 2;
        $user->save();

        $user  = new User();
        $user->name = "User 5";
        $user->email = "user5rep@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 2;
        $user->save();


        $user  = new User();
        $user->name = "User 1";
        $user->email = "user1tp@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 6;
        $user->save();

        $user  = new User();
        $user->name = "User 2";
        $user->email = "user2tp@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 6;
        $user->save();

        $user  = new User();
        $user->name = "User 3";
        $user->email = "user3tp@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 6;
        $user->save();

        $user  = new User();
        $user->name = "User 4";
        $user->email = "user4tp@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 6;
        $user->save();

        $user  = new User();
        $user->name = "User 5";
        $user->email = "user5tp@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 6;
        $user->save();

    }
}
