<?php

namespace App\Console\Commands;

use App\Enums\Roles;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CreateSuperUser extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-super-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle() {

        $this->line('Setup system admin details!');

        $isExist = User::whereHas('getUserProfile', function ($query) {
            return $query->where(['role_id' => Roles::Superadmin->value]);
        })->first();

        if ($isExist) {
            $this->info('SystemAdmin is already exist with Email: ' . $isExist->email);
            return;
        }

        $name = $this->validateField(function () {
            return $this->ask('Enter name: ');
        }, ['required', 'string']);


        $email = $this->validateField(function () {
            return $this->ask('Enter email: ');
        }, ['email', 'required|email']);

        $password = $this->validateField(function () {
            return $this->secret('Enter password: ');
        }, ['password', 'required']);
        $confirmPassword = $this->validateField(function () {
            return $this->secret('Confirm password: ');
        }, ['password_confirmation', 'required']);

        if ($password != $confirmPassword) {
            $this->error('Password Not Match Please retry.');
            $this->newLine();
            return $this->handle();
        }

        $this->line('Your Details are:');
        $this->table(
            ['name', 'Email', 'Password'],
            [[$name, $email, 'Your Entered Password']]
        );

        if ($this->confirm('Do you wish to continue with this detail : ?')) {

            try {
                DB::beginTransaction();
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'email_verified_at' => date('Y-m-d H:i:s'),
                    'password' => bcrypt($password)
                ]);

                UserProfile::create([
                    'user_id' => $user->id,
                    'role_id' => Roles::Superadmin->value
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
            }
            return;
        } else {
            $this->info('You choose no to repeat the process.:');
            return $this->handle();
        }
    }


    public function validateField($method, $rules) {

        $value = $method();
        $validator = Validator::make([$rules[0] => $value], [
            $rules[0] => $rules[1],
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->messages()[$rules[0]][0]);
            return $this->validateField($method, $rules);
        }

        return $value;
    }

}
