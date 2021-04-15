<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {name?} {email?} {password?} {role?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'User Creating Command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->askValid('What is the user name?', 'name', ['required', 'string', 'between:4,30']);
        $password = $this->askValid('What is the password?', 'password', ['required', 'string', 'between:4,50']);
        $email = $this->askValid('What is the email?', 'email', ['required', 'string', 'between:4,50', 'email', 'unique:users']);
        $roleInput = $this->choice('What is the role?', ['admin', 'default'], $allowMultipleSelections = false);

        $user = new User;
        $user->name = $name;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->email_verified_at = now();
        $user->save();

        $role = Role::where('name', $roleInput)->firstOrFail();
        $user->roles()->attach($role);

        $this->info("User with role $roleInput successfully added!");
    }

    /**
     * @param $question
     * @param $field
     * @param $rules
     * @return mixed
     */
    protected function askValid($question, $field, $rules)
    {
        if ($field == 'password' || $field == 'confirm_password') {
            $value = $this->secret($question);
        } else {
            $value = $this->ask($question);
        }

        if ($message = $this->validateInput($rules, $field, $value)) {
            $this->error($message);

            return $this->askValid($question, $field, $rules);
        }

        return $value;
    }

    /**
     * @param $rules
     * @param $fieldName
     * @param $value
     * @return string|null
     */
    protected function validateInput($rules, $fieldName, $value)
    {
        $validator = Validator::make([
            $fieldName => $value
        ], [
            $fieldName => $rules
        ]);

        return $validator->fails()
            ? $validator->errors()->first($fieldName)
            : null;
    }
}
