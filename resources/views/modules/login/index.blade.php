@extends('layouts.blank')

@section('title', $title)

@section('styles')
  
@endsection

@section('content')
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">
      <!-- Register -->
      <div class="card">
        <div class="card-body">
          <div class="app-brand justify-content-center">
            <a href="{{ url('/') }}" class="app-brand-link gap-2">
              <span class="app-brand-logo demo">
                <img src="{{ asset('assets/img/logo.png') }}" style="width: 100%;" alt="">
              </span>
              <span class="app-brand-text demo h3 mb-0 fw-bold">Trento HRIS</span>
            </a>
          </div>
          <form id="formSubmit" class="mb-3">
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input
                type="text"
                class="form-control"
                name="username"
                placeholder="Enter username"
                autofocus
              />
            </div>
            <div class="mb-3 form-password-toggle">
              <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Password</label>
              </div>
              <div class="input-group input-group-merge">
                <input
                  type="password"
                  class="form-control"
                  name="password"
                  placeholder="············"
                />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
            </div>
          </form>

        </div>
      </div>
      <!-- /Register -->
    </div>
  </div>
@endsection

@section('scripts')
    <script>

      $(document).on('submit', '#formSubmit', function(e) {
        e.preventDefault()
        
        const formID = 'formSubmit'
        const formData = new FormData($('#'+formID).get(0))

        apiCall(`/api/authentication/login/`, 'POST', formData, 
            // beforesend
            function() {
              btnLoading(`#${formID} button[type="submit"]`, `Loading...`)
            }, 
            // done
            function(res) {
              if (res.status == 200) {
                localStorage.setItem('fp', res.items.deviceFingerprint)
                localStorage.setItem('t', res.items.token)
                authenticationVerify()
              } else {
                Toast.fire({ icon : "warning", title : res.name, html : res.message })
              }
            }, 
            // always
            function() {
              btnLoading(`#${formID} button[type="submit"]`, `Sign in`, 0)
            }, 
            '' 
        )

      })

    </script>
@endsection
