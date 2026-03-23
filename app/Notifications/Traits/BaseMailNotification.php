<?php
7: 
8: namespace App\Notifications\Traits;
9: 
10: use App\Services\Mail\MailConfigService;
11: use App\Services\Mail\TemplateService;
12: use Illuminate\Notifications\Messages\MailMessage;
13: 
14: trait BaseMailNotification
15: {
16:     /**
17:      * Get the mail representation of the notification.
18:      */
19:     public function toMail(object $notifiable): MailMessage
20:     {
21:         // Apply dynamic SMTP config
22:         MailConfigService::apply();
23: 
24:         // Parse template
25:         [$subject, $body] = TemplateService::parse($this->getTemplateKey(), $this->getTemplateData($notifiable));
26: 
27:         return (new MailMessage)
28:             ->subject($subject)
29:             ->line(nl2br($body));
30:     }
31: 
32:     /**
33:      * Define the template key for this notification.
34:      */
35:     abstract protected function getTemplateKey(): string;
36: 
37:     /**
38:      * Define variables for the template.
39:      */
40:     abstract protected function getTemplateData(object $notifiable): array;
41: }
42: 1: 1: 
