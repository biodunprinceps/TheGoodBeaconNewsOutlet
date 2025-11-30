<?php

namespace App\Console\Commands;

use App\Mail\ArticlePublishedMail;
use App\Models\Article;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email? : The email address to send to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to verify email configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?: $this->ask('Enter email address to test');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email address!');
            return 1;
        }

        $this->info("Sending test email to: {$email}");

        // Get the first published article
        $article = Article::published()->first();

        if (!$article) {
            $this->error('No published articles found! Please publish an article first.');
            return 1;
        }

        try {
            Mail::to($email)->send(new ArticlePublishedMail($article));
            $this->info('✅ Test email sent successfully!');
            $this->line("Article: {$article->title}");
            $this->line("Check your inbox at: {$email}");

            if (config('mail.mailer') === 'log') {
                $this->warn('📝 Mail is set to "log" driver. Check storage/logs/laravel.log');
            }
        } catch (\Exception $e) {
            $this->error('❌ Failed to send email!');
            $this->error($e->getMessage());
            return 1;
        }

        return 0;
    }
}
