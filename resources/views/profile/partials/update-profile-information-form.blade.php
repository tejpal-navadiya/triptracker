
<div class="card-header">
    <h3 class="card-title">{{ __('Personal Information') }}</h3>
</div>
<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>
<form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
@csrf
@method('patch')
<div class="card-body2">
    <div class="row pad-5">
        <div class="col-md-6">
            <div class="form-group">
                <x-input-label for="name" :value="__('First Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                    required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif

            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Profile Image</label>
                <div>
                    <input type="file" name="image" accept="image/*" class="add_btn fileinput-button">
                    @if (Auth::user()->image)
                        <a href="{{ url(env('IMAGE_URL').'superadmin/profile_image/' . Auth::user()->image) }} " target="_blank"><div title="{{ Auth::user()->image }}" class="ptm pbm">{{ Auth::user()->image }}</div></a>
                    @endif
                    <!-- <button class="add_btn fileinput-button"><i class="fas fa-upload mr-2"></i>Choose file here</button> -->
                    <span>Please upload a valid image file. Size of image should not be more than 2MB.</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row py-20 px-10">
        <div class="col-md-12 text-center">
            <button type="submit" class="add_btn">{{ __('Save Changes') }}</button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Profile Updated successfully.') }}</p>
            @endif

        </div>
    </div>
</div>
</form>