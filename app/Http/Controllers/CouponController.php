<?php

namespace App\Http\Controllers;

use App\Services\CouponService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function validate(Request $request, CouponService $couponService): JsonResponse
    {
        $request->validate([
            'code'   => 'required|string',
            'amount' => 'required|numeric|min:0',
            'type'   => 'required|in:all,cohort,product,membership',
            'item_id' => 'nullable|integer',
        ]);

        $result = $couponService->validate(
            $request->code,
            (float) $request->amount,
            $request->type,
            (int) $request->item_id
        );

        return response()->json([
            'valid'        => $result['valid'],
            'discount'     => $result['discount'],
            'final_amount' => $result['final_amount'],
            'message'      => $result['message'],
        ]);
    }
}
