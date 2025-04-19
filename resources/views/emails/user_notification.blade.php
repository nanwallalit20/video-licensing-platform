@extends('emails.layout.email')
@section('title', 'MovieRites - Registration Email')
@section('content')
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <p style="font-size: 16px; color: #333; margin-bottom: 20px;">
        Dear {{ $user_name }},
    </p>

    <p style="font-size: 16px; color: #333; margin-bottom: 25px;">
        Welcome to MovieRites! We're thrilled to have you on board as a filmmaker. 🚀
    </p>

    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
        <p style="font-size: 16px; color: #333; margin-bottom: 15px;">
            <strong>With MovieRites, you can:</strong>
        </p>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="margin-bottom: 10px; color: #333;">
                ✅ Upload and showcase your films
            </li>
            <li style="margin-bottom: 10px; color: #333;">
                ✅ Distribute your content to top streaming platforms
            </li>
            <li style="margin-bottom: 10px; color: #333;">
                ✅ Track performance and revenue with real-time insights
            </li>
            <li style="margin-bottom: 10px; color: #333;">
                ✅ Retain full rights and control over your work
            </li>
        </ul>
    </div>

    <div style="margin-bottom: 25px;">
        <p style="font-size: 16px; color: #333; margin-bottom: 15px;">
            <strong>Next Steps:</strong>
        </p>
        <ol style="padding-left: 20px;">
            <li style="margin-bottom: 10px; color: #333;">
                <strong>Complete Your Profile</strong> – Add details about yourself and your films.
            </li>
            <li style="margin-bottom: 10px; color: #333;">
                <strong>Upload Your First Film</strong> – Start sharing your masterpiece.
            </li>
            <li style="margin-bottom: 10px; color: #333;">
                <strong>Explore Distribution Opportunities</strong> – Get your content in front of audiences worldwide.
            </li>
        </ol>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <p style="font-size: 16px; color: #333; margin-bottom: 20px;">
            🎬 <strong>Ready to get started?</strong>
        </p>
        <a href="{{ route('login') }}"
           style="display: inline-block;
                  background-color: #007bff;
                  color: white;
                  padding: 12px 25px;
                  text-decoration: none;
                  border-radius: 5px;
                  font-weight: bold;">
            Log in Now
        </a>
    </div>
</div>
@endsection


