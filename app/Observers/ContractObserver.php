<?php

namespace App\Observers;

use App\Contract;
use App\Notifications\ManufacturerOrder;
use App\User;

class ContractObserver
{
    /**
     * Handle the contract "created" event.
     *
     * @param  \App\Contract  $contract
     * @return void
     */
    public function created(Contract $contract)
    {
//        $users = User::role('Nhà máy');
//        foreach ($users as $user) {
//            $user->notify(new ManufacturerOrder($contract));
//        }
    }

    /**
     * Handle the contract "updated" event.
     *
     * @param  \App\Contract  $contract
     * @return void
     */
    public function updated(Contract $contract)
    {
        //
    }

    /**
     * Handle the contract "deleted" event.
     *
     * @param  \App\Contract  $contract
     * @return void
     */
    public function deleted(Contract $contract)
    {
        //
    }

    /**
     * Handle the contract "restored" event.
     *
     * @param  \App\Contract  $contract
     * @return void
     */
    public function restored(Contract $contract)
    {
        //
    }

    /**
     * Handle the contract "force deleted" event.
     *
     * @param  \App\Contract  $contract
     * @return void
     */
    public function forceDeleted(Contract $contract)
    {
        //
    }
}
