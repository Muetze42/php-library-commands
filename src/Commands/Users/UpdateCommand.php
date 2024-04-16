<?php

namespace NormanHuth\Library\Commands\Users;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:users:update')]
class UpdateCommand extends CreateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:users:update {user : The ID of the User resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update an existing User resource.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            $this->user = User::findOrFail($this->argument('user'));
        } catch (\Exception $exception) {
            $this->components->error($exception->getMessage());

            return;
        }

        $this->attributes = $this->user->only(array_keys($this->attributes));

        parent::handle();
    }

    /**
     * Finalize the execution of the command.
     */
    protected function finalize(): void
    {
        $password = $this->ask('New password for this User (optional)');

        if ($password) {
            $validator = Validator::make(
                ['password' => $password],
                ['password' => Password::default()]
            );

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    $this->error($error);
                }

                $this->finalize();

                return;
            }

            $this->attributes['password'] = $password;
        }

        foreach ($this->attributes as $key => $value) {
            $this->user->{$key} = $value;
        }

        $this->user->save();

        $this->components->info(
            sprintf('The user %s was updated', $this->attributes['email'])
        );
    }
}
