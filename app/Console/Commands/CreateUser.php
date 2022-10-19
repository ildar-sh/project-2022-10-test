<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {login}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a user with specified login';

    protected function rules() : array
    {
        return [
            'login' => ['required', 'unique:users', 'max:32'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $login = $this->argument('login');
        $password = $this->secret('Enter the password');
        $password_confirmation = $this->secret('Confirm the password');

        $params = compact('login', 'password', 'password_confirmation');
        $validator = Validator::make($params, $this->rules());

        if ($validator->fails()) {
            $this->warn("User wasn't created, validation errors occurred:");

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return Command::FAILURE;
        } else {
            if ($user = User::create([
                'login' => $validator->validated()['login'],
                'password' => Hash::make($validator->validated()['password']),
            ])) {
                $this->info("User {$user->login} created!");
                return Command::SUCCESS;
            } else {
                $this->error("Something went wrong, user not saved!");
                return Command::FAILURE;
            }
        }
    }
}
