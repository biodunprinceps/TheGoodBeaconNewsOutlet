<?php

namespace App\Observers;

use App\Models\Article;
use App\Models\User;
use App\Notifications\ArticlePublishedNotification;
use Illuminate\Support\Facades\Notification;

class ArticleObserver
{
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
