<?php


namespace App\Http\Controllers\Modules\Reports;


use App\Helpers\Helper;
use App\Http\Controllers\DatatableTrait;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\SubModuleTrait;
use App\Models\PurchaseOrders;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderReport extends ModuleController
{
    use DatatableTrait;
    use SubModuleTrait{
        SubModuleTrait::__construct as subModuleConstructor;
    }

    public $record = [];

    public function __construct()
    {
        parent::__construct();
        $this->subModuleConstructor();
        $this->setModuleName("reports");
    }

    public function getStatus(): array
    {
        $status = ['1' => 'Confirm', '2' => 'Pending'];
        return $status;
    }

    public function index()
    {
        $status = $this->getStatus();
        $this->injectDatatable();
        return $this->view('purchase_order_report', ['status' => $status]);
    }

    public function search(Request $request)
    {
        $status = $this->getStatus();
        $this->injectDatatable();
        $params = \request()->all();
        $base = PurchaseOrders::where('is_archive', 0);
        $query = $this->poQuery($base, $params);

        $sumTotal = $query->sum('grand_total');
        $sumTotalGraph = json_encode($query->get(['grand_total'])->toArray());
        $sumItems = $query->sum('count');
        $sumCountGraph = json_encode($query->get(['count'])->toArray());


        $queryConfirmStatus = $this->poQuery($base, $params, 1);
        $cItem = $queryConfirmStatus->count();
        $queryPendingStatus = $this->poQuery($base, $params, 2);
        $pItem = $queryPendingStatus->count();


        $data = [
            'sumTotalGraph' => $sumTotalGraph,
            'sumCountGraph' => $sumCountGraph,
            'status' => $status,
            'grand_total' => $sumTotal,
            'count' => $sumItems,
            'cItem' => $cItem,
            'pItem' => $pItem
        ];

        return $this->view('purchase_order_report', $data);

    }

    public function poQuery($query, $params, $status = null)
    {
        if (!empty($params['from_date']) && !empty($params['to_date'])) {
            $from = date('Y-m-d', strtotime($params['from_date']));
            $to = date('Y-m-d', strtotime($params['to_date']));
            $query = $query->whereBetween('order_date', [$from, $to]);
        } elseif (!empty($params['to_date'])) {
            $to = date('Y-m-d', strtotime($params['to_date']));
            $query = $query->where('order_date', '<=', $to);

        }

        if (!empty($status)) {
            $query = $query->where('status', $status);
        } elseif (!empty($params['status'])) {
            $query = $query->where('status', $params['status']);
        }
        if (!empty($params['user_id'])) {
            $query = $query->where('user_id', $params['supplier_id']);
        }
        return $query;
    }

    protected function getDataTableRows(): array
    {
        $params = \request()->all();
        if (!empty($params['_token'])) {
            $base = PurchaseOrders::with('supplier')->where('is_archive', 0)->orderBy('id', 'DESC');
            $query = $this->poQuery($base, $params);
            return $query->get()->toArray();
        } else {
            return [];
        }
    }

    protected function getDataTableColumns(): array
    {
        return [
            ["data" => "id"],
            ["data" => "order_date", "onAction" => function ($row) {
                return date('m/d/Y', strtotime($row['order_date']));
            }],
            ["data" => "grand_total","onAction"=>function($row){
                return Helper::price($row['grand_total']);
            }],
            ["data" => "count"],

            ["data" => "action", "orderable" => false, "searchable" => false, "onAction" => function ($row) {
                $html = '';
                if ($row['status'] == 1) {
                    $html = "<i style='color: #0d9448' class='fas fa-check'></i> Confirmed";
                } elseif ($row['status'] == 2) {
                    $html = "<i style='color: #edb100' class='fas fa-bell'></i> Pending";
                }
                return $html;
            }]
        ];
    }

    protected function getModuleTable(): string
    {
        return (new PurchaseOrders())->getTable();
    }
}
