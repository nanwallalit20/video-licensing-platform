@extends('emails.layout.email')
@section('title', 'MovieRites - Title Status Update')
@section('content')
@php
    use App\Enums\TitleStatus;
@endphp
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <!-- Greeting -->
    <p style="font-size: 16px; color: #333; margin-bottom: 20px;">
        Dear {{ $title->getUser->name }},
    </p>

    <!-- Status Update -->
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
        <h2 style="color: #333; margin-top: 0; margin-bottom: 15px;">
            Status Update for "{{ $title->name }}"
        </h2>
        <p style="font-size: 16px; color: #333; margin-bottom: 15px;">
            <strong>Current Status:</strong>
            <span>{{ $title->status->displayName() }}</span>
        </p>
        @if($head_msg)
            <div style="background-color: white; padding: 15px; border-left: 4px solid #007bff; margin-top: 15px;">
                <p style="font-size: 15px; color: #333; margin: 0;">
                    <strong>Feedback:</strong><br>
                    {{ $head_msg }}
                </p>
            </div>
        @endif
    </div>

    <!-- Next Steps Section -->
    <div style="margin-bottom: 25px;">
        <h3 style="color: #333; margin-bottom: 15px;">Next Steps</h3>
        @if($title->status->value == TitleStatus::Accepted->value)
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 10px; color: #333;">
                    • Your title has successfully passed our review process
                </li>
                <li style="margin-bottom: 10px; color: #333;">
                    • Please proceed to select your preferred revenue plan through your dashboard
                </li>
                <li style="margin-bottom: 10px; color: #333;">
                    • Access detailed analytics and performance metrics via your account
                </li>
            </ul>
        @elseif($title->status->value == TitleStatus::Rejected->value)
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 10px; color: #333;">
                    • Please review the feedback provided in the review comments section
                </li>
                <li style="margin-bottom: 10px; color: #333;">
                    • Update your submission according to the review guidelines
                </li>
                <li style="margin-bottom: 10px; color: #333;">
                    • Submit your revised title for another review
                </li>
            </ul>
        @endif
    </div>
    <p>If you have any questions or concerns, please don't hesitate to contact our support team.</p>

    <p>Thanks,</p>
    <p><strong>{{ config('app.name') }}</strong></p>
</div>
@endsection
