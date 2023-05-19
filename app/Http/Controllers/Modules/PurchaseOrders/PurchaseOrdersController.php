<?php


namespace App\Http\Controllers\Modules\PurchaseOrders;


use App\Helpers\Helper;
use App\Http\Controllers\DatatableTrait;
use App\Http\Controllers\ModuleController;
use App\Models\Inventory;
use App\Models\Items;
use App\Models\Ledger;
use App\Models\PurchaseOrderItems;
use App\Models\PurchaseOrders;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseOrdersController extends ModuleController
{

    use DatatableTrait;

    public function index()
    {
        $this->injectDatatable();
        return $this->view('index');
    }

    public function getItems(): array
    {
        return Items::where('user_id',Auth::user()->id)->get()->toArray();
    }

    public function getStatus(): array
    {
        $status = ['1' => 'Confirm', '2' => 'Pending'];
        return $status;
    }

    public function add()
    {
        $items = $this->getItems();
        $status = $this->getStatus();
        return $this->view('add', ['items' => $items, 'status' => $status]);
    }

    public function viewOrder($id)
    {

        $data = PurchaseOrders::with('supplier')->where('id', $id)->first();
        $orders = PurchaseOrderItems::with('item')->where('purchase_order_id', $data['id']);
        return $this->view('viewOrder', ['data' => $data, 'orders' => $orders->get()->toArray()]);
    }
    public function invoice($id)
    {

        $data = PurchaseOrders::with('supplier')->where('id', $id)->first();
        $orders = PurchaseOrderItems::with('item')->where('purchase_order_id', $data['id']);
        $company_info = Auth::user()->toArray();

//        \PDF::saveFromView($this->view('invoice', ['data' => $data, 'orders' => $orders->get()->toArray()]), $id." - ".date('d-m-Y').'.pdf');
        return $this->view('invoice', ['data' => $data, 'orders' => $orders->get()->toArray(),'company_info'=>$company_info]);
    }

    public function status(Request $request, $id, $field = "status"): array
    {
        if(!empty($request->toArray()['description'])){
            DB::table($this->getModuleTable())->where('id', $id)->update(['description' => $request->toArray()['description']]);
        }
        $data = DB::table($this->getModuleTable())->where('id', $id)->get()->first();
        if (!empty($data)) {

            $data = (array)$data;
            DB::table($this->getModuleTable())->where('id', $id)->update([$field => ($data[$field] == 1 ? 2 : 1)]);

            $field = $data[$field] == 1 ? 2 : 1;
            $items = PurchaseOrderItems::where('purchase_order_id',$id)->get()->toArray();
        }
        return ['success' => 1];
    }


    public function create(Request $request)
    {
        Validator::make($request->all(), [
            'order_date' => 'required',
            'status' => 'required',
        ], [], [
            'order_date' => 'Order Date',
            'status' => 'Status',

        ])->validate();
        $items = json_decode($request->input('po'), true);
        if (empty($items)) {
            return redirect()->back()->withInput()->with('error', 'Please Select Item!');
        }
        $purchaseOrder = new PurchaseOrders();
        $purchaseOrder->order_date = date('Y-m-d', strtotime($request->input('order_date')));
        $purchaseOrder->description = $request->input('description');
        $purchaseOrder->status = $request->input('status');
        $purchaseOrder->grand_total = $request->input('grandTotal');
        $purchaseOrder->count = $request->input('count');
        $purchaseOrder->user_id = Auth::user()->id;
        $purchaseOrder->save();
        $pId = $purchaseOrder->id;
        $error = 0;
        if (!empty($request->input('po'))) {

            foreach ($items as $item) {
                if (!empty($item['item'])) {
                    $itemId = $item['item']["code"];
                    $cost = $item['cost'];
                    $qty = $item['qty']??1;
                    $total = $item['total'];
                    $purchaseOrderItems = new PurchaseOrderItems();
                    $purchaseOrderItems->purchase_order_id = $pId;
                    $purchaseOrderItems->item_id = $itemId;
                    $purchaseOrderItems->quantity = $qty;
                    $purchaseOrderItems->unit_cost = $cost;
                    $purchaseOrderItems->total = $total;
                    $purchaseOrderItems->save();
                    $poiId = $purchaseOrderItems->id;

                    /*if ($purchaseOrder->status == 1) {
                        $itemsTable = Items::where('id', $itemId)->first();
                        $itemsTable->last_updated_stock += $qty;
                        $itemsTable->last_updated_cost = $cost;
                        $itemsTable->last_updated_price = $price;
                        $itemsTable->save();
                    }*/

                } else {
                    $error++;
                }
            }
            if ($error == sizeof($items)) {
                $purchaseOrder->refresh()->delete();
                return redirect()->back()->withInput()->with('error', 'Please Select Item!');
            }
            if (!empty($request->input('saveClose'))) {
                return redirect()->route($this->mRoute('home'))->with('success', 'Purchase Order Added Successfully!');
            } else {
                return redirect()->route($this->mRoute('add'))->with('success', 'Purchase Order Added Successfully!');

            }

        } else {
            return redirect()->back()->withInput()->with('error', 'Please Select Item!');

        }


    }

    protected function getDataTableRows(): array
    {

        return PurchaseOrders::with('supplier')->where('is_archive', 0)->orderBy('id', 'DESC')->get()->toArray();
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

            }],
            ["data" => "action1", "orderable" => false, "searchable" => false, "onAction" => function ($row) {
                $deleteFun = "delete_row(" . $row["id"] . ",'" . route($this->mRoute('delete'), [$row["id"]]) . "','" . csrf_token() . "',this)";
                $statusFun = "orderStatus(" . $row["id"] . ",'" . route($this->mRoute('status'), [$row["id"], 'status']) . "','" . csrf_token() . "',this)";
                $checkStatus = "" . ($row['status'] == 1 ? 'd-none' : '') . "";
                $html = '
                    <div class="dropdown">
                      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                        <a class="dropdown-item" href="' . route($this->mRoute('viewOrder'), [$row['id']]) . '"><i class="fas fa-eye"></i>&nbsp;&nbsp;View</a>
                        <a class="dropdown-item ' . $checkStatus . '" href="#" onclick="' . $deleteFun . '"><i class="fas fa-trash"></i>&nbsp;&nbsp;Delete</a>
                      </div>
                    </div>
                ';
                return $html;
            }],
        ];
    }

    protected function getModuleTable(): string
    {
        return (new PurchaseOrders())->getTable();
    }


}
