## Laravel10 - Custom Artisan command to send email newsletter to all users with attachment

### Install Laravel

If you're developing on a Mac and the Docker Desktop is already installed, you can use a simple terminal command to create a new Laravel project.

```
curl -s "https://laravel.build/laravel10-custom-artisan-command" | bash
```
After the project has been created, you can navigate to the application directory and start Laravel Sail. Laravel Sail provides a simple command-line interface for interacting with Laravel's default Docker configuration:
```
cd laravel10-vue-SPA-pinia-vite && ./vendor/bin/sail up
```
Install authentication scaffolding with Vue and Bootstrap using the following commands
```
composer require laravel/ui
php artisan ui vue --auth
```
Run the following commands to compile your fresh scaffolding.
```
npm install && npm run dev
```
To run all of your outstanding migrations, execute the migrate Artisan command:
```
php artisan migrate
```

##Generating Commands
To create a new command, you may use the make:command Artisan command. This command will create a new command class in the app/Console/Commands directory.php 
```
artisan make:command SendEmails
```

After generating your command, you should define appropriate values for the signature and description properties of the class.These properties will be used when displaying your command on the list screen.The handle method will be called when your command is executed. You may place your command logic in this method.

### Generating Mailables

When building Laravel applications, each type of email sent by your application is represented as a "mailable" class. These classes are stored in the app/Mail directory.
```
php artisan make:mail NewsletterEmail
```
Add the following line of code into NewsletterEmail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    /**
     * Create a new message instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('aneesh85@gmail.com', 'Aneesh John'),
            replyTo: [
                new Address('aneesh85@gmail.com', 'Aneesh John'),
            ],
            subject: 'Email Newsletter',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromStorage('public/sample.pdf')
                    ->as('name.pdf')
                    ->withMime('application/pdf'),
        ];
    }
} 

Here I have added the attachment file as  sample.pdf file in storage/app/public directory

### Configuring The View

Within a mailable class's content method, you may define the view, or which template should be used when rendering the email's contents.

Create the newsletter email template in views/emails/newsletter.blade.php and add the below code
```
Dear {{ $user->name }}<br>

This is a sample email template
```

You have to add send mail configuration with mail driver as Gmail server, mail host, mail port, mail username, and mail password so Laravel 10 will use those sender details on email. So you can simply add the following in the .env file.
```
MAIL_MAILER=smtp 
MAIL_HOST=smtp.gmail.com 
MAIL_PORT=465 
MAIL_USERNAME=aneesh85@gmail.com 
MAIL_PASSWORD="xxxxxx" 
MAIL_ENCRYPTION=tls 
MAIL_FROM_ADDRESS="hello@example.com" 
MAIL_FROM_NAME="${APP_NAME}" 
```
### GMAIL SMTP configuration

Google has stopped support for Less Secure Apps recently. In order to send email via gmail SMTP, you should do something in the Google account settings as below

1) Login to your Gmail
2) Go to the Security setting and Enable 2-factor authentication
3) After enabling this you can see the app passwords option. 
4) From Your app passwords tab select the Other option and put your app name and click the GENERATE button to get a new app password.
5) Finally copy the 16-digit password and click done. Now use this password instead of an email password to send mail via your app.

### Run the custom command

Now we are ready to go. Run the following command from your console to send sample email to all users with the attachment

```
PHP artisan newsletter:send
```

### Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Aneesh John via [aneesh85@gmail.com](mailto:aneesh85@gmail.com). 

