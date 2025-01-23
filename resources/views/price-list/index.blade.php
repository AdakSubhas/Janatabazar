<!-- File export table -->
@include('layouts.header')
<div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">Price List </h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Price List
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div>
                        <form method="post" action="{{ route('upload.csv') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row gy-4">
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-control-wrap">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="customFile" name="daily_price_list" onchange="updateFileName(this)">
                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                            </div>
                                           
                                        </div>
                                        @error('csv_upload_t1')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-primary uploadbtn w-100">Upload</button>
                                </div>
                                 
                                <div class="col-sm-3">
                                    <a href="<?= env('APP_URL')?>/datafile/daily_price_list.csv" class="btn btn-success uploadbtn w-100"><span>Download Sample Format</span>
                                    <em class="icon ni ni-download"></em>
                                    </a>
                                </div>
                            </div>
                        </form>
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
                                                    <th>Sl No</th>
                                                    <th>State</th>
                                                    <th>District</th>
                                                    <th>Pincode</th>
                                                    <th>Category</th>
                                                    <th>Product</th>
                                                    <th>Price</th>
                                                    <th>Status</th>
                                                    <th>Last Updated</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data as $value)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{   DB::table('states')->where('id',$value->state_id)->value('name')  }}</td>
                                                    <td>{{   DB::table('districts')->where('id',$value->district_id)->value('name')  }}</td>
                                                    <td>{{   DB::table('pincodes')->where('id',$value->pin_id)->value('pincode')  }}</td>
                                                    <td>{{   DB::table('product_categories')->where('id',$value->category_id)->value('category_name')  }}</td>
                                                    <td>{{   DB::table('products')->where('id',$value->product_id)->value('item')  }}</td>
                                                    <td>{{$value->price}}</td>
                                                    @if($value->status == 1)
                                                    <td>Active</td>
                                                    @else
                                                    <td>Inactive</td>
                                                    @endif
                                                    @if(isset($value->updated_at))
                                                    <td>{{$value->updated_at}}</td>
                                                    @else
                                                    <td>{{$value->created_at}}</td>
                                                    @endif
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Sl No</th>
                                                    <th>State</th>
                                                    <th>District</th>
                                                    <th>Pincode</th>
                                                    <th>Category</th>
                                                    <th>Product</th>
                                                    <th>Price</th>
                                                    <th>Status</th>
                                                    <th>Last Updated</th>
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
    <script>
        function updateFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : "Choose file";
            const label = input.nextElementSibling; // The label element
            label.textContent = fileName;
        }

    </script>
@include('layouts.footer')