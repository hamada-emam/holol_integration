<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use App\Services\Client\Imile\Customer\Order as OrderService;
use App\Services\Client\Imile\Auth\Login;
use DateTime;
use Exception;

class CancelOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $orderCode)
    {
    }

    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil(): DateTime
    {
        return now()->addHours(24);
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     *
     * @return array<int, int>
     */
    public function backoff(): array
    {
        // return array_merge([1, 2, 3], [$this->attempts() * 4]);
        return [1, 2, 10];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $token = Login::login();
            OrderService::delete($this->orderCode, $token);
        } catch (Exception $e) {
            info("error->");
            info($e->getMessage());
        }
    }
}
