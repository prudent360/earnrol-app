<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\CohortEnrollment;
use App\Models\CoachingBooking;
use App\Models\MembershipSubscription;
use App\Models\ProductPurchase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $creator = Auth::user();
        $filter = $request->get('filter', 'all');
        $search = $request->get('search');

        // Get IDs of all creator's products, cohorts, memberships, coaching
        $productIds = $creator->digitalProducts()->pluck('id');
        $cohortIds = $creator->createdCohorts()->pluck('id');
        $membershipIds = $creator->membershipPlans()->pluck('id');
        $coachingIds = $creator->coachingServices()->pluck('id');

        // Collect unique student user IDs based on filter
        $studentQuery = User::query();

        if ($filter === 'products') {
            $userIds = ProductPurchase::whereIn('digital_product_id', $productIds)->pluck('user_id')->unique();
            $studentQuery->whereIn('id', $userIds);
        } elseif ($filter === 'cohorts') {
            $userIds = CohortEnrollment::whereIn('cohort_id', $cohortIds)->pluck('user_id')->unique();
            $studentQuery->whereIn('id', $userIds);
        } elseif ($filter === 'memberships') {
            $userIds = MembershipSubscription::whereIn('membership_plan_id', $membershipIds)->pluck('user_id')->unique();
            $studentQuery->whereIn('id', $userIds);
        } elseif ($filter === 'coaching') {
            $userIds = CoachingBooking::whereIn('coaching_service_id', $coachingIds)->pluck('user_id')->unique();
            $studentQuery->whereIn('id', $userIds);
        } else {
            // All students across all types
            $productUserIds = ProductPurchase::whereIn('digital_product_id', $productIds)->pluck('user_id');
            $cohortUserIds = CohortEnrollment::whereIn('cohort_id', $cohortIds)->pluck('user_id');
            $membershipUserIds = MembershipSubscription::whereIn('membership_plan_id', $membershipIds)->pluck('user_id');
            $coachingUserIds = CoachingBooking::whereIn('coaching_service_id', $coachingIds)->pluck('user_id');

            $allUserIds = $productUserIds
                ->merge($cohortUserIds)
                ->merge($membershipUserIds)
                ->merge($coachingUserIds)
                ->unique();

            $studentQuery->whereIn('id', $allUserIds);
        }

        if ($search) {
            $studentQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $studentQuery->orderBy('name')->paginate(25)->appends($request->query());

        // For each student, attach what they purchased/enrolled in
        $students->getCollection()->transform(function ($student) use ($productIds, $cohortIds, $membershipIds, $coachingIds) {
            $student->purchased_products = ProductPurchase::where('user_id', $student->id)
                ->whereIn('digital_product_id', $productIds)
                ->with('product:id,title,slug')
                ->get();

            $student->enrolled_cohorts = CohortEnrollment::where('user_id', $student->id)
                ->whereIn('cohort_id', $cohortIds)
                ->with('cohort:id,title,slug')
                ->get();

            $student->membership_subs = MembershipSubscription::where('user_id', $student->id)
                ->whereIn('membership_plan_id', $membershipIds)
                ->with('membershipPlan:id,title,slug')
                ->get();

            $student->coaching_bookings = CoachingBooking::where('user_id', $student->id)
                ->whereIn('coaching_service_id', $coachingIds)
                ->with('service:id,title,slug')
                ->get();

            return $student;
        });

        // Counts for filter tabs
        $counts = [
            'all' => User::whereIn('id',
                ProductPurchase::whereIn('digital_product_id', $productIds)->pluck('user_id')
                    ->merge(CohortEnrollment::whereIn('cohort_id', $cohortIds)->pluck('user_id'))
                    ->merge(MembershipSubscription::whereIn('membership_plan_id', $membershipIds)->pluck('user_id'))
                    ->merge(CoachingBooking::whereIn('coaching_service_id', $coachingIds)->pluck('user_id'))
                    ->unique()
            )->count(),
            'products' => ProductPurchase::whereIn('digital_product_id', $productIds)->distinct('user_id')->count('user_id'),
            'cohorts' => CohortEnrollment::whereIn('cohort_id', $cohortIds)->distinct('user_id')->count('user_id'),
            'memberships' => MembershipSubscription::whereIn('membership_plan_id', $membershipIds)->distinct('user_id')->count('user_id'),
            'coaching' => CoachingBooking::whereIn('coaching_service_id', $coachingIds)->distinct('user_id')->count('user_id'),
        ];

        return view('creator.students.index', compact('students', 'filter', 'search', 'counts'));
    }
}
