<?php

namespace App\Modules\Dashboard\Livewire;

use App\Modules\Accounts\Models\Account;
use App\Modules\Payments\Models\RecurringPayment;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{

    public $balance;
    public $account = 1;

   # public $days = [];

    public function render()
    {
        return view('dashboard::dashboard');;
    }



    public function getDaysProperty(){

        $days = [];

        $account = Account::find($this->account);

        $this->balance = $account->balance;

       $start = Carbon::now();

       $i = 0;

       $inOnNextWeekday = 0;
       $outOnNextWeekday = 0;

       while($i < 730){

           $date = (clone $start)->addDays($i);

           $payments = RecurringPayment::where('accounts_id', $this->account)
                ->where(
               function($payment) use ($date){
                   $payment->where(
                       function($monthly) use ($date){
                           $monthly->where('frequency', 'm');
                           $monthly->where('frequency_on', $date->format('d'));
                       }
                   );
                   $payment->orWhere(
                       function($monthly) use ($date){
                           $monthly->where('frequency', 'w');
                           $monthly->where('frequency_on', $date->dayOfWeek);
                       }
                   );
               }
           );
           $in = (clone $payments)->where('type', 'in')->sum('value');
           $out = (clone $payments)->where('type', 'out')->sum('value');

           $inDesc = (clone $payments)->where('type', 'in')->pluck('name');
           $outDesc = (clone $payments)->where('type', 'out')->pluck('name');


           $this->balance += $in;
           $this->balance -= $out;





           $days[] = [
               'date' => $date->format('d/m/Y'),
               'balance' => $this->balance,
               'in' => $in,
               'inDesc' => implode(', ', $inDesc->toArray()),
               'out' => $out,
               'outDesc' => implode(', ', $outDesc->toArray()),
           ];
           $i++;
       }

       return $days;
    }

    public function getAccountsProperty(){
        return Account::get();
    }

    public function selectAccount($id){
        $this->account = $id;
    }
}
