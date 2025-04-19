@extends('emails.layout.email')
@section('title', 'MovieRites - Content Subscription')
@section('content')
<p style="font-size: 16px; color: #333; margin-bottom: 20px;">
    Dear {{ $user_name }},
</p>

<!-- Main Content -->
<div style="background-color: #f8f9fa; padding: 25px; border-radius: 8px; margin-bottom: 25px;">
    <h2 style="color: #333; margin-top: 0; margin-bottom: 20px;">
        Your Buyer Account Request Has Been Approved
    </h2>

    <p style="font-size: 16px; color: #333; line-height: 1.6; margin-bottom: 20px;">
        We are pleased to inform you that your request for access to the MovieRites Content Library has been approved. To complete your subscription and gain immediate access to our extensive collection of premium content, please proceed with the payment process.
    </p>

    <div style="background-color: white; padding: 20px; border-left: 4px solid #333; margin: 25px 0;">
        <p style="font-size: 15px; color: #333; margin: 0;">
            <strong>Important Note:</strong> After completing the payment, you will receive an email with your full access credentials and detailed instructions to begin exploring our content library.
        </p>
    </div>
</div>

<!-- Action Button -->
<div style="text-align: center; margin: 30px 0;">
    <a href="{{ $subscribeLink }}"
       style="display: inline-block;
              background-color: #333;
              color: white;
              padding: 14px 30px;
              text-decoration: none;
              border-radius: 5px;
              font-weight: bold;
              font-size: 16px;">
        Complete Subscription
    </a>
</div>

<!-- Security Notice -->
<div style="margin-bottom: 25px;">
    <p style="font-size: 14px; color: #666; line-height: 1.5;">
        This subscription link is unique to your account and will expire in 72 hours. For security reasons, please do not share this link with others.
    </p>
</div>
<p>If you have any questions or concerns, please don't hesitate to contact our support team.</p>

<p>Thanks,</p>
<p><strong>{{ config('app.name') }}</strong></p>
@endsection
