<?php

namespace App\Observers;

use App\Models\TextRequest;
use Illuminate\Support\Str;

class TextRequestObserver
{

    /**
     * Handle the TextRequest "creating" event.
     *
     * @param  \App\Models\TextRequest  $textRequest
     * @return void
     */
    public function saving(TextRequest $textRequest)
    {
        if ($textRequest->isDirty('final_text')) {
            $textRequest->word_count = Str::wordCount($textRequest->final_text);
        }
    }

    /**
     * Handle the TextRequest "saved" event.
     *
     * @param  \App\Models\TextRequest  $textRequest
     * @return void
     */
    public function saved(TextRequest $textRequest)
    {
        if ($textRequest->isDirty('title')) {
            $textRequest->logs()->create(['type' => 'title', 'content' => $textRequest->title]);
        }

        if ($textRequest->isDirty('final_text')) {
            $textRequest->logs()->create(['type' => 'final_text', 'content' => $textRequest->final_text]);
        }

        if ($textRequest->isDirty('summary')) {
            $textRequest->logs()->create(['type' => 'summary', 'content' => $textRequest->summary]);
        }

        if ($textRequest->isDirty('meta_description')) {
            $textRequest->logs()->create(['type' => 'meta_description', 'content' => $textRequest->meta_description]);
        }
    }
}
