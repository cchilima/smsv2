<?php

namespace App\Repositories\Users;

use App\Models\Users\UserNextOfKin;

class userNextOfKinRepository
{
    public function destroy($id)
    {
        return UserNextOfKin::destroy($id);
    }
}
