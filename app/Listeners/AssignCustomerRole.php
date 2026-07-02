<?php

namespace App\Listeners;

use App\Events\CustomerRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Permission\Models\Role;

class AssignCustomerRole
{
  /**
   * Create the event listener.
   */
  public function __construct()
  {
    //
  }

  /**
   * Handle the event.
   */
  public function handle(CustomerRegistered $event): void
  {
    $this->assignCustomerRole($event->user);
  }

  public function assignCustomerRole($user)
  {
    $role = Role::findByName('customer', 'api');
    $user->assignRole($role);
  }
}
