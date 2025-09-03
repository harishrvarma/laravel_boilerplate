<div class="login-box mx-auto mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-header bg-dark text-white text-center">
            <h3>User Login</h3>
        </div>

        <div class="card-body login-card-body">

            {{-- Flash or Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf

                {{-- Email --}}
                <div class="input-group mb-3">
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        placeholder="Email"
                        value="{{ old('email') }}"
                        required
                    >
                    <span class="input-group-text">
                        <i class="fas fa-envelope"></i>
                    </span>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="input-group mb-3">
                    <input 
                        type="password" 
                        name="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        placeholder="Password"
                        required
                    >
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
