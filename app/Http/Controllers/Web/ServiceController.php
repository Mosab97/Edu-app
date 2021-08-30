<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Advantage;
use App\Models\Blog;
use App\Models\ContactUs;
use App\Models\CustomerReviews;
use App\Models\Faq;
use App\Models\Manager;
use App\Models\Order;
use App\Models\Package;
use App\Models\Service;
use App\Models\SpecialService;
use App\Models\Statistic;
use App\Notifications\ContactUsNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;

class ServiceController extends Controller
{
    private $view = 'website.service.';

    public function view_special_service_form(Request $request)
    {
        $serviceValidationRules["project_title"] = 'required|max:255';
        $serviceValidationRules["expected_budget"] = 'required|numeric';
        $serviceValidationRules["expected_delivery_time"] = 'required|numeric';
        $serviceValidationRules["service_type"] = 'required|numeric';
        $serviceValidationRules["project_details"] = 'required|max:300';
        $service_validator = JsValidator::make($serviceValidationRules, $this->validationMessages);

        return view($this->view . 'view_special_service_form', compact('service_validator'));
    }

    public function post_special_service_form(Request $request)
    {
//        dd(checkRequestIsWorkingOrNot());
        $serviceValidationRules["project_title"] = 'required|max:255';
        $serviceValidationRules["expected_budget"] = 'required|numeric';
        $serviceValidationRules["expected_delivery_time"] = 'required|numeric';
        $serviceValidationRules["service_type"] = 'required|numeric';
        $serviceValidationRules["project_details"] = 'required|max:300';
        $request->validate($serviceValidationRules);

        $data = $request->except(['_token']);
        if ($request->hasFile('other_help_attachments')) $data['other_help_attachments'] = $this->uploadImage($request->other_help_attachments, 'other_help_attachments');
        $data['user_id'] = user('web')->id;
//dd($data,checkRequestIsWorkingOrNot());
        SpecialService::create($data);
        return redirect()->back()->with('m-class', 'success')->with('message', w('Thanks the specialist for contacting you for a period of no more than 24 hours.'));

    }

    private function getRules($service_id)
    {
        $serviceValidationRules = [];
        switch ($service_id) {
            case Service::f3Types['Help in choosing the accounting program']:
                $serviceValidationRules['project_activity'] = ['required', 'min:3', 'max:255'];
                $serviceValidationRules['company_offers'] = ['required', 'numeric'];
                $serviceValidationRules['profile'] = ['nullable', 'url'];
                $serviceValidationRules['employee_number'] = ['required', 'numeric'];
                $serviceValidationRules['size_of_the_estimated_revenue_of_the_project'] = ['required', 'numeric'];
                $serviceValidationRules['size_of_the_estimated_expenses_of_the_project'] = ['required', 'numeric'];
                $serviceValidationRules['monthly_budget_allocated_to_the_accounting_program'] = ['required', 'numeric'];
                $serviceValidationRules['lang'] = ['required', 'numeric'];
                $serviceValidationRules['details'] = ['required', 'min:3', 'max:255'];
                $serviceValidationRules['describe_your_need_for_the_program'] = ['required', 'min:3', 'max:255'];
                break;
            case Service::f3Types['Establishing the chart of accounts']:
                $serviceValidationRules['work_activity'] = ['required', 'min:3', 'max:255'];
                $serviceValidationRules['current_accounting_program'] = ['required', 'min:3', 'max:255'];
                $serviceValidationRules['mechanism_of_action'] = ['required', 'numeric'];
                $serviceValidationRules['service_description'] = ['required', 'min:3', 'max:255'];
                break;
            case Service::f3Types['Training service']:
                $serviceValidationRules['training_title'] = ['required', 'min:3', 'max:255'];
                $serviceValidationRules['mechanism_of_action'] = ['required', 'numeric'];
                $serviceValidationRules['number_people'] = ['required', 'numeric'];
                $serviceValidationRules['number_of_hours_required'] = ['required', 'numeric'];
                $serviceValidationRules['training_requirements'] = ['required', 'min:3', 'max:255'];
                break;
            default:
                $v = 'def_service_form';
                break;
        }
        return $serviceValidationRules;

    }

    public function view_service_form(Request $request, $id)
    {
        $v = 'def_service_form';
        $service = null;

        $serviceValidationRules = [];
        switch ($id) {
            case Service::f3Types['Help in choosing the accounting program']:
                $v = 'view_Help_in_choosing_the_accounting_program';
                $service = Service::findOrFail(Service::f3Types['Help in choosing the accounting program']);
                break;
            case Service::f3Types['Establishing the chart of accounts']:
                $v = 'view_establishing_the_chart_of_accounts';
                $service = Service::findOrFail(Service::f3Types['Establishing the chart of accounts']);
                break;
            case Service::f3Types['Training service']:
                $v = 'view_training_service';
                $service = Service::findOrFail(Service::f3Types['Training service']);
                break;
            default:
                $v = 'def_service_form';
                break;
        }
        $service_validator = JsValidator::make($this->getRules($id), $this->validationMessages);

        return view($this->view . $v, compact('service', 'service_validator'));
    }

    public function view_service_details(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        return view($this->view . 'view_service_details', compact('service'));
    }

    public function post_Help_in_choosing_the_accounting_program(Request $request)
    {
        $request->validate($this->getRules($request->service_id));
        $user = user('web');
//        dd(checkRequestIsWorkingOrNot());
        $data = $request->except(['_token', 'other_help_attachments']);
        $service = Service::findOrFail($request->service_id);
        if (isset($user) && $user->orders()->where('service_id', $service->id)->count() > 0) return redirect()->back()->with('m-class', 'success')->with('message', w('Can not Register more than once'));//abort(403);
//dd(isset($user) &&$user->orders()->where('service_id', $service->id)->count() > 0,checkRequestIsWorkingOrNot());
        $data['user_id'] = user('web')->id;
        Order::create($data);
        return redirect()->back()->with('m-class', 'success')->with('message', w('Bought Successfully'));

    }

}
