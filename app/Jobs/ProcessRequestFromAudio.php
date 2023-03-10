<?php

namespace App\Jobs;

use App\Models\TextRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

class ProcessRequestFromAudio implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public TextRequest $textRequest;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(TextRequest $textRequest)
    {
        $this->textRequest = $textRequest;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $textRequest = $this->textRequest;

        // Bus::chain([
        //     new ProcessAudio($textRequest),
        //     function () use ($textRequest) {
        //         $textRequest->refresh();
        //         ParaphraseText::dispatchIf($textRequest->language == 'en', $textRequest);
        //     },
        //     new SummarizeText($textRequest->refresh()),
        //     new GenerateTitle($textRequest->refresh()),
        //     new GenerateMetaDescription($textRequest->refresh()),
        //     function () use ($textRequest) {
        //         $textRequest->refresh();
        //         $textRequest->update(['status' => 'finished']);
        //     }
        // ])->dispatch();
    }
}
