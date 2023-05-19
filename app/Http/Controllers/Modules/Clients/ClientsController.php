<?php


namespace App\Http\Controllers\Modules\Clients;


use App\Helpers\Helper;
use App\Http\Controllers\DatatableTrait;
use App\Http\Controllers\ModuleController;
use App\Models\VendorService;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClientsController extends ModuleController
{
    use DatatableTrait;

    public function __construct()
    {
        parent::__construct();
        $this->setModuleName('clients');
    }
    public function index()
    {
        $this->injectDatatable();
        return $this->view('index');
    }

    public function add()
    {
        $type = config('auth.user-types');
        return $this->view('add',['types'=>$type]);
    }

    public function show($id)
    {
        $type = config('auth.user-types');
        $data = User::where('id', $id)->first();
        $appointments = Appointment::where();
        return $this->view('show', ['data' => $data, 'types'=>$type]);
    }
    public function edit($id)
    {
        $type = config('auth.user-types');
        $data = User::where('id', $id)->first();
        return $this->view('edit', ['data' => $data, 'types'=>$type]);
    }


    public function create(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required',
            'email' => 'required|string',
            'password' => 'required_with:password_confirmation|same:password_confirmation|between:8,20',
            'password_confirmation' => 'between:8,20',
            'type' => 'required|in:'.implode(",",config('auth.user-types')),

        ])->validate();

        $user = new User();
        //On left field name in DB and on right field name in Form/view
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->type = $request->input('type');
        $user->password = Hash::make($request->input('password'));

        $user->save();
        if (!empty($request->input('saveClose'))) {
            return redirect()->route($this->mRoute('home'))->with('success', 'User Created Successfully!');
        } else {
            return redirect()->route($this->mRoute('add'))->with('success', 'User Created Successfully!');

        }

    }
    public function update(Request $request)
    {

        Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required',
            'email' => 'required|string',
            'password' => 'required_with:password_confirmation|same:password_confirmation|sometimes|nullable|between:8,20',
            'password_confirmation' => 'sometimes|nullable|between:8,20',
            'type' => 'required|in:'.implode(",",config('auth.user-types')),
        ])->validate();

        $cdata = $request->except('_token', '_method');
        unset($cdata['password_confirmation']);

        if(empty($cdata['password'])){
            unset($cdata['password']);
        }else{
            $cdata['password'] = Hash::make($cdata['password']);
        }


        User::where('id', $cdata['id'])->update($cdata);

        return redirect()->route($this->mRoute('home'))->with('success', 'User Updated Successfully!');

    }

    protected function getDataTableColumns(): array
    {
        return [
            ["data" => "id"],
            ["data" => "name"],
            ["data" => "mobile"],
            ["data" => "email"],
            // ["data" => "type"],
            ["data" => "Approve", "onAction" => function($row){
                $statusFun = "approve_user(" . $row["id"] . ",'" . route($this->mRoute('approve'), [$row["id"],'is_admin_approve']) . "','" . csrf_token() . "',this)";
                $checkStatus = "" . ($row['is_admin_approve'] == 1 ? 'checked' : '') . "";
                $btn = '<input switch-button onchange="' . $statusFun . '" ' . $checkStatus . ' type="checkbox" >';
                return $btn;
            }],
            ["data" => "action", "orderable" => false, "searchable" => false, "onAction" => function ($row) {
                //delete_row('.$row["id"].','.route('module.suppliers.delete',[$row["id"]]).')
                $statusFun = "change_status(" . $row["id"] . ",'" . route($this->mRoute('status'), [$row["id"],'status']) . "','" . csrf_token() . "',this)";
                $checkStatus = "" . ($row['status'] == 1 ? 'checked' : '') . "";
                $btn = '<input switch-button onchange="' . $statusFun . '" ' . $checkStatus . ' type="checkbox" >';
                return $btn;
            }],
            ["data" => "action1", "orderable" => false, "searchable" => false, "onAction" => function ($row) {
                //delete_row('.$row["id"].','.route('module.suppliers.delete',[$row["id"]]).')
                $deleteFun = "delete_row(" . $row["id"] . ",'" . route($this->mRoute('delete'), [$row["id"]]) . "','" . csrf_token() . "',this)";
                $btn = '<a href=' . route($this->mRoute('edit'), [$row['id']]) . '><i class="fas fa-edit"></i></a>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href=' . route($this->mRoute('show'), [$row['id']]) . '><i class="fas fa-eye"></i></a>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="javascript:" onclick="' . $deleteFun . '" style="color: red!important;"><i class="fas fa-trash"></i></a>';
                return $btn;
            }],
        ];
    }

    protected function getModuleTable() : string
    {
        return (new User())->getTable();
    }

    protected function getDataTableRows(): array
    {
        return User::where([['type',"CLIENT"]])->orderBy('id', 'DESC')->get()->toArray();
    }
}
