<?php

declare(strict_types=1);

namespace App\Domain\Checkins\Repositories;

use App\Domain\Checkins\Models\Checkin;

class EloquentCheckinRepository implements CheckinRepositoryInterface
{
    /**
     * Persist the given checkin.
     *
     * @param  Checkin  $checkin  The checkin to save.
     * @return Checkin The saved checkin.
     */
    public function save(Checkin $checkin): Checkin
    {
        $checkin->save();

        return $checkin;
    }
}
