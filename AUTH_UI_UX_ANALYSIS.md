# Authentication Pages UI/UX Analysis Report

## Executive Summary

The authentication system demonstrates a modern dark-themed design with good visual hierarchy and consistent branding. However, there are several UX improvements, accessibility concerns, and design inconsistencies that should be addressed. This report identifies specific issues and provides actionable recommendations for each authentication page.

---

## 1. Login Page (`login.blade.php`)

### Current Design Review

**Strengths:**
- Two-column split layout (branding + form) on desktop
- Clear visual hierarchy with centered form
- Good use of whitespace and spacing
- Responsive design with mobile logo fallback
- Error message support with x-alert component
- "Remember me" and "Forgot Password" options
- Google Sign-in placeholder (future feature)

**Current Structure:**
- Left side: Branding (desktop only)
- Right side: Login form with username/email input
- Secondary CTA: Sign-up link
- Tertiary CTA: Google Sign-in

### Issues Identified

#### 1. Label/Input Naming Confusion
**Issue:** Input labeled "Username" but name attribute is "email"
```blade
<x-input
    label="Username"
    name="email"
    type="text"
    placeholder="Enter your username or email"
```
**Problem:** Accessibility tools may read this incorrectly; backend logic might be unclear
**WCAG Impact:** Label mismatch with form field name

#### 2. Missing Input Validation Feedback
**Issue:** No real-time validation or password requirements display
**Problem:** Users don't know if their password meets requirements before submission
**UX Impact:** Increased form rejection rate; no helpful error guidance

#### 3. Google Sign-in Button Alert
**Issue:** `onclick="alert('Google Sign-in coming soon!')"` is UX anti-pattern
**Problem:** Native alert is jarring; dismisses focus from form context
**Better Approach:** Use modal or toast notification; disable button with visual indicator

#### 4. Missing Accessibility Attributes
**Issue:** Checkbox "Remember me" lacks proper ID association
```blade
<input type="checkbox" name="remember" class="...">
<label ... >Remember me</label>
```
**Problem:** No explicit for/id relationship for screen readers
**WCAG Impact:** Level A violation (1.3.1 Info and Relationships)

#### 5. No Loading State on Submit
**Issue:** Button doesn't indicate submission in progress
**Problem:** Users may click multiple times; no feedback during auth check
**Better Practice:** Disable button, show spinner while awaiting response

#### 6. Error Message Display
**Issue:** All errors shown in single alert without field-level feedback
```blade
@foreach($errors->all() as $error)
    {{ $error }}
@endforeach
```
**Problem:** Generic list approach; users can't associate errors with specific fields
**Best Practice:** Display errors adjacent to failing inputs

### Recommendations

**HIGH PRIORITY:**

1. **Fix Label/Input Mismatch**
   ```blade
   <x-input
       label="Username or Email"  <!-- Update label -->
       name="email"
       type="email"  <!-- Change type to email for better validation -->
       placeholder="Enter your username or email"
       :value="old('email')"
       required
   />
   ```

2. **Add Field-Level Error Display**
   Modify x-input component to auto-display errors:
   ```blade
   <x-input
       label="Username or Email"
       name="email"
       type="email"
       :value="old('email')"
       :error="$errors->first('email')"
       required
   />
   ```

3. **Improve Remember Me Accessibility**
   ```blade
   <label class="flex items-center gap-2 cursor-pointer">
       <input 
           type="checkbox" 
           id="remember-checkbox"  <!-- Add ID -->
           name="remember" 
           class="w-4 h-4 rounded border-dark-border bg-dark-elevated text-primary focus:ring-2 focus:ring-primary/50 cursor-pointer"
       >
       <span for="remember-checkbox" class="text-sm text-text-secondary">Remember me</span>
   </label>
   ```

4. **Replace Alert with Proper UI**
   ```blade
   <div id="comingSoonBanner" class="w-full p-3 bg-status-warning-bg border border-yellow-600 rounded-lg text-status-warning-text text-sm mb-4">
       <strong>Coming Soon:</strong> Google Sign-in integration launching soon
   </div>
   
   <button
       type="button"
       disabled
       class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-dark-elevated border border-dark-border rounded-lg text-text-secondary cursor-not-allowed opacity-50 transition-all"
   >
       <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><!-- Google icon --></svg>
       <span>Log in with Google <small>(Coming Soon)</small></span>
   </button>
   ```

5. **Add Loading State to Button**
   Enhance x-button component with loading prop:
   ```blade
   <x-button type="submit" variant="primary" class="w-full" id="loginBtn">
       Login
   </x-button>
   
   <script>
   document.querySelector('form').addEventListener('submit', function(e) {
       const btn = document.getElementById('loginBtn');
       btn.setAttribute('disabled', 'disabled');
       btn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2"></svg>Logging in...';
   });
   </script>
   ```

**MEDIUM PRIORITY:**

6. **Add Password Requirements Hint**
   ```blade
   <x-input
       label="Password"
       name="password"
       type="password"
       placeholder="Enter your password"
       hint="8+ characters, letters and numbers"  <!-- If component supports hint prop -->
       required
   />
   ```

7. **Improve Layout on Tablet**
   - Left branding section hidden on lg should show on md
   - Consider 3-column layout for very wide screens
   ```blade
   <div class="hidden md:flex md:flex-1 ...">  <!-- Changed from lg -->
   ```

8. **Add Forgotten Account Recovery**
   - Add "Can't log in?" link before Google button
   - Points to account recovery/contact support

---

## 2. Register Page (`register.blade.php`)

### Current Design Review

**Strengths:**
- Consistent with login page layout
- Good field organization with grid layout for password fields
- Terms & Privacy Policy checkbox
- Google sign-up option
- Clear heading and mobile responsiveness

**Weaknesses:**
- Too many required fields without progressive disclosure
- No password strength indicator
- No field-level validation feedback
- Phone field lacks formatting guidance

### Issues Identified

#### 1. Phone Field Input Type
**Issue:** 
```blade
<x-input
    label="WhatsApp Number"
    name="phone"
    type="text"  <!-- Should be tel -->
```
**Problems:**
- Text input doesn't trigger numeric keyboard on mobile
- No built-in validation or pattern
- No formatting guidance (country code?)

#### 2. Password Confirmation Not Clear
**Issue:** Fields in grid layout don't clearly relate to each other
```blade
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <x-input ... label="Password" />
    <x-input ... label="Confirm Password" />
</div>
```
**Problem:** On mobile (cols-1), unclear that second field must match first
**UX Impact:** Users may misunderstand requirement

#### 3. Missing Password Strength Indicator
**Issue:** No visual feedback on password complexity
**Problem:** Users unsure if password is strong; no guidance
**Security Impact:** Users may choose weak passwords

#### 4. Terms Checkbox Accessibility
**Issue:** Similar to login - no proper id/for relationship
```blade
<input type="checkbox" id="terms" required ...>
<label for="terms" class="...">I agree to <a>Terms & Privacy Policy</a></label>
```
**Problem:** Good pattern but "Privacy Policy" link href is "#" (broken)

#### 5. No Form Success State
**Issue:** After submission, no loading state or success feedback
**Problem:** User doesn't know registration is processing

#### 6. Username Field Lacks Constraints
**Issue:** No hint about username requirements (length, chars allowed)
**Problem:** Likely server-side rejection if requirements exist

#### 7. Email Field Type Issue
**Issue:**
```blade
<x-input
    label="Email Address"
    name="email"
    type="email"  <!-- Good -->
    ...
/>
```
**Good Practice:** Email type is correct, but should have autocomplete hint:
```blade
type="email"
autocomplete="email"
```

### Recommendations

**HIGH PRIORITY:**

1. **Fix Phone Input Type**
   ```blade
   <x-input
       label="WhatsApp Number"
       name="phone"
       type="tel"  <!-- Changed from text -->
       placeholder="62812345678"  <!-- Add example format -->
       pattern="[0-9+\-\s]{10,15}"  <!-- Add pattern validation -->
       :error="$errors->first('phone')"
       hint="Enter without +, e.g., 62812345678 or 081-234-5678"
       required
   />
   ```

2. **Improve Password Confirmation UX**
   ```blade
   <!-- Option A: Stack on all sizes -->
   <x-input
       label="Password"
       name="password"
       type="password"
       placeholder="Enter password"
       required
   />
   
   <x-input
       label="Confirm Password"
       name="password_confirmation"
       type="password"
       placeholder="Re-enter password"
       hint="Must match password above"
       :error="$errors->first('password_confirmation')"
       required
   />
   
   <!-- Option B: Add client-side matching indicator -->
   <script>
   const pwd = document.querySelector('input[name="password"]');
   const confirm = document.querySelector('input[name="password_confirmation"]');
   
   confirm.addEventListener('input', function() {
       if (this.value && this.value !== pwd.value) {
           this.classList.add('border-status-error');
       } else {
           this.classList.remove('border-status-error');
       }
   });
   </script>
   ```

3. **Add Password Strength Indicator**
   ```blade
   <div class="mb-5">
       <x-input
           label="Password"
           name="password"
           type="password"
           placeholder="Enter password"
           hint="8+ characters, mix of letters & numbers"
           required
           id="passwordInput"
       />
       <!-- Strength indicator -->
       <div class="mt-2 flex gap-1">
           <div id="strength-0" class="flex-1 h-1 bg-dark-border rounded-full"></div>
           <div id="strength-1" class="flex-1 h-1 bg-dark-border rounded-full"></div>
           <div id="strength-2" class="flex-1 h-1 bg-dark-border rounded-full"></div>
       </div>
       <p id="strengthText" class="text-xs text-text-tertiary mt-1">Password strength: Weak</p>
   </div>
   
   <script>
   const passwordInput = document.getElementById('passwordInput');
   const strengthIndicators = [
       document.getElementById('strength-0'),
       document.getElementById('strength-1'),
       document.getElementById('strength-2')
   ];
   const strengthText = document.getElementById('strengthText');
   
   passwordInput.addEventListener('input', function(e) {
       const pwd = e.target.value;
       let strength = 0;
       const colors = ['bg-status-error', 'bg-status-warning', 'bg-status-success'];
       const labels = ['Weak', 'Fair', 'Strong'];
       
       // Weak: < 8 chars
       if (pwd.length >= 8) strength++;
       // Fair: has letters + numbers
       if (/[a-z]/.test(pwd) && /[0-9]/.test(pwd)) strength++;
       // Strong: has special chars
       if (/[!@#$%^&*]/.test(pwd)) strength++;
       
       strengthIndicators.forEach((indicator, i) => {
           indicator.className = 'flex-1 h-1 bg-dark-border rounded-full';
           if (i < strength) {
               indicator.classList.add(colors[strength - 1]);
           }
       });
       
       strengthText.textContent = `Password strength: ${labels[Math.max(0, strength - 1)]}`;
   });
   </script>
   ```

4. **Fix Terms Link and Add Validation**
   ```blade
   <div class="flex items-start gap-3">
       <input
           type="checkbox"
           id="terms"
           name="terms"  <!-- Add name attribute -->
           required
           class="mt-1 w-4 h-4 rounded border-dark-border bg-dark-elevated text-primary focus:ring-2 focus:ring-primary/50 cursor-pointer"
       >
       <label for="terms" class="text-sm text-text-secondary cursor-pointer">
           I agree to <a href="{{ route('terms') }}" target="_blank" class="text-primary hover:text-primary-400 transition-colors">Terms & Privacy Policy</a>
       </label>
       @error('terms')
           <p class="mt-1.5 text-xs text-status-error-text">{{ $message }}</p>
       @enderror
   </div>
   ```

5. **Add Autocomplete Attributes**
   ```blade
   <x-input
       label="Username"
       name="username"
       type="text"
       autocomplete="username"  <!-- Add -->
       placeholder="Enter your username"
       :error="$errors->first('username')"
       hint="3-20 characters, letters and numbers only"
       required
   />
   
   <x-input
       label="Email Address"
       name="email"
       type="email"
       autocomplete="email"  <!-- Add -->
       placeholder="Enter your email address"
       :error="$errors->first('email')"
       required
   />
   
   <x-input
       label="Password"
       name="password"
       type="password"
       autocomplete="new-password"  <!-- Add for registration context -->
       placeholder="Enter password"
       required
   />
   
   <x-input
       label="Confirm Password"
       name="password_confirmation"
       type="password"
       autocomplete="new-password"  <!-- Add -->
       placeholder="Confirm password"
       required
   />
   ```

**MEDIUM PRIORITY:**

6. **Add Username Requirements Display**
   ```blade
   <x-input
       label="Username"
       name="username"
       type="text"
       autocomplete="username"
       placeholder="Enter your username"
       hint="3-20 characters, letters, numbers, underscore allowed"
       :error="$errors->first('username')"
       id="usernameInput"
       required
   />
   <!-- Optional: Real-time validation -->
   <script>
   const usernameInput = document.getElementById('usernameInput');
   usernameInput.addEventListener('blur', async function(e) {
       const username = e.target.value;
       if (username.length < 3) return;
       
       const response = await fetch('/api/check-username?username=' + encodeURIComponent(username));
       const data = await response.json();
       
       if (!data.available) {
           usernameInput.classList.add('border-status-error');
           // Show error message
       } else {
           usernameInput.classList.remove('border-status-error');
       }
   });
   </script>
   ```

7. **Replace Google Sign-up Alert**
   ```blade
   <div id="googleBanner" class="w-full p-3 bg-status-warning-bg border border-yellow-600 rounded-lg text-status-warning-text text-sm mb-4">
       <strong>Coming Soon:</strong> Quick sign-up with Google
   </div>
   
   <button
       type="button"
       disabled
       class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-dark-elevated border border-dark-border rounded-lg text-text-secondary cursor-not-allowed opacity-50 transition-all"
   >
       <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><!-- Google icon --></svg>
       <span>Sign up with Google <small>(Coming Soon)</small></span>
   </button>
   ```

8. **Add Progressive Disclosure for Phone Number**
   - Move WhatsApp number to optional field or secondary step
   - Or add hint about why it's needed: "For two-factor auth and account recovery"

---

## 3. Forgot Password Page (`forgot-password.blade.php`)

### Current Design Review

**Strengths:**
- Simple, focused single-field form
- Uses x-card component for proper spacing
- Back to Login link for navigation
- Success message support
- Good visual hierarchy

**Weaknesses:**
- No description of what happens after submission
- No expected delivery time for reset email
- No handling for non-existent email addresses

### Issues Identified

#### 1. Missing User Guidance
**Issue:** No explanation of next steps
```blade
<h1 class="text-3xl font-bold text-center mb-8">Forgot Password</h1>
<!-- No description -->
```
**Problem:** Users unsure what happens after form submission
**UX Impact:** Uncertainty about email delivery; may duplicate submissions

#### 2. Email Not Pre-filled
**Issue:** If user came from login with email visible, not pre-populated
**Problem:** User must re-enter email they already provided
**Best Practice:** Pass email from referrer if available

#### 3. No Email Confirmation
**Issue:** After submission, no confirmation that email was sent
**Problem:** User may doubt if form actually submitted
**Better Pattern:** Redirect to "Check your email" confirmation page

#### 4. No Security/Privacy Notice
**Issue:** No mention of email security
**Problem:** Privacy-conscious users may worry about data handling
**Best Practice:** Add security notice about what happens to their email

#### 5. Lack of Mobile Optimization
**Issue:** Could use more breathing room on small screens
**Problem:** Form appears cramped on very small devices

### Recommendations

**HIGH PRIORITY:**

1. **Add Descriptive Content**
   ```blade
   <x-card class="max-w-md mx-auto">
       <h1 class="text-3xl font-bold text-center mb-3">Forgot Password</h1>
       
       <!-- NEW: Add description -->
       <p class="text-center text-text-secondary mb-8 leading-relaxed">
           Enter your email address and we'll send you a link to reset your password. 
           The link will expire in <strong>60 minutes</strong>.
       </p>
       
       <!-- Success Message -->
       @if(session('success'))
           <x-alert type="success" class="mb-6">
               <div>
                   <p class="font-semibold mb-1">Check your email</p>
                   <p class="text-sm">If an account exists for {{ session('email') ?? 'that email' }}, you'll receive a password reset link shortly.</p>
               </div>
           </x-alert>
       @endif
   ```

2. **Add Security/Privacy Notice**
   ```blade
   <div class="mt-8 pt-6 border-t border-dark-border">
       <p class="text-xs text-text-tertiary text-center">
           For your security, we don't confirm whether an email address is registered. 
           If you don't receive a reset email within a few minutes, check your spam folder.
       </p>
   </div>
   ```

3. **Create Confirmation Page**
   New view: `reset-password-sent.blade.php`
   ```blade
   @extends('layouts.auth')
   @section('title', 'Reset Email Sent - UP STORE')
   @section('content')
   <div class="min-h-screen flex items-center justify-center p-6">
       <div class="w-full max-w-md">
           <!-- Logo -->
           <div class="text-center mb-12">
               <div class="flex items-center justify-center gap-2 mb-6">
                   <span class="text-6xl">✓</span>  <!-- Or check icon -->
               </div>
               <div class="flex items-center justify-center gap-2">
                   <span class="text-4xl font-black italic text-primary">UP</span>
                   <span class="text-2xl font-bold tracking-wider">STORE</span>
               </div>
           </div>
           
           <x-card class="text-center">
               <h1 class="text-2xl font-bold mb-4">Check Your Email</h1>
               
               <p class="text-text-secondary mb-6">
                   We sent a password reset link to:<br>
                   <strong class="text-text-primary">{{ $email ?? session('email') }}</strong>
               </p>
               
               <div class="bg-dark-base p-4 rounded-lg mb-6">
                   <p class="text-sm text-text-tertiary mb-2">Next steps:</p>
                   <ol class="text-sm text-left text-text-secondary space-y-2">
                       <li>1. Check your email inbox (and spam folder)</li>
                       <li>2. Click the reset link in the email</li>
                       <li>3. Enter your new password</li>
                       <li>4. Log in with your new password</li>
                   </ol>
               </div>
               
               <p class="text-xs text-text-tertiary mb-6">
                   The reset link expires in 60 minutes for security reasons.
               </p>
               
               <div class="space-y-3">
                   <p class="text-sm text-text-secondary">Didn't receive an email?</p>
                   <a href="{{ route('password.request') }}" class="inline-block text-primary hover:text-primary-400 font-medium transition-colors">
                       Try another email address
                   </a>
                   <div class="text-text-tertiary text-xs">or</div>
                   <a href="{{ route('login') }}" class="inline-block text-primary hover:text-primary-400 font-medium transition-colors">
                       Back to login
                   </a>
               </div>
           </x-card>
       </div>
   </div>
   @endsection
   ```

4. **Modify Backend to Redirect to Confirmation**
   Update PasswordResetController:
   ```php
   public function store(Request $request)
   {
       $request->validate(['email' => 'required|email']);
       
       // ... send reset link ...
       
       // Instead of redirect with session message:
       return redirect()->route('password.sent')->with('email', $request->email);
   }
   ```

**MEDIUM PRIORITY:**

5. **Add Error Handling for Invalid Email**
   ```blade
   @if($errors->any())
       <x-alert type="error" class="mb-6">
           <div>
               <p class="font-semibold mb-1">Couldn't send reset email</p>
               @foreach($errors->all() as $error)
                   <p class="text-sm">{{ $error }}</p>
               @endforeach
           </div>
       </x-alert>
   @endif
   ```

6. **Add Resend Option**
   ```blade
   <div class="mt-6 text-center">
       <p class="text-sm text-text-secondary mb-3">
           Don't remember your email?
       </p>
       <a href="{{ route('register') }}" class="inline-block text-primary hover:text-primary-400 font-medium transition-colors">
           Create a new account
       </a>
   </div>
   ```

7. **Mobile-Specific Spacing**
   ```blade
   <x-card class="max-w-md mx-auto px-4 sm:px-6">
       <!-- Add padding adjustment for mobile -->
   </x-card>
   ```

---

## 4. Reset Password Page (`reset-password.blade.php`)

### Current Design Review

**Strengths:**
- Clean form with focused input fields
- Hidden token and email fields for security
- Read-only email display shows user context
- Consistent styling with other auth pages

**Weaknesses:**
- Read-only email field could be clearer
- No password requirements display
- No strength indicator
- No token expiration warning
- Confusing "input" class on readonly field

### Issues Identified

#### 1. Read-only Field Styling
**Issue:**
```blade
<input
    type="email"
    value="{{ $email }}"
    readonly
    class="input bg-dark-base cursor-not-allowed opacity-75"
>
```
**Problems:**
- Mixing input classes with readonly state
- opacity-75 makes text hard to read
- No visual indicator it's non-editable
- Missing label for screen readers

#### 2. No Password Requirements Hint
**Issue:** Users don't see password complexity requirements
**Problem:** Likely rejections if requirements exist
**Impact:** Increased form submissions/rejections

#### 3. No Token Validation Feedback
**Issue:** Invalid tokens show generic errors
**Problem:** User doesn't understand why token is invalid
**Better Practice:** Pre-validate token and show specific error

#### 4. Missing Strength Indicator
**Issue:** No visual feedback on password strength
**Problem:** Same as registration - users unsure if password adequate

#### 5. Accessibility: Label Missing
**Issue:**
```blade
<div class="w-full">
    <label class="block text-sm font-medium text-text-secondary mb-2">
        Email Address
    </label>
    <input
        type="email"
        value="{{ $email }}"
        readonly
        class="input bg-dark-base cursor-not-allowed opacity-75"
    >
</div>
```
**Problem:** Label not connected to input with for/id
**WCAG Impact:** Level A violation

#### 6. No Loading State
**Issue:** Submit button doesn't show loading state
**Problem:** Users may click multiple times during processing

### Recommendations

**HIGH PRIORITY:**

1. **Fix Read-only Field Display**
   ```blade
   <div class="w-full">
       <label for="email-display" class="block text-sm font-medium text-text-secondary mb-2">
           Email Address
       </label>
       <div class="relative">
           <input
               id="email-display"
               type="email"
               value="{{ $email }}"
               readonly
               class="w-full px-4 py-3 bg-dark-base border border-dark-border rounded-lg text-text-secondary cursor-default"
               aria-label="Email address (cannot be changed)"
           >
           <svg class="absolute right-3 top-3.5 w-5 h-5 text-text-tertiary" fill="currentColor" viewBox="0 0 20 20">
               <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
               <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
           </svg>
       </div>
       <p class="mt-1.5 text-xs text-text-tertiary">This email cannot be changed</p>
   </div>
   
   <!-- Hidden actual email for form submission -->
   <input type="hidden" name="email" value="{{ $email }}">
   ```

2. **Add Password Requirements and Strength Indicator**
   ```blade
   <div class="mb-5">
       <x-input
           label="New Password"
           name="password"
           type="password"
           placeholder="Enter new password"
           :error="$errors->first('password')"
           hint="8+ characters with letters, numbers, and symbols"
           id="passwordInput"
           required
       />
       
       <!-- Strength Indicator -->
       <div class="mt-2 space-y-2">
           <div class="flex gap-1">
               <div id="strength-bar-0" class="flex-1 h-1 bg-dark-border rounded-full transition-colors"></div>
               <div id="strength-bar-1" class="flex-1 h-1 bg-dark-border rounded-full transition-colors"></div>
               <div id="strength-bar-2" class="flex-1 h-1 bg-dark-border rounded-full transition-colors"></div>
           </div>
           <p id="strengthLabel" class="text-xs text-text-tertiary">Password strength: Weak</p>
       </div>
       
       <!-- Requirements Checklist -->
       <div class="mt-3 space-y-1 text-xs">
           <div class="flex items-center gap-2">
               <span id="req-length" class="text-text-tertiary">○</span>
               <span id="req-length-text" class="text-text-tertiary">At least 8 characters</span>
           </div>
           <div class="flex items-center gap-2">
               <span id="req-letter" class="text-text-tertiary">○</span>
               <span id="req-letter-text" class="text-text-tertiary">Contains letters (a-z, A-Z)</span>
           </div>
           <div class="flex items-center gap-2">
               <span id="req-number" class="text-text-tertiary">○</span>
               <span id="req-number-text" class="text-text-tertiary">Contains numbers (0-9)</span>
           </div>
           <div class="flex items-center gap-2">
               <span id="req-special" class="text-text-tertiary">○</span>
               <span id="req-special-text" class="text-text-tertiary">Contains special character (!@#$%^&*)</span>
           </div>
       </div>
   </div>
   
   <script>
   const passwordInput = document.getElementById('passwordInput');
   const requirements = {
       length: document.getElementById('req-length'),
       letter: document.getElementById('req-letter'),
       number: document.getElementById('req-number'),
       special: document.getElementById('req-special'),
   };
   
   passwordInput.addEventListener('input', function(e) {
       const pwd = e.target.value;
       
       // Check requirements
       const checks = {
           length: pwd.length >= 8,
           letter: /[a-zA-Z]/.test(pwd),
           number: /[0-9]/.test(pwd),
           special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(pwd),
       };
       
       // Update requirement indicators
       Object.keys(checks).forEach(key => {
           const elem = requirements[key];
           if (checks[key]) {
               elem.textContent = '✓';
               elem.className = 'text-status-success';
           } else {
               elem.textContent = '○';
               elem.className = 'text-text-tertiary';
           }
       });
       
       // Calculate strength
       const strength = Object.values(checks).filter(Boolean).length;
       const strengths = ['Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
       const colors = ['bg-status-error', 'bg-status-warning', 'bg-primary', 'bg-status-success', 'bg-accent'];
       
       document.getElementById('strengthLabel').textContent = `Password strength: ${strengths[strength]}`;
       
       for (let i = 0; i < 3; i++) {
           const bar = document.getElementById(`strength-bar-${i}`);
           bar.className = `flex-1 h-1 rounded-full transition-colors ${i < Math.ceil(strength / 1.5) ? colors[strength - 1] : 'bg-dark-border'}`;
       }
   });
   </script>
   ```

3. **Add Token Expiration Status**
   ```blade
   <div class="bg-status-warning-bg border border-yellow-600 rounded-lg p-4 mb-6">
       <p class="text-sm text-status-warning-text">
           <strong>Important:</strong> This reset link expires in 60 minutes. 
           If it expires, you'll need to request a new one.
       </p>
   </div>
   ```

4. **Add Loading State to Submit Button**
   ```blade
   <x-button type="submit" variant="primary" class="w-full mt-6" id="resetBtn">
       Reset Password
   </x-button>
   
   <script>
   document.querySelector('form').addEventListener('submit', function(e) {
       const btn = document.getElementById('resetBtn');
       btn.disabled = true;
       btn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline"></svg>Resetting password...';
   });
   </script>
   ```

**MEDIUM PRIORITY:**

5. **Improve Password Confirmation Match Indicator**
   ```blade
   <x-input
       label="Confirm Password"
       name="password_confirmation"
       type="password"
       placeholder="Confirm new password"
       :error="$errors->first('password_confirmation')"
       id="passwordConfirmInput"
       required
   />
   
   <p id="matchText" class="mt-2 text-xs text-text-tertiary">
       Passwords must match
   </p>
   
   <script>
   const pwd = document.getElementById('passwordInput');
   const confirm = document.getElementById('passwordConfirmInput');
   const matchText = document.getElementById('matchText');
   
   confirm.addEventListener('input', function() {
       if (this.value) {
           if (this.value === pwd.value) {
               this.classList.remove('border-status-error');
               matchText.className = 'mt-2 text-xs text-status-success';
               matchText.textContent = '✓ Passwords match';
           } else {
               this.classList.add('border-status-error');
               matchText.className = 'mt-2 text-xs text-status-error-text';
               matchText.textContent = '✗ Passwords do not match';
           }
       }
   });
   </script>
   ```

6. **Add Helpful Links**
   ```blade
   <div class="mt-6 space-y-3 text-center">
       <p class="text-sm text-text-secondary">
           Remember your password?
       </p>
       <a href="{{ route('login') }}" class="inline-block text-primary hover:text-primary-400 font-medium transition-colors">
           Back to login
       </a>
   </div>
   ```

---

## 5. 2FA Verification Page (`two-factor-verify.blade.php`)

### Current Design Review

**Strengths:**
- Clear purpose with lock icon and descriptive text
- Smart input with auto-formatting (numeric/alphanumeric)
- Recovery code fallback option clearly visible
- Good help text below input
- Tab between TOTP and recovery code modes

**Weaknesses:**
- "OR" divider could be more prominent
- Recovery code toggle not visually distinct enough
- No countdown for TOTP code expiration
- Font/spacing issues with recovery code format

### Issues Identified

#### 1. Recovery Code Toggle Behavior
**Issue:** 
```javascript
onclick="toggleRecoveryMode()"
```
**Problem:** Plain onclick handler; no ARIA attributes for state change
**Accessibility Impact:** Screen reader doesn't announce mode change

#### 2. Missing TOTP Expiration Countdown
**Issue:** No indication that 6-digit code expires every 30 seconds
**Problem:** Users may not know to regenerate code if it takes too long
**UX Impact:** Increased failed attempts

#### 3. Input Placeholder Inconsistency
**Issue:** Recovery code shows as "XXXXXXXX" (8 uppercase)
**Problem:** May confuse users about format (are X's actual input?)
**Better Practice:** Show example format

#### 4. No Paste Support for Desktop
**Issue:** Auto-formatting works, but paste functionality could be clearer
**Problem:** User pastes but format validation may reject it

#### 5. Accessibility: Button vs Link
**Issue:**
```html
<button type="button" onclick="toggleRecoveryMode()">
    Use a recovery code instead
</button>
```
**Problem:** Styled as link (underline) but is button - confusing semantics
**WCAG Impact:** 4.1.2 Name, Role, Value confusion

#### 6. No Error Recovery Path
**Issue:** After 5+ failed attempts, no guidance
**Problem:** User locks account without knowing next steps

### Recommendations

**HIGH PRIORITY:**

1. **Add TOTP Countdown Timer**
   ```blade
   <div class="mb-6">
       <div class="flex justify-between items-baseline mb-2">
           <label for="codeInput" id="codeLabel" class="block text-sm font-medium text-text-secondary">
               Authentication Code
           </label>
           <span id="timeRemaining" class="text-xs text-status-warning text-animation-pulse">
               Code expires in <strong>30s</strong>
           </span>
       </div>
       
       <input
           type="text"
           name="code"
           id="codeInput"
           maxlength="8"
           inputmode="numeric"
           placeholder="000000"
           required
           autofocus
           class="input w-full text-center text-2xl font-bold tracking-[0.75rem] bg-dark-base"
       >
       <p id="helpText" class="mt-2 text-xs text-center text-text-tertiary">
           Open your authenticator app and enter the current code
       </p>
   </div>
   
   <script>
   let totp_expiry = 30;
   let countdown_interval;
   
   function updateCountdown() {
       const timeEl = document.getElementById('timeRemaining');
       totp_expiry--;
       
       if (totp_expiry <= 0) {
           totp_expiry = 30;
           document.getElementById('codeInput').value = '';
       }
       
       // Color changes based on time
       if (totp_expiry <= 5) {
           timeEl.className = 'text-xs text-status-error animate-pulse';
       } else if (totp_expiry <= 10) {
           timeEl.className = 'text-xs text-status-warning';
       } else {
           timeEl.className = 'text-xs text-text-tertiary';
       }
       
       timeEl.innerHTML = `Code expires in <strong>${totp_expiry}s</strong>`;
   }
   
   // Update countdown every second
   countdown_interval = setInterval(updateCountdown, 1000);
   
   // Clear on form submission
   document.querySelector('form').addEventListener('submit', function() {
       clearInterval(countdown_interval);
   });
   </script>
   ```

2. **Improve Recovery Code Toggle Accessibility**
   ```blade
   <div class="relative my-6">
       <div class="absolute inset-0 flex items-center">
           <div class="w-full border-t border-dark-border"></div>
       </div>
       <div class="relative flex justify-center text-sm">
           <span class="px-2 bg-dark-surface text-text-tertiary">OR</span>
       </div>
   </div>
   
   <!-- Recovery Code Toggle (improved) -->
   <div class="text-center">
       <button
           type="button"
           id="recoveryToggle"
           aria-pressed="false"
           aria-label="Switch to recovery code verification"
           onclick="toggleRecoveryMode()"
           class="text-sm text-primary hover:text-primary-400 underline transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:rounded"
       >
           Use a recovery code instead
       </button>
   </div>
   
   <!-- Update JavaScript to manage ARIA state -->
   <script>
   let isRecoveryMode = false;
   const toggleBtn = document.getElementById('recoveryToggle');
   
   function toggleRecoveryMode() {
       isRecoveryMode = !isRecoveryMode;
       const input = document.getElementById('codeInput');
       const label = document.getElementById('codeLabel');
       const helpText = document.getElementById('helpText');
       
       if (isRecoveryMode) {
           input.placeholder = 'XXXXXXXX';
           input.maxLength = 8;
           input.style.letterSpacing = '0.375rem';
           input.inputMode = 'text';  // Changed from numeric
           input.pattern = '[0-9A-Z]{8}';  // Recovery codes format
           label.textContent = 'Recovery Code';
           helpText.textContent = 'Enter one of your 8-character recovery codes (uppercase)';
           toggleBtn.textContent = 'Use authenticator app instead';
           toggleBtn.setAttribute('aria-pressed', 'true');
           toggleBtn.setAttribute('aria-label', 'Switch back to authenticator app');
       } else {
           input.placeholder = '000000';
           input.maxLength = 6;
           input.style.letterSpacing = '0.75rem';
           input.inputMode = 'numeric';
           input.pattern = '[0-9]{6}';
           label.textContent = 'Authentication Code';
           helpText.textContent = 'Open your authenticator app and enter the current code';
           toggleBtn.textContent = 'Use a recovery code instead';
           toggleBtn.setAttribute('aria-pressed', 'false');
           toggleBtn.setAttribute('aria-label', 'Switch to recovery code verification');
       }
       
       input.value = '';
       input.focus();
   }
   </script>
   ```

3. **Improve Recovery Code Placeholder**
   ```blade
   <div class="text-xs text-center text-text-tertiary mt-2 p-2 bg-dark-base rounded">
       Example: <code class="font-mono">ABC12345</code> 
       (uppercase, 8 characters)
   </div>
   ```

4. **Add Account Lockout Guidance**
   ```blade
   <div class="mt-8 pt-6 border-t border-dark-border text-center">
       <p class="text-sm text-text-secondary mb-3">
           <strong>Lost your phone?</strong><br>
           Use one of your recovery codes to log in
       </p>
       <p class="text-xs text-text-tertiary">
           Can't log in? Your account may be temporarily locked after too many failed attempts.<br>
           <a href="{{ route('password.request') }}" class="text-primary hover:text-primary-400">Reset your password</a> or <a href="#" class="text-primary hover:text-primary-400">contact support</a>
       </p>
   </div>
   ```

**MEDIUM PRIORITY:**

5. **Add Paste-Friendly Input**
   Already good, but enhance detection:
   ```javascript
   document.getElementById('codeInput').addEventListener('paste', function(e) {
       e.preventDefault();
       let text = (e.clipboardData || window.clipboardData).getData('text');
       
       if (!isRecoveryMode) {
           text = text.replace(/[^0-9]/g, '').substring(0, 6);
           this.inputMode = 'numeric';
       } else {
           text = text.replace(/[^0-9A-Za-z]/g, '').toUpperCase().substring(0, 8);
           this.inputMode = 'text';
       }
       
       this.value = text;
       
       // Auto-submit if complete
       if (text.length === (isRecoveryMode ? 8 : 6)) {
           this.form.submit();
       }
   });
   ```

6. **Add Resend Code Option** (if SMS TOTP available)
   ```blade
   <div class="mt-6 text-center">
       <p class="text-sm text-text-secondary mb-2">
           No authenticator app? Request a code via SMS
       </p>
       <button type="button" class="text-sm text-primary hover:text-primary-400 underline">
           Send code via SMS
       </button>
   </div>
   ```

---

## 6. 2FA Settings Page (`two-factor.blade.php`)

### Current Design Review

**Strengths:**
- Status badges clearly show 2FA state (enabled/disabled)
- How-it-works section educates users
- Modal for confirming disable action
- Recovery code management links
- Print/copy functions for codes

**Weaknesses:**
- Mixed styling (inline styles + CSS classes)
- Inconsistent color scheme with app
- Not using x-card or standard components
- Modal implementation is custom, not consistent
- "How It Works" section could be more visual

### Issues Identified

#### 1. Inconsistent Styling Approach
**Issue:** Page uses inline CSS instead of Tailwind/components
```blade
<style>
    .settings-container { max-width: 800px; margin: 0 auto; }
    .page-title { font-size: 28px; font-weight: 700; ... }
```
**Problem:** Diverges from app design system; hard to maintain
**Impact:** Visual inconsistency; contradicts CLAUDE.md guidelines

#### 2. Hard-coded Colors
**Issue:** Uses custom colors (#FF8C00, #2a2a2a) instead of design tokens
```css
.btn-primary { background: #FF8C00; color: white; }
.recovery-code { color: #FF8C00; }
```
**Problem:** If app colors change, this page won't update
**Better Practice:** Use Tailwind color classes

#### 3. Modal Not Accessible
**Issue:**
```javascript
window.onclick = function(event) {
    const modal = document.getElementById('disableModal');
    if (event.target == modal) { closeModal(); }
}
```
**Problem:** No focus trap; no Escape key handling; no role="dialog"
**WCAG Impact:** 2.4.3 Focus Order violation

#### 4. Emoji Usage
**Issue:** Page titles use emoji (🔐, ⚠️, 💡)
**Problem:** Inconsistent with login/register pages; accessibility issues
**Better Practice:** Use SVG icons instead

#### 5. Recovery Codes Display
**Issue:** Simple text display with numbers
**Problem:** Hard to distinguish which code was used
**Better Feature:** Track which codes have been used

#### 6. Password Input Lacks Feedback
**Issue:** Disable modal password input has no validation
**Problem:** User may not see if password was incorrect
**Better Practice:** Show field-level error

### Recommendations

**HIGH PRIORITY:**

1. **Refactor to Use Design System Components**
   ```blade
   @extends('layouts.main')
   
   @section('title', '2FA Settings - UP STORE')
   
   @section('content')
   <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
       <h1 class="text-4xl font-bold text-text-primary mb-12">Two-Factor Authentication</h1>
       
       @if(session('success'))
           <x-alert type="success" class="mb-6">
               {{ session('success') }}
           </x-alert>
       @endif
       
       @if(session('warning'))
           <x-alert type="warning" class="mb-6">
               {{ session('warning') }}
           </x-alert>
       @endif
       
       <!-- Status Card -->
       <x-card class="mb-8">
           <div class="flex justify-between items-start mb-6">
               <div>
                   <h2 class="text-2xl font-bold text-text-primary mb-2">
                       Security Status
                   </h2>
                   @if($user->has2FAEnabled())
                       <span class="inline-block px-4 py-2 rounded-lg bg-status-success-bg border border-status-success-border text-status-success-text font-medium text-sm">
                           ✓ 2FA Enabled
                       </span>
                   @else
                       <span class="inline-block px-4 py-2 rounded-lg bg-status-error-bg border border-status-error-border text-status-error-text font-medium text-sm">
                           ✗ 2FA Disabled
                       </span>
                   @endif
               </div>
           </div>
           
           <p class="text-text-secondary mb-6 leading-relaxed">
               @if($user->has2FAEnabled())
                   Your account is protected with Two-Factor Authentication. 
                   You'll need to enter a code from your authenticator app when logging in.
               @else
                   Two-Factor Authentication adds an extra layer of security to your account. 
                   Even if someone knows your password, they won't be able to log in without your phone.
               @endif
           </p>
           
           <div class="flex gap-3 flex-wrap">
               @if($user->has2FAEnabled())
                   <a href="{{ route('2fa.recovery') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-dark-surface border border-dark-border text-text-secondary hover:text-primary hover:border-primary transition-colors">
                       View Recovery Codes
                   </a>
                   <button 
                       @click="disableModal = true"
                       type="button"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-status-error text-white hover:bg-status-error/90 transition-colors"
                   >
                       Disable 2FA
                   </button>
               @else
                   <a href="{{ route('2fa.enable') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary text-white hover:bg-primary-hover transition-colors font-medium">
                       Enable 2FA Now
                   </a>
               @endif
           </div>
       </x-card>
       
       @if(session('recovery_codes'))
           <!-- Recovery Codes Card -->
           <x-card class="mb-8 border-status-warning-border bg-status-warning-bg/10">
               <div class="mb-6">
                   <h3 class="text-xl font-bold text-text-primary mb-2">
                       Save Your Recovery Codes
                   </h3>
                   <p class="text-text-secondary mb-4">
                       Store these codes in a safe place. Each code can only be used once 
                       if you lose access to your authenticator app.
                   </p>
               </div>
               
               <!-- Recovery codes grid -->
               <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6 bg-dark-base p-4 rounded-lg">
                   @foreach(session('recovery_codes') as $code)
                       <div class="font-mono text-sm text-primary bg-dark-base px-3 py-2 rounded border border-dark-border">
                           {{ $code }}
                       </div>
                   @endforeach
               </div>
               
               <!-- Action buttons -->
               <div class="flex gap-3 flex-wrap">
                   <button @click="printCodes()" type="button" class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-lg bg-dark-surface border border-dark-border text-text-secondary hover:border-primary hover:text-primary transition-colors">
                       Print Codes
                   </button>
                   <button @click="copyCodes()" type="button" class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-lg bg-dark-surface border border-dark-border text-text-secondary hover:border-primary hover:text-primary transition-colors">
                       Copy to Clipboard
                   </button>
               </div>
           </x-card>
       @endif
       
       <!-- How It Works Card -->
       <x-card class="bg-dark-elevated">
           <h3 class="text-xl font-bold text-text-primary mb-6">How It Works</h3>
           
           <div class="space-y-4">
               <div class="flex gap-4">
                   <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm">
                       1
                   </div>
                   <div>
                       <h4 class="font-semibold text-text-primary mb-1">Download an Authenticator App</h4>
                       <p class="text-sm text-text-secondary">
                           Install Google Authenticator, Microsoft Authenticator, or Authy on your phone
                       </p>
                   </div>
               </div>
               
               <div class="flex gap-4">
                   <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm">
                       2
                   </div>
                   <div>
                       <h4 class="font-semibold text-text-primary mb-1">Scan the QR Code</h4>
                       <p class="text-sm text-text-secondary">
                           Open your authenticator app and scan the QR code we provide
                       </p>
                   </div>
               </div>
               
               <div class="flex gap-4">
                   <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm">
                       3
                   </div>
                   <div>
                       <h4 class="font-semibold text-text-primary mb-1">Verify Your Setup</h4>
                       <p class="text-sm text-text-secondary">
                           Enter the 6-digit code from your app to complete setup
                       </p>
                   </div>
               </div>
               
               <div class="flex gap-4">
                   <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm">
                       4
                   </div>
                   <div>
                       <h4 class="font-semibold text-text-primary mb-1">Save Recovery Codes</h4>
                       <p class="text-sm text-text-secondary">
                           Store your recovery codes in a safe place for emergency access
                       </p>
                   </div>
               </div>
           </div>
       </x-card>
   </div>
   
   <!-- Disable Modal -->
   <div x-show="disableModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display: none;">
       <x-card class="w-full max-w-md">
           <h3 class="text-xl font-bold text-text-primary mb-4">
               Disable Two-Factor Authentication
           </h3>
           
           <p class="text-text-secondary mb-6">
               Are you sure? Disabling 2FA makes your account less secure.
           </p>
           
           <form method="POST" action="{{ route('2fa.disable') }}" class="space-y-4">
               @csrf
               
               <x-input
                   label="Enter your password to confirm"
                   name="password"
                   type="password"
                   :error="$errors->first('password')"
                   placeholder="••••••••"
                   required
               />
               
               <div class="flex gap-3">
                   <x-button type="submit" variant="danger" class="flex-1">
                       Yes, Disable 2FA
                   </x-button>
                   <x-button type="button" variant="secondary" class="flex-1" @click="disableModal = false">
                       Cancel
                   </x-button>
               </div>
           </form>
       </x-card>
   </div>
   
   <!-- Add Alpine.js support -->
   <script>
   document.addEventListener('alpine:init', () => {
       Alpine.data('twoFactorSettings', () => ({
           disableModal: false,
           
           printCodes() {
               window.print();
           },
           
           copyCodes() {
               const codes = @json(session('recovery_codes', []));
               const text = codes.join('\n');
               navigator.clipboard.writeText(text).then(() => {
                   alert('Recovery codes copied to clipboard!');
               });
           }
       }));
   });
   </script>
   @endsection
   ```

2. **Improve Modal Accessibility**
   Use x-card component with proper ARIA attributes:
   ```blade
   <div 
       x-show="disableModal"
       role="dialog"
       aria-modal="true"
       aria-labelledby="modalTitle"
       class="fixed inset-0 ..."
       @keydown.escape.window="disableModal = false"
   >
       <!-- Content -->
   </div>
   ```

3. **Replace Emoji with SVG Icons**
   Create or use consistent icon set:
   ```blade
   <h1 class="text-4xl font-bold text-text-primary mb-12">
       <span class="inline-flex items-center gap-3">
           <svg class="w-10 h-10 text-primary" fill="currentColor" viewBox="0 0 24 24">
               <!-- Lock icon SVG -->
           </svg>
           Two-Factor Authentication
       </span>
   </h1>
   ```

**MEDIUM PRIORITY:**

4. **Add Recovery Code Status Tracking**
   ```blade
   @if($user->has2FAEnabled())
       <x-card class="mb-8">
           <h3 class="text-lg font-bold text-text-primary mb-4">Recovery Codes Status</h3>
           
           @php
               $usedCount = count($user->used_recovery_codes ?? []);
               $totalCount = 8;
           @endphp
           
           <div class="space-y-4">
               <div class="flex justify-between items-center">
                   <span class="text-text-secondary">Remaining codes: {{ $totalCount - $usedCount }} of {{ $totalCount }}</span>
                   <div class="w-32 h-2 bg-dark-border rounded-full overflow-hidden">
                       <div 
                           class="h-full transition-all {{ ($totalCount - $usedCount) > 3 ? 'bg-status-success' : 'bg-status-warning' }}"
                           style="width: {{ (($totalCount - $usedCount) / $totalCount) * 100 }}%"
                       ></div>
                   </div>
               </div>
               
               @if(($totalCount - $usedCount) <= 2)
                   <x-alert type="warning">
                       You're running low on recovery codes. 
                       <a href="{{ route('2fa.recovery.regenerate') }}" class="font-medium hover:underline">Generate new codes</a>
                   </x-alert>
               @endif
           </div>
       </x-card>
   @endif
   ```

5. **Improve Recovery Codes Page (`two-factor-recovery.blade.php`)**
   Same refactoring approach - replace inline styles with Tailwind and x-components

6. **Add Backup Options**
   ```blade
   <x-card class="mb-8">
       <h3 class="text-lg font-bold text-text-primary mb-4">Backup Options</h3>
       <p class="text-text-secondary mb-4">
           Set up additional backup methods to access your account if needed:
       </p>
       <div class="space-y-3">
           <a href="#" class="flex items-center justify-between p-4 rounded-lg bg-dark-elevated border border-dark-border hover:border-primary transition-colors">
               <div>
                   <h4 class="font-semibold text-text-primary">Backup Email</h4>
                   <p class="text-sm text-text-secondary">{{ auth()->user()->backup_email ?? 'Not set' }}</p>
               </div>
               <span class="text-primary text-sm font-medium">{{ auth()->user()->backup_email ? 'Update' : 'Add' }}</span>
           </a>
       </div>
   </x-card>
   ```

---

## 7. 2FA Enable Page (`two-factor-enable.blade.php`)

### Current Design Review

**Strengths:**
- Clear step-by-step layout
- QR code prominently displayed
- Secret key fallback for manual entry
- Instructions match step numbers
- Code input with verification

**Weaknesses:**
- Inline styles instead of Tailwind
- Step indicator at top not fully utilized
- Manual entry of secret key not copy-friendly
- No progress tracking (which step are we on?)
- Inconsistent styling with app

### Issues Identified

#### 1. Step Indicator Not Functional
**Issue:**
```html
<div class="step active"></div>  <!-- Step 1 -->
<div class="step active"></div>  <!-- Step 2 -->
<div class="step"></div>          <!-- Step 3 -->
```
**Problem:** Visual indicator doesn't update to reflect current step
**UX Impact:** No clear progress through setup

#### 2. Manual Secret Key Hard to Copy
**Issue:** Large monospace text but no copy button
```css
.secret-key-value { font-family: monospace; font-size: 18px; color: #FF8C00; }
```
**Problem:** Users need to manually select and copy
**Better Practice:** Add "Copy Secret Key" button

#### 3. Inline CSS Over-Styling
**Issue:** Uses inline styles for everything instead of Tailwind
**Problem:** Diverges from app design system
**Better Practice:** Use Tailwind + x-components

#### 4. Code Input Validation
**Issue:**
```html
<input type="text" ... pattern="[0-9]{6}" inputmode="numeric" required>
```
**Problem:** Pattern exists but no error display on invalid input
**UX Impact:** Users unsure why submission fails

#### 5. No QR Code Error Handling
**Issue:** If QR code fails to generate, no error shown
**Problem:** User may think setup failed silently

#### 6. Missing Accessibility
**Issue:** No fieldset/legend structure for form sections
**Problem:** Screen readers can't group related inputs

### Recommendations

**HIGH PRIORITY:**

1. **Convert to Design System Components**
   ```blade
   @extends('layouts.main')
   @section('title', 'Enable 2FA - UP STORE')
   
   @section('content')
   <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ currentStep: 3 }">
       <h1 class="text-4xl font-bold text-text-primary mb-4 text-center">Enable Two-Factor Authentication</h1>
       <p class="text-center text-text-secondary mb-12">Follow the steps below to secure your account</p>
       
       <!-- Step Indicator -->
       <div class="flex justify-center gap-4 mb-12">
           <div class="flex flex-col items-center">
               <div :class="`w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-colors ${ currentStep >= 1 ? 'bg-primary text-white' : 'bg-dark-surface text-text-secondary border-2 border-dark-border' }`">
                   1
               </div>
               <span class="text-xs text-text-secondary mt-2 text-center">Download App</span>
           </div>
           
           <div class="flex-1 flex items-start mt-5">
               <div :class="`flex-1 h-1 transition-colors ${ currentStep >= 2 ? 'bg-primary' : 'bg-dark-border' }`"></div>
           </div>
           
           <div class="flex flex-col items-center">
               <div :class="`w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-colors ${ currentStep >= 2 ? 'bg-primary text-white' : 'bg-dark-surface text-text-secondary border-2 border-dark-border' }`">
                   2
               </div>
               <span class="text-xs text-text-secondary mt-2 text-center">Scan QR</span>
           </div>
           
           <div class="flex-1 flex items-start mt-5">
               <div :class="`flex-1 h-1 transition-colors ${ currentStep >= 3 ? 'bg-primary' : 'bg-dark-border' }`"></div>
           </div>
           
           <div class="flex flex-col items-center">
               <div :class="`w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-colors ${ currentStep >= 3 ? 'bg-primary text-white' : 'bg-dark-surface text-text-secondary border-2 border-dark-border' }`">
                   3
               </div>
               <span class="text-xs text-text-secondary mt-2 text-center">Verify</span>
           </div>
       </div>
       
       <x-card class="max-w-lg mx-auto">
           <!-- Instructions -->
           <div class="space-y-6 mb-8">
               <div class="flex gap-4">
                   <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm">
                       1
                   </div>
                   <div>
                       <h3 class="font-semibold text-text-primary">Download an Authenticator App</h3>
                       <p class="text-sm text-text-secondary mt-1">
                           Install Google Authenticator, Microsoft Authenticator, or Authy on your phone
                       </p>
                   </div>
               </div>
               
               <div class="flex gap-4">
                   <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm">
                       2
                   </div>
                   <div>
                       <h3 class="font-semibold text-text-primary">Scan This QR Code</h3>
                       <p class="text-sm text-text-secondary mt-1">
                           Open your authenticator app and scan this QR code with your phone's camera
                       </p>
                   </div>
               </div>
           </div>
           
           <!-- QR Code -->
           <div class="flex justify-center mb-8">
               <div class="bg-white p-6 rounded-lg inline-block">
                   @if(isset($qrCodeSvg))
                       {!! $qrCodeSvg !!}
                   @else
                       <div class="text-center text-status-error-text p-8">
                           <p class="font-semibold mb-1">QR Code Error</p>
                           <p class="text-sm">Enter the secret key manually below</p>
                       </div>
                   @endif
               </div>
           </div>
           
           <!-- Secret Key -->
           <div class="mb-8 p-4 bg-dark-base rounded-lg">
               <p class="text-xs text-text-tertiary mb-3 font-medium">Or enter this code manually:</p>
               <div class="flex items-center justify-between gap-3">
                   <code class="font-mono text-lg text-primary font-bold tracking-wider">{{ $secret }}</code>
                   <button
                       type="button"
                       onclick="copySecret()"
                       class="px-3 py-2 rounded bg-primary text-white text-sm font-medium hover:bg-primary-hover transition-colors"
                   >
                       Copy
                   </button>
               </div>
           </div>
           
           <!-- Divider -->
           <div class="relative my-8">
               <div class="absolute inset-0 flex items-center">
                   <div class="w-full border-t border-dark-border"></div>
               </div>
               <div class="relative flex justify-center text-sm">
                   <span class="px-2 bg-dark-surface text-text-tertiary">STEP 3</span>
               </div>
           </div>
           
           <!-- Verification Form -->
           @if($errors->any())
               <x-alert type="error" class="mb-6">
                   <ul class="space-y-1">
                       @foreach($errors->all() as $error)
                           <li>{{ $error }}</li>
                       @endforeach
                   </ul>
               </x-alert>
           @endif
           
           <form method="POST" action="{{ route('2fa.verify.post') }}" class="space-y-6">
               @csrf
               
               <div>
                   <h3 class="font-semibold text-text-primary mb-4">
                       Enter the 6-digit code from your app
                   </h3>
                   
                   <x-input
                       label="Verification Code"
                       name="code"
                       type="text"
                       placeholder="000000"
                       maxlength="6"
                       pattern="[0-9]{6}"
                       inputmode="numeric"
                       :error="$errors->first('code')"
                       id="codeInput"
                       required
                       autofocus
                       class="text-center text-2xl font-bold tracking-[0.75rem]"
                   />
                   
                   <p class="mt-3 text-xs text-text-tertiary text-center">
                       This code changes every 30 seconds
                   </p>
               </div>
               
               <div class="flex gap-3">
                   <x-button type="submit" variant="primary" class="flex-1">
                       Verify & Enable 2FA
                   </x-button>
                   <a href="{{ route('2fa.show') }}" class="flex-1 inline-flex items-center justify-center px-6 py-3 rounded-lg bg-dark-surface border border-dark-border text-text-secondary hover:text-primary transition-colors font-medium">
                       Cancel
                   </a>
               </div>
           </form>
       </x-card>
   </div>
   
   <script>
   function copySecret() {
       const secret = @json($secret);
       navigator.clipboard.writeText(secret).then(() => {
           const btn = event.target;
           const originalText = btn.textContent;
           btn.textContent = 'Copied!';
           setTimeout(() => {
               btn.textContent = originalText;
           }, 2000);
       });
   }
   
   // Auto-format code input
   document.getElementById('codeInput').addEventListener('input', function(e) {
       this.value = this.value.replace(/[^0-9]/g, '');
   });
   </script>
   @endsection
   ```

2. **Improve QR Code Error Handling**
   ```blade
   @if(isset($qrCodeSvg))
       <div class="flex justify-center mb-8">
           <div class="bg-white p-6 rounded-lg inline-block">
               {!! $qrCodeSvg !!}
           </div>
       </div>
   @else
       <x-alert type="error" class="mb-8">
           <p><strong>QR Code Error:</strong> Could not generate QR code. Enter the secret key manually instead.</p>
       </x-alert>
   @endif
   ```

3. **Enhance Copy-to-Clipboard UX**
   Already included in refactored code above with visual feedback

**MEDIUM PRIORITY:**

4. **Add Time Remaining Indicator**
   ```blade
   <p class="mt-3 text-xs text-text-tertiary text-center">
       Code expires in <span id="timeRemaining">30</span>s
       (use next code if this doesn't work)
   </p>
   
   <script>
   let timeRemaining = 30;
   setInterval(() => {
       timeRemaining = timeRemaining === 0 ? 30 : timeRemaining - 1;
       document.getElementById('timeRemaining').textContent = timeRemaining;
   }, 1000);
   </script>
   ```

5. **Add Backup Option Link**
   ```blade
   <div class="text-center">
       <p class="text-xs text-text-tertiary mb-2">
           Don't have an authenticator app?
       </p>
       <a href="#" class="text-primary text-sm hover:text-primary-400 transition-colors">
           Set up backup authentication method
       </a>
   </div>
   ```

---

## Summary of Issues by Priority

### Critical (Immediate)
1. **All pages:** Field-level error display missing
2. **Login/Register:** Accessibility violations (missing id/for relationships)
3. **All 2FA pages:** Inline styles instead of design system
4. **2FA Verify:** No TOTP countdown timer

### High Priority
1. **Register:** Phone field should be `type="tel"`
2. **Register:** Add password strength indicator
3. **Reset Password:** Add password requirements display
4. **Forgot Password:** Create confirmation/sent page
5. **2FA Settings:** Refactor to use components (not inline styles)
6. **2FA Enable:** Add secret key copy button
7. **All 2FA:** Replace emoji with SVG icons

### Medium Priority
1. **Register:** Username requirement hints
2. **Login:** Remove Google alert anti-pattern
3. **2FA Verify:** Improve recovery code mode toggle
4. **All pages:** Add autocomplete attributes
5. **2FA Settings:** Add recovery code usage tracking

---

## Implementation Roadmap

**Phase 1 (Critical Fixes - 1 week)**
- Add field-level error display via x-input enhancement
- Fix accessibility violations (id/for relationships)
- Add TOTP countdown to 2FA verify page
- Convert phone input to type="tel"

**Phase 2 (Design System - 1 week)**
- Refactor 2FA Settings page to use Tailwind + x-components
- Refactor 2FA Enable page same way
- Refactor 2FA Recovery page same way
- Remove all inline styles from auth pages

**Phase 3 (UX Improvements - 1 week)**
- Add password strength indicators (register + reset)
- Add field requirement hints
- Improve error messaging and handling
- Add confirmation pages (password reset sent)

**Phase 4 (Polish - 1 week)**
- Add loading states to submit buttons
- Add accessibility enhancements (ARIA labels, focus management)
- Add success notifications
- Test all pages for mobile responsiveness

---

## Testing Checklist

All authentication pages should be tested for:
- [ ] Desktop (1920px+), Tablet (768px), Mobile (375px)
- [ ] Form submission with valid/invalid data
- [ ] Error message display and clarity
- [ ] Password field reveal toggle (if added)
- [ ] Loading states during submission
- [ ] Keyboard navigation (Tab, Enter, Escape)
- [ ] Screen reader compatibility (NVDA, JAWS)
- [ ] Password manager integration (autofill)
- [ ] Mobile autofill (Android, iOS)
- [ ] Email client links for reset/verification links

