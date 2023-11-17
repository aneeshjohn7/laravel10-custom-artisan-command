<?php

namespace App\Console\Commands;

use App\Mail\NewsletterEmail;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email newsletter to all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::select('name', 'email')->get();
        foreach($users as $user){
            try{

                Mail::to($user->email)->send(new NewsletterEmail($user));
            } catch(Exception $e) {
                
                echo "Error sending email to: ".$user->email.":- ".$e->getMessage();
            }
        }
    }
}
