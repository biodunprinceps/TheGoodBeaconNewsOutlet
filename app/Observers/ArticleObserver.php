<?php

namespace App\Observers;

use App\Models\Article;
use App\Models\User;
use App\Notifications\ArticlePublishedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class ArticleObserver
{
    /**
     * Handle the Article "saving" event.
     * Sanitize data before saving to prevent UTF-8 errors.
     */
    public function saving(Article $article): void
    {
        try {
            // Sanitize text fields to ensure valid UTF-8
            $textFields = ['title', 'excerpt', 'content', 'meta_title', 'meta_description', 'meta_keywords'];

            foreach ($textFields as $field) {
                if ($article->isDirty($field) && !empty($article->$field)) {
                    $value = $article->$field;

                    // Check if the value is valid UTF-8
                    if (!mb_check_encoding($value, 'UTF-8')) {
                        // Convert to valid UTF-8
                        $article->$field = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                    }
                }
            }

            // Log the save attempt
            Log::info('Article saving', [
                'id' => $article->id,
                'title' => mb_substr($article->title ?? '', 0, 50),
                'has_media' => $article->id ? $article->getMedia('featured_image')->count() : 0,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ArticleObserver::saving: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }

    /**
     * Handle the Article "created" event.
     */
    public function created(Article $article): void
    {
        // Send notification when article is published on creation
        if ($article->status === 'published' && $article->published_at) {
            $this->notifySubscribers($article);
        }
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article): void
    {
        // Send notification when article status changes to published
        if ($article->wasChanged('status') && $article->status === 'published' && $article->published_at) {
            $this->notifySubscribers($article);
        }
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "restored" event.
     */
    public function restored(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "force deleted" event.
     */
    public function forceDeleted(Article $article): void
    {
        //
    }

    /**
     * Notify all admin users about the published article
     */
    protected function notifySubscribers(Article $article): void
    {
        // Get all admin users (you can modify this to get subscribers instead)
        $users = User::all();

        // Send notification to all users
        Notification::send($users, new ArticlePublishedNotification($article));
    }
}
