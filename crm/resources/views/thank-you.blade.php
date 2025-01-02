@include('masteradmin.layouts.headerlink')
<div class="thankyou-bg">
    <div class="container">
        <div class="thankyou-contentbox"> 
            <div class="text-center">
                <img src="public/dist/img/check-icon.png">
            </div>
            <h1 class="thank-you-title">Thank You for Your Subscription!</h1>
            <p class="thank-you-p mb-2">Transaction ID: 277000404400591</p>
            <p class="thank-you-p mb-2">Your payment has been successfully processed, and your subscription is now active.</p>
            <p class="thank-you-p mb-0">You can now log in to access your account and manage your subscription.</p>
            <div class="thankyou-pt-30">
                <h2 class="thank-you-subtitle">Payment Details</h2>
                <p class="thank-you-p mb-0">Amount Paid: 2,065 (₹1,750+₹315[Tax])</p>
            </div>
            <div class="thankyou-pt-30">
                <h2 class="thank-you-subtitle">Check your Email</h2>
                <p class="thank-you-p mb-0">If you didn't receive any mail contact info@gmail.com</p>
            </div>
            <div class="text-center">
                <a href="{{ route('masteradmin.login') }}" class="thank-you-backbtn">Go to Login</a>
            </div>
        </div>
    </div>
</div>

