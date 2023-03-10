<?php

namespace App\Jobs;

use App\Events\AudioProcessed;
use App\Models\TextRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProcessAudio implements ShouldQueue
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
        $fileName = Str::uuid() . '.mp3';
        $processer = $this->defineProcesser();
        $response = Http::timeout(900)
            ->attach('audio_file', file_get_contents($this->textRequest->audio_file_path), $fileName)
            ->post($processer);

        if ($response->failed()) {
            return $response->throw();
        }

        if ($response->successful()) {
            $originalText = Str::squish($response->json('text'));
            $this->textRequest->update(['original_text' => $originalText]);
            //    event(new AudioProcessed($this->textRequest));
        }
    }

    public function defineProcesser()
    {
        $language = $this->textRequest->language;
        $service = 'whisper';

        if ($language != 'en') {
            $service = 'whisper-large';
        }

        return "http://$service:9000/asr?task=transcribe&language=$language&output=json";
    }
}
