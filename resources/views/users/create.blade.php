
    <!-- CONTENT -->
    <main class="main_content" aria-label="content">
        <div class="container-fluid pt-2 pb-4">
            <div class="row mt-2 justify-content-center">
                <div style="max-width: 500px;">
                    <!-- Page Title -->
                    <div class="row mt-2">
                        <div class="col col-xs-12">
                            <h4 class="display-6 text-center">
                                Sign Up
                            </h4>
                        </div>
                    </div>

                    <form action="{{ route('Register') }}" method="post" class="mt-3">
                        @csrf
                        <div class="row d-flex align-item-center flex-column gap-3">
                            <div class="d-flex flex-column gap-1 column-gap-2">
                                <label class="labels" for="name">Name</label>
                                <input type="text" class="form-control" placeholder="John" value="{{ old('name') }}"
                                    name="name">
                                @if ($errors->has('name'))
                                    <div class="error" style="color: red">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                            <div class="d-flex flex-column gap-1">
                                <label class="labels" for="email">Email</label>
                                <input type="email" class="form-control" placeholder="example@gmail.com"
                                    value="{{ old('email') }}" name="email">
                                @if ($errors->has('email'))
                                    <div class="error" style="color: red">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                            <div class="d-flex flex-column gap-1">
                                <label class="labels" for="password">Password</label>
                                <input type="password" class="form-control" placeholder="Password" value=""
                                    name="password">
                                @if ($errors->has('password'))
                                    <div class="error" style="color: red">{{ $errors->first('password') }}</div>
                                @endif
                            </div>
                            <div class="d-flex flex-column gap-1">
                                <label class="labels" for="confirm_password">Confirm Password</label>
                                <input type="password" class="form-control" placeholder="Confirm Password" value=""
                                    name="confirm_password">
                                @if ($errors->has('confirm_password'))
                                    <div class="error" style="color: red">{{ $errors->first('confirm_password') }}</div>
                                @endif
                            </div>

                            <div class="d-flex align-item-center justify-content-between gap-3 mt-3">
                               
                                <input type="submit" value="Add" class="btn btn-primary profile-button w-50">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    {{-- </div> --}}
    </main>
    <!-- CONTENT END -->
    </div>

