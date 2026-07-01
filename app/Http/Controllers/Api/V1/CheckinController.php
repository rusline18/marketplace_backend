<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Checkins\Actions\RecordCheckinAction;
use App\Domain\Checkins\Models\Checkin;
use App\Domain\Orders\Models\Order;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCheckinRequest;
use App\Http\Resources\Api\V1\CheckinResource;
use Illuminate\Http\JsonResponse;

class CheckinController extends Controller
{
    /**
     * Record a checkin confirming receipt or handover of an order's listing.
     *
     * @param  StoreCheckinRequest  $request  The validated checkin attributes.
     * @param  RecordCheckinAction  $action  The action that records the checkin.
     */
    public function store(StoreCheckinRequest $request, RecordCheckinAction $action): JsonResponse
    {
        $order = Order::findOrFail($request->validated('order_id'));

        $this->authorize('create', [Checkin::class, $order]);

        $checkin = $action->handle($request->user()->id, $order, $request->validated());

        return (new CheckinResource($checkin))->response()->setStatusCode(201);
    }
}
