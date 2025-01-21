@include('layouts.header')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title">Edit User</h3>
                <div class="row breadcrumbs-top"></div>
            </div>
        </div>
        <div class="content-body">
            <section id="basic-form-layouts">
                <div class="row justify-content-md-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" id="basic-layout-card-center">Edit User</h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <form class="form" action="{{ route('update-user', $user->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="la la-eye"></i> About User</h4>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="userinput1">Name</label>
                                                        <input type="text" id="userinput1" class="form-control border-primary" placeholder="Name" name="name" value="{{ $user->name }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="userinput2">Username</label>
                                                        <input type="text" id="userinput2" class="form-control border-primary" placeholder="Username" name="username" value="{{ $user->username }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="userinput3">Address</label>
                                                        <input type="text" id="userinput3" class="form-control border-primary" placeholder="Address" name="address" value="{{ $user->address }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="userinput4">Profile Image</label>
                                                        <input type="file" id="userinput4" class="form-control border-primary" name="profilei">
                                                        @if ($user->profile_image)
                                                            <p class="mt-2">Current Image: <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image" style="height: 50px;"></p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="userinput3">Password</label>
                                                        <input type="password" id="userinput3" class="form-control border-primary" placeholder="Leave blank to keep current password" name="password">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="userinput4">Status</label>
                                                        <select id="userinput4" class="form-control border-primary" name="status">
                                                            <option value="1" {{ isset($user) && $user->status == 1 ? 'selected' : '' }}>Active</option>
                                                            <option value="0" {{ isset($user) && $user->status == 0 ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                    
                                            <h4 class="form-section"><i class="ft-mail"></i> Contact Info</h4>
                                    
                                            <div class="form-group">
                                                <label for="userinput5">Email</label>
                                                <input class="form-control border-primary" type="email" placeholder="Email" id="userinput5" name="email" value="{{ $user->email }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Contact Number</label>
                                                <input class="form-control border-primary" id="userinput7" type="tel" placeholder="Contact Number" name="mobile" value="{{ $user->mobile }}">
                                            </div>
                                        </div>
                                    
                                        <div class="form-actions text-right">
                                            <a href="{{ route('add-user') }}"><button type="button" class="btn btn-warning mr-1">
                                                <i class="ft-x"></i> Cancel
                                            </button></a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="la la-check-square-o"></i> Update
                                            </button>
                                        </div>
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@include('layouts.footer')
