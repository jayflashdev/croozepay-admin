<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Betsite;
use App\Models\CablePlan;
use App\Models\DatacardPlan;
use App\Models\DataPlan;
use App\Models\Decoder;
use App\Models\Education;
use App\Models\Electricity;
use App\Models\Network;
use App\Models\RechargePlan;
use Illuminate\Http\Request;
use Str;

class PlanController extends Controller
{
    // Airtime
    public function airtime()
    {
        $plans = Network::all();

        return view('admin.plans.airtime.index', compact('plans'));
    }

    public function updateAirtime(Request $request, $id)
    {
        $input = $request->all();
        $data = Network::find($id);
        $data->update($input);

        return back()->withSuccess('Network Updated Successfully');
    }

    public function airtimeStatus($id, $status)
    {
        $network = Network::findorFail($id);
        $network->airtime = $status;
        $network->save();

        return redirect()->back()->withSuccess(__('Network Updated Successfully.'));
    }

    // Data
    public function dataNetwork()
    {
        $plans = Network::whereStatus(1)->get();

        return view('admin.plans.data.network', compact('plans'));
    }

    public function dataPlans($network = null)
    {
        $network ? $plans = DataPlan::whereNetworkId($network)->get() : $plans = DataPlan::all();

        // $plans = DataPlan::all();
        return view('admin.plans.data.index', compact('plans'));
    }

    public function createData()
    {
        $networks = Network::all();

        return view('admin.plans.data.create', compact('networks'));
    }

    public function storeData(Request $request)
    {
        $input = $request->all();
        $data = DataPlan::create($input);

        // return $request;
        return redirect()->route('admin.plan.data')->withSuccess('Plan Added Successfully');
    }

    public function editData($id)
    {
        $plan = DataPlan::find($id);
        $networks = Network::all();

        return view('admin.plans.data.edit', compact('plan', 'networks'));
    }

    public function updateData(Request $request, $id)
    {
        $input = $request->all();
        $data = DataPlan::find($id);
        $data->update($input);

        return redirect()->route('admin.plan.data')->withSuccess('Plan Updated Successfully');
    }

    public function dataStatus($id, $status)
    {
        $network = Network::findorFail($id);
        $network->data = $status;
        $network->save();

        return redirect()->back()->withSuccess(__('Network Updated Successfully.'));
    }

    // Cable TV
    public function decoders()
    {
        $decoders = Decoder::all();

        return view('admin.plans.cable.decoders', compact('decoders'));
    }

    public function decoderStatus($id, $status)
    {
        $decoder = Decoder::findorFail($id);
        $decoder->status = $status;
        $decoder->save();

        return redirect()->back()->withSuccess(__('Decoder Updated Successfully.'));
    }

    public function decoderPlans($decoder)
    {

        $decoder ? $plans = CablePlan::whereDecoderId($decoder)->get() : $plans = CablePlan::all();

        return view('admin.plans.cable.index', compact('plans'));
    }

    public function cablePlans($decoder = null)
    {

        $decoder ? $plans = CablePlan::whereDecoderId($decoder)->get() : $plans = CablePlan::all();

        return view('admin.plans.cable.index', compact('plans'));
    }

    public function createCable()
    {
        $cables = Decoder::all();

        return view('admin.plans.cable.create', compact('cables'));
    }

    public function storeCable(Request $request)
    {
        $input = $request->all();
        $data = CablePlan::create($input);

        return redirect()->route('admin.plan.cable')->withSuccess('Plan Added Successfully');
    }

    public function editCable($id)
    {
        $plan = CablePlan::find($id);
        $cables = Decoder::all();

        return view('admin.plans.cable.edit', compact('plan', 'cables'));
    }

    public function updateCable(Request $request, $id)
    {
        $input = $request->all();
        $data = CablePlan::find($id);
        $data->update($input);

        return redirect()->route('admin.plan.cable')->withSuccess('Plan Updated Successfully');
    }

    public function deleteCable($id)
    {
        $plan = CablePlan::find($id);
        $plan->delete();

        return back()->withSuccess('Plan Deleted Successfully');
    }

    // Betting
    public function betting()
    {
        $plans = Betsite::all();

        return view('admin.plans.bet.index', compact('plans'));
    }

    public function createBet()
    {
        return view('admin.plans.bet.create');
    }

    public function storeBet(Request $request)
    {
        $input = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Str::slug($request->name).'.png';
            $image->move(public_path('uploads/betsites'), $imageName);
            $input['image'] = 'betsites/'.$imageName;
        }
        $plan = new Betsite();
        $plan->create($input);

        return redirect()->route('admin.plan.bet')->withSuccess('Plan Added Successfully');
    }

    public function updateBet(Request $request, $id)
    {
        $plan = Betsite::find($id);
        $input = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Str::slug($request->name).'.png';
            $image->move(public_path('uploads/betsites'), $imageName);
            $input['image'] = 'betsites/'.$imageName;
        }
        $plan->update($input);

        return redirect()->back()->withSuccess('Plan Updated Successfully');
    }

    // Education
    public function education()
    {
        $plans = Education::whereDeleted(0)->get();

        return view('admin.plans.exam.index', compact('plans'));
    }

    public function educationStatus($id, $status)
    {
        $plan = Education::findorFail($id);
        $plan->status = $status;
        $plan->save();

        return redirect()->back()->withSuccess(__('Plan Updated Successfully.'));
    }

    public function updateEducation(Request $request, $id)
    {
        $input = $request->all();
        $plan = Education::findorFail($id);
        $plan->update($input);

        return redirect()->back()->withSuccess(__('Plan updated Successfully.'));
    }

    // Electricity
    public function electricity()
    {
        $plans = Electricity::whereDeleted(0)->get();

        return view('admin.plans.electricity.index', compact('plans'));
    }

    public function electricityStatus($id, $status)
    {
        $plan = Electricity::findorFail($id);
        $plan->status = $status;
        $plan->save();

        return redirect()->back()->withSuccess(__('Plan Updated Successfully.'));
    }

    public function createElectricity()
    {
        return view('admin.plans.electricity.create');
    }

    public function storeElectricity(Request $request)
    {
        $request->validate([
            'fee' => 'required',
            'name' => 'required|string',
        ]);
        $input = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Str::slug($request->name).'.png';
            $image->move(public_path('uploads/power'), $imageName);
            $input['image'] = 'power/'.$imageName;
        }
        Electricity::create($input);

        return to_route('admin.plan.electricity')->withSuccess(__('Electricity Created Successfully.'));
    }

    public function editElectricity($id)
    {
        $plan = Electricity::find($id);

        return view('admin.plans.electricity.edit', compact('plan'));
    }

    public function updateElectricity(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
        $input = $request->all();
        $plan = Electricity::findOrFail($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Str::slug($request->name).'.png';
            $image->move(public_path('uploads/power'), $imageName);
            $input['image'] = 'power/'.$imageName;
        }
        $plan->update($input);

        // return $plan;
        return to_route('admin.plan.electricity')->withSuccess(__('Electricity Updated Successfully.'));
    }

    // Bulksms
    public function bulksms()
    {
        return view('admin.plans.bulksms');
    }

    // Datacard
    public function datacardNetwork()
    {
        $plans = Network::whereStatus(1)->get();

        return view('admin.plans.datacard.network', compact('plans'));
    }

    public function datacardPlans($network = null)
    {
        $network ? $plans = DatacardPlan::whereNetworkId($network)->get() : $plans = DatacardPlan::all();

        // $plans = DatacardPlan::all();
        return view('admin.plans.datacard.index', compact('plans'));
    }

    public function createDatacard()
    {
        $networks = Network::all();

        return view('admin.plans.datacard.create', compact('networks'));
    }

    public function storeDatacard(Request $request)
    {
        $input = $request->all();
        $plan = DatacardPlan::create($input);

        // return $request;
        return redirect()->route('admin.plan.datacard')->withSuccess('Plan Added Successfully');
    }

    public function editDatacard($id)
    {
        $plan = DatacardPlan::find($id);
        $networks = Network::all();

        return view('admin.plans.datacard.edit', compact('plan', 'networks'));
    }

    public function updateDatacard(Request $request, $id)
    {
        $input = $request->all();
        $plan = DatacardPlan::find($id);
        $plan->update($input);

        return redirect()->route('admin.plan.datacard')->withSuccess('Plan Updated Successfully');
    }

    public function datacardStatus($id, $status)
    {
        $network = Network::findorFail($id);
        $network->datacard = $status;
        $network->save();

        return redirect()->back()->withSuccess(__('Network Updated Successfully.'));
    }

    public function deleteDatacard($id)
    {
        $plan = DatacardPlan::find($id);
        $plan->save();

        return redirect()->back()->withSuccess(__('Plan deleted Successfully.'));
    }

    // recharge cards
    public function rechargeNetwork()
    {
        $plans = Network::whereStatus(1)->get();

        return view('admin.plans.recharge.network', compact('plans'));
    }

    public function rechargeStatus($id, $status)
    {
        $network = Network::findorFail($id);
        $network->recharge_card = $status;
        $network->save();

        return redirect()->back()->withSuccess(__('Network Updated Successfully.'));
    }

    public function rechargePlans($network = null)
    {
        $network ? $plans = RechargePlan::whereNetworkId($network)->get() : $plans = RechargePlan::all();

        return view('admin.plans.recharge.index', compact('plans'));
    }

    public function rechargePlanStatus($id, $status)
    {
        $plan = RechargePlan::findorFail($id);
        $plan->status = $status;
        $plan->save();

        return redirect()->back()->withSuccess(__('Plan Updated Successfully.'));
    }

    public function editRecharge($id)
    {
        $plan = RechargePlan::find($id);
        $networks = Network::all();

        return view('admin.plans.recharge.edit', compact('plan', 'networks'));
    }

    public function updateRecharge(Request $request, $id)
    {
        $input = $request->all();
        $plan = RechargePlan::find($id);
        $plan->update($input);

        return redirect()->route('admin.plan.recharge')->withSuccess('Plan Updated Successfully');
    }

    // Airtime Swap
    public function airtimeSwap()
    {
        $plans = Network::whereStatus(1)->get();

        return view('admin.plans.swap', compact('plans'));
    }

    // swap status
    public function swapStatus($id, $status)
    {
        $plan = Network::findorFail($id);
        $plan->swap = $status;
        $plan->save();

        return redirect()->back()->withSuccess(__('Plan Updated Successfully.'));
    }
}
