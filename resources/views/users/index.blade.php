<!-- File export table -->
@include('layouts.header')
<div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">Users </h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Users
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                        <button class="btn btn-info round dropdown-toggle dropdown-menu-right box-shadow-2 px-2 mb-1" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ft-settings icon-left"></i> Settings</button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1"><a class="dropdown-item" href="card-bootstrap.html">Cards</a><a class="dropdown-item" href="component-buttons-extended.html">Buttons</a></div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- File export table -->
                <section id="file-export">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
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
                                    <div class="card-body card-dashboard dataTables_wrapper dt-bootstrap">
                                        <table class="table table-striped table-bordered file-export">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th>Mobile</th>
                                                    <th>Email</th>
                                                    <th>Address</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data as $value)
                                                <tr>
                                                    <td>{{$value->name}}</td>
                                                    <td>{{$value->type}}</td>
                                                    <td>{{$value->mobile}}</td>
                                                    <td>{{$value->email}}</td>
                                                    <td>{{$value->address}}</td>
                                                    @if($value->status == 1)
                                                    <td>Active</td>
                                                    @else
                                                    <td>Inactive</td>
                                                    @endif
                                                    <td>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a href="{{url('edit-user/'.$value->id)}}"><button type="button" class="btn  btn-warning mx-1">
                                                                <i class="simple-icon-note" data-toggle="tooltip" data-placement="top" data-original-title="Edit">Edit</i>
                                                            </button></a>
                                                            <a href="{{ url('delete-user/'.$value->id) }}"><button type="button" class="btn btn-danger">
                                                                <i class="simple-icon-trash" data-toggle="tooltip" data-placement="top" data-original-title="Delete">Delete</i>
                                                            </button></a>
                                                            
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th>Mobile</th>
                                                    <th>Email</th>
                                                    <th>Address</th>
                                                    <th>Status</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- File export table -->

            </div>
        </div>
    </div>
@include('layouts.footer')