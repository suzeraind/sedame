<div
    class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8"
    x-data="registrationForm()"
    x-init="init()"
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
                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                />
            </svg>
        </div>
        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">Create an account</h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Already have an account?
            <a
                href="/login"
                class="font-medium text-indigo-600 hover:text-indigo-500"
            >Log in here</a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <!-- Success Message -->
            <?php if (isset($_GET["registered"])): ?>
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
                action="/register"
                @submit.prevent="handleSubmit"
            >
                <!-- Name Field -->
                <div>
                    <label
                        for="name"
                        class="block text-sm font-medium text-gray-700"
                    >Name</label>
                    <div class="mt-1 relative">
                        <input
                            id="name"
                            name="name"
                            type="text"
                            required
                            x-model="form.name"
                            :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors.name, 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500': !errors.name}"
                            class="block w-full appearance-none rounded-md border px-3 py-2 placeholder-gray-400 shadow-sm focus:outline-none sm:text-sm"
                        >
                        <div
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            x-show="form.name && form.name.length >= 2"
                        >
                            <svg
                                class="h-5 w-5 text-green-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                        </div>
                    </div>
                    <p
                        class="mt-1 text-sm text-gray-500"
                        x-show="!form.name"
                    >Enter your name</p>
                    <p
                        class="mt-1 text-sm text-red-600"
                        x-show="errors.name"
                        x-text="errors.name"
                    ></p>
                </div>

                <!-- Email Field -->
                <div>
                    <label
                        for="email"
                        class="block text-sm font-medium text-gray-700"
                    >Email address</label>
                    <div class="mt-1 relative">
                        <input
                            id="email"
                            name="email"
                            type="email"
                            required
                            x-model="form.email"
                            :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors.email, 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500': !errors.email}"
                            class="block w-full appearance-none rounded-md border px-3 py-2 placeholder-gray-400 shadow-sm focus:outline-none sm:text-sm"
                        >
                        <div
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            x-show="form.email && isValidEmail(form.email)"
                        >
                            <svg
                                class="h-5 w-5 text-green-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                        </div>
                    </div>
                    <p
                        class="mt-1 text-sm text-gray-500"
                        x-show="!form.email"
                    >Enter a valid email</p>
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
                    <div class="mt-1 relative">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            x-model="form.password"
                            :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors.password, 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500': !errors.password}"
                            class="block w-full appearance-none rounded-md border px-3 py-2 placeholder-gray-400 shadow-sm focus:outline-none sm:text-sm"
                        >
                        <div
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            x-show="form.password && form.password.length >= 6"
                        >
                            <svg
                                class="h-5 w-5 text-green-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-1">
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div
                                class="bg-blue-600 h-1.5 rounded-full transition-all duration-300"
                                :style="`width: ${passwordStrength.percentage}%`"
                            ></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span x-text="passwordStrength.label"></span>
                            <span x-show="form.password.length > 0"><span x-text="form.password.length"></span>/6
                                characters</span>
                        </div>
                    </div>
                    <p
                        class="mt-1 text-sm text-red-600"
                        x-show="errors.password"
                        x-text="errors.password"
                    ></p>
                </div>

                <!-- Password Confirm Field -->
                <div>
                    <label
                        for="password_confirm"
                        class="block text-sm font-medium text-gray-700"
                    >Confirm Password</label>
                    <div class="mt-1 relative">
                        <input
                            id="password_confirm"
                            name="password_confirm"
                            type="password"
                            required
                            x-model="form.password_confirm"
                            :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors.password_confirm, 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500': !errors.password_confirm}"
                            class="block w-full appearance-none rounded-md border px-3 py-2 placeholder-gray-400 shadow-sm focus:outline-none sm:text-sm"
                        >
                        <div
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            x-show="form.password_confirm && form.password_confirm === form.password"
                        >
                            <svg
                                class="h-5 w-5 text-green-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                        </div>
                    </div>
                    <p
                        class="mt-1 text-sm text-red-600"
                        x-show="errors.password_confirm"
                        x-text="errors.password_confirm"
                    ></p>
                </div>

                <!-- Error Messages -->
                <?php if (isset($errors) && !empty($errors)): ?>
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
                                <h3 class="text-sm font-medium text-red-800">Registration errors:</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc space-y-1 pl-5">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?= htmlspecialchars($error) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
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
                        <span x-show="!isSubmitting">Create account</span>
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
    function registrationForm() {
        return {
            form: {
                name: '<?= htmlspecialchars($old["name"] ?? "") ?>',
                email: '<?= htmlspecialchars($old["email"] ?? "") ?>',
                password: '',
                password_confirm: ''
            },
            errors: {
                name: '',
                email: '',
                password: '',
                password_confirm: ''
            },
            isSubmitting: false,

            init() {
                // Initialize with already passed errors from PHP
                <?php if (isset($errors) && !empty($errors)): ?>
                                this.validateForm();
                    <?php endif; ?>
                },
                
                validateForm() {
                    // Name validation
                    if (!this.form.name.trim()) {
                        this.errors.name = 'Name is required';
                    } else if (this.form.name.length < 2) {
                        this.errors.name = 'Name must be at least 2 characters';
                    } else {
                        this.errors.name = '';
                    }
                    
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
                    } else if (this.form.password.length < 6) {
                        this.errors.password = 'Password must be at least 6 characters';
                    } else {
                        this.errors.password = '';
                    }
                    
                    // Password confirmation validation
                    if (this.form.password !== this.form.password_confirm) {
                        this.errors.password_confirm = 'Passwords do not match';
                    } else {
                        this.errors.password_confirm = '';
                    }
                },
                
                isValidEmail(email) {
                    const re = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;
                    return re.test(email);
                },
                
                get passwordStrength() {
                    const length = this.form.password.length;
                    if (length === 0) return { percentage: 0, label: 'No password entered' };
                    if (length < 3) return { percentage: 20, label: 'Weak' };
                    if (length < 6) return { percentage: 50, label: 'Medium' };
                    return { percentage: 100, label: 'Strong' };
                },
                
                get isFormValid() {
                    return this.form.name.trim() && 
                           this.form.name.length >= 2 &&
                           this.form.email.trim() && 
                           this.isValidEmail(this.form.email) &&
                           this.form.password.length >= 6 &&
                           this.form.password === this.form.password_confirm &&
                           !this.errors.name && 
                           !this.errors.email && 
                           !this.errors.password && 
                           !this.errors.password_confirm;
                },
                
                handleSubmit() {
                    this.validateForm();
                    
                    if (this.isFormValid) {
                        this.isSubmitting = true;
                        setTimeout(() => {
                            this.$el.submit();
                        }, 100);
                    }
                }
            }
        }
    }
</script>