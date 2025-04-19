@php
    use App\Enums\RevenuePlanType
@endphp
@extends('layouts.app')
@section('title', 'Revenue Plans')
@section('content')

    @php
        $plan = $title->getRevenuePlan ?? null;
        $planInfo = $title->getRevenuePlan ?? [];
    @endphp
    @if($plan)
        @php
            $planInfo = RevenuePlanType::tryFrom($plan->type)->planInfo();
        @endphp
    @endif

    <div class="container-fluid py-4 card">
        <form method="post"
              action="{{ route('title.revenuePlanSubmit', [request()->route('slug')]) }}"
              class="ajaxForm">
            <div class="card-header">
                <div class="card-title">
                    <h5>Title: {{ $title->name }}</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="plan_details_area mb-4">
                    <h6>Revenue share model chosen:
                        <select name="type" class="title_revenue_plan">
                            <option value="0">Select Plan</option>
                            @foreach(RevenuePlanType::cases() as $planType)
                                <option value="{{ $planType->value }}"
                                        @if($plan && $plan->type == $planType->value) selected @endif>
                                    {{ $planType->displayName() }}
                                </option>
                            @endforeach
                        </select>
                    </h6>
                    @foreach(RevenuePlanType::cases() as $planType)
                        <div class="plan_{{$planType->value}}">
                            @foreach($planType->planInfo() as $planDetails)
                                {{ $planDetails }}<br/>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                <div class="d-flex row row-cols-1">
                    <div class="col">
                        <div class="form-check">
                            <input name="term_and_condition" class="form-check-input" type="checkbox" value="1"
                                   id="checkInput"/>
                            <label class="form-check-label" for="checkInput"> I agree with terms and
                                conditions. </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex flex-column justify-content-end align-items-end">
                    Please check your entry before pressing the submit buton.
                    <button class="btn btn-primary align-right my-0">Submit to generate agreement</button>
                </div>
            </div>
        </form>
        @include('titles.revenue.revenuePlanForm')
    </div>
@endsection
