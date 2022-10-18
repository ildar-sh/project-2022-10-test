<?php

namespace App\Console\Commands;

use App\Actions\CreateTransactionAction;
use App\Enums\TransactionType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;


class TransactionCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:create
                            {login}
                            {description}
                            {amount}
                            {--type=credit : Set transaction type, debit or credit(default)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create transaction with description for specified user by login';

    protected function rules(): array
    {
        return [
            'login' => ['required', 'exists:users', 'max:32'],
            'description' => ['required', 'max:1024'],
            'amount' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($value <= 0) {
                        $fail('The ' . $attribute . ' must be > 0');
                    }
                },
            ],
            'type' => ['required', new Enum(TransactionType::class)],
        ];
    }

    protected CreateTransactionAction $action;

    public function __construct(CreateTransactionAction $action)
    {
        $this->action = $action;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $params = $this->arguments() + $this->options();

        $validator = Validator::make($params, $this->rules());

        if ($validator->fails()) {
            $this->warn("Transaction wasn't created, validation errors occurred:");

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return Command::FAILURE;
        } else {
           $transaction = $this->action->execute($validator->validated());

            if ($transaction) {
                $this->info("Transaction {$transaction->id} created, status is {$transaction->status->value}!");
                return Command::SUCCESS;
            } else {
                $this->error("Something went wrong, transaction not created!");
                return Command::FAILURE;
            }
        }
    }
}
