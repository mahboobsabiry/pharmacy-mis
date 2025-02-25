<?php

namespace App\Http\Controllers\Admin\Examination;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreferentialTariffRequest;
use App\Models\Examination\Harvest;
use App\Models\Examination\HarvestItem;
use App\Models\Examination\PreferentialTariff;
use App\Models\Examination\PTItems;
use App\Models\Office\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreferentialTariffController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:examination_pt_view', ['only' => ['index', 'show']]);
        $this->middleware('permission:examination_pt_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:examination_pt_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:examination_pt_delete', ['only' => ['destroy']]);
    }

    /**
     * Index Page to retrieve all of preferential_tariffs
     */
    public function index()
    {
        $tariffs = PreferentialTariff::all()->where('status', 0);

        return view('admin.examination.preferential_tariffs.index', compact('tariffs'));
    }

    /**
     * Create Page
     */
    public function create()
    {
        $companies = Company::all()->where('status', 1);
        return view('admin.examination.preferential_tariffs.create', compact('companies'));
    }

    /**
     * Store DATA
     */
    public function store(PreferentialTariffRequest $request)
    {
        $tariff               = new PreferentialTariff();
        $tariff->user_id      = Auth::user()->id;
        $tariff->company_id   = $request->company_id;
        $tariff->doc_number   = $request->doc_number;
        $tariff->doc_date     = $request->doc_date;
        $tariff->start_date   = $request->start_date;
        $tariff->end_date     = $request->end_date;
        $tariff->info         = $request->info;
        $tariff->save();

        $data = $request->all();
        // Add Product Attributes
        foreach ($data['good_name'] as $key => $value) {
            if (!empty($value)) {
                // Add Attribute
                $item = new PTItems();
                $item->pt_id        = $tariff->id;
                $item->good_name    = $value;
                $item->hs_code      = $data['hs_code'][$key];
                $item->total_packages   = $data['total_packages'][$key];
                $item->weight           = $data['weight'][$key];
                $item->save();
            }
        }

        //  Has File && Save Avatar Image
        if ($request->hasFile('photo')) {
            $avatar = $request->file('photo');
            $fileName = 'pt-' . time() . rand(111, 99999) . '.' . $avatar->getClientOriginalExtension();
            $tariff->storeImage($avatar->storeAs('examination/preferential_tariffs', $fileName, 'public'));
        }

        return redirect()->route('admin.examination.preferential_tariffs.index')->with([
            'message'   => 'جایداد ثبت گردید!',
            'alertType' => 'success'
        ]);
    }

    /**
     * Show details of record
     */
    public function show($id)
    {
        $tariff = PreferentialTariff::with('pt_items')->findOrFail($id);
        return view('admin.examination.preferential_tariffs.show', compact('tariff'));
    }

    /**
     * Edit details of record
     */
    public function edit($id)
    {
        $tariff = PreferentialTariff::with('pt_items')->findOrFail($id);
        $companies = Company::all()->where('status', 1);
        return view('admin.examination.preferential_tariffs.edit', compact('tariff', 'companies'));
    }

    /**
     * Update DATA
     */
    public function update(Request $request, $id)
    {
        $tariff = PreferentialTariff::with('pt_items')->findOrFail($id);
        $tariff->user_id      = Auth::user()->id;
        $tariff->company_id   = $request->company_id;
        $tariff->doc_number   = $request->doc_number;
        $tariff->doc_date     = $request->doc_date;
        $tariff->start_date   = $request->start_date;
        $tariff->end_date     = $request->end_date;
        $tariff->info         = $tariff->info . '<br>' . $request->info;
        $tariff->save();

        //  Has File && Save Avatar Image
        if ($request->hasFile('photo')) {
            $avatar = $request->file('photo');
            $fileName = 'property-' . time() . rand(111, 99999) . '.' . $avatar->getClientOriginalExtension();
            $tariff->updateImage($avatar->storeAs('examination/preferential_tariffs', $fileName, 'public'));
        }

        return redirect()->route('admin.examination.preferential_tariffs.index')->with([
            'message'   => 'موفقانه بروزرسانی گردید!',
            'alertType' => 'success'
        ]);
    }

    /**
     * Delete DATA
     */
    public function destroy($id)
    {
        $tariff = PreferentialTariff::with('pt_items')->findOrFail($id);
        $tariff->delete();

        return redirect()->route('admin.examination.preferential_tariffs.index')->with([
            'message'   => 'موفقانه حذف گردید!',
            'alertType' => 'success'
        ]);
    }

    // Renewal
    public function renewal(Request $request, $id)
    {
        $tariff = PreferentialTariff::with('pt_items')->findOrFail($id);

        if ($request->isMethod('POST')) {
            // Validation
            $request->validate([
                'end_date'      => 'required',
                'doc_number'    => 'required',
                'doc_date'      => 'required'
            ]);

            $tariff->update([
                'end_date'  => $request->end_date,
                'info'      => $tariff->info . '<br> الی تاریخ ' . $request->end_date . ' بر اساس مکتوب نمبر ' . $request->doc_number . ' مورخ ' . $request->doc_date . ' تمدید گردید.'
            ]);

            return redirect()->route('admin.examination.preferential_tariffs.show', $tariff->id)->with([
                'message'   => 'موفقانه تمدید گردید.',
                'alertType' => 'success'
            ]);
        }

        return view('admin.examination.preferential_tariffs.renewal', compact('tariff'));
    }

    // Harvesting PT
    public function harvesting_pts()
    {
        $tariffs = PreferentialTariff::all()->where('status', 1);

        return view('admin.examination.preferential_tariffs.harvesting_pts', compact('tariffs'));
    }

    // Harvested PT
    public function harvested_pts()
    {
        $tariffs = PreferentialTariff::all()->where('status', 2);

        return view('admin.examination.preferential_tariffs.harvested_pts', compact('tariffs'));
    }

    // Add New Item
    public function new_item(Request $request, $id)
    {
        $tariff = PreferentialTariff::findOrFail($id);

        $request->validate([
            'good_name' => 'required',
            'hs_code'   => 'required|numeric',
            'total_packages'    => 'required|numeric',
            'weight'            => 'required|numeric'
        ]);

        $item = new PTItems();
        $item->pt_id    = $tariff->id;
        $item->good_name    = $request->good_name;
        $item->hs_code    = $request->hs_code;
        $item->total_packages    = $request->total_packages;
        $item->weight    = $request->weight;
        $item->save();

        return back()->with([
            'message'   => 'قلم جدید ثبت گردید.',
            'alertType' => 'success'
        ]);
    }

    // Harvest
    public function harvest(Request $request, $id)
    {
        $tariff = PreferentialTariff::with('pt_items')->findOrFail($id);

        if ($tariff->harvests()) {
            if ($tariff->pt_items->sum('weight') <= $tariff->harvests->sum('weight')) {
                return back()->with([
                    'message'   => 'هیچ محموله‌ای برای برداشت وجود ندارد.',
                    'alertType' => 'danger'
                ]);
            }
        }

        if ($request->isMethod('POST')) {
            $data = $request->all();

            $harvest = new Harvest();
            $harvest->pt_id = $tariff->id;
            $harvest->code  = Harvest::code();
            $harvest->info  = $tariff->info;
            $harvest->save();

            $request->validate([
                'good_name[]'       => 'required',
                'hs_code[]'         => 'required|numeric',
                'total_packages[]'  => 'required|numeric',
                'weight[]'          => 'required|numeric',
            ]);
            foreach ($data['good_name'] as $key=>$item) {
                $h_item = new HarvestItem();
                $h_item->harvest_id = $harvest->id;
                $h_item->good_name  = $data['good_name'][$key];
                $h_item->hs_code    = $data['hs_code'][$key];
                $h_item->total_packages = $data['total_packages'][$key];
                $h_item->weight         = $data['weight'][$key];
                $h_item->save();
            }

            return redirect()->route('admin.examination.preferential_tariffs.show', $tariff->id)->with([
                'message'   => 'برداشت انجام شد!',
                'alertType' => 'danger'
            ]);
        }

        return view('admin.examination.preferential_tariffs.harvest', compact('tariff'));
    }

    // Select Item
    public function select_item(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();

            // Query the database to get the relevant data
            $item = PTItems::where('good_name', $data['item_good_name'])->first();

            if ($item) {
                // Assuming 'employee_name' is the field you want to retrieve
                $pt_good_name = $item->good_name;
                $pt_hs_code = $item->hs_code;
                $pt_total_packages = $item->total_packages;
                $pt_weight = $item->weight;

                // Return the data as a JSON response
                return response()->json([
                    'pt_good_name' => $pt_good_name,
                    'pt_hs_code' => $pt_hs_code,
                    'pt_total_packages' => $pt_total_packages,
                    'pt_weight' => $pt_weight
                ]);
            } else {
                // Handle the case when the aircraft ID is not found
                return response()->json(['error' => 'Aircraft not found'], 404);
            }
        }
    }
}
