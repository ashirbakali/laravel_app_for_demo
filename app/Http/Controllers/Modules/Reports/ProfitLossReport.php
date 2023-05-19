<?php


namespace App\Http\Controllers\Modules\Reports;

use App\Http\Controllers\ModuleController;
use App\Http\Controllers\SubModuleTrait;
use App\Models\Expenses;
use App\Models\PurchaseOrders;
use App\Models\SaleOrders;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfitLossReport extends ModuleController
{

    use SubModuleTrait{
        SubModuleTrait::__construct as subModuleConstructor;
    }

    public function __construct()
    {
        parent::__construct();
        $this->subModuleConstructor();
        $this->setModuleName("reports");
    }

    public function index()
    {
        return $this->view('profit_loss_report');
    }

    public function search(Request $request)
    {
        Validator::make($request->all(), [
            'from_date' => 'required',
            'to_date' => 'required',
        ], [], [
            'from_date' => 'From Date',
            'to_date' => 'to Date',

        ])->validate();

        $params = \request()->all();
        $data_all = [];
        $total_all = [
            "costTotal"=>0,
            "grossProfitTotal"=>0,
            "netProfitTotal"=>0,
        ];
        $from = date('Y-m-d',strtotime($params['from_date']));
        $to = date('Y-m-d',strtotime($params['to_date']));
        $months = CarbonPeriod::create($from, '1 month', $to);
        if(sizeof($months) > 5){
            return redirect()->back()->withInput()->with('error', 'you can only select 6 Months!');

        }
        foreach ($months as $key => $dt) {
            /*          echo Carbon::parse($dt)->month.'<br>';*/
            /*     echo $dt.'<br>';
                 echo $dt->format("Y-m").'<br>';*/
            $data_all[$key]['cost'] = PurchaseOrders::where('is_archive', 0)->where('status', 1)->whereYear('order_date', Carbon::parse($dt)->year)->whereMonth('order_date', Carbon::parse($dt)->month)->sum('grand_total');
            $data_all[$key]['gross_profit'] =   $data_all[$key]['cost'];
            $data_all[$key]['net_profit'] =      $data_all[$key]['gross_profit'];
            $total_all['grossProfitTotal'] +=$data_all[$key]['gross_profit'];
            $total_all['netProfitTotal'] += $data_all[$key]['net_profit'];
            $total_all['costTotal'] +=$data_all[$key]['cost'];
        }



        $data = [
            'months' => $months,
            'data_all' => $data_all,
            'total_all' => $total_all,
        ];

        return $this->view('profit_loss_report', $data);

    }

    protected function getModuleTable(): string
    {
        return  "";
    }
}
