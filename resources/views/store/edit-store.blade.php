@include('layouts.header')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title">Edit Store</h3>
                <div class="row breadcrumbs-top">
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Basic form layout section start -->
            <section id="basic-form-layouts">

                <div class="row justify-content-md-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" id="basic-layout-card-center">Edit Store</h4>
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                        <li><a data-action="close"><i class="ft-x"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <form class="form" action="{{route('update-store', $data->id)}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="la la-eye"></i> About Store</h4>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="userinput1">Name</label>
                                                        <input type="text" id="userinput1" class="form-control border-primary" placeholder="Enter Name" name="name" value="{{ $data->name }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="userinput2">Store Name</label>
                                                        <input type="text" id="userinput2" class="form-control border-primary" placeholder="Enter store name" name="sname" value="{{ $data->store_name }}"> 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="userinput3">Username</label>
                                                        <input type="text" id="userinput3" class="form-control border-primary" placeholder="Enter username" name="username" value="{{ $data->username }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="userinput4">Store Image</label>
                                                        <input type="file" id="userinput4" class="form-control border-primary" placeholder="upload image" name="storeimg" value="{{ $data->storeimg }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="userinput3">Address</label>
                                                        <input type="text" id="userinput3" class="form-control border-primary" placeholder="Enter address" name="address" value="{{ $data->address }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="userinput4">Zip Code</label>
                                                        <input type="text" id="userinput4" class="form-control border-primary" placeholder="Enter zip" name="zip" value="{{ $data->zip_code }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="userinput4">Status</label>
                                                        <select id="userinput4" class="form-control border-primary" name="status" value="{{ $data->status }}">
                                                            <option value="1" {{ isset($data) && $data->status == 1 ? 'selected' : '' }}>Active</option>
                                                            <option value="0" {{ isset($data) && $data->status == 0 ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="userinput4">Activity</label>
                                                        <select id="userinput4" class="form-control border-primary" name="activity" value="{{ $data->activity }}">
                                                            <option value="1" {{ isset($data) && $data->status == 1 ? 'selected' : '' }}>Online</option>
                                                            <option value="0" {{ isset($data) && $data->status == 0 ? 'selected' : '' }}>Offline</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                            </div>

                                            <h4 class="form-section"><i class="ft-mail"></i> Contact Info</h4>

                                            <div class="form-group">
                                                <label for="userinput5">Email</label>
                                                <input class="form-control border-primary" type="email" placeholder="email" id="userinput5" name="email" value="{{ $data->email }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Contact Number</label>
                                                <input class="form-control border-primary" id="userinput7" type="tel" placeholder="Contact Number" name="mobile" value="{{ $data->mobile }}"> 
                                            </div>
                                        </div>

                                        <div class="form-actions text-right">
                                            <a href="{{route('store-list')}}"><button type="button" class="btn btn-warning mr-1">
                                                <i class="ft-x"></i> Cancel
                                            </button></a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="la la-check-square-o"></i> Submit
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- // Basic form layout section end -->
        </div>
    </div>
</div>
@include('layouts.footer')
