@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="contact-breadcrumb">
          <div class="breadcrumb-main add-contact justify-content-sm-between ">
            <div class=" d-flex flex-wrap justify-content-center breadcrumb-main__wrapper">
              <div class="d-flex align-items-center add-contact__title justify-content-center me-sm-25">
                <h4 class="text-capitalize fw-500 breadcrumb-title">{{ trans('supplier.Suppliers\' Payments Reminders') }}
                </h4>
                <span class="sub-title ms-sm-25 ps-sm-25"></span>
              </div>
              <div class="action-btn mt-sm-0 mt-15">

              </div>
            </div>
            <div class="breadcrumb-main__wrapper">

              <form action="{{route('suppliers.payments.reminder')}}" method="GET"
                    class="d-flex align-items-center add-contact__form my-sm-0 my-2">
                <img src="{{ asset('assets/img/svg/search.svg') }}" alt="search" class="svg">
                <input name="search" class="form-control me-sm-2 border-0 box-shadow-none" type="search"
                       placeholder="Search by Name" aria-label="Search">
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 mb-30">
        <div class="card">
          <div class="card-header color-dark fw-500">
            {{trans('supplier.Scheduled Payments')}}
          </div>
          <div class="card-body">
            <div class="userDatatable global-shadow border-light-0 w-100">
              <div class="table-responsive">
                <table class="table mb-0 table-borderless">
                  <thead>
                  <tr class="userDatatable-header">
                    <th>
                      <span class="userDatatable-title">{{ trans('supplier.name') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('supplier.code') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{trans('supplier.balance') . " " . trans('statement.rmb')}}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{trans('supplier.payment_date')}}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{trans('supplier.paymenr_status')}}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title float-end">Actions</span>
                    </th>
                  </tr>
                  </thead>
                  <tbody>
                  @if (count($paymentReminders) == 0)
                    <tr>
                      <td colspan="7">
                        <p class="text-center">No Supplier Found !</p>
                      </td>
                    </tr>
                  @else
                    @foreach ($paymentReminders as $paymentReminder)
                      <tr>
                        <td>
                          <div class="d-flex">
                            <div class="userDatatable__imgWrapper d-flex align-items-center">

                            </div>
                            <div class="userDatatable-inline-title">
                              <a href="#" class="text-dark fw-500">
                                <h6>{{ $paymentReminder->supplier?->name??'--' }}</h6>
                              </a>
                            </div>
                          </div>
                        </td>
                        <td>
                          <div class="userDatatable-content">
                            {{ $paymentReminder->supplier?->code??'--' }}
                          </div>
                        </td>
                        <td>
                          <div class="userDatatable-content">
                            @if(($paymentReminder->supplier?->activeAccount?->total_rmb ?? 0) > 0)
                              <span class="text-danger fw-bold">
                          {{
                          __(
                          'supplier.We owe the supplier :amount RMB'
                          ,['amount'=>number_format(($paymentReminder->supplier?->activeAccount?->total_rmb ?? 0)
                          *-1, 2, '.', ',')]) }}
                        </span>
                            @elseif(($paymentReminder->supplier?->activeAccount?->total_rmb ??0) == 0)

                              0.00
                            @else
                              <span class="text-success fw-bold">
                          {{ str_replace('-', '', __('supplier.The Supplier owe us :amount RMB',[
                              'amount'=>number_format(abs($paymentReminder->supplier?->activeAccount?->total_rmb ?? 0),
                          2, '.', ',')])) }}
                        </span>
                            @endif
                          </div>
                        </td>
                        <td>
                          <div class="userDatatable-content">
                            {{ $paymentReminder->payment_date??'--' }}
                          </div>
                        </td>
                        <td>

                          <div class="text-center radius-md
                      @if($paymentReminder->status == 'pending')
                      badge-warning
                      @elseif($paymentReminder->status == 'paid')
                      badge-success
                      @elseif($paymentReminder->status == 'overdue')
                      badge-danger
                      @elseif($paymentReminder->status == 'extended')
                      badge-dark
                      @endif
                       d-inline-block w-auto px-2">
                            {{
                            __('supplier.'.$paymentReminder->status) }}
                          </div>

                        </td>

                        <td>
                          <ul class="orderDatatable_actions mb-0 d-flex flex-wrap">
                            <a href="{{ route('expenses.create') }}?supplier={{ $paymentReminder->supplier_id }}&o_type={{$paymentType}}"
                               class="btn btn-success w-auto mx-2 pay" target="_blank">{{
                          __('supplier.pay_now') }}</a>

                            <a href="#" class="btn btn-dark w-auto mx-2 change-date" data-bs-toggle="modal"
                               data-bs-target="#changePaymentDateModal" data-id="{{ $paymentReminder->id }}">{{
                          __('supplier.Change Payment Date') }}</a>
                            <li>
                              <a href="#" class="remove" onclick="deleteItem('delete-{{ $paymentReminder->id }}')">
                                <i class="uil uil-trash-alt"></i>
                              </a>

                              <form style="display:none;" id="delete-{{ $paymentReminder->id }}"
                                    action="{{ route('suppliers.payments.reminder.destroy', [$paymentReminder]) }}" method="POST">
                                @csrf
                                @method('delete')
                              </form>
                            </li>
                          </ul>
                        </td>
                      </tr>
                    @endforeach
                  @endif
                  </tbody>
                </table>
              </div>
            </div>


          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="changePaymentDateModal" tabindex="-1" aria-labelledby="changePaymentDateModalLabel"
       aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="changePaymentDateModalLabel">{{ __('supplier.Change Payment Date') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Form inside the modal -->
          <form action="{{ route('suppliers.payments.reminder.change.date') }}" method="POST">
            @csrf
            <!-- Hidden input for payment_id -->
            <input type="hidden" name="id" value="" id="changePaymentDateModalId">

            <!-- Date input for the new payment date -->
            <div class="mb-3">
              <label for="new_payment_date" class="form-label">{{ __('supplier.New Payment Date') }}</label>
              <input type="date" class="form-control" id="new_payment_date" name="new_payment_date" required>
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary">{{ __('supplier.Save Changes') }}</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="schedulePaymentDateModal" tabindex="-1" aria-labelledby="schedulePaymentDateModalLabel"
       aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="schedulePaymentDateModalLabel">{{ __('supplier.Create Payment Date') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Form inside the modal -->
          <form action="{{ route('suppliers.payments.reminder.create.date') }}" method="POST">
            @csrf
            <!-- Hidden input for payment_id -->
            <input type="hidden" name="supplier_id" value="" id="schedulePaymentDateModalSupplierId">

            <!-- Date input for the new payment date -->
            <div class="mb-3">
              <label for="new_payment_date" class="form-label">{{ __('supplier.Payment Date') }}</label>
              <input type="date" class="form-control" id="new_payment_date" name="new_payment_date" required>
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary">{{ __('supplier.Save Changes') }}</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="PaymentModal" tabindex="-1" aria-labelledby="PaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="PaymentModalLabel">{{ __('expense.expenses') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Form inside the modal -->
          @include('supplier_payment_reminder.components.paymentForm')
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    function deleteItem(form){
      Swal.fire({
        title: "{{ app()->getLocale() == 'ar' ? 'هل انت متأكد من حذف هذا البند؟' : 'Do you want to delete this item?' }}",
        showCancelButton: true,
        confirmButtonText: "Delete",
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          event.preventDefault();
          document.getElementById(form).submit();
        }
      });
    }
  </script>
  <script>
    // JavaScript to set payment_id in the modal's hidden input
    $('.change-date').click(function (event) {
      var paymentId = $(this).data('id');
      $('#changePaymentDateModalId').val(paymentId);

    });
  </script>
  <script>
    // JavaScript to set payment_id in the modal's hidden input
    $('.schedule-date').click(function (event) {
      var supplier = $(this).data('supplier-id');
      $('#schedulePaymentDateModalSupplierId').val(supplier);

    });
  </script>
  <script>
    // JavaScript to set payment_id in the modal's hidden input
    $('.pay').click(function (event) {
      var supplier_id = $(this).data('supplier-id');
      var amount = $(this).data('payment-amount');
      $('#financialModalSupplierId').val(supplier_id);
      $('#amount').val(amount);

    });
  </script>


  <script>
    $(document).ready(function() {
      $('#client_id').select2();
      $('#supplier_id').select2();
      // Function to update otherCurrency based on currency selection
      function updateOtherCurrency() {
        // Get selected currency value
        var currency = $('#currency').val();

        // Get amount value
        var amount = parseFloat($('#amount').val());

        // Get rate value
        var rate = parseFloat($('#rate').val());

        // Calculate otherCurrency based on currency selection
        var otherCurrency = (currency === 'usd') ? amount * rate : (currency === 'rmb') ? amount / rate : '';

        // Update the otherCurrency input field
        $('#otherCurrency').val(otherCurrency.toFixed(2));
      }

      // Attach the updateOtherCurrency function to currency, amount, and rate change events
      $('#currency, #amount, #rate').on('change input', updateOtherCurrency);

      // Trigger the updateOtherCurrency function initially
      updateOtherCurrency();
    });


    // Change event handler for the select dropdown
    $('#type').change(function () {
      // Get the selected value
      var selectedValue = $(this).val();

      // Check the selected value and enable/disable fields accordingly
      if (selectedValue === 'Rent' || selectedValue === 'Salary' || selectedValue === 'Commissions' || selectedValue === 'Other') {
        $('#client_id').attr('disabled', true).parent().hide(0);
        $('#supplier_id').attr('disabled', true).parent().hide(0)
        ;
      } else if(selectedValue === 'Payment from Client' || selectedValue === 'Paid for Client') {
        $('#client_id').attr('disabled', false).parent().show(0);
        $('#supplier_id').attr('disabled', true).parent().hide(0);
      }else if(selectedValue === 'Payment to Supplier') {
        $('#client_id').attr('disabled', true).parent().hide(0);
        $('#supplier_id').attr('disabled', false).parent().show(0);
      }

    });
    $('#currency').change(function () {

      // Get the selected value
      var selectedValue = $(this).val();

      // Check the selected value and enable/disable fields accordingly
      if (selectedValue === 'usd') {
        $('#rate').attr('disabled', false).parent().show(0);
        $('#otherCurrency').attr('disabled', false).parent().show(0);
      } else {
        $('#rate').attr('disabled', true).parent().hide(0);
        $('#otherCurrency').attr('disabled', true).parent().hide(0);
      }
    });
  </script>
@endsection
