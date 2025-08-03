<div
    class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8"
    x-data="loginForm()"
>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <svg
                class="h-12 w-12 text-indigo-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"
                />
            </svg>
        </div>
        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">Sign in to your account</h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            No account?
            <a
                href="/register"
                class="font-medium text-indigo-600 hover:text-indigo-500"
            >Register here</a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <!-- Success Message -->
            <?php if (isset($_GET['registered'])): ?>
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg
                                class="h-5 w-5 text-green-400"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">Registration successful! You can now log in.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form
                class="space-y-6"
                method="POST"
                action="/login"
                @submit.prevent="handleSubmit"
            >
                <!-- Email Field -->
                <div>
                    <label
                        for="email"
                        class="block text-sm font-medium text-gray-700"
                    >Email address</label>
                    <div class="mt-1">
                        <input
                            id="email"
                            name="email"
                            type="email"
                            required
                            x-model="form.email"
                            :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors.email, 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500': !errors.email}"
                            class="block w-full appearance-none rounded-md border px-3 py-2 placeholder-gray-400 shadow-sm focus:outline-none sm:text-sm"
                        >
                    </div>
                    <p
                        class="mt-1 text-sm text-red-600"
                        x-show="errors.email"
                        x-text="errors.email"
                    ></p>
                </div>

                <!-- Password Field -->
                <div>
                    <label
                        for="password"
                        class="block text-sm font-medium text-gray-700"
                    >Password</label>
                    <div class="mt-1">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            x-model="form.password"
                            :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors.password, 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500': !errors.password}"
                            class="block w-full appearance-none rounded-md border px-3 py-2 placeholder-gray-400 shadow-sm focus:outline-none sm:text-sm"
                        >
                    </div>
                    <p
                        class="mt-1 text-sm text-red-600"
                        x-show="errors.password"
                        x-text="errors.password"
                    ></p>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            id="remember-me"
                            name="remember-me"
                            type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                        >
                        <label
                            for="remember-me"
                            class="ml-2 block text-sm text-gray-900"
                        >Remember me</label>
                    </div>

                    <div class="text-sm">
                        <a
                            href="#"
                            class="font-medium text-indigo-600 hover:text-indigo-500"
                        >Forgot your password?</a>
                    </div>
                </div>

                <!-- Error Messages -->
                <?php if (isset($error)): ?>
                    <div class="rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg
                                    class="h-5 w-5 text-red-400"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Authentication error</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p><?= htmlspecialchars($error) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Submit Button -->
                <div>
                    <button
                        type="submit"
                        :disabled="isSubmitting || !isFormValid"
                        :class="{'opacity-50 cursor-not-allowed': isSubmitting || !isFormValid, 'hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500': !isSubmitting && isFormValid}"
                        class="flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm transition duration-150 ease-in-out"
                    >
                        <span x-show="!isSubmitting">Sign in</span>
                        <span
                            x-show="isSubmitting"
                            class="flex items-center"
                        >
                            <svg
                                class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function loginForm() {
        return {
            form: {
                email: '<?= htmlspecialchars($old_email ?? '') ?>',
                password: ''
            },
            errors: {
                email: '',
                password: ''
            },
            isSubmitting: false,

            validateForm() {
                // Email validation
                if (!this.form.email.trim()) {
                    this.errors.email = 'Email is required';
                } else if (!this.isValidEmail(this.form.email)) {
                    this.errors.email = 'Enter a valid email';
                } else {
                    this.errors.email = '';
                }

                // Password validation
                if (!this.form.password) {
                    this.errors.password = 'Password is required';
                } else {
                    this.errors.password = '';
                }
            },

            isValidEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            },

            get isFormValid() {
                return (
                    this.form.email.trim() &&
                    this.isValidEmail(this.form.email) &&
                    this.form.password.length > 0 &&
                    !this.errors.email &&
                    !this.errors.password
                );
            },

            handleSubmit() {
                this.validateForm();

                if (this.isFormValid) {
                    this.isSubmitting = true;
                    // Submit the form via standard submit
                    setTimeout(() => {
                        this.$el.submit();
                    }, 100);
                }
            }
        };
    }
</script>