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
        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">Вход в аккаунт</h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Нет аккаунта?
            <a
                href="/register"
                class="font-medium text-indigo-600 hover:text-indigo-500"
            >Зарегистрируйтесь</a>
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
                            <p class="text-sm font-medium text-green-800">Регистрация успешна! Теперь вы можете войти.</p>
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
                    >Email адрес</label>
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
                    >Пароль</label>
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
                        >Запомнить меня</label>
                    </div>

                    <div class="text-sm">
                        <a
                            href="#"
                            class="font-medium text-indigo-600 hover:text-indigo-500"
                        >Забыли пароль?</a>
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
                                <h3 class="text-sm font-medium text-red-800">Ошибка авторизации</h3>
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
                        <span x-show="!isSubmitting">Войти</span>
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
                            Обработка...
                        </span>
                    </button>
                </div>
            </form>

            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="bg-white px-2 text-gray-500">Или продолжить с</span>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-3">
                <div>
                    <a
                        href="#"
                        class="inline-flex w-full justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-500 shadow-sm hover:bg-gray-50"
                    >
                        <span class="sr-only">Sign in with Google</span>
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                        >
                            <path
                                d="M12.24 10.285V14.4h6.806c-.275 1.765-2.056 5.174-6.806 5.174-4.095 0-7.439-3.389-7.439-7.574s3.345-7.574 7.439-7.574c2.33 0 3.891.989 4.785 1.849l3.254-3.138C18.189 1.186 15.479 0 12.24 0c-6.635 0-12 5.365-12 12s5.365 12 12 12c6.926 0 11.52-4.869 11.52-11.726 0-.788-.085-1.39-.189-1.989H12.24z"
                            />
                        </svg>
                    </a>
                </div>
                <div>
                    <a
                        href="#"
                        class="inline-flex w-full justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-500 shadow-sm hover:bg-gray-50"
                    >
                        <span class="sr-only">Sign in with GitHub</span>
                        <svg
                            class="h-5 w-5"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
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
                // Валидация email
                if (!this.form.email.trim()) {
                    this.errors.email = 'Email обязателен';
                } else if (!this.isValidEmail(this.form.email)) {
                    this.errors.email = 'Введите корректный email';
                } else {
                    this.errors.email = '';
                }

                // Валидация пароля
                if (!this.form.password) {
                    this.errors.password = 'Пароль обязателен';
                } else {
                    this.errors.password = '';
                }
            },

            isValidEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            },

            get isFormValid() {
                return this.form.email.trim() &&
                    this.isValidEmail(this.form.email) &&
                    this.form.password.length > 0 &&
                    !this.errors.email &&
                    !this.errors.password;
            },

            handleSubmit() {
                this.validateForm();

                if (this.isFormValid) {
                    this.isSubmitting = true;
                    // Отправляем форму через стандартный submit
                    setTimeout(() => {
                        this.$el.submit();
                    }, 100);
                }
            }
        };
    }
</script>