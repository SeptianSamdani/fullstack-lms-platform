<?php

namespace App\Jobs;

use App\Models\Lesson;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\File;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class UploadLessonVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;

    public function __construct(
        public Lesson $lesson,
        public string $tempPath, // path di disk 'local', file sementara hasil upload
    ) {}

    public function handle(): void
    {
        $cloudinaryPath = Storage::disk('cloudinary')->putFile(
            'lms/lessons',
            new File(Storage::disk('local')->path($this->tempPath))
        );

        $this->lesson->update([
            'content_url'   => Storage::disk('cloudinary')->url($cloudinaryPath),
            'upload_status' => 'ready',
        ]);

        Storage::disk('local')->delete($this->tempPath);
    }

    public function failed(\Throwable $e): void
    {
        $this->lesson->update(['upload_status' => 'failed']);
        Storage::disk('local')->delete($this->tempPath);
    }
}