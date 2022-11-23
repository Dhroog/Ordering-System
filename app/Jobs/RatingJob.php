<?php

namespace App\Jobs;

use App\Models\Restaurant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RatingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $restaurant_id;
    protected int $rate;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($rate , $restaurant_id)
    {
        $this->rate = $rate;
        $this->restaurant_id = $restaurant_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $res = Restaurant::findOrFail($this->restaurant_id);
        $res->many_rated++;
        $res->rate = ($this->rate + $res->rate) / $res->many_rated;
        $res->save();
    }
}
