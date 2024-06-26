<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Deals
        </h2>
    </x-slot>

    <div class="py-12" style="font-size:12px;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5">

                <div class="container mt-4">
                    <div class="d-flex justify-content-end w-full">
                        <button class="btn btn-primary mb-3 " style="font-size:12px;" data-toggle="modal" data-target="#addModal">Add Deal</button>
                    </div>

                    <div class="Deals-item">
                      <div class="overflow-auto">
                        <table class="table table-bordered">
                          <thead class="thead-dark">
                            <tr>
                              <th>Id</th>
                              <th>Deal Name</th>
                              <th>Client</th>
                              <th>Work Type</th>
                              <th>Source Type</th>
                              <th>Deal Date</th>
                              <th>Deal Created</th>
                              <th>Due Date</th>

                              <th>Deal Version</th>
                              <th>Deal Value</th>
                              <th>Payments</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if ($deals->isNotEmpty())

                            @foreach ($deals as $deal)

                            <tr>

                                    <td>{{ $deal->id }}</td>
                                    <td>{{ $deal->name }}</td>
                                    <td>{{ $deal->client->name }}</td>
                                    <td>{{ $deal->work_type }}</td>
                                    <td>{{ $deal->sourceType->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($deal->deal_date)->format('jS F, Y h:i A') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($deal->created_at)->format('jS F, Y h:i A') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($deal->due_date)->format('jS F, Y h:i A') }}</td>
                                    <td>{{ $deal->version }}</td>
                                    <td>{{ $deal->deal_value }}</td>
                                    <td>
                                        @if ($deal->payments != null)
                                            @foreach ($deal->payments as $payment)
                                                @if ($payment->status == 0)
                                                    <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @endif
                                                @if ($payment->status == 1)
                                                    <svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @endif

                                            @endforeach

                                        @endif

                                    </td>
                                    <td class="row gap-2">

                                        @if ($deal->payments->isNotEmpty())
                                            <button class="btn btn-sm btn-info" onclick="toggleSubTable(this)"><i class="fas fa-chevron-down"></i></button>

                                        @endif
                                    </td>

                            </tr>



                            @if($deal->payments->isNotEmpty())
                            <tr class="sub-table" style="display: none;background-color:rgb(203, 233, 175)">
                                <td colspan="12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Payment Date</th>
                                                    <th>Amount</th>
                                                    <th>Invoice</th>
                                                    <th>Remarks</th>
                                                    <th>Verified By</th>
                                                    <th>Verified At</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($deal->payments as $payment)

                                                    <tr>

                                                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('jS F, Y h:i A') }}</td>
                                                        <td>{{ $payment->payment_value }}</td>
                                                        <td>{{ $payment->receipt_path }}</td>
                                                        <td>{{ $payment->remarks }}</td>
                                                        <td>{{ $payment->verified_by_id ? $payment->verified_by->name : "N/A" }}</td>
                                                        <td>{{ $payment->verified_at ? \Carbon\Carbon::parse($payment->verified_at)->format('jS F, Y h:i A') : "N/A" }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#approvePayment" onclick="approvePayment('{{ $payment->id }}')"><i class="fas fa-edit"></i></button>

                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                            @endif
                        </tbody>

                        </table>
                      </div>
                    </div>

                  </div>

                  <div class="modal fade" id="approvePayment" tabindex="-1" role="dialog" aria-labelledby="approvePaymentLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document"> <!-- modal-lg for large modal -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="approvePaymentLabel">Approve Payment <span id="payment-id"></span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="approvePaymentForm">
                                <div class="modal-body">
                                    <div class="container mx-auto py-8">
                                        <div class="flex justify-center col-md-12">

                                            <!-- Payment Section -->
                                            < div class=" row w-full col-md-12 rounded-lg shadow-lg p-6">
                                                <div class="form-group col-md-6">
                                                    <label for="payment_id">Payment Id</label>
                                                    <span id="payment_id"></span>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="payment_deal_id">Deal Id</label>
                                                    <span id="payment_deal_id" ></span>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="payment_date">Payment Date</label>
                                                    <span id="payment_date" ></span>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="payment_value">Amount</label>
                                                    <span id="payment_value" ></span>

                                                </div>


                                                <div class="form-group col-md-6">
                                                    <label for="payment_remarks">Remarks</label>
                                                    <span id="payment_remarks"></span>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <img id="receipt_image_path" src="" alt="payment"/>
                                                    </div>

                                                </div>

                                            </div>
                                            < div class=" row w-full col-md-12 rounded-lg shadow-lg p-6">
                                                <div class="form-group col-md-6 col-12">
                                                    <label for="status">Status</label>
                                                    <select id="status" name="status" class="form-control">
                                                        <option value="0" >Rejected</option>
                                                        <option value="1">Approved</option>

                                                    </select>
                                                    <p></p>

                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="verification_remarks">Remarks</label>
                                                    <textarea id="verification_remarks" name="verification_remarks" class="form-control" required></textarea>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <input type="hidden" id="verification_receipt_id" name="verification_receipt_id" value="">
                                                    <label for="verification_receipt">Verification Receipt</label>
                                                    <div id="verification_receipt" class="dropzone dz-clickable">
                                                        <div class="dz-message needsclick">
                                                            <br>Drop files here or click to upload. <br><br>
                                                        </div>
                                                    </div>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <img id="verification_receipt_image_path" src="" alt="payment"/>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>


        </div>
    </div>
    @section('customJs')
        <script>

            // Example JavaScript specific to this page
            function toggleSubTable(button) {
                const row = button.closest('tr');
                const subTable = row.nextElementSibling;

                if (subTable.classList.contains('sub-table')) {
                    subTable.style.display = subTable.style.display === 'none' ? 'table-row' : 'none';
                }
            }

            function handleEditFieldError(fieldName, errors) {
                var fieldElement = $('#edit-' + fieldName);
                var errorElement = fieldElement.siblings('p');

                if (errors[fieldName]) {
                    fieldElement.addClass('is-invalid');
                    errorElement.addClass('invalid-feedback').html(errors[fieldName][0]);
                } else {
                    fieldElement.removeClass('is-invalid');
                    errorElement.removeClass('invalid-feedback').html('');
                }
            }

            function handleFieldError(fieldName, errors) {
                var fieldElement = $("#" + fieldName);
                var errorElement = fieldElement.siblings('p');

                if (errors[fieldName]) {
                    fieldElement.addClass('is-invalid');
                    errorElement.addClass('invalid-feedback').html(errors[fieldName][0]);
                } else {
                    fieldElement.removeClass('is-invalid');
                    errorElement.removeClass('invalid-feedback').html("");
                }
            }


            function timestampToDate(timestamp) {
                // Create a new Date object from the timestamp (milliseconds since Unix epoch)
                const date = new Date(timestamp);

                // Extract the year, month, day, hours, and minutes from the Date object
                const year = date.getFullYear();
                const monthNames = ["January", "February", "March", "April", "May", "June",
                                    "July", "August", "September", "October", "November", "December"];
                const month = monthNames[date.getMonth()]; // Get the month name
                const day = date.getDate(); // Day of the month
                let hours = date.getHours();
                const minutes = ('0' + date.getMinutes()).slice(-2); // Adding leading zero if needed
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12; // The hour '0' should be '12'

                // Format the date in 10 March, 2022 11:00AM
                const formattedDate = `${day} ${month}, ${year} ${hours}:${minutes}${ampm}`;

                return formattedDate;
            }

            function approvePayment($id) {

                $('#approvePaymentForm')[0].reset(); // Reset the form before populating new data
                var url = '{{ route('payments.edit', ['id' => ':id']) }}'.replace(':id', $id);
                // Make AJAX request to fetch client data
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        document.getElementById('payment_deal_id').textContent = response.payment.deal_id;
                        document.getElementById('payment_value').textContent = response.payment.payment_value;
                        document.getElementById('payment_remarks').textContent = response.payment.remarks;
                        document.getElementById('payment_id').textContent = response.payment.id;
                        document.getElementById('payment_date').textContent =timestampToDate(response.payment.payment_date);

                        // Example imageUrl variable (replace with your dynamic URL)
                        var imageUrl = '{{ asset("storage/payments/") }}' +"/"+ response.payment.receipt_path;

                        // Select the image element by its ID or another selector
                        var imgElement = document.getElementById('receipt_image_path'); // Replace 'yourImgId' with your actual image element ID

                        // Set the src attribute of the image element
                        if (imgElement) {
                            imgElement.src = imageUrl;
                        } else {
                            console.error('Image element not found.');
                        }
                                            // Show the edit modal
                        $('#approvePayment').modal('show');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Error fetching client data:', errorThrown);
                    }
                });
            }

            $("#approvePaymentForm").submit(function(event){
                event.preventDefault();
                var element = $("#approvePaymentForm");
                $("button[type=submit]").prop('disabled',true);
                var dealId=$('#payment_id').val();

                $.ajax({
                    url:'{{ route('payments.verify', ['id' => ':id']) }}'.replace(':id', dealId),
                    type:'put',
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response){
                        $("button[type=submit]").prop('disabled',false);

                        if(response["status"] == true) {
                            $(".error").removeClass('invalid-feedback');
                            $('input[type="text"], select').removeClass('is-invalid');
                            window.location.href="{{ route('deals.index') }}";

                        }else{

                            var errors = response['errors'];
                            console.log(errors);
                            handleEditFieldError('name', errors);
                            handleEditFieldError('client_id', errors);
                            handleEditFieldError('source_type_id', errors);
                            handleEditFieldError('work_type', errors);
                            handleEditFieldError('deal_value', errors);
                            handleEditFieldError('deal_date', errors);
                            handleEditFieldError('due_date', errors);
                            handleEditFieldError('remarks', errors);


                        }
                    },
                    error: function(jqXHR, exception){
                        console.log("केहि गलति भयो!");
                    }
                });
            });




        </script>
    @endsection
</x-app-layout>