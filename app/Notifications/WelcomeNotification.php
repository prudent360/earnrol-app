<?php
7: 
8: namespace App\Notifications;
9: 
10: use Illuminate\Bus\Queueable;
11: use Illuminate\Notifications\Notification;
12: 
13: class WelcomeNotification extends Notification
14: {
15:     use Queueable, Traits\BaseMailNotification;
16: 
17:     public function via(object $notifiable): array
18:     {
19:         return ['database', 'mail'];
20:     }
21: 
22:     protected function getTemplateKey(): string
23:     {
24:         return 'welcome';
25:     }
26: 
27:     protected function getTemplateData(object $notifiable): array
28:     {
29:         return [
30:             'name' => $notifiable->name,
31:         ];
32:     }
33: 
34:     public function toArray(object $notifiable): array
35:     {
36:         return [
37:             'type'    => 'welcome',
38:             'icon'    => '👋',
39:             'title'   => 'Welcome to EarnRol',
40:             'message' => 'We\'re glad to have you here. Start exploring our courses!',
41:             'url'     => '/dashboard',
42:         ];
43:     }
44: }
45: 1: 
