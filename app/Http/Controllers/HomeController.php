<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Expenses;
use App\Models\Appointment;
use App\Models\UserCourse as UserService;
use App\Models\Service;
use App\Models\User;
use App\Models\VendorService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends AuthenticatedController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $secondBox = $this->secondBox();
        $topBox = $this->topBox();
        $thirdBox = $this->thirdBox();
        $thirdBox['clientsCount'] = User::where('type', 'CLIENT')->count();
        $thirdBox['userssCount'] = User::where('type', 'USER')->count();
        $thirdBox['vendorServiceCount'] = VendorService::count();
        $thirdBox['serviceCount'] = Service::count();
        return view('dashboard', ['topBox' => $topBox,'secondBox'=>$secondBox,'thirdBox'=>$thirdBox]);
        // return view('dashboard', ['thirdBox'=>$thirdBox]);

    }
    private function secondBox(): array
    {
        $dates_month = Helper::rangeMonth();
        $sale_purchase_comp = [];
        $month_data = [];
        foreach ($dates_month as $key => $value){
           $sale_purchase_comp[date('M',strtotime($value))] = [];
           $sale_purchase_comp[date('M',strtotime($value))]['po'] = UserService::whereMonth('created_at',Carbon::parse($value)->month)->count();
           $sale_purchase_comp[date('M',strtotime($value))]['po'] += Appointment::where('appointment_status', '<>', 'reject')->whereMonth('created_at',Carbon::parse($value)->month)->count();
            $month_data['courses'][date('M',strtotime($value))] = UserService::whereMonth('created_at',Carbon::parse($value)->month)->sum('amount');
            $month_data['appointments'][date('M',strtotime($value))] = Appointment::where('appointment_status', '<>', 'reject')->whereMonth('created_at',Carbon::parse($value)->month)->sum('amount');
        }

    //   dd($month_data);

        $purchase_month_pie = UserService::whereMonth('created_at',Carbon::parse(date('Y-m-d'))->month)->sum('amount');
        $purchase_month_pie += Appointment::whereMonth('created_at',Carbon::parse(date('Y-m-d'))->month)->sum('amount');

        $data =
           [
               'purchase_month_pie'=>$purchase_month_pie,
               'sale_purchase_comp'=>json_encode($sale_purchase_comp),
               'month_data' =>json_encode($month_data),
           ];
        return $data;

    }

    private function thirdBox(): array
    {
        $purchaseSumToday = UserService::where('created_at',date('Y-m-d'))->sum('amount');
        $purchaseSumToday += Appointment::where('created_at',date('Y-m-d'))->sum('amount');
        $purchaseSumWeek = UserService::whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('amount');
        $purchaseSumWeek += Appointment::whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('amount');
        $purchaseSumMonth = UserService::whereMonth('created_at',Carbon::parse(date('Y-m-d'))->month)->sum('amount');
        $purchaseSumMonth += Appointment::whereMonth('created_at',Carbon::parse(date('Y-m-d'))->month)->sum('amount');
        $purchaseSumYear = UserService::whereYear('created_at',Carbon::parse(date('Y-m-d'))->year)->sum('amount');
        $purchaseSumYear = Appointment::whereYear('created_at',Carbon::parse(date('Y-m-d'))->year)->sum('amount');

        $previous_week = strtotime("-1 week +1 day");
        $start_week = strtotime("last sunday midnight",$previous_week);
        $end_week = strtotime("next saturday",$start_week);
        $start_week = date("Y-m-d",$start_week);
        $end_week = date("Y-m-d",$end_week);
        $purchaseSumTodayLast = UserService::where('created_at',date('Y-m-d',strtotime("-1 days")))->sum('amount');
        $purchaseSumTodayLast += Appointment::where('created_at',date('Y-m-d',strtotime("-1 days")))->sum('amount');
        $purchaseSumWeekLast = UserService::whereBetween('created_at', [$start_week, $end_week])->sum('amount');
        $purchaseSumWeekLast += Appointment::whereBetween('created_at', [$start_week, $end_week])->sum('amount');
        $purchaseSumMonthLast = UserService::whereMonth('created_at',Carbon::now()->subMonth()->month)->sum('amount');
        $purchaseSumMonthLast += Appointment::whereMonth('created_at',Carbon::now()->subMonth()->month)->sum('amount');
        $purchaseSumYearLast = UserService::whereYear('created_at',date('Y', strtotime('-1 year')))->sum('amount');
        $purchaseSumYearLast += Appointment::whereYear('created_at',date('Y', strtotime('-1 year')))->sum('amount');

        $data =
            [
                'purchaseSumToday'=>$purchaseSumToday,
                'purchaseSumWeek'=>$purchaseSumWeek,
                'purchaseSumMonth'=>$purchaseSumMonth,
                'purchaseSumYear'=>$purchaseSumYear,
                'purchaseSumTodayLast'=>$purchaseSumTodayLast,
                'purchaseSumWeekLast'=>$purchaseSumWeekLast,
                'purchaseSumMonthLast'=>$purchaseSumMonthLast,
                'purchaseSumYearLast'=>$purchaseSumYearLast,
                'todayComp'=>($purchaseSumToday > $purchaseSumTodayLast ? 1 : 0),
                'weekComp'=>($purchaseSumWeek > $purchaseSumWeekLast ? 1 : 0),
                'monthComp'=>($purchaseSumMonth > $purchaseSumMonthLast ? 1 : 0),
                'yearComp'=>($purchaseSumYear > $purchaseSumYearLast ? 1 : 0),
            ];
            // dd($data);
        return $data;
    }

    private function topBox(): array
    {
        $purchaseSum = new Appointment;
        $purchaseSum1 = new UserService;

        $sums = array();
        foreach($purchaseSum->get() as $sum)
        {
            $sums[]['amount'] = $sum['amount'];
        }
        foreach($purchaseSum1->get() as $sum)
        {
            $sums[]['amount'] = $sum['amount'];
        }
        // $purchaseSum1 = UserService::get(['amount'])->toArray();
        // $purchaseSum1[] = json_encode($purchaseSum->get(['amount'])->toArray());
        $purchaseSumGraph = json_encode($sums);

//        dd($saleCountGraph);
        $purchase = $purchaseSum->sum('amount');
        $purchase += $purchaseSum1->sum('amount');
        $data = [
            'purchaseSum' => $purchase,
            'grossProfit' => $purchase,
            'netProfit' => ($purchase),
            'purchaseSumGraph' => $purchaseSumGraph,
        ];

        return $data;
    }

}
