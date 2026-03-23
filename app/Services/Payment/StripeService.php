<?php
7: 
8: namespace App\Services\Payment;
9: 
10: use App\Models\Course;
11: use App\Models\Setting;
12: use App\Models\User;
13: use Stripe\Stripe;
14: use Stripe\Checkout\Session;
15: use Illuminate\Support\Facades\Log;
16: 
17: class StripeService
18: {
19:     protected string $secretKey;
20: 
21:     public function __construct()
22:     {
23:         // Prefer settings from DB, fallback to config
24:         $this->secretKey = Setting::get('stripe_secret_key', config('services.stripe.secret'));
25:         Stripe::setApiKey($this->secretKey);
26:     }
27: 
28:     /**
29:      * Create a Stripe Checkout Session for a course.
30:      */
31:     public function createCheckoutSession(Course $course, User $user): ?Session
32:     {
33:         try {
34:             $currency = Setting::get('currency', 'GBP');
35:             
36:             return Session::create([
37:                 'payment_method_types' => ['card'],
38:                 'line_items' => [[
39:                     'price_data' => [
40:                         'currency' => strtolower($currency),
41:                         'product_data' => [
42:                             'name' => $course->title,
43:                             'description' => $course->description,
44:                         ],
45:                         'unit_amount' => intval($course->price * 100),
46:                     ],
47:                     'quantity' => 1,
48:                 ]],
49:                 'mode' => 'payment',
50:                 'success_url' => route('payments.callback') . '?session_id={CHECKOUT_SESSION_ID}&gateway=stripe',
51:                 'cancel_url' => route('courses.show', $course),
52:                 'customer_email' => $user->email,
53:                 'metadata' => [
54:                     'course_id' => $course->id,
55:                     'user_id' => $user->id,
56:                 ],
57:             ]);
58:         } catch (\Exception $e) {
59:             Log::error('Stripe Checkout Session Error', ['message' => $e->getMessage()]);
60:             return null;
61:         }
62:     }
63: 
64:     /**
65:      * Retrieve and verify a Stripe Checkout Session.
66:      */
67:     public function verifySession(string $sessionId): ?Session
68:     {
69:         try {
70:             $session = Session::retrieve($sessionId);
71:             if ($session->payment_status === 'paid') {
72:                 return $session;
73:             }
74:             return null;
75:         } catch (\Exception $e) {
76:             Log::error('Stripe Session Verification Error', ['message' => $e->getMessage()]);
77:             return null;
78:         }
79:     }
80: }
81: 
